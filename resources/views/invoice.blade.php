@extends('layouts.app')
@section('title', 'Invoice - ' . $order->event->name)
@push('styles')
    <style>
        .countdown {
            font-family: monospace;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ef4444;
            font-variant-numeric: tabular-nums;
        }

        .copy-btn {
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            color: #3b82f6;
        }

        .clipboard-copied {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 50;
        }

        .action-button {
            transition: all 0.2s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .card-body {
            padding: 1.5rem;
        }

        .ticket-card {
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-badge.paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-badge.failed {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .payment-code {
            font-family: monospace;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 0.375rem;
            padding: 0.75rem;
            font-size: 1.25rem;
            letter-spacing: 0.05em;
        }

        .step-item {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 1rem;
        }

        .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.75rem;
            height: 1.75rem;
            background-color: #3b82f6;
            color: white;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .tabs-container {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .tab {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .wide-container {
            width: 100%;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (max-width: 640px) {
            .wide-container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="wide-container">
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm relative"
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

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($isExpired && $order->payment_status == 'pending')
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
                    <p class="font-bold">Payment Expired</p>
                    <p>Your payment deadline has passed. Please make a new order.</p>
                </div>
            @endif

            <div class="card mb-8">
                <div class="bg-white p-6 rounded-t-lg border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="mb-4 md:mb-0">
                            <div class="text-sm text-gray-600 mb-1">Order #{{ $order->reference }}</div>
                            <h1 class="text-2xl font-bold">{{ $order->event->name }}</h1>
                            <div class="flex items-center mt-2">
                                <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
                                <span
                                    class="text-gray-600">{{ \Carbon\Carbon::parse($order->event->event_date)->format('F d, Y') }}
                                    • {{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div
                                class="
                                status-badge
                                {{ $order->payment_status == 'paid' ? 'paid' : ($order->payment_status == 'pending' ? 'pending' : 'failed') }}
                            ">
                                @if ($order->payment_status == 'paid')
                                    <i class="fas fa-check-circle mr-1"></i> Paid
                                @elseif($order->payment_status == 'pending')
                                    <i class="fas fa-clock mr-1"></i> Awaiting Payment
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> {{ ucfirst($order->payment_status) }}
                                @endif
                            </div>
                            <div class="text-lg font-bold mt-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->payment_status == 'pending' && !$isExpired)
                    <div class="bg-yellow-50 p-4 flex items-start border-b border-yellow-100">
                        <div class="text-yellow-800">
                            <div class="font-medium mb-1">Payment Deadline:</div>
                            <div id="expiry-time" class="font-medium"
                                data-expires="{{ \Carbon\Carbon::parse($order->expire_time)->format('c') }}">
                                {{ \Carbon\Carbon::parse($order->expire_time)->format('d M Y, H:i') }}
                            </div>
                            <div id="countdown" class="countdown mt-1"></div>
                        </div>
                    </div>
                @endif

                <div class="p-6" x-data="{ activeTab: 'details' }">
                    <div class="tabs-container">
                        <div class="tab" :class="{ 'active': activeTab === 'details' }" @click="activeTab = 'details'">
                            <i class="fas fa-info-circle mr-2"></i> Order Details
                        </div>
                        @if ($order->payment_status == 'pending' && !$isExpired)
                            <div class="tab" :class="{ 'active': activeTab === 'payment' }"
                                @click="activeTab = 'payment'">
                                <i class="fas fa-credit-card mr-2"></i> Payment Instructions
                            </div>
                        @endif
                        @if ($order->payment_status == 'paid')
                            <div class="tab" :class="{ 'active': activeTab === 'tickets' }"
                                @click="activeTab = 'tickets'">
                                <i class="fas fa-ticket-alt mr-2"></i> E-Tickets
                            </div>
                        @endif
                    </div>

                    <div x-show="activeTab === 'details'">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <h3 class="font-medium text-gray-700 mb-3">Buyer Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Name</div>
                                        <div>{{ $order->user->name }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Email</div>
                                        <div>{{ $order->user->email }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">WhatsApp Number</div>
                                        <div>{{ $order->user->no_whatsapp }}</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700 mb-3">Event Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Venue</div>
                                        <div>{{ $order->event->venue->name }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Date</div>
                                        <div>{{ \Carbon\Carbon::parse($order->event->event_date)->format('F d, Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Time</div>
                                        <div>{{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700 mb-3">Payment Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Payment Method</div>
                                        <div>{{ $order->payment_method }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-500">Payment Status</div>
                                        <div>
                                            @if ($order->payment_status == 'paid')
                                                <span class="text-green-600">Payment Successful</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="text-yellow-600">Awaiting Payment</span>
                                            @else
                                                <span class="text-red-600">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Order Date</div>
                                        <div>{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y, H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="font-medium text-gray-700 mb-3">Purchase Details</h3>
                        <div class="bg-gray-50 rounded-lg overflow-hidden mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Item
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $order->event->name }} Tickets</div>
                                            <div class="text-sm text-gray-500">
                                                @if ($order->items->count() > 0)
                                                    Seats: {{ $order->items->pluck('seat_label')->implode(', ') }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ $order->ticket_count }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            Rp {{ number_format($order->event->ticket_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @php
                                                $subtotal = $order->event->ticket_price * $order->ticket_count;
                                            @endphp
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    @if ($order->ticket_count > 5)
                                        @php
                                            $discountAmount = 50000 * $order->ticket_count;
                                        @endphp
                                        <tr class="bg-green-50">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-green-700">
                                                    <i class="fas fa-tag mr-1"></i> Special Discount
                                                </div>
                                                <div class="text-sm text-green-600">
                                                    Rp 50.000 discount per ticket for orders over 5 tickets
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-green-600">
                                                {{ $order->ticket_count }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-green-600">
                                                -Rp 50.000
                                            </td>
                                            <td class="px-6 py-4 text-right text-green-600 font-medium">
                                                -Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($order->fee > 0)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">Service Fee</div>
                                            </td>
                                            <td class="px-6 py-4 text-center">1</td>
                                            <td class="px-6 py-4 text-right">
                                                Rp {{ number_format($order->fee, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                Rp {{ number_format($order->fee, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4"></td>
                                        <td class="px-6 py-4 text-right font-medium">Total</td>
                                        <td class="px-6 py-4 text-right font-bold">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if ($order->ticket_count > 5)
                            <div class="bg-green-50 border border-green-100 p-4 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-0.5 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div>
                                        <p class="text-green-700 font-medium">Special Offer Applied</p>
                                        <p class="text-green-600 text-sm mt-1">
                                            You saved Rp {{ number_format(50000 * $order->ticket_count, 0, ',', '.') }}
                                            with our discount offer!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- <div class="flex justify-center">
                            <button
                                class="bg-blue-600 text-white py-2 px-6 rounded-lg flex items-center hover:bg-blue-700 action-button">
                                <i class="fas fa-download mr-2"></i> Download Invoice
                            </button>
                        </div> --}}
                    </div>

                    @if ($order->payment_status == 'pending' && !$isExpired)
                        <div x-show="activeTab === 'payment'">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-medium text-gray-700">Please Complete Your Payment</h3>
                                <p class="text-gray-600 mt-2">Make payment according to the following instructions</p>
                            </div>

                            @if (isset($order->pay_code) && $order->pay_code)
                                <div class="bg-gray-50 p-5 rounded-lg mb-6">
                                    <div class="text-center">
                                        <div class="text-gray-700 font-medium mb-2">Payment Code / Virtual Account Number
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <div class="payment-code mr-2">{{ $order->pay_code }}</div>
                                            <span class="copy-btn" data-clipboard="{{ $order->pay_code }}"
                                                title="Copy">
                                                <i class="far fa-copy text-xl text-blue-500"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (isset($order->qr_image) && $order->qr_image)
                                <div class="bg-gray-50 p-5 rounded-lg mb-6">
                                    <div class="text-center">
                                        <div class="text-gray-700 font-medium mb-2">Payment QR Code</div>
                                        <div class="flex flex-col items-center">
                                            <img src="{{ $order->qr_image }}" alt="Payment QR Code"
                                                class="h-48 w-48 mx-auto border p-2 bg-white rounded">
                                            <p class="text-sm text-gray-600 mt-2">Scan this QR code to make payment</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (isset($transactionDetails['data']['instructions']))
                                <div class="mb-6">
                                    <h3 class="font-medium text-gray-700 mb-4">How to Pay via
                                        {{ $order->payment_method }}</h3>

                                    <div class="bg-gray-50 rounded-lg p-5">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            @foreach ($transactionDetails['data']['instructions'] as $instruction)
                                                <div>
                                                    <h4 class="font-medium text-gray-800 mb-3">{{ $instruction['title'] }}
                                                    </h4>
                                                    <ol class="space-y-3">
                                                        @foreach ($instruction['steps'] as $index => $step)
                                                            <li class="step-item">
                                                                <div class="step-number">{{ $index + 1 }}</div>
                                                                <div>{!! $step !!}</div>
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (isset($order->checkout_url) && $order->checkout_url)
                                <div class="flex justify-center mt-8">
                                    <a href="{{ $order->checkout_url }}" target="_blank"
                                        class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition action-button shadow-md">
                                        <i class="fas fa-credit-card mr-2"></i> Continue to Payment
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($order->payment_status == 'paid')
                        <div x-show="activeTab === 'tickets'">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-medium text-gray-700">Your E-Tickets</h3>
                                <p class="text-gray-600 mt-2">Show these e-tickets during check-in at the event location
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach ($order->items as $index => $item)
                                    <div class="ticket-card bg-gray-50 rounded-lg overflow-hidden">
                                        <div class="p-5">
                                            <div class="flex flex-col md:flex-row gap-6">
                                                <div class="flex-grow">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <div>
                                                            <h3 class="font-bold text-lg text-gray-800">
                                                                {{ $order->event->name }}</h3>
                                                            <div
                                                                class="bg-blue-100 text-blue-800 text-sm font-bold rounded-full px-3 py-1 inline-block mt-2">
                                                                SEAT {{ $item->seat_label }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-2">
                                                        <div class="flex items-center">
                                                            <i
                                                                class="far fa-calendar-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                            <span>{{ \Carbon\Carbon::parse($order->event->event_date)->format('F d, Y') }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i class="far fa-clock text-blue-500 mr-3 w-5 text-center"></i>
                                                            <span>{{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i
                                                                class="fas fa-map-marker-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                            <span>{{ $order->event->venue->name }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i
                                                                class="fas fa-ticket-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                            <span>TICKET ID:
                                                                {{ $item->seat_label }}-{{ substr($order->reference, -5) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="flex flex-col items-center justify-center">
                                                    <div class="bg-white p-3 rounded border border-gray-200 mb-2">
                                                        <img src="https://placehold.co/200x200/777/fff?text=QR+CODE"
                                                            alt="QR Code" class="h-32 w-32">
                                                    </div>
                                                    <p class="text-xs text-gray-500">Scan for entry</p>
                                                </div> --}}
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="bg-white p-3 rounded border border-gray-200 mb-2">
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $item->seat_label }}-{{ substr($order->reference, -5) }}"
                                                            alt="QR Code Ticket" class="h-32 w-32 mt-2">
                                                    </div>
                                                    <p class="text-xs text-gray-500">Scan for entry</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if (count($order->items) == 0)
                                    @for ($i = 1; $i <= $order->ticket_count; $i++)
                                        <div class="ticket-card bg-gray-50 rounded-lg overflow-hidden">
                                            <div class="p-5">
                                                <div class="flex flex-col md:flex-row gap-6">
                                                    <div class="flex-grow">
                                                        <div class="flex justify-between items-start mb-4">
                                                            <div>
                                                                <h3 class="font-bold text-lg text-gray-800">
                                                                    {{ $order->event->name }}</h3>
                                                                <div
                                                                    class="bg-blue-100 text-blue-800 text-sm font-bold rounded-full px-3 py-1 inline-block mt-2">
                                                                    TICKET #{{ $i }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-2">
                                                            <div class="flex items-center">
                                                                <i
                                                                    class="far fa-calendar-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                                <span>{{ \Carbon\Carbon::parse($order->event->event_date)->format('F d, Y') }}</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <i
                                                                    class="far fa-clock text-blue-500 mr-3 w-5 text-center"></i>
                                                                <span>{{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <i
                                                                    class="fas fa-map-marker-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                                <span>{{ $order->event->venue->name }}</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <i
                                                                    class="fas fa-user text-blue-500 mr-3 w-5 text-center"></i>
                                                                <span>{{ $order->customer_name }}</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <i
                                                                    class="fas fa-ticket-alt text-blue-500 mr-3 w-5 text-center"></i>
                                                                <span>TICKET ID:
                                                                    HEMS-{{ $i }}-{{ substr($order->reference, -5) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-white p-3 rounded border border-gray-200 mb-2">
                                                            <img src="https://placehold.co/200x200/777/fff?text=QR+CODE"
                                                                alt="QR Code" class="h-32 w-32">
                                                        </div>
                                                        <p class="text-xs text-gray-500">Scan for entry</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>

                            {{-- <div class="text-center mt-8">
                                <button
                                    class="bg-blue-600 text-white py-2 px-6 rounded-lg flex items-center hover:bg-blue-700 mx-auto action-button">
                                    <i class="fas fa-print mr-2"></i> Print Tickets
                                </button>
                            </div> --}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('detail-event', $order->event->slug) }}" class="text-blue-600 hover:underline">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Event Details
                    </span>
                </a>

                <a href="{{ route('user-dashboard') }}" class="text-blue-600 hover:underline">
                    <span class="flex items-center">
                        View My Orders
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="clipboard-copied" id="clipboard-notification">
        <span>Copied to clipboard!</span>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expiryElement = document.getElementById('expiry-time');
            const countdownElement = document.getElementById('countdown');

            if (expiryElement && countdownElement) {
                const expiryTime = new Date(expiryElement.dataset.expires).getTime();

                const updateCountdown = () => {
                    const now = new Date().getTime();
                    const timeLeft = expiryTime - now;

                    if (timeLeft <= 0) {
                        countdownElement.innerHTML = 'EXPIRED';
                        setTimeout(() => window.location.reload(), 3000);
                        return;
                    }

                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    countdownElement.innerHTML =
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');

                    if (timeLeft < 300000) {
                        countdownElement.style.color = '#ef4444';
                        countdownElement.style.animation = 'pulse 1s infinite';
                    }
                };

                updateCountdown();
                setInterval(updateCountdown, 1000);
            }

            document.querySelectorAll('.copy-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const textToCopy = button.dataset.clipboard;
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const notification = document.getElementById(
                            'clipboard-notification');
                        notification.style.display = 'block';
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 2000);
                    });
                });
            });
        });
    </script>
@endpush
