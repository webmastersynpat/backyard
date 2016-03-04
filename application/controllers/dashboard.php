<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller { 
	function __construct(){
		parent::__construct();
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper(); 
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
		$this->load->model('opportunity_model');
		$this->load->model('lead_model');
		$this->load->model('user_model');
		$this->load->model('general_model');
		$this->load->model('client_model');
		$this->load->model('acquisition_model');
		$this->load->model('customer_model');
		$this->layout->auto_render=false;
		$this->layout->layout='default';
	}
	
	function run_process_single(){
		$data= array();
		if(isset($_POST) && count($_POST)>0 && isset($_POST['email']) && !empty($_POST['email'])){
			$data  = $this->lead_model->getCampaignListByEmailInProccess($_POST['email'],$_POST['campaign_id']);
		}		
		echo json_encode($data);
		die;
	}
	
	function run_process(){
		$data = $this->lead_model->getRecentCampaignProcess();
		echo json_encode($data);
		die;
	}
	
	public function sales_activity_email_save(){
		$data = 0; 
		if(isset($_POST) && count($_POST)>0){			
			if(isset($_POST['lead_id']) && (int)$_POST['lead_id']>0){
				if(isset($_POST['address'])){
					$to = trim($_POST['address']);
					if(!empty($to)){
						$message = "";
						if((int)$_POST['type']==3){
							$getContact = $this->client_model->find_contact_by_email($to);
							$message = "Sales activity for email.";
						} else {
							$getContact = $this->client_model->find_contact_by_linkedin($to);
							$message = "Sales activity for linkedin.";
						}
						
						if(count($getContact)){
							$note = '';
							$subject = '';
							
							if(isset($_POST['subject'])){
								$subject = $_POST['subject'];
							}
							$date = $this->input->post('campaign_date');
							$email_id = 0 ;
							if((int)$_POST['type']==3){
								$send = $this->findEmailImapSendBox($subject,array($to,""),$date,$_POST['lead_id'],1,1,$_POST['user_id']);
								$dataSend = json_decode($send,true);
								if(isset($dataSend['send'])){
									$email_id = $dataSend['send'];
								} else if(isset($dataSend->send)){
									$email_id = $dataSend->send;
								}
							}
							$date = $this->lead_model->findEmailDate($email_id);
							$saveData = array('lead_id'=>$_POST['lead_id'],'company_id'=>$getContact->company_id,'contact_id'=>$getContact->id,'type'=>$_POST['type'],'note'=>$note,'user_id'=>$_POST['user_id'],'subject'=>$subject,'activity_date'=>$date,"email_id"=>$email_id);		
							
							if((int)$_POST['main_activity']==2){
								$data = $this->lead_model->insertAcquistionActivity($saveData);
							} else if((int)$_POST['main_activity']==3){
								$saveData['company_id'] = $_POST['pre_co_id'];
								$data = $this->lead_model->insertPreSaleActivity($saveData);
							} else {
								/*$saveData['note'] = '<div>'.$note.'</div>';*/
								$data = $this->lead_model->insetSalesActivity($saveData);
							}
							if($data>0){
								$this->lead_model->updateCampaignList($_POST['campaign_id'],$to,array('send'=>1,'proccessed'=>1));
								$user_history = array('lead_id'=>$_POST['lead_id'],'user_id'=>$_POST['user_id'],'message'=>$message,'opportunity_id'=>0,'create_date'=>$date);
								$this->user_model->addUserHistory($user_history);
							}							
						}
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function end_campaign_process(){
		if(isset($_POST) && count($_POST)>0 && $_POST['process_id']>0){
			echo $this->lead_model->deleteCampaignProcess($_POST['process_id']);
		}
		die;
	}
	
	function log_user_history(){
		$data =0;
		if(isset($_POST) && count($_POST)>0){
			$user_history = array('lead_id'=>$_POST['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>$_POST['message'],'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
			$data = $this->user_model->addUserHistory($user_history);
		}
		echo $data;
		die;
	}
	
	function update_sales_company_stage(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->updateSalesCompanyStage($_POST['lead'],$_POST['c'],array("stage"=>$_POST['s']));
		}
		echo $data;
		die;
	}
	
	function find_contacts_in_company(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->c_my_contact_list($_POST['c'],"*");
		}
		echo json_encode($data);
		die;
	}
	
		
	function extract_email_address ($string) {
	   $emails = array();
	   $string = str_replace("\r\n",' ',$string);
	   $string = str_replace("\n",' ',$string);

	   foreach(preg_split('/ /', $string) as $token) {
			$email = filter_var($token, FILTER_VALIDATE_EMAIL);
			if ($email !== false) { 
				$emails[] = $email;
			}
		}
		return $emails;
	}
	
	
	
	function sendRequestURL(){
		$data =array();
		if(isset($_POST) && count($_POST)>0){
			if($this->input->post('t')=='c3luUGF0TWFya2V0'){
				if(!isset($_SESSION)){
					session_start();
				}
				$_SESSION['clicked_url']="dashboard";
				unset($_SESSION['clickedd_url']);
				unset($_SESSION['clickedddd_url']);
				$data =array(base64_encode(rand()));
			}
		} 
		echo json_encode($data);
		die;
	}
	
	
	
	function m_time(){
		$start_date = new DateTime($this->session->userdata['login_date']);
		$end_date = new DateTime(date('Y-m-d H:i:s'));
		$interval = $start_date->diff($end_date);
		$hours   = $interval->format('%h'); 
		$minutes = $interval->format('%i');
		echo $hours.":".$minutes;
		die;
	}
	
	function validateDriveFilePermission(){
		if(isset($_POST) && count($_POST)>0){
			$file = $this->input->post('f');
			$file = str_replace("https://docs.google.com/document/d/","",$file);
			$file = str_replace("https://docs.google.com/spreadsheets/d/","",$file);
			$file = str_replace("https://docs.google.com/file/d/","",$file);
			$file = str_replace("https://drive.google.com/file/d/","",$file);
			$file = str_replace("/view?usp=drivesdk","",$file);
			$file = str_replace("/preview","",$file);
			$file = str_replace("/edit?usp=drivesdk","",$file);
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$getFileDetail = $service->getFileInfo($file);
			if($getFileDetail){
				if($getFileDetail->mimeType=="application/vnd.google-apps.spreadsheet"){
					$service->setAdditionalPermissions( $getFileDetail->id, "","writer","anyone");
				} else {
					$service->setAdditionalPermissions($getFileDetail->id,"","reader","anyone");
				}				
			}
		}		
		die;
	}
	
	function drive_mode(){
		$data =array('status'=>'','url'=>'');
		if(isset($_POST) && count($_POST)>0){
			$driveMode = $this->input->post();
			$getLeadData = $this->lead_model->getLeadData($driveMode['l']);
			if(count($getLeadData)>0){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$getFileDetail = $service->getFileInfo($driveMode['r']);
				if($getFileDetail){
					$folderID = $getLeadData->folder_id;
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $folderID );
					$title =  $getFileDetail->title;
					$strPos = stripos($title,"Master");
					if($strPos!==false){
						$title = substr($title,$strPos);
						$title = str_replace("Master",$getLeadData->lead_name,$title);
						$title = str_replace("master",$getLeadData->lead_name,$title);
						$strPos = stripos($title,$getLeadData->lead_name);
						if($strPos===false){
							$title = $title." - ".$getLeadData->lead_name;
						}
					} else {
						$title = str_replace("Master",$getLeadData->lead_name,$title);
						$title = str_replace("master",$getLeadData->lead_name,$title);
						$strPos = stripos($title,$getLeadData->lead_name);
						if($strPos===false){
							$title = $title." - ".$getLeadData->lead_name;
						}
					}
					$getFileInfo = $service->copyFile($getFileDetail->id,$title,$fileParent);
					if(is_object($getFileInfo)){
						$updateDate = date('Y-m-d H:i:s');
						$leadButtonData = $this->lead_model->findButtonID($driveMode['b'],$driveMode['l']);
						$newStatus = "";
						if(count($leadButtonData)>0){
							$originalButton = $this->lead_model->findOriginalButtonByButtonID($leadButtonData->button_id);
							if(count($originalButton)>0){
								$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButton->status_message.' <br/>';
								$newStatus = $leadButtonData->status_message_fill.$newStatus;
							}
						} else {
							$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span> <br/>';
						}
						$update = $this->lead_model->updateButton(array('file_url'=>$getFileInfo->alternateLink,'file_name'=>$title,'status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$driveMode['b']);
						$user_history = array('lead_id'=>$driveMode['l'],'user_id'=>$this->session->userdata['id'],'message'=>$newStatus,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						if($update){
							$data['status'] = $updateDate;
							$data['url'] = $getFileInfo->alternateLink;
						}
					}
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	function task_mode(){
		$data =array('status'=>'');
		if(isset($_POST) && count($_POST)>0){
			$emailMode = $this->input->post();
			$getLeadData = $this->lead_model->getLeadData($emailMode['l']);
			if(count($getLeadData)>0){
				$updateDate = date('Y-m-d H:i:s');
				$leadButtonData = $this->lead_model->findButtonID($emailMode['b'],$emailMode['l']);
				$newStatus = "";
				if(count($leadButtonData)>0){
					$originalButton = $this->lead_model->findOriginalButtonByButtonID($leadButtonData->button_id);
					if(count($originalButton)>0){
						$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButton->status_message.' <br/>';
						$newStatus = $leadButtonData->status_message_fill.$newStatus;
					}
				} else {
					$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span> <br/>';
				}
				$update = $this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$emailMode['b']);
				$user_history = array('lead_id'=>$emailMode['l'],'user_id'=>$this->session->userdata['id'],'message'=>$newStatus,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				if($update){
					$data['status'] = $updateDate;
				}
			}
		}
		echo json_encode($data);
		die;
	}
	
	
	function email_mode(){
		$data =array('status'=>'','detail'=>'');
		if(isset($_POST) && count($_POST)>0){
			$emailMode = $this->input->post();
			$findTemplate = $this->general_model->getTemplate($emailMode['r']);
			if(count($findTemplate)>0){
				$data['detail'] = $findTemplate;
				$updateDate = date('Y-m-d H:i:s');
				$leadButtonData = $this->lead_model->findButtonID($emailMode['b'],$emailMode['l']);
				$newStatus = "";
				if(count($leadButtonData)>0){
					$originalButton = $this->lead_model->findOriginalButtonByButtonID($leadButtonData->button_id);
					if(count($originalButton)>0){
						$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButton->status_message.' <br/>';
						$newStatus = $leadButtonData->status_message_fill.$newStatus;
					}
				} else {
					$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span> <br/>';
				}
				$update = $this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$emailMode['b']);
				$user_history = array('lead_id'=>$emailMode['l'],'user_id'=>$this->session->userdata['id'],'message'=>$newStatus,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				if($update){
					$data['status'] = $updateDate;
				}
			}			
		}
		echo json_encode($data);
		die;
	}
	
	function search_contact(){
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION['all_contacts'] = $this->client_model->getAllAutoCompleteContacts();
		echo json_encode($_SESSION['all_contacts']);
		die;
	}
	
	public function c_my_contact_list(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->c_my_contact_list($this->input->post('c'),"c.id as contactID,c.first_name as firstName, c.last_name as lastName");
		}
		echo json_encode($data);
		die;
	}	
	function run_login(){
		$data = "0";
		if(isset($_POST) && count($_POST)>0){
			$check = $this->input->post('d');
			if((int)$check==1){
				/*Insert*/
				if($this->input->post('l')>0){					
					$checkLead = $this->user_model->checkLastTimeLoglead($this->input->post('l'),date('Y-m-d H:i:s',strtotime('-1 hours')),$this->session->userdata['id']);
					if(count($checkLead)==0){						
						$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('l'),'sid'=>$this->session->userdata['session_id'],'login_date'=>date('Y-m-d H:i:s')));
						$data = 1;
					} else {						
						$this->user_model->updateLogTime($this->session->userdata['id'],array('logout_date'=>date('Y-m-d H:i:s')));
						$data = 1;
					} 
				} else {					
					$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('l'),'sid'=>$this->session->userdata['session_id'],'login_date'=>date('Y-m-d H:i:s')));
					$data = 1;
				}				
			} else if((int)$check==0){
				
				/*update*/
				$data = 1;
				$this->user_model->updateLogTime($this->session->userdata['id'],array('logout_date'=>date('Y-m-d H:i:s')));
			}
		}
		echo $data;
		die;
	}
		
	function lead_login(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$oldLead = $this->input->post('o');
			$newLead = $this->input->post('n');
			$this->user_model->updateLogTime($this->session->userdata['id'],array('logout_date'=>date('Y-m-d H:i:s')));
			if((int)$oldLead !=(int)$newLead && (int)$oldLead>0 && (int)$newLead>0){
				$logoutDate = date('Y-m-d H:i:s',strtotime('2 seconds',time()));
				$loginDate = date('Y-m-d H:i:s',strtotime('1 seconds',strtotime($logoutDate)));
				if((int)$oldLead>0){
					$data = 1;
					$this->user_model->updateLeadLogTime($this->session->userdata['id'],$oldLead,array('logout_date'=>$logoutDate));
				}
				if((int)$newLead>0){
					$data = 1;
					$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$newLead,'sid'=>$this->session->userdata['session_id'],'login_date'=>$loginDate));
				}
			} else {
				if((int)$oldLead==0 && (int)$newLead>0){
					$checkLead = $this->user_model->checkLastTimeLoglead($newLead,date('Y-m-d H:i:s',strtotime('-1 hours')),$this->session->userdata['id']);
					if(count($checkLead)==0){
						$data = 1;
						$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$newLead,'sid'=>$this->session->userdata['session_id'],'login_date'=>date('Y-m-d H:i:s')));
					} else {
						$data = 1;
					$this->user_model->updateLeadLogTime($this->session->userdata['id'],$newLead,array('logout_date'=>date('Y-m-d H:i:s')));
					}
				} else if((int)$oldLead==(int)$newLead)	{
					$data = 1;
					$this->user_model->updateLeadLogTime($this->session->userdata['id'],$oldLead,array('logout_date'=>date('Y-m-d H:i:s')));
				}			
			}		
		}
		echo $data;
		die;
	}
	
	function check_login(){
		$data = "0";
		if(isset($_POST) && count($_POST)>0){
			$lastLogin = $this->user_model->checkLastLog($this->session->userdata['id']);
			if($lastLogin==true){
				$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'sid'=>$this->session->userdata['session_id'],'login_date'=>date('Y-m-d H:i:s')));
				$data = 1;
			}
		}
		echo $data;
		die;
	}
	
	function button_renewable(){
		$data = "";
		if(isset($_POST) && count($_POST)>0){
			$buttonID = $this->input->post('b');
			$leadID = $this->input->post('l');
			if($buttonID>0){
				$buttonData = $this->lead_model->findButtonID($buttonID,$leadID);
				if(count($buttonData)>0){
					$renewable = 0;
					if($buttonData->renewable==0){
						$renewable = 1;
					}
					$this->lead_model->updateButton(array("renewable"=>$renewable),$buttonID);
					if($renewable=="0"){
						$data = "Disable renewable.";
					} else {
						$data = "Enable renewable.";
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function oldLinkInBulk(){
		$send =0;
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			if(!empty($box['e'])){
				$allEm = json_decode($box['e']);
				$activityType=0;
				if(isset($box['t']) && (int)$box['t']){
					$activityType=$box['t'];
					unset($box['t']);
				}
				if(count($allEm)>0){
					for($e=0;$e<count($allEm);$e++){
						$findData = $this->lead_model->findBoxNewById($allEm[$e]);
						if(count($findData)>0){
							$sendData = 0;
							foreach($findData as $thread){
								$this->lead_model->updateBox($thread->id,array("sent_from"=>1));								
								$event = array();
								if(isset($box['p']) && !empty($box['p']) && $box['p']!="," && $box['p']!=", "){
									$type=1;
								}
								
								$message = "";
								if(!empty($thread->file_attach)){
									$attachment = explode(',',$thread->file_attach);
									foreach($attachment as $url){
										if($url!=""){
											$href = $url;
											$title = pathinfo($href,PATHINFO_FILENAME);
											$message .= '<a  href="javascript://" data-file-id="" onclick=\'open_drive_files("'.trim($href).'");\' data-href="'.$href.'" data-mime="" target="_blank" style="color:#2196f3" class="file_draggable"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$title.'</a> &nbsp; &nbsp; &nbsp;';
										}							
									}
								}
								$event['company_id'] = $box['c_id'];
								$event['contact_id'] = $box['p'];
								if($thread->sent_from==1){
									$event['type'] = 3;
								} else{
									$event['type'] = 6;
								}								
								$event['note'] = $message;
								$event['user_id'] = $this->session->userdata['id'];
								$event['email_id'] = $thread->id;
								$event['subject'] = "View Email";
								$event['lead_id'] = $box['l'];
								$event['activity_date'] = date('Y-m-d H:i:s');
								if($activityType==2){
									$sendData = $this->lead_model->insertAcquistionActivity($event);
								}/* else {
									$this->lead_model->insertAcquistionActivity($event);
								}*/

							}
							if($sendData>0){
								$this->lead_model->updateBox($thread->id,array('type'=>1));
								$user_history = array('lead_id'=>$box['l'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into acquisition activity.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);
								$send= 1;
							} else {
								$send= 0;
							}
						}
					}
				}				
			}
		}
		echo json_encode(array('send'=>$send));
		die;
	}
	
	function oldlinkWithMessage(){
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			/*$findData = $this->lead_model->findBoxByThread($box['thread']);*/
			$findData = $this->lead_model->findBoxNewById($box['thread']);
			if(count($findData)>0){
				$sendData = 0;
				foreach($findData as $thread){
					$this->lead_model->updateBox($thread->id,array("sent_from"=>1));
					$activityType=0;
					$event = array();
					if(isset($box['p']) && !empty($box['p']) && $box['p']!="," && $box['p']!=", "){
						$type=1;
					}
					if(isset($box['t']) && (int)$box['t']){
						$activityType=$box['t'];
						unset($box['t']);
					}
					$message = "";
					if(!empty($thread->file_attach)){
						$attachment = explode(',',$thread->file_attach);
						foreach($attachment as $url){
							if($url!=""){
								$href = $url;
								$title = pathinfo($href,PATHINFO_FILENAME);
								$message .= '<a  href="javascript://" data-file-id="" onclick=\'open_drive_files("'.trim($href).'");\' data-href="'.$href.'" data-mime="" target="_blank" style="color:#2196f3" class="file_draggable"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$title.'</a> &nbsp; &nbsp; &nbsp;';
							}							
						}
					}
					$getContactDetail = $this->lead_model->getContactById($box['p']);
					if(count($getContactDetail)>0){
						if($activityType==1){
							$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($box['old_thread'],$getContactDetail->company_id);
							if(count($checkCompanyInSalesActivity)==0){
								$this->opportunity_model->insertInvitees(array('lead_id'=>$box['old_thread'],'contact_id'=>$getContactDetail->company_id));
							}
							
						} else if($activityType==2){
							$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($box['old_thread'],$getContactDetail->company_id);
							if(count($checkCompanyInAcquisitionActivity)==0){
								$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$box['old_thread'],'contact_id'=>$getContactDetail->company_id));
							}
						}
					}
					
					
					$event['company_id'] = $box['c_id'];
					$event['contact_id'] = $box['p'];
					if($thread->sent_from==1){
						$event['type'] = 3;
					} else{
						$event['type'] = 6;
					}
					$event['note'] = $message;
					$event['user_id'] = $this->session->userdata['id'];
					$event['email_id'] = $thread->id;
					$event['subject'] = "View Email";
					$event['lead_id'] = $box['old_thread'];
					$event['activity_date'] = date('Y-m-d H:i:s');
					$this->lead_model->deleteAcquisitionActivity($thread->id);
					$this->lead_model->deleteSalesActivity($thread->id);
					if($activityType==1){
						$sendData = $this->lead_model->insetSalesActivity($event);
					} else if((int)$activityType==3){
						$data = $this->lead_model->insertPreSaleActivity($event);
					} else {
						$sendData = $this->lead_model->insertAcquistionActivity($event);
					}
				}
				if($sendData>0){
					$this->lead_model->updateBox($thread->id,array('type'=>1,'lead_id'=>$box['old_thread']));					
					$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into acquisition activity.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					echo json_encode(array('send'=>1));
				} else {
					echo json_encode(array('send'=>0));
				}
			}else {
				echo json_encode(array('send'=>0));
			}
		}else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	function move_drive_file(){
		$data = array('send'=>0);
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			$leadInfo = $this->lead_model->getLeadData($box['old_thread']);
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$fileMoved = $service->moveFile($box['drive'],$leadInfo->folder_id);
			if(is_object($fileMoved)){
				$data['send'] = 1;
			} 
		}
		echo json_encode($data);
		die;
	}
	
	function move_drive_file_in_lead_folder(){
		$data = array('send'=>0);
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$fileMoved = $service->moveFile($box['d'],$box['f']);
			if(is_object($fileMoved)){
				$data['send'] = 1;
			} 
		}
		echo json_encode($data);
		die;
	}
	
	function findEmailImapSendBox($subject,$toUsers,$date,$leadID,$type=1,$sendFrom=0,$userID=0){
		if($userID==0){
			$userID = $this->session->userdata['id'];
		}
		$data = array('send'=>0);
		$hostname = 'imap.gmail.com:993';
		$this->config->load('config');
		$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl');
		$this->load->library('Imap',$params);
		$s = 0;
		$mainMessage=array();
		if($this->imap->isConnected()){
			$this->imap->selectFolder('[Gmail]/Sent Mail');
			$messages = $this->imap->getMessages();			
			if(count($messages)>0){
				for($m=0;$m<count($messages);$m++){
					$s = 0;
					if(trim($messages[$m]['subject'])==trim($subject)){						
						$toSendBox = $messages[$m]['to'];
						foreach($toSendBox as $to){
							$to = substr($to,0,stripos($to,'<'));
							if(count($toUsers)>0){
								for($i=0;$i<count($toUsers)-1;$i++){
									if(trim($toUsers[$i]) == trim($to)){
										$s = $s +1;
									}
								}
							}
						}					
						if($s == count($toSendBox)){
							$mainMessage=$messages[$m];
							break; 
						}
					}
				}
			}
		}
		if($s>0 && $mainMessage>0){
			$attachmentsConnect = array();
			$filesAttachment = "";
			if(isset($mainMessage['attachments']) && count($mainMessage['attachments'])>0){
				$a = 0;
				foreach($mainMessage['attachments'] as $attachment){
					$filename = $attachment['name'];
					$attachmentBody = $this->imap->getAttachment($mainMessage['uid'],$a);
					if(isset($attachmentBody['content'])&& !empty($attachmentBody['content'])){
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						fwrite($fh, $attachmentBody['content']);
						fclose($fh);
						$finfo = finfo_open(FILEINFO_MIME_TYPE); 
						$mimeType = finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
						$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
						$attachmentsConnect[] = array('filename'=>$_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename,'mimeType'=>$mimeType,"attachmentId"=>$a,"size"=>$attachmentBody['size']);
					}
					$a++;
				}
			}
			$mainMessage['attachments'] = $attachmentsConnect;
			$saveData = array('content'=>json_encode($mainMessage),'file_attach'=>$filesAttachment,"lead_id"=>$leadID,"user_id"=>$userID,"date_received"=>date('Y-m-d H:i:s', strtotime($mainMessage['date'])),'type'=>1,'sent_from'=>1,'account_type'=>'2');
			$sendData = $this->lead_model->insertBox($saveData);
			if($sendData>0){
				$data = array('send'=>$sendData);
			}
		}
		return json_encode($data);
	}
	
	function find_lead_by_contact_id(){
		$list = array();
		if(isset($_POST) && isset($_POST['contact_id']) && !empty($_POST['contact_id']) && (int)$_POST['contact_id']){
			$list = $this->opportunity_model->checkAllLeadsByContact($_POST['contact_id']);
		}
		echo json_encode(array('list'=>$list));
		die;
	}
	
	function find_activites_leads_from_email_id(){
		$data= array();
		if(isset($_POST) && isset($_POST['id']) && !empty($_POST['id'])){
			$data = $this->opportunity_model->checkAllLeadsFromEmailActivityByID($_POST['id']);
		}
		echo json_encode($data);
	}
	
	function find_activites_leads_from_email(){
		$list = array();
		$lead=0;
		$leadName="";
		$activity=0;
		$companyID=0;
		$personID=0;
		$count=0;
		if(isset($_POST) && isset($_POST['email']) && !empty($_POST['email'])){
			$getList = $this->opportunity_model->checkAllLeadsFromEmailActivity($_POST['email']);
			if(count($getList['list'])>1){
				$list = $getList['list'];
				if(count($getList['contact'])>0){
					$personID = $getList['contact']->id;
					$companyID = $getList['contact']->company_id;
				}
				$activity = $getList['activity'];
				$count = count($list);
			} else if(count($getList['list'])==1){
				if(count($getList['contact'])>0){
					$personID = $getList['contact']->id;
					$companyID = $getList['contact']->company_id;
				}
				$activity = $getList['list'][0]->activity;
				$lead = $getList['list'][0]->id;
				$leadName = $getList['list'][0]->lead_name;
			}
		}		
		$data = array('count'=>$count,'lead'=>$lead,'activity'=>$activity,'company_id'=>$companyID,'person_id'=>$personID,'list'=>$list,'lead_name'=>$leadName);
		echo json_encode($data);
		die;		
	}
	
	function runCheckingEmailFailureActivity(){
		$data = array('email'=>'','failure'=>'0');
		if(isset($_POST) && count($_POST)>0){
			$data = $_POST;
			$data['failure'] = 0;
			$hostname = 'imap.gmail.com:993';
			$this->config->load('config');
			$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl');
			$this->load->library('Imap',$params);
			if($this->imap->isConnected()){
				if(isset($data['sentFrom'])){
					if((int)$data['sentFrom']==0){
						$this->imap->selectFolder('INBOX');
					}else if((int)$data['sentFrom']==1){
						$this->imap->selectFolder('[Gmail]/Sent Mail');
					}	
				} else {
					$this->imap->selectFolder('INBOX');
				}							
				$message = $this->imap->getMessage($data['msgNo']);
				$body = $message['body'];
				$pos = strpos($body,'Message-ID');
				if($pos!=false){
					$body = substr($body,$pos) ;
					$pos1 = strpos($body,"backyard.synpat.com");					
					$email = "";
					if($pos1 !=false){
						$body = substr($body,11,$pos1+19) ;
						$explodeLess = explode('&lt;',trim($body));
						$stringAgain = implode('',$explodeLess);
						$stringAgain = trim($stringAgain);
						$stringAgain = explode('<',$stringAgain);
						$stringAgain = implode('',$stringAgain);
						$stringAgain = trim($stringAgain);
						$explodeLess = explode('&gt;',$stringAgain);
						if(count($explodeLess)>0){
							$emailSp = trim($explodeLess[0]);
							if(!empty($emailSp)){
								$email = $emailSp;
								if(strpos($email,'>')){
									$email = substr($email,0,strpos($email,'>'));
								}
								$email =filter_var($email, FILTER_VALIDATE_EMAIL);
							}
						}
					}
					if(empty($email)):
						$pos1 = strpos($body,"X-Mailer");
						if($pos1 !=false){
							$body = substr($body,11,$pos1+9) ;
							$explodeLess = explode('&lt;',trim($body));
							$stringAgain = implode('',$explodeLess);
							$stringAgain = trim($stringAgain);
							$stringAgain = explode('<',$stringAgain);
							$stringAgain = implode('',$stringAgain);
							$stringAgain = trim($stringAgain);
							$explodeLess = explode('&gt;',$stringAgain);
							if(count($explodeLess)>0){
								$emailSp = trim($explodeLess[0]);
								if(!empty($emailSp)){
									$email = $emailSp;
									if(strpos($email,'>')){
										$email = substr($email,0,strpos($email,'>'));
									}
									$email =filter_var($email, FILTER_VALIDATE_EMAIL);
								}
							}
						}
					endif;
					if(!empty($email)){
						$searchData = $this->lead_model->fincImapEmailWithMessageID($email);
						if(count($searchData)>0){							
							/*Assign failure email with contact and lead and */
							$attachmentsConnect = array();
							$filesAttachment = "";
							if(isset($message['attachments']) && count($message['attachments'])>0){
								$a = 0;
								foreach($message['attachments'] as $attachment){
									$filename = $attachment['name'];
									$attachmentBody = $this->imap->getAttachment($data['UID'],$a);
									if(isset($attachmentBody['content'])&& !empty($attachmentBody['content'])){
										$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
										fwrite($fh, $attachmentBody['content']);
										fclose($fh);
										$finfo = finfo_open(FILEINFO_MIME_TYPE); 
										$mimeType = finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
										$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
										$attachmentsConnect[] = array('filename'=>$_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename,'mimeType'=>$mimeType,"attachmentId"=>$a,"size"=>$attachmentBody['size']);
									}
									$a++;
								}
							}
							$contact = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($searchData[0]->id);
							if(count($contact)==0){
								$contact = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($searchData[0]->id);
								if(count($contact)>0){
									$activity = 2;
								} else {
									$contact = $this->lead_model->getPersonCompanyDetailFromPreSalesActivityLogByEmailID($searchData[0]->id);
									if(count($contact)>0){
										$activity = 3;
									} else {
										$contact = (object)array('contact_id'=>0,'company_id'=>0);
									}
								}
							} else{
								$activity = 1;
							}
							$message['attachments'] = $attachmentsConnect;
							if((int)$searchData[0]->lead_id>0):
							$saveData = array('content'=>json_encode($message),'file_attach'=>$filesAttachment,"lead_id"=>$searchData[0]->lead_id,"user_id"=>$this->session->userdata['id'],"date_received"=>date('Y-m-d H:i:s', strtotime($message['date'])),'type'=>1,'account_type'=>'2');
							$sendData = $this->lead_model->insertBox($saveData);
							if($sendData>0){
								/*Move Message to Lead Folder*/
								$this->imap->copyMessage($data['msgNo'],'Leads');
								$ID = 0;
								if((int)$searchData[0]->aID>0){
									$ID = $searchData[0]->aID;
								} else if((int)$searchData[0]->sID>0){
									$ID = $searchData[0]->sID;
								}else if((int)$searchData[0]->pID>0){
									$ID = $searchData[0]->pID;
								}
								$data['lead_id'] = $searchData[0]->lead_id;
								$data['activity'] = $activity;
								if($activity==1){
									$this->lead_model->updateSalesActivity($ID,array('error'=>1));
								} else if($activity==3){
									$this->lead_model->updatePreSaleActivity($ID,array('error'=>1));
								} else if($activity==2){
									$this->lead_model->updateAcquistionActivity($ID,array('error'=>1));
								}								
								$event = array();
								$event['company_id'] = $contact->company_id;
								$event['contact_id'] = $contact->contact_id;
								$event['type'] = 6;
								$event['note'] = '';
								$event['user_id'] = $this->session->userdata['id'];
								$event['email_id'] = $sendData;
								$event['subject'] = $message['subject'];
								$event['lead_id'] = $searchData[0]->lead_id;
								$event['activity_date'] = date('Y-m-d H:i:s', strtotime($message['date']));
								/*$this->lead_model->insetSalesActivity($event);*/
								if($activity==1){
									$this->lead_model->insetSalesActivity($event);
								} else if($activity==3){
									$this->lead_model->insertPreSaleActivity($event);
								} else {
									$this->lead_model->insertAcquistionActivity($event);
								}
								/*$data = $sendData;*/
								/*update sales activity*/
								$data['failure'] = 1;
								$data['send_email'] = $sendData;
								$user_history = array('lead_id'=>$searchData[0]->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);						
							}
							endif;
						}
					} else {
						$data['failure'] = 0;
					}					
				} else {
					$data['failure'] = 0;
				}
			} else {
				$data['failure'] = 0;
			}
		}
		echo json_encode($data);
		die;
	}
	
	function linkImapMessage(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;				
			$hostname = 'imap.gmail.com:993';
			$this->config->load('config');
			$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl');
			$this->load->library('Imap',$params);
			if($this->imap->isConnected()){
				$this->imap->selectFolder('INBOX');
				$message = $this->imap->getMessage($box['msg_no']);
				$attachmentsConnect = array();
				$filesAttachment = "";
				if(isset($message['attachments']) && count($message['attachments'])>0){
					$a = 0;
					foreach($message['attachments'] as $attachment){
						$filename = $attachment['name'];
						$attachmentBody = $this->imap->getAttachment($box['uid'],$a);
						if(isset($attachmentBody['content'])&& !empty($attachmentBody['content'])){
							$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
							fwrite($fh, $attachmentBody['content']);
							fclose($fh);
							$finfo = finfo_open(FILEINFO_MIME_TYPE); 
							$mimeType = finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
							$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
							$attachmentsConnect[] = array('filename'=>$_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename,'mimeType'=>$mimeType,"attachmentId"=>$a,"size"=>$attachmentBody['size']);
						}
						$a++;
					}
				}
				$message['attachments'] = $attachmentsConnect;
				$saveData = array('content'=>json_encode($message),'file_attach'=>$filesAttachment,"lead_id"=>$box['lead_id'],"user_id"=>$this->session->userdata['id'],"date_received"=>date('Y-m-d H:i:s', strtotime($message['date'])),'type'=>1,'account_type'=>'2');
				$sendData = $this->lead_model->insertBox($saveData);
				if($sendData>0){
					$this->imap->moveMessage($box['msg_no'],'Leads');
					$event = array();
					$event['company_id'] = $box['c_id'];
					$event['contact_id'] = $box['p_id'];
					$event['type'] = 6;
					$event['note'] = '';
					$event['user_id'] = $this->session->userdata['id'];
					$event['email_id'] = $sendData;
					$event['subject'] = $message['subject'];
					$event['lead_id'] = $box['lead_id'];
					$event['activity_date'] = date('Y-m-d H:i:s');
					if($box['activity_type']==1){
						$this->lead_model->insetSalesActivity($event);
					}else if((int)$box['activity_type']==3){
						$data = $this->lead_model->insertPreSaleActivity($event);
					} else if((int)$box['activity_type']==2){
						$this->lead_model->insertAcquistionActivity($event);
					}
					$data = $sendData;
					$user_history = array('lead_id'=>$box['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);						
				}
			}
		}
		echo $data;
		die;
	}
	
	
	
	function linkWithMessage(){
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;			
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$service->setAccessToken($_SESSION['another_access_token']);			
			$emails = $_SESSION['STARRED'];
			$findIDFlag = 0;
			$message = "";
			$thread_id = "";
			$threadComesFrom = "STARRED";
			if(count($emails)>0){
				foreach($emails as $email){
					if($email['message_id'] == $box['thread']){
						$findIDFlag = 1;
						$message = $email['content'];
						$thread_id = $email['thread_id'];
					}
				}
			}
			if($findIDFlag==0){
				$emails = $_SESSION['INBOX'];
				$threadComesFrom = "INBOX";
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $box['thread']){
							$findIDFlag = 1;
							$message = $email['content'];
							$thread_id = $email['thread_id'];
						}
					}
				}
			}			
			if($findIDFlag==0){
				$emails = $_SESSION['TRASH'];
				$threadComesFrom = "TRASH";
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $box['thread']){
							$findIDFlag = 1;
							$message = $email['content'];
							$thread_id = $email['thread_id'];
						}
					}
				}
			}
			if($findIDFlag==0){
				$emails = $_SESSION['LEAD'];
				$threadComesFrom = "LEAD";
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $box['thread']){
							$findIDFlag = 1;
							$message = $email['content'];
							$thread_id = $email['thread_id'];
						}
					}
				}
			}
			if($findIDFlag==0){
				$messageDetail = $service->findThreadData($box['thread']);
				$findIDFlag = 1;
				$message = $messageDetail[0]['content'];
				$thread_id = $messageDetail[0]['thread_id'];
			}
			if($findIDFlag==0){
				$emails = $_SESSION['SENT'];
				$threadComesFrom = "SENT";
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $box['thread']){
							$findIDFlag = 1;
							$message = $email['content'];
							$thread_id = $email['thread_id'];
						}
					}
				}
			}
			$filesAttachment = "";			
			$gmailMessageID = $box['thread'];			
			$attachmentArray = array();
			$content = array();
			if(is_object($message)>0){
					$messageBody = '';
					$attachmentsConnect = array();
					$parts = $message->getPayload()->getParts();
					$header = $message->getPayload()->getHeaders();
					if(count($parts)>0){
						if($parts[0]->mimeType=='text/plain' || $parts[0]->mimeType=='text/html'){						
							if(isset($parts[1])  && $parts[1]->mimeType=='text/html'){
								$rawBody = $parts[1]->getBody();
							} else {
								$rawBody = $parts[0]->getBody();
							}
							$rawData = $rawBody->data;	
							$sanitizedData = strtr($rawData,'-_', '+/');
							$messageBody = base64_decode($sanitizedData);
						} else if($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related" || $parts[0]->mimeType=="multipart/mixed"){						
							$internalParts = $parts[0]->getParts();
							$rawBody = $internalParts[1]->getBody();
							$rawData = $rawBody->data;	
							if(empty($rawData)){
								$rawParts = $internalParts[0]->getParts();
								$rawBody = $rawParts[1]->getBody();
								$rawData = $rawBody->data;
							}
							$sanitizedData = strtr($rawData,'-_', '+/');
							$messageBody = base64_decode($sanitizedData);
						} 
						if(empty($messageBody)){
							if(isset($parts[0])){
								$internalParts = $parts[0]->getParts();
								if(isset($internalParts[1])){
									$rawBody = $internalParts[1]->getBody();
									$rawData = $rawBody->data;									
								} else if(isset($internalParts[0])){
									$rawParts = $internalParts[0]->getParts();
									$rawBody = $rawParts[1]->getBody();
									$rawData = $rawBody->data;
								} else if(isset($parts[0])){
									$rawBody = $parts[1]->getBody();
									$rawData = $rawBody->data;									
								} else {
									$rawBody = $parts[0]->getBody();
									$rawData = $rawBody->data;		
								}
								$sanitizedData = strtr($rawData,'-_', '+/');
								$messageBody = base64_decode($sanitizedData);
							}
						}
					} else {
						$rawBody = $message->getPayload()->getBody();
						$rawData = $rawBody->data;	 
						$sanitizedData = strtr($rawData,'-_', '+/');
						$messageBody = base64_decode($sanitizedData);
						$messageBody = nl2br($messageBody);
					}
					
					if(count($parts)>0 && (isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related" || $parts[0]->mimeType=="multipart/mixed") || $parts[0]->mimeType=="text/plain" || $parts[0]->mimeType=="text/html")){
						for($i=1;$i<count($parts);$i++){
							if(isset($parts[$i])){
								$attachments = $parts[$i];
								$filename =  $attachments->filename;
								$fileExt = explode('.',$filename);
								if(count($fileExt)==2){
									if($fileExt[1]==""){
										switch($parts[$i]->mimeType){
											case 'application/pdf':
											$filename= $filename.".pdf";
											break;
											case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
											$filename= $filename.".docx";
											break;
											case 'application/msword':
											$filename= $filename.".doc";
											break;
											case 'application/oda':
											$filename= $filename.".oda";
											break;
											case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
											$filename= $filename.".dotx";
											break;
											case 'application/vnd.ms-excel':
											$filename= $filename.".xls";
											break;
											case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
											$filename= $filename.".xlsx";
											break;
											case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
											$filename= $filename.".pptx";
											break;
											case 'application/vnd.openxmlformats-officedocument.presentationml.slide':
											$filename= $filename.".sldx";
											break;
											case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
											$filename= $filename.".ppsx";
											break;
											case 'image/jpeg':
											$filename= $filename.".jpeg";
											break;
											case 'image/png':
											$filename= $filename.".png";
											break;										
											case 'image/bmp':
											$filename= $filename.".bmp";
											break;
											case 'image/gif':
											$filename= $filename.".gif";
											break;
											
										}
									}
								}
								$attachmentID = $attachments->getBody()->getAttachmentId();	
								if(!in_array($attachmentID,$attachmentArray)){
									if(!empty($filename) && !empty($attachmentID)){
										$attachmentsData = $service->downloadAttachments($gmailMessageID,$attachmentID);
										if(is_object($attachmentsData)){
											$rawData = $attachmentsData->data;
											$sanitizedData = strtr($rawData,'-_', '+/');
											$data = strtr($rawData, array('-' => '+', '_' => '/'));
											$body = base64_decode($sanitizedData);
											$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
											fwrite($fh, $body);
											fclose($fh);	
											$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
											$attachmentsConnect[] = array('filename'=>$filename,'mimeType'=>$parts[$i]->mimeType,"attachmentId"=>$attachmentID,"size"=>$parts[$i]->getBody()->getSize(),"realAttachID"=>'');
										}
									}
								}
							}														
						}
					}
					$content[] = array("message_id"=>$message->id,"thread_id"=>$thread_id,"labelIds"=>$message->labelIds,"header"=>$header,"body"=>$messageBody,"attachments"=>$attachmentsConnect,'ppp'=>$parts);
			}
			
			$type = 0;
			if(isset($box['c_id']) && isset($box['p']) && (int)$box['p']>0 && (int)$box['c_id']>0){
				$type = 1;
			}
			$activityType=0;
			if(isset($box['t']) && (int)$box['t']){
				$activityType=$box['t'];
			}
			if(isset($box['from_email']) && !empty($box['from_email'])){
				$getContactDetail = $this->lead_model->getContactByEmail(trim($box['from_email']));
				if(count($getContactDetail)>0){
					if($activityType!=3){
						$box['c_id'] = $getContactDetail->company_id;
					}
					
					$box['p'] = $getContactDetail->id;
					$act = 0;
					$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($box['old_thread'],$getContactDetail->company_id);
					if(count($checkCompanyInSalesActivity)>0){
						$act = 1;						
					}
					if($act==0){
						$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($box['old_thread'],$getContactDetail->company_id);
						if(count($checkCompanyInAcquisitionActivity)>0){
							$act = 2;
						} 
					}
					if($activityType==3){
						$act = 3;
					}
					if($act>0){
						$activityType=$act;
						$type = 1;
					} else {
						echo json_encode(array('send'=>0,'message'=>'Email address does not exist in any activity.'));
						die;
					}
				} else {
					echo json_encode(array('send'=>0,'message'=>'Email address does not exist, Please add email address in contacts.'));
					die;
				}
				if($act==0 || $type==0){
					echo json_encode(array('send'=>0,'message'=>'Got some error, Please try again.'));
					die;
				}
			}			
			if(!empty($content)){
				/*Check Thread in DB*/
				$this->lead_model->removeFromBox($box['old_thread'],$box['thread']);
				/*End checking*/
				if(!empty($filesAttachment)){
					$filesAttachment = substr($filesAttachment,0,-1);
				}
				$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],"user_id"=>$this->session->userdata['id'],"thread_id"=>$thread_id,'message_id'=>$box['thread'],"content"=>json_encode($content),"file_attach"=>$filesAttachment,"date_received"=>date('Y-m-d H:i:s', strtotime($box['date'])),'type'=>$type));
				$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				/*Delete Message*/
				$unEmail = $_SESSION[$threadComesFrom];
				if(count($unEmail)>0){					
					$index=-1;
					$i=0;
					foreach($unEmail as $email){
						if($email['message_id'] == $box['thread']){							
							$index=$i;
						}
						$i++;
					}
					if($index>=0){
						unset($unEmail[$index]);
						$emailsREIndex = array_values($unEmail);
						$_SESSION[$threadComesFrom] = $emailsREIndex;
					}					
				}
				/*Delete Message From Session*/
				
				if($type==1){
					
					if($activityType==1){
						$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($box['old_thread'],$box['c_id']);
						if(count($checkCompanyInSalesActivity)==0){
							$this->opportunity_model->insertInvitees(array('lead_id'=>$box['old_thread'],'contact_id'=>$box['c_id']));
						}
						
					} else if($activityType==2){
						$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($box['old_thread'],$box['c_id']);
						if(count($checkCompanyInAcquisitionActivity)==0){
							$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$box['old_thread'],'contact_id'=>$box['c_id']));
						}
					}
					$event = array();
					$event['company_id'] = $box['c_id'];
					$event['contact_id'] = $box['p'];
					$event['type'] = 6;
					$event['note'] = '';
					$event['user_id'] = $this->session->userdata['id'];
					$event['email_id'] = $sendData;
					$event['subject'] = '';
					$event['lead_id'] = $box['old_thread'];
					$event['activity_date'] = date('Y-m-d H:i:s');
					if($activityType==1){
						$this->lead_model->insetSalesActivity($event);
						$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Associate email to sales activity.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					}else if($activityType==3){
						$this->lead_model->insertPreSaleActivity($event);
						$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Associate email to PreSale activity.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					} else {
						$this->lead_model->insertAcquistionActivity($event);
						$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Associate email to acquisition activity.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					}
				}
				if($sendData>0){
					$labels = $service->listLabels();
					$labelID = "";
					foreach($labels as $key=>$label){
						if(strtoupper($label)=="LEAD"){
							$labelID = $key;
						}
					}
					if(empty($labelID)){
						/*Create label*/
						$label = $service->createLabel("LEAD");
						if(is_object($label) && $label->getId()!=""){
							$labelID = $label->getId();
						}
						/*End Create Label*/
					}
					if(!empty($labelID)){
						$service->modifyMessage($gmailMessageID,"me",$labelID);
						$service->modifyMessageRemove($gmailMessageID,"me",'INBOX');
						$service->modifyMessageRemove($gmailMessageID,"me",'STARRED');
					}  
					echo json_encode(array('send'=>$sendData));
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}			
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	function intialiseAfter(){
		if(isset($_POST) && count($_POST)>0){
			if((int)$this->input->post('inta')==1){
				$user_data = $this->session->userdata;
				$user_data['initialise_email'] = 1;
				$this->session->set_userdata($user_data);
				/**/
				$this->user_model->updateAllLogTime($this->session->userdata['id']);
				/**/
			}
		}
		die;
	}
	
	function index($id=null){		
		$data = array();
		if((int)$this->session->userdata['type']==8 || (int)$this->session->userdata['type']==9 ){
			$data['waiting_approval'] = $this->opportunity_model->waitingApproval($this->session->userdata['id']);
		}
		$data['emails'] = array();
		$this->load->library('DriveServiceHelper');
		if(!isset($_SESSION)){
			session_start();		
		}
		$service = new GmailServiceHelper();
		unset($_SESSION['clicked_url']);
		if(!isset($_SESSION['another_access_token'])){
			$data['auth_url'] = $service->createAuthUrl();
			$data['emails'] = array();
			$sign = $this->user_model->signature();
			$originalSignature = $sign->signature;
			$emailSignature = $originalSignature;
			$user = $this->user_model->getUserData($this->session->userdata['id']);
			$emailSignature = str_replace('#Name#',$user->name,$emailSignature);
			if(!empty($user->mobile_number)){
				$emailSignature = str_replace('#Mobile#',"M: ".$user->mobile_number,$emailSignature);
			} else {
				$emailSignature = str_replace('#Mobile#',"",$emailSignature);
			}
			$emailSignature = str_replace('#Email#',$user->email_for_signature,$emailSignature);
			$emailSignature = str_replace('#Title#',$user->title,$emailSignature);
			if(!empty($user->direct_number)){
				$emailSignature = str_replace('#Direct#',"D: ".$user->direct_number,$emailSignature);
			} else {
				$emailSignature = str_replace('#Direct#',"",$emailSignature);
			}
			$user_data = $this->session->userdata;
			$user_data['user'] = (array)$user;
			$user_data['email'] = $user->email;
			$user_data['name'] = $user->name;
			$user_data['phone_number'] = $user->phone_number;
			$user_data['mobile_number'] = $user->mobile_number;
			$user_data['direct_number'] = $user->direct_number;
			$user_data['email_for_signature'] = $user->email_for_signature;
			$user_data['title'] = $user->title;
			$user_data['signature'] = $emailSignature;
			$user_data['original_signature'] = $originalSignature;
			if(!isset($_SESSION)){
				session_start();
			}
	
			$_SESSION['find_user'] = $user_data;
			$this->session->set_userdata($user_data);
		} else {
			if($service->checkExpiredToken()){
				if($_SESSION['another_access_token']!=""){
					$google_token= json_decode($_SESSION['another_access_token']);					
					if(isset($google_token->refresh_token)){						
						$service->refreshToken($google_token->refresh_token);
						$newToken = json_decode($service->getAccessToken());
						$google_token->id_token = $newToken->id_token;
						$google_token->access_token = $newToken->access_token;
						$google_token->created = $newToken->created;
						$google_token = json_encode($google_token);						
						$_SESSION['another_access_token'] = $google_token;
						$_SESSION['access_token'] = $google_token;
					} else {
						$data['auth_url'] = $service->createAuthUrl();
						$data['emails'] = array();
					}					
				} else {
					$data['auth_url'] = $service->createAuthUrl();
					$data['emails'] = array();
				}
			}			
			$service->setAccessToken($_SESSION['another_access_token']);
			$userInfoArray = 	$service->getAuthUserEmail();			
			if(isset($userInfoArray->email)){				
				if($userInfoArray->email==$this->session->userdata['email']){
					/*$emailService = new SignatureServiceHelper();
					$authString = $emailService->getAccessToken();
					$getUserSignature = $emailService->getUserSignature($this->session->userdata['email']);
					$xmlString =  substr($getUserSignature,strrpos($getUserSignature,"<?xml"));
					$emailSignature = '';
					$originalSignature = '';
					if(!empty($xmlString)){ 
						$xml = new SimpleXmlElement($xmlString);
						$alias_email = $xml->children('apps', true)->property[0]->attributes();
						if(count($alias_email)>0){
							$emailSignature = (string)$alias_email['value'];
							$originalSignature = $emailSignature;
							$user_data = $this->session->userdata;
							$emailSignature = str_replace('#Name#',$user_data['name'],$emailSignature);
							$emailSignature = str_replace('#Mobile#',$user_data['phone_number'],$emailSignature);
							$emailSignature = str_replace('#Email#',$user_data['email'],$emailSignature);
							$emailSignature = str_replace('#Title#',$user_data['title'],$emailSignature);
							$emailSignature = str_replace('#Direct#',$user_data['direct_number'],$emailSignature);
						}
					}					
					$user_data = $this->session->userdata;
					$user_data['signature'] = $emailSignature;
					$user_data['original_signature'] = $originalSignature;
					if(!isset($_SESSION)){
						session_start();
					}
					$_SESSION['find_user'] = $user_data;					
					$this->session->set_userdata($user_data);
					*/
					
						$sign = $this->user_model->signature();
						$originalSignature = $sign->signature;
						$emailSignature = $originalSignature;
						$user = $this->user_model->getUserData($this->session->userdata['id']);
						$emailSignature = str_replace('#Name#',$user->name,$emailSignature);
						if(!empty($user->mobile_number)){
							$emailSignature = str_replace('#Mobile#',"M: ".$user->mobile_number,$emailSignature);
						} else {
							$emailSignature = str_replace('#Mobile#',"",$emailSignature);
						}
						$emailSignature = str_replace('#Email#',$user->email,$emailSignature);
						$emailSignature = str_replace('#Title#',$user->title,$emailSignature);
						if(!empty($user->direct_number)){
							$emailSignature = str_replace('#Direct#',"D: ".$user->direct_number,$emailSignature);
						} else {
							$emailSignature = str_replace('#Direct#',"",$emailSignature);
						}
						$user_data = $this->session->userdata;
						$user_data['user'] = (array)$user;
						$user_data['email'] = $user->email;
						$user_data['name'] = $user->name;
						$user_data['phone_number'] = $user->phone_number;
						$user_data['mobile_number'] = $user->mobile_number;
						$user_data['direct_number'] = $user->direct_number;
						$user_data['title'] = $user->title;
						$user_data['signature'] = $emailSignature;
						$user_data['original_signature'] = $originalSignature;
						if(!isset($_SESSION)){
							session_start();
						}
						$_SESSION['find_user'] = $user_data;
						$this->session->set_userdata($user_data);
					
					$data['auth_url'] = "";
				} else {
					$this->session->set_flashdata('error','Login with your email id only.');
					$data['emails'] = array();
					$data['auth_url'] = $service->createAuthUrl();
					$_SESSION['clicked_url'] = "dashboard";	
					unset($_SESSION['another_access_token']);
				}
			} else {
				$data['auth_url'] = $service->createAuthUrl();
				$_SESSION['clicked_url'] = "dashboard";	
				$data['emails'] = array();
				unset($_SESSION['another_access_token']);
			}			
		}
		$this->layout->title_for_layout = 'Backyard Dashboard';
		$this->layout->render('user/dashboard',$data);
	}
	
	public function charts($id=null){
		$this->layout->layout='opportunity';
		$this->layout->title_for_layout = 'Backyard Charts';
		$data['lucidPatents'] = $this->lead_model->findLucidData($id);
		if(count($data['lucidPatents'])==0){
			$getLeadData = $this->lead_model->getLeadData($id);
			if(count($getLeadData)>0){
				if(!empty($getLeadData->patent_data)){
					try{
						$patentData = json_decode($getLeadData->patent_data);
						if(count($patentData)>0){
							foreach($patentData as $patent){
								if($patent[0]!=null){
									$this->lead_model->insertChart(array("lead_id"=>$id,"patent"=>$patent[0]));
								}
							}
							$data['lucidPatents'] = $this->lead_model->findLucidData($id);
						}
					} catch(Exception $e ){
						
					}
				}
			}
		}
		$this->layout->render('user/chart',$data);
	}
	
	function createDocument($patent,$leadID){
		$documentId = $this->input->get('documentId');
		if(!empty($patent) && !empty($leadID) && !empty($documentId)){
			$this->lead_model->updateChartWithLead($leadID,$patent,array("file_url"=>$documentId));
			redirect('dashboard/index/'.$leadID);
		} else {
			redirect('dashboard');
		}
	}
	function generateRandomString($length = 5) {
		/*$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';*/
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function create_lead(){
		if(isset($_POST) && count($_POST)>0){
			$name = $this->input->post('n');
			$type = $this->input->post('t');
			if(!empty($name) && !empty($type)){
				/*Check Lead Name*/				
				$findLead = $this->lead_model->findLeadByName($name);
				if(count($findLead)==0){
					$serial_number = $this->getNewSerialNumber(0,$this->generateRandomString());
					$this->load->library("DriveServiceHelper");
					$service = new DriveServiceHelper();
					$leadFolderID = false;
					switch($type){
						case 'NON':
							$leadFolderID = $service->getFileIdByName('Non-Acquisitions');
						break;
						case 'INT':
							$leadFolderID = $service->getFileIdByName('Internal');
						break;
						default:
							$leadFolderID = $service->getFileIdByName('Leads');	
						break;
					}
					if($leadFolderID){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $leadFolderID );
						$getFolderInfo = $service->createSubFolder($name.'_folder',$fileParent);
						if($getFolderInfo){							
							$lead = array("lead_name"=>$name,"type"=>$type,'folder_id'=>$getFolderInfo,'serial_number'=>$serial_number,"user_id"=>$this->session->userdata['id'],"complete"=>0,"create_date"=>date('Y-m-d H:i:s'));		
							$lead_id = $this->lead_model->from_litigation_insert($lead);
							if($lead_id>0){
								/*image_folder*/
								$fileParent = new Google_Service_Drive_ParentReference();
								$fileParent->setId( $getFolderInfo );
								$getImageFolderInfo = $service->createSubFolder('image',$fileParent);
								if($getImageFolderInfo){
									$this->lead_model->from_litigation_update($lead_id,array("image_folder"=>$getImageFolderInfo));
								}
								$findUser = $this->user_model->findAllInsiderUser('1');
								if(count($findUser)>0){
									foreach($findUser as $user){
										$this->user_model->insert_assign_lead(array('lead_id'=>$lead_id,'pd_id'=>$user->id));
									}
								} else {
									$this->user_model->insert_assign_lead(array('lead_id'=>$lead_id,'pd_id'=>$this->session->userdata['id']));
								}								
								/*End*/
								if($type=="SEP"){
									$type="General";
								}
								$getButtonList = $this->general_model->getAllButtonList($type);
								if(count($getButtonList)>0){
									foreach($getButtonList as $button){
										$this->lead_model->insertLeadButton(array("lead_id"=>$lead_id,"sort"=>$button->sort,"button_id"=>$button->id));
									}
								}
								/*Move all skeleton to lead*/
								$allTemplates = $this->general_model->getAllTemplates();
								if(count($allTemplates)>0){
									foreach($allTemplates as $row){					
										$template = $row->template_html;					
										$urlName ="";
										if($row->main_type=="2"){	
											$acquisition = $this->acquisition_model->getData($lead_id);
											$category_list = $this->customer_model->categoryList(0);
											$lead_data = $this->lead_model->getLeadData($lead_id);
											if(!empty($acquisition['acquisition']->store_name)):				
											if($acquisition['acquisition']->category>0){
												if(count($category_list)>0){
													for($cc=0;$cc<count($category_list);$cc++){
														if($category_list[$cc]->id==$acquisition['acquisition']->category){
															$urlName = $category_list[$cc]->name;
															$urlName = str_replace('','_',$urlName);
															$urlName = str_replace('-','_',$urlName);
															$urlName = str_replace('&',' ',$urlName);
															$urlName = str_replace('&amp;',' ',$urlName);
															$urlName = preg_replace("/[^a-zA-Z0-9_\s-]/", "_", $urlName);
															$urlName = preg_replace('/-/','_',$urlName);
															$urlName = preg_replace('/[\s,\-!]/',' ',$urlName);
															$urlName = preg_replace('/\s+/','_',$urlName);
														}
													}
												}
												if(!empty($urlName)){
													$urlName ='/departments/'.$urlName.'-'.$acquisition['acquisition']->category.'/'.$lead_data->serial_number.'/';
												}
											}
											endif;
											if(!empty($urlName)){
												$urlName = "http://www.synpat.com".$urlName;
											}
											$template =  str_replace('link-data-href=""',"href='".$urlName."'",$template);
										}
										$data =  $this->general_model->moveAllToLeadBankTemplate(array('lead_id'=>$lead_id,'subject'=>$row->subject,'template_html'=>$template,'type'=>$row->type,'template_name'=>$row->template_name,'main_type'=>$row->main_type));
									}
									if($data>0){
										$user_history = array('lead_id'=>$this->input->post('lead_id'),'user_id'=>$this->session->userdata['id'],'message'=>"Move skeleton to lead templates.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
										$this->user_model->addUserHistory($user_history);
									}
								}
								
								/*End*/
								$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$lead_id,'message'=>'Created lead from '.$type,'create_date'=>date('Y-m-d H:i:s')));
								echo $lead_id;
							} else {
								echo "0";
							}
						} else {
							echo "0";
						}
					} else {
						echo "0";
					}					
				} else {
					echo "-1";
				}
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
		die;
	}
	
	public function delete_lead(){
		$data = 0;
		if(isset($_POST) && $this->input->post('b')>0){
			$checkLeadData = $this->lead_model->getLeadData($this->input->post('b'));
			$status = '3';
			if(count($checkLeadData)>0){
				if($checkLeadData->status=='3'){
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
					$deleteFiles = $service->deleteFilesFolder($checkLeadData->folder_id);
					if($deleteFiles){
						$data = $this->lead_model->deleteLead($this->input->post('b'));
						$data = 1;
						$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('b'),'message'=>'Delete lead - '.$checkLeadData->lead_name,'create_date'=>date('Y-m-d H:i:s')));
					}
				} else {
					$this->lead_model->from_litigation_update($this->input->post('b'),array("status"=>$status));
					$data =  1;
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('b'),'message'=>'Change Status of lead.','create_date'=>date('Y-m-d H:i:s')));
				}
			}
		}		
		echo $data;
	}
	
	
	public function findLeadPrePatent($lead=0){
		$data['prePatents'] = array();
		$data['lead'] = 0;
		if(isset($lead) && count($lead)>0){
			if((int)$lead>0){
				$getLeadData =  $this->lead_model->getLeadData($lead);
				if(count($getLeadData)>0){
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://synpat.com/getPrePatents.php',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'serial_number' => $getLeadData->serial_number,
					)				
					));
					$resp = curl_exec($curl);
					if($resp){
						$data['prePatents'] = json_encode($resp);
					}
					$data['lead'] = $lead;
				}
			}			
		}
		$this->layout->layout='opportunity';
		$this->layout->title_for_layout = 'Backyard Pre Lead Patents';
		$this->layout->render('user/lead_pre_patent',$data);
	}
	
	function potentialParticipant(){
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/potential_particpant',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => $postData['l'],
					'file' => $postData['file']
				)				
			));
			$resp = curl_exec($curl);
			if($resp){
				echo $resp;
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
		die;
	}
	
	
	function fileUploadFromFTPAndDrive($file,$filename,$l,$request){
		$getUploadFileData = (object) array();
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://appadmin.synpat.com/Users/uploadFileToServer',
			CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'source' => $file,
				'destination'=>$filename,
			)				
		));							
	$resp = curl_exec($curl);
	if(!empty($resp)){
		if($resp=="Done"){
			$getLeadData = array();
			if($l>0){
				$getLeadData = $this->lead_model->getLeadData($l);
			} else {
				if($request->License->serial_number>0){
					$getLeadData = $this->lead_model->findSerialNumber($request->License->serial_number);
				}										
			}									
			if(count($getLeadData)>0){
				if(!empty($getLeadData->folder_id)){
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
					$parent = new Google_Service_Drive_ParentReference();
					$parent->setId($getLeadData->folder_id);
					$fileName = $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename;
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mimeType =  finfo_file($finfo, $fileName);
					if($mimeType=="application/zip"  && strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'docx'){
						$mimeType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					}
					finfo_close($finfo);
					$convert=true;
					if($mimeType=="application/pdf" || ($mimeType == 'inode/x-empty' && strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'pdf')) {    
						$convert=false;
						$mimeType="application/pdf";
					}					
					$getUploadFileData = $service->insertFile($filename,$mimeType,$fileName,$parent,$convert);
					if($getUploadFileData){												
						unlink($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
					}
				}
			}
		}
	}
	return $getUploadFileData;
	}
	
	function send_revised(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/fineRevisedDocData',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => $postData['i']
				)				
			));
			$resp = curl_exec($curl);			
			if($resp){
				$request = json_decode($resp);
				if(count($request)>0){
					$license_aggreement = $request->Revised_documents->license_aggreement;
					$strategic_aggreement = $request->Revised_documents->strategic_aggreement;
					$request_aggreement = $request->Revised_documents->request_aggreement;
					$program_aggreement = $request->Revised_documents->program_aggreement;
					$licenseUpload = array('file_url'=>'','id'=>'','name'=>'');
					$strategicUpload = array('file_url'=>'','id'=>'','name'=>'');
					$requestUpload = array('file_url'=>'','id'=>'','name'=>'');
					$programUpload = array('file_url'=>'','id'=>'','name'=>'');
					if(!empty($license_aggreement)){
						$file = $license_aggreement;
						$fileInfo = pathinfo($file);
						$filename = $fileInfo['basename'];
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename)){
							$dataFileUpload = $this->fileUploadFromFTPAndDrive($file,$filename,$postData['l'],$request);
							if(is_object($dataFileUpload) && isset($dataFileUpload->id) && !empty($dataFileUpload->id)){
								$licenseUpload = array('file_url'=>$dataFileUpload->alternateLink,'id'=>$dataFileUpload->id,'name'=>$dataFileUpload->title);	
							}
						}
					}
					if(!empty($strategic_aggreement)){
						$file = $strategic_aggreement;
						$fileInfo = pathinfo($file);
						$filename = $fileInfo['basename'];
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename)){
							$dataFileUpload = $this->fileUploadFromFTPAndDrive($file,$filename,$postData['l'],$request);
							if(is_object($dataFileUpload) && isset($dataFileUpload->id) && !empty($dataFileUpload->id)){
								$strategicUpload = array('file_url'=>$dataFileUpload->alternateLink,'id'=>$dataFileUpload->id,'name'=>$dataFileUpload->title);	
							}
						}
					}
					if(!empty($request_aggreement)){
						$file = $request_aggreement;
						$fileInfo = pathinfo($file);
						$filename = $fileInfo['basename'];
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename)){
							$dataFileUpload = $this->fileUploadFromFTPAndDrive($file,$filename,$postData['l'],$request);
							if(is_object($dataFileUpload) && isset($dataFileUpload->id) && !empty($dataFileUpload->id)){
								$requestUpload = array('file_url'=>$dataFileUpload->alternateLink,'id'=>$dataFileUpload->id,'name'=>$dataFileUpload->title);	
							}
						}
					}
					if(!empty($program_aggreement)){
						$file = $program_aggreement;
						$fileInfo = pathinfo($file);
						$filename = $fileInfo['basename'];
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename)){
							$dataFileUpload = $this->fileUploadFromFTPAndDrive($file,$filename,$postData['l'],$request);
							if(is_object($dataFileUpload) && isset($dataFileUpload->id) && !empty($dataFileUpload->id)){
								$programUpload = array('file_url'=>$dataFileUpload->alternateLink,'id'=>$dataFileUpload->id,'name'=>$dataFileUpload->title);	
							}
						}
					}
										
					$getContact = $this->client_model->getInfo($postData['p']);
					$getLeadInfo = $this->lead_model->getLeadData($postData['l']);
					if(count($getContact)>0){
						$note = $request->Revised_documents->message;
						if(!empty($licenseUpload['file_url'])){
							$note = $note.'<br/><a  href="javascript://" data-file-id="'.$licenseUpload['id'].'" onclick=\'open_drive_files("'.trim($licenseUpload['file_url']).'");\' data-href="'.$licenseUpload['file_url'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$licenseUpload['name'].'</a>';		
						}
						if(!empty($strategicUpload['file_url'])){
							$note = $note.'<br/><a  href="javascript://" data-file-id="'.$strategicUpload['id'].'" onclick=\'open_drive_files("'.trim($strategicUpload['file_url']).'");\' data-href="'.$strategicUpload['file_url'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$strategicUpload['name'].'</a>';		
						}
						if(!empty($requestUpload['file_url'])){
							$note = $note.'<br/><a  href="javascript://" data-file-id="'.$requestUpload['id'].'" onclick=\'open_drive_files("'.trim($requestUpload['file_url']).'");\' data-href="'.$requestUpload['file_url'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$requestUpload['name'].'</a>';		
						}
						if(!empty($programUpload['file_url'])){
							$note = $note.'<br/><a  href="javascript://" data-file-id="'.$programUpload['id'].'" onclick=\'open_drive_files("'.trim($programUpload['file_url']).'");\' data-href="'.$programUpload['file_url'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$programUpload['name'].'</a>';		
						}							
						$saveData = array('lead_id'=>$postData['l'],'company_id'=>$getContact->company_id,'contact_id'=>$getContact->id,'type'=>6,'note'=>$note,'user_id'=>$this->session->userdata['id'],'subject'=>$getLeadInfo->lead_name,'activity_date'=>date('Y-m-d H:i:s'));
						$dataSales = $this->lead_model->insetSalesActivity($saveData);
						if($dataSales>0){
							$data = $dataSales;
							$user_history = array('lead_id'=>$postData['l'],'user_id'=>$this->session->userdata['id'],'message'=>'Add Email in sales activity','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
							$curl = curl_init();
							// Set some options - we are passing in a useragent too here
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://appadmin.synpat.com/Users/removeRevisedDocument',
								CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									'i' => $postData['i']
								)				
							));
							$resp = curl_exec($curl);							
						}						
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function send_c(){
		$data = array("error"=>1);
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/findRequestData',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => $postData['i']
				)				
			));
			$resp = curl_exec($curl);
			
			if($resp){
				$request = json_decode($resp);
				if(count($request)>0){
					if(!empty($request->Request_to_participates->file)){
						$file = $request->Request_to_participates->file;
						$fileInfo = pathinfo($file);
						/*$filename = $fileInfo['filename'].".".$fileInfo['extension'];	*/	
						$filename = $fileInfo['basename'];		
						if((int)$request->Request_to_participates->type==0){
							$filename = "RTP - ".$filename;
						} else if((int)$request->Request_to_participates->type==1){
							$filename = "RFL - ".$filename;
						}
						
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename)){
							
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://appadmin.synpat.com/Users/uploadFileToServer',
								CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									'source' => $file,
									'destination'=>$filename,
								)				
							));
							
							$resp = curl_exec($curl);
							
							if(!empty($resp)){								
								if($resp=="Done"){									
									/* 
										*File Send To Drive
										*Get Email Template
										*Contact Address
										*Insert Data to Tables
									*/	
									$getLeadData = array();
									if($postData['l']>0){
										$getLeadData = $this->lead_model->getLeadData($postData['l']);
									} else {
										if($request->License->serial_number>0){
											$getLeadData = $this->lead_model->findSerialNumber($request->License->serial_number);
										}										
									}
									
									if(count($getLeadData)>0){
										if(!empty($getLeadData->folder_id)){
											$this->load->library('DriveServiceHelper');
											$service = new DriveServiceHelper();
											$parent = new Google_Service_Drive_ParentReference();
											$parent->setId($getLeadData->folder_id);
											$fileName = $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename;
											$finfo = finfo_open(FILEINFO_MIME_TYPE);
											$mimeType =  finfo_file($finfo, $fileName);
											finfo_close($finfo);
											$convert=true;
											if($mimeType=="application/pdf" || ($mimeType == 'inode/x-empty' && strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'pdf')) {    
												$convert=false;
												$mimeType="application/pdf";
											}
											
											$getUploadFileData = $service->insertFile($filename,$mimeType,$fileName,$parent,$convert);
											
											if($getUploadFileData){
												$templateData = $this->general_model->getTemplateBYType(1);
												unlink($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
												$data['error']= "0";
												$data['file']= $getUploadFileData;
												$data['template']= $templateData->template_html;
												$data['subject']= $templateData->subject;
												$data['lead_id'] = $getLeadData->id;
												$service->setAdditionalPermissions( $getUploadFileData->id, $this->session->userdata['email'],"reader","anyone",array('emailMessage'=>'File has been share with you .','sendNotificationEmails'=>true));					
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
		echo json_encode($data);
		die;
	}
	
	function updatePricePotential(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			if((int)$postData['i']>0 && (float)$postData['p']>0){
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/updatePricePotential',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'i' => $postData['i'],
						'p'=>$postData['p'],
					)				
				));
				$resp = curl_exec($curl);
				if($resp){
					$data = $resp;
				}
			}
		}
		echo $data;
		die;
	}
	
	public function licensees($leadID=0){
		$data = array('licensees'=>array());
		if((int)$leadID==0){
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/findRequestToParticipant',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio'				
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			if($resp){
				$data['licensees'] = json_decode($resp);
			}
		} else {
			$getStoreName = $this->acquisition_model->getData($leadID);
			if(count($getStoreName)>0){
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/findRequestToParticipant/'.$getStoreName['acquisition']->store_name,
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio'				
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp){
					$data['licensees'] = json_decode($resp);
				}
			}
		}
		$this->layout->layout='opportunity';	
		$this->layout->title_for_layout = 'Contact';
		$this->layout->render('user/licensees',$data);
	}
	
	public function litigation(){
		$lead_id = 0;
		if(isset($_POST) && count($_POST)>0){
			$litigationData = $this->input->post();			
            $litigationData['litigation']['user_id']= $this->session->userdata['id'];
			if(!isset($litigationData['complete'])){
				$litigationData['complete'] = 0;
			}
			if(isset($litigationData['litigation']['worksheet_id']) && $litigationData['litigation']['worksheet_id']=='-- Select Worksheet --'){
				$litigationData['litigation']['worksheet_id'] = '';
			}
			$user_id  = $this->session->userdata['id'];
			if((int)$litigationData['litigation']['id']==0){
				$litigationData['litigation']['create_date']= date('Y-m-d H:i:s');
				$litigationData['litigation']['type']= 'Litigation';		
				$saveLitigation = $this->lead_model->from_litigation_insert($litigationData['litigation']);
				$lead_id = $saveLitigation;				
				if($lead_id>0){
					/*Create Folder in Drive*/
					$this->load->library("DriveServiceHelper");
					$service = new DriveServiceHelper();
					$leadFolderID = $service->getFileIdByName('Leads');
					if($leadFolderID){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $leadFolderID );
						$getFolderInfo = $service->createSubFolder($litigationData['litigation']['lead_name'].'_folder',$fileParent);
						if($getFolderInfo){
							/*Save Folder ID  in DB*/
							$this->lead_model->from_litigation_update($lead_id,array('folder_id'=>$getFolderInfo));
						}
					}						
					/**/
					$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Created lead From Litigation.','create_date'=>$litigationData['litigation']['create_date']));
					$this->session->set_flashdata('message','Record added.');
				}			
			}else {
				/*Check Lead Name*/
				$findLeadByName = $this->lead_model->findLeadByName($litigationData['litigation']['lead_name']);
				if(count($findLeadByName)>0){
					if($findLeadByName->id ==$litigationData['litigation']['id']){
						if((int)$findLeadByName->serial_number==0){
							$litigationData['litigation']['serial_number'] = $this->getNewSerialNumber(0,$this->generateRandomString());
						}
						$saveLitigation = $this->lead_model->from_litigation_update($litigationData['litigation']['id'],$litigationData['litigation']);
						$lead_id = $saveLitigation;
						$this->session->set_flashdata('message','Record updated.');
						$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Update lead From Litigation.','create_date'=>date('Y-m-d H:i:s')));
					} else {
						$this->session->set_flashdata('message','Lead name already exist');
						echo "Lead name already exist.";
					}
				} else {
					$saveLitigation = $this->lead_model->from_litigation_update($litigationData['litigation']['id'],$litigationData['litigation']);
					$lead_id = $saveLitigation;
					$this->session->set_flashdata('message','Record updated.');
					$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Update lead From Litigation.','create_date'=>date('Y-m-d H:i:s')));
				}
			}			
		}
		die;
	}
	
	Public function getNewSerialNumber($flag=0,$serialNumber){
		if($flag==1){
			$serialNumber = $this->generateRandomString();
		}		
		$checkSerialNumber = $this->lead_model->findSerialNumber($serialNumber);
		if(count($checkSerialNumber)==0){
			return $serialNumber;
		} else {
			return $this->getNewSerialNumber(1,$serialNumber);
		}
	}
	
	
	function market(){
		$lead_id = 0;
		if(isset($_POST) && count($_POST)>0){			
			$marketData = $this->input->post();			
			/*Create Portfolio Number*/
			$checkData = $this->lead_model->checkDataFromSameOwnerToday($marketData['market']['plantiffs_name'],date('Y-m-d'),'Market');
			$portfolioIncrementNumber = (int)$checkData->portfolio + 1;
			$portfolioNumber = 'PN'.date('mdy').'-'.$portfolioIncrementNumber;
			/*End Creating*/			
			$marketData['market']['user_id']= $this->session->userdata['id'];
			$marketData['market']['create_date']= date('Y-m-d H:i:s');
			$marketData['market']['type']= 'Market';
			$marketData['market']['portfolio_number']= $portfolioNumber;
			$gmailMessageID = $marketData['market']['gmail_message_id'];
			unset($marketData['market']['gmail_message_id']);
			if(!isset($marketData['market']['complete'])){
				$marketData['market']['complete'] = 0;
			}
			if(isset($marketData['market']['worksheet_id'])&& $marketData['market']['worksheet_id']=='-- Select Worksheet --'){
				$marketData['market']['worksheet_id'] = '';
			} 
			
			if((int)$marketData['market']['id']==0){
			    $this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$saveMarket = $this->lead_model->from_litigation_insert($marketData['market']);
				$lead_id = $saveMarket;
				$leadFolderID = $service->getFileIdByName('Leads');
				if($leadFolderID){
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $leadFolderID );
					$getFolderInfo = $service->createSubFolder($marketData['market']['lead_name'].'_folder',$fileParent);
					if($getFolderInfo){
						$this->lead_model->from_litigation_update($saveMarket,array('folder_id'=>$getFolderInfo));
					}
				}
				if(isset($_SESSION['another_access_token']) && !empty($_SESSION['another_access_token'])){
					if(!empty($gmailMessageID)){
						if(!isset($_SESSION)){
							session_start();
						}
						$service = new GmailServiceHelper();
						$service->setAccessToken($_SESSION['access_token']);
						$getMessageData = $service->findThreadData($gmailMessageID);
						$filesAttachment = "";						
						if(count($getMessageData)>0){
							foreach($getMessageData as $message){
								foreach($message['attachments'] as $attachments){
									$attachmentID = $attachments['attachmentId'];
									$filename =  $attachments['filename'];
									$attachmentsData = $service->downloadAttachments($gmailMessageID,$attachmentID);
									if(is_object($attachmentsData)){
										$rawData = $attachmentsData->data;
										$sanitizedData = strtr($rawData,'-_', '+/');
										$data = strtr($rawData, array('-' => '+', '_' => '/'));
										$body = base64_decode($sanitizedData);
										$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
										fwrite($fh, $body);
										fclose($fh);	
										$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
									}
								}
							}
						}
						$labels = $service->listLabels();
						$labelID = "";
						foreach($labels as $key=>$label){
							if(strtoupper($label)=="LEAD"){
								$labelID = $key;
							}
						}
						if(empty($labelID)){
							/*Create label*/
							$label = $service->createLabel("LEAD");
							if(is_object($label) && $label->getId()!=""){
								$labelID = $label->getId();
							}
							/*End Create Label*/
						}
						if(!empty($labelID)){
							$service->modifyThreadRemove($gmailMessageID,"me",'INBOX');
							$service->modifyThread($gmailMessageID,"me",$labelID);
						}
						$filesAttachment = substr($filesAttachment,0,-1);
						$this->lead_model->insertBox(array("lead_id"=>$saveMarket,"thread_id"=>$gmailMessageID,"content"=>json_encode($getMessageData),"file_attach"=>$filesAttachment));
					}
				}
				$lead_id = $saveMarket;
				$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Create a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			} else {
				$id = $marketData['market']['id'];
				$lead_id = $id;
				unset($marketData['market']['id']);
				unset($marketData['market']['gmail_message_id']); 
				/*Check Lead Name*/
				$findLeadByName = $this->lead_model->findLeadByName($marketData['market']['lead_name']);
				if(count($findLeadByName)>0){
					if($findLeadByName->id == $id){
						if((int)$findLeadByName->serial_number==0){
							$marketData['market']['serial_number'] = $this->getNewSerialNumber(0,$this->generateRandomString());
						}
						$saveMarket = $this->lead_model->from_litigation_update($id,$marketData['market']);
						$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						$this->session->set_flashdata('message','Record updated.');
					} else {
						$this->session->set_flashdata('error','Lead name already exist.');
						echo "Lead name already exist;";
					}
				}  else {
					$saveMarket = $this->lead_model->from_litigation_update($id,$marketData['market']);
					$findLeadByName = $this->lead_model->getLeadData($id);
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
					$service->renameFile($findLeadByName->folder_id,$marketData['market']['lead_name'].'_folder');
					$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					$this->session->set_flashdata('message','Record updated.');
				}
			}
			if(isset($lead_id) && $lead_id>0){
				if(isset($marketData['market']['expected_price']) && !empty($marketData['market']['expected_price'])){
					$getAcqusitionData = $this->acquisition_model->getData($lead_id);
					if(count($getAcqusitionData)>0){
						$updateData = $this->acquisition_model->updateData($lead_id,array('seller_asking_price'=>$marketData['market']['expected_price'],'option_expiration_data'=>$marketData['market']['option_expiration_date']));
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_other_update_data',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'license_number' => $getAcqusitionData['acquisition']->store_name,
								'asking_price'=>$marketData['market']['expected_price'],
								'option_expiration_date'=>$marketData['market']['option_expiration_date']
							)				
						));
						$resp = curl_exec($curl);
					}
				}
			}
		} else {
			$this->session->set_flashdata('error','Please try after sometime.');
		}		
	}
	
	
	
	function lead_form(){
		if(isset($_POST) && count($_POST)>0){
			$formData = $this->input->post();			
			/*Create Portfolio Number*/
			$checkData = $this->lead_model->checkDataFromSameOwnerToday($formData['acquisition']['plantiffs_name'],date('Y-m-d'),$formData['acquisition']['type']);
			$portfolioIncrementNumber = (int)$checkData->portfolio + 1;
			$portfolioNumber = 'PN'.date('mdy').'-'.$portfolioIncrementNumber;
			/*End Creating*/
			
			$id = $formData['acquisition']['id'];
			$formData['acquisition']['update_date']= date('Y-m-d H:i:s');
			$lead_id = $id;
			unset($formData['acquisition']['id']);
			unset($formData['acquisition']['gmail_message_id']); 
			if(!isset($formData['acquisition']['complete'])){
				$formData['acquisition']['complete'] = 0;
			}
			if(isset($formData['acquisition']['worksheet_id'])&& $formData['acquisition']['worksheet_id']=='-- Select Worksheet --'){
				$formData['acquisition']['worksheet_id'] = '';
			}
			/*Check Lead Name*/
			$findLeadByName = $this->lead_model->findLeadByName($formData['acquisition']['lead_name']);
			if(count($findLeadByName)>0){
				if($findLeadByName->id == $id){
					if((int)$findLeadByName->serial_number==0){
						$formData['acquisition']['serial_number'] = $this->getNewSerialNumber(0,$this->generateRandomString());
					}
					$saveMarket = $this->lead_model->from_litigation_update($id,$formData['acquisition']);
					$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from '.$formData['acquisition']['type'].'.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					$this->session->set_flashdata('message','Record updated.');
				} else {
					$this->session->set_flashdata('error','Lead name already exist.');
					echo "Lead name already exist;";
				}
			}  else {
				$saveMarket = $this->lead_model->from_litigation_update($id,$formData['acquisition']);
				$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from '.$formData['acquisition']['type'].'.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				$this->session->set_flashdata('message','Record updated.');
			}
		} else {
			$this->session->set_flashdata('error','Please try after sometime.');
		}	
	}
	
	public function lititgation_scrap_data(){
		$data =0;
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['lead']) && (int) $_POST['lead']>0){
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead'],array('scrapper_data'=>$_POST['scrap']));
				$user_history = array('lead_id'=>$_POST['lead'],'user_id'=>$this->session->userdata['id'],'message'=>'Update Litigation data scrap','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
		}
		echo $data;
		die;
	}
	
	public function taskList(){
		$this->load->library('DriveServiceHelper');
		$service = new TaskServiceHelper();
		$getList = $service->taskList();
		foreach ($getList['items'] as $list) {
			print "<h3>ID: ".$list['title']." TITLE: ".$list['title']."</h3>";
			$tasks = $service->findTask($list['id']);
			foreach ($tasks['items'] as $task) {
				print "<p id='post'>".$task['title']."</p>";
			}
		}
		die;
	}
	
	public function modifyLabel(){
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			if(!isset($_SESSION)){
				session_start();
			}
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['another_access_token']);
			$modifyData = $service->modifyThread($this->input->post('token'),"me",strtoupper(strtolower($this->input->post('label'))));
			if(is_object($modifyData)){
				$type = strtoupper(strtolower($this->input->post("active")));
				if(isset($_SESSION[$type])){
					$emails = $_SESSION[$type];
					if(count($emails)>0){
						for($i=0;$i<count($emails);$i++){
							if(isset($emails[$i]['message_id']) && $emails[$i]['message_id']==$this->input->post('token')){
								unset($emails[$i]);
							}
						}
						$_SESSION[$type] = $emails;
					}
				}
				echo "Starred";
			} else {
				echo "Not Starred";
			}		
		}
		die;
	}
	
	function moveThreadDiff(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			if(!isset($_SESSION)){
				session_start();
			}
			$service = new GmailServiceHelper();			
			$service->setAccessToken($_SESSION['another_access_token']);
			$service->modifyMessageRemove($this->input->post('thread'),"me","INBOX");
			$service->modifyMessageRemove($this->input->post('thread'),"me","STARRED");
			$labelID = $this->input->post('label');
			if(strtolower($labelID)=="nonlead"){
				$labelID ="";
				$service->setAccessToken($_SESSION['another_access_token']);
				$listOfLabels = $service->listLabels();			
				if(count($listOfLabels)>0){
					foreach($listOfLabels as $key=> $label){
						if(strtolower($label)=='nonlead'){
							$labelID = $key;
						}
					}
				}
				if(empty($labelID)){
					$label = $service->createLabel('Nonlead');
					if($label){
						$labelID = $label->getId();							
					}
				}
			}		
			if(!empty($labelID)){
				$modifyData = $service->modifyMessage($this->input->post('thread'),"me",$labelID);
				if(is_object($modifyData)){
					/*Remove from Session*/
					$type = strtoupper(strtolower($this->input->post("active")));
					if(isset($_SESSION[$type])){
						$emails = $_SESSION[$type];
						if(count($emails)>0){
							for($i=0;$i<count($emails);$i++){
								if(isset($emails[$i]['message_id']) && $emails[$i]['message_id']==$this->input->post('thread')){
									unset($emails[$i]);
								}
							}
							$_SESSION[$type] = $emails;
						}
					}					
					/*End Session Remove*/
					$data = 1;
				}
			}
		}
		echo $data;
		die;
	}
	
	public function saveComment(){
		if(isset($_POST) && count($_POST)>0){
			$commentData = $this->input->post();
            $comment['other']['comment1'] = $commentData['comment1'];
            $comment['other']['comment2'] = $commentData['comment2'];
            $comment['other']['comment3'] = $commentData['comment3'];          
            $comment['other']['parent_id'] = $commentData['lead_id']; 
            $comment['other']['type'] = $commentData['type'];           
            $comment['other']['attractive'] = $commentData['attractiveness'];          
			$comment['other']['user_id']= $this->session->userdata['id'];				
			$comment['other']['id']= $commentData['id'];				
			if($commentData['id']==0){
				$comment['other']['created']= date('Y-m-d H:i:s');	
                $comment['other']['updated']= date('Y-m-d H:i:s');	
				$checkComment = $this->lead_model->userLeadTeamNote($commentData['lead_id'],$this->session->userdata['id']);
				if(count($checkComment)>0){
					$comment['other']['updated']= date('Y-m-d H:i:s');
					$comment['other']['id']= $checkComment->id;
					$saveComment = $this->lead_model->from_litigation_update_comment($comment['other']);
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['lead_id'],'message'=>'Update comment','create_date'=>date('Y-m-d H:i:s')));
				} else {
					$saveComment = $this->lead_model->from_litigation_comment($comment['other']);
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['lead_id'],'message'=>'Add a comment','create_date'=>date('Y-m-d H:i:s')));
				}
				
			} else {
				$comment['other']['updated']= date('Y-m-d H:i:s');
				$saveComment = $this->lead_model->from_litigation_update_comment($comment['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['lead_id'],'message'=>'Update comment','create_date'=>date('Y-m-d H:i:s')));
			}
            
			if($saveComment>0){
				echo json_encode(array('message'=>'Records added','id'=>$saveComment,'error'=>''));
                
			} else {
				echo json_encode(array('error'=>'Try after sometime.'));
			}
		}
		die;
	}
	
	function resendImapEmail(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$id = $this->input->post('which_n');
			$findEmail = array();
			if((int)$id>0){
				$findEmail  = $this->lead_model->findBoxNewById($id);
			}
			if(count($findEmail)>0 && isset($findEmail[0]->lead_id) && (int)$findEmail[0]->lead_id>0){
				$message = $findEmail[0]->content;
				$threadMessage = json_decode($message);
				if($threadMessage->body!=""){
					$body = $threadMessage->body;
					$headerTo = $threadMessage->header->to;
					$too = array();
					foreach($headerTo as $hTo){
						$too[] = $hTo->personal;
					}
					$to = implode(',',$too);
					$ccc = "";
					if(isset($threadMessage->cc)){
						$ccc = implode(',',$threadMessage->cc);
					}
					$subject = $threadMessage->subject;
					$this->config->load('config');					
					require_once(APPPATH.'libraries/PHPMailer-master/PHPMailer-master/PHPMailerAutoload.php');
					$mail = new PHPMailer;
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = $this->config->item('license_email');                 // SMTP username
					$mail->Password = $this->config->item('license_password');                           // SMTP password
					$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 465;                                    // TCP port to connect to
					$mail->From = $this->config->item('license_email');
					$mail->FromName = "Licenses SynPat";
					$mail->isHTML(true);                                
					// Set email format to HTML
					$mail->Subject = $subject;
					$strToMail = explode(',',$to);
					if(isset($strToMail[0]) && !empty($strToMail[0])){
						foreach($strToMail as $ema){
							$to= trim($ema);
							if(!empty($to)){
								$mail->AddAddress(trim($ema),trim($ema));
							}				
						}
					}
					if(isset($ccc)){
						$cc = explode(",",$ccc);
						if(isset($cc[0]) && !empty($cc[0])){
							foreach($cc as $ema){
								$emailCC= trim($ema);
								if(!empty($emailCC)){					
									$mail->addCC($emailCC,$emailCC);
								}
							}
						}					
					}
					$strMailContent = $body;
					/*$mail->SMTPDebug = 2;  */
					$mail->Body = $strMailContent;
					if($mail->send()) {	
						$date = date('Y-m-d H:i:s');
						$leadID = $this->input->post('lead');
						$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Email send",'opportunity_id'=>0,'create_date'=>$date);
						array_push($strToMail,'');
						$send = $this->findEmailImapSendBox($subject,$strToMail,$date,$leadID,1,1);
					
						$email_id = 0 ;
						$dataSend = json_decode($send,true);
						if(isset($dataSend['send'])){
							$email_id = $dataSend['send'];
						} else if(isset($dataSend->send)){
							$email_id = $dataSend->send;
						}
						if($email_id>0){	
							$data= $email_id;
							$message = $strMailContent;
							$emailTo = $strToMail;
							if(count($emailTo)>0){						
								for($t=0;$t<count($emailTo);$t++){
									$toEmail =	trim($emailTo[$t]);
									if(!empty($toEmail)){
										$getContactDetail = $this->lead_model->getContactByEmail($toEmail);
										if(count($getContactDetail)>0){
											$event['contact_id'] = $getContactDetail->id;
											$event['company_id'] = $getContactDetail->company_id;
											$event['type'] = 3;
											$event['note'] = $message;
											$event['user_id'] = $this->session->userdata['id'];
											$event['email_id'] = $email_id;
											$event['subject'] = $subject;
											$event['lead_id'] = $leadID;
											$event['activity_date'] = $date;
											$this->lead_model->insetSalesActivity($event);
										}
									}									
								}
							}
						}
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	
	function reply_email(){
		if(isset($_POST) && count($_POST)>0){			
			if(!isset($_SESSION)){
				session_start();
			}
			$popEmail=0;
			$this->load->library('DriveServiceHelper');
			$email = $this->input->post('email');
			$fileName="";
			$fileSrc = "";
			$type=0;
			$arrayPID = array();
			$activityType=0;
			$event = array();
			$preSaleBrokerCompanyID = 0;
			if(isset($_POST['event'])){
				$event = $this->input->post('event');
			}	
			if(isset($event['c_id']) && (int)$event['c_id']>0){
				$preSaleBrokerCompanyID = $event['c_id'];
			}
			if(count($event)>0 && isset($event['p_id']) && !empty($event['p_id']) && $event['p_id']!="," && $event['p_id']!=", "){
				$type=1;
			}
			if(isset($event['t']) && (int)$event['t']){
				$activityType=$event['t'];
				unset($event['t']);
			}
			$templateSave = 0;
			if(isset($email['save_template']) && $email['save_template']==1){
				$templateSave = $email['save_template'];
				unset($email['save_template']);
			}
			$leadID = $email['lead_id'];
			unset($email['lead_id']);
			if(isset($email['doc_url']) && !empty($email['doc_url'])){
				$this->load->library('simple_html_dom');
				$html = $this->simple_html_dom->load($email['doc_url'],true,true);
				$allAnchor = array();
				foreach($html->find('<ul class="attachment-list">') as $asTable) {
					foreach($asTable->find('<li class="attachment-list-item">') as $title){
						$titles = "";
						$img = "";
						$href = "";
						foreach($title->find("img") as $img){
							if(!empty($img->src)){
								$img = $img->src;
							}
						}
						foreach($title->find("a") as $anchor){
							if(!empty($anchor->innertext) && empty($titles)){
								$titles = $anchor->innertext;
							}
							if(!empty($anchor->plaintext) && empty($titles)){
								$titles = $anchor->plaintext;
							}
							if(!empty($anchor->{'data-href'})){
								$href = $anchor->{'data-href'};
							}
						}
						$allAnchor[] = array('title'=>$titles,'img'=>$img,'href'=>$href);
					}									
				}
				if(count($allAnchor)>0){
					$email['href'] = $allAnchor;
				} else {
					$email['href'] = array();
				}
			} else {
				$email['href'] = array();
			}
			$accountType = "1";
			if(isset($email['account_type'])){
				$accountType = $email['account_type'];
				unset($email['account_type']);
			}
			if((int)$accountType==2){
				require_once(APPPATH.'libraries/PHPMailer-master/PHPMailer-master/PHPMailerAutoload.php');
				$mail = new PHPMailer;
				if(isset($email['message_id'])){
					$mail->InReplyTo = $email['message_id'];
				}
				if(isset($email['reference'])){
					$mail->References = $email['reference'];
				}
				
				$this->config->load('config');
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = $this->config->item('license_email');                 // SMTP username
				$mail->Password = $this->config->item('license_password');                           // SMTP password
				$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465;                                    // TCP port to connect to
				$mail->From = $this->config->item('license_email');
				$mail->FromName = "SynPat Licenses";
				$mail->isHTML(true);                                
				// Set email format to HTML
				$mail->Subject = $email['subject'];
				$strToMail = explode(',',$email['to']);
				$cc = array();
				if(isset($strToMail[0]) && !empty($strToMail[0])){
					foreach($strToMail as $ema){
						$to= trim($ema);
						if(!empty($to)){					
							$mail->addAddress($to,$to);
						}				
					}
				}
				
				if(isset($email['cc'])){
					$cc = explode(",",$email['cc']);
					if(isset($cc[0]) && !empty($cc[0])){
						foreach($cc as $ema){
							$emailCC= trim($ema);
							if(!empty($emailCC)){					
								$mail->addCC($emailCC,$emailCC);
							}
						}
					}					
				}
				if(isset($email['bcc'])){
					$bcc = explode(",",$email['bcc']);
					if(isset($bcc[0]) && !empty($bcc[0])){
						foreach($bcc as $ema){
							$emailBcc= trim($ema);
							if(!empty($emailBcc)){					
								$mail->addBCC($emailBcc,$emailBcc);
							}
						}
					}					
				}
				$strMailContent = $email['message'];
				if(isset($email['href']) && count($email['href'])>0){
					$i=0;
					foreach($email['href'] as $drive){
						$strMailContent .=" <br/><div class='gmail_chipgmail_drive_chip'style='width:396px;height:18px;max-height:18px;background-color:#f5f5f5;padding:5px;color:#222;font-family:arial;font-style:normal;font-weight:bold;font-size:13px;border:1px solid #ddd;line-height:1'><a href='".$drive['href']."' target='_blank' style='display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-decoration:none;padding:1px 0px;border:none;width:100%'><img style='vertical-align: bottom; border: none;' src='".$drive['img']."'>&nbsp;<span dir='ltr' style='color:#15c;text-decoration:none;vertical-align:bottom'>".$drive['title']."</div></span></div> ";
						if($i==count($email['href'])-1){
							$strMailContent .="<br/>";
						}
						$i++;
					}	
				}
				/*$strMailContent .=$this->session->userdata['signature'];*/
				$mail->Body = $strMailContent;
				if($mail->send()) {	
					$date = date('Y-m-d H:i:s');
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Email send",'opportunity_id'=>0,'create_date'=>$date);
					$this->user_model->addUserHistory($user_history);
					/*Link to Email Box*/					
					$send = $this->findEmailImapSendBox($email['subject'],$strToMail,$date,$leadID,1,1);
					
					$email_id = 0 ;
					$dataSend = json_decode($send,true);
					if(isset($dataSend['send'])){
						$email_id = $dataSend['send'];
					} else if(isset($dataSend->send)){
						$email_id = $dataSend->send;
					}
					unset($event['c_id']);
					unset($event['p_id']);
					
					if($email_id>0){	
						$popEmail= $email_id;
						$message = $email['message'];	
						if(count($email['href'])>0){
							$message .= "<br/>";
							foreach($email['href'] as $url){
								$href = $url['href'];
								if(stristr($url['title'],"pdf")!==false){
									$href = str_replace("view?usp=drivesdk","preview",$href);
								}
								$message .= '<a  href="javascript://" data-file-id="" onclick=\'open_drive_files("'.trim($href).'");\' data-href="'.$url['href'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$url['title'].'</a> &nbsp; &nbsp; &nbsp;';
							}
						}
						$emailTo = explode(',',$email['to']);
						if(count($emailTo)>0){						
							for($t=0;$t<count($emailTo);$t++){
								$toEmail =	trim($emailTo[$t]);
								if(!empty($toEmail)){
									$getContactDetail = $this->lead_model->getContactByEmail($toEmail);
									if(count($getContactDetail)>0){
										if($activityType==1){
											$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInSalesActivity)==0){
												$this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
											
										} else if($activityType==2){
											$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInAcquisitionActivity)==0){
												$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
										}
										
										$event['contact_id'] = $getContactDetail->id;
										$event['company_id'] = $getContactDetail->company_id;
										$event['type'] = 3;
										$event['note'] = $message;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = $email_id;
										$event['subject'] = $email['subject'];
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $date;
										if($activityType==1){
											$this->lead_model->insetSalesActivity($event);
										} else if($activityType==3){
											/*Change Company ID*/
											$event['company_id'] = $preSaleBrokerCompanyID;
											$this->lead_model->insertPreSaleActivity($event);
										} else {
											 $this->lead_model->insertAcquistionActivity($event);
										}
									}	
								}
							}
						}/*
						$emailCc = explode(',',$email['cc']);
						if(count($emailCc)>0){
							for($t=0;$t<count($emailCc);$t++){
								if(!empty($emailCc[$t])){								
									$getContactDetail = $this->lead_model->getContactByEmail($emailCc[$t]);
									if(count($getContactDetail)>0){
										if($activityType==1){
											$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInSalesActivity)==0){
												$this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
											
										} else {
											$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInAcquisitionActivity)==0){
												$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
										}
										$event['company_id'] = $getContactDetail->company_id;
										$event['contact_id'] = $getContactDetail->id;
										$event['type'] = 3;
										$event['note'] = $message;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = $email_id;
										$event['subject'] = $email['subject'];
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $date;
										if($activityType==1){
										echo	$this->lead_model->insetSalesActivity($event);
										} else {
											echo $this->lead_model->insertAcquistionActivity($event);
										}
									}
								}
							}
						}*/
					}
				} else {
					$this->session->set_flashdata('error','Please select message first');
				}
			} else {
			
			if(isset($_SESSION['another_access_token'])){				
				$service = new GmailServiceHelper();
				if($service->checkExpiredToken()){
					if($_SESSION['another_access_token']!=""){
						$google_token= json_decode($_SESSION['another_access_token']);
						if(isset($google_token->refresh_token)){						
							$service->refreshToken($google_token->refresh_token);
							$newToken = json_decode($service->getAccessToken());
							$google_token->id_token = $newToken->id_token;
							$google_token->access_token = $newToken->access_token;
							$google_token->created = $newToken->created;
							$google_token = json_encode($google_token);						
							$_SESSION['another_access_token'] = $google_token;
							$_SESSION['access_token'] = $google_token;
						}	
					}
				}			
				$service->setAccessToken($_SESSION['another_access_token']);
				$send  = $service->sendMessage($email);
				$another = $this->input->post('another');
				if(isset($another['sys']) && $another['sys']!='0'){
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/updatePricePotential',
						CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => array(
							'i' => $another['sys'],
							'thread_id'=>$send->threadId,
						)				
					));
					$resp = curl_exec($curl);					
				}
				if(isset($another['legal_log']) && (int) $another['legal_log']>0){
					$totalPatents = $another['legal_patents'];
					$type = $another['f_t'];
					if(!empty($type)){
						$allEmails = $email['to'].$email['cc'].$email['bcc'];
						$dataArray = array("lead_id"=>$leadID,"type"=>$type,"total_patent"=>$totalPatents,"create_date"=>date('Y-m-d H:i:s'),"expert"=>$allEmails,'file_url'=>implode(',',$email['href']));
						$this->user_model->due_logtime($dataArray);
					}
				}
				if(isset($send->labelIds[0]) && $send->labelIds[0]=="SENT"){
					$listOfLabels = $service->listLabels();			
					$labelID = "";
					if(count($listOfLabels)>0){
						foreach($listOfLabels as $key=> $label){
							if(strtolower($label)=='lead'){
								$labelID = $key;
							}
						}
					}
					if(empty($labelID)){
						$label = $service->createLabel('Lead');
						if($label){
							$labelID = $label->getId();							
						}
					}
					
					if(!empty($labelID)){
						$service->modifyMessage($send->id,"me",$labelID);
					}	
					$date = date('Y-m-d H:i:s');
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Email send",'opportunity_id'=>0,'create_date'=>$date);
					$this->user_model->addUserHistory($user_history);
					/*Link to Email Box*/					
					$send = $this->linkWithMessageOnGo($leadID,$send->id,$date,$type,1);
					/*End Link to Email Box*/
					$pID = 0;
					if($type==1){
						/*Attach email with sales activity*/
						$dataSend = json_decode($send,true);
						$email_id = 0 ;
						if(isset($dataSend['send'])){
							$email_id = $dataSend['send'];
						} else if(isset($dataSend->send)){
							$email_id = $dataSend->send;
						}
						$popEmail= $email_id;
						$message = $email['message'];
						if(count($email['href'])>0){
							$message .= "<br/>";
							foreach($email['href'] as $url){
								$href = $url['href'];
								if(stristr($url['title'],"pdf")!==false){
									$href = str_replace("view?usp=drivesdk","preview",$href);
								}
								$message .= '<a  href="javascript://" data-file-id="" onclick=\'open_drive_files("'.trim($href).'");\' data-href="'.$url['href'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$url['title'].'</a> &nbsp; &nbsp; &nbsp;';
							}
						}
						$pID = $event['p_id'];
						$event['company_id'] = $event['c_id'];
						$event['contact_id'] = $event['p_id'];
						$event['type'] = 3;
						$event['note'] = $message;
						$event['user_id'] = $this->session->userdata['id'];
						$event['email_id'] = $email_id;
						$event['subject'] = $email['subject'];
						$event['lead_id'] = $leadID;
						$event['activity_date'] = $date;
						unset($event['c_id']);
						unset($event['p_id']);
						if($activityType==1){
							$this->lead_model->insetSalesActivity($event);
						} else if($activityType==3){
							$event['company_id'] = $preSaleBrokerCompanyID;
							$this->lead_model->insertPreSaleActivity($event);
						} else {
							$this->lead_model->insertAcquistionActivity($event);
						}
					}
					
					$dataSend = json_decode($send,true);
					$email_id = 0 ;
					if(isset($dataSend['send'])){
						$email_id = $dataSend['send'];
					} else if(isset($dataSend->send)){
						$email_id = $dataSend->send;
					}
					$popEmail= $email_id;
					$message = $email['message'];					
					if(count($email['href'])>0){
						$message .= "<br/>";
						foreach($email['href'] as $url){
							$href = $url['href'];
							if(stristr($url['title'],"pdf")!==false){
								$href = str_replace("view?usp=drivesdk","preview",$href);
							}
							$message .= '<a  href="javascript://" data-file-id="" onclick=\'open_drive_files("'.trim($href).'");\' data-href="'.$url['href'].'" data-mime="" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$url['title'].'</a> &nbsp; &nbsp; &nbsp;';
						}
					}
					
					/*Email to, Email CC, Email Bcc*/
					$emailTo = explode(',',$email['to']);
					
					if(isset($event['p_id'])){
						unset($event['c_id']);
						unset($event['p_id']);
					}
					if(count($emailTo)>0){						
						for($t=0;$t<count($emailTo);$t++){
							$toEmail = trim($emailTo[$t]);
							if(!empty($toEmail)){
								$getContactDetail = $this->lead_model->getContactByEmail($toEmail);
								if(count($getContactDetail)>0){
									if((isset($pID) && $getContactDetail->id!=$pID) || isset($event['contact_id']) && $event['contact_id']!=$getContactDetail->id){
										if($activityType==1){
											$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInSalesActivity)==0){
												$this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
											
										} else if($activityType==2){
											$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInAcquisitionActivity)==0){
												$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
										}
										$event['company_id'] = $getContactDetail->company_id;
										$event['contact_id'] = $getContactDetail->id;
										$event['type'] = 3;
										$event['note'] = $message;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = $email_id;
										$event['subject'] = $email['subject'];
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $date;
										if($activityType==1){
											$this->lead_model->insetSalesActivity($event);
										} else if($activityType==3){
											$event['company_id'] = $preSaleBrokerCompanyID;
											$this->lead_model->insertPreSaleActivity($event);
										} else {
											$this->lead_model->insertAcquistionActivity($event);
										}
									}																	
								}
							}
						}
					}
					/*
					$emailBcc = explode(',',$email['bcc']);
					if(count($emailBcc)>0){
						for($t=0;$t<count($emailBcc);$t++){
							if(!empty($emailBcc[$t])){
								if((isset($pID) && $emailBcc[$t]!=$pID) || isset($event['contact_id']) && $event['contact_id']!=$emailBcc[$t]){
									$getContactDetail = $this->lead_model->getContactByEmail($emailBcc[$t]);
									if(count($getContactDetail)>0){
										if($activityType==1){
											$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInSalesActivity)==0){
												$this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
											
										} else {
											$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInAcquisitionActivity)==0){
												$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
										}
										$event['company_id'] = $getContactDetail->company_id;
										$event['contact_id'] = $getContactDetail->id;
										$event['type'] = 3;
										$event['note'] = $message;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = $email_id;
										$event['subject'] = $email['subject'];
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $date;
										if($activityType==1){
											$this->lead_model->insetSalesActivity($event);
										} else {
											$this->lead_model->insertAcquistionActivity($event);
										}
									}																	
								}
							}
						}
					}
					$emailCc = explode(',',$email['cc']);
					if(count($emailCc)>0){
						for($t=0;$t<count($emailCc);$t++){
							if(!empty($emailCc[$t])){
								if((isset($pID) && $emailCc[$t]!=$pID) || isset($event['contact_id']) && $event['contact_id']!=$emailCc[$t]){
									$getContactDetail = $this->lead_model->getContactByEmail($emailCc[$t]);
									if(count($getContactDetail)>0){
										if($activityType==1){
											$checkCompanyInSalesActivity = $this->opportunity_model->checkCompanyInSales($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInSalesActivity)==0){
												$this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
											
										} else {
											$checkCompanyInAcquisitionActivity = $this->opportunity_model->checkCompanyInAcquisition($leadID,$getContactDetail->company_id);
											if(count($checkCompanyInAcquisitionActivity)==0){
												$this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$getContactDetail->company_id));
											}
										}
										$event['company_id'] = $getContactDetail->company_id;
										$event['contact_id'] = $getContactDetail->id;
										$event['type'] = 3;
										$event['note'] = $message;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = $email_id;
										$event['subject'] = $email['subject'];
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $date;
										if($activityType==1){
											$this->lead_model->insetSalesActivity($event);
										} else {
											$this->lead_model->insertAcquistionActivity($event);
										}
									}																	
								}
							}
						}
					}*/
					/*End */
					$this->session->set_flashdata('message','Message send successfully');
					/*redirect('dashboard/index/'.$leadID);*/
				} else {
					$this->session->set_flashdata('error','Please try after sometime.');
					/*redirect('dashboard');*/
				}				
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				/*redirect('dashboard');*/
			}
			}
		echo $popEmail;
		} else {
			$this->session->set_flashdata('error','Please select message first');
			/*redirect('dashboard');*/
		}		
	}
	
	function linkSearchMessage(){
		$data = 0;		
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_POST) && count($_POST)>0){
			$send = $this->linkWithMessageOnGo($_POST['lead'],$_POST['mesg'],$_POST['date'],$_POST['type'],$_POST['send_from']);
			$dataSend = json_decode($send,true);
			$email_id = 0 ;
			if(isset($dataSend['send'])){
				$email_id = $dataSend['send'];
			} else if(isset($dataSend->send)){
				$email_id = $dataSend->send;
			}
			if($email_id>0){
				$data = $email_id;
				$event = array();
				$event['company_id'] = $_POST['c_id'];
				$event['contact_id'] = $_POST['p_id'];
				$event['type'] = 6;
				$event['note'] = "";
				$event['user_id'] = $this->session->userdata['id'];
				$event['email_id'] = $email_id;
				$event['subject'] = $_POST['subject'];
				$event['lead_id'] = $_POST['lead'];
				$event['activity_date'] = date('Y-m-d H:i:s');
				if($_POST['activity_type']==1){
					$this->lead_model->insetSalesActivity($event);
				} else if($_POST['activity_type']==3){
					$this->lead_model->insertPreSaleActivity($event);
				} else {
					$this->lead_model->insertAcquistionActivity($event);
				}
				$emails = $_SESSION['STARRED'];	
				$threadComesFrom = "";
				$findIDFlag = 0;				
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $_POST['mesg']){
							$findIDFlag = 1;
							$threadComesFrom = "STARRED";
						}
					}
				}
				if($findIDFlag==0){
					$emails = $_SESSION['INBOX'];
					if(count($emails)>0){
						foreach($emails as $email){
							if($email['message_id'] == $_POST['mesg']){
								$findIDFlag = 1;
								$threadComesFrom = "INBOX";
							}
						}
					}
				}			
				if($findIDFlag==0){
					$emails = $_SESSION['TRASH'];
					if(count($emails)>0){
						foreach($emails as $email){
							if($email['message_id'] == $_POST['mesg']){
								$findIDFlag = 1;
								$threadComesFrom = "TRASH";
							}
						}
					}
				}
				if($findIDFlag==0){
					$emails = $_SESSION['LEAD'];
					if(count($emails)>0){
						foreach($emails as $email){
							if($email['message_id'] == $_POST['mesg']){
								$findIDFlag = 1;
								$threadComesFrom = "LEAD";
							}
						}
					}
				}
				if($findIDFlag==0){
					$emails = $_SESSION['SENT'];
					if(count($emails)>0){
						foreach($emails as $email){
							if($email['message_id'] == $_POST['mesg']){
								$findIDFlag = 1;
								$threadComesFrom = "SENT";
							}
						}
					}
				}
				if(!empty($threadComesFrom)){
					$unEmail = $_SESSION[$threadComesFrom];
					if(count($unEmail)>0){					
						$index=-1;
						$i=0;
						foreach($unEmail as $email){
							if($email['message_id'] == $_POST['mesg']){							
								$index=$i;
							}
							$i++;
						}
						if($index>=0){						
							unset($unEmail[$index]);
							$emailsREIndex = array_values($unEmail);
							$_SESSION[$threadComesFrom] = $emailsREIndex;
						}					
					}
				}
				$service = new GmailServiceHelper();
				$service->setAccessToken($_SESSION['another_access_token']);
				$labels = $service->listLabels();
				$labelID = "";
				foreach($labels as $key=>$label){
					if(strtoupper($label)=="LEAD"){
						$labelID = $key;
					}
				}
				if(empty($labelID)){
					/*Create label*/
					$label = $service->createLabel("LEAD");
					if(is_object($label) && $label->getId()!=""){
						$labelID = $label->getId();
					}
					/*End Create Label*/
				}
				if(!empty($labelID)){
					$service->modifyMessage($_POST['mesg'],"me",$labelID);
					$service->modifyMessageRemove($_POST['mesg'],"me",'INBOX');
					$service->modifyMessageRemove($_POST['mesg'],"me",'STARRED');
				}
			}
		}
		echo $data;
		die;
	}
	
	
	function linkWithMessageOnGo($leadID,$threadID,$date,$type,$sendFrom=0){
		if(!empty($leadID) && !empty($threadID)){
			if(!isset($_SESSION)){
				session_start();
			}
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['access_token']);
			$getMessagesDetails = $service->findThreadData($threadID);
			$filesAttachment = "";
			$gmailMessageID = $threadID;
			$content = array();
			$thread_id = "";
			if(count($getMessagesDetails)>0){
				$attachmentArray = array();
				foreach($getMessagesDetails as $message){					
					$attachmentsConnect = array();
					$attachments = $message['attachments'];
					if(count($attachments)>0){
						foreach($attachments as $attachment){
							$filename =  $attachment['filename'];
							$attachmentID  =  $attachment['attachmentId'];
							if(!in_array($attachmentID,$attachmentArray)){
								if(!empty($filename) && !empty($attachmentID)){
									$attachmentsData = $service->downloadAttachments($gmailMessageID,$attachmentID);
									if(is_object($attachmentsData)){
										$attachmentArray[] = $attachmentID;
										$rawData = $attachmentsData->data;
										$sanitizedData = strtr($rawData,'-_', '+/');
										$data = strtr($rawData, array('-' => '+', '_' => '/'));
										$body = base64_decode($sanitizedData);
										$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
										fwrite($fh, $body);
										fclose($fh);	
										$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
										$attachmentsConnect[] = array('filename'=>$filename,'mimeType'=>$attachment['mimeType'],"attachmentId"=>$attachmentID,"size"=>$attachment['size'],"realAttachID"=>$attachment['realAttachID']);
									}
								}
							}
						}
					}
					$thread_id = $message['thread_id'];
					$getMessageData = $message['content'];
					$parts = $getMessageData->getPayload()->getParts();
					$content[] = array("message_id"=>$message['message_id'],"labelIds"=>$message['labelIds'],"header"=>$message['header'],"body"=>$message['body'],"attachments"=>$attachmentsConnect,'ppp'=>$parts);
				}
				if(!empty($filesAttachment)){
					$filesAttachment = substr($filesAttachment,0,-1);
				}
				$sendData = $this->lead_model->insertBox(array("lead_id"=>$leadID,"user_id"=>$this->session->userdata['id'],"message_id"=>$threadID,"thread_id"=>$thread_id,"content"=>json_encode($content),"file_attach"=>$filesAttachment,'date_received'=>date('Y-m-d H:i:s',strtotime($date)),'type'=>$type,'sent_from'=>$sendFrom));
				if($sendData>0){
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					return json_encode(array('send'=>$sendData));
				} else {
					return json_encode(array('send'=>0));
				}				
			} else {
				return json_encode(array('send'=>0));
			} 
		} else {
			return json_encode(array('send'=>0));
		}
	} 
	
	function message_task_conversation_send(){
		$getList = array();
		if(isset($_POST) && count($_POST)>0){
			$message = $this->input->post('message');		
			if(isset($message['task_id']) && (int)$message['task_id']>0){				
				$getDetails = $this->lead_model->getTaskConversationList($message['task_id']);
				$savedID = 0;
				$message['create_c'] = date('Y-m-d H:i:s');
				$users = array();
				if(isset($message['user'])){
					$users = $message['user'];
					unset($message['user']);
				}
				$sendEmail = 0;
				if(isset($message['send_email']) && $message['send_email']!=""){
					$sendEmail = $message['send_email'];
				}
				unset($message['send_email']);				
				$savedID = $this->lead_model->saveTaskConversation($message);
				$sendMessgae = array();
				$getTaskDetail = $this->opportunity_model->findTask($message['task_id']);
				if(count($getDetails)==0){					
					if(count($getTaskDetail)>0){
						$sendUserID = 0;
						if($getTaskDetail->fromUserID!=$this->session->userdata['id']){
							$sendMessgae[] = $getTaskDetail->fromUserID;
							$sendUserID = $getTaskDetail->fromUserID;
							$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->fromUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
						} else {
							$sendMessgae[] = $getTaskDetail->toUserID;	
							$sendUserID = $getTaskDetail->toUserID;
							$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->toUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
						}
						if($sendUserID>0){
							$checkUserData = $this->user_model->getUserData($sendUserID);
							if(count($checkUserData) && (int)$sendEmail==1){
								$leadData = $this->lead_model->getLeadData($getTaskDetail['id']);
								$this->load->library('email');
								$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
								$this->email->to($checkUserData->email); 
								$this->email->set_mailtype('html'); 
								$this->email->subject($getTaskDetail['subject']);
								$this->email->message('<html><head><title>New Task</title></head><body>'.$message['message'].'<p><a href="'.$this->config->base_url().'dashboard/index/'.$leadData->id.'">Click</a> href.</p></body></html>');	
								$this->email->send();
							}
						}
					}
				} else {					
					$setFlag = 0;
					foreach($getDetails as $user){			
						if($savedID>0){
							if($user->fromUser!=$this->session->userdata['id']  && !in_array($user->fromUser,$sendMessgae)){
								$sendMessgae[] = $user->fromUser;															
								$this->lead_model->saveTaskConversationFlag(array("user_id"=>$user->fromUser,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
								$checkUserData = $this->user_model->getUserData($user->fromUser);
								if(count($checkUserData) && (int)$sendEmail==1){
									$leadData = $this->lead_model->getLeadData($getTaskDetail['id']);
									$this->load->library('email');
									$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
									$this->email->to($checkUserData->email); 
									$this->email->set_mailtype('html'); 
									$this->email->subject($getTaskDetail['subject']);
									$this->email->message('<html><head><title>New Task</title></head><body>'.$message['message'].'<p><a href="'.$this->config->base_url().'dashboard/index/'.$leadData->id.'">Click</a> href.</p></body></html>');	
									$this->email->send();
								}
								$setFlag = 1;
							}
						}						
					}
					
					if($setFlag ==0){
						if($savedID>0){
							$sendUserID = 0;
							if(count($getTaskDetail)>0){
								if($getTaskDetail->fromUserID!=$this->session->userdata['id']){
									$sendUserID = $getTaskDetail->fromUserID;
									$sendMessgae[] = $getTaskDetail->fromUserID;		
									$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->fromUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
								} else {
									$sendUserID = $getTaskDetail->toUserID;
									$sendMessgae[] = $getTaskDetail->toUserID;		
									$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->toUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
								}								
							}
							
							if($sendUserID>0){
								$checkUserData = $this->user_model->getUserData($sendUserID);
								if(count($checkUserData) && (int)$sendEmail==1){
									$leadData = $this->lead_model->getLeadData($getTaskDetail['id']);
									$this->load->library('email');
									$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
									$this->email->to($checkUserData->email); 
									$this->email->set_mailtype('html'); 
									$this->email->subject($getTaskDetail['subject']);
									$this->email->message('<html><head><title>New Task</title></head><body>'.$message['message'].'<p><a href="'.$this->config->base_url().'dashboard/index/'.$leadData->id.'">Click</a> href.</p></body></html>');	
									$this->email->send();
								}
							}
							
						}
					}
				}
				if(count($users)>0){
					if($savedID>0){
						foreach($users as $user){
							if(!in_array($user,$sendMessgae)){
								$sendMessgae[] = $user;								
								$this->lead_model->saveTaskConversationFlag(array("user_id"=>$user,"task_id"=>$message['task_id'],"message_id"=>$savedID,'status'=>1));
								$checkUserData = $this->user_model->getUserData($user);
								if(count($checkUserData) && (int)$sendEmail==1){
									$leadData = $this->lead_model->getLeadData($getTaskDetail['id']);
									$this->load->library('email');
									$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
									$this->email->to($checkUserData->email); 
									$this->email->set_mailtype('html'); 
									$this->email->subject($getTaskDetail['subject']);
									$this->email->message('<html><head><title>New Task</title></head><body>'.$message['message'].'<p><a href="'.$this->config->base_url().'dashboard/index/'.$leadData->id.'">Click</a> href.</p></body></html>');	
									$this->email->send();
								}
							}
						}
					}
				}
				if($savedID>0){
					$getList = $this->lead_model->getTaskConversation($message['task_id']);					
				}
			}
		}
		echo json_encode($getList);
		die;
	}
	
	function task_conversation(){
		$getList = array();
		if(isset($_POST) && count($_POST)>0){
			$c = $this->input->post('c');
			$getList = $this->lead_model->getTaskConversation($c);
		}
		echo json_encode($getList);
		die;
	}
	
	function message_task_conversation_remove(){
		if(isset($_POST) && count($_POST)>0){
			$c = $this->input->post('c');
			$this->lead_model->updateTaskFlagConversation($c,$this->session->userdata['id'],array('status'=>'2'));
		}
		die;
	}
	
	function callnotification(){
		$userID = $this->session->userdata['id'];
		$getList = $this->lead_model->getFlagConversations($userID,$this->input->post('f'),$this->input->post('l'));
		echo json_encode($getList);
		die;
	}
	
	
	function search_lead(){
		$searchData = array();
		if(isset($_POST) && count($_POST)>0){
			$searchFields = $this->input->post();
			$searchData = $this->lead_model->searchLeads($searchFields['search']);			
		}
		echo json_encode($searchData);
		die;
	}
	
	
	function retreiveDashboardEmails(){		
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();	
		}		
		$sessionAccessToken = "";
		if(!isset($_SESSION['guess_login'])){
			$_SESSION['guess_login']="0";
		}
		if(isset($_SESSION['guess_login']) && $_SESSION['guess_login']=="0"){
			if(isset($_SESSION['another_access_token'])){
				$sessionAccessToken = $_SESSION['another_access_token'];
			}
		} else {
			$sessionAccessToken= "synPat Guess Login";
		}
		if(!empty($sessionAccessToken)):
		if(isset($_SESSION['INBOX'])){
			$emails = $_SESSION['INBOX'];
		} else {
			$emails = array();
		}
		$incomplete = array();
		if((int)$this->session->userdata['type']!=9 && $this->session->userdata['type']!=8){
			$incomplete = $this->lead_model->findIncompleteANDCompleteListAccUser($this->session->userdata['id']);
		} else{
			$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
		}
		$openEmailBox = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(11,$this->session->userdata['modules_assign'])){
				$openEmailBox = false;
			}
		}
		/*if($openEmailBox===true):
			$boxList = $this->lead_model->findAllBoxList();
			$pass_lead = array();
		else:
			$boxList = array();
			$pass_lead = array();
		endif;*/
	?><style>.circle {width: 10px;height: 10px;-moz-border-radius: 50px;-webkit-border-radius: 50px;border-radius: 50px;}.red{background:#d9534f;}.green{background: green;}#all_type_list thead tr th.header{padding:5px;display:table-cell;cursor:hand;cursor:pointer}#all_type_list thead tr th.headerSortUp,#all_type_list thead tr th.headerSortDown{background:#acc8dd}.attachment-list-item{float:left;width:100%}.attachment-list-item a{float:left;width:95%}#activityTable .active > .glyph-icon{color:#2196f3 !important;;}</style> <script>snapGlobalFileWorkName='',snapGlobalFileWorkID='';$(function(){$(".gmail-modal .close").on("click",function(){$(".gmail-modal").hide()})});jQuery(document).ready(function(){initDragDrop();tabDropInit();leadsTableOneLineCells()});_cmU="<?php echo $this->session->userdata['email'];?>";window.specialChars={8:"\\b",9:"\\t",10:"\\n",12:"\\f",13:"\\r",39:"\\'",92:"\\\\"};window.escapedString=function(j){if(!j){return undefined}var a="";for(var b=0;b<j.length;b++){var e=j.charCodeAt(b);a+=specialChars[e]?specialChars[e]:String.fromCharCode(e)}return a};var timeline="";_CS="<?php echo $this->session->userdata['id']?>";function removeFromBox(b,a){if(jQuery.trim(a)!=""){jQuery.ajax({type:"POST",url:"<?php echo $this->config->base_url();?>leads/removeFromBox",data:{thread:a,g:b},cache:false,success:function(e){_data=jQuery.parseJSON(e);if(parseInt(_data.send)>0){refreshAcquisitionAndSalesActivity()}}})}}function initHoverEmailClose(){jQuery(document).ready(function(){jQuery("#aquisitionTable,#activityTable").find("table").find("tbody").find("tr").mouseover(function(){jQuery(this).find(".email-close").removeClass("hide")}).mouseleave(function(){jQuery(this).find(".email-close").addClass("hide")});jQuery("#aquisitionTable,#activityTable").find("tbody").find("tr").mouseover(function(){jQuery(this).find(".email-close").removeClass("hide")}).mouseleave(function(){jQuery(this).find(".email-close").addClass("hide")});jQuery("#from_regular,#from_litigation,#from_nonacquistion").find("#litigation_doc_list").find("ul").find("li").mouseover(function(){jQuery(this).find(".drive-close").removeClass("hide")}).mouseleave(function(){jQuery(this).find(".drive-close").addClass("hide")})})}function salesEmailDroppable(){}function recordLocal(a){switch(a){case"next":mainIndex=mainIndex+1;if(mainIndex>totalCC){mainIndex=totalCC}break;case"prev":mainIndex=mainIndex-1;if(mainIndex<0){mainIndex=0}break}threadDetail(jQuery("#all_type_list").find("tbody").find("tr").eq(mainIndex))}function activateLead(a){jQuery("#all_type_list").find("tbody").find("tr").each(function(){if(jQuery(this).attr("data-id")==a){threadDetail(jQuery(this))}})}function tabDropInit(){jQuery("#show_data").find(".nav-responsive").find("li").find("a").click(function(a){a.preventDefault();_tab=jQuery(this).attr("href");jQuery("#show_data").find(".nav-responsive").find("li").removeClass("active");jQuery("#show_data").find(".tab-pane").removeClass("active");jQuery(this).parent().addClass("active");jQuery("#show_data").find(_tab).addClass("active");reinitTabData();if(jQuery("#show_data").find('input[type="search"]').parent().find('i').length==0){
			jQuery("#show_data").find('input[type="search"]').css('width','80%').parent().append('<i class="glyph-icon icon-search" style="margin-left:-20px;"></i>');
		}showDataClickPrevent();});}function initAttachRemove(){jQuery(document).ready(function(){jQuery("ul.attachment-list").find("li").mouseover(function(){jQuery(this).find(".remove-attachment").removeClass("hide")}).mouseleave(function(){jQuery(this).find(".remove-attachment").addClass("hide")})})}function deleteMe(a){a.parent().parent().remove()}function initDragDrop(){$(".draggable").draggable({revert:true,zIndex:9999});$(".label-dropable").droppable({hoverClass:"drop-hover",tolerance:"pointer",drop:function(a,b){b.draggable.css("display","none");_thread=b.draggable.attr("data-id");_label=jQuery(this).attr("data-title");_active=jQuery.trim(jQuery(".emails-group-container").find("a.active").html());if(_thread!=undefined&&_thread!=""&&_label!=undefined&&_label!=""&&_active!=undefined&&_active!=""){jQuery.ajax({type:"POST",url:__baseUrl+"dashboard/moveThreadDiff",data:{thread:b.draggable.attr("data-id"),label:_label,active:_active},cache:false,success:function(e){if(e>0){b.draggable.remove()}else{b.draggable.css("display","block");b.draggable.css("top","0px");b.draggable.css("left","0px")}}})}}});
$("#taskDocUrl").droppable({hoverClass:"drop-hover",drop:function(a,b){if(b.draggable.parent().hasClass('todo-box-1') && b.draggable.find('a').length>0){jQuery(this).val(b.draggable.find('a').attr('data-href'));}}});
$(".droppable").droppable({
	hoverClass: "drop-hover",
	accept:function(d) { 
		// console.log(!$('#gmail_message_modal').hasClass('sb-active'));
        if(!$('#gmail_message_modal').hasClass('sb-active')) {
    		return true;
		}
    },
	drop:function(a,b){
		jQuery("#other_list_boxes").find("table").find("tr").find("td").removeClass("active");if(jQuery("#marketLead").length>0){jQuery("#marketLead").get(0).reset()}	jQuery(".messages-list-leads").find(".message-item").each(function(){jQuery(this).removeClass("message-active");jQuery(this).find("h5").addClass("c-dark");jQuery(this).find("h4").addClass("c-dark");jQuery(this).find("p").addClass("c-gray");jQuery(this).find("a").addClass("c-dark")});	jQuery("#subject").empty();jQuery(".message_detail").empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;"><img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt=""></div>');jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){jQuery(this).removeClass("active")});if(jQuery(this).hasClass("old_lead")){_neLD=b.draggable.attr("data-id");_dateEmail=b.draggable.attr("data-date");if(_dateEmail==undefined){_dateEmail=""}_newObject=jQuery(this);if(b.draggable.hasClass("driveDragable")&&jQuery(this).attr("data-id")!=undefined&&parseInt(jQuery(this).attr("data-id"))>0&&jQuery("#gmail_message_modal").hasClass("sb-active")==false){jQuery(this).find("td").eq(0).append('<div id="onFlyAddLoader" class="glyph-icon remove-border tooltip-button icon-spin-1 icon-spin float-left mrg0A" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');jQuery.ajax({type:"POST",url:"<?php echo $this->config->base_url();?>dashboard/move_drive_file",data:{old_thread:jQuery(this).attr("data-id"),drive:b.draggable.find("a.drive_file_click").attr("data-file-id"),date:_dateEmail},cache:false,success:function(e){__data=jQuery.parseJSON(e);jQuery("#onFlyAddLoader").remove();if(__data.send>0){_newObject.addClass("active");_aObject=_newObject.find("a");threadDetail(_aObject,"",1)}else{alert("Server busy, Please try after sometime.")}}})} else if(jQuery(this).attr('data-id')!=undefined && parseInt(jQuery(this).attr('data-id'))>0 && jQuery(this).find('td').length>1 && b.draggable.attr('data-id')!=undefined && b.draggable.attr('data-id')!="" && b.draggable.attr('data-message-id')!=undefined){/*_mainActivity = parseInt(jQuery("#activityMainType").val());*/
_fromEmail = b.draggable.find('h5.c-dark').find('a.c-dark').html();
_emailFrom = "";
if(_fromEmail.indexOf('<')>=0){
	_start = _fromEmail.indexOf('<')
	_end = _fromEmail.indexOf('></');
	_emailFrom = _fromEmail.substring(_start+1,_end);
}
jQuery("#moveEmailPopup").modal('show');
_lID = jQuery(this).attr('data-id');
jQuery.ajax({
	type:"POST",url:__baseUrl+"dashboard/linkWithMessage",
	data:{old_thread:_lID,thread:b.draggable.attr('data-id'),date:b.draggable.attr('data-date'),t:2,from_email:_emailFrom},
	success:function(et){
		jQuery("#moveEmailPopup").modal("hide");
		if(et!=""){
			_send=jQuery.parseJSON(et);
			if(_send.send>0){				
				_newObject.addClass('active');
				_aObject = _newObject.find('a');
				threadDetail(_aObject,"",1);
				if(b.draggable.attr('data-task')!='undefined' && b.draggable.attr('data-task')==1){
					leadGlobal = _lID;
					openTaskModal();
					jQuery("#taskEmailId").val(_send.send);
				}
				b.draggable.remove();
			} else{
				if(typeof _send.message!='undefined'){
					alert(_send.message);
				} else {
					alert("Please try after sometime");
				}				
			}
		} else {
			alert("Please try after sometime")
		}
	}
});}            }}});$("#attach_droppable").droppable({hoverClass:"drop-hover",drop:function(e,j){var b=j.draggable.find("a").attr("href");var a=j.draggable.html();if($(this).find("ul").length>0){$(this).find("ul").append("<li class='attachment-list-item'>"+a+" <span class='remove-attachment hide pull-right'><a class='' onclick='deleteMe(jQuery(this))'><i class='glyph-icon icon-close'></i></a></span></li>")}else{$(this).append("<ul class='attachment-list'><li class='attachment-list-item'>"+a+"<span class='remove-attachment hide pull-right'><a class='' onclick='deleteMe(jQuery(this))'><i class='glyph-icon icon-close'></i></a></span></li></ul>")}jQuery("#emailDocUrl").val($(this).html());initAttachRemove()}});}function driveFileDraggable(){jQuery(document).ready(function(){$(".driveDragable").draggable({revert:true,helper:"clone",zIndex:9999})})}function deleteSalesInvitedC(a){con=confirm("Are you sure?");if(con){jQuery.ajax({type:"POST",url:__baseUrl+"opportunity/delete_c_sales",data:{l:leadGlobal,c:a},cache:false,success:function(b){refreshAcquisitionAndSalesActivity();}})}}function deleteAcquisitionInvitedC(a){con=confirm("Are you sure?");if(con){jQuery.ajax({type:"POST",url:__baseUrl+"opportunity/delete_c_acquisition",data:{l:leadGlobal,c:a},cache:false,success:function(b){if(b>0){refreshAcquisitionAndSalesActivity();}}})}}
_leadCompaniesAssignBroker=[];
_bUsT = <?php echo $this->session->userdata['type'];?>;
function salesActivityList(a){
	window.SalesUser=[];
	jQuery("#activityTable").find("tbody.main_active").empty();if(a.length>0){for(i=0;i<a.length;i++){_cID=a[i].company.id;_cName=a[i].company.company_name;if(a[i].company.company_name_alias!=''){_cName=a[i].company.company_name_alias;}broker_name='';broker_company='';/*if(typeof a[i].company.broker_details.first_name!='undefined'){broker_name = a[i].company.broker_details.first_name+' '+a[i].company.broker_details.last_name;broker_company = a[i].company.broker_details.company_name; }*/_person="";_activity="";editConf='';_date="";_note="";if(a[i].activities.length>0){_person=a[i].activities[0].firstName+" "+a[i].activities[0].lastName;_activity=salesActivities[a[i].activities[0].type];_date=a[i].activities[0].activity_date;_note=a[i].activities[0].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[0].type==11){_note = _note.nl2br();} if(a[i].activities[0].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[0].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[0].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}}
	_stepsProcess='',_discardICON='',_classActivity='';	
	_openEditContact='';
		if(_bUsT==9){
			_classActivity = 'showActivity';
			_openEditContact = "onclick='openCompanyContact("+_cID+")'";
			/*_discardICON="<a href='javascript://' onclick='deleteSalesInvitedC("+_cID+")'><i class='glyph-icon'><img src='"+__baseUrl+"public/images/discard.png' style='opacity:0.55'></i></a>";*/
			_discardICON ="<input type='checkbox' name='assign_delete[]'/>";
			_stepsProcess ='<select name="stage_progress" class="mrg5L" id="stage_progress" onchange="openProgressPop(jQuery(this))">';
			_stepsProcess +='<option value="" class="no-contact">No contacts</option>';
			stage = 1==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="1" '+stage+' class="torquoise">Contact Found</option>';
			stage = 2==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="2" '+stage+' class="torquoise">Invitation was Sent</option>';
			stage = 7==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="7" '+stage+' class="torquoise">Invite was received</option>';
			stage = 3==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="3" '+stage+' class="torquoise">Sale call was scheduled</option>';
			stage = 4==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="4" '+stage+' class="seablue">Opportunity is understood</option>';
			stage = 5==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="5" '+stage+' class="seablue">Documents are reviewed</option>';
			stage = 8==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="8" '+stage+' class="seablue">Customer is interested</option>';
			stage = 9==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="9" '+stage+' class="darksea">Documents are exchanges</option>';
			stage = 10==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="10" '+stage+' class="darksea">RTP was submitted</option>';
			stage = 11==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="11" '+stage+' class="darksea">Payment is made</option>';
			stage = 6==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="6" '+stage+' class="redsea">Pass</option>';
			_stepsProcess +='</select>';
		} else {
			console.log(parseInt(a[i].company.stage));
			switch(parseInt(a[i].company.stage)){
				case 1:
					_stepsProcess = 'Contact Found';
				break;
				case 2:
					_stepsProcess = 'Invitation was Sent';
				break;
				case 3:
					_stepsProcess = 'Sale call was scheduled';
				break;
				case 4:
					_stepsProcess = 'Opportunity is understood';
				break;
				case 5:
					_stepsProcess = 'Documents are reviewed';
				break;
				case 6:
					_stepsProcess = 'Pass';
				break;
				case 7:
					_stepsProcess = 'Invite was received';
				break;
				case 8:
					_stepsProcess = 'Customer is interested';
				break;
				case 9:
					_stepsProcess = 'Documents are exchanges';
				break;
				case 10:
					_stepsProcess = 'RTP was submitted';
				break;
				case 11:
					_stepsProcess = 'Payment is made';
				break;
			}			
		}
	
	
	if(_leadCompaniesAssignBroker.length>0 && _bUsT!=9){
		_classActivity ='',broker_company='',broker_name='';
		for(lb=0;lb<_leadCompaniesAssignBroker.length;lb++){
			if(_leadCompaniesAssignBroker[lb].SBLCID ==_cID){
				_classActivity = 'showActivity';
				broker_name = _leadCompaniesAssignBroker[lb].company_name;
				broker_company = _leadCompaniesAssignBroker[lb].company_name;
				_stepsProcess ='<select name="stage_progress" class="mrg5L" id="stage_progress" onchange="openProgressPop(jQuery(this))">';
				_stepsProcess +='<option value="" class="no-contact">No contacts</option>';
				stage = 1==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="1" '+stage+' class="torquoise">Contact Found</option>';
				stage = 2==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="2" '+stage+' class="torquoise">Invitation was Sent</option>';
				stage = 7==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="7" '+stage+' class="torquoise">Invite was received</option>';
				stage = 3==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="3" '+stage+' class="torquoise">Sale call was scheduled</option>';
				stage = 4==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="4" '+stage+' class="seablue">Opportunity is understood</option>';
				stage = 5==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="5" '+stage+' class="seablue">Documents are reviewed</option>';
				stage = 8==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="8" '+stage+' class="seablue">Customer is interested</option>';
				stage = 9==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="9" '+stage+' class="darksea">Documents are exchanges</option>';
				stage = 10==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="10" '+stage+' class="darksea">RTP was submitted</option>';
				stage = 11==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="11" '+stage+' class="darksea">Payment is made</option>';
				stage = 6==a[i].company.stage ?'SELECTED="SELECTED"':'';
				_stepsProcess +='<option value="6" '+stage+' class="redsea">Pass</option>';
				_stepsProcess +='</select>';
			}
		}
	} else {
		for(lb=0;lb<_leadCompaniesAssignBroker.length;lb++){			
			_classActivity = 'showActivity';
			broker_name = _leadCompaniesAssignBroker[lb].company_name;
			broker_company = _leadCompaniesAssignBroker[lb].company_name;
			_stepsProcess ='<select name="stage_progress" class="mrg5L" id="stage_progress" onchange="openProgressPop(jQuery(this))">';
			_stepsProcess +='<option value="" class="no-contact">No contacts</option>';
			stage = 1==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="1" '+stage+' class="torquoise">Contact Found</option>';
			stage = 2==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="2" '+stage+' class="torquoise">Invitation was Sent</option>';
			stage = 7==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="7" '+stage+' class="torquoise">Invite was received</option>';
			stage = 3==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="3" '+stage+' class="torquoise">Sale call was scheduled</option>';
			stage = 4==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="4" '+stage+' class="seablue">Opportunity is understood</option>';
			stage = 5==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="5" '+stage+' class="seablue">Documents are reviewed</option>';
			stage = 8==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="8" '+stage+' class="seablue">Customer is interested</option>';
			stage = 9==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="9" '+stage+' class="darksea">Documents are exchanges</option>';
			stage = 10==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="10" '+stage+' class="darksea">RTP was submitted</option>';
			stage = 11==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="11" '+stage+' class="darksea">Payment is made</option>';
			stage = 6==a[i].company.stage ?'SELECTED="SELECTED"':'';
			_stepsProcess +='<option value="6" '+stage+' class="redsea">Pass</option>';
			_stepsProcess +='</select>';
		}
	}
		/*<td style='width:100px;'>"+_activity+"</td>*/
		_tr="<tr class='master '  data-c='"+_cID+"'><td style='width:220px;'>"+_discardICON+_stepsProcess+"</td><td style='width:380px;'><a href='javascript://' class='"+_classActivity+"'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a>&nbsp;<a href='javascript://' "+_openEditContact+"><b>"+_cName+"</b> <span class='broker_detail' data-company='"+broker_company+"' style='float:right;'>"+broker_name+"</span></a></td><td style='width:130px;'>"+_date+"</td><td style='width:150px;'>"+_person+"</td>";if(_activity!="" && a[i].activities[0].type!=undefined){switch(a[i].activities[0].type){case 1: case 2: case 37: editConf="<a href='javascript:void(0);' class='' onclick='editActivitiesData("+leadGlobal+',"'+a[i].activities[0].id+"\")'><i class='glyph-icon icon-close'></i></a>"; break; }}if(_activity!=""&&(a[i].activities[0].type=="6" || a[i].activities[0].type=="3")&&a[i].activities[0].email_id!=0){if(a[i].activities[0].email.length>0){window.SalesUser[a[i].activities[0].email[0].id] = a[i].activities[0].email[0];__a='';if(_bUsT==9){__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+a[i].activities[0].email[0].id+"\")'><i class='glyph-icon icon-close'></i></a>";}if(a[i].activities[0].email[0].account_type==2){_color='#2196f3';if(a[i].activities[0].email[0].sent_from==1){_color='#d1c8c8';}message = a[i].activities[0].email[0];if(a[i].activities[0].error==1){_color="#cc0000";}
		if(a[i].activities[0].subject==""){_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[0].email[0].id+",jQuery(this));'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>View Email</a>";} _tr+="<td><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>";}else{_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[0].email[0].id+",jQuery(this));'>"+a[i].activities[0].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>"+a[i].activities[0].subject+"</a>";}_tr+="<td><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>"}
	} else {_color='#2196f3';if(a[i].activities[0].email[0].sent_from==1){_color='#d1c8c8';}if(a[i].activities[0].error==1){_color="#cc0000";}
		if(a[i].activities[0].subject==""){_an="";if(_classActivity!=''){_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>View Email</a>";} else {_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"'>View Email</a>";}_tr+="<td style=''><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>";}else{_an="";if(_classActivity!=''){_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+a[i].activities[0].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>View Email</a>";}_tr+="<td><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>"}
	}}}else{_tr+="<td style=''><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}jQuery("#activityTable").find("tbody.main_active").append(_tr);_cList="<table class='table' style='border:0px;table-layout:fixed'><thead><tr><th>#&nbsp;<a href='javascript://' onclick='addNewContact(jQuery(this))' class='mrg10L' style='display:inline-block'><i class='glyph-icon icon-plus-circle'></i></a></th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody></tbody></table>";_cActivites="<table class='table' style='border:0px;'></table>";if(a[i].people.length>0 && _classActivity!=''){_cList="";_tr="";for(p=0;p<a[i].people.length;p++){_name=a[i].people[p].first_name+" "+a[i].people[p].last_name;_phone=a[i].people[p].phone;_gateway='';if(a[i].people[p].gateway>0){_gateway='&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/gateway.png" style="width:16px;"/>';}no_contact='';if(a[i].people[p].no_contact=='1'){no_contact = '&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/no_contact.jpg"/>';}if(_phone==""){_phone=a[i].people[p].telephone;if(_phone!=''){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}else{if(a[i].people[p].telephone!=""){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';_phone+='<br/><a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+a[i].people[p].telephone+'"),jQuery(this))\'>'+a[i].people[p].telephone+'</a>';} else {_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}_sLinks='';if(a[i].people[p].email!=''){_sLinks='<a onclick="openComposeEmail(jQuery(this));" href="javascript://"><i class="glyph-icon icon-envelope" style="color:#2196f3"></i></a>';}if(a[i].people[p].linkedin_url!=''){_sLinks +='&nbsp;<a href="'+a[i].people[p].linkedin_url+'" target="_BLANK"><i class="glyph-icon icon-linkedin" style="color:#2196f3"></i></a>';}_tr+="<tr class='salesFDroppable' data-c='"+_cID+"' data-p='"+a[i].people[p].id+"'><td style='border-left:0px;border-bottom:0px;width:30px;'><input name='sales_person[]' class='sales-activity-checkbox' data-attr-em='"+a[i].people[p].email+"' data-attr-linkedin='"+a[i].people[p].linkedin_url+"' data-attr-name='"+_name+"' data-attr-c-name='"+_cName+"'  type='checkbox' value='"+a[i].people[p].id+"' style='margin-left:0px;'/>"+_sLinks+"</td><td style='border-left:0px;border-bottom:0px;'><a href='javascript://' onclick='editContact("+a[i].people[p].id+")'>"+_name+_gateway+no_contact+"</a></td><td style='border-left:0px;border-bottom:0px;'>"+a[i].people[p].job_title+"</td><td style='border-left:0px;border-bottom:0px;'>"+_phone+"</td></tr>";}_cList="<table class='table' style='border:0px;table-layout:fixed'><thead><tr><th>#&nbsp;<a href='javascript://' onclick='addNewContact(jQuery(this))' class='mrg10L' style='display:inline-block'><i class='glyph-icon icon-plus-circle'></i></a></th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>"+_tr+"</tbody></table>"}if(a[i].activities.length>0){_cActivites="";_tr="";for(al=1;al<a[i].activities.length;al++){_person=a[i].activities[al].firstName+" "+a[i].activities[al].lastName;_activity=salesActivities[a[i].activities[al].type];_date=a[i].activities[al].activity_date;_note=a[i].activities[al].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[al].type==11){_note = _note.nl2br();}if(a[i].activities[al].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[al].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[al].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}/*<td style='width: 100px;'>"+_activity+"</td>*/if(_activity!=""&&(a[i].activities[al].type=="6" || a[i].activities[al].type=="3")&&a[i].activities[al].email_id!=0){if(a[i].activities[al].email.length>0){
			window.SalesUser[a[i].activities[al].email[0].id] = a[i].activities[al].email[0];
			if(a[i].activities[al].email[0].account_type==2){
				_color='#2196f3';if(a[i].activities[al].email[0].sent_from==1){_color='#d1c8c8';}
				message = a[i].activities[al].email[0];if(a[i].activities[al].error==1){_color="#cc0000";}
				if(a[i].activities[al].subject==""){
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[al].email[0].id+",jQuery(this));'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>View Email</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}else{
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[al].email[0].id+",jQuery(this));'>"+a[i].activities[al].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>"+a[i].activities[al].subject+"</a>";}
				_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}
			} else {
				_color='#2196f3';if(a[i].activities[al].email[0].sent_from==1){_color='#d1c8c8';}if(a[i].activities[al].error==1){_color="#cc0000";}
				if(a[i].activities[al].subject==""){
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>View Email</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}else{
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>"+a[i].activities[al].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>"+a[i].activities[al].subject+"</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}
	}
	}}_tr+="<tr><td style='width:130px;'>"+_date+"</td><td style='width: 150px;'>"+_person+"</td><td style='border-right:0px;'><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}_cActivites="<table class='table' style='border:0px;table-layout:fixed'><tbody>"+_tr+"</tbody></table>"}_newTr="<tr style='display:none;'><td colspan='2' style='padding:0px;border:0px;width:435px;'>"+_cList+"</td><td colspan='4' style='padding:0px;border:0px;'>"+_cActivites+"</td></tr>";jQuery("#activityTable").find("tbody.main_active").append(_newTr)}}toggleCompanySales();initHoverEmailClose();checkProcessColor();runFixedTableLayoutProccess(1);}
	function checkProcessColor(){
		_mainActivity = jQuery("#activityMainType").val();
		if(_mainActivity=="1"){
			jQuery("#activityTable").find("tbody.main_active").find('tr.master').each(function(){
				_p = jQuery(this).find("select");
				if(_p.find('option:selected')){
					className = _p.find('option:selected').attr('class');
				}
				_p.addClass(className);
			});
		}
	}
	function preSalesActivityList(a){
	jQuery("#preSaleActivityTable").find("tbody.main_active").empty();if(a.length>0){for(i=0;i<a.length;i++){_cID=a[i].company.id;_cName=a[i].company.company_name;broker_name='';broker_company='';/*if(typeof a[i].company.broker_details.first_name!='undefined'){broker_name = a[i].company.broker_details.first_name+' '+a[i].company.broker_details.last_name;broker_company = a[i].company.broker_details.company_name; }*/_person="";_activity="";editConf='';_date="";_note="";if(a[i].activities.length>0){_person=a[i].activities[0].firstName+" "+a[i].activities[0].lastName;_activity=salesActivities[a[i].activities[0].type];_date=a[i].activities[0].activity_date;_note=a[i].activities[0].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[0].type==11){_note = _note.nl2br();}if(a[i].activities[0].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[0].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[0].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}}
	_stepsProcess='',_discardICON='',_classActivity='';	
	_classActivity = 'showActivity';
		if(_bUsT==9){
			_classActivity = 'showActivity';
			/*_discardICON="<a href='javascript://' onclick='deleteSalesInvitedC("+_cID+")'><i class='glyph-icon'><img src='"+__baseUrl+"public/images/discard.png' style='opacity:0.55'></i></a>";*/
			_discardICON ="<input type='checkbox' name='assign_delete[]'/>";			
		} 
		/*<td style='width:100px;'>"+_activity+"</td>*/
		_tr="<tr class='master '  data-c='"+_cID+"'><td style='width:65px;'>"+_discardICON+_stepsProcess+"</td><td style='width:234px;'><a href='javascript://' class='"+_classActivity+"'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a>&nbsp;<a href='javascript://' onclick='openCompanyContact("+_cID+")'><b>"+_cName+"</b> <span class='broker_detail' data-company='"+broker_company+"' style='float:right;'>"+broker_name+"</span></a></td><td style='width:110px;'>"+_date+"</td><td style='width:120px;'>"+_person+"</td>";if(_activity!="" && a[i].activities[0].type!=undefined){switch(a[i].activities[0].type){case 1: case 2: case 37: editConf="<a href='javascript:void(0);' class='' onclick='editActivitiesData("+leadGlobal+',"'+a[i].activities[0].id+"\")'><i class='glyph-icon icon-close'></i></a>"; break; }}if(_activity!=""&&(a[i].activities[0].type=="6" || a[i].activities[0].type=="3")&&a[i].activities[0].email_id!=0){if(a[i].activities[0].email.length>0){window.SalesUser[a[i].activities[0].email[0].id] = a[i].activities[0].email[0];__a='';if(_bUsT==9){__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+a[i].activities[0].email[0].id+"\")'><i class='glyph-icon icon-close'></i></a>";}if(a[i].activities[0].email[0].account_type==2){_color='#2196f3';if(a[i].activities[0].email[0].sent_from==1){_color='#d1c8c8';}message = a[i].activities[0].email[0];if(a[i].activities[0].error==1){_color="#cc0000";}
		if(a[i].activities[0].subject==""){_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[0].email[0].id+",jQuery(this));'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>View Email</a>";} _tr+="<td style='width:400px;'><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>";}else{_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[0].email[0].id+",jQuery(this));'>"+a[i].activities[0].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>"+a[i].activities[0].subject+"</a>";}_tr+="<td><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>"}
	} else {_color='#2196f3';if(a[i].activities[0].email[0].sent_from==1){_color='#d1c8c8';}if(a[i].activities[0].error==1){_color="#cc0000";}
		if(a[i].activities[0].subject==""){_an="";if(_classActivity!=''){_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>View Email</a>";} else {_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"'>View Email</a>";}_tr+="<td style='width:400px;'><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>";}else{_an="";if(_classActivity!=''){_an="<a style='' href='javascript://' data-tr='"+a[i].activities[0].email[0].id+"' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+a[i].activities[0].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[0].email[0].id+"' href='javascript://'>View Email</a>";}_tr+="<td><i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an+"</td></tr>"}
	}}}else{_tr+="<td style='width:400px;'><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}jQuery("#preSaleActivityTable").find("tbody.main_active").append(_tr);_cList="<table class='table' style='border:0px;'><thead><tr><th>#</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody></tbody></table>";_cActivites="<table class='table' style='border:0px;'></table>";if(a[i].people.length>0 && _classActivity!=''){_cList="";_tr="";for(p=0;p<a[i].people.length;p++){_name=a[i].people[p].first_name+" "+a[i].people[p].last_name;_phone=a[i].people[p].phone;_gateway='';if(a[i].people[p].gateway>0){_gateway='&nbsp;&nbsp;<i class="glyph-icon icon-key tooltip-button" title="" data-placement="bottom" data-original-title="Gateway"></i>';}if(_phone==""){_phone=a[i].people[p].telephone;if(_phone!=''){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}else{if(a[i].people[p].telephone!=""){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';_phone+='<br/><a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+a[i].people[p].telephone+'"),jQuery(this))\'>'+a[i].people[p].telephone+'</a>';} else {_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}_sLinks='';if(a[i].people[p].email!=''){_sLinks='<a href="javascript://" onclick="openComposeEmail(jQuery(this))"><i class="glyph-icon icon-envelope-square"></i></a>';}if(a[i].people[p].linkedin_url!=''){_sLinks +='&nbsp;&nbsp;<a target="_BLANK" href="'+a[i].people[p].linkedin_url+'"><i class="glyph-icon icon-linkedin"></i></a>';}_tr+="<tr class='salesFDroppable' data-c='"+_cID+"' data-p='"+a[i].people[p].id+"'><td style='border-left:0px; width:65px;'><input name='sales_person[]' class='sales-activity-checkbox' data-attr-em='"+a[i].people[p].email+"' data-attr-linkedin='"+a[i].people[p].linkedin_url+"' data-attr-name='"+_name+"' data-attr-c-name='"+_cName+"'  type='checkbox' value='"+a[i].people[p].id+"' style='margin-left:0px;'/>"+_sLinks+"</td><td><a href='javascript://' onclick='editContact("+a[i].people[p].id+")'>"+_name+_gateway+"</a></td><td>"+a[i].people[p].job_title+"</td><td style=''>"+_phone+"</td></tr>"}_cList="<table class='table' style='border:0px;'><thead><tr><th>#</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>"+_tr+"</tbody></table>"}if(a[i].activities.length>0){_cActivites="";_tr="";for(al=1;al<a[i].activities.length;al++){_person=a[i].activities[al].firstName+" "+a[i].activities[al].lastName;_activity=salesActivities[a[i].activities[al].type];_date=a[i].activities[al].activity_date;_note=a[i].activities[al].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[al].type==11){_note = _note.nl2br();}if(a[i].activities[al].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[al].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[al].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}if(_activity!=""&&(a[i].activities[al].type=="6" || a[i].activities[al].type=="3")&&a[i].activities[al].email_id!=0){if(a[i].activities[al].email.length>0){
			window.SalesUser[a[i].activities[al].email[0].id] = a[i].activities[al].email[0];
			if(a[i].activities[al].email[0].account_type==2){
				_color='#2196f3';if(a[i].activities[al].email[0].sent_from==1){_color='#d1c8c8';}
				message = a[i].activities[al].email[0];if(a[i].activities[al].error==1){_color="#cc0000";}
				if(a[i].activities[al].subject==""){
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[al].email[0].id+",jQuery(this));'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>View Email</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}else{
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='imapShowDataSales("+a[i].activities[al].email[0].id+",jQuery(this));'>"+a[i].activities[al].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>"+a[i].activities[al].subject+"</a>";}
				_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}
			} else {
				_color='#2196f3';if(a[i].activities[al].email[0].sent_from==1){_color='#d1c8c8';}if(a[i].activities[al].error==1){_color="#cc0000";}
				if(a[i].activities[al].subject==""){
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>View Email</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>View Email</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}else{
					_an="";if(_classActivity!=''){_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>"+a[i].activities[al].subject+"</a>";} else {_an="<a style='' data-tr='"+a[i].activities[al].email[0].id+"' href='javascript://'>"+a[i].activities[al].subject+"</a>";}
					_note="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;"+_an;
				}
	}
	}}/*<td style='width: 100px;'>"+_activity+"</td>*/_tr+="<tr><td style='width: 110px;'>"+_date+"</td><td style='width: 120px;'>"+_person+"</td><td style='border-right:0px; width: 400px;'><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}_cActivites="<table class='table' style='border:0px;'><tbody>"+_tr+"</tbody></table>"}_newTr="<tr style='display:none;'><td colspan='2' style='padding:0px;border:0px;width:400px;'>"+_cList+"</td><td colspan='4' style='padding:0px;border:0px;'>"+_cActivites+"</td></tr>";jQuery("#preSaleActivityTable").find("tbody.main_active").append(_newTr)}}toggleCompanySales();initHoverEmailClose()}
	function docFileDraggable(){jQuery(document).ready(function(){$(".docDragable").draggable({revert:true,helper:"clone",zIndex:9999});/*$(".emailDragable").draggable({revert:true,helper:"clone",zIndex:9999});*/$(".docDropable").droppable({hoverClass:"drop-hover",drop:function(e,j){var b=j.draggable.find("a").attr("data-href");var a=j.draggable.find("a").text();_id=jQuery(this).find("ul").attr("data-id");mainParent=jQuery(this);jQuery("#mainDocWaitBox").modal("show");if(b!=""&&a!=""&&_id!=""&&_id!=undefined&&a!=undefined&&b!=undefined){_container='';if(jQuery('#from_nonacquistion').is(':visible')){_container='from_nonacquistion';}if(jQuery('#from_litigation').is(':visible')){_container='from_litigation';}if(jQuery('#from_regular').is(':visible')){_container='from_regular';}_fF='';if(_container!=''){_fF = jQuery('#'+_container).find('select#clipboard').val();}jQuery.ajax({url:__baseUrl+"leads/fileInsert",type:"POST",data:{file_name:a,doc_url:b,d:_id,f:_fF},cache:false,success:function(k){jQuery("#mainDocWaitBox").modal("hide");_data=jQuery.parseJSON(k);if(_data.error=="0"){/*Refresh Drive Folder*/findThisDriveFile(jQuery('#'+_container).find('select#clipboard'));}else{alert("Please try later")}}})}}})})}function findOwnThread(a,e,b,c){if(typeof _globalAjax=='object'){_globalAjax.abort();}if(typeof c=='undefined'){jQuery('#displayEmail').empty();}_mainActivity = parseInt(jQuery("#activityMainType").val());if(typeof(b)==="undefined"){b=0}jQuery(".mCSB_container").find(".message-item").each(function(){jQuery(this).removeClass("message-active");jQuery(this).find("h5").addClass("c-dark");jQuery(this).find("h4").addClass("c-dark");jQuery(this).find("p").addClass("c-gray");jQuery(this).find("a").addClass("c-dark")});if(b==0){e.parent().parent().parent().find("h5").removeClass("c-dark");e.parent().parent().parent().find("h4").removeClass("c-dark");e.parent().parent().parent().find("p").removeClass("c-gray");e.parent().parent().parent().find("a").removeClass("c-dark");e.parent().parent().parent().find("a").css("color","#FFF");e.parent().parent().parent().addClass("message-active");jQuery("#gmail_message").hide();jQuery("#from_regular").hide();jQuery("#from_litigation").hide();if(jQuery("#myDashboardComposeEmails").length>0){jQuery("#myDashboardComposeEmails").get(0).reset()}$("#all_type_list tbody td").removeClass("active");$("#all_type_list tbody tr").removeClass("active");$(".DTFC_Cloned tbody td").removeClass("active");$(".DTFC_Cloned tbody tr").removeClass("active");$("#other_list_boxes").empty()}if(typeof c!='undefined'){ if(c==2){e.parent().parent().parent().parent().find('div.message-item').removeClass('message-active')}if(c==1 || c==2) {leadGlobal='0';leadNameGlobal='';$("#all_type_list tbody td, #all_type_list tbody tr").removeClass("active");$(".DTFC_Cloned tbody td,.DTFC_Cloned tbody tr").removeClass("active"); jQuery("#marketLead").get(0).reset();jQuery('#from_regular,#from_litigation,#from_nonacquistion').css('display','none');e.parent().parent().parent().addClass('message-active');}}$("#other_list_boxes .message td").removeClass("active");if(!b){$("#all_type_list tbody td").removeClass("active");$(".DTFC_Cloned tbody td").removeClass("active")}jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){jQuery(this).removeClass("active");jQuery("#marketLead").get(0).reset()});if(b){jQuery("#aquisitionTable").find('tbody>tr>td').removeClass('active');jQuery("#aquisitionTable").find('tbody>tr>td').find('div').removeClass('active');jQuery("#activityTable").find('tbody>tr>td').removeClass('active');jQuery("#activityTable").find('tbody>tr>td').find('div').removeClass('active');jQuery("#preSaleActivityTable").find('tbody>tr>td').removeClass('active');jQuery("#preSaleActivityTable").find('tbody>tr>td').find('div').removeClass('active');e.parent().addClass("active")}if(_mainActivity>0 && typeof(e)=='object'){p=0,c_id=0;if(_mainActivity==2){if(jQuery("#aquisitionTable:visible").length>0){if(jQuery("#aquisitionTable:visible").find('input[name="sales_person[]"]:checked').length>0){if(jQuery("#aquisitionTable:visible").find('input[name="sales_person[]"]:checked').length==1){p=jQuery("#aquisitionTable:visible").find('input[name="sales_person[]"]:checked').val();c_id=jQuery("#aquisitionTable:visible").find('input[name="sales_person[]"]:checked').parent().parent().attr("data-c");} else {alert("Please select only one person from whom you received email")}}}}if(p>0&&c_id>0){jQuery('#moveEmailPopup').modal('show');jQuery.ajax({type:"POST",url:__baseUrl+"dashboard/oldlinkWithMessage",data:{old_thread:leadGlobal,c_id:c_id,p:p,thread:e.parent().parent().attr("data-id"),t:_mainActivity},cache:false,success:function(et){jQuery('#moveEmailPopup').modal('hide');if(et!=""){__data=jQuery.parseJSON(et);if(__data.send){e.parent().parent().remove();refreshAcquisitionAndSalesActivity();/*threadDetail(jQuery("#all_type_list").find("tbody").find("tr.active"))*/}else{alert("Please try after sometime")}}else{showLocalEmail(a,e,b);}}})}else{showLocalEmail(a,e,b);}} else {showLocalEmail(a,e,b);}}function showLocalEmail(a,e,b){var j=jQuery("#message-detail").height()-jQuery("#message-detail .panel-heading").outerHeight()+12;j=296;

	jQuery("#displayEmail").html('<iframe src="'+__baseUrl+"users/own_server_email/"+jQuery.trim(a)+'" scrolling="yes" width="100%" height="'+j+'">')
	jQuery("#displayEmail iframe").off('load').on('load', function() {
		checkMyEmailsHeight();
	});

}_leadPatent="";_lT="";function updatePatenteesData(b){__HTMLL='<div id="TextBoxesGroup"><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Assets:</h2><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" style="width:100% !important"><span class="mainColor mainFont">1. Number of patents you wish to sell:</span></p><span style="width: 134px;display: inline-block;">&nbsp;</span><span style="width: 141px;display: inline-block;margin-left: 5px;text-align: center;">Patent</span><span style="width: 130px;display: inline-block;margin-left: 5px;text-align: center;">Application</span></div><div id="patentNumbers" style="margin-bottom: 5px !important;"></div><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop"><span class="mainColor mainFont">2. How many are Standard Essential?</span></p><input id="essnt" readonly onkeypress="return isNumber(event)" class="p_tet noPadding  mainFont  p_100 mainTextCenter"  maxlength="3" name="essnt"  type="text" value="" placeholder=""  /></div><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Price:</h2><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">3. Your total asking price ($): </span></p><input id="u_upfront" onkeypress="return isNumber(event)" onkeyup="inputKeyUp(event,jQuery(this))" readonly class="p_tet  noPadding  mainFont p_100 mainTextCenter" maxlength="9" name="u_upfront" required="" type="text" value="" placeholder=" " /></div><div class="cHalf" id="alert_message" style="display:none"></div><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Market:</h2><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">4. Number of expected licensees (total):</span></p><input id="n_lic" readonly onkeypress="return isNumber(event)" onchange="inputKeyUp(event,jQuery(this))"  class="p_tet noPadding  mainFont p_100 mainTextCenter" maxlength="3" name="n_lic" type="text" value="" placeholder=" " /></div><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">5.Relating Technologies: </span></p><input id="relatedTechnologies" class="tertext mainFont" name="Technologies" type="text" value="" readonly/></div><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">6.Relating Standards: </span></p><input id="Standards" readonly class="tertext mainFont" name="Standards"  type="text" value="" /></div><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">7.Relating Markets: </span></p><input id="Markets" readonly class="tertext mainFont" name="Markets"  type="text" value="" /></div><div class="cHalf"><p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">8.Relating Products: </span></p><input id="Products" readonly class="tertext mainFont" name="Products"  type="text" value="" /></div><div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 100% !important;"><span class="mainColor mainFont">9. Potential interested licensees under your patents:</span></p></div><div id="TaxtBoxesGroup" style="margin-bottom: 5px !important;"><div id="TaxtBoxesGroup1"><div class="cHalf"><input readonly id="" class="tertext mainFont" name="n_name[]"  type="text" value="" placeholder="Name" /></div><div class="cHalf"><input id="" class="tertext mainFont" readonly name="r_lice[]"  type="text" value="" placeholder="Reason it may desire a license" /></div><div class="patenteespara mainColor mainWidth mainMarginBottom mainFont">Is there existing Evidence of Use?<input id="" style="padding-top: 0px; margin-bottom:3px;margin-left:8px;margin-right:3px;" name="evidence_e1" type="radio" value="No" />NO<input id="" style="padding-top: 0px; margin-bottom: 3px;margin-left:10px;margin-right:3px;" name="evidence_e1" type="radio" value="Yes" />YES, here is a link</div><div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 181px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" class="tertext mainFont" readonly style="width: 260px !important;" name="r_link[]" type="text" value="" placeholder=""></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;"></div></div></div><h2 class="headingPOP1 mainColor">Seller’s Info:</h2><div class="cHalf"><input id="companyname" class="tertext mainFont" name="companyname"  type="text" value="" placeholder="Seller’s Name " readonly/></div><div class="cHalf"><input id="Broker" class="tertext mainFont" name="Broker"  type="text" value="" placeholder="Broker " readonly/></div><div class="cHalf"><input id="fname" class="tertext mainFont" name="fname"  type="text" value="" placeholder="First Name " /></div><div class="cHalf"><input id="lname" readonly class="tertext mainFont" name="lname"  type="text" value="" placeholder="Last Name " /></div><div class="cHalf"><input type="text" name="email" readonly value="" class="tertext mainFont" id="email" required   placeholder="Email Address"></div><div class="cHalf"><textarea readonly class="tertext" id="address" style="width: 100%; height: 100px !important; padding: 10px;" cols="4" name="address" rows="4" placeholder="Note"></textarea></div></div>';counterPatent=1;jQuery("#patentees_data").html(__HTMLL);if(b!=null&&typeof b.popup_type!="undefined"&&parseInt(b.popup_type)==4){jQuery("#TextBoxesGroup").before("<a target='_BLANK' href='http://www.synpat.com/sellerform/?sr="+snp+"' class='btn btn-primary btn-block'>Synpat.com</a>");_anotherLicense=jQuery.parseJSON(b.other_field);if(_anotherLicense.patent_list!=undefined){jQuery("#patentNumbers").empty();for(i=0;i<_anotherLicense.patent_list.length;i++){var a=$("<div/>").attr("id","patentNumbers"+counterPatent);_country=_anotherLicense.patent_list[i].country;_application=_anotherLicense.patent_list[i].application;_patent=_anotherLicense.patent_list[i].patent;a.html(' <div class="cHalf" ><input type="text" name="country_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:135px !important;" id="textbox'+counterPatent+'" value="'+_country+'"    placeholder="Country" readonly > <input type="text" name="patent_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:135px !important;" readonly value="'+_patent+'"   id="textbox'+counterPatent+'"    placeholder="Patents"  > <input type="text" name="application_n[]" readonly class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:135px !important;" value="'+_application+'"   id="textbox'+counterPatent+'"    placeholder="Applications"  ></div>');jQuery("#patentNumbers").append(a);if(jQuery("#patentNumbers").find(".rem").length>1){jQuery("#patentNumbers").find(".rem").css("display","inline-block")}counterPatent++}}jQuery("#n_patents").val(_anotherLicense.n_patents);jQuery("#essnt").val(_anotherLicense.essnt);jQuery("#n_lic").val(_anotherLicense.n_lic);jQuery("#u_upfront").val(_anotherLicense.u_upfront);jQuery("#relatedTechnologies").val(_anotherLicense.technologies);jQuery("#Standards").val(_anotherLicense.standards);jQuery("#Markets").val(_anotherLicense.markets);jQuery("#Products").val(_anotherLicense.products);if(_anotherLicense.another_license.length>0){counter=1;jQuery("#TaxtBoxesGroup").empty();for(i=0;i<_anotherLicense.another_license.length;i++){jQuery("#TaxtBoxesGroup").find(".adem").css("display","none");var a=$("<div/>").attr("id","TaxtBoxesGroup"+counter);_name=_anotherLicense.another_license[i].name;_r_lice=_anotherLicense.another_license[i].lice;ychecked="";nchecked="";_link="";if(_anotherLicense.another_license[i].link!=undefined){_link=_anotherLicense.another_license[i].link;}if(_anotherLicense.another_license[i].evidence=="No"){nchecked="CHECKED='CHECKED'"}if(_anotherLicense.another_license[i].evidence=="Yes"){ychecked="CHECKED='CHECKED'"}a.html(' <div class="cHalf" ><input type="text" readonly name="n_name[]" value="'+_name+'" class="tertext mainFont" id="textbox'+counter+'"    placeholder="Name "  ></div><div class="cHalf"> <input type="text" readonly name="r_lice[]" value="'+_r_lice+'" class="tertext mainFont" id="textbox'+counter+'"    placeholder="Reason it may desire a license "  ></div><div class="cHalf patenteespara mainColor mainWidth mainMarginBottom mainFont" >Is there existing Evidence of Use?<input type="radio" name="evidence_e'+counter+'" value="No" '+nchecked+' class="" id="" style="margin-bottom:3px;margin-left:8px;margin-right:3px" >NO<input type="radio" name="evidence_e'+counter+'" '+ychecked+' value="Yes" class="" id="" style="margin-bottom:3px;margin-left:10px;margin-right:3px;">YES, here is a link</div><div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 181px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="textbox1" class="tertext mainFont" style="width: 260px !important;" name="r_link[]" type="text" value="'+_link+'" placeholder=""></div>');jQuery("#TaxtBoxesGroup").append(a);counter++}}jQuery("#companyname").val(b.type_name);jQuery("#Broker").val(b.broker);jQuery("#fname").val(b.first_name);jQuery("#lname").val(b.last_name);jQuery("#email").val(b.email_address);jQuery("#address").val(b.note)}}

	function leadRunEntry(b,a){
		jQuery.ajax({
			type:"POST",
			url:__baseUrl+"dashboard/lead_login",
			data:{o:b,n:a},
			cache:false,
			success:function(e){}
		})
	}
	
	function threadDetail(a,b,c){
		_formOpen='';if(typeof _globalAjax=='object'){_globalAjax.abort();}
		jQuery('.actBtn').removeClass('active');jQuery("#activityType").val('');if(typeof c =='undefined'){jQuery("#displayEmail").empty();}jQuery('#aquisitionTable').find('tbody').empty();jQuery('#activityTable').find('tbody').empty();jQuery('#preSaleActivityTable').find('tbody').empty();mainLogBox=0;__dashFlag=false;$("#all_type_list tbody td").removeClass("active");$("#all_type_list tbody tr").removeClass("active");$(".DTFC_Cloned tbody td").removeClass("active");$(".DTFC_Cloned tbody tr").removeClass("active");$(".message-item").removeClass("message-active");$("#other_list_boxes .message td").removeClass("active");if(a.attr("data-id")!=undefined){g=a.attr("data-id");t=a.attr("data-type");a.parent().find("tr").removeClass("active");_inde=a.index();if(jQuery("#search_lead_box").is(":visible")){jQuery("#all_type_list").find("tbody").find("tr").each(function(index){if(jQuery(this).attr("data-id")==g){_inde = index;jQuery(this).addClass("active")}})}$(".DTFC_Cloned tbody tr").eq(_inde).addClass('active');$("#all_type_list tbody tr").eq(_inde).addClass("active");}else{g=a.parent().parent().attr("data-id");t=a.parent().parent().attr("data-type");_inde=a.parent().parent().parent().index();$(".DTFC_Cloned tbody tr").eq(_inde).addClass('active');$("#all_type_list tbody tr").eq(_inde).addClass("active");}if(___FLAG===1){if(jQuery("#from_litigation").is(":visible")){submitData()}else{if(jQuery("#from_regular").is(":visible")){submitDataMarket()}else{if(jQuery("#from_nonacquistion").is(":visible")){submitFromData("from_nonacquistion")}}}}___FLAG=0;leadRunEntry(leadGlobal,g);jQuery("#search_lead_box").css("display","none");if(jQuery("#all_type_list_wrapper>.row").css("display")=="block"){if(jQuery("#all_type_list").find("tbody").find("tr.active").length>0){jQuery("#all_type_list").find("tbody").html(__TableDT);
	tableDT=$("#all_type_list").DataTable({
		destroy:true,
		scrollY:"270px",
		scrollX:true,
		scrollCollapse:true,
		searching:true,
		paging:false,
		fixedColumns: {leftColumns:1},
		fnInitComplete:function(j,e) {
			jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");
			_index=jQuery("#all_type_list").find("tbody").find('tr[data-id="'+g+'"]').index();
			jQuery("#all_type_list").find("tbody").find("tr").eq(_index).addClass("active");
			jQuery(".DTFC_Cloned").find("tbody").find("tr").eq(_index).addClass("active");
			_scrollTop=jQuery("#all_type_list").find("tbody").find("tr.active").offset();
			if(jQuery("#dashboard_charts").is(":visible")){
				jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-263.5)
			} else {
				jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106)
			}

			removeDTResizeEvents();
			runTableLeadBal();
		},
		fnDrawCallback: function() {
			checkMyEmailsHeight();
		}
	})
	

}}jQuery(".messages-list-leads").find(".message-item").each(function(){jQuery(this).removeClass("message-active");jQuery(this).find("h5").addClass("c-dark");jQuery(this).find("h4").addClass("c-dark");jQuery(this).find("p").addClass("c-gray");jQuery(this).find("a").addClass("c-dark")});jQuery(".message-detail-title").css("display","none");jQuery(".message-detail-right").hide();jQuery("#subject").empty();jQuery(".message-detail-subject").empty();if(typeof(b)==="undefined"){jQuery("#other_list_boxes").empty()}jQuery("#other_list_boxes").find("table").find("tr").removeClass("activetr");jQuery(".message_detail").empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;"><img src="'+__baseUrl+'public/images/ajax-loader.gif" alt=""></div>');if(jQuery("#marketLead").length>0){jQuery("#marketLead").get(0).reset()}if(jQuery("#leadForm").length>0){jQuery("#leadForm").get(0).reset()}if(jQuery("#formLitigation").length>0){jQuery("#formLitigation").get(0).reset()}if(jQuery("#myDashboardComposeEmails").length>0 && !jQuery('#gmail_message_modal').is(':visible')){jQuery("#myDashboardComposeEmails").get(0).reset()}jQuery("#gmail_message_modal").hide();jQuery("#gmail_message_modal").find("#attach_droppable").empty();jQuery("#from_regular").find("#litigation_doc_list").empty();jQuery("#from_litigation").find("#litigation_doc_list").empty();jQuery("#from_nonacquistion").find("#litigation_doc_list").empty();mainIndex=jQuery("#all_type_list").find("tbody").find("tr.active").index();jQuery(".pager-text").html((mainIndex+1)+"/"+totalCC);if(parseInt(g)>0){jQuery("#bottom_form_market").css("display","block");jQuery("#from_regular,#from_litigation,#from_nonacquistion").css("display","none");jQuery.ajax({type:"POST",url:__baseUrl+"leads/findBoxList",data:{boxes:g,t:t},cache:false,success:function(H){jQuery("#scrap_patent_data").find("tbody").empty();jQuery("#bottom_form_market").css("display","none");_data=jQuery.parseJSON(H);_lT=_data;if(_data.detail.length>0){$("#dashboard-page").append('<div id="dashboardPageOverlay" style="background:#ffffff; height:100%; left: 0; position: absolute; top: 0; width: 100%"></div>');setTimeout(function(){$("#dashboardPageOverlay").remove()},500);snapGlobal=_data.detail[0].litigation.file_url;console.log(snapGlobal);_st="<?php echo $this->session->userdata['type'];?>";_sp="<?php echo $this->session->userdata['id'];?>";leadGlobal=_data.detail[0].litigation.id;snp=_data.detail[0].litigation.serial_number;leadNameGlobal=_data.detail[0].litigation.lead_name;snapGlobalFileID=_data.detail[0].litigation.spreadsheet_id;snapGlobalFileWorkID = _data.detail[0].litigation.worksheet_id;snapGlobalFileWorkName = _data.detail[0].litigation.worksheet_name;jQuery(".topbar-lead-name").css("width","440px;").html(leadNameGlobal);jQuery("#from_regular").find("#serialNumber").html(_data.detail[0].litigation.serial_number);jQuery("#from_nonacquistion").find("#serialNumber").html(_data.detail[0].litigation.serial_number);jQuery("#from_litigation").find("#serialNumber").html(_data.detail[0].litigation.serial_number);if(typeof _data.detail[0].acquisitions.store_name!='undefined'){jQuery('#btnLinkToDocket').attr('href','http://synpat.com/store/'+_data.detail[0].acquisitions.store_name).attr('target','_BLANK');} else {jQuery('#btnLinkToDocket').attr('href','javascript://').removeAttr('target');}if(typeof _data.detail[0].portfolio!='undefined'){jQuery('#btnLinkToStore').attr('href','http://synpat.com'+_data.detail[0].portfolio).attr('target','_BLANK');} else {jQuery('#btnLinkToDocket').attr('href','javascript://').removeAttr('target');}
<?php 
	$openDocket = true;
	if((int)$this->session->userdata['type']!=9){
		if(!in_array(2,$this->session->userdata['modules_assign'])){
			$openDocket = false;
		}
	}
	if($openDocket===true):?>
		if(jQuery("#open_opportunity").is(":visible")){jQuery("#docket_frame").html('<iframe width="100%" height="600px" scrolling="yes" src="'+__baseUrl+'opportunity/dummy_opportunity/'+leadGlobal+'"></iframe>');jQuery("#open_opportunity").find("#docketLabel").html("Docket - "+leadNameGlobal)}
<?php endif;?>
<?php 
	$openEOU = true;
	if((int)$this->session->userdata['type']!=9){
		if(!in_array(20,$this->session->userdata['modules_assign'])){
			$openEOU = false;
		}
	}
	if($openEOU===true):?>
		if(jQuery("#open_eou_folder").is(":visible")){jQuery("#eou_frame").html('<iframe width="100%" height="600px" scrolling="yes" src="'+__baseUrl+'opportunity/eou_in_folder/'+leadGlobal+'"></iframe>')}
	<?php endif;?>
	<?php 
		$openTimeline = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(7,$this->session->userdata['modules_assign'])){
				$openTimeline = false;
			}
		}
		if($openTimeline===true):?>
		var I="";$('#activitySlidebarTable').DataTable().destroy();
		_dataTimeLine=_data.detail[0].timeLine;for(i=0;i<_dataTimeLine.length;i++){switch(jQuery.trim(_dataTimeLine[i].message)){case"PPA drafted":_draft_ppa_date=_dataTimeLine[i].create_date;break;case"Executed PPA":_executed_ppa=_dataTimeLine[i].create_date;break;case"PPA executed":_executed_ppa=_dataTimeLine[i].create_date;break;case"Send request to CIPO for uploading damages report.":_upload_damage_report=_dataTimeLine[i].create_date;break;case"Send request to CIPO to start work on DD":_cipo_start_work_dd=_dataTimeLine[i].create_date;break;case"NDA created":_nda_created=_dataTimeLine[i].create_date;break;case"NDA approved":_nda_approved=_dataTimeLine[i].create_date;break;case"Execute NDA by PD":_nda_execute=_dataTimeLine[i].create_date;break;case"CIPO approved NDA":_nda_approved=_dataTimeLine[i].create_date;break;case"Send request to CIPO for NDA approval":_send_req_nda_approval=_dataTimeLine[i].create_date;break;case"EOU confirmed":_eou_confirmed=_dataTimeLine[i].create_date;break;case"NDA shared with Sellers":_nda_shared=_dataTimeLine[i].create_date;break;case"Insert list of Assets":_list_of_assets_send=_dataTimeLine[i].create_date;break;case"CIPO Approved assets.":_list_of_assets_approve=_dataTimeLine[i].create_date;break;case"Drafted PLA":_draft_pla=_dataTimeLine[i].create_date;break;case"Drafted Participant":_draft_participant=_dataTimeLine[i].create_date;break}if(_dataTimeLine[i].hasOwnProperty("leadType")){switch(_dataTimeLine[i].leadType){case"Litigation":_colorClass="bg-yellow";break;case"Market":_colorClass="bg-green";break;case"General":_colorClass="label-info";break;case"SEP":_colorClass="bg-warning";break}_colorClass="";_label="";_label=(_dataTimeLine[i].lead_name!="")?_dataTimeLine[i].lead_name:_dataTimeLine[i].plantiffs_name}I+='<tr><td  onclick="openLeadDetail('+_dataTimeLine[i].lead_id+')">'+_label+'</td><td>'+_dataTimeLine[i].name+'</td><td>'+_dataTimeLine[i].message+'</td><td>'+moment(new Date(_dataTimeLine[i].create_date)).format("MMM D, YYYY")+'</td></tr>'}jQuery("#activity").find('table').find('tbody').html(I);$('#activitySlidebarTable').find('thead').html('<tr><th>Lead</th><th>Person</th><th>Activity</th><th>Date</th></tr>');$('#activitySlidebarTable').DataTable({
													"searching":false,
													"autoWidth": true,
													"paging": false,
													"sScrollY": $(window).height() - 130 + 'px',
													"sScrollX": "100%",
													"sScrollXInner": "100%"
												});
		<?php endif;?>
		jQuery("#taskType").val(_data.detail[0].litigation.type);
		<?php 
		$openTask = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(6,$this->session->userdata['modules_assign'])){
				$openTask = false;
			}
		}
		if($openTask===true):?>
		var K="";_dataTask=_data.detail[0].task;for(i=0;i<_dataTask.length;i++){_colors_array="";switch(_dataTask[i].type){case"Litigation":_colors_array="bg-yellow";break;case"Market":case"NON":case"INT":_colors_array="bg-green";break;case"General":_colors_array="label-info";break;case"SEP":_colors_array="bg-warning";break}if(parseInt(_dataTask[i].userType)==9&&_dataTask[i].approved_type=="LEAD"){_userName="System"}else{_userName=_dataTask[i].userName}_img='<img title="'+_dataTask[i].uuserName+'" src="'+__baseUrl+'public/upload/user.png" width="28" />';if(_dataTask[i].profile_pic!=""){_img='<img title="'+_dataTask[i].uuserName+'" src="'+_dataTask[i].profile_pic+'" width="28" />'}_leadName=(_dataTask[i].lead_name!="")?_dataTask[i].lead_name:_dataTask[i].plantiffs_name;K+='<li><a href="javascript://" onclick="approvedFile('+_dataTask[i].approved_id+')"><span class="tl-label bs-label '+_colors_array+'">'+_leadName+'</span><span class="todo-container"><span class="todo-content" for="todo-1" title="'+_dataTask[i].subject+'">'+_dataTask[i].subject+'</span><span class="todo-footer clearfix"><span class="todo-footer-dateuser"> '+moment(new Date(_dataTask[i].taskCreateDate)).format("MMM D, YYYY")+"	&nbsp;&nbsp;&nbsp;"+_userName+"</span>"+_img+"</span></span></a></li>"}
		jQuery("#task_list").find("ul.todo-box").html(K);_dataTask=_data.detail[0].task_i;if(_dataTask.length>0){jQuery("#my_c_task_list").find("ul.todo-box").empty();K="";for(i=0;i<_dataTask.length;i++){_colors_array="";switch(_dataTask[i].type){case"Litigation":_colors_array="bg-yellow";break;case"Market":case"NON":case"INT":_colors_array="bg-green";break;case"General":_colors_array="label-info";break;case"SEP":_colors_array="bg-warning";break}if(parseInt(_dataTask[i].userType)==9&&_dataTask[i].approved_type=="LEAD"){_userName="System"}else{_userName="<?php echo $this->session->userdata['name']?>"}_img='<img title="'+_dataTask[i].userName+'" src="'+__baseUrl+'public/upload/user.png" width="28" />';if(_dataTask[i].profile_pic!=""){_img='<img title="'+_dataTask[i].userName+'" src="'+_dataTask[i].profile_pic+'" width="28" />'}_leadName=(_dataTask[i].lead_name!="")?_dataTask[i].lead_name:_dataTask[i].plantiffs_name;K+='<li><a href="javascript://" onclick="approvedFile('+_dataTask[i].approved_id+')"><span class="tl-label bs-label '+_colors_array+'">'+_leadName+'</span><span class="todo-container"><span class="todo-content" for="todo-1" title="'+_dataTask[i].subject+'">'+_dataTask[i].subject+'</span><span class="todo-footer clearfix"><span class="todo-footer-dateuser"> '+moment(new Date(_dataTask[i].taskCreateDate)).format("MMM D, YYYY")+"	&nbsp;&nbsp;&nbsp;"+_userName+"</span>"+_img+"</span></span></a></li>"}jQuery("#my_c_task_list").find("ul.todo-box").html(K)}else{jQuery("#my_c_task_list").find("ul.todo-box").empty()}
	<?php endif;?>
	_senddt="<?php echo $this->session->userdata['id'];?>";
	_leadCompaniesAssignBroker=_data.detail[0].broker_as_companies;
	<?php 
		$openTask = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(4,$this->session->userdata['modules_assign'])){
				$openTask = false;
			}
		}
		if($openTask===true):?>
		if(typeof _data.detail[0].patentees!="undefined"){_ddata=_data.detail[0].patentees;updatePatenteesData(_ddata)}
		jQuery("#commentComment1").val("");jQuery("#commentComment2").val("");jQuery("#commentComment3").val("");jQuery("#leadType").val(_data.detail[0].litigation.type);jQuery("#commentID").val("0");jQuery("select[name='attractiveness']").find("option").each(function(){jQuery(this).removeAttr("SELECTED")});if(jQuery("#btnComments:hidden").length>0){jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>")}else{jQuery("#btnComments").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>")}if(_data.detail[0].comment.length>0){_tr="";jQuery("#btnComments:hidden").find("table").find("tbody").html("");for(i=0;i<_data.detail[0].comment.length;i++){_flag=0;_comment=_data.detail[0].comment[i];if(_comment.user_id==_senddt){_flag=1;_commentID=_comment.id;_commentText1=_comment.comment1;_commentText2=_comment.comment2;_commentText3=_comment.comment3;_commentAttractive=_comment.attractive;jQuery("#commentComment1").val(_commentText1);jQuery("#commentComment2").val(_commentText2);jQuery("#commentComment3").val(_commentText3);jQuery("#commentId").val(_commentID);jQuery("select[name='attractiveness']").find("option").each(function(){if(jQuery(this).attr("value")==_commentAttractive){jQuery(this).attr("SELECTED","SELECTED")}else{jQuery(this).removeAttr("SELECTED")}})}if(_flag==0){createdDate="";u="";if(_comment.created==null||_comment.created=="0000-00-00 00:00:00"){createdDate=""}else{createdDate=moment(new Date(_comment.created)).format("MM-D-YY")}if(_comment.updated==null||_comment.updated=="0000-00-00 00:00:00"){u=""}else{var u=moment(new Date(_comment.updated)).format("MM-D-YY")}comment1=nl2br(_comment.comment1);comment2=nl2br(_comment.comment2);comment3=nl2br(_comment.comment3);_tr="<tr><td>"+comment1.linkify()+" </td><td>"+comment2.linkify()+" </td><td>"+comment3.linkify()+' </td><td><span class="label alert">'+_comment.attractive+"</span></td>	<td>"+createdDate+"</td><td>"+u+"</td><td>"+_comment.name+"</td></tr>";if(jQuery("#btnComments:hidden").length>0){jQuery("#btnComments:hidden").find("table").find("tbody").append(_tr)}else{jQuery("#btnComments").find("table").find("tbody").append(_tr)}}if(_data.detail[0].comment.length==1&&_flag==1){jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>")}}}else{jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>")}		
		<?php endif;
		$openLeadDT = true;
		$openPatentTable = true;
		$openLitigationTable = true;
		if((int)$this->session->userdata['type']!=9){												
			if(!in_array(12,$this->session->userdata['modules_assign'])){
				$openLeadDT = false;
			} 
		}
		if((int)$this->session->userdata['type']!=9){												
			if(!in_array(16,$this->session->userdata['modules_assign'])){
				$openPatentTable = false;
			} 
		}
		if((int)$this->session->userdata['type']!=9){												
			if(!in_array(19,$this->session->userdata['modules_assign'])){
				$openLitigationTable = false;
			} 
		}
		if((int)$this->session->userdata['user']['type']==9 || in_array(15,$this->session->userdata['modules_assign'])):?>
			salesActivityList(_data.detail[0].sales_activity);
		<?php endif;
		if((int)$this->session->userdata['user']['type']==9 || in_array(25,$this->session->userdata['modules_assign'])):?>
			preSalesActivityList(_data.detail[0].presales_activity);
		<?php endif;?>			
		if(_data.detail[0].litigation.type!="Litigation"&&_data.detail[0].litigation.type!="NON"&&_data.detail[0].litigation.type!="INT"){_mainButtonParentElement="from_regular"}else{if(_data.detail[0].litigation.type=="NON"||_data.detail[0].litigation.type=="INT"){_mainButtonParentElement="from_nonacquistion"}else{if(_data.detail[0].litigation.type=="Litigation"){_mainButtonParentElement="from_litigation"}}}
		<?php if($openLeadDT===true):?>
		if(_data.detail[0].litigation.type!="Litigation"&&_data.detail[0].litigation.type!="NON"&&_data.detail[0].litigation.type!="INT"){jQuery("#from_litigation").hide();jQuery("#from_nonacquistion").hide();if(jQuery('#btnFormCollapse').hasClass('menu-active')){jQuery("#from_regular").show();}_mainButtonParentElement="from_regular"}else{if(_data.detail[0].litigation.type=="NON"||_data.detail[0].litigation.type=="INT"){jQuery("#from_litigation").hide();if(jQuery('#btnFormCollapse').hasClass('menu-active')){jQuery("#from_nonacquistion").show();}jQuery("#from_regular").hide();_mainButtonParentElement="from_nonacquistion"}else{if(_data.detail[0].litigation.type=="Litigation"){if(jQuery('#btnFormCollapse').hasClass('menu-active')){jQuery("#from_litigation").show();}jQuery("#from_regular").hide();jQuery("#from_nonacquistion").hide();_mainButtonParentElement="from_litigation"}}}
		<?php endif;?>
		_leadPatent=_data.detail[0].litigation.patent_data;jQuery("#mytimeline").empty();_createDD=moment(new Date(_data.detail[0].litigation.create_date)).format("D.MM.YYYY");$("#timeline-html-wrap:hidden").empty();
		jQuery("#from_nonacquistion,#from_regular,#from_litigation").find(".button-list").empty();
		<?php if($openLeadDT===true):?>
		for(bt=0;bt<_data.detail[0].buttons.length;bt++){
			_aC="";if(jQuery("#"+_mainButtonParentElement).find(".button-list").find("div.row").length>0){_aC="mrg5T"}switch(_data.detail[0].buttons[bt].button_id){
			case "DRIVE":_statusMessage="";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}_refrence=_data.detail[0].buttons[bt].reference_id;jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="drive_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
			if(_data.detail[0].buttons[bt].btnStatus=="0"||_data.detail[0].buttons[bt].renewable=="1"){
				jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="driveMode('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"','"+_refrence+"',"+_data.detail[0].buttons[bt].send_task+");\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
			}else{
				if(_data.detail[0].buttons[bt].blink==1){
					jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth btn-blink" data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="btnModeStatus('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
				} else {
					if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> <span class="date-style">'+moment(new Date(_data.detail[0].buttons[bt].update_date)).format("MM-D-YY")+"</span> "+_statusMessage)}}
			}
			break;
			case "EMAIL":
			_statusMessage="";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}_refrence=_data.detail[0].buttons[bt].reference_id;jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="email_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
			if(_data.detail[0].buttons[bt].btnStatus=="0"||_data.detail[0].buttons[bt].renewable=="1"){jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="emailMode('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"','"+_refrence+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
			}else{
				if(_data.detail[0].buttons[bt].blink==1){
					jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth btn-blink" data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="btnModeStatus('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
				} else {
					if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> <span class="date-style">'+moment(new Date(_data.detail[0].buttons[bt].update_date)).format("MM-D-YY")+"</span> "+_statusMessage)}
				}												
			}
		break;
		case "TASK":
		_statusMessage="";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}_refrence=_data.detail[0].buttons[bt].reference_id;jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="task_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
		if(_data.detail[0].buttons[bt].btnStatus=="0"||_data.detail[0].buttons[bt].renewable=="1"){jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="taskMode('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"','"+_refrence+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
		}else{
			if(_data.detail[0].buttons[bt].blink==1){
				jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth btn-blink" data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="btnModeStatus('+_data.detail[0].buttons[bt].id+",'"+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
			} else {
				if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html(_data.detail[0].buttons[bt].status_message_fill);
				}else{
					jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i>  <span class="date-style">'+moment(new Date(_data.detail[0].buttons[bt].update_date)).format("MM-D-YY")+"</span> "+_statusMessage);
				}
			}												
		}
		break;
		case "SELLER":
		_statusMessage="Seller Info Done";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="assign_task_market"></div></div>');if(_data.detail[0].litigation.seller_info==1){jQuery("#"+_mainButtonParentElement).find("#assign_task_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth btn-blink" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="assign_task_mode(2,\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
		}else{
			if(_data.detail[0].litigation.seller_info==2){if(_data.detail[0].litigation.seller_info_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.seller_info_text==null){jQuery("#"+_mainButtonParentElement).find("#assign_task_market").html(_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#assign_task_market").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.seller_info_text)).format("MM-D-YY")+"</span> "+_statusMessage);}
			}else{
				if(_data.detail[0].litigation.seller_info==0){jQuery("#"+_mainButtonParentElement).find("#assign_task_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="assign_task_mode(1,\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
			}
		}
	break;
	case "SELLER_IS_INTERSTED":_statusMessage="Seller like the deal";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="seller_deal_for_market"></div></div>');if(_data.detail[0].litigation.seller_like!=""&&_data.detail[0].litigation.seller_like!=null){jQuery("#"+_mainButtonParentElement).find("#seller_deal_for_market").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.seller_like)).format("MM-D-YY")+"</span> "+_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#seller_deal_for_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="sellerInterested(\''+_mainButtonParentElement+"')\"><b>"+_data.detail[0].buttons[bt].name+'</b></a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
	break;
	case "FUNDING":_statusMessage="Funding Successful";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="funding_successful"></div></div>');if(_data.detail[0].litigation.funding_trnsfr!=""&&_data.detail[0].litigation.funding_trnsfr!=null){jQuery("#"+_mainButtonParentElement).find("#funding_successful").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.funding_trnsfr)).format("MM-D-YY")+"</span> "+_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#funding_successful").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="fundingTransfer(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_funding_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
	break;
	case "CLAIM_ILLUS":_statusMessage="Claim Illustration done";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}if(_data.detail[0].litigation.claim_illus!=null){_statusMessage=moment(new Date(_data.detail[0].litigation.claim_illus)).format("MM-D-YY")+" "+_statusMessage}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="claim_illus"></div></div>');
	if(_data.detail[0].litigation.claim_status_dd=="1"&&_data.detail[0].buttons[bt].renewable=="0"){jQuery("#"+_mainButtonParentElement).find("#claim_illus").removeClass("btn").removeClass("btn-mwidth").addClass("btn-blink").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="claimIllusStatusChange(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_claim_illus_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
	}else{
		if(_data.detail[0].litigation.claim_status_dd=="2"&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#claim_illus").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{jQuery("#"+_mainButtonParentElement).find("#claim_illus").addClass("staRenewalAction").removeClass("btn-blink").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}
		}else{jQuery("#"+_mainButtonParentElement).find("#claim_illus").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="claimIllus(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_claim_illus_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
	}
	break;
	case "TECHNICAL_DD":_statusMessage="Techinical Due Dilligence Done";
	if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}if(_data.detail[0].litigation.technical_dd!=null){_statusMessage="<span class='date-style'>"+moment(new Date(_data.detail[0].litigation.technical_dd)).format("MM-D-YY")+"</span> "+_statusMessage}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="technical_dd"></div></div>');
	if(_data.detail[0].litigation.technical_status_dd=="1"&&_data.detail[0].buttons[bt].renewable=="0"){jQuery("#"+_mainButtonParentElement).find("#technical_dd").removeClass("btn").removeClass("btn-mwidth").addClass("btn-blink").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="technicalStatusChange(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
	}else{
		if(_data.detail[0].litigation.technical_status_dd=="2"&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#technical_dd").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{jQuery("#"+_mainButtonParentElement).find("#technical_dd").addClass("staRenewalAction").removeClass("btn-blink").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}
		}else{jQuery("#"+_mainButtonParentElement).find("#technical_dd").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="technicalDD(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
	}
	break;
	case "LEGAL_DD":_statusMessage="Legal Due Dilligence Done";
	if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}if(_data.detail[0].litigation.legal_dd!=null){_statusMessage=moment(new Date(_data.detail[0].litigation.legal_dd)).format("MM-D-YY")+" "+_statusMessage}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="legal_dd"></div></div>');
	if(_data.detail[0].litigation.legal_status_dd=="1"&&_data.detail[0].buttons[bt].renewable=="0"){jQuery("#"+_mainButtonParentElement).find("#legal_dd").removeClass("btn").removeClass("btn-mwidth").addClass("btn-blink").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="legalStatusChange(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_legal_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
	}else{
		if(_data.detail[0].litigation.legal_status_dd=="2"&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#legal_dd").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{jQuery("#"+_mainButtonParentElement).find("#legal_dd").addClass("staRenewalAction").removeClass("btn-blink").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}
		}else{jQuery("#"+_mainButtonParentElement).find("#legal_dd").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="legalDD(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_legal_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
		}
	}
	break;
	case"PROPOSAL":break;
	case"PATENT_LIST":
		_statusMessage="Patent Spreadsheet created";
		if(_data.detail[0].buttons[bt].status_message!=""){
			_statusMessage=_data.detail[0].buttons[bt].status_message
		}
		jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="create_patent_list_market"></div></div>');
		if(_data.detail[0].litigation.create_patent_list==1&&_data.detail[0].buttons[bt].renewable=="0"){
				jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth btn-blink" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="spreadsheet_box_mode(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_patent_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
		}else{
			if(_data.detail[0].litigation.create_patent_list==2&&_data.detail[0].buttons[bt].renewable=="0"){
				if(_data.detail[0].buttons[bt].status_message_fill!=""){
					jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").addClass("staRenewalAction").html(_data.detail[0].buttons[bt].status_message_fill);
				}else{
					if(_data.detail[0].litigation.create_patent_list_text==null||_data.detail[0].litigation.create_patent_list_text=="0000-00-00 00:00:00"){
						jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").addClass("staRenewalAction").removeClass("btn").removeClass("btn-mwidth").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)
					}else{
						jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass("btn").removeClass("btn-mwidth").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+'<span class="date-style">'+moment(new Date(_data.detail[0].litigation.create_patent_list_text)).format("MM-D-YY")+"</span> "+_statusMessage);
					}
				}
			}else{
				if(_data.detail[0].litigation.create_patent_list==0||_data.detail[0].buttons[bt].renewable=="1"){
					jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default renewable btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="spreadsheet_box_mode(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_patent_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
				}
			}
		}
		break;
		case "REVIEW":
			_statusMessage="Review";
			if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="forward_to_review_market"></div></div>');
			if(_data.detail[0].litigation.complete==1||_data.detail[0].litigation.complete==2){if(_data.detail[0].litigation.forward_to_review_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.forward_to_review_text==null){jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").html(_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format("MM-D-YY")+"</span> "+_statusMessage);}
			}else{jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" onclick="forward_to_review_mode(\''+_mainButtonParentElement+'\')" href="javascript://">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_review_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" data-original-title="Processing Importing Data" style="color:#222222;"></div>')
			}
		break;
		case"SCHEDULE":break;
		case"NDA_TERMSHEET":_statusMessage="NDA and TermSheet created";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="ndaTermSheetMarket"></div></div>');if(_data.detail[0].litigation.nda_term_sheet==1&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{if(_data.detail[0].litigation.nda_term_sheet_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.nda_term_sheet_text==null){jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+'<span class="date-style">'+moment(new Date(_data.detail[0].litigation.nda_term_sheet_text)).format("MM-D-YY")+"</span> "+_statusMessage);}}}else{if(_data.detail[0].litigation.nda_term_sheet==2&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{if(_data.detail[0].litigation.nda_term_sheet_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.nda_term_sheet_text==null){jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}else{jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+'<span class="date-style">'+moment(new Date(_data.detail[0].litigation.nda_term_sheet_text)).format("MM-D-YY")+"</span> "+_statusMessage);}}}else{if(_data.detail[0].litigation.nda_term_sheet==0||_data.detail[0].buttons[bt].renewable=="1"){jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript:void(0);" onclick="createPartNDATermsheetMode(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_NDA_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}}}
		break;
		case"APPROVED_LEAD":
		_statusMessage="Synpat like the deal";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="approved_lead"></div></div>');if(_data.detail[0].litigation.status==1||_data.detail[0].litigation.status==2){jQuery("#"+_mainButtonParentElement).find("#approved_lead").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.synpat_like)).format("MM-D-YY")+"</span> "+_statusMessage);}else{jQuery("#"+_mainButtonParentElement).find("#approved_lead").removeClass("btn").removeClass("btn-mwidth").html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript:void(0);" onclick="approvedLead(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_approved_lead" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>')}
		break;
		case"EXECUTE_NDA":
		_statusMessage="NDA Executed successfully by CIPO";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="execute_nda"></div></div>');if(typeof(_data.detail[0].report)!="undefined"&&typeof(_data.detail[0].report.id)!="undefined"){if(_data.detail[0].report.executed_nda==0){jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="executeNDA(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_execute_nda" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Executing NDA" style="color:#222222;"></div>')}else{if(_data.detail[0].report.executed_nda==1){jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">'+moment(new Date(_nda_execute)).format("MM-D-YY")+"</span> Waiting for Admin to execute NDA"); }else{if(_data.detail[0].report.executed_nda==2&&_data.detail[0].report.nda_execute==0){jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">'+moment(new Date(_send_req_nda_approval)).format("MM-D-YY")+"</span> Waiting for CIPO to execute NDA")}else{if(_data.detail[0].report.executed_nda==2&&_data.detail[0].report.nda_execute==2){jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation._nda_approved)).format("MM-D-YY")+"</span> "+_statusMessage);}}}}}else{jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="executeNDA(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_execute_nda" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Executing NDA" style="color:#222222;"></div>')}
		break;
		case"EOU":
		_statusMessage="Seller EOU is in the Lead folder";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="seller_rou"></div></div>');if(typeof(_data.detail[0].report)!="undefined"&&typeof(_data.detail[0].report.id)!="undefined"){if(_data.detail[0].report.eou_folder==0){jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="eouConfirmation(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_eou" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Seller eou" style="color:#222222;"></div>')}else{if(_data.detail[0].report.eou_folder==2){if(typeof(_eou_confirmed)!="undefined"){jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<span class="date-style">'+moment(new Date(_eou_confirmed)).format("MM-D-YY")+"</span> "+_statusMessage);}else{jQuery("#"+_mainButtonParentElement).find("#seller_rou").html(_statusMessage)}}}}else{jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="eouConfirmation(\''+_mainButtonParentElement+'\')">Seller\'s EOU in Folder</a><div id="loader_seller_eou" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Seller eou" style="color:#222222;"></div>')}
		break;
		case "DRAFT_PPA":
		_statusMessage="PPA has been successfully drafted";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="draft_a_ppa"></div></div>');if((typeof(_data.detail[0].report)!="undefined"&&typeof(_data.detail[0].report.id)!="undefined")||_data.detail[0].litigation.ppa_id!=""&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].litigation.ppa_id!=""){jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.ppa_text_date)).format("MM-D-YY")+"</span> "+_statusMessage);}else{if(_data.detail[0].report.draft_a_ppa==0||_data.detail[0].buttons[bt].renewable=="1"){jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<a class="btn btn-default btn-mwidth renewable" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="draft_a_ppa(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>')}else{if(_data.detail[0].report.draft_a_ppa==2&&_data.detail[0].buttons[bt].renewable=="0"){if(_data.detail[0].buttons[bt].status_message_fill!=""){jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_data.detail[0].buttons[bt].status_message_fill)}else{if(typeof(_draft_ppa_date)!="undefined"){jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+'<span class="date-style">'+moment(new Date(_draft_ppa_date)).format("MM-D-YY")+"</span> "+_statusMessage);}else{jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass("staRenewalAction").html('<i class="glyph-icon icon-repeat"></i> '+_statusMessage)}}}}}}else{jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<a class="btn btn-default btn-mwidth renewable" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="draft_a_ppa(\''+_mainButtonParentElement+"')\">"+_data.detail[0].buttons[bt].name+'</a><div id="loader_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>')}
		break;
		case "EXECUTE_PPA":
		_statusMessage="PPA has successfully executed";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="execute_a_ppa"></div></div>');if((typeof(_data.detail[0].report)!="undefined"&&typeof(_data.detail[0].report.id)!="undefined")||_data.detail[0].litigation.execute_ppa!="0"){if(_data.detail[0].litigation.execute_ppa!="0"){if(typeof(_executed_ppa)!="undefined"){jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<span class="date-style">'+moment(new Date(_executed_ppa)).format("MM-D-YY")+"</span> "+_statusMessage);}else{jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html(_statusMessage)}}else{if(_data.detail[0].report.execute_ppa==0){jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="execute_ppa(\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-execute_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Execute a PPA" style="color:#222222;"></div>')}else{if(_data.detail[0].report.execute_ppa==1){jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<p class="label-after-btn is-blink"><i class="glyph-icon icon-caret-right"></i> <span>Waiting for CEO for execute PPA</span></p>')}else{if(_data.detail[0].report.execute_ppa>1&&(_data.detail[0].report.execute_ppa==2||_data.detail[0].report.execute_ppa==3)){if(typeof(_executed_ppa)!="undefined"){jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<span class="date-style">'+moment(new Date(_executed_ppa)).format("MM-D-YY")+"</span> "+_statusMessage);}else{jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html(_statusMessage);}}}}}}else{jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="execute_ppa(\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-execute_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Execute a PPA" style="color:#222222;"></div>')}
		break;
		case "PPA_EXECUTE":
		_statusMessage="PPA has successfully executed by CEO";if(_data.detail[0].buttons[bt].status_message!=""){_statusMessage=_data.detail[0].buttons[bt].status_message}jQuery("#"+_mainButtonParentElement).find(".button-list").append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="ppa_execute"></div></div>');if((typeof(_data.detail[0].report)!="undefined"&&typeof(_data.detail[0].report.id)!="undefined")||_data.detail[0].litigation.ppa_execute!="0"){if(_data.detail[0].litigation.ppa_execute!="0"){jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.ppa_date)).format("MM-D-YY")+"</span> "+_statusMessage);}else{if(typeof(_data.detail[0].report)!="undefined"){if(_data.detail[0].report.execute_ppa==2||_data.detail[0].report.execute_ppa==1){jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>')}else{if(_data.detail[0].report.execute_ppa==3){jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.ppa_date)).format("MM-D-YY")+"</span> "+_statusMessage);}}}else{jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\''+_mainButtonParentElement+"');\">"+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>')}}}else{jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\'from_regular\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>')}
		break;
	}
	}
	<?php endif; ?> 
	if(_data.detail[0].litigation.type!="Litigation"&&_data.detail[0].litigation.type!="NON"&&_data.detail[0].litigation.type!="INT"&&jQuery("#marketOwner").length>0){
	<?php if($openLeadDT===true):?>
		jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);jQuery("#marketProspects").val(_data.detail[0].litigation.no_of_prospects);jQuery("#marketExpectedPrice").val(_data.detail[0].litigation.upfront_price);jQuery("#marketProspectsName").val(_data.detail[0].litigation.prospects_name);jQuery("#marketlead_name").val(_data.detail[0].litigation.lead_name);jQuery("#marketTechnologies").val(_data.detail[0].litigation.technologies);jQuery("#marketNo_of_us_patents").val(_data.detail[0].litigation.no_of_us_patents);jQuery("#marketno_of_non_us_patents").val(_data.detail[0].litigation.no_of_non_us_patents);jQuery("#marketFileUrl").val(_data.detail[0].litigation.file_url);jQuery("#marketSellerInfo").val(_data.detail[0].litigation.seller_info);jQuery("#marketProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);jQuery("#marketCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);if(_data.detail[0].litigation.complete==1){if(_data.detail[0].litigation.forward_to_review_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.create_patent_list_text==null){jQuery("#forward_to_review").html(" Review")}else{jQuery("#forward_to_review").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format("MM-D-YY")+"</span> Review")}}_mainValue=_data.detail[0].litigation.market_data;jQuery("#marketMarketData").val(_mainValue);if(_mainValue!=""){marketData=_mainValue.split(",");if(marketData.length>0){$("#marketBoxList").empty();$("#marketBoxList").append("<ul class='todo-box-1'></ul>");for(mk=0;mk<marketData.length;mk++){$("#marketBoxList").find("ul.todo-box-1").append("<li>"+marketData[mk]+"</li>")}}}jQuery("#showSellerName").html(_data.detail[0].litigation.seller_contact);jQuery("#marketSellerContact").val(_data.detail[0].litigation.seller_contact);jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);jQuery("#sellerBtn").attr("onclick","openContactForFrom(1,'from_regular');");jQuery("#showBrokerFirm").html(_data.detail[0].litigation.broker_contact);jQuery("#optionExpirationDate").val(_data.detail[0].litigation.option_expiration_date);jQuery("#marketBrokerContact").val(_data.detail[0].litigation.broker_contact);jQuery("#marketBroker").val(_data.detail[0].litigation.broker);jQuery("#brokerFirmBtn").attr("onclick","openContactForFrom(2,'from_regular');");jQuery("#showBrokerPerson").html(_data.detail[0].litigation.broker_person_contact);jQuery("#marketBrokerPersonContact").val(_data.detail[0].litigation.broker_person_contact);jQuery("#marketBrokerPerson").val(_data.detail[0].litigation.broker_person);jQuery("#brokerPersonBtn").attr("onclick","openContactForFrom(3,'from_regular');");jQuery("#showNameFirst").html(_data.detail[0].litigation.person_name_1);jQuery("#marketPersonName1").val(_data.detail[0].litigation.person_name_1);jQuery("#marketPersonTitle1").val(_data.detail[0].litigation.person_title_1);jQuery("#showNameBtn").attr("onclick","openContactForFrom(4,'from_regular');");jQuery("#showNameSecond").html(_data.detail[0].litigation.person_name_2);jQuery("#marketPersonName2").val(_data.detail[0].litigation.person_name_2);jQuery("#marketPersonTitle2").val(_data.detail[0].litigation.person_title_2);jQuery("#showNameSecondBtn").attr("onclick","openContactForFrom(5,'from_regular');");jQuery("#from_regular").find("#marketComplete").val(_data.detail[0].litigation.complete);jQuery("#marketBroker").val(_data.detail[0].litigation.broker);jQuery("#marketAddress").val(_data.detail[0].litigation.address);jQuery("#from_regular").find("#taskLeadId").val(_data.detail[0].litigation.id);jQuery("#marketLeadId").val(_data.detail[0].litigation.id);jQuery("#marketPersonName1").val(_data.detail[0].litigation.person_name_1);jQuery("#marketPersonName2").val(_data.detail[0].litigation.person_name_2);jQuery("#marketRelatesTo").val(_data.detail[0].litigation.relates_to);jQuery("#marketupfront_price").val(_data.detail[0].litigation.expected_price);
		<?php endif;?>
		jQuery("#from_regular").find("#litigation_doc_list").empty();
		jQuery("#scrap_patent_data").find("tbody").empty();
		<?php if($openLeadDT===true):?>
		jQuery("#from_regular").find("#litigation_doc_list").addClass("docDropable").append("<ul class='todo-box-1 ' data-id='"+_data.detail[0].litigation.id+"'></ul>");
		fillPatentSheetListMode(_data.detail[0].litigation.folder_id);
		jQuery("#taskLeadId").val(_data.detail[0].litigation.id);
		jQuery("#marketLeadId").val(_data.detail[0].litigation.id);
		jQuery("#marketPatentData").val(_data.detail[0].litigation.patent_data);<?php endif;?>
	}else{
		if((_data.detail[0].litigation.type=="NON"||_data.detail[0].litigation.type=="INT")&&jQuery("#acquisitionOwner").length>0){
			<?php if($openLeadDT===true):?>
			jQuery("#acquisitionOwner").val(_data.detail[0].litigation.plantiffs_name);jQuery("#acquisitionProspects").val(_data.detail[0].litigation.no_of_prospects);jQuery("#acquisitionExpectedPrice").val(_data.detail[0].litigation.upfront_price);jQuery("#acquisitionProspectsName").val(_data.detail[0].litigation.prospects_name);jQuery("#acquisitionlead_name").val(_data.detail[0].litigation.lead_name);jQuery("#acquisitionTechnologies").val(_data.detail[0].litigation.technologies);jQuery("#acquisitionNo_of_us_patents").val(_data.detail[0].litigation.no_of_us_patents);jQuery("#acquisitionno_of_non_us_patents").val(_data.detail[0].litigation.no_of_non_us_patents);jQuery("#acquisitionFileUrl").val(_data.detail[0].litigation.file_url);jQuery("#acquisitionSellerInfo").val(_data.detail[0].litigation.seller_info);jQuery("#acquisitionProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);jQuery("#acquisitionCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);jQuery("#acquisitionType").val(_data.detail[0].litigation.type);jQuery("#acquisitionCreateDate").val(moment(new Date(_data.detail[0].litigation.create_date)).format("YYYY-MM-DD"));_update="";if(_data.detail[0].litigation.update_date!=""&&_data.detail[0].litigation.update_date!="0000-00-00 00:00:00"){_update=moment(new Date(_data.detail[0].litigation.update_date)).format("YYYY-MM-DD")}jQuery("#acquisitionUpdateDate").val(_update);_nextAction="";if(_data.detail[0].litigation.next_action!=""&&_data.detail[0].litigation.next_action!="0000-00-00 00:00:00"){_nextAction=moment(new Date(_data.detail[0].litigation.next_action)).format("YYYY-MM-DD")}jQuery("#acquisitionNextAction").val(_nextAction);if(_data.detail[0].litigation.complete==1){if(_data.detail[0].litigation.forward_to_review_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.create_patent_list_text==null){jQuery("#forward_to_review").html(" Review")}else{jQuery("#forward_to_review").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format("MM-D-YY")+"</span> Review")}}_mainValue=_data.detail[0].litigation.market_data;jQuery("#acquisitionMarketData").val(_mainValue);if(_mainValue!=""){marketData=_mainValue.split(",");if(marketData.length>0){$("#acquisitionBoxList").empty();$("#acquisitionBoxList").append("<ul class='todo-box-1'></ul>");for(mk=0;mk<marketData.length;mk++){$("#acquisitionBoxList").find("ul.todo-box-1").append("<li>"+marketData[mk]+"</li>")}}}jQuery("#from_nonacquistion").find("#showSellerName").html(_data.detail[0].litigation.seller_contact);jQuery("#acquisitionSellerContact").val(_data.detail[0].litigation.seller_contact);jQuery("#acquisitionOwner").val(_data.detail[0].litigation.plantiffs_name);jQuery("#from_nonacquistion").find("#sellerBtn").attr("onclick","openContactForFrom(1,'from_nonacquistion');");jQuery("#from_nonacquistion").find("#showBrokerFirm").html(_data.detail[0].litigation.broker_contact);jQuery("#acquisitionBrokerContact").val(_data.detail[0].litigation.broker_contact);jQuery("#acquisitionBroker").val(_data.detail[0].litigation.broker);jQuery("#from_nonacquistion").find("#brokerFirmBtn").attr("onclick","openContactForFrom(2,'from_nonacquistion');");jQuery("#from_nonacquistion").find("#showBrokerPerson").html(_data.detail[0].litigation.broker_person_contact);jQuery("#acquisitionBrokerPersonContact").val(_data.detail[0].litigation.broker_person_contact);jQuery("#acquisitionBrokerPerson").val(_data.detail[0].litigation.broker_person);jQuery("#from_nonacquistion").find("#brokerPersonBtn").attr("onclick","openContactForFrom(3,'from_nonacquistion');");jQuery("#from_nonacquistion").find("#showNameFirst").html(_data.detail[0].litigation.person_name_1);jQuery("#acquisitionPersonName1").val(_data.detail[0].litigation.person_name_1);jQuery("#acquisitionPersonTitle1").val(_data.detail[0].litigation.person_title_1);jQuery("#from_nonacquistion").find("#showNameBtn").attr("onclick","openContactForFrom(4,'from_nonacquistion');");jQuery("#from_nonacquistion").find("#showNameSecond").html(_data.detail[0].litigation.person_name_2);jQuery("#acquisitionPersonName2").val(_data.detail[0].litigation.person_name_2);jQuery("#acquisitionPersonTitle2").val(_data.detail[0].litigation.person_title_2);jQuery("#from_nonacquistion").find("#showNameSecondBtn").attr("onclick","openContactForFrom(5,'from_nonacquistion');");jQuery("#from_nonacquistion").find("#marketComplete").val(_data.detail[0].litigation.complete);jQuery("#acquisitionBroker").val(_data.detail[0].litigation.broker);jQuery("#acquisitionAddress").val(_data.detail[0].litigation.address);jQuery("#from_nonacquistion").find("#taskLeadId").val(_data.detail[0].litigation.id);jQuery("#acquisitionLeadId").val(_data.detail[0].litigation.id);jQuery("#acquisitionPersonName1").val(_data.detail[0].litigation.person_name_1);jQuery("#acquisitionPersonName2").val(_data.detail[0].litigation.person_name_2);jQuery("#acquisitionRelatesTo").val(_data.detail[0].litigation.relates_to);jQuery("#acquisitionOptionExpirationDate").val(_data.detail[0].litigation.option_expiration_date);
		<?php endif;?>		
		jQuery("#from_nonacquistion").find("#litigation_doc_list").empty();
		jQuery("#scrap_patent_data").find("tbody").empty();
		<?php if($openLeadDT===true):?>
		jQuery("#from_nonacquistion").find("#litigation_doc_list").addClass("docDropable").append("<ul class='todo-box-1 ' data-id='"+_data.detail[0].litigation.id+"'></ul>");
		jQuery("#taskLeadId").val(_data.detail[0].litigation.id);
		jQuery("#acquisitionLeadId").val(_data.detail[0].litigation.id);jQuery("#acquisitionPatentData").val(_data.detail[0].litigation.patent_data)
		<?php endif;?>
		}else{
			if(_data.detail[0].litigation.type=="Litigation"&&jQuery("#litigationleadName").length>0){<?php if($openLeadDT===true):?>
			jQuery("#litigationCaseName").val(_data.detail[0].litigation.case_name);jQuery("#litigationLitigationStage").val(_data.detail[0].litigation.litigation_stage);jQuery("#litigationMarketIndustry").val(_data.detail[0].litigation.market_industry);jQuery("#litigationCaseType").val(_data.detail[0].litigation.case_type);jQuery("#litigationCaseNumber").val(_data.detail[0].litigation.case_number);jQuery("#litigationCause").val(_data.detail[0].litigation.cause);jQuery("#litigationNoOfPatent").val(_data.detail[0].litigation.no_of_patent);jQuery("#litigationFillingDate").val(_data.detail[0].litigation.filling_date);jQuery("#litigationActiveDefendants").val(_data.detail[0].litigation.active_defendants);jQuery("#litigationLeadAttorney").val(_data.detail[0].litigation.LeadAttorney);jQuery("#litigationFileUrl").val(_data.detail[0].litigation.file_url);jQuery("#from_litigation").find("#litigationleadName").val(_data.detail[0].litigation.lead_name);jQuery("#litigationId").val(_data.detail[0].litigation.id);jQuery("#from_litigation").find("#litigationLeadAttorney").val(_data.detail[0].litigation.lead_attorney);jQuery("#litigationScrapperData").val(_data.detail[0].litigation.scrapper_data);jQuery("#litgationPatentData").val(_data.detail[0].litigation.patent_data);jQuery("#litigationOriginalDefendants").val(_data.detail[0].litigation.original_defendants);jQuery("#litigationCourt").val(_data.detail[0].litigation.court);jQuery("#litigationLinkToPacer").val(_data.detail[0].litigation.link_to_pacer);jQuery("#litigationLinkToRPX").val(_data.detail[0].litigation.link_to_rpx);jQuery("#litigationProspects").val(_data.detail[0].litigation.no_of_prospects);jQuery("#litigationExpectedPrice").val(_data.detail[0].litigation.upfront_price);jQuery("#litigationSellerInfo").val(_data.detail[0].litigation.seller_info);jQuery("#litigationProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);jQuery("#litigationCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);jQuery("#litigationUpfront_price").val(_data.detail[0].litigation.expected_price);jQuery("#from_litigation").find("#litigationComplete").val(_data.detail[0].litigation.complete);if(_data.detail[0].litigation.complete==1){if(_data.detail[0].litigation.forward_to_review_text=="0000-00-00 00:00:00"||_data.detail[0].litigation.forward_to_review_text==null){jQuery("#forward_to_review").html("Review")}else{jQuery("#forward_to_review").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format("MM-D-YY")+"</span> Review")}}
		<?php endif;?>
		jQuery("#taskLeadId").val(_data.detail[0].litigation.id);
		jQuery("#from_litigation").find("#show_data").html("");
		<?php if($openLitigationTable===true):?>
		if(_data.detail[0].litigation.defendants==""&&_data.detail[0].litigation.court_docket_entries==""){
			emptyForm();
			initDataTableLitigation();
			jQuery(function(){jQuery(".tabs").tabs()});
			jQuery(function(){jQuery(".tabs-hover").tabs({event:"mouseover"})});
			tabDropInit();
			_cUT="<?php echo $this->session->userdata['type']?>";							
			if(_data.detail[0].litigation.scrapper_data!=""&&_data.detail[0].litigation.scrapper_data!=null){
					implementLitigationScrap(JSON.parse(_data.detail[0].litigation.scrapper_data));tabDropInit();
			}else{
				jQuery("#from_litigation").find("#show_data").html("")
			}
		}else{
			jQuery("#from_litigation").find("#show_data").html('<div class="col-sm-12 noPadding" style="overflow-y:scroll;overflow:x:none;height:400px;"><div class="col-sm-6 noPadding" id="defendant"><img src="'+_data.detail[0].litigation.court_docket_entries+'" style="width:490px;"/></div><div class="col-sm-6" id="court_docket"><img src="'+_data.detail[0].litigation.court_docket_entries+'" style="width:490px;"/></div></div>')
		}
		<?php endif;?>		
		jQuery("#from_litigation").find("#litigation_doc_list").empty();
		jQuery("#scrap_patent_data").find("tbody").empty();
		<?php if($openLeadDT===true):?>					
		jQuery("#from_litigation").find("#litigation_doc_list").addClass("docDropable").append("<ul class='todo-box-1' data-id='"+_data.detail[0].litigation.id+"'></ul>");
		jQuery("#from_litigation").find("#litigationId").val(_data.detail[0].litigation.id);
		jQuery("#from_litigation").find("#litigationPatentData").val(_data.detail[0].litigation.patent_data);
		<?php endif;?>
		}
	}
	}
	windowResize();checkBodyScrollable();
	<?php if((int)$this->session->userdata['user']['type']==9 || (in_array(14,$this->session->userdata['modules_assign']) && in_array(15,$this->session->userdata['modules_assign']) && in_array(16,$this->session->userdata['modules_assign']))):?>
	if(_data.detail[0].litigation.spreadsheet_id!=""){jQuery("#patentSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id)}if(_data.detail[0].litigation.spreadsheet_id!=""&&_data.detail[0].litigation.worksheet_id==""){findWorksheetMode(jQuery("#patentSpreadsheetId"),'','undefined')}if(_data.detail[0].litigation.spreadsheet_id!=""&&_data.detail[0].litigation.worksheet_id!=""){findWorksheetMode(jQuery("#patentSpreadsheetId"),_data.detail[0].litigation.worksheet_id,'undefined')}
	fillPatentSheetListMode(_data.detail[0].litigation.folder_id);
	if(snapGlobal!='' || snapGlobalFileID!=''){
		findPatentFromSheetForm('undefined','undefined');
		jQuery("#patentFileUrl").val(snapGlobal);
		console.log(snapGlobal);
	}
	if(_mainButtonParentElement=='from_litigation'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_litigation',jQuery(this))");jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_litigation',jQuery(this))");jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_litigation')");jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_litigation',jQuery(this))");
	}else if(_mainButtonParentElement=='from_regular'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_regular',jQuery(this))");jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_regular',jQuery(this))");jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_regular')");jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_regular',jQuery(this))");
	}else if(_mainButtonParentElement=='from_nonacquistion'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_nonacquistion',jQuery(this))");jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_nonacquistion',jQuery(this))");jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_nonacquistion')");jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_nonacquistion',jQuery(this))");}	
	<?php elseif(in_array(14,$this->session->userdata['modules_assign'])):?>
	if(_mainButtonParentElement=='from_litigation'){jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_litigation',jQuery(this))");}else if(_mainButtonParentElement=='from_regular'){jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_regular',jQuery(this))");}else if(_mainButtonParentElement=='from_nonacquistion'){jQuery("#acquisitionActivityButton").attr("onclick","displayAquisitionActivityTable('from_nonacquistion',jQuery(this))");}
	<?php elseif(in_array(15,$this->session->userdata['modules_assign'])):?>
	if(_mainButtonParentElement=='from_litigation'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_litigation',jQuery(this))");}else if(_mainButtonParentElement=='from_regular'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_regular',jQuery(this))");}else if(_mainButtonParentElement=='from_nonacquistion'){jQuery("#salesActivityButton").attr("onclick","displaySaleActivityTable('from_nonacquistion',jQuery(this))");}
	<?php elseif(in_array(25,$this->session->userdata['modules_assign'])):?>
	if(_mainButtonParentElement=='from_litigation'){jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_litigation',jQuery(this))");}else if(_mainButtonParentElement=='from_regular'){jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_regular',jQuery(this))");}else if(_mainButtonParentElement=='from_nonacquistion'){jQuery("#preSaleActivityButton").attr("onclick","displayPreSaleActivityTable('from_nonacquistion',jQuery(this))");}
	<?php elseif(in_array(16,$this->session->userdata['modules_assign'])):?>
	if(_mainButtonParentElement=='from_litigation'){jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_litigation')")}else if(_mainButtonParentElement=='from_regular'){jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_regular')")}else if(_mainButtonParentElement=='from_nonacquistion'){jQuery("#btnPatentsAll").attr("onclick","displayPatentTable('from_nonacquistion')");
	if(_data.detail[0].litigation.spreadsheet_id!=""){jQuery("#patentSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id)}if(_data.detail[0].litigation.spreadsheet_id!=""&&_data.detail[0].litigation.worksheet_id==""){findWorksheetMode(jQuery("#patentSpreadsheetId"),'','undefined')}if(_data.detail[0].litigation.spreadsheet_id!=""&&_data.detail[0].litigation.worksheet_id!=""){findWorksheetMode(jQuery("#patentSpreadsheetId"),_data.detail[0].litigation.worksheet_id,'undefined')}
	fillPatentSheetListMode(_data.detail[0].litigation.folder_id);if(snapGlobal!='' || snapGlobalFileID!=''){
		findPatentFromSheetForm('undefined','undefined');console.log(snapGlobal);
	}}
	
	console.log(snapGlobal);
	<?php endif;?>
	<?php if((int)$this->session->userdata['user']['type']==9 || in_array(12,$this->session->userdata['modules_assign'])):?>
	_worksheet="";
	_spreadSheet="";
	_fileURLInput="";
	switch(_mainButtonParentElement){
		case 'from_litigation':
			_worksheet="#litigationWorksheetId";
			_spreadSheet="#litigationSpreadsheetId";			
			_fileURLInput="#litigationFileUrl";			
		break;
		case 'from_regular':
			_worksheet="#marketWorksheetId";
			_spreadSheet="#marketSpreadsheetId";
			_fileURLInput="#marketFileUrl";
		break;
		case 'from_nonacquistion':
			_worksheet="#acquisitionWorksheetId";
			_spreadSheet="#acquisitionSpreadsheetId";
			_fileURLInput="#acquisitionFileUrl";
		break;
	}
	if(_data.detail[0].litigation.spreadsheet_id!=""){jQuery("#"+_mainButtonParentElement).find(_spreadSheet).val(_data.detail[0].litigation.spreadsheet_id)}
	if(_data.detail[0].litigation.worksheet_id!=""){
		jQuery("#"+_mainButtonParentElement).find(_worksheet).val(_data.detail[0].litigation.worksheet_id);
	}
	jQuery("#"+_mainButtonParentElement).find(_fileURLInput).val(snapGlobal);
	<?php endif;?>
	runTableLeadBal();	
	callnotification();
	if(_mainButtonParentElement!=undefined){
		_mainBtnParentElement = _mainButtonParentElement;
	}
	countActiveMenus();
	}}}).done(function(){
		<?php if((int)$this->session->userdata['user']['type']==9 || (in_array(14,$this->session->userdata['modules_assign']) && in_array(12,$this->session->userdata['modules_assign']))):?>
		jQuery.ajax({type:"POST",url:__baseUrl+"leads/findEmailBoxes",data:{boxes:leadGlobal,type:0},cache:false,success:function(e){_data=jQuery.parseJSON(e);acquisitionImport(_data);}}).done(function(){loadDriveFiles()});
		<?php else:
			if(in_array(14,$this->session->userdata['modules_assign'])){
		?>
		jQuery.ajax({type:"POST",url:__baseUrl+"leads/findEmailBoxes",data:{boxes:leadGlobal,type:0},cache:false,success:function(e){_data=jQuery.parseJSON(e);acquisitionImport(_data);}});
		<?php
			} else if(in_array(12,$this->session->userdata['modules_assign'])){
		?>
		loadDriveFiles();
		<?php
			}
		endif;?>
	});
	
	enableActionRightAgain()}}
	_referenceString="";function findThread(a,e,b){if(typeof _globalAjax=='object'){_globalAjax.abort();}_formOpen='';jQuery('#displayEmail').empty();_mainActivity = parseInt(jQuery("#activityMainType").val());if(_mainActivity>0 && typeof(e)=='object'){p=0,c_id=0,_dateEmail="";if(_mainActivity==1){_atO = jQuery("#activityTable:visible");if(_atO.length>0){_atOSP=_atO.find('input[name="sales_person[]"]:checked');if(_atOSP.length>0){if(_atOSP.length==1){p=_atOSP.val();c_id=_atOSP.parent().parent().attr("data-c");_dateEmail=e.parent().parent().parent().attr("data-date");} else {alert("Please select only one person from whom you received email")}}}}else if(_mainActivity==3){_atO = jQuery("#preSaleActivityTable:visible");if(_atO.length>0){_atOSP=_atO.find('input[name="sales_person[]"]:checked');if(_atOSP.length>0){if(_atOSP.length==1){p=_atOSP.val();c_id=_atOSP.parent().parent().attr("data-c");_dateEmail=e.parent().parent().parent().attr("data-date");} else {alert("Please select only one person from whom you received email")}}}} else if(_mainActivity==2){_aatO = jQuery("#aquisitionTable:visible");if(_aatO.length>0){_aatOSP=_aatO.find('input[name="sales_person[]"]:checked');if(_aatOSP.length>0){if(_aatOSP.length==1){p=_aatOSP.val();c_id=_aatOSP.parent().parent().attr("data-c");_dateEmail=e.parent().parent().parent().attr("data-date");} else {alert("Please select only one person from whom you received email")}}}}if(p>0&&c_id>0&&_dateEmail!=""){
												msg = confirm('Are you sure you want to associate email with this person?');
												if(msg==true){jQuery("#moveEmailPopup").modal("show");jQuery.ajax({type:"POST",url:__baseUrl+"dashboard/linkWithMessage",data:{old_thread:leadGlobal,c_id:c_id,p:p,thread:e.parent().parent().parent().attr("data-id"),date:_dateEmail,t:_mainActivity},cache:false,success:function(et){jQuery("#moveEmailPopup").modal("hide");if(et!=""){_send=jQuery.parseJSON(et);if(_send.send>0){e.parent().parent().parent().remove();refreshAcquisitionAndSalesActivity();}else{alert("Please try after sometime")}}else{alert("Please try after sometime")}}});}
												
											}else{leadGlobal=0;jQuery('.topbar-lead-name').html('');showServerEmail(a,e,b);}} else {leadGlobal=0;jQuery('.topbar-lead-name').html('');showServerEmail(a,e,b)}}function  showServerEmail(a,e,b){if(typeof(b)==="undefined"){b=0}resetMenus();jQuery(".mCSB_container").find(".message-item").each(function(){if(jQuery(this).attr("data-id")==jQuery.trim(a)){if(b==0){e.parent().parent().parent().find("a").css("color","#FFF");e.parent().parent().parent().addClass("message-active");e.parent().parent().parent().find("h5").removeClass("c-dark");e.parent().parent().parent().find("h4").removeClass("c-dark");e.parent().parent().parent().find("p").removeClass("c-gray");e.parent().parent().parent().find("a").removeClass("c-dark");jQuery("#gmail_message").hide();jQuery("#from_regular").hide();jQuery("#from_litigation").hide();jQuery('#sales_acititity').removeClass('show').addClass('hide');jQuery("#from_nonacquistion").hide();if(jQuery("#myDashboardComposeEmails").length>0){jQuery("#myDashboardComposeEmails").get(0).reset()}$("#all_type_list tbody tr").removeClass("active");$("#all_type_list tbody td").removeClass("active");$("#other_list_boxes").empty()}}else{jQuery(this).removeClass("message-active");jQuery(this).find("h5").addClass("c-dark");jQuery(this).find("h4").addClass("c-dark");jQuery(this).find("p").addClass("c-gray");jQuery(this).find("a").addClass("c-dark")}});$("#other_list_boxes .message td").removeClass("active");if(!b){jQuery('.DTFC_Cloned tbody tr, .DTFC_Cloned tbody td').removeClass('active');$("#all_type_list tbody td").removeClass("active")}if(b){e.parent().addClass("active")}jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){jQuery(this).removeClass("active");jQuery("#marketLead").get(0).reset()});var j=jQuery("#message-detail").height()-jQuery("#message-detail .panel-heading").outerHeight()+12;

