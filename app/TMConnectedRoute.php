<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const CONNRTE_SYSTEMNAME = "systemName";
const CONNRTE_ROUTE = "route";
const CONNRTE_BANNER = "banner";
const CONNRTE_GROUPNAME = "groupname";
const CONNRTE_FIRSTROOT = "firstRoot";
const CONNRTE_MILEAGE = "mileage";

const CONNRTE = "connectedRoutes";
const CONNRTE_PK = CONNRTE_FIRSTROOT;

class TMConnectedRoute extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $table = CONNRTE;
    protected $primaryKey = CONNRTE_PK;

    function routes() {
        return $this->belongsToMany('App\TMRoute', 'connectedRouteRoots', 'firstRoot', 'root');
    }

    function system() {
        return $this->belongsTo('App\TMSystem', CONNRTE_SYSTEMNAME);
    }
}
