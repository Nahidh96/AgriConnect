<?php

@include '../config.php';

session_start();

$user_id = $_COOKIE['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['update_profile'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['old_image'];

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'රූපයේ ප්‍රමාණය විශාලයි!';
      }else{
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $user_id]);
         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'රූපය සාර්ථකව යාවත්කාලීන කරන ලදි!';
         };
      };
   };

   $old_pass = $_POST['old_pass'];
   $update_pass = md5($_POST['update_pass']);
   $update_pass = filter_var($update_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $new_pass = md5($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $confirm_pass = md5($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   if(!empty($update_pass) AND !empty($new_pass) AND !empty($confirm_pass)){
      if($update_pass != $old_pass){
         $message[] = 'පැරණි මුරපදය ගැලපෙන්නේ නැත!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'අලුත් මුරපදය තහවුරු නොවේ!';
      }else{
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $user_id]);
         $message[] = 'මුරපදය සාර්ථකව යාවත්කාලීන කරන ලදි!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="si">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>පරිශීලක පැතිකඩ යාවත්කාලීන කරන්න</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/components.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="update-profile">

   <h1 class="title">පැතිකඩ යාවත්කාලීන කරන්න</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <img src="../uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <div class="flex">
         <div class="inputBox">
            <span>පරිශීලක නාමය :</span>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="පරිශීලක නාමය යාවත්කාලීන කරන්න" required class="box">
            <span>ඊ-මේල් :</span>
            <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="ඊ-මේල් යාවත්කාලීන කරන්න" required class="box">
            <span>නැවත පින්තූරය :</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
            <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
            <span>පැරණි මුරපදය :</span>
            <input type="password" name="update_pass" placeholder="පැරණි මුරපදය ඇතුල් කරන්න" class="box">
            <span>අලුත් මුරපදය :</span>
            <input type="password" name="new_pass" placeholder="අලුත් මුරපදය ඇතුල් කරන්න" class="box">
            <span>මුරපදය තහවුරු කරන්න :</span>
            <input type="password" name="confirm_pass" placeholder="අලුත් මුරපදය තහවුරු කරන්න" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="පැතිකඩ යාවත්කාලීන කරන්න" name="update_profile">
         <a href="index.php" class="option-btn">පසුබට යන්න</a>
      </div>
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
