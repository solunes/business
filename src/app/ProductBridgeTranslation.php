<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class ProductBridgeTranslation extends Model {
    
    protected $table = 'product_bridge_translation';
    public $timestamps = false;
    protected $fillable = ['slug','name','internal_url', 'content'];

    use Sluggable, SluggableScopeHelpers;
    public function sluggable(){
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function product_bridge() {
        return $this->belongsTo('Solunes\Business\App\ProductBridge');
    }

}