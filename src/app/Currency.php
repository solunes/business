<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {
	
	protected $table = 'currencies';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'code'=>'required',
		'name'=>'required',
		'plural'=>'required',
		'type'=>'required',
		'main_exchange'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'code'=>'required',
		'name'=>'required',
		'plural'=>'required',
		'type'=>'required',
		'main_exchange'=>'required',
	);
    
    public function region() {
        return $this->belongsTo('Solunes\Business\App\Region');
    }

}