<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalEvents = Event::count();
        $totalVenues = Venue::count();
        $totalOrganizer = User::where('role', 'organizer')->count();

        $currentMonth = date('n');
        $currentYear = date('Y');
        $currentMonthName = date('F');

        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $recentEvents = Event::with('venue')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $venueUsageData = $this->getVenueUsageData($currentYear, $currentMonth);
        $ticketSalesData = $this->getTicketSalesData($currentYear, $currentMonth);

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalVenues',
            'totalOrganizer',
            'currentMonth',
            'currentYear',
            'currentMonthName',
            'months',
            'recentEvents',
            'venueUsageData',
            'ticketSalesData'
        ));
    }

    private function getVenueUsageData($year, $month)
    {
        $venueUsage = Event::select('venues.name', DB::raw('COUNT(*) as event_count'))
            ->join('venues', 'events.venues_id', '=', 'venues.id')
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->groupBy('venues.name')
            ->orderBy('event_count', 'desc')
            ->get();

        $result = [];
        foreach ($venueUsage as $venue) {
            $result[$venue->name] = $venue->event_count;
        }

        return $result;
    }

    private function getTicketSalesData($year, $month)
    {
        $ticketSales = Event::select('events.name', DB::raw('SUM(orders.ticket_count) as tickets_sold'))
            ->join('orders', 'events.id', '=', 'orders.event_id')
            ->whereYear('events.event_date', $year)
            ->whereMonth('events.event_date', $month)
            ->where('orders.payment_status', 'paid')
            ->groupBy('events.name')
            ->orderBy('tickets_sold', 'desc')
            ->get();

        $result = [];
        foreach ($ticketSales as $event) {
            $result[$event->name] = $event->tickets_sold;
        }

        return $result;
    }

    public function getMonthlyStats($year, $month)
    {
        $venueUsageData = $this->getVenueUsageData($year, $month);
        $ticketSalesData = $this->getTicketSalesData($year, $month);

        $monthName = date('F', mktime(0, 0, 0, $month, 1, $year));

        return response()->json([
            'venueLabels' => array_keys($venueUsageData),
            'venueData' => array_values($venueUsageData),
            'ticketLabels' => array_keys($ticketSalesData),
            'ticketData' => array_values($ticketSalesData),
            'monthName' => $monthName
        ]);
    }

    public function viewAppSettings()
    {
        $settings = AppSettings::first();

        return view('admin.app-settings', compact('settings'));
    }

    public function updateAppSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'slogan' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $settings = AppSettings::first();

            if ($request->hasFile('logo')) {
                if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                    Storage::disk('public')->delete($settings->logo);
                }

                $logoPath = $request->file('logo')->storeAs('logos', 'logo.png', 'public');
                $settings->logo = $logoPath;
            }

            if ($request->hasFile('favicon')) {
                if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
                    Storage::disk('public')->delete($settings->favicon);
                }

                $faviconPath = $request->file('favicon')->storeAs('favicons', 'favicon.png', 'public');
                $settings->favicon = $faviconPath;
            }

            $settings->update([
                'name' => $request->name,
                'url' => $request->url,
                'slogan' => $request->slogan,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'instagram' => $request->instagram,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan aplikasi.');
        }
    }

    public function viewVenue()
    {
        $venues = Venue::latest()->paginate(10);

        return view('admin.venue.index', compact('venues'));
    }

    public function viewVenueAdd()
    {
        return view('admin.venue.add');
    }

    private function createVenueSeats($venueId, $totalCapacity): void
    {
        $rowLetters = range('A', 'Z');
        $numRows = min(ceil(sqrt($totalCapacity)), 26);
        $seatsPerRow = ceil($totalCapacity / $numRows);

        $venueSeats = [];
        $seatCount = 0;

        for ($i = 0; $i < $numRows; $i++) {
            $rowName = $rowLetters[$i];

            for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
                $seatCount++;

                if ($seatCount > $totalCapacity) {
                    break;
                }
                $xCoordinate = $seatNum * 30;
                $yCoordinate = $i * 40;

                $venueSeats[] = [
                    'venues_id' => $venueId,
                    'row_name' => $rowName,
                    'seat_number' => $seatNum,
                    'x_coordinate' => $xCoordinate,
                    'y_coordinate' => $yCoordinate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($seatCount >= $totalCapacity) {
                break;
            }
        }

        DB::table('venue_seats')->insert($venueSeats);
    }

    public function addVenue(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues',
            'alamat' => 'required|string|max:255',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();
            $venue = Venue::create([
                'name' => $request->name,
                'alamat' => $request->alamat,
                'total_capacity' => $request->total_capacity,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            $this->createVenueSeats($venue->id, $request->total_capacity);
            DB::commit();

            return redirect()->route('venues.index')->with('success', 'Venue berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan venue.');
        }
    }

    public function viewVenueUpdate($id)
    {
        $venue = Venue::findOrFail($id);

        return view('admin.venue.update', compact('venue'));
    }

    public function updateVenue(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name,' . $request->id,
            'address' => 'required|string|max:255',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $venue = Venue::findOrFail($request->id);
            $capacityChanged = $venue->total_capacity != $request->total_capacity;

            $venue->update([
                'name' => $request->name,
                'alamat' => $request->address,
                'total_capacity' => $request->total_capacity,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            if ($capacityChanged) {
                $seatsInUse = DB::table('event_seats')
                    ->join('venue_seats', 'event_seats.venue_seat_id', '=', 'venue_seats.id')
                    ->where('venue_seats.venues_id', $request->id)
                    ->where('event_seats.status', '!=', 'available')
                    ->exists();

                if ($seatsInUse) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Total kapasitas tidak dapat diubah karena beberapa kursi sedang digunakan oleh event.');
                }

                DB::table('venue_seats')->where('venues_id', $request->id)->delete();
                $this->createVenueSeats($request->id, $request->total_capacity);
            }

            DB::commit();
            return redirect()->route('venues.index')->with('success', 'Venue berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui venue.');
        }
    }

    public function deleteVenue($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            $venue->delete();

            return redirect()->route('venues.index')->with('success', 'Venue berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus venue.');
        }
    }

    public function viewOrganizer()
    {
        $organizers = User::where('role', 'organizer')
            ->with('organizer')
            ->latest()
            ->paginate(10);

        return view('admin.organizer.index', compact('organizers'));
    }

    public function viewOrganizerAdd()
    {
        return view('admin.organizer.add');
    }

    public function addOrganizer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'no_whatsapp' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'organizer_name' => 'required|string|max:255|unique:organizers,name',
            'organizer_type' => 'required|string|max:255',
            'auditorium_type' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_whatsapp' => $request->no_whatsapp,
                'password' => bcrypt($request->password),
                'role' => 'organizer',
            ]);

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->storeAs('logos', 'logo_' . $user->id . '.png', 'public');
            }

            $user->organizer()->create([
                'user_id' => $user->id,
                'name' => $request->organizer_name,
                'slug' => Str::slug($request->organizer_name),
                'organizer_type' => $request->organizer_type,
                'auditorium_type' => $request->auditorium_type,
                'logo' => $logoPath,
            ]);

            DB::commit();
            return redirect()->route('organizers.index')->with('success', 'Organizer berhasil ditambahkan.');
        } catch (\Throwable $th) {
            if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan organizer.');
        }
    }

    public function viewOrganizerUpdate($id)
    {
        $user = User::where('role', 'organizer')->findOrFail($id);

        return view('admin.organizer.update', compact('user'));
    }

    public function updateOrganizer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $request->id,
            'email' => 'required|email|max:255|unique:users,email,' . $request->id,
            'no_whatsapp' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'organizer_type' => 'required|string|max:255',
            'auditorium_type' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $request->validate([
                'organizer_name' => 'required|string|max:255|unique:organizers,name,' . $user->organizer->id,
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'no_whatsapp' => $request->no_whatsapp,
            ]);

            if ($request->password) {
                $user->update(['password' => bcrypt($request->password)]);
            }

            if ($request->hasFile('logo')) {
                if ($user->organizer->logo && Storage::disk('public')->exists($user->organizer->logo)) {
                    Storage::disk('public')->delete($user->organizer->logo);
                }

                $logoPath = $request->file('logo')->storeAs('logos', 'logo_' . $user->id . '.png', 'public');
                $user->organizer()->update(['logo' => $logoPath]);
            }

            $user->organizer()->update([
                'name' => $request->organizer_name,
                'slug' => Str::slug($request->organizer_name),
                'organizer_type' => $request->organizer_type,
                'auditorium_type' => $request->auditorium_type,
            ]);

            DB::commit();
            return redirect()->route('organizers.index')->with('success', 'Organizer berhasil diperbarui.');
        } catch (\Throwable $th) {
            if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui organizer.');
        }
    }

    public function deleteOrganizer($id)
    {
        try {
            $user = User::where('role', 'organizer')->findOrFail($id);
            if ($user->organizer->logo && Storage::disk('public')->exists($user->organizer->logo)) {
                Storage::disk('public')->delete($user->organizer->logo);
            }
            $user->delete();

            return redirect()->route('organizers.index')->with('success', 'Organizer berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus organizer.');
        }
    }

    public function organizerDashboard()
    {
        $organizerId = auth()->user()->organizer->id;
        $totalEvents = Event::where('organizers_id', $organizerId)->count();
        $totalRevenues = Order::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizers_id', $organizerId);
        })->where('payment_status', 'paid')->sum('total_amount');
        $totalTicketsSold = Order::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizers_id', $organizerId);
        })->where('payment_status', 'paid')->sum('ticket_count');
        $recentEvents = Event::with(['venue', 'eventSeats'])
            ->where('organizers_id', $organizerId)
            ->orderBy('event_date', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentEvents as $event) {
            $event->tickets_sold = Order::where('event_id', $event->id)
                ->where('payment_status', 'paid')
                ->sum('ticket_count');

            $event->total_seats = $event->eventSeats->count();
        }

        $recentSales = Order::with(['user', 'event'])
            ->whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizers_id', $organizerId);
            })
            ->where('payment_status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->limit(5)
            ->get();

        $thirtyDaysAgo = now()->subDays(30);
        $events = Event::where('organizers_id', $organizerId)->get();
        $eventSalesData = [];

        foreach ($events as $event) {
            $ticketsSold = Order::where('event_id', $event->id)
                ->where('payment_status', 'paid')
                ->where('paid_at', '>=', $thirtyDaysAgo)
                ->sum('ticket_count');

            if ($ticketsSold > 0) {
                $eventSalesData[$event->name] = $ticketsSold;
            }
        }

        arsort($eventSalesData);

        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalRevenues',
            'totalTicketsSold',
            'recentEvents',
            'recentSales',
            'eventSalesData'
        ));
    }

    public function getSalesData($period)
    {
        $organizerId = auth()->user()->organizer->id;

        $startDate = null;
        switch ($period) {
            case '30':
                $startDate = now()->subDays(30);
                break;
            case '90':
                $startDate = now()->subDays(90);
                break;
            case '180':
                $startDate = now()->subMonths(6);
                break;
            case '365':
                $startDate = now()->subYear();
                break;
            default:
                break;
        }

        $events = Event::where('organizers_id', $organizerId)->get();
        $eventSalesData = [];

        foreach ($events as $event) {
            $query = Order::where('event_id', $event->id)
                ->where('payment_status', 'paid');

            if ($startDate) {
                $query->where('paid_at', '>=', $startDate);
            }

            $ticketsSold = $query->sum('ticket_count');

            if ($ticketsSold > 0) {
                $eventSalesData[$event->name] = $ticketsSold;
            }
        }

        arsort($eventSalesData);
        $eventSalesData = array_slice($eventSalesData, 0, 10);

        return response()->json([
            'labels' => array_keys($eventSalesData),
            'values' => array_values($eventSalesData)
        ]);
    }

    public function viewOrganizerEvent(Request $request)
    {
        $query = Event::with('venue');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('status') && in_array($request->status, ['ready', 'soldout'])) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('event_date', 'asc')->orderBy('event_time', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('event_date', 'desc')->orderBy('event_time', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('ticket_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('ticket_price', 'desc');
                break;
            default:
                $query->orderBy('event_date', 'desc')->orderBy('event_time', 'desc');
        }

        $events = $query->where('organizers_id', auth()->user()->organizer->id)
            ->paginate(10)
            ->withQueryString();

        return view('organizer.event.index', compact('events'));
    }

    public function viewOrganizerEventAdd()
    {
        $organizers = Organizer::all();
        $venues = Venue::where('status', 'active')->get();

        return view('organizer.event.add', compact('organizers', 'venues'));
    }

    private function createEventSeats($eventId, $venueId)
    {
        $venueSeats = DB::table('venue_seats')
            ->where('venues_id', $venueId)
            ->get();

        $eventSeats = [];
        foreach ($venueSeats as $venueSeat) {
            $eventSeats[] = [
                'event_id' => $eventId,
                'venue_seat_id' => $venueSeat->id,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('event_seats')->insert($eventSeats);
    }

    public function addOrganizerEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:events',
            'venues_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'ticket_price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,soldout',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->storeAs('events', 'cover_' . time() . '.png', 'public');
            }

            $event = Event::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'organizers_id' => auth()->user()->organizer->id,
                'venues_id' => $request->venues_id,
                'description' => $request->description,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'ticket_price' => $request->ticket_price,
                'status' => $request->status,
                'available_seats' => 0,
                'cover_image' => $coverImagePath,
            ]);

            $this->createEventSeats($event->id, $request->venues_id);
            $availableSeats = DB::table('event_seats')
                ->where('event_id', $event->id)
                ->where('status', 'available')
                ->count();
            $event->update(['available_seats' => $availableSeats]);

            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan event.');
        }
    }

    public function viewOrganizerEventUpdate($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::where('status', 'active')->get();

        return view('organizer.event.update', compact('event', 'venues'));
    }

    public function updateOrganizerEvent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:events,name,' . $id,
            'venues_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'ticket_price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,soldout',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($request->id);
            if ($request->hasFile('cover_image')) {
                if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                    Storage::disk('public')->delete($event->cover_image);
                }

                $coverImagePath = $request->file('cover_image')->storeAs('events', 'cover_' . time() . '.png', 'public');
                $event->update(['cover_image' => $coverImagePath]);
            }

            $venueChanged = $event->venues_id != $request->venues_id;
            $event->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'venues_id' => $request->venues_id,
                'description' => $request->description,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'ticket_price' => $request->ticket_price,
                'status' => $request->status,
            ]);

            if ($venueChanged) {
                DB::table('event_seats')->where('event_id', $event->id)->delete();
                $this->createEventSeats($event->id, $request->venues_id);
                $availableSeats = DB::table('event_seats')
                    ->where('event_id', $event->id)
                    ->where('status', 'available')
                    ->count();

                $event->update(['available_seats' => $availableSeats]);
            }

            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui event.');
        }
    }

    public function deleteOrganizerEvent($id)
    {
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);
            if ($event->organizers_id != auth()->user()->organizer->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus event ini.');
            }

            DB::table('event_seats')->where('event_id', $id)->delete();
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $event->delete();

            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus event.');
        }
    }
}
