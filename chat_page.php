<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

$seller_id = $_GET['seller_id'];

// Check if the seller ID exists in the users table
$seller_check = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$seller_check->execute([$seller_id]);
if ($seller_check->rowCount() == 0) {
    die('Invalid seller ID');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   // Insert message into the database
   $stmt = $conn->prepare("INSERT INTO `messages` (sender_id, receiver_id, message) VALUES (?, ?, ?)");
   $stmt->execute([$user_id, $seller_id, $message]);

   header("Location: chat_page.php?seller_id=$seller_id");
}

$messages = $conn->prepare("SELECT * FROM `messages` WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$messages->execute([$user_id, $seller_id, $seller_id, $user_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chat with Seller</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="chat">

   <h1 class="title">Chat with Seller</h1>

   <div class="messages">
      <?php
      while ($row = $messages->fetch(PDO::FETCH_ASSOC)) {
         if ($row['sender_id'] == $user_id) {
            echo '<div class="message user"><p>' . htmlspecialchars($row['message']) . '</p></div>';
         } else {
            echo '<div class="message seller"><p>' . htmlspecialchars($row['message']) . '</p></div>';
         }
      }
      ?>
   </div>

   <form action="" method="POST" class="message-form">
      <textarea name="message" rows="5" placeholder="Type your message here..." required></textarea>
      <button type="submit" class="btn">Send</button>
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
