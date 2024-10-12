<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check user credentials
    $select = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $select->execute([$email, $pass]);

    if ($select->rowCount() > 0) {
        $user = $select->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id'];
        $user_type = $user['user_type'];

        // Set cookies for user session
        $cookie_expiration_time = time() + (86400 * 90); // 30 days expiry
        setcookie('user_id', $user_id, $cookie_expiration_time, "/");
        if (isset($_COOKIE['user_id'])) {
            echo 'Cookie is set! User ID: ' . $_COOKIE['user_id'];
        } else {
            echo 'Failed to set cookie.';
        }


        // Redirect based on user type
        if ($user_type === 'seller') {
            header('Location: choose_page.php');
            exit();
        } else {
            header('Location: index.php');
            exit();
        }
    } else {
        $message[] = 'Invalid email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Login to Agriconnect to manage your organic farming products and orders. Secure access for sellers and buyers.">
   <meta name="keywords" content="login, Agriconnect, organic farming, seller login, buyer login, Nahidh Naseem">
   <meta name="robots" content="noindex, nofollow">
   <link rel="canonical" href="https://www.agriconnect.lk/login">
   <meta name="author" content="Nahidh Naseem | Agriconnect Team">
   <title>Login | Agriconnect</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <!-- favicon -->
   <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>

<body class="custom-bg">

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form action="" method="POST">
      <h3>Login</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="submit" value="Login" class="btn" name="login">
      <p>Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

</body>
</html>