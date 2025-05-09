<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page_title = 'Articles';
$db = Database::getInstance();

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'published';

// Build the query
$query = "SELECT a.*, u.username as author_name, u.profile_image as author_image
          FROM articles a 
          JOIN users u ON a.author_id = u.id 
          WHERE 1=1";

$params = [];

if ($category) {
    $query .= " AND a.category = :category";
    $params[':category'] = $category;
}

if ($search) {
    $query .= " AND (a.title LIKE :search OR a.content LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($status) {
    $query .= " AND a.status = :status";
    $params[':status'] = $status;
}

$query .= " ORDER BY a.created_at DESC";

// Get all articles
$stmt = $db->prepare($query);
$stmt->execute($params);
$articles = $stmt->fetchAll();

// Get all categories for the filter
$categories = $db->query("SELECT DISTINCT category FROM articles ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<div class="bg-light">
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Explore Articles</h1>
                    <p class="lead mb-4">Discover insightful content across various categories and topics</p>
                    <form method="GET" class="d-flex">
                        <div class="input-group input-group-lg shadow">
                            <input type="text" class="form-control border-0" name="search" 
                                   placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="assets/Screenshot 2025-03-18 014707.png" alt="Articles" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Categories</h5>
                        <div class="list-group list-group-flush">
                            <a href="?status=<?php echo $status; ?>" 
                               class="list-group-item list-group-item-action d-flex align-items-center <?php echo !$category ? 'active' : ''; ?>">
                                <i class="fas fa-th-large me-2"></i>All Categories
                            </a>
                            <?php foreach ($categories as $cat): ?>
                                <a href="?category=<?php echo urlencode($cat); ?>&status=<?php echo $status; ?>" 
                                   class="list-group-item list-group-item-action d-flex align-items-center <?php echo $category === $cat ? 'active' : ''; ?>">
                                    <i class="fas fa-folder me-2"></i><?php echo htmlspecialchars($cat); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Filter</h5>
                        <form method="GET" class="filter-form">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>
                                        Published
                                    </option>
                                    <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>
                                        Draft
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="col-lg-9">
                <?php if (empty($articles)): ?>
                    <div class="card shadow-sm text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-newspaper fa-4x text-muted mb-4"></i>
                            <h3>No articles found</h3>
                            <p class="text-muted">Try adjusting your search or filter criteria</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php foreach ($articles as $article): ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <?php if ($article['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                                             class="card-img-top" alt="<?php echo htmlspecialchars($article['title']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-light text-primary">
                                                <i class="fas fa-folder me-1"></i>
                                                <?php echo htmlspecialchars($article['category']); ?>
                                            </span>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
                                            </small>
                                        </div>
                                        <h5 class="card-title">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </h5>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="<?php echo htmlspecialchars($article['author_image'] ?? 'assets/images/default-avatar.png'); ?>" 
                                                 alt="<?php echo htmlspecialchars($article['author_name']); ?>"
                                                 class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                            <small class="text-muted">
                                                By <?php echo htmlspecialchars($article['author_name']); ?>
                                            </small>
                                        </div>
                                        <p class="card-text text-muted flex-grow-1">
                                            <?php echo substr(strip_tags($article['content']), 0, 150) . '...'; ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <a href="article.php?id=<?php echo $article['id']; ?>" 
                                               class="btn btn-primary">
                                                <i class="fas fa-eye me-2"></i>Read More
                                            </a>
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $article['author_id']): ?>
                                                <div class="btn-group">
                                                    <a href="edit_article.php?id=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="delete_article.php?id=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-danger"
                                                       onclick="return confirm('Are you sure you want to delete this article?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 