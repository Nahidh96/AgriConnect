<?php

@include '../config.php';

session_start();

$user_id = $_COOKIE['user_id'];

if(!isset($user_id)){
   header('location:../login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'ඉහත දැන්වීමෙහි අඩංගු වේ!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'ඉහත වාණිජ බදු නොමැත!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'ප්‍රියතමයන්ට එක් කරන ලදී!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'ඉහත වාණිජ බදු නොමැත!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'වාණිජ බදු එකතු කරන ලදී!';
   }

}

?>

<!DOCTYPE html>
<html lang="si">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>අලෙවි | AgriConnect</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="p-category">

   <a href="category.php?category=fruits">පලතුරු</a>
   <a href="category.php?category=vegitables">තක්කාලි</a>
   <a href="category.php?category=equipments">කෝපන</a>
   <a href="category.php?category=fertilizers">කෘෂිකර්ම</a>

</section>

<section class="products">

   <h1 class="title">නවතම නිෂ්පාදන</h1>

   <div class="box-container">

   <?php
      // Modify the query to join with the promotions table
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
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <div class="pre_order">
         <div class="preorder"><?= $fetch_products['pre_order'] ? '<span>පෙර-නියෝගය</span><br>' : ''; ?></div>
      </div>
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="ලැයිස්තුවට එකතු කරන්න" class="option-btn" name="add_to_wishlist" style="font-size: 1.8rem;">
      <input type="submit" value="බැගයට එකතු කරන්න" class="btn" name="add_to_cart" style="font-size: 1.8rem;">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">අමතර නිෂ්පාදන කිසිවක් එකතු කර නොමැත!</p>';
   }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>