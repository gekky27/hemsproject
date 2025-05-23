@extends('layouts.app')
@section('title', 'Organizer Dashboard')
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

        .status-active {
            background-color: rgb(187, 247, 208);
            color: rgb(22, 101, 52);
        }

        .status-inactive {
            background-color: rgb(254, 202, 202);
            color: rgb(153, 27, 27);
        }

        .status-pending {
            background-color: rgb(254, 240, 138);
            color: rgb(133, 77, 14);
        }

        .status-soldout {
            background-color: rgb(224, 231, 255);
            color: rgb(67, 56, 202);
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
        }

        .alert-warning {
            background-color: #fff7ed;
            border: 1px solid #f97316;
            color: #9a3412;
        }

        .alert-info {
            background-color: #eff6ff;
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-right: 0.75rem;
            width: 20px;
            height: 20px;
        }

        .alert-message {
            flex-grow: 1;
        }

        .alert-close {
            flex-shrink: 0;
            margin-left: 0.75rem;
            color: currentColor;
            opacity: 0.7;
            cursor: pointer;
        }

        .alert-close:hover {
            opacity: 1;
        }
    </style>
@endpush
@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-8">
                @include('layouts.organizer.sidebar')

                <div class="flex-grow">
                    @if (session('success'))
                        <div class="alert alert-success mb-6" id="success-alert">
                            <div class="alert-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24"
                                    height="24">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="alert-message">{{ session('success') }}</div>
                            <button type="button" class="alert-close" onclick="closeAlert('success-alert')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-bold mb-1">Welcome to Organizer Dashboard</h2>
                        <p class="text-gray-600 mb-4">Manage your events and track ticket sales.</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-indigo-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mr-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Events</div>
                                        <div class="text-2xl font-bold">{{ $totalEvents }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-3">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Revenue</div>
                                        <div class="text-2xl font-bold">
                                            {{ 'Rp ' . number_format($totalRevenues, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Tickets Sold</div>
                                        <div class="text-2xl font-bold">{{ $totalTicketsSold ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Quick Actions</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <a href="{{ route('events.create') }}"
                                class="bg-purple-600 text-white p-4 rounded-lg flex items-center hover:bg-purple-700 transition-colors">
                                <div class="mr-3 bg-purple-700 rounded-lg p-2">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Create New Event</div>
                                    <div class="text-xs text-purple-200">Add a new event to your calendar</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Ticket Sales by Event</h3>
                            <div class="flex space-x-2">
                                <select id="time-period" class="text-sm border border-gray-300 rounded px-2 py-1"
                                    onchange="updateSalesChart()">
                                    <option value="30">Last 30 Days</option>
                                    <option value="90">Last 90 Days</option>
                                    <option value="180">Last 6 Months</option>
                                    <option value="365">Last 12 Months</option>
                                    <option value="all">All Time</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="chart-container">
                                <canvas id="eventSalesChart"></canvas>
                            </div>
                            @if (empty($eventSalesData))
                                <div class="text-center text-gray-500 py-4">
                                    No ticket sales data available for this period
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">My Events</h3>
                            <a href="{{ route('events.index') }}"
                                class="text-purple-600 text-sm font-medium hover:text-purple-800">View
                                All</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event</th>
                                        <th
                                            class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tickets Sold</th>
                                        <th
                                            class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($recentEvents as $event)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-lg object-cover"
                                                            src="{{ $event->cover_image == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $event->name . '' : asset('storage/' . $event->cover_image) }}"
                                                            alt="{{ $event->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $event->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $event->venue->name ?? 'No venue' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ date('M d, Y', strtotime($event->event_date)) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ date('g:i A', strtotime($event->event_time)) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $totalCapacity = $event->available_seats + $event->tickets_sold;
                                                    $soldPercentage =
                                                        $totalCapacity > 0
                                                            ? ($event->tickets_sold / $totalCapacity) * 100
                                                            : 0;
                                                @endphp
                                                <div class="text-sm text-gray-900">{{ $event->tickets_sold }} /
                                                    {{ $totalCapacity }}</div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                    <div class="bg-green-500 h-1.5 rounded-full"
                                                        style="width: {{ $soldPercentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span
                                                    class="status-badge {{ $event->status === 'ready' ? 'status-active' : 'status-soldout' }}">
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="#" class="text-purple-600 hover:text-purple-900 mr-3"
                                                        title="Edit Event">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                No events found. <a href="{{ route('events.create') }}"
                                                    class="text-purple-600 hover:underline">Create your first event</a>.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Recent Ticket Sales</h3>
                            <a href="#" class="text-purple-600 text-sm font-medium hover:text-purple-800">View
                                All</a>
                        </div>

                        <div class="p-4">
                            <ul class="divide-y divide-gray-200">
                                @forelse($recentSales as $order)
                                    <li class="py-3 flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                            {{ strtoupper(substr($order->user->name ?? 'User', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $order->user->name ?? 'Anonymous User' }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->event->name }} -
                                                {{ $order->ticket_count }} ticket(s)</div>
                                        </div>
                                        <div class="ml-auto">
                                            <span class="text-gray-600">Rp
                                                {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-center text-gray-500">
                                        No recent ticket sales found.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let eventSalesChart;

        document.addEventListener('DOMContentLoaded', function() {
            const eventSalesCtx = document.getElementById('eventSalesChart').getContext('2d');

            eventSalesChart = new Chart(eventSalesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($eventSalesData ?? [])) !!},
                    datasets: [{
                        label: 'Tickets Sold',
                        data: {!! json_encode(array_values($eventSalesData ?? [])) !!},
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.6)',
                            'rgba(124, 58, 237, 0.6)',
                            'rgba(16, 185, 129, 0.6)',
                            'rgba(245, 158, 11, 0.6)',
                            'rgba(239, 68, 68, 0.6)',
                            'rgba(79, 70, 229, 0.6)',
                            'rgba(236, 72, 153, 0.6)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(124, 58, 237, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(79, 70, 229, 1)',
                            'rgba(236, 72, 153, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Tickets'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });

        function updateSalesChart() {
            const period = document.getElementById('time-period').value;

            fetch(`/organizer-dashboard/sales-data/${period}`)
                .then(response => response.json())
                .then(data => {
                    eventSalesChart.data.labels = data.labels;
                    eventSalesChart.data.datasets[0].data = data.values;
                    eventSalesChart.update();

                    const noDataMessage = document.querySelector('#eventSalesChart').closest('.chart-container')
                        .nextElementSibling;
                    if (data.labels.length === 0) {
                        noDataMessage.style.display = 'block';
                    } else {
                        noDataMessage.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching sales data:', error);
                });
        }

        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
@endpush
