<?php

@include 'config.php';

session_start();

$admin_id = $_COOKIE['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_ticket'])){
   $ticket_id = $_POST['ticket_id'];
   $response = $_POST['response'];
   $status = $_POST['status'];

   $update_ticket = $conn->prepare("UPDATE `support_tickets` SET `response` = ?, `status` = ? WHERE `id` = ?");
   $update_ticket->execute([$response, $status, $ticket_id]);
}

$support_tickets = $conn->prepare("SELECT * FROM `support_tickets`");
$support_tickets->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Support Tickets</title>
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/custom.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="support">
   <h1 class="title">Manage Support Tickets</h1>
   <div class="messages">
      <?php while($ticket = $support_tickets->fetch(PDO::FETCH_ASSOC)){ ?>
         <div class="box">
            <h3>Seller ID: <?= $ticket['seller_id']; ?></h3>
            <p>Subject: <?= $ticket['subject']; ?></p>
            <p>Message: <?= $ticket['message']; ?></p>
            <form action="" method="post" class="ticket-form">
               <textarea name="response" rows="4" placeholder="Type your response here..."><?= $ticket['response']; ?></textarea>
               <select name="status">
                  <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                  <option value="closed" <?= $ticket['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
               </select>
               <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
               <input type="submit" name="update_ticket" value="Update Ticket" class="btn">
            </form>
         </div>
      <?php } ?>
   </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
