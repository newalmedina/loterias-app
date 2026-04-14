<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiVentasController extends Controller
{
    /**
     * Finalizar una venta recibida desde la app.
     */
    public function finalizarVenta(Request $request)
    {
        $request->validate([
            'detalles' => 'required|array|min:1',
            'detalles.*.numero' => 'required|string',
            'detalles.*.monto' => 'required|numeric',
            'detalles.*.tipo' => 'required|string',
            'detalles.*.loteriaId' => 'required|integer',
            'detalles.*.loteriaSecondId' => 'nullable|integer',
        ]);

        $order = Order::create([
            'date' => now(),
        ]);

        foreach ($request->detalles as $detalle) {
            OrderDetail::create([
                'order_id' => $order->id,
                'loterie_id' => $detalle['loteriaId'],
                'second_loterie_id' => $detalle['loteriaSecondId'] ?? null,
                'number' => str_replace('-', '', $detalle['numero']),
                'type' => $detalle['tipo'],
                'monto_jugada' => $detalle['monto'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Venta finalizada correctamente',
        ]);
    }
}
