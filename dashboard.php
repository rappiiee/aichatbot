<?php
require_once __DIR__ . '/includes/admin_auth.php';

$page_title = 'Dashboard';
$active = 'dashboard';

$total_users    = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$total_chats    = $conn->query("SELECT COUNT(*) as c FROM chat_history WHERE sender = 'user'")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_faqs     = $conn->query("SELECT COUNT(*) as c FROM faqs")->fetch_assoc()['c'];

$recent_chats = $conn->query("
  SELECT ch.message, ch.created_at, u.first_name, u.last_name
  FROM chat_history ch
  JOIN users u ON u.id = ch.user_id
  WHERE ch.sender = 'user'
  ORDER BY ch.created_at DESC
  LIMIT 8
");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon blue">👥</div>
    <div><div class="stat-value"><?php echo (int)$total_users; ?></div><div class="stat-label">Total Users</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon teal">💬</div>
    <div><div class="stat-value"><?php echo (int)$total_chats; ?></div><div class="stat-label">Total Chats</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple">🛒</div>
    <div><div class="stat-value"><?php echo (int)$total_products; ?></div><div class="stat-label">Total Products</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange">❓</div>
    <div><div class="stat-value"><?php echo (int)$total_faqs; ?></div><div class="stat-label">Total FAQs</div></div>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <h3>Recent Customer Inquiries</h3>
  </div>
  <table>
    <thead>
      <tr><th>Customer</th><th>Message</th><th>Date</th></tr>
    </thead>
    <tbody>
      <?php if ($recent_chats && $recent_chats->num_rows > 0): ?>
        <?php while ($row = $recent_chats->fetch_assoc()): ?>
          <tr>
            <td><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></td>
            <td><?php echo e(mb_strimwidth($row['message'], 0, 70, '...')); ?></td>
            <td><?php echo date('M j, g:i A', strtotime($row['created_at'])); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="3">No customer inquiries yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
