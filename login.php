<?php
require_once __DIR__ . '/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errorMessage = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $statement = $conn->prepare('SELECT userid, full_name, password FROM users WHERE email = ?');
    $statement->bind_param('s', $email);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['userid'];
        $_SESSION['user_name'] = $user['full_name'];
        header('Location: dashboard.php');
        exit;
    }

    $errorMessage = 'The email or password is incorrect.';
}

$pageTitle = 'Login';
require __DIR__ . '/header.php';
?>
<section class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <p class="section-label mb-2">Welcome back</p>
        <h1 class="h3 page-heading">Log in</h1>
        <p class="text-muted-custom">Sign in to view and manage the gallery.</p>

        <?php if ($errorMessage): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Log in</button>
        </form>
        <p class="text-center text-muted-custom mt-4 mb-0">New to the gallery? <a href="register.php">Create an account</a></p>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
