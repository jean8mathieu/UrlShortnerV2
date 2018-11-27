<?php

namespace App\Http\Controllers;

use App\Forbidden;
use Illuminate\Http\Request;

class ForbiddenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.forbidden.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.forbidden.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check if keyword exist
        if(Forbidden::query()->where('contains', $request->keyword)->exists()){
            return Response(['error' => true, 'message' => "Keyword already exist"], 500);
        }

        //Create keyword if doesn't exist
        if(Forbidden::create(['contains' => $request->keyword])){
            return Response(['error' => false, 'message' => "Keyword added!"], 200);
        } else {
            return Response(['error' => true, 'message' => "Keyword couldn't be added"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Forbidden  $forbidden
     * @return \Illuminate\Http\Response
     */
    public function show(Forbidden $forbidden)
    {
        return view('admin.forbidden.create', ['forbidden' => $forbidden]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Forbidden  $forbidden
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Forbidden $forbidden)
    {
        if($forbidden->update(['contains' => $request->keyword])){
            return Response(['error' => false, 'message' => 'Keyword updated']);
        } else {
            return Response(['error' => true, 'message' => "We were unable to update the keyword"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Forbidden  $forbidden
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forbidden $forbidden)
    {
        try {
            if ($forbidden->delete()) {
                return Response(['error' => false, 'message' => "Keyword deleted"], 200);
            } else {
                return Response(['error' => true, 'message' => "We couldn't delete the keyword"], 500);
            }
        } catch (\Exception $e) {
            return Response(['error' => true, 'message' => "Something went wrong"], 500);
        }
    }
}
