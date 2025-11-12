# Blog Headless CMS API (Laravel)

A headless CMS API for a blog platform built with Laravel. This repository contains the backend API that serves content to a frontend (e.g. Next.js). The codebase exposes endpoints for posts, categories, users, authentication, and admin management.

- Project root: [`composer.json`](composer.json) · [`package.json`](package.json)
- API documentation: [`apiary.apib`](apiary.apib) · [`documentation.html`](documentation.html)

---

## Tech Stack

- PHP 8.1+ and Laravel (see [`composer.json`](composer.json))
- Authentication via Laravel Sanctum (see [`config/sanctum.php`](config/sanctum.php) and [`config/auth.php`](config/auth.php))
- Database: configured via [`config/database.php`](config/database.php)
- Tests: PHPUnit (see [`phpunit.xml`](phpunit.xml) and `tests/`)
- Seeder: [`database/seeders/BlogSeeder.php`](database/seeders/BlogSeeder.php)

---

## Quick Links (key files & controllers)

- Routes: [`routes/api.php`](routes/api.php) · [`routes/web.php`](routes/web.php)  
- Controllers:
  - [`App\Http\Controllers\Api\PostController`](app/Http/Controllers/Api/PostController.php)
  - [`App\Http\Controllers\Api\CategoryController`](app/Http/Controllers/Api/CategoryController.php)
  - [`App\Http\Controllers\Api\AuthController`](app/Http/Controllers/Api/AuthController.php)
  - [`App\Http\Controllers\Api\UserController`](app/Http/Controllers/Api/UserController.php)
  - [`App\Http\Controllers\Api\DashboardController`](app/Http/Controllers/Api/DashboardController.php)
- Models (expected):
  - [`App\Models\BlogPost`](app/Models/BlogPost.php)
  - [`App\Models\Category`](app/Models/Category.php)
  - [`App\Models\User`](app/Models/User.php)
- Kernel & routing:
  - [`app/Http/Kernel.php`](app/Http/Kernel.php)
  - [`app/Providers/RouteServiceProvider.php`](app/Providers/RouteServiceProvider.php)
- Seeds & factories: [`database/seeders/BlogSeeder.php`](database/seeders/BlogSeeder.php)
- API design & examples: [`apiary.apib`](apiary.apib) · [`documentation.html`](documentation.html)
- Tests: [`tests/Feature/ExampleTest.php`](tests/Feature/ExampleTest.php)

---

## Requirements

- PHP ^8.1
- Composer
- A supported database (MySQL / Postgres / SQLite)
- Node & npm (for frontend integration or asset tooling if needed)
- (Optional) Docker / Laravel Sail for reproducible environment

---

## Local Setup

1. Clone the repo and install PHP dependencies:

```bash
git clone <repo-url>
cd blog-api
composer install
```

2. Copy environment file and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure your `.env` (DB credentials, APP_URL, SANCTUM stateful domains). See:
   - [`config/database.php`](config/database.php)
   - [`config/sanctum.php`](config/sanctum.php)
   - [`config/app.php`](config/app.php)

4. Run migrations and seed sample data:

```bash
php artisan migrate
php artisan db:seed --class=BlogSeeder
```

The seeder outputs sample credentials:
- Admin: `admin@blog.com / password123`
- Author: `author@blog.com / password123`

Seeder file: [`database/seeders/BlogSeeder.php`](database/seeders/BlogSeeder.php)

5. (Optional) Create storage symlink for uploaded assets:

```bash
php artisan storage:link
```

6. Start server:

```bash
php artisan serve
# or use sail: ./vendor/bin/sail up -d
```

---

## Running Tests

Run PHPUnit tests with:

```bash
vendor/bin/phpunit --configuration=phpunit.xml
```

Configuration file: [`phpunit.xml`](phpunit.xml)  
Example feature test: [`tests/Feature/ExampleTest.php`](tests/Feature/ExampleTest.php)

---

## API Documentation

- Human-readable docs: [`documentation.html`](documentation.html)
- API blueprint / examples: [`apiary.apib`](apiary.apib)

The API is documented with request/response examples in [`apiary.apib`](apiary.apib). The API expects token-based auth (Sanctum). See the Authentication section below.

---

## Authentication (Sanctum)

Public endpoints:
- `POST /api/login` -> handled by [`App\Http\Controllers\Api\AuthController::login`](app/Http/Controllers/Api/AuthController.php)
- `POST /api/register` -> handled by [`App\Http\Controllers\Api\AuthController::register`](app/Http/Controllers/Api/AuthController.php)

Protected endpoints require `Authorization: Bearer {token}` (personal access token returned on login/register). The protected group is declared in [`routes/api.php`](routes/api.php).

Check guard configuration: [`config/auth.php`](config/auth.php) and Sanctum config: [`config/sanctum.php`](config/sanctum.php).

Note: In [`app/Http/Kernel.php`](app/Http/Kernel.php) the middleware line for Sanctum stateful SPA requests is commented:
```php
// \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
```
If you are using a first-party SPA + cookie-based session auth, you may need to enable it.

---

## Main Endpoints (high-level)

Public:
- GET `/api/posts` -> [`App\Http\Controllers\Api\PostController::index`](app/Http/Controllers/Api/PostController.php)
- GET `/api/posts/{slug}` -> [`App\Http\Controllers\Api\PostController::show`](app/Http/Controllers/Api/PostController.php)
- GET `/api/posts/filter/search` -> [`App\Http\Controllers\Api\PostController::filtered`](app/Http/Controllers/Api/PostController.php)
- GET `/api/categories` -> [`App\Http\Controllers\Api\CategoryController::index`](app/Http/Controllers/Api/CategoryController.php)
- GET `/api/authors` -> [`App\Http\Controllers\Api\PostController::getAuthors`](app/Http/Controllers/Api/PostController.php)

Protected (examples; see [`routes/api.php`](routes/api.php) for full list):
- Admin posts management -> `/api/admin/posts` handled by [`App\Http\Controllers\Api\PostController`](app/Http/Controllers/Api/PostController.php)
- Admin categories -> [`App\Http\Controllers\Api\CategoryController`](app/Http/Controllers/Api/CategoryController.php)
- User management -> [`App\Http\Controllers\Api\UserController`](app/Http/Controllers/Api/UserController.php)
- Dashboard stats -> [`App\Http\Controllers\Api\DashboardController`](app/Http/Controllers/Api/DashboardController.php)

---

## Seeder & Sample Data

The project includes a blog data seeder: [`database/seeders/BlogSeeder.php`](database/seeders/BlogSeeder.php). It seeds categories, sample posts, and user accounts used in the examples and docs.

---

## Development Notes & Recommendations

- Ensure the `api` middleware group and Sanctum settings match your frontend strategy:
  - If using token-based auth (personal access tokens), current setup is fine.
  - For SPA cookie-based auth, enable `EnsureFrontendRequestsAreStateful` in [`app/Http/Kernel.php`](app/Http/Kernel.php) under `'api'` group.
- Rate limiting is configured in [`app/Providers/RouteServiceProvider.php`](app/Providers/RouteServiceProvider.php) via `RateLimiter::for('api', ...)`.
- Check file storage permissions for uploads and run `php artisan storage:link` if needed.
- If using Redis for cache/queues, update [`config/database.php`](config/database.php).

---

## Troubleshooting

- 500 errors / No DB: verify `.env` and DB migrations.
- Auth token invalid: confirm tokens created by [`App\Http\Controllers\Api\AuthController`](app/Http/Controllers/Api/AuthController.php) and sent in `Authorization` header.
- Missing routes: verify `php artisan route:list` and that routes are loaded from [`routes/api.php`](routes/api.php).

---

## Contributing

1. Fork the repo and open a PR.
2. Run tests and include new tests for any new features.
3. Keep behavior and API responses consistent with the examples in [`apiary.apib`](apiary.apib).

---

## License

This project follows the license referenced in the repository root (`composer.json`).