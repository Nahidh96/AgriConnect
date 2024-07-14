<?php
@include 'config.php';
session_start();

$seller_id = $_SESSION['seller_id'];
if(!isset($seller_id)){
   header('location:seller_login.php');
}

if(isset($_POST['generate_report'])){
   $start_date = $_POST['start_date'];
   $end_date = $_POST['end_date'];

   // Fetch sales data within the selected date range
   $report_query = $conn->prepare("SELECT * FROM `orders` WHERE `seller_id` = ? AND `placed_on` BETWEEN ? AND ?");
   $report_query->execute([$seller_id, $start_date, $end_date]);

   // Fetch sales total
   $total_sales_query = $conn->prepare("SELECT SUM(total_price) AS total_sales FROM `orders` WHERE `seller_id` = ? AND `placed_on` BETWEEN ? AND ?");
   $total_sales_query->execute([$seller_id, $start_date, $end_date]);
   $total_sales = $total_sales_query->fetch(PDO::FETCH_ASSOC)['total_sales'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Generate Sales Report</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="report">
   <h1 class="title">Generate Sales Report</h1>
   <form action="" method="post">
      <label for="start_date">Start Date:</label>
      <input type="date" name="start_date" id="start_date" required>
      <label for="end_date">End Date:</label>
      <input type="date" name="end_date" id="end_date" required>
      <input type="submit" name="generate_report" value="Generate Report" class="btn">
   </form>
   
   <?php if(isset($report_query)){ ?>
      <div class="report-results">
         <h2>Sales Report from <?= $start_date; ?> to <?= $end_date; ?></h2>
         <p>Total Sales: Rs. <?= $total_sales; ?>/-</p>
         <table>
            <thead>
               <tr>
                  <th>Order ID</th>
                  <th>Placed On</th>
                  <th>Total Price</th>
                  <th>Status</th>
               </tr>
            </thead>
            <tbody>
               <?php while($order = $report_query->fetch(PDO::FETCH_ASSOC)){ ?>
                  <tr>
                     <td><?= $order['id']; ?></td>
                     <td><?= $order['placed_on']; ?></td>
                     <td>Rs. <?= $order['total_price']; ?>/-</td>
                     <td><?= $order['payment_status']; ?></td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>
   <?php } ?>
</section>
<script src="js/script.js"></script>
</body>
</html>
