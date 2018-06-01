<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const ROUTE_SYSTEM_NAME = "systemName";
const ROUTE_REGION = "region";
const ROUTE_ROUTE = "route";
const ROUTE_BANNER = "banner";
const ROUTE_ABBREV = "abbrev";
const ROUTE_CITY = "city";
const ROUTE_ROOT = "root";
const ROUTE_MILEAGE = "mileage";

const ROUTE = "routes";
const ROUTE_PK = ROUTE_ROOT;

class TMRoute extends Model
{
    protected $table = "routes";
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ROUTE_ROOT;

    public function system() {
        return $this->belongsTo('App\TMSystem', ROUTE_SYSTEM_NAME);
    }

    public function _region() {
        return $this->belongsTo('App\TMRegion', ROUTE_REGION);
    }


    public function waypoints() {
        return $this->hasMany('App\TMWaypoint', ROUTE_PK);
    }

    public function visibleWaypoints() {
        return $this->waypoints()->where('pointName', 'NOT LIKE', '+%');
    }

    public function segments() {
        return $this->hasMany('App\TMSegment', ROUTE_PK);
    }

    function connectedRoutes() {
        return $this->belongsToMany('App\TMConnectedRoute', 'connectedRouteRoots', ROUTE_PK, 'firstRoot');
    }

    function travelers() {
        return $this->belongsToMany('App\TMTraveler', 'clinchedRoutes', 'route', 'traveler')
            ->withPivot('mileage', 'clinched');
    }


    public function display_name(): string {
        $name = $this[ROUTE_ROUTE];

        if ($this->banner) {
            $name .= " " . $this->banner;
        }

        if ($this->city) {
            $name .= " (" . $this->city . ")";
        }

        return $name;
    }

    public function list_name(): string {
        //$routeInfo['region'] . " " . $routeInfo['route'] . $routeInfo['banner'] . $routeInfo['abbrev'] .
        return $this->region . " " . $this->route . $this->banner . $this->abbrev;
    }

}
