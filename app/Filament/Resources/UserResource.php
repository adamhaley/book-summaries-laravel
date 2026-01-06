<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\UserProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account')
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(6),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->seconds(false)
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('Leave blank to keep the current password.')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->columnSpan(6),
                    ]),

                Forms\Components\Section::make('Admin access')
                    ->columns(12)
                    ->schema([
                        Forms\Components\Select::make('filament_role')
                            ->label('Role')
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                            ])
                            ->default('user')
                            ->required()
                            ->columnSpan(4),
                        Forms\Components\KeyValue::make('filament_preferences')
                            ->label('Preferences')
                            ->helperText('Stored in user_profiles.preferences (JSON).')
                            ->columnSpan(8),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile.role')
                    ->label('Role')
                    ->badge()
                    ->default('user')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (! $value) {
                            return $query;
                        }

                        return $query->whereHas('profile', fn (Builder $q) => $q->where('role', $value));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleAdmin')
                    ->label(fn (User $record) => ($record->profile?->role === 'admin') ? 'Revoke admin' : 'Make admin')
                    ->icon('heroicon-o-shield-check')
                    ->color(fn (User $record) => ($record->profile?->role === 'admin') ? 'gray' : 'primary')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $profile = $record->profile()->firstOrNew();

                        $profile->role = ($profile->role === 'admin') ? 'user' : 'admin';

                        if (! is_array($profile->preferences)) {
                            $profile->preferences = [
                                'style' => 'narrative',
                                'length' => '5pg',
                            ];
                        }

                        $profile->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('profile');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Store profile data separately in afterCreate().
        unset($data['filament_role'], $data['filament_preferences']);

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['filament_role'], $data['filament_preferences']);

        return $data;
    }

    public static function afterCreate(User $record, array $data): void
    {
        $profile = $record->profile()->firstOrNew();
        $profile->role = $data['filament_role'] ?? 'user';
        $profile->preferences = $data['filament_preferences'] ?? $profile->preferences ?? [
            'style' => 'narrative',
            'length' => '5pg',
        ];
        $profile->save();
    }

    public static function afterSave(User $record, array $data): void
    {
        $profile = $record->profile()->firstOrNew();
        $profile->role = $data['filament_role'] ?? $profile->role ?? 'user';
        $profile->preferences = $data['filament_preferences'] ?? $profile->preferences ?? [
            'style' => 'narrative',
            'length' => '5pg',
        ];
        $profile->save();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
