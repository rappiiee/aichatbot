<?php
require_once __DIR__ . '/includes/admin_auth.php';

$page_title = 'Manage FAQs';
$active = 'faqs';

$faqs = $conn->query("SELECT * FROM faqs ORDER BY id DESC");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="panel">
  <div class="panel-head">
    <h3>All FAQs</h3>
    <div style="display:flex; gap:12px; align-items:center;">
      <div class="search-box"><input type="text" id="tableSearch" placeholder="Search FAQs..."></div>
      <button class="btn btn-primary btn-sm" data-open-modal="addFaqModal">+ Add FAQ</button>
    </div>
  </div>

  <table>
    <thead>
      <tr><th>Question</th><th>Answer</th><th>Added</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if ($faqs && $faqs->num_rows > 0): ?>
        <?php while ($f = $faqs->fetch_assoc()): ?>
          <tr>
            <td><strong><?php echo e($f['question']); ?></strong></td>
            <td><?php echo e(mb_strimwidth($f['answer'], 0, 70, '...')); ?></td>
            <td><?php echo date('M j, Y', strtotime($f['created_at'])); ?></td>
            <td>
              <div class="action-btns">
                <button class="icon-btn edit" data-open-modal="editFaqModal"
                  data-edit='<?php echo json_encode([
                    'id' => $f['id'], 'question' => $f['question'], 'answer' => $f['answer']
                  ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>✏️</button>
                <form class="delete-form" method="POST" action="faq_process.php" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
                  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                  <button type="submit" class="icon-btn delete">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4">No FAQs yet. Click "Add FAQ" to create one.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Add FAQ Modal -->
<div class="modal-overlay" id="addFaqModal">
  <div class="modal-box">
    <span class="modal-close" data-close-modal>&times;</span>
    <h3>Add New FAQ</h3>
    <form method="POST" action="faq_process.php">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <div class="form-group">
        <label>Question</label>
        <input type="text" name="question" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Answer</label>
        <textarea name="answer" class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Save FAQ</button>
    </form>
  </div>
</div>

<!-- Edit FAQ Modal -->
<div class="modal-overlay" id="editFaqModal">
  <div class="modal-box">
    <span class="modal-close" data-close-modal>&times;</span>
    <h3>Edit FAQ</h3>
    <form method="POST" action="faq_process.php">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="id" id="edit_id">
      <div class="form-group">
        <label>Question</label>
        <input type="text" name="question" id="edit_question" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Answer</label>
        <textarea name="answer" id="edit_answer" class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Update FAQ</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
