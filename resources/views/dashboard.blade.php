@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
    <style>
        .nav-pill {
            transition: all 0.2s ease;
        }

        .nav-pill.active {
            background-color: rgba(37, 99, 235, 0.1);
            color: rgb(37, 99, 235);
            font-weight: 500;
        }

        .nav-pill:hover:not(.active) {
            background-color: rgba(243, 244, 246, 1);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: rgb(254, 240, 138);
            color: rgb(133, 77, 14);
        }

        .status-paid {
            background-color: rgb(187, 247, 208);
            color: rgb(22, 101, 52);
        }

        .status-expired {
            background-color: rgb(254, 202, 202);
            color: rgb(153, 27, 27);
        }

        .status-failed,
        .status-canceled {
            background-color: rgb(229, 231, 235);
            color: rgb(55, 65, 81);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div
                                    class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl font-bold mr-4">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">{{ auth()->user()->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Account</h4>
                            <nav class="space-y-1">
                                <a href="{{ route('user-dashboard') }}"
                                    class="nav-pill active flex items-center px-4 py-3 rounded-md">
                                    <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i>
                                    <span>Dashboard</span>
                                </a>
                                {{-- <a href="#" class="nav-pill flex items-center px-4 py-3 rounded-md text-gray-700">
                                    <i class="fas fa-user w-5 text-center mr-3"></i>
                                    <span>Profile</span>
                                </a> --}}
                            </nav>
                            <div class="pt-6 mt-6 border-t border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center px-4 py-3 rounded-md text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-grow">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
                        <p class="text-gray-600 mb-4">Here's what's happening with your account today.</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Upcoming Events</div>
                                        <div class="text-2xl font-bold">{{ $upcomingEvents }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-3">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Orders Completed</div>
                                        <div class="text-2xl font-bold">{{ $completedOrders }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-3">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Orders</div>
                                        <div class="text-2xl font-bold">{{ $orders->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Order History</h3>
                        </div>

                        @if ($orders->isEmpty())
                            <div class="p-8 text-center">
                                <div class="text-gray-400 text-4xl mb-4">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No Orders Yet</h3>
                                <p class="text-gray-500 mb-4">You haven't made any orders yet. Explore events and book your
                                    tickets!</p>
                                <a href="{{ route('landingpages') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-search mr-2"></i> Explore Events
                                </a>
                            </div>
                        @else
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th
                                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Order ID</th>
                                            <th
                                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Event</th>
                                            <th
                                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date</th>
                                            <th
                                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount</th>
                                            <th
                                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($orders as $order)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    #{{ $order->reference }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            {{-- <img class="h-10 w-10 rounded-lg object-cover"
                                                                src="{{ $order->event->cover_image ? asset('storage/' . $order->event->cover_image) : 'https://placehold.co/100x100/777/fff?text=' . urlencode($order->event->name) }}"
                                                                alt="{{ $order->event->name }}"> --}}
                                                            <img class="h-10 w-10 rounded-lg object-cover"
                                                                src="{{ $order->event->cover_image == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $order->event->name . '' : asset('storage/' . $order->event->cover_image) }}"
                                                                alt="{{ $order->event->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $order->event->name }}</div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ $order->event->venue->name ?? 'Venue TBA' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ \Carbon\Carbon::parse($order->event->event_date)->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="status-badge status-{{ $order->payment_status }}">
                                                        {{ ucfirst($order->payment_status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="{{ route('payment.invoice', $order->reference) }}"
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition-colors">
                                                            <i class="fas fa-eye mr-1.5"></i>
                                                            View
                                                        </a>
                                                        {{-- @if ($order->payment_status == 'pending' && \Carbon\Carbon::parse($order->expire_time)->isFuture())
                                                            <a href="{{ $order->checkout_url ?? route('payment.invoice', $order->reference) }}"
                                                                target="{{ $order->checkout_url ? '_blank' : '_self' }}"
                                                                class="inline-flex items-center px-2.5 py-1.5 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                                                                <i class="fas fa-credit-card mr-1.5"></i>
                                                                Pay Now
                                                            </a>
                                                        @endif --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="md:hidden px-4 py-4 space-y-4">
                                @foreach ($orders as $order)
                                    <div class="bg-white rounded-lg shadow overflow-hidden">
                                        <div class="flex items-center p-4 border-b border-gray-200">
                                            <img src="{{ $order->event->cover_image ? asset('storage/' . $order->event->cover_image) : 'https://placehold.co/100x100/777/fff?text=' . urlencode($order->event->name) }}"
                                                alt="{{ $order->event->name }}"
                                                class="w-16 h-16 object-cover rounded-lg mr-3">
                                            <div>
                                                <div class="font-medium">{{ $order->event->name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($order->event->event_date)->format('M d, Y') }}
                                                    •
                                                    {{ \Carbon\Carbon::parse($order->event->event_time)->format('H:i') }}
                                                </div>
                                                <div class="text-xs text-gray-500">#{{ $order->reference }}</div>
                                            </div>
                                        </div>
                                        <div class="p-4 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Venue:</span>
                                                <span
                                                    class="text-right">{{ $order->event->venue->name ?? 'Venue TBA' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Amount:</span>
                                                <span class="font-medium">Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status:</span>
                                                <span class="status-badge status-{{ $order->payment_status }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex border-t border-gray-200">
                                            <a href="{{ route('payment.invoice', $order->reference) }}"
                                                class="flex-1 py-3 border-r border-gray-200 text-center text-blue-600 font-medium hover:bg-blue-50 transition-colors">
                                                <i class="fas fa-eye mr-1.5"></i> View
                                            </a>

                                            @if ($order->payment_status == 'paid')
                                                <a href="{{ route('payment.invoice', $order->reference) }}"
                                                    class="flex-1 py-3 text-center text-blue-600 font-medium hover:bg-blue-50 transition-colors">
                                                    <i class="fas fa-ticket-alt mr-1.5"></i> Tickets
                                                </a>
                                            @elseif($order->payment_status == 'pending' && \Carbon\Carbon::parse($order->expire_time)->isFuture())
                                                <a href="{{ $order->checkout_url ?? route('payment.invoice', $order->reference) }}"
                                                    target="{{ $order->checkout_url ? '_blank' : '_self' }}"
                                                    class="flex-1 py-3 text-center text-yellow-600 font-medium hover:bg-yellow-50 transition-colors">
                                                    <i class="fas fa-credit-card mr-1.5"></i> Pay Now
                                                </a>
                                            @else
                                                <a href="{{ route('payment.invoice', $order->reference) }}"
                                                    class="flex-1 py-3 text-center text-gray-600 font-medium hover:bg-gray-100 transition-colors">
                                                    <i class="fas fa-info-circle mr-1.5"></i> Details
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
