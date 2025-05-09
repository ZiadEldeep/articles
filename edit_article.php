<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = Database::getInstance();
$error = '';
$success = '';

// Get article ID from URL
$article_id = $_GET['id'] ?? 0;

// Fetch article data
try {
    $stmt = $db->prepare("
        SELECT * FROM articles 
        WHERE id = ? AND author_id = ?
    ");
    $stmt->execute([$article_id, $_SESSION['user_id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        header('Location: articles.php');
        exit();
    }
} catch (PDOException $e) {
    $error = 'Error fetching article: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $image_path = $article['image_path'];

    // Validate input
    if (empty($title) || empty($content) || empty($category)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'assets/images/articles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_extension, $allowed_extensions)) {
                $file_name = uniqid() . '.' . $file_extension;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    // Delete old image if exists
                    if ($image_path && file_exists($image_path)) {
                        unlink($image_path);
                    }
                    $image_path = $target_path;
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Invalid file type. Allowed types: ' . implode(', ', $allowed_extensions);
            }
        }

        if (empty($error)) {
            try {
                $stmt = $db->prepare("
                    UPDATE articles 
                    SET title = ?, content = ?, category = ?, status = ?, image_path = ?, updated_at = NOW()
                    WHERE id = ? AND author_id = ?
                ");
                
                $stmt->execute([
                    $title,
                    $content,
                    $category,
                    $status,
                    $image_path,
                    $article_id,
                    $_SESSION['user_id']
                ]);

                $success = 'Article updated successfully!';
                header('Location: articles.php');
                exit();
            } catch (PDOException $e) {
                $error = 'Error updating article: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - ArticleHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="form-container">
            <h1>Edit Article</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="article-form">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo htmlspecialchars($article['title']); ?>">
                </div>

                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="Web Development" <?php echo $article['category'] === 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
                        <option value="Technology" <?php echo $article['category'] === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                        <option value="Lifestyle" <?php echo $article['category'] === 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
                        <option value="Marketing" <?php echo $article['category'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                        <option value="Health" <?php echo $article['category'] === 'Health' ? 'selected' : ''; ?>>Health</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" rows="10" required><?php 
                        echo htmlspecialchars($article['content']); 
                    ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Featured Image</label>
                    <?php if ($article['image_path']): ?>
                        <div class="current-image">
                            <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Current featured image" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft" <?php echo $article['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $article['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Article</button>
                    <a href="articles.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 