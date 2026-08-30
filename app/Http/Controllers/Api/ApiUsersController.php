<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CenterLoterie;
use App\Models\Loterie;
use App\Models\LoterieResults;
use App\Services\LoterieScraperService;
use App\Services\PremiosDoScraperService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUsersController extends Controller
{
    /**
     * Obtiene los usuarios disponibles para filtrar ventas según el centro del usuario autenticado.
     *
     * - Si el usuario NO tiene `show_all_orders`, devuelve solo su propio registro.
     * - Si el usuario SÍ tiene `show_all_orders`, devuelve la unión de:
     *     · Usuarios activos del mismo centro.
     *     · Usuarios con órdenes registradas (aunque estén inactivos o hayan cambiado de centro).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCenterVentas()
    {
        try {
            $authUser = auth()->user();
            $centerId = $authUser->center_id;

            /**
             * Si el usuario autenticado NO tiene permiso para ver todas las órdenes,
             * devolvemos únicamente su propio registro como único elemento del listado.
             * Esto permite que el select del frontend lo recorra igual que la lista completa.
             */
            if (!$authUser->show_all_orders) {
                return response()->json([
                    'code' => 200,
                    'data' => [
                        [
                            'id'       => $authUser->id,
                            'name'     => $authUser->name,
                            'username' => $authUser->username,
                            'email'    => $authUser->email,
                            'active'   => $authUser->active,
                        ]
                    ]
                ]);
            }

            /**
             * Si el usuario tiene permiso (show_all_orders = true), construimos la lista
             * combinando dos grupos de usuarios sin duplicados:
             *
             * Grupo 1 → Usuarios activos que pertenecen actualmente al centro.
             *            Estos se incluyen aunque no tengan ninguna orden registrada.
             *
             * Grupo 2 → Usuarios que tienen al menos una orden creada (created_by),
             *            sin importar si están activos o si ya no pertenecen al centro.
             *            Se traen porque existe historial y no deben desaparecer del filtro.
             */
            $users = \App\Models\User::where(function ($q) use ($centerId) {
                // Grupo 1: activos del centro
                $q->where('center_id', $centerId)
                    ->where('active', 1);
            })
                ->orWhereIn('id', function ($sub) {
                    // Grupo 2: usuarios con órdenes registradas (no eliminadas)
                    $sub->select('created_by')
                        ->from('orders')
                        ->whereNotNull('created_by')
                        ->whereNull('deleted_at');
                })
                ->select('id', 'name', 'username', 'email', 'active', 'center_id')
                ->distinct()
                ->get();

            return response()->json([
                'code' => 200,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al obtener los usuarios',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
