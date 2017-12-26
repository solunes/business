<?php

namespace Solunes\Business\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Asset;

class CustomAdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	public function getIndex() {
		$user = auth()->user();
		//$array['tasks'] = $user->active_business_tasks;
		$array['tasks'] = \Solunes\Business\App\BusinessTask::limit(2)->get();
		$array['active_issues_businesss'] = \Solunes\Business\App\Business::has('active_business_issues')->with('active_business_issues')->get();
      	return view('business::list.dashboard', $array);
	}

	/* Módulo de Proyectos */

	public function allBusinesss() {
		$array['items'] = \Solunes\Business\App\Business::get();
      	return view('business::list.businesss', $array);
	}

	public function findBusiness($id, $tab = 'description') {
		if($item = \Solunes\Business\App\Business::find($id)){
			$array = ['item'=>$item, 'tab'=>$tab];
      		return view('business::item.business', $array);
		} else {
			return redirect($this->prev)->with('message_error', 'Item no encontrado');
		}
	}

	public function findBusinessTask($id) {
		if($item = \Solunes\Business\App\BusinessTask::find($id)){
			$array = ['item'=>$item];
      		return view('business::item.business-task', $array);
		} else {
			return redirect($this->prev)->with('message_error', 'Item no encontrado');
		}
	}

	public function findProjecIssue($id) {
		if($item = \Solunes\Business\App\BusinessIssue::find($id)){
			$array = ['item'=>$item];
      		return view('business::item.business-issue', $array);
		} else {
			return redirect($this->prev)->with('message_error', 'Item no encontrado');
		}
	}

	public function allWikis($business_type_id = NULL, $wiki_type_id = NULL) {
		$array['business_type_id'] = $business_type_id;
		$array['wiki_type_id'] = $wiki_type_id;
		if($business_type_id&&$wiki_type_id){
			$array['items'] = \Solunes\Business\App\Wiki::where('business_type_id',$business_type_id)->where('wiki_type_id',$wiki_type_id)->get();
		} else if($business_type_id){
			$array['items'] = \Solunes\Business\App\WikiType::get();
		} else {
			$array['items'] = \Solunes\Business\App\BusinessType::get();
		}
      	return view('business::list.wikis', $array);
	}

	public function findWiki($id) {
		if($item = \Solunes\Business\App\Wiki::find($id)){
			$array = ['item'=>$item];
      		return view('business::item.wiki', $array);
		} else {
			return redirect($this->prev)->with('message_error', 'Item no encontrado');
		}
	}

}