j=296;

jQuery("#displayEmail").html('<iframe src="'+__baseUrl+"users/email/"+jQuery.trim(a)+'" scrolling="yes" width="100%" height="'+j+'">')
jQuery("#displayEmail iframe").off('load').on('load', function() {
	checkMyEmailsHeight();
	iframe = jQuery("#displayEmail iframe").get(0);
iframewindow= iframe.contentWindow? iframe.contentWindow : iframe.contentDocument.defaultView;
if(iframewindow!=undefined){
	if(typeof iframewindow.bigContent==="function"){
		iframewindow.bigContent();
	}
}
});

}jQuery(document).ready(function(){/*jQuery("#emailOpenModal").on("hidden.bs.modal",function(){jQuery("body").attr("onselectstart","return false");document.oncontextmenu=new Function("return false")})*/});

function composeEmail(a) {
	if(leadGlobal==0) {
		alert("Please select lead first")
	}
	else {
		if(a==undefined) {
			jQuery("#emailDocUrl").val("");
			jQuery("#attach_droppable").empty()
		}

		jQuery("#anotherSys").val(0);
		refreshContacts();
		jQuery("#emailCC").css("width","725px");
		$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');
		openSlidebar(jQuery("#gmail_message_modal"));
		openSlideBarLeftMessageResize();
		$(".dropdown-toggle").dropdown();
		jQuery("#emailThreadId").val("");
		jQuery("#emailMessageId").val("");
		jQuery("#attach_droppable").empty();
		jQuery("#emailSubject").removeAttr("readonly");
		jQuery("#gmail_message").css("display","block");
		jQuery(".gmail-modal").css("display","block");

		if(mainLogBox==1){ 
			jQuery("#legal_log").val(1)
		}
		else {
			jQuery("#legal_log").val(0)
		}

		jQuery("#gmail_message_modal").find("h4").html("Compose Message: "+leadNameGlobal);
		jQuery("#messageLeadId").val(leadGlobal);
		jQuery("body").data("modalzindex",jQuery("#gmail_message_modal").css("z-index"));
		jQuery("#emailTo").focus();
	}
}

