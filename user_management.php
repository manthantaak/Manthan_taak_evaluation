<?php
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$statement = $conn->query('SELECT userid, full_name, email FROM users ORDER BY userid DESC');
$users = $statement->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'User Management';
require __DIR__ . '/header.php';
?>
<div class="page-heading">
    <p class="section-label mb-2">Accounts</p>
    <h1 class="h2">User management</h1>
    <p class="mb-0">Edit registered account details or remove an account.</p>
</div>
<div class="card content-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (!$users): ?>
                    <tr><td colspan="4" class="text-center text-muted-custom py-4">No registered users.</td></tr>
                <?php else: foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int) $user['userid'] ?></td>
                        <td><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?><?php if ((int) $user['userid'] === (int) $_SESSION['user_id']): ?> <span class="badge text-bg-secondary">You</span><?php endif; ?></td>
                        <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="edit_user.php?id=<?= (int) $user['userid'] ?>">Edit</a>
                            <form class="d-inline" method="post" action="delete_user.php" onsubmit="return confirm('Delete this user and all photographs they uploaded?');">
                                <input type="hidden" name="id" value="<?= (int) $user['userid'] ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
