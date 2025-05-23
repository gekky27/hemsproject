<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizers_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venues_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'event_id');
    }

    public function eventSeats()
    {
        return $this->hasMany(EventSeat::class, 'event_id');
    }
}
