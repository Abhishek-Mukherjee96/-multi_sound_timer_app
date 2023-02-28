<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //GET ALL NOTIFICATION
    public function notification_list(){
        $get_notification = Notification::latest()->get();
        return response()->json(['get_notification' => $get_notification], 200);
    }

    //NOTIFICATION ACTION
    public function notification_action(Request $req){
        $notification = Notification::where('id',$req->id)->first();
        $notification->id = $notification->id;
        $notif = Notification::find($req->id);
        if($notification->for_user == ''){
            if($notification->to_user == ''){
                $notif->to_user = auth()->user()->id;
                $notif->read_status = 1;
            }else{
                $existing_user_id = $notification->to_user;
                $current_user_id = $existing_user_id.','. auth()->user()->id;
                //echo $current_user_id;die;
                $notif->to_user = $current_user_id;
                $notif->read_status = 1;
            }

        }else{
            $notif->read_status = 1;
            
        }
        $notif->save();
        return response()->json(['success' => true], 200);
    }
}
