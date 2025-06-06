@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-fit mx-auto">
        <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md text-gray-800">
            <h1 class="text-2xl font-bold mb-6 text-center text-cyan-700">Booking Summary</h1>

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

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

            <button type="button" id="rzp-button1"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg w-full transition duration-200 mt-6">
                💳 Pay Now - ₹{{ number_format($booking->price, 0) }}
            </button>

            <div id="payment-loading" class="hidden mt-4 text-center">
                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150 cursor-not-allowed">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing Payment...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}", 
                "amount": "{{ $booking->price * 100 }}", 
                "currency": "INR",
                "name": "{{ env('APP_NAME') }}", 
                "description": "Booking Payment for {{ $booking->location }}",
                "order_id": "{{ $orderId }}", 
                "handler": function (response) {
                    // Show loading state
                    var paymentLoadingDiv = document.getElementById('payment-loading');
                    if (paymentLoadingDiv) {
                        paymentLoadingDiv.classList.remove('hidden');
                    }
                    var rzpButton = document.getElementById('rzp-button1');
                    if (rzpButton) {
                        rzpButton.disabled = true;
                    }
                    
                    // Create form and submit
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('razorpay.verify') }}";
                    
                    // Add CSRF token
                    var csrfTokenInput = document.createElement('input');
                    csrfTokenInput.type = 'hidden';
                    csrfTokenInput.name = '_token';
                    csrfTokenInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfTokenInput);
                    
                    // Add payment details
                    var paymentIdInput = document.createElement('input');
                    paymentIdInput.type = 'hidden';
                    paymentIdInput.name = 'razorpay_payment_id';
                    paymentIdInput.value = response.razorpay_payment_id;
                    form.appendChild(paymentIdInput);
                    
                    var orderIdInput = document.createElement('input');
                    orderIdInput.type = 'hidden';
                    orderIdInput.name = 'razorpay_order_id';
                    orderIdInput.value = response.razorpay_order_id;
                    form.appendChild(orderIdInput);
                    
                    var signatureInput = document.createElement('input');
                    signatureInput.type = 'hidden';
                    signatureInput.name = 'razorpay_signature';
                    signatureInput.value = response.razorpay_signature;
                    form.appendChild(signatureInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                "prefill": {
                    "name": "{{ $booking->guest_name }}", 
                    "contact": "{{ $booking->phone ?? '' }}"
                },
                "notes": {
                    "booking_slug": "{{ $booking->slug }}",
                    "location": "{{ $booking->location }}"
                },
                "theme": {
                    "color": "#DC2626"
                },
                "modal": {
                    "ondismiss": function() {
                        // Hide loading state if payment modal is dismissed
                        var paymentLoadingDiv = document.getElementById('payment-loading');
                        if (paymentLoadingDiv) {
                            paymentLoadingDiv.classList.add('hidden');
                        }
                        var rzpButton = document.getElementById('rzp-button1');
                        if (rzpButton) {
                            rzpButton.disabled = false;
                        }
                    }
                }
            };

            if (typeof Razorpay === 'undefined') {
                console.error('Razorpay SDK not loaded. Ensure checkout.js is included and not blocked.');
                alert('Payment gateway is currently unavailable. Please try again later or contact support.');
                return;
            }

            var rzp1 = new Razorpay(options);
            
            rzp1.on('payment.failed', function (response) {
                console.error('Razorpay payment.failed event:', response.error);
                alert('Payment failed: ' + response.error.description + (response.error.field ? ' (Field: ' + response.error.field + ')' : ''));
                // Redirect to failed page
                window.location.href = "{{ route('failed', $booking->slug) }}";
            });
            
            var rzpButton = document.getElementById('rzp-button1');
            if (rzpButton) {
                rzpButton.onclick = function(e) {
                    e.preventDefault(); // Prevent default button action first

                    if (!options.key) {
                        console.error('Razorpay key is missing from options.');
                        alert('Payment configuration error (key missing). Please contact support.');
                        return;
                    }
                    if (!options.order_id) {
                        console.error('Razorpay order_id is missing from options. Backend might not have generated or passed it.');
                        alert('Could not initiate payment: Order ID is missing. Please refresh and try again. If the problem persists, contact support.');
                        return;
                    }
                    
                    console.log('Attempting to open Razorpay checkout with options:', JSON.parse(JSON.stringify(options))); // Log a copy for inspection
                    rzp1.open();
                };
            } else {
                console.error('Razorpay button #rzp-button1 not found in the DOM.');
            }
        });
    </script>
    
    <script>
        // Prevent back button issues
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
@endpush