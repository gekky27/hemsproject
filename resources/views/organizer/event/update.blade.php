@extends('layouts.app')
@section('title', 'Edit Event')
@push('styles')
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-error {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .required-mark {
            color: #dc2626;
            margin-left: 0.125rem;
        }

        .file-input {
            display: flex;
            align-items: center;
        }

        .file-input-preview {
            width: 120px;
            height: 80px;
            object-fit: cover;
            margin-right: 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            padding: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
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
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Edit Event</h2>
                            <p class="text-gray-600">Update information for "{{ $event->name }}"</p>
                        </div>

                        <div class="p-6">
                            @if ($errors->any())
                                <div class="alert alert-error mb-6" id="validation-alert">
                                    <div class="alert-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            width="24" height="24">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="alert-message">
                                        <p>Please correct the following errors:</p>
                                        <ul class="list-disc list-inside mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="alert-close" onclick="closeAlert('validation-alert')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('events.update', $event->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Event Name<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input"
                                            value="{{ old('name', $event->name) }}" required>
                                        @error('name')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="venues_id" class="form-label">
                                            Venue<span class="required-mark">*</span>
                                        </label>
                                        <select id="venues_id" name="venues_id" class="form-input" required>
                                            <option value="">Select Venue</option>
                                            @foreach ($venues as $venue)
                                                <option value="{{ $venue->id }}"
                                                    {{ old('venues_id', $event->venues_id) == $venue->id ? 'selected' : '' }}>
                                                    {{ $venue->name }} (Capacity:
                                                    {{ number_format($venue->total_capacity) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @if (old('venues_id', $event->venues_id) != $event->venues_id)
                                            <p class="text-amber-600 text-xs mt-1">
                                                Warning: Changing the venue will reset all seat assignments for this event.
                                            </p>
                                        @endif
                                        @error('venues_id')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="ticket_price" class="form-label">
                                            Ticket Price (Rp)<span class="required-mark">*</span>
                                        </label>
                                        <input type="number" id="ticket_price" name="ticket_price" class="form-input"
                                            value="{{ old('ticket_price', $event->ticket_price) }}" min="0"
                                            required>
                                        @error('ticket_price')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="event_date" class="form-label">
                                            Event Date<span class="required-mark">*</span>
                                        </label>
                                        <input type="date" id="event_date" name="event_date" class="form-input"
                                            value="{{ old('event_date', $event->event_date) }}" required>
                                        @error('event_date')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="event_time" class="form-label">
                                            Event Time<span class="required-mark">*</span>
                                        </label>
                                        <input type="time" id="event_time" name="event_time" class="form-input"
                                            value="{{ old('event_time', date('H:i', strtotime($event->event_time))) }}"
                                            required>
                                        @error('event_time')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status" class="form-label">
                                            Status<span class="required-mark">*</span>
                                        </label>
                                        <select id="status" name="status" class="form-input" required>
                                            <option value="ready"
                                                {{ old('status', $event->status) == 'ready' ? 'selected' : '' }}>Ready
                                            </option>
                                            <option value="soldout"
                                                {{ old('status', $event->status) == 'soldout' ? 'selected' : '' }}>
                                                Sold Out</option>
                                        </select>
                                        @error('status')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="description" class="form-label">
                                        Description<span class="required-mark">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="4" class="form-input" required>{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <label for="cover_image" class="form-label">
                                        Cover Image
                                    </label>
                                    <div class="file-input">
                                        <img src="{{ $event->cover_image ? asset('storage/' . $event->cover_image) : 'https://placehold.co/500x500/444/fff?text=Images' }}"
                                            alt="{{ $event->name }}" class="file-input-preview" id="coverPreview">
                                        <input type="file" id="cover_image" name="cover_image" class="form-input"
                                            onchange="previewImage(this, 'coverPreview')">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Recommended
                                        size: 1200x628 pixels (JPG, PNG)</p>
                                    @error('cover_image')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <a href="{{ route('events.index') }}"
                                            class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md mr-2 hover:bg-gray-300 transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                            class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition-colors">
                                            Update Event
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }
    </script>
@endpush
