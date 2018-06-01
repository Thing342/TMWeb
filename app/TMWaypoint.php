<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const WAYPOINT_POINTID = "pointId";
const WAYPOINT_POINTNAME = "pointName";
const WAYPOINT_LATITUDE = "latitude";
const WAYPOINT_LONGITUDE = "longitude";
const WAYPOINT_ROOT = "root";

const WAYPOINT = "waypoints";
const WAYPOINT_PK = WAYPOINT_POINTID;

class TMWaypoint extends Model
{
    protected $table = WAYPOINT;
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = WAYPOINT_PK;

    public function route() {
        return $this->belongsTo("App\TMRoute", WAYPOINT_ROOT);
    }

    public function segmentsStarted() {
        return $this->hasMany("App\TMSegment", WAYPOINT_PK . "1");
    }

    public function segmentsEnded() {
        return $this->hasMany("App\TMSegment", WAYPOINT_PK . "2");
    }
}
