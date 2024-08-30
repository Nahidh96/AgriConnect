<?php
@include 'config.php';

session_start();

// Check if user_id is set in cookies
$user_id = $_COOKIE['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Check if the user is a seller
$select = $conn->prepare("SELECT user_type FROM `users` WHERE id = ?");
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['user_type'] !== 'seller') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['add_promotion'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $start_date = filter_var($_POST['start_date'], FILTER_SANITIZE_STRING);
    $end_date = filter_var($_POST['end_date'], FILTER_SANITIZE_STRING);

    $add_promotion = $conn->prepare("INSERT INTO `promotions` (seller_id, product_id, discount, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $add_promotion->execute([$user_id, $product_id, $discount, $start_date, $end_date]);
}

if (isset($_POST['delete_promotion'])) {
    $promotion_id = filter_var($_POST['promotion_id'], FILTER_SANITIZE_NUMBER_INT);
    $delete_promotion = $conn->prepare("DELETE FROM `promotions` WHERE id = ?");
    $delete_promotion->execute([$promotion_id]);
}

// Retrieve promotions for the seller
$promotions = $conn->prepare("SELECT * FROM `promotions` WHERE `seller_id` = ?");
$promotions->execute([$user_id]);

// Retrieve products for the seller
$products = $conn->prepare("SELECT * FROM `products` WHERE `seller_id` = ?");
$products->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Promotions</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        /* Additional CSS for manage promotions page */
        .promotions {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            max-width: 800px;
        }

        .promotions form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .promotions form select,
        .promotions form input[type="number"],
        .promotions form input[type="date"],
        .promotions form .normbtn {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
            box-shadow: none;
        }

        .promotions form .normbtn {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .promotions form .normbtn:hover {
            background-color: #45a049;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .box {
            position: relative;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .box h3 {
            margin-top: 0;
            font-size: 18px;
        }

        .box p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* Custom delete button style */
        .delete-btncustom {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
            text-align: center;
            box-shadow: none;
        }
    </style>
</head>
<body>
<?php include 'seller_header.php'; ?>

<section class="promotions">
    <h1 class="title">Manage Promotions</h1>
    <form action="" method="post">
        <select name="product_id" required>
            <option value="" selected disabled>Select Product</option>
            <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?= htmlspecialchars($product['id']); ?>"><?= htmlspecialchars($product['name']); ?></option>
            <?php } ?>
        </select>
        <input type="number" name="discount" placeholder="Discount %" min="0" max="100" required>
        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <input type="submit" name="add_promotion" value="Add Promotion" class="normbtn">
    </form>
    <div class="box-container">
        <?php while ($promotion = $promotions->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="box">
                <form action="" method="post">
                    <input type="hidden" name="promotion_id" value="<?= htmlspecialchars($promotion['id']); ?>">
                    <input type="submit" name="delete_promotion" value="Delete" class="delete-btncustom">
                </form>
                <h3>Product ID: <?= htmlspecialchars($promotion['product_id']); ?></h3>
                <p>Discount: <?= htmlspecialchars($promotion['discount']); ?>%</p>
                <p>Start Date: <?= htmlspecialchars($promotion['start_date']); ?></p>
                <p>End Date: <?= htmlspecialchars($promotion['end_date']); ?></p>
            </div>
        <?php } ?>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
