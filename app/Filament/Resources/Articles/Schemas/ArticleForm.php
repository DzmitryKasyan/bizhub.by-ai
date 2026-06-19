<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, $livewire) => $livewire instanceof CreateArticle
                        ? $set('slug', Str::slug($state))
                        : null),
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
    }
}
