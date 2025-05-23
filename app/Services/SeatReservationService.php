<?php

namespace App\Services;

use App\Models\EventSeat;
use App\Models\TempSeatReservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SeatReservationService
{
    /**
     * Reservation time in minutes
     */
    const RESERVATION_TIME = 10;

    /**
     * Reserve seats temporarily for the current user
     *
     * @param int $eventId The event ID
     * @param array $venueSeatIds Array of venue seat IDs to reserve
     * @param array $metadata Additional metadata to store with reservation
     * @return bool Success or failure
     */
    public function reserveSeats(int $eventId, array $venueSeatIds, array $metadata = []): bool
    {
        $sessionId = Session::getId();
        $userId = Auth::id();
        $expiresAt = now()->addMinutes(self::RESERVATION_TIME);

        try {
            DB::beginTransaction();
            $this->clearUserReservations($eventId, $sessionId);
            $unavailableSeats = $this->getUnavailableSeats($eventId, $venueSeatIds);

            if ($unavailableSeats->count() > 0) {
                DB::rollBack();
                Log::warning('Attempted to reserve unavailable seats', [
                    'event_id' => $eventId,
                    'unavailable_seats' => $unavailableSeats->pluck('id')->toArray(),
                    'user_id' => $userId,
                    'session_id' => $sessionId
                ]);
                return false;
            }

            if (!empty($venueSeatIds)) {
                $mainSeatId = $venueSeatIds[0];
                TempSeatReservation::create([
                    'event_id' => $eventId,
                    'venue_seat_id' => $mainSeatId,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'expires_at' => $expiresAt,
                    'metadata' => json_encode($metadata),
                ]);

                foreach (array_slice($venueSeatIds, 1) as $seatId) {
                    TempSeatReservation::create([
                        'event_id' => $eventId,
                        'venue_seat_id' => $seatId,
                        'user_id' => $userId,
                        'session_id' => $sessionId,
                        'expires_at' => $expiresAt,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reserve seats: ' . $e->getMessage(), [
                'event_id' => $eventId,
                'seat_ids' => $venueSeatIds,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get unavailable seats (either permanently booked or temporarily reserved)
     *
     * @param int $eventId
     * @param array $venueSeatIds
     * @return \Illuminate\Support\Collection
     */
    public function getUnavailableSeats(int $eventId, array $venueSeatIds)
    {
        $unavailableEventSeats = EventSeat::where('event_id', $eventId)
            ->whereIn('venue_seat_id', $venueSeatIds)
            ->where('status', '!=', 'available')
            ->get();

        $unavailableTempSeats = TempSeatReservation::where('event_id', $eventId)
            ->whereIn('venue_seat_id', $venueSeatIds)
            ->active()
            ->where('session_id', '!=', Session::getId())
            ->get();
        $unavailableSeats = $unavailableEventSeats->merge($unavailableTempSeats);

        return $unavailableSeats;
    }

    /**
     * Clear any existing reservations by this user/session for this event
     *
     * @param int $eventId
     * @param string $sessionId
     * @return void
     */
    public function clearUserReservations(int $eventId, string $sessionId): void
    {
        TempSeatReservation::where('event_id', $eventId)
            ->where('session_id', $sessionId)
            ->delete();
    }

    /**
     * Confirm reservations by marking the seats as booked in event_seats
     *
     * @param int $eventId
     * @param string $sessionId
     * @return bool
     */
    public function confirmReservations(int $eventId, string $sessionId): bool
    {
        try {
            DB::beginTransaction();

            $reservations = TempSeatReservation::where('event_id', $eventId)
                ->where('session_id', $sessionId)
                ->active()
                ->get();

            if ($reservations->isEmpty()) {
                DB::rollBack();
                return false;
            }

            foreach ($reservations as $reservation) {
                EventSeat::where('event_id', $eventId)
                    ->where('venue_seat_id', $reservation->venue_seat_id)
                    ->update(['status' => 'booked']);
            }

            $this->clearUserReservations($eventId, $sessionId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to confirm seat reservations: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all seats currently reserved by this user session
     *
     * @param int $eventId
     * @return \Illuminate\Support\Collection
     */
    public function getUserReservedSeats(int $eventId)
    {
        $sessionId = Session::getId();

        return TempSeatReservation::where('event_id', $eventId)
            ->where('session_id', $sessionId)
            ->active()
            ->with('venueSeat')
            ->get();
    }

    /**
     * Get the main reservation record with metadata
     *
     * @param int $eventId
     * @return \App\Models\TempSeatReservation|null
     */
    public function getUserReservation(int $eventId)
    {
        $sessionId = Session::getId();

        return TempSeatReservation::where('event_id', $eventId)
            ->where('session_id', $sessionId)
            ->whereNotNull('metadata')
            ->active()
            ->first();
    }

    /**
     * Extend current user's seat reservations
     *
     * @param int $eventId
     * @param int $minutes
     * @return bool
     */
    public function extendReservations(int $eventId, int $minutes = self::RESERVATION_TIME): bool
    {
        $sessionId = Session::getId();
        $newExpiry = now()->addMinutes($minutes);

        return TempSeatReservation::where('event_id', $eventId)
            ->where('session_id', $sessionId)
            ->update(['expires_at' => $newExpiry]);
    }
}
