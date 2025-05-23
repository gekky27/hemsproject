@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="container mx-auto px-4 mb-12 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Featured Events</h2>
            <div class="hidden md:flex space-x-2">
                <button id="prev-featured"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chevron-left text-gray-600"></i>
                </button>
                <button id="next-featured"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chevron-right text-gray-600"></i>
                </button>
            </div>
        </div>

        <div class="relative rounded-2xl overflow-hidden shadow-xl">
            @if (count($featuredEvents) > 0)
                <div id="featured-carousel" class="relative h-96 md:h-[28rem]">
                    @foreach ($featuredEvents as $index => $event)
                        <div class="featured-slide absolute inset-0 transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                            data-index="{{ $index }}">
                            <img src="{{ $event->cover_image == 'default.jpg' ? 'https://placehold.co/1200x500/444/fff?text=' . $event->name . '' : asset('storage/' . $event->cover_image) }}"
                                alt="{{ $event->name }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent flex flex-col justify-end p-6 md:p-10">
                                <div class="max-w-3xl">
                                    <span
                                        class="inline-block bg-blue-600 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4 tracking-wide">FEATURED</span>
                                    <h3 class="text-white text-3xl md:text-4xl font-bold mb-3 leading-tight">
                                        {{ $event->name }}</h3>

                                    <div class="flex flex-wrap items-center text-white/90 text-sm mb-5 gap-y-2">
                                        <div class="flex items-center mr-6">
                                            <i class="far fa-calendar-alt mr-2 text-blue-400"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                                        </div>

                                        <div class="flex items-center mr-6">
                                            <i class="far fa-clock mr-2 text-blue-400"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                                        </div>

                                        <div class="flex items-center mr-6">
                                            <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                                            <span>{{ $event->venue ? $event->venue->name : 'Venue TBA' }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <i class="fas fa-user-tie mr-2 text-blue-400"></i>
                                            <span>{{ $event->organizer ? $event->organizer->name : 'By: TBA' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-white/90 mb-8 hidden md:block text-base max-w-2xl leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit($event->description, 150) }}
                                    </p>

                                    <div class="flex items-center flex-wrap gap-4">
                                        <a href="{{ route('detail-event', $event->slug) }}"
                                            class="bg-blue-600 hover:bg-blue-700 transition-colors text-white px-8 py-3 rounded-lg font-medium flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                                            Buy Tickets
                                            <i class="fas fa-arrow-right ml-3"></i>
                                        </a>
                                        <span class="text-white font-bold text-xl">
                                            Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute top-6 right-6 bg-black/60 text-white text-sm font-medium px-3 py-1.5 rounded-full">
                                {{ $index + 1 }}/{{ count($featuredEvents) }}
                            </div>
                        </div>
                    @endforeach

                    <button id="mobile-prev-featured"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 transition-colors text-white w-12 h-12 rounded-full flex items-center justify-center focus:outline-none shadow-lg md:hidden z-10">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <button id="mobile-next-featured"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-black/80 transition-colors text-white w-12 h-12 rounded-full flex items-center justify-center focus:outline-none shadow-lg md:hidden z-10">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div id="featured-pagination" class="absolute bottom-8 left-0 right-0 flex justify-center space-x-3 z-10">
                    @foreach ($featuredEvents as $index => $event)
                        <button
                            class="pagination-dot h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-10 bg-white' : 'w-3 bg-white/40 hover:bg-white/70' }}"
                            data-index="{{ $index }}"></button>
                    @endforeach
                </div>
            @else
                <div class="relative h-96 rounded-xl bg-gray-100 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="text-gray-300 text-6xl mb-6">
                            <i class="far fa-calendar-xmark"></i>
                        </div>
                        <h3 class="text-2xl font-medium text-gray-700">No Featured Events Available</h3>
                        <p class="text-gray-500 mt-3">Check back soon for upcoming featured events</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-6">Upcoming Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <div
                    class="border border-gray-200 rounded-xl overflow-hidden relative hover:shadow-lg transition-shadow group {{ $event->status === 'soldout' ? 'opacity-80' : '' }}">
                    @if ($event->status === 'soldout')
                        <div class="absolute inset-0 bg-gray-900/50 z-10 flex items-center justify-center">
                            <div
                                class="bg-red-600 text-white px-6 py-2 font-bold text-xl uppercase rounded-lg transform -rotate-12 shadow-lg">
                                Sold Out
                            </div>
                        </div>
                    @endif

                    <img src="{{ $event->cover_image == 'default.jpg' ? 'https://placehold.co/400x200/777/fff?text=' . $event->name . '' : asset('storage/' . $event->cover_image) }}"
                        alt="{{ $event->name }}"
                        class="w-full h-48 object-cover {{ $event->status !== 'soldout' ? 'group-hover:scale-105 transition-transform duration-500' : '' }}">

                    @php
                        $venue = DB::table('venues')->where('id', $event->venues_id)->first();
                        $organizer = DB::table('organizers')->where('id', $event->organizers_id)->first();
                    @endphp

                    <div class="p-5">
                        <h3 class="font-bold text-lg">{{ $event->name }}</h3>

                        <div class="flex flex-wrap items-center text-gray-600 text-sm mt-2 gap-y-1">
                            <div class="flex items-center w-full">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500 w-4 text-center"></i>
                                <span>{{ $venue ? $venue->name : 'Venue not found' }}</span>
                            </div>

                            <div class="flex items-center w-full">
                                <i class="far fa-calendar-alt mr-2 text-blue-500 w-4 text-center"></i>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                            </div>

                            <div class="flex items-center w-full">
                                <i class="far fa-clock mr-2 text-blue-500 w-4 text-center"></i>
                                <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                            </div>

                            <div class="flex items-center w-full">
                                <i class="fas fa-user-tie mr-2 text-blue-500 w-4 text-center"></i>
                                <span>{{ $organizer ? $organizer->name : 'Organizer not found' }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <span class="font-bold">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</span>

                            @if ($event->status === 'soldout')
                                <span class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg cursor-not-allowed">
                                    Sold Out
                                </span>
                            @else
                                <a href="{{ route('detail-event', $event->slug) }}"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Buy Tickets
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center">
                    <div class="text-gray-300 text-5xl mb-4">
                        <i class="far fa-calendar-xmark"></i>
                    </div>
                    <p class="text-gray-500 text-lg">No upcoming events found.</p>
                    <p class="text-gray-400 mt-2">Check back soon for new events.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('featured-carousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.featured-slide');
            const dots = document.querySelectorAll('#featured-pagination .pagination-dot');
            const totalSlides = slides.length;
            let currentSlide = 0;
            let timer;

            function goToSlide(index) {
                slides.forEach(slide => {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0', 'pointer-events-none');
                });

                slides[index].classList.remove('opacity-0', 'pointer-events-none');
                slides[index].classList.add('opacity-100');
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.remove('w-3', 'bg-white/40');
                        dot.classList.add('w-10', 'bg-white');
                    } else {
                        dot.classList.remove('w-10', 'bg-white');
                        dot.classList.add('w-3', 'bg-white/40');
                    }
                });

                currentSlide = index;
            }

            function nextSlide() {
                const next = (currentSlide + 1) % totalSlides;
                goToSlide(next);
            }

            function prevSlide() {
                const prev = (currentSlide - 1 + totalSlides) % totalSlides;
                goToSlide(prev);
            }

            function startAutoplay() {
                timer = setInterval(nextSlide, 5000);
            }

            function stopAutoplay() {
                clearInterval(timer);
            }

            document.getElementById('next-featured')?.addEventListener('click', function() {
                stopAutoplay();
                nextSlide();
                startAutoplay();
            });

            document.getElementById('prev-featured')?.addEventListener('click', function() {
                stopAutoplay();
                prevSlide();
                startAutoplay();
            });

            document.getElementById('mobile-next-featured')?.addEventListener('click', function() {
                stopAutoplay();
                nextSlide();
                startAutoplay();
            });

            document.getElementById('mobile-prev-featured')?.addEventListener('click', function() {
                stopAutoplay();
                prevSlide();
                startAutoplay();
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    stopAutoplay();
                    goToSlide(index);
                    startAutoplay();
                });
            });

            carousel.addEventListener('mouseenter', stopAutoplay);
            carousel.addEventListener('mouseleave', startAutoplay);
            startAutoplay();
        });
    </script>
@endpush
