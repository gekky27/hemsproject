<div class="w-full md:w-64 flex-shrink-0">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div
                    class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-xl font-bold mr-4">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-bold text-lg">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full">Organizer</span>
                </div>
            </div>
        </div>

        <div class="p-4">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Organizer Menu
            </h4>
            <nav class="space-y-1">
                <a href="{{ route('organizer-dashboard') }}"
                    class="nav-pill flex items-center px-4 py-3 rounded-md {{ request()->routeIs('organizer-dashboard') ? 'active bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('events.index') }}"
                    class="nav-pill flex items-center px-4 py-3 rounded-md {{ request()->routeIs('events.index') ? 'active bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-calendar-alt w-5 text-center mr-3"></i>
                    <span>My Events</span>
                </a>
            </nav>
            <div class="pt-6 mt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 rounded-md text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
