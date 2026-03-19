<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoterieSpecialDateResource\Pages;
use App\Models\Loterie;
use App\Models\LoterieSpecialDate;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class LoterieSpecialDateResource extends Resource
{
    protected static ?string $model = LoterieSpecialDate::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 12;
    public static function getModelLabel(): string
    {
        return 'Fechas especial';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fechas especiales';
    }
    public static function canViewAny(): bool
    {
        return auth()->user()?->super_admin == true;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([
                Select::make('loterie_id')
                    ->label('Lotería')
                    ->options(Loterie::pluck('nombre', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpan(6),

                DatePicker::make('date')
                    ->label('date')
                    ->required()
                    ->columnSpan(3),

                TimePicker::make('end_time')
                    ->label('Hora de cierre')
                    ->withoutSeconds()
                    ->columnSpan(3),

                Toggle::make('not_enable')
                    ->label('No disponible')
                    ->default(false)
                    ->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loterie.nombre')
                    ->label('Lotería')
                    ->searchable(),

                TextColumn::make('date')
                    ->label('date')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d-m-Y')),

                TextColumn::make('end_time')
                    ->label('Hora de cierre')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),

                IconColumn::make('not_enable')
                    ->boolean()
                    ->label('No disponible'),

                TextColumn::make('created_at')
                    ->label('Fecha creación')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d-m-Y H:i'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                // Filtro de disponibilidad
                Filter::make('not_enable')
                    ->label('Disponibilidad')
                    ->form([
                        Select::make('not_enable')
                            ->label('Mostrar')
                            ->options([
                                '' => 'Todos',
                                0 => 'Disponibles',
                                1 => 'No disponibles',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['not_enable']) && $data['not_enable'] !== '') {
                            $query->where('not_enable', $data['not_enable']);
                        }
                    }),

                // Filtro por rango de fechas con valores por defecto
                Filter::make('fecha_rango')
                    ->label('Rango de fecha')
                    ->form([
                        DatePicker::make('fecha_inicio')
                            ->label('Fecha inicio')
                            ->default(Carbon::now()->startOfWeek()),

                        DatePicker::make('fecha_fin')
                            ->label('Fecha fin')
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['fecha_inicio'])) {
                            $query->whereDate('date', '>=', $data['fecha_inicio']);
                        }
                        if (!empty($data['fecha_fin'])) {
                            $query->whereDate('date', '<=', $data['fecha_fin']);
                        }
                    }),
            ])
            ->defaultSort('date', 'asc') // Ordenar por fecha ascendente
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoterieSpecialDates::route('/'),
            // 'create' => Pages\CreateLoterieSpecialDate::route('/create'),
            // 'edit' => Pages\EditLoterieSpecialDate::route('/{record}/edit'),
        ];
    }
}
