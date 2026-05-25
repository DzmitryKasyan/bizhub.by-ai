#!/usr/bin/env bash
# install-cron.sh — добавить cron-задачу для Laravel scheduler
# Запуск: bash install-cron.sh [путь-к-проекту]
# По умолчанию путь — текущая директория.

set -euo pipefail

PROJECT_DIR="${1:-$(pwd)}"
CRON_LINE="* * * * * cd ${PROJECT_DIR} && php artisan schedule:run >> storage/logs/cron.log 2>&1"
MARKER="# bizhub-scheduler"

echo "==> Project dir: ${PROJECT_DIR}"
echo "==> Cron line:   ${CRON_LINE}"

# Удалить предыдущую запись с маркером, если есть
crontab -l 2>/dev/null | grep -v "${MARKER}" | crontab - || true

# Добавить новую
(crontab -l 2>/dev/null; echo "${CRON_LINE} ${MARKER}") | crontab -

echo "==> Cron installed. Current crontab:"
echo "----------------------------------------"
crontab -l | grep "${MARKER}" || echo "(empty — something went wrong)"
echo "----------------------------------------"
echo "Done."
