<?php

class Landing extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->renderMenu(false);
		$this->render('landing');
	}

}

/* End of file  */
/* Location: ./application/controllers/ */