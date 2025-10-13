<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function stripe(): View
    {
        return view('stripe');
    }

    public function stripePost(Request $request): RedirectResponse
    {
        $request->validate([
            'stripeToken' => 'required|string',
            'name' => 'required|string',
            'amount' => 'required|integer'
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        Charge::create([
            "amount" => $request->amount, // Amount in paisa
            "currency" => "inr",          // INR currency
            "source" => $request->stripeToken,
            "description" => "Payment from Laravel 12"
        ]);

        return back()->with('success', 'Payment successful!');
    }
}
