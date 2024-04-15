<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class PusherAuthController extends Controller
{

    public function authenticate(Request $request)
    {
        // Authenticate the user (e.g., using Laravel's authentication system)
        // if (!Auth::check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // Generate the presence channel authentication response
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' =>"ap1",
                'useTLS' => true,
            ]
        );

        $presence_data = ['id' => 1, 'name' => "Admin edit  Admin"];
        $socket_id = $request->input('socket_id');
        $channel_name = $request->input('channel_name');
        $auth = $pusher->presence_auth($channel_name, $socket_id, 1, $presence_data);

        return response($auth);
    }
}
