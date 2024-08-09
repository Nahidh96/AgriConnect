<?php

@include 'config.php';

session_start();

$admin_id = $_COOKIE['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['add_ad'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $link = $_POST['link'];
   $link = filter_var($link, FILTER_SANITIZE_STRING);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   
   $times_per_day = $_POST['times_per_day'];
   $times_per_day = filter_var($times_per_day, FILTER_SANITIZE_NUMBER_INT);
   $days_duration = $_POST['days_duration'];
   $days_duration = filter_var($days_duration, FILTER_SANITIZE_NUMBER_INT);
   $start_date = date('Y-m-d');

   $select_ads = $conn->prepare("SELECT * FROM `ads` WHERE title = ?");
   $select_ads->execute([$title]);

   if($select_ads->rowCount() > 0){
      $message[] = 'ad title already exists!';
   }else{

      $insert_ad = $conn->prepare("INSERT INTO `ads`(title, link, image, times_per_day, days_duration, start_date) VALUES(?,?,?,?,?,?)");
      $insert_ad->execute([$title, $link, $image, $times_per_day, $days_duration, $start_date]);

      if($insert_ad){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new ad added!';
         }
      }
   }
}

if(isset($_POST['update_ad'])){

   $ad_id = $_POST['ad_id'];
   $ad_id = filter_var($ad_id, FILTER_SANITIZE_NUMBER_INT);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $link = $_POST['link'];
   $link = filter_var($link, FILTER_SANITIZE_STRING);
   $times_per_day = $_POST['times_per_day'];
   $times_per_day = filter_var($times_per_day, FILTER_SANITIZE_NUMBER_INT);
   $days_duration = $_POST['days_duration'];
   $days_duration = filter_var($days_duration, FILTER_SANITIZE_NUMBER_INT);

   $update_ad = $conn->prepare("UPDATE `ads` SET title = ?, link = ?, times_per_day = ?, days_duration = ? WHERE id = ?");
   $update_ad->execute([$title, $link, $times_per_day, $days_duration, $ad_id]);

   $message[] = 'ad updated successfully!';

   if(!empty($_FILES['image']['name'])){
      $old_image = $_POST['old_image'];
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/'.$image;

      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `ads` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $ad_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('uploaded_img/'.$old_image);
         $message[] = 'image updated successfully!';
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `ads` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_ads = $conn->prepare("DELETE FROM `ads` WHERE id = ?");
   $delete_ads->execute([$delete_id]);
   header('location:admin_ads.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ads</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add New Ad</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <input type="text" name="title" class="box" required placeholder="Enter ad title">
            <input type="text" name="link" class="box" required placeholder="Enter ad link">
         </div>
         <div class="inputBox">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
            <input type="number" name="times_per_day" class="box" required placeholder="Enter times per day">
            <input type="number" name="days_duration" class="box" required placeholder="Enter duration in days">
         </div>
      </div>
      <input type="submit" class="btn" value="Add Ad" name="add_ad">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Ads Added</h1>

   <div class="box-container">

   <?php
      $show_ads = $conn->prepare("SELECT * FROM `ads`");
      $show_ads->execute();
      if($show_ads->rowCount() > 0){
         while($fetch_ads = $show_ads->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="uploaded_img/<?= $fetch_ads['image']; ?>" alt="">
      <div class="name"><?= $fetch_ads['title']; ?></div>
      <div class="cat"><?= $fetch_ads['link']; ?></div>
      <div class="details">
         <p>Times per day: <?= $fetch_ads['times_per_day']; ?></p>
         <p>Duration in days: <?= $fetch_ads['days_duration']; ?></p>
         <p>Start date: <?= $fetch_ads['start_date']; ?></p>
      </div>
      <div class="flex-btn">
         <a href="<?= $fetch_ads['link']; ?>" target="_blank" class="option-btn">visit</a>
         <a href="admin_ads.php?delete=<?= $fetch_ads['id']; ?>" class="delete-btn" onclick="return confirm('delete this ad?');">delete</a>
         <a href="admin_ads.php?update=<?= $fetch_ads['id']; ?>" class="option-btn">update</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No ads added yet!</p>';
   }
   ?>

   </div>

</section>

<?php
if(isset($_GET['update'])){
   $update_id = $_GET['update'];
   $select_ads = $conn->prepare("SELECT * FROM `ads` WHERE id = ?");
   $select_ads->execute([$update_id]);
   if($select_ads->rowCount() > 0){
      $fetch_ads = $select_ads->fetch(PDO::FETCH_ASSOC);
?>

<section class="edit-product-form">
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="ad_id" value="<?= $fetch_ads['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_ads['image']; ?>">
      <img src="uploaded_img/<?= $fetch_ads['image']; ?>" alt="">
      <input type="text" name="title" class="box" required placeholder="Enter ad title" value="<?= $fetch_ads['title']; ?>">
      <input type="text" name="link" class="box" required placeholder="Enter ad link" value="<?= $fetch_ads['link']; ?>">
      <input type="number" name="times_per_day" class="box" required placeholder="Enter times per day" value="<?= $fetch_ads['times_per_day']; ?>">
      <input type="number" name="days_duration" class="box" required placeholder="Enter duration in days" value="<?= $fetch_ads['days_duration']; ?>">
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Update Ad" name="update_ad" class="btn">
      <input type="button" value="Cancel" onclick="location.href='admin_ads.php';" class="option-btn">
   </form>
</section>

<?php
   }
}
?>

<script src="js/script.js"></script>

</body>
</html>
