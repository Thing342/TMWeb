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

    public function routes() {
        return $this->belongsToMany('App\TMRoute', 'clinchedRoutes', TRAVELER_PK, 'route')
            ->withPivot('mileage', 'clinched');
    }


    public function regions() {
        return $this->belongsToMany('App\TMRegion', 'clinchedOverallMileageByRegion', TRAVELER_PK, 'region')
            ->withPivot('activeMileage', 'activePreviewMileage');
    }

    public function regionClinchedRoutes() {
        return $this->belongsToMany('App\TMRegion', 'clinchedRoutesByRegion', TRAVELER_PK, 'region')
            ->withPivot('active', 'activePreview');
    }

    public function systems() {
        return $this->belongsToMany('App\TMSystem', 'clinchedSystemMileage', TRAVELER_PK, 'systemName')
            ->withPivot('mileage');
    }

    public function systemClinchedRoutes() {
        return $this->belongsToMany('App\TMSystem', 'clinchedRoutesBySystem', TRAVELER_PK, 'systemName')
            ->withPivot('clinched');
    }
}
