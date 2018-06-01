<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const SYSTEM_SYSTEMNAME = "systemName";
const SYSTEM_COUNTRYCODE = "countryCode";
const SYSTEM_FULLNAME = "fullName";
const SYSTEM_COLOR = "color";
const SYSTEM_LEVEL = "level";
const SYSTEM_TIER = "tier";

const SYSTEM = "systems";
const SYSTEM_PK = SYSTEM_SYSTEMNAME;

class TMSystem extends Model
{
    protected $table = SYSTEM;
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = SYSTEM_PK;

    public function routes() {
        return $this->hasMany('App\TMRoute', SYSTEM_PK);
    }

    public function connectedRoutes() {
        return $this->hasMany('App\TMConnectedRoute', SYSTEM_PK);
    }
}
