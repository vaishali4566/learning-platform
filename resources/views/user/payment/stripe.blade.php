<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Payment | {{ $course->title }}</title>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .payment-card {
            width: 420px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0, 194, 255, 0.15);
            padding: 36px;
            text-align: center;
            animation: fadeSlideUp 0.8s ease-out;
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #card-element {
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.05);
            margin-bottom: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(to right, #2563EB, #00C2FF);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 194, 255, 0.25);
        }

        .btn:hover {
            transform: scale(1.04);
            box-shadow: 0 0 22px rgba(0, 194, 255, 0.4);
        }

        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: none;
            background: rgba(10, 14, 25, 0.85);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .spinner {
            border: 5px solid rgba(255, 255, 255, 0.1);
            border-top: 5px solid #00C2FF;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div id="loader" class="loader-overlay">
        <div class="spinner"></div>
    </div>

    <div class="payment-card">
        <h2 class="course-title">{{ $course->title }}</h2>
        <p class="text-sm text-gray-300 mb-1"><strong>Price:</strong> ₹{{ $course->price }}</p>
        <p class="course-desc">{{ Str::limit($course->description, 100) }}</p>

        <form action="{{ route('payment.post') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="stripeToken" id="stripeToken">
            <div id="card-element"></div>
            <button type="submit" class="btn mt-3">Pay ₹{{ $course->price }}</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    color: '#FF6B6B'
                }
            }
        });
        card.mount('#card-element');

        const loader = document.getElementById('loader');
        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            loader.style.display = 'flex';

            const {
                token,
                error
            } = await stripe.createToken(card);

            if (error) {
                loader.style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed',
                    text: error.message,
                    confirmButtonColor: '#2563EB',
                    background: '#0A0E19',
                    color: '#fff'
                });
                return;
            }

            const formData = new FormData(form);
            formData.set('stripeToken', token.id);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData,
                })
                .then(async response => {
                    loader.style.display = 'none';
                    if (response.ok) {
                        // ✅ Show success popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful!',
                            text: 'You have successfully purchased the course. Redirecting...',
                            showConfirmButton: false,
                            timer: 5000,
                            background: '#0A0E19',
                            color: '#fff',
                            didOpen: () => {
                                const content = Swal.getHtmlContainer();
                                if (content) {
                                    const interval = setInterval(() => {
                                        Swal.update({
                                            title: 'Redirecting to your dashboard...',
                                        });
                                    }, 1000);
                                    setTimeout(() => clearInterval(interval), 5000);
                                }
                            }
                        });

                        // ⏳ Redirect after 5 seconds
                        setTimeout(() => {
                            window.location.href = "{{ route('user.courses.index') }}";
                        }, 5000);
                    } else {
                        const data = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Error',
                            text: data.message || 'Something went wrong!',
                            confirmButtonColor: '#2563EB',
                            background: '#0A0E19',
                            color: '#fff'
                        });
                    }
                })
                .catch(err => {
                    loader.style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: err.message,
                        confirmButtonColor: '#2563EB',
                        background: '#0A0E19',
                        color: '#fff'
                    });
                });
        });
    </script>

</body>

</html> 