<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {
	
	protected $table = 'channels';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'type'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'type'=>'required',
	);

    public function product_bridge_channel() {
        return $this->belongsToMany('Solunes\Business\App\ProductBridge', 'product_bridge_channel', 'channel_id', 'product_bridge_id');
    }

}