<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;

class SoundController extends Controller
{
    //GET ALL SOUND
    public function sound_list(){
        $get_sound = Sound::where('status','=',1)->latest()->get();         
        return response()->json(['get_sound' => $get_sound], 200);
    }

    //SELECT SOUND
    public function select_sound(Request $req){
        $sound = Sound::where('id',$req->id)->first();
        $name = $sound->sound_name;
        $url = $sound->file;
        $user = User::find(Auth::user()->id);
        $user->sound_id = $req->id;
       
        if($user->save()){
            return response()->json(['success' => true, 'name' => $name,'url' => $url, 'message' => 'Sound updated successfully.']);
        }else{
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }

    
}
