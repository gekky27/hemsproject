@extends('layouts.app')
@section('title', 'Events Management')
@push('styles')
    <style>
        .table-container {
            overflow-x: auto;
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

        .status-ready {
            background-color: rgb(187, 247, 208);
            color: rgb(22, 101, 52);
        }

        .status-soldout {
            background-color: rgb(254, 202, 202);
            color: rgb(153, 27, 27);
        }

        .event-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.375rem;
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
                @include('layouts.organizer.sidebar')

                <div class="flex-grow">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold">Events Management</h2>
                                <p class="text-gray-600">Manage your events and their details</p>
                            </div>
                            <a href="{{ route('events.create') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Create Event
                            </a>
                        </div>

                        <div class="p-6">
                            @if (session('success'))
                                <div class="alert alert-success mb-6" id="success-alert">
                                    <div class="alert-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            width="24" height="24">
                                            <path fill-rule="evenodd"
                                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="alert-message">{{ session('success') }}</div>
                                    <button type="button" class="alert-close" onclick="closeAlert('success-alert')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-error mb-6" id="error-alert">
                                    <div class="alert-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            width="24" height="24">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="alert-message">{{ session('error') }}</div>
                                    <button type="button" class="alert-close" onclick="closeAlert('error-alert')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <div class="mb-4 flex flex-col md:flex-row gap-4 justify-between">
                                <div class="flex w-full md:w-64">
                                    <input type="text" id="search" placeholder="Search events..."
                                        class="w-full border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button
                                        class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-md px-3 hover:bg-gray-200">
                                        <i class="fas fa-search text-gray-500"></i>
                                    </button>
                                </div>
                                <div class="flex gap-2">
                                    <select id="status-filter"
                                        class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Statuses</option>
                                        <option value="ready">Ready</option>
                                        <option value="soldout">Sold Out</option>
                                    </select>
                                    <select id="sort-by"
                                        class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="date_asc">Date (Oldest First)</option>
                                        <option value="date_desc" selected>Date (Newest First)</option>
                                        <option value="name_asc">Name (A-Z)</option>
                                        <option value="name_desc">Name (Z-A)</option>
                                        <option value="price_asc">Price (Low to High)</option>
                                        <option value="price_desc">Price (High to Low)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-container">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Event</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Venue</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date & Time</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tickets</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Price</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($events as $event)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900">
                                                        {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        {{-- <div class="flex-shrink-0 h-15 w-15 mr-4">
                                                            <img class="event-image"
                                                                src="{{ $event->cover_image == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $event->name . '' : asset('storage/' . $event->cover_image) }}"
                                                                alt="{{ $event->name }}">
                                                        </div> --}}
                                                        <div>
                                                            <div class="font-medium text-gray-900">
                                                                {{ $event->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $event->venue->name ?? 'No venue assigned' }}</div>
                                                    {{-- <div class="text-xs text-gray-500">{{ $event->venue->alamat ?? '' }}
                                                    </div> --}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-sm text-gray-900">
                                                        {{ date('d M Y', strtotime($event->event_date)) }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ date('h:i A', strtotime($event->event_time)) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $ticketsSold = $event
                                                            ->orders()
                                                            ->where('payment_status', 'paid')
                                                            ->sum('ticket_count');
                                                        $totalSeats = $event->available_seats + $ticketsSold;
                                                        $soldPercent =
                                                            $totalSeats > 0 ? ($ticketsSold / $totalSeats) * 100 : 0;
                                                    @endphp
                                                    <div class="text-sm text-gray-900">{{ $ticketsSold }} /
                                                        {{ $totalSeats }}</div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                        <div class="bg-blue-500 h-1.5 rounded-full"
                                                            style="width: {{ $soldPercent }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-sm font-medium text-gray-900">Rp
                                                        {{ number_format($event->ticket_price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="status-badge status-{{ $event->status }}">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('detail-event', $event->slug) }}"
                                                        class="text-blue-600 hover:text-blue-900 mr-3"
                                                        title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('events.edit', $event->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 mr-3" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="text-red-600 hover:text-red-900"
                                                        onclick="confirmDelete({{ $event->id }}, '{{ $event->name }}')"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                    No events found. <a href="{{ route('events.create') }}"
                                                        class="text-blue-600 hover:underline">Create your first event</a>.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($events->hasPages())
                                <nav role="navigation" aria-label="Pagination Navigation"
                                    class="flex items-center justify-between mt-4">
                                    <div class="flex justify-between flex-1 sm:hidden">
                                        @if ($events->onFirstPage())
                                            <span
                                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                                Previous
                                            </span>
                                        @else
                                            <a href="{{ $events->previousPageUrl() }}"
                                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                Previous
                                            </a>
                                        @endif

                                        @if ($events->hasMorePages())
                                            <a href="{{ $events->nextPageUrl() }}"
                                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                Next
                                            </a>
                                        @else
                                            <span
                                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                                                Next
                                            </span>
                                        @endif
                                    </div>

                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ $events->firstItem() }}</span>
                                                to
                                                <span class="font-medium">{{ $events->lastItem() }}</span>
                                                of
                                                <span class="font-medium">{{ $events->total() }}</span>
                                                results
                                            </p>
                                        </div>

                                        <div>
                                            <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                                @if ($events->onFirstPage())
                                                    <span aria-disabled="true" aria-label="Previous">
                                                        <span
                                                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-l-md"
                                                            aria-hidden="true">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                @else
                                                    <a href="{{ $events->previousPageUrl() }}" rel="prev"
                                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50"
                                                        aria-label="Previous">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                @foreach ($events->links()->elements as $element)
                                                    @if (is_string($element))
                                                        <span aria-disabled="true">
                                                            <span
                                                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">{{ $element }}</span>
                                                        </span>
                                                    @endif

                                                    @if (is_array($element))
                                                        @foreach ($element as $page => $url)
                                                            @if ($page == $events->currentPage())
                                                                <span aria-current="page">
                                                                    <span
                                                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default">{{ $page }}</span>
                                                                </span>
                                                            @else
                                                                <a href="{{ $url }}"
                                                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50"
                                                                    aria-label="Go to page {{ $page }}">
                                                                    {{ $page }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                                @if ($events->hasMorePages())
                                                    <a href="{{ $events->nextPageUrl() }}" rel="next"
                                                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50"
                                                        aria-label="Next">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span aria-disabled="true" aria-label="Next">
                                                        <span
                                                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-r-md"
                                                            aria-hidden="true">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mx-auto" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Confirm Deletion</h3>
                <p class="text-gray-600 mt-2">Are you sure you want to delete <span id="eventName"
                        class="font-semibold"></span>? This action cannot be undone.</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none"
                    onclick="closeDeleteModal()">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmDelete(id, name) {
            document.getElementById('eventName').textContent = name;
            document.getElementById('deleteForm').action = `{{ route('events.delete', '') }}/` + id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
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

        document.getElementById('search').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        document.getElementById('status-filter').addEventListener('change', applyFilters);
        document.getElementById('sort-by').addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('search').value;
            const status = document.getElementById('status-filter').value;
            const sortBy = document.getElementById('sort-by').value;

            let url = new URL(window.location.href);

            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }

            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            if (sortBy) {
                url.searchParams.set('sort', sortBy);
            } else {
                url.searchParams.delete('sort');
            }

            window.location.href = url.toString();
        }

        window.addEventListener('DOMContentLoaded', function() {
            const url = new URL(window.location.href);

            const search = url.searchParams.get('search');
            if (search) {
                document.getElementById('search').value = search;
            }

            const status = url.searchParams.get('status');
            if (status) {
                document.getElementById('status-filter').value = status;
            }

            const sort = url.searchParams.get('sort');
            if (sort) {
                document.getElementById('sort-by').value = sort;
            }
        });
    </script>
@endpush
