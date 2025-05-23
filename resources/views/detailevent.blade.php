@extends('layouts.app')
@section('title', $event->name)
@section('content')
    <div class="relative h-80 md:h-96 lg:h-[32rem] bg-gray-900">
        <img src="{{ $event->cover_image == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $event->name . '' : asset('storage/' . $event->cover_image) }}"
            alt="{{ $event->name }}" class="w-full h-full object-cover opacity-70">

        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

        <div class="container mx-auto px-4 absolute inset-x-0 bottom-0 pb-8 md:pb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-3xl">
                    @if ($event->status === 'soldout')
                        <span
                            class="inline-block bg-red-600 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4 tracking-wide">SOLD
                            OUT</span>
                    @endif
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">{{ $event->name }}</h1>

                    <div class="flex flex-wrap items-center text-white/90 text-sm md:text-base gap-y-3 gap-x-6">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-blue-400"></i>
                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="far fa-clock mr-2 text-blue-400"></i>
                            <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                            <span>{{ $event->venue->name ?? 'Venue TBA' }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-user-tie mr-2 text-blue-400"></i>
                            <span>{{ $event->organizer->name ?? 'Organizer TBA' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center md:items-end space-y-2">
                    <div class="text-white/80 text-sm mb-2">Price</div>
                    <div class="text-white font-bold text-2xl md:text-3xl">
                        Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                    </div>
                    <div class="text-white/80 text-xs">
                        @if ($event->status === 'ready')
                            Tickets available
                        @else
                            Sold out
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-2/3">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="text-gray-600">
                        <span
                            class="inline-flex items-center {{ $event->status === 'ready' ? 'text-green-600' : 'text-red-600' }}">
                            <i
                                class="fas {{ $event->status === 'ready' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $event->status === 'ready' ? 'Tickets Available' : 'Sold Out' }}
                        </span>
                    </div>
                </div>

                <div x-data="{ activeTab: 'description' }">
                    <div class="border-b border-gray-200 mb-8">
                        <ul class="flex flex-wrap -mb-px">
                            <li class="mr-2">
                                <button @click="activeTab = 'description'"
                                    :class="{ 'border-blue-600 text-blue-600': activeTab === 'description', 'border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700': activeTab !== 'description' }"
                                    class="inline-block py-4 px-1 border-b-2 font-medium text-sm">
                                    Description
                                </button>
                            </li>
                            <li class="mr-2">
                                <button @click="activeTab = 'venue'"
                                    :class="{ 'border-blue-600 text-blue-600': activeTab === 'venue', 'border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700': activeTab !== 'venue' }"
                                    class="inline-block py-4 px-1 border-b-2 font-medium text-sm">
                                    Venue
                                </button>
                            </li>
                            <li>
                                <button @click="activeTab = 'organizer'"
                                    :class="{ 'border-blue-600 text-blue-600': activeTab === 'organizer', 'border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700': activeTab !== 'organizer' }"
                                    class="inline-block py-4 px-1 border-b-2 font-medium text-sm">
                                    Organizer
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div x-show="activeTab === 'description'" class="space-y-6">
                        <div class="prose max-w-none">
                            <h2 class="text-2xl font-bold mb-4">About this event</h2>
                            <p>{{ $event->description }}</p>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Event Details</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-gray-200">
                                    <div>
                                        <span class="text-gray-500 text-sm">Date</span>
                                        <div class="font-medium">
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y') }}</div>
                                    </div>
                                    <div class="mt-2 md:mt-0">
                                        <span class="text-gray-500 text-sm">Time</span>
                                        <div class="font-medium">
                                            {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</div>
                                    </div>
                                </div>

                                <div class="pb-4 border-b border-gray-200">
                                    <span class="text-gray-500 text-sm">Location</span>
                                    <div class="font-medium">{{ $event->venue->name ?? 'Venue TBA' }}</div>
                                    <div class="text-gray-600 text-sm">
                                        {{ $event->venue->alamat ?? 'Address details will be announced soon' }}</div>
                                </div>

                                <div class="pb-4 border-b border-gray-200">
                                    <span class="text-gray-500 text-sm">Organizer</span>
                                    <div class="font-medium">{{ $event->organizer->name ?? 'Organizer TBA' }}</div>
                                    <div class="text-gray-600 text-sm">
                                        {{ $event->organizer->organizer_type ?? '' }}
                                        {{ $event->organizer->auditorium_type ? ' • ' . $event->organizer->auditorium_type : '' }}
                                    </div>
                                </div>

                                <div>
                                    <span class="text-gray-500 text-sm">Ticket Price</span>
                                    <div class="font-medium">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'venue'" class="space-y-6">
                        <h2 class="text-2xl font-bold mb-4">Venue Information</h2>

                        @if (isset($event->venue))
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2">{{ $event->venue->name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ $event->venue->alamat }}</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-medium mb-2">Venue Details</h4>
                                            <ul class="space-y-2">
                                                <li class="flex items-start">
                                                    <i class="fas fa-users mt-1 mr-3 text-gray-500"></i>
                                                    <span>Capacity: {{ number_format($event->venue->total_capacity) }}
                                                        people</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <i class="fas fa-info-circle mt-1 mr-3 text-gray-500"></i>
                                                    <span>Status: {{ ucfirst($event->venue->status) }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <h4 class="font-medium mb-2">Description</h4>
                                            <p class="text-gray-600">{{ $event->venue->description }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <h4 class="font-medium mb-2">Seating Information</h4>
                                        <p class="text-gray-600 mb-4">
                                            This venue has seating arrangements with rows and numbered seats.
                                            Available seats will be shown during checkout.
                                        </p>
                                        @if (isset($availableSeats) && $availableSeats > 0)
                                            <div
                                                class="bg-green-50 border border-green-200 rounded p-3 text-green-700 text-sm inline-block">
                                                <i class="fas fa-check-circle mr-1"></i> {{ $availableSeats }} seats
                                                available
                                            </div>
                                        @elseif($event->status === 'ready')
                                            <div
                                                class="bg-yellow-50 border border-yellow-200 rounded p-3 text-yellow-700 text-sm inline-block">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Limited seating available
                                            </div>
                                        @else
                                            <div
                                                class="bg-red-50 border border-red-200 rounded p-3 text-red-700 text-sm inline-block">
                                                <i class="fas fa-times-circle mr-1"></i> No seats available
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-lg p-8 text-center">
                                <div class="text-gray-400 text-5xl mb-4">
                                    <i class="fas fa-map-marker-slash"></i>
                                </div>
                                <h3 class="text-xl font-medium text-gray-700 mb-2">Venue Information Unavailable</h3>
                                <p class="text-gray-500">Details about this venue will be provided soon.</p>
                            </div>
                        @endif
                    </div>

                    <div x-show="activeTab === 'organizer'" class="space-y-6">
                        <h2 class="text-2xl font-bold mb-4">Organizer Information</h2>

                        @if (isset($event->organizer))
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        @if ($event->organizer->logo)
                                            <img src="{{ $event->organizer->logo == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $event->name . '' : asset('storage/' . $event->organizer->logo) }}"
                                                alt="{{ $event->organizer->name }}"
                                                class="w-16 h-16 rounded-full mr-4 object-cover">
                                        @else
                                            <div
                                                class="w-16 h-16 rounded-full mr-4 bg-blue-100 flex items-center justify-center text-blue-500">
                                                <i class="fas fa-building text-2xl"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-xl font-semibold">{{ $event->organizer->name }}</h3>
                                            <p class="text-gray-600">{{ $event->organizer->organizer_type }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-medium mb-2">Organizer Details</h4>
                                            <ul class="space-y-2">
                                                <li class="flex items-start">
                                                    <i class="fas fa-tag mt-1 mr-3 text-gray-500"></i>
                                                    <span>Type: {{ $event->organizer->organizer_type }}</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <i class="fas fa-music mt-1 mr-3 text-gray-500"></i>
                                                    <span>Auditorium: {{ $event->organizer->auditorium_type }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <h4 class="font-medium mb-2">Other Events by This Organizer</h4>
                                            @if (isset($organizerEvents) && count($organizerEvents) > 0)
                                                <ul class="space-y-2">
                                                    @foreach ($organizerEvents as $orgEvent)
                                                        <li>
                                                            <a href="{{ route('detail-event', $orgEvent->slug) }}"
                                                                class="text-blue-600 hover:underline">
                                                                {{ $orgEvent->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-gray-600">No other events currently scheduled</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-lg p-8 text-center">
                                <div class="text-gray-400 text-5xl mb-4">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h3 class="text-xl font-medium text-gray-700 mb-2">Organizer Information Unavailable</h3>
                                <p class="text-gray-500">Details about this organizer will be provided soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden sticky top-24">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Get Tickets</h3>
                        @if (session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 relative"
                                role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <i class="fas fa-exclamation-circle mr-3"></i>
                                    </div>
                                    <div>
                                        <p>{{ session('error') }}</p>
                                    </div>
                                </div>
                                <button onclick="this.parentElement.style.display='none'"
                                    class="absolute top-0 right-0 mt-4 mr-4 text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        @if ($event->status === 'ready')
                            <div x-data="{
                                ticketCount: 1,
                                ticketPrice: {{ $event->ticket_price }},
                                hasDiscount: false,
                                discountAmount: 50000,
                            
                                get total() {
                                    if (this.ticketCount > 5) {
                                        this.hasDiscount = true;
                                        return this.ticketCount * (this.ticketPrice - this.discountAmount);
                                    } else {
                                        this.hasDiscount = false;
                                        return this.ticketCount * this.ticketPrice;
                                    }
                                },
                            
                                get originalTotal() {
                                    return this.ticketCount * this.ticketPrice;
                                },
                            
                                get savings() {
                                    return this.originalTotal - this.total;
                                },
                            
                                get discountPerTicket() {
                                    return this.hasDiscount ? this.discountAmount : 0;
                                },
                            
                                increaseTickets() {
                                    this.ticketCount++;
                                },
                            
                                decreaseTickets() {
                                    if (this.ticketCount > 1) {
                                        this.ticketCount--;
                                    }
                                },
                            
                                formatCurrency(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }" class="space-y-4 mb-6">
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-4 bg-blue-50 border-blue-200">
                                        <div>
                                            <h4 class="font-medium text-blue-700">Standard Ticket</h4>
                                            <p class="text-sm text-blue-600">General admission</p>
                                        </div>
                                        <div class="font-bold text-blue-700">
                                            Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                                        <div class="flex items-center">
                                            <button @click="decreaseTickets()"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100"
                                                :disabled="ticketCount === 1"
                                                :class="{ 'opacity-50 cursor-not-allowed': ticketCount === 1 }">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <span class="w-8 text-center" x-text="ticketCount"></span>
                                            <button @click="increaseTickets()"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="hasDiscount" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-tags text-green-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700">
                                                <span class="font-medium">Discount applied!</span> You're eligible for a Rp
                                                50.000 discount per ticket when buying more than 5 tickets.
                                            </p>
                                            <p class="text-xs text-green-600 mt-1">
                                                You're saving <span class="font-bold"
                                                    x-text="formatCurrency(savings)"></span> in total
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="!hasDiscount"
                                    class="text-sm text-blue-600 bg-blue-50 p-3 rounded-md flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                                    <span>Buy more than 5 tickets and get a Rp 50.000 discount on each ticket!</span>
                                </div>

                                <div class="border-t border-gray-200 pt-4 mb-6">
                                    <h3 class="font-medium mb-3">Order Summary</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Harga per tiket</span>
                                            <span>Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Jumlah tiket</span>
                                            <span x-text="ticketCount"></span>
                                        </div>
                                        <div class="flex justify-between text-green-600" x-show="hasDiscount">
                                            <span>Discount (Rp 50.000 × <span x-text="ticketCount"></span>)</span>
                                            <span>-<span x-text="formatCurrency(savings)"></span></span>
                                        </div>
                                        <div
                                            class="flex justify-between font-bold text-base pt-2 border-t border-gray-200 mt-2">
                                            <span>Total</span>
                                            <span x-text="formatCurrency(total)"></span>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('checkout.seats', $event->slug) }}"
                                    class="w-full">
                                    @csrf
                                    <input type="hidden" name="ticket_count" x-model="ticketCount">
                                    <input type="hidden" name="total_price" x-model="total">
                                    <input type="hidden" name="has_discount" x-model="hasDiscount">
                                    <input type="hidden" name="discount_amount" value="50000">
                                    <input type="hidden" name="original_price" x-model="originalTotal">
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition flex items-center justify-center">
                                        Checkout <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </form>

                                <div class="text-xs text-gray-500 text-center mt-4">
                                    By completing this purchase you agree to our <a href="#"
                                        class="text-blue-600 hover:underline">Terms of Service</a>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                <div class="text-red-500 text-4xl mb-3">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <h4 class="text-lg font-medium text-red-700 mb-2">Sold Out</h4>
                                <p class="text-red-600 mb-4">This event is currently sold out.</p>
                                <div class="flex justify-center">
                                    <button
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                                        Join Waitlist
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                                Secure Checkout
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag mr-2 text-blue-600"></i>
                                Best Price Guarantee
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="bg-gray-50 py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Other Events You Might Like</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($similarEvents ?? [] as $similarEvent)
                    <div
                        class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative h-48">
                            <img src="https://placehold.co/400x200/777/fff?text={{ urlencode($similarEvent->name) }}"
                                alt="{{ $similarEvent->name }}" class="w-full h-full object-cover">
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md">
                                <i class="far fa-heart text-gray-500"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1">{{ $similarEvent->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $similarEvent->venue->name ?? 'Venue TBA' }}</p>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ \Carbon\Carbon::parse($similarEvent->event_date)->format('M d, Y') }} •
                                {{ \Carbon\Carbon::parse($similarEvent->event_time)->format('H:i') }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold">Rp
                                    {{ number_format($similarEvent->ticket_price, 0, ',', '.') }}</span>
                                <a href="{{ route('detail-event', $similarEvent->slug) }}"
                                    class="text-blue-600 hover:text-blue-800">View details</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (!isset($similarEvents) || count($similarEvents ?? []) === 0)
                    @for ($i = 1; $i <= 4; $i++)
                        <div
                            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative h-48">
                                <img src="https://placehold.co/400x200/777/fff?text=Event+{{ $i }}"
                                    alt="Event {{ $i }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1">Sample Event {{ $i }}</h3>
                                <p class="text-gray-600 text-sm mb-2">Venue Name</p>
                                <p class="text-gray-600 text-sm mb-3">May {{ 10 + $i }}, 2023 • 19:00</p>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">Rp
                                        {{ number_format(mt_rand(100000, 500000), 0, ',', '.') }}</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-800">View details</a>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div> --}}
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
