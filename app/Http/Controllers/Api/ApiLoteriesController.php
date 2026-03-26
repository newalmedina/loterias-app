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

        $loterieCenters = CenterLoterie::active()->join("loteries", "center_loteries.loterie_id", "loteries.id")->orderBy("loteries.name", "asc")->get();

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
        // Obtener parámetros de la query
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');
        $reloadResultados = filter_var($request->query('reload', false), FILTER_VALIDATE_BOOLEAN);
        $loteries  = $request->query('loteries', []);

        // Convertimos a Carbon para poder iterar
        $start = $startDate ? Carbon::parse($startDate) : null;
        $end   = $endDate   ? Carbon::parse($endDate)   : null;

        // Si reloadResultados es true y las fechas son válidas, llamamos al Service
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

        // Si no recargamos, devolvemos los resultados existentes de la BD
        $query = LoterieResults::query();

        if ($start) {
            $query->whereDate('date', '>=', $start);
        }

        if ($end) {
            $query->whereDate('date', '<=', $end);
        }


        if (count($loteries) > 0) {
            $query->whereIn('loterie_id', $loteries);
        }

        $loterieResults = $query->with(['loterie' => fn($q) => $q->active()])->get();

        $results = $loterieResults
            ->filter(fn($lotery) => $lotery->loterie) // solo loterías activas
            ->sortBy(fn($lotery) => $lotery->loterie->nombre) // ordenar por nombre ascendente
            ->map(function ($lotery) {
                return [
                    "loterie_id" => $lotery->loterie_id,
                    "date"       => $lotery->date ? Carbon::parse($lotery->date)->format("d-m-Y") : null,
                    "short_name" => $lotery->loterie->short_name,
                    "name"       => $lotery->loterie->nombre,
                    "slug"       => $lotery->loterie->slug,
                    "code"       => $lotery->loterie->code,
                    "numbers"    => $lotery->numbers_formatted,
                    "image"      => $lotery->loterie->image_base64,
                ];
            })
            ->values(); // Reindexar array
        return response()->json([
            'code' => 200,
            'data' => $results
        ]);
    }
}
