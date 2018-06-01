<?php

namespace App\Http\Controllers;

use App\TMRegion;
use Illuminate\Http\Request;

class TMRegionController extends Controller
{
    function index() {
        $regions = \App\TMRegion::all();

        return view('hb.regions', [
            'regions' => $regions
        ]);
    }

    function read(TMRegion $region) {
        return view('region', [
           'region' => $region,
           'tmroutes' => $region->routes
        ]);
    }
}
