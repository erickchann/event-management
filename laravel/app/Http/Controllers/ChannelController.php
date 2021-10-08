<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Event;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function create(Event $event) {
        return view('channels.create', compact('event'));
    }

    public function store(Request $request, Event $event) {
        $request->validate([
            'name' => 'required'
        ]);

        Channel::create([
            'event_id' => $event->id,
            'name' => $request->name
        ]);

        return redirect("/events/$event->id")->with('status', 'Channel successfully created');
    }
}
