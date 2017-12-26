<?php

namespace Solunes\Business\App\Controllers\Integrations;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Asset;

class GitlabController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	private function generateQuery($path) {
        $key_code = config('business.gitlab_api_key');

        // Consulta CURL a Web Service
        $url = 'https://gitlab.com/api/v4/'.$path.'?private_token='.config('business.gitlab_api_key');
        $ch = curl_init();
        $options = array(
          CURLOPT_URL            => $url,
          CURLOPT_POST           => false,
          CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);  

        $result = json_decode($result);
        return $result;
	}

	public function getGroupBusinesss($group_name) {
		$path = 'groups/'.urlencode($group_name).'/businesss';
		return ['results'=>$this->generateQuery($path)];
	}

	public function getBusiness($group_name, $business_name) {
		$path = 'businesss/'.urlencode($group_name.'/'.$business_name);
		return $this->generateQuery($path);
	}

	public function getBusinessCommits($group_name, $business_name) {
		$path = 'businesss/'.urlencode($group_name.'/'.$business_name).'/repository/commits';
		return $this->generateQuery($path);
	}

}