<?php
/**
 * Shared header + navigation bar.
 * Expects (optional): $page_title, $active
 */
require_once __DIR__ . '/functions.php';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' | ' : ''; ?>AI Chatbot for Customer Support</title>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="site-header">
  <div class="container nav-wrap">
    <a href="index.php" class="brand">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="16" cy="16" r="16" fill="#1D5EF0"/>
        <path d="M9 13.5C9 11.01 11.01 9 13.5 9H18.5C20.99 9 23 11.01 23 13.5C23 15.99 20.99 18 18.5 18H14L10.5 20.5V18C9.6 17.6 9 16.6 9 15.5V13.5Z" fill="white"/>
        <circle cx="13.5" cy="13.5" r="1.2" fill="#1D5EF0"/>
        <circle cx="18.5" cy="13.5" r="1.2" fill="#1D5EF0"/>
      </svg>
      <span>AI ChatSupport</span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="mainNav">
      <a href="index.php#home">Home</a>
      <a href="index.php#about">About</a>
      <a href="products.php">Products</a>
      <a href="faq.php">FAQs</a>
      <a href="index.php#contact">Contact Us</a>
      <?php if (is_logged_in()): ?>
        <a href="chat.php">AI Chat</a>
        <a href="chat_history.php">History</a>
        <a href="logout.php" class="btn-nav-outline">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn-nav-outline">Login</a>
        <a href="register.php" class="btn-nav-filled">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<?php if ($flash): ?>
  <div class="container">
    <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
  </div>
<?php endif; ?>
