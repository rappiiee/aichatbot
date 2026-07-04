<?php
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Register';
$errors = [];
$old = ['first_name' => '', 'last_name' => '', 'email' => '', 'contact_number' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid form submission. Please try again.';
    }

    $first_name       = clean_input($_POST['first_name'] ?? '');
    $last_name        = clean_input($_POST['last_name'] ?? '');
    $email            = clean_input($_POST['email'] ?? '');
    $contact_number   = clean_input($_POST['contact_number'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $old = compact('first_name', 'last_name', 'email', 'contact_number');

    // ---- Validation ----
    if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || empty($password) || empty($confirm_password)) {
        $errors[] = 'All fields are required.';
    }
    if (!empty($email) && !is_valid_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!empty($contact_number) && !preg_match('/^[0-9+\-\s]{7,20}$/', $contact_number)) {
        $errors[] = 'Please enter a valid contact number.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Password and Confirm Password do not match.';
    }

    // ---- Check duplicate email ----
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'This email is already registered. Please log in instead.';
        }
    }

    // ---- Create account ----
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, contact_number, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $first_name, $last_name, $email, $contact_number, $hashed);

        if ($stmt->execute()) {
            set_flash('success', 'Account created successfully! Please log in.');
            redirect('login.php');
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="auth-card" style="max-width: 500px;">
    <h2>Create Your Account</h2>
    <p class="subtitle">Sign up to start chatting with our AI support assistant.</p>

    <?php foreach ($errors as $error): ?>
      <div class="alert alert-error" style="margin-bottom:16px;"><?php echo e($error); ?></div>
    <?php endforeach; ?>

    <form method="POST" action="register.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

      <div class="form-row">
        <div class="form-group">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo e($old['first_name']); ?>" required>
        </div>
        <div class="form-group">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo e($old['last_name']); ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" value="<?php echo e($old['email']); ?>" required>
      </div>

      <div class="form-group">
        <label for="contact_number">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" class="form-control" value="<?php echo e($old['contact_number']); ?>" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" minlength="8" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <p class="auth-footer-link">Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
