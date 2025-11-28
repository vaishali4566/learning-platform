<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Course Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0A0E19, #0E1426, #141C33);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            color: #E6EDF7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-card {
            width: 400px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0, 194, 255, 0.1);
            padding: 32px;
            text-align: center;
            animation: fadeSlideUp 0.8s ease-out;
        }

        .course-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #00C2FF;
            margin-bottom: 8px;
        }

        .course-desc {
            color: #B0B8C5;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        #card-element {
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            margin-bottom: 16px;
        }

        .btn {
            width: 100%;
            padding: 10px 0;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            background: linear-gradient(to right, #2F82DB, #00C2FF);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 12px rgba(0, 194, 255, 0.2);
        }

        .btn:hover {
            transform: scale(1.03);
            box-shadow: 0 0 18px rgba(0, 194, 255, 0.35);
        }

        /* Loader Overlay */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 14, 25, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(6px);
        }

        .loader {
            border: 3px solid rgba(255, 255, 255, 0.15);
            border-top: 3px solid #00C2FF;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loader-text {
            color: #E6EDF7;
            margin-top: 12px;
            font-size: 0.95rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="payment-card">
        <h2 class="course-title">{{ $course->title }}</h2>
        <p class="text-sm text-gray-300 mb-1"><strong>Price:</strong> ₹{{ $course->price }}</p>
        <p class="course-desc">{{ $course->description }}</p>

        <form action="{{ route('trainer.payment.post') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="stripeToken" id="stripeToken">

            <div id="card-element"></div>
            <button type="submit" class="btn mt-2">Pay ₹{{ $course->price }}</button>
        </form>
    </div>

    <!-- Loader -->
    <div id="loader-overlay">
        <div class="flex flex-col items-center">
            <div class="loader"></div>
            <p class="loader-text mt-3">Processing your payment...</p>
        </div>
    </div>

    <script>
        const stripe = Stripe("{{ env('STRIPE_PUBLISHED_KEY') }}");
        const elements = stripe.elements();
        const card = elements.create('card', {
            style: {
                base: {
                    color: '#E6EDF7',
                    fontFamily: 'Inter, sans-serif',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#8E9BB2'
                    },
                },
                invalid: {
                    color: '#FF6B6B',
                }
            }
        });
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const loader = document.getElementById('loader-overlay');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            loader.style.display = 'flex'; // Show loader

            const { token, error } = await stripe.createToken(card);

            if (error) {
                loader.style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: error.message,
                    confirmButtonColor: '#00C2FF'
                });
                return;
            }

            try {
                const response = await fetch("{{ route('trainer.payment.post') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        stripeToken: token.id,
                        course_id: "{{ $course->id }}"
                    })
                });

                const result = await response.json();
                loader.style.display = 'none'; // Hide loader

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful',
                        text: result.message,
                        confirmButtonText: 'Go to My Courses',
                        confirmButtonColor: '#00C2FF'
                    }).then(() => {
                        window.location.href = result.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed',
                        text: result.message || 'Something went wrong.',
                        confirmButtonColor: '#00C2FF'
                    });
                }

            } catch (err) {
                loader.style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message,
                    confirmButtonColor: '#00C2FF'
                });
            }
        });
    </script>
</body>

</html>
