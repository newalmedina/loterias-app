<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoterieResults extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    // Añadimos el atributo al JSON
    protected $appends = ['numbers_formatted'];

    protected static function booted()
    {
        static::creating(function ($model) {
            // Si no existe spain_created_at, asignamos ahora en hora de España
            if (!$model->spain_created_at) {
                $model->spain_created_at = Carbon::now('Europe/Madrid');
            }

            // Siempre que se cree, spain_updated_at = ahora
            $model->spain_updated_at = Carbon::now('Europe/Madrid');
        });

        static::updating(function ($model) {
            // Al actualizar, solo spain_updated_at cambia
            $model->spain_updated_at = Carbon::now('Europe/Madrid');
        });
    }
    public function loterie()
    {
        return $this->belongsTo(Loterie::class);
    }

    // Accessor: convierte numbers string "[8,2,12]" a array [8,2,12]
    public function getNumbersFormattedAttribute()
    {
        if (!$this->numbers) {
            return [];
        }

        $numbers = json_decode($this->numbers, true);

        if (!is_array($numbers)) {
            return [];
        }

        // Formateamos a dos dígitos
        return array_map(fn($n) => str_pad($n, 2, '0', STR_PAD_LEFT), $numbers);
    }
}
