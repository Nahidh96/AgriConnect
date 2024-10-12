<?php

@include 'config.php';

session_start();

$user_id = $_COOKIE['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Learn more about Agriconnect and our mission to promote organic farming and sustainable agriculture. Discover how we connect farmers and consumers in Sri Lanka.">
   <meta name="keywords" content="about us, Agriconnect, organic farming, sustainable agriculture, Sri Lanka, Nahidh Naseem">
   <meta name="robots" content="index, follow">
   <link rel="canonical" href="https://www.agriconnect.lk/about">
   <meta name="author" content="Nahidh Naseem | Agriconnect Team">
   <title>About | Agriconnect</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <!-- favicon -->
   <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>

<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <h3>Download the Android AgriConnect Version</h3>
         <a href="https://storage.googleapis.com/download/storage/v1/b/web-to-native-website-android-apps/o/t5gx1ITY645x6K7oVJpH.apk?generation=1726215663997688&alt=media" class="btn">Download</a>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
