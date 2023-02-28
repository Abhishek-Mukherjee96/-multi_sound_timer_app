<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timer;
use App\Models\TimerSegment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class TimerController extends Controller
{
    //GET TIMER LIST
    public function timer_list()
    {
        $timer_timeline = Timer::where('user_id', auth()->user()->id)->where('status','=',1)->latest()->get();
        //print_r($timer_timeline);die;
        $timer_segments = [];

        foreach ($timer_timeline as $timer) {
            $arr = [];
            $arr['id'] = $timer->id;
            $arr['timer_title'] = $timer->timer_title;
            $arr['timer_subhead'] = $timer->timer_subhead;
            $arr['start_sound'] = $timer->start_sound;

            $obj = TimerSegment::where("timer_id", $timer->id)->get();
            $minutes = 0;
            foreach($obj as $value){
                list($hour, $minute) = explode(':', $value->duration);
                $minutes += $hour * 60;
                $minutes += $minute;
            }
            $hours = floor($minutes / 60);
            $minutes -= $hours * 60;
            $total_dur = sprintf('%02d:%02d', $hours, $minutes);
            $arr['duration'] = $total_dur;
            array_push($timer_segments, $arr);

        }
       
        return response()->json(['timer_segments' => $timer_segments], 200);
    }

    //ADD TIMER
    public function add_timer_action(Request $req)
    {
        // dd($req->all());
        $add_timer = new Timer();

        if ($image = $req->file('start_sound')) {
            $destinationPath = 'public/admin/assets/sound/';
            $profileImage = rand() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $add_timer['start_sound'] = "$profileImage";
        }

        $add_timer->user_id = auth()->user()->id;
        $add_timer->timer_title = $req->timer_title;
        $add_timer->timer_subhead = $req->timer_subhead;
        $add_timer->start_sound = URL::to('/') . '/public/admin/assets/sound/' . $add_timer['start_sound'];
        $add_timer->status = 1;
        // dd($add_timer);
        $add_timer->save();

        foreach ($req->addmore as $value) {
            $add_timers = new TimerSegment();

            if ($image = $value['end_sound']) {
                $destinationPath = 'public/admin/assets/sound/';
                $profileImage = rand() . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $value['end_sound'] = $profileImage;
            }
            $add_timers->timer_id =  $add_timer->id;
            $add_timers->segment_name = $value['segment_name'];
            $add_timers->duration = $value['duration'];
            $add_timers->end_sound = URL::to('/') . '/public/admin/assets/sound/' . $add_timer['end_sound'];
        }

        if ($add_timers->save()) {
            return response()->json(['add_timers' => $add_timers, 'message' => 'Timer Added Successfully.']);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }

    //DUPLICATE TIMER
    public function duplicate_timer(Request $req){
        $timer = Timer::where('id',$req->id)->first();
        $timer = $timer->replicate();
        $timer->created_at = Carbon::now();
        $timer->save();
        $timer_id = $timer->id;
        $timer_segments = TimerSegment::where('timer_id',$req->id)->get();

        foreach($timer_segments as $value){
            $segment = new TimerSegment();
            $segment->timer_id = $timer_id;
            $segment->segment_name = $value->segment_name;
            $segment->duration = $value->duration;
            $segment->end_sound = $value->end_sound;
            $segment->save();
        }

        return response()->json(['success'=>true, 'message' => 'Timer Duplicated Successfully.']);

    }

    //DELETE TIMER
    public function delete_timer(Request $req){
        $data = DB::table('timers')->select('status')->where('id', '=', $req->id)->first();

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
        $delete_timer  = DB::table('timers')->where('id', $req->id)->update($data);
        return response()->json(['success'=>true, 'message' => 'Timer Deleted Successfully.']);
    }
}
