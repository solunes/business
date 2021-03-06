<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class VariationOption extends Model {
	
	protected $table = 'variation_options';
	public $timestamps = true;

	public $translatedAttributes = ['name','description'];
    protected $fillable = ['name','description','extra_price','max_quantity'];

    use \Dimsav\Translatable\Translatable;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
	);
	
    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }
		
    public function parent() {
        return $this->belongsTo('Solunes\Business\App\Variation');
    }

    public function variation() {
        return $this->belongsTo('Solunes\Business\App\Variation', 'parent_id');
    }

}