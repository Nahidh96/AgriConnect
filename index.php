<?php

include 'config.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    
    // Retrieve the user type from the database
    $select_user = $conn->prepare("SELECT user_type FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);
    $user_type = $user['user_type']; // This will be 'seller' or 'user'
} else {
    $user_type = ''; // Default value if user is not logged in
}

session_start();

// Check if user_id is set in cookies
if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit; // Ensure script stops after redirect
}

$user_id = $_COOKIE['user_id']; // Retrieve user ID from the cookie

$current_page = basename($_SERVER['PHP_SELF']); // Get the current script name (e.g., 'index.php')

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
        //     if ($current_page !== 'index.php') {
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

    // Check if there's a discount on the product
    $select_discount = $conn->prepare("SELECT discount FROM `promotions` WHERE product_id = ? AND start_date <= CURDATE() AND end_date >= CURDATE()");
    $select_discount->execute([$pid]);
    $discount = $select_discount->fetchColumn();
    
    if ($discount) {
        $p_price = $p_price * (1 - $discount / 100); // Calculate discounted price
    }

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
    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_NUMBER_INT); // This will be replaced with the discounted price if available
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_NUMBER_INT);

    // Check if a discount is available for the product
    $check_discount = $conn->prepare("
        SELECT pr.discount 
        FROM `products` p 
        LEFT JOIN `promotions` pr ON p.id = pr.product_id 
        WHERE p.id = ? AND pr.start_date <= CURDATE() AND pr.end_date >= CURDATE()
    ");
    $check_discount->execute([$pid]);
    $discount_data = $check_discount->fetch(PDO::FETCH_ASSOC);

    if ($discount_data) {
        $discount = $discount_data['discount'];
        $p_price = $p_price * (1 - $discount / 100); // Apply the discount to the price
    }

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

        $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image, product_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image, $pid])) {
            $message[] = 'added to cart!';
        } else {
            print_r($insert_cart->errorInfo()); // Print SQL error information
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Agriconnect offers a wide range of organic farming products, including fruits, vegetables, fertilizers, and equipment. Discover the benefits of organic living with us.">
   <meta name="keywords" content="organic food, fruits, vegetables, fertilizers, organic farming, Agriconnect, AgriConnect LK, AgriConnect Sri Lanka, Nahidh Naseem">
   <meta name="robots" content="index, follow">
   <link rel="canonical" href="https://www.agriconnect.lk/">
   <meta name="author" content="Nahidh Naseem | Agriconnect Team">
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
            <p>Discover the pure, natural benefits of organic living.</p>
            <a href="about.php" class="btn">about us</a>
            <?php if ($user_type === 'seller'): ?>
                <a href="seller_page.php" class="btn">Seller Dashboard</a>
            <?php endif; ?>
        </div>
    </section>
</div>


<section class="home-category">
    <h1 class="title">Shop by Category</h1>

    <div class="box-container">
        <div class="box">
            <div class="icon">
                <i class="fas fa-apple-alt" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fruits" class="cbtn">Fruits</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-carrot" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=vegetables" class="cbtn">Vegetables</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-seedling" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=fertilizers" class="cbtn">Fertilizers</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-tractor" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=equipments" class="cbtn">Equipments</a>
        </div>

        <div class="box">
            <div class="icon">
                <i class="fas fa-lightbulb" style="color: #1f5120;"></i>
            </div>
            <a href="category.php?category=startups" class="cbtn">Startups</a>
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
         LIMIT 15
      ");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         $counter = 0; // Initialize counter
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
            $counter++; // Increment counter

            // Insert the notice after the 4th product
            if($counter == 3) {
   ?>
   <div class="notice">
       <h3>Join Us as a Seller Today!</h3>
       <p>Grow your business with us. Reach more customers and increase your sales effortlessly.</p>
       <a href="seller_r.php" class="option-btn">Start Selling</a>
   </div>
   <?php
            }
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
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
