<?php

namespace models;

/**
 * @Entity
 * @Table(name="prelaunch_subscriber")
 **/
class PrelaunchSubscriber extends Entity{

	/**
	 * @Column(name="model_make", type="string", length=100, nullable=false) 
	 */
	protected $modelMake;

	/**
	 * @Column(type="string", length=100, unique=true, nullable=false) 
	 */
	protected $email;

	/**
	 * @Column(type="integer", length=10, nullable=false)
	 */
	protected $timestamp;

	public function __construct(array $params = array()){
		parent::__construct($params);
		if(!isset($this->timestamp)){
			$this->timestamp = time();
		}
	}

	public function getModelMake(){
		return $this->modelMake;
	}

	public function setModelMake($modelMake){
		$this->modelMake = $modelMake;
	}

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
}

/* End of file  */
/* Location: ./application/models/ */