<?php
    require_once __DIR__ . '/db.php';
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $id = (int) ($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        exit('A valid user ID is required.');
    }

    $statement = $conn->prepare('SELECT userid, full_name, email FROM users WHERE userid = ?');
    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        http_response_code(404);
        exit('User not found.');
    }

    $errors = [];
    $fullName = $user['full_name'];
    $email = $user['email'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);

        if ($fullName == '' || $email == '') {
            $errors[] = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email.';
        } else {
            try {
                $statement = $conn->prepare('UPDATE users SET full_name = ?, email = ? WHERE userid = ?');
                $statement->bind_param('ssi', $fullName, $email, $id);
                $statement->execute();

                if ((int) $id === (int) $_SESSION['user_id']) {
                    $_SESSION['user_name'] = $fullName;
                }

                $_SESSION['flash_message'] = 'The user details were updated.';
                $_SESSION['flash_type'] = 'success';
                header('Location: user_management.php');
                exit;
            } catch (mysqli_sql_exception $error) {
                if ($error->getCode() === 1062) {
                    $errors[] = 'That email address is already being used.';
                } else {
                    $errors[] = 'The user could not be updated. Please try again.';
                }
            }
        }
    }

    $pageTitle = 'Edit User';
    require __DIR__ . '/header.php';
    ?>
    <div class="page-heading">
        <p class="section-label mb-2">Account details</p>
        <h1 class="h2">Edit user</h1>
    </div>
    <section class="card content-card">
        <div class="card-body p-4">
            <?php if ($errors): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3"><label class="form-label" for="full_name">Full Name</label><input class="form-control" id="full_name" name="full_name" maxlength="100" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>" required></div>
                <div class="mb-4"><label class="form-label" for="email">Email</label><input class="form-control" id="email" name="email" type="email" maxlength="150" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required></div>
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a class="btn btn-outline-secondary" href="user_management.php">Cancel</a>
            </form>
        </div>
    </section>
    <?php require __DIR__ . '/footer.php'; ?>
