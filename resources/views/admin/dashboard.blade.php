@extends('layouts.app')
@section('title', 'Admin Dashboard')
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

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Alert styles */
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
                @include('layouts.admin.sidebar')

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
                        <h2 class="text-xl font-bold mb-1">Welcome to Admin Dashboard</h2>
                        <p class="text-gray-600 mb-4">Manage event, venues, organizers, and view statistics.</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-th-list"></i>
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
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Venues</div>
                                        <div class="text-2xl font-bold">{{ $totalVenues }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-3">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Total Organizers</div>
                                        <div class="text-2xl font-bold">{{ $totalOrganizer }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('venues.create') }}"
                                class="bg-green-600 text-white p-4 rounded-lg flex items-center hover:bg-green-700 transition-colors">
                                <div class="mr-3 bg-green-700 rounded-lg p-2">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Add Venue</div>
                                    <div class="text-xs text-green-200">Register a new venue</div>
                                </div>
                            </a>

                            <a href="{{ route('organizers.create') }}"
                                class="bg-purple-600 text-white p-4 rounded-lg flex items-center hover:bg-purple-700 transition-colors">
                                <div class="mr-3 bg-purple-700 rounded-lg p-2">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Register Organizer</div>
                                    <div class="text-xs text-purple-200">Create organizer account</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                                <h3 class="text-lg font-bold">Venues Used ({{ $currentMonthName }} {{ $currentYear }})</h3>
                                <div class="flex space-x-2">
                                    <select id="month-select" class="text-sm border border-gray-300 rounded px-2 py-1 mr-2"
                                        onchange="updateCharts()">
                                        @foreach ($months as $index => $month)
                                            <option value="{{ $index + 1 }}"
                                                {{ $currentMonth == $index + 1 ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select id="year-select" class="text-sm border border-gray-300 rounded px-2 py-1"
                                        onchange="updateCharts()">
                                        @for ($i = $currentYear; $i >= $currentYear - 2; $i--)
                                            <option value="{{ $i }}"
                                                {{ $currentYear == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="chart-container">
                                    <canvas id="venueUsageChart"></canvas>
                                </div>
                                @if (count($venueUsageData) == 0)
                                    <div class="text-center text-gray-500 py-4">
                                        No venue usage data found for this period
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                                <h3 class="text-lg font-bold">Ticket Sales by Event ({{ $currentMonthName }}
                                    {{ $currentYear }})</h3>
                                <div class="flex space-x-2 hidden">
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="chart-container">
                                    <canvas id="ticketSalesChart"></canvas>
                                </div>
                                @if (count($ticketSalesData) == 0)
                                    <div class="text-center text-gray-500 py-4">
                                        No ticket sales data found for this period
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Recent Events</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event Name</th>
                                        <th
                                            class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Venue</th>
                                        <th
                                            class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @if (isset($recentEvents) && $recentEvents->count() > 0)
                                        @foreach ($recentEvents as $event)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900">{{ $event->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ Str::limit($event->description, 50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        @if (isset($event->venue))
                                                            {{ $event->venue->name }}
                                                        @else
                                                            Not set
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-sm text-gray-900">
                                                        {{ date('d M Y', strtotime($event->event_date)) }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ date('H:i', strtotime($event->event_time)) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span
                                                        class="status-badge {{ $event->status === 'ready' ? 'status-active' : 'status-inactive' }}">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                No events found.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
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
        let venueUsageChart, ticketSalesChart;

        document.addEventListener('DOMContentLoaded', function() {
            const venueUsageCtx = document.getElementById('venueUsageChart').getContext('2d');
            venueUsageChart = new Chart(venueUsageCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($venueUsageData)) !!},
                    datasets: [{
                        label: 'Events Count',
                        data: {!! json_encode(array_values($venueUsageData)) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
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
                                text: 'Number of Events'
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

            const ticketSalesCtx = document.getElementById('ticketSalesChart').getContext('2d');
            ticketSalesChart = new Chart(ticketSalesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($ticketSalesData)) !!},
                    datasets: [{
                        label: 'Tickets Sold',
                        data: {!! json_encode(array_values($ticketSalesData)) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Tickets Sold'
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

        function updateCharts() {
            const month = document.getElementById('month-select').value;
            const year = document.getElementById('year-select').value;

            fetch(`/admin-dashboard/stats/${year}/${month}`)
                .then(response => response.json())
                .then(data => {
                    venueUsageChart.data.labels = data.venueLabels;
                    venueUsageChart.data.datasets[0].data = data.venueData;
                    venueUsageChart.update();

                    ticketSalesChart.data.labels = data.ticketLabels;
                    ticketSalesChart.data.datasets[0].data = data.ticketData;
                    ticketSalesChart.update();

                    document.querySelector('.bg-white:nth-child(1) h3').textContent =
                        `Venues Used (${data.monthName} ${year})`;
                    document.querySelector('.bg-white:nth-child(2) h3').textContent =
                        `Ticket Sales by Event (${data.monthName} ${year})`;

                    const venueNoData = document.querySelector('#venueUsageChart').closest('.chart-container')
                        .nextElementSibling;
                    const ticketNoData = document.querySelector('#ticketSalesChart').closest('.chart-container')
                        .nextElementSibling;

                    if (data.venueLabels.length === 0) {
                        venueNoData.style.display = 'block';
                    } else {
                        venueNoData.style.display = 'none';
                    }

                    if (data.ticketLabels.length === 0) {
                        ticketNoData.style.display = 'block';
                    } else {
                        ticketNoData.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching statistics:', error);
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
