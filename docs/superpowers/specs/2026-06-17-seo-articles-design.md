# SEO-контент: 50 статей для начинающих предпринимателей Беларуси

## Цель

Создать кластер из 50 SEO-статей под вопросы новичков в бизнесе (регион — Беларусь), наполнить ими модуль `Articles`, связать статьи между собой и перенести на продакшен через `ArticleSeeder`.

## Контекст проекта

- Модель: `App\Models\Article` (`title`, `slug`, `content`, `meta_description`, `is_published`).
- Публичный роут: `/article/{article:slug}` (`article.show`).
- Админка: Filament-ресурс `ArticleResource`.
- Размещение контента: `database/seeders/ArticleSeeder.php` (существующий сидер с 12 статьями сохраняется, новые 50 добавляются в конец массива).

## Категории статей

Помимо самих статей добавляем полноценный справочник категорий.

### Сущности

- Модель `App\Models\ArticleCategory` (`id`, `name`, `slug`, `timestamps`).
- Связь: `ArticleCategory hasMany Article`, `Article belongsTo ArticleCategory`.
- Поле `articles.article_category_id` (nullable → required в форме админки).

### Категории

| Категория | Slug | Описание |
|-----------|------|----------|
| Старт в бизнесе | `start-v-biznese` | Регистрация, выбор ниши, налоги, первые шаги |
| Покупка бизнеса | `pokupka-biznesa` | Выбор, оценка, проверка, сделка |
| Инвестиции и финансирование | `investicii-i-finansirovanie` | Инвесторы, заём, господдержка |
| Развитие и управление | `razvitie-i-upravlenie` | Масштабирование, партнёрство, франшиза, недвижимость, оборудование |
| Продажа бизнеса | `prodazha-biznesa` | Подготовка, оценка, сделка, выход |
| О сервисе | `o-servise` | Служебные страницы: о проекте, правила, политика |

### Распределение новых 50 статей по категориям

- Старт в бизнесе — статьи 1–10
- Покупка бизнеса — статьи 11–20
- Инвестиции и финансирование — статьи 21–30
- Развитие и управление — статьи 31–40
- Продажа бизнеса — статьи 41–50

### Распределение существующих 12 статей

| Существующий slug | Категория |
|---------------------|-----------|
| `about` | О сервисе |
| `terms` | О сервисе |
| `privacy` | О сервисе |
| `trust-management` | Развитие и управление |
| `sell-business` | Продажа бизнеса |
| `buy-business` | Покупка бизнеса |
| `seek-investment` | Инвестиции и финансирование |
| `offer-investment` | Инвестиции и финансирование |
| `franchise` | Развитие и управление |
| `partnership` | Развитие и управление |
| `real-estate` | Развитие и управление |
| `equipment` | Развитие и управление |

### Админка

- Новый Filament-ресурс `ArticleCategoryResource`:
  - список категорий;
  - создание/редактирование (`name`, авто-`slug`);
  - удаление с проверкой на наличие статей.
- В `ArticleForm` добавляется обязательный `Select::make('article_category_id')` с выбором категории.
- В `ArticlesTable` добавляется колонка `articleCategory.name` и фильтр по категории.

## Структура кластера

Гибридный подход: **5 этапов воронки пользователя × 10 статей**, внутри каждого этапа — смесь типов запросов (что-это, how-to, сколько-стоит, сравнение, ошибки/чек-лист).

Длина статей — смешанная:
- **Короткие SEO-ответы**: 300–500 слов (низкая/средняя частотность).
- **Развёрнутые статьи**: 1000–1500 слов (высокая частотность, сложные темы).

## Частотность

- **Высокая (H)**: широкие массовые запросы, типа «как открыть ИП», «как купить бизнес».
- **Средняя (M)**: уточняющие запросы, конкретные процедуры.
- **Низкая (L)**: узкие, но конверсионные темы.

## Список из 50 вопросов

### 1. Старт в бизнесе (10 статей)

| # | Вопрос (заголовок статьи) | Частотность | Тип запроса | Slug |
|---|---------------------------|-------------|-------------|------|
| 1 | Как открыть ИП в Беларуси пошагово? | H | how-to | `kak-otkryt-ip-v-belarusi-poshagovo` |
| 2 | Что лучше выбрать: ИП или ООО? | H | сравнение | `ip-ili-ooo-chto-luchshe` |
| 3 | Сколько денег нужно для старта бизнеса в Беларуси? | H | сколько-стоит | `skolko-deneg-nuzhno-dlya-starta-biznesa` |
| 4 | Как выбрать нишу для бизнеса новичку? | H | how-to | `kak-vybrat-nishu-dlya-biznesa-novichku` |
| 5 | Какие налоги платит ИП в Беларуси? | H | что-это | `kakie-nalogi-platit-ip-v-belarusi` |
| 6 | Как составить бизнес-план для малого бизнеса? | M | how-to | `kak-sostavit-biznes-plan` |
| 7 | Где взять идею для бизнеса в Беларуси? | M | how-to | `gde-vzyat-ideyu-dlya-biznesa` |
| 8 | Какие документы нужны для регистрации ООО? | M | чек-лист | `dokumenty-dlya-registracii-ooo` |
| 9 | Какие ошибки совершают начинающие предприниматели? | M | ошибки | `oshibki-nachinayushih-predprinimateley` |
| 10 | Можно ли совмещать работу и свой бизнес? | L | что-это | `sovmeshchat-rabotu-i-biznes` |

