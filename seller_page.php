<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:seller_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Dashboard</title>
   <link rel="stylesheet" href="css/admin_style.css">
   <!-- Include a library for charts (optional) -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Seller Dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $total_earnings = 0;
         $select_earnings = $conn->prepare("SELECT total_price FROM `orders` WHERE seller_id = ? AND payment_status = ?");
         $select_earnings->execute([$seller_id, 'completed']);
         while($fetch_earnings = $select_earnings->fetch(PDO::FETCH_ASSOC)){
            $total_earnings += $fetch_earnings['total_price'];
         };
      ?>
      <h3>Rs. <?= $total_earnings; ?>/-</h3>
      <p>Total Earnings</p>
      </div>

      <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
         $select_orders->execute([$seller_id]);
         $number_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $number_of_orders; ?></h3>
      <p>Orders Placed</p>
      <a href="seller_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
         $select_products->execute([$seller_id]);
         $number_of_products = $select_products->rowCount();
      ?>
      <h3><?= $number_of_products; ?></h3>
      <p>Products Added</p>
      <a href="seller_products.php" class="btn">See Products</a>
      </div>

      <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `message` WHERE seller_id = ?");
         $select_messages->execute([$seller_id]);
         $number_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $number_of_messages; ?></h3>
      <p>Messages Received</p>
      <a href="seller_contacts.php" class="btn">See Messages</a>
      </div>

      <div class="box">
      <h3>Recent Orders</h3>
      <?php
         $recent_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? ORDER BY placed_on DESC LIMIT 5");
         $recent_orders->execute([$seller_id]);
         while($fetch_recent_orders = $recent_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <p> Order ID: <?= $fetch_recent_orders['id']; ?> | Placed on: <?= $fetch_recent_orders['placed_on']; ?> | Total: $<?= $fetch_recent_orders['total_price']; ?> </p>
      <?php
         }
      ?>
      <a href="seller_orders.php" class="btn">See All Orders</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
