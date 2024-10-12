<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $address = $_POST['address'];
    $business_name = $_POST['business_name'];
    $terms_agreed = isset($_POST['terms_agreed']) ? $_POST['terms_agreed'] : '';

    // Validate terms agreement
    if ($terms_agreed != 'on') {
        $message[] = 'You must agree to the terms and conditions!';
    } else {
        // Get the user ID from the cookie
        $user_id = $_COOKIE['user_id'] ?? null;

        if ($user_id) {
            try {
                // Check if the user is currently registered as a user
                $select = $conn->prepare("SELECT user_type FROM `users` WHERE id = ?");
                $select->execute([$user_id]);
                $user = $select->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $current_user_type = $user['user_type'];

                    // If the user is currently a 'user', update to 'seller'
                    if ($current_user_type == 'user') {
                        $update = $conn->prepare("UPDATE `users` SET user_type = 'seller', address = ?, business_name = ? WHERE id = ?");
                        $update->execute([$address, $business_name, $user_id]);

                        if ($update) {
                            $message[] = 'Successfully registered as a seller!';
                            header('Location: seller_page.php');
                            exit(); // Prevent further execution after redirection
                        } else {
                            $message[] = 'Registration failed!';
                        }
                    } else {
                        $message[] = 'You are already registered as a seller or have another user type!';
                    }
                } else {
                    $message[] = 'User not found!';
                }
            } catch (PDOException $e) {
                $message[] = 'Database error: ' . $e->getMessage();
            }
        } else {
            $message[] = 'User ID is not set in cookies!';
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
   <title>Seller Registration</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body class="custom-bg">

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>
   
<section class="form-container">

   <form action="" method="POST">
      <h3>Complete Seller Registration</h3>
      <input type="text" name="address" class="box" placeholder="Enter your home address" required>
      <input type="text" name="business_name" class="box" placeholder="Enter your business name" required>

      <label>
         <input type="checkbox" name="terms_agreed">
         I agree to the <a href="terms_conditions.php" target="_blank">terms and conditions</a>.
      </label>

      <input type="submit" value="Register as Seller" class="btn" name="submit">
   </form>
</section>

</body>
</html>
