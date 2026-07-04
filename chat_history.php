<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$page_title = 'Chat History';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT sender, message, created_at FROM chat_history WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$history = $stmt->get_result();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Your Conversations</span>
      <h2>Chat History</h2>
    </div>

    <div class="history-list">
      <?php if ($history && $history->num_rows > 0): ?>
        <?php while ($m = $history->fetch_assoc()): ?>
          <div class="history-item">
            <div class="meta">
              <span class="<?php echo $m['sender'] === 'user' ? 'sender-user' : 'sender-bot'; ?>"><?php echo $m['sender'] === 'user' ? 'You' : 'AI Assistant'; ?></span>
              <span><?php echo date('M j, Y g:i A', strtotime($m['created_at'])); ?></span>
            </div>
            <p><?php echo nl2br(e($m['message'])); ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>You haven't started a conversation yet. <a href="chat.php" style="color:var(--primary); font-weight:600;">Start chatting now</a>.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
