<?php

@include 'config.php';

session_start();

$seller_id = $_COOKIE['seller_id'];

if (!isset($seller_id)) {
   header('location:login.php');
}

if (isset($_POST['add_product'])) {
   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];
   $details = $_POST['details'];
   $pre_order = isset($_POST['pre_order']) ? 1 : 0;
   $pre_order_date = !empty($_POST['pre_order_date']) ? $_POST['pre_order_date'] : null; // Handle pre_order_date
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $insert_product = $conn->prepare("INSERT INTO `products`(name, category, details, price, image, seller_id, pre_order, pre_order_date) VALUES(?,?,?,?,?,?,?,?)");
   $insert_product->execute([$name, $category, $details, $price, $image, $seller_id, $pre_order, $pre_order_date]);

   if ($insert_product) {
      move_uploaded_file($image_tmp_name, $image_folder);
      $message[] = 'Product added successfully!';
   } else {
      $message[] = 'Product could not be added!';
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   header('location:seller_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>seller products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="add-products">

   <h1 class="title">add new product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" class="box" required placeholder="Enter product name">
      <input type="number" name="price" class="box" required placeholder="Enter product price">
      <select name="category" class="box" required>
         <option value="" selected disabled>Select category</option>
         <option value="vegetables">Vegetables</option>
         <option value="fruits">Fruits</option>
         <option value="meat">Fertilizers</option>
         <option value="fish">Rentals</option>
      </select> 
      <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
      <input type="file" name="image" class="box" required>
      <span>available for pre-order</span>
      <input type="checkbox" name="pre_order">
      <br>
      <label for="pre_order_date">Pre-order Date:</label>
      <input type="date" name="pre_order_date" id="pre_order_date" class="box">
      <br>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">
   <h1 class="title">your products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
      $select_products->execute([$seller_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">$<?= $fetch_products['price']; ?>/-</div>
      <div class="pre_order">
         <?= $fetch_products['pre_order'] ? '<span>Available for pre-order</span><br>Date available: ' . $fetch_products['pre_order_date'] : ''; ?>
      </div>
      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>
      <a href="seller_products.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="seller_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
