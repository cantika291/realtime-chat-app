<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        $allUsers = User::where('id', '!=', auth()->id())->get();

        $messages = Message::where(function ($query) use ($id) {

            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $id);

        })->orWhere(function ($query) use ($id) {

            $query->where('sender_id', $id)
                  ->where('receiver_id', auth()->id());

        })->orderBy('created_at', 'asc')->get();

        return view('chat.show', compact(
            'user',
            'messages',
            'allUsers'
        ));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
        'message' => 'required'
    ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $id,
            'message' => $request->message,
        ]);


        broadcast(new MessageSent($message));

        return response()->json([
            'success' => true
        ]);
    }
}