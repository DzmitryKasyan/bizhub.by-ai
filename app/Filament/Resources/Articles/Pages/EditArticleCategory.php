<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArticleCategory extends EditRecord
{
    protected static string $resource = ArticleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (ArticleCategory $record): bool => $record->articles()->exists())
                ->tooltip('Удаление невозможно: в категории есть статьи'),
        ];
    }
}
