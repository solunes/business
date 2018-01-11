<?php

namespace Solunes\Business\App\Controllers\Integrations;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HubspotController extends Controller {

	protected $request;
	protected $url;

	public function __construct() {
	  //$this->middleware('auth');
	  //$this->middleware('permission:dashboard');
	  //$this->prev = $url->previous();
	  $this->module = 'admin';
	  $this->company_fields = ['name','industry','domain','phone','description'];
	  $this->contact_fields = ['firstname','lastname','email','jobtitle','phone'];
	  //$this->deal_fields = ['firstname','lastname','email','jobtitle','phone'];
	}

	// Webhook
	public function postHubspotWebhook(Request $request) {
		$return = ['created'=>false];
		if($request->subscriptionType=='contact.creation'){
			$return = $this->postContactCreated($request->objectId);
		} else if($request->subscriptionType=='company.creation'){
			$return = $this->postCompanyCreated($request->objectId);
		} else if($request->subscriptionType=='deal.creation'){
			//$return = $this->postDealCreated($request->objectId);
		}
		return $return;
	}

	// Import All Companies from HubSpot
	public function getImportCompanies($count = 50) {
		$node = \FuncNode::get_node('company');
		$array = $this->company_fields;
		$hubspot = $this->initiateHubspot();
		$all_array = $this->generateQueryAllArray($array, 'company', $count);
		$response = $hubspot->companies()->all($all_array);
		$count = $this->processImportMany($response->companies, $array, $node);
		return ['done'=>true, 'recieved_count'=>count($response->companies), 'processed_count'=>$count];
	}

	// Import All Contacts from HubSpot
	public function getImportContacts($count = 50) {
		$node = \FuncNode::get_node('contact');
		$array = $this->contact_fields;
		$hubspot = $this->initiateHubspot();
		$all_array = $this->generateQueryAllArray($array, 'contact', $count);
		$response = $hubspot->contacts()->all($all_array);
		$count = $this->processImportMany($response->contacts, $array, $node);
		return ['done'=>true, 'recieved_count'=>count($response->contacts), 'processed_count'=>$count];
	}

	// Import All Companies from HubSpot
	/*public function getImportDeals() {
		$items = \HubSpot::company()->all();
        $array = ['name','industry','domain','phone','description'];
		foreach($items as $item){
			$properties = $item->properties;
	        $main_array = \Business::importHubspotProperty($properties, $array);
	        $main_array['type'] = 'customer';
		}
		foreach($item->associations as $property_name => $property){
			if($property_name=='associatedCompanyIds'){
				$company_ids = $property;
			} else if($property_name=='associatedVids') {
				$contact_ids = $property;
			}
		}
		\Business::generateDeal($main_array, $main_array['id'], $company_ids, $contact_ids);
		return ['created'=>true];
	}*/

	// Import Company from HubSpot
	public function postCompanyCreated($id) {
		$node = \FuncNode::get_node('company');
		$array = $this->company_fields;
		$hubspot = $this->initiateHubspot();
		$all_array = $this->generateQueryAllArray($array, 'company', $count);
		$response = $hubspot->companies()->getById($id, $all_array);
		$count = $this->processImportOne($response, $array, $node);
		return ['created'=>true];
	}

	// Import Contact from HubSpot
	public function postContactCreated($id) {
		$node = \FuncNode::get_node('contact');
		$array = $this->contact_fields;
		$hubspot = $this->initiateHubspot();
		$all_array = $this->generateQueryAllArray($array, 'contact', $count);
		$response = $hubspot->contacts()->getById($id, $all_array);
		$count = $this->processImportOne($response, $array, $node);
		return ['created'=>true];
	}

	// Import Deal from HubSpot
	/*public function postDealCreated($id) {
		$item = \HubSpot::deal()->getById($id);
		$properties = $item->properties;
        $array = ['firstname','lastname','email','jobtitle','phone'];
        $main_array = \Business::importHubspotProperty($properties, $array);
		foreach($item->associations as $property_name => $property){
			if($property_name=='associatedCompanyIds'){
				$company_ids = $property;
			} else if($property_name=='associatedVids') {
				$contact_ids = $property;
			}
		}
		\Business::generateDeal($main_array, $id, $company_ids, $contact_ids);
		return ['created'=>true];
	}*/

	// Export Company to HubSpot
	public function exportCompanyCreated($id) {
		$item = \Solunes\Business\App\Company::find($id);
        $array = $this->company_fields;
        $this->processExportOne($item, $array, 'companies');
		return ['created'=>true];
	}

	// Export Contact to HubSpot
	public function exportContactCreated($id) {
		$item = \Solunes\Business\App\Contact::find($id);
        $array = $this->contact_fields;
        $this->processExportOne($item, $array, 'contacts');
		return ['created'=>true];
	}

	// Export Deal to HubSpot
	/*public static function exportDealCreated($id) {
		$item = \Solunes\Business\App\Deal::find($id);
		\Business::exportDeal($item);
		return ['created'=>true];
	}*/

	/* Hubspot Library:
	Libreria utilizada por el controlador para las funciones de Integración de Hubspot */

	// Generate Hubspot Query
	public function initiateHubspot() {
		$hubspot = \SevenShores\Hubspot\Factory::create(config('business.hubspot_api_key'));
		return $hubspot;
	}

	// Generate Hubspot Query To All
	public function generateQueryAllArray($properties, $type, $count = 50) {
		if($type=='contact'){
			$property_key = 'property';
		} else {
			$property_key = 'properties';
		}
		$array = [
		    'count'     => $count,
		    $property_key  => $properties,
		    //'vidOffset' => 123456,
		];
		return $array;
	}

	// Generate Hubspot Query To All
	public function processImportMany($items, $array, $node) {
		$count = 0;
		foreach($items as $item){
			if($this->processImportOne($item, $array, $node)){
				$count++;
			}
		}
		return $count;
	}

	// Generate Hubspot Import Query for One
	public function processImportOne($item, $array, $node) {
		$created = false;
		$properties = $item->properties;
        $main_array = $this->importHubspotProperty($properties, $array);
        if(count($main_array)>0){
	        $main_array['type'] = 'customer';
	        $identifiers = $this->getIdentifiers($item, $node);
	        $model = \FuncNode::node_check_model($node);
			$this->putInDatabase($model, $main_array, $identifiers);
			$created = true;
        }
		return $created;
	}

	// Generate Hubspot Export Query for One
	public function processExportOne($item, $array, $node) {
        $properties = $this->generateHubspotField($item, $node, $array);
        $fixed_item = $properties;
        $item = $this->generateHubspotQuery($node, $item, $fixed_item);
		return $item;
	}

	// Puts Item in Database or Replace if Exists
	public function putInDatabase($model, $main_array, $identifiers) {
		$external_code = NULL;
		// Determinar si existe en la base de datos
		$item = $model;
		$operation_where = true;
		foreach($identifiers as $identifier_name => $identifier){
			if($identifier_name=='external_code'){
				$external_code = $identifier;
			}
			if($operation_where){
				$operation = 'where';
				$operation_where = false;
			} else {
				$operation = 'orWhere';
			}
			$item = $item->$operation($identifier_name, $identifier);
		}
		$item = $item->first();
		// Si no existe, crearlo
        if(!$item){
            $item = new $model;
        }
		// Poner y llenar todos los campos
        $item->external_code = $external_code;
        foreach($main_array as $array_key => $array_item){
            $item->$array_key = $array_item;
        }
        $item->save();
        return $item;
	}

    public function importHubspotProperty($properties, $array) {
    	$object = [];
        foreach($array as $field){
            if(isset($properties->$field)){
                $value = $properties->$field;
                $object[$field] = $value->value;
            }
        }
        return $object;
    }

    public function generateHubspotField($item, $type, $array) {
    	if($type=='contacts'){
    		$property_key = 'property';
    	} else {
    		$property_key = 'name';
    	}
        foreach($array as $field){
            if($value = $item->$field){
                $properties[] = [$property_key=>$field, 'value'=>$value];
            }
        }
        return $properties;
    }

	// Get Mass Query Formatted Identifiers
    public function generateHubspotQuery($type, $item, $fixed_item) {
		$hubspot = $this->initiateHubspot();
        //$fixed_item = json_encode($fixed_item);
        if($item->external_code){
            $action = 'update';
            $response = $hubspot->$type()->$action($item->external_code, $fixed_item);
        } else {
            $action = 'create';
            $response = $hubspot->$type()->$action($fixed_item);
            if($data = $response->data){
            	if($identifiers = $this->getIdentifiers($data, $type)){
		            $item->external_code = $identifiers['external_code'];
		            $item->save();
            	}
            }
        }
        return $item;
    }

	// Get Mass Query Formatted Identifiers
	public function getIdentifiers($item, $type) {
		if($type=='contacts'){
			$profiles = $item->{"identity-profiles"};
			$return = [];
			foreach($profiles as $profile){
				foreach($profile->identities as $identity){
					if(isset($identity->type)){
						$name = $identity->type;
						if($name=='LEAD_GUID'){
							$name = 'external_code';
						}
						$name = strtolower($name);
						$return[$name] = $identity->value;
					}
				}
			}
		} else if($type=='companies') {
			$return['external_code'] = $item->companyId;
		} else if($type=='deals') {
			$return['external_code'] = $item->dealId;
		}
		return $return;
	}

}