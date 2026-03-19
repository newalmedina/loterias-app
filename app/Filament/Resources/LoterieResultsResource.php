<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoterieResultsResource\Pages;
use App\Models\Loterie;
use App\Models\LoterieResults;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class LoterieResultsResource extends Resource
{
    protected static ?string $model = LoterieResults::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Loterías';
    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return 'Resultado';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Resultados';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([

                Select::make('loterie_id')
                    ->label('Lotería')
                    ->options(
                        Loterie::active()
                            ->orderBy('nombre', 'asc')   // Orden alfabético
                            ->pluck('nombre', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->live()
                    ->columnSpan(6),

                DatePicker::make('date')
                    ->label('Fecha')
                    ->required()
                    ->columnSpan(3),

                TextInput::make('numbers')
                    ->label('Números')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(12),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('loterie.image')
                    ->label('Imagen')
                    ->getStateUsing(function ($record) {
                        if (!$record->loterie || !$record->loterie->image) return null;
                        return Storage::disk('public')->url($record->loterie->image);
                    })
                    ->size(40)
                    ->rounded(),
                TextColumn::make('loterie.nombre')
                    ->label('Lotería')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Fecha')
                    ->sortable()
                    ->formatStateUsing(
                        fn($state) =>
                        Carbon::parse($state)->format('d-m-Y')
                    ),
                TextColumn::make('numbers')
                    ->label('Números')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $numbers = json_decode($state, true); // Convertimos JSON a array
                        if (!$numbers) return null;

                        // Cada número dentro de un span con estilos inline para ser bola circular
                        return collect($numbers)
                            ->map(function ($number) {
                                return "<span style='
                    display: inline-flex;
                    justify-content: center;
                    align-items: center;
                    width: 40px;
                    height: 40px;
                    background-color: #267806;
                    color: #ffffff;
                    border-radius: 50%;
                    margin: 0 5px 5px 0;
                    font-weight: 600;
                    font-size: 14px;
                    line-height: 1;
                '>{$number}</span>";
                            })
                            ->implode(' ');
                    })
                    ->html(), // Permite renderizar HTML

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(
                        fn($state) =>
                        Carbon::parse($state)->format('d-m-Y H:i')
                    ),

            ])

            ->filters([

                Filter::make('loterie')
                    ->label('Lotería')
                    ->form([
                        Select::make('loterie_id')
                            ->label('Lotería')
                            ->options(
                                Loterie::active()
                                    ->orderBy('nombre', 'asc')   // Orden alfabético
                                    ->pluck('nombre', 'id')
                            )
                            ->searchable()
                            ->live(),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['loterie_id'])) {
                            $query->where('loterie_id', $data['loterie_id']);
                        }
                    })
                    ->indicateUsing(function (array $data) {
                        return !empty($data['loterie_id'])
                            ? Loterie::find($data['loterie_id'])->nombre
                            : null;
                    }),

                Filter::make('fecha_rango')
                    ->label('Rango de fechas')
                    ->form([
                        DatePicker::make('fecha_inicio')
                            ->label('Fecha inicio')
                            ->default(now()->subDay()), // ayer

                        DatePicker::make('fecha_fin')
                            ->label('Fecha fin'),
                    ])
                    ->query(function ($query, array $data) {

                        if (!empty($data['fecha_inicio'])) {
                            $query->whereDate('date', '>=', $data['fecha_inicio']);
                        }

                        if (!empty($data['fecha_fin'])) {
                            $query->whereDate('date', '<=', $data['fecha_fin']);
                        }
                    })
                    ->indicateUsing(function (array $data) {
                        $indicators = [];

                        if (!empty($data['fecha_inicio'])) {
                            $indicators[] = 'Desde: ' . \Carbon\Carbon::parse($data['fecha_inicio'])->format('d/m/Y');
                        }

                        if (!empty($data['fecha_fin'])) {
                            $indicators[] = 'Hasta: ' . \Carbon\Carbon::parse($data['fecha_fin'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),

            ])

            ->defaultSort('date', 'desc')

            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar')->visible(fn() => auth()->user()?->super_admin),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar')->visible(fn() => auth()->user()?->super_admin),
            ])

            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoterieResults::route('/'),
            // 'create' => Pages\CreateLoterieResults::route('/create'),
            // 'edit' => Pages\EditLoterieResults::route('/{record}/edit'),
        ];
    }
}
