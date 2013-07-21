<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('authenticator', '', 'auth');
	}

	/**
	 * TOOD: Create validation template for login, and use it
	 */
	public function index(){
		if($this->auth->check_login(models\User::ROLE_USER)){
			redirect('/');
		}
		$submitted = $this->input->post('submitted');
		if(!empty($submitted)){
			$this->submit();
		}
		else{
			$return = $this->session->flashdata('return') ? $this->session->flashdata('return')
			: '/';
			$this->addData('return', $return);
			$this->render('login', 'Login');
		}
	}

	protected function submit(){
		$this->load->helper('form');
		$redir = $this->input->post('return');
		if($this->auth->login(models\User::ROLE_USER, set_value('email'), set_value('password')) === false){
			Utils::redirect_response($redir, Response::error('Wrong username or password'));
		}
		else{
			redirect($redir);
		}
	}
}

/* End of file  */
/* Location: ./application/controllers/ */