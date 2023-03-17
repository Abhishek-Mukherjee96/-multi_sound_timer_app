<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Timer;
use App\Models\User;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    //GET COLLECTION 
    public function collection_list(Request $request){
        $token = $request->bearerToken();
        $check_token = User::where('remember_token',$token)->first();
        if($check_token){
            $collections = Collection::latest()->where('status',1)->get();
            return response()->json(['collections'=> $collections]);
        }else{
            return response()->json(['collections' => 'You are unauthorised.']);
        }
    }

    //ADD COLLECTION
    public function add_collection(Request $req){
        $token = $req->bearerToken();
        $check_token = User::where('remember_token',$token)->first();
        if($check_token){
            $count = sizeof($req->collection_name);
            for ($i = 0; $i < $count; $i++) {
                $add_collection = new Collection();
                $add_collection->collection_name = $req->collection_name[$i];
                $add_collection->status = 1;
                $add_collection->flag = 0;
                $add_collection->save();
            }
            return response()->json([
                'success' => true, 'message' => 'Collection Added Successfully.'
            ]);
        }else{
            return response()->json([
                'message' => 'You are unauthorised.'
            ]);
        }
        
    }

    //UPDATE COLLECTION 
    public function update_collection(Request $req){
        $token = $req->bearerToken();
        $check_token = User::where('remember_token',$token)->first();
        if ($check_token) {
            $collection = Collection::where('id', $req->id)->first();
            if ($collection->timers_id == "") {
                /*Update Query(Timer Table & Collection Table)*/
                $query = Collection::where('id', $req->id)->update(['timers_id' => $req->timer_id, 'flag' => 1]);
                $timer_query = Timer::where('id', $req->timer_id)->update(['flag' => 1]);
            } else {
                $vv = $collection->timers_id . "," . $req->timer_id;
                $query = Collection::where('id', $req->id)->update(['timers_id' => $vv]);
                $timer_query = Timer::where('id', $req->timer_id)->update(['flag' => 1]);
                /*Update Query Timer Table*/
            }
            return response()->json(['success' => true]);
        }else{
            return response()->json(['message' => 'You are unauthorised.']);
        }
        
    }
}
