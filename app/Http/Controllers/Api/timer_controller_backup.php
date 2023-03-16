$add_timer = new Timer();

$add_timer->user_id = auth()->user()->id;
$add_timer->timer_title = $req->timer_title;
$add_timer->timer_subhead = $req->timer_subhead;
$add_timer->start_sound = $req->start_sound;
$add_timer->status = 1;
$add_timer->favourite = 0;
$add_timer->save();

for ($i = 0; $i < sizeof($req->seg_name); $i++) {

    $add_timers = new TimerSegment();
    $x = explode(":", $req->seg_dur[$i]);
    $add_timers->timer_id = $add_timer->id;
    $add_timers->segment_name = $req->seg_name[$i];
    if(sizeof($x) > 1){
    //echo "ok";
    $add_timers->duration = $req->seg_dur[$i];
    }else{
    $add_timers->duration = $req->seg_dur[$i] . ":00";
    }
    $add_timers->end_sound = $req->seg_end[$i];
    $add_timers->save();
    }

    return response()->json([
    'success' => true, 'message' => 'Timer Added Successfully.'
    ]);

    ------------------------------------------------------

    $token = $request->bearerToken();
    $check_token = User::where('remember_token', $token)->first();
    if($check_token){
    $timer_timeline = Timer::where('user_id', auth()->user()->id)->where('status', '=', 1)->latest()->get();
    $timer_segments = [];

    foreach ($timer_timeline as $timer) {
    $arr = [];
    if ($timer->favourite == 1) {
    $arr['stat'] = true;
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
    }else{
    return response()->json(['timer_segments' => 'You are unauthorized.'], 400);
    }