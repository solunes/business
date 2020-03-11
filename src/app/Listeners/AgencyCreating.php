<?php

namespace Solunes\Business\App\Listeners;

class AgencyCreating {

    public function handle($event) {
    	if(config('customer.different_customers_by_agency')&&$event&&!$event->token){
            $event->token = \FuncNode::generateUniqueCode('agency-token',16);
            return $event;
    	}
    }

}
