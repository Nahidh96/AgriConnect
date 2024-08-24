<?php
@include 'config.php';
session_start();

$seller_id = $_COOKIE['seller_id'];
if (!isset($seller_id)) {
    header('location:seller_login.php');
}

// Handle form submission for creating a marketing campaign
if (isset($_POST['create_campaign'])) {
    $campaign_name = $_POST['campaign_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $budget = $_POST['budget'];
    $description = $_POST['description'];

    // Insert new campaign into the database
    $insert_query = $conn->prepare("INSERT INTO `marketing_campaigns` (`seller_id`, `name`, `start_date`, `end_date`, `budget`, `description`) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_query->execute([$seller_id, $campaign_name, $start_date, $end_date, $budget, $description]);

    $message = "Campaign created successfully.";
}

// Fetch existing campaigns
$campaigns_query = $conn->prepare("SELECT * FROM `marketing_campaigns` WHERE `seller_id` = ?");
$campaigns_query->execute([$seller_id]);
$campaigns = $campaigns_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Tools</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<br>
<section class="marketing">
    <h1 class="title">Marketing Tools</h1>

    <!-- Form to Create a New Campaign -->
    <form action="" method="post">
        <h2>Create New Campaign</h2>
        <label for="campaign_name">Campaign Name:</label>
        <input type="text" name="campaign_name" id="campaign_name" required>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" required>
        <label for="budget">Budget (Rs):</label>
        <input type="number" name="budget" id="budget" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>
        <input type="submit" name="create_campaign" value="Create Campaign" class="btn">
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    </form>

    <!-- Display Existing Campaigns -->
    <h2>Existing Campaigns</h2>
    <?php if (count($campaigns) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Budget</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaigns as $campaign) { ?>
                    <tr>
                        <td><?= htmlspecialchars($campaign['name']); ?></td>
                        <td><?= htmlspecialchars($campaign['start_date']); ?></td>
                        <td><?= htmlspecialchars($campaign['end_date']); ?></td>
                        <td>Rs. <?= htmlspecialchars($campaign['budget']); ?>/-</td>
                        <td><?= htmlspecialchars($campaign['description']); ?></td>
                        <td>
                            <!-- Implement Edit and Delete functionality -->
                            <a href="edit_campaign.php?id=<?= htmlspecialchars($campaign['id']); ?>">Edit</a> |
                            <a href="delete_campaign.php?id=<?= htmlspecialchars($campaign['id']); ?>" onclick="return confirm('Are you sure you want to delete this campaign?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No campaigns found.</p>
    <?php } ?>
</section>
<script src="js/script.js"></script>
</body>
</html>
