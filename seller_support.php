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

if (isset($_POST['create_ticket'])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $create_ticket = $conn->prepare("INSERT INTO `support_tickets` (`seller_id`, `subject`, `message`) VALUES (?, ?, ?)");
    $create_ticket->execute([$user_id, $subject, $message]);
}

// Fetch support tickets
$support_tickets = $conn->prepare("SELECT * FROM `support_tickets` WHERE `seller_id` = ?");
$support_tickets->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>

<?php include 'seller_header.php'; ?>

<section class="support">
    <h1 class="title">Support Tickets</h1>
    <form action="" method="post" class="ticket-form">
        <input type="text" name="subject" placeholder="Subject" class="subject" required>
        <textarea name="message" rows="5" placeholder="Type your message here..." required></textarea>
        <input type="submit" name="create_ticket" value="Create Ticket" class="btn">
    </form>
    <br>
    <div class="messages">
        <?php while($ticket = $support_tickets->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="box">
                <h3><?= htmlspecialchars($ticket['subject']); ?></h3>
                <p>Status: <?= htmlspecialchars($ticket['status']); ?></p>
                <?php if (!empty($ticket['response'])): ?>
                    <p>Admin Response: <?= htmlspecialchars($ticket['response']); ?></p>
                <?php endif; ?>
                <p><?= htmlspecialchars($ticket['message']); ?></p>
            </div>
        <?php } ?>
    </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
