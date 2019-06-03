<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class LaborDay extends Model {
	
	protected $table = 'labor_days';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'day'=>'required',
		'initial_time'=>'required',
		'end_time'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'day'=>'required',
		'initial_time'=>'required',
		'end_time'=>'required',
	);

}