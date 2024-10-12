<?php
@include 'config.php';

session_start();

// Check if user_id is set in cookies
$user_id = $_COOKIE['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch seller_id and user_type from the users table
$select = $conn->prepare("SELECT id, user_type FROM `users` WHERE id = ?");
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['user_type'] !== 'seller') {
    header('Location: login.php');
    exit();
}

$seller_id = $user['id'];

if (isset($_GET['id'])) {
    $campaign_id = $_GET['id'];

    // Fetch campaign details
    $campaign_query = $conn->prepare("SELECT * FROM `campaigns` WHERE `id` = ? AND `seller_id` = ?");
    $campaign_query->execute([$campaign_id, $seller_id]);
    $campaign = $campaign_query->fetch(PDO::FETCH_ASSOC);

    if (!$campaign) {
        die('Campaign not found.');
    }
}

if (isset($_POST['update_campaign'])) {
    $campaign_name = $_POST['campaign_name'];
    $description = $_POST['description'];

    // Update campaign details
    $update_query = $conn->prepare("UPDATE `campaigns` SET `campaign_name` = ?, `campaign_description` = ? WHERE `id` = ? AND `seller_id` = ?");
    $update_query->execute([$campaign_name, $description, $campaign_id, $seller_id]);

    $message = "Campaign updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Campaign</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="edit-campaign">
    <h1 class="title">Edit Campaign</h1>
    <form action="" method="post">
        <label for="campaign_name">Campaign Name:</label>
        <input type="text" name="campaign_name" id="campaign_name" value="<?= htmlspecialchars($campaign['campaign_name']); ?>" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($campaign['campaign_description']); ?></textarea>
        <input type="submit" name="update_campaign" value="Update Campaign" class="btn">
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    </form>
</section>
<script src="js/script.js"></script>
</body>
</html>
