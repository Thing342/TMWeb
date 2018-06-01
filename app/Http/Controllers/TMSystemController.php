<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TMSystemController extends Controller
{
    function index() {
        $systems = \App\TMSystem::orderBy('countryCode')
            ->orderBy('tier')
            ->orderBy('systemName')
            ->get();

        return view('hb.systems', [
            'systems' => $systems
        ]);
    }

    function read(String $systemName) {
        $system = \App\TMSystem::find($systemName);

        return view('routes', [
            'system' => $system,
            'tmroutes' => $system->routes
        ]);
    }
}
