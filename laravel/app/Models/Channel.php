<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function rooms() {
        return $this->hasMany(Room::class);
    }

    public function sessions() {
        return $this->hasManyThrough(Session::class, Room::class, 'channel_id', 'room_id');
    }
}
