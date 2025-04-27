<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();

        if (!$booking->is_active || $booking->is_paid) {
            return view('bookings.expired');
        }

        return view('bookings.show', compact('booking'));
    }

    public function pay($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();

        if (!$booking->is_active || $booking->is_paid) {
            return redirect('/bookings/'.$slug)->with('error', 'This link is no longer active.');
        }

        // Integrate Razorpay or PayU here

        // For now, simulate payment success:
        $booking->update([
            'is_paid' => true,
            'is_active' => false
        ]);

        return redirect('/thank-you/' . $slug);
    }

    public function thankYou($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();

        return view('thank-you', compact('booking'));
    }
}

