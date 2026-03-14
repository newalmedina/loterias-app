<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CenterLoterieResource\Pages;
use App\Models\CenterLoterie;
use App\Models\Loterie;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Hidden;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CenterLoterieResource extends Resource
{
    protected static ?string $model = CenterLoterie::class;

    protected static ?string $navigationLabel = 'Mis loterías';
    protected static ?string $pluralModelLabel = 'Mis loterías';
    protected static ?string $modelLabel = 'Mis loterias';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 12;

    // Solo el usuario autenticado puede ver sus loterías
    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function getEloquentQuery(): Builder
    {

        $query = parent::getEloquentQuery();

        $user = Auth::user();
        // Obtener el ID del panel actual

        $query->where('center_id', $user->center?->id ?? -1);

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    Section::make('Datos de la Lotería')
                        ->columnSpan(['default' => 12])
                        ->schema([
                            // Imagen de la lotería
                            Placeholder::make('imagen_loteria')
                                ->label('')
                                ->columnSpanFull()
                                ->content(function ($get) {
                                    $loterie = Loterie::find($get('loterie_id'));
                                    if (!$loterie || !$loterie->image) return '';
                                    $url = Storage::disk('public')->url($loterie->image);
                                    return view('components.loteria-imagen', [
                                        'url' => $url,
                                        'nombre' => $loterie->nombre,
                                    ]);
                                }),
                            // Centro
                            // Campo oculto para centro
                            Hidden::make('center_id')
                                ->default(fn() => auth()->user()->center_id ?? null),

                            // Campo para lotería con buscador y validación única
                            Select::make('loterie_id')
                                ->label('Lotería')
                                ->relationship('loterie', 'nombre', function ($query) {
                                    $query->orderBy('nombre', 'asc'); // Orden ascendente por nombre
                                })
                                ->preload() // <- aquí está la clave
                                ->live() // <- aquí está la clave
                                ->searchable()
                                ->required()
                                ->rule(function ($record) {
                                    $centerId = auth()->user()->center_id;
                                    return Rule::unique('center_loteries', 'loterie_id')
                                        ->where('center_id', $centerId)
                                        ->ignore($record?->id);
                                })
                                ->columnSpan(6),




                            // Activo
                            Toggle::make('active')
                                ->label('Activo')
                                ->inline(false)
                                ->default(true)
                                ->columnSpan(3),

                            // Min bloqueo
                            TextInput::make('min_bloqueo')
                                ->label('Mínimo Bloqueo')
                                ->numeric()
                                ->default(10)
                                ->required()
                                ->columnSpan(3),

                            // Máximo soportado
                            TextInput::make('maximo_soportado')
                                ->label('Máximo Soportado')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->columnSpan(3),

                            // Horarios y disponibilidad
                            Placeholder::make('horarios')
                                ->label('Hora cierra (Rep. dominicana ) y días de la semana  disponibles')
                                ->columnSpanFull()
                                ->content(function ($get) {
                                    $loterie = Loterie::find($get('loterie_id'));
                                    if (!$loterie) return 'Selecciona una lotería para ver horarios';

                                    $dias = [
                                        'lunes' => 'Lunes',
                                        'martes' => 'Martes',
                                        'miercoles' => 'Miércoles',
                                        'jueves' => 'Jueves',
                                        'viernes' => 'Viernes',
                                        'sabado' => 'Sábado',
                                        'domingo' => 'Domingo',
                                    ];

                                    $output = '';
                                    foreach ($dias as $key => $nombre) {
                                        if ($loterie->{$key . '_disponible'}) {
                                            $hora = $loterie->{$key . '_hora_fin'}
                                                ? Carbon::parse($loterie->{$key . '_hora_fin'})->format('H:i')
                                                : '-';
                                            $output .= "✅ {$nombre} - Cierre: {$hora}\n";
                                        } else {
                                            $output .= "❌ {$nombre} - No disponible\n";
                                        }
                                    }

                                    // Texto plano, sin <br>
                                    return $output;

                                    // O si quieres respetar saltos de línea en HTML:
                                    // return "<div style='white-space: pre-line;'>{$output}</div>";
                                }),
                        ]),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Imagen de la lotería
                ImageColumn::make('loterie.image')
                    ->label('Imagen')
                    ->getStateUsing(function ($record) {
                        if (!$record->loterie || !$record->loterie->image) return null;
                        return Storage::disk('public')->url($record->loterie->image);
                    })
                    ->size(40)
                    ->rounded(),

                // Nombre de la lotería
                TextColumn::make('loterie.nombre')->label('Lotería')->searchable(),

                // Horas de cierre por día
                TextColumn::make('loterie.lunes_hora_fin')
                    ->label('Lunes Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.martes_hora_fin')
                    ->label('Martes Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.miercoles_hora_fin')
                    ->label('Miércoles Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.jueves_hora_fin')
                    ->label('Jueves Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.viernes_hora_fin')
                    ->label('Viernes Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.sabado_hora_fin')
                    ->label('Sábado Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),
                TextColumn::make('loterie.domingo_hora_fin')
                    ->label('Domingo Fin')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : '-'),

                // Disponibilidad por día
                IconColumn::make('loterie.lunes_disponible')->boolean()->label('Lunes Disp'),
                IconColumn::make('loterie.martes_disponible')->boolean()->label('Martes Disp'),
                IconColumn::make('loterie.miercoles_disponible')->boolean()->label('Miércoles Disp'),
                IconColumn::make('loterie.jueves_disponible')->boolean()->label('Jueves Disp'),
                IconColumn::make('loterie.viernes_disponible')->boolean()->label('Viernes Disp'),
                IconColumn::make('loterie.sabado_disponible')->boolean()->label('Sábado Disp'),
                IconColumn::make('loterie.domingo_disponible')->boolean()->label('Domingo Disp'),

                // Activo de la relación
                IconColumn::make('active')->boolean()->label('Activo'),

                // Min y max
                TextColumn::make('min_bloqueo')->label('Mínimo Bloqueo'),
                TextColumn::make('maximo_soportado')->label('Máximo Soportado'),
            ])
            ->filters([
                // Filtro por nombre de lotería

            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make()->label('Eliminar Seleccionados'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCenterLoteries::route('/'),
            'create' => Pages\CreateCenterLoterie::route('/create'),
            'edit' => Pages\EditCenterLoterie::route('/{record}/edit'),
        ];
    }
}
