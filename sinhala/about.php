<?php

@include '../config.php';

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
   <title>අප ගැන | AgriConnect</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <img src="project images/about-img-2.svg" alt="">
         <h3>මන්ද අපව තෝරා ගත යුතුද?</h3>
         <p>අපි විශ්වාසනීය සමාගමක් වන අතර, වසර ගණනාවක් පුරා උසස් තත්ත්වයේ නිෂ්පාදන සහ සේවාවන් ලබා දෙන අත්දැකීමක් ඇත. අපගේ විශේෂඥ කණ්ඩායම විශිෂ්ට සේවා සම්පූර්ණ කිරීම සඳහා කැපවී සිටියි. අපි ඔබේ අවශ්‍යතා ඉටු කිරීම සඳහා ප්‍රතිසංවාසී මිල, විශ්වාසනීය බෙදාහැරීම සහ පුළුල් නිෂ්පාදන පරාසයක් ලබා දෙන්නෙමු. අපව තෝරා ගන්න නිවසේ සිටම පහසු සහ ආගන්තුක සේවා අත්දැකීමක් ලබා ගන්න.</p>
         <a href="contact.php" class="btn">අප හා සම්බන්ධ වන්න</a>
      </div>

      <div class="box">
         <h3>අපි මොනවාද සපයන්නේ?</h3>
         <p>අපි ඔබේ අවශ්‍යතා පරිපූර්ණ කිරීම සඳහා උසස් තත්ත්වයේ නිෂ්පාදන සහ සේවාවන් පුළුල් පරාසයක් සපයමු. ඔබ කෘෂිකාර්මික උපකරණ, බීජ, පොහොර හෝ විශේෂඥ උපදෙස් සෙවූවත්, අපි ඔබට සහය වනු ඇත. අපගේ කණ්ඩායම විශිෂ්ට සේවා සම්පූර්ණ කිරීම සහ ඔබට පහසු සහ ආගන්තුක අත්දැකීමක් ලබා දීම සඳහා කැපවී සිටී. ඔබේ සියලු කෘෂිකාර්මික අවශ්‍යතා සඳහා අපව තෝරා ගන්න.</p>
         <a href="shop.php" class="btn">අපගේ වෙළඳසැල</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">පාරිභෝගිකයන්ගේ සමාලෝචන</h1> 

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>"AgriConnect මා කෙරෙහි සැපයූ කෘෂිකාර්මික උපකරණ විශිෂ්ටයි. ඉතාමත් නිර්දේශ කළ හැක!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>සිත්තරා ප්‍රනාන්දු</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>"AgriConnect වෙතින් මිලදී ගත් බීජ උසස් තත්ත්වයෙන් යුක්ත වූවාය. මගේ කෘෂිකාර්මික වගා මීට පෙර කිසිදිනකම මෙතරම් හොඳින් කරා නැත!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>කමල් පෙරේරා</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>"ඔවුන්ගේ පාරිභෝගික සේවාවෙන් මට ආසිරි ප්‍රසාද ලැබී ඇත. මාගේ ගොවිපල සඳහා නිසි පොහොර තෝරා ගන්නට ඔවුන් මට උපදෙස් ලබා දුන්නේය."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>නුවසන් ජයසිංහ</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>"AgriConnect වෙතින් ලබා දුන් විශේෂඥ උපදෙස් මට මගේ ගොවිපළ වගා ක්‍රමවේදයන් යථාතත්වයට පත් කිරීමට උපකාරී විය."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>සමන්තා සිල්වා</h3>
      </div>

      <div class="box">
         <!-- comment test 6 -->
         <img src="images/pic-5.png" alt="">
         <p>"බෙදාහැරීම වේගවත් විය, සහ නිෂ්පාදන විස්තර කළාක් මෙන්ම යථාර්ථයෙහිද තිබිණි. ඉතා සතුටින්!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>නිමල් ප්‍රනාන්දු</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>"විවිධාකාර නිෂ්පාදන පරාසයක් තිබූ විශිෂ්ට වෙළඳ අත්දැකීමක්. නැවත මිලදී ගන්නා කරමි."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>මංජුල විජේසිංහ</h3>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
