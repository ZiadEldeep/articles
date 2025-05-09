<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$db = Database::getInstance();

// Get user details
$user = $db->query(
    "SELECT * FROM users WHERE id = ?",
    [$user_id]
)->fetch(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($email)) {
        $error = 'Please fill in all required fields';
    } else {
        // Check if username is changed and already exists
        if ($username !== $user['username']) {
            $stmt = $db->query("SELECT id FROM users WHERE username = ? AND id != ?", [$username, $user_id]);
            if ($stmt->fetch()) {
                $error = 'Username already exists';
            }
        }

        // Check if email is changed and already exists
        if ($email !== $user['email']) {
            $stmt = $db->query("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $user_id]);
            if ($stmt->fetch()) {
                $error = 'Email already exists';
            }
        }

        // Handle password change
        if (!empty($current_password)) {
            if (!password_verify($current_password, $user['password'])) {
                $error = 'Current password is incorrect';
            } elseif (empty($new_password) || empty($confirm_password)) {
                $error = 'Please fill in both new password fields';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match';
            } elseif (strlen($new_password) < 6) {
                $error = 'New password must be at least 6 characters long';
            }
        }

        if (empty($error)) {
            // Handle profile image upload
            $profile_image = $user['profile_image'];
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $new_image = upload_profile_image($_FILES['profile_image'], $user_id);
                if ($new_image) {
                    // Delete old image if exists
                    if ($profile_image && file_exists($profile_image)) {
                        unlink($profile_image);
                    }
                    $profile_image = $new_image;
                }
            }

            // Update user
            $params = [$username, $email, $profile_image];
            $sql = "UPDATE users SET username = ?, email = ?, profile_image = ?";
            
            if (!empty($current_password)) {
                $sql .= ", password = ?";
                $params[] = password_hash($new_password, PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $user_id;
            
            $db->query($sql, $params);

            // Update session
            $_SESSION['username'] = $username;

            $success = 'Profile updated successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - ArticleHub</title>
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container" style="max-width: 600px; margin: 50px auto;">
            <h1>Edit Profile</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="edit_profile.php" enctype="multipart/form-data" class="profile-form">
                <div class="profile-image-section">
                    <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                         alt="Profile Image" 
                         class="profile-image-preview">
                    <div class="form-group">
                        <label for="profile_image">Change Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                    <p class="help-text">Leave empty to keep current password</p>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ArticleHub. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .profile-image-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid #007bff;
        }

        .help-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>

    <script>
        // Preview profile image before upload
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-image-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html> 