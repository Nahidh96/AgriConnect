<?php
include 'config.php';

if(isset($_POST['submit'])) {
    $language_preference = $_POST['language_preference'];
    
    // User registration
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = md5($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/'.$image;
    } else {
        $image = 'default.png';
        $image_folder = 'images/'.$image;
    }

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);

    if($select->rowCount() > 0){
        $message[] = 'User email already exists!';
    } else {
        if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
        } else {
            $insert = $conn->prepare("INSERT INTO `users`(name, email, phone, password, image, user_type, language_preference) VALUES(?,?,?,?,?,?,?)");
            $insert->execute([$name, $email, $phone, $pass, $image, 'user', $language_preference]);

            if($insert){
                // Setting cookies after successful registration
                $cookie_expiration_time = time() + (86400 * 30); // 30 days expiry
                setcookie('user_id', $conn->lastInsertId(), $cookie_expiration_time, "/");
                setcookie('language_preference', $language_preference, $cookie_expiration_time, "/");

                if(!empty($_FILES['image']['name']) && $image_size > 2000000){
                    $message[] = 'Image size is too large!';
                } else {
                    if(!empty($_FILES['image']['name'])){
                        move_uploaded_file($image_tmp_name, $image_folder);
                    }
                    $message[] = 'Registered successfully!';
                    header('location:index.php');
                    exit(); // Added to prevent further execution after redirection
                }
            }
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
   <meta name="description" content="Join Agriconnect and become part of the organic farming community. Register as a seller or buyer today.">
   <meta name="keywords" content="register, sign up, Agriconnect, organic farming, seller register, buyer register, Nahidh Naseem">
   <meta name="robots" content="noindex, nofollow">
   <link rel="canonical" href="https://www.agriconnect.lk/register">
   <meta name="author" content="Nahidh Naseem | Agriconnect Team">
   <title>Register | Agriconnect</title>
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

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Register Now</h3>
      <input type="text" name="name" class="box" placeholder="Enter your name" required>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="text" name="phone" class="box" placeholder="Enter your phone number" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
      <!--<input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">-->

      <label for="language_preference">Language Preference:</label>
      <select name="language_preference" id="language_preference" required>
         <option value="english">English</option>
         <option value="sinhala">Sinhala</option>
         <option value="tamil">Tamil</option>
      </select>

      <input type="submit" value="Register Now" class="btn" name="submit">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</section>

</body>
</html>