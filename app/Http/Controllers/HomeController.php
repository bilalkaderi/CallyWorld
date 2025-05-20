<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use DB;
use Mail;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Client;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clientemail = session()->get('clientemail');
        $email = session()->get('email');

        $user = DB::select("select * from users where email='$email'");
        $userid = DB::select("select id from users where email='$email'");

        $client = DB::select("select * from client where email='$clientemail'");
        $clientid = DB::select("select id from client where email='$clientemail'");

        $prod=DB::table('product')
          ->where(function ($query) {
              $query->where('our_stock', '>', 0)
                    ->orWhere('stock', '>', 0);
          })
          ->where('validated', '=', 'yes')
          ->get();

        $categories=Category::all();
        return view('home',['products'=>$prod,'client'=>$client,'user'=>$user,'categories'=>$categories]);
    }

    public function returnProduct(Request $request){
        $product = DB::select("select product.id,product.name,product.userId,product.photo,product.price,product.promotion,product.width,product.height,product.description,product.categoryId,product.stock,
        users.name as username,categories.name as type from product
        left join users on product.userId=users.id
        left join categories on product.categoryId=categories.id where product.id=$request->id");
        // $user= DB::select("select * from users where id=$product->userId");
        if($product != null)
          return view ('product',['product'=>$product]);
        else
          return redirect()->to('/home');
    }

    public function explorevideos(){
        $videos = DB::select("select videos.id,videos.caption,videos.userId,videos.comments,videos.created_at,
        users.name as username from videos left join users on videos.userId=users.id");

        return view ('videos',['videos'=>$videos]);
    }

    public function userprofile(Request $request)
    {
        $user = User::find($request->id);

        $userproducts = DB::table('product')
          ->select('product.id','product.name', DB::raw('SUM(productsrate.rate) / COUNT(productsrate.productId) as rating'))
          ->leftjoin('productsrate', 'product.id', '=', 'productsrate.productId')
          ->groupBy('product.id', 'product.name')
          ->orderBy('soldno', 'desc')
          ->orderBy('rating', 'desc')
          ->where('userId','=',$user->id)
          ->take(5)
          ->get();

        return view ('userprofile',['user'=>$user,'userproducts'=>$userproducts]);
    }

    public function returnCategoryToHome(Request $request){
        $cat = Category::find($request->id);
        return response()->json([
            'categoryValue' => $cat->name
            ]);
    }

    public function reportUser(Request $request){
        // $u = User::find($request->id);
        // $c = Client::find($request->clientid);

        $report = Report::create(['from' => 'client: '.$request->clientid,
                            'to' => 'user: '.$request->id,
                            'reason' => $request->reportmessage,
                            'blocked' => 'No'
                        ]);
        return response()->json([
            'success' => 'Supplier has been reported.'
            ]);
    }

    public function blockUser(Request $request){
        // $u = User::find($request->id);
        // $c = Client::find($request->clientid);
        $r = Report::where('from','=','client: '.$request->clientid)->where('to','=',"Supplier: ".$request->id)->first();
        $r->blocked='Yes';
        $r->save();
    }

    public function search(Request $request)
    {
        $search_query = $request->search_query;
        $users=DB::table('users')->where('name','LIKE','%'.$search_query.'%')->get();
        $products=DB::table('product')->where('name','LIKE','%'.$search_query.'%')->get();
        if($users==null && $products==null){
          $results="<div class='dropdown-menu' id='dropdown-menu2' style='display:block;position:absolute' aria-labelledby='dropdownMenu2'>No results found.</div>";
        }
        else if($search_query==''){
          $results="";
        }
        else{
          $results="<div class='dropdown-menu' id='dropdown-menu2' style='display:block;position:absolute' aria-labelledby='dropdownMenu2'><h3 disabled>Suppliers</h3>";
          foreach($users as $u){
            $results .= "<p><a href='/userprofile/$u->id'>$u->name</a></p>";
            }
          $results.="<h3>Products</h3>";
          foreach($products as $p){
              $results .= "<p><a class='atop' href='/product/$p->id'>$p->name</a></p>";
            }

          $results.="</div>";
        }
        return response()->json([
          'results'=> $results
        ]);

    }

    public function contact(Request $request){
      $message=$request->message;
      $info = [
        'recipient'=>'CallyWorld@management.com',
        'from'=>$request->email,
        'fromName'=>$request->name,
        'subject'=>$request->subject,
        'text'=> $message,
        'body'=>'Hello Bilal, a new message: From '.$request->name.':'
      ];

      Mail::send('layouts.email-homeContact', $info, function($message) use ($info) {
        $message->to($info['recipient'])
        ->from($info['from'],$info['fromName'])
        ->subject($info['subject']);
      });

        return response()->json([
          'success' => 'Your email has been sent!'
        ]);
    }


}
