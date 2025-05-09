/* Custom styles for article website */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #3498db;
    --success-color: #2ecc71;
    --danger-color: #e74c3c;
    --warning-color: #f1c40f;
    --light-color: #ecf0f1;
    --dark-color: #2c3e50;
    --text-color: #2c3e50;
    --text-light: #7f8c8d;
    --border-color: #e0e0e0;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: #f8f9fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
    font-weight: 600;
    color: var(--primary-color);
}

/* Header and Navigation */
.navbar {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 0.8rem 0;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-brand {
    font-size: 1.5rem;
}

.navbar-brand i {
    font-size: 1.8rem;
}

.nav-link {
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    color: var(--bs-dark) !important;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: var(--bs-primary) !important;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: var(--bs-primary);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 100%;
}

.navbar-dark .navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.85);
}

.navbar-dark .navbar-nav .nav-link:hover {
    color: #fff;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.5rem;
    margin-top: 0.5rem;
}

.dropdown-item {
    padding: 0.6rem 1rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: var(--bs-primary);
    color: white;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.navbar-toggler {
    border: none;
    padding: 0.5rem;
}

.navbar-toggler:focus {
    box-shadow: none;
}

/* Article Cards */
.article-card {
    transition: var(--transition);
    border: none;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.article-card .card-img-top {
    height: 200px;
    object-fit: cover;
}

.article-card .card-body {
    padding: 1.5rem;
}

.article-card .card-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.article-card .card-text {
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Category Badges */
.badge {
    padding: 0.5em 1em;
    font-weight: 500;
    border-radius: 20px;
}

.badge.bg-primary {
    background-color: var(--accent-color) !important;
}

/* Buttons */
.btn {
    padding: 0.5rem 1.5rem;
    border-radius: 20px;
    font-weight: 500;
    transition: var(--transition);
}

.btn-primary {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.btn-outline-primary {
    border-color: #e0e0e0;
    color: var(--bs-primary);
}

.btn-outline-primary:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

/* Search and Filter Section */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
}

.input-group {
    border-radius: 20px;
    overflow: hidden;
}

.form-control {
    border: 1px solid #e0e0e0;
    padding: 0.4rem 1rem;
}

.form-control:focus {
    box-shadow: none;
    border-color: var(--bs-primary);
}

/* Category Quick Links */
.d-flex.flex-wrap.gap-2 {
    gap: 0.5rem !important;
}

.btn-outline-primary.active {
    background-color: var(--accent-color);
    color: white;
}

/* Article Meta Information */
.article-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: var(--text-light);
}

.article-meta i {
    color: var(--accent-color);
}

/* Footer */
.footer {
    background-color: var(--primary-color);
    color: white;
    padding: 3rem 0;
    margin-top: 3rem;
}

.social-links a {
    font-size: 0.9rem;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1.25rem;
    }
    
    .article-card .card-img-top {
        height: 150px;
    }
    
    .btn {
        padding: 0.4rem 1rem;
    }
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }
    
    .nav-link::after {
        display: none;
    }

    .dropdown-menu {
        border: none;
        box-shadow: none;
        margin-top: 0;
    }

    .user-menu {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.article-card {
    animation: fadeIn 0.5s ease-out;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--light-color);
}

::-webkit-scrollbar-thumb {
    background: var(--accent-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2980b9;
}

/* Loading State */
.loading {
    position: relative;
    min-height: 200px;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px;
    height: 40px;
    border: 4px solid var(--light-color);
    border-top-color: var(--accent-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Main content */
.main-content {
    flex: 1;
    padding: 2rem 0;
}

/* Form styles */
.form-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Alert styles */
.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: var(--success-color);
    color: var(--white);
}

.alert-danger {
    background-color: var(--danger-color);
    color: var(--white);
}

.alert-warning {
    background-color: var(--warning-color);
    color: var(--text-color);
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: var(--white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow);
}

th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    background-color: var(--light-bg);
    font-weight: 600;
}

/* Grid Layout */
.article-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

/* Utility Classes */
.text-center {
    text-align: center;
}

.mt-1 { margin-top: 1rem; }
.mt-2 { margin-top: 2rem; }
.mt-3 { margin-top: 3rem; }

.mb-1 { margin-bottom: 1rem; }
.mb-2 { margin-bottom: 2rem; }
.mb-3 { margin-bottom: 3rem; }

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-published {
    background-color: var(--success-color);
    color: var(--white);
}

.status-draft {
    background-color: var(--warning-color);
    color: var(--text-color);
}

/* Hero Section */
.hero-section {
    margin-top: 60px;
}

.hero-section h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero-section p {
    font-size: 1.2rem;
}

.read-more {
    display: block;
    padding: 1rem;
    text-align: center;
    background: #007bff;
    color: white;
    text-decoration: none;
    transition: background 0.3s;
}

.read-more:hover {
    background: #0056b3;
}

/* Top Bar Styles */
.top-bar {
    font-size: 0.875rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.top-bar a {
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.top-bar a:hover {
    opacity: 0.8;
}

/* User Menu Styles */
.user-menu {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--bs-primary);
}

/* Navbar Card Styles */
.brand-card,
.nav-card,
.search-card,
.user-card,
.auth-card {
    border: none;
    background: transparent;
    transition: all 0.3s ease;
}

.brand-card:hover,
.nav-card:hover,
.search-card:hover,
.user-card:hover,
.auth-card:hover {
    transform: translateY(-2px);
}

.brand-card .card-body,
.nav-card .card-body,
.search-card .card-body,
.user-card .card-body,
.auth-card .card-body {
    padding: 0.5rem;
}

/* Brand Card */
.brand-card {
    background: linear-gradient(45deg, var(--bs-primary), #0056b3);
    border-radius: 8px;
}

.brand-card .navbar-brand {
    color: white !important;
}

.brand-card .navbar-brand i {
    font-size: 1.8rem;
}

/* Navigation Cards */
.nav-card {
    border-radius: 8px;
    margin: 0 0.25rem;
}

.nav-card:hover {
    background: rgba(var(--bs-primary-rgb), 0.1);
}

.nav-card .nav-link {
    color: var(--bs-dark) !important;
    padding: 0.5rem 1rem !important;
}

.nav-card:hover .nav-link {
    color: var(--bs-primary) !important;
}

/* Search Card */
.search-card {
    border-radius: 20px;
    background: rgba(var(--bs-primary-rgb), 0.05);
}

.search-card .input-group {
    border-radius: 20px;
    overflow: hidden;
}

.search-card .form-control {
    border: none;
    background: transparent;
}

.search-card .btn {
    border: none;
    background: transparent;
    color: var(--bs-primary);
}

/* User Card */
.user-card {
    border-radius: 20px;
    background: rgba(var(--bs-primary-rgb), 0.05);
}

.user-card .dropdown-toggle::after {
    display: none;
}

.user-card .dropdown-menu {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0;
    margin-top: 0.5rem;
}

.user-card .dropdown-menu .card {
    border: none;
    border-radius: 8px;
}

.user-card .dropdown-item {
    padding: 0.75rem 1rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.user-card .dropdown-item:hover {
    background: var(--bs-primary);
    color: white;
    transform: translateX(5px);
}

/* Auth Card */
.auth-card {
    border-radius: 20px;
    background: rgba(var(--bs-primary-rgb), 0.05);
}

.auth-card .btn {
    border-radius: 20px;
    padding: 0.4rem 1rem;
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .brand-card,
    .nav-card,
    .search-card,
    .user-card,
    .auth-card {
        margin: 0.25rem 0;
    }

    .navbar-collapse {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }

    .user-card .dropdown-menu {
        position: static !important;
        transform: none !important;
        box-shadow: none;
    }
} 