<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //PROFILE DETAILS
    public function profile()
    {
        $profile_details = User::where('id', Auth::user()->id)->select('users.*')->first();
        return view('admin.profile.edit', compact('profile_details'));
    }

    //UPDATE PROFILE
    public function update_profile(Request $req)
    {

        $update_profile = User::find(Auth::user()->id);
        $update_profile->name = $req->name;
        $update_profile->email = $req->email;
        $update_profile->zip_or_postal_code = $req->zip_or_postal_code;

        if ($image = $req->file('image')) {
            $destinationPath = 'public/admin/assets/user-profile/';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $update_profile['image'] = "$profileImage";
        }

        if ($update_profile->save()) {
            $req->session()->flash('success', 'Profile Updated Successfully.');
            return redirect()->route('profile');
        } else {
            $req->session()->flash('error', 'Something Went Wrong, Please Try Again.');
            return redirect()->back();
        }
    }

    //CHANGE PASSWORD
    public function update_password(Request $req)
    {
        $req->validate([
            'old_password' => 'required|min:8|string',
            'password' => 'required|min:8|string|confirmed',
        ]);
        $hashedPassword = Auth::user()->password;
        //dd($hashedPassword);
        if (Hash::check($req->old_password, $hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($req->password);
            $user->save();
            Auth::logout();
            $req->session()->flash('success', 'Password changed successfully.');
            return redirect()->route('login')->with('success', 'Password Changed Successfully.');
        } else {
            $req->session()->flash('error', 'Old Password Doesn not Match, Please Try Again.');
            return redirect()->back();
        }
       
    }
}
