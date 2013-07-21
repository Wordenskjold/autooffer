<?php

class Response{

	const ERROR   = 0;
	const SUCCESS = 1;

	private $message;
	private $status;
	private $type;

	private function __construct($msg, $status, $type = 'general'){
		$this->message = $msg;
		$this->status = $status;
		$this->type = $type;
	}

	static function error($msg = ""){
		return new Response($msg, self::ERROR);
	}

	static function success($msg = ""){
		return new Response($msg, self::SUCCESS);
	}

    public function getMessage(){
        return $this->message;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getType(){
        return $this->type;
    }
}