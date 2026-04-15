<?php

namespace App\Filament\Pages;

use App\Mail\TestMail;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Actions, ColorPicker, TextInput, FileUpload, Toggle, DatePicker, Radio, Select, Section, Grid, Tabs};
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Facades\Mail;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Illuminate\Support\Facades\Artisan;

class Configuration extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Configuración del sitio';
    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'filament.pages.configuration';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 82;
    public static function getNavigationLabel(): string
    {
        return 'Información del sitio';
    }
    public function getTitle(): string
    {
        return 'Información del sitio';
    }

    public ?array $data = [];

    public function mount()
    {
        $user = Auth::user();
        $center = $user->center; // Relación con el centro

        if ($center) {
            $this->form->fill([
                'name' => $center->name,
                'nif' => $center->nif,
                'email' => $center->email,
                'phone' => $center->phone,
                'address' => $center->address,
                'postal_code' => $center->postal_code,
                'country_id' => $center->country_id,
                'state_id' => $center->state_id,
                'city_id' => $center->city_id,
                'bank_name' => $center->bank_name,
                'bank_number' => $center->bank_number,
                'active' => $center->active,
                'image' => $center->image,
                'primary_color' => $center->primary_color,
                'primary_color_soft' => $center->primary_color_soft,
                'start_message' => $center->start_message,
                'end_message' => $center->end_message,
                'mail_enable_integration' => $center->mail_enable_integration,
                'enable_start_message' => $center->enable_start_message,
                'enable_end_message' => $center->enable_end_message,
                // ================= CONFIG JUGADAS =================
                'min_antes_bloqueo' => $center->min_antes_bloqueo,
                'monto_maximo_quinielas' => $center->monto_maximo_quinielas,

                // Quiniela
                'quiniela_primer_premio' => $center->quiniela_primer_premio,
                'quiniela_segundo_premio' => $center->quiniela_segundo_premio,
                'quiniela_tercer_premio' => $center->quiniela_tercer_premio,
                'quiniela_monto_soportado' => $center->quiniela_monto_soportado,

                // Palé
                'pale_primer_premio' => $center->pale_primer_premio,
                'pale_segundo_premio' => $center->pale_segundo_premio,
                'pale_tercer_premio' => $center->pale_tercer_premio,
                'pale_monto_soportado' => $center->pale_monto_soportado,

                // Super Palé
                'suppale_primer_premio' => $center->suppale_primer_premio,
                'suppale_segundo_premio' => $center->suppale_segundo_premio,
                'suppale_tercer_premio' => $center->suppale_tercer_premio,
                'suppale_monto_soportado' => $center->suppale_monto_soportado,

                // Tripleta
                'tripleta_primer_premio' => $center->tripleta_primer_premio,
                'tripleta_segundo_premio' => $center->tripleta_segundo_premio,
                'tripleta_tercer_premio' => $center->tripleta_tercer_premio,
                'tripleta_monto_soportado' => $center->tripleta_monto_soportado,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)
                    ->schema([

                        Section::make('Imagen')
                            ->columnSpan([
                                'default' => 12,
                                'md' => 3,
                            ])
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('centers')
                                    ->columnSpanFull(),
                            ]),

                        Tabs::make('Configuraciones')
                            ->columnSpan([
                                'default' => 12,
                                'md' => 9,
                            ])
                            ->tabs([

                                // ================= INFORMACIÓN GENERAL =================
                                Tab::make('Información general')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([

                                                Actions::make([
                                                    FormAction::make('sendTestEmail')
                                                        ->label('Enviar correo de prueba')
                                                        ->icon('heroicon-o-envelope')
                                                        ->color('warning')
                                                        ->action(function (): void {
                                                            $email = Auth::user()->email;

                                                            Mail::to($email)->send(new TestMail(Auth::user()));

                                                            Notification::make()
                                                                ->title('Correo enviado a ' . $email)
                                                                ->success()
                                                                ->send();
                                                        }),

                                                    FormAction::make('downloadOldBackup')
                                                        ->label('Descargar BBDD Antigua')
                                                        ->icon('heroicon-o-arrow-down')
                                                        ->extraAttributes([
                                                            'style' => 'background-color: #6B21A8; color: white;',
                                                        ])
                                                        ->visible(fn() => auth()->user()?->super_admin == true)
                                                        ->form([
                                                            TextInput::make('filename')
                                                                ->label('Nombre del archivo')
                                                                ->required(),
                                                        ])
                                                        ->action(function (array $data, $livewire) {
                                                            $url = route('filament.backups.download', [
                                                                'filepath' => $data['filename']
                                                            ]);

                                                            $livewire->redirect($url);
                                                        }),
                                                ]),

                                                TextInput::make('name')->required(),
                                                TextInput::make('nif')->required(),
                                                TextInput::make('email')->email()->required(),
                                                TextInput::make('phone'),
                                                TextInput::make('address'),
                                                TextInput::make('postal_code'),

                                                Select::make('country_id')
                                                    ->label('País')
                                                    ->options(fn() => \App\Models\Country::pluck('name', 'id')),

                                                Select::make('state_id')
                                                    ->label('Estado')
                                                    ->options(
                                                        fn(Get $get) =>
                                                        \App\Models\State::where('country_id', $get('country_id'))
                                                            ->pluck('name', 'id')
                                                    )
                                                    ->live()
                                                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),

                                                Select::make('city_id')
                                                    ->label('Ciudad')
                                                    ->options(
                                                        fn(Get $get) =>
                                                        \App\Models\City::where('state_id', $get('state_id'))
                                                            ->pluck('name', 'id')
                                                    ),

                                                TextInput::make('bank_name'),
                                                TextInput::make('bank_number'),

                                                ColorPicker::make('primary_color'),
                                                ColorPicker::make('primary_color_soft'),
                                            ]),
                                    ]),

                                // ================= CONFIGURACIÓN DE JUGADAS (NUEVO TAB) =================
                                Tab::make('Configuración de jugadas')
                                    ->schema([

                                        Section::make('Configuración general')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('min_antes_bloqueo')
                                                            ->label('Minutos antes de bloqueo')
                                                            ->numeric()
                                                            ->default(0),

                                                        TextInput::make('monto_maximo_quinielas')
                                                            ->label('Monto máximo quinielas')
                                                            ->numeric()
                                                            ->default(0),
                                                    ]),
                                            ]),

                                        Section::make('Quiniela')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('quiniela_primer_premio')->label('1er Premio')->numeric(),
                                                        TextInput::make('quiniela_segundo_premio')->label('2do Premio')->numeric(),
                                                        TextInput::make('quiniela_tercer_premio')->label('3er Premio')->numeric(),
                                                        TextInput::make('quiniela_monto_soportado')->label('Monto soportado')->numeric(),
                                                    ]),
                                            ]),

                                        Section::make('Palé')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('pale_primer_premio')->label('1er Premio')->numeric(),
                                                        TextInput::make('pale_segundo_premio')->label('2do Premio')->numeric(),
                                                        TextInput::make('pale_tercer_premio')->label('3er Premio')->numeric(),
                                                        TextInput::make('pale_monto_soportado')->label('Monto soportado')->numeric(),
                                                    ]),
                                            ]),

                                        Section::make('Super Palé')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('suppale_primer_premio')->label('1er Premio')->numeric(),
                                                        TextInput::make('suppale_segundo_premio')->label('2do Premio')->numeric(),
                                                        TextInput::make('suppale_tercer_premio')->label('3er Premio')->numeric(),
                                                        TextInput::make('suppale_monto_soportado')->label('Monto soportado')->numeric(),
                                                    ]),
                                            ]),

                                        Section::make('Tripleta')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('tripleta_primer_premio')->label('1er Premio')->numeric(),
                                                        TextInput::make('tripleta_segundo_premio')->label('2do Premio')->numeric(),
                                                        TextInput::make('tripleta_tercer_premio')->label('3er Premio')->numeric(),
                                                        TextInput::make('tripleta_monto_soportado')->label('Monto soportado')->numeric(),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $user = Auth::user();
        $center = $user->center;

        if (!$center) {
            Notification::make()
                ->title('El usuario no tiene un centro asignado.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();
        // dd($data);
        $center->update($data);


        Notification::make()
            ->title('Centro actualizado correctamente')
            ->success()
            ->send();
    }
}
