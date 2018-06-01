<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const TRAVELER_TRAVELER = "traveler";
const TRAVELER_OVERALL = "overallActiveMileage";
const TRAVELER_PREVIEW = "overallActivePreviewMileage";

const TRAVELER = "travelers";
const TRAVELER_PK = TRAVELER_TRAVELER;

class TMTraveler extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $table = TRAVELER;
    protected $primaryKey = TRAVELER_PK;

    public function clinchedSegements() {
        return $this->hasMany('App\TMClinchedSegment', TRAVELER_PK);
    }

    public function segments() {
        return $this->belongsToMany('App\TMSegment', 'clinched', TRAVELER_PK, 'segmentID');
    }

    function routes() {
        return $this->belongsToMany('App\TMRoute', 'clinchedRoutes', TRAVELER_PK, 'route')
            ->withPivot('mileage', 'clinched');
    }
}
