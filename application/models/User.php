<?php

namespace models;

/**
 * @Entity(repositoryClass="models\repositories\UserCacheRepository")
 * @Table(name="user")
 **/
class User extends Entity{

	const ROLE_USER   = 1;
	const ROLE_DEALER = 2;
	const ROLE_ADMIN  = 3;

	/**
	 * @Column(name="first_name", type="string", length=25, nullable=false) 
	 */
	protected $firstName;

	/**
	 * @Column(name="last_name", type="string", length=50, nullable=false) 
	 */
	protected $lastName;

	/**
	 * @Column(type="string", length=100, unique=true, nullable=false) 
	 */
	protected $email;

	/**
	 * @Column(type="string", length=150, nullable=false) 
	 */
	protected $agent;

	/**
	 * @Column(type="string", length=50, nullable=true) 
	 */
	protected $referrer;

	/**
	 * @Column(type="string", length=100, nullable=false)
	 */
	protected $password;

	/**
	 * @Column(type="string", length=32, nullable=false)
	 */
	protected $salt;

	/**
	 * @Column(type="integer", length=10, nullable=false)
	 */
	protected $timestamp;

	/**
	 * @Column(type="integer", name="last_modified", length=10, nullable=true)
	 */
	protected $lastModified;

	/**
	 * @Column(type="integer", name="last_login", length=10, nullable=true)
	 */
	protected $lastLogin;

	/** 
	 * @Column(type="integer", nullable=false) 
	 */
	protected $role;

	/** 
	 * @Column(type="boolean", nullable=false) 
	 */
	protected $banned;

	/** 
	 * @Column(type="string", name="verification_hash", length=32, nullable=true)
	 */
	protected $verificationHash;

	/**
	 * @ManyToMany(targetEntity="Dealer", cascade="persist")
	 * @JoinTable(name="dealer_users", 
	 *   joinColumns={ @JoinColumn(unique=true) }
	 * )
	 **/
	protected $dealer;

	public function __construct(array $params = array()){
		parent::__construct($params);
		$this->banned = false;
		if(!isset($this->role)){
			$this->role = self::ROLE_USER;
		}
		if(!isset($this->timestamp)){
			$this->timestamp    = time();
			$this->lastModified = $this->timestamp;
			$this->lastLogin    = $this->timestamp;
		}
	}

	public function checkIdentity($lastMod, $id, $lastLogin){
		return $lastMod == $this->lastModified 
			&& $id == $this->id 
			&& $lastLogin == $this->lastLogin;
	}

	public function hasAccess($role){
		return ($this->role >= $role && !$this->banned);
	}

	public function getLastModified(){
		return $this->lastModified;
	}

	public function getLastLogin(){
		return $this->lastLogin;
	}

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getRole(){
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    public function getDealer(){
        return ($this->dealer)[0];
    }

    public function setDealer($dealer) {
        $this->dealer[] = $dealer;
        return $this;
    }

    public function isBanned(){
    	return $this->banned;
    }

    /**
     * Users who have not verified their email
     * CANNOT submit requests
     */
    public function isVerified(){
    	return is_null($this->verificationHash);
    }

    public function verify(){
    	$this->verificationHash = null;
    }

    public function getPassword(){
    	return $this->password;
    }

	public function getVerificationHash(){
		return $this->verificationHash;
	}

    public function getSalt(){
    	return $this->salt;
    }

    public function getFirstName(){
    	return $this->firstName;
    }

    public function getLastName(){
    	return $this->lastName;
    }

    public function getName(){
    	return $this->getFirstName() . " " . $this->getLastName();
    }

    public function setLastLogin($unix){
    	$this->lastLogin = $unix;
    }

    public function setLastModified($unix){
    	$this->lastModified = $unix;
    }
}

/* End of file  */
/* Location: ./application/models/ */