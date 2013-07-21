<?php

namespace models;

/** 
 * @MappedSuperclass 
 */
class Entity {

	/** 
	 * @Id 
	 * @Column(type="integer") 
	 * @GeneratedValue 
	 */
	protected $id;

	public function __construct(array $params = array()){
		foreach($params as $key => $value){
			if(property_exists($this,$key)){
				$this->{$key} = $value;
			}
		}
	}

	public function __toString(){
		return serialize($this);
	}

	public function getId(){
		return $this->id;
	}

	public function getAttribute($attribute){
		if(property_exists($this,$attribute)){
			$attribute = ucfirst($attribute);
			return $this->{'get' . $attribute}();
		}
	}

}

/* End of file  */
/* Location: ./application/models/ */