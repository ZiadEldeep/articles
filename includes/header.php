<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Article Management System'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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

    <!-- Main Content Container -->
    <main class="container py-4">
        <div class="container py-4">

<style>
    .navbar {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 1rem 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links li {
        margin-left: 1.5rem;
    }

    .nav-links a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .nav-links a:hover {
        color: #007bff;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .nav-links {
            margin-top: 1rem;
        }

        .nav-links li {
            margin: 0 0.75rem;
        }
    }
</style> 