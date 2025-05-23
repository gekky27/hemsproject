@extends('layouts.app')
@section('title', 'Login')
@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold">Login</h2>
                    <p class="text-gray-600 mt-2">Sign in to your {{ $appset->name }} account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="your@email.com" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3 rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="••••••••" required autocomplete="current-password">
                                <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute right-3 top-3 text-gray-400">
                                    <i id="password-toggle-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                            <div class="flex justify-between mt-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-300">
                                Login
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
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
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
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var icon = document.getElementById('password-toggle-icon');

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
