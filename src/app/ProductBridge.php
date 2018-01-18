<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class ProductBridge extends Model {
	
	protected $table = 'product_bridges';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'type'=>'required',
		'city_id'=>'required',
		'address'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'type'=>'required',
		'city_id'=>'required',
		'address'=>'required',
	);
    
    public function city() {
        return $this->belongsTo('Solunes\Business\App\City');
    }

}