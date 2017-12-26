<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
	
	protected $table = 'companies';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'type'=>'required',
		'address'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'type'=>'required',
		'address'=>'required',
	);

    public function contacts() {
        return $this->hasMany('Solunes\Business\App\Contact');
    }

}