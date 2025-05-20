<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Client;
use App\Models\Report;
use App\Models\Video;
use App\Models\Order;
use DB;
use Auth;
use Mail;

class UserController extends Controller
{
  public function index()
  {
        $userid = Auth::user()->id;
        $userprod = DB::select("select * from product where userId=$userid");
        $userdata = DB::select("select * from users where id=$userid");
       return view('user2',['userprod'=>$userprod,'userdata'=>$userdata]);
  }



    public function addproduct(Request $request){
      if($request->delivery_type=='Week'){
        $time=$request->delivery_time*7;
      }
      else{
        $time=$request->delivery_time;
      }
      try{

      $p = Product::create(['name' => $request->name,
                          'userId'=>Auth::user()->id,
                          'price' => $request->price,
                          'description' => $request->description,
                          'height' => $request->height,
                          'width' => $request->width,
                          'stock' => $request->stock,
                          'categoryId'=>$request->type,
                          'validated'=>'no',
                          'sales_after_promotion'=>0,
                          'our_stock'=>0,
                          'promotion'=>0,
                          'expecting_delivery_time'=>$time,
                      ]);
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $file_name = $p->name."_".$p->userId.".".$file->extension();
            $file->move('img', $file_name);//move the image
            //update the name in the table
            $p->photo = $file_name;
            $p->save();
        }

        $row="<td id='box_$p->id'><div class='box' id='box' style='display:relative'>
          <div class='front'>";
        if($p->photo != null)
          $row.= "<img src='img/$p->photo' style='width:300px;height:240px;border-radius:20px' alt=''>";
        else
          $row.="<h2 style='font-size:20px'>No Preview Photo</h2>";

      $row.="</div>
      <div class='back'>
        <h1 style='font-family:Calibri;color:#618685'>$p->name</h1>
        <h5 style='font-family:Calibri;color:#618685;font-family:bold;font-size:20px'>$p->description</h5>
        <p style='font-family:Calibri;color:#618685;font-size:20px'>$p->price $
        <button type='button' onclick='' style='margin-left:5px;padding:10px;border:0;background-color:transparent'><img src='img/edit.png' style='border-radius:6px;background-color:lightblue;height:30px;width:33px;opacity:0.5;cursor:pointer;float:right'></button></p>
        </div>
      </div></td>";

        return response()->json([
          'success' => true,
            'row' =>$row
            ]);
          }
          catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function addvideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimetypes:video/mp4,video/quicktime,image/gif|max:50000',
        ]);
        $comments = [];
        $v = new Video;
        $v->userId = Auth::user()->id;
        $v->caption = $request->caption;
        $v->comments = json_encode($comments);
        $v->reported = 0;
        $v->save();
        if($request->hasFile('video')){
          $file = $request->file('video');
          $file_name = $v->id.".".$file->extension();
          $file->move('videos', $file_name);
        }

        return redirect()->back()->with('videosuccess', 'Video uploaded successfully.');
    }

    public function returnProduct(Request $request){
      $p = Product::find($request->id);
        return response()->json([
            'name' =>$p->name,
            'price' =>$p->price,
            'type' =>$p->categoryId,
            'photo' =>$p->photo,
            'width' =>$p->width,
            'height' =>$p->height,
            'stock' =>$p->stock,
            'promotion' =>$p->promotion,
            'description' =>$p->description,
            'id' =>$p->id
        ]);
    }

    public function updateProduct(Request $request){
      $p = Product::where('id','=',$request->id)->first();
      $oldP=$p->promotion;
      $p->name=$request->name;
      $p->categoryId=$request->type;
      $p->price=$request->price;
      $p->width=$request->width;
      $p->height=$request->height;
      $p->stock=$request->stock;
      $p->promotion=$request->promotion;
      $p->description=$request->description;
      $p->save();
      if($request->hasFile('photo')){
          $file = $request->file('photo');
          $file_name = $p->name."_".$p->userId.".".$file->extension();
          $file->move('img', $file_name);//move the image
          //update the name in the table
          $p->photo = $file_name;
          $p->save();
      }
      $newP=$request->promotion;
      $newprice=$p->price - (($newP/100)*$p->price);
      if($oldP < $newP && $p->validated =='yes'){
        $subscribers=Auth::user()->subscribers;
        $array=json_decode($subscribers, true);
        foreach($array as $a){

          $c=Client::find($a);

          $data = [
            'recipient'=>$c->email,
            'from'=>Auth::user()->email,
            'fromName'=>Auth::user()->name,
            'subject'=>'New Promotion',
            'photo'=>$p->photo,
            'description'=>$p->description,
            'price'=>$p->price,
            'newprice'=>"New Price: $".$newprice,
            'body'=>'Hello '.$c->name.','.Auth::user()->name.' has made a new offer on his product.'
          ];

          Mail::send('layouts.email-newProduct', $data, function($message) use ($data) {
            $message->to($data['recipient'])
            ->from($data['from'],$data['fromName'])
            ->subject($data['subject']);
          });
        }
      }

      return response()->json([
        'success' =>'Product successfully updated.'
      ]);

    }

    public function deleteProduct(Request $request){
        $p = Product::find($request->id);
        $p->delete();
        return response()->json([
            'success' =>'Product has been deleted'
        ]);
    }

    public function confirmOrder(Request $request){
        $o = Order::find($request->id);
        if($o != null){
          $product = Product::find($o->productId);
          $o->status = "confirmed";
          $o->expecting_delivery_date = $request->delTime;
          $o->save();
          if($product != null){
            $product->soldno = $product->soldno + 1;
            $product->stock = $product->stock - $o->quantity;
          }
          $product->save();
        }
        return response()->json([
          'success' =>'Order Confirmed'
        ]);
    }

    public function ready(Request $request){
        $o = Order::find($request->id);
        if($o != null){
          $product = Product::find($o->productId);
          $o->status = "Packed";
          $o->save();
        }
        return response()->json([
          'success' =>'Order is ready to be delivered!'
        ]);
    }

    public function cancelOrder(Request $request){
        $o = Order::find($request->id);
        if($o != null){
          $o->status = 'Sorry but the product you ordered is not available now.';
          $o->save();
        }
        return response()->json([
          'success' =>'You confirmed the order.'
        ]);

    }
    public function allOrders(){
        $id = Auth::user()->id;
        $orders = DB::select("select orders.id,orders.productId,orders.clientId,orders.total_price,orders.quantity,orders.expecting_delivery_date,
                orders.status,orders.created_at,product.id as productid,product.photo,product.name as pname,product.price,
                client.name as cname,client.phone from orders
            left join client on orders.clientId=client.id
            left join product on orders.productId=product.id where orders.userId= $id
            order by orders.created_at desc");
        $ordersnb=DB::table('orders')->where('userId','=',$id)->where('status','=','pending')->count();
        return view('allOrders',['orders'=>$orders,'ordersnb'=>$ordersnb]);

    }

    public function Comment(Request $request){
      $c = Comment::where('productId','=',$request->id)->get();
      $product=Product::find($request->id);
      $userid=Auth::user()->id;
      if($product->userId==$userid)
        return view('comments',['comments'=>$c,'product'=>$product]);
      else
      return redirect("/product/$product->id");
    }

    public function removeComment(Request $request){
        $c = Comment::find($request->id);
        $c->delete();
        return response()->json([
            'success' =>'Comment has been removed'
        ]);
    }

    public function returnSales(Request $request){
      $id=Auth::user()->id;
      $div="<div class='year-stats'>";
      $sales = DB::table('orders')->where("userId","=",$id)->where('status','=','Delivered')->where('updated_at','>',$request->dateFrom)->where('updated_at','<',$request->dateTo)->sum('total_price');
      $products = DB::table('orders')->select('productId')->where("userId","=",$id)->where('status','=','Delivered')->where('updated_at','>',$request->dateFrom)->where('updated_at','<',$request->dateTo)->distinct()->get();

          foreach($products as $up){
            $pid=$up->productId;
            $pSales=DB::table('orders')->where("productId","=",$pid)->where('status','=','Delivered')->where('updated_at','>',$request->dateFrom)->where('updated_at','<',$request->dateTo)->sum('quantity');
            $prod=DB::table('product')->where("id","=",$pid)->first();
              $div.="<div class='month-group'>
              <p class='month'>$prod->name</p>
              <div class='bar' style='height:".$pSales."0px' title='$pSales'></div>$pSales
              </div>";
            }

          $div.="</div>
              <div class='info'>
                <p>Total sales :<span>   $ $sales</span></p>
              </div>";

        return response()->json([
            'success' =>$div
        ]);
    }







    public function reportClient(Request $request){
        // $u = User::find($request->id);
        // $c = Client::find($request->clientid);

        $report = Report::create(['from' => 'Supplier: '.$request->id,
                            'to' => 'client: '.$request->clientid,
                            'reason' => $request->reportmessage,
                            'blocked' => 'No'
                        ]);
        return response()->json([
            'success' => 'Supplier has been reported.'
            ]);
    }

    public function blockClient(Request $request){
        // $u = User::find($request->id);
        // $c = Client::find($request->clientid);
        $r = Report::where('from','=','Supplier: '.$request->id)->where('to','=',"client: ".$request->clientid)->first();
        $r->blocked='Yes';
        $r->save();
        $row="This client is blocked by you.
        <button type='button' value='' onclick='unblock()' class='btn btn-info' style='background-color:#17a2b8;color:#fff;cursor:pointer'>Unblock</button>";
        return response()->json([
            'success' => $row
            ]);
    }

    public function unblockClient(Request $request){
        // $u = User::find($request->id);
        // $c = Client::find($request->clientid);
        $r = Report::where('from','=','Supplier: '.$request->id)->where('to','=',"client: ".$request->clientid)->first();
        $r->blocked='No';
        $r->save();
        $row="You unblocked this account.";
        return response()->json([
            'success' => $row
            ]);
    }

}
