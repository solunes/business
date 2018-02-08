<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class CityTranslation extends Model {
    
    protected $table = 'city_translation';
    public $timestamps = false;
    protected $fillable = ['name'];

}