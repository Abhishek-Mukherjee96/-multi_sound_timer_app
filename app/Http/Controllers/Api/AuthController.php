<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\FindUs;
use App\Models\PasswordReset;
use App\Models\Sound;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    //ACCOUNT TYPE
    public function account_type()
    {
        $account_type = AccountType::where('status', '=', 1)->latest()->get();
        return response()->json(['account_type' => $account_type], 200);
    }

    //HOW DID YOU FIND US
    public function find_us()
    {
        $find_us = FindUs::where('status', '=', 1)->latest()->get();
        return response()->json(['find_us' => $find_us], 200);
    }

    //REGISTER
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'account_type' => 'required',
            'find_us' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
            // $message = $validator->errors();
            // return response()->json(['success' => false, 'message' => $message], 200);
        }

        $add_user = new User();

        if ($image = $req->file('image')) {
            $destinationPath = 'public/admin/assets/user-profile';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $add_user['image'] = "$profileImage";
        }else{
            $add_user->image = '';
        }

        $add_user->user_type = 'user';
        $add_user->name = $req->name;
        $add_user->email = $req->email;
        $add_user->password = Hash::make($req->password);
        $add_user->account_type = $req->account_type;
        if ($add_user->account_type == 1) {
            $add_user->card_name = NULL;
            $add_user->card_number = NULL;
            $add_user->exp_date = NULL;
            $add_user->security_code = NULL;
        } elseif ($req->account_type == 2) {
            $validator = Validator::make($req->all(), [
                'card_name' => 'required',
                'card_number' => 'required',
                'exp_date' => 'required',
                'security_code' => 'required',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 400);
            }
            $add_user->card_name = $req->card_name;
            $add_user->card_number = encrypt($req->card_number);
            $add_user->exp_date = encrypt($req->exp_date);
            $add_user->security_code = encrypt($req->security_code);
        } else {
            $validator =  Validator::make($req->all(), [
                'card_name' => 'required',
                'card_number' => 'required',
                'exp_date' => 'required',
                'security_code' => 'required',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 400);
            }
            $add_user->card_name = $req->card_name;
            $add_user->card_number = encrypt($req->card_number);
            $add_user->exp_date = encrypt($req->exp_date);
            $add_user->security_code = encrypt($req->security_code);
        }
        $add_user->find_us = $req->find_us;
        $add_user->zip_or_postal_code = $req->zip_or_postal_code;
        $add_user->is_verified = 0;
        $add_user->status = 1;
        $token = $add_user->createToken('timer_app')->accessToken;
        $add_user->remember_token = $token;
        $unique_code = random_int(0, 999999);
        $add_user->unique_code = $unique_code;
        $add_user->sound_id = 1;
        if ($add_user->save()) {
            
            $data['unique_code'] = $unique_code;
            $data['name'] = $req->name;
            $data['email'] = $req->email;
            $data['title'] = "Email Verification";
            $data['body'] = "Please use this code to verify your email.";
            Mail::send('api.verify_mail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            $message1 = "Account created successfully.";
            return response()->json(['message1'=>$message1, 'token' => $token, 'add_user' => $add_user, 'message' => 'An email has been sent to you, please confirm your email address in order to login into the app. If you did not receive the email, please check your spam folder.'], 200);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.'], 401);
        }
    }

    //VERIFY EMAIL WITH CODE
    public function verify_email_with_code(Request $req){
        
        $token = User::where('remember_token',$req->token)->first();
        $code = $token->unique_code;

        if($code == $req->unique_code){
            //return "ok";
            $dateTime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($token->id);
            $user->is_verified = 1;
            $user->email_verified_at = $dateTime;
            $user->save();
            return response()->json(['message' => 'Email Verified Successfully.'],200);
        }else{
            return response()->json(['message' => 'Something Went Wrong.'], 400);
        }
        
    }

    //LOGIN
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $data = [
            'email' => $req->email,
            'password' => $req->password,
            'is_verified' => 1,
            'status' => 1,
            
        ];

        if(auth()->attempt($data) && auth()->user()->account_type == 1) {
            $id = auth()->user()->id;
            $user_info = auth()->user();
            $token = auth()->user()->createToken('timer_app')->accessToken;
            $sound = Sound::where('id', 1)->first();
            return response()->json(['user_id' => $id,'sound' => $sound, 'account'=> 'Free Account - 3 Timers', 'user_info' => $user_info, 'token' => $token, 'message' => 'Login successfully.'], 200);
        }elseif(auth()->attempt($data) && auth()->user()->account_type == 2){
            $id = auth()->user()->id;
            $user_info = auth()->user();
            $token = auth()->user()->createToken('timer_app')->accessToken;
            $sound = Sound::where('id', 1)->first();
            return response()->json(['user_id' => $id, 'sound' => $sound, 'account' => 'Subscription - Monthly', 'user_info' => $user_info, 'token' => $token, 'message' => 'Login successfully.'], 200);
        }elseif(auth()->attempt($data) && auth()->user()->account_type == 3) {
            $id = auth()->user()->id;
            $user_info = auth()->user();
            $token = auth()->user()->createToken('timer_app')->accessToken;
            $sound = Sound::where('id', 1)->first();
            return response()->json(['user_id' => $id, 'sound' => $sound, 'account' => 'Subscription - Yearly', 'user_info' => $user_info, 'token' => $token, 'message' => 'Login successfully.'], 200);
        }else{
            return response()->json(['message' => 'Invalid email & password'], 400);
        }
    }

    //USER INFO
    public function user_info()
    {
        $user_info = auth()->user();
        $account_type = $user_info->account_type;
        $find_us = $user_info->find_us;
        $account = AccountType::where('id','=', $account_type)->first();
        $find_us = FindUs::where('id','=', $find_us)->first();
        if($user_info->card_number != NULL){
            $card_number = decrypt($user_info->card_number);
        }else{
            $card_number = '';
        }

        if ($user_info->security_code != NULL) {
            $security_code = decrypt($user_info->security_code);
        } else {
            $security_code = '';
        }

        if ($user_info->exp_date != NULL) {
            $expiry_date = decrypt($user_info->exp_date);
        } else {
            $expiry_date = '';
        }
        
        if($user_info->image != NULL){
            $image = URL::to('/') . '/public/admin/assets/user-profile/' . $user_info->image;
        }else{
            $image = '';
        }
        return response()->json(['image'=> $image, 'user_info' => $user_info, 'card_number'=> $card_number, 'security_code' => $security_code, 'expiry_date'=> $expiry_date, 'account_type'=> $account, 'find_us'=> $find_us], 200);
    }

    //EDIT ACCOUNT 
    public function edit_account()
    {
        $account_info = auth()->user();
        return response()->json(['account_info' => $account_info], 200);
    }

    //UPDATE ACCOUNT
    public function edit_account_action(Request $req)
    {
        $account_info = auth()->user();
        if ($image = $req->file('image')) {
            $destinationPath = 'public/admin/assets/user-profile';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $account_info['image'] = "$profileImage";
        }else{
            $account_info->image = '';
        }

        $account_info->user_type = 'user';
        $account_info->account_type = $req->account_type;
        if ($account_info->account_type == 1) {
            $account_info->card_name = NULL;
            $account_info->card_number = NULL;
            $account_info->exp_date = NULL;
            $account_info->security_code = NULL;
            $account_info->zip_or_postal_code = NULL;
        } elseif ($req->account_type == 2) {
            $validator = Validator::make($req->all(), [
                'card_name' => 'required',
                'card_number' => 'required',
                'exp_date' => 'required',
                'security_code' => 'required',
                'zip_or_postal_code' => 'required',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 400);
            }
            $account_info->card_name = $req->card_name;
            $account_info->card_number = encrypt($req->card_number);
            $account_info->exp_date = encrypt($req->exp_date);
            $account_info->security_code = encrypt($req->security_code);
            $account_info->zip_or_postal_code = $req->zip_or_postal_code;
        } else {
            $validator =  Validator::make($req->all(), [
                'card_name' => 'required',
                'card_number' => 'required',
                'exp_date' => 'required',
                'security_code' => 'required',
                'zip_or_postal_code' => 'required',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 400);
            }
            $account_info->card_name = $req->card_name;
            $account_info->card_number = encrypt($req->card_number);
            $account_info->exp_date = encrypt($req->exp_date);
            $account_info->security_code = encrypt($req->security_code);
            $account_info->zip_or_postal_code = $req->zip_or_postal_code;
        }
        if ($account_info->save()) {
            return response()->json(['success'=>true, 'message' => 'Account updated successfully.']);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }

    //DELETE ACCOUNT
    public function delete_account_action(Request $req)
    {
        $data = DB::table('users')->select('status')->where('id', '=', auth()->user()->id)->first();

        if ($data->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $data = array('status' => $status);
        $delete_user = User::find(auth()->user()->id);
        $delete_user->feedback = $req->feedback;
        $delete_user->update();
        $update_query = DB::table('users')->where('id', auth()->user()->id)->update($data, $delete_user);
        if($update_query){
            return response()->json(['success' => true, 'message' => 'Account deleted successfully.']);
        }else{
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }

    //FORGET PASSWORD MAIL SEND
    public function forget_password(Request $req)
    {
        
        $user = User::where('email', $req->email)->first();
        if($user){
            $unique_code = random_int(0, 999999);
            $data['unique_code'] = $unique_code;
            $data['email'] = $req->email;
            $data['title'] = "Password Reset";
            $data['body'] = "Please use this code to reset your password.";
            Mail::send('api.forget_password_mail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        
            $token = $user->createToken('timer_app')->accessToken;
            $update_user = User::find($user->id);
            $update_user->remember_token = $token;
            $update_user->unique_code = $unique_code;
            $update_user->update();
            return response()->json(['token' => $token, 'user' => $user, 'success' => true, 'message' => 'An email has been sent to you, please click on link provided to reset your password. If you did not receive the email, please check your spam folder or try resetting your password again.']);
        }else{
            return response()->json(['message' => 'Email does not exists.', 'success'=>false]);
        }

    }

    //FORGET PASSWORD ACTION
    public function update_forget_password(Request $req){
        $validator = Validator::make($req->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,
                400
            );
        }

        $reset_data = User::where('remember_token',$req->token)->first();
        $reset_data->password = Hash::make($req->password);
        if($reset_data->update()){
            return response()->json(['success' => true, 'message' => 'Your password has been reset successfully.']);
        }else{
            return response()->json(['message' => 'Something went wrong, please try again.', 'success' => false]);
        }
        
    }

    //LOGOUT
    public function logout(Request $req)
    {
        $token = $req->user()->token();
        $token->revoke();
        $response = ["message" => "You have successfully logout."];
        return response()->json($response, 200);
    }
}
