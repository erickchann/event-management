<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create(Event $event) {
        return view('tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event) {
        $request->validate([
            'name' => 'required',
            'cost' => 'required',
        ]);

        if ($request->special_validity == 'date') {
            $request->validate([
                'valid_until' => 'required'
            ]);
        }

        if ($request->special_validity == 'amount') {
            $request->validate([
                'amount' => 'required'
            ]);
        }

        if (!$request->special_validity) {
            EventTicket::create([
                'event_id' => $event->id,
                'name' => $request->name,
                'cost' => $request->cost,
                'special_validity' => NULL
            ]);

            return redirect("/events/$event->id")->with('status', 'Ticket successfully created');
        }

        $specialValidity = ['type' => $request->special_validity, $request->special_validity => $request->special_validity == 'amount' ? $request->amount : $request->valid_until];

        EventTicket::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'cost' => $request->cost,
            'special_validity' => json_encode($specialValidity)
        ]);

        return redirect()->back()->with('status', 'Ticket successfully created');
    }
}
