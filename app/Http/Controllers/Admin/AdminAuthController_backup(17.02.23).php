<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AdminAuthController extends Controller
{
    public function admin_login()
    {
        return view('admin.auth.login');
    }

    public function dashboard()
    {
        $hour = date("G");
        $minute = date("i");
        $second = date("s");
        $msg = " Today is " . date("l, M. d, Y.") . " And the time is " . date("g:i a");

        if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59) {
            $greet = "Good Morning,";
        } else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59) {
            $greet = "Good Day,";
        } else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
            $greet = "Good Afternoon,";
        } else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59) {
            $greet = "Good Evening,";
        } else {
            $greet = "Welcome,";
        }
        return view('admin.dashboard', compact('greet'));
    }

    //LOGIN CODE 
    public function admin_login_action(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        //dd($req->all());
        $email = $req->email;
        $password = $req->password;

        if (Auth::attempt(["email" => $email, "password" => $password, "status" => 1, "user_type" => "super_admin"])) {
            return redirect()->route('dashboard');
        } else {
            return Redirect::back()->with(["error" => "Invalid Username & Password."]);
        }
    }

    //LOGOUT CODE
    public function logout()
    {
        session()->flush();
        Auth::logout();
        return redirect()->route('login');
    }

    //STORING DATA IN SESSION
    public function get_session_data(Request $req)
    {
        $req->session()->put('user_id', Auth::user()->id);
        $req->session()->put('user_name', Auth::user()->name);
        //GET ALL DATA FROM SESSION
        $value = session()->all();
        dd($value);
    }

    //RESET PASSWORD FORM LOAD
    public function reset_password_load(Request $req)
    {
        $reset_data = PasswordReset::where('token', $req->token)->get();
           //dd($reset_data);

        if (isset($req->token) && count($reset_data) > 0) {
            $user = User::where('email', $reset_data[0]['email'])->get();
            return view('admin.password.reset_password', compact('user'));
        } else {
            return view('admin.password.404');
        }
   
    }

    //RESET PASSWORD 
    public function reset_password(Request $req)
    {
        $req->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::find($req->id);
        $user->password = Hash::make($req->password);
        $user->save();
        PasswordReset::where('email', $user->email)->delete();
        $req->session()->flash('success', 'Your password has been reset successfully.');
        return redirect()->route('admin_login');
    }

    //
    public function verify_email($token){
        $user = User::where('remember_token',$token)->get();
        if(count($user) > 0){
            $dateTime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            // dd($user);
            $user->remember_token = '';
            $user->is_verified = 1;
            $user->email_verified_at = $dateTime;
            $user->save();
            return "<h1>Email verified successfully.</h1>";
        }else{
            return view('admin.password.404');
        }
    }
}
