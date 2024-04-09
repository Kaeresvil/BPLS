<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::with('role')->get();
        // return response()->json($users);

        $params = $request->all();

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search'])) {
            $search = $params['search'];
            $query = Role::where('name', 'LIKE', '%'.$search.'%')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Role::orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Roles List Fetch Successfully',
            'data' => $query
        ], 200);
 
    }
}
