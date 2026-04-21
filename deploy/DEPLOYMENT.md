# Deployment Guide: Tor .onion Service

## 📋 Script Execution Order (Run These in Sequence)

### **FIRST TIME SETUP** (One-time conversion from artisan serve to Nginx)

| Order | Script | Purpose |
|-------|--------|---------|
| **1st** | `setup_nginx.sh` | Install Nginx, PHP-FPM, configure services, enable auto-start on reboot |

### **EVERY DEPLOY** (After pulling code changes)

| Order | Script | Purpose |
|-------|--------|---------|
| **1st** | `git pull origin main` | Get latest code |
| **2nd** | `build_onion_assets.sh` | Build Vite CSS/JS assets with relative paths |
| **3rd** | Reload Nginx | `sudo systemctl reload nginx` |

---

## Quick Deploy After Pulling Changes

```bash
cd /var/www/project-x

# 1. Pull latest changes
git pull origin main

# 2. Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install

# 3. Run database migrations (if any)
php artisan migrate --force

# 4. Build frontend assets (relative paths for Tor)
./deploy/build_onion_assets.sh

# 5. Reload Nginx
sudo systemctl reload nginx

# 6. Clear Laravel caches (optional but recommended)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Setup: One-Time Nginx Configuration

**Before your first deploy, configure Nginx once:**

```bash
# 1. Copy the example config
sudo cp /var/www/project-x/deploy/nginx.onion.conf.example \
  /etc/nginx/sites-available/project-x.onion

# 2. Edit the file and replace `yourhiddenserviceaddress.onion` with your actual .onion address
sudo nano /etc/nginx/sites-available/project-x.onion

# 3. Verify the PHP-FPM socket path is correct (check `/run/php/` or `/var/run/php/`)
# Adjust `fastcgi_pass unix:/run/php/php8.3-fpm.sock;` if needed

# 4. Enable the site
sudo ln -s /etc/nginx/sites-available/project-x.onion \
  /etc/nginx/sites-enabled/project-x.onion

# 5. Test Nginx config
sudo nginx -t

# 6. Reload Nginx
sudo systemctl reload nginx
```

## Asset Base URL Options

### Option 1: Relative Paths (Recommended for Tor)
Automatically used by the build script. Assets load relative to the page URL.

```bash
./deploy/build_onion_assets.sh
```

### Option 2: Absolute .onion URL
Pass your `.onion` domain to the script:

```bash
./deploy/build_onion_assets.sh "http://yourhiddenserviceaddress.onion/"
```

### Option 3: Manual Environment Variable
```bash
export VITE_ASSET_BASE="http://yourhiddenserviceaddress.onion/"
npm run build
```

## Verify Deployment

After deploy, test your hidden service:

```bash
# Inside Tor or via Tor browser to your .onion address:
curl -x socks5h://127.0.0.1:9050 http://yourhiddenserviceaddress.onion/
curl -x socks5h://127.0.0.1:9050 http://yourhiddenserviceaddress.onion/login
curl -x socks5h://127.0.0.1:9050 http://yourhiddenserviceaddress.onion/dashboard
```

Check Nginx and PHP-FPM logs:

```bash
sudo tail -f /var/log/nginx/project-x.error.log
sudo tail -f /var/log/nginx/project-x.access.log
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| CSS/JS not loading | Verify `VITE_ASSET_BASE` matches your domain. Check browser DevTools → Network for 404s. Ensure `public/build/` exists. |
| Routes return 404 | Verify Nginx `try_files $uri $uri/ /index.php?$query_string;` is in the config. Check that `.onion` is in `server_name`. |
| HTTPS redirect | Confirm `AppServiceProvider.php` skips `URL::forceScheme()` for `.onion` hosts (already patched). |
| PHP-FPM socket not found | Check `sudo ls /run/php/` and update `fastcgi_pass` path in Nginx config if needed. |

## Rollback (if needed)

```bash
cd /var/www/project-x
git revert HEAD  # or git reset --hard <commit-hash>
./deploy/build_onion_assets.sh
sudo systemctl reload nginx
```
