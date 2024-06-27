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
         $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE receiver_id = ?");
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
      <p> Order ID: <?= $fetch_recent_orders['id']; ?> | Placed on: <?= $fetch_recent_orders['placed_on']; ?> | Total: Rs. <?= $fetch_recent_orders['total_price']; ?>/- </p>
      <?php
         }
      ?>
      <a href="seller_orders.php" class="btn">See All Orders</a>
      </div>

      <!-- Promotions and Discounts -->
      <div class="box">
      <h3>Promotions Discounts</h3>
      <?php
         $promotions_query = $conn->prepare("SELECT id, name, discount FROM `promotions` WHERE seller_id = ?");
         $promotions_query->execute([$seller_id]);
         while($promotion = $promotions_query->fetch(PDO::FETCH_ASSOC)){
      ?>
      <p>Promotion: <?= $promotion['name']; ?> | Discount: <?= $promotion['discount']; ?>%</p>
      <?php
         }
      ?>
      <a href="manage_promotions.php" class="btn">Manage</a>
      </div>

      <!-- Sales Reports -->
      <div class="box">
      <h3>Sales Reports</h3>
      <a href="generate_report.php" class="btn">Generate Report</a>
      </div>

      <!-- Support Tickets -->
      <div class="box">
      <h3>Support Tickets</h3>
      <?php
         $support_query = $conn->prepare("SELECT id, subject, status FROM `support_tickets` WHERE seller_id = ?");
         $support_query->execute([$seller_id]);
         while($support_ticket = $support_query->fetch(PDO::FETCH_ASSOC)){
      ?>
      <p>Ticket ID: <?= $support_ticket['id']; ?> | Subject: <?= $support_ticket['subject']; ?> | Status: <?= $support_ticket['status']; ?></p>
      <?php
         }
      ?>
      <a href="seller_support.php" class="btn">Manage Support</a>
      </div>

      <!-- Marketing Tools -->
      <div class="box">
      <h3>Marketing Tools</h3>
      <a href="marketing_tools.php" class="btn">Create Campaign</a>
      </div>

      <div class="box">
      <h3>Top Selling Products</h3>
      <?php
         $top_products = $conn->prepare("SELECT p.name, SUM(o.total_price) as total_sales FROM `orders` o JOIN `products` p ON o.seller_id = p.seller_id WHERE o.seller_id = ? GROUP BY p.name ORDER BY total_sales DESC LIMIT 5");
         $top_products->execute([$seller_id]);
         while($fetch_top_products = $top_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <p> <?= $fetch_top_products['name']; ?>: Rs. <?= $fetch_top_products['total_sales']; ?>/- </p>
      <?php
         }
      ?>
      <a href="seller_products.php" class="btn">See All Products</a>
      </div>

      <div class="box">
      <h3>Sales Trends</h3>
      <canvas id="salesChart"></canvas>
      <?php
         // Fetch data for the chart
         $sales_data = [];
         $sales_query = $conn->prepare("SELECT DATE(placed_on) as date, SUM(total_price) as total_sales FROM `orders` WHERE seller_id = ? GROUP BY DATE(placed_on) ORDER BY date DESC LIMIT 7");
         $sales_query->execute([$seller_id]);
         while($fetch_sales = $sales_query->fetch(PDO::FETCH_ASSOC)){
            $sales_data[] = $fetch_sales;
         }
      ?>
      <script>
         var ctx = document.getElementById('salesChart').getContext('2d');
         var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
               labels: [<?php foreach($sales_data as $data) { echo '"' . $data['date'] . '",'; } ?>],
               datasets: [{
                  label: 'Sales',
                  data: [<?php foreach($sales_data as $data) { echo $data['total_sales'] . ','; } ?>],
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1
               }]
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true
                  }
               }
            }
         });
      </script>
      </div>

      <!-- Inventory Management -->
      <div class="box">
      <h3>Inventory Management</h3>
      <?php
         $inventory_query = $conn->prepare("SELECT id, name, quantity FROM `products` WHERE seller_id = ?");
         $inventory_query->execute([$seller_id]);
         while($inventory = $inventory_query->fetch(PDO::FETCH_ASSOC)){
      ?>
      <p>Product ID: <?= $inventory['id']; ?> | Name: <?= $inventory['name']; ?> | Quantity: <?= $inventory['quantity']; ?></p>
      <?php
         }
      ?>
      <a href="manage_inventory.php" class="btn">Manage Inventory</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
