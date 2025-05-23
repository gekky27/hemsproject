@extends('layouts.app')
@section('title', 'Update Organizer')
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

        .file-input {
            display: flex;
            align-items: center;
        }

        .file-input-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            padding: 0.25rem;
        }

        .section-title {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            border-left: 3px solid #3b82f6;
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
                            <h2 class="text-xl font-bold">Update Organizer</h2>
                            <p class="text-gray-600">Update the organizer account and profile details</p>
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

                            <form action="{{ route('organizers.update', $user->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <h3 class="section-title">User Account Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Name<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            Email<span class="required-mark">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="form-input"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="no_whatsapp" class="form-label">
                                            WhatsApp Number<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="no_whatsapp" name="no_whatsapp" class="form-input"
                                            value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required
                                            placeholder="e.g., 628123456789">
                                        @error('no_whatsapp')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            Password
                                        </label>
                                        <input type="password" id="password" name="password" class="form-input">
                                        @error('password')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            Confirm Password
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-input">
                                    </div>
                                </div>

                                <h3 class="section-title mt-6">Organizer Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="organizer_name" class="form-label">
                                            Organizer Name<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="organizer_name" name="organizer_name" class="form-input"
                                            value="{{ old('organizer_name', $user->organizer->name) }}" required>
                                        <p class="text-xs text-gray-500 mt-1">This will be the official name of the
                                            organization</p>
                                        @error('organizer_name')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="organizer_type" class="form-label">
                                            Organizer Type<span class="required-mark">*</span>
                                        </label>
                                        <select id="organizer_type" name="organizer_type" class="form-input" required>
                                            <option value="">Select Organizer Type</option>
                                            <option value="Concerts"
                                                {{ old('organizer_type', $user->organizer->organizer_type) == 'Concerts' ? 'selected' : '' }}>
                                                Concerts
                                            </option>
                                            <option value="Conference"
                                                {{ old('organizer_type', $user->organizer->organizer_type) == 'Conference' ? 'selected' : '' }}>
                                                Conference
                                            </option>
                                            <option value="Workshop"
                                                {{ old('organizer_type', $user->organizer->organizer_type) == 'Workshop' ? 'selected' : '' }}>
                                                Workshop
                                            </option>
                                            <option value="Theater"
                                                {{ old('organizer_type', $user->organizer->organizer_type) == 'Theater' ? 'selected' : '' }}>
                                                Theater
                                            </option>
                                            <option value="All Type"
                                                {{ old('organizer_type', $user->organizer->organizer_type) == 'All Type' ? 'selected' : '' }}>
                                                All Type
                                            </option>
                                        </select>
                                        @error('organizer_type')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="auditorium_type" class="form-label">
                                            Auditorium Type<span class="required-mark">*</span>
                                        </label>
                                        <select id="auditorium_type" name="auditorium_type" class="form-input" required>
                                            <option value="">Select Auditorium Type</option>
                                            <option value="Melodia Pavilion"
                                                {{ old('auditorium_type', $user->organizer->auditorium_type) == 'Melodia Pavilion' ? 'selected' : '' }}>
                                                Melodia Pavilion</option>
                                            <option value="Garuda Theater"
                                                {{ old('auditorium_type', $user->organizer->auditorium_type) == 'Garuda Theater' ? 'selected' : '' }}>
                                                Garuda
                                                Theater
                                            </option>
                                            <option value="Celestia Theater"
                                                {{ old('auditorium_type', $user->organizer->auditorium_type) == 'Celestia Theater' ? 'selected' : '' }}>
                                                Celestia Theater</option>
                                        </select>
                                        @error('auditorium_type')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="logo" class="form-label">
                                            Organizer Logo
                                        </label>
                                        <div class="file-input">
                                            @if ($user->organizer->logo != 'default.jpg')
                                                <img src="{{ asset('storage/' . $user->organizer->logo) }}"
                                                    alt="Favicon" class="file-input-preview" id="faviconPreview">
                                            @else
                                                <img src="https://placehold.co/500x500/444/fff?text=Logo"
                                                    alt="Default Logo" class="file-input-preview" id="logoPreview">
                                            @endif
                                            <input type="file" id="logo" name="logo" class="form-input"
                                                onchange="previewImage(this, 'logoPreview')">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Recommended size: 200x200 pixels (PNG, JPG)
                                        </p>
                                        @error('logo')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <a href="{{ route('organizers.index') }}"
                                            class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md mr-2 hover:bg-gray-300 transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                            class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition-colors">
                                            Update Organizer
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
