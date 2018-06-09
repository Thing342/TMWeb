<?php

namespace App\Http\Controllers;

use App\TMRoute;
use App\TMTraveler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TMTravelerController extends Controller
{
    function changeUser(Request $request) {
        $oldUser = $request->session()->get('user', null);
        $newUser = $request->get('user', null);

        if ($newUser == "") {
            $request->session()->put('user', null);
        } else if($newUser != null && $newUser != $oldUser) {
            $request->session()->put('user', $newUser);
        }

        return back();
    }

    function mapview(TMTraveler $traveler, Request $request) {
        $rgcode = $request->input('rg', null);
        $syscode = $request->input('sys', null);
        $conroot = $request->input('conn', null);

        $query = TMRoute::query();
        $query = $query->with('system');
        $query = $query->with(['travelers' => function($query) use($traveler) {
            $query->where('clinchedRoutes.traveler', '=', $traveler->traveler);
        }]);

        $apiQueryString = "?u=" . $traveler->traveler ;
        if ($rgcode) {
            $query = $query->where('region', '=', $rgcode);
            $apiQueryString .= "&rg=" . $rgcode;
        }
        if ($syscode) {
            $query = $query->where('systemName', '=', $syscode);
            $apiQueryString .= "&sys=" . $syscode;
        }
        if ($conroot) {
            $query = $query->with(['connectedRoutes' => function($query) use($conroot) {
                $query->where('connectedRoutes.firstRoot', '=', $conroot);
            }]);
        }

        $routes = $query->get()->sortBy(function ($it) { return $it->system->tier . $it->systemName . $it->root; });

        $context = [
            'traveler' => $traveler,
            'routes' => $routes,
            'apiQueryString' => $apiQueryString
        ];

        return view('mapview', $context);
    }

    function clinchedSegments(TMTraveler $traveler, string $root) {
        return $traveler->segments()
            ->where('root', '=', $root)
            ->orderBy("clinched.segmentId")
            ->pluck("clinched.segmentId");
    }

    function clinchedRoutes(TMTraveler $traveler) {
        return $traveler->routes;
    }
}