### 2. Покупка бизнеса (10 статей)

| # | Вопрос (заголовок статьи) | Частотность | Тип запроса | Slug |
|---|---------------------------|-------------|-------------|------|
| 11 | Как купить готовый бизнес в Беларуси? | H | how-to | `kak-kupit-gotovyy-biznes-v-belarusi` |
| 12 | Сколько стоит купить бизнес в Беларуси? | H | сколько-стоит | `skolko-stoit-kupit-biznes` |
| 13 | Как оценить стоимость бизнеса перед покупкой? | H | how-to | `kak-ocenit-stoimost-biznesa` |
| 14 | Что такое due diligence при покупке бизнеса? | M | что-это | `chto-takoe-due-diligence` |
| 15 | Как проверить бизнес на долги и риски? | H | how-to | `kak-proverit-biznes-na-dolgi` |
| 16 | Какие документы нужны для покупки ООО? | M | чек-лист | `dokumenty-dlya-pokupki-ooo` |
| 17 | Можно ли купить бизнес в рассрочку? | M | что-это | `kupit-biznes-v-rassrochku` |
| 18 | В чём разница между покупкой ИП и ООО? | L | сравнение | `pokupka-ip-ili-ooo` |
| 19 | Как договориться о цене при покупке бизнеса? | M | how-to | `kak-dogovoritsya-o-cene-pri-pokupke` |
| 20 | Какие риски есть при покупке готового бизнеса? | M | ошибки | `riski-pri-pokupke-gotovogo-biznesa` |

### 3. Инвестиции и финансирование (10 статей)

| # | Вопрос (заголовок статьи) | Частотность | Тип запроса | Slug |
|---|---------------------------|-------------|-------------|------|
| 21 | Как привлечь инвестиции в бизнес? | H | how-to | `kak-privlech-investicii-v-biznes` |
| 22 | Где найти инвестора в Беларуси? | H | how-to | `gde-nayti-investora-v-belarusi` |
| 23 | Что такое бизнес-ангел? | M | что-это | `chto-takoe-biznes-angel` |
| 24 | Как написать инвестиционное предложение? | M | how-to | `kak-napisat-investicionnoe-predlozhenie` |
| 25 | В чём разница между долевым и займовым финансированием? | M | сравнение | `dolevoe-vs-zaymovoe-finansirovanie` |
| 26 | Как инвестировать в бизнес с минимальным риском? | M | how-to | `kak-investirovat-s-minimalnym-riskom` |
| 27 | Какие есть программы господдержки стартапов в РБ? | M | что-это | `gosudarstvennaya-podderzhka-startapov-rb` |
| 28 | Сколько можно заработать на инвестициях в бизнес? | L | сколько-стоит | `skolko-mozhno-zarabotat-na-investiciyah` |
| 29 | Какие ошибки совершают при привлечении инвестиций? | L | ошибки | `oshibki-pri-privlechenii-investiciy` |
| 30 | Что такое краудлендинг и доступен ли он в Беларуси? | L | что-это | `kraudlending-v-belarusi` |

### 4. Развитие, управление и партнёрство (10 статей)

| # | Вопрос (заголовок статьи) | Частотность | Тип запроса | Slug |
|---|---------------------------|-------------|-------------|------|
| 31 | Как масштабировать бизнес в Беларуси? | M | how-to | `kak-masshtabirovat-biznes` |
| 32 | Как найти бизнес-партнёра? | M | how-to | `kak-nayti-biznes-partnera` |
| 33 | Что такое франшиза и как её купить? | H | что-это/how-to | `chto-takoe-franshiza-i-kak-ee-kupit` |
| 34 | Сколько стоит франшиза в Беларуси? | M | сколько-стоит | `skolko-stoit-franshiza-v-belarusi` |
| 35 | Что такое доверительное управление бизнесом? | L | что-это | `chto-takoe-doveritelnoe-upravlenie-biznesom` |
| 36 | Как купить коммерческую недвижимость для бизнеса? | M | how-to | `kak-kupit-kommercheskuyu-nedvizhimost` |
| 37 | Где купить б/у оборудование для бизнеса? | L | how-to | `gde-kupit-bu-oborudovanie-dlya-biznesa` |
| 38 | Как нанимать сотрудников в ИП? | M | how-to | `kak-nanimat-sotrudnikov-v-ip` |
| 39 | Как автоматизировать бизнес-процессы? | L | how-to | `kak-avtomatizirovat-biznes-processy` |
| 40 | Как повысить прибыль малого бизнеса? | H | how-to | `kak-povysit-pribyl-malogo-biznesa` |

