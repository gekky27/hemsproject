@extends('layouts.app')
@section('title', 'Payment - ' . $event->name)
@push('styles')
    <style>
        .payment-option {
            transition: all 0.2s ease;
        }

        .payment-option:hover {
            border-color: #3b82f6;
        }

        .payment-option.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .payment-group {
            scroll-margin-top: 20px;
        }

        .countdown {
            font-variant-numeric: tabular-nums;
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
                        <div class="h-1 w-16 md:w-24 bg-blue-600"></div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                3</div>
                            <span class="text-sm mt-1 text-blue-600 font-medium">Payment</span>
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
                <div class="lg:w-2/3">
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h1 class="text-2xl font-bold mb-6">Select Payment Method</h1>

                        <div x-data="{ activeTab: 'virtual-account' }" class="mb-6">
                            <div class="flex overflow-x-auto border-b border-gray-200 mb-6 pb-1 gap-4">
                                @foreach ($filteredChannels as $groupName => $channels)
                                    <button
                                        @click="activeTab = '{{ strtolower(str_replace(' ', '-', $groupName)) }}'; $el.scrollIntoView({ behavior: 'smooth' })"
                                        :class="{ 'border-b-2 border-blue-500 text-blue-600 font-medium': activeTab === '{{ strtolower(str_replace(' ', '-', $groupName)) }}', 'text-gray-500 hover:text-gray-700': activeTab !== '{{ strtolower(str_replace(' ', '-', $groupName)) }}' }"
                                        class="py-2 px-1 focus:outline-none transition-colors whitespace-nowrap">
                                        {{ $groupName }}
                                    </button>
                                @endforeach
                            </div>

                            <form action="{{ route('checkout.process-payment', $event->slug) }}" method="post"
                                id="payment-form">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method" value="">
                                @foreach ($filteredChannels as $groupName => $channels)
                                    <div x-show="activeTab === '{{ strtolower(str_replace(' ', '-', $groupName)) }}'"
                                        class="payment-group" id="{{ strtolower(str_replace(' ', '-', $groupName)) }}">

                                        <div class="space-y-3">
                                            @foreach ($channels as $channel)
                                                <div class="payment-option border border-gray-200 rounded-lg p-4 cursor-pointer"
                                                    onclick="selectPaymentMethod('{{ $channel['code'] }}', this)">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 mr-4">
                                                            @if (isset($channel['icon_url']) && $channel['icon_url'])
                                                                <img src="{{ $channel['icon_url'] }}"
                                                                    alt="{{ $channel['name'] }}" class="h-10">
                                                            @else
                                                                <div
                                                                    class="bg-gray-100 rounded p-2 flex items-center justify-center w-12 h-10">
                                                                    <span class="text-xs text-gray-500">No Icon</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow">
                                                            <h3 class="font-medium">{{ $channel['name'] }}</h3>
                                                            @php
                                                                $tripayController = app(
                                                                    'App\Http\Controllers\TripayController',
                                                                );
                                                                $fee = $tripayController->calculateFee(
                                                                    $channel['code'],
                                                                    $totalPrice,
                                                                );
                                                            @endphp
                                                            @if ($fee > 0)
                                                                <p class="text-sm text-gray-500">
                                                                    Fee: Rp {{ number_format($fee, 0, ',', '.') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="flex-shrink-0 ml-4">
                                                            <div
                                                                class="w-6 h-6 border border-gray-300 rounded-full flex items-center justify-center payment-radio">
                                                                <div
                                                                    class="w-3 h-3 bg-blue-500 rounded-full hidden payment-radio-dot">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mt-8 flex justify-between">
                                    <a href="{{ route('checkout.seats', $event->slug) }}"
                                        class="px-6 py-2 bg-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-300 transition">
                                        Back to Seats
                                    </a>

                                    <button type="submit" id="pay-button" disabled
                                        class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium transition cursor-not-allowed">
                                        Continue to Pay
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold mb-4">Selected Seats</h2>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedSeats as $seat)
                                    <div class="bg-blue-100 border border-blue-200 rounded px-3 py-1 text-sm">
                                        {{ $seat['rowName'] }}{{ $seat['seatNumber'] }}
                                    </div>
                                @endforeach
                            </div>
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

                            @php
                                $originalPrice = $ticketCount * $event->ticket_price;
                                $hasDiscount = $ticketCount > 5;
                                $discountAmount = $hasDiscount ? 50000 * $ticketCount : 0;
                            @endphp

                            @if ($hasDiscount)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Original Subtotal</span>
                                    <span class="line-through text-gray-500">Rp
                                        {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-sm text-green-600 bg-green-50 p-2 rounded">
                                    <span>Discount (Rp 50.000 × {{ $ticketCount }})</span>
                                    <span>-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-sm" id="fee-row" style="display: none;">
                                <span class="text-gray-600">Payment Fee</span>
                                <span id="payment-fee">Rp 0</span>
                            </div>

                            <div class="border-t border-gray-200 pt-4 flex justify-between font-bold">
                                <span>Total</span>
                                <span id="total-with-fee">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if ($hasDiscount)
                            <div class="mt-4 bg-green-50 border border-green-100 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-0.5 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <p class="text-green-700 font-medium">Special Offer Applied!</p>
                                        <p class="text-green-600 text-sm mt-1">
                                            You've saved Rp {{ number_format($discountAmount, 0, ',', '.') }} with our Rp
                                            50.000 discount per ticket for purchases of more than 5 tickets.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6" x-data="countdown()" x-init="start()">
                            <div class="bg-red-50 border border-red-100 p-4 rounded-lg text-sm">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mt-0.5 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-red-700 font-medium">Time Remaining</p>
                                        <p class="text-red-600 mt-1 countdown" x-text="display"></p>
                                        <p class="text-red-600 mt-2">Your seat reservation will expire after this time.</p>
                                    </div>
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
        const baseAmount = {{ $totalPrice }};
        const paymentFees = {};

        @foreach ($filteredChannels as $groupName => $channels)
            @foreach ($channels as $channel)
                @php
                    $tripayController = app('App\Http\Controllers\TripayController');
                    $fee = $tripayController->calculateFee($channel['code'], $totalPrice);
                @endphp
                paymentFees['{{ $channel['code'] }}'] = {{ $fee }};
            @endforeach
        @endforeach

        function selectPaymentMethod(code, element) {
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('selected');
                el.querySelector('.payment-radio-dot').classList.add('hidden');
            });

            element.classList.add('selected');
            element.querySelector('.payment-radio-dot').classList.remove('hidden');
            document.getElementById('payment_method').value = code;

            const fee = paymentFees[code] || 0;
            if (fee > 0) {
                document.getElementById('fee-row').style.display = 'flex';
                document.getElementById('payment-fee').textContent = 'Rp ' + fee.toLocaleString('id-ID');
            } else {
                document.getElementById('fee-row').style.display = 'none';
            }

            const total = baseAmount + fee;
            document.getElementById('total-with-fee').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const payButton = document.getElementById('pay-button');
            payButton.disabled = false;
            payButton.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            payButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', () => ({
                display: '10:00',
                minutes: 10,
                seconds: 0,
                interval: null,

                start() {
                    this.interval = setInterval(() => {
                        this.tick();
                    }, 1000);
                },

                tick() {
                    if (this.seconds === 0) {
                        if (this.minutes === 0) {
                            clearInterval(this.interval);
                            alert(
                                'Your seat reservation has expired. You will be redirected to the event page.'
                            );
                            window.location.href = "{{ route('detail-event', $event->slug) }}";
                            return;
                        }
                        this.minutes -= 1;
                        this.seconds = 59;
                    } else {
                        this.seconds -= 1;
                    }

                    this.display =
                        `${this.minutes.toString().padStart(2, '0')}:${this.seconds.toString().padStart(2, '0')}`;
                }
            }));
        });
    </script>
@endpush
