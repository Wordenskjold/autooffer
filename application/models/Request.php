<?php

namespace models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * A request contains n > 0 cars, where one car represents the requested car,
 * all other cars represent the cars that the customer wants to sell.
 * Each request is open for 48 hours, at which point it becomes unavailable for bidding.
 * A dealer can send one offer to each request.
 * 
 * @author Frederik Wordenskjold
 * @version 1.0
 * 
 * @Entity 
 * @Table(name="request")
 */
class Request extends Entity {

	/**
	 * @ManyToOne(targetEntity="User", cascade="persist")
     * @JoinColumn(nullable=false)
	 */
	protected $user;

    /**
     * @ManyToMany(targetEntity="Car", cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinTable(name="request_cars",
     *      inverseJoinColumns={@JoinColumn(onDelete="cascade")}
     * )
     **/
    protected $cars;

    /**
     * @OneToMany(targetEntity="Offer", mappedBy="request", cascade="all")
     */
    protected $offers;

    /**
     * @Column(type="integer", length=13, nullable=false)
     */
    protected $timestamp;

    const HOURS_AVAILABLE = 48;

	public function __construct(array $params = array()){
		parent::__construct($params);
		$this->cars = new ArrayCollection();
		$this->offers = new ArrayCollection();
        $this->timestamp = time();
	}

    /**
     * @Test PASSED
     * Is this request within the HOURS_AVAILABLE window?
     */
    public function isOpen(){
        return ((time() - $this->timestamp) / 3600) < self::HOURS_AVAILABLE;
    }

    /**
     * @Test PASSED
     *  The user who issued the request
     */
    public function getUser(){
        return $this->user;
    }

    /**
     * The offers added to this request, sorted by
     * the method $by on the offer object.
     */
    public function getOffers($by = false){
        $offers = $this->offers->toArray();
        if($by && method_exists('models\\Offer', $by)){
            $offers = msort($offers, $by);
        }
        return $offers;
    }

    /**
     * The cars added to this request, sorted by
     * the method $by on the car object
     */
    public function getCars($by = false){
        $cars = $this->cars->toArray();
        if($by && method_exists('models\\Car', $by)){
            $cars = msort($cars, $by);
        }
        return $cars;
    }

    /**
     * @Test PASSED
     * Helper function to determine if a dealer has already
     * added an offer for this request.
     */
    public function hasOfferFromDealer($dealer){
        $dealerIds = mpull($this->getOffers(), null, 'getDealerId');
        return idx($dealerIds, $dealer->getId(), false) !== false;
    }

    /**
     * @Test PASSED
     * Only one offer per dealer. This constraint should be checked before 
     * Calling this function, as we don't want to return Message objects here.
     */
    public function addOffer($offer){
        if(!$this->hasOfferFromDealer($offer->getDealer())){
            $this->offers[] = $offer;
        }
    }

    /** 
     * @Test PASSED
     * Ensures that no single car is added twice
     */
    public function addCar($car){
        $carIds = mpull($this->getCars(), null, 'getId');
        if(idx($carIds, $car->getId(), false) === false){
            $this->cars[] = $car;
        }
    }
}

/* End of file  */
/* Location: ./application/models/ */