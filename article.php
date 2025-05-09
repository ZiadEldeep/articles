<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if article ID is provided
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$article_id = (int)$_GET['id'];
$article = get_article($article_id);

if (!$article) {
    header('Location: index.php');
    exit;
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        add_comment($article_id, $_SESSION['user_id'], $comment);
    }
}

// Get article comments
$comments = get_article_comments($article_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - ArticleHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">ArticleHub</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="articles.php">Articles</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container" style="max-width: 800px;">
            <article class="article-full">
                <?php if ($article['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($article['title']); ?>" 
                         class="article-image">
                <?php endif; ?>

                <h1><?php echo htmlspecialchars($article['title']); ?></h1>

                <div class="article-meta">
                    <img src="<?php echo htmlspecialchars($article['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($article['username']); ?>" 
                         class="author-image">
                    <span class="author-name"><?php echo htmlspecialchars($article['username']); ?></span>
                    <span class="article-date"><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                </div>

                <div class="article-content">
                    <?php echo $article['content']; ?>
                </div>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $article['author_id'] || is_admin())): ?>
                    <div class="article-actions">
                        <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn">Edit Article</a>
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this article?')">Delete Article</a>
                    </div>
                <?php endif; ?>
            </article>

            <section class="comments-section">
                <h2>Comments</h2>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <textarea name="comment" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn">Post Comment</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="login.php">login</a> to post a comment.</p>
                <?php endif; ?>

                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <img src="<?php echo htmlspecialchars($comment['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($comment['username']); ?>" 
                                     class="comment-author-image">
                                <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                <span class="comment-date"><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div class="comment-content">
                                <?php echo htmlspecialchars($comment['content']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ArticleHub. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .article-full {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .article-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .article-content {
            margin-top: 2rem;
            line-height: 1.8;
        }

        .comments-section {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .comment-form textarea {
            width: 100%;
            min-height: 100px;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .comments-list {
            margin-top: 2rem;
        }

        .comment {
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
        }

        .comment:last-child {
            border-bottom: none;
        }

        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .comment-author-image {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .comment-author {
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .comment-date {
            color: #666;
            font-size: 0.9rem;
        }

        .comment-content {
            margin-left: 2.5rem;
        }
    </style>
</body>
</html> 