<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->library('authenticator', '', 'auth');
		$this->auth->logout();
		Utils::redirect_response('/', Response::success('ok'));
	}
}

/* End of file  */
/* Location: ./application/controllers/ */