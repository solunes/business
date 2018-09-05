<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class ProductBridge extends Model {
    
    protected $table = 'product_bridges';
    public $timestamps = true;
    
    public $translatedAttributes = ['slug','name','internal_url', 'content'];
    protected $fillable = ['product_type','product_id','image','currency_id','price','weight','slug','name','internal_url', 'content'];

    use \Dimsav\Translatable\Translatable;

    /* Creating rules */
    public static $rules_create = array(
        'name'=>'required',
        'type'=>'required',
        'city_id'=>'required',
        'address'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'name'=>'required',
        'type'=>'required',
        'city_id'=>'required',
        'address'=>'required',
    );

    public function product_bridge_variation() {
        return $this->belongsToMany('\App\Variation', 'product_bridge_variation');
    }

    public function currency() {
        return $this->belongsTo('Solunes\Business\App\Currency');
    }

    public function getRealPriceAttribute() {
        $price = $this->price;
        if($offer = $this->product_offer){
            if($offer->type=='discount_percentage'){
                $price = $price - ($price * $offer->value / 100);
            } else if($offer->type=='discount_value'){
                $price = $price - $offer->value;
            }
        }
        return $price;
    }

}