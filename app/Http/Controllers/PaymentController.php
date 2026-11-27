<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Session;
use App\Models\Client;
use App\Services\CartCheckout;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Validate the form data
        $request->validate([
            'cardNumber' => 'required|numeric',
            'cardHolder' => 'required|string',
            'cardExpiry' => 'required|string',
            'cardCVV' => 'required|numeric',
        ]);

        $c = Client::where('email', '=', session('clientemail'))->first();
        if (!$c) {
            return redirect()->route('cart')->with('paymentFail', 'Sign in first!');
        }

        // The amount charged is computed from the product table, never taken from the form.
        $checkout = new CartCheckout(session('items'));
        if ($checkout->isEmpty()) {
            return redirect()->route('cart')->with('paymentFail', 'Your cart is empty.');
        }

        // Set up Stripe API keys
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a Stripe charge
            $charge = Charge::create([
                'amount' => $checkout->totalInCents(),
                'currency' => 'usd',
                'description' => 'Payment Data',
                'source' => $request->input('stripeToken'),
            ]);

            if ($charge->status !== 'succeeded') {
                return redirect()->route('cart')->with('paymentFail', 'Payment failed!');
            }

            $dt = now()->addDays((int) $request->date_2);
            $checkout->place($c, 'Paid using Card', $request->addressClient, $dt);

            session()->put('items', []);
            Session::save();

            return redirect()->route('cart')->with('paymentSuccess', 'Payment succeeded!');
        } catch (\Exception $e) {
            // An error occurred during payment processing
            return redirect()->route('cart')->with('paymentError', $e->getMessage());
        }
    }
}
