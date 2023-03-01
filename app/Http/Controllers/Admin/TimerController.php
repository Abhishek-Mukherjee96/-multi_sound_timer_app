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
        return view('admin.timer.index', compact('timer_timeline'));
    }

    public function add_timer()
    {
        return view('admin.timer.add');
    }

    //ADD TIMER
    public function add_timer_action(Request $req)
    {
        $add_timer = new Timer();

        $add_timer->user_id = auth()->user()->id;
        $add_timer->timer_title = $req->timer_title;
        $add_timer->timer_subhead = $req->timer_subhead;
        $add_timer->start_sound = $req->start_sound;
        $add_timer->status = 1;
        $add_timer->favourite = 0;
        $add_timer->save();

        foreach ($req->addmore as $value) {
            $add_timers = new TimerSegment();

            $add_timers->timer_id =  $add_timer->id;
            $add_timers->segment_name = $value['segment_name'];
            $add_timers->duration = $value['duration'];
            $add_timers->end_sound = $value['end_sound'];
            $add_timers->save();
        }

        $req->session()->flash('success', 'Timer Added Successfully.');
        return redirect()->route('timer_list');
    }

    //EDIT TIMER
    public function edit_timer($id)
    {
        $timer = Timer::where('id', $id)->first();
        $edit_timer = TimerSegment::where('timer_id', $timer->id)->get();
        //dd($edit_timer);
        return view('admin.timer.edit', compact('timer', 'edit_timer'));
    }
}
