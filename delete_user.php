<?php
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Use the delete button on the user list.');
}

$id = (int) ($_POST['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    exit('A valid user ID is required.');
}

$statement = $conn->prepare('SELECT userid FROM users WHERE userid = ?');
$statement->bind_param('i', $id);
$statement->execute();
$result = $statement->get_result();
if (!$result->fetch_assoc()) {
    $_SESSION['flash_message'] = 'That user was not found.';
    $_SESSION['flash_type'] = 'warning';
    header('Location: user_management.php');
    exit;
}

$statement = $conn->prepare('SELECT image FROM photographs WHERE user_id = ?');
$statement->bind_param('i', $id);
$statement->execute();
$result = $statement->get_result();
$photos = $result->fetch_all(MYSQLI_ASSOC);

$statement = $conn->prepare('DELETE FROM photographs WHERE user_id = ?');
$statement->bind_param('i', $id);
$statement->execute();

$statement = $conn->prepare('DELETE FROM users WHERE userid = ?');
$statement->bind_param('i', $id);
$statement->execute();

foreach ($photos as $photo) {
    if (file_exists($photo['image'])) {
        unlink($photo['image']);
    }
}

if ((int) $id === (int) $_SESSION['user_id']) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$_SESSION['flash_message'] = 'The user and their photographs were deleted.';
$_SESSION['flash_type'] = 'success';
header('Location: user_management.php');
exit;
