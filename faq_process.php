<?php
require_once __DIR__ . '/includes/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Invalid request.');
    redirect('manage_faqs.php');
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $question = clean_input($_POST['question'] ?? '');
    $answer   = clean_input($_POST['answer'] ?? '');
    $admin_id = $_SESSION['admin_id'];

    if (empty($question) || empty($answer)) {
        set_flash('error', 'Please fill in both the question and answer.');
    } else {
        $stmt = $conn->prepare("INSERT INTO faqs (question, answer, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $question, $answer, $admin_id);
        $stmt->execute();
        set_flash('success', 'FAQ added successfully.');
    }

} elseif ($action === 'edit') {
    $id       = (int)($_POST['id'] ?? 0);
    $question = clean_input($_POST['question'] ?? '');
    $answer   = clean_input($_POST['answer'] ?? '');

    if (empty($question) || empty($answer) || $id <= 0) {
        set_flash('error', 'Please fill in both the question and answer.');
    } else {
        $stmt = $conn->prepare("UPDATE faqs SET question = ?, answer = ? WHERE id = ?");
        $stmt->bind_param('ssi', $question, $answer, $id);
        $stmt->execute();
        set_flash('success', 'FAQ updated successfully.');
    }

} elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM faqs WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        set_flash('success', 'FAQ deleted successfully.');
    }
} else {
    set_flash('error', 'Unknown action.');
}

redirect('manage_faqs.php');
