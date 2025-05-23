<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'venue_seat_id',
        'status'
    ];

    /**
     * Get the event that owns the seat.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the venue seat associated with this event seat.
     */
    public function venueSeat()
    {
        return $this->belongsTo(VenueSeat::class);
    }

    /**
     * Scope a query to only include available seats.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include unavailable seats.
     */
    public function scopeUnavailable($query)
    {
        return $query->where('status', 'unavailable');
    }

    /**
     * Scope a query to only include booked seats.
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope a query to only include sold seats.
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }
}
