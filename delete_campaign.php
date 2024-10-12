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
$select = $conn->prepare("SELECT id, user_type FROM `users` WHERE id = ?")
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['user_type'] !== 'seller') {
    header('Location: login.php');
    exit();
}

$seller_id = $user['id'];

if (isset($_GET['id'])) {
    $campaign_id = $_GET['id'];

    // Delete campaign
    $delete_query = $conn->prepare("DELETE FROM `campaigns` WHERE `id` = ? AND `seller_id` = ?");
    $delete_query->execute([$campaign_id, $seller_id]);

    header('Location: marketing_tools.php');
    exit();
} else {
    die('No campaign ID provided.');
}
?>