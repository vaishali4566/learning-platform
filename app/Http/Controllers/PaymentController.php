<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    // Show Stripe payment page
    public function stripe($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('stripe', compact('course'));
    }

    // Handle Stripe payment submission
    public function stripePost(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required|string',
            'course_id'   => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Create charge
            $charge = Charge::create([
                "amount" => $course->price * 100, // Stripe works in paise
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Payment for course: " . $course->title,
            ]);

            // Store payment details
            Payment::create([
                'user_id' => Auth::id() ?? 1, // Replace with logged-in user or test id
                'course_id' => $course->id,
                'transaction_id' => $charge->id,
                'amount' => $course->price,
                'currency' => $charge->currency,
                'payment_method' => 'stripe',
                'status' => $charge->status === 'succeeded' ? 'success' : 'failed',
                'receipt_url' => $charge->receipt_url ?? null,
            ]);

            return redirect()
                ->route('payment.stripe', $course->id)
                ->with('success', 'Payment successful for ' . $course->title . '!');
        } catch (\Exception $e) {
            Log::error('Stripe Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}
