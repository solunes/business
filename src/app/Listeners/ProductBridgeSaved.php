<?php

namespace Solunes\Business\App\Listeners;

class ProductBridgeSaved {

    public function handle($event) {
        /*if($event->product_type=='product'&&$event->product_id){
            $product = $event->product;
            if($product){
                foreach(['product_bridge_variation_option','product_bridge_variation','product_bridge_channel'] as $field_name){
                    $new_field_array = [];
                    foreach($product->$field_name as $field_subitem){
                        $sub_field_name = str_replace('product_bridge_','',$field_name);
                        $sub_field_name .= '_id';
                        \Log::info($sub_field_name);
                        \Log::info($field_subitem->pivot->product_id.' - '.$field_subitem->pivot->$sub_field_name.' - '.$event->id);
                        $new_field_array[$field_subitem->pivot->$sub_field_name] = ['product_bridge_id'=>$event->id];
                    }
                    $product->$field_name()->sync($new_field_array);
                }
            } 
        }*/
        return $event;    	
    }

}
