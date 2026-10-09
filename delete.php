<?php
require_once __DIR__ . '/config.php';

// Deletion only via POST with a valid CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: read.php');
    exit;
}
csrf_check();

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    flash('Contact deleted.');
} else {
    flash('Contact not found.', 'warning');
}
header('Location: read.php');
exit;
