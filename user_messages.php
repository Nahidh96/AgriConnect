<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$message = []; // Initialize as an array to store multiple messages

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['receiver_id'])) {
    $msg_content = $_POST['message'];
    $msg_content = filter_var($msg_content, FILTER_SANITIZE_STRING);
    $receiver_id = $_POST['receiver_id'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO `messages` (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $receiver_id, $msg_content]);

    $message[] = 'Message sent!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Messages</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="messages">

   <h1 class="title">Your Messages</h1>

   <div class="box-container">

      <?php
         $select_messages = $conn->prepare("SELECT DISTINCT receiver_id FROM `messages` WHERE sender_id = ?");
         $select_messages->execute([$user_id]);
         if ($select_messages->rowCount() > 0) {
            while ($fetch_receiver = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                $receiver_id = $fetch_receiver['receiver_id'];
                
                // Fetch seller details from users table
                $seller_stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $seller_stmt->execute([$receiver_id]);
                $seller_details = $seller_stmt->fetch(PDO::FETCH_ASSOC);
                
                // Fetch messages
                $messages_stmt = $conn->prepare("SELECT * FROM `messages` WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY id");
                $messages_stmt->execute([$user_id, $receiver_id, $receiver_id, $user_id]);
      ?>
      <div class="box">
         <p>To: <span><?= htmlspecialchars($seller_details['name']); ?></span></p>
         <p>Email: <span><?= htmlspecialchars($seller_details['email']); ?></span></p>
         
         <div class="message-box">
            <?php while ($message_row = $messages_stmt->fetch(PDO::FETCH_ASSOC)) { ?>
               <p><b><?= $message_row['sender_id'] == $user_id ? 'You' : htmlspecialchars($seller_details['name']); ?>:</b> <?= htmlspecialchars($message_row['message']); ?></p>
            <?php } ?>
         </div>
         
         <form action="" method="POST" class="message-form">
            <textarea name="message" class="message-textarea" rows="5" placeholder="Type your message here..." required></textarea>
            <input type="hidden" name="receiver_id" value="<?= $receiver_id; ?>">
            <button type="submit" class="btn">Send</button>
        </form>

         </div>
      <?php
            }
         } else {
            echo '<p class="empty">No messages yet!</p>';
         }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
