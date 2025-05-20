<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Session;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Retrieve form data
        $cardNumber = $request->cardNumber;
        $cardHolder = $request->cardHolder;
        $expiryDate = $request->cardExpiry;
        $cvv = $request->cardCVV;
        $amountPayment = $request->amountPayment*100;

        // Validate the form data
        $validatedData = $request->validate([
            'cardNumber' => 'required|numeric',
            'cardHolder' => 'required|string',
            'cardExpiry' => 'required|string',
            'cardCVV' => 'required|numeric',
        ]);

        // Set up Stripe API keys
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a Stripe charge
            $charge = Charge::create([
                'amount' => $amountPayment,
                'currency' => 'usd',
                'description' => 'Payment Data',
                'source' => $request->input('stripeToken'),
            ]);

            // Check if the payment was successful
            if ($charge->status === 'succeeded') {
                  $items=session('items');
                  $email=session('clientemail');
                  $c=Client::where('email','=',$email)->first();

                  // $total_amount=$amountPayment;
                  $paymentMethod="Paid using Card";
                  $date=$request->date_2;
                  $dt=now()->addDays($date);
                  $cart=new Cart();
                  $cart->clientId =$c->id;
                  $cart->items = json_encode($items);
                  $cart->status = 'Registered';
                  $cart->total_amount=$amountPayment/100;
                  $cart->pref_address=$request->addressClient;
                  $cart->payment_method=$paymentMethod;
                  $cart->expecting_delivery_date=$dt;
                  $cart->save();
                  session()->pull('items');

                  foreach($items as $key => $item) {
                    $id=$item['id'];
                    $quantity=$item['quantity'];
                    $price=$item['price'];
                    $p=Product::find($id);
                    // $price=$p->price;
                    // $prom=$p->promotion;

                    if($p->our_stock<$quantity)
                    {
                        $st=$p->our_stock;
                        $requestedQty=$quantity-$st;
                        $order=new Order();
                        $order->productId=$id;
                        $order->cartId=$cart->id;
                        $order->clientId=$c->id;
                        $order->userId=$p->userId;
                        $order->quantity=$requestedQty;
                        // if($prom > 0){
                        //   $newpr=$price-(($prom/100)*$price);
                        //   $order->total_price=$newpr*$quantity;
                        // }
                        // else{
                        $order->total_price=$price*$requestedQty;
                        // }
                        $order->status='pending';
                        $order->visible='yes';
                        $order->expecting_delivery_date=$dt;
                        $order->save();

                        $order2=new Order();
                        $order2->productId=$id;
                        $order2->cartId=$cart->id;
                        $order2->clientId=$c->id;
                        $order2->userId=$p->userId;
                        $order2->quantity=$st;
                        // if($prom > 0){
                        //   $newpr=$price-(($prom/100)*$price);
                        //   $order->total_price=$newpr*$quantity;
                        // }
                        // else{
                        $order2->total_price=$price*$st;
                        // }
                        $order2->status='Delivered';
                        $order2->visible='no';
                        $order2->expecting_delivery_date= now()->format('Y-m-d');
                        $order2->save();


                    }
                    elseif($p->our_stock >= $quantity){
                      $dt=now()->format('Y-m-d');
                      $order=new Order();
                      $order->productId=$id;
                      $order->cartId=$cart->id;
                      $order->clientId=$c->id;
                      $order->userId=$p->userId;
                      $order->quantity=$quantity;
                      // if($prom > 0){
                      //   $newpr=$price-(($prom/100)*$price);
                      //   $order->total_price=$newpr*$quantity;
                      // }
                      // else{
                      $order->total_price=$price*$quantity;
                      // }
                      $order->status='Delivered';
                      $order->visible='no';
                      $order->expecting_delivery_date=$dt;
                      $order->save();
                    }
                  }


                  session()->put('items', [    ['id' => '0', 'quantity' => 0, 'price' => 0],  ]);
                  $items = session('items');
                  foreach($items as $key => $item) {
                      if ($item['id'] === '0') {
                          $index = $key;
                          break;
                      }
                  }

                  session()->forget('items.'.$index);
                  Session::save();
                // Payment succeeded
                return redirect()->route('cart')->with('paymentSuccess','Payment succeeded!');
                // return view('payment.success');
            } else {
                // Payment failed
                return redirect()->route('cart')->with('paymentFail','Payment failed!');
                // return view('payment.failure')->with('error', 'Payment failed.');
            }
        } catch (Exception $e) {
            // An error occurred during payment processing
            return redirect()->route('cart')->with('paymentError', $e->getMessage());
            // return view('payment.failure')->with();
        }
    }
}
