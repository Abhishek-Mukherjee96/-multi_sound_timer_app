<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;

class SoundController extends Controller
{
    //GET ALL SOUND
    public function sound_list(Request $request){
        $token = $request->bearerToken();
       // echo $token;die;
        $get_sound = Sound::where('status','=',1)->get();  
        $arr['get_sound'] = array();  
        foreach($get_sound as $sound){
            $arr1 = [];
            $arr1['sound_id'] = $sound->id;
            $arr1['name'] = $sound->sound_name;
            $arr1['url'] = $sound->file;
            $query = DB::table('users')->select(DB::raw('case when sound_id = "'.$sound->id.'" then true else false end stf'))->where('remember_token', $token)->first();
            $arr1['Stat'] = $query->stf;
            array_push($arr['get_sound'], $arr1);
        }
        return response()->json($arr, 200);
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
