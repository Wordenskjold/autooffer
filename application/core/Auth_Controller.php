<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * -- Description --
 *
 * @author Frederik Wordenskjold
 * @version 1.0
 */
class Auth_Controller extends MY_Controller{

	private $requiredRole;

	function __construct(){
		parent::__construct();
		
		/**
		 * Set no-cache headers so pages are never cached by the browser.
		 * This is necessary because if the browser caches a page, the 
		 * login or logout link and user specific data may not change when 
		 * the logged in status changes.
		 */
	 	header('Expires: Wed, 13 Dec 1972 18:37:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');

		/* Require user role by default */
		$this->requireRole(models\User::ROLE_USER);

		/* Load the authenticator */
		$this->load->library('authenticator', '', 'auth');
	}

	/** 
	 * Is the current user logged in?
	 */
	protected function is_logged_in(){
		return $this->auth->check_login($this->requiredRole) !== false;
	}

	/**
	 * Require a specific role
	 */
	protected function requireRole($role){
		$this->requireRole = $role;
	}

	protected function getUser(){
		return $this->auth->getUser();
	}

	protected function authenticate(){
		if(!$this->is_logged_in()){
			$this->session->set_flashdata('return', base_url() . uri_string());
			redirect('/login');
		}
		else{
			if(!$this->getData('user')){
				$this->addUserData();
			}
		}
	}

	private function addUserData(){
		$user = $this->getUser();
		$fields = config_item('selected_profile_attributes');
		$data = [];
		if(!empty($fields)){
			foreach($fields as $field){
				if(property_exists($user,$field)){
					$data[$field] = $user->getAttribute($field);
				}
			}
			$this->addData('user', $data);
		}
	}
}