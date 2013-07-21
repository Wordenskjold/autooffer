<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO: Show a message prompting the users to verify their account, 
 * if they haven't already.
 */
class MY_Controller extends CI_Controller{

	private $data;
	private $loadFooter = true;
	private $loadHeader = true;
	private $renderMenu = true;

	function __construct(){
		parent::__construct();

		global $data;
		$this->data = $data;
		/* Call custom hook */
		$GLOBALS['EXT']->_call_hook('pre_controller_constructor');


		/* Add globally used data to the $data array */
		$this->setTitle("AutoOffer");

		/* Setup flashdata variables */
		if($msg = $this->session->flashdata('msg')){
			$this->data['msg'] = $msg;
		}
	}

	protected function loadFooter($loadFooter){
		$this->loadFooter = $loadFooter;
	}

	protected function loadHeader($loadHeader){
		$this->loadHeader = $loadHeader;
	}

	protected function renderMenu($renderMenu){
		$this->renderMenu = $renderMenu;
	}

	protected function useLibs($classes, $type = 'library'){
		if(!is_array($classes)){
			$classes = explode(',', $classes);
		}
		foreach($classes as $key => $class){
			$type = $key ? $key : $type;
			if(is_array($class)){
				$this->useLibs($class, $type);
			}
			$this->load->{$type}(trim($class));
		}
	}

	protected function getData($key){
		return idx($this->data, $key, false);
	}

	protected function addData($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	protected function build(array $dataArray){
		foreach($dataArray as $key => $value){
			$this->addData($key, $value);
		}
		return $this;
	}

	protected function setTitle($title){
		$this->data['title'] = $title;
	}

	protected function render($view = false, $title = "", $parseTemplateVars = true, $return = false){

		$template = "";
		/* Set title of view */
		if(!empty($title)){
			$this->data['title'] = $title;
		}

		/* Add body class variable */
		if($view){
			$this->addData('bodyClass', $view);
			$this->addData('renderMenu', $this->renderMenu);
			$this->loadLanguageFile($view);
		}

		/* Load static files */
		$this->data['static'] = $this->get_static();
		if($this->loadHeader){
			$template.= $this->parser->parse('templates/header', $this->data, $return);
		}
		if($view){
			$template.= $parseTemplateVars 
				? $this->parser->parse($view, $this->data, $return)
				:  $this->load->view($view, $this->data, $return);
		}
		if($this->loadFooter){
			$template.= $this->load->view('templates/footer', $this->data, $return);
		}
		return $template;
	}

	protected function loadLanguageFile($idiom){
		$this->lang->load('common');
		$this->lang->load($idiom);
		$this->build($GLOBALS['LANG']->language);
	}

	protected function generate($view = false, $title = "", $parseTemplateVars = true){
		$this->loadHeader(false);
		$this->loadFooter(false);
		return $this->render($view, $title, $parseTemplateVars, true);
	}

	protected function get_static(){
		$_static['css'] = "";
		$_static['js'] = "";
		$this->config->load('static_files', true);
		$static = $this->config->item('static_files');

		/* Minify */
		if(ENVIRONMENT === 'production'){

		} 
		else{
			foreach($static['css'] as $value){
				$_static['css'].= "<link rel='stylesheet' type='text/css' href='$value' />";
			}
			foreach($static['js'] as $value){
				$_static['js'].= "<script src='$value'></script>";
			}	
		}
		return $_static;
	}
}