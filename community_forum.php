<?php
@include 'config.php';
session_start();

$seller_id = $_SESSION['seller_id'];
if (!isset($seller_id)) {
    header('location:seller_login.php');
    exit;
}

// Verify seller_id exists in users table and is a seller
$seller_check_query = $conn->prepare("SELECT * FROM users WHERE id = ? AND user_type = 'seller'");
$seller_check_query->execute([$seller_id]);
$seller_exists = $seller_check_query->fetch(PDO::FETCH_ASSOC);

if (!$seller_exists) {
    die('Error: Seller does not exist.');
}

if (isset($_POST['create_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $create_post_query = $conn->prepare("INSERT INTO forum_posts (seller_id, title, content) VALUES (?, ?, ?)");
    $create_post_query->execute([$seller_id, $title, $content]);
}

if (isset($_POST['add_comment'])) {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    $add_comment_query = $conn->prepare("INSERT INTO forum_comments (post_id, seller_id, comment) VALUES (?, ?, ?)");
    $add_comment_query->execute([$post_id, $seller_id, $comment]);
}

// Fetch posts and their authors
$posts_query = $conn->prepare("SELECT forum_posts.*, users.name AS seller_name FROM forum_posts JOIN users ON forum_posts.seller_id = users.id WHERE users.user_type = 'seller' ORDER BY forum_posts.created_at DESC");
$posts_query->execute();
$posts = $posts_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'seller_header.php'; ?>
<section class="forum">
    <h1 class="title">Community Forum</h1>
    
    <form action="" method="post" class="create-post">
        <h2>Create a New Post</h2>
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" placeholder="Post Content" required></textarea>
        <input type="submit" name="create_post" value="Create Post" class="btn">
    </form>

    <div class="posts">
        <?php foreach ($posts as $post) { ?>
            <div class="post">
                <h2><?= $post['title']; ?></h2>
                <p><?= $post['content']; ?></p>
                <small>Posted by <?= $post['seller_name']; ?> on <?= $post['created_at']; ?></small>
                
                <div class="comments">
                    <?php
                    // Fetch comments and their authors
                    $comments_query = $conn->prepare("SELECT forum_comments.*, users.name AS seller_name FROM forum_comments JOIN users ON forum_comments.seller_id = users.id WHERE forum_comments.post_id = ? ORDER BY forum_comments.created_at DESC");
                    $comments_query->execute([$post['id']]);
                    $comments = $comments_query->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($comments as $comment) { ?>
                        <div class="comment">
                            <p><?= $comment['comment']; ?></p>
                            <small>Commented by <?= $comment['seller_name']; ?> on <?= $comment['created_at']; ?></small>
                        </div>
                    <?php } ?>

                    <form action="" method="post" class="add-comment">
                        <textarea name="comment" placeholder="Add a comment" required></textarea>
                        <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                        <input type="submit" name="add_comment" value="Add Comment" class="btn">
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<script src="js/script.js"></script>
</body>
</html>
