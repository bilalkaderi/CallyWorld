<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Hash;
use Mail;
use Session;
use DB;
use App\Models\Client;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Rate;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Productrate;
use App\Models\Report;


class AdminController extends Controller
{
  public function index()
  {
    return view ('admin/dashboard');
  }
  public function user()
  {
    return view ('admin/user');
  }

  public function returnPage(Request $request)
  {
    return view ("admin/$request->page");
  }

  public function deleteUser(Request $request){
      $u = User::find($request->id);
      $u->delete();
      return response()->json([
          'success' =>'Supplier has been removed'
      ]);
  }

  public function returnUser(Request $request){
    $div="<div class='year-stats'>";
    $u = User::find($request->id);
    $id=$u->id;
    $sales = DB::table('orders')->where("userId","=",$id)->where('status','=','Delivered')->sum('total_price');
    $products = DB::table('orders')->select('productId')->where("userId","=",$id)->where('status','=','Delivered')->distinct()->get();
    foreach($products as $up){
      $pid=$up->productId;
      $pSales=DB::table('orders')->where("productId","=",$pid)->where('status','=','Delivered')->sum('quantity');
      $prod=DB::table('product')->where("id","=",$pid)->first();
        $div.="<div>
        <p>$prod->name <strong>$pSales</strong></p>
        </div>";
      }

      $div.="</div>
          <div class='info'>
            <p>Total sales :<span>   $ $sales</span></p>
          </div>";

      return response()->json([
        'success' =>$div,
        'name' =>$u->name,
          'email' =>$u->email,
          'phone' =>$u->phone,
          'sales' =>$u->nbOfSales,
          'photo' =>$u->photo,
          'about' =>$u->aboutme,
          'status' =>$u->status,
          'reported' =>$u->reported,
          'role' =>$u->role,
          'id' =>$u->id
      ]);
  }

  public function editUser(Request $request){
    $u = User::where('id','=',$request->id)->first();
    $u->name=$request->name;
    $u->email=$request->email;
    $u->phone=$request->phone;
    $u->aboutme=$request->about;
    $u->status=$request->status;
    // $u->nbOfSales=$request->sales;
    $u->role=$request->role;
    $u->save();

    return response()->json([
      'success' =>'Supplier info successfully updated.'
    ]);

  }

