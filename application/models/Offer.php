<?php

namespace models;

/**
 * @Entity(readOnly=true)
 * @Table(name="offer")
 */
class Offer extends Entity {

	/**
	 * @ManyToOne(targetEntity="Dealer", inversedBy="offers")
     * @JoinColumn(nullable=false)
	 */
	protected $dealer;

	/**
	 * @ManyToOne(targetEntity="Request", inversedBy="offers")
     * @JoinColumn(nullable=false)
	 */
	protected $request;

    /**
     * @Column(type="integer", length=13, nullable=false) 
     */
    protected $timestamp;
	
	public function __construct(array $params = array()){
		parent::__construct($params);
        $this->timestamp = time();
	}

    public function getUser(){
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function getDealer(){
        return $this->dealer;
    }

    public function setDealer($dealer) {
        $this->dealer = $dealer;
        return $this;
    }

    public function getRequest(){
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }

    /* Helper function for searching and sorting */
    public function getDealerId(){
        return $this->getDealer()->getId();
    }
}

/* End of file  */
/* Location: ./application/models/ */