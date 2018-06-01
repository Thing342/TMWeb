<?php

namespace App\Http\Controllers;

use App\TMTraveler;
use Illuminate\Support\Facades\DB;

class TMTravelerController extends Controller
{
    /*
    function clinchedSegments(string $traveler, string $root) {
        return DB::table("clinched")
            ->join("segments", "segments.segmentId", '=', "clinched.segmentId")
            ->join("routes", "routes.root", '=', "segments.root")
            ->where("routes.root", '=', $root)
            ->where("clinched.traveler", '=', $traveler)
            ->orderBy("clinched.segmentId")
            ->pluck("clinched.segmentId");
    }*/

    function mapview(TMTraveler $traveler) {
        return view('mapview', ['traveler' => $traveler]);
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
