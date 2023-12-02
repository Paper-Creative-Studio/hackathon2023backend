<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pin;
use App\Models\pin_photos;
use App\Models\photos;
use App\Models\users;
use Illuminate\Support\Facades\Auth;

class CordsController extends Controller
{
    public function index(){

    }


    public function cords_display(){
        Auth::loginUsingId(1);
        $place = pin::all(['id', 'name', 'longitude', 'latitude']);

        return response()->json($place);
    }   
    
    public function cords_display_with_photos(Request $request)
    {
        Auth::loginUsingId(1);
        $id = $request->input('id');
        // Retrieve longitude, latitude, and photo_id for the given id from the pin table
        $pin = pin::select(['longitude', 'latitude', 'photo_id', 'name'])->find($id);
        
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
            'name' => $pin->name,
            'photos' => $photos->toArray(),
        ];
    
        // Return the result as a JSON response
        return response()->json($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    public function photos_display(Request $request)
    {
        Auth::loginUsingId(1);
        
        $id = $request->input('id');    
        // Retrieve photos related to the pin using the photo_id
        $photos = photos::where('pin_id', $id)->get();
    
        // Return the result as a JSON response
        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function user_info(){
        Auth::loginUsingId(1);
        $user = auth()->user();

        return response()->json($user, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function user_info_get(){
        Auth::loginUsingId(1);
        $user = auth()->user();

        return response()->json($user, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function feed(){
        Auth::loginUsingId(1);
        $user = auth()->user();
        $friendIds = $user->friends;
        $photos = photos::whereIn('user_id', explode(',', $friendIds))->take(30)->get();
        return response()->json([
            'photos' => $photos,
            'loggged_user' => $user,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        

    }

    public function post_image(Request $request){
        Auth::loginUsingId(1);
        $user = auth()->user();
        $pinid = $request->input('pinid');
        $name = $request->input('name');
        $description = $request->input('description');
        $base64 = $request->input('base64');
        $string = '["'.$user->id.'"]';
        $photo = new photos;
        $photo->pin_id = $pinid;
        $photo->name = $name;
        $photo->likes = $string;
        $photo->base64 = $base64;
        $photo->description = $description;
        $photo->user_id = $user->id;
        $photo->save();
        return response()->json("true", JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }   

    public function like(Request $request){
        Auth::loginUsingId(1);
        $user = auth()->user();
        $photoid = $request->input('photoid');
        $photo = photos::where('id', $photoid)->first();
        $likes = $photo->likes;
        $array = json_decode($likes, true);
        $index = array_search($user->id, $array);
        if ($index !== false) {
                unset($array[$index]);
                $array = array_values($array);
                $photo->likes = $array;
                $photo->save();
            return response()->json("false", JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
            else{
                
            $array[] = ''.$user->id.'';
            $array = array_values($array);
            $photo->likes = $array;
            $photo->save();
            return response()->json("true", JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);    
        }
        }   

    public function pinfeed(Request $request){
        Auth::loginUsingId(1);
        $pinid = $request->input('pinid');
        $photos = photos::where('pin_id', $pinid)->get();

        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }   

    public function hello(){
        Auth::loginUsingId(1);
        $photos = photos::take(10)->orderBy('created_at', 'desc')->get();
        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function friends(){
        Auth::loginUsingId(1);
        $user = users::where('id', auth()->user()->id)->first();
        $friendIds = $user->friends;
        $friends = users::whereIn('id', explode(',', $friendIds))->get(['id', 'name', 'base64']);
        return response()->json($friends, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function friendshow(Request $request){
        Auth::loginUsingId(1);
        $id = $request->input('id');
        $user = users::where('id', $id)->first();
        $photos = photos::where('user_id', $user->id)->get(['id','pin_id','name', 'base64', 'likes', 'description']);
        return response()->json($photos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function friendfind(Request $request){

        Auth::loginUsingId(1);
        $user = auth()->user();
        $friendname = $request->input('name');
        $friendid = users::where('name', $friendname)->first();
        
        if(users::where('name', $friendname)->exists()){
            $idid = $friendid->id;
            $user = users::where('id', $user->id)->first();
            $friends = $user->friends;
            $newfriends = $friends . ", " . $idid;
            $user->friends = $newfriends;
            $user->save();
            return true;

        }
        else{
            return false;
        }
        
    }

    public function frienddelete(Request $request, string $friendname){
        Auth::loginUsingId(1);
        $user = auth()->user();
        $friend = users::where('name', $friendname)->firstOrFail();
        $id = $friend->id;
        $array = explode(',', $user->friends);
        $key = array_search($id, $array);

        if ($key != false) {
            unset($array[$key]);
            $user->friends = implode(', ', $array);
            $user->save();
        }

        return response()->json();
    }



};
