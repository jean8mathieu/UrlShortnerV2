<?php

namespace App\Http\Controllers;

use App\View;
use Illuminate\Http\Request;
use App\Url;

class APIController extends Controller
{
    //Array of restricted keyword
    private $restrictions = [
        "porn",
        "sex",
        "xxx",
        "fuck",
        "suck",
        "redtube",
        "video-one",
        "xvideos",
        "spankbang",
        "xhamster",
        "xnxx",
        "cur.lv",
        "tinyurl",
        "jmdev",
        "chaturbate",
        "bazoocam",
        "jizz"
    ];

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
        $url = strtolower(urldecode(trim($request->url)));
        $private = ($request->private == true ? true : false);

        if (strlen($url) === 0) {
            return response([
                'error' => true,
                'message' => "You must enter a url before we can generate it for you..."
            ], 200);
        }

        $date = new \DateTime;
        $date->modify('-30 seconds');
        $last30sec = $date->format('Y-m-d H:i:s');

        if ($this->checkRestrictedUrl($url)) {
            return response([
                'error' => true,
                'message' => "You're not allowed to have those kind of url on this site"
            ], 200);
        }

        $lastGenerate = Url::query()
            ->where('ip', $ip)
            ->where('created_at', '>=', $last30sec)
            ->first();

        if ($lastGenerate) {
            return response([
                'error' => true,
                'message' => 'You can only generate 1 url every 30 seconds'
            ]);
        }

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
        } else {
            return redirect('/');
        }
    }

    /**
     * Return an array of restricted keyword
     *
     * @return boolean
     */
    private function checkRestrictedUrl($url)
    {
        foreach ($this->restrictions as $bad) {
            $place = strpos($url, $bad);
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
                'message' => 'Nothing was found into oru database'
            ]);
        }

        //Data found returning the value
        return response([
            'error' => false,
            'data' => $data
        ]);
    }

}
