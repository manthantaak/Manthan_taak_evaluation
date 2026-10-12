<?php
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$statement = $conn->query(
    'SELECT photographid, photo_title, photographer_name, category, description, image
     FROM photographs ORDER BY created_at DESC, photographid DESC'
);
$photographs = $statement->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Gallery';
require __DIR__ . '/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 page-heading">
    <div>
        <p class="section-label mb-2">Photography portfolio</p>
        <h1 class="h2 mb-1">Hello, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="mb-0">A collection of moments, places, and people.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="add_photograph.php">Add Photograph</a>
        <a class="btn btn-outline-secondary" href="manage_photographs.php">Manage Photographs</a>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-sm btn-outline-success" href="export_excel.php">Download Excel (CSV)</a>
    <a class="btn btn-sm btn-outline-danger" href="export_pdf.php">Download PDF</a>
    <a class="btn btn-sm btn-outline-secondary" href="user_management.php">User Management</a>
    <a class="btn btn-sm btn-outline-secondary" href="ui_components.php">UI Components</a>
</div>

<?php if (!$photographs): ?>
    <div class="card content-card">
        <div class="card-body p-5 text-center">
            <h2 class="h4">Your gallery is ready</h2>
            <p class="text-muted-custom">There are no photographs yet. Add the first one to get started.</p>
            <a class="btn btn-primary" href="add_photograph.php">Add your first photograph</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($photographs as $photograph): ?>
            <div class="col-sm-6 col-lg-4">
                <article class="card photo-card">
                    <img src="<?= htmlspecialchars($photograph['image'], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= htmlspecialchars($photograph['photo_title'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="card-body d-flex flex-column">
                        <p class="photo-category mb-2"><?= htmlspecialchars($photograph['category'], ENT_QUOTES, 'UTF-8') ?></p>
                        <h2 class="h5"><?= htmlspecialchars($photograph['photo_title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="text-muted-custom small mb-2">Photographed by <?= htmlspecialchars($photograph['photographer_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="card-text mb-0"><?= nl2br(htmlspecialchars($photograph['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
