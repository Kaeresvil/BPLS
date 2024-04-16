<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use App\Models\User;

class PusherAuthController extends Controller
{

    public function authenticate(Request $request)
    {
        // Authenticate the user (e.g., using Laravel's authentication system)
        // if (!Auth::check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // Generate the presence channel authentication response
        $parts = explode(".", $request->input('channel_name'));
        $id = intval($parts[1]); 
        $auth_user = User::find($id);

        $pusher = new Pusher(
            config('services.pusher.key'),
            config('services.pusher.secret'),
            config('services.pusher.app_id'),
            [
                'cluster' =>"ap1",
                'useTLS' => true,
            ]
        );

        $presence_data = ['id' => $auth_user->id, 'name' => $auth_user->full_name];
        $socket_id = $request->input('socket_id');
        $channel_name = $request->input('channel_name');
        $auth = $pusher->presence_auth($channel_name, $socket_id, $auth_user->id, $presence_data);

        return response($auth);
    }
}
