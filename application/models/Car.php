<?php

namespace models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="car")
 **/
class Car extends Entity{

	/** 
	 * @Column(type="string", nullable=false, length=50)
	 */
	protected $manufacturer;

    /**
     * Raw attributes maintained by Doctrine
     * @OneToMany(targetEntity="CarAttribute", mappedBy="car", cascade="all")
     **/
	protected $_attributes;

    /**
     * Associative map containing a representation of 
     * the key-value structure of the car_attribute table.
     */
    protected $attributes;

	public function __construct(array $params = array()){
		parent::__construct($params);
        $this->_attributes = new ArrayCollection();
	}

    public function getManufacturer(){
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function addAttribute($key, $value){
    	$this->_attributes[] = new CarAttribute($key, $value, $this);
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key){
    	return idx($this->getAttributes(), $key, null);
    }

    public function getAttributes(){
        if($this->attributes === null){
            $this->mapAttributes();
        }
    	return $this->attributes;
    }

    private function mapAttributes(){
        $this->attributes = [];
        foreach($this->_attributes->toArray() as $attribute){
            $this->attributes[$attribute->getKey()] = $attribute->getValue();
        }
    }
}

/* End of file  */
/* Location: ./application/models/ */