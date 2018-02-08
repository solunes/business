<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class RegionTranslation extends Model {
    
    protected $table = 'region_translation';
    public $timestamps = false;
    protected $fillable = ['name'];
    
}