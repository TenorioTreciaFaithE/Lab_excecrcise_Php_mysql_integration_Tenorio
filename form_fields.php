<?php
// Shared form fields for create.php and update.php
// Expects $data (array) and $errors (array)
function field($name, $label, $type, $data, $errors, $required = false) { ?>
  <div class="mb-3">
    <label for="<?= e($name) ?>" class="form-label"><?= e($label) ?><?= $required ? ' <span class="text-danger">*</span>' : '' ?></label>
    <input type="<?= e($type) ?>" id="<?= e($name) ?>" name="<?= e($name) ?>"
           class="form-control<?= isset($errors[$name]) ? ' is-invalid' : '' ?>"
           value="<?= e($data[$name] ?? '') ?>">
    <?php if (isset($errors[$name])): ?>
      <div class="invalid-feedback"><?= e($errors[$name]) ?></div>
    <?php endif; ?>
  </div>
<?php }
