<?php

namespace App\Http\Controllers;

use App\TMRegion;
use App\TMRoute;
use App\TMTraveler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TMTravelerController extends Controller
{
    function changeUser(Request $request)
    {
        $oldUser = $request->session()->get('user', null);
        $newUser = $request->get('user', null);

        if ($newUser == "") {
            $request->session()->put('user', null);
        } else if ($newUser != null && $newUser != $oldUser) {
            $request->session()->put('user', $newUser);
        }

        return back();
    }

    function mapview(TMTraveler $traveler, Request $request)
    {
        $rgcode = $request->input('rg', null);
        $syscode = $request->input('sys', null);
        $conroot = $request->input('conn', null);

        $query = TMRoute::query();
        $query = $query->with('system');
        $query = $query->with(['travelers' => function ($query) use ($traveler) {
            $query->where('clinchedRoutes.traveler', '=', $traveler->traveler);
        }]);

        $apiQueryString = "?u=" . $traveler->traveler;
        if ($rgcode) {
            $query = $query->where('region', '=', $rgcode);
            $apiQueryString .= "&rg=" . $rgcode;
        }
        if ($syscode) {
            $query = $query->where('systemName', '=', $syscode);
            $apiQueryString .= "&sys=" . $syscode;
        }
        if ($conroot) {
            $query = $query->with(['connectedRoutes' => function ($query) use ($conroot) {
                $query->where('connectedRoutes.firstRoot', '=', $conroot);
            }]);
        }

        $routes = $query->get()->sortBy(function ($it) {
            return $it->system->tier . $it->systemName . $it->root;
        });

        $context = [
            'traveler' => $traveler,
            'routes' => $routes,
            'apiQueryString' => $apiQueryString
        ];

        return view('mapview', $context);
    }

    function leaderboard(Request $request)
    {
        $travelers = TMTraveler::orderBy('overallActiveMileage', 'DESC')->get();
        $totalMileage = DB::select("SELECT sum(activeMileage) AS activeMileage, sum(activePreviewMileage) AS activePreviewMileage FROM overallMileageByRegion")[0];
        $trav_rank = 1;

        $traveler = null;
        if ($request->session()->get('user')) {
            $traveler = TMTraveler::find($request->session()->get('user'));

            $i = 1;
            foreach ($travelers as $t) {
                if ($t->traveler == $traveler->traveler) {
                    $trav_rank = $i;
                    break;
                }
                $i += 1;
            }
        }

        return view('user.leaderboard', [
            'travelers' => $travelers,
            'totals' => $totalMileage,
            'traveler' => $traveler,
            'trav_rank' => $trav_rank
        ]);
    }

    function read(TMTraveler $traveler) {
        $traveler->load(['regions', 'systems', 'regionClinchedRoutes', 'systemClinchedRoutes', 'routes']);
        $regionTotals = DB::table('overallMileageByRegion')->get()->keyBy('region');
        $systemTotals = DB::table('systemMileage')->get()->keyBy('systemName');

        return view('user.user', [
            'traveler' => $traveler,
            'regionTotals' => $regionTotals,
            'systemTotals' => $systemTotals
        ]);
    }

    function clinchedSegments(TMTraveler $traveler, string $root)
    {
        return $traveler->segments()
            ->where('root', '=', $root)
            ->orderBy("clinched.segmentId")
            ->pluck("clinched.segmentId");
    }

    function clinchedRoutes(TMTraveler $traveler)
    {
        return $traveler->routes;
    }
}
