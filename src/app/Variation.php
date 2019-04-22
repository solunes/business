<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model {
	
	protected $table = 'variations';
	public $timestamps = true;

	public $translatedAttributes = ['name','label'];
    protected $fillable = ['name','label','type','subtype','max_choices','optional'];

    use \Dimsav\Translatable\Translatable;

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
			
    public function variation_options() {
        return $this->hasMany('Solunes\Business\App\VariationOption','parent_id');
    }
	
}