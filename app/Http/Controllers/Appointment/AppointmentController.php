<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Occupancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();


        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search'])) {
            $search = $params['search'];
            $searchable = ['name', 'description'];
            $query = Appointment::where(function ($query) use ($search) {
                $query->where('date', 'like', '%' . $search . '%')
                      ->orWhere('date_claimed', 'like', '%' . $search . '%')
                      ->orWhereHas('applicant', function($query) use($search) {
                        $query->where('first_name', 'like', '%' . $search . '%')
                        ->where('last_name', 'like', '%' . $search . '%');
                       })
                       ->orWhereHas('application', function($query) use($search) {
                        $query->where('ref_no', 'like', '%' . $search . '%');
                    });
            });
            if(Auth::user()->role == 'Applicant'){
           $query = $query->where('user_id',Auth::user()->id)->with(['applicant','application'])->orderBy($orderByColumn, $direction)->paginate($limit);
           }else{
            $query = $query->orderBy($orderByColumn, $direction)->with(['applicant','application'])->paginate($limit);
           }
        }else{
            if(Auth::user()->role == 'Applicant'){
            $query = Appointment::where('user_id',Auth::user()->id)->with(['applicant','application'])->orderBy($orderByColumn, $direction)->paginate($limit);
            }else{
                $query = Appointment::orderBy($orderByColumn, $direction)->with(['applicant','application'])->paginate($limit);
            }
        }

        return response()->json([
            'message' => 'Appointment List Fetch Successfully',
            'data' => $query
        ], 200);
 
    }

    public function store(Request $request)
    {
        $exist_appointment = Appointment::where('application_id', $request->application_id)->where('is_claimed', 0)->first();
        if($exist_appointment){
            throw ValidationException::withMessages([
                'authentication' => 'Your application has been scheduled already. If you want to re-schedule please update.',
            ]);
        }

        $appointment = new Appointment();

       $appointment->user_id = Auth::user()->id;
       $appointment->application_id = $request->application_id;
       $appointment->is_claimed = 0;
       $appointment->date = Carbon::parse($request->date)->format('Y-m-d');

       $appointment->save();

       return response()->json([
        'message' => 'Appointment Schedule Submitted Successfully',
        'data' => $appointment
    ], 200);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if($appointment->is_claimed == 1){
            throw ValidationException::withMessages([
                'authentication' => 'Your application claimed already.',
            ]);
        }
        $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
        $appointment->update();
        return response()->json([
            'message' => 'Appointment Schedule Updated Successfully',
            'data' => $appointment
        ], 200);
    }

    public function show($id)
    {
        $post = Appointment::find($id);
        return response()->json($post);
    }


    public function availableDate(Request $request)
    {
        $params = $request->all();
        $firstDayOfMonth = Carbon::parse($params['date'])->firstOfMonth()->format('Y-m-d');
        $lastDayOfMonth = Carbon::parse($params['date'])->lastOfMonth()->format('Y-m-d');

        $total = Occupancy::where('id', 1)->first();
        $daysInMonth = CarbonPeriod::create($firstDayOfMonth, $lastDayOfMonth);                    
        $attribute = [];
    
        foreach ($daysInMonth as $day) {
            $date =  Carbon::parse($day)->format('Y-m-d');
            $appointed = Appointment::where('date',$date)->where('is_claimed', 0)->get();
            Log::info($appointed);
            if($appointed->count() == $total->total){
                array_push($attribute, $date);
            }
        }

       
        return response()->json($attribute);
    }

}
