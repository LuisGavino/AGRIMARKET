# AgriMarket

One Laravel application with two interfaces that share the same codebase, configuration and database:

| Interface | Layout | Stylesheet | URL prefix | Route names |
|---|---|---|---|---|
| Customer | `layouts/public`, `layouts/app`, `layouts/seller` | `public/css/app.css` | `/` | `landing`, `home`, `products`, `wishlist`, `settings.*`, `seller.*` ... |
| Administrator | `layouts/admin` | `public/css/admin.css` | `/admin` | `admin.dashboard`, `admin.users`, `admin.produce`, `admin.livestock`, `admin.service-providers`, `admin.settings.*` |

## Installation

Requires PHP 8.3+ (with `pdo_sqlite` or `pdo_mysql`) and Composer.

```bash
composer install
cp .env.example .env          # only if you have no .env yet
php artisan key:generate      # only for a new .env
```

## Database setup

SQLite is the default. For an existing database, keep your current `DB_*` values in `.env`.

```bash
touch database/database.sqlite   # new SQLite setups only
php artisan migrate              # adds missing tables and users.role; keeps data
```

Do not use `migrate:fresh` or `db:wipe` on a database you want to keep.

## Run

```bash
php artisan serve
```

Customer interface: <http://localhost:8000>. Administrator sign-in: <http://localhost:8000/admin/login>.

## Authentication

- **Registration** (`/signup`): name, unique email (case-insensitive), password with confirmation, and acceptance of the terms. Passwords need 8+ characters, upper and lower case letters and a symbol. New accounts are always customers and are signed in immediately.
- **Login** (`/login` or `/admin/login`): both use the same handler (`POST /login`). Admins go to `/admin/dashboard`, customers to `/home`. The session is regenerated, and 5 failed attempts per email and IP are temporarily blocked. "Save Login" keeps you signed in.
- **Logout** (`POST /logout`): invalidates the session and renews the CSRF token.
- **Password hashing**: the `hashed` cast on `App\Models\User` hashes every password (bcrypt by default). Plain text is never stored.
- **Middleware**: `auth` protects signed-in customer pages; `auth` + `admin` protect `/admin/*` (customers get 403); `guest` covers login and signup. All forms that change state carry a CSRF token.

## Admin and customer accounts

No account or password ships with the project.

- Customer: register at `/signup`.
- Admin: run `php artisan agrimarket:make-admin` and answer the prompts. Entering an existing email offers to promote that user.

If you ran the old seeder, a `test@example.com` account with a well-known password may exist. Delete it.

## Tests

```bash
composer test
```

Tests use an in-memory SQLite database (see `phpunit.xml`); a `.env` with an `APP_KEY` must exist. Use `composer test`, which clears cached configuration first, so tests cannot run against your real database.

Manual check: register a customer, log out, create an admin, log in at `/admin/login`, then confirm a customer gets 403 on `/admin/dashboard`.

## Not yet implemented

Password reset ("forgot password"), Google/Apple sign-in, email verification, and saving changes on the account and security settings pages.

## Conventions

- Customer Blade views live in `resources/views/{pages,app,auth,settings,seller,partials,layouts}`; administrator views in `resources/views/admin` and `resources/views/components/admin`.
- `<x-icon>` is the customer icon set; `<x-admin.icon>` is the administrator icon set. They intentionally differ, so do not mix them.
- Roles: `users.role` is `customer` (default) or `admin`. It is not mass assignable.

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
