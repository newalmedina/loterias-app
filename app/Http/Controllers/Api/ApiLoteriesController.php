<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CenterLoterie;
use App\Models\Loterie;
use App\Models\LoterieResults;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoteriesController extends Controller
{
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

    public function getResults(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        $query = LoterieResults::query();

        $startDate = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : null;
        $endDate   = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : null;

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $loterieResults = $query->with(['loterie' => fn($q) => $q->active()])->get();

        $results = $loterieResults->filter(fn($lotery) => $lotery->loterie) // solo activas
            ->map(function ($lotery) {
                return [
                    "date"       => $lotery->date ? Carbon::parse($lotery->date)->format("d-m-Y") : null,
                    "short_name" => $lotery->loterie->short_name,
                    "name"       => $lotery->loterie->nombre,
                    "slug"       => $lotery->loterie->slug,
                    "code"       => $lotery->loterie->code,
                    "numbers"    => $lotery->numbers_formatted,
                    "image"      => $lotery->loterie->image_base64,
                ];
            })
            ->values(); // reindexar array

        return response()->json([
            'code' => 200,
            'data' => $results
        ]);
    }
}
