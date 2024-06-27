<?php
@include 'config.php';
session_start();

$seller_id = $_SESSION['seller_id'];
if(!isset($seller_id)){
   header('location:seller_login.php');
}

// Implement necessary functionality for marketing tools here
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Marketing Tools</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="marketing">
   <h1 class="title">Marketing Tools</h1>
   <!-- Add forms and tools for marketing campaigns here -->
</section>
<script src="js/script.js"></script>
</body>
</html>
