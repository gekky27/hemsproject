@extends('layouts.app')
@section('title', 'App Settings')
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

        .file-input {
            display: flex;
            align-items: center;
        }

        .file-input-preview {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            padding: 0.25rem;
        }

        .required-mark {
            color: #dc2626;
            margin-left: 0.125rem;
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
                            <h2 class="text-xl font-bold">Application Settings</h2>
                            <p class="text-gray-600">Configure your application settings and branding information.</p>
                        </div>

                        <div class="p-6">
                            @if (session('success'))
                                <div class="alert alert-success mb-6" id="success-alert">
                                    <div class="alert-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
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

                            @if ($errors->any())
                                <div class="alert alert-error mb-6" id="validation-alert">
                                    <div class="alert-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="alert-message">
                                        <p>Please check the following errors:</p>
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

                            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Application Name<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input"
                                            value="{{ $settings->name ?? old('name') }}" required>
                                        @error('name')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="url" class="form-label">
                                            Website URL<span class="required-mark">*</span>
                                        </label>
                                        <input type="url" id="url" name="url" class="form-input"
                                            value="{{ $settings->url ?? old('url') }}" required>
                                        @error('url')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="slogan" class="form-label">
                                            Slogan<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="slogan" name="slogan" class="form-input"
                                            value="{{ $settings->slogan ?? old('slogan') }}" required>
                                        @error('slogan')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            Contact Email<span class="required-mark">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="form-input"
                                            value="{{ $settings->email ?? old('email') }}" required>
                                        @error('email')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="whatsapp" class="form-label">
                                            WhatsApp Number<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="whatsapp" name="whatsapp" class="form-input"
                                            value="{{ $settings->whatsapp ?? old('whatsapp') }}" required>
                                        @error('whatsapp')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="instagram" class="form-label">
                                            Instagram Handle<span class="required-mark">*</span>
                                        </label>
                                        <input type="text" id="instagram" name="instagram" class="form-input"
                                            value="{{ $settings->instagram ?? old('instagram') }}" required>
                                        @error('instagram')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="deskripsi" class="form-label">
                                        Description<span class="required-mark">*</span>
                                    </label>
                                    <textarea id="deskripsi" name="deskripsi" rows="4" class="form-input" required>{{ $settings->deskripsi ?? old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <p class="input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div class="form-group">
                                        <label for="logo" class="form-label">Logo</label>
                                        <div class="file-input">
                                            @if (isset($settings->logo) && Storage::disk('public')->exists($settings->logo))
                                                <img src="{{ Storage::url($settings->logo) }}" alt="Logo"
                                                    class="file-input-preview" id="logoPreview">
                                            @else
                                                <img src="{{ asset('logo.png') }}" alt="Default Logo"
                                                    class="file-input-preview" id="logoPreview">
                                            @endif
                                            <input type="file" id="logo" name="logo" class="form-input"
                                                onchange="previewImage(this, 'logoPreview')">
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Recommended size: 200x60 pixels (PNG, SVG or
                                            JPG)</p>
                                        @error('logo')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="favicon" class="form-label">Favicon</label>
                                        <div class="file-input">
                                            @if (isset($settings->favicon) && Storage::disk('public')->exists($settings->favicon))
                                                <img src="{{ Storage::url($settings->favicon) }}" alt="Favicon"
                                                    class="file-input-preview" id="faviconPreview">
                                            @else
                                                <img src="{{ asset('favicon.png') }}" alt="Default Favicon"
                                                    class="file-input-preview" id="faviconPreview">
                                            @endif
                                            <input type="file" id="favicon" name="favicon" class="form-input"
                                                onchange="previewImage(this, 'faviconPreview')">
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Recommended size: 32x32 pixels (PNG or ICO)
                                        </p>
                                        @error('favicon')
                                            <p class="input-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin-dashboard') }}"
                                            class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md mr-2 hover:bg-gray-300 transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                            class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition-colors">
                                            Update Settings
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

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
@endpush
