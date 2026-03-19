<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoterieResults extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    // Añadimos el atributo al JSON
    protected $appends = ['numbers_formatted'];

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
