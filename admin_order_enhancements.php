<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Order Enhancements</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head> 
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Order Enhancements</h1>

   <div class="box-container">

      <div class="box">
         <h3>Order Processing</h3>
         <p>Tools to update order statuses, print invoices, and generate shipping labels.</p>
         <a href="admin_order_processing.php" class="btn">process orders</a>
      </div>

      <div class="box">
         <h3>Return/Refund Management</h3>
         <p>Manage product returns and process refunds.</p>
         <a href="admin_return_management.php" class="btn">manage returns</a>
      </div>

      <div class="box">
         <h3>Order Notifications</h3>
         <p>Automated email or SMS notifications for order updates.</p>
         <a href="admin_order_notifications.php" class="btn">view notifications</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
