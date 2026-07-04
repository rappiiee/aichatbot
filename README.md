# AI Chatbot for Customer Support in Small Businesses

A full-stack web app: HTML5/CSS3/JavaScript frontend, PHP backend, MySQL database.
Built to run on **XAMPP** (Apache + PHP + MySQL).

## 1. Setup with XAMPP

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL** from the control panel.
2. Copy the whole `aichatbot` folder into your XAMPP `htdocs` directory, e.g.:
   - Windows: `C:\xampp\htdocs\aichatbot`
   - macOS: `/Applications/XAMPP/htdocs/aichatbot`
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Click **Import**, choose `database/schema.sql`, and run it.
   This creates the `ai_chatbot_db` database with all 5 tables and seed data.
5. Check `includes/db_connect.php` — the default XAMPP credentials
   (`root` / empty password) are already set. Update them if your MySQL
   setup is different.
6. Visit `http://localhost/aichatbot/index.php` in your browser.

## 2. Default Accounts

| Role  | Email                  | Password   |
|-------|------------------------|------------|
| Admin | admin@aichatbot.com    | Admin@123  |

Customer accounts are created via the **Register** page.
Logging in with either a customer or admin email automatically routes you
to the correct dashboard — the system detects the role for you.

## 3. Folder Structure

```
aichatbot/
├── admin/                 Admin panel (dashboard, product/FAQ CRUD)
│   └── includes/          Admin-only header/footer/auth guard
├── css/                   style.css (site) + admin.css (admin panel)
├── database/              schema.sql (run this in phpMyAdmin)
├── images/                SVG icons/avatars
├── includes/              Shared PHP: db connection, auth, chatbot logic
├── js/                    main.js, chat.js, admin.js
├── uploads/                (reserved for future product image uploads)
├── index.php              Home page
├── login.php / register.php / logout.php
├── chat.php / chat_process.php   AI chat UI + AJAX handler
├── chat_history.php
├── products.php / product_details.php
└── faq.php
```

## 4. Security Notes

- Passwords are hashed with `password_hash()` / verified with `password_verify()`.
- All database queries use **prepared statements** (mysqli) to prevent SQL injection.
- All dynamic output is escaped with `htmlspecialchars()` via the `e()` helper to prevent XSS.
- Forms are protected with **CSRF tokens**.
- Access to `chat.php`, `chat_history.php`, and the entire `/admin` folder is
  gated by session checks (`require_login()` / `require_admin()`).

## 5. Extending the Chatbot

The chatbot's reply logic lives in `includes/chatbot_logic.php`. It currently
matches keywords for business hours, contact info, location, pricing, and a
generic FAQ keyword search — all pulled live from the `faqs` and `products`
tables, so adding a new FAQ or product automatically improves the chatbot's
answers. No code changes needed for new content.
