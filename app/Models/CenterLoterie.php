<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenterLoterie extends Model
{
    protected $table = 'center_loteries';

    protected $guarded = ['id'];

    // Hook para asignar el centro del usuario si no viene
    protected static function booted()
    {
        static::creating(function ($centerLoterie) {
            if (!$centerLoterie->center_id && auth()->check()) {
                $centerLoterie->center_id = auth()->user()->center_id;
            }
        });
    }
    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function loterie()
    {
        return $this->belongsTo(Loterie::class);
    }
}
