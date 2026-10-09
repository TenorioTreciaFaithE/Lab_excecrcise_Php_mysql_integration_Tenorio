<?php
// Database configuration - edit these to match your MySQL setup (XAMPP defaults shown)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'app_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die('Database connection failed. Check config.php and make sure app_db.sql was imported.');
}

session_start();

// Escape output safely
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// One-time flash messages
function flash($message = null, $type = 'success') {
    if ($message !== null) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        return;
    }
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// CSRF protection
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check() {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        die('Invalid request token.');
    }
}

// Validate contact form input; returns [cleanData, errors]
function validate_contact($src) {
    $data = [
        'name'    => trim($src['name'] ?? ''),
        'email'   => trim($src['email'] ?? ''),
        'phone'   => trim($src['phone'] ?? ''),
        'address' => trim($src['address'] ?? ''),
    ];
    $errors = [];
    if ($data['name'] === '' || mb_strlen($data['name']) > 100) {
        $errors['name'] = 'Name is required (max 100 characters).';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 150) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (mb_strlen($data['phone']) > 30) {
        $errors['phone'] = 'Phone is too long (max 30 characters).';
    }
    if (mb_strlen($data['address']) > 255) {
        $errors['address'] = 'Address is too long (max 255 characters).';
    }
    return [$data, $errors];
}
