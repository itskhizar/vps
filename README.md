# VPS — V Provide Services
> Official Company Website & Project Management Admin Dashboard

VPS is an internationally registered digital, technical, creative, and design agency operating from Pakistan and serving global enterprise clients. This repository contains the complete production-grade web platform and administrative control portal.

---

## 🚀 Key Highlights & Architecture

- **Zero-Bloat Native Stack**: Built entirely with clean PHP 8+, MySQL, HTML5, Tailwind CSS, Material Symbols, and jQuery/AJAX. No cumbersome heavy frameworks, maintaining high performance and direct maintainability.
- **Dynamic Database-Driven Content**: Services, Marketing Case Studies, Team Members, Blog Articles, Inquiries, Settings, and Status Audit Trails are 100% database-persisted.
- **Enterprise Project Inquiry Portal**: Comprehensive client intake flow with real-time service preselection, conditional budget handling, randomized multi-format document attachment uploads, and automatic unique reference number generation (`VPS-YYYY-XXXX`).
- **Complete Administrative Dashboard**: Dedicated control center under `/admin/` featuring live statistical metrics, full CRUD for all entities, status lifecycle transitions, private internal notes, activity auditing, and staff management.
- **Strict Role-Based User Levels**: Built-in User Level system honoring **User Level 9** for super-administrators, with self-deletion protection and bcrypt password security.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2+ / 8.4+ (Vanilla, Object-Oriented Session & Database wrapper, PDO Prepared Statements) |
| **Database** | MySQL / MariaDB (InnoDB engine, utf8mb4 encoding, foreign keys, transaction support) |
| **Frontend Styling** | Tailwind CSS (Production utility configuration), Custom Design Tokens |
| **Typography & Icons** | Plus Jakarta Sans, Inter, Google Material Symbols Outlined |
| **Scripting & Interactivity** | Modern Vanilla JavaScript, jQuery 3.7.1, AJAX Async Handlers, IntersectionObserver |
| **Web Server** | Apache 2.4+ (with `.htaccess` rewrite and security headers) / Nginx |

---

## 📁 Directory Structure

```text
vprovideservices/
├── .htaccess                       # Apache rewrite rules, security headers & error documents
├── .gitignore                      # Git ignore patterns for OS files, uploads & test scripts
├── 403.php                         # Forbidden error page
├── 404.php                         # Page Not Found error page
├── 500.php                         # Server Error page
├── maintenance.php                 # Temporary maintenance notice
├── index.php                       # Dynamic Homepage with animated sections & live DB counters
├── services.php                    # Services directory with category filtering
├── service-details.php             # Individual service detail page with dynamic CTA link
├── start-project.php               # Multi-field project intake form with file upload & confirmation
├── portfolio.php                   # Filterable marketing portfolio & case studies
├── case-study.php                  # In-depth case study presentation with challenge/solution/results
├── team.php                        # Team directory
├── team-member.php                 # Individual team member profile
├── about.php                       # Company story, mission, vision, values & workflow
├── contact.php                     # Contact inquiry form & direct agency channels
├── blog.php                        # Searchable & paginated blog publication
├── blog-post.php                   # Individual article reader with author card & related posts
├── newsletter-subscribe.php        # AJAX newsletter subscription endpoint
├── robots.txt                      # Search engine crawler directives
├── sitemap.xml                     # Search engine XML sitemap
│
├── admin/                          # Administrative Management Portal
│   ├── index.php                   # Dashboard overview with live database counts & status charts
│   ├── login.php                   # Staff authentication gateway with brute-force defense
│   ├── logout.php                  # Safe session termination
│   ├── header.php                  # Reusable admin navigation sidebar with real-time badges
│   ├── footer.php                  # Reusable admin footer & modal scripts
│   ├── projects.php                # Client projects master list, search, filter, and quick actions
│   ├── project-details.php         # Project deep-dive, attachment viewer, status changer & audit notes
│   ├── services.php                # Full Services CRUD with image upload & category management
│   ├── portfolio.php               # Marketing Case Studies CRUD with visual gallery uploads
│   ├── team.php                    # Team profiles CRUD with avatar uploads & skills manager
│   ├── blog.php                    # Blog Articles CRUD with categories & publication states
│   ├── messages.php                # Client contact inquiries inbox & message manager
│   ├── users.php                   # Staff & Administrator user management (Admin Level = 9)
│   ├── settings.php                # Global website settings (phone, email, socials, metadata)
│   └── init_admin.php              # Safe command-line administrator bootstrap utility
│
├── include/                        # Core Application Libraries & Includes
│   ├── functions.php               # Centralized PDO helper, CSRF, sanitization & utility functions
│   ├── header.php                  # Reusable public header, dynamic navigation & mobile drawer
│   ├── footer.php                  # Reusable public footer, newsletter AJAX, WhatsApp & legal links
│   ├── classes/
│   │   ├── constants.php           # Database credentials, table names, userlevels, session keys
│   │   ├── database.php            # MySQL connection and legacy auth compatibility layer
│   │   └── session.php             # Active session manager, authentication and visitor tracking
│   └── sqls/
│       ├── 3g.sql                  # Complete standalone base database schema
│       └── vps_schema_update.sql   # Safe incremental migration script with seed data
│
└── uploads/                        # Filesystem storage for media & client documents
    ├── attachments/                # Private project requirements documents (PDF, DOCX, ZIP)
    ├── blog/                       # Featured article cover images
    ├── portfolio/                  # Case study screenshots & showcase imagery
    ├── services/                   # Service iconography and banner imagery
    └── team/                       # Team portrait photography
```

