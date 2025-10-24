<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;
use App\Notifications\PurchaseSuccessMail;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    // ✅ Stripe checkout page
    public function stripe($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('user.payment.stripe', compact('course'));
    }

    // ✅ Stripe payment post
    public function stripePost(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $charge = Charge::create([
                "amount" => $course->price * 100,
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Payment for course: " . $course->title,
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'transaction_id' => $charge->id,
                'amount' => $course->price,
                'currency' => $charge->currency,
                'payment_method' => 'stripe',
                'status' => $charge->status === 'succeeded' ? 'success' : 'failed',
                'receipt_url' => $charge->receipt_url ?? null,
            ]);

            if ($charge->status === 'succeeded' && Auth::check()) {
                Auth::user()->notify(new PurchaseSuccessMail(Auth::user(), $course));
            }

            return redirect()->route('payment.stripe', $course->id)
                             ->with('success', 'Payment successful for ' . $course->title . '!');
        } catch (\Exception $e) {
            Log::error('Stripe Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // 🔹 Razorpay checkout page
    public function razorpayCheckout($courseId)
    {
        $course = Course::findOrFail($courseId);
        $key = env('RAZORPAY_KEY');
        $amount = $course->price * 100; // paise
        return view('user.payment.razorpay', compact('key', 'amount', 'course'));
    }

    // 🔹 Razorpay payment capture
    public function razorpayPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            $payment->capture(['amount' => $payment['amount']]);

            $course = Course::findOrFail($request->course_id);

            Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'transaction_id' => $payment['id'],
                'amount' => $payment['amount'] / 100,
                'currency' => $payment['currency'],
                'payment_method' => 'razorpay',
                'status' => 'success',
                'receipt_url' => null,
            ]);

            if (Auth::check()) {
                Auth::user()->notify(new PurchaseSuccessMail(Auth::user(), $course));
            }

            return redirect()->route('user.dashboard')
                             ->with('success', 'Payment successful via Razorpay!');
        } catch (\Exception $e) {
            Log::error('Razorpay Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Razorpay payment failed: ' . $e->getMessage());
        }
    }

    // 🔹 Payment method selection
    public function selectMethod($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('user.payment.select', compact('course'));
    }
}
