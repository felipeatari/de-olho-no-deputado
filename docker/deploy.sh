#!/bin/bash

echo ""
echo "==> 🚀 Atualizando repositório..."
git pull origin main

echo ""
echo "==> 🧹 Limpando containers antigos e preparando ambiente Docker..."
docker compose down --remove-orphans
docker compose build --no-cache
docker image prune --force
docker compose up -d --remove-orphans

echo ""
echo "==> 📦 Instalando dependências PHP (composer)..."
docker exec docker-app-1 composer install --no-dev --optimize-autoloader

echo ""
echo "==> 💻 Instalando dependências JavaScript (npm)..."
docker exec docker-app-1 npm ci
docker exec docker-app-1 npm run build
docker exec docker-app-1 npm prune --omit=dev

echo ""
echo "==> 🔒 Colocando app em manutenção..."
docker exec docker-app-1 php artisan down

echo ""
echo "==> 🧼 Limpando caches antigos do Laravel..."
docker exec docker-app-1 php artisan cache:clear
docker exec docker-app-1 php artisan config:clear
docker exec docker-app-1 php artisan route:clear
docker exec docker-app-1 php artisan view:clear
docker exec docker-app-1 php artisan optimize:clear

echo ""
echo "==> ⏳ Aguardando MySQL iniciar..."
until docker exec mysql mysqladmin ping -h "127.0.0.1" --silent; do
  sleep 2
done
echo "==> ✅ MySQL está pronto."

echo ""
echo "==> 🛠️ Executando migrations..."
docker exec docker-app-1 php artisan migrate --force

echo ""
echo "==> 🔧 Recriando caches e otimizando aplicação..."
docker exec docker-app-1 php artisan config:cache
docker exec docker-app-1 php artisan route:cache
docker exec docker-app-1 php artisan view:cache
docker exec docker-app-1 php artisan optimize

echo ""
echo "==> 🔗 Criando link simbólico do storage..."
docker exec docker-app-1 php artisan storage:link

echo ""
echo "==> 🔓 Tirando app de manutenção..."
docker exec docker-app-1 php artisan up

echo ""
echo "✅ Deploy finalizado com sucesso!"
