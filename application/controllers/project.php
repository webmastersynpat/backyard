<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	
	function __construct(){
		parent::__construct();
		$this->load->library('layout');
		$this->layout->layout='default';
		$this->layout->add_css('public/libs/jqueryui/ui-lightness/jquery-ui-1.10.4.custom.min.css');
		$this->layout->add_css('public/libs/bootstrap/css/bootstrap.min.css');
		$this->layout->add_css('public/libs/font-awesome/css/font-awesome.min.css');
		$this->layout->add_css('public/libs/fontello/css/fontello.css');
		$this->layout->add_css('public/libs/animate-css/animate.min.css');
		$this->layout->add_css('public/libs/nifty-modal/css/component.css');
		$this->layout->add_css('public/libs/magnific-popup/magnific-popup.css');
		$this->layout->add_css('public/libs/ios7-switch/ios7-switch.css');
		$this->layout->add_css('public/libs/pace/pace.css');
		$this->layout->add_css('public/libs/sortable/sortable-theme-bootstrap.css');
		$this->layout->add_css('public/libs/bootstrap-datepicker/css/datepicker.css');
		$this->layout->add_css('public/libs/jquery-icheck/skins/all.css');		
		
		$this->layout->add_css('public/libs/bootstrap-select/bootstrap-select.min.css');
		$this->layout->add_css('public/libs/summernote/summernote.css');
		$this->layout->add_css('public/css/style.css');
		$this->layout->add_css('public/css/style-responsive.css');
		
		
		$this->layout->add_js('public/libs/jquery/jquery-1.11.1.min.js');
		$this->layout->add_js('public/libs/bootstrap/js/bootstrap.min.js');
		$this->layout->add_js('public/libs/jqueryui/jquery-ui-1.10.4.custom.min.js');
		$this->layout->add_js('public/libs/jquery-ui-touch/jquery.ui.touch-punch.min.js');
		$this->layout->add_js('public/libs/jquery-detectmobile/detect.js');
		$this->layout->add_js('public/libs/jquery-animate-numbers/jquery.animateNumbers.js');
		$this->layout->add_js('public/libs/ios7-switch/ios7.switch.js');
		$this->layout->add_js('public/libs/fastclick/fastclick.js');
		$this->layout->add_js('public/libs/jquery-blockui/jquery.blockUI.js');
		$this->layout->add_js('public/libs/bootstrap-bootbox/bootbox.min.js');
		$this->layout->add_js('public/libs/jquery-slimscroll/jquery.slimscroll.js');
		$this->layout->add_js('public/libs/jquery-sparkline/jquery-sparkline.js');
		$this->layout->add_js('public/libs/nifty-modal/js/classie.js');
		$this->layout->add_js('public/libs/nifty-modal/js/modalEffects.js');
		$this->layout->add_js('public/libs/sortable/sortable.min.js');
		$this->layout->add_js('public/libs/bootstrap-fileinput/bootstrap.file-input.js');
		$this->layout->add_js('public/libs/bootstrap-select/bootstrap-select.min.js');
		$this->layout->add_js('public/libs/bootstrap-select2/select2.min.js');
		$this->layout->add_js('public/libs/magnific-popup/jquery.magnific-popup.min.js');
		$this->layout->add_js('public/libs/pace/pace.min.js');
		$this->layout->add_js('public/libs/bootstrap-datepicker/js/bootstrap-datepicker.js');
		$this->layout->add_js('public/libs/jquery-icheck/icheck.min.js');
		$this->layout->add_js('public/libs/prettify/prettify.js');
		$this->layout->add_js('public/js/init.js');
		
		$this->layout->add_js('public/libs/bootstrap-select/bootstrap-select.min.js');
		$this->layout->add_js('public/libs/bootstrap-inputmask/inputmask.js');
		$this->layout->add_js('public/libs/summernote/summernote.js');
		$this->layout->add_js('public/js/pages/forms.js');
	}
	public function index()
	{
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Projects';
		$this->layout->render('client/index');
	}
	public function new_project(){
		$this->load->helper('form');
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard New Project';
		$this->layout->render('project/new_project');
	}
	public function edit_project_contact(){
		$this->load->helper('form');
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Remove or Add People';
		$this->layout->render('project/edit_project_contact');
	}
	public function acquisition(){
		$this->load->helper('form');
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Acquisition';
		$this->layout->render('project/acquisition');
	}
	public function request_to_participate(){
		$this->load->helper('form');
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Request to Participate';
		$this->layout->render('project/request_to_participate');
	}
	public function sale(){
		$this->load->helper('form');
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Sale';
		$this->layout->render('project/sale');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */