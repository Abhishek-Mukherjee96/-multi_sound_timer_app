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
        $timer_timeline = Timer::where('user_id', auth()->user()->id)->where('status', '=', 1)->latest()->get();
        //print_r($timer_timeline);die;

        $timer_segments = [];

        foreach ($timer_timeline as $timer) {
            $arr = [];
            if ($timer->favourite == 1) {
                $arr['stat'] =  true;
            } else {
                $arr['stat'] = false;
            }
            $arr['id'] = $timer->id;
            $arr['timer_title'] = $timer->timer_title;
            $arr['timer_subhead'] = $timer->timer_subhead;
            $arr['start_sound'] = $timer->start_sound;

            $obj = TimerSegment::where("timer_id", $timer->id)->get();
            $minutes = 0;
            foreach ($obj as $value) {
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

        $add_timer->user_id = auth()->user()->id;
        $add_timer->timer_title = $req->timer_title;
        $add_timer->timer_subhead = $req->timer_subhead;
        $add_timer->start_sound = $req->start_sound;
        $add_timer->status = 1;
        $add_timer->favourite = 0;
        $add_timer->save();
        //print_r($req->addmore);die;
        foreach ($req->addmore as $value) {
            $add_timers = new TimerSegment();

            $add_timers->timer_id =  $add_timer->id;
            $add_timers->segment_name = $value['s_Name'];
            $add_timers->duration = $value['s_Dur'];
            $add_timers->end_sound = $value['s_Song'];
            $add_timers->save();
        }
        return response()->json(['success' => true, 'message' => 'Timer Added Successfully.']);
    }

    //DUPLICATE TIMER
    public function duplicate_timer(Request $req)
    {
        $timer = Timer::where('id', $req->id)->first();
        $timer = $timer->replicate();
        $timer->created_at = Carbon::now();
        $timer->save();
        $timer_id = $timer->id;
        $timer_segments = TimerSegment::where('timer_id', $req->id)->get();

        foreach ($timer_segments as $value) {
            $segment = new TimerSegment();
            $segment->timer_id = $timer_id;
            $segment->segment_name = $value->segment_name;
            $segment->duration = $value->duration;
            $segment->end_sound = $value->end_sound;
            $segment->save();
        }

        return response()->json(['success' => true, 'message' => 'Timer Duplicated Successfully.']);
    }

    //DELETE TIMER
    public function delete_timer(Request $req)
    {
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
        return response()->json(['success' => true, 'message' => 'Timer Deleted Successfully.']);
    }

    //FAVOURITE TIMER
    public function favourite(Request $req)
    {
        $timer = Timer::find($req->id);
        $timer->favourite = 1;
        $timer->created_at = Carbon::now();
        $timer->save();

        return response()->json(['success' => true, 'message' => 'Timer is now favourite.']);
    }

    //FAVOURITE TAB

    public function favourite_tab()
    {
        $timer_timeline = Timer::where('user_id', auth()->user()->id)->where('status', '=', 1)->where('favourite', '=', 1)->latest()->get();
        //print_r($timer_timeline);die;
        $timer_segments = [];

        foreach ($timer_timeline as $timer) {
            $arr = [];
            if ($timer->favourite == 1) {
                $arr['stat'] =  true;
            } else {
                $arr['stat'] = false;
            }
            $arr['id'] = $timer->id;
            $arr['timer_title'] = $timer->timer_title;
            $arr['timer_subhead'] = $timer->timer_subhead;
            $arr['start_sound'] = $timer->start_sound;

            $obj = TimerSegment::where("timer_id", $timer->id)->get();
            $minutes = 0;
            foreach ($obj as $value) {
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
}
