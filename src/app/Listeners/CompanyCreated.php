<?php

namespace Solunes\Business\App\Listeners;

class CompanyCreated {

    public function handle($event) {
    	// Revisar que no esté de manera externa
    	if($event&&!$event->external_code){
    		$hubspot = new \Solunes\Business\App\Controllers\Integrations\HubspotController;
            $event = $hubspot->exportCompanyCreated($event->id);
            return $event;
    	}
    }

}
