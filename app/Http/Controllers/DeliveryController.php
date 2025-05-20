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

          if($d != null) {
              if($request->password==$d->password){
                session(['deliveryman'=>$request->username]);
                Session::save();

                return response()->json([
                'success' =>'Logged in successfully!',
                'delivery'=>$d
                ]);

              }
              else{
                  return response()->json([
                  'success' =>'Login details are not valid.'
                  ]);
                }

        }
        else{
            return response()->json([
                'success' =>'Username does not exist!'
                ]);
            }

}
}
