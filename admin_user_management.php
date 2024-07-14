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
   <title>Admin User Management</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">User Management</h1>

   <div class="box-container">

      <div class="box">
         <h3>User Activity Logs</h3>
         <p>Track user activities such as login times, pages visited, and actions taken.</p>
         <a href="admin_user_logs.php" class="btn">view logs</a>
      </div>

      <div class="box">
         <h3>User Feedback</h3>
         <p>Collect and display feedback from users.</p>
         <a href="admin_user_feedback.php" class="btn">view feedback</a>
      </div>

      <div class="box">
         <h3>User Segmentation</h3>
         <p>Tools to segment users based on behavior, purchases, etc.</p>
         <a href="admin_user_segmentation.php" class="btn">segment users</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
