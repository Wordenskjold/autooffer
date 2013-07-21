<?php

namespace models;

/**
 * @Entity(readOnly=true)
 * @Table(name="car_attribute")
 */
class CarAttribute extends Entity {

    /** 
     * @ManyToOne(targetEntity="Car", inversedBy="attributes") 
     * @JoinColumn(nullable=false)
     */
    private $car;

    /** 
     * @Column(type="string", length=50) 
     */
    private $attribute;

    /** 
     * @Column(type="string", length=50)
     */
    private $value;

	public function __construct($name, $value, $car){
		$this->attribute = $name;
		$this->value = $value;
		$this->car = $car;
	}

	public function toString(){
		return $this->serialize();
	}

    public function getKey(){
        return $this->attribute;
    }

    public function getValue(){
        return $this->value;
    }
}

/* End of file  */
/* Location: ./application/models/ */