<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Hash;
use Session;
use Mail;
use DB;

use App\Models\Client;
use App\Models\User;
use App\Models\Product;
use App\Models\Rate;
use App\Models\Order;
use App\Models\Message;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Productrate;
use Illuminate\Http\Request;

class ClientController extends Controller
{
  public function signup(Request $request)
  {
    $token = Str::random(40);

        $c = new Client;
        $c->name = $request->name;
        $c->email=$request->email;
        $c->password= Hash::make($request->password);
        $c->phone= $request->phone;
        $c->address= $request->address;
        $c->verification_token= $token;
        $c->save();

      $data = [
        'recipient'=>$request->email,
        'to'=>$request->name,
        'token'=> $c->verification_token,
        'from'=>'CallyWorld@management.com',
        'fromName'=>'Bilal',
        'subject'=>'Verification Link'
      ];

      Mail::send('layouts.email-template-client', $data, function($message) use ($data) {
        $message->to($data['recipient'])
        ->from($data['from'],$data['fromName'])
        ->subject($data['subject']);
      });

      session(['clientemail'=>$request->email]);
      Session::save();

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

      return redirect()->back()->with('clientregistered','Kindly check your an Email inbox with a link to verify your registered email.');
  }

  public function verifyClient(Request $request)
  {
      $c = Client::where('verification_token','=',$request->token)->first();
      if($c!=null){
        $c->status='verified';
        $c->save();
      }
      return view('layouts.verifyEmail')->with('verified','Your email is verified.');
  }

  public function customClientLogin(Request $request)
  {
          $c = Client::where('email', '=', $request->email)->first();

          if($c != null) {
              if(Hash::check($request->password, $c->password)){
                session()->pull('clientemail');

                session(['clientemail'=>$request->email]);
                Session::save();
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
                return response()->json([
                    'success' =>'Logged in successfully.',
                    'client' => $c
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
                'success' =>'No such email exist.'
                ]);
            }

}

  public function placeorder(Request $request){
    $p = Product::find($request->productId);
    /*$o = DB::table('orders')->insert([
        'name' => $request->name,
        'productId'=>$request->productId,
        'clientId'=>'1',
        'userId' => $request->userId,
        'quantity' => $request->quantity,
        'total_price'=> $request->quantity,
        'status' => 'pending'
  ]);*/

    $o = Order::create(['name' => $p->name,
                        'productId'=>$request->productId,
                        'userId' => $request->userId,
                        'clientId'=> $request->clientId,
                        'quantity' => $request->quantity,
                        'total_price'=> ($request->quantity)*($p->price),
                        'status' => 'pending',
                    ]);

      //$userprod = DB::select("select * from product where artistemail='Auth::user()->email'");
      return response()->json([
          'success' =>'Your order has been placed.'
          ]);
  }

  public function deleteOrder(Request $request){
      $o = Order::find($request->id);
      if($o != null){
        $o->delete();
        return response()->json([
          'success' =>'Order has been canceled'
        ]);
      }
      else{
        return response()->json([
          'success' =>'Please try again!'
        ]);
      }
  }

  public function rateUser(Request $request){
    if(session()->has('clientemail'))
    {
      $rate=Rate::where('userId','=',$request->userid)->where('clientId','=',$request->clientid)->first();
      if($rate != null){
        $rate->rate=$request->rating;
        $rate->save();
      }

      else{
        $r = Rate::create([
          'userId' => $request->userid,
          'clientId' => $request->clientid,
          'rate' => $request->rating,
        ]);
      }

      $count=Rate::where('userId','=',$request->userid)->count();
      $sum=Rate::where('userId','=',$request->userid)->sum('rate');
      $ratings=round($sum/$count,'1');

      return response()->json([
        'success' =>'Thank you!',
        'ratings'=>"Ratings: ☆ $ratings"
      ]);
    }
    else{
      return response()->json([
        'success' =>'Sign in first!',
      ]);
    }

  }

  public function rateProduct(Request $request){
    if(session()->has('clientemail')){
      $rate=Productrate::where('productId','=',$request->productid)->where('clientId','=',$request->clientid)->first();
      if($rate != null){
        $rate->rate=$request->rating;
        $rate->save();
      }

      else{
        $pr = Productrate::create([
          'productId' =>$request->productid,
          'clientId' => $request->clientid,
          'rate' => $request->rating,
        ]);
      }

      $count=Productrate::where('productId','=',$request->productid)->count();
      $sum=Productrate::where('productId','=',$request->productid)->sum('rate');
      $ratings=round($sum/$count,'1');

      return response()->json([
        'success' =>'Thank you!',
        'ratings'=>"Ratings: $ratings"
      ]);
    }
    else{
      return response()->json([
        'success' =>'Sign in first!',
      ]);
    }

  }

  public function addcomment(Request $request){
    if(session()->has('clientemail')){
      $c = Comment::create([
        'productId' => $request->id,
        'clientId' => $request->cId,
        'comment' => $request->comment,
      ]);
      return response()->json([
        "success" =>"<tr><th style='padding:40px'>You</th>
        <td style='padding:40px'>$request->comment</td>
        </tr>"
      ]);
    }
    else{
      return response()->json([
        'success' =>'Sign in first!'
      ]);
    }
  }
  public function deleteComment(Request $request){
      $c = Comment::find($request->id);
      $c->delete();
      return response()->json([
          'success' =>'Comment has been removed'
      ]);
  }

  public function cart(){
    if(session()->has('clientemail')){
      $email=session()->get('clientemail');
      $c = Client::where('email', '=', $email)->first();
      return view('cart',['client'=>$c]);
    }
    else{
      return response()->json([
        'success' =>'Sign in first!'
      ]);
    }
  }
  public function orderCart(Request $request){
      $items=session('items');
      $email=session('clientemail');
      $c=Client::where('email','=',$email)->first();

      $total_amount=$request->total_amount;
      $paymentMethod=$request->paymentMethod;
      $date=$request->date;
      $dt=now()->addDays($date);
      $cart=new Cart();
      $cart->clientId =$c->id;
      $cart->items = json_encode($items);
      $cart->status = 'Registered';
      $cart->total_amount=$total_amount;
      $cart->pref_address=$request->address;
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

      $transaction_id = $cart->id;
      $client_name = $c->name;
      $cart_items = json_encode($items);
      $total_price = $total_amount;
      $address = $request->address;
      $payment_date = $dt->format('Y-m-d');

   // Execute the Python script
      $python_script_path = ('C:\Users\lenovo\PycharmProjects\pythonProject\HelloWorld\demo.py');
      $command = "python {$python_script_path} --transaction_id={$transaction_id} --client_name={$client_name} --cart_items='{$cart_items}' --total_price={$total_price} --address='{$address}' --payment_date={$payment_date}";
      // $command = "python {$python_script_path} --transaction_id={$transaction_id}";
      // $output = [];
      $output = shell_exec($command);

      return response()->json([
        'success' =>'added'
      ]);

  }

  public function addToCart(Request $request){
    if(session()->has('clientemail')){
      $id=$request->id;
      $price=$request->price;
      $item = ['id' => $id, 'quantity' => 1, 'price' => $price];
      Session::push('items', $item);
      Session::save();
      // session()->get('items');
      // session()->push('items', ['id' => '15', 'quantity' => 1]);
      return response()->json([
        'success' =>'Item added!'
      ]);
    }
    else{
      return response()->json([
        'success' =>'Sign in to complete your operation!'
      ]);
    }
  }

  public function ToCart(Request $request){
    if(session()->has('clientemail')){
      $id=$request->productId;
      $quantity=$request->quantity;
      $price=$request->price;
      $item = ['id' => $id, 'quantity' => $quantity, 'price' => $price];
      Session::push('items', $item);
      Session::save();
      // session()->get('items');
      // session()->push('items', ['id' => '15', 'quantity' => 1]);
      return response()->json([
        'success' =>'Item added!'
      ]);
    }
    else{
      return response()->json([
        'success' =>'Sign in first!'
      ]);
    }
  }

  public function removeFromCart(Request $request){
      $id=$request->id;
      session_start();
      // $items=session()->get('items');
      $items = session('items');
      foreach($items as $key => $item) {
          if ($item['id'] === $id) {
              $index = $key;
              break;
          }
      }

      session()->forget('items.'.$index);
      Session::save();

      return response()->json([
          'success' =>'Item removed!'
      ]);
  }

  public function increase(Request $request){
      $id=$request->id;
      $p=Product::find($request->id);
      session_start();
      // $items=session()->get('items');
      $items = session('items');
      foreach($items as $key => $item) {
          if ($item['id'] === $id) {
            if($items[$key]['quantity']<5){
              $items[$key]['quantity']++;
              break;
          }
          else{
            break;
          }
      }}
      session(['items' => $items]);
      Session::save();

      return response()->json([
          'success' =>'increased'
      ]);
  }
  public function decrease(Request $request){
      $id=$request->id;
      session_start();
      // $items=session()->get('items');
      $items = session('items');
      foreach($items as $key => $item) {
          if ($item['id'] === $id) {
            if($items[$key]['quantity']>1){
              $items[$key]['quantity']--;
              break;
            }
            else{
              break;
            }
          }
      }
      session(['items' => $items]);
      Session::save();

      $p=DB::select("select * from product where id=$id");

      $price=$p->price;
      $prom=$p->promotion;
      if($prom > 0){
        $newpr=$price-(($prom/100)*$price);
      }
      else{
        $newpr=$price;
      }

      return response()->json([
        'success' =>'decreased',
          'price' =>$newpr
      ]);
  }

  // public function sendMessage(Request $request){
  //   // $user=User::find($request->userid)->first();
  //   // $client=DB::select("select name from client where id=$request->clientid");
  //   $dt=now()->format('Y-m-d H:i:s');
  //   Storage::disk('public')->put($request->name.'.txt', '['.$dt.']  You: '.$request->message);
  //     $m = Message::create([
  //       'userId' => $request->userid,
  //       'clientId' => $request->clientid,
  //       'message' => "From: $request->fName $request->lName,
  //       $request->message",
  //     ]);
  //     return response()->json([
  //         "success" =>"Your message has been sent.."
  //     ]);
  // }


public function sendEmail(Request $request){
  $u=User::where('email','=',$request->to)->first();
    $data = [
      'recipient'=>$request->to,
      'recipientName'=>$u->name,
      'from'=>$request->clientemail,
      'clientname'=>$request->clientname,
      'subject'=>$request->subject,
      'body'=>$request->message
    ];

    Mail::send('layouts.email-clientToUser', $data, function($message) use ($data) {
      $message->to($data['recipient'])
      ->from($data['from'],$data['clientname'])
      ->subject($data['subject']);
    });



    return response()->json([
        "success" =>'Success'
    ]);

}

  public function subscribe(Request $request){
    $uid=$request->userid;
    $cid=$request->clientid;
    $user=DB::table('users')->where("id","=","$uid")->first();
    $client=DB::table('client')->where("id","=","$cid")->first();
    if($user != null && $client->status != 'pending'){
      if($user->subscribers != null){
        $subscribers=json_decode($user->subscribers, true);
        $subscribers[]=$cid;
        $newSubscribers=json_encode($subscribers);

        DB::table('users')->where('id', $uid)->update(['subscribers' => $newSubscribers]);
      }
      else{
        $subscribers[]=$cid;
        $newSubscribers=json_encode($subscribers);

        DB::table('users')->where('id', $uid)->update(['subscribers' => $newSubscribers]);
      }
      // $user->save();
      $data = [
        'recipient'=>$client->email,
        'from'=>$user->email,
        'fromName'=>$user->name,
        'to'=>$client->name,
        'subject'=>'New Supplier Followed',
        'body'=>'Hello '.$client->name.', thanks for following my account.
        You will now receive emails from me everytime I post a new product or make a new promotion.'
      ];

      Mail::send('layouts.email-subscription', $data, function($message) use ($data) {
        $message->to($data['recipient'])
        ->from($data['from'],$data['fromName'])
        ->subject($data['subject']);
      });

      return response()->json([
        "success" => "You, $client->name, followed $user->name account."
      ]);
    }
    else{
      return response()->json([
        "success" => "Sign in first!"
      ]);
    }



  }


  public function unsubscribe(Request $request){
    $uid=$request->userid;
    $cid=$request->clientid;
    $user=DB::table('users')->where("id","=","$uid")->first();
    $client=DB::table('client')->where("id","=","$cid")->first();
    if($user != null && $client->status != 'pending'){
      $subscribers=json_decode($user->subscribers, true);
      $array = array_filter($subscribers, function($value) use ($cid) {
          return $value != $cid;
      });

      $newSubscribers=json_encode($array);

      DB::table('users')->where('id', $uid)->update(['subscribers' => $newSubscribers]);

      $data = [
        'recipient'=>$client->email,
        'from'=>'CallyWorld@management.com',
        'fromName'=>'Bilal',
        'to'=>$client->name,
        'subject'=>'Thank you,',
        'body'=>'Hello '.$client->name.', you unfollowed '.$user->name.' account.
        You won\'t receive new emails from his account.'
      ];

      Mail::send('layouts.email-subscription', $data, function($message) use ($data) {
        $message->to($data['recipient'])
        ->from($data['from'],$data['fromName'])
        ->subject($data['subject']);
      });

      return response()->json([
        "success" => "You, $client->name, unfollowed $user->name account."
      ]);
    }
    else{
      return response()->json([
        "success" => "Sign in first!"
      ]);
    }



  }

  public function addFav(Request $request){
    $f=Favorite::where('productId','=',$request->id)->where('clientId','=',$request->clientId)->first();
    if($f != null){
      $f->delete();
      return response()->json([
        "color"=>"#eee"
      ]);
    }
    else{
      $f = Favorite::create([
        'productId' => $request->id,
        'clientId' => $request->clientId,
      ]);
      return response()->json([
        "color"=>"red"
      ]);
    }

  }

  public function updateClient(Request $request)
  {
          $c = Client::find($request->id);
            $c->name=$request->name;
            $c->phone=$request->phone;
            $c->address=$request->address;
            $c->card_number=$request->cardNumber;
            $c->save();
              return response()->json([
                  'success' =>'Your changes successfully saved.'
                ]);
      }




  public function changePassword(Request $request)
  {
          $c = Client::find($request->id);
            if(Hash::check($request->oldpassword, $c->password)){
              $c->password=Hash::make($request->newpassword);
              $c->save();
              return response()->json([
                  'success' =>'Password changed successfully!'
                  ]);
            }
            else{
                return response()->json([
                'success' =>'Old password is not correct.'
                ]);
              }
        }


  public function logOut() {
      session()->pull('clientemail');
      session()->pull('clientpassword');
      return Redirect('/');
  }

}
