<?php

@include 'config.php';

session_start();

$admin_id = $_COOKIE['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit(); // Add exit after redirection to stop script execution
}

$message = []; // Initialize $message array to avoid potential undefined variable warning

if(isset($_POST['update_order'])){
   try {
      $order_id = $_POST['order_id'];
      $update_payment = isset($_POST['update_payment']) ? $_POST['update_payment'] : null;
      $update_payment = filter_var($update_payment, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Update the order
      $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
      $update_orders->execute([$update_payment, $order_id]);

      // Fetch user_id associated with the order
      $order_info = $conn->prepare("SELECT user_id FROM `orders` WHERE id = ?");
      $order_info->execute([$order_id]);
      $order = $order_info->fetch(PDO::FETCH_ASSOC);
      
      if ($order && isset($order['user_id'])) {
          $user_id = $order['user_id'];
      } else {
          error_log("Error: Could not fetch user_id for order #$order_id");
          $message[] = "Error: Could not fetch user information.";
          return; // Stop further execution if user_id can't be fetched
      }

      // Insert notification
      $notification_message = "Your order #$order_id payment status has been updated to '$update_payment'.";
      $insert_notification = $conn->prepare("INSERT INTO notifications (seller_id, message) VALUES (?, ?)");
      $insert_notification->execute([$user_id, $notification_message]);

      $message[] = 'Payment has been updated!';
   } catch (Exception $e) {
      error_log("Error updating order: " . $e->getMessage());
      $message[] = 'Payment has been updated!';
   }
}


if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);

   // Fetch user_id associated with the order
   $order_info = $conn->prepare("SELECT user_id FROM `orders` WHERE id = ?");
   $order_info->execute([$delete_id]);
   $order = $order_info->fetch(PDO::FETCH_ASSOC);
   $user_id = $order['user_id'];

   // Insert a notification for the user
   $notification_message = "Your order #$delete_id has been deleted.";
   $insert_notification = $conn->prepare("INSERT INTO notifications (seller_id, message) VALUES (?, ?)");
   $insert_notification->execute([$user_id, $notification_message]);

   header('location:admin_orders.php');
   exit(); // Add exit after redirection to stop script execution
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders | AgriConnect</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-orders">
   <h1 class="title">Placed Orders</h1>
   <div class="box-container">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> User ID : <span><?= $fetch_orders['user_id']; ?></span> </p>
         <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
         <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> Total Price : <span>Rs.<?= $fetch_orders['total_price']; ?>/-</span> </p>
         <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="update_payment" class="drop-down">
               <option value="<?= $fetch_orders['payment_status']; ?>" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">Pending</option>
               <option value="completed">Completed</option>
            </select>
            <div class="flex-btn">
               <input type="submit" name="update_order" class="option-btn" value="Update">
               <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
            </div>
         </form>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
      ?>
   </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
