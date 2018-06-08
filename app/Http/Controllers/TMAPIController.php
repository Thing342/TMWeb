<?php

namespace App\Http\Controllers;

use App\TMRoute;
use App\TMTraveler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TMAPIController extends Controller
{
    function pointinfo(Request $request) {
        $ucode = $request->input('u', null);
        $rcode = $request->input('r', null);

        if($rcode) {
            $tmroute = TMRoute::findOrFail($rcode);
            $tmtraveler = TMTraveler::find($ucode);
            return $this->pointinfo_single($tmroute, $request, $tmtraveler);
        } else {
            $tmtraveler = TMTraveler::findOrFail($ucode);
            return $this->pointinfo_multi($tmtraveler, $request);
        }
    }

    private function apply_filters(Builder $query, Request $request): Builder {
        $rgcode = $request->input('rg', null);
        $syscode = $request->input('sys', null);
        $conroot = $request->input('conn', null);

        if ($rgcode) {
            $query = $query->where('region', '=', $rgcode);
        }
        if ($syscode) {
            $query = $query->where('systemName', '=', $syscode);
        }
        if ($conroot) {
            $query = $query
                ->join("connectedRouteRoots", "routes.root", '=', "connectedRouteRoots.root")
                ->where('connectedRouteRoots.firstRoot', '=', $conroot);
        }
        return $query;
    }

    private function pointinfo_multi(TMTraveler $traveler, Request $request)
    {
        $query = TMRoute::query();
        $query = $query->with(['system', 'waypoints', 'segments']);
        $query = $this->apply_filters($query, $request);
        $query = $query->orderBy('root');
        $routes = $query->get();
        $routeNames = $query->pluck('routes.root');

        $clinched = $traveler->segments()
            ->whereIn('segments.root', $routeNames)
            ->pluck('segments.segmentId');

        $resp = [
            'waypoints' => [],

            'newRouteIndices' => [],
            'routeTier' => [],
            'routeColor' => [],
            'routeSystem' => [],

            'traveler' => $traveler->traveler,

            'segments' => [],
            'clinched' => $clinched,

            'mapClinched' => true,
            'genEdges' => true
        ];

        $i = 0;
        $windex = 0;
        $sindex = 0;
        foreach ($routes as $route) {
            $resp['routeTier'][] = $route->system->tier;
            $resp['routeColor'][] = $route->system->color;
            $resp['routeSystem'][] = $route->systemName;
            $resp['newRouteIndices'][] = $windex;

            foreach ($route->waypoints as $waypoint) {
                $resp['waypoints'][] = [
                    'label' => $waypoint->pointName,
                    'lat' => $waypoint->latitude,
                    'lon' => $waypoint->longitude,
                    'visible' => $waypoint->pointName[0] != '+',
                    'elabel' => "",
                    'edgeList' => [],
                    'intersecting' => []
                ];
                $windex++;
            }

            foreach ($route->segments as $segment) {
                $resp['segments'][] = $segment->segmentId;
                $sindex++;
            }

            $i++;
        }

        return $resp;
    }

    private function pointinfo_single(TMRoute $route, Request $request, ?TMTraveler $traveler = null) {
        $resp = [
            'waypoints' => [],

            'routeTier' => "",
            'routeColor' => "",
            'routeSystem' => "",

            'segments' => $route->segments()->pluck('segments.segmentId'),

            'mapClinched' => false,
            'genEdges' => true
        ];

        $resp['routeTier'] = $route->system->tier;
        $resp['routeColor'] = $route->system->color;
        $resp['routeSystem'] = $route->systemName;

        $waypointsWithIntersecting = DB::select("SELECT w.*, intersecting.*
FROM waypoints w
  LEFT JOIN waypoints w2 ON (w.latitude = w2.latitude) AND (w.longitude = w2.longitude) AND (w.pointId != w2.pointId)
  LEFT JOIN routes intersecting ON w2.root = intersecting.root
WHERE w.root = :root;", ['root'=>$route->root]);

        $aWaypoint = null;
        $lastId = 0;

        foreach ($waypointsWithIntersecting as $record) {
            if($record->pointId != $lastId) {
                if($aWaypoint != null) {
                    $resp['waypoints'][] = $aWaypoint;
                }

                $aWaypoint = [
                    'id' => $record->pointId,
                    'label' => $record->pointName,
                    'lat' => $record->latitude,
                    'lon' => $record->longitude,
                    'visible' => $record->pointName[0] != '+',
                    'elabel' => "",
                    'edgeList' => [],
                    'intersecting' => []
                ];

                $lastId = $record->pointId;
            }

            if($record->root != null) {
                $aWaypoint['intersecting'][] = [
                    'root' => $record->root,
                    'route' => $record->route,
                    'region' => $record->region,
                    'banner' => $record->banner,
                    'abbrev' => $record->abbrev,
                    'city' => $record->city
                ];
            }
        }

        if($traveler) {
            $resp['mapClinched'] = true;
            $resp['traveler'] = $traveler->traveler;
            $resp['clinched'] = $traveler->segments()
                ->where('root', '=', $route->root)
                ->pluck('segments.segmentId');
        }

        return $resp;
    }
}
