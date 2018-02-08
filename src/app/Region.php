<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model {
	
	protected $table = 'regions';
	public $timestamps = true;

	public $translatedAttributes = ['name'];
    protected $fillable = ['name', 'active'];

    use \Dimsav\Translatable\Translatable;

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
    
    public function cities() {
        return $this->hasMany('Solunes\Business\App\City');
    }

}