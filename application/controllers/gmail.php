<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gmail extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->layout->auto_render=false;	
		$this->layout->layout='default';		
	}
	
	function index(){
		
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		echo "<pre>";
		print_r($service->listMessages("me"));
		die;
	}
}