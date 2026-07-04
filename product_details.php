<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    set_flash('error', 'Product not found.');
    redirect('products.php');
}

$page_title = $product['name'];
require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container">
    <div style="display:flex; gap:50px; flex-wrap:wrap; align-items:flex-start;">
      <div class="product-thumb" style="width:340px; height:280px; border-radius: var(--radius); flex-shrink:0;">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      <div style="flex:1; min-width:280px;">
        <h1 style="margin-bottom:14px;"><?php echo e($product['name']); ?></h1>
        <span class="badge <?php echo $product['availability'] === 'Available' ? 'badge-available' : 'badge-outofstock'; ?>"><?php echo e($product['availability']); ?></span>
        <p style="margin: 20px 0; color: var(--text-grey); font-size:15.5px; line-height:1.7;"><?php echo nl2br(e($product['description'])); ?></p>
        <div class="price" style="font-size:28px; margin-bottom:26px;">₱<?php echo number_format($product['price'], 2); ?></div>
        <a href="<?php echo is_logged_in() ? 'chat.php' : 'register.php'; ?>" class="btn btn-primary">Ask Our AI Assistant</a>
        <a href="products.php" class="btn btn-outline">Back to Products</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
