# 📚 Library Management System

A modern Laravel 12 web application for managing library operations: cataloguing books, managing categories and members, and tracking loans with due dates and returns.

Built with **Laravel 12**, **Blade**, **Tailwind CSS 4** (Vite), with flexible database support for **SQLite** (instant local setup) and **PostgreSQL** (via pgAdmin).

---

## ⚡ Quick Start (Under 2 Minutes)

### 1. Prerequisites
- **PHP 8.2+** (with `pdo_sqlite` or `pdo_pgsql` enabled)
- **Composer 2**
- **Node.js 18+ & npm**

### 2. Setup & Run
From the project folder (`library-management/`):

```bash
# 1. Install dependencies & build frontend assets
composer install
npm install
npm run build

# 2. Configure environment & generate application key
cp .env.example .env
php artisan key:generate

# 3. Setup database & seed sample data (SQLite default)
php artisan migrate:fresh --seed

# 4. Start the development server
php artisan serve
```

The application is now live at **[http://127.0.0.1:8000](http://127.0.0.1:8000)**!

---

## 🔑 How to Log In & Credentials

1. Open **[http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)** in your browser.
2. Use one of the pre-seeded accounts:

| Role | Email | Password | Access & Capabilities |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@library.test` | `password` | Book & category CRUD, user management, loan logs, analytics dashboard |
| **Member** | `nfeest@example.net` | `password` | Browse catalogue, search & filter, borrow/return books, personal loan history |
| **New Member** | *Register at [/register](http://127.0.0.1:8000/register)* | *(any password)* | Automatically assigned member role |

*(All pre-seeded member accounts use the password `password`. Run `php artisan tinker --execute="App\Models\User::where('role','member')->pluck('email')"` to view all member emails).*

---

## 🏗️ System Architecture

### 1. Architectural Layers

```
┌────────────────────────────────────────────────────────┐
│                   Frontend Layer                       │
│    Blade Views  •  Tailwind CSS 4  •  Vite 7 Bundler   │
└──────────────────────────┬─────────────────────────────┘
                           │ HTTP Requests / Forms
┌──────────────────────────▼─────────────────────────────┐
│                 Application / Controller               │
│  • Auth Controllers (Login, Register, Session)         │
│  • Book & Category Controllers (CRUD & Catalogue)      │
│  • Borrow Controller (Borrow, Return, Loan Logs)       │
│  • Admin Controller & EnsureUserIsAdmin Middleware     │
└──────────────────────────┬─────────────────────────────┘
                           │ Eloquent ORM
┌──────────────────────────▼─────────────────────────────┐
│                   Data Model Layer                     │
│        User  •  Book  •  Category  •  BorrowRecord     │
└──────────────────────────┬─────────────────────────────┘
                           │ Database Drivers
┌──────────────────────────▼─────────────────────────────┐
│                   Database Engine                      │
│       SQLite (Local Default)  /  PostgreSQL 14+        │
└────────────────────────────────────────────────────────┘
```

### 2. Entity Relationships

```
Category (1) ──────< (n) Book (1) ──────< (n) BorrowRecord
                          ^
                          │
                       User (1)
```

- **Category `1 ── n` Book**: Books belong to a category. Deleting a category sets `category_id` to null.
- **Book `1 ── n` BorrowRecord**: Tracks borrowing history. Deleting a book cascades to its records.
- **User `1 ── n` BorrowRecord**: Tracks loans per user. Deleting a user cascades to their records.

### 3. Core Database Tables

| Table | Primary Fields | Notes |
| :--- | :--- | :--- |
| **`users`** | `name`, `email`, `role`, `phone`, `password` | Roles: `admin` or `member` (default `member`). |
| **`categories`** | `name`, `description` | Unique category name. |
| **`books`** | `title`, `author`, `isbn`, `total_copies`, `available_copies`, `category_id` | Unique ISBN; tracks available stock count. |
| **`borrow_records`**| `user_id`, `book_id`, `borrowed_at`, `due_date`, `returned_at`, `status` | Status: `borrowed` or `returned`. |

---

## 📜 Business Rules

1. **Loan Period**: Standard loan duration is **14 days** (`due_date = borrowed_at + 14 days`).
2. **Copy Availability**: Books can only be borrowed if `available_copies > 0`. Borrowing decrements copies; returning increments them.
3. **No Duplicate Loans**: A member cannot borrow another copy of the same book while they already have an active loan for it.
4. **Return Authorization**: Members can only return their own loans; Admins can return books on behalf of any member.
5. **Overdue Calculation**: Dynamically calculated on the fly (`status = 'borrowed'` and `due_date < today`).

---

## 🧭 Key Routes

| Method | URI | Route Name | Access | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/dashboard` | `dashboard` | Auth | Role-based dashboard (Admin stats or Member active loans) |
| `GET` | `/books` | `books.index` | Auth | Browse and search catalogue |
| `GET` | `/books/{book}` | `books.show` | Auth | View book details & availability |
| `POST` | `/books/{book}/borrow`| `borrows.store` | Auth | Borrow a book |
| `POST` | `/borrows/{borrow}/return` | `borrows.return` | Auth | Return a borrowed book |
| `GET` | `/my-borrows` | `borrows.index` | Auth | Member loan history |
| `*` | `/books/*`, `/categories/*` | `*.create/edit` | Admin | Full CRUD for books & categories |
| `GET` | `/admin/borrows` | `admin.borrows.index` | Admin | Library-wide borrow log with status filters |
| `GET` | `/admin/users` | `admin.users.index` | Admin | User listing, promote/demote, deletion |

---

## 🗄️ Database Configuration

### Option A: SQLite (Recommended for Local Dev — Zero Setup)
In `.env`:
```dotenv
DB_CONNECTION=sqlite
```
Run `php artisan migrate:fresh --seed`. The database file will be saved at `database/database.sqlite`.

### Option B: PostgreSQL (via pgAdmin / Production)
1. Create the database in pgAdmin or terminal:
   ```bash
   psql -U postgres -h 127.0.0.1 -c "CREATE DATABASE library_management"
   ```
2. Configure `.env`:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=library_management
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```
3. Run `php artisan migrate:fresh --seed`.

---

## 📁 Project Directory Structure

```
library-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Auth, Books, Categories, Borrows, Dashboard
│   │   └── Middleware/        # EnsureUserIsAdmin (Admin route protection)
│   └── Models/                # User, Book, Category, BorrowRecord
├── database/
│   ├── migrations/            # Schema definitions
│   └── seeders/               # DatabaseSeeder (sample categories, books, users)
├── resources/
│   ├── views/                 # Blade templates (books, borrows, admin, layouts)
│   └── css/ & js/             # Tailwind CSS 4 & application assets
├── routes/
│   └── web.php                # Web route definitions
└── tests/                     # Unit and Feature tests
```

---

## 🧪 Testing & Helpful Commands

```bash
# Run test suite
php artisan test

# Format code with Laravel Pint
./vendor/bin/pint

# Clear cached configuration
php artisan config:clear

# List all registered routes
php artisan route:list
```

---

## 💡 Troubleshooting

- **Server already running or Port 8000 in use:** Run `php artisan serve --port=8080`.
- **Database connection error:** Make sure `DB_CONNECTION=sqlite` is set in `.env` if you do not have PostgreSQL running locally.
- **Styling missing / raw HTML:** Run `npm run build` to compile the Tailwind CSS assets.
- **403 Forbidden on Admin areas:** Log in with `admin@library.test` or promote your account from `/admin/users`.
