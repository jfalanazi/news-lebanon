#!/usr/bin/env bash
# نشر «نشرة لبنان» على السيرفر (الماك البعيد)
# يسحب آخر نسخة من GitHub ويعيد بناء الحاويات ويهيّئ التطبيق.
# الاستخدام على السيرفر:  ./deploy.sh   (أو: bash deploy.sh)
set -euo pipefail
cd "$(dirname "$0")"

echo "→ (1/4) سحب آخر تحديث من GitHub…"
git pull origin main

echo "→ (2/4) بناء وتشغيل الحاويات…"
docker compose up -d --build

echo "→ (3/4) تهيئة التطبيق…"
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan storage:link || true
docker compose exec -T app php artisan optimize:clear

echo "→ (4/4) الحالة:"
docker compose ps

echo "✓ تم النشر. اللوحة على: http://<عنوان-السيرفر>:8080/admin"
