<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSeatReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'venue_seat_id',
        'user_id',
        'session_id',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'metadata' => 'json',
    ];

    /**
     * The event relationship
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * The venue seat relationship
     */
    public function venueSeat()
    {
        return $this->belongsTo(VenueSeat::class, 'venue_seat_id');
    }

    /**
     * The user relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include active reservations
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope a query to only include expired reservations
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Clean up expired temp seat reservations
     */
    public static function cleanupExpired()
    {
        return self::expired()->delete();
    }
}
