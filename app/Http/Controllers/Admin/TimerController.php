<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timer;
use App\Models\TimerSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class TimerController extends Controller
{
    //GET TIMER LIST
    public function timer_list()
    {
        $timer_timeline = Timer::where('user_id', auth()->user()->id)->latest()->get();
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
            //return $timer_segments;die;
            return view('admin.timer.index', compact('timer_segments'));
        }
    }

    public function add_timer()
    {
        return view('admin.timer.add');
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
            $add_timers->end_sound = $value['end_sound'];
            $add_timers->end_sound = URL::to('/') . '/public/admin/assets/sound/' . $add_timer['end_sound'];
            $add_timers->save();
        }

        $req->session()->flash('success', 'Timer Added Successfully.');
        return redirect()->route('timer_list');
    }

    //EDIT TIMER
    public function edit_timer($id)
    {
        $timer = Timer::where('id', $id)->first();
        $edit_timer = TimerSegment::where('timer_segments.timer_id', $timer->id)->get();
        //dd($edit_timer);
        return view('admin.timer.edit', compact('timer', 'edit_timer'));
    }
}
