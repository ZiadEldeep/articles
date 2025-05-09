<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if article ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$article_id = (int)$_GET['id'];
$db = Database::getInstance();

// Get article details
$article = $db->query(
    "SELECT * FROM articles WHERE id = ?",
    [$article_id]
)->fetch(PDO::FETCH_ASSOC);

// Check if article exists and user has permission to delete
if (!$article || ($article['author_id'] !== $_SESSION['user_id'] && !is_admin())) {
    header('Location: dashboard.php');
    exit;
}

// Delete article image if exists
if ($article['image_path'] && file_exists($article['image_path'])) {
    unlink($article['image_path']);
}

// Delete article
$db->query("DELETE FROM articles WHERE id = ?", [$article_id]);

// Redirect to dashboard
header('Location: dashboard.php');
exit; 