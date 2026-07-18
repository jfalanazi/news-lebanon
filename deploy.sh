#!/usr/bin/env bash
# نشر «نشرة لبنان» على السيرفر (الماك البعيد)
# يسحب آخر نسخة، يبني الحاويات، يهيّئ البيئة والاعتماديات، ويشغّل التطبيق.
# الاستخدام على السيرفر:  ./deploy.sh   (أو: bash deploy.sh)
set -euo pipefail
cd "$(dirname "$0")"

echo "→ (1/6) سحب آخر تحديث من GitHub…"
git pull origin main || true

echo "→ (2/6) بناء وتشغيل الحاويات…"
docker compose up -d --build

# تهيئة ملف البيئة إن لزم
if [ ! -f src/.env ]; then
  echo "→ إنشاء src/.env من القالب src/.env.docker…"
  cp src/.env.docker src/.env
fi

echo "→ (3/6) تركيب اعتماديات PHP (ينشئ vendor)…"
docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# توليد مفتاح التطبيق إن كان فارغًا
if ! grep -q '^APP_KEY=base64' src/.env; then
  echo "→ توليد مفتاح التطبيق…"
  docker compose exec -T app php artisan key:generate --force
fi

echo "→ (4/6) الترحيلات…"
docker compose exec -T app php artisan migrate --force

echo "→ (5/6) الربط والأصول والتحسين…"
docker compose exec -T app php artisan storage:link || true
docker compose exec -T app php artisan filament:assets || true
docker compose exec -T app php artisan optimize:clear

echo "→ (6/6) الحالة:"
docker compose ps

echo ""
echo "✓ تم النشر. اللوحة: http://localhost:8080/admin"
echo "  أنشئ مستخدمًا (أول مرة): docker compose exec app php artisan make:filament-user"
