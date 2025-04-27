@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-fit mx-auto">
        <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md text-gray-800">
            <h1 class="text-2xl font-bold mb-6 text-center text-cyan-700">Booking Summary</h1>

            <div class="space-y-2 mb-6">
                <p><strong>Guest Name:</strong> {{ $booking->guest_name }}</p>
                <p><strong>Location:</strong> {{ $booking->location }}</p>
                <p><strong>Booking Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</p>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-x-5">
                <div class="flex justify-between py-2">
                    <span>{{$booking->Adults}} Adult{{ $booking->Adults > 1 ? 's' : '' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>{{ $booking->Children }} Children{{ $booking->Children > 1 ? 's' : '' }} [6–12]</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>{{ $booking->Kids }} Kid{{ $booking->Kids > 1 ? 's' : '' }} [3–5]</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>{{ $booking->Infants }} Infant{{ $booking->Infants > 1 ? 's' : '' }} [0–2]</span>
                </div>
            </div>
            <div class="border-t mt-3 pt-3 flex justify-between font-bold text-lg">
                <span>Total Fee</span>
                <span>₹{{ number_format($booking->price, 0) }}</span>
            </div>

            <form action="/bookings/{{ $booking->slug }}/pay" method="POST" class="mt-6">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg w-full transition duration-200">
                    💳 Pay Now
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
@endpush
