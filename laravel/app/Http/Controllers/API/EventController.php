<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Organizer;
use App\Models\Registration;
use App\Models\SessionRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        $events = Event::with('organizer')->get();

        $events->makeHidden(['organizer_id']);

        return response()->json(['events' => $events], 200);
    }

    public function show(Request $request, $organizer_slug, $event_slug) {
        $organizer = Organizer::where('slug', $organizer_slug)->first();

        if (!$organizer) return response()->json(['message' => 'Organizer not found'], 404);
        
        $event = Event::with('channels', 'channels.rooms', 'channels.rooms.sessions', 'tickets')
                      ->where('slug', $event_slug)
                      ->where('organizer_id', $organizer->id)
                      ->first();
        
        
        if (!$event) return response()->json(['message' => 'Event not found'], 404);

        $event->makeHidden(['organizer_id']);
        $event->channels->makeHidden(['event_id']);
        $event->tickets->makeHidden(['event_id', 'special_validity']);

        foreach ($event->channels as $channel) {
            foreach ($channel->rooms as $room) {
                $room->makeHidden(['channel_id', 'capacity']);

                foreach ($room->sessions as $session) {
                    $session->makeHidden(['room_id']);
                }
            }
        }


        // ticket validator
        foreach ($event->tickets as $ticket) {
            $specialValidity = json_decode($ticket->special_validity);

            if (!$specialValidity) {
                $ticket['description'] = null;
                $ticket['available'] = true;
            }

            if ($specialValidity) {
                if ($specialValidity->type == 'date') {
                    $ticket['description'] = 'Available until ' . date('F d, y', strtotime($specialValidity->date));
                    
                    if (strtotime($specialValidity->date) > strtotime(date('Y-m-d'))) {
                        $ticket['available'] = true;
                    } else {
                        $ticket['available'] = false;
                    }
                }
            }

            if ($specialValidity) {
                if ($specialValidity->type == 'amount') {
                    $ticket['description'] = $specialValidity->amount . ' tickets available';
                    
                    if ($specialValidity->amount > 0) {
                        $ticket['available'] = true;
                    } else {
                        $ticket['available'] = false;
                    }
                }
            }
        }

        return response()->json($event, 200);
    }

    public function registration(Request $request, $organizer_slug, $event_slug) {
        // not logged in
        if (!$request->token) return response()->json(['message' => 'User not logged in'], 401);

        $attende = Attendee::where('login_token', $request->token)->first();

        if (!$attende) return response()->json(['message' => 'User not logged in'], 401);


        // Ticket is no longer available
        $ticket = EventTicket::find($request->ticket_id);
        $specialValidity = json_decode($ticket->special_validity);
        
        if ($specialValidity) {
            if ($specialValidity->type == 'date') {
                if (strtotime($specialValidity->date) < strtotime(date('Y-m-d'))) {
                    return response()->json(['message' => 'Ticket is no longer available'], 401);
                }
            }
        }

        if ($specialValidity) {
            if ($specialValidity->type == 'amount') {
                if ($specialValidity->amount <= 0) {
                    return response()->json(['message' => 'Ticket is no longer available'], 401);
                }
            }
        }

        // User already registered
        if (Registration::where('attendee_id', $attende->id)->where('ticket_id', $request->ticket_id)->first()) {
            return response()->json(['message' => 'User already registered'], 401);
        }

        $registration = Registration::create([
            'ticket_id' => $request->ticket_id,
            'attendee_id' => $attende->id,
            'registration_time' => Carbon::now()
        ]);

        if ($request->session_ids) {
            foreach ($request->session_ids as $session) {
                SessionRegistration::create([
                    'registration_id' => $registration->id,
                    'session_id' => $session
                ]);
            }
        }

        return response()->json(['message' => 'Registration successful'], 200);
    }

    public function getRegistrations(Request $request) {
        // not logged in
        if (!$request->token) return response()->json(['message' => 'User not logged in'], 401);

        $attende = Attendee::where('login_token', $request->token)->first();

        if (!$attende) return response()->json(['message' => 'User not logged in'], 401);

        $res = Registration::where('attendee_id', $attende->id)->orderBy('id')->get();
        $res->makeHidden(['id', 'attendee_id', 'ticket_id', 'registration_time']);

        return response()->json(['registration' => $res], 200);
    }
}