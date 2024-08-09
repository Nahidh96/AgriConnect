<?php
@include 'config.php';
session_start();

$seller_id = $_COOKIE['seller_id'];
if(!isset($seller_id)){
   header('location:seller_login.php');
}

if(isset($_POST['create_ticket'])){
   $subject = $_POST['subject'];
   $message = $_POST['message'];
   $create_ticket = $conn->prepare("INSERT INTO `support_tickets` (`seller_id`, `subject`, `message`) VALUES (?, ?, ?)");
   $create_ticket->execute([$seller_id, $subject, $message]);
}

$support_tickets = $conn->prepare("SELECT * FROM `support_tickets` WHERE `seller_id` = ?");
$support_tickets->execute([$seller_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Support Tickets</title>
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="support">
   <h1 class="title">Support Tickets</h1>
   <form action="" method="post">
      <input type="text" name="subject" placeholder="Subject" required>
      <textarea name="message" placeholder="Message" required></textarea>
      <input type="submit" name="create_ticket" value="Create Ticket" class="btn">
   </form>
   <div class="box-container">
      <?php while($ticket = $support_tickets->fetch(PDO::FETCH_ASSOC)){ ?>
         <div class="box">
            <h3><?= $ticket['subject']; ?></h3>
            <p>Status: <?= $ticket['status']; ?></p>
            <p><?= $ticket['message']; ?></p>
         </div>
      <?php } ?>
   </div>
</section>
<script src="js/script.js
