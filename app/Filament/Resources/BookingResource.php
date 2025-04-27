<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Enums\BookingLocation;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('guest_name')
                    ->required()
                    ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }
                    
                        $set('slug', Str::slug($state));
                    }),
                
                TextInput::make('slug')
                    ->required(),

                Select::make('location')
                    ->options(BookingLocation::options())
                    ->required(),

                DatePicker::make('booking_date')
                    ->native(false)
                    ->required(),

                TextInput::make('Adults')
                    ->label('Adults')
                    ->numeric()
                    ->required(),

                TextInput::make('Children')
                    ->label('Children')
                    ->numeric()
                    ->required(),

                TextInput::make('Kids')
                    ->label('Kids')
                    ->numeric()
                    ->required(),

                TextInput::make('Infants')
                    ->label('Infants')
                    ->numeric()
                    ->required(),

                TextInput::make('price')
                    ->numeric()
                    ->prefix('₹')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === true) {
                            $set('is_paid', false);
                        }
                    })
                    ->default(true),

                Toggle::make('is_paid')
                    ->label('Paid')
                    ->default(false)
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->columns([
                Tables\Columns\TextColumn::make('guest_name'),
                Tables\Columns\TextColumn::make('slug')
                ->label('Booking Link')
                ->color('primary')
                ->formatStateUsing(fn ($state) => $state)
                ->url(fn ($record) => config('app.url') . "/bookings/{$record->slug}")
                ->icon('heroicon-o-link')
                ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('location')->label('Location'),
                Tables\Columns\TextColumn::make('booking_date')->date(),
                // Tables\Columns\TextColumn::make('Adults'),
                // Tables\Columns\TextColumn::make('Children'),
                // Tables\Columns\TextColumn::make('Kids'),
                // Tables\Columns\TextColumn::make('Infants'),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
            ])
            ->filters([
                Filter::make('is_paid')
                    ->label('Payment Status')
                    ->query(fn (Builder $query): Builder => $query->where('is_paid', true))
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('secondary'),
                    EditAction::make()
                        ->color('primary'),
                    DeleteAction::make()
                        ->color('danger'),
                    Action::make('is_paid')
                        ->label('Mark as Paid')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Booking $record) {
                            $record->is_active = false;
                            $record->is_paid = true;
                            $record->save();
                        })
                        ->hidden(fn (Booking $record): bool => $record->is_paid),
                
                    Action::make('is_active')
                        ->label('Mark as Active')
                        ->color('success')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (Booking $record) {
                            $record->is_active = true;
                            $record->is_paid = false;
                            $record->save();
                        })
                        ->hidden(fn (Booking $record): bool => $record->is_active),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
