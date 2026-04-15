<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loterie;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $center = Auth::user()->center;

        // $errorMessage = [
        //     "Loteria Nac. cerrada",
        //     "Num. 70 para  la lot, Nac supera el limite de 20$ habiendose jugado 10-20€",
        // ];
        $errorMessage = [];
        //validar errores
        foreach ($request->detalles as $detalle) {
            $tipo = $detalle['tipo'];
            //loteria cerrada o ya han salido resultados
            if ($detalle["loteriaId"]) {
                $loteria = Loterie::find($detalle["loteriaId"]);

                if (!$loteria->disponible) {

                    $errorMessage[] = "$loteria->short_name ya ha cerrado ventas ($tipo)";
                }
            }
            if ($detalle["loteriaSecondId"]) {
                $segundaLoteria = Loterie::find($detalle["loteriaSecondId"]);

                if (!$segundaLoteria->disponible) {

                    $errorMessage[] = "$segundaLoteria->short_name ya ha cerrado ventas ($tipo)";
                }
            }

            //validar que el monto jugado no supere las jugadas realizada monto limite
            $montoJugadoDiaHoy = (float) (
                Order::where("date", Carbon::now()->format("Y-m-d"))
                ->myCenter()
                ->join("order_details", "orders.id", "=", "order_details.order_id")
                ->where("order_details.number", str_replace('-', '', $detalle['numero']))
                ->where("order_details.loterie_id", $detalle['loteriaId'])
                ->where("order_details.second_loterie_id", $detalle['loteriaSecondId'])
                ->where("order_details.type", $detalle['tipo'])
                ->sum("order_details.monto_jugada") ?? 0
            );

            switch ($detalle['tipo']) {
                case 'Qui':
                    $montoPermitido = $center->quiniela_monto_soportado;

                    break;
                case 'Pal':
                    $montoPermitido = $center->pale_monto_soportado;
                    break;
                case 'SPal':
                    $montoPermitido = $center->suppale_monto_soportado;
                    break;
                case 'Tri':
                    $montoPermitido = $center->tripleta_monto_soportado;
                    break;

                default:
                    break;
            }

            $montoNuevo = (float) ($detalle['monto'] ?? 0);

            if (($montoJugadoDiaHoy + $montoNuevo) > $montoPermitido) {

                $tipo = $detalle['tipo'];
                $numero = str_replace('-', '', $detalle['numero']);
                $diferenciaDisponible = max(0, $montoPermitido - $montoJugadoDiaHoy);
                if ($detalle['tipo'] == "SPal") {
                    $errorMessage[] = "($tipo)  {$numero} - {$loteria->short_name} - {$segundaLoteria->short_name}  ha superado el monto permitido diario ($montoPermitido$). Disponible: {$diferenciaDisponible}$.";
                } else {
                    $errorMessage[] = "($tipo)  {$numero} - {$loteria->short_name} ha superado el monto permitido diario ($montoPermitido$). Disponible: {$diferenciaDisponible}$.";
                }
            }
        }


        if (count($errorMessage) == 0) {
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
        return response()->json([
            'success' => false,
            'message' => 'Ha ocurrido un error  ',
            'messageList' => $errorMessage,
        ]);
    }
}
