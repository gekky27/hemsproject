@extends('layouts.app')
@section('title', 'Add New Venue')
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
                            <h2 class="text-xl font-bold">Add New Venue</h2>
                            <p class="text-gray-600">Create a new venue for events</p>
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

                            <form action="{{ route('venues.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Venue Name<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="total_capacity" class="form-label">
                                            Total Capacity<span class="required-mark">*</span>
                                        </label>
                                        <input type="number" id="total_capacity" name="total_capacity" class="form-input"
                                            value="{{ old('total_capacity') }}" min="1" required>
                                        @error('total_capacity')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="alamat" class="form-label">
                                        Address<span class="required-mark">*</span>
                                    </label>
                                    <input type="text" id="alamat" name="alamat" class="form-input"
                                        value="{{ old('alamat') }}" required>
                                    @error('alamat')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <label for="description" class="form-label">
                                        Description<span class="required-mark">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="4" class="form-input" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <label for="status" class="form-label">
                                        Status<span class="required-mark">*</span>
                                    </label>
                                    <select id="status" name="status" class="form-input" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <a href="{{ route('venues.index') }}"
                                            class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md mr-2 hover:bg-gray-300 transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                            class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition-colors">
                                            Save Venue
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
        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }
    </script>
@endpush
