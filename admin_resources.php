<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

$message = '';

if(isset($_POST['add_resource'])){
   $title = $_POST['title'];
   $description = $_POST['description'];
   $url = $_POST['url'];

   $add_resource_query = $conn->prepare("INSERT INTO resources (title, description, url) VALUES (?, ?, ?)");
   $add_resource_query->execute([$title, $description, $url]);

   $message = 'Resource added successfully!';
}

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_resource_query = $conn->prepare("DELETE FROM resources WHERE id = ?");
   $delete_resource_query->execute([$delete_id]);

   $message = 'Resource deleted successfully!';
}

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
    <title>Admin Resources</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="resources">
    <h1 class="title">Admin Resources</h1>
    
    <form action="" method="post" class="add-resource">
        <h2>Add New Resource</h2>
        <div class="flex">
         <div class="inputBox">
        <input type="text" name="title" placeholder="Resource Title" required>
         </div>
        <div class="inputBox">
        <input type="text" name="url" placeholder="Resource URL">
         </div>
        </div>
        <textarea name="description" placeholder="Resource Description" class="box" required></textarea>
        <input type="submit" name="add_resource" value="Add Resource" class="btn">
    </form>

    <?php if (!empty($message)) { ?>
        <div class="message"><?= $message; ?></div>
    <?php } ?>

    <div class="resource-list">
        <?php foreach ($resources as $resource) { ?>
            <div class="resource">
                <h2><?= $resource['title']; ?></h2>
                <p><?= $resource['description']; ?></p>
                <?php if ($resource['url']) { ?>
                    <a href="<?= $resource['url']; ?>" target="_blank">Learn More</a>
                <?php } ?>
                <small>Added on <?= $resource['created_at']; ?></small>
                <a href="admin_resources.php?delete=<?= $resource['id']; ?>" class="delete-btn" onclick="return confirm('Delete this resource?');">Delete</a>
            </div>
        <?php } ?>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
