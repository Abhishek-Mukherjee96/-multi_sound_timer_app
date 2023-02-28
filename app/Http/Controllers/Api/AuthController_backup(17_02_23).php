<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\FindUs;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
        }

        $add_user = new User();

        if ($image = $req->file('image')) {
            $destinationPath = 'public/admin/assets/user-profile';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $add_user['image'] = "$profileImage";
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

        if ($add_user->save()) {
            return response()->json(['token' => $token, 'add_user' => $add_user, 'message' => 'Account created successfully.'], 200);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.'], 401);
        }
    }

    //VERIFY EMAIL
    public function verify_email($email)
    {
        if (auth()->user()) {
            $user = User::where('email', $email)->get();

            if (count($user) > 0) {

                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/verify-email/' . $random;

                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = "Email Verification";
                $data['body'] = "Please click here below to verify your email.";
                Mail::send('api.verify_mail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();

                return response()->json(['message' => 'An email has been sent to you, please confirm your email address in order to login into the app. If you did not receive the email, please check your spam folder.'], 200);
            } else {
                return response()->json(['message' => 'User not found.'], 400);
            }
        } else {
            return response()->json(['message' => 'User is not authenticated.'], 400);
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
            'is_verified' => 1
        ];

        if (auth()->attempt($data)) {
            // print_r($data);
            // die;
            $user_info = auth()->user();
            $token = auth()->user()->createToken('timer_app')->accessToken;
            return response()->json(['user_info' => $user_info, 'token' => $token, 'message' => 'Login successfully.'], 200);
        } else {
            return response()->json(['message' => 'Invalid email & password test'], 400);
        }
    }

    //USER INFO
    public function user_info()
    {
        $user_info = auth()->user();
        return response()->json(['user_info' => $user_info], 200);
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
        $validator = Validator::make($req->all(), [
            'account_type' => 'required',
            'find_us' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $account_info = auth()->user();
        if ($image = $req->file('image')) {
            $destinationPath = 'public/admin/assets/user-profile';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $account_info['image'] = "$profileImage";
        }

        $account_info->user_type = 'user';
        $account_info->name = $req->name;
        $account_info->email = $req->email;
        $account_info->account_type = $req->account_type;
        if ($account_info->account_type == 'subscription_monthly') {
            $account_info->card_name = $req->card_name;
            $account_info->card_number = encrypt($req->card_number);
            $account_info->exp_date = encrypt($req->exp_date);
            $account_info->security_code = encrypt($req->security_code);
        } elseif ($account_info->account_type == 'subscription_yearly') {
            $account_info->card_name = $req->card_name;
            $account_info->card_number = encrypt($req->card_number);
            $account_info->exp_date = encrypt($req->exp_date);
            $account_info->security_code = encrypt($req->security_code);
        } else {
            $account_info->card_name = $req->card_name;
            $account_info->card_number = encrypt($req->card_number);
            $account_info->exp_date = encrypt($req->exp_date);
            $account_info->security_code = encrypt($req->security_code);
        }
        $account_info->find_us = $req->find_us;
        $account_info->zip_or_postal_code = $req->zip_or_postal_code;
        $account_info->status = 1;

        if ($account_info->update()) {
            return response()->json(['account_info' => $account_info, 'message' => 'Account updated successfully.']);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }

    //DELETE ACCOUNT
    public function delete_account_action()
    {
        $user = auth()->user();
        $user->delete();
        return response()->json(['message' => 'Account deleted successfully.']);
    }

    //FORGET PASSWORD
    public function forget_password(Request $req)
    {
        try {
            $user = User::where('email', $req->email)->get();
            if (count($user) > 0) {
                $token = Str::random(40);
                $domain = URL::to('/');
                //$id = $user[0]->id;
                $url = $domain . '/reset-password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $req->email;
                $data['title'] = "Password Reset";
                $data['body'] = "Please click on below link to reset your password";

                Mail::send('api.forget_password_mail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $req->email],
                    [
                        'email' => $req->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );
                return response()->json(['success' => true, 'message' => 'An email has been sent to you, please click on link provided to reset your password. If you did not receive the email, please check your spam folder or try resetting your password again.']);
            } else {
                return response()->json(['success' => false, 'message' => 'User not found.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
