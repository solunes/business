<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model {
	
	protected $table = 'agencies';
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
             
    public function children() {
        return $this->hasMany('Solunes\Business\App\Agency', 'parent_id')->orderBy('order','ASC');
    }
    
    public function parent() {
        return $this->belongsTo('Solunes\Business\App\Agency', 'parent_id');
    }     

    public function region() {
        return $this->belongsTo('Solunes\Business\App\Region');
    } 

    public function city() {
        return $this->belongsTo('Solunes\Business\App\City');
    }
   
    public function store() {
        return $this->hasOne('App\Store');
    }   

    public function area() {
        return $this->belongsTo('App\Area');
    }

    public function agency_payment_method() {
        return $this->belongsToMany('Solunes\Payments\App\PaymentMethod', 'agency_payment_method', 'agency_id', 'payment_method_id');
    }
   
    public function agency_shipping() {
        return $this->belongsToMany('Solunes\Sales\App\Shipping', 'agency_shipping', 'agency_id', 'shipping_id');
    }

    public function product_bridge_stocks() {
        return $this->hasMany('Solunes\Inventory\App\ProductBridgeStock');
    }

}