<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO: 
 * Implement email verification (activate account no matter what, but remind user to verify)
 * Use common brute-forced usernames as honey pots (admin, user, etc...).
 * 
 */
class Signup extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		#$user = $this->dataStore->find('user', 3);
		#$this->sendVerificationMail($user);
		$this->render();
	}

	public function confirm($hash){
		$user = $this->dataStore->getRepo('user')->getUnverifiedUser($hash);
		if($user){
			$user->verify();
			$user->setLastModified(time());
			$this->dataStore->update($user);
			$this->dataStore->commit();
			Utils::redirect_response('/signup', Response::success('Your email has been verified. Now you are ready to purchase your dream car!'));
		}
		else{
			Utils::redirect_response('/signup', Response::error('This verification code is not valid, or no longer active.'));
		}
	}

	public function create(){
		$this->createUser();
	}

	protected function createUser(){
		$this->load->helper('form');
		$this->load->library('authenticator', '', 'auth');
		$this->load->library('user_agent', '', 'agent');
		$salt = SecurityUtils::generateRandomToken();
		$data = [
			'firstName'        => set_value('firstName'),
			'lastName'         => set_value('lastName'),
			'email'            => set_value('email'),
			'salt'             => $salt,
			'password'         => $this->auth->hash_passwd(set_value('password'), $salt),
			'agent'            => $this->agent->agent_string(),
			'referrer'         => $this->agent->referrer(),
			'verificationHash' => SecurityUtils::generateRandomToken()
		];
		$newUser = new models\User($data);
		$this->dataStore->save($newUser);
		$this->dataStore->commit();
		$this->sendVerificationMail($newUser);
	}

	/**
	 * TODO: Load email template and use it
	 */
	protected function sendVerificationMail($user){
		$title = 'Welcome to AutoOffer!';
		$this->build([
			'name' => $user->getFirstName(),
			'recipient' => $user->getEmail(),
			'confirmationUrl' => 'somelink_with_hash.dk?hash=' . $user->getVerificationHash()
		]);
		$this->loadHeader(false);
		$this->loadFooter(false);
		$template = $this->generate('templates/confirmation_email', $title);

		$this->load->library('email');
		$this->email->initialize(array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.sendgrid.net',
			'smtp_user' => 'wordenskjold',
			'smtp_pass' => '1409FN__',
			'smtp_port' => 587,
			'crlf' => "\r\n",
			'newline' => "\r\n"
		));
		$this->email->from('info@autooffer.dk', 'AutoOffer');
		$this->email->to($user->getEmail());
		$this->email->subject($title);
		$this->email->message($template);	
		$this->email->send();
		#log_message('debug', $this->email->print_debugger());
		#$template = $this->load->view('templates/confirmation_email', '', true);
		#print $template;
	}
}

/* End of file  */
/* Location: ./application/controllers/ */