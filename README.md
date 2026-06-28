# GuruHub Platform

**GuruHub** is a full-stack learning management platform for Indonesian educators and students. It connects course discovery, enrollment, payments, live classes, quizzes, certificates, and role-based dashboards in one mobile-first web application.

---

## Overview

GuruHub helps schools and training providers run digital classes end-to-end:

| Role | Capabilities |
|------|----------------|
| **Admin** | User management, payments, permissions, course oversight |
| **Guru (Teacher)** | Courses, materials, videos, schedules, quizzes, earnings, certificates |
| **Siswa (Student)** | Catalog, booking, payments, learning room, quizzes, reviews, biodata |

The student and teacher experiences use a **premium mobile-native UI** (floating dock navigation, glassmorphism, Iconsax icons) while admin panels remain desktop-optimized.

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Tailwind CSS v4, Alpine.js, Vite |
| Auth & RBAC | Laravel Auth + Spatie Permission |
| Media | Laravel Storage, FFmpeg (optional) |
| Icons | Iconsax (generated Blade components) |

---

## Features

- Public landing page and role-based registration (guru / siswa)
- Course catalog, booking wizard, and manual bank transfer payments
- Learning room with video, PDF materials, and progress tracking
- Quiz builder (multiple choice, essay, PDF attachment) and grading
- Live class scheduling (Zoom / Google Meet)
- Teacher earnings and certificate management
- Admin dashboard with payment verification workflow

---

## Requirements

- PHP >= 8.2 with extensions: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8+ or MariaDB 10.6+

---

## Installation

```bash
# 1. Clone repository
git clone https://github.com/Firmanstmik/guru-hub-platform.git
cd guru-hub-platform

# 2. Install dependencies
composer install
npm install

# 3. Environment
cp .env.example .env
php artisan key:generate

# Edit .env — set database credentials:
# DB_DATABASE=guruhub
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Database
php artisan migrate
php artisan db:seed --class=GuruHubDemoSeeder

# 5. Storage link
php artisan storage:link

# 6. Build assets
npm run build

# 7. Run application
php artisan serve
```

Open **http://127.0.0.1:8000** in your browser.

### Development (with hot reload)

```bash
composer run dev
```

---

## Demo Accounts

After running `GuruHubDemoSeeder`:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@gmail.com` | `password123` |
| Guru | `guru@gmail.com` | `password123` |
| Siswa | `siswa@gmail.com` | `password123` |

---

## Project Structure

```
app/
  Http/Controllers/   # Feature controllers (courses, quiz, payments, …)
  Models/             # Eloquent models
  Support/            # Helpers (MediaDefaults, etc.)
database/
  migrations/         # Schema migrations
  seeders/            # Demo data (GuruHubDemoSeeder)
resources/
  css/                # Design system (app-mobile, app-premium, landing)
  views/              # Blade templates + components
  views/components/   # Reusable UI (app/*, ui/iconsax)
public/assets/        # Avatars, cover images, logos
scripts/              # Iconsax Blade generator
routes/web.php        # Application routes
```

---

## Useful Commands

```bash
# Regenerate Iconsax Blade icons after package update
npm run icons:build

# Clear caches
php artisan optimize:clear

# Run tests
php artisan test
```

---

## Security Notes

- Never commit `.env` or credentials to version control
- Change demo passwords before deploying to production
- Configure `APP_DEBUG=false` and `APP_ENV=production` in production
- Review file upload limits and storage permissions

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Author

**Firmanstmik** — [github.com/Firmanstmik](https://github.com/Firmanstmik)
