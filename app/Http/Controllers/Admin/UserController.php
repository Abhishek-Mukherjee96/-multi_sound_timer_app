<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\FindUs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   //GET ALL USER 
   public function user()
   {
      $get_user = User::where('user_type','=','user')->latest()->get();
      return view('admin.accounts.user.index', compact('get_user'));
   }

   public function add_user()
   {
      $account_type = AccountType::where('status', '=', 1)->latest()->get(); 
      $find_us = FindUs::where('status', '=', 1)->latest()->get(); 
      return view('admin.accounts.user.add', compact('account_type', 'find_us'));
   }

   //ADD USER 
   public function add_user_action(Request $req)
   {
      $req->validate([
         'name' => 'required',
         'email' => 'required|email|unique:users,email',
         'password' => 'required|min:8|confirmed|string',
         'account_type' => 'required',
         'find_us' => 'required',
         'zip_or_postal_code' => 'required',
      ]);
      $add_user = new User();

      // if ($image = $req->file('image')) {
      //    $destinationPath = 'public/admin/assets/user-profile';
      //    $profileImage = rand() . "." . $image->getClientOriginalExtension();
      //    $image->move($destinationPath, $profileImage);
      //    $add_user['image'] = "$profileImage";
      // }

      $add_user->user_type = 'user';
      $add_user->name = $req->name;
      $add_user->email = $req->email;
      $add_user->password = Hash::make($req->password);
      $add_user->account_type = $req->account_type;
      if ($add_user->account_type == 2) {
         $add_user->card_name = $req->card_name;
         $add_user->card_number = encrypt($req->card_number);
         $add_user->exp_date = encrypt($req->exp_date);
         $add_user->security_code = encrypt($req->security_code);
         $add_user->zip_or_postal_code = $req->zip_or_postal_code;
      } elseif ($add_user->account_type == 3) {
         $add_user->card_name = $req->card_name;
         $add_user->card_number = encrypt($req->card_number);
         $add_user->exp_date = encrypt($req->exp_date);
         $add_user->security_code = encrypt($req->security_code);
         $add_user->zip_or_postal_code = $req->zip_or_postal_code;
      } else {
         $add_user->card_name = NULL;
         $add_user->card_number = NULL;
         $add_user->exp_date = NULL;
         $add_user->security_code = NULL;
         $add_user->zip_or_postal_code = NULL;
      }
      $add_user->find_us = $req->find_us;
      $add_user->is_verified = 0;
      $add_user->status = 1;
      $add_user->sound_id = 1;

      if ($add_user->save()) {
         $req->session()->flash('success', 'User Added Successfully.');
         return redirect()->route('user');
      } else {
         $req->session()->flash('error', 'Something Went Wrong, Please Try Again.');
         return redirect()->back();
      }
   }

   //EDIT USER
   public function edit_user($id){
      $edit_user = User::find($id);
      $account_type = AccountType::where('status', '=', 1)->latest()->get();
      $find_us = FindUs::where('status', '=', 1)->latest()->get(); 
      return view('admin.accounts.user.edit', compact('edit_user', 'account_type', 'find_us'));
   }

   //UPDATE USER
   public function edit_user_action(Request $req){
      $update_user = User::find($req->id);
      // if ($image = $req->file('image')) {
      //    $destinationPath = 'public/admin/assets/user-profile';
      //    $profileImage = rand() . "." . $image->getClientOriginalExtension();
      //    $image->move($destinationPath, $profileImage);
      //    $update_user['image'] = "$profileImage";
      // }

      $update_user->user_type = 'user';
      $update_user->name = $req->name;
      $update_user->email = $req->email;
      $update_user->password = Hash::make($req->password);
      $update_user->account_type = $req->account_type;
      if ($update_user->account_type == 2) {
         $update_user->card_name = $req->card_name;
         $update_user->card_number = encrypt($req->card_number);
         $update_user->exp_date = encrypt($req->exp_date);
         $update_user->security_code = encrypt($req->security_code);
      } elseif ($update_user->account_type == 3) {
         $update_user->card_name = $req->card_name;
         $update_user->card_number = encrypt($req->card_number);
         $update_user->exp_date = encrypt($req->exp_date);
         $update_user->security_code = encrypt($req->security_code);
      } else {
         $update_user->card_name = NULL;
         $update_user->card_number = NULL;
         $update_user->exp_date = NULL;
         $update_user->security_code = NULL;
         $update_user->zip_or_postal_code = NULL;
      }
      $update_user->find_us = $req->find_us;

      if ($update_user->save()) {
         $req->session()->flash('success', 'User Updated Successfully.');
         return redirect()->route('user');
      } else {
         $req->session()->flash('error', 'Something Went Wrong, Please Try Again.');
         return redirect()->back();
      }
   }

   //DELETE USER
   public function delete_user(Request $req,$id){
      User::destroy($id);
      $req->session()->flash('success', 'User Deleted Successfully.');
      return redirect()->route('user');
   }

   //VIEW USER DETAILS
   public function view_user_details($id)
   {
      $view_user_details = User::find($id);
      return view('admin.accounts.user.view', compact('view_user_details'));
   }

   //UPDATE USER STATUS
   public function edit_user_status(Request $req, $id)
   {
      //get post status with the help of post id

      $data = DB::table('users')->select('status')->where('id', '=', $id)->first();

      //check post status

      if (
         $data->status == '1'
      ) {
         $status = '0';
      } else {
         $status = '1';
      }

      //update post status

      $data = array('status' => $status);
      DB::table('users')->where('id', $id)->update($data);
      $req->session()->flash('success', 'Status has been updated successfully.');
      return redirect()->route('user');
   }

}
