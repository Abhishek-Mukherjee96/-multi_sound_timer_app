<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SoundController extends Controller
{
    //GET ALL SOUND
    public function sound_list(){
        $get_sound = Sound::latest()->get();
        // dd($get_sound);
        return view('admin.sound.index',compact('get_sound'));
    }

    //ADD SOUND
    public function add_sound(){
        $get_sound_cat = SoundCategory::where('status',1)->latest()->get();
        return view('admin.sound.add',compact('get_sound_cat'));
    }

    //ADD SOUND ACTION
    public function add_sound_action(Request $req){
        $req->validate([
            'sound_name' => 'required'
        ]);

        $add_sound = new Sound();
        if ($sound = $req->file('file')) {
            $destinationPath = 'public/admin/assets/sound-list/';
            $profileImage = rand() . "." . $sound->getClientOriginalExtension();
            $sound->move($destinationPath, $profileImage);
            $add_sound['file'] = "$profileImage";
        }
        $add_sound->cat_id = $req->cat_id;
        $add_sound->sound_name = $req->sound_name;
        $add_sound->status = 1;

        if ($add_sound->save()) {
            $req->session()->flash('success', 'Sound Added Successfully.');
            return redirect()->route('sound_list');
        } else {
            $req->session()->flash('error', 'Something Went Wrong, Please Try Again.');
            return redirect()->back();
        }
    }

    //EDIT SOUND ACTION
    public function edit_sound($id){
        $edit_sound = Sound::find($id);
        $get_sound_cat = SoundCategory::where('status', 1)->latest()->get();
        return view('admin.sound.edit',compact('edit_sound', 'get_sound_cat'));
    }

    //UPDATE SOUND 
    public function edit_sound_action(Request $req){
        $update_sound = Sound::find($req->id);
        if ($sound = $req->file('file')) {
            $destinationPath = 'public/admin/assets/sound-list/';
            $profileImage = rand() . "." . $sound->getClientOriginalExtension();
            $sound->move($destinationPath, $profileImage);
            $update_sound['file'] = "$profileImage";
        }
        $update_sound->cat_id = $req->cat_id;
        $update_sound->sound_name = $req->sound_name;
        $update_sound->status = 1;

        if ($update_sound->save()) {
            $req->session()->flash('success', 'Sound Updated Successfully.');
            return redirect()->route('sound_list');
        } else {
            $req->session()->flash('error', 'Something Went Wrong, Please Try Again.');
            return redirect()->back();
        }

    }

    //DELETE SOUND
    public function delete_sound(Request $req,$id){
        Sound::destroy($id);
        $req->session()->flash('success', 'Sound Deleted Successfully.');
        return redirect()->route('sound_list');
    }

    //UPDATE SOUND STATUS
    public function edit_sound_status(Request $req,$id){
        //get post status with the help of post id

        $data = DB::table('sounds')->select('status')->where('id', '=', $id)->first();

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
        DB::table('sounds')->where('id', $id)->update($data);
        $req->session()->flash('success', 'Status has been updated successfully.');
        return redirect()->route('sound_list');
    }
}
