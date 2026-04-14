<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $guarded = ['id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // 🎯 Lotería principal
    public function loterie(): BelongsTo
    {
        return $this->belongsTo(Loterie::class, 'loterie_id');
    }

    // 🎯 Lotería secundaria
    public function secondLoterie(): BelongsTo
    {
        return $this->belongsTo(Loterie::class, 'second_loterie_id');
    }
}
