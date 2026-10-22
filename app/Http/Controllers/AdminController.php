<?php

namespace App\Http\Controllers;

use App\Models\Bans;
use App\Models\Forbidden;
use App\Models\Url;
use App\Models\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {

        //Getting all the bans from the database
        $bans = Bans::all();

        //Map each banned IP to its ban so the url tables can link to it
        $bannedIps = $bans->keyBy('ip');

        //Loop through the url generated
        $data = [];
        foreach (Url::query()
                     ->where('private', false)
                     ->withCount('views')
                     ->orderBy('views_count', 'DESC')
                     ->limit(50)
                     ->get() as $u) {

            $data[] = [
                'id' => $u->id,
                'url' => $u->url,
                'shortUrl' => $u->short_url,
                'ip' => $u->ip,
                'click' => $u->views_count,
                'banned' => $bannedIps->has($u->ip)
            ];
        }

        $urlGenerated = Url::query()
            ->withCount('views')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $forbiddens = Forbidden::all();

        $stats = [
            'urls' => Url::count(),
            'clicks' => View::count(),
            'bans' => $bans->count(),
            'keywords' => $forbiddens->count(),
        ];

        return view('admin.dashboard',
            ['topUrl' => $data, 'bans' => $bans, 'bannedIps' => $bannedIps, 'forbiddens' => $forbiddens,
                'urlGenerated' => $urlGenerated, 'stats' => $stats]);
    }
}
