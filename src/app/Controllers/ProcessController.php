<?php

namespace Solunes\Business\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use Validator;
use Asset;
use AdminList;
use AdminItem;
use PDF;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProcessController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->prev = $url->previous();
	}

    public function getCalculateShipping($shipping_id, $city_id, $weight) {
	    $shipping_array = \Sales::calculate_shipping_cost($shipping_id, $city_id, $weight);
	    return $shipping_array;
    }

    public function getCustomerLogout() {
    	if(auth()->check()){
	        Auth::logout();
	        return redirect('inicio')->with('message_success', 'Su sesión fue cerrada correctamente.');
    	} else {
    		return redirect('inicio')->with('message_error', 'No tiene una sesión para cerrar.');
    	}
    }

    public function postCustomerRegistration(Request $request) {
        $user = \Sales::userRegistration($request);
        if(is_string($user)){
       		return redirect($this->prev)->with('message_error', 'Hubo un error al finalizar su registro: '.$user);
        }
	    return redirect('inicio')->with('message_success', 'Su cuenta fue creada correctamente.');
    }

}