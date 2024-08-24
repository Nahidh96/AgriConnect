<?php
@include '../config.php';
session_start();

$seller_id = $_COOKIE['seller_id'];
if (!isset($seller_id)) {
    header('location:seller_login.php');
}

if (isset($_GET['id'])) {
    $campaign_id = $_GET['id'];
    
    // Fetch campaign details
    $campaign_query = $conn->prepare("SELECT * FROM `marketing_campaigns` WHERE `id` = ? AND `seller_id` = ?");
    $campaign_query->execute([$campaign_id, $seller_id]);
    $campaign = $campaign_query->fetch(PDO::FETCH_ASSOC);
    
    if (!$campaign) {
        die('Campaign not found.');
    }
}

if (isset($_POST['update_campaign'])) {
    $campaign_name = $_POST['campaign_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $budget = $_POST['budget'];
    $description = $_POST['description'];

    // Update campaign details
    $update_query = $conn->prepare("UPDATE `marketing_campaigns` SET `name` = ?, `start_date` = ?, `end_date` = ?, `budget` = ?, `description` = ? WHERE `id` = ? AND `seller_id` = ?");
    $update_query->execute([$campaign_name, $start_date, $end_date, $budget, $description, $campaign_id, $seller_id]);

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
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="edit-campaign">
    <h1 class="title">Edit Campaign</h1>
    <form action="" method="post">
        <label for="campaign_name">Campaign Name:</label>
        <input type="text" name="campaign_name" id="campaign_name" value="<?= htmlspecialchars($campaign['name']); ?>" required>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($campaign['start_date']); ?>" required>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($campaign['end_date']); ?>" required>
        <label for="budget">Budget (Rs):</label>
        <input type="number" name="budget" id="budget" value="<?= htmlspecialchars($campaign['budget']); ?>" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($campaign['description']); ?></textarea>
        <input type="submit" name="update_campaign" value="Update Campaign" class="btn">
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    </form>
</section>
<script src="js/script.js"></script>
</body>
</html>
