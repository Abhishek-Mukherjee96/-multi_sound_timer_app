<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sound;
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

    //TIMER DETAILS
    public function timer_details(Request $req)
    {
        $timer_timeline = Timer::join('sounds', 'sounds.id', '=', 'timers.start_sound')->select('timers.id', 'timers.timer_title', 'timers.timer_subhead', 'sounds.sound_name', 'sounds.file')->where('timers.id', '=', $req->id)->first();
        //print_r($timer_timeline);die;

        $timer_segments['fragments'] = array();

        $timer_segments['timer_title'] = $timer_timeline->timer_title;
        $timer_segments['timer_subhead'] = $timer_timeline->timer_subhead;
        $timer_segments['start_sound'] = $timer_timeline->sound_name;
        $timer_segments['file'] = $timer_timeline->file;

        $obj = TimerSegment::join('sounds', 'sounds.id', '=', 'timer_segments.end_sound')->select('timer_segments.id','timer_segments.segment_name', 'timer_segments.duration', 'sounds.sound_name', 'sounds.file')->where("timer_segments.timer_id", $timer_timeline->id)->get();

        foreach ($obj as $val) {
            $arr1 = [];
            $arr1['id'] = $val->id;
            $arr1['name'] = $val->segment_name;
            $arr1['duration'] = $val->duration;
            $arr1['sound_name'] = $val->sound_name;
            $arr1['file'] = $val->file;
            array_push($timer_segments['fragments'], $arr1);
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
        $add_timer->file = $req->start_sound;
        $add_timer->status = 1;
        $add_timer->favourite = 0;
        $add_timer->save();
        //print_r($req->addmore);die;

        for ($i = 0; $i < sizeof($req->seg_name); $i++) {

            $add_timers = new TimerSegment();
            $add_timers->timer_id =  $add_timer->id;
            $add_timers->segment_name = $req->seg_name[$i];
            $add_timers->duration = $req->seg_dur[$i];
            $add_timers->end_sound = $req->seg_end[$i];
            $add_timers->save();
        }

        return response()->json([
            'success' => true, 'message' => 'Timer Added Successfully.'
        ]);
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
