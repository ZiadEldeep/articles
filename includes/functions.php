<?php
/**
 * Sanitize user input
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Upload profile image
 */
function upload_profile_image($file, $user_id) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }

    $upload_dir = PROFILE_IMAGE_PATH;
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = $user_id . '_' . uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return 'assets/uploads/profiles/' . $file_name;
    }

    return false;
}

/**
 * Post to social media
 */
function postToSocialMedia($article_id, $title) {
    $db = Database::getInstance();
    $article_url = SITE_URL . '/article.php?id=' . $article_id;
    $message = "Check out our new article: " . $title . " at " . $article_url;

    // Post to Facebook
    if (FACEBOOK_APP_ID && FACEBOOK_APP_SECRET) {
        try {
            // Facebook API implementation
            // This is a placeholder - you'll need to implement the actual Facebook API integration
            $db->query(
                "INSERT INTO social_media_posts (article_id, platform, post_id) VALUES (?, 'facebook', ?)",
                [$article_id, 'facebook_post_id']
            );
        } catch (Exception $e) {
            error_log("Facebook post failed: " . $e->getMessage());
        }
    }

    // Post to Twitter
    if (TWITTER_API_KEY && TWITTER_API_SECRET) {
        try {
            // Twitter API implementation
            // This is a placeholder - you'll need to implement the actual Twitter API integration
            $db->query(
                "INSERT INTO social_media_posts (article_id, platform, post_id) VALUES (?, 'twitter', ?)",
                [$article_id, 'twitter_post_id']
            );
        } catch (Exception $e) {
            error_log("Twitter post failed: " . $e->getMessage());
        }
    }
}

/**
 * Check if user is admin
 */
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if user is author
 */
function is_author() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'author');
}

/**
 * Get user's articles
 */
function get_user_articles($user_id) {
    $db = Database::getInstance();
    return $db->query(
        "SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC",
        [$user_id]
    )->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get article by ID
 */
function get_article($id) {
    $db = Database::getInstance();
    $stmt = $db->query(
        "SELECT a.*, u.username, u.profile_image 
         FROM articles a 
         JOIN users u ON a.author_id = u.id 
         WHERE a.id = ?",
        [$id]
    );
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get article comments
 */
function get_article_comments($article_id) {
    $db = Database::getInstance();
    return $db->query(
        "SELECT c.*, u.username, u.profile_image 
         FROM comments c 
         JOIN users u ON c.user_id = u.id 
         WHERE c.article_id = ? 
         ORDER BY c.created_at DESC",
        [$article_id]
    )->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Add comment to article
 */
function add_comment($article_id, $user_id, $content) {
    $db = Database::getInstance();
    return $db->query(
        "INSERT INTO comments (article_id, user_id, content) VALUES (?, ?, ?)",
        [$article_id, $user_id, $content]
    );
}

/**
 * Delete article
 */
function delete_article($article_id, $user_id) {
    $db = Database::getInstance();
    
    // Check if user is admin or article owner
    if (is_admin() || is_article_owner($article_id, $user_id)) {
        return $db->query("DELETE FROM articles WHERE id = ?", [$article_id]);
    }
    
    return false;
}

/**
 * Check if user is article owner
 */
function is_article_owner($article_id, $user_id) {
    $db = Database::getInstance();
    $stmt = $db->query(
        "SELECT id FROM articles WHERE id = ? AND author_id = ?",
        [$article_id, $user_id]
    );
    return $stmt->fetch() !== false;
} 