<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Timer;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    //GET COLLECTION 
    public function collection_list(){
        $collections = Collection::latest()->where('status',1)->get();
        return response()->json(['collections'=> $collections]);
    }

    //ADD COLLECTION
    public function add_collection(Request $req){
        $count = sizeof($req->collection_name);
        for($i=0; $i< $count; $i++){
            $add_collection = new Collection();
            $add_collection->collection_name = $req->collection_name[$i];
            $add_collection->status = 1;
            $add_collection->save();
        }
        return response()->json([
                'success' => true, 'message' => 'Collection Added Successfully.'
            ]);
    }

    //UPDATE COLLECTION 
    public function update_collection(Request $req){
        $collection = Collection::where('id', $req->id)->first();
        //return $collection;die;
        $timer = Timer::find($req->timer_id);
        $timer->collections_id = $req->id;
        if ($timer->save()) {
            return response()->json(['success' => true, 'message' => 'Collection Updated Successfully.']);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again.']);
        }
    }
}
