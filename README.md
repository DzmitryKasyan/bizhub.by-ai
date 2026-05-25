# BizHub.by

Платформа для покупки и продажи бизнеса в Беларуси.

**Стек:** Laravel 12, PHP 8.4, MySQL 8, Redis, Meilisearch, Tailwind CSS, Alpine.js

## Быстрый старт (локально)

### 1. Клонировать
```bash
git clone <repo-url> bizhub
cd bizhub
```

### 2. Переменные окружения
```bash
cp .env.example .env
# Отредактировать .env: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 3. Запустить Docker-сервисы
```bash
docker compose up -d
```

### 4. Установить зависимости
```bash
docker compose exec app composer install
```

### 5. Сгенерировать ключ + миграции + сиды
```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

### 6. Создать storage link
```bash
docker compose exec app php artisan storage:link
```

### 7. Открыть в браузере
```
http://bizhub/
```

## Команды

| Команда | Назначение |
|---|---|
| `php artisan onliner:parse` | Спарсить объявления с onliner.by в JSONL |
| `php artisan onliner:import` | Импорт из JSONL в БД |
| `php artisan exchange:update` | Обновить кеш курсов валют (НБРБ + CoinGecko) |
| `php artisan optimize:clear` | Сброс всех кэшей |
| `php artisan migrate:fresh --seed` | Пересоздать БД с тестовыми данными |
| `php artisan onliner:import` | Импорт объявлений из JSONL (после migrate:fresh) |

## Cron

Для периодических задач (курсы валют, очистка старых токенов и т.д.) Laravel использует встроенный планировщик.

**Автоматически (рекомендуется):**
```bash
bash install-cron.sh
```

**Вручную — добавить в crontab:**
```cron
* * * * * cd /path/to/bizhub && php artisan schedule:run >> storage/logs/cron.log 2>&1
```

Это запускает планировщик каждую минуту. Сами задачи описаны в `routes/console.php`:
- `exchange:update` — каждый час (обновление курсов валют)

## Структура

```
bizhub/
├── app/
│   ├── Console/Commands/   # onliner:parse, onliner:import
│   ├── Enums/              # Currency, ListingType, ListingStatus…
│   ├── Filament/           # Админ-панель (Filament)
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/            # app.blade.php (основной), dashboard.blade.php
│   ├── listings/           # Каталог и карточка объявления
│   ├── dashboard/          # Личный кабинет
│   ├── auth/               # Логин и регистрация
│   └── partials/           # listing-card, аналитика
├── routes/web.php
├── docker-compose.yml
└── storage/app/
    ├── onliner_listings.jsonl  # Результат парсинга
    └── public/listings/        # Изображения объявлений
```

## Админ-панель

URL: `http://bizhub/admin`

Логин и пароль — из `.env` (`ADMIN_EMAIL`, `ADMIN_PASSWORD`), или создаются сидером `AdminUserSeeder`.

## Парсинг Onliner

```bash
# 1. Спарсить 5 страниц (250 объявлений)
php artisan onliner:parse

# 2. Импортировать в БД
php artisan onliner:import
```

Парсер сохраняет результат в `storage/app/onliner_listings.jsonl`. При повторном запуске дубликаты пропускаются (по `topic_id`).
