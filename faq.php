<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'FAQs';
$faqs = $conn->query("SELECT * FROM faqs ORDER BY id ASC");

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">FAQs</span>
      <h2>Frequently Asked Questions</h2>
      <p>Can't find what you're looking for? <a href="<?php echo is_logged_in() ? 'chat.php' : 'register.php'; ?>" style="color:var(--primary); font-weight:600;">Chat with our AI assistant</a>.</p>
    </div>

    <div class="accordion">
      <?php if ($faqs && $faqs->num_rows > 0): ?>
        <?php while ($f = $faqs->fetch_assoc()): ?>
          <div class="accordion-item">
            <div class="accordion-question">
              <span><?php echo e($f['question']); ?></span>
              <span class="icon">+</span>
            </div>
            <div class="accordion-answer">
              <div class="accordion-answer-inner"><?php echo e($f['answer']); ?></div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No FAQs available yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
