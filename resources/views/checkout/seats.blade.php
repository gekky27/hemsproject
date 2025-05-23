@extends('layouts.app')
@section('title', 'Select Seats - ' . $event->name)
@push('styles')
    <style>
        .seat-grid {
            max-height: 500px;
            overflow-y: auto;
            padding: 1rem;
            background-color: #f7f7f7;
            border-radius: 0.5rem;
        }

        .row-container {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }

        .row-container:last-child {
            border-bottom: none;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 0.3s ease-in-out;
        }

        .timer-container {
            background-color: #FEF3C7;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .timer-text {
            font-family: monospace;
            font-size: 1.2rem;
            margin-left: 0.5rem;
        }

        .timer-warning {
            animation: pulse 1s infinite;
            color: #DC2626;
        }

        .temp-reserved {
            background-color: #F59E0B;
            border-color: #D97706;
            color: white;
            cursor: not-allowed;
            position: relative;
            overflow: hidden;
        }

        .temp-reserved::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(45deg,
                    rgba(255, 152, 0, 0.7),
                    rgba(255, 152, 0, 0.7) 10px,
                    rgba(255, 152, 0, 0.5) 10px,
                    rgba(255, 152, 0, 0.5) 20px);
            animation: moveStripes 2s linear infinite;
        }

        .user-reserved {
            background-color: #3B82F6;
            border-color: #2563EB;
            color: white;
        }

        @keyframes moveStripes {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <div class="flex items-center justify-center max-w-3xl mx-auto">
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                1</div>
                            <span class="text-sm mt-1 text-blue-600 font-medium">Details</span>
                        </div>
                        <div class="h-1 w-16 md:w-24 bg-blue-600"></div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                2</div>
                            <span class="text-sm mt-1 text-blue-600 font-medium">Seats</span>
                        </div>
                        <div class="h-1 w-16 md:w-24 bg-gray-300"></div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                                3</div>
                            <span class="text-sm mt-1 text-gray-600">Payment</span>
                        </div>
                        <div class="h-1 w-16 md:w-24 bg-gray-300"></div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            4</div>
                        <span class="text-sm mt-1 text-gray-600">Done</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3 bg-white p-6 rounded-lg shadow-sm">
                    <h1 class="text-2xl font-bold mb-6">Select Your Seats</h1>
                    @if ($venueSeats->contains('status', 'user_reserved'))
                        @php
                            $expiryTime = $venueSeats->where('status', 'user_reserved')->first()->reservation_expires;
                            $expiryDate = now()->addSeconds($expiryTime);
                        @endphp
                        <div class="timer-container" id="reservation-timer-container">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-2 text-amber-700">Seat reservation expires in: </span>
                            <span id="reservation-timer" class="timer-text text-amber-700"></span>
                            <input type="hidden" id="expiry-timestamp" value="{{ $expiryDate }}">
                            <input type="hidden" id="event-slug" value="{{ $event->slug }}">
                        </div>
                    @endif

                    <div class="mb-6">
                        <div class="mt-8 mb-6 text-center text-sm text-gray-500">
                            <p>Please select {{ $ticketCount }} seat(s)</p>
                        </div>

                        <div class="flex justify-center mb-6">
                            <div class="flex flex-wrap justify-center gap-4">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-md border border-gray-400 bg-white mr-2"></div>
                                    <span class="text-sm">Available</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-md bg-blue-500 mr-2"></div>
                                    <span class="text-sm">Selected</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-md temp-reserved mr-2"></div>
                                    <span class="text-sm">Reserved by Others</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-md bg-gray-400 mr-2"></div>
                                    <span class="text-sm">Sold</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center mb-2">
                            <div class="w-full max-w-md bg-gray-200 p-3 rounded-lg text-center font-bold text-gray-700">
                                STAGE</div>
                        </div>
                    </div>

                    <div x-data="seatSelector" class="flex flex-col items-center">
                        <div class="w-full mb-6">
                            @php
                                $rows = $venueSeats->groupBy('row_name')->sortBy(function ($row, $key) {
                                    if (preg_match('/^([A-Za-z]+)(\d+)$/', $key, $matches)) {
                                        return ord(strtoupper($matches[1])) * 1000 + intval($matches[2]);
                                    }
                                    return $key;
                                });
                            @endphp

                            <div class="seat-grid space-y-4">
                                @foreach ($rows as $rowName => $rowSeats)
                                    <div class="row-container">
                                        <div class="flex items-center">
                                            <div class="w-10 flex-shrink-0 text-center font-bold text-sm text-gray-700">
                                                {{ $rowName }}
                                            </div>
                                            <div class="flex flex-wrap gap-2 ml-2">
                                                @foreach ($rowSeats->sortBy('seat_number') as $seat)
                                                    <button type="button"
                                                        @click="toggleSeat({{ $seat->id }}, '{{ $seat->row_name }}', {{ $seat->seat_number }})"
                                                        data-seat-id="{{ $seat->id }}"
                                                        data-seat-row="{{ $seat->row_name }}"
                                                        data-seat-number="{{ $seat->seat_number }}"
                                                        data-seat-status="{{ $seat->status }}"
                                                        :class="{
                                                            'bg-white border-gray-400 hover:bg-blue-100': '{{ $seat->status }}'
                                                            === 'available' && !isSeatSelected({{ $seat->id }}),
                                                            'bg-blue-500 border-blue-600 text-white': isSeatSelected(
                                                                {{ $seat->id }}) && '{{ $seat->status }}'
                                                            !== 'user_reserved',
                                                            'user-reserved': '{{ $seat->status }}'
                                                            === 'user_reserved',
                                                            'temp-reserved': '{{ $seat->status }}'
                                                            === 'temp_reserved',
                                                            'bg-gray-400 border-gray-500 text-white cursor-not-allowed': '{{ $seat->status }}'
                                                            === 'unavailable' ||
                                                            '{{ $seat->status }}'
                                                            === 'booked' ||
                                                            '{{ $seat->status }}'
                                                            === 'sold'
                                                        }"
                                                        class="w-10 h-10 rounded-md border flex items-center justify-center text-xs font-medium transition-all duration-200"
                                                        :disabled="'{{ $seat->status }}'
                                                        === 'unavailable' ||
                                                            '{{ $seat->status }}'
                                                        === 'temp_reserved' ||
                                                            '{{ $seat->status }}'
                                                        === 'booked' ||
                                                            '{{ $seat->status }}'
                                                        === 'sold' ||
                                                            (!isSeatSelected({{ $seat->id }}) &&
                                                                isSelectionComplete() &&
                                                                '{{ $seat->status }}'
                                                                !== 'user_reserved')">
                                                        {{ $seat->seat_number }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-full bg-blue-50 p-4 rounded-lg mb-6">
                            <h3 class="font-medium text-blue-800 mb-2">Selected Seats</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-if="selectedSeats.length === 0">
                                    <div class="text-sm text-gray-500">No seats selected yet</div>
                                </template>
                                <template x-for="seat in selectedSeats" :key="seat.id">
                                    <div
                                        class="bg-blue-100 border border-blue-200 rounded px-3 py-1 text-sm flex items-center">
                                        <span x-text="`${seat.rowName}${seat.seatNumber}`" class="mr-2"></span>
                                        <button @click.prevent="removeSeat(seat.id)" x-show="!seat.isReserved"
                                            class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-between w-full gap-4">
                            <a href="{{ route('detail-event', $event->slug) }}"
                                class="px-6 py-2 bg-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-300 transition">
                                Back
                            </a>

                            <form action="{{ route('checkout.process-seats', $event->slug) }}" method="POST"
                                class="inline">
                                @csrf
                                <input type="hidden" id="selected_seats" name="selected_seats"
                                    x-bind:value="JSON.stringify(selectedSeats)">
                                <input type="hidden" name="ticket_count" value="{{ $ticketCount }}">
                                <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                                <button type="submit" class="px-6 py-2 rounded-lg font-medium transition"
                                    :class="{
                                        'bg-blue-600 hover:bg-blue-700 text-white': isSelectionComplete(),
                                        'bg-gray-300 text-gray-500 cursor-not-allowed': !isSelectionComplete()
                                    }"
                                    :disabled="!isSelectionComplete()">
                                    Continue to Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">
                        <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                        <div class="border-t border-gray-200 pt-4 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Event</span>
                                <span class="font-medium">{{ $event->name }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Date</span>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Time</span>
                                <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Venue</span>
                                <span>{{ $event->venue->name }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ticket Price</span>
                                <span>Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Number of Tickets</span>
                                <span>{{ $ticketCount }}</span>
                            </div>

                            @if ($ticketCount > 5)
                                <div class="flex justify-between text-sm bg-green-50 p-3 rounded border border-green-100">
                                    <span class="text-green-700">Discount</span>
                                    <span class="text-green-700 font-medium">
                                        -Rp {{ number_format(50000 * $ticketCount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Rp 50.000 × {{ $ticketCount }} tickets</span>
                                    <span>-Rp {{ number_format(50000 * $ticketCount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-200 pt-4 flex justify-between font-bold">
                                <span>Total</span>
                                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if ($ticketCount <= 5)
                            <div class="mt-4 bg-blue-50 border border-blue-100 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-blue-700 font-medium">Special Offer!</p>
                                        <p class="text-blue-600 text-sm mt-1">Buy more than 5 tickets and get Rp 50.000
                                            discount per ticket!</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-4 bg-green-50 border border-green-100 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-0.5 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <p class="text-green-700 font-medium">Discount Applied!</p>
                                        <p class="text-green-600 text-sm mt-1">You're saving Rp
                                            {{ number_format(50000 * $ticketCount, 0, ',', '.') }} with our special offer!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 text-sm bg-yellow-50 border border-yellow-100 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mt-0.5 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="text-yellow-700 font-medium">Time Limit</p>
                                    <p class="text-yellow-600 mt-1">Selected seats will be reserved for <span
                                            class="font-bold">10 minutes</span> to complete your payment.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md">
            <div class="flex">
                <div class="py-1"><svg class="h-6 w-6 text-red-500 mr-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                <div>
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('seatSelector', () => ({
                selectedSeats: [],
                availableSeats: [],
                maxSeats: {{ $ticketCount }},
                isSelecting: false,

                init() {
                    this.collectAvailableSeats();
                    this.initializeReservedSeats();
                    this.initializeTimer();
                },

                collectAvailableSeats() {
                    const availableButtons = document.querySelectorAll(
                        'button[data-seat-status="available"]');
                    this.availableSeats = Array.from(availableButtons).map(btn => ({
                        id: parseInt(btn.dataset.seatId),
                        rowName: btn.dataset.seatRow,
                        seatNumber: parseInt(btn.dataset.seatNumber)
                    }));
                },

                initializeReservedSeats() {
                    const userReservedButtons = document.querySelectorAll(
                        'button[data-seat-status="user_reserved"]');

                    Array.from(userReservedButtons).forEach(btn => {
                        this.selectedSeats.push({
                            id: parseInt(btn.dataset.seatId),
                            rowName: btn.dataset.seatRow,
                            seatNumber: parseInt(btn.dataset.seatNumber),
                            isReserved: true
                        });
                    });
                },

                initializeTimer() {
                    const timerElement = document.getElementById('reservation-timer');
                    if (timerElement) {
                        const expiryTimeEl = document.getElementById('expiry-timestamp');

                        if (expiryTimeEl) {
                            const expiryTimeStr = expiryTimeEl.value;
                            const expiryTime = new Date(expiryTimeStr).getTime();

                            this.startTimer(expiryTime, timerElement);
                        }
                    }
                },

                startTimer(expiryTime, displayElement) {
                    const timerInterval = setInterval(() => {
                        const now = Date.now();
                        const timeLeft = Math.floor((expiryTime - now) / 1000);

                        if (timeLeft <= 0) {
                            displayElement.innerHTML = 'Time expired!';
                            displayElement.parentElement.classList.add('bg-red-100');
                            clearInterval(timerInterval);

                            const eventSlug = document.getElementById('event-slug').value;
                            window.location.href = `/event/${eventSlug}?expired=true`;
                            return;
                        }

                        const minutes = Math.floor(timeLeft / 60);
                        const seconds = timeLeft % 60;

                        displayElement.innerHTML =
                            `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                        if (timeLeft < 120) {
                            displayElement.classList.add('text-red-600');
                            displayElement.classList.add('timer-warning');
                        }
                    }, 1000);
                },

                toggleSeat(id, rowName, seatNumber) {
                    if (this.isSelecting) return;
                    this.isSelecting = true;

                    const seatButton = document.querySelector(`button[data-seat-id="${id}"]`);
                    const isUserReserved = seatButton && seatButton.dataset.seatStatus ===
                        'user_reserved';
                    const index = this.selectedSeats.findIndex(seat => seat.id === id);

                    if (index === -1) {
                        if (this.selectedSeats.length < this.maxSeats) {
                            this.selectedSeats.push({
                                id,
                                rowName,
                                seatNumber,
                                isReserved: isUserReserved
                            });

                            this.animateSeatSelection(id);
                        }
                    } else {
                        if (!this.selectedSeats[index].isReserved) {
                            this.selectedSeats.splice(index, 1);
                        }
                    }

                    setTimeout(() => {
                        this.isSelecting = false;
                    }, 300);
                },

                removeSeat(id) {
                    const index = this.selectedSeats.findIndex(seat => seat.id === id);
                    if (index !== -1 && !this.selectedSeats[index].isReserved) {
                        this.selectedSeats.splice(index, 1);
                    }
                },

                isSeatSelected(id) {
                    return this.selectedSeats.some(seat => seat.id === id);
                },

                isSelectionComplete() {
                    return this.selectedSeats.length === this.maxSeats;
                },

                animateSeatSelection(id) {
                    const seatButton = document.querySelector(`button[data-seat-id="${id}"]`);
                    if (seatButton) {
                        seatButton.classList.add('pulse-animation');
                        setTimeout(() => {
                            seatButton.classList.remove('pulse-animation');
                        }, 300);
                    }
                }
            }));
        });
    </script>
@endpush
