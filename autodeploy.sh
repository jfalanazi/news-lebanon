#!/usr/bin/env bash
# نشر تلقائي: يفحص GitHub، وإذا فيه commit جديد يسحبه وينشره.
# يُشغّل دوريًا عبر cron على الماك ميني (مثلاً كل دقيقتين).
set -euo pipefail

# مسارات docker/git لبيئة cron المحدودة (Apple Silicon + Intel)
export PATH="/opt/homebrew/bin:/usr/local/bin:/usr/bin:/bin:$PATH"

cd "$(dirname "$0")"

git fetch origin main -q
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/main)

# لا جديد → اخرج بهدوء
if [ "$LOCAL" = "$REMOTE" ]; then
  exit 0
fi

echo "===== $(date) — تحديث جديد ($REMOTE) — جارِ النشر ====="
git merge --ff-only origin/main

# أعِد تركيب الاعتماديات فقط إن تغيّر composer.lock
if git diff --name-only "$LOCAL" "$REMOTE" | grep -q '^src/composer.lock'; then
  echo "→ composer.lock تغيّر — تركيب الاعتماديات…"
  docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

# ترحيلات إن وُجدت
docker compose exec -T app php artisan migrate --force || true

# أعِد بناء الحاويات فقط إن تغيّر Dockerfile/compose
if git diff --name-only "$LOCAL" "$REMOTE" | grep -qE '^docker/|^docker-compose'; then
  echo "→ إعداد Docker تغيّر — إعادة بناء…"
  docker compose up -d --build || true
fi

docker compose exec -T app php artisan optimize:clear || true
docker compose restart scheduler || true

echo "===== $(date) — ✓ تم النشر ====="
