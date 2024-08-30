<?php

@include 'config.php';

session_start();

if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit; 
}

$user_id = $_COOKIE['user_id'];

if (isset($_COOKIE['language_preference'])) {
    $language_preference = $_COOKIE['language_preference'];

    switch ($language_preference) {
        case 'sinhala':
            if ($current_page !== 'index.php') {
                header('Location: sinhala/index.php');
                exit();
            }
            break;
        case 'tamil':
            if ($current_page !== 'tamil/index.php') {
                header('Location: tamil/index.php');
                exit();
            }
            break;
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
<html lang="ta">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>முகப்புப் பக்கம்</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <!-- favicon -->
   <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>வெறுக்க வேண்டாம், ஆர்கானிக் பண்ணுங்கள்</span>
         <h3>ஆரோக்கியமான உங்கள் நோக்கத்திற்காக ஆர்கானிக் உணவுகளைத் தேர்வு செய்யுங்கள்</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
         <a href="about.php" class="btn">எங்களை பற்றி</a>
      </div>

   </section>

</div>

<section class="home-category">
    <h1 class="title">வகைப்பட சிறப்பிக்க</h1>

    <div class="box-container">
        <div class="box">
            <div class="icon">
                <i class="fas fa-apple-alt" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fruits" class="cbtn">பழங்கள்</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-carrot" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=vegetables" class="cbtn">காய்கறிகள்</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-seedling" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fertilizers" class="cbtn">உழவர் உரங்கள்</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-tractor" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=equipments" class="cbtn">பயன்பாட்டு உபகரணங்கள்</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-lightbulb" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=startups" class="cbtn">ஆரம்பகால திட்டங்கள்</a>
        </div>
    </div>
</section>


<section class="products">

   <h1 class="title">சமீபத்திய தயாரிப்புகள்</h1>

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
            <span class="original-price">ரூ. <?= $original_price; ?>/-</span> 
            <span class="discounted-price"> <?= number_format($discounted_price, 2); ?>/-</span>
         </div>
      <?php } else { ?>
         <div class="price">ரூ.<span><?= $fetch_products['price']; ?></span>/-</div>
      <?php } ?>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <a href="chat_page.php?seller_id=<?= $fetch_products['seller_id']; ?>" class="fas fa-comments"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <button type="submit" name="add_to_wishlist" class="fas fa-heart"></button>
      <input type="number" min="1" name="p_qty" value="1" class="qty">
      <button type="submit" name="add_to_cart" class="btn">கூடையில் சேர்</button>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">முடிவுகள் இல்லை!</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
