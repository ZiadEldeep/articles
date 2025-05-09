<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    header('Location: ../login.php');
    exit;
}

$db = Database::getInstance();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        
        switch ($_POST['action']) {
            case 'delete':
                $db->query("DELETE FROM users WHERE id = ?", [$user_id]);
                break;
            case 'ban':
                $db->query("UPDATE users SET role = 'banned' WHERE id = ?", [$user_id]);
                break;
            case 'unban':
                $db->query("UPDATE users SET role = 'user' WHERE id = ?", [$user_id]);
                break;
            case 'make_author':
                $db->query("UPDATE users SET role = 'author' WHERE id = ?", [$user_id]);
                break;
        }
    }
}

// Get all users
$users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ArticleHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="../index.php" class="logo">ArticleHub</a>
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../articles.php">Articles</a></li>
                    <li><a href="../dashboard.php">Dashboard</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h1>Admin Panel</h1>
            
            <div class="admin-section">
                <h2>User Management</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        
                                        <?php if ($user['role'] !== 'admin'): ?>
                                            <?php if ($user['role'] === 'banned'): ?>
                                                <button type="submit" name="action" value="unban" class="btn btn-sm">Unban</button>
                                            <?php else: ?>
                                                <button type="submit" name="action" value="ban" class="btn btn-sm btn-warning">Ban</button>
                                            <?php endif; ?>
                                            
                                            <?php if ($user['role'] !== 'author'): ?>
                                                <button type="submit" name="action" value="make_author" class="btn btn-sm">Make Author</button>
                                            <?php endif; ?>
                                            
                                            <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-section">
                <h2>Site Statistics</h2>
                <div class="stats-grid">
                    <?php
                    $total_users = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
                    $total_articles = $db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
                    $total_comments = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
                    ?>
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <p><?php echo $total_users; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Articles</h3>
                        <p><?php echo $total_articles; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Comments</h3>
                        <p><?php echo $total_comments; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ArticleHub. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.5rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        .admin-table th {
            background-color: #f5f5f5;
        }

        .admin-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            margin-bottom: 0.5rem;
            color: #666;
        }

        .stat-card p {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</body>
</html> 