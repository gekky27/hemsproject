<nav class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
    <div class="flex items-center">
        <a href="{{ $appset->url }}" class="flex items-center">
            <div class="mr-2">
                <img src="{{ asset('HEMSLogo.png') }}" alt="Logo" class="h-14 transform">
            </div>
            <div>
                <h1 class="font-bold text-xl">{{ $appset->name }}</h1>
                <p class="text-sm">{{ $appset->slogan }}</p>
            </div>
        </a>
    </div>

    <div class="hidden md:flex items-center space-x-6">
        {{-- <a href="#" class="text-lg">Explore</a>
        <a href="#" class="text-lg">Favourite</a> --}}
        @guest
            <a href="{{ route('login') }}" class="text-lg">Login</a>
        @else
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('admin-dashboard') }}" class="text-lg">Admin Dashboard</a>
            @elseif (auth()->user()->role == 'organizer')
                <a href="{{ route('organizer-dashboard') }}" class="text-lg">Organizer Dashboard</a>
            @elseif (auth()->user()->role == 'user')
                <a href="{{ route('user-dashboard') }}" class="text-lg">Dashboard</a>
            @endif
        @endguest
    </div>
</nav>
