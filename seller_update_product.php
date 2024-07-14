// seller_update_product.php
<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:login.php');
}

if (isset($_GET['update'])) {
   $update_id = $_GET['update'];
   $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
   $select_product->execute([$update_id, $seller_id]);
   if ($select_product->rowCount() > 0) {
      $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
   } else {
      header('location:seller_products.php');
   }
}

if (isset($_POST['update_product'])) {
   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];
   $details = $_POST['details'];
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   if (!empty($image)) {
      $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ?, image = ? WHERE id = ? AND seller_id = ?");
      $update_product->execute([$name, $category, $details, $price, $image, $update_id, $seller_id]);
      move_uploaded_file($image_tmp_name, $image_folder);
   } else {
      $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ? AND seller_id = ?");
      $update_product->execute([$name, $category, $details, $price, $update_id, $seller_id]);
   }

   header('location:seller_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="update-product">

   <h1 class="title">Update Product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" class="box" required placeholder="Enter product name" value="<?= $fetch_product['name']; ?>">
      <input type="number" name="price" class="box" required placeholder="Enter product price" value="<?= $fetch_product['price']; ?>">
      <select name="category" class="box" required>
         <option value="<?= $fetch_product['category']; ?>" selected><?= $fetch_product['category']; ?></option>
         <option value="vegetables">Vegetables</option>
         <option value="fruits">Fruits</option>
         <option value="meat">Meat</option>
         <option value="fish">Fish</option>
      </select>
      <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"><?= $fetch_product['details']; ?></textarea>
      <input type="file" name="image" class="box">
      <input type="submit" value="Update Product" name="update_product" class="btn">
   </form>

</section>

<script src="js/script.js"></script>

</body>
</html>
