<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pin;
use App\Models\pin_photos;
use App\Models\photos;

class CordsController extends Controller
{
    public function index(){

    }


    public function cords_display(){
        $place = pin::all(['id', 'name', 'longitude', 'latitude']);

        return response()->json($place);
    }   
    
    public function cords_display_with_photos($id, Request $request)
    {
        // Retrieve longitude, latitude, and photo_id for the given id from the pin table
        $pin = pin::select(['longitude', 'latitude', 'photo_id'])->find($id);
        
        if (!$pin) {
            // Handle the case where the pin with the specified id is not found
            return response()->json(['error' => 'Pin not found'], 404);
        }
    
        // Retrieve photos related to the pin using the photo_id
        $photos = pin_photos::where('id', $pin->photo_id)->get();
    
        // Create the response array
        $result = [
            'longitude' => $pin->longitude,
            'latitude' => $pin->latitude,
            'photo_id' => $pin->photo_id,
            'photos' => $photos->toArray(),
        ];
    
        // Return the result as a JSON response
        return response()->json($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    public function photos_display($id, Request $request)
    {
        
    
        // Retrieve photos related to the pin using the photo_id
        $photos = photos::where('pin_id', $id)->get();
    
        // Return the result as a JSON response
        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function user_info(){
        $user = auth()->user();

        return response()->json($user, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function feed(){
        $user = auth()->user();
        $friendIds = $user->friends;
        $photos = photos::whereIn('user_id', explode(',', $friendIds))->take(30)->get();
        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        

    }

    public function post_image(Request $request){
        $user = auth()->user();
        $pinid = $request->input('pinid');
        $name = $request->input('name');
        $description = $request->input('description');
        $base64 = $request->input('base64');
        $photo = new photos;
        $photo->pin_id = $pinid;
        $photo->name = $name;
        $photo->base64 = $base64;
        $photo->description = $description;
        $photo->user_id = $user->id;
        $photo->save();
        return response()->json("true", JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }   

    public function like(Request $request){
        $user = auth()->user();
        $photoid = $request->input('photoid');
        $photo = photos::where('id', $photoid)->first();
        $likes = $photo->likes;
        $newlikes = $likes . ", " . $user->id;
        $photo->likes = $newlikes;
        $photo->save();
        return response()->json("true", JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }   

    public function pinfeed($id){
        //, Request $request
        $pinid = $id;
        //$pinid = $request->input('pinid');
        $photos = photos::where('pin_id', $pinid)->get();

        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }   




};