### 5. Продажа и выход из бизнеса (10 статей)

| # | Вопрос (заголовок статьи) | Частотность | Тип запроса | Slug |
|---|---------------------------|-------------|-------------|------|
| 41 | Как продать бизнес в Беларуси? | H | how-to | `kak-prodat-biznes-v-belarusi` |
| 42 | Когда лучше продавать бизнес? | M | что-это | `kogda-luchshe-prodavat-biznes` |
| 43 | Как оценить бизнес для продажи? | H | how-to | `kak-ocenit-biznes-dlya-prodazhi` |
| 44 | Как подготовить бизнес к продаже? | M | how-to | `kak-podgotovit-biznes-k-prodazhe` |
| 45 | Где разместить объявление о продаже бизнеса? | M | how-to | `gde-razmestit-obyavlenie-o-prodazhe-biznesa` |
| 46 | Какие налоги при продаже бизнеса? | M | что-это | `nalogi-pri-prodazhe-biznesa` |
| 47 | Как передать дело новому владельцу? | L | how-to | `kak-peredat-delo-novomu-vladelcu` |
| 48 | Нужен ли брокер для продажи бизнеса? | L | сравнение | `nuzhen-li-broker-dlya-prodazhi-biznesa` |
| 49 | Как не стать жертвой мошенников при продаже бизнеса? | M | ошибки | `kak-ne-popatsya-moshennikam-pri-prodazhe` |
| 50 | Что делать после продажи бизнеса? | L | что-это | `chto-delat-posle-prodazhi-biznesa` |

## Связывание статей

Каждая статья содержит 2–5 контекстных внутренних ссылок вида:

```html
<a href="{{ route('article.show', 'slug') }}">...</a>
```

### Принципы перелинковки

1. **По смыслу**: ссылка должна естественно продолжать тему (например, из «Как купить бизнес» — на due diligence, оценку, проверку долгов).
2. **По воронке**: из статьи раннего этапа ведём на следующий этап (из «Старт» → «Покупка», из «Покупка» → «Развитие» и т.д.).
3. **По типу запроса**: из сравнения ссылаемся на связанные how-to, из how-to — на чек-листы и ошибки.
4. **Не перегружаем**: максимум 5 ссылок в одной статье, чтобы не уводить пользователя раньше времени.

### Примерные связи (фрагмент)

- `kak-otkryt-ip-v-belarusi-poshagovo` → `ip-ili-ooo-chto-luchshe`, `kakie-nalogi-platit-ip-v-belarusi`
- `kak-kupit-gotovyy-biznes-v-belarusi` → `kak-ocenit-stoimost-biznesa`, `kak-proverit-biznes-na-dolgi`, `chto-takoe-due-diligence`
- `kak-prodat-biznes-v-belarusi` → `kak-ocenit-biznes-dlya-prodazhi`, `kak-podgotovit-biznes-k-prodazhe`, `gde-razmestit-obyavlenie-o-prodazhe-biznesa`
- `chto-takoe-franshiza-i-kak-ee-kupit` → `skolko-stoit-franshiza-v-belarusi`, `kak-kupit-gotovyy-biznes-v-belarusi`
- `kak-privlech-investicii-v-biznes` → `kak-napisat-investicionnoe-predlozhenie`, `gde-nayti-investora-v-belarusi`, `chto-takoe-biznes-angel`

Полный линкбилдинг для всех 50 статей прописывается непосредственно в контенте при генерации сидера.

## Выходной артефакт

Файл `database/seeders/ArticleSeeder.php` с сохранением существующих 12 записей и добавлением 50 новых вида:

```php
[
    'title' => '...',
    'slug' => '...',
    'article_category_id' => 1, // ID категории
    'content' => '<h2>...</h2><p>...</p><a href="{{ route(\'article.show\', \'drugoy-slug\') }}">...</a> ...',
    'meta_description' => '...',
    'is_published' => true,
],
```

Сидер сначала создаёт категории через `updateOrCreate(['slug' => ...])`, получает их ID и подставляет в массив статей.

## Перенос на продакшен

```bash
php artisan db:seed --class=ArticleSeeder
```

Благодаря `updateOrCreate(['slug' => ...])` повторный запуск сидера не создаст дубликатов.

## Критерии приёмки

- [ ] Созданы миграция, модель `ArticleCategory` и Filament-ресурс для категорий.
- [ ] В `ArticleForm` добавлен выбор категории, в `ArticlesTable` — колонка и фильтр.
- [ ] В `ArticleSeeder` добавлены 6 категорий и 50 новых статей с заполненным `article_category_id`.
- [ ] Старым 12 статьям назначены категории.
- [ ] Все slug уникальны и не конфликтуют с существующими 12.
- [ ] Каждая статья содержит 2–5 внутренних ссылок на другие статьи.
- [ ] У каждой статьи заполнены `title`, `content`, `meta_description`, `is_published`.
- [ ] `php artisan db:seed --class=ArticleSeeder` выполняется без ошибок.
- [ ] `php -l database/seeders/ArticleSeeder.php` проходит успешно.
