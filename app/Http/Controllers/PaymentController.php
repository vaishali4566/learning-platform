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

class PaymentController extends Controller
{
    /** 🔹 Show Stripe checkout page */
    public function stripe($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('user.payment.stripe', compact('course'));
    }

    /** 🔹 Handle Stripe payment */
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

            Log::info("✅ Stripe charge created successfully", [
                'charge_id' => $charge->id,
                'status' => $charge->status
            ]);

            // ✅ Create payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'transaction_id' => $charge->id,
                'amount' => $course->price,
                'currency' => $charge->currency,
                'payment_method' => 'stripe',
                'status' => $charge->status === 'succeeded' ? 'success' : 'failed',
                'receipt_url' => $charge->receipt_url ?? null,
            ]);

            Log::info("✅ Payment record created", ['payment_id' => $payment->id]);

            // ✅ Create purchase record only if payment succeeds
            if ($charge->status === 'succeeded') {
                $purchase = Purchase::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course->id,
                    'payment_id' => $payment->id,
                    'status' => 'completed',
                    'progress' => 0.00,
                ]);

                Log::info("✅ Purchase created successfully", ['purchase_id' => $purchase->id]);

                // ✅ Send confirmation mail
                Auth::user()->notify(new PurchaseSuccessMail(Auth::user(), $course));

                Log::info("📧 Purchase success mail sent to " . Auth::user()->email);

                // ✅ Redirect to purchases page with success message
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('user.purchases'),
                    'message' => 'Payment successful 🎉',
                ]);

            } else {
                Log::warning("⚠ Stripe charge failed", ['charge_status' => $charge->status]);
                return back()->with('error', 'Payment failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('❌ Stripe Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }


    /** 🔹 Show Razorpay checkout */
    public function razorpayCheckout($courseId)
    {
        $course = Course::findOrFail($courseId);
        $key = env('RAZORPAY_KEY');
        $amount = $course->price * 100; // paise
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

            // ✅ Create payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'transaction_id' => $paymentData['id'],
                'amount' => $paymentData['amount'] / 100,
                'currency' => $paymentData['currency'],
                'payment_method' => 'razorpay',
                'status' => 'success',
                'receipt_url' => null,
            ]);

            // ✅ Create purchase record
            Purchase::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'payment_id' => $payment->id,
                'status' => 'completed',
                'progress' => 0.00,
            ]);

            // ✅ Send confirmation mail
            Auth::user()->notify(new PurchaseSuccessMail(Auth::user(), $course));

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
