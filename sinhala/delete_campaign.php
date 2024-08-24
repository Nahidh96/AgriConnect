<?php
@include '../config.php';
session_start();

$seller_id = $_COOKIE['seller_id'];
if (!isset($seller_id)) {
    header('location:seller_login.php');
}

if (isset($_GET['id'])) {
    $campaign_id = $_GET['id'];
    
    // Delete campaign
    $delete_query = $conn->prepare("DELETE FROM `marketing_campaigns` WHERE `id` = ? AND `seller_id` = ?");
    $delete_query->execute([$campaign_id, $seller_id]);
    
    header('Location: marketing_tools.php');
}
?>
