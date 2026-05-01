<?php

namespace App\Models;

use App\Services\QrService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Order extends Model
{
    use SoftDeletes;


    protected $guarded = [];
    protected $appends = [
        'total_venta_bruto',
        'total_comision',
        'total_neto',
        'total_premiado',
        'qr_code',
        'can_delete',
        'can_pay',

        'premiado', // 👈 NUEVO
    ];

    protected $casts = [
        'date' => 'date',
    ];




    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }




    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Appointment.php



    public function getPremiadoAttribute(): bool
    {
        return $this->orderDetails->contains(function ($detail) {
            return (int) $detail->premiado === 1;
        });
    }

    public function getDisabledSalesAttribute(): bool
    {
        return $this->status == 'invoiced';
    }
    // public function scopeMyCenter($query)
    // {
    //     return $query->where('orders.center_id', Auth::user()->center_id);
    // }

    public function scopeMyCenter($query)
    {
        $user = Auth::user();

        $query->where('orders.center_id', $user->center_id);

        if (!$user->show_all_orders) {
            $query->where('orders.created_by', $user->id);
        }

        return $query;
    }
    private static function generateCode($order)
    {
        $date = Carbon::now();

        // Fecha comprimida (base36)
        $datePart = strtoupper(base_convert($date->format('ymd'), 10, 36));

        // Usuario (2 caracteres)
        $userCode = strtoupper(substr(auth()->user()->username ?? 'XXX', 0, 3));

        $prefix = $datePart . $userCode;

        // Secuencia SOLO 2 dígitos (00-99)
        $latest = self::withTrashed()
            ->whereDate('created_at', $date->toDateString())
            ->where('code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextSequence = $latest
            ? ((int) substr($latest->code, -2) + 1)
            : 1;

        do {
            $code = $prefix . str_pad($nextSequence, 2, '0', STR_PAD_LEFT);

            $exists = self::withTrashed()
                ->where('code', $code)
                ->exists();

            if ($exists) {
                $nextSequence++;
            } else {
                break;
            }
        } while (true);

        return $code;
    }

    protected static function booted(): void
    {
        // Asigna automáticamente el usuario autenticado al crear
        static::creating(function ($order) {
            if (Auth::check()) {
                $order->created_by = Auth::id();
                // $order->assigned_user_id = Auth::id();

                $order->porcentaje_comision = Auth::user()->porcentaje_comision;
                $order->code = self::generateCode($order);
                $order->center_id = Auth::user()->center_id;

                $order->uuid = Str::uuid();
            }
        });

        // Asigna automáticamente el usuario que elimina
        static::deleting(function ($order) {
            if (Auth::check() && !$order->isForceDeleting()) {
                $order->deleted_by = Auth::id();
                $order->save();
            }
        });
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    public function getTotalVentaBrutoAttribute(): float
    {
        return round((float) $this->orderDetails->sum('monto_jugada'), 2);
    }

    public function getTotalComisionAttribute(): float
    {
        $total = $this->total_venta_bruto;

        return round($total * ($this->porcentaje_comision / 100), 2);
    }

    public function getTotalNetoAttribute(): float
    {
        return round(
            $this->total_venta_bruto - $this->total_comision,
            2
        );
    }

    public function getTotalPremiadoAttribute(): float
    {
        return round((float) $this->orderDetails->sum('monto_premio'), 2);
    }

    public function getQrCodeAttribute()
    {
        $svg = QrCode::size(200)->generate('Make me into a QrCode!');

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function getCanDeleteAttribute()
    {

        // ⏱️ 1. Validar tiempo (máx 10 min)
        if ($this->created_at->diffInMinutes(now()) >= 10) {
            return false;
        }

        // 📦 2. Cargar detalles con loterías
        $this->loadMissing('orderDetails.loterie', 'orderDetails.secondLoterie');

        foreach ($this->orderDetails as $detail) {

            // 🔹 verificar lotería principal
            if ($detail->loterie_id) {
                $hasResult = \App\Models\LoterieResults::where('loterie_id', $detail->loterie_id)
                    ->whereDate('date', now()->toDateString())
                    ->exists();

                if ($hasResult) {
                    return false;
                }
            }

            // 🔹 verificar segunda lotería (si existe)
            if ($detail->second_loterie_id) {
                $hasResult = \App\Models\LoterieResults::where('loterie_id', $detail->second_loterie_id)
                    ->whereDate('date', now()->toDateString())
                    ->exists();

                if ($hasResult) {
                    return false;
                }
            }
        }

        // ✅ si pasa todo
        return true;
    }


    public function getCanPayAttribute()
    {
        //validar si aun le faltan los 10 min para tirar la loteria
        if ($this->premiado && empty($this->paid_at)) {
            return true;
        }
        return false;
    }
}
