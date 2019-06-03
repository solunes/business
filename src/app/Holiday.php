<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {
	
	protected $table = 'holidays';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'recurrent'=>'required',
		'initial_date'=>'required',
		'end_date'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'recurrent'=>'required',
		'initial_date'=>'required',
		'end_date'=>'required',
	);
    
}