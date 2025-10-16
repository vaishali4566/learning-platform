<!DOCTYPE html>
<html>
<head>
    <title>Course Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        .btn {
            background: #6772e5;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }
        #card-element { 
            border: 1px solid #ccc; 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="payment-card">
    <h2>{{ $course->title }}</h2>
    <p><strong>Price:</strong> ₹{{ $course->price }}</p>
    <p>{{ $course->description }}</p>

    <form action="{{ route('payment.post') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="stripeToken" id="stripeToken">

        <div id="card-element"></div>
        <button type="submit" class="btn">Pay ₹{{ $course->price }}</button>
    </form>
</div>

<script>
    // Stripe setup
    const stripe = Stripe("{{ env('STRIPE_PUBLISHED_KEY') }}");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    // Form submission
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(e){
        e.preventDefault();
        const { token, error } = await stripe.createToken(card);
        if(error){
            alert(error.message);
        } else {
            document.getElementById('stripeToken').value = token.id;
            form.submit();
        }
    });

    // SweetAlert on success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Payment Successful!',
            text: '{{ session("success") }}',
            timer: 10000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Go Home'
        }).then((result) => {
            window.location.href = '/';
        });
    @endif
</script>
</body>
</html>
        