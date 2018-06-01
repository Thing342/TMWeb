<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const REGION_CODE = "code";
const REGION_NAME = "name";
const REGION_COUNTRY = "country";
const REGION_CONTINENT = "continent";

const REGION = "regions";
const REGION_PK = REGION_CODE;

class TMRegion extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $table = REGION;
    protected $primaryKey = REGION_PK;

    public function routes() {
        return $this->hasMany('App\TMRoute', 'region');
    }
}
