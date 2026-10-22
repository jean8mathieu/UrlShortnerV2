<?php

namespace App\Http\Controllers;

use App\Models\Bans;
use App\Models\Forbidden;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function dashboard()
    {

        //Getting all the bans from the database
        $bans = Bans::all();

        //Map each banned IP to its ban so the url tables can link to it
        $bannedIps = $bans->keyBy('ip');

        //Top 50 by stored click count (indexed, no counting of the view table)
        $data = [];
        foreach (Url::query()
                     ->where('private', false)
                     ->orderBy('clicks', 'DESC')
                     ->limit(50)
                     ->get() as $u) {

            $data[] = [
                'id' => $u->id,
                'url' => $u->url,
                'shortUrl' => $u->short_url,
                'ip' => $u->ip,
                'click' => $u->clicks,
                'banned' => $bannedIps->has($u->ip)
            ];
        }

        $forbiddens = Forbidden::all();

        //Counting 600k+ urls is slow, so the totals refresh every 5 minutes
        $totals = Cache::remember('dashboard.totals', now()->addMinutes(5), fn () => [
            'urls' => Url::count(),
            'clicks' => (int) Url::sum('clicks'),
        ]);

        $urlGenerated = Url::query()
            ->orderBy('id', 'DESC')
            ->paginate(10, total: $totals['urls']);

        $stats = [
            'urls' => $totals['urls'],
            'clicks' => $totals['clicks'],
            'bans' => $bans->count(),
            'keywords' => $forbiddens->count(),
        ];

        return view('admin.dashboard',
            ['topUrl' => $data, 'bans' => $bans, 'bannedIps' => $bannedIps, 'forbiddens' => $forbiddens,
                'urlGenerated' => $urlGenerated, 'stats' => $stats]);
    }
}
