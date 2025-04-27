@extends('layouts.app')

@section('content')
    <div>
        {{-- Main Content --}}
        <main class="">
            {{-- Hero --}}
            <section class="text-center py-16 w-full mx-auto bg-cover bg-center relative" style="background-image: url('images/ferry_banner.png');">
                <!-- Dark overlay to make the text more readable -->
                <div class="absolute inset-0 bg-black opacity-40"></div>
                
                <div class="relative z-10 flex flex-col gap-y-3 md:gap-y-8 justify-start items-start ml-7">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 shadow-lg">
                        Sail Through Paradise
                    </h2>
                    <p class="text-base sm:text-lg text-white mb-6 text-left max-w-md">
                        Explore the beautiful islands of Lakshadweep with our fast and reliable ferry booking service.
                    </p>
                    <a href="{{ route('home') }}"
                       class="inline-block bg-cyan-700 hover:bg-cyan-800 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:scale-105">
                        Book a Ferry
                    </a>
                </div>
            </section>
            
            

            {{-- Popular Destinations --}}
            <section class="py-12">
                <h3 class="text-3xl font-bold text-gray-800 text-center mb-8">Popular Destinations</h3>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
                    @php
                        $islands = [
                            ['name' => 'Agatti Island', 'desc' => 'Crystal clear lagoons & coral reefs', 'image' => asset('images/Agatti_Island.png')],
                            ['name' => 'Bangaram Island', 'desc' => 'Peaceful vibes & white sand beaches', 'image' => asset('images/Agatti_Island.png')],
                            ['name' => 'Kadmat Island', 'desc' => 'Great for scuba diving & kayaking', 'image' => asset('images/Agatti_Island.png')],
                            ['name' => 'Minicoy Island', 'desc' => 'Tuna fishing & iconic lighthouse', 'image' => asset('images/Agatti_Island.png')],
                        ];
                    @endphp
                    @foreach ($islands as $island)
                        <div class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition">
                            <img src="{{ $island['image'] }}" alt="{{ $island['name'] }}" class="rounded-xl mb-4" />
                            <h4 class="text-lg font-bold text-cyan-700 mb-2">{{ $island['name'] }}</h4>
                            <p class="text-gray-600 text-sm md:text-md">{{ $island['desc'] }}</p>
                        </div>
                    @endforeach
                </div>                
            </section>

            {{-- How it works --}}
            <section class="py-12 text-center max-w-5xl mx-auto">
                <h3 class="text-3xl font-bold text-gray-800 mb-8">How it Works</h3>
                <div class="grid gap-8 md:grid-cols-3 text-gray-700">
                    <div>
                        <div class="text-cyan-700 text-4xl mb-2">📅</div>
                        <h4 class="font-semibold mb-1">Choose a Date</h4>
                        <p>Pick your travel date and destination easily.</p>
                    </div>
                    <div>
                        <div class="text-cyan-700 text-4xl mb-2">🛳️</div>
                        <h4 class="font-semibold mb-1">Select a Ferry</h4>
                        <p>Browse available ferries with schedules.</p>
                    </div>
                    <div>
                        <div class="text-cyan-700 text-4xl mb-2">💳</div>
                        <h4 class="font-semibold mb-1">Confirm Booking</h4>
                        <p>Pay securely and receive instant confirmation.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection
