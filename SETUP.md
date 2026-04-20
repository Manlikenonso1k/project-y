# Quick Start Guide - Electro Shop

## 🚀 First Time Setup

### Step 1: Navigate to Project Directory

```bash
cd "c:\Users\Tenstrings Music Ins\Documents\Custom Office Templates\ecommerce-laravel"
```

### Step 2: Install Filament (if not done automatically)

```bash
composer require filament/filament
php artisan filament:install
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate

# Create admin user
php artisan make:filament-user
```

Enter your email and password when prompted.

### Step 4: Start the Development Server

```bash
php artisan serve
```

### Step 5: Access the Application

- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin
- **Tinker (CLI):** `php artisan tinker`

---

## 📦 Database Tables

After running migrations, you'll have these tables:

- `users` - User accounts
- `categories` - Product categories
- `products` - Product inventory
- `carts` - Shopping carts (per session/user)
- `cart_items` - Items in cart
- `orders` - Customer orders
- `order_items` - Items in orders

---

## 🏪 Adding Sample Data

### Using Tinker REPL

```bash
php artisan tinker
```

```php
# Create a category
$cat = Category::create([
    'name' => 'Smartphones',
    'slug' => 'smartphones',
    'description' => 'Latest smartphones'
]);

# Create products
Product::create([
    'name' => 'iPhone 15 Pro',
    'slug' => 'iphone-15-pro',
    'description' => 'Apple iPhone 15 Pro with A17 Pro chip',
    'price' => 999,
    'original_price' => 1099,
    'quantity' => 50,
    'category_id' => $cat->id,
    'is_active' => true,
    'is_featured' => true
]);

Product::create([
    'name' => 'Samsung Galaxy S24',
    'slug' => 'samsung-galaxy-s24',
    'description' => 'Samsung Galaxy S24 Ultra with 200MP camera',
    'price' => 1199,
    'quantity' => 30,
    'category_id' => $cat->id,
    'is_active' => true,
    'is_featured' => true
]);
```

Exit tinker by typing `exit` or pressing Ctrl+D.

---

## ⚙️ Configuration

### Key Configuration Files

- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/filesystems.php` - File storage configuration
- `.env` - Environment variables

### Common Changes

**Change app name:**
Edit `.env`:
```
APP_NAME="My Store"
```

**Enable maintenance mode:**
```bash
php artisan down
php artisan up
```

**Clear all caches:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

---

## 🔐 Security

1. **Always use HTTPS in production**
2. **Generate unique APP_KEY:** `php artisan key:generate`
3. **Keep `.env` file secret** - never commit to version control
4. **Update dependencies regularly:** `composer update`
5. **Use strong admin passwords** (20+ characters recommended)

---

## 📁 Important Files

| File | Purpose |
|------|---------|
| `routes/web.php` | URL routes |
| `app/Http/Controllers/*` | Business logic |
| `app/Models/*` | Database models |
| `resources/views/*` | HTML templates (Blade) |
| `public/css/style.css` | Custom styling |
| `public/js/app.js` | Minimal JavaScript |
| `.env` | Environment config |

---

## 🛠️ Common Tasks

### Create a new page
1. Add route in `routes/web.php`
2. Create controller method
3. Create view in `resources/views/`

### Add product image
1. Upload via admin panel
2. Images stored in `storage/app/public/products/`
3. Create symlink: `php artisan storage:link`

### Reset database
```bash
php artisan migrate:refresh  # Warning: deletes all data!
php artisan migrate:fresh --seed  # Refresh with seeders
```

### View database schema
```bash
php artisan db:show
php artisan db:table table_name
```

---

## 📊 Admin Panel Features

Access at `/admin` after logging in:

- ✅ Add/Edit/Delete Products
- ✅ Manage Categories
- ✅ View Orders (read-only by design)
- ✅ Update Order Status
- ✅ User Management

---

## 🐛 Debugging

### Enable debug mode
Edit `.env`:
```
APP_DEBUG=true
```

### View errors
- Browser console (F12)
- `storage/logs/laravel.log`

### Test database connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## 📈 Performance

- Minimal JavaScript (works without JS)
- Bootstrap 5 CDN (lightweight)
- Server-side rendering (no SPA overhead)
- Database indexing for fast queries
- Session-based carts (no API calls needed)

---

## 🚢 Ready to Deploy?

## 🛡️ Production Dependencies (Ubuntu + Tor Hidden Service)

Install system packages:

```bash
sudo apt update
sudo apt install -y \
  tor \
  nginx \
  mysql-server \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl \
  composer \
  unzip git curl \
  fail2ban ufw unattended-upgrades
```

Optional (if you expose clearnet HTTPS in addition to .onion):

```bash
sudo apt install -y certbot python3-certbot-nginx
```

Install Laravel production dependencies:

```bash
cd /var/www/ecommerce-laravel
composer install --no-dev --optimize-autoloader --classmap-authoritative
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Required production environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-onion-address.onion
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
BLOCKONOMICS_API_KEY=your_store_api_key
BLOCKONOMICS_CALLBACK_SECRET=generate_a_long_random_secret
BLOCKONOMICS_CALLBACK_IPS=
```

Minimum Tor hidden service mapping (/etc/tor/torrc):

```conf
HiddenServiceDir /var/lib/tor/ecommerce_service/
HiddenServicePort 80 127.0.0.1:8080
```

Apply Tor config:

```bash
sudo chown -R debian-tor:debian-tor /var/lib/tor/ecommerce_service
sudo chmod 700 /var/lib/tor/ecommerce_service
sudo systemctl restart tor
sudo cat /var/lib/tor/ecommerce_service/hostname
```

Nginx should listen only on localhost for onion-only hosting:

```nginx
server {
    listen 127.0.0.1:8080;
    server_name _;
    root /var/www/ecommerce-laravel/public;
    index index.php;

    autoindex off;
    location ~ /\.(?!well-known).* { deny all; }
    location ~* /(\.env|vendor|storage) { deny all; }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
```

Enable Nginx site and restart:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

Harden firewall for Tor-only deployment:

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH
sudo ufw enable
sudo ufw status
```

Before going live:

1. Set `.env`:
   ```
   APP_DEBUG=false
   APP_ENV=production
   APP_URL=http://your-onion-address.onion
   ```

2. Optimize:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Configure database on hosting

4. Set up file upload storage

5. Enable HTTPS (required)

---

## 🆘 Troubleshooting

**502 Bad Gateway?**
- Check error logs: `storage/logs/`
- Verify database connection
- Clear caches: `php artisan cache:clear`

**Images not showing?**
- Create storage link: `php artisan storage:link`
- Check `storage/app/public/` directory

**Admin panel not accessible?**
- Verify user exists: `php artisan tinker` → `User::all()`
- Clear caches

**Migrations failed?**
- Check database connection in `.env`
- Ensure database exists
- Run: `php artisan migrate --force`

---

## 📚 Learning Resources

- Laravel Documentation: https://laravel.com/docs
- Filament Documentation: https://filamentphp.com
- Bootstrap 5: https://getbootstrap.com/docs/5.0/
- Eloquent ORM: https://laravel.com/docs/eloquent

---

**Happy selling! 🛍️**
