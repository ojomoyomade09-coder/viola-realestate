# VIOLA Real Estate

PHP/MySQL luxury listings app with a cinematic VIOLA-style homepage, PDO-secured search, accounts, and owner submissions.

## Folder structure

```
viola-real-estate/
├── index.php
├── listings.php
├── agents.php
├── pages.php
├── faq.php
├── contact.php
├── blog.php
├── account.php
├── submit.php
├── logout.php
├── config/db.php
├── includes/
├── assets/
└── sql/schema.sql
```

## Setup

1. Install PHP 8.1+ with the PDO MySQL driver, and MySQL 8+.
2. Edit `config/db.php` with your MySQL username and password.
3. Import a fresh schema (this drops and recreates app tables):

```bash
mysql -u root -p < sql/schema.sql
```

4. From this project folder:

```bash
php -S localhost:8000
```

5. Open http://localhost:8000

## Demo account

- Email: `demo@viola.test`
- Password: `password`

Change or remove this user before any public deployment.

Owner submissions are stored as **pending** and stay off the public Properties grid until you set `visibility` to `public` in MySQL.
