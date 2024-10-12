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

$seller_id = $user_id; // Use the authenticated seller_id

// Handle creating a new post
if (isset($_POST['create_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Sanitize inputs
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    $create_post_query = $conn->prepare("INSERT INTO forum_posts (seller_id, title, content) VALUES (?, ?, ?)");
    if ($create_post_query->execute([$seller_id, $title, $content])) {
        $message = "Post created successfully.";
    } else {
        $message = "Error creating post.";
    }
}

// Handle adding a comment
if (isset($_POST['add_comment'])) {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    // Sanitize inputs
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

    $add_comment_query = $conn->prepare("INSERT INTO forum_comments (post_id, seller_id, comment) VALUES (?, ?, ?)");
    if ($add_comment_query->execute([$post_id, $seller_id, $comment])) {
        $message = "Comment added successfully.";
    } else {
        $message = "Error adding comment.";
    }
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
    
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <form action="" method="post" class="create-post">
        <h2>Create a New Post</h2>
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" placeholder="Post Content" required></textarea>
        <input type="submit" name="create_post" value="Create Post" class="btn">
    </form>

    <div class="posts">
        <?php foreach ($posts as $post) { ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']); ?></h2>
                <p><?= htmlspecialchars($post['content']); ?></p>
                <small>Posted by <?= htmlspecialchars($post['seller_name']); ?> on <?= htmlspecialchars($post['created_at']); ?></small>
                
                <div class="comments">
                    <?php
                    // Fetch comments and their authors
                    $comments_query = $conn->prepare("SELECT forum_comments.*, users.name AS seller_name FROM forum_comments JOIN users ON forum_comments.seller_id = users.id WHERE forum_comments.post_id = ? ORDER BY forum_comments.created_at DESC");
                    $comments_query->execute([$post['id']]);
                    $comments = $comments_query->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($comments as $comment) { ?>
                        <div class="comment">
                            <p><?= htmlspecialchars($comment['comment']); ?></p>
                            <small>Commented by <?= htmlspecialchars($comment['seller_name']); ?> on <?= htmlspecialchars($comment['created_at']); ?></small>
                        </div>
                    <?php } ?>

                    <form action="" method="post" class="add-comment">
                        <textarea name="comment" placeholder="Add a comment" required></textarea>
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']); ?>">
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