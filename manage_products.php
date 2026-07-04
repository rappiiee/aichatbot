<?php
require_once __DIR__ . '/includes/admin_auth.php';

$page_title = 'Manage Products';
$active = 'products';

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="panel">
  <div class="panel-head">
    <h3>All Products</h3>
    <div style="display:flex; gap:12px; align-items:center;">
      <div class="search-box"><input type="text" id="tableSearch" placeholder="Search products..."></div>
      <button class="btn btn-primary btn-sm" data-open-modal="addProductModal">+ Add Product</button>
    </div>
  </div>

  <table>
    <thead>
      <tr><th></th><th>Name</th><th>Price</th><th>Availability</th><th>Added</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if ($products && $products->num_rows > 0): ?>
        <?php while ($p = $products->fetch_assoc()): ?>
          <tr>
            <td><div class="table-thumb">🛒</div></td>
            <td><strong><?php echo e($p['name']); ?></strong></td>
            <td>₱<?php echo number_format($p['price'], 2); ?></td>
            <td><span class="badge <?php echo $p['availability'] === 'Available' ? 'badge-available' : 'badge-outofstock'; ?>"><?php echo e($p['availability']); ?></span></td>
            <td><?php echo date('M j, Y', strtotime($p['created_at'])); ?></td>
            <td>
              <div class="action-btns">
                <button class="icon-btn edit" data-open-modal="editProductModal"
                  data-edit='<?php echo json_encode([
                    'id' => $p['id'], 'name' => $p['name'], 'description' => $p['description'],
                    'price' => $p['price'], 'availability' => $p['availability']
                  ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>✏️</button>
                <form class="delete-form" method="POST" action="product_process.php" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                  <button type="submit" class="icon-btn delete">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No products yet. Click "Add Product" to create one.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Add Product Modal -->
<div class="modal-overlay" id="addProductModal">
  <div class="modal-box">
    <span class="modal-close" data-close-modal>&times;</span>
    <h3>Add New Product</h3>
    <form method="POST" action="product_process.php">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label>Price (₱)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Availability</label>
        <select name="availability" class="form-control">
          <option value="Available">Available</option>
          <option value="Out of Stock">Out of Stock</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Save Product</button>
    </form>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal-overlay" id="editProductModal">
  <div class="modal-box">
    <span class="modal-close" data-close-modal>&times;</span>
    <h3>Edit Product</h3>
    <form method="POST" action="product_process.php">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="id" id="edit_id">
      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" id="edit_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label>Price (₱)</label>
        <input type="number" step="0.01" min="0" name="price" id="edit_price" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Availability</label>
        <select name="availability" id="edit_availability" class="form-control">
          <option value="Available">Available</option>
          <option value="Out of Stock">Out of Stock</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Update Product</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
