# BizHub.by — TODO

## Готово
- [x] Дизайн из `1.html` (hero dark, glass-navbar, footer, card-3d, glow-orbs, CTA)
- [x] Цветовая система: blue→primary, gray→slate, green→emerald, yellow→amber
- [x] BYN → Br во всех ценах
- [x] Форма обратной связи: роут, контроллер, миграция, Filament-resource
- [x] Поиск `?search=`, фильтры работают
- [x] Парсер onliner.by + импортёр: JSONL, дедупликация, авто-категории
- [x] Приоритет partnership перед buy_business
- [x] Переклассификация 241 объявления, 13 спам-займов удалено
- [x] Картинки: 154 объявления с изображениями
- [x] `whereHas('images')` — без картинок на главную не попадают
- [x] SEO-title на страницах типов
- [x] OPcache отключён
- [x] `pt-24` на `<main>`
- [x] «Все категории» и «Три простых шага» на главной
- [x] Порядок секций: Categories → Recent (тёмный) → Steps (светлый) → CTA
- [x] Кастомный README.md
- [x] Оставшиеся `<span>BYN</span>` → Br
- [x] Механизм статей: Article (модель, миграция, Filament, роут, blade)
- [x] Статьи для футера: about, terms, privacy + сидеры
- [x] Ссылки футера → article-роуты
- [x] Admin: миниатюры изображений в таблице и просмотре
- [x] Admin: ширина на всю страницу (CSS-переопределение `.fi-width-7xl`)
- [x] Форма фидбэка: создание + админка
- [x] CDN убран: Tailwind v3 + Alpine → Vite 5, `postcss.config.js`
- [x] Раздел «Жалобы» (ReportResource) — модерация
- [x] `postcss.config.js` — билд Tailwind через PostCSS
- [x] Главная восстановлена после перехода на Vite (64 KB CSS)
- [x] В админке Filament отображаются фото в списке и в просмотре объявления
- [x] Роль пользователя при регистрации интегрирована в систему ролей Laravel (Spatie Permission)
- [x] Чат между продавцами и покупателями (Conversation + Message, real-time ready)

## Осталось

- [] В бредкрамсах пропущен раздел где все статьи: Главная / Какие риски есть при покупке готового бизнеса?
- [] В статьях ссылки почему то имеют такой вид: http://bizhub/article/%7B%7B%20route('article.show',%20'kak-proverit-biznes-na-dolgi')%20%7D%7D

