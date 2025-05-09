<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get latest articles
$db = Database::getInstance();
$latest_articles = $db->query("
    SELECT a.*, u.username, u.profile_image 
    FROM articles a 
    JOIN users u ON a.author_id = u.id 
    WHERE a.status = 'published' 
    ORDER BY a.created_at DESC 
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

// Get featured articles (most viewed/commented)
$featured_articles = $db->query("
    SELECT a.*, u.username, u.profile_image, 
           COUNT(c.id) as comment_count
    FROM articles a 
    JOIN users u ON a.author_id = u.id 
    LEFT JOIN comments c ON a.id = c.article_id
    WHERE a.status = 'published'
    GROUP BY a.id
    ORDER BY comment_count DESC 
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

// Get article categories
$categories = $db->query("
    SELECT category, COUNT(*) as article_count
    FROM articles
    WHERE status = 'published'
    GROUP BY category
    ORDER BY article_count DESC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

// Handle newsletter subscription
$newsletter_success = '';
$newsletter_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = trim($_POST['newsletter_email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Store email in database (you'll need to create a newsletter_subscribers table)
        try {
            $db->query(
                "INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())",
                [$email]
            );
            $newsletter_success = 'Thank you for subscribing to our newsletter!';
        } catch (PDOException $e) {
            $newsletter_error = 'You are already subscribed to our newsletter.';
        }
    } else {
        $newsletter_error = 'Please enter a valid email address.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArticleHub - Professional Article Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Internal CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --background-light: #f9fafb;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-light);
        }

        .hero-section {
            background: var(--primary-color);
            padding: 6rem 0;
            color: white;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-section .btn {
            padding: 0.8rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            background: white;
            color: var(--primary-color);
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .article-card {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .article-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .article-card .card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .article-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .author-image {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .read-more {
            margin-top: auto;
            display: inline-block;
            padding: 0.6rem 1.2rem;
            background: var(--primary-color);
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }

        .category-card {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            height: 100%;
        }

        .category-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .category-count {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 1rem 0;
        }

        .category-card .btn {
            padding: 0.6rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .newsletter-section {
            background: var(--primary-color);
            padding: 4rem 0;
            color: white;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-form input[type="email"] {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .newsletter-form button {
            width: 100%;
            padding: 0.8rem;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }

        .alert {
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        /* Simplified Navigation */
        .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .nav-link {
            color: var(--text-color);
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-shadow: none;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background: var(--background-light);
        }

        /* Simplified Top Bar */
        .top-bar {
            background: var(--text-color);
            padding: 0.5rem 0;
            font-size: 0.875rem;
        }

        .social-links a {
            color: white;
            opacity: 0.8;
            margin-left: 1rem;
        }

        .social-links a:hover {
            opacity: 1;
        }

        /* Grid Layout */
        .article-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 4rem 0;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .section-title h2 {
                font-size: 1.75rem;
            }

            .article-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar bg-dark text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small>
                        <i class="fas fa-envelope me-2"></i>contact@articlehub.com
                        <i class="fas fa-phone ms-3 me-2"></i>+1 234 567 890
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <!-- Brand Card -->
            <div class="card brand-card">
                <div class="card-body p-2">
                    <a class="navbar-brand d-flex align-items-center" href="index.php">
                        <i class="fas fa-newspaper text-primary me-2"></i>
                        <span class="fw-bold">ArticleHub</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Main Navigation -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <div class="card nav-card">
                            <div class="card-body p-2">
                                <a class="nav-link" href="index.php">
                                    <i class="fas fa-home me-1"></i>Home
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="card nav-card">
                            <div class="card-body p-2">
                                <a class="nav-link" href="articles.php">
                                    <i class="fas fa-newspaper me-1"></i>Articles
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <div class="card nav-card">
                            <div class="card-body p-2">
                                <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-th-large me-1"></i>Categories
                                </a>
                                <div class="dropdown-menu">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <a class="dropdown-item" href="articles.php?category=Technology">
                                                <i class="fas fa-laptop me-2"></i>Technology
                                            </a>
                                            <a class="dropdown-item" href="articles.php?category=Business">
                                                <i class="fas fa-briefcase me-2"></i>Business
                                            </a>
                                            <a class="dropdown-item" href="articles.php?category=Science">
                                                <i class="fas fa-flask me-2"></i>Science
                                            </a>
                                            <a class="dropdown-item" href="articles.php?category=Health">
                                                <i class="fas fa-heartbeat me-2"></i>Health
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <div class="card nav-card">
                                <div class="card-body p-2">
                                    <a class="nav-link" href="dashboard.php">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- User Actions -->
                <div class="d-flex align-items-center">
                    <!-- Search Card -->
                    <div class="card search-card me-3">
                        <div class="card-body p-2">
                            <form class="d-flex" action="articles.php" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Search..." name="search">
                                    <button class="btn btn-outline-primary btn-sm" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- User Menu Card -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="card user-card">
                            <div class="card-body p-2">
                                <div class="dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                        <img src="<?php echo htmlspecialchars($_SESSION['profile_image'] ?? 'assets/images/default-avatar.png'); ?>" 
                                             alt="Profile" 
                                             class="rounded-circle me-2" 
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="card">
                                            <div class="card-body p-2">
                                                <a class="dropdown-item" href="profile.php">
                                                    <i class="fas fa-user me-2"></i>Profile
                                                </a>
                                                <a class="dropdown-item" href="create_article.php">
                                                    <i class="fas fa-plus me-2"></i>New Article
                                                </a>
                                                <a class="dropdown-item" href="my_articles.php">
                                                    <i class="fas fa-list me-2"></i>My Articles
                                                </a>
                                                <hr class="dropdown-divider">
                                                <a class="dropdown-item" href="logout.php">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card auth-card">
                            <div class="card-body p-2">
                                <div class="d-flex gap-2">
                                    <a href="login.php" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login
                                    </a>
                                    <a href="register.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-user-plus me-1"></i>Register
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="container">
                <h1>Welcome to ArticleHub</h1>
                <p>Discover and share amazing articles</p>
                <a href="articles.php" class="btn">Browse Articles</a>
            </div>
        </section>

        <section class="featured-section">
            <div class="container">
                <div class="section-title">
                    <h2>Featured Articles</h2>
                    <p>Check out our most popular articles</p>
                </div>
                <div class="article-grid">
                    <?php foreach ($featured_articles as $article): ?>
                        <article class="article-card">
                            <?php if ($article['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <div class="article-meta">
                                <img src="<?php echo htmlspecialchars($article['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($article['username']); ?>" 
                                     class="author-image">
                                <span class="author-name"><?php echo htmlspecialchars($article['username']); ?></span>
                                <span class="article-date"><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">Read More</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="latest-articles">
            <div class="container">
                <div class="section-title">
                    <h2>Latest Articles</h2>
                    <p>Stay up to date with our newest content</p>
                </div>
                <div class="article-grid">
                    <?php foreach ($latest_articles as $article): ?>
                        <article class="article-card">
                            <?php if ($article['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <div class="article-meta">
                                <img src="<?php echo htmlspecialchars($article['profile_image'] ?? 'assets/images/default-profile.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($article['username']); ?>" 
                                     class="author-image">
                                <span class="author-name"><?php echo htmlspecialchars($article['username']); ?></span>
                                <span class="article-date"><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">Read More</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="categories-section">
            <div class="container">
                <div class="section-title">
                    <h2>Popular Categories</h2>
                    <p>Explore articles by category</p>
                </div>
                <div class="article-grid">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-card">
                            <h3><?php echo htmlspecialchars($category['category']); ?></h3>
                            <div class="category-count"><?php echo $category['article_count']; ?></div>
                            <a href="articles.php?category=<?php echo urlencode($category['category']); ?>" class="btn">View Articles</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="newsletter-section">
            <div class="container">
                <div class="section-title">
                    <h2>Subscribe to Our Newsletter</h2>
                    <p>Stay updated with our latest articles and news</p>
                </div>
                <div class="newsletter-form">
                    <?php if ($newsletter_success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($newsletter_success); ?></div>
                    <?php endif; ?>
                    <?php if ($newsletter_error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($newsletter_error); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="index.php">
                        <input type="email" name="newsletter_email" placeholder="Enter your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ArticleHub. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 