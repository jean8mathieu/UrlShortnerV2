<?php

namespace App\Http\Controllers;

use App\Bans;
use App\Forbidden;
use App\Url;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {

        //Getting all the bans from the database
        $bans = Bans::all();

        //Creating an ipBans array
        $ipBans = [];
        foreach ($bans as $ban) {
            $ipBans[] = $ban->ip;
        }

        //Loop through the url generated
        $data = [];

        $urlGenerated = Url::query()
            ->with('views')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $forbiddens = Forbidden::all();

        return view('admin.dashboard',
            ['topUrl' => $data, 'bans' => $bans, 'forbiddens' => $forbiddens, 'urlGenerated' => $urlGenerated]);
    }
}
