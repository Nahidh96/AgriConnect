<?php

@include '../config.php';

session_start();

$user_id = $_COOKIE['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

$seller_id = $_GET['seller_id'];

// Check if the seller ID exists in the users table
$seller_check = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$seller_check->execute([$seller_id]);
if ($seller_check->rowCount() == 0) {
    die('අවලංගු විකුණුම්කරු ID');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   // Insert message into the database
   $stmt = $conn->prepare("INSERT INTO `messages` (sender_id, receiver_id, message) VALUES (?, ?, ?)");
   $stmt->execute([$user_id, $seller_id, $message]);

   header("Location: chat_page.php?seller_id=$seller_id");
}

$messages = $conn->prepare("SELECT * FROM `messages` WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$messages->execute([$user_id, $seller_id, $seller_id, $user_id]);

?>

<!DOCTYPE html>
<html lang="si">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>විකුණුම්කරු සමඟ කතාබහ</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         margin: 0;
         padding: 0;
      }
      .chat {
         max-width: 800px;
         margin: 20px auto;
         background-color: #fff;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      .title {
         text-align: center;
         margin-bottom: 20px;
         color: #333;
      }
      .messages {
         max-height: 400px;
         overflow-y: auto;
         padding: 10px;
         border: 1px solid #ddd;
         border-radius: 4px;
         margin-bottom: 20px;
         font-size: 1.7rem;
      }
      .message {
         padding: 10px;
         margin-bottom: 10px;
         border-radius: 4px;
      }
      .message p {
         margin: 0;
      }
      .user {
         background-color: #e6f2ff;
         align-self: flex-end;
      }
      .seller {
         background-color: #f0f0f0;
         align-self: flex-start;
      }
      .message-form {
         display: flex;
         flex-direction: column;
      }
      .message-form textarea {
         resize: none;
         padding: 10px;
         margin-bottom: 10px;
         border-radius: 4px;
         border: 1px solid #ddd;
         font-size: 14px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="chat">

   <h1 class="title">විකුණුම්කරු සමඟ කතාබහ</h1>

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
      <textarea name="message" rows="5" placeholder="ඔබගේ පණිවිඩය මෙහි ටයිප් කරන්න..." required></textarea>
      <button type="submit" class="btn">යවන්න</button>
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>