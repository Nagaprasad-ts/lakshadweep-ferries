@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-20 p-8 bg-green-50 border border-green-300 rounded-xl shadow text-center">
        <h1 class="text-3xl font-bold text-green-700 mb-4">🎉 Payment Successful!</h1>
        <p class="text-lg text-gray-700">Thank you for your booking, <b>{{ $booking->guest_name }}</b>.</p>
        <p class="mt-2">We’ve received your payment for <br /><strong>{{ $booking->location }}</strong> on <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</strong>.</p>
        <p class="mt-6 text-sm text-gray-500">You’ll receive a confirmation shortly.</p>
    </div>
@endsection
