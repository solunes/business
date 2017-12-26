<?php

namespace Solunes\Business\App\Controllers\Integrations;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HubspotController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	// Webhook
	public function postHubspotWebhook(Request $request) {
		$return = ['created'=>false];
		if($request->subscriptionType=='contact.creation'){
			$return = $this->postContactCreated($request->objectId);
		} else if($request->subscriptionType=='company.creation'){
			$return = $this->postCompanyCreated($request->objectId);
		} else if($request->subscriptionType=='deal.creation'){
			$return = $this->postDealCreated($request->objectId);
		}
		return $return;
	}

	// Webhook Actions
	public function postContactCreated($id) {
		$item = \HubSpot::contacts()->getById($id);
		$print_r($item);
		$prop = $item->properties;
		\Business::generateContact('customer', $prop->firstname->value, $prop->lastname->value, $prop->email->value, $prop->phone->value, ['external_code'=>$id]);
		return ['created'=>true];
	}

	public function postCompanyCreated($id) {
		$item = \HubSpot::company()->getById($id);
		$print_r($item);
		$prop = $item->properties;
		\Business::generateCompany('customer', $prop->name, ['external_code'=>$id]);
		return ['created'=>true];
	}

	public function postDealCreated($id) {
		$item = \HubSpot::deal()->getById($id);
		$print_r($item);
		$prop = $item->properties;
		foreach($item->associations as $property_name => $property){
			if($property_name=='associatedCompanyIds'){
				$company_ids = $property;
			} else if($property_name=='associatedVids')) {
				$contact_ids = $property;
			}
		}
		\Project::generateProject($prop->firstname, $properties->lastname, $item->email, $item->phone, ['external_code'=>$id]);
		return ['created'=>true];
	}

}