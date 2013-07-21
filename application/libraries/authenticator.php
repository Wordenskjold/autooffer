<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * TODO: Cleanup code.
 * Implement delay between each authentication attempt, on multiple signin attempts.
 * Return informational messages.
 * Implement page specific authorization. (Only x can view his offers).
 * Move security methods to SecurityUtils class
 * 
 * We need a statistics table, to log general login failures, to test if there currently is 
 * an abnormal high number of login failures, indicating an ongoing DoS attack. 
 * 
 * 
 *
 * -- Description --
 *
 * @author Frederik Wordenskjold
 * @version 1.0
 */

class Authenticator {
	/**
	 * The CodeIgniter super object
	 */
	protected $CI;

	/**
	 * The logged in User
	 */
	protected $user = null;

	/**
	 * The auth identifier, used to create the user object
	 */
	protected $auth_identifier;

	/**
	 * The status of a login attempt
	 */
	protected $login_error = FALSE;

	// --------------------------------------------------------------

	public function __construct($config){
		$this->CI =& get_instance();

		$this->CI->config->load('authenticator');

		/* Get the auth identifier from the session if it exists */
		$this->auth_identifier = $this->CI->session->userdata('auth_identifier');

		/* Create user object from identifier */
		if(!empty($this->auth_identifier)){
			$this->user = $this->CI->dataStore->find('user', $this->expose_user_id());
		}
	}

	// --------------------------------------------------------------

	/**
	 * Get currently logged in user
	 */
	public function getUser(){
		return $this->user;
	}

	// --------------------------------------------------------------

	/**
	 * Test post of login form 
	 * 
	 * @param   mixed  a user level (int) or an array of user roles
	 * @param   string  the posted username or email address
	 * @param   string  the posted password
	 * @return  mixed  either an object containing the user's data or FALSE
	 */
	public function login($role, $user_string, $user_pass){

		// Get user table data if username or email address matches a record
		if( $user = $this->CI->dataStore->getRepo('user')->find($user_string)) {

			// Confirm user
			if( ! $this->_user_confirmed($user, $role, $user_pass)){

				// Login failed ...
				log_message('debug', 'User was not allowed access');
			}
			else{
				// Set session cookie and HTTP user data delete_cookie
				$this->_maintain_state($user);

				// Send the auth data back to the controller
				return $user;
			}
		}
		else{
			// Login failed ...
			log_message('debug', "\n No matching email or password found for " . $user_string);
		}

		$this->login_error = TRUE;
		
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Verify if user already logged in. 
	 * 
	 * @param   mixed  a user level (int) or an array of user roles
	 * @return  mixed  either an object containing the user's data or FALSE
	 */
	public function check_login($role){

		// Check that the auth identifier is not empty
		if(empty($this->auth_identifier)){
			return FALSE;
		}

		// Get the last user modification time from the session
		$user_last_mod = $this->expose_user_last_mod($this->auth_identifier);

		// Get the user ID from the session
		$user_id = $this->expose_user_id($this->auth_identifier);

		// Get the last login time from the session
		$login_time = $this->expose_login_time($this->auth_identifier);

		/*
		 * Check database for matching user record:
		 * 1) last user modification time matches
		 * 2) user ID matches
		 * 3) login time matches ( not applicable if multiple logins allowed )
		 */

		// If the query produced a match
		if($this->user->checkIdentity($user_last_mod, $user_id, $login_time)) {

			// Confirm user
			if(!$this->user->hasAccess($role)){

				/* Log why this user was blocked */
				log_message('debug','User was not allowed access');
			}
			else{
				// Send the auth data back to the controller
				return $this->user;
			}
		}
		else {
			// Auth Data === FALSE because no user matching in DB ...
			log_message(
				'debug',
				"\n last user modification time from session = " . $user_last_mod . 
				"\n user id from session                     = " . $user_id . 
				"\n last login time from session             = " . $login_time . 
				"\n disallowed multiple logins               = " . ( config_item('disallow_multiple_logins') ? 'true' : 'false' )
			);
		}

		// Unset session
		$this->CI->session->unset_userdata('auth_identifier');

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Create the auth identifier, which contains 
	 * the user ID and last modification time.
	 * 
	 * @param   int  the user ID 
	 * @param   int  an epoch time that the user account was last modified
	 * @return  int  the auth identifier
	 */
	public function create_auth_identifier( $user_id, $user_modified, $login_time ){

		$umod_split = str_split( $user_modified , 5 );

		$login_time_split = str_split( $login_time , 5 );

		return $login_time_split[0] .
			rand(0,9) .
			$umod_split[1] .
			rand(0,9) .
			$user_id .
			rand(0,9) .
			$umod_split[0] .
			rand(0,9) .
			rand(0,9) .
			$login_time_split[1];
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the user ID hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user ID
	 */
	public function expose_user_id($auth_identifier = false){
		if(!$auth_identifier){
			$auth_identifier = $this->auth_identifier;
		}
		$temp = substr( $auth_identifier , 12 );

		return substr_replace( $temp , '' , -13 );
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the last modification time hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user's last modified data
	 */
	public function expose_user_last_mod($auth_identifier = false){
		if(!$auth_identifier){
			$auth_identifier = $this->auth_identifier;
		}
		return substr( $auth_identifier , -12 , 5 ) . substr( $auth_identifier , 6 , 5 );
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the login time hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user's last login time
	 */
	public function expose_login_time($auth_identifier = false){
		if(!$auth_identifier){
			$auth_identifier = $this->auth_identifier;
		}
		return substr( $auth_identifier , 0 , 5 ) . substr( $auth_identifier , -5 , 5 );
	}

	// --------------------------------------------------------------

	/**
	 * Log the user out
	 */
	public function logout(){
		// Get the user ID from the session
		$user_id = $this->expose_user_id($this->auth_identifier);


		if( config_item('delete_session_cookie_on_logout') ){
			// Completely delete the session cookie
			delete_cookie( config_item('sess_cookie_name') );
		}
		else{
			// Unset auth identifier
			$this->CI->session->unset_userdata('auth_identifier');
		}

		$this->CI->load->helper('cookie');

		// Delete remember me cookie
		delete_cookie( config_item('remember_me_cookie_name') );

		// Delete the http user cookie
		delete_cookie( config_item('http_user_cookie_name') );
	}

	// --------------------------------------------------------------

	/**
	 * Hash Password
	 *
	 * @param   string  The raw (supplied) password
	 * @param   string  The random salt
	 * @return  string  the hashed password
	 */
	public function hash_passwd( $password, $random_salt = null )
	{
		if(!isset($random_salt)){
			$random_salt = SecurityUtils::generateRandomToken();
		}
		/**
		 * bcrypt is the preferred hashing for passwords, but
		 * is only available for PHP 5.3+. Even in a PHP 5.3+ 
		 * environment, we have the option to use PBKDF2; just 
		 * set the PHP52_COMPATIBLE_PASSWORDS constant located 
		 * in config/constants.php to 1.
		 */
		if( CRYPT_BLOWFISH == 1 && PHP52_COMPATIBLE_PASSWORDS === 0 )
		{
			return crypt( $password . config_item('encryption_key'), '$2a$09$' . $random_salt . '$' );
		}

		// Fallback to PBKDF2 if bcrypt not available
		$this->CI->load->helper('pbkdf2');

		/**
		 * Key length (param #5) set at 30 so that pbkdf2() 
		 * returns a string which has a length that matches 
		 * the length of the `user_pass` field (60 chars).
		 */
		return pbkdf2( 'sha256', $password . config_item('encryption_key'), $random_salt, 4096, 30, FALSE );
	}

	// --------------------------------------------------------------

	/**
	 * Check Password
	 *
	 * @param   string  The hashed password 
	 * @param   string  The random salt
	 * @param   string  The raw (supplied) password
	 * @return  bool
	 */
	public function check_passwd($hash, $random_salt, $password){
		return ($hash === $this->hash_passwd($password, $random_salt));
	}
	
	/**
	 * Confirm the User During Login Attempt or Status Check
	 *
	 * 1) Is the user banned?
	 * 2) If a login attempt, does the password match the one in the user record?
	 * 5) Is the user the appropriate role for the request?
	 *
	 * @param   obj    the user record
	 * @param   mixed  the required user level or array of roles
	 * @param   mixed  the posted password during a login attempt
	 * @return  bool
	 */
	private function _user_confirmed($user, $role, $user_pass) {

		// Check if user is banned
		$is_banned = ($user->isBanned());

		// Check if the posted password matches the one in the user profile
		$wrong_password = (!$this->check_passwd($user->getPassword(), $user->getSalt(), $user_pass));

		// Check if the user has the appropriate role
		$wrong_role = ($user->getRole() < $role);

		return !($is_banned || $wrong_role || $wrong_password);
	}
	
	// ---------------------------------------------------------------
	
	/**
	 * Setup session, HTTP user cookie, and remember me cookie 
	 * during a successful login attempt.
	 *
	 * @param   obj  the user record
	 * @return  void
	 */
	private function _maintain_state($user){
		// Store login time in database and cookie
		$timestamp = time();

		// Update user
		$user->setLastLogin($timestamp);
		$this->CI->dataStore->save($user);
		$this->CI->dataStore->commit();

		/**
		 * Since the session cookie needs to be able to use
		 * the secure flag, we want to hold some of the user's 
		 * data in another cookie.
		 */
		$http_user_cookie = array(
			'name'   => config_item('http_user_cookie_name'),
			'domain' => config_item('cookie_domain'),
			'path'   => config_item('cookie_path'),
			'prefix' => config_item('cookie_prefix'),
			'secure' => FALSE
		);

		// Initialize the HTTP user cookie data
		$http_user_cookie_data['_user_id'] = $user->getId();

		// Get the array of selected profile columns
		$selected_profile_attributes = config_item('selected_profile_attributes');

		// If selected profile columns are to be added to the HTTP user cookie
		if(!empty($selected_profile_attributes)){
			foreach($selected_profile_attributes as $attribute){
				if(property_exists($user,$attribute)){
					$http_user_cookie_data['_' . $attribute] = $user->getAttribute($attribute);
				}
			}
		}

		// Serialize the HTTP user cookie data
		$this->CI->load->object('utils');
		$http_user_cookie['value'] = Utils::serializeData($http_user_cookie_data);

		// Check if remember me requested, and set cookie if yes
		if(config_item('allow_remember_me') && $this->CI->input->post('remember_me')){
			$remember_me_cookie = array(
				'name'   => config_item('remember_me_cookie_name'),
				'value'  => config_item('remember_me_expiration') + time(),
				'expire' => config_item('remember_me_expiration'),
				'domain' => config_item('cookie_domain'),
				'path'   => config_item('cookie_path'),
				'prefix' => config_item('cookie_prefix'),
				'secure' => FALSE
			);

			$this->CI->input->set_cookie($remember_me_cookie);

			// Make sure the CI session cookie doesn't expire on close
			$this->CI->session->sess_expire_on_close = FALSE;
			$this->CI->session->sess_expiration = config_item('remember_me_expiration');

			// Set the expiration of the http user cookie
			$http_user_cookie['expire'] = config_item('remember_me_expiration') + time();
		}
		else {
			// Unless remember me is requested, the http user cookie expires when the browser closes.
			$http_user_cookie['expire'] = 0;
		}

		$this->CI->input->set_cookie($http_user_cookie);

		// Set CI session cookie
		$this->CI->session->set_userdata( 
			'auth_identifier',
			$this->create_auth_identifier(
				$user->getId(),
				$user->getLastModified(),
				$user->getLastLogin()
			)
		);
	}
	
	// -----------------------------------------------------------------------
}

/* End of file Authenticator.php */
/* Location: /application/libraries/Authenticator.php */ 