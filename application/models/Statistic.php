<?php

namespace models;

/**
 * Simple key-value pair for storing statistics, 
 * like referrers, failed login attempts, etc.
 * 
 * @Entity(repositoryClass="models\repositories\StatisticCacheRepository")
 * @Table(name="statistic")
 */
class Statistic extends Entity{

    /** 
     * @Column(type="string", length=50, nullable=false) 
     */
    private $key;

    /** 
     * @Column(type="string", length=50, nullable=false)
     */
    private $value;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    private $timestamp; 
	
	public function __construct(){}

    public function __toString(){
        return $this->getValue();
    }

    public function getKey(){
        return $this->key;
    }

    public function getValue(){
        return $this->value;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }
}

/* End of file  */
/* Location: ./application/models/ */