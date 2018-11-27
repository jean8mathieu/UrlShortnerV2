<?php

namespace App\Http\Controllers;

use App\Bans;
use Illuminate\Http\Request;

class BansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.bans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //Check if IP already exist
            if(Bans::query()->where('ip', $request->ip)->exists()) {
                return Response(['error' => true, 'message' => 'This IP already been added...'], 500);
            }

            //Add IP to the bans table
            if (Bans::create(['ip' => $request->ip, 'notes' => $request->notes])) {
                return Response(['error' => false, 'message' => "Ban added!"]);
            } else {
                return Response(['error' => true, 'message' => "Ban could not be added..."]);
            }
        } catch (\Exception $e) {
            return Response(['error' => true, 'message' => "Something went wrong..."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bans  $bans
     * @return \Illuminate\Http\Response
     */
    public function show(Bans $bans)
    {
        return view('admin.bans.create', ['ban' => $bans]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bans  $bans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bans $bans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bans  $bans
     * @return \Illuminate\Http\Response
     */
    public function destroy($bans)
    {
        $bans = Bans::find($bans);

        $ip = $bans->ip;
        $url = route("bans.create", ['ip' => $ip]);

        try {
            if ($bans->delete()) {
                return Response(['error' => false, 'message' => "Ban deleted", "value" => ["ip" => $ip, "url" => $url]], 200);
            } else {
                return Response(['error' => true, 'message' => "We couldn't delete the ban"], 500);
            }
        } catch (\Exception $e) {
            return Response(['error' => true, 'message' => 'Something went wrong'], 500);
        }
    }
}
