<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Hash;
use Mail;
use App\Models\Product;
use App\Models\Country;
use Session;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CustomAuthController extends Controller
{

    public function index()
    {
        if (session()->has('email') && session()->has('password')) {
          $email=Session::get('email');
          $user = DB::select("select * from users where email='$email'");
          $userprod = DB::select("select * from product where userId=$user->id");
          return view('user2',['userprod'=>$userprod]);
        }
        return view('auth.login');
    }


    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => ['required','string', 'email', 'max:255'],
            'password' => ['required','string', 'min:8'],
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
          if(Auth::user()->status=='verified'){
            if(Auth::user()->role=='1'){
              return redirect()->route('admin');
            }
            else{
              session(['email'=>Auth::user()->email]);
              session(['password'=>$request->password]);
              Session::save();
              return redirect()->route('user')->withSuccess('Signed in');
            }
          }
          else{
            return redirect()->back()->with('notverified', 'You didn\'t verify your Email.');
          }
        }
        else{
          return redirect()->back()->with('notvalid','Login details are not valid');
        }

    }

    public function registration()
    {
        return view('auth.login');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          'password' => ['required', 'string', 'min:8', 'confirmed'],
          'phone' => ['required', 'string', 'min:8'],
          'countryCode' => ['required'],
          'photoprofile' => ['image','nullable'],
        ]);
        $subscribers = [];

        $u = new User;
        $u->name = $request->name;
        $u->email = $request->email;
        $u->password = Hash::make($request->password);
        $u->phone = "+".$request->countryCode.$request->phone;
        $u->nbOfSales = '0';
        $u->reported='0';
        $u->address=$request->address;
        $token = Str::random(40);
        $u->verification_token=$token;
        $u->subscribers=json_encode($subscribers);
        //$data = $request->all();
        //$this->create($data);

        if($request->hasFile('photoprofile')){
          $file = $request->file('photoprofile');
          $file_name = $u->name."_".$u->email.".".$file->extension();
          $file->move('profiles', $file_name);//move the image
          //update the name in the table
          $u->photo = $file_name;
        }
        $u->save();

        $data = [
          'recipient'=>$u->email,
          'to'=>$u->name,
          'token'=> $u->verification_token,
          'from'=>'CallyWorld@management.com',
          'fromName'=>'Bilal',
          'subject'=>'Verification Link'
        ];

        Mail::send('layouts.email-template', $data, function($message) use ($data) {
          $message->to($data['recipient'])
          ->from($data['from'],$data['fromName'])
          ->subject($data['subject']);
        });

        return redirect()->route('register-user')->with('registered','Kindly check your an Email inbox with a link to verify your registered email.');
    }

    /*public function create(array $data)
    {
      $u = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'phone' => $data['phone'],
        'nbOfSales' => '0',
      ]);
    }*/
    public function verify(Request $request)
    {
        $user = User::where('verification_token','=',$request->token)->first();
        if($user!=null){
          $user->status='verified';
          $user->save();
        }
        return redirect()->route('register-user')->with('verified','Your email is verified successfully.');
    }

    public function returnCategoryToHome(Request $request){
        $cat = Category::find($request->id);
        return response()->json([
            'categoryValue' => $cat->name
            ]);
    }
    public function loggedin()
    {
        if(Auth::check()){
            return view('login');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        session()->pull('email');
        session()->pull('password');
        Auth::logout();

        return Redirect('login');
    }


    public function updateUser(Request $request)
    {
            $u = User::find($request->id);
            if($u!=null){
              $u->name=$request->name;
              $u->phone=$request->phone;
              $u->aboutme=$request->aboutme;
              $u->address=$request->address;
              $u->card_number=$request->cardNumber;

              if($request->hasFile('photo')){
                $file = $request->file('photo');
                $file_name = $u->name."_".$u->email.".".$file->extension();
                $file->move('profiles', $file_name);//move the image
                //update the name in the table
                $u->photo = $file_name;
              }
              $u->save();
              return response()->json([
                'success' =>'Successfully Updated'
              ]);
            }
        }




    public function changePassword(Request $request)
    {
            $u = User::find($request->id);
              if(Hash::check($request->oldpassword, $u->password)){
                $u->password=Hash::make($request->newpassword);
                $u->save();
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
}
