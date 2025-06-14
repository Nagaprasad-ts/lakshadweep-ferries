<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log; // Only needed imports

class BookingController extends Controller
{
    // Show booking and initialize Razorpay order if needed
    public function show($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();

        if (!$booking->is_active || $booking->is_paid) {
            return view('bookings.expired');
        }

        // Generate Razorpay Order only if not already created
        if (!$booking->razorpay_order_id) {
            try {
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                $razorpayOrder = $api->order->create([
                    'receipt' => $booking->slug . '_' . time(),
                    'amount' => (int) ($booking->price * 0.3 * 100), // in paise
                    'currency' => 'INR',
                    'notes' => [
                        'booking_slug' => $booking->slug,
                        'guest_name' => $booking->guest_name
                    ]
                ]);
                $booking->razorpay_order_id = $razorpayOrder['id'];
                $booking->save();
            } catch (\Exception $e) {
                return back()->with('error', 'Unable to create payment order. Please try again.');
            }
        }
        $orderId = $booking->razorpay_order_id;
        return view('bookings.show', compact('booking', 'orderId'));
    }

    // Handle Razorpay payment verification and redirect accordingly
    public function verifyPayment(Request $request)
    {
        $signature = $request->razorpay_signature;
        $paymentId = $request->razorpay_payment_id;
        $orderId = $request->razorpay_order_id;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Find booking by Razorpay order ID
            $booking = Booking::where('razorpay_order_id', $orderId)->first();

            // If booking not found
            if (!$booking) {
                return redirect()->route('home')->with('error', 'Booking not found.');
            }

            // If already paid and inactive, prevent double processing
            if ($booking->is_paid && !$booking->is_active) {
                return redirect('/page-expired');
            }

            // Verify Razorpay signature
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];
            $api->utility->verifyPaymentSignature($attributes);

            // Update booking as paid and inactive
            $booking->update([
                'is_paid' => true,
                'is_active' => false,
                'razorpay_payment_id' => $paymentId,
                'payment_status' => 'completed'
            ]);

            return redirect()->route('thank-you', $booking->slug);

        } catch (\Exception $e) {

            $booking = Booking::where('razorpay_order_id', $orderId)->first();
            if ($booking) {
                return redirect()->route('failed', $booking->slug)->with('error', 'Payment verification failed.');
            }
            return redirect()->route('home')->with('error', 'Payment failed and booking not found.');
        }
    }

    // Show thank you page only for paid bookings
    public function thankYou($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();
        if (!$booking->is_paid) {
            return redirect()->route('booking', $slug);
        }
        return view('bookings.thank-you', compact('booking'));
    }

    // Show failed page for unsuccessful payments
    public function failed($slug)
    {
        $booking = Booking::where('slug', $slug)->firstOrFail();

        if (!$booking->is_active || $booking->is_paid) {
            return view('bookings.expired');
        }
        
        $orderId = $booking->razorpay_order_id;
        return view('bookings.failed', compact('booking', 'orderId'));
    }
}