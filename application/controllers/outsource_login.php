<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Outsource_Login extends CI_Controller {

	public function __construct(){		
		parent::__construct();
		if ( $this->session->userdata('miner')){
				redirect('outsource/datasource');
		}
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));		
	}

	function index(){
		$this->layout->auto_render=false;	
		$this->layout->layout='outsource';		
		$this->layout->render('outsource/dataentry/login');
	}
	function login(){
		$post = $this->input->post('login');
		if(count($post)>0){
			if(!empty($post['email']) && !empty($post['password'])){
				$this->load->model('outsource_model');
				$checkUser = $this->outsource_model->login($post['email'],md5($post['password']));
				if(count($checkUser)>0){
					$this->session->set_userdata("miner",$checkUser);
					redirect('outsource/dataentry/start');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Wrong email and password.</div>');
					redirect('outsource/login');
				}
			}  else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Username and password cannot be blank</div>');
				redirect('outsource/login');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Please login first.</div>');
			redirect('outsource/login');
		}		
	}
}
/* End of file dataentry.php */
/* Location: ./application/controllers/outsource/login.php */