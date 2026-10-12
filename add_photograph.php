<?php
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$title = '';
$photographerName = '';
$category = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['photo_title']);
    $photographerName = trim($_POST['photographer_name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);

    if ($title == '' || $photographerName == '' || $category == '' || $description == '') {
        $errors[] = 'Please fill in all fields.';
    }
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Choose a photograph to upload.';
    } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        $errors[] = 'The image must be smaller than 5 MB.';
    } else {
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) || !$imageInfo) {
            $errors[] = 'Choose a valid JPG, PNG, or WEBP image.';
        }
    }

    if (!$errors) {
        $imagePath = null;
        try {
            $folder = 'uploads/photographs/';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $fileName = uniqid() . '.' . $extension;
            $imagePath = $folder . $fileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                throw new RuntimeException('The image could not be uploaded.');
            }

            $userId = (int) $_SESSION['user_id'];
            $statement = $conn->prepare('INSERT INTO photographs (user_id, photo_title, photographer_name, category, description, image) VALUES (?, ?, ?, ?, ?, ?)');
            $statement->bind_param('isssss', $userId, $title, $photographerName, $category, $description, $imagePath);
            $statement->execute();
            $_SESSION['flash_message'] = 'Your photograph was added to the gallery.';
            $_SESSION['flash_type'] = 'success';
            header('Location: dashboard.php');
            exit;
        } catch (RuntimeException $error) {
            $errors[] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            if ($imagePath) {
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $errors[] = 'The photograph could not be saved. Please try again.';
        }
    }
}

$pageTitle = 'Add Photograph';
require __DIR__ . '/header.php';
?>
<div class="page-heading">
    <p class="section-label mb-2">New entry</p>
    <h1 class="h2">Add a photograph</h1>
    <p class="mb-0">Add the details and choose an image for your gallery card.</p>
</div>
<section class="card content-card">
    <div class="card-body p-4">
        <?php if ($errors): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label" for="photo_title">Photograph Title</label>
                <input class="form-control" id="photo_title" name="photo_title" maxlength="200" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="photographer_name">Photographer Name</label>
                <input class="form-control" id="photographer_name" name="photographer_name" maxlength="100" value="<?= htmlspecialchars($photographerName, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category">Photography Category</label>
                <input class="form-control" id="category" name="category" maxlength="100" value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>" placeholder="For example: Nature, Portrait, Travel" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label" for="image">Upload Photograph</label>
                <input class="form-control" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                <div class="form-text">JPG, PNG, or WEBP. Maximum file size: 5 MB.</div>
            </div>
            <button class="btn btn-primary" type="submit">Save Photograph</button>
            <a class="btn btn-outline-secondary" href="dashboard.php">Cancel</a>
        </form>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
