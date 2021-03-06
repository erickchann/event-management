<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all()->sortBy('date');

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|regex:/^[a-z0-9-]*$/',
            'date' => 'required',
        ]);

        $organizerId = Auth::user()->id;

        $slug = Event::where('slug', $request->slug)->first();

        if ($slug) return redirect()->back()->withInput($request->all)->withErrors(['error' => 'Slug is already used']);

        $event = Event::create([
            'organizer_id' => $organizerId,
            'name' => $request->name,
            'slug' => $request->slug,
            'date' => $request->date,
        ]);

        return redirect("/events/$event->id")->with('status' , 'Event successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('events.detail', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|regex:/^[a-z0-9-]*$/',
            'date' => 'required',
        ]);

        $organizerId = Auth::user()->id;

        if ($request->slug != $event->slug) {
            $slug = Event::where('slug', $request->slug)->first();
    
            if ($slug) return redirect()->back()->withInput($request->all)->withErrors(['error' => 'Slug is already used']);
        }


        $event->update([
            'organizer_id' => $organizerId,
            'name' => $request->name,
            'slug' => $request->slug,
            'date' => $request->date,
        ]);

        return redirect("/events/$event->id")->with('status' , 'Event successfully updated');
    }
}
