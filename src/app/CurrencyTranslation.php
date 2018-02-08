<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class CurrencyTranslation extends Model {
    
    protected $table = 'currency_translation';
    public $timestamps = false;
    protected $fillable = ['name', 'plural'];

}