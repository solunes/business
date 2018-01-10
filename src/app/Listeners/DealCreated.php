<?php

namespace Solunes\Business\App\Listeners;

class DealCreated {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	if($event&&!$event->external_code){
    		$hubspot = new \Solunes\Business\App\Controllers\Integrations\HubspotController;
            $event = $hubspot->exportDealCreated($event->id);
            return $event;
    	}
    }

}
