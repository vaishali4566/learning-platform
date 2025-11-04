@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Choose Payment Method</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form method="GET" id="paymentForm">
            <input type="hidden" name="courseId" value="{{ $courseId }}">
            
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="radio" name="gateway" value="stripe" checked>
                    <span class="ml-2">Stripe</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="gateway" value="razorpay">
                    <span class="ml-2">Razorpay</span>
                </label>
            </div>

            <button type="submit" class="mt-5 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Continue
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('paymentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const gateway = document.querySelector('input[name="gateway"]:checked').value;
        const courseId = '{{ $courseId }}';
        if (gateway === 'stripe') {
            window.location.href = `/user/payment/${courseId}/stripe`;
        } else {
            window.location.href = `/user/payment/${courseId}/razorpay`;
        }
    });
</script>
@endsection
