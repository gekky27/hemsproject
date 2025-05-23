<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSeat;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $tripayController;

    public function __construct(TripayController $tripayController)
    {
        $this->tripayController = $tripayController;
    }

    /**
     * Handle payment callback from Tripay
     */
    public function callback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $this->tripayController->tripay->tripay_private);

        if ($callbackSignature !== $signature) {
            Log::error('Invalid callback signature', [
                'received' => $callbackSignature,
                'calculated' => $signature
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature'
            ], 400);
        }

        $data = json_decode($json, true);
        $tripayReference = $data['reference'];
        $merchantRef = $data['merchant_ref'];
        $status = strtolower($data['status']);
        $statusMapping = [
            'unpaid' => 'pending',
            'paid' => 'paid',
            'expired' => 'expired',
            'failed' => 'failed',
            'canceled' => 'canceled'
        ];

        $paymentStatus = $statusMapping[$status] ?? 'pending';
        $order = Order::where('tripay_reference', $tripayReference)
            ->orWhere('merchant_ref', $merchantRef)
            ->first();

        if (!$order) {
            Log::error('Order not found for payment callback', [
                'tripay_reference' => $tripayReference,
                'merchant_ref' => $merchantRef
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->payment_status != $paymentStatus) {
            try {
                DB::beginTransaction();
                $order->payment_status = $paymentStatus;

                if ($paymentStatus == 'paid') {
                    $order->paid_at = now();

                    $orderItems = OrderItem::where('order_id', $order->id)->get();
                    foreach ($orderItems as $item) {
                        EventSeat::where('event_id', $item->event_id)
                            ->where('venue_seat_id', $item->venue_seat_id)
                            ->update(['status' => 'sold']);
                    }

                    $event = Event::find($order->event_id);
                    if ($event && $event->available_seats <= 0) {
                        $event->status = 'soldout';
                        $event->save();
                    }
                } elseif (in_array($paymentStatus, ['expired', 'failed', 'canceled'])) {
                    $orderItems = OrderItem::where('order_id', $order->id)->get();
                    foreach ($orderItems as $item) {
                        EventSeat::where('event_id', $item->event_id)
                            ->where('venue_seat_id', $item->venue_seat_id)
                            ->update(['status' => 'available']);
                    }

                    $event = Event::find($order->event_id);
                    if ($event) {
                        $event->increment('available_seats', $order->ticket_count);
                        if ($event->status == 'soldout') {
                            $event->status = 'ready';
                            $event->save();
                        }
                    }
                }

                $order->save();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating payment status: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'exception' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update payment status'
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'No changes in payment status'
        ]);
    }

    public function invoicePayment(Request $request, $reference)
    {
        $order = Order::where('reference', $reference)
            ->with(['event', 'event.venue', 'event.organizer', 'items'])
            ->firstOrFail();

        $transactionDetails = $this->tripayController->getTransactionDetails($order->tripay_reference);
        $isExpired = $order->isExpired();

        return view('payment.invoice', compact('order', 'transactionDetails', 'isExpired'));
    }
}
