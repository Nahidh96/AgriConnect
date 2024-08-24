<?php
@include '../config.php';
session_start();

$resources_query = $conn->prepare("SELECT * FROM resources ORDER BY created_at DESC");
$resources_query->execute();
$resources = $resources_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resources</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>

<section class="resources">
    <h1 class="title">Educational Resources</h1>
    
    <div class="resource-list">
        <?php foreach ($resources as $resource) { ?>
            <div class="resource">
                <h2><?= $resource['title']; ?></h2>
                <p><?= $resource['description']; ?></p>
                <?php if ($resource['url']) { ?>
                    <a href="<?= $resource['url']; ?>" target="_blank" class="secondary-btn">Learn More</a>
                <?php } ?>
                <small>Added on <?= $resource['created_at']; ?></small>
            </div>
        <?php } ?>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
