<?php

namespace Solunes\Business\App\Listeners;

class ContactCreated {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	if($event&&!$event->external_code){
            $event = \Solunes\Business\App\Controllers\Integrations\HubspotController::exportContact($event);
            return $event;
    	}
    }

}
