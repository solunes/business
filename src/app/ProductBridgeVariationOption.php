<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class ProductBridgeVariationOption extends Model {
	
	protected $table = 'product_bridge_variation_options';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'industry'=>'required',
		'type'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'industry'=>'required',
		'type'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Business\App\ProductBridge');
    }

    public function variation() {
        return $this->belongsTo('Solunes\Business\App\Variation');
    }

    public function variation_option() {
        return $this->belongsTo('Solunes\Business\App\VariationOption');
    }

}