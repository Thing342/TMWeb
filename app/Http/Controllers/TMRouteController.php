<?php

namespace App\Http\Controllers;

use App\TMConnectedRoute;
use App\TMRegion;
use App\TMRoute;

use App\TMSystem;
use App\TMTraveler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TMRouteController extends Controller
{
    function indexBySystem(TMSystem $system)
    {
        $routes = $system->routes()
            ->with(['system', '_region'])
            ->orderBy('root')
            ->get();
        return view('hb.routes', [
            'title' => $system->fullName,
            'routes' => $routes,
            'backstack' => [
                ['Highway Browser', route('route.browser')],
                ['Systems', route('system.index')]
            ]
        ]);
    }

    function indexByRegion(TMRegion $region)
    {
        $routes = $region->routes()
            ->with(['system', '_region'])
            ->orderBy('root')
            ->get()
            ->sortBy(function ($it) {return $it->system->tier . $it->root;});
        return view('hb.routes', [
            'title' => $region->name,
            'routes' => $routes,
            'backstack' => [
                ['Highway Browser', route('route.browser')],
                ['Regions', route('region.index')]
            ]
        ]);
    }

    function index(TMSystem $system, string $region)
    {
        $routes = $system->routes()
            ->where('region', '=', $region)
            ->with(['system', '_region'])
            ->orderBy('root')
            ->get();

        return view('hb.routes', [
            'title' => $system->fullName . ' in ' . $region,
            'routes' => $routes,
            'backstack' => [
                ['Highway Browser', route('route.browser')],
                ['Systems', route('system.index')],
                [$system->fullName, route('route.index.system', $system->systemName)],
                [$region, route('route.index.region', $region)],
            ]
        ]);
    }

    function searchResults($query, $syscode = '_', $rgcode = '_') {
        $exactMatch = TMRoute::query()->where('root', '=', $query)->first();
        if($exactMatch) {
            return redirect()->route('route.read', $exactMatch->root);
        }

        // TODO: better searching algorithm

        $close = TMRoute::query();

        if($syscode == '_') $close = $close->where('systemName', '=', $syscode);
        if($rgcode == '_') $close = $close->where('region', '=', $rgcode);

        $close = $close
            ->with(['system', '_region'])
            ->where('root', 'LIKE', "%$query%")
            ->where('route', 'LIKE', "%$query%", 'or')
            ->orderBy('root')
            ->get()
            ->sortBy(function ($it) {return $it->system->tier . $it->root;});

        return view('hb.routes', [
            'title' => "Results for '$query':",
            'routes' => $close,
            'backstack' => [
                ['Highway Browser', route('route.browser')],
            ]
        ]);
    }

    function browser(Request $request)
    {
        if ($request->input('_token', false)) {
            $syscode = $request->input('sys', '_');
            $rgcode = $request->input('rg', '_');
            $query = $request->input('query', '_');

            if($query != '_') return $this->searchResults($query, $syscode, $rgcode);

            if ($syscode != '_' && $rgcode != '_') {
                return redirect()->route('route.index', [$syscode, $rgcode]);
            } elseif ($syscode != '_') {
                return redirect()->route('route.index.system', $syscode);
            } elseif ($rgcode != '_') {
                return redirect()->route('route.index.region', $rgcode);
            }
        }

        $regions = TMRegion::orderBy('country')->orderBy('name')->get();
        $systems = TMSystem::orderBy('countryCode')->orderBy('fullName')->get();

        return view('hb.browser', ['systems' => $systems, 'regions' => $regions]);
    }

    function read(TMRoute $route, Request $request) {
        $route->load(['system', '_region', 'waypoints', 'segments', 'segments.clinched']);

        $context = ['route' => $route];

        $context['system'] = $route->system;
        $context['region'] = $route->_region;
        $context['waypoints'] = $route->waypoints;
        $context['segments'] = $route->segments;

        $context['travelers'] = $route->travelers;
        $context['clinchers'] = $route->travelers()->wherePivot('clinched', 1)->get();
        $context['total_travelers_count'] = TMTraveler::count();

        $context['travelPct'] = sizeof($context['travelers']) / $context['total_travelers_count'] * 100;
        $context['clinchedPct'] = sizeof($context['clinchers']) / $context['total_travelers_count'] * 100;

        $context['sumDistanceDriven'] = 0.0;
        foreach ($context['travelers'] as $traveler) {
            $context['sumDistanceDriven'] += $traveler->pivot->mileage;
        }

        if(sizeof($context['travelers']) > 0) {
            $context['clinchedPctOfDriven'] = $context['clinchedPct'] / $context['travelPct'] * 100;
            $context['meanDistanceDriven'] = $context['sumDistanceDriven'] / sizeof($context['travelers']);
            $context['meanDistanceDrivenPct'] = $context['meanDistanceDriven'] / $route->mileage * 100;
        } else {
            $context['clinchedPctOfDriven'] = 0.0;
            $context['meanDistanceDriven'] = 0.0;
            $context['meanDistanceDrivenPct'] = 0.0;
        }

        $context['waypointStats'] = [];
        foreach ($context['segments'] as $segment) {
            $waypointName = $segment->waypoint1;
            $context['waypointStats'][$waypointName] = [
                'clinched' => $segment->clinched,
                'clinchedPct' => (sizeof($context['travelers']) > 0) ?
                    (sizeof($segment->clinched) / sizeof($context['travelers']) * 100)
                    : 0.0
            ];
        }

        $context['backstack'] = [
            ['Highway Browser', route('route.browser')],
            ['Systems', route('system.index')],
            [$context['system']->fullName, route('route.index.system', $context['system']->systemName)],
            [$context['region']->name, route('route.index.region', $context['region']->code)],
        ];

        return view('hb.route', $context);
    }

    function waypoints(TMRoute $route)
    {
        return $route->waypoints()
            ->get()
            ->keyBy('pointId');
    }

    function segments(TMRoute $route)
    {
        return $route->segments()
            ->get()
            ->keyBy('segmentId');
    }

    function clinchedSegments(string $route, string $traveler)
    {
        return DB::table("clinched")
            ->join("segments", "segments.segmentId", '=', "clinched.segmentId")
            ->join("routes", "routes.root", '=', "segments.root")
            ->where("routes.root", '=', $route)
            ->where("clinched.traveler", '=', $traveler)
            ->orderBy("clinched.segmentId")
            ->pluck("clinched.segmentId");
    }

    function connectedRoute(TMConnectedRoute $connRte)
    {
        return $connRte->routes;
    }

    function addFilters(Builder $q, ?array $regions, ?array $systems): Builder {
        if ($regions) {
            $q = $q->whereIn('region', $regions);
        }

        if ($systems) {
            $q = $q->whereIn('systemName', $systems);
        }

        return $q;
    }

}
