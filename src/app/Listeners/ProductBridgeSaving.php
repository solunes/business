<?php

namespace Solunes\Business\App\Listeners;

class ProductBridgeSaving {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	$internal_url = NULL;
    	if($event){
    		$internal_url .= config('business.product_page').'/';
    		if(config('business.product_slug')){
    			$internal_url .= $event->slug;
    		} else {
    			$internal_url .= $event->id;
    		}
    	}
        $event->internal_url = $internal_url;
        return $event;    	
    }

}
