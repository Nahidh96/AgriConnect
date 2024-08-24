<?php
@include '../config.php';
session_start();

$seller_id = $_COOKIE['seller_id'];
if(!isset($seller_id)){
   header('location:seller_login.php');
}

if(isset($_POST['update_inventory'])){
   $product_id = $_POST['product_id'];
   $quantity = $_POST['quantity'];
   $update_inventory = $conn->prepare("UPDATE `products` SET `quantity` = ? WHERE `id` = ?");
   $update_inventory->execute([$quantity, $product_id]);
}

$products = $conn->prepare("SELECT * FROM `products` WHERE `seller_id` = ?");
$products->execute([$seller_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Inventory</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="inventory">
   <h1 class="title">Manage Inventory</h1>
   <form action="" method="post">
      <?php while($product = $products->fetch(PDO::FETCH_ASSOC)){ ?>
         <div class="product">
            <h3><?= $product['name']; ?></h3>
            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
            <input type="number" name="quantity" value="<?= $product['quantity']; ?>" min="0">
            <input type="submit" name="update_inventory" value="Update Quantity" class="btn">
         </div>
      <?php } ?>
   </form>
</section>
<script src="js/script.js"></script>
</body>
</html>
