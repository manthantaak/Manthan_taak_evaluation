<?php
require_once __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' | ' : '' ?>Lens & Light</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark site-nav">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= isset($_SESSION['user_id']) ? 'dashboard.php' : 'login.php' ?>">Lens &amp; Light</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="dashboard.php">Gallery</a>
                    <a class="nav-link" href="add_photograph.php">Add Photograph</a>
                    <a class="nav-link" href="manage_photographs.php">Manage Photographs</a>
                    <a class="nav-link" href="user_management.php">Users</a>
                    <a class="nav-link" href="ui_components.php">UI Components</a>
                    <a class="btn btn-sm btn-outline-light ms-lg-2" href="logout.php">Log out</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="btn btn-sm btn-light ms-lg-2" href="register.php">Create account</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="container page-wrap">
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
