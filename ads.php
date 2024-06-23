<?php

// Include your configuration and database connection file here
@include 'config.php';

function display_ads() {
    global $conn;

    // Example query to fetch a random ad (you can modify this query based on your requirements)
    $select_ad = $conn->query("SELECT * FROM ads ORDER BY RAND() LIMIT 1");
    $ad = $select_ad->fetch(PDO::FETCH_ASSOC);

    if ($ad) {
        // HTML structure for the ad
        echo '<div id="adContainer" class="ad-container">';
        echo '<div class="ad-box">';
        echo '<span id="closeAd" class="close-icon"><i class="fas fa-times-circle"></i></span>';
        echo '<img src="uploaded_img/' . $ad['image'] . '" alt="' . $ad['title'] . '" class="ad-image">';
        echo '<div class="ad-title">' . $ad['title'] . '</div>';
        echo '<a href="' . $ad['link'] . '" target="_blank" class="ad-link">Visit Now</a>';
        echo '</div>';
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ad</title>
<style>
    /* Global styles */
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Ad styles */
    .ad-container {
        display: none; /* Initially hide the ad */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 20px;
        z-index: 9999; /* Ensure ad is above other content */
        max-width: 80%; /* Adjust width if needed */
    }

    .ad-box {
        text-align: center;
        position: relative;
    }

    .ad-image {
        max-width: 100%;
        border-radius: 10px;
        /* Ensure image fits within container */
        max-height: 300px; /* Adjust height if needed */
        object-fit: contain;
    }

    .ad-title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    .ad-link {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 16px;
        background-color: #4CAF50;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .ad-link:hover {
        background-color: #45a049;
    }

    .close-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 20px;
        color: #888888;
    }

    .close-icon:hover {
        color: #555555;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Display the ad -->
    <?php display_ads(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get close button element
    var closeBtn = document.getElementById('closeAd');

    // Get ad container element
    var adContainer = document.getElementById('adContainer');

    // Add click event listener to close button
    closeBtn.addEventListener('click', function() {
        adContainer.style.display = 'none'; // Hide ad container
    });

    // Show ad container when page loads
    adContainer.style.display = 'block';
});
</script>

</body>
</html>
