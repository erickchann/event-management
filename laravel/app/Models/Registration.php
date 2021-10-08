<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    protected $appends = ['event', 'session_ids'];

    public function getEventAttribute() {
        $eventId = EventTicket::find($this->ticket_id)->event_id;

        return Event::with('organizer')->find($eventId)->makeHidden('organizer_id');
    }

    public function getSessionIdsAttribute() {
        return SessionRegistration::where('registration_id', $this->id)->pluck('id');
    }
}
