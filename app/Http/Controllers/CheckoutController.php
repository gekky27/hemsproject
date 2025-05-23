<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSeat;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tripay;
use App\Services\SeatReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $tripayController;
    protected $seatReservationService;

    public function __construct(TripayController $tripayController, SeatReservationService $seatReservationService)
    {
        $this->tripayController = $tripayController;
        $this->seatReservationService = $seatReservationService;
    }

    public function showSeatSelection(Request $request, $slug)
    {
        $event = Event::with(['venue', 'organizer'])->where('slug', $slug)->firstOrFail();
        if (!$event) {
            Log::error('Event not found with slug: ' . $slug);
            abort(404, 'Event not found');
        }

        $ticketCount = $request->input('ticket_count', 1);
        $totalPrice = $event->ticket_price * $ticketCount;
        if ($ticketCount > 5) {
            $totalPrice = $totalPrice - (50000 * $ticketCount);
        }
        $userReservedSeats = $this->seatReservationService->getUserReservedSeats($event->id);
        $eventSeats = EventSeat::where('event_id', $event->id)
            ->with('venueSeat')
            ->get();

        $venueSeats = collect();

        foreach ($eventSeats as $eventSeat) {
            $venueSeat = $eventSeat->venueSeat;
            $venueSeat->status = $eventSeat->status;

            if ($eventSeat->status === 'available') {
                $tempReservation = DB::table('temp_seat_reservations')
                    ->where('event_id', $event->id)
                    ->where('venue_seat_id', $venueSeat->id)
                    ->where('session_id', '!=', session()->getId())
                    ->where('expires_at', '>', now())
                    ->first();

                if ($tempReservation) {
                    $venueSeat->status = 'temp_reserved';
                }
            }

            $userReservation = $userReservedSeats->first(function ($reservation) use ($venueSeat) {
                return $reservation->venue_seat_id === $venueSeat->id;
            });

            if ($userReservation) {
                $venueSeat->status = 'user_reserved';
                $venueSeat->reservation_expires = $userReservation->expires_at->diffInSeconds(now());
            }

            $venueSeats->push($venueSeat);
        }

        return view('checkout.seats', compact('event', 'ticketCount', 'totalPrice', 'venueSeats'));
    }

    public function processSeatSelection(Request $request, $slug)
    {
        $request->validate([
            'selected_seats' => 'required|json',
            'ticket_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);

        $event = Event::where('slug', $slug)->firstOrFail();
        $selectedSeats = json_decode($request->input('selected_seats'), true);
        $ticketCount = $request->input('ticket_count');
        $totalPrice = $request->input('total_price');

        if (count($selectedSeats) != $ticketCount) {
            Log::warning('Seat count mismatch: selected ' . count($selectedSeats) . ', expected ' . $ticketCount);
            return back()->with('error', 'Jumlah kursi yang dipilih tidak sesuai dengan jumlah tiket');
        }

        $venueSeatIds = array_column($selectedSeats, 'id');
        $validSeats = EventSeat::where('event_id', $event->id)
            ->whereIn('venue_seat_id', $venueSeatIds)
            ->count();

        if ($validSeats !== count($venueSeatIds)) {
            Log::warning('Invalid seat IDs detected', [
                'event_id' => $event->id,
                'requested_ids' => $venueSeatIds,
                'valid_count' => $validSeats
            ]);
            return back()->with('error', 'seat selected are invalid. try again.');
        }

        $reservationSuccess = $this->seatReservationService->reserveSeats($event->id, $venueSeatIds, [
            'ticket_count' => $ticketCount,
            'total_price' => $totalPrice,
            'selected_seats' => $selectedSeats
        ]);

        if (!$reservationSuccess) {
            return redirect()->route('detail-event', $event->slug)
                ->with('error', 'Beberapa kursi yang dipilih sudah tidak tersedia. Silakan pilih kursi lain.');
        }

        return redirect()->route('checkout.payment', $event->slug);
    }

    public function showPayment(Request $request, $slug)
    {
        $event = Event::with(['venue', 'organizer'])->where('slug', $slug)->firstOrFail();
        $reservation = $this->seatReservationService->getUserReservation($event->id);

        if (!$reservation) {
            Log::warning('No active reservation found for payment page');
            return redirect()->route('detail-event', $slug)
                ->with('error', 'Silahkan pilih tiket dan kursi terlebih dahulu');
        }

        $this->seatReservationService->extendReservations($event->id);

        $reservationData = json_decode($reservation->metadata, true);
        $selectedSeats = $reservationData['selected_seats'] ?? [];
        $ticketCount = $reservationData['ticket_count'] ?? 0;
        $totalPrice = $reservationData['total_price'] ?? 0;
        $expiryTime = $reservation->expires_at;

        $paymentChannels = $this->tripayController->getPaymentChannels();
        $filteredChannels = collect($paymentChannels['data'] ?? [])
            ->filter(function ($channel) {
                return $channel['active'] == true;
            })
            ->groupBy('group');

        $originalPrice = $ticketCount * $event->ticket_price;
        $hasDiscount = $ticketCount > 5;
        $discountAmount = $hasDiscount ? 50000 * $ticketCount : 0;
        $totalPrice = $originalPrice - $discountAmount;

        return view('checkout.payment', compact(
            'event',
            'selectedSeats',
            'ticketCount',
            'totalPrice',
            'filteredChannels',
            'expiryTime',
            'reservation'
        ));
    }

    public function processPayment(Request $request, $slug)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $event = Event::with(['venue', 'organizer'])->where('slug', $slug)->firstOrFail();
        $reservation = $this->seatReservationService->getUserReservation($event->id);

        if (!$reservation) {
            Log::warning('No active reservation found for payment processing', [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'session_id' => Session::getId()
            ]);
            return redirect()->route('detail-event', $slug)
                ->with('error', 'Silahkan pilih tiket dan kursi terlebih dahulu');
        }

        $reservationData = json_decode($reservation->metadata, true);
        $selectedSeats = $reservationData['selected_seats'] ?? [];
        $ticketCount = $reservationData['ticket_count'] ?? 0;
        $totalPrice = $reservationData['total_price'] ?? 0;
        if ($ticketCount > 5) {
            $originalPrice = $ticketCount * $event->ticket_price;
            $discountAmount = 50000 * $ticketCount;
            $totalPrice = $originalPrice - $discountAmount;
        }

        $referenceId = 'TICKET-' . strtoupper(Str::random(8)) . '-' . time();
        $paymentCode = $request->input('payment_method');
        $fee = $this->tripayController->calculateFee($paymentCode, $totalPrice);
        $itemDetails = [
            [
                'name' => $event->name,
                'price' => $event->ticket_price,
                'quantity' => $ticketCount,
            ]
        ];

        $seatDescriptions = [];
        foreach ($selectedSeats as $seat) {
            $seatDescriptions[] = $seat['rowName'] . '-' . $seat['seatNumber'];
        }
        $seatInfo = implode(', ', $seatDescriptions);
        $description = "Tiket Event {$event->name} - {$event->event_date} {$event->event_time} - Kursi: {$seatInfo}";
        $tripayData = [
            'method' => $paymentCode,
            'merchant_ref' => $referenceId,
            'amount' => $totalPrice,
            'customer_name' => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->no_whatsapp,
            'order_items' => $ticketCount > 5
                ? [
                    [
                        'name' => $event->name,
                        'price' => $event->ticket_price,
                        'quantity' => $ticketCount,
                    ],
                    [
                        'name' => 'Diskon pembelian > 5 tiket',
                        'price' => -50000 * $ticketCount,
                        'quantity' => 1,
                    ]
                ]
                : [
                    [
                        'name' => $event->name,
                        'price' => $event->ticket_price,
                        'quantity' => $ticketCount,
                    ]
                ],
            'return_url' => route('payment.invoice', $referenceId),
            'expired_time' => (time() + (10 * 60)), // 10 minutes
            'signature' => hash_hmac('sha256', $this->tripayController->tripay->tripay_merchant . $referenceId .  $totalPrice, $this->tripayController->tripay->tripay_private)
        ];

        try {
            $transaction = $this->tripayController->requestTransactions($tripayData);
            $responseData = json_decode($transaction->getContent(), true);
            if (!isset($responseData['success']) || $responseData['success'] !== true) {
                Log::error('Failed to create payment transaction', [
                    'event_id' => $event->id,
                    'user_id' => auth()->id(),
                    'response' => $responseData
                ]);
                return back()->with('error', 'Gagal membuat transaksi pembayaran: ' . ($responseData['message'] ?? 'Silakan coba lagi.'));
            }

            $tripayResponse = $responseData['data'];
            DB::beginTransaction();

            try {
                $this->seatReservationService->confirmReservations($event->id, Session::getId());
                $event->decrement('available_seats', $ticketCount);
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'event_id' => $event->id,
                    'description' => $description,
                    'reference' => $referenceId,
                    'tripay_reference' => $tripayResponse['reference'],
                    'merchant_ref' => $tripayResponse['merchant_ref'],
                    'total_amount' => $tripayResponse['amount'],
                    'fee' => $fee,
                    'payment_method' => $tripayResponse['payment_name'],
                    'checkout_url' => $tripayResponse['checkout_url'] ?? null,
                    'pay_code' => $tripayResponse['pay_code'] ?? null,
                    'qr_image' => $tripayResponse['qr_url'] ?? null,
                    'payment_status' => 'pending',
                    'ticket_count' => $ticketCount,
                    'expire_time' => date('Y-m-d H:i:s', $tripayResponse['expired_time']),
                    'order_items' => json_encode($itemDetails),
                ]);

                $venueSeatIds = array_column($selectedSeats, 'id');
                $eventSeats = EventSeat::where('event_id', $event->id)
                    ->whereIn('venue_seat_id', $venueSeatIds)
                    ->with('venueSeat')
                    ->get();

                foreach ($eventSeats as $eventSeat) {
                    $seat = $eventSeat->venueSeat;
                    $selectedSeat = collect($selectedSeats)->firstWhere('id', $seat->id);
                    $seatLabel = $selectedSeat ? $selectedSeat['rowName'] . '-' . $selectedSeat['seatNumber'] : $seat->row_name . '-' . $seat->seat_number;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'venue_seat_id' => $seat->id,
                        'seat_label' => $seatLabel,
                        'price' => $event->ticket_price,
                    ]);
                }

                DB::commit();
                return redirect()->route('payment.invoice', [
                    'reference' => $referenceId
                ])->with('success', 'Order berhasil dibuat. Silahkan lakukan pembayaran');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to create order with DB transaction: ' . $e->getMessage(), [
                    'event_id' => $event->id,
                    'user_id' => auth()->id(),
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return back()->with('error', 'Gagal membuat order, silahkan hubungi admin.');
            }
        } catch (\Exception $e) {
            Log::error('Exception when requesting Tripay transaction: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat berkomunikasi dengan payment gateway. Silakan coba lagi.');
        }
    }
}