<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$page_title = 'AI Chat';
$user_id = $_SESSION['user_id'];

// Fetch this user's chat history (most recent first, grouped loosely by day for the sidebar)
$stmt = $conn->prepare("SELECT sender, message, created_at FROM chat_history WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$history = $stmt->get_result();

// Distinct days for the sidebar list
$stmt2 = $conn->prepare("SELECT DISTINCT DATE(created_at) as day FROM chat_history WHERE user_id = ? ORDER BY day DESC LIMIT 10");
$stmt2->bind_param('i', $user_id);
$stmt2->execute();
$days = $stmt2->get_result();

require_once __DIR__ . '/includes/header.php';
?>

<div class="chat-layout">
  <aside class="chat-sidebar">
    <h3>Chat History</h3>
    <?php if ($days && $days->num_rows > 0): ?>
      <?php while ($d = $days->fetch_assoc()): ?>
        <div class="chat-session-item"><?php echo date('F j, Y', strtotime($d['day'])); ?></div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="font-size:13px; color: var(--text-grey);">No previous conversations yet. Say hello below!</p>
    <?php endif; ?>
    <a href="chat_history.php" class="btn btn-outline btn-sm btn-block" style="margin-top:16px;">View Full History</a>
  </aside>

  <main class="chat-main">
    <div class="chat-header">
      <div class="bot-avatar">🤖</div>
      <div class="chat-header-info">
        <h4>AI Support Assistant</h4>
        <span>● Online</span>
      </div>
    </div>

    <div class="chat-messages" id="chatMessages">
      <?php if ($history && $history->num_rows > 0): ?>
        <?php while ($m = $history->fetch_assoc()): ?>
          <div class="msg-row <?php echo $m['sender'] === 'user' ? 'user' : 'bot'; ?>">
            <div class="msg-avatar <?php echo $m['sender'] === 'user' ? 'user' : 'bot'; ?>"><?php echo $m['sender'] === 'user' ? '🙂' : '🤖'; ?></div>
            <div class="msg-bubble"><?php echo nl2br(e($m['message'])); ?></div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="msg-row bot">
          <div class="msg-avatar bot">🤖</div>
          <div class="msg-bubble">Hi <?php echo e($_SESSION['user_first_name']); ?>! 👋 I'm your AI support assistant. Ask me about business hours, products, prices, or anything else you'd like to know.</div>
        </div>
      <?php endif; ?>
    </div>

    <form class="chat-input-bar" id="chatForm">
      <input type="text" id="chatInput" placeholder="Type your message..." autocomplete="off" required>
      <button type="submit" class="send-btn" aria-label="Send">➤</button>
    </form>
  </main>
</div>

<script src="js/chat.js"></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
