<?php

namespace models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="dealer")
 **/
class Dealer extends Entity{

    /** 
     * @Column(type="integer", nullable=false, unique=true) 
     */
    protected $cvr;

    /** 
     * @Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="dealer", cascade="persist")
     * @OrderBy({"firstName", "lastName"})
     */
    protected $employees;

    /**
     * @ManyToMany(targetEntity="Car", cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinTable(name="dealer_cars",
     *      inverseJoinColumns={@JoinColumn(unique=true, onDelete="cascade")}
     *      )
     **/
    protected $cars;

    /**
     * @OneToMany(targetEntity="Offer", mappedBy="dealer", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $offers;

    public function __construct(array $params = array()) {
        parent::__construct($params);
        $this->employees  = new ArrayCollection();
        $this->cars       = new ArrayCollection();
        $this->offers     = new ArrayCollection();
    }

    public function __toString(){
        return (string) $this->getId();
    }

    public function getCvr(){
        return $this->cvr;
    }

    public function setCvr($cvr){
        $this->cvr = $cvr;
    }

    public function getName(){
        return $this->name;
    }

    public function addEmployee($employee){
        $this->employees[] = $employee;
    }

    public function getEmployees(){
        return $this->employees;
    }

    public function getCars(){
        return $this->cars->toArray();
    }

    public function addCar($car){
        $this->cars[] = $car;
    }

    public function removeCar($car){
        if(is_object($car)){
            $this->cars->removeElement($car);
        }
        else if(is_numeric($car)){
            $this->cars->remove($car);
        }
    }

    public function removeOffer($offer){
        if(is_object($offer)){
            $this->offers->removeElement($offer);
        }
        else if(is_numeric($offer)){
            $this->offers->remove($offer);
        }  
    }

    public function getOffers(){
        return $this->offers->toArray();
    }
}

/* End of file  */
/* Location: ./application/models/ */