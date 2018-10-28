<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getIndex(Request $request){
        return view('home');
    }
}
