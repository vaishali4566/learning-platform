<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    // Show Stripe payment page with course info
    public function stripe($courseId)
    {
        $course = Course::findOrFail($courseId);

        return view('stripe', compact('course'));
    }

    // Handle payment form submission
    public function stripePost(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required|string',
            'course_id'   => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            Charge::create([
                "amount" => $course->price * 100, // convert to paise
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Payment for course: " . $course->title,
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
    