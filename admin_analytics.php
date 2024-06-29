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
   <title>Admin Analytics Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <!-- Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Analytics Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Sales Analysis</h3>
         <canvas id="salesChart"></canvas>
      </div>

      <div class="box">
         <h3>User Engagement</h3>
         <canvas id="engagementChart"></canvas>
      </div>

      <div class="box">
         <h3>Product Performance</h3>
         <canvas id="productChart"></canvas>
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
         backgroundColor: 'rgba(75, 192, 192, 0.2)',
         borderColor: 'rgba(75, 192, 192, 1)',
         borderWidth: 1
      }]
   };

   const engagementData = {
      labels: ['Logins', 'Pages Visited', 'Actions Taken'],
      datasets: [{
         label: 'Engagement',
         data: [500, 1200, 900],
         backgroundColor: 'rgba(153, 102, 255, 0.2)',
         borderColor: 'rgba(153, 102, 255, 1)',
         borderWidth: 1
      }]
   };

   const productData = {
      labels: ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
      datasets: [{
         label: 'Sales',
         data: [50, 70, 30, 90, 110],
         backgroundColor: 'rgba(255, 159, 64, 0.2)',
         borderColor: 'rgba(255, 159, 64, 1)',
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
      type: 'horizontalBar',
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

</body>
</html>
