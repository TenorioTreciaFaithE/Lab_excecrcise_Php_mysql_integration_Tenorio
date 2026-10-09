<?php
require_once __DIR__ . '/config.php';

$search = trim($_GET['q'] ?? '');
$perPage = 8;
$page = max(1, (int)($_GET['page'] ?? 1));

// Count rows (with optional search)
$like = '%' . $search . '%';
if ($search !== '') {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM contacts WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?');
    $stmt->bind_param('sss', $like, $like, $like);
} else {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM contacts');
}
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_row()[0];
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;

// Fetch the current page
if ($search !== '') {
    $stmt = $conn->prepare('SELECT * FROM contacts WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY name LIMIT ? OFFSET ?');
    $stmt->bind_param('sssii', $like, $like, $like, $perPage, $offset);
} else {
    $stmt = $conn->prepare('SELECT * FROM contacts ORDER BY name LIMIT ? OFFSET ?');
    $stmt->bind_param('ii', $perPage, $offset);
}
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = 'Contacts';
require __DIR__ . '/header.php';
?>
<div class="card">
  <div class="card-body p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <h1 class="h4 mb-0">Contacts <span class="badge text-bg-secondary"><?= $total ?></span></h1>
      <form class="d-flex" method="get" role="search">
        <input class="form-control me-2" type="search" name="q" placeholder="Search name, email, phone"
               value="<?= e($search) ?>">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>

    <?php if (!$rows): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        No contacts found. <a href="create.php">Add one</a>.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th class="text-end">Actions</th></tr>
          </thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td>
                <span class="avatar me-2"><?= e(mb_strtoupper(mb_substr($r['name'], 0, 1))) ?></span>
                <?= e($r['name']) ?>
              </td>
              <td><a href="mailto:<?= e($r['email']) ?>" class="text-decoration-none"><?= e($r['email']) ?></a></td>
              <td><?= e($r['phone']) ?></td>
              <td><?= e($r['address']) ?></td>
              <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-secondary" href="update.php?id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                        data-id="<?= (int)$r['id'] ?>" data-name="<?= e($r['name']) ?>"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($pages > 1): ?>
      <nav>
        <ul class="pagination justify-content-center mb-0">
          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item<?= $i === $page ? ' active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="post" action="delete.php">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="id" id="deleteId">
      <div class="modal-header">
        <h5 class="modal-title">Delete contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">Are you sure you want to delete <strong id="deleteName"></strong>? This cannot be undone.</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>
<script>
  document.getElementById('deleteModal').addEventListener('show.bs.modal', function (ev) {
    document.getElementById('deleteId').value = ev.relatedTarget.dataset.id;
    document.getElementById('deleteName').textContent = ev.relatedTarget.dataset.name;
  });
</script>
<?php require __DIR__ . '/footer.php'; ?>
