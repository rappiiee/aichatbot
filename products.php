<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Products';
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Our Products</span>
      <h2>Support Packages &amp; Services</h2>
      <p>Choose the plan that fits your business — or ask our AI assistant which one is right for you.</p>
    </div>

    <div class="product-grid">
      <?php if ($products && $products->num_rows > 0): ?>
        <?php while ($p = $products->fetch_assoc()): ?>
          <div class="product-card">
            <div class="product-thumb">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <div class="product-body">
              <h3><?php echo e($p['name']); ?></h3>
              <p class="desc"><?php echo e(mb_strimwidth($p['description'], 0, 110, '...')); ?></p>
              <div class="product-meta">
                <span class="price">₱<?php echo number_format($p['price'], 2); ?></span>
                <span class="badge <?php echo $p['availability'] === 'Available' ? 'badge-available' : 'badge-outofstock'; ?>"><?php echo e($p['availability']); ?></span>
              </div>
              <a href="product_details.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-outline btn-block btn-sm">View Details</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products available at the moment. Please check back soon.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
