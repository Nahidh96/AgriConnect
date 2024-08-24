<?php

@include '../config.php';

session_start();

$seller_id = $_COOKIE['seller_id'];

if(!isset($seller_id)){
   header('location:seller_login.php');
}

$message = []; // Initialize as an array to store multiple messages

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['receiver_id'])) {
    $msg_content = $_POST['message'];
    $msg_content = filter_var($msg_content, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $receiver_id = $_POST['receiver_id'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO `messages` (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$seller_id, $receiver_id, $msg_content]);

    $message[] = 'Message sent!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Messages</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="messages">

   <h1 class="title">Your Messages</h1>

   <div class="box-container">

      <?php
         $select_messages = $conn->prepare("SELECT DISTINCT sender_id FROM `messages` WHERE receiver_id = ?");
         $select_messages->execute([$seller_id]);
         if($select_messages->rowCount() > 0){
            while($fetch_sender = $select_messages->fetch(PDO::FETCH_ASSOC)){
                $sender_id = $fetch_sender['sender_id'];
                
                // Fetch user details
                $user_stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $user_stmt->execute([$sender_id]);
                $user_details = $user_stmt->fetch(PDO::FETCH_ASSOC);
                
                // Fetch messages
                $messages_stmt = $conn->prepare("SELECT * FROM `messages` WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY id");
                $messages_stmt->execute([$sender_id, $seller_id, $seller_id, $sender_id]);
      ?>
      <div class="box">
         <p>From: <span><?= htmlspecialchars($user_details['name']); ?></span></p>
         <p>Email: <span><?= htmlspecialchars($user_details['email']); ?></span></p>
         
         <div class="message-box">
            <?php while ($message_row = $messages_stmt->fetch(PDO::FETCH_ASSOC)) { ?>
               <p><b><?= $message_row['sender_id'] == $seller_id ? 'You' : htmlspecialchars($user_details['name']); ?>:</b> <?= htmlspecialchars($message_row['message']); ?></p>
            <?php } ?>
         </div>
         
         <form action="" method="POST" class="message-form">
            <textarea name="message" rows="5" placeholder="Type your message here..." required></textarea>
            <input type="hidden" name="receiver_id" value="<?= $sender_id; ?>">
            <button type="submit" class="btn">Send</button>
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">No messages yet!</p>';
         }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
