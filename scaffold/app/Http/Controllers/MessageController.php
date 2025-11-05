<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function store(Request $request, Sponsorship $sponsorship)
    {
        Gate::authorize('message', $sponsorship);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = new Message([
            'body' => $validated['body'],
        ]);
        $message->sender()->associate($request->user());
        $sponsorship->messages()->save($message);

        return back()->with('status', 'メッセージを送信しました。');
    }
}
