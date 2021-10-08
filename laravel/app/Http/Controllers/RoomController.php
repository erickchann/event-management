<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function create(Event $event) {
        return view('rooms.create', compact('event'));
    }

    public function store(Request $request, Event $event) {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required',
            'channel' => 'required'
        ]);

        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'channel_id' => $request->channel
        ]);

        return redirect("/events/$event->id")->with('status', 'Room successfully created');
    }
}
