<?php
@include 'config.php';
session_start();

$seller_id = $_SESSION['seller_id'];
if(!isset($seller_id)){
   header('location:seller_login.php');
}

if(isset($_POST['add_promotion'])){
   $name = $_POST['name'];
   $discount = $_POST['discount'];
   $add_promotion = $conn->prepare("INSERT INTO `promotions` (`seller_id`, `name`, `discount`) VALUES (?, ?, ?)");
   $add_promotion->execute([$seller_id, $name, $discount]);
}

$promotions = $conn->prepare("SELECT * FROM `promotions` WHERE `seller_id` = ?");
$promotions->execute([$seller_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Promotions</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="promotions">
   <h1 class="title">Manage Promotions</h1>
   <form action="" method="post">
      <input type="text" name="name" placeholder="Promotion Name" required>
      <input type="number" name="discount" placeholder="Discount %" min="0" max="100" required>
      <input type="submit" name="add_promotion" value="Add Promotion" class="btn">
   </form>
   <div class="box-container">
      <?php while($promotion = $promotions->fetch(PDO::FETCH_ASSOC)){ ?>
         <div class="box">
            <h3><?= $promotion['name']; ?></h3>
            <p>Discount: <?= $promotion['discount']; ?>%</p>
         </div>
      <?php } ?>
   </div>
</section>
<script src="js/script.js"></script>
</body>
</html>
