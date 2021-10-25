<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Steps;

class HomeController extends Controller
{
    //Read json data
    public function leaderboard(){
        return json_decode(file_get_contents(public_path()."/assets/leaderboard.json"), true);
    }

    //Save Steps
    public function save(Request $request){
        //Server-side validations
        $this->validate($request, [
            'steps'    =>  'required|numeric|lt:50000',
        ]);

        Steps::create([
            'uId'   =>  auth()->id(),
            'steps' =>  $request->steps
        ]);

        return $this->leaderboard();
    }
}
