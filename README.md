# Ecommerce Laravel Project

Full-stack ecommerce application built with Laravel 13, Filament Admin, and a Blockonomics Bitcoin payment page.

## Current Features

- Storefront pages: Home, Shop, Product Details, Categories, Cart, Checkout, Order Success
- Cart management: add, update quantity, remove, clear cart
- Order creation flow from checkout
- Filament admin panel with dashboard widgets and product/category/order management
- Bitcoin payment page with Blockonomics address generation + QR code

## Server Requirements

### Required versions

- PHP 8.3+
- Composer 2.7+
- Node.js 20+
- NPM 10+

### PHP extensions

Install/enable these PHP extensions:

- bcmath
- ctype
- curl
- fileinfo
- intl
- json
- mbstring
- openssl
- pdo
- tokenizer
- xml
- zip
- pdo_sqlite or pdo_mysql (depending on database choice)

### Database

Supported by Laravel config:

- SQLite (default in project)
- MySQL/MariaDB

## Install and Run (Server Setup)

### 1) Clone and install dependencies

```bash
git clone <your-repository-url>
cd ecommerce-laravel
composer install
npm install
```

### 2) Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit .env and configure at least:

- APP_NAME
- APP_ENV
- APP_URL
- APP_DEBUG (false in production)
- DB_CONNECTION
- DB_DATABASE (and DB_HOST/DB_PORT/DB_USERNAME/DB_PASSWORD for MySQL)
- BLOCKONOMICS_API_KEY

### 3) Database and storage

```bash
php artisan migrate --seed --force
php artisan storage:link
```

### 4) Frontend assets

```bash
npm run build
```

### 5) Start application (development)

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

App URL: http://127.0.0.1:8000

## Important Routes

- Store Home: /
- Shop: /shop
- Cart: /cart
- Checkout: /checkout
- Bitcoin Payment Page: /create-payment
- Filament Admin: /admin

## Admin Access (Current Seed)

Seeded admin account currently configured:

- Email: itachi@example.com
- Password: itachi

Change this immediately on production.

## Blockonomics Setup

This project uses Blockonomics merchant API for creating Bitcoin receiving addresses.

Required environment variable:

```env
BLOCKONOMICS_API_KEY=your_blockonomics_api_key_here
```

Without a valid API key, the payment page still loads but shows a graceful error message instead of an address.

## Production Checklist

- Set APP_ENV=production
- Set APP_DEBUG=false
- Use a real web server (Nginx/Apache) pointing to public directory
- Configure HTTPS and APP_URL
- Configure a real database backup strategy
- Set correct folder permissions for storage and bootstrap/cache
- Cache configuration/routes/views after deployment:

```bash
php artisan optimize
```

- Restart PHP-FPM / queue workers after deploy

## Useful Commands

```bash
php artisan route:list
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan test
```

## License

This project is built on Laravel and follows the MIT license model from the Laravel ecosystem.
