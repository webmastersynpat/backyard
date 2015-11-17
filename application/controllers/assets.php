<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets extends CI_Controller {

	function __construct(){
		parent::__construct();
 		if(!isset($this->session->userdata['type']) || empty($this->session->userdata['email'])){
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']);
			} else {
				redirect('login');
			}
		}		 
		$this->load->model('assets_model');
		$this->layout->auto_render=false;
		$this->layout->layout='default';

	}
	public function index()
	{
		$data = array();
		$param=$this->uri->uri_to_assoc(3);

		if($param['leadid']){
			
			$data['list'] = $this->assets_model->getList($param['leadid']);
		}
		$this->layout->title_for_layout = 'Assets Viewer';
		$this->layout->render('assets/lucidViewer',$data);
	}
	public function getList(){
		$data = array();		
		if($this->session->flashdata('item')=='success'){
			$data['message']='1 record added';
		}else
		$data['list']=$this->assets_model->getAllList();
		$this->layout->title_for_layout = 'Assets List';
		$this->layout->render('assets/list',$data);		
	}
	public function create(){
		$data=$_POST;
		
		if($data){
			if($this->assets_model->create($data)){
				$this->session->set_flashdata('item', 'success');
				redirect('/assets/getList', 'refresh');				
			}else{
				$this->layout->title_for_layout = 'Create Asset';
				$data['message']='failed insert data';
				$this->layout->render('assets/create');		
			}		

		}else{
			$this->layout->title_for_layout = 'Create Asset';
			$this->layout->render('assets/create');		
		}
	}	
	public function update(){	
		$data=$_POST;
		if($data){
			if($this->assets_model->update($data)){
				$this->session->set_flashdata('item', 'success');
				redirect('/assets/getList', 'refresh');				
			}else{
				$this->layout->title_for_layout = 'Update Asset';
				$data['message']='failed update data';
				$this->layout->render('assets/update',$data);		
			}		

		}else{
			$data = array();			
			$param=$this->uri->uri_to_assoc(3);
			if($param['id']){
				$data['values']=$this->assets_model->findOne($param['id']);
				$this->layout->title_for_layout = 'Update Asset';
				$this->layout->render('assets/update',$data);		
			}else{
				$this->session->set_flashdata('item', 'failed');
				redirect('/assets/getList', 'refresh');								
			}
		}		
	}
}

/* End of file assets.php */
/* Location: ./application/controllers/assets.php */