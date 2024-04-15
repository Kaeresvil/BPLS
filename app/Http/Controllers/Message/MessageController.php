<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messagesQuery = Message::getMessagesQueryBetweenTwoUsers($request, auth()->user()->id, $request->receiver_id);

        if($request->earlier_date) {
            $dateFormatted = Carbon::now()->format('Y-m-d H:i:s');

            $messagesQuery->where("created_at", "<", $dateFormatted);
        }

        // display top 10 messages for user
        $result = $messagesQuery->orderBy('created_at', 'DESC')
                        ->limit($request->limit ?? 10)
                        ->get();

        if($result->count()) {
            foreach ($result as $msg) {
                if((int) $msg->receiver_id === auth()->user()->id) {
                    $msg->update(['seen' => 1]);
                }
            }
        }

        return response()->json([
            'message' => 'Messages Fetch Successfully',
            'data' => $result
        ], 200);
    }

    public function store(Request $request)
    {
        if(!$request->message_content) {
            throw ValidationException::withMessages([
                'authentication' => 'No message!.',
            ]);
        }

        $message = new Message();
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $request->receiver_id;
        $message->content = $request->message_content;
        $message->save();

        $updatedMessage = Message::with(['sender', 'receiver'])->find($message->id);

        // fire the message sent event
        MessageSent::dispatch($updatedMessage);

        return response()->json([
            'message' => 'Message Sent Successfully',
            'data' => $updatedMessage
        ], 220);
    }



}
