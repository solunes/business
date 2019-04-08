<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {
	
	protected $table = 'countries';
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
    
    public function regions() {
        return $this->hasMany('Solunes\Business\App\Region');
    }

}