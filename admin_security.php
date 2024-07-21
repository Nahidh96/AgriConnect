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
   <title>Admin Security Dashboard | AgriConnect</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Security Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Admin Logs</h3>
         <p>Track admin activities and changes made on the site.</p>
         <a href="admin_logs.php" class="btn">view logs</a>
      </div>

      <div class="box">
         <h3>Backup Tools</h3>
         <p>Tools to backup and restore the site’s data.</p>
         <a href="admin_backup.php" class="btn">backup</a>
      </div>

      <div class="box">
         <h3>Security Alerts</h3>
         <p>Notifications for potential security issues.</p>
         <a href="admin_security_alerts.php" class="btn">view alerts</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
