<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CenterLoterie;
use App\Models\Loterie;
use App\Models\LoterieResults;
use App\Services\LoterieScraperService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoteriesController extends Controller
{
    public function getLoteries()
    {
        try {
            // Obtenemos las loterías activas
            $loteries = Loterie::active()->get()->toArray(); // convertir a array para evitar problemas con Eloquent

            return response()->json([
                'code' => 200,
                'data' => $loteries
            ]);
        } catch (\Exception $e) {
            // Capturamos cualquier excepción y devolvemos un JSON con el error
            return response()->json([
                'code' => 500,
                'message' => 'Error al obtener las loterías',
                'error' => $e->getMessage(), // mensaje del error
            ], 500);
        }
    }
    public function getCenterLoteries()
    {
        $autenticatedUser = Auth::user();

        $loterieCenters = CenterLoterie::active()->get();

        $results = [];

        foreach ($loterieCenters as $loterieCenter) {
            $results[] = [
                "short_name" => $loterieCenter?->loterie?->short_name,
                "name" => $loterieCenter?->loterie?->nombre,
                "slug" => $loterieCenter?->loterie?->slug,
                "code" => $loterieCenter?->loterie?->code,
                "image" => $loterieCenter?->loterie?->image_base64,
            ];
        }

        return response()->json([
            'code' => 200,
            'data' => $results
        ]);
    }

    public function getResults(Request $request, LoterieScraperService $scraper)
    {
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');
        $reloadResultados = filter_var($request->query('reload', false), FILTER_VALIDATE_BOOLEAN);
        $loteries  = $request->query('loteries', []);

        $start = $startDate ? Carbon::parse($startDate) : null;
        $end   = $endDate   ? Carbon::parse($endDate)   : null;

        if ($reloadResultados && $start && $end) {
            $resultsLog = [];

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $result = $scraper->scrapeDate($date);

                if (!$result['success']) {
                    $resultsLog[] = [
                        'status' => 'error',
                        'message' => $result['message'],
                        'date' => $date->format('Y-m-d')
                    ];
                    continue;
                }

                foreach ($result['results'] as $r) {
                    $resultsLog[] = $r;
                }
            }
        }

        // JOIN con la tabla loteries y filtrar solo activas
        $query = LoterieResults::select('loterie_results.*', 'loteries.short_name', 'loteries.nombre', 'loteries.slug', 'loteries.code', 'loteries.image_base64')
            ->join('loteries', function ($join) {
                $join->on('loterie_results.loterie_id', '=', 'loteries.id')
                    ->where('loteries.active', 1); // solo loterías activas
            });

        if ($start) {
            $query->whereDate('loterie_results.date', '>=', $start);
        }

        if ($end) {
            $query->whereDate('loterie_results.date', '<=', $end);
        }

        if (count($loteries) > 0) {
            $query->whereIn('loterie_results.loterie_id', $loteries);
        }

        // Ordenar por nombre de la lotería ascendente
        $loterieResults = $query->orderBy('loteries.nombre', 'asc')->get();

        $results = $loterieResults->map(function ($lotery) {
            return [
                "loterie_id" => $lotery->loterie_id,
                "date"       => $lotery->date ? Carbon::parse($lotery->date)->format("d-m-Y") : null,
                "short_name" => $lotery->short_name,
                "name"       => $lotery->nombre,
                "slug"       => $lotery->slug,
                "code"       => $lotery->code,
                "numbers"    => $lotery->numbers_formatted,
                "image"      => $lotery->image_base64,
            ];
        });

        return response()->json([
            'code' => 200,
            'data' => $results
        ]);
    }
}
