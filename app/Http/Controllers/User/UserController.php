<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersId;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\DeactivatedEmail;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
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
            $searchable = ['name', 'description'];
            $query = User::where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('extension_name', 'like', '%' . $search . '%')
                      ->orWhere('middle_name', 'like', '%' . $search . '%');
            })
            ->orWhere('email', 'like', '%' . $search . '%')
            ->with('role')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = User::with('role')->orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Users List Fetch Successfully',
            'data' => $query
        ], 200);
 
    }
    
    public function profileUpdate(Request $request, $id)
    {

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
            Rule::unique('users')->ignore(auth()->id())->where(function ($query) use ($request) {
                return $query->where('email', '!=', $request->user()->email);
            }),
        ],
        ]);

        $user = User::find($id);
        if($request->old_password != ''){
            if(Hash::check($request->old_password,$user->password)){
                $request->validate([
                    'password' => ['required', 'string', 'min:8'],
                    'confirmpassword' => ['required', 'string', 'min:8'],
                ]);
                if($request->password == $request->confirmpassword){
                    $user->first_name = $request->first_name;
                    $user->middle_name = $request->middle_name;
                    $user->last_name = $request->last_name;
                    $user->extension_name = $request->extension_name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
    
                }else{
                    throw ValidationException::withMessages([
                        'authentication' => 'Password does not match!',
                    ]);
                }
               
            }else{
                throw ValidationException::withMessages([
                    'authentication' => 'Old Password does not match!',
                ]);
            }
        }else{
            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->extension_name = $request->extension_name;
            $user->email = $request->email;
        }

        $user->save();

        $response = [
            'data' => $user,
            'message' => 'Profile Updated Successfully',
    
        ];

        return response()->json($response);

    }

    public function register(Request $request)
    {


        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'confirmpassword' => ['required', 'string', 'min:8'],
        ]);
       

            $user = new User();
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->extension_name = $request->extension_name;
                $user->email = $request->email;
                $user->is_verified = $request->is_verified;
                $user->role_id = $request->isapplicant ? 3:2;
                $user->password = Hash::make($request->password);

               
            if($request->password == $request->confirmpassword){
                $user->save();
                $data = $user;
                $message = "Register Account Successfully";

            }else{
                throw ValidationException::withMessages([
                    'authentication' => 'Register account failed! Password does not match!',
                ]);
            }

            if (isset($request['users_files'])) {
                foreach ($request['users_files'] as $key => $value) {
                    $fileName = time() . '_' . $value->getClientOriginalName();
                    $folderName = 'public';
                    $path = Storage::disk('local')->putFileAs($folderName, $value, $fileName);

                    UsersId::create([
                        'user_id' => $user->id,
                        'file_name' => $fileName,
                        'file_path' =>  $path
                    ]);
    
                }
            }

            $response = [
                'data' => $data,
                'message' => $message,
        
            ];
    
          
            return response()->json($response);
    

    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
            Rule::unique('users')->ignore($user->id)->where(function ($query) use ($request) {
                return $query->where('email', '!=', $request->user()->email);
            }),
        ],
        ]);


            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->extension_name = $request->extension_name;
            $user->email = $request->email;
            $user->is_verified = intval($request->is_verified);


        $user->save();

        if($user->is_verified == 1 && $user->wasChanged('is_verified')){
        Mail::to($user->email)->send(new VerificationEmail($user->email));
        }
        if($user->is_verified == 0 ){
        Mail::to($user->email)->send(new DeactivatedEmail($user->email));
        }


        $response = [
            'data' => $user,
            'message' => 'User Updated Successfully',
    
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        $response = [
            'data' => $user,
            'message' => 'User Deleted Successfully',
    
        ];
        return response()->json($response);
    }

    public function getOnlineUsers()
    {
        if(!auth()->check()) {
            return response()->json(data: ['users' => []]);
        }
        if(auth()->user()->role_id == 3){
        $users = User::with('unseenMessages')->where('role_id','!=', 3)->where('id', '!=', auth()->user()->id)->get();
        }else{
        $users = User::with('unseenMessages')->where('id', '!=', auth()->user()->id)->get();
        }

        return response()->json(data: ['users' => $users]);
    }

}
