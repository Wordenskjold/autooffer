<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utils{

	/* Static class */
	private function __construct(){ }

	static function serializeData($data){
		if (is_array($data)){
			foreach ($data as $key => $val){
				if (is_string($val)){
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else{
			if (is_string($data)){
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}
		return serialize($data);
	}

	static function unserializeData($data){
		$data = @unserialize(strip_slashes($data));
		if (is_array($data)){
			foreach ($data as $key => $val)	{
				if (is_string($val)){
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}
			return $data;
		}
		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}

	static function redirect_response($url, $response){
		if($url === '/'){
			$url = base_url();
		}
		$CI = &get_instance();
		$CI->session->set_flashdata('msg',array(
			'status' => $response->getStatus(),
			'type'   => $response->getType(),
			'msg'    => $response->getMessage(),
		));
		redirect($url);
	}
}