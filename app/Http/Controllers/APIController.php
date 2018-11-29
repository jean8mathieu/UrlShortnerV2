<?php

namespace App\Http\Controllers;

use App\Bans;
use App\Forbidden;
use App\View;
use Illuminate\Http\Request;
use App\Url;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        foreach (Url::query()
                     ->where('private', false)
                     ->orderBy('id', 'DESC')
                     ->limit(50)
                     ->with('views')
                     ->limit(50)->get() as $u) {
            $data[] = ['id' => $u->id, 'url' => $u->url, 'shortUrl' => $u->short_url, 'click' => $u->views->count()];
        }

        return response([
            'error' => false,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ip = $request->ip();

        //Check if the IP being used for the request have been banned from our system
        if (Bans::query()->where('ip', $ip)->exists()) {
            return response([
                'error' => true,
                'message' => "You've been blocked from using our service." .
                    "Please contact jean-mathieu.emond@jmdev.ca if you feel this is an error"
            ], 403);
        }

        $url = urldecode(trim($request->url));
        $tempUrl = strtolower($url);
        $private = ($request->private == true ? true : false);

        if (strlen($url) === 0) {
            return response([
                'error' => true,
                'message' => "You must enter a url before we can generate it for you..."
            ], 200);
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return response([
                'error' => true,
                'message' => "Invalid Url. Please try again..."
            ], 500);
        }

        $date = new \DateTime;
        $date->modify('-30 seconds');
        $last30sec = $date->format('Y-m-d H:i:s');

        if ($this->checkRestrictedUrl($tempUrl)) {
            return response([
                'error' => true,
                'message' => "You're not allowed to have those kind of url on this site"
            ], 403);
        }

        $lastGenerated = Url::query()
            ->where('ip', $ip)
            ->where('created_at', '>=', $last30sec)
            ->first();

        if ($lastGenerated) {
            return response([
                'error' => true,
                'message' => 'You can only generate 1 url every 30 seconds'
            ], 500);
        }

        //Generate the short version of the url
        $short = $this->generateUrl($url);

        // Insert the url into the table
        if (Url::create(['url' => $url, 'short_url' => $short, 'ip' => $ip, 'private' => $private])) {
            return response([
                'error' => false,
                'message' => 'Your url have been generated!',
                'href' => route('redirect', [$short])
            ], 200);
        } else {
            return response([
                'error' => true,
                'message' => "Something went wrong while trying to generate your url. Please try again."
            ], 200);
        }
    }

    /**
     * Generate URL
     *
     * @param $url
     * @return string
     */
    private function generateUrl($url)
    {
        //Loop until the short url is unique
        do {
            $short = substr(md5(microtime() . $url), rand(0, 26), 5);
        } while (Url::query()->where('short_url', $short)->exists());

        //Return the short url
        return $short;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function show($shortUrl)
    {
        //If short_url exist redirect to the url else go to the main page
        if ($url = Url::query()->where('short_url', $shortUrl)->first()) {
            $ip = request()->ip();
            View::create(['url_id' => $url->id, 'ip' => $ip]);
            return redirect($url->url);
        }
        return redirect('/');
    }

    /**
     * Return an array of restricted keyword
     *
     * @return boolean
     */
    private function checkRestrictedUrl($url)
    {
        //Get all the forbidden string
        $forbiddeen = Forbidden::all();

        //Loop through the forbidden string
        foreach ($forbiddeen as $bad) {
            $place = strpos($url, $bad->contains);
            if (!empty($place)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Find the url with the info given in the search
     *
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function showUrlInfo(Request $request)
    {
        if (strlen($request->search) < 4) {
            return response([
                'error' => true,
                'message' => "Hey! We don't work for no reason. Give me more than 3 characters if you want me to look into it for you :)"
            ], 500);
        }

        //Initializing the array
        $data = [];
        //Looking for data
        foreach (Url::query()
                     ->where('url', 'LIKE', "%{$request->search}%")
                     ->orWhere('short_url', 'LIKE', "%{$request->search}%")
                     ->with('views')
                     ->limit(50)
                     ->get() as $u) {
            $data[] = ['id' => $u->id, 'url' => $u->url, 'shortUrl' => $u->short_url, 'click' => $u->views->count()];
        }

        //Nothing was found return an error
        if (count($data) === 0) {
            return response([
                'error' => true,
                'message' => 'Nothing was found into our database'
            ], 404);
        }

        //Data found returning the value
        return response([
            'error' => false,
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bans $bans
     * @return \Illuminate\Http\Response
     */
    public function destroy($url)
    {
        $url = Url::find($url);

        try {
            if ($url->delete()) {
                return Response(['error' => false, 'message' => "Url deleted"], 200);
            } else {
                return Response(['error' => true, 'message' => "We couldn't delete the url"], 500);
            }
        } catch (\Exception $e) {
            return Response(['error' => true, 'message' => 'Something went wrong'], 500);
        }
    }


    /**
     * Admin Panel Search
     *
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function searchAdmin(Request $request)
    {
        if (strlen($request->search) < 4) {
            return response([
                'error' => true,
                'message' => "Hey! We don't work for no reason. Give me more than 3 characters if you want me to look into it for you :)"
            ], 500);
        }

        //Initializing the array
        $data = [];
        //Looking for data
        foreach (Url::query()
                     ->where('url', 'LIKE', "%{$request->search}%")
                     ->orWhere('short_url', 'LIKE', "%{$request->search}%")
                     ->orWhere('id', $request->search)
                     ->with('views')
                     ->limit(50)
                     ->get() as $u) {
            $data[] = [
                'id' => $u->id,
                'url' => $u->url,
                'click' => $u->views->count(),
                'ip' => $u->ip,
                'deleteUrl' => route('api.destroy', [$u->id])
            ];
        }

        //Nothing was found return an error
        if (count($data) === 0) {
            return response([
                'error' => true,
                'message' => 'Nothing was found into our database'
            ], 404);
        }

        //Data found returning the value
        return response([
            'error' => false,
            'data' => $data
        ], 200);
    }

}
