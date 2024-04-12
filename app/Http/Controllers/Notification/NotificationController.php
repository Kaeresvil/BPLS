<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::with('role')->get();
        // return response()->json($users);
        $params = $request->all();

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['admin']) && $params['admin'] == 'Applicant') {
            $query = Notification::where('user_id', Auth::user()->id)->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Notification::where('is_ForStaff', 1)->orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Notification List Fetch Successfully',
            'data' => $query
        ], 200);
 
    }

    public function read($id)
    {
        $model = Notification::find($id);
        $model->is_read = 1;
        $model->update();
        return response()->json([
            'message' => 'Notification Update Successfully',
            'data' => $model
        ], 220);
    }
}
