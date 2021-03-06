<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Category extends Model {
	
	protected $table = 'categories';
	public $timestamps = true;

    public $translatedAttributes = ['name','description'];
    protected $fillable = ['name', 'description','level','image'];

    use \Dimsav\Translatable\Translatable;

    use Sluggable, SluggableScopeHelpers;
    public function sluggable(){
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

	/* Creating rules */
	public static $rules_create = array(
        'level'=>'required',
        'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
	);
	   
    public function children() {
        return $this->hasMany('Solunes\Business\App\Category', 'parent_id')->orderBy('order','ASC');
    }

    public function parent() {
        return $this->belongsTo('Solunes\Business\App\Category', 'parent_id');
    }

    public function agency() {
        return $this->belongsTo('Solunes\Business\App\Agency');
    }

    public function variation() {
        return $this->belongsTo('Solunes\Business\App\Variation');
    }

    public function category_variation() {
        return $this->belongsToMany('Solunes\Business\App\Variation', 'category_variation', 'category_id', 'variation_id');
    }

    public function products() {
        return $this->hasMany('Solunes\Product\App\Product')->where('active', 1);
    }

    public function product_bridges() {
        return $this->hasMany('Solunes\Business\App\ProductBridge')->where('active', 1);
    }

}