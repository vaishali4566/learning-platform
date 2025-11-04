@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <h2 class="text-2xl font-semibold mb-4">Pay with Razorpay</h2>
    <button id="rzp-button1" class="bg-green-600 text-white px-4 py-2 rounded">
        Pay ₹{{ $amount / 100 }}
    </button>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ $key }}",
        "amount": "{{ $amount }}",
        "currency": "INR",
        "name": "Learning Platform",
        "description": "Course Purchase #{{ $courseId }}",
        "handler": function (response){
            fetch("{{ route('payment.razorpay.post') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id
                })
            }).then(() => {
                window.location.href = "{{ route('trainer.dashboard') }}";
            });
        }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
</script>
@endsection
