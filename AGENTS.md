# Copilot CLI Runbook

Use this repository as a Laravel 13 ecommerce app with Filament and Blockonomics payments.

## What to do first

1. Install PHP dependencies with `composer install`.
2. Install frontend dependencies with `npm install`.
3. Ensure a local `.env` exists with `APP_ENV=local`, `APP_DEBUG=true`, and a valid `APP_KEY`.
4. Use SQLite locally unless the user explicitly points the app at MySQL/MariaDB/PostgreSQL in `.env`.
5. Create or verify the database file at `database/database.sqlite` when using SQLite.

## Local startup sequence

Run these commands in order when the user asks to start the project:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve --host=127.0.0.1 --port=8000
```

If frontend assets are needed, run `npm run build` or `npm run dev` depending on the task.

## Data expectations

- The storefront and shop pages depend on seeded products.
- The shop page only shows products where `is_active = true`.
- Demo products come from `database/seeders/ProductSeeder.php`.
- If the database is empty, seed it before testing the product pages.

## Payment flow

- Blockonomics configuration lives in `.env`.
- Required key: `BLOCKONOMICS_API_KEY`.
- The payment page should fail gracefully if the API key is missing.

## Deployment note

If the app is moved to a server with SQL instead of SQLite, update `.env` to use the server database and run:

```bash
php artisan migrate --force
php artisan db:seed --force
```

That ensures the product catalog exists on the server too.

## Useful checks

- `php artisan route:list`
- `php artisan config:clear`
- `php artisan cache:clear`
- `php artisan view:clear`
- `php artisan test`
