<?php
require_once __DIR__ . '/includes/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Invalid request.');
    redirect('manage_products.php');
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $name         = clean_input($_POST['name'] ?? '');
    $description  = clean_input($_POST['description'] ?? '');
    $price        = (float)($_POST['price'] ?? 0);
    $availability = in_array($_POST['availability'] ?? '', ['Available', 'Out of Stock']) ? $_POST['availability'] : 'Available';
    $admin_id     = $_SESSION['admin_id'];

    if (empty($name) || empty($description) || $price < 0) {
        set_flash('error', 'Please fill in all fields correctly.');
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, availability, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdsi', $name, $description, $price, $availability, $admin_id);
        $stmt->execute();
        set_flash('success', 'Product added successfully.');
    }

} elseif ($action === 'edit') {
    $id           = (int)($_POST['id'] ?? 0);
    $name         = clean_input($_POST['name'] ?? '');
    $description  = clean_input($_POST['description'] ?? '');
    $price        = (float)($_POST['price'] ?? 0);
    $availability = in_array($_POST['availability'] ?? '', ['Available', 'Out of Stock']) ? $_POST['availability'] : 'Available';

    if (empty($name) || empty($description) || $price < 0 || $id <= 0) {
        set_flash('error', 'Please fill in all fields correctly.');
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, availability = ? WHERE id = ?");
        $stmt->bind_param('ssdsi', $name, $description, $price, $availability, $id);
        $stmt->execute();
        set_flash('success', 'Product updated successfully.');
    }

} elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        set_flash('success', 'Product deleted successfully.');
    }
} else {
    set_flash('error', 'Unknown action.');
}

redirect('manage_products.php');
