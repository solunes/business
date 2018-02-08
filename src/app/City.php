<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class City extends Model {
	
	protected $table = 'cities';
	public $timestamps = true;

	public $translatedAttributes = ['name'];
    protected $fillable = ['name', 'active', 'region_id'];

    use \Dimsav\Translatable\Translatable;


	/* Creating rules */
	public static $rules_create = array(
		'region_id'=>'required',
		'name'=>'required',
		'active'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'region_id'=>'required',
		'name'=>'required',
		'active'=>'required',
	);
    
    public function region() {
        return $this->belongsTo('Solunes\Business\App\Region');
    }
        
    public function agencies() {
        return $this->hasMany('Solunes\Business\App\Agency');
    }

    public function contacts() {
        return $this->hasMany('Solunes\Business\App\Contact');
    }

}