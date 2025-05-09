<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in and is an author/admin
if (!isset($_SESSION['user_id']) || (!is_author() && !is_admin())) {
    header('Location: login.php');
    exit();
}

$db = Database::getInstance();
$message = '';

// Handle article deletion
if (isset($_POST['delete_article'])) {
    $article_id = (int)$_POST['article_id'];
    try {
        $db->query("DELETE FROM articles WHERE id = ? AND (author_id = ? OR ? IN (SELECT id FROM users WHERE role = 'admin'))", 
            [$article_id, $_SESSION['user_id'], $_SESSION['user_id']]);
        $message = 'Article deleted successfully!';
    } catch (PDOException $e) {
        $message = 'Error deleting article: ' . $e->getMessage();
    }
}

// Get all articles for the current user
$articles = $db->query(
    "SELECT a.*, u.username 
     FROM articles a 
     JOIN users u ON a.author_id = u.id 
     WHERE a.author_id = ? OR ? IN (SELECT id FROM users WHERE role = 'admin')
     ORDER BY a.created_at DESC",
    [$_SESSION['user_id'], $_SESSION['user_id']]
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles - ArticleHub</title>
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
                    <li><a href="manage_articles.php">Manage Articles</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="manage-articles-header">
                <h1>Manage Articles</h1>
                <a href="create_article.php" class="btn">Create New Article</a>
            </div>

            <?php if ($message): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="articles-table">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($article['title']); ?></td>
                                <td><?php echo htmlspecialchars($article['category']); ?></td>
                                <td><?php echo htmlspecialchars($article['status']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($article['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                        <button type="submit" name="delete_article" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <style>
        .manage-articles-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .articles-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</body>
</html> 