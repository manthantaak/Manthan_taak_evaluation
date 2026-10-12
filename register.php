<?php
require_once __DIR__ . '/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($fullName == '' || $email == '' || $password == '' || $confirmPassword == '') {
        $errors[] = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    } elseif ($password != $confirmPassword) {
        $errors[] = 'The passwords do not match.';
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $statement = $conn->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
            $statement->bind_param('sss', $fullName, $email, $hashedPassword);
            $statement->execute();
            $_SESSION['flash_message'] = 'Your account was created. You can log in now.';
            $_SESSION['flash_type'] = 'success';
            header('Location: login.php');
            exit;
        } catch (mysqli_sql_exception $error) {
            $errors[] = 'This email may already be registered. Please try another one.';
        }
    }
}

$pageTitle = 'Create account';
require __DIR__ . '/header.php';
?>
<section class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <p class="section-label mb-2">Join the gallery</p>
        <h1 class="h3 page-heading">Create your account</h1>
        <p class="text-muted-custom">Sign up to share and manage your photographs.</p>

        <?php if ($errors): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label" for="full_name">Full Name</label>
                <input class="form-control" id="full_name" name="full_name" maxlength="100" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" maxlength="150" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" minlength="8" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input class="form-control" id="confirm_password" name="confirm_password" type="password" minlength="8" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Create account</button>
        </form>
        <p class="text-center text-muted-custom mt-4 mb-0">Already registered? <a href="login.php">Log in</a></p>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
