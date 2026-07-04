<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Home';

// Fetch a few products for the homepage preview
$products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 3");

// Fetch a few FAQs for the homepage preview
$faqs = $conn->query("SELECT * FROM faqs ORDER BY id ASC LIMIT 3");

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" id="home">
  <div class="container hero-wrap">
    <div class="hero-text">
      <span class="eyebrow">AI-Powered Customer Support</span>
      <h1>Give your small business a <span>24/7 support assistant</span></h1>
      <p>Answer customer questions instantly, showcase your products, and never miss an inquiry again — powered by an AI chatbot built for small businesses.</p>
      <div class="hero-actions">
        <a href="<?php echo is_logged_in() ? 'chat.php' : 'register.php'; ?>" class="btn btn-primary">Start Chat</a>
        <a href="products.php" class="btn btn-outline">View Products</a>
      </div>
    </div>
    <div class="hero-visual">
      <div class="chat-mock">
        <div class="chat-mock-header">
          <span class="dot"></span>
          <strong>AI Support Assistant</strong>
        </div>
        <div class="chat-mock-bubble bot">Hi! 👋 How can I help you today?</div>
        <div class="chat-mock-bubble user">What are your business hours?</div>
        <div class="chat-mock-bubble bot">We're open Mon–Sat, 9 AM to 6 PM!</div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="about">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">About Us</span>
      <h2>Why small businesses choose us</h2>
      <p>We help small businesses deliver fast, friendly, and consistent customer support without hiring extra staff.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon">⚡</div>
        <h3>Instant Responses</h3>
        <p>Customers get accurate answers in seconds, any time of day, without waiting on hold.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🕒</div>
        <h3>24/7 Availability</h3>
        <p>Your chatbot never sleeps — customers can reach out even outside business hours.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📈</div>
        <h3>Grows With You</h3>
        <p>Add new products and FAQs anytime through a simple admin dashboard — no coding required.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt" id="products-preview">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Our Products</span>
      <h2>Support packages for every business</h2>
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
              <p class="desc"><?php echo e(mb_strimwidth($p['description'], 0, 90, '...')); ?></p>
              <div class="product-meta">
                <span class="price">₱<?php echo number_format($p['price'], 2); ?></span>
                <span class="badge <?php echo $p['availability'] === 'Available' ? 'badge-available' : 'badge-outofstock'; ?>"><?php echo e($p['availability']); ?></span>
              </div>
              <a href="product_details.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-outline btn-block btn-sm">View Details</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products available yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section" id="faq-preview">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">FAQs</span>
      <h2>Frequently Asked Questions</h2>
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
      <?php endif; ?>
    </div>
    <div style="text-align:center; margin-top: 30px;">
      <a href="faq.php" class="btn btn-outline">See All FAQs</a>
    </div>
  </div>
</section>

<section class="section section-alt" id="contact">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Contact Us</span>
      <h2>We'd love to hear from you</h2>
      <p>Send us a message, or better yet, chat with our AI assistant for an instant answer.</p>
    </div>
    <form class="auth-card" style="max-width: 560px; margin: 0 auto;" onsubmit="alert('Thanks for reaching out! Our team will get back to you soon.'); this.reset(); return false;">
      <div class="form-row">
        <div class="form-group">
          <label for="cname">Your Name</label>
          <input type="text" id="cname" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="cemail">Email Address</label>
          <input type="email" id="cemail" class="form-control" required>
        </div>
      </div>
      <div class="form-group">
        <label for="cmessage">Message</label>
        <textarea id="cmessage" class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Send Message</button>
    </form>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
