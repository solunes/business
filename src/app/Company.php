<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
	
	protected $table = 'companies';
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

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function contacts() {
        return $this->hasMany('Solunes\Business\App\Contact');
    }

    public function sales() {
        return $this->hasMany('Solunes\Sales\App\Sale');
    }

}