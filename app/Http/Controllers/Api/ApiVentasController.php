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

                    $errorMessage[] = "$loteria->short_name ya ha cerrado ventas";
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

    public function searchVenta(Request $request)
    {
        $onlyTrash = $request->boolean('onlyTrash', false);
        $query = Order::query()
            ->with([
                'orderDetails' => function ($q) use ($request) {

                    if (!empty($request->loteriaIds)) {
                        $q->whereIn('loterie_id', $request->loteriaIds)
                            ->orWhereIn('second_loterie_id', $request->loteriaIds);
                    }

                    if (!empty($request->loteria)) {
                        $q->whereIn('loterie_id', $request->loteria);
                    }

                    if (!empty($request->type)) {
                        $q->whereIn('type', $request->type);
                    }

                    $q->with(['loterie', 'secondLoterie']);
                }
            ])
            ->myCenter();

        if ($onlyTrash) {
            $query->onlyTrashed();
        }
        // 📅 FILTRO POR FECHAS
        if ($request->fecha_inicio) {
            $query->whereDate('date', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('date', '<=', $request->fecha_fin);
        }

        // 🎟️ FILTRO POR CÓDIGO DE TICKET
        if ($request->code) {
            $query->where('code', 'like', $request->code . '%');
        }

        // 🎯 FILTRO POR LOTERÍAS (orderDetails)
        if (!empty($request->loteriaIds)) {
            $query->whereHas('orderDetails', function ($q) use ($request) {
                $q->whereIn('loterie_id', $request->loteriaIds)
                    ->orWhereIn('second_loterie_id', $request->loteriaIds);
            });
        }

        // 🆕 FILTRO POR LOTERÍA DIRECTA (si aplica a la tabla orders)
        if (!empty($request->loteria)) {
            $query->whereHas('orderDetails', function ($q) use ($request) {

                $q->whereIn('loterie_id', $request->loteria);
            });
        }

        // 🆕 FILTRO POR TYPE
        if (!empty($request->type)) {
            $query->whereHas('orderDetails', function ($q) use ($request) {

                $q->whereIn('order_details.type', $request->type); // ['Qui', 'Pal', 'SPal', 'Tri']
            });
        }

        // 🆕 FILTRO POR CREATED_BY
        // if (!empty($request->created_by)) {
        //     $query->where('created_by', $request->created_by);
        // }

        // 🏆 FILTRO PREMIADOS / NO PREMIADOS
        if ($request->premiado !== null) {
            if ($request->premiado) {
                $query->whereHas('orderDetails', function ($q) {
                    $q->where('premiado', 1);
                });
            } else {
                $query->whereDoesntHave('orderDetails', function ($q) {
                    $q->where('premiado', 0);
                });
            }
        }

        // 💰 FILTRO PAGADOS / NO PAGADOS
        if ($request->pagado !== null) {
            if ($request->pagado) {
                $query->whereNotNull('paid_at');
            } else {
                $query->whereNull('paid_at');
            }
        }

        // 📊 ORDEN Y RESULTADO
        $orders = $query->latest()->get();

        $result = [];

        $result = $orders->map(function ($order) {
            return $this->formatOrder($order);
        });

        return response()->json($result);
    }

    private function formatOrder($order)
    {
        $details = [];

        foreach ($order->orderDetails as $detail) {
            $details[] = [
                'id' => $detail->id,
                'loterie_id' => $detail->loterie_id,
                'loterie_nombre' => $detail->loterie->nombre ?? null,

                'second_loterie_id' => $detail->second_loterie_id,
                'second_loterie_nombre' => $detail->secondLoterie->nombre ?? null,

                'number' => $detail->number,
                'type' => $detail->type,
                'monto_jugada' => $detail->monto_jugada,
                'premiado' => $detail->premiado,
            ];
        }

        return [
            'id' => $order->id,
            'code' => $order->code,

            'date' => $order->date ? Carbon::parse($order->date)->format('d-m-Y')
                : null,,

            'premiado' => $order->premiado,

            'created_by' => $order->created_by,
            'created_by_name' => $order->createdBy?->name,
            'created_by_code' => $order->createdBy?->username,

            // =========================
            // FECHAS FORMATEADAS
            // =========================
            'paid_at' => $order->paid_at
                ? Carbon::parse($order->paid_at)->format('d-m-Y')
                : null,

            'deleted_at' => $order->deleted_at
                ? Carbon::parse($order->deleted_at)->format('d-m-Y')
                : null,

            'created_at' => $order->created_at
                ? Carbon::parse($order->created_at)->format('d-m-Y')
                : null,

            // =========================
            // USUARIOS
            // =========================
            'paid_by' => $order->paid_by,
            'paid_by_name' => $order->paidBy?->name,
            'paid_by_code' => $order->paidBy?->username,

            'deleted_by' => $order->deleted_by,
            'deleted_by_name' => $order->deletedBy?->name,
            'deleted_by_code' => $order->deletedBy?->username,

            // =========================
            // OTROS CAMPOS
            // =========================
            'porcentaje_comision' => $order->porcentaje_comision,

            'total_venta_bruto' => $order->total_venta_bruto,
            'total_comision' => $order->total_comision,
            'total_neto' => $order->total_neto,
            'total_premiado' => $order->total_premiado,
            'qr_code' => $order->qr_code,

            'details' => $details
        ];
    }

    public function findVenta($id)
    {
        $order = Order::with([
            'orderDetails.loterie',
            'orderDetails.secondLoterie',
            'createdBy',
            'paidBy',
            'deletedBy'
        ])
            ->myCenter() // 🔥 aquí la condición
            ->withTrashed()
            ->findOrFail($id);

        return response()->json(
            $this->formatOrder($order)
        );
    }
}
