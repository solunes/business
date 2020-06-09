<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
	
	protected $table = 'brands';
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

}