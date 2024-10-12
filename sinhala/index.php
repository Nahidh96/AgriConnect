<?php

@include '../config.php';

session_start();

// Check if user_id is set in cookies
if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit; // Ensure script stops after redirect
}

$user_id = $_COOKIE['user_id']; // Retrieve user ID from the cookie

// Get the current page
$current_page = basename($_SERVER['PHP_SELF']);

// Check if user_id is set in cookies
if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit; // Ensure script stops after redirect
}

$user_id = $_COOKIE['user_id']; // Retrieve user ID from the cookie

if (isset($_COOKIE['language_preference'])) {
    $language_preference = $_COOKIE['language_preference'];

    switch ($language_preference) {
        case 'sinhala':
            if ($current_page !== 'index.php') {
                header('Location: sinhala/index.php');
                exit();
            }
            break;
        // case 'tamil':
        //     if ($current_page !== 'tamil/index.php') {
        //         header('Location: tamil/index.php');
        //         exit();
        //     }
        //     break;
        case 'english':
        default:
            // Only redirect if not already on 'index.php'
            if ($current_page !== 'index.php') {
                header('Location: index.php');
                exit();
            }
            break;
    }
} else {
    // Default to English if no preference is set
    if ($current_page !== 'index.php') {
        header('Location: index.php');
        exit();
    }
}

if (isset($_POST['add_to_wishlist'])) {

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'already added to wishlist!';
    } elseif ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'already added to cart!';
    } else {
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
        $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
        $message[] = 'added to wishlist!';
    }

}

if (isset($_POST['add_to_cart'])) {

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'already added to cart!';
    } else {

        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        if ($check_wishlist_numbers->rowCount() > 0) {
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$p_name, $user_id]);
        }

        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
        $message[] = 'added to cart!';
    }

}

?>


<!DOCTYPE html>
<html lang="si">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>මුල් පිටුව</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">
   <!-- favicon -->
   <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

        <div class="content" style="font-size: 0.9em;">
            <span style="font-size: 1.95rem;">කෙලින්ම පන්නා එපා, ස්වභාවික වර්ග තෝරන්න</span>
            <h3 style="font-size: 2.65rem;">ඔබේ සෞඛ්‍යය තවදුරටත් උසස් කරන්න ස්වභාවික ආහාර වලින්</h3>
            <a href="about.php" class="btn">අපි ගැන</a>
        </div>


   </section>

</div>

<section class="home-category">
    <h1 class="title">ප්‍රවර්ග අනුව මිලදී ගන්න</h1>

    <div class="box-container">
        <div class="box">
            <div class="icon">
                <i class="fas fa-apple-alt" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fruits" class="cbtn">පළතුරු</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-carrot" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=vegetables" class="cbtn">එළවලු</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-seedling" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fertilizers" class="cbtn">පොහොර</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-tractor" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=equipments" class="cbtn">උපාංග</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-lightbulb" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=startups" class="cbtn">ව්‍යාපාර</a>
        </div>
    </div>
</section>


<section class="products">

   <h1 class="title">නවතම නිෂ්පාදන</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("
         SELECT p.*, pr.discount, pr.start_date, pr.end_date 
         FROM `products` p 
         LEFT JOIN `promotions` pr ON p.id = pr.product_id 
         AND pr.start_date <= CURDATE() AND pr.end_date >= CURDATE() 
         LIMIT 6
      ");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            $has_promotion = !empty($fetch_products['discount']);
            $original_price = $fetch_products['price'];
            $discounted_price = $has_promotion ? $original_price * (1 - $fetch_products['discount'] / 100) : $original_price;
   ?>
   <form action="" class="box" method="POST">
      <?php if($has_promotion){ ?>
         <div class="price">
            <span class="original-price">රු. <?= $original_price; ?>/-</span> 
            <span class="discounted-price"> <?= number_format($discounted_price, 2); ?>/-</span>
         </div>
      <?php } else { ?>
         <div class="price">රු.<span><?= $fetch_products['price']; ?></span>/-</div>
      <?php } ?>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <a href="chat_page.php?seller_id=<?= $fetch_products['seller_id']; ?>" class="fas fa-comments"></a>
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <div class="pre_order">
         <div class="preorder"><?= $fetch_products['pre_order'] ? '<span>පූර්ව ඇණවුම</span><br>' : ''; ?></div>
      </div>
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="ලැයිස්තුවට එකතු කරන්න" class="option-btn" name="add_to_wishlist" style="font-size: 1.8rem;">
      <input type="submit" value="බැගයට එකතු කරන්න" class="btn" name="add_to_cart" style="font-size: 1.8rem;">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">තවමත් කිසිඳු නිෂ්පාදන එකතු කර නැත!</p>';
      }
   ?>

   </div>

   <div class="language-switcher">
        <button class="lang-btn" onclick="switchLanguage('si')">සිංහල</button>
        <!--<button class="lang-btn" onclick="switchLanguage('ta')">தமிழ்</button>-->
    </div>

<script>
    function switchLanguage(lang) {
        let url;
        switch(lang) {
            case 'si':
                url = 'sinhala/index.php';
                break;
            case 'ta':
                url = 'tamil/index.php';
                break;
            default:
                url = 'index.php';
                break;
        }
        window.location.href = url;
    }
</script>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
