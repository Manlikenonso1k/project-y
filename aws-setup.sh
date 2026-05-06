#!/bin/bash
# AWS Automated Setup Script
# Run this on AWS EC2 after cloning the repo

set -e

echo "🚀 Starting AWS Project X Setup..."

# Step 1: Update system
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Step 2: Install Docker
echo "🐳 Installing Docker & Docker Compose..."
sudo apt install -y docker.io docker-compose git

# Step 3: Add user to docker
echo "👤 Setting up Docker permissions..."
sudo usermod -aG docker ubuntu

# Step 4: Fix Dockerfile
echo "🔧 Fixing Dockerfile..."
sed -i 's/libmysqlclient-dev/libmariadb-dev/g' Dockerfile

# Step 5: Copy env
echo "⚙️ Setting up environment..."
cp docker/.env.example .env
echo ""
echo "⚠️  IMPORTANT: Edit .env with your values!"
echo "Run: nano .env"
echo ""
echo "Update these lines:"
echo "  MAIL_USERNAME=your-email@gmail.com"
echo "  MAIL_PASSWORD=your-app-password"
echo "  ADMIN_EMAIL=your-admin@gmail.com"
echo "  TELEGRAM_BOT_TOKEN=your-token"
echo "  TELEGRAM_CHAT_ID=your-chat-id"
echo ""
read -p "Press Enter after editing .env..."

# Step 6: Start Docker
echo "🏗️ Building and starting Docker containers..."
docker compose up -d

echo "⏳ Waiting for database to be ready..."
sleep 20

# Step 7: Import database
echo "📥 Importing database backup..."
if [ -f "database-backup.sql" ]; then
    docker compose exec -T db mysql -u root -p"${DB_PASSWORD}" projectx < database-backup.sql
    echo "✅ Database imported"
else
    echo "⚠️  No database-backup.sql found. Running fresh migrations..."
fi

# Step 8: Run migrations
echo "🗄️ Running Laravel migrations..."
docker compose exec app php artisan migrate --force

# Step 9: Verify
echo ""
echo "✅ Setup Complete!"
echo ""
echo "Running services:"
docker compose ps

echo ""
echo "🌐 Your app should be accessible at: http://YOUR_EC2_PUBLIC_IP"
echo ""
echo "Next steps:"
echo "1. Visit http://YOUR_EC2_PUBLIC_IP in your browser"
echo "2. Test placing an order"
echo "3. Verify email and Telegram notifications work"
echo ""
echo "To view logs:"
echo "  docker compose logs -f app"
echo ""
echo "To stop services:"
echo "  docker compose down"
