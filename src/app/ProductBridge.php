<?php

namespace Solunes\Business\App;

use Illuminate\Database\Eloquent\Model;

class ProductBridge extends Model {
    
    protected $table = 'product_bridges';
    public $timestamps = true;
    
    public $translatedAttributes = ['slug','name','internal_url', 'content'];
    protected $fillable = ['product_type','product_id','image','currency_id','price','weight','slug','name','internal_url','content','active'];

    use \Dimsav\Translatable\Translatable;

    /* Creating rules */
    public static $rules_create = array(
        'name'=>'required',
        'product_type'=>'required',
        'product_id'=>'required',
        'active'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'name'=>'required',
        'product_type'=>'required',
        'product_id'=>'required',
        'active'=>'required',
    );

    public function product_bridge_variation() {
        return $this->belongsToMany('Solunes\Business\App\Variation', 'product_variation', 'product_bridge_id', 'variation_id')->withPivot('product_id','quantity','new_price','value');
    }

    public function product_variation() {
        return $this->belongsToMany('Solunes\Business\App\Variation', 'product_variation', 'product_bridge_id', 'variation_id')->withPivot('product_id','quantity','new_price','value');
    }

    public function variation() {
        return $this->belongsTo('Solunes\Business\App\Variation');
    }

    public function variation_option() {
        return $this->belongsTo('Solunes\Business\App\VariationOption');
    }

    public function stockable_product_bridge_variations() {
        return $this->hasMany('Solunes\Business\App\ProductBridgeVariationOption')->groupBy('variation_id');
    }

    public function product_bridge_variation_options() {
        return $this->hasMany('Solunes\Business\App\ProductBridgeVariationOption');
    }

    public function product_bridge_parent() {
        return $this->belongsTo('Solunes\Business\App\ProductBridge');
    }

    public function product_bridge_childs() {
        return $this->hasMany('Solunes\Business\App\ProductBridge','product_bridge_parent_id');
    }

    public function product() {
        return $this->belongsTo('Solunes\Product\App\Product');
    }

    public function category() {
        if(config('business.categories')){
            return $this->belongsTo('Solunes\Business\App\Category');
        } else {
            return $this->belongsTo('App\Category');
        }
    }

    public function currency() {
        return $this->belongsTo('Solunes\Business\App\Currency');
    }

    public function product_bridge_stocks() {
        return $this->hasMany('Solunes\Inventory\App\ProductBridgeStock', 'parent_id');
    }

    public function last_product_bridge_stocks() {
        return $this->hasMany('Solunes\Inventory\App\ProductBridgeStock', 'parent_id')->groupBy('parent_id','agency_id')->orderBy('date','DESC');
    }

    public function getTotalStockAttribute() {
        if(count($this->product_bridge_stocks)>0){
            return $this->product_bridge_stocks->sum('quantity');
        } else {
            return 0;
        }
    }

    public function getProductUrlAttribute() {
        $url = config('business.product_page').'/';
        if(config('business.product_slug')){
            $url .= $this->slug;
        } else {
            $url .= $this->id;
        }
        return $url;
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