<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occupancy;

class OccupancyController extends Controller
{
    public function store(Request $request)
    {
        $occu = Occupancy::where('id', 1)->first();
        $occu->total = $request->total;
        $occu->update();

        return response()->json([
            'message' => 'Occupancy Set Successfully',
            'data' => []

   ],200);
    }

    public function show()
    {
        $occu = Occupancy::where('id', 1)->first();
        return response()->json($occu);
    }
}
