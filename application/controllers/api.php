<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO: Protect private endpoints (Like translator_get() and elasticsearch_get()).
 * 		 They should only be callable from localhost.
 */
class API extends REST_Controller {
	
	function user_post(){
		echo "Hello, you just posted the following user: ";
		print_r($this->input->post());
	}

	function user_get(){
		$this->response('dede', 200);
	}

	function elasticsearch_get($index, $search){
		$this->load->library('elasticsearch/elasticsearch', '', 'els');
		$result = $this->els->find($index, $search);
		$this->response($result, 200);
	}

	function translator_get(){
		if(sizeof($GLOBALS['LANG']->language) === 0){
			$this->lang->load('common');
		}
		$this->response($this->lang->language, 200);
	}

	function landing_post($func){
		if($func === 'subscribe'){
			$email = $this->input->post('email');
			$modelMake = $this->input->post('make_model');
			$subscriber = new models\PrelaunchSubscriber([
				'email' => $email,
				'modelMake' => $modelMake
			]);
			$this->dataStore->save($subscriber);
			$response = $this->dataStore->commit() 
				? Response::success('subscription_success')
				: Response::error('subscription_error');
			Utils::redirect_response('/', $response);
		}
	}
}

/* End of file  */
/* Location: ./application/controllers/ */