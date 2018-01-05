<?php

namespace Solunes\Business\App\Listeners;

class DealCreated {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	if($event&&!$event->external_code){
            $event = \Solunes\Business\App\Controllers\Integrations\HubspotController::exportDeal($event);
            return $event;
    	}
    }

}
