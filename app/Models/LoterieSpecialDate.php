<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoterieSpecialDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'loterie_id',
        'date',
        'end_time',
        'not_enable',
    ];

    public function loterie()
    {
        return $this->belongsTo(Loterie::class);
    }
}
