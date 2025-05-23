@extends('layouts.app')
@section('title', 'Register')
@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold">Create Your Account</h2>
                    <p class="text-gray-600 mt-2">Join {{ $appset->name }} to discover and book amazing events</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="John Doe" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="your@email.com" required autocomplete="email">
                            @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="no_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">No
                                WhatsApp</label>
                            <input type="number" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                class="w-full px-4 py-3 rounded-lg border @error('no_whatsapp') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Your Number" required autocomplete="no_whatsapp">
                            @error('no_whatsapp')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3 rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="••••••••" required autocomplete="new-password">
                                <button type="button"
                                    onclick="togglePasswordVisibility('password', 'password-toggle-icon')"
                                    class="absolute right-3 top-3 text-gray-400">
                                    <i id="password-toggle-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                                Password</label>
                            <div class="relative">
                                <input type="password" id="password-confirm" name="password_confirmation"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="••••••••" required autocomplete="new-password">
                                <button type="button"
                                    onclick="togglePasswordVisibility('password-confirm', 'confirm-toggle-icon')"
                                    class="absolute right-3 top-3 text-gray-400">
                                    <i id="confirm-toggle-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="newsletter"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-600">I want to receive promotional emails</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="terms"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('terms') border-red-500 @enderror"
                                    required>
                                <span class="ml-2 text-sm text-gray-600">I agree to the <a href="#"
                                        class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#"
                                        class="text-blue-600 hover:underline">Privacy Policy</a></span>
                            </label>
                            @error('terms')
                                <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-300">
                                Register
                            </button>
                        </div>
                    </div>
                </form>

                {{-- <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <button
                            class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fab fa-google text-red-500"></i>
                        </button>
                        <button
                            class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fab fa-facebook-f text-blue-600"></i>
                        </button>
                        <button
                            class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fab fa-apple text-gray-800"></i>
                        </button>
                    </div>
                </div> --}}

                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endpush
