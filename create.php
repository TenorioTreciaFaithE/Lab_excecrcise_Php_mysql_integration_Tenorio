<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form_fields.php';

$data = ['name' => '', 'email' => '', 'phone' => '', 'address' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$data, $errors] = validate_contact($_POST);

    if (!$errors) {
        try {
            $stmt = $conn->prepare('INSERT INTO contacts (name, email, phone, address) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $data['name'], $data['email'], $data['phone'], $data['address']);
            $stmt->execute();
            flash('Contact added successfully.');
            header('Location: read.php');
            exit;
        } catch (mysqli_sql_exception $ex) {
            if ($ex->getCode() === 1062) {
                $errors['email'] = 'That email is already in use.';
            } else {
                $errors['form'] = 'Could not save the contact. Please try again.';
            }
        }
    }
}

$page_title = 'Add Contact';
require __DIR__ . '/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body p-4">
        <h1 class="h4 mb-4"><i class="bi bi-person-plus me-2"></i>Add Contact</h1>
        <?php if (isset($errors['form'])): ?>
          <div class="alert alert-danger"><?= e($errors['form']) ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <?php
            field('name', 'Full name', 'text', $data, $errors, true);
            field('email', 'Email', 'email', $data, $errors, true);
            field('phone', 'Phone', 'text', $data, $errors);
            field('address', 'Address', 'text', $data, $errors);
          ?>
          <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
            <a class="btn btn-outline-secondary" href="read.php">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