---

## 🗄️ Database Setup & Installation

The application is configured to connect to a MySQL database named `vps`.

### Option A: Via phpMyAdmin (XAMPP)
1. Start **Apache** and **MySQL** in your XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin/` in your browser.
3. If database `vps` does not exist, create a new database with collation `utf8mb4_unicode_ci`.
4. Select `vps`, go to the **Import** tab, choose `include/sqls/3g.sql` (or `include/sqls/vps_schema_update.sql`), and click **Import**.

### Option B: Via Command Line (MySQL CLI)
Run the following in PowerShell or Command Prompt:
```bash
# Navigate to the project root
cd c:\xampp\htdocs\vprovideservices

# Apply the safe migration and seed data
mysql -u root -p vps < include/sqls/vps_schema_update.sql
```

### Database Tables Summary
- `users`: Staff and admin accounts (`username`, `password`, `userlevel`, `email`, `timestamp`)
- `services`: Service catalog (`title`, `slug`, `category`, `short_desc`, `full_desc`, `features`, `display_order`, `is_active`)
- `projects`: Inquiries and projects (`reference_no`, `client_name`, `client_email`, `status`, `timeline`, `budget_type`, etc.)
- `project_attachments`: Uploaded scope files (`original_name`, `file_path`, `file_size`, `mime_type`)
- `project_status_history`: Complete audit trail (`previous_status`, `new_status`, `changed_by`, `comment`)
- `project_notes`: Private administrative annotations (`admin_username`, `note`, `created_at`)
- `team`: Team members directory (`name`, `slug`, `title`, `bio`, `specialties`, `skills`, `order_num`, `is_active`)
- `portfolio`: Case study showcases (`title`, `slug`, `client_name`, `challenge`, `solution`, `results`, `is_featured`, `is_published`)
- `blog_categories` & `blog_posts`: Content publications system
- `contact_messages`: Inbound inquiries submitted from `contact.php`
- `settings`: Configurable site parameters (Phone, WhatsApp, Email, Address, Socials, SEO defaults)
- `newsletter_subscribers`: Email subscriber list

---

## 🔐 Administrative Access & User Management

### Default Admin Credentials
- **Admin Gateway**: `http://localhost/vprovideservices/admin/login.php`
- **Username**: `admin` (or `Admin`)
- **Password**: `admin123`
- **User Level**: `9` (Full Super-Admin Access)

### User Levels Architecture
The system employs an integer-based hierarchy defined in `include/classes/constants.php`:
- `ADMIN_LEVEL = 9`: Full access to the administration suite, projects, CRUD, settings, and staff accounts.
- `USER_LEVEL = 1`: Standard verified account.
- **Protection Rules**: The active administrator cannot delete their own active account in `/admin/users.php`.

### Resetting / Creating Admin Accounts
If you need to re-initialize an administrator account from the CLI:
```bash
php admin/init_admin.php
```

---

## 🛡️ Security & Hardening Measures

- **Prepared SQL Statements**: All dynamic queries execute through PDO prepared statements with parameterized inputs.
- **Cross-Site Scripting (XSS) Prevention**: All user-rendered output is escaped using the `e()` helper (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`).
- **Cross-Site Request Forgery (CSRF)**: Every POST form is accompanied by a unique session-bound CSRF token verified via `verify_csrf()`.
- **Brute-Force Login Throttling**: The login gateway records failed attempts in session storage and locks attempts exceeding safety thresholds.
- **Safe File Upload Security**:
  - File extensions are validated against an explicit whitelist (`pdf`, `docx`, `doc`, `txt`, `zip`, `jpg`, `jpeg`, `png`, `webp`).
  - MIME types are verified server-side.
  - Stored files are renamed to randomized unique hashes preventing file overwrite and path traversal attacks.
  - Executable extensions (`.php`, `.phtml`, `.exe`, `.sh`) are strictly rejected.
- **HTTP Security Headers**: Configured in `.htaccess` and PHP includes:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`

---

## 🌐 Local Development & Testing

1. Ensure **XAMPP** is running with PHP 8.2 or 8.4 and MySQL.
2. Verify access to the public portal:
   - Homepage: `http://localhost/vprovideservices/`
   - Services: `http://localhost/vprovideservices/services.php`
   - Start a Project: `http://localhost/vprovideservices/start-project.php`
   - Portfolio: `http://localhost/vprovideservices/portfolio.php`
   - Team: `http://localhost/vprovideservices/team.php`
   - About: `http://localhost/vprovideservices/about.php`
   - Contact: `http://localhost/vprovideservices/contact.php`
   - Blog: `http://localhost/vprovideservices/blog.php`
3. Test project intake:
   - Fill out an inquiry at `start-project.php`.
   - Confirm receipt of your project reference code (e.g. `VPS-2026-XXXX`).
   - Log into `/admin/` and inspect the newly created project in `/admin/projects.php`.

---

## 📞 Official VPS Contact Details

- **Company**: VPS (V Provide Services)
- **Local Contact**: `03328912706`
- **WhatsApp / International**: `+92 332 8912706`
- **Direct WhatsApp Link**: `https://wa.me/923328912706`
- **Headquarters**: Islamabad / Rawalpindi, Pakistan

---

## 📜 License & Copyright

&copy; 2026 VPS (V Provide Services). All rights reserved.
