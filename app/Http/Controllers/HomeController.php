<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSeat;
use App\Models\Order;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Event::where('event_date', '>=', now())
            ->where('status', 'ready')
            ->inRandomOrder()
            ->take(3)
            ->with('venue')
            ->get();

        $eventQuery = Event::orderBy('event_date', 'asc');

        if ($featuredEvents->count() > 0) {
            $eventQuery->whereNotIn('id', $featuredEvents->pluck('id')->toArray());
        }

        $events = $eventQuery->get();

        return view('landingpages', compact('events', 'featuredEvents'));
    }

    public function detailEvent($slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['venue', 'organizer'])
            ->firstOrFail();
        $organizerEvents = Organizer::where('id', $event->organizers_id)
            ->with('events')
            ->get();

        return view('detailevent', compact('event', 'organizerEvents'));
    }

    public function userDashboard()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
            ->with(['event', 'event.venue'])
            ->orderBy('created_at', 'desc')
            ->get();
        $upcomingEvents = $orders->filter(function ($order) {
            return $order->payment_status == 'paid' &&
                $order->event->event_date >= now()->format('Y-m-d');
        })->count();
        $completedOrders = $orders->where('payment_status', 'paid')->count();

        return view('dashboard', compact('orders', 'upcomingEvents', 'completedOrders'));
    }
}
