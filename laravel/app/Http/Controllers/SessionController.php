<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create(Event $event) {
        return view('sessions.create', compact('event'));
    }

    public function store(Request $request, Event $event) {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'speaker' => 'required',
            'room' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required'
        ]);
        
        Session::create([
            'type' => $request->type,
            'title' => $request->title,
            'speaker' => $request->speaker,
            'room_id' => $request->room,
            'cost' => $request->cost,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description
        ]);

        return redirect("/events/$event->id")->with('status', 'Session successfully created');
    }
    
    public function edit(Event $event, Session $session) {
        return view('sessions.edit', ['event' => $event, 'session' => $session]);
    }
    public function update(Request $request, Event $event, Session $session) {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'speaker' => 'required',
            'room' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required'
        ]);
        
        Session::create([
            'type' => $request->type,
            'title' => $request->title,
            'speaker' => $request->speaker,
            'room_id' => $request->room,
            'cost' => $request->cost,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description
        ]);

        return redirect("/events/$event->id")->with('status', 'Session successfully updated');
    }
}
