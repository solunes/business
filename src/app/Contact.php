<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {
	
	protected $table = 'contacts';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'first_name'=>'required',
		'last_name'=>'required',
		'type'=>'required',
		'city_id'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'first_name'=>'required',
		'last_name'=>'required',
		'type'=>'required',
		'city_id'=>'required',
	);
        
    public function region() {
        return $this->belongsTo('Solunes\Business\App\Region');
    }

    public function city() {
        return $this->belongsTo('Solunes\Business\App\City');
    }

    public function company() {
        return $this->belongsTo('Solunes\Business\App\Company');
    }
   
    public function user() {
        return $this->hasOne('App\User');
    }

}