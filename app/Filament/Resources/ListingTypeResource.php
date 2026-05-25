<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ListingTypeResource\Pages\ListListingTypes;
use App\Filament\Resources\ListingTypeResource\Pages\CreateListingType;
use App\Filament\Resources\ListingTypeResource\Pages\EditListingType;
use App\Models\ListingTypeModel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ListingTypeResource extends Resource
{
    protected static ?string $model = ListingTypeModel::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Типы объявлений';

    protected static ?string $modelLabel = 'Тип объявления';

    protected static ?string $pluralModelLabel = 'Типы объявлений';

    protected static string|\UnitEnum|null $navigationGroup = 'Контент';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('code')
                    ->label('Код')
                    ->required()
                    ->maxLength(50),

                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                TextInput::make('icon')
                    ->label('Иконка')
                    ->maxLength(100),

                TextInput::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListingTypes::route('/'),
            'create' => CreateListingType::route('/create'),
            'edit' => EditListingType::route('/{record}/edit'),
        ];
    }
}
