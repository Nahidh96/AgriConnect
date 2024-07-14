<?php

@include 'config.php';

session_start();

// Check if user_id is set in session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit; // Ensure script stops after redirect
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_to_wishlist'])) {

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

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

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

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
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>
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
         <span>Don't panic, Go organic</span>
         <h3>Reach For A Healthier You With Organic Foods</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
         <a href="about.php" class="btn">about us</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat-1.png" alt="">
         <a href="category.php?category=fruits" class="cbtn">fruits</a>
      </div>

      <div class="box">
         <img src="images/cat-3.png" alt="">
         <a href="category.php?category=vegetables" class="cbtn">vegetables</a>
      </div>

      <div class="box">
         <img src="images/cat-4.png" alt="" style="height: 120px; width: 180px;">
         <a href="category.php?category=fish" class="cbtn">Fertilizers</a>
      </div>

      <div class="box">
         <img src="images/cat-5.png" alt="" style="height: 120px; width: 180px;">
         <a href="category.php?category=fish" class="cbtn">Rentals</a>
      </div>

      <div class="box">
         <img src="images/cat-6.png" alt="" style="height: 120px; width: 115px;">
         <a href="category.php?category=fish" class="cbtn">Startups</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

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
            <span class="original-price">Rs. <?= $original_price; ?>/-</span> 
            <span class="discounted-price"> <?= number_format($discounted_price, 2); ?>/-</span>
         </div>
      <?php } else { ?>
         <div class="price">Rs.<span><?= $fetch_products['price']; ?></span>/-</div>
      <?php } ?>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <a href="chat_page.php?seller_id=<?= $fetch_products['seller_id']; ?>" class="fas fa-comments"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <div class="pre_order">
         <div class="preorder"><?= $fetch_products['pre_order'] ? '<span>Pre-Order</span><br>' : ''; ?></div>
      </div>
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
