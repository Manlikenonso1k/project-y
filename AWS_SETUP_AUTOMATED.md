# AWS Automated Setup Guide

## Step 1: On Your Local Server (Current Dev Box)

Export the database and env:

```bash
cd /var/www/projectx/project-x

# Export MySQL database
mysqldump -u root -p projectx > database-backup.sql
# (Enter MySQL password when prompted)

# View your current env
cat .env
```

Copy all the email/telegram values from `.env`:
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `ADMIN_EMAIL`
- `TELEGRAM_BOT_TOKEN`
- `TELEGRAM_CHAT_ID`
- Your onion URL (if applicable)

## Step 2: Push to GitHub

```bash
cd /var/www/projectx/project-x

git add database-backup.sql AWS_SETUP_AUTOMATED.md
git commit -m "Add database backup and AWS setup guide"
git push origin main
```

## Step 3: On AWS EC2 (SSH into your instance)

```bash
# Clone the repo
git clone https://github.com/Manlikenonso1k/project-x.git
cd project-x

# Install dependencies
sudo apt update && sudo apt upgrade -y
sudo apt install -y docker.io docker-compose git

# Add user to docker group
sudo usermod -aG docker ubuntu
exit

# Reconnect
ssh -i projectx-aws-key.pem ubuntu@YOUR_EC2_PUBLIC_DNS
```

## Step 4: Setup Docker Environment

```bash
cd project-x

# Copy env template
cp docker/.env.example .env

# Edit with your values (nano .env)
nano .env
```

**Update these lines in .env:**
```
MAIL_USERNAME=leeadrian994@gmail.com
MAIL_PASSWORD=alkyeycilwljemsh
ADMIN_EMAIL=leeadrian994@gmail.com
TELEGRAM_BOT_TOKEN=8553841666:AAFvLOLdcV4JvQAwUPKAyAFB2_fr0TBOB9U
TELEGRAM_CHAT_ID=1963161428
```

Save with: `Ctrl+X`, `Y`, `Enter`

## Step 5: Start Docker & Import Database

```bash
# Fix Dockerfile (replace libmysqlclient-dev with libmariadb-dev)
nano Dockerfile
# Change line 9: libmysqlclient-dev → libmariadb-dev
# Save with Ctrl+X, Y, Enter

# Start Docker
docker compose up -d

# Wait 30 seconds for database to be ready, then import backup
docker compose exec db mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS projectx;"
docker compose exec db mysql -u root -p projectx < database-backup.sql

# Run migrations
docker compose exec app php artisan migrate --force
```

## Step 6: Verify Everything Works

```bash
# Check running services
docker compose ps

# View logs
docker compose logs app

# Test the app
curl http://localhost

# Check if email/Telegram services are working
# Place a test order through the web interface
```

## Step 7: (Optional) Keep Your Onion URL the Same

If you're running Tor on AWS too:
```bash
# SSH into AWS and set up Tor
sudo apt install -y tor

# Copy your existing onion keys from local to AWS
scp -r /var/lib/tor/onion/ ubuntu@YOUR_EC2_PUBLIC_DNS:~/

# On AWS:
sudo cp -r ~/onion/* /var/lib/tor/
sudo chown -R debian-tor:debian-tor /var/lib/tor/onion
sudo systemctl restart tor
```

## Step 8: Install GitHub Copilot CLI (Optional)

Copilot CLI is lightweight (~100MB). To install on AWS:

```bash
# Install Node.js (if not present)
sudo apt install -y nodejs npm

# Install Copilot CLI globally
npm install -g @github/gh-cli

# Or via curl
curl https://github.com/copilot/releases/download/latest/copilot-linux -o copilot
chmod +x copilot
sudo mv copilot /usr/local/bin/
```

---

## Troubleshooting

### Docker compose not found
Use: `docker compose up -d` (no hyphen)

### Database import fails
Make sure MySQL container is running first:
```bash
docker compose up -d db
sleep 10
docker compose exec db mysql -u root -p projectx < database-backup.sql
```

### Ports not accessible
Update AWS Security Group to allow:
- Port 80 (HTTP)
- Port 443 (HTTPS)
- Port 22 (SSH)

### Email/Telegram not working
Verify in AWS `.env` that credentials match exactly:
```bash
docker compose exec app php artisan tinker
>>> config('mail.from')
>>> env('TELEGRAM_BOT_TOKEN')
```

---

Done! Your Project X is now on AWS with all data migrated. 🚀
