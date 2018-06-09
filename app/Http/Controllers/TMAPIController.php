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

    private function routeinfo($route) {
        return [
            "root" => $route->root,
            "route" => $route->route,
            "systemName" => $route->systemName,
            "banner" => $route->banner,
            "abbrev" => $route->abbrev,
            "city" => $route->city
        ];
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
        $query = $query->with(['segments.clinched' => function($query) use($traveler) {
            $query->where('clinched.traveler', '=', $traveler->traveler);
        }]);
        $query = $this->apply_filters($query, $request);
        $routes = $query->cursor();

        $resp = [];
        foreach ($routes as $route) {
            $r = [
                "info" => $this->routeinfo($route),
                "waypoints" => [],
                "segments" => [],
                "tier" => $route->system->tier,
                "color" => $route->system->color,
            ];

            foreach ($route->waypoints as $waypoint) {
                $r['waypoints'][$waypoint->pointId] = [
                    'pointId' => $waypoint->pointId,
                    'label' => $waypoint->pointName,
                    'lat' => $waypoint->latitude,
                    'lon' => $waypoint->longitude,
                    'elabel' => "",
                    'edgeList' => [],
                    'intersecting' => []
                ];
            }

            foreach ($route->segments as $segment) {
                $r['segments'][] = [
                    'segmentId' => $segment->segmentId,
                    'fromID' => $segment['waypoint1'],
                    'toID' => $segment['waypoint2'],
                    'clinched' => (sizeof($segment->clinched) > 0)
                ];
            }

            $resp[] = $r;
        }

        return [
            'mapClinched' => true,
            'genEdges' => true,
            'routes' => $resp
        ];

        /**
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
         * **/
    }

    private function pointinfo_single(TMRoute $route, Request $request, ?TMTraveler $traveler = null) {
        $resp = [
            "info" => $this->routeinfo($route),
            "waypoints" => [],
            "segments" => [],
            "color" => $route->system->color,
            "tier" => $route->system->tier,
        ];

        $wpt_set = DB::select("SELECT w.*, intersecting.*
FROM waypoints w
  LEFT JOIN waypoints w2 ON (w.latitude = w2.latitude) AND (w.longitude = w2.longitude) AND (w.pointId != w2.pointId)
  LEFT JOIN routes intersecting ON w2.root = intersecting.root
WHERE w.root = :root;", ["root" => $route->root]);

        $wpt = null;
        foreach ($wpt_set as $record) {
            if ($wpt == null || $wpt['pointId'] != $record->pointId) {
                if($wpt != null) {
                    $resp['waypoints'][$wpt['pointId']] = $wpt;
                }
                $wpt = [
                    "pointId" => $record->pointId,
                    "label" => $record->pointName,
                    "lat" => $record->latitude,
                    "lon" => $record->longitude,
                    "elabel" => "",
                    "edgeList" => [],
                    "intersecting" => []
                ];
            }

            if($record->root != null) {
                $wpt['intersecting'][] = $this->routeinfo($record);
            }
        }
        if($wpt != null) {
            $resp['waypoints'][$wpt['pointId']] = $wpt;
        }

        $segmentSet = $route->segments()->orderBy('segmentId')->get();
        if($traveler) {
            $clinchedSet = $traveler
                ->segments()
                ->where('root', '=', $route->root)
                ->orderBy('segmentId')
                ->pluck('segments.segmentId');

            $csIndex = 0;
            foreach ($segmentSet as $item) {
                if(($csIndex < sizeof($clinchedSet) ) && ($item->segmentId == $clinchedSet[$csIndex])) {
                    $clinched = true;
                    $csIndex += 1;
                } else {
                    $clinched = false;
                }

                $resp['segments'][] = [
                    'segmentId' => $item->segmentId,
                    'fromID' => $item['waypoint1'],
                    'toID' => $item['waypoint2'],
                    'clinched' => $clinched
                ];
            }
        } else {
            foreach ($segmentSet as $item) {
                $resp['segments'][] = [
                    'segmentId' => $item->segmentId,
                    'fromID' => $item['waypoint1'],
                    'toID' => $item['waypoint2'],
                    'clinched' => false
                ];
            }
        }

        return [
            'mapClinched' => ($traveler != null),
            'genEdges' => true,
            'routes' => [$resp]
        ];
    }
}
