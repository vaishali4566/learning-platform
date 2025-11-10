<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;
use Razorpay\Api\Api;
use App\Notifications\PurchaseSuccessMail;
use App\Http\Controllers\Web\TrainerController;

class PaymentController extends Controller
{
    public function stripe($courseId)
    {
        $course = Course::findOrFail($courseId);

        if (auth('trainer')->check()) {
            // Trainer view
            return view('trainer.payment.stripe', compact('course'));
        }

        // User view
        return view('user.payment.stripe', compact('course'));
    }


    /** 🔹 Handle Stripe payment */
   public function stripePost(Request $request)
{
    // Accept JSON or form data
    $stripeToken = $request->input('stripeToken');
    $courseId = $request->input('course_id');

    if (!$stripeToken || !$courseId) {
        return response()->json(['success' => false, 'message' => 'Invalid data.']);
    }

    $course = Course::find($courseId);
    if (!$course) {
        return response()->json(['success' => false, 'message' => 'Course not found.']);
    }

    try {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $charge = Charge::create([
            "amount" => $course->price * 100,
            "currency" => "inr",
            "source" => $stripeToken,
            "description" => "Payment for course: " . $course->title,
        ]);

        if (auth('trainer')->check()) {
            $buyer = auth('trainer')->user();
            $buyerType = 'trainer';
            $buyerId = $buyer->id;
            if ($course->trainer_id == $buyerId) {
                return response()->json(['success' => false, 'message' => 'You cannot buy your own course.']);
            }
            $paymentData = ['trainer_id' => $buyerId, 'buyer_type' => $buyerType];
        } else {
            $buyer = auth()->user();
            $buyerType = 'user';
            $buyerId = $buyer->id;
            $paymentData = ['user_id' => $buyerId, 'buyer_type' => $buyerType];
        }

        $payment = Payment::create(array_merge($paymentData, [
            'course_id' => $course->id,
            'transaction_id' => $charge->id,
            'amount' => $course->price,
            'currency' => $charge->currency,
            'payment_method' => 'stripe',
            'status' => $charge->status === 'succeeded' ? 'success' : 'failed',
            'receipt_url' => $charge->receipt_url ?? null,
        ]));

        if ($charge->status === 'succeeded') {
            $purchaseData = [
                'course_id' => $course->id,
                'payment_id' => $payment->id,
                'status' => 'completed',
                'progress' => 0,
            ];
            if ($buyerType === 'trainer') $purchaseData['trainer_id'] = $buyerId;
            else $purchaseData['user_id'] = $buyerId;

            Purchase::create($purchaseData);

           try {
                $buyer->notify(new PurchaseSuccessMail($buyer, $course));
            } catch (\Exception $e) {
                Log::warning("Mail not sent to buyer ID: {$buyer->id}, Type: " . get_class($buyer));
                
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'redirect_url' => $buyerType === 'trainer'
                    ? route('trainer.courses.my.purchases')
                    : route('user.courses.my')
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Payment failed.']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}



    /** 🔹 Show Razorpay checkout */
    public function razorpayCheckout($courseId)
    {
        $course = Course::findOrFail($courseId);
        $key = env('RAZORPAY_KEY');
        $amount = $course->price * 100; // in paise
        return view('user.payment.razorpay', compact('key', 'amount', 'course'));
    }

    /** 🔹 Handle Razorpay payment */
    public function razorpayPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $paymentData = $api->payment->fetch($request->razorpay_payment_id);
            $paymentData->capture(['amount' => $paymentData['amount']]);

            $course = Course::findOrFail($request->course_id);

            // 🔹 Detect buyer (user or trainer)
            if (auth('trainer')->check()) {
                $buyer = auth('trainer')->user();
                $buyerType = 'trainer';
                $buyerId = $buyer->id;

                // ❌ Prevent trainer from buying their own course
                if ($course->trainer_id == $buyerId) {
                    return back()->with('error', 'You cannot buy your own course.');
                }

                $buyerColumns = [
                    'trainer_id' => $buyerId,
                    'buyer_type' => $buyerType,
                ];
            } else {
                $buyer = Auth::user();
                $buyerType = 'user';
                $buyerId = $buyer->id;

                $buyerColumns = [
                    'user_id' => $buyerId,
                    'buyer_type' => $buyerType,
                ];
            }

            // ✅ Create payment record
            $payment = Payment::create(array_merge($buyerColumns, [
                'course_id' => $course->id,
                'transaction_id' => $paymentData['id'],
                'amount' => $paymentData['amount'] / 100,
                'currency' => $paymentData['currency'],
                'payment_method' => 'razorpay',
                'status' => 'success',
                'receipt_url' => null,
            ]));

            // ✅ Create purchase record
            $purchaseData = [
                'course_id' => $course->id,
                'payment_id' => $payment->id,
                'status' => 'completed',
                'progress' => 0.00,
            ];

            if ($buyerType === 'trainer') {
                $purchaseData['trainer_id'] = $buyerId;
            } else {
                $purchaseData['user_id'] = $buyerId;
            }

            Purchase::create($purchaseData);

            // ✅ Record trainer earning
            $trainerController = new TrainerController();
            $trainerController->addEarning($course->trainer_id, $course->id, $course->price, 'course sale');

            // ✅ Send confirmation mail
            $buyer->notify(new PurchaseSuccessMail($buyer, $course));

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Payment successful via Razorpay!');

        } catch (\Exception $e) {
            Log::error('Razorpay Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Razorpay payment failed: ' . $e->getMessage());
        }
    }

    /** 🔹 Payment method selection page */
    public function selectMethod($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('user.payment.select', compact('course'));
    }
}
