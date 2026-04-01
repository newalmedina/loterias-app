<?php

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loterie extends Model
{
    use HasFactory;
    // Permitimos llenado masivo de todos los campos
    protected $guarded = ["id"];
    protected $appends = ['image_base64', 'end_time', "disponible"];
    // Casts para booleans y horas
    protected $casts = [
        'active' => 'boolean',
        'lunes_disponible' => 'boolean',
        'martes_disponible' => 'boolean',
        'miercoles_disponible' => 'boolean',
        'jueves_disponible' => 'boolean',
        'viernes_disponible' => 'boolean',
        'sabado_disponible' => 'boolean',
        'domingo_disponible' => 'boolean',

        // Horas de fin
        'lunes_hora_fin' => 'datetime:H:i',
        'martes_hora_fin' => 'datetime:H:i',
        'miercoles_hora_fin' => 'datetime:H:i',
        'jueves_hora_fin' => 'datetime:H:i',
        'viernes_hora_fin' => 'datetime:H:i',
        'sabado_hora_fin' => 'datetime:H:i',
        'domingo_hora_fin' => 'datetime:H:i',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {


            if (empty($model->code)) {

                // Generar código único de 5 dígitos
                do {
                    $code = random_int(10000, 99999);
                } while (self::where('code', $code)->exists());

                $model->code = $code;
            }
        });
    }


    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function centerLoteries()
    {
        return $this->hasMany(CenterLoterie::class);
    }

    public function myCenterLoteries()
    {
        return $this->centerLoteries()
            ->active()
            ->myCenter();
    }

    public function results()
    {
        return $this->hasMany(LoterieResults::class);
    }
    public function centers()
    {
        return $this->belongsToMany(Center::class, 'center_loteries')
            ->withPivot(['active', 'min_bloqueo'])
            ->withTimestamps();
    }

    public function getImageBase64Attribute()
    {
        if (!$this->image) {
            return null;
        }

        $path = storage_path('app/public/' . $this->image);

        if (!file_exists($path)) {
            return null;
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        return 'data:' . $type . ';base64,' . base64_encode($file);
    }

    public function getDisponibleAttribute()
    {
        //si hay resultados para esta loteria y fecha no esta disponible

        $date = now();

        $HasResultados = LoterieResults::where("loterie_id", $this->id)
            ->whereDate("date", $date->format('Y-m-d'))
            ->count();


        //si no tiene fecha fin o tiene resultados quiere decir que no esta disponible
        if (empty($this->end_time) || $HasResultados > 0) {
            return false;
        }


        //validar si aun le faltan los 10 min para tirar la loteria
        $horaFinDate = Carbon::parse($this->end_time)
            ->subMinutes(10);

        return $date->lt($horaFinDate);
    }

    public function getEndTimeAttribute()
    {
        $date = now();

        $specialDate = LoterieSpecialDate::where("loterie_id", $this->id)
            ->whereDate("date", $date->format('Y-m-d'))
            ->first();

        if (!empty($specialDate->id)) {
            if ($specialDate->not_enable) {
                return null;
            } elseif (!empty($specialDate->end_time)) {
                return $specialDate->end_time;
            }
        }

        $map = [
            1 => ['disponible' => 'lunes_disponible', 'hora' => 'lunes_hora_fin'],
            2 => ['disponible' => 'martes_disponible', 'hora' => 'martes_hora_fin'],
            3 => ['disponible' => 'miercoles_disponible', 'hora' => 'miercoles_hora_fin'],
            4 => ['disponible' => 'jueves_disponible', 'hora' => 'jueves_hora_fin'],
            5 => ['disponible' => 'viernes_disponible', 'hora' => 'viernes_hora_fin'],
            6 => ['disponible' => 'sabado_disponible', 'hora' => 'sabado_hora_fin'],
            0 => ['disponible' => 'domingo_disponible', 'hora' => 'domingo_hora_fin'],
        ];

        $dayConfig = $map[$date->dayOfWeek] ?? null;

        if (!$dayConfig) {
            return null;
        }
        // 👇 aquí está la clave
        return $this->{$dayConfig['hora']};
    }
}
