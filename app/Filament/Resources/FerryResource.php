<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FerryResource\Pages;
use App\Models\Ferry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class FerryResource extends Resource
{
    protected static ?string $model = Ferry::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Ferries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ferry_name')
                    ->label('Ferry Name')
                    ->required(),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()  // Ensure the file uploaded is an image
                    ->nullable(),  // Optional if image is not required

                TextInput::make('from')
                    ->label('Departure Location')
                    ->required(),

                TextInput::make('to')
                    ->label('Destination Location')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ferry_name')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('image')->sortable(),
                Tables\Columns\TextColumn::make('from')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('to')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFerries::route('/'),
            'create' => Pages\CreateFerry::route('/create'),
            'edit' => Pages\EditFerry::route('/{record}/edit'),
        ];
    }
}
