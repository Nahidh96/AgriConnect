// seller_products.php
<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:login.php');
}

if(isset($_POST['add_product'])){
   $name = $_POST['name'];
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $insert_product = $conn->prepare("INSERT INTO `products`(name, price, image, seller_id) VALUES(?,?,?,?)");
   $insert_product->execute([$name, $price, $image, $seller_id]);

   if($insert_product){
      move_uploaded_file($image_tmp_name, $image_folder);
      $message[] = 'Product added successfully!';
   }else{
      $message[] = 'Product could not be added!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Products</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add New Product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" class="box" required placeholder="Enter product name">
      <input type="number" name="price" class="box" required placeholder="Enter product price">
      <input type="file" name="image" class="box" required>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Your Products</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
         $select_products->execute([$seller_id]);
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="price">$<?= $fetch_products['price']; ?>/-</div>
         <a href="seller_update_product.php?update=<?= $fetch_products['id']; ?>" class="btn">Update</a>
         <a href="seller_delete_product.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn">Delete</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
