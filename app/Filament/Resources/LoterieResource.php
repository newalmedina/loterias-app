<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoterieResource\Pages;
use App\Models\Loterie;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoterieResource extends Resource
{
    protected static ?string $model = Loterie::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Tablas de sistemas';
    protected static ?int $navigationSort = 11;

    public static function canViewAny(): bool
    {
        return auth()->user()?->super_admin == true;
    }

    public static function getModelLabel(): string
    {
        return 'Lotería';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Loterías';
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::loterieFormSchema());
    }

    public static function loterieFormSchema(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    Section::make('Imagen')
                        ->columnSpan([
                            'default' => 12, // móvil
                            'md' => 3,       // escritorio
                        ])
                        ->schema([

                            Forms\Components\FileUpload::make('image')
                                ->label('Imagen')
                                ->image()
                                ->disk('public')
                                ->directory('loterias')
                                ->columnSpanFull(),
                        ]),
                    Section::make('imformacion')
                        ->columnSpan([
                            'default' => 12, // móvil
                            'md' => 9,       // escritorio
                        ])
                        ->schema([
                            Section::make('Información general')
                                ->columnSpan(['default' => 12])
                                ->schema([
                                    Grid::make(12)
                                        ->schema([
                                            TextInput::make('nombre')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(8), // ocupa la mayor parte

                                            Toggle::make('active')
                                                ->label('¿Activo?')
                                                ->default(true)
                                                ->columnSpan(2), // ocupa un espacio pequeño


                                        ]),

                                    Textarea::make('descripcion')
                                        ->rows(3),
                                ]),

                            Section::make('Horas de fin por día (República dominicana )')
                                ->columnSpan(['default' => 12])
                                ->schema([
                                    Grid::make(12) // Grid de 12 columnas
                                        ->schema([
                                            TimePicker::make('lunes_hora_fin')
                                                ->label('Lunes')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('martes_hora_fin')
                                                ->label('Martes')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('miercoles_hora_fin')
                                                ->label('Miércoles')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('jueves_hora_fin')
                                                ->label('Jueves')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('viernes_hora_fin')
                                                ->label('Viernes')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('sabado_hora_fin')
                                                ->label('Sábado')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),

                                            TimePicker::make('domingo_hora_fin')
                                                ->label('Domingo')
                                                ->required()
                                                ->withoutSeconds()
                                                ->columnSpan(3),
                                        ]),
                                ]),

                            Section::make('Disponibilidad por día')
                                ->columnSpan(['default' => 12])
                                ->schema([
                                    Grid::make(12)
                                        ->schema([
                                            Toggle::make('lunes_disponible')->label('Lunes Disponible')->default(true)->columnSpan(3),
                                            Toggle::make('martes_disponible')->label('Martes Disponible')->default(true)->columnSpan(3),
                                            Toggle::make('miercoles_disponible')->label('Miércoles Disponible')->default(true)->columnSpan(3),
                                            Toggle::make('jueves_disponible')->label('Jueves Disponible')->default(true)->columnSpan(3),

                                            Toggle::make('viernes_disponible')->label('Viernes Disponible')->default(true)->columnSpan(3),
                                            Toggle::make('sabado_disponible')->label('Sábado Disponible')->default(true)->columnSpan(3),
                                            Toggle::make('domingo_disponible')->label('Domingo Disponible')->default(true)->columnSpan(3),
                                        ]),
                                ]),

                        ]),


                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen'),
                TextColumn::make('nombre')->label('Nombre')->searchable(),
                TextColumn::make('descripcion')->label('Descripción')->limit(50),
                IconColumn::make('active')->boolean()->label('¿Activo?'),
                // TextColumn::make('bloquear_jugada')->label('Bloquear Jugada')->sortable(),

                // Horas de fin sin segundos
                TextColumn::make('lunes_hora_fin')
                    ->label('Lunes Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('martes_hora_fin')
                    ->label('Martes Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('miercoles_hora_fin')
                    ->label('Miércoles Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('jueves_hora_fin')
                    ->label('Jueves Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('viernes_hora_fin')
                    ->label('Viernes Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('sabado_hora_fin')
                    ->label('Sábado Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),
                TextColumn::make('domingo_hora_fin')
                    ->label('Domingo Fin')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('H:i')),

                // Disponibilidad como IconColumn
                IconColumn::make('lunes_disponible')->boolean()->label('Lunes Disp'),
                IconColumn::make('martes_disponible')->boolean()->label('Martes Disp'),
                IconColumn::make('miercoles_disponible')->boolean()->label('Miércoles Disp'),
                IconColumn::make('jueves_disponible')->boolean()->label('Jueves Disp'),
                IconColumn::make('viernes_disponible')->boolean()->label('Viernes Disp'),
                IconColumn::make('sabado_disponible')->boolean()->label('Sábado Disp'),
                IconColumn::make('domingo_disponible')->boolean()->label('Domingo Disp'),

                TextColumn::make('created_at')
                    ->label('Fecha creación')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y h:i');
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Puedes agregar DeleteBulkAction aquí si quieres
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
            'index' => Pages\ListLoteries::route('/'),
            'create' => Pages\CreateLoterie::route('/create'),
            'edit' => Pages\EditLoterie::route('/{record}/edit'),
        ];
    }
}
