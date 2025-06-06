@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-20 p-8 bg-green-50 border border-green-300 rounded-xl shadow text-center">
        <h1 class="text-3xl font-bold text-green-700 mb-4">🎉 Payment Failed!</h1>
        <p class="text-lg text-gray-700">Retry Payment, <b>{{ $booking->guest_name }}</b>.</p>
        <p class="mt-2">Payment for <br /><strong>{{ $booking->location }}</strong> on <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</strong>.</p>
        <form action="/bookings/{{ $booking->slug }}/pay" method="POST" class="mt-6">
            @csrf
            <button type="submit" id="rzp-button1"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg w-full transition duration-200">
                💳 Pay Now
            </button>
        </form>
    </div>
@endsection
