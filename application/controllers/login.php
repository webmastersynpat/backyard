<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Login extends CI_Controller {



		

	

	function __construct(){

		parent::__construct();
        $this->load->library('email');   
		$this->load->library('layout');
        $this->load->helper('url');
        

	}

	public function index()

	{

		if(isset($_POST) && count($_POST)>0){

			/**/

			$postUserData = $this->input->post();

			$suceessLogin = $this->simpleloginsecure->login($postUserData['user']['email'],$postUserData['user']['password']);

			if($suceessLogin){

				/*redirect('dashboard');*/
				if(!isset($_SESSION)){
					session_start();
				}

				$_SESSION['clicked_url']="dashboard";
				$_SESSION['guess_login']=$postUserData['user']['guess_login'];
				if($postUserData['user']['guess_login']=='0'){
					$this->load->library('DriveServiceHelper');
					$service = new GmailServiceHelper();
					$redirectUrl = $service->createAuthUrl();
					redirect($redirectUrl);
				} else {
					redirect('dashboard');
				}
				
			} else {

				$this->session->set_flashdata('message','Enter wrong email and password.');

				redirect('login');

			}

		} else {

			$this->layout->auto_render=false;		

			$this->layout->layout='login';

			$this->layout->title_for_layout = 'Backyard Synpat Login';		

			$this->layout->render('user/login');

		}

	}

	public function confirm_password($c=null){
		if($c){
			$data = array('forgot_code' => $c);
			$chkEmailExist = $this->simpleloginsecure->checkDataExist($data);
			if(count($chkEmailExist) > 0){
    	       if(isset($_POST) && count($_POST) > 0){
        	       $password = $this->input->post();
                   $suceessLogin = $this->simpleloginsecure->confirm_password($c,$password['confirm']['password']);
                   if($suceessLogin == TRUE){
                        $this->session->set_flashdata('error','Successfully Changed Password');
                        redirect('login');
                   }else{
                   /* echo 'A';
                    die;*/
                        $this->session->set_flashdata('error','Please try after sometime!');
                        redirect('user/confirm_password'/$c);
                   }    
    	       }
			}else{
			       /* echo 'B';
                    die;*/
                $this->session->set_flashdata('error','Please try after sometime!');
                redirect('user/confirm_password'/$c);
			}
			$data['c'] = $c;
			$this->layout->auto_render=false;	
			$this->layout->layout='login';
    		$this->layout->title_for_layout = 'Forgot Password';		
    		$this->layout->render('user/confirm_password',$data); 
		}else{
	       $this->session->set_flashdata('error','Please try after sometime!');
            redirect('login');
		}
	}
    
    public function logout($id,$sid){
		$this->CI =& get_instance();
		if(!isset($this->session->userdata['type'])){
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']);
			}
		}	
		/*if(isset($this->CI->session->userdata['session_id'])){
			$sid = $this->CI->session->userdata['session_id'];
			$id = $this->CI->session->userdata['id'];
		}
		*/
		if((int)$sid==1){
			$getData = $this->CI->db->select("*")->from('user_logtime')->where('user_id',$id)->order_by('id','desc')->get()->row();
			if(count($getData)>0 && $getData->logout_date=='0000-00-00 00:00:00'){
				$this->CI->db->where('id',$getData->id);
				$this->CI->db->update('user_logtime',array('logout_date'=>date('Y-m-d H:i:s')));
			}	
		}
		
		$this->simpleloginsecure->logout();

		redirect('login');

	}
    
    public function forgot_password(){
        if(isset($_POST) && count($_POST) > 0){ 
            $data = array('email' => $_POST['forgot']['email']);
            $chkEmailExist = $this->simpleloginsecure->checkDataExist($data);
           // print_r($chkEmailExist);
          //  die;
            if(count($chkEmailExist) > 0){
                $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1',
                    'wordwrap' =>TRUE
                   );
                $this->email->set_mailtype("html");
                $this->email->initialize($config);
                $this->email->from('no-reply@synpat.com', 'Backyard SynPat');
                $this->email->to($_POST['forgot']['email']); 
                $this->email->subject('Forgot Password');
                
                $code = rand(100000, 999999);
                $md5_code = md5($code);
                $chkEmailExist = $this->simpleloginsecure->updateEmailForgotCode($_POST['forgot']['email'],array('forgot_code' => $md5_code));
                
                $message = 'This email was sent to you as a result of your request to renew your password to Backyard. ';
                $message .= "<a href='".$this->config->base_url()."login/confirm_password/".$md5_code."' target='_blank'>Click here to create a new password.</a>";
                $this->email->message($message);	
                $this->email->send();
                $this->session->set_flashdata('success','Please check your email');
                redirect('login');
            }else{
                $this->session->set_flashdata('error','Email does not Exist');
                redirect('login');
            }
        }
    }

}



/* End of file login.php */

/* Location: ./application/controllers/login.php */