<?php
/**
 * Session-based authentication guards.
 * Include this AFTER functions.php (session_start already handled there).
 */

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

/** Require a logged-in customer; otherwise redirect to login. */
function require_login() {
    if (!is_logged_in()) {
        set_flash('error', 'Please log in to continue.');
        redirect('login.php');
    }
}

/** Require a logged-in admin; otherwise redirect to admin login. */
function require_admin() {
    if (!is_admin_logged_in()) {
        set_flash('error', 'Please log in to the admin panel to continue.');
        redirect('login.php');
    }
}
