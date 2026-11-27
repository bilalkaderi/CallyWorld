<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Hash;
use Session;
use Mail;
use DB;

use App\Models\DeliveryMan;
use App\Models\Cart;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{

  public function index()
  {
    if(session()->has('deliveryman')){
      return redirect()->route('deliveryprofile')->withSuccess('Signed in');
  }
  else{
    return view ('delivery/login');
  }
}

  public function deliveryprofile()
  {
    if(session()->has('deliveryman')){
      return view ('delivery/profile');
    }
    else{
      return view ('delivery/login');

    }
  }

  public function deliveryResponse(Request $request)
  {
    $cart=Cart::find($request->id);
    if($cart!=null){
      $cart->delivery_response=$request->response;
      $cart->save();

      return response()->json([
      'success' =>'Response sent!'
      ]);
    }

  }

  public function customDeliveryLogin(Request $request)
  {
          $d = DeliveryMan::where('username', '=', $request->username)->first();

          // Same message for an unknown username and a wrong password,
          // so the form can't be used to find out which usernames exist.
          if(!$d || !Hash::check((string)$request->password, $d->password)){
              return response()->json([
                'success' =>'Login details are not valid.'
              ]);
          }

          session(['deliveryman'=>$d->username]);
          Session::save();

          return response()->json([
            'success' =>'Logged in successfully!'
          ]);
  }
}
