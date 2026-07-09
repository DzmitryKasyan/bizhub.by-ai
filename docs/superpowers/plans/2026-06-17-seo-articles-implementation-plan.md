> I'm using the writing-plans skill to create the implementation plan.

# SEO-статьи + категории статей — Implementation Plan

**Goal:** Добавить категории к модулю статей, реализовать управление ими в Filament, и наполнить сидер 50 SEO-статьями с перелинковкой.

**Architecture:** Отдельная сущность `ArticleCategory` связана one-to-many с `Article`. Категории создаются/редактируются через Filament. В сидере сначала заполняются категории, затем существующим и новым статьям назначаются `article_category_id`. Контент 50 статей генерируется отдельным subagent-заданием на основе спецификации.

**Tech Stack:** Laravel 11, Filament v4, SQLite/MySQL, PHP 8.3

---

## File Structure

| File | Responsibility |
|------|----------------|
| `database/migrations/2026_06_17_000000_create_article_categories_table.php` | Таблица категорий + FK в `articles` |
| `app/Models/ArticleCategory.php` | Модель категории, связь со статьями |
| `app/Models/Article.php` | Обновление fillable + belongsTo категории |
| `app/Filament/Resources/Articles/ArticleCategoryResource.php` | Filament-ресурс категорий |
| `app/Filament/Resources/Articles/Pages/ListArticleCategories.php` | Страница списка категорий |
| `app/Filament/Resources/Articles/Pages/CreateArticleCategory.php` | Страница создания категории |
| `app/Filament/Resources/Articles/Pages/EditArticleCategory.php` | Страница редактирования категории |
| `app/Filament/Resources/Articles/Schemas/ArticleForm.php` | Добавление Select категории в форму статьи |
| `app/Filament/Resources/Articles/Tables/ArticlesTable.php` | Колонка и фильтр по категории в списке статей |
| `database/seeders/ArticleSeeder.php` | Категории + 62 статьи (12 старых + 50 новых) |

---

### Task 1: Migration for article categories

**Files:**
- Create: `database/migrations/2026_06_17_000000_create_article_categories_table.php`
- Modify: `database/migrations/2026_05_25_091933_create_articles_table.php` (new migration instead — no direct edit)

Actually create a second migration to keep existing migration untouched.

- [ ] **Step 1: Create migration for categories + FK**

Create file `database/migrations/2026_06_17_000000_create_article_categories_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('article_category_id')
                ->nullable()
                ->after('slug')
                ->constrained('article_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['article_category_id']);
            $table->dropColumn('article_category_id');
        });

        Schema::dropIfExists('article_categories');
    }
};
```

- [ ] **Step 2: Run migration locally**

```bash
php artisan migrate
```

Expected: no errors, table `article_categories` created, column `article_category_id` added.

- [ ] **Step 3: Commit**

```bash
git add database/migrations/2026_06_17_000000_create_article_categories_table.php
git commit -m "feat: add article_categories table and articles.category_id fk"
```

---

### Task 2: Models and relations

**Files:**
- Create: `app/Models/ArticleCategory.php`
- Modify: `app/Models/Article.php`

- [ ] **Step 1: Create ArticleCategory model**

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ArticleCategory extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
```

- [ ] **Step 2: Update Article model**

Edit `app/Models/Article.php`:

```php
protected $fillable = ['title', 'slug', 'article_category_id', 'content', 'meta_description', 'is_published'];
```

Add relation:

```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function articleCategory(): BelongsTo
{
    return $this->belongsTo(ArticleCategory::class);
}
```

- [ ] **Step 3: Verify syntax**

```bash
php -l app/Models/ArticleCategory.php
php -l app/Models/Article.php
```

Expected: no syntax errors.

- [ ] **Step 4: Commit**

```bash
git add app/Models/ArticleCategory.php app/Models/Article.php
git commit -m "feat: ArticleCategory model and Article relation"
```

---

### Task 3: Filament resource for categories

**Files:**
- Create: `app/Filament/Resources/Articles/ArticleCategoryResource.php`
- Create: `app/Filament/Resources/Articles/Pages/ListArticleCategories.php`
- Create: `app/Filament/Resources/Articles/Pages/CreateArticleCategory.php`
- Create: `app/Filament/Resources/Articles/Pages/EditArticleCategory.php`

- [ ] **Step 1: Create ListArticleCategories page**

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListArticleCategories extends ListRecords
{
    protected static string $resource = ArticleCategoryResource::class;
}
```

- [ ] **Step 2: Create CreateArticleCategory page**

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleCategory extends CreateRecord
{
    protected static string $resource = ArticleCategoryResource::class;
}
```

- [ ] **Step 3: Create EditArticleCategory page**

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditArticleCategory extends EditRecord
{
    protected static string $resource = ArticleCategoryResource::class;
}
```

- [ ] **Step 4: Create ArticleCategoryResource**

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticleCategory;
use App\Filament\Resources\Articles\Pages\EditArticleCategory;
use App\Filament\Resources\Articles\Pages\ListArticleCategories;
use App\Models\ArticleCategory;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleCategoryResource extends Resource
{
    protected static ?string $model = ArticleCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Категории статей';

    protected static ?string $modelLabel = 'Категория';

    protected static ?string $pluralModelLabel = 'Категории статей';

    protected static string|\UnitEnum|null $navigationGroup = 'Контент';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('articles_count')
                    ->label('Статьи')
                    ->counts('articles'),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleCategories::route('/'),
            'create' => CreateArticleCategory::route('/create'),
            'edit' => EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
```

- [ ] **Step 5: Verify syntax and check Filament resources**

```bash
php -l app/Filament/Resources/Articles/ArticleCategoryResource.php
php -l app/Filament/Resources/Articles/Pages/ListArticleCategories.php
php -l app/Filament/Resources/Articles/Pages/CreateArticleCategory.php
php -l app/Filament/Resources/Articles/Pages/EditArticleCategory.php
php artisan route:list --path=admin/article-categories
```

Expected: no errors, routes present.

- [ ] **Step 6: Commit**

```bash
git add app/Filament/Resources/Articles/ArticleCategoryResource.php app/Filament/Resources/Articles/Pages/
git commit -m "feat: filament resource for article categories"
```

---

### Task 4: Article form — category select

**Files:**
- Modify: `app/Filament/Resources/Articles/Schemas/ArticleForm.php`

- [ ] **Step 1: Add category select to form**

Replace the component block with:

```php
use App\Models\ArticleCategory;
use Filament\Forms\Components\Select;

return $schema
    ->components([
        TextInput::make('title')
            ->label('Заголовок')
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
        TextInput::make('slug')
            ->label('Slug')
            ->required()
            ->maxLength(255)
            ->unique(ignoreRecord: true),
        Select::make('article_category_id')
            ->label('Категория')
            ->required()
            ->relationship('articleCategory', 'name')
            ->searchable()
            ->preload(),
        RichEditor::make('content')
            ->label('Содержание')
            ->required()
            ->columnSpanFull(),
        Textarea::make('meta_description')
            ->label('Meta Description')
            ->maxLength(255)
            ->rows(2),
        Toggle::make('is_published')
            ->label('Опубликовано')
            ->default(false),
    ]);
```

- [ ] **Step 2: Verify syntax**

```bash
php -l app/Filament/Resources/Articles/Schemas/ArticleForm.php
```

- [ ] **Step 3: Commit**

```bash
git add app/Filament/Resources/Articles/Schemas/ArticleForm.php
git commit -m "feat: add category select to article form"
```

---

### Task 5: Articles table — category column and filter

**Files:**
- Modify: `app/Filament/Resources/Articles/Tables/ArticlesTable.php`

- [ ] **Step 1: Add category column and filter**

```php
use Filament\Tables\Filters\SelectFilter;

return $table
    ->columns([
        TextColumn::make('title')
            ->label('Заголовок')
            ->searchable()
            ->sortable(),
        TextColumn::make('articleCategory.name')
            ->label('Категория')
            ->searchable()
            ->sortable(),
        TextColumn::make('slug')
            ->label('Slug')
            ->searchable(),
        IconColumn::make('is_published')
            ->label('Опубликовано')
            ->boolean()
            ->sortable(),
        TextColumn::make('updated_at')
            ->label('Обновлено')
            ->dateTime('d.m.Y H:i')
            ->sortable(),
    ])
    ->filters([
        SelectFilter::make('article_category_id')
            ->label('Категория')
            ->relationship('articleCategory', 'name')
            ->searchable()
            ->preload(),
    ])
    ->recordActions([
        EditAction::make(),
    ])
    ->toolbarActions([
        BulkActionGroup::make([
            DeleteBulkAction::make(),
        ]),
    ]);
```

- [ ] **Step 2: Verify syntax**

```bash
php -l app/Filament/Resources/Articles/Tables/ArticlesTable.php
```

- [ ] **Step 3: Commit**

```bash
git add app/Filament/Resources/Articles/Tables/ArticlesTable.php
git commit -m "feat: category column and filter in articles table"
```

---

### Task 6: Generate 50 SEO articles content

**Files:**
- Modify: `database/seeders/ArticleSeeder.php`

This task is content-heavy. Use a dedicated subagent with the spec `docs/superpowers/specs/2026-06-17-seo-articles-design.md` to generate the `$articles` array for 50 new entries and update existing 12 entries with `article_category_id`.

- [ ] **Step 1: Dispatch subagent for content generation**

Subagent prompt:

> Read `docs/superpowers/specs/2026-06-17-seo-articles-design.md`. Generate the full content for 50 new articles and update the existing 12 articles in `database/seeders/ArticleSeeder.php` with category assignments. Keep the existing 12 entries (about, terms, privacy, trust-management, sell-business, buy-business, seek-investment, offer-investment, franchise, partnership, real-estate, equipment). Insert `article_category_id` for all entries. Add 50 new entries after the existing ones. Each article must have: title, slug, article_category_id, content (HTML with 2–5 internal links `{{ route('article.show', 'slug') }}`), meta_description, is_published true. Ensure all slugs are unique and do not conflict with existing 12. Use the category mapping from the spec. Article lengths: high-frequency articles ~1000–1500 words, medium/low ~300–500 words. Return the final `ArticleSeeder.php` file content.

- [ ] **Step 2: Review generated seeder**

Check:
- exactly 62 entries total (12 + 50);
- every entry has `article_category_id`;
- all slugs unique;
- links point to existing slugs.

- [ ] **Step 3: Verify syntax**

```bash
php -l database/seeders/ArticleSeeder.php
```

- [ ] **Step 4: Commit**

```bash
git add database/seeders/ArticleSeeder.php
git commit -m "content: seed 50 SEO articles with categories and internal links"
```

---

### Task 7: Run seeder and verify

**Files:**
- None (runtime check)

- [ ] **Step 1: Run ArticleSeeder**

```bash
php artisan db:seed --class=ArticleSeeder
```

Expected output: `Articles seeded: 62`

- [ ] **Step 2: Check data sanity**

```bash
php artisan tinker --execute="echo App\Models\ArticleCategory::count().' categories, '.App\Models\Article::count().' articles';"
```

Expected: `6 categories, 62 articles`

- [ ] **Step 3: Run existing tests / syntax checks**

```bash
php -l database/seeders/ArticleSeeder.php
php artisan view:cache
```

Expected: no errors.

- [ ] **Step 4: Commit (if any final changes)**

```bash
git commit -m "chore: verify seeder and category setup" || true
```

---

## Self-review

**Spec coverage:**
- Categories model/migration — Task 1–2.
- Filament admin for categories — Task 3.
- Category select in article form — Task 4.
- Category column/filter in articles list — Task 5.
- 50 new articles + old categorization — Task 6.
- Seeder run verification — Task 7.

**Placeholder scan:** no TBD/TODO. Content generation is delegated to subagent with explicit prompt and spec reference.

**Type consistency:** `article_category_id` used in migration, model fillable, form, table, and seeder consistently.
