<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    public function store(Request $request)
    {
        $assess = $request->input();
        Assessment::where('application_id', $assess[0]['application_id'])->delete();

        foreach($request->input() as $index => $item){
            log::info($item);
            $assessment = new Assessment();
            $assessment->application_id = $item['application_id'];
            $assessment->key = $item['key'];
            $assessment->tax = $item['tax'];
            $assessment->amount = $item['amount'];
            $assessment->penalty = $item['penalty'];
            $assessment->total = $item['total'];
            $assessment->save();
        }
        return response()->json([
            'message' => 'Assessment Set Successfully',
            'data' => []

   ],200);
    }

    public function show($id)
    {
        $occu = Assessment::where('application_id', $id)->get();
        return response()->json($occu);
    }
}