jQuery(document).ready(function(){jQuery("#gmail_message_modal").on("hidden.bs.modal",function(){jQuery("body").attr("onselectstart","return false");document.oncontextmenu=new Function("return false")})});function discardEmail(){jQuery("#myDashboardComposeEmails").get(0).reset();jQuery('#emailAccountType').val(1);jQuery("#emailThreadId").val("");jQuery("#emailMessageId").val("");jQuery("#gmail_message_modal").hide();jQuery("#attach_droppable").empty();jQuery("#emailTo").focus();jQuery("#emailMessage").code("<br/><br/><br/><br/>"+jQuery("#original_signature").val());closeSlideBarLeftMessage();checkBodyScrollable();windowResize()}function removeAlert(){jQuery(".alert-close-btn").click(function(a){a.preventDefault();jQuery(this).parent().remove()})}___check="";function getEmails(a,b,e){jQuery(".emails-group-container").find(".list-group-item").removeClass("active");jQuery('.emails-group-container').find(a).eq(jQuery('.emails-group-container').find(a).length-2).removeClass('active');e.addClass("active");_string='<div class="loading-spinner" id="loading_spinner_heading_messages" style="display:none;"><img src="'+__baseUrl+'public/images/ajax-loader.gif" alt=""></div>';jQuery("."+a).html(_string);jQuery("#loading_spinner_heading_messages").css("display","block");jQuery.ajax({type:"POST",url:__baseUrl+"users/getOldEmails",data:{type:b},cache:false,success:function(j){jQuery("#loading_spinner_heading_messages").css("display","none");jQuery("."+a).html(j);if(b!="DRAFT"){initDragDrop()}}})}function getNewEmails(a,b,e){jQuery(".emails-group-container").find(".list-group-item").removeClass("active");e.addClass("active");_string='<div class="loading-spinner" id="loading_spinner_heading_messages" style="display:none;"><img src="'+__baseUrl+'public/images/ajax-loader.gif" alt=""></div>';jQuery("."+a).html(_string);jQuery("#loading_spinner_heading_messages").css("display","block");runRetrieveNew()}function openNewLeadDialogueBox(){jQuery("#from_litigation").hide();jQuery("#from_regular").hide();jQuery('#show_data').removeClass('show').addClass('hide');jQuery("#newLeadFormElement").modal("show");jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");return false}__TableDT="";function toggleTableFilter(a){var b=a.parent().parent().parent().parent().parent().parent().parent().parent().prev();b.toggle();$("#all_type_list_wrapper .dataTables_scrollBody").toggleClass("is-small");return false}function leadsTableOneLineCells(){$(".one-line-cell").each(function(a,b){var e=$(b);e.html(e.html().replace(/ /g,"&nbsp;"))})}</script> <div class="page-mailbox" id="main-content"> <div data-equal-height="true" class="row" style="margin-right:-9px"> <div class="col-md-8 list-messages" style='padding:0px;'> <div class="row row-width" style="margin:0"> <div class="col-width" style='padding:0;width:73px' id='listLabels'> <div class="panel panel-default panel-no-margin panel-blue-border" style="">
 <?php if($openEmailBox===true){?><div id="signalA" class='circle' style='position:absolute;top: 12px;z-index: 9999;left: 58px;'></div><!-- <button class="btn btn-default btn-block text-left" type="button" onclick="composeEmail()" style="padding-left:8px;margin-bottom:-1px;margin-top:-1px;border-left:medium none;border-right:medium none">Compose</button> --><div class="list-group emails-group-container"> <a href="javascript:void(0)" data-title="INBOX" onclick="getEmails('messages_container','INBOX',jQuery(this))" class="list-group-item active">Inbox</a> <a href="javascript:void(0)" data-title="STARRED" onclick="getEmails('messages_container','STARRED',jQuery(this))" class="list-group-item label-dropable">Starred</a> <a href="javascript:void(0)" data-title="LEAD" onclick="getEmails('messages_container','LEAD',jQuery(this))" class="list-group-item">Leads</a> <a href="javascript:void(0)" data-title="NONLEAD" onclick="getEmails('messages_container','NONLEAD',jQuery(this))" class="list-group-item label-dropable">NonLead</a> <a href="javascript:void(0)" data-title="DRAFT" onclick="getEmails('messages_container','DRAFT',jQuery(this))" class="list-group-item">Draft</a> <a href="javascript:void(0)" data-title="SENT" onclick="getEmails('messages_container','SENT',jQuery(this))" class="list-group-item">Sent</a> <a href="javascript:void(0)" data-title="TRASH" onclick="getEmails('messages_container','TRASH',jQuery(this))" class="list-group-item label-dropable">Trash</a> <a href="javascript:void(0)" onclick="getNewEmails('messages_container','',jQuery(this))" class="list-group-item btn-primary btn-block">Retreive</a><?php }?><?php if((int)$this->session->userdata['user']['type']==9 || in_array(23,$this->session->userdata['modules_assign'])):?> <a href="javascript:void(0)" onclick="getImapEmails('messages_container',jQuery(this))" class="list-group-item btn-block" style='margin:0px;'>Licenses</a><a href="javascript:void(0)" onclick="voiceMailCalls()" class="list-group-item btn-block" style='margin:0px;'>V.Mails</a><?php endif;?>
 <?php if($openEmailBox===true){?><a href="javascript:void(0)" onclick="getNextInboxEmails('messages_container',jQuery(this),100)" class="list-group-item btn-block" style='padding:0px 8px 3px 8px;margin:0px;'>Next 100</a></div> <?php }?> </div> </div> <div class="" style='padding:0;margin-top:0;margin-left:-1px;z-index:1;float:left' id='listMainContainerModif'> <div class="panel panel-default panel-no-margin panel-blue-border" style=""> <?php if($openEmailBox===true  || in_array(23,$this->session->userdata['modules_assign'])):?> <div class="messages messages-list-leads"> <div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-list"> <div style="max-height:300px;min-height:300px;overflow-x:hidden!important;overflow-y:auto!important;max-width:100%" id="mCSB_116" class="mCustomScrollBox mCS-dark-2"> <div style="max-width:100%;overflow:hidden" class="mCSB_container messages_container"> <div class="loading-spinner" id="loading_spinner_heading_messages" style='display:none'> <img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt=""> </div> 
<?php 
			if(count($emails)>0){	
				foreach($emails as $message){	
					$mainFlag = 0;
					if($mainFlag==0){
						$flag = 0;
						if($flag==0){
							$from ="";													
							$subject="";													
							$date = "";	
							$messageIDDD ="";
							foreach($message['header'] as $header){
								if($header->name=="From"){	
									$from = $header->value;
								}
								if($header->name=="Subject"){
									$subject = $header->value;	
								}
								if($header->name=="Date"){
									$date = $header->value;
								}
								if($header->name=="Message-ID"){
									$messageIDDD = $header->value;
								}
							}
			?>
			<div class="message-item media draggable" data-date='<?php echo $date?>' data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>" data-task="0"> <div class="message-item-right"> <div class="media"> <div class="media-body" onclick="findThread('<?php echo $message['message_id']?>',jQuery(this))">
			<h5 class="c-dark"> 
			<?php 
				if(in_array(strtoupper('unread'),$message['labelIds'])){
			?> <strong><a class="c-dark" href="javascript:void(0)"><?php echo $from;?></a></strong> <?php
			} else {
			?> <a class="c-dark" style='font-weight:normal' href="javascript:void(0)"><?php echo $from;?></a> <?php
			}
			?> </h5> 
			<h4 class="c-dark"><?php echo $subject;?></h4> <div> <span class="message-item-date"><?php echo  date('M d, Y',strtotime($date));?></span> &nbsp; <?php 
				$parts = $message['parts'];
				$countAttachments = 0;
				if(isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related")){
					for($i=1;$i<count($parts);$i++){
						$attachmentID = $parts[$i]->getBody()->getAttachmentId();
						if(!empty($attachmentID)){
							$countAttachments++;
						}
					}
				}
				if($countAttachments>0):?>
				<strong><i class="glyph-icon icon-paperclip"></i> <?php echo $countAttachments;?></strong> <?php endif;?> <!--<a href='javascript://' onclick="enableTask(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon icon-plus"></i></a>--><a href='javascript://' onclick="moveEmailToTrash(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon"><img src="http://backyard.synpat.com/public/images/discard.png" style="opacity:0.55;width:10px;"></i></a> </div> </div> </div> </div> </div> 
			<?php
				}
			}
		}												
	}
?>
				</div></div></div></div><?php endif;?></div>
				</div><div class=" list-messages" style='padding:0;margin-top:0;margin-left:-1px;margin-right:-1px;width:900px;float:left'> <div class="panel panel-default panel-no-margin panel-blue-border" id="old_lead"> <div class="messages"> <div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-boxlist"> <div style="height:300px;overflow-x:hidden!important;overflow-y:auto!important;max-width:100%" id="mCSB_116" class="mCustomScrollBox mCS-dark-2"> 
<script>
	var tableDT="";

	$(document).ready(function(){
		tableDT=$("#all_type_list").DataTable({
			scrollY:"270px",
			scrollX:true,
			scrollCollapse:true,
			searching:true,
			paging:false,
			fixedColumns:{leftColumns:1},
			fnInitComplete:function(b,a){
				__TableDT=jQuery("#all_type_list").find("tbody").html()

				// console.log(location.href.indexOf('/dashboard/index/') !== -1);
				if(location.href.indexOf('/dashboard/index/') !== -1) {
					var highlightedLeadId = location.href.split('/dashboard/index/')[1];

					// jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");
					// _index=jQuery("#all_type_list").find("tbody").find('tr[data-id="'+highlightedLeadId+'"]').index();
					// jQuery("#all_type_list").find("tbody").find("tr").eq(_index).addClass("active");
					// jQuery(".DTFC_Cloned").find("tbody").find("tr").eq(_index).addClass("active");
					// _scrollTop=jQuery("#all_type_list").find("tbody").find("tr.active").offset();
					// console.log(_scrollTop);

					// if(jQuery("#dashboard_charts").is(":visible")){
					// 	jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-163.5)
					// } else {
					// 	jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106)
					// }

					setTimeout(function() {
						var offsetTop = jQuery("#all_type_list").find("tbody").find('tr[data-id="'+highlightedLeadId+'"]').offset().top;
						jQuery("#all_type_list").parent().scrollTop(0);
						jQuery("#all_type_list").parent().scrollTop(offsetTop-106);
						runTableLeadBal();
						// console.log([highlightedLeadId, offsetTop-106]);
					}, 500);

				}

				removeDTResizeEvents();
			},
			fnDrawCallback: function() {
				checkMyEmailsHeight();
			}
		})
	});
	</script>

	<table class="table" id="all_type_list" style="border-left:none;border-right:0;width:599px!important"> <thead> <tr> <th class="no-sort" style="text-align:center;border-right:0;border-left:none;width:200px"> <div class="prelative"> 
	<?php
	$openCreateLead = true;
	if((int)$this->session->userdata['type']!=9){
		if(!in_array(17,$this->session->userdata['modules_assign'])){
			$openCreateLead = false;
		}
	}
	if($openCreateLead===true):
	?> <a href='javascript://' class='btn btn-primary pabsolute' style='left:0;padding:0;min-width:0;border-radius:3px;width:18px;line-height:16px;height:18px' onclick='return openNewLeadDialogueBox()'>+</a>
	<?php else: ?>
	<a href='javascript://' class='btn btn-primary pabsolute' style='left:0;padding:0;min-width:0;border-radius:3px;width:18px;line-height:16px;height:18px'>+</a><?php endif;?>
	<span>Lead</span> <a href='javascript://' class='pabsolute' style='right:0;padding:0;min-width:0;border-radius:3px;width:18px;line-height:16px;height:18px' onclick='return toggleTableFilter($(this))'> <i class="glyph-icon icon-search"></i> </a> </div> </th> <th style="text-align:left;border-right:0;border-left:none;width:45px"><span title="Type">Type</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="Create">Create</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="Info">Info</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="Seller">Seller</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="Synpat">Synpat</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="PPA">PPA</span></th> <th style="text-align:left;border-right:0;border-left:none;width:60px"><span title="Close">Close</span></th> <th style="text-align:left;border-right:0;border-left:none"><span title="Broker">Broker</span></th> <th style="text-align:left;border-right:0;border-left:none"><span title="Seller">Seller</span></th> <th style="text-align:left;border-right:0;border-left:none"><span title="Title1">Title1</span></th> <th style="text-align:left;border-right:0;border-left:none"><span title="Title2">Title2</span></th> <th style="text-align:left;border-right:0;border-left:none"><span title="Tech. / Markets">Tech. / Markets</span></th> </tr> </thead> <tbody>
	<?php 
		if(count($incomplete)>0){
			foreach($incomplete as $message){
				$mainFlag = 0;
				$type="";
				$stage ="";
				$main = "mainLead";
				switch($message->type){
					case 'Litigation':
						$type ="Lit.";
					break;
					case 'Market':
						$type ="Mkt.";
					break;
					case 'General':
						$type ="Pro.";
					break;
					case 'SEP':
						$type ="SEP";
					break;
					default:
						$type = $message->type;
						$main = "outterLead";
					break;
				}
				if($message->complete<2){
					$stage="Lead";
				} else {
					$stage ="Oppt.";
				}
				$sellerInfo = "";
				$createDate = date('m/d/y',strtotime($message->create_date));
				if($message->seller_info_text!="" && $message->seller_info_text!=null){
					$sellerInfo = date('m/d/y',strtotime($message->seller_info_text));
				}
				$sellerLike = "";
				if($message->seller_like!="" && $message->seller_like!=null){
					$sellerLike = date('m/d/y',strtotime($message->seller_like));
				}
				$synpatLike = "";
				if($message->synpat_like!="" && $message->synpat_like!=null){
					$synpatLike = date('m/d/y',strtotime($message->synpat_like));
				}
				$ppa = "";
				if($message->ppa_date!="" && $message->ppa_date!=null){
					$ppa = date('m/d/y',strtotime($message->ppa_date));
				}
				$fundingTrnsfr = "";
				if($message->funding_trnsfr!="" && $message->funding_trnsfr!=null){
					$fundingTrnsfr = date('m/d/y',strtotime($message->funding_trnsfr));
				}
				$sellerClass = "";
				if($message->seller_info==1){
					$sellerClass = "btn-blink";
				}
				if($mainFlag == 0){
	?> <tr class="border-blue-alt droppable old_lead <?php echo $main;?>" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" onclick="threadDetail(jQuery(this))" <?php if($stage=="Oppt."):?>ondblclick="opportunityRedirect('<?php echo $message->id?>');"<?php endif;?>> <td style="padding:3px 2px;border-right:0;border-left:none;width:200px" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" class=""><label><a style='text-align:left' title="<?php echo $message->lead_name;?>" class='btn' href="javascript:void(0)"><?php echo substr($message->lead_name,0,30);?></a></label></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:45px"><?php echo $type;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px" class=' one-line-cell'><?php echo $createDate;?></td><td style="padding:3px 2px;border-right:0;border-left:none;width:71px" class='<?php echo $sellerClass;?> one-line-cell'><?php echo $sellerInfo;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $sellerLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $synpatLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $ppa;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $fundingTrnsfr;?> </div> </td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->broker_contact;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none">
	<?php 
								if(empty($message->seller_contact)){
									echo $message->plantiffs_name;
								} else {
									echo $message->seller_contact;
								}
								?> </td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->person_name_1;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->person_name_2;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->relates_to;?></td> </tr> <?php
					} 
				}
			}
		?> </tbody> </table> </div> </div> </div> </div> </div> <!--div class="col-xs-6 list-messages" style='padding:0;margin-top:0'> <div class="panel panel-default panel-no-margin panel-blue-border" style='min-height:302px;max-height:302px;height:302px;overflow-y:scroll;margin-right:2px; position: relative; z-index: 10;'> <div id="other_list_boxes"></div> </div> </div--> </div> </div> <div class="col-lg-4 col-md-4 email-hidden-sm detail-message" style="padding:0;margin-top:0;margin-left:-8px;border:1px solid #2196f3;background:#fff" id="displayEmail"></div> </div> </div><script>jQuery(document).ready(function(){jQuery('#messages-list').resizable();jQuery('#old_lead').resizable()})</script> <?php
					
		else:		
		?> <script>window.location="<?php echo $service->createAuthUrl()?>";</script> <?php
		endif;
		die;
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */