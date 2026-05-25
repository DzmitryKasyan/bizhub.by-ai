# Дизайн-спецификация: Brutalist Redesign (Paper + Red)

**Дата:** 2026-05-14  
**Статус:** Утверждён  
**Затрагивает:** все Blade-шаблоны (`resources/views/**`), конфигурацию Tailwind

---

## 1. Эстетическое направление

**Brutalist / Смелый минимализм** с элементами швейцарского дизайна.

- Светлый фон, жирные чёрные линии вместо теней
- Минимум цветов: чёрный, белый, серый + красный акцент
- Крупная типографика, верхний регистр для заголовков секций
- Контраст между гротеском (DM Sans) и моноширинным (JetBrains Mono)

---

## 2. Дизайн-токены

### 2.1 Цвета

| Токен | HEX | Tailwind | Назначение |
|-------|-----|----------|------------|
| `--color-paper` | `#fafafa` | `paper` | Фон страницы |
| `--color-ink` | `#0a0a0a` | `ink` | Основной текст |
| `--color-muted` | `#525252` | `muted` | Второстепенный текст |
| `--color-faint` | `#a3a3a3` | `faint` | Мета-данные, плейсхолдеры |
| `--color-accent` | `#ef4444` | `accent` | Акцент (red-500) |
| `--color-accent-hover` | `#dc2626` | `accent-hover` | Акцент при наведении (red-600) |
| `--color-border` | `#e5e5e5` | `border` | Стандартные границы |
| `--color-border-strong` | `#0a0a0a` | `border-strong` | Жирные/акцентные границы |

### 2.2 Типографика

| Роль | Шрифт | Weight | Особенности |
|------|-------|--------|-------------|
| Заголовки (h1-h2) | DM Sans | 800 | `letter-spacing: -0.02em`, верхний регистр |
| Заголовки секций | DM Sans | 800 | `text-transform: uppercase`, `letter-spacing: 0.5px` |
| Подзаголовки (h3-h4) | DM Sans | 700 | |
| Тело текста | DM Sans | 400-500 | `line-height: 1.5` |
| Навигация | DM Sans | 600 | `text-transform: uppercase` |
| Мета-данные, код, цифры | JetBrains Mono | 400 | Моноширинный, для цен, валют, дат |
| Комментарии (`//`) | JetBrains Mono | 400 | `color: accent` или `color: faint` |

### 2.3 Границы и скругления

- Стандартная граница: `2px solid #e5e5e5`
- Жирная/акцентная граница: `2px solid #0a0a0a` (хедер, карточки ТОП)
- Красная акцентная линия: `3px solid #ef4444` (секционные разделители)
- Скругления: **4px максимум** (кнопки, пилюли). Карточки и секции — без скруглений.

### 2.4 Пространство

- Щедрые вертикальные отступы: `py-40` для hero, `py-20` для секций
- Горизонтальные: стандартный контейнер `max-w-7xl`, внутренние отступы `px-8`
- Карточки: отступы `p-4`
- Сетка: `gap-4` / `gap-6`

### 2.5 Тени

**Не используются.** Границы заменяют тени везде.

---

## 3. Компоненты

### 3.1 Хедер (навигация)

- `bg-white`, `border-b-2 border-ink` (жирная чёрная линия)
- Логотип: "BizHub" (DM Sans 800) + ".by" (JetBrains Mono, accent)
- Ссылки: uppercase, DM Sans 600, цвет ink
- Разделитель: вертикальная линия `w-0.5 h-5 bg-border`
- Кнопка «+ РАЗМЕСТИТЬ»: DM Sans 600, цвет ink

### 3.2 Hero-секция (главная)

- `bg-paper`, `border-b-[3px] border-ink`
- Мета-комментарий: `// платформа для сделок` (JetBrains Mono, accent, uppercase, tracking-widest)
- Заголовок: DM Sans 800, крупно (text-5xl), верхний регистр, `leading-[0.95]`
- Подзаголовок: DM Sans 400, цвет muted
- Кнопки: граница 2px, без скруглений, uppercase:
  - Основная: `bg-ink text-white border-ink`
  - Второстепенная: `bg-transparent text-ink border-ink`
- Статистика: DM Sans 800 (цифры) + JetBrains Mono (подписи), разделители — вертикальные линии

### 3.3 Карточка объявления

- `border-2 border-[#e5e5e5] bg-white`
- Изображение: `aspect-video`, сверху граница 2px
- Тип объявления: JetBrains Mono, accent (для featured) или faint (обычные), формат `// тип`
- Заголовок: DM Sans 700, `text-sm`
- Цена: DM Sans 800, `text-xl`
- Валюта: JetBrains Mono, faint
- Мета (локация, просмотры): JetBrains Mono, muted, разделены границей сверху
- ТОП-карточка: `border-ink` (жирная чёрная граница)

### 3.4 Страница каталога

- Боковая панель фильтров: `border-r-2 border-ink`, `bg-white`
- Заголовок фильтров: `// фильтры` (JetBrains Mono, faint)
- Группы фильтров: заголовок uppercase DM Sans 700
- Поля ввода: `border-2 border-[#e5e5e5]`, JetBrains Mono
- Кнопка «Применить»: `bg-ink text-white border-2 border-ink`, uppercase
- Активные фильтры (пилюли): `border-2 border-accent`, JetBrains Mono, accent
- Сортировка: JetBrains Mono, активный — жирный ink
- Заголовок каталога: красная полоска слева + uppercase DM Sans 800

### 3.5 Футер

- `bg-ink`, `border-t-[3px] border-accent`
- Логотип: DM Sans 800 (paper) + JetBrains Mono (accent)
- Копирайт: JetBrains Mono, muted
- Категории ссылок: заголовок JetBrains Mono (путь-стиль, напр. `/каталог`)
- Ссылки: DM Sans 600, faint

### 3.6 Flash-сообщения

- `border-2` вместо теней
- Успех: `border-accent` (красный вместо зелёного — единый акцент)
- Ошибка: `border-ink`

---

## 4. Ограничения

- **Ссылки и тексты не менять** — только визуальное оформление
- **Структура Blade-шаблонов сохраняется** — меняются только CSS-классы и inline-стили
- **Tailwind CDN с inline-конфигом** — без Vite-сборки
- **Шрифты подключаются через Google Fonts** (DM Sans, JetBrains Mono)
- **JavaScript (Alpine.js) не трогаем** — только CSS

---

## 5. Файлы, подлежащие изменению

| Файл | Что меняется |
|------|-------------|
| `resources/views/layouts/app.blade.php` | Tailwind-конфиг, шрифты, хедер, футер, flash-сообщения |
| `resources/views/home.blade.php` | Hero, секции, статистика, CTA |
| `resources/views/partials/listing-card.blade.php` | Карточка: границы, типографика, бейджи |
| `resources/views/listings/index.blade.php` | Каталог: фильтры, тулбар, сетка |
| `resources/views/listings/show.blade.php` | Детальная: галерея, метрики, хлебные крошки |

Остальные шаблоны (auth, dashboard, blog, pages) — по остаточному принципу, базовое перекрашивание.

---

## 6. Критерии приёмки

- [ ] Все цвета соответствуют токенам (paper, ink, muted, faint, accent)
- [ ] DM Sans используется для заголовков и основного текста
- [ ] JetBrains Mono используется для мета-данных, цен, валют
- [ ] Тени заменены на границы (`border-2`)
- [ ] Нет скруглений больше 4px
- [ ] Hero-секция с красной мета-строкой `//` и крупным заголовком
- [ ] Карточки с границами 2px, featured — с жирной чёрной
- [ ] Футер чёрный с красной полосой сверху
- [ ] Ссылки и тексты не изменены
- [ ] Страницы не сломаны (визуальная проверка)
