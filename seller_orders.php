<?php

@include 'config.php';

session_start();

// Check if user_id is set in cookies
$user_id = $_COOKIE['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch seller_id from users table
$select = $conn->prepare("SELECT id, user_type FROM `users` WHERE id = ?");
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['user_type'] !== 'seller') {
    header('Location: login.php');
    exit();
}

$seller_id = $user['id']; // Define seller_id based on fetched user_id

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'seller_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Your Orders</h1>

   <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
         $select_orders->execute([$seller_id]);

         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="box">
         <p> user id : <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span> </p>
         <p> placed on : <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span> </p>
         <p> name : <span><?= htmlspecialchars($fetch_orders['name']); ?></span> </p>
         <p> email : <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
         <p> number : <span><?= htmlspecialchars($fetch_orders['number']); ?></span> </p>
         <p> address : <span><?= htmlspecialchars($fetch_orders['address']); ?></span> </p>
         <p> total products : <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span> </p>
         <p> total price : <span>$<?= htmlspecialchars(number_format($fetch_orders['total_price'], 2)); ?>/-</span> </p>
         <p> payment method : <span><?= htmlspecialchars($fetch_orders['method']); ?></span> </p>
         <p> payment status : <span><?= htmlspecialchars($fetch_orders['payment_status']); ?></span> </p>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
      ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
