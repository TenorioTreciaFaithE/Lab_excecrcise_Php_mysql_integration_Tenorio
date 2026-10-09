# Contact Manager (PHP + MySQL + Bootstrap 5)

A simple CRUD web app: add, list/search, edit and delete contacts.

## Files
| File | Purpose |
|---|---|
| `app_db.sql` | Creates the `app_db` database, `contacts` table and sample rows |
| `config.php` | Database connection + helpers (escaping, flash messages, CSRF, validation) |
| `header.php` / `footer.php` | Shared Bootstrap layout |
| `form_fields.php` | Shared form field renderer |
| `create.php` | Create a contact |
| `read.php` | List, search and paginate contacts (main page) |
| `update.php` | Edit a contact |
| `delete.php` | Delete a contact (POST only) |

## Setup (XAMPP / WAMP / Laragon)
1. Copy this folder into `htdocs` (XAMPP) or `www` (WAMP/Laragon), e.g. `htdocs/contact-app`.
2. Start Apache and MySQL.
3. Import `app_db.sql` via phpMyAdmin (Import tab) or: `mysql -u root -p < app_db.sql`
4. Edit `config.php` if your MySQL user/password differ (XAMPP default: `root` / empty).
5. Open http://localhost/contact-app/read.php

## Or run with PHP's built-in server
```
php -S localhost:8000
```
then open http://localhost:8000/read.php

## Notes
- Uses prepared statements (no SQL injection), output escaping (no XSS) and CSRF tokens.
- Bootstrap and Bootstrap Icons load from a CDN, so an internet connection is needed for styling.
- Requires PHP 7.4+ with the `mysqli` extension.
