# ExpenseMate — Personal Expense Tracker

## Live Demo
- URL: (add after hosting)
- Admin: admin@expensemate.com / Admin@12345
- User: user@expensemate.com / User@12345

## About
ExpenseMate is a SaaS Personal Expense Tracking application
built with Laravel 12. It allows users to track income and
expenses, view analytics, and manage their finances.

## Tech Stack
- Laravel 12
- Laravel Jetstream (Livewire stack)
- Laravel Sanctum (API authentication)
- Livewire 3 (reactive UI)
- MySQL database
- Tailwind CSS
- Chart.js
- Google OAuth (Socialite)
- Two Factor Authentication (2FA)

## Features
- User registration and login
- Google Sign In
- Two Factor Authentication (2FA)
- Role-based access (User/Admin)
- Expense CRUD with Livewire
- Income and expense tracking
- Category management
- Dashboard with charts
- Summary and analytics
- Admin panel
- RESTful API with Sanctum
- Mobile responsive design
- Security headers and rate limiting

## Installation

### Requirements
- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Node.js 20.x

### Steps
```bash
# Clone repository
git clone https://github.com/yourusername/expensemate.git
cd expensemate

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

##  Default Accounts
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@expensemate.com | Admin@12345 |
| User | user@expensemate.com | User@12345 |

##  API Documentation
See API.md for full API documentation.

##  Security Documentation
See SECURITY.md for security implementation details.

##  Testing
```bash
php artisan test
```
27 tests passing, 4 skipped.

## Key Files
- `app/Livewire/ExpenseManager.php` - Livewire component
- `app/Http/Controllers/Api/` - API controllers
- `app/Http/Middleware/` - Custom middleware
- `SECURITY.md` - Security documentation
- `API.md` - API documentation
