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
        return $this->belongsToMany('Solunes\Business\App\Variation', 'product_bridge_variation', 'product_bridge_id', 'variation_id');
    }
    
    public function product_bridge_variation_option() {
        return $this->belongsToMany('Solunes\Business\App\VariationOption', 'product_bridge_variation_option', 'product_bridge_id', 'variation_option_id');
    }

    public function product_bridge_channel() {
        return $this->belongsToMany('Solunes\Business\App\Channel', 'product_bridge_channel', 'product_bridge_id', 'channel_id');
    }
    
    public function seller_user() {
        return $this->belongsTo('App\User');
    }
    
    public function agency() {
        return $this->belongsTo('Solunes\Business\App\Agency');
    }

    public function stockable_product_bridge_variations() {
        return $this->hasMany('Solunes\Business\App\ProductBridgeVariationOption')->groupBy('variation_id');
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

    public function pricing_rule() {
        return $this->hasOne('Solunes\Business\App\PricingRule');
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
        if(config('inventory.basic_inventory')){
            return $this->quantity;
        } else {
            if(count($this->product_bridge_stocks)>0){
                return $this->product_bridge_stocks->sum('quantity');
            } else {
                return 0;
            }
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

    public function getFullPriceAttribute() {
        $price = $this->price;
        return $price;
    }

    public function getRealPriceAttribute() {
        $price = $this->price;
        if($this->discount_price){
            $price = $this->discount_price;
        }
        if(config('business.pricing_rules')){
            $range_price = \Solunes\Business\App\PricingRule::where('active','1')->where('type','automatic')->where('item_type','product')->where('product_bridge_id', $this->id)->first();
            if(!$range_price){
                $range_price = \Solunes\Business\App\PricingRule::where('active','1')->where('type','automatic')->where('item_type','category')->where('category_id', $this->category_id)->first();
            }
            if($range_price){
                if($range_price->item_type=='percentage'){
                    $price = $price - ($price * $offer->discount_percentage / 100);
                } else if($range_price->item_type=='normal'){
                    $price = $price - $offer->discount_value;
                }
            } 
        }
        if($price<0){
            $price = 0;
        }
        return $price;
    }

}