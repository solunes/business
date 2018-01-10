<?php

namespace Solunes\Business\App\Listeners;

class ContactCreated {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	if($event&&!$event->external_code){
    		$hubspot = new \Solunes\Business\App\Controllers\Integrations\HubspotController;
            $event = $hubspot->exportContactCreated($event->id);
            return $event;
    	}
    }

}
