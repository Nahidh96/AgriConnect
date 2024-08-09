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
   <title>Customize | AgriConnect</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Customization Options</h1>

   <div class="box-container">

      <div class="box">
         <h3>Theme Settings</h3>
         <p>Customize the appearance of the admin dashboard.</p>
         <a href="admin_theme_settings.php" class="btn">theme settings</a>
      </div>

      <div class="box">
         <h3>Widget Management</h3>
         <p>Add, remove, and rearrange dashboard widgets.</p>
         <a href="admin_widget_management.php" class="btn">manage widgets</a>
      </div>

      <div class="box">
         <h3>Custom Reports</h3>
         <p>Create custom reports tailored to specific business needs.</p>
         <a href="admin_custom_reports.php" class="btn">create report</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
