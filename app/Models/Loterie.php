<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loterie extends Model
{
    use HasFactory;
    // Permitimos llenado masivo de todos los campos
    protected $guarded = ["id"];
    protected $appends = ['image_base64'];
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
}
