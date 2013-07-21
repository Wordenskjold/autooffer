<?php

namespace models;

/**
 * A session object used by CodeIgniter.
 * Only here for creating the database schema through Doctrine.
 * 
 * @author Frederik Wordenskjold
 * @version 1.0
 *
 * @Entity 
 * @Table(name="session", indexes={@index(name="last_activity_idx", columns={"last_activity"})})
 **/
class Session{

	/**
	 * @Id
	 * @Column(type="string", length=40, nullable=false) 
	 */
	protected $session_id = 0;

	/**
	 * @Column(type="string", length=45, nullable=false) 
	 */
	protected $ip_address = 0;

	/**
	 * @Column(type="string", length=120, nullable=false) 
	 */
	protected $user_agent = 0;

	/**
	 * @Column(type="integer", length=10, nullable=false, options={"unsigned"=true})
	 */
	protected $last_activity = 0;

	/**
	 * @Column(type="text", nullable=false) 
	 */
	protected $user_data;
	
}