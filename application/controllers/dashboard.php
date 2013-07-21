<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Auth_Controller {

	public function __construct(){
		parent::__construct();
		$this->requireRole(models\User::ROLE_USER);
		$this->authenticate();
	}

	public function index(){
		$user = $this->auth->getUser();
		switch($user->getRole()){
			case models\User::ROLE_USER: 
				$this->user_dashboard(); 
				break;
			case models\User::ROLE_DEALER:
				$this->dealer_dashboard();
				break;
			case models\User::ROLE_ADMIN:
				$this->admin_dashboard();
				break;
		}
	}

	protected function user_dashboard(){

	}

	protected function dealer_dashboard(){

	}

	protected function admin_dashboard(){
		print 'admin dashboard';
	}
}

/* End of file  */
/* Location: ./application/controllers/ */