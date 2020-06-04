<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model {
	
	protected $table = 'pricing_rules';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'active'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'active'=>'required',
	);
    
    public function category() {
        return $this->belongsTo('Solunes\Business\App\Category');
    }
       
    public function currency() {
        return $this->belongsTo('Solunes\Business\App\Currency');
    }

    public function product_bridge() {
        return $this->belongsTo('Solunes\Business\App\ProductBridge');
    }

}