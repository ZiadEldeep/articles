<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get user's articles
$articles = $db->query(
    "SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC",
    [$user_id]
)->fetchAll(PDO::FETCH_ASSOC);

// Get user's profile
$user = $db->query(
    "SELECT * FROM users WHERE id = ?",
    [$user_id]
)->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ArticleHub</title>
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
                    <li><a href="create_article.php">New Article</a></li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="admin/">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="dashboard-header">
                <div class="profile-section">
                    <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                         alt="Profile Image" 
                         class="profile-image">
                    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
                    <a href="edit_profile.php" class="btn">Edit Profile</a>
                </div>
            </div>

            <div class="dashboard-content">
                <section class="my-articles">
                    <h2>My Articles</h2>
                    <a href="create_article.php" class="btn">Create New Article</a>
                    
                    <div class="article-grid">
                        <?php foreach ($articles as $article): ?>
                            <article class="article-card">
                                <?php if ($article['image_path']): ?>
                                    <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                                         alt="<?php echo htmlspecialchars($article['title']); ?>">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <div class="article-meta">
                                    <span class="article-status">Status: <?php echo htmlspecialchars($article['status']); ?></span>
                                    <span class="article-date"><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                                </div>
                                <div class="article-actions">
                                    <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn">Edit</a>
                                    <a href="delete_article.php?id=<?php echo $article['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ArticleHub. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html> 