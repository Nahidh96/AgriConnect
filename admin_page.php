<?php

@include 'config.php';

session_start();

$admin_id = $_COOKIE['admin_id'];

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
   <title>Admin Page | AgriConnect</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <!-- Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <style>
      
   </style>

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

   <div class="box">
         <h3>Sales Analysis</h3>
         <canvas id="salesChart"></canvas>
      </div>

      <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['owner_share'];
         };
      ?>
      <h3>Rs. <?= $total_pendings; ?>/-</h3>
      <p>total pendings</p>
      <a href="admin_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
      <?php
         $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completed->execute(['completed']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            $total_completed += $fetch_completed['owner_share'];
         };
      ?>
      
      <h3>Rs. <?= $total_completed; ?>/-</h3>
      <p>completed orders</p>
      <a href="admin_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $number_of_orders; ?></h3>
      <p>orders placed</p>
      <a href="admin_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
      <h3>Support Tickets</h3>
      <p>Manage support tickets</p>
      <a href="admin_support_tickets.php" class="btn">Manage Tickets</a>
      </div>


      <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $number_of_products = $select_products->rowCount();
      ?>
      <h3><?= $number_of_products; ?></h3>
      <p>products added</p>
      <a href="admin_products.php" class="btn">see products</a>
      </div>

      <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_users->execute(['user']);
         $number_of_users = $select_users->rowCount();
      ?>
      <h3><?= $number_of_users; ?></h3>
      <p>total users</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_admins->execute(['admin']);
         $number_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $number_of_admins; ?></h3>
      <p>total admins</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `users`");
         $select_accounts->execute();
         $number_of_accounts = $select_accounts->rowCount();
      ?>
      <h3><?= $number_of_accounts; ?></h3>
      <p>total accounts</p>
      <a href="admin_users.php" class="btn">see accounts</a>
      </div>

      <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `message`");
         $select_messages->execute();
         $number_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $number_of_messages; ?></h3>
      <p>total messages</p>
      <a href="admin_contacts.php" class="btn">see messages</a>
      </div>

      <div class="box">
      <?php
         $select_ads = $conn->prepare("SELECT * FROM `ads`");
         $select_ads->execute();
         $number_of_ads = $select_ads->rowCount();
      ?>
      <h3><?= $number_of_ads; ?></h3>
      <p>total ads</p>
      <a href="admin_ads.php" class="btn">see ads</a>
      </div>

      <!-- New Features -->
      <div class="box">
         <h3>Analytics</h3>
         <p>View detailed analytics</p>
         <a href="admin_analytics.php" class="btn">view analytics</a>
      </div>

      <div class="box">
         <h3>User M.</h3>
         <p>Manage user activities</p>
         <a href="admin_user_management.php" class="btn">manage users</a>
      </div>

      <div class="box">
         <h3>User Engagement</h3>
         <canvas id="engagementChart"></canvas>
      </div>

      <div class="box">
         <h3>Order Management Enhancements</h3>
         <p>Process orders, manage returns, and more</p>
         <a href="admin_order_enhancements.php" class="btn">manage orders</a>
      </div>

      <div class="box">
         <h3>Security and Maintenance</h3>
         <p>Manage admin logs, backups, and security alerts</p>
         <a href="admin_security.php" class="btn">view security</a>
      </div>

      <div class="box">
         <h3>Customization Options</h3>
         <p>Customize dashboard appearance and create custom reports</p>
         <a href="admin_customization.php" class="btn">customize</a>
      </div>

   </div>

</section>

<script>
   // Sample data for charts
   const salesData = {
      labels: ['January', 'February', 'March', 'April', 'May'],
      datasets: [{
         label: 'Sales',
         data: [1200, 1900, 3000, 5000, 2300],
         backgroundColor: 'rgba(75, 192, 75, 0.2)',
         borderColor: 'rgba(75, 192, 75, 1)',
         borderWidth: 1
      }]
   };

   const engagementData = {
      labels: ['Logins', 'Pages Visited', 'Actions Taken'],
      datasets: [{
         label: 'Engagement',
         data: [500, 1200, 900],
         backgroundColor: 'rgba(34, 139, 34, 0.2)',
         borderColor: 'rgba(34, 139, 34, 1)',
         borderWidth: 1
      }]
   };

   const productData = {
      labels: ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
      datasets: [{
         label: 'Sales',
         data: [50, 70, 30, 90, 110],
         backgroundColor: 'rgba(0, 128, 0, 0.2)',
         borderColor: 'rgba(0, 128, 0, 1)',
         borderWidth: 1
      }]
   };

   // Sales Chart
   const salesCtx = document.getElementById('salesChart').getContext('2d');
   new Chart(salesCtx, {
      type: 'bar',
      data: salesData,
      options: {
         responsive: true,
         plugins: {
            legend: {
               position: 'top',
            },
            title: {
               display: true,
               text: 'Sales Analysis'
            }
         }
      },
   });

   // User Engagement Chart
   const engagementCtx = document.getElementById('engagementChart').getContext('2d');
   new Chart(engagementCtx, {
      type: 'pie',
      data: engagementData,
      options: {
         responsive: true,
         plugins: {
            legend: {
               position: 'top',
            },
            title: {
               display: true,
               text: 'User Engagement'
            }
         }
      },
   });

   // Product Performance Chart
   const productCtx = document.getElementById('productChart').getContext('2d');
   new Chart(productCtx, {
      type: 'bar',
      data: productData,
      options: {
         responsive: true,
         plugins: {
            legend: {
               position: 'top',
            },
            title: {
               display: true,
               text: 'Product Performance'
            }
         }
      },
   });
</script>


<script src="js/script.js"></script>

</body>
</html>
