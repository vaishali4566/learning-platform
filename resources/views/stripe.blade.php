<!DOCTYPE html>
<html>
<head>
    <title>Laravel 12 Stripe Payment Gateway (INR)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style>
        #card-element {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px;
            background-color: #fff;
            height: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <h3 class="card-header p-3">Laravel 12 Stripe Payment Gateway (INR)</h3>
                <div class="card-body">

                    {{-- Display success message --}}
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="checkout-form" method="POST" action="{{ route('stripe.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label><strong>Name:</strong></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                        </div>

                        <div class="mb-3">
                            <label><strong>Card Information:</strong></label>
                            <div id="card-element"></div>
                        </div>

                        <input type="hidden" name="stripeToken" id="stripe-token-id">
                        <input type="hidden" name="amount" value="1000"> {{-- Amount in paisa (₹10) --}}

                        <button 
                            id="pay-btn"
                            type="button"
                            class="btn btn-success mt-3"
                            style="width: 100%; padding: 10px;"
                            onclick="createToken()"
                        >
                            PAY ₹10
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env("STRIPE_PUBLISHED_KEY") }}');

    var elements = stripe.elements();
    var cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#495057',
                '::placeholder': { color: '#6c757d' },
            },
            invalid: { color: '#fa755a' }
        }
    });
    cardElement.mount('#card-element');

    function createToken() {
        document.getElementById("pay-btn").disabled = true;

        stripe.createToken(cardElement).then(function(result) {
            if(result.error) {
                document.getElementById("pay-btn").disabled = false;
                alert(result.error.message);
            } else if(result.token) {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>

</body>
</html>