  public function addUser(Request $request)
  {

    $request->validate([
      'newname' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'newpassword' => ['required', 'string', 'min:8'],
      'newphone' => ['required', 'string', 'min:8'],
    ]);


      $u = new User;
      $subscribers=[];
      $u->name = $request->newname;
      $u->email = $request->email;
      $u->password = Hash::make($request->newpassword);
      $u->phone = "+". $request->countryCode.$request->newphone;
      $u->nbOfSales = '0';
      $u->subscribers=json_encode($subscribers);
      $u->role = $request->newrole;
      $u->status = $request->newstatus;
      $token = Str::random(40);
      $u->verification_token=$token;

      $u->save();

      return redirect()->route('userAdmin')->with('newregistration','New account has been registered by you!');

  }
////////////////////////////////////////CLIENT//////////////////////////////////////////////////////
public function deleteClient(Request $request){
    $c = Client::find($request->id);
    $c->delete();
    return response()->json([
        'success' =>'Client has been removed'
    ]);
}

public function returnClient(Request $request){
  $c = Client::find($request->id);
    return response()->json([
        'name' =>$c->name,
        'email' =>$c->email,
        'phone' =>$c->phone,
        'status' =>$c->status,
        'id' =>$c->id
    ]);
}

public function editClient(Request $request){
  $c = Client::where('id','=',$request->id)->first();
  $c->name=$request->name;
  $c->email=$request->email;
  $c->phone=$request->phone;
  $c->status=$request->status;
  $c->save();

  return response()->json([
    'success' =>'Client info successfully updated.'
  ]);

}
//////////////////////////////////Category//////////////////////////////////////////
public function addCategory(Request $request){
  $c = Category::create([
      'name'=>$request->name,
      'totalproducts'=>'0'
    ]);

  return response()->json([
    'success' => "<tr id=''>
      <th scope='row'>New</th>
      <td>$request->name</td>
      <td>0</td>
      <td>Now</td>
      <td>
        <button type='button' class='btn btn-info' data-toggle='modalUser' id='togglemodal' >Edit</button>
        <button type='button' class='btn btn-light' onclick='deleteUser({{$c->id}})'>Remove</button>
      </td>
    </tr>"
  ]);

}
public function deleteCat(Request $request){
    $c = Category::find($request->id);
    $c->delete();
    return response()->json([
        'success' =>'Category has been removed'
    ]);
}

public function returnCat(Request $request){
  $c = Category::find($request->id);
    return response()->json([
        'name' =>$c->name,
        'id' =>$c->id
    ]);
}

public function editCat(Request $request){
  $c = Category::where('id','=',$request->id)->first();
  $c->name=$request->name;
  $c->save();

  return response()->json([
    'success' =>'Category name successfully updated.'
  ]);

}
////////////////////////Product/////////////////////////////////
public function deleteProduct(Request $request){
    $p = Product::find($request->id);
    $p->delete();
    return response()->json([
        'success' =>'Product has been removed'
    ]);
}

public function returnProduct(Request $request){
  $p = Product::find($request->id);
    return response()->json([
        'name' =>$p->name,
        'description' =>$p->description,
        'price' =>$p->price,
        'type' =>$p->categoryId,
        'soldno' =>$p->soldno,
        'suppStock' =>$p->stock,
        'validation' =>$p->validated,
        'ourStock' =>$p->our_stock,
        'promotion' =>$p->promotion,
        'id' =>$p->id
    ]);
}

public function editProduct(Request $request){
  $p = Product::where('id','=',$request->id)->first();
  $oldP=$p->promotion;
  $oldV=$p->validated;
  $p->name=$request->name;
  $p->categoryId=$request->type;
  $p->price=$request->price;
  $p->description=$request->description;
  $p->soldno=$request->soldno;
  $p->stock=$request->suppStock;
  $p->promotion=$request->promotion;
  $p->validated=$request->validation;
  $p->our_stock=$request->ourStock;
  $p->save();

  $newP=$request->promotion;
  $newprice=$p->price - (($newP/100)*$p->price);

  if($oldP < $newP){
      $c=Client::all()->where('status','=','verified');
      foreach($c as $c){
        $data = [
          'recipient'=>$c->email,
          'from'=>'CallyWorld@management.com',
          'fromName'=>'Bilal',
          'subject'=>'New Promotion',
          'photo'=>$p->photo,
          'description'=>$p->description,
          'price'=>$p->price,
          'newprice'=>'New Price: $'. $newprice,
          'body'=>'Hello '.$c->name.',we made a new offer on this product.'
        ];

        Mail::send('layouts.email-newProduct', $data, function($message) use ($data) {
          $message->to($data['recipient'])
          ->from($data['from'],$data['fromName'])
          ->subject($data['subject']);
        });
      }
    }

    if($oldV=='no' && $request->validation == 'yes'){
        $c=Client::all()->where('status','=','verified');
        foreach($c as $c){
          $data = [
            'recipient'=>$c->email,
            'from'=>'CallyWorld@management.com',
            'fromName'=>'Bilal',
            'subject'=>'New Product',
            'photo'=>$p->photo,
            'description'=>$p->description,
            'price'=>'Old Price: $'.$p->price,
            'newprice'=>'',
            'body'=>'Hello '.$c->name.', new product is available now.'
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
/////////////////////Comment//////////////////////////////
public function deleteComment(Request $request){
    $c = Comment::find($request->id);
    $c->delete();
    return response()->json([
        'success' =>'Comment has been removed'
    ]);
}

public function returnComment(Request $request){
  $c = Comment::find($request->id);
    return response()->json([
        'comment' =>$c->comment,
        'id' =>$c->id
    ]);
}

public function editComment(Request $request){
  $c = Comment::where('id','=',$request->id)->first();
  $c->comment=$request->comment;
  $c->save();

  return response()->json([
    'success' =>'Comment is successfully updated.'
  ]);

}
//////////////////////RATES/////////////////////////////
public function deleteRate(Request $request){
    $r = Rate::find($request->id);
    $r->delete();
    return response()->json([
        'success' =>'Supplier\'s ratings has been removed'
    ]);
}
public function deleteProdRate(Request $request){
    $pr = Productrate::find($request->id);
    $pr->delete();
    return response()->json([
        'success' =>'Product\'s ratings has been removed'
    ]);
}


public function changePasswordUserAdmin(Request $request)
{
        $u = User::find($request->id);
        if($u != null){
          $u->password=Hash::make($request->newpassword);
          $u->save();
          return response()->json([
            'success' =>'Supplier\'s Password changed successfully!'
          ]);
        }

      }


    public function changePasswordClientAdmin(Request $request)
    {
          $c = Client::find($request->id);
          if($c != null){
            $c->password=Hash::make($request->newpassword);
            $c->save();
            return response()->json([
              'success' =>'Client\'s Password changed successfully!'
            ]);
          }
        }

        public function userReport(Request $request){
            $u = User::find($request->id);
            if($u!=null){
              $info="<tr><td rowspan='8' style='padding:35px;border-radius: 5px;background-color:#eef'>
                        <img src='profiles/'.$u->photo class='photo' style='width:185px;height:190px;border-radius:4px'>
                    </td></tr>
        				<tr><td>$u->name</td></tr>
        				<tr><td>$u->email</td></tr>
                <tr><td>$u->nbOfSales Sold Products</td></tr>
                <tr><td>$u->reported times reported</td></tr>
                <tr><td>Joined on $u->created_at</td></tr>
            </table>";
              $r = DB::select("select * from report where report.to LIKE '%user: $request->id%'");
              $table='';
              if($r!=null){
                foreach($r as $r){
                  $table.="<tr id='$r->id'><td>$r->from</td><td>$r->reason</td><td>$r->blocked</td></tr>";
                }
              }
              return response()->json([
                'success' =>$table,
                'info' =>$info
              ]);
            }

        }

        public function clientReport(Request $request){
            $c = Client::find($request->id);
            if($c!=null){
              $info="
        				<tr><td>$c->name</td></tr>
        				<tr><td>$c->email</td></tr>
                <tr><td>Joined on $c->created_at</td></tr>
            </table>";
              $r = DB::select("select * from report where report.to LIKE '%client: $request->id%'");
              $table='';
              if($r!=null){
                foreach($r as $r){
                  $table.="<tr id='$r->id'><td>$r->from</td><td>$r->reason</td><td>$r->blocked</td></tr>";
                }
              }
              return response()->json([
                'success' =>$table,
                'info' =>$info
              ]);
            }

        }


        public function changeStatus(Request $request){
            $c = Cart::find($request->id);
            if($c!=null){
              $c->status=$request->status;
              if($request->delDate){
                $c->expecting_delivery_date=$request->delDate;
              }
              $c->save();
              if($c->status=='On Preparing'){
                $color='red';
              }
              elseif($c->status=='On Delivery'){
                $c->delivery_hour=$request->time;
                $c->delivery_details=$request->delDetails;
                $c->save();
                $color='blue';
              }
              elseif($c->status=='Delivered'){
                $data = json_decode($c->items, true);
                foreach($data as $data){
                  $id=$data['id'];
                  $quantity=$data['quantity'];
                  $price=$data['price'];
                  $p=Product::find($id);
                  $u=User::find($p->userId);
                    if($price<$p->price){
                      $p->sales_after_promotion = $p->sales_after_promotion +$quantity;
                      $p->save();
                      $u->nbOfSales=$u->nbOfSales+$quantity;
                      $u->save();

                      $diff=$p->our_stock - $quantity;

                      if($diff>=0){
                        $p->our_stock=$p->our_stock-$quantity;
                        $p->save();
                      }
                      else{
                        $p->our_stock=($p->our_stock-$quantity)-$diff;
                        $p->stock=$p->stock+$diff;
                        $p->save();
                      }
                    }
                    else{
                      $p->stock=$p->stock-$quantity;
                      $p->soldno=$p->soldno +$quantity;
                      $p->save();
                      $u->nbOfSales=$u->nbOfSales+$quantity;
                      $u->save();
                    }

                }
                $color='lightgreen';
              }
              elseif($c->status=='Not Delivered'){
                $color='red';
              }
              $td='<span class="dot" style="margin-right:20px;height: 15px;width: 15px;background-color:'.$color.';border-radius: 50%;display: inline-block;"></span>'.$c->status;
              return response()->json([
                'success' =>$td
              ]);
            }

        }

        public function returnProducts(Request $request){
          $c = Cart::find($request->id);
          if($c!=null){
            $data = json_decode($c->items, true);
            $table="<p style='font-size:25px'><strong>Cart #$c->id</strong></p>";
            foreach($data as $data){
              $id=$data['id'];
              $quantity=$data['quantity'];
              $price=$data['price'];
              $p=DB::select("select * from product where id=$id");
              foreach($p as $p){
                $o=DB::table('orders')->where('cartId','=',$c->id)->where('productId','=',$p->id)->first();

                $table.="<div class='card-body'>
                  <div class='row' id='item_$p->id'>
                    <div class='col-lg-5 col-md-6 mb-4 mb-lg-0'>
                      <!-- Data -->
                      <p><strong>$p->name</strong></p>
                      <p><strong>Quantity requested:</strong> $quantity</p>
                      <p><strong>Supplier's Stock:</strong> $p->stock</p>
                      <p><strong>Our Stock:</strong> $p->our_stock</p>
                      <p>$p->description</p>
                      <p>Size: $p->width x $p->height</p>
                      <p>Expected Delivery Time: $p->expecting_delivery_time Days</p>
                      <!-- Data -->
                    </div>

                    <div class='col-lg-4 col-md-6 mb-4 mb-lg-0'>
                      <p class='text-start text-md-center'>
                        <span><strong>$ $price</strong></span>
                        <p class='text-start text-md-center'>";
                        if($o!=null){
                          $table.="<span><strong>Status: $o->status</strong></span><br>
                          <span><strong>Quantity Requested from Supplier: $o->quantity</strong></span><br>
                          <span><strong>Available in our stock: $p->our_stock</strong></span><br>
                          </p>";
                          if($o->status=='confirmed'){
                            $table.="<p class='text-start text-md-center'>
                            <span>The order should be ready on: <strong>$o->expecting_delivery_date</strong></span>
                            </p>";
                          }
                          elseif($o->status=='Delivered'){
                            $table.="<p class='text-start text-md-center'>
                            <span><span class='dot' style='height: 35px;width: 35px;background-color:lightgreen;border-radius: 50%;display: inline-block;'></span></strong></span></p>";
                          }
                        }
                        else{
                          $table.="<span><strong>Available in our Stock: $p->our_stock</strong></span>
                          </p>";
                        }
                      $table.="</p>
                      <!-- Price -->
                    </div>
                  </div>

                  <hr class='my-4' />
                </div>";
              }

            }
            return response()->json([
                'table' =>$table
            ]);
          }
        }


        public function delivered(Request $request){
            $o = Order::find($request->id);
            if($o!=null){
              $o->status='Delivered';
              $o->save();
              return response()->json([
                'success' => 'Order is delivered!'
              ]);
            }

        }

        public function remind(Request $request){
          $o=Order::find($request->id);
          $u=DB::table('users')->where('id','=',$o->userId)->first();
          $p=DB::table('product')->where('id','=',$o->productId)->first();
          $info = [
            'recipient'=>$u->email,
            'from'=>'CallyWorld@management.com',
            'fromName'=>'Bilal',
            'subject'=>'Attention: Order to be Packed',
            'productName'=>$p->name,
            'expDel'=>$o->expecting_delivery_date,
            'body'=>'Hello '.$u->name.', you have to prepare the order to deliver it as soon as possible.
            Send a confirmation from your profile when the order gets packed and ready to be delivered.'
          ];

          Mail::send('layouts.email-alertSupplier', $info, function($message) use ($info) {
            $message->to($info['recipient'])
            ->from($info['from'],$info['fromName'])
            ->subject($info['subject']);
          });

            return response()->json([
              'success' => 'Your email has been sent!'
            ]);
        }

}
