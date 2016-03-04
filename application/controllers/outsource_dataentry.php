<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Outsource_Dataentry extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata('miner')){
				redirect('outsource/login');
		}		
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));		
	}

	function index(){
		$this->layout->auto_render=false;	
		$this->layout->layout='outsource';
		$this->load->model('outsource_model');
		$user = $this->session->userdata("miner");
		$data['my_projects'] = $this->outsource_model->findUserProjectByUserID($user->id);
		$this->layout->render('outsource/dataentry/index',$data);
	}
	
	function logout(){
		$this->session->sess_destroy();
		redirect('outsource/login');
	}
	
	function start_project($key=null){
		$user = $this->session->userdata('miner');
		$this->load->model('outsource_model');
		$data['my_projects'] = $this->outsource_model->findUserProjectByUserID($user->id);
		if(!is_null($key)){
			$decryptKey = explode('~',$key);
			if(count($decryptKey)!=2){
				$key = '~'.md5($data['my_projects'][0]->id);
			}			
		} else {
			$key = '~'.md5($data['my_projects'][0]->id);
		}
		$decryptKey = explode('~',$key);
		if(count($decryptKey)==2){				
			$projectID = $decryptKey[1];				
			$getDetailsOfProject = $this->outsource_model->findProjectFullDetailWithColumnsEncryptID($projectID);
			$data['full_project_details'] = $getDetailsOfProject;
			$data['find_my_current_month_entry'] = $this->outsource_model->findMyEntryCurrentMonth($user->id,$getDetailsOfProject['project_details']->id);
			$data['id'] = $key;				
		}
		$data['userData'] = $this->outsource_model->findUserDetails($user->id);		
		$this->layout->auto_render=false;	
		$this->layout->layout='outsource';
		$this->layout->render('outsource/dataentry/start',$data);
	}
	
	function profile(){
		$data = array();
		$success = 0;
		if(isset($_POST) && count($_POST)>0){
			$user = $this->session->userdata('miner');
			if((int)$user->id>0){
				$userPostData = $this->input->post('profile');
				if(isset($userPostData['password']) && empty($userPostData['password'])){
					unset($userPostData['password']);
				}
				$this->load->model('outsource_model');
				$success = $this->outsource_model->updateProfile($userPostData,$user->id);
			}			
		}
		if($success==1){
			$data = array('success'=>'Profile updated!');
		} else {
			$data = array('success'=>'','error'=>'Error!');
		}
		echo json_encode($data);
		die;
	}
	
	function u_singleton(){
		$success = 0;
		if(isset($_POST) && count($_POST)>0){
			$key = $this->input->post('key');
			$postData = $this->input->post('enter_data');
			if(!empty($key)){
				$decryptKey = explode('~',$key);
				if(count($decryptKey)==2){
					$this->load->model('outsource_model');
					$projectID = $decryptKey[1];
					$getDetailsOfProject = $this->outsource_model->findProjectFullDetailWithColumnsEncryptID($projectID);
					if(count($getDetailsOfProject['project_details'])>0 && count($getDetailsOfProject['column_heading'])>0){
						$user = $this->session->userdata('miner');						
						$dataEnter = false;
						$row = (int)$postData[0] + 1;
						$col = (int)$postData[1] + 1;
						if(count($getDetailsOfProject['column_heading'])>0){
							foreach($getDetailsOfProject['column_heading'] as $heading){
								if((int)$heading->status==1 && (int) $heading->column_num==$col){
									$dataEnter=true;
								}
							}											
						}
						if($dataEnter===true){							
							$findData = $this->outsource_model->findProjectDataWithRowCol($row,$col,$getDetailsOfProject['project_details']->id);
							if(count($findData)>0){
								$postformData = trim($postData[3]);
								if(!empty($postformData)){
									$success = $this->outsource_model->updateFormData(array('data'=>$postformData,'enter_by'=>'User','user_id'=>$user->id,'recorded_date'=>date('Y-m-d H:i:s')),$findData->id);
									/*Update in Contacts*/
									$this->load->model('client_model');
									switch((int)$col){
										case 5:
											$this->client_model->update($findData->contact_id,array('phone'=>$postformData,'new_phone'=>1));
										break;
										case 6:
											$this->client_model->update($findData->contact_id,array('linkedin_url'=>$postformData,'new_linkedin'=>1));
										break;
									}									
								}
							}
						}
					}
				}
			}
		}
		if($success==1){
			echo json_encode(array('verified'=>1,'changes'=>$postData));
		} else {
			echo json_encode(array('error'=>1,'changes'=>$postData));
		}
		die;
	}
	
	
	function check_email() {
		$success = 0;
		if(isset($_POST) && count($_POST)>0){
			$key = $this->input->post('key');
			$postData = $this->input->post('enter_data');
			$domain = "sandbox16993e3c0bb4463c806e1bc5d0e80167.mailgun.org";
			$ch = curl_init();
			$to = $postData[3];
			$message = "Congratulation";
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:key-3cf64d493227e4799da49c3d32bcdfd0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			$plain = strip_tags(nl2br($message));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.$domain.'/messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('from'    => 'Mailgun Sandbox <postmaster@sandbox16993e3c0bb4463c806e1bc5d0e80167.mailgun.org>',
				'to' => $to,
				'subject' => "You won $1000000000!",
				'text' => $plain));
			$j = json_decode(curl_exec($ch));
			$info = curl_getinfo($ch);
			curl_close($ch);
			if($info['http_code'] == 200){
				if(!empty($key)){
					$decryptKey = explode('~',$key);
					if(count($decryptKey)==2){
						$this->load->model('outsource_model');
						$projectID = $decryptKey[1];
						$getDetailsOfProject = $this->outsource_model->findProjectFullDetailWithColumnsEncryptID($projectID);
						if(count($getDetailsOfProject['project_details'])>0 && count($getDetailsOfProject['column_heading'])>0){
							$user = $this->session->userdata('miner');						
							$dataEnter = false;
							$row = (int)$postData[0] + 1;
							$col = (int)$postData[1] + 1;
							if(count($getDetailsOfProject['column_heading'])>0){
								foreach($getDetailsOfProject['column_heading'] as $heading){
									if((int)$heading->status==1 && (int) $heading->column_num==$col){
										$dataEnter=true;
									}
								}											
							}
							if($dataEnter===true){							
								$findData = $this->outsource_model->findProjectDataWithRowCol($row,$col,$getDetailsOfProject['project_details']->id);
								if(count($findData)>0){
									$postformData = trim($postData[3]);
									if(!empty($postformData)){
										$success = $this->outsource_model->updateFormData(array('data'=>$postformData,'enter_by'=>'User','user_id'=>$user->id,'recorded_date'=>date('Y-m-d H:i:s'),'message_id'=>$j->id),$findData->id);
										$this->load->model('client_model');
										$this->client_model->update($findData->contact_id,array('email'=>$postformData,'new_email'=>1));
									}
								}
							}
						}
					}
				}
			}			
		}
		if($success==1){
			echo json_encode(array('verified'=>1,'changes'=>$postData));
		} else {
			echo json_encode(array('email_error'=>1,'changes'=>$postData,'email'=>$postData[3]));
		}
		die;
	}
	
	function check_bounce_emails(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$key = $this->input->post('key');
			$decryptKey = explode('~',$key);
			if(count($decryptKey)==2){
				$this->load->model('outsource_model');
				$projectID = $decryptKey[1];
				$getDetailsOfProject = $this->outsource_model->findProjectFullDetailWithColumnsEncryptID($projectID);
				if(count($getDetailsOfProject['project_details'])>0 && count($getDetailsOfProject['column_heading'])>0){
					$user = $this->session->userdata('miner');
					$data['get_all_bounce_emails'] = $this->outsource_model->checkAllBounceEmailByProjectIDAndUserID($getDetailsOfProject['project_details']->id,$user->id);
				}
			}
		}
		echo json_encode($data);
		die;
	}
	
	
	function update_data(){
		$success = 0;
		if(isset($_POST) && count($_POST)>0){
			$data = $this->input->post('dataentry');
			if(!empty($data['key'])){
				$decryptKey = explode('~',$data['key']);
				if(count($decryptKey)==2){
					$this->load->model('outsource_model');
					$projectID = $decryptKey[1];
					$getDetailsOfProject = $this->outsource_model->findProjectFullDetailWithColumnsEncryptID($projectID);
					if(count($getDetailsOfProject['project_details'])>0 && count($getDetailsOfProject['column_heading'])>0){
						$user = $this->session->userdata('miner');
						$postData = $data['put'];
						if(!empty($postData) && $postData!="dataentry"){
							$dataOnPost = json_decode($postData,true);
							if(count($dataOnPost)>0){
								$row=0;
								for($i=0;$i<count($dataOnPost);$i++){
									$row = $i+1;
									$col=0;
									for($j=0;$j<count($dataOnPost[$i]);$j++){
										$col = $j+1;
										$dataEnter = false;
										if(count($getDetailsOfProject['column_heading'])>0){
											foreach($getDetailsOfProject['column_heading'] as $heading){
												if((int)$heading->status==1 && (int) $heading->column_num==$col){
													$dataEnter=true;
												}
											}											
										}
										if($dataEnter===true){
											$findData = $this->outsource_model->findProjectDataWithRowCol($row,$col,$getDetailsOfProject['project_details']->id);
											if(count($findData)>0){
												$postformData = trim($dataOnPost[$i][$j]);
												if(!empty($postformData)){
													$success = $this->outsource_model->updateFormData(array('data'=>$postformData,'enter_by'=>'User','user_id'=>$user->id,'recorded_date'=>date('Y-m-d H:i:s')),$findData->id);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		echo $success;
		die;
	}
}
/* End of file dataentry.php */
/* Location: ./application/controllers/outsource/dataentry.php */