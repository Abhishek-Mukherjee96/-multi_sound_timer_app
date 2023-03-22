<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Timer;
use App\Models\TimerSegment;
use App\Models\User;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    //GET COLLECTION 
    public function collection_list(Request $request)
    {
        $token = $request->bearerToken();
        $check_token = User::where('remember_token', $token)->first();
        if ($check_token) {
            $collections = Collection::latest()->where('status', 1)->get();
            return response()->json(['collections' => $collections]);
        } else {
            return response()->json(['collections' => 'You are unauthorised.']);
        }
    }

    //ADD COLLECTION
    public function add_collection(Request $req)
    {
        $token = $req->bearerToken();
        $check_token = User::where('remember_token', $token)->first();
        if ($check_token) {
            $count = sizeof($req->collection_name);
            for ($i = 0; $i < $count; $i++) {
                $add_collection = new Collection();
                $add_collection->user_id = auth()->user()->id;
                $add_collection->collection_name = $req->collection_name[$i];
                $add_collection->status = 1;
                $add_collection->flag = 0;
                $add_collection->timers_id = '';
                $add_collection->save();
            }
            return response()->json([
                'success' => true, 'message' => 'Collection Added Successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'You are unauthorised.'
            ]);
        }
    }

    //UPDATE COLLECTION 
    public function update_collection(Request $req)
    {
        $token = $req->bearerToken();
        $check_token = User::where('remember_token', $token)->first();
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
        } else {
            return response()->json(['message' => 'You are unauthorised.']);
        }
    }

    //TIMER PLAYLIST
    public function timer_playlist(Request $req)
    {
        $token = $req->bearerToken();
        $check_token = User::where('remember_token', $token)->first();
        if($check_token){
            $collections = Collection::where('collections.flag', 1)->join('users', 'users.id', '=', 'collections.user_id')->where('collections.user_id', auth()->user()->id)->get();
            $Arr['collections'] = array();
            $Arr1['only_timers'] = array();
            $ARR['final'] = array();
            foreach ($collections as $collection) {

                $arr1['Timer'] = array();
                $timer_ids = explode(',', $collection->timers_id);
                $arr1['name'] = $collection->collection_name;
                $arr1['status'] = $collection->status;
                $arr1['flag'] = $collection->flag;

                foreach ($timer_ids as $id) {

                    $check_timer_id = Timer::where('id', $id)->get();
                                        
                    foreach ($check_timer_id as $timer_id) {
                        $arr2 = [];
                        if ($timer_id->favourite == 1) {
                            $arr2['stat'] = true;
                        } else {
                            $arr2['stat'] = false;
                        }
                        $arr2['id'] = $timer_id->id;
                        $arr2['timer_title'] = $timer_id->timer_title;
                        $arr2['timer_subhead'] = $timer_id->timer_subhead;
                        $arr2['start_sound'] = $timer_id->start_sound;

                        $obj = TimerSegment::where("timer_id", $timer_id->id)->get();
                        $minutes = 0;
                        foreach ($obj as $value) {
                            list($hour, $minute) = explode(':', $value->duration);
                            $minutes += $hour * 60;
                            $minutes += $minute;
                        }
                        $hours = floor($minutes / 60);
                        $minutes -= $hours * 60;
                        $total_dur = sprintf('%02d:%02d', $hours, $minutes);
                        $arr2['duration'] = $total_dur;
                        array_push($arr1['Timer'], $arr2);
                    }
                }

                array_push($Arr['collections'], $arr1);
            }

            $check_timer = Timer::select('timers.id as timer_id', 'timers.timer_title', 'timers.timer_subhead', 'timers.start_sound')->where('timers.flag', 0)->join('users', 'users.id', '=', 'timers.user_id')->where('users.id', auth()->user()->id)->get();
            //return response()->json($check_timer);
            foreach ($check_timer as $timer) {
                $arr3 = [];
                if ($timer->favourite == 1) {
                    $arr3['stat'] = true;
                } else {
                    $arr3['stat'] = false;
                }
                $arr3['id'] = $timer->timer_id;
                $arr3['timer_title'] = $timer->timer_title;
                $arr3['timer_subhead'] = $timer->timer_subhead;
                $arr3['start_sound'] = $timer->start_sound;

                $obj = TimerSegment::where("timer_id", $timer->timer_id)->get();
                $minutes = 0;
                foreach ($obj as $value) {
                    list($hour, $minute) = explode(':', $value->duration);
                    $minutes += $hour * 60;
                    $minutes += $minute;
                }
                $hours = floor($minutes / 60);
                $minutes -= $hours * 60;
                $total_dur = sprintf('%02d:%02d', $hours, $minutes);
                $arr3['duration'] = $total_dur;

                array_push($Arr1['only_timers'], $arr3);
            }
            $ARR = array_merge($Arr, $Arr1);
            return response()->json($ARR);
        }else{
            return response()->json(['message' => 'You are unauthorised.']);
        }
        
    }

    //GET THE LAST QUERY
    // DB::enableQueryLog();
    // $user = User::get();
    // $query = DB::getQueryLog();
    // dd($query);
}
