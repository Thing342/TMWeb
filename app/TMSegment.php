<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const SEGMENT_SEGMENTID = "segmentId";
const SEGMENT_WAYPOINT1 = "waypoint1";
const SEGMENT_WAYPOINT2 = "waypoint2";
const SEGMENT_ROOT = "root";

const SEGMENT = "segments";
const SEGMENT_PK = SEGMENT_SEGMENTID;

class TMSegment extends Model
{
    protected $table = SEGMENT;
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = SEGMENT_PK;

    public function waypoint1() {
        return $this->belongsTo('App\TMWaypoint', SEGMENT_WAYPOINT1);
    }

    public function waypoint2() {
        return $this->belongsTo('App\TMWaypoint', SEGMENT_WAYPOINT2);
    }

    public function route() {
        return $this->belongsTo('App\TMRoute', SEGMENT_ROOT);
    }

    public function clinched() {
        return $this->belongsToMany('App\TMTraveler', 'clinched', SEGMENT_PK, 'traveler');
    }
}
