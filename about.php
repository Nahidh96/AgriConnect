<?php

@include 'config.php';

session_start();

$user_id = $_COOKIE['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

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
         <img src="project images/about-img-2.svg" alt="">
         <h3>Why Choose Us?</h3>
         <p>We are a trusted company with years of experience in providing high-quality products and services. Our team of experts is dedicated to delivering exceptional customer satisfaction. We offer competitive prices, reliable delivery, and a wide range of products to meet your needs. Choose us for a seamless and enjoyable shopping experience.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

      <div class="box">
         <h3>What We Provide?</h3>
         <p>We provide a wide range of high-quality products and services to meet your needs. Whether you're looking for agricultural equipment, seeds, fertilizers, or expert advice, we have you covered. Our team of professionals is dedicated to delivering exceptional customer satisfaction and ensuring that you have a seamless and enjoyable shopping experience with us. Choose us for all your agricultural needs.</p>
         <a href="shop.php" class="btn">our shop</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">clients reviews</h1> 

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>"AgriConnect provided me with the best agricultural equipment I've ever used. Highly recommended!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Sithara Fernando</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>"The quality of seeds I bought from AgriConnect was exceptional. My crops have never been better!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Kamal Perera</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>"I was impressed with their customer service. They helped me choose the right fertilizer for my farm."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Nuwan Jayasinghe</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>"AgriConnect's expert advice helped me improve my farming techniques significantly."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Samantha Silva</h3>
      </div>

      <div class="box">
         <!-- comment test 6 -->
         <img src="images/pic-5.png" alt="">
         <p>"The delivery was prompt and the products were exactly as described. Very satisfied!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Nimal Fernando</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>"A great shopping experience with a wide variety of products to choose from. Will buy again."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Manjula Wijesinghe</h3>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
