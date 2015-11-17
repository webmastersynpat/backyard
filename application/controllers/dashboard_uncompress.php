<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper(); 
		if(!isset($this->session->userdata['type'])){
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
	
	function leadLogTime(){
		$data = "";
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('l');
			if((int)$leadID>0){
				$data  = $this->user_model->findFullHoursForLead($leadID,$this->session->userdata['id']);
			}
		}
		echo $data;
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
		$data =array('status'=>'');
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
					$getFileInfo = $service->copyFile($getFileDetail->id,$getFileDetail->title,$fileParent);
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
						$update = $this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$driveMode['b']);
						if($update){
							$data['status'] = $updateDate;
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
					$checkLead = $this->user_model->checkLastTimeLoglead($this->input->post('l'),date('Y-m-d H:i:s',strtotime('-1 hours')));
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
			if((int)$oldLead !=(int)$newLead){
				if((int)$oldLead>0){
					$data = 1;
					$this->user_model->updateLeadLogTime($this->session->userdata['id'],$oldLead,array('logout_date'=>date('Y-m-d H:i:s')));
				}
				if((int)$newLead>0){
					$data = 1;
					$this->user_model->insertLogTime(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$newLead,'sid'=>$this->session->userdata['session_id'],'login_date'=>date('Y-m-d H:i:s')));
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
	
	function updateLogHr(){
		$data="0";
		if(isset($_POST) && count($_POST)>0){
			$aH = $this->input->post('ah');
			$c =(string) $this->input->post('c');
			$id = (int) $this->input->post('i');
			if($id>0){
				$this->user_model->updateLogTimeById($id,array('comment'=>$c,'actual_hrs'=>(string)$aH));	
				$getData  = $this->user_model->getLog($id);			
				$duration = $getData->hrsWorked;
				$adjustedlHrs = $getData->actual_hrs;
				if(!empty($adjustedlHrs)){
					$adjustedlHrs = $adjustedlHrs.":00";
				}
				$data = $duration;
				if($duration!='' && $duration!='00:00:00'){
					$d = strtotime($duration);
					$lengthOfAdjustedHrs = strlen($adjustedlHrs);
					if($lengthOfAdjustedHrs==9){
						list($h,$m,$s) = explode(':',$adjustedlHrs);
						list($hd,$md,$sd) = explode(':',$duration);						
						if(strlen($h)==3){
							$h = $h*-1;
							if($hd>=$h){
								if($md>=$m){
									$data = date('H:i:s',strtotime("-".$h." hour  -".$m." minutes",$d));
								} else {
									$data = "00:00:00";
								}
							} else {
								$data = "00:00:00";
							}
						} else {
							$data = date('H:i:s',strtotime("-".$h." hour  -".$m." minutes",$d));
						}											
					} else if($lengthOfAdjustedHrs==8){
						list($h,$m,$s) = explode(':',$adjustedlHrs);
						$data = date('H:i:s',strtotime("+".$h." hour  +".$m." minutes",$d));
					}
				} else {
					$data = $adjustedlHrs;
				}
			}
		}
		echo  $data;
		die;
	}
	
	function oldlinkWithMessage(){
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			$findData = $this->lead_model->findBoxByThread($box['thread']);
			if(count($findData)>0){
				$sendData = 0;
				foreach($findData as $thread){
					$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],"user_id"=>$this->session->userdata['id'],"thread_id"=>$box['thread'],"content"=>$thread->content,"file_attach"=>$thread->file_attach,"date_received"=>$thread->date_received,"type"=>$thread->type,"sent_from"=>$thread->sent_from));					
				}
				if($sendData>0){
					$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
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
				$emails = $_SESSION['SENT'];
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
					
					if(isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related" || $parts[0]->mimeType=="multipart/mixed")){
						for($i=1;$i<count($parts);$i++){
							$attachments = $parts[$i];
							$filename =  $attachments->filename;
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
					$content[] = array("message_id"=>$message->id,"thread_id"=>$thread_id,"labelIds"=>$message->labelIds,"header"=>$header,"body"=>$messageBody,"attachments"=>$attachmentsConnect,'ppp'=>$parts);
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
				$service->modifyMessage($gmailMessageID,"me",$labelID);
				$service->modifyMessageRemove($gmailMessageID,"me",'INBOX');
				$service->modifyMessageRemove($gmailMessageID,"me",'STARRED');
			}  
			if(!empty($filesAttachment)){
				$filesAttachment = substr($filesAttachment,0,-1);
			}
			/*Check Thread in DB*/
			$this->lead_model->removeFromBox($box['old_thread'],$box['thread']);
			/*End checking*/
			$type = 0;
			if(isset($box['c_id']) && isset($box['p']) && (int)$box['p']>0 && (int)$box['c_id']>0){
				$type = 1;
			}
			if(!empty($content)){
				$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],"user_id"=>$this->session->userdata['id'],"thread_id"=>$thread_id,'message_id'=>$box['thread'],"content"=>json_encode($content),"file_attach"=>$filesAttachment,"date_received"=>date('Y-m-d H:i:s', strtotime($box['date'])),'type'=>$type));
				if($type==1){
					$event = array();
					$event['company_id'] = $box['c_id'];
					$event['contact_id'] = $box['p'];
					$event['type'] = 3;
					$event['note'] = '';
					$event['user_id'] = $this->session->userdata['id'];
					$event['email_id'] = $sendData;
					$event['subject'] = '';
					$event['lead_id'] = $box['old_thread'];
					$event['activity_date'] = date('Y-m-d H:i:s');
					$this->lead_model->insetSalesActivity($event);
				}
				if($sendData>0){
					$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					echo json_encode(array('send'=>1));
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
			}
		}
		die;
	}
	
	public function index($id=null){		
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
		} else {
			$service->setAccessToken($_SESSION['another_access_token']);
			$userInfoArray = 	$service->getAuthUserEmail();
			if(isset($userInfoArray->email)){				
				if($userInfoArray->email==$this->session->userdata['email']){
					$emailService = new SignatureServiceHelper();
					$authString = $emailService->getAccessToken();
					$getUserSignature = $emailService->getUserSignature($this->session->userdata['email']);
					$xmlString =  substr($getUserSignature,strrpos($getUserSignature,"<?xml"));
					$emailSignature = '';
					if(!empty($xmlString)){ 
						$xml = new SimpleXmlElement($xmlString);
						$alias_email = $xml->children('apps', true)->property[0]->attributes();
						if(count($alias_email)>0){
							$emailSignature = (string)$alias_email['value'];
						}
					}
					$user_data = $this->session->userdata;
					$user_data['signature'] = $emailSignature;
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
	public function generateRandomString($length = 5) {
		/*$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';*/
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public function create_lead(){
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
							
							$lead = array("lead_name"=>$name,"type"=>$type,'serial_number'=>$serial_number,"user_id"=>$this->session->userdata['id'],"complete"=>0,"create_date"=>date('Y-m-d H:i:s'));		
							$lead_id = $this->lead_model->from_litigation_insert($lead);
							if($lead_id>0){
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
								$this->lead_model->from_litigation_update($lead_id,array('folder_id'=>$getFolderInfo));
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
					}
				} else {
					$this->lead_model->from_litigation_update($this->input->post('b'),array("status"=>$status));
					$data =  1;
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
					CURLOPT_URL => 'http://synpatnew.com/getPrePatents.php',
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
	
	public function potentialParticipant(){
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/potential_particpant',
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
	
	public function send_c(){
		$data = array("error"=>1);
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/findRequestData',
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
								CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/uploadFileToServer',
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
									$getLeadData = $this->lead_model->getLeadData($postData['l']);
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
											if($mimeType=="application/pdf"){
												$convert=false;
											}
											$getUploadFileData = $service->insertFile($fileInfo['filename'],$mimeType,$fileName,$parent,$convert);
											if($getUploadFileData){
												$templateData = $this->general_model->getTemplateBYType(1);
												unlink($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename);
												$data['error']= "0";
												$data['file']= $getUploadFileData;
												$data['template']= $templateData->template_html;
												$data['subject']= $templateData->subject;
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
					CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/updatePricePotential',
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
				CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/findRequestToParticipant',
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
					CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/findRequestToParticipant/'.$getStoreName['acquisition']->store_name,
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
	
	
	public function market(){
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
				if(isset($market['expected_price']) && !empty($market['expected_price'])){
					$getAcqusitionData = $this->acquisition_model->getData($lead_id);
					if(count($getAcqusitionData)>0){
						$updateData = $this->acquisition_model->updateData($lead_id,array('seller_asking_price'=>$market['expected_price']));
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/license_other_update_data',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'license_number' => $getAcqusitionData['acquisition']['store_name'],
								'asking_price'=>$market['expected_price'],
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
	
	
	
	public function lead_form(){
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
				$saveComment = $this->lead_model->from_litigation_comment($comment['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['lead_id'],'message'=>'Add a comment','create_date'=>date('Y-m-d H:i:s')));
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
	
	function reply_email(){
		if(isset($_POST) && count($_POST)>0){
			if(!isset($_SESSION)){
				session_start();
			}
			$this->load->library('DriveServiceHelper');
			$email = $this->input->post('email');
			
			if(isset($_SESSION['another_access_token'])){
				$fileName="";
				$fileSrc = "";
				$service = new GmailServiceHelper();
				$service->setAccessToken($_SESSION['another_access_token']);
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
					}
				}
				$type=0;
				$event = array();
				if(isset($_POST['event'])){
					$event = $this->input->post('event');
				}				
				if(count($event)>0 && isset($event['c_id']) && isset($event['p_id']) && (int) $event['c_id']>0 && (int) $event['p_id']>0){
					$type=1;
				}
				$leadID = $email['lead_id'];
				unset($email['lead_id']);
				$send  = $service->sendMessage($email);
				$another = $this->input->post('another');
				if(isset($another['sys']) && $another['sys']!='0'){
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://synpatnew.com/appadmin/Users/updatePricePotential',
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
						$dataArray = array("lead_id"=>$leadID,"type"=>$type,"total_patent"=>$totalPatents,"create_date"=>date('Y-m-d H:i:s'),"expert"=>$allEmails);
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
					
					$send = $this->linkWithMessageOnGo($leadID,$send->id,date('Y-m-d',strtotime($date)),$type,1);
					/*End Link to Email Box*/
					if($type==1){
						/*Attach email with sales activity*/
						$dataSend = json_decode($send,true);
						$email_id = 0 ;
						if(isset($dataSend['send'])){
							$email_id = $dataSend['send'];
						} else if(isset($dataSend->send)){
							$email_id = $dataSend->send;
						}
						$getMessageData = $this->lead_model->findBoxByThread($send->id);
						$event['company_id'] = $event['c_id'];
						$event['contact_id'] = $event['p_id'];
						$event['type'] = 3;
						$event['note'] = '';
						$event['user_id'] = $this->session->userdata['id'];
						$event['email_id'] = $email_id;
						$event['subject'] = $email['subject'];
						$event['lead_id'] = $leadID;
						$event['activity_date'] = $date;
						unset($event['c_id']);
						unset($event['p_id']);
						$this->lead_model->insetSalesActivity($event);
					}
					$this->session->set_flashdata('message','Message send successfully');
					redirect('dashboard/index/'.$leadID);
				} else {
					$this->session->set_flashdata('error','Please try after sometime.');
					redirect('dashboard');
				}				
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata('error','Please select message first');
			redirect('dashboard');
		}		
	}
	
	function linkWithMessageOnGo($leadID,$threadID,$date,$type,$sendFrom=0){
		if(!empty($leadID) && !empty($threadID)){
			/*session_start();*/
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
				$savedID = $this->lead_model->saveTaskConversation($message);
				$sendMessgae = array();
				if(count($getDetails)==0){
					$getTaskDetail = $this->opportunity_model->findTask($message['task_id']);
					if(count($getTaskDetail)>0){
						if($getTaskDetail->fromUserID!=$this->session->userdata['id']){
							$sendMessgae[] = $getTaskDetail->fromUserID;	
							$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->fromUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID));
						} else {
							$sendMessgae[] = $getTaskDetail->toUserID;	
							$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->toUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID));
						}						
					}
				} else {					
					$setFlag = 0;
					foreach($getDetails as $user){			
						if($savedID>0){
							if($user->fromUser!=$this->session->userdata['id']  && !in_array($user->fromUser,$sendMessgae)){
								$sendMessgae[] = $user->fromUser;															
								$this->lead_model->saveTaskConversationFlag(array("user_id"=>$user->fromUser,"task_id"=>$message['task_id'],"message_id"=>$savedID));
								$setFlag = 1;
							}
						}						
					}
					
					if($setFlag ==0){
						if($savedID>0){
							$getTaskDetail = $this->opportunity_model->findTask($message['task_id']);
							if(count($getTaskDetail)>0){
								if($getTaskDetail->fromUserID!=$this->session->userdata['id']){
									$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->fromUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID));
								} else {
									$this->lead_model->saveTaskConversationFlag(array("user_id"=>$getTaskDetail->toUserID,"task_id"=>$message['task_id'],"message_id"=>$savedID));
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
								$this->lead_model->saveTaskConversationFlag(array("user_id"=>$user,"task_id"=>$message['task_id'],"message_id"=>$savedID));
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
		if((int)$this->session->userdata['type']!=9){
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
		if($openEmailBox===true):
			$boxList = $this->lead_model->findAllBoxList();
			$pass_lead = array();/*$this->lead_model->getPassLead();	*/
		else:
			$boxList = array();
			$pass_lead = array();
		endif;
	?>
		
		 <style>
			#all_type_list thead tr th.header{
				padding:5px;
				display: table-cell;cursor:hand;cursor:pointer;
			}
			#all_type_list thead tr th.headerSortUp, #all_type_list thead tr th.headerSortDown {
			  background: #acc8dd;
			}
			.attachment-list-item{
				float: left;
				width: 100%;
			}
			.attachment-list-item a{
			float:left;width:95%;
			}
			/*#all_type_list thead tr th.header span{display:run-in}*/

		 </style>

<script>
	$(function() {
		$('.gmail-modal .close').on('click', function() {
			$('.gmail-modal').hide();
		});
	});
					jQuery(document).ready(function(){
						initDragDrop();
						tabDropInit();
						leadsTableOneLineCells();
					});
				_cmU = '<?php echo $this->session->userdata['email'];?>';
				window.specialChars = {
					8: "\\b",       
					9: "\\t",       
					10: "\\n",     
					12: "\\f",      
					13: "\\r",     
					39: "\\'",     
					92: "\\\\"       
				};

				window.escapedString = function(source) {
					if (! source) {
						return undefined;
					}
					
					var result = "";
					for (var index = 0; index < source.length; index++) {
						var code = source.charCodeAt(index);
						result += specialChars[code] ? specialChars[code] : String.fromCharCode(code);
					}
					
					return result;
				};
					var timeline = "";
					_CS = '<?php echo $this->session->userdata['id']?>';
					function recordLocal(t){
						switch(t){
							case 'next':
							mainIndex = mainIndex + 1;
							if(mainIndex>totalCC){
								mainIndex = totalCC;
							}
							break;
							case 'prev':
							mainIndex = mainIndex - 1;
							if(mainIndex<0){
								mainIndex = 0;
							}
							break;
						}
						threadDetail(jQuery("#all_type_list").find("tbody").find('tr').eq(mainIndex));						
					}
					
					function activateLead(n){
						jQuery("#all_type_list").find('tbody').find('tr').each(function(){
							if(jQuery(this).attr('data-id')==n){
								threadDetail(jQuery(this));	
							}
						}); 
					}
					
					function tabDropInit(){
						jQuery("#from_litigation").find(".nav-responsive").find("li").find("a").click(function(e){
							e.preventDefault();
							_tab = jQuery(this).attr("href");
							jQuery("#from_litigation").find(".nav-responsive").find("li").removeClass('active');
							jQuery("#from_litigation").find(".tab-pane").removeClass('active');
							jQuery(this).parent().addClass('active');
							jQuery("#from_litigation").find(_tab).addClass("active");
						});
					}
					function initHoverEmailClose(){
						jQuery(document).ready(function(){
							jQuery('#other_list_boxes').find('table').find('tbody').find('tr').mouseover(function(){
								jQuery(this).find('.email-close').removeClass('hide');
							}).mouseleave(function(){
								jQuery(this).find('.email-close').addClass('hide');
							});
							jQuery('#from_regular,#from_litigation,#from_nonacquistion').find('#litigation_doc_list').find('ul').find('li').mouseover(function(){
								jQuery(this).find('.drive-close').removeClass('hide');
							}).mouseleave(function(){
								jQuery(this).find('.drive-close').addClass('hide');
							});
						});
					}
					function initAttachRemove(){
						jQuery(document).ready(function(){
							jQuery('ul.attachment-list').find('li').mouseover(function(){
								jQuery(this).find('.remove-attachment').removeClass('hide');
							}).mouseleave(function(){
								jQuery(this).find('.remove-attachment').addClass('hide');
							});
						});
					}
					
					function deleteMe(o){
						o.parent().parent().remove();
					}
					
					function initDragDrop(){
						$( ".draggable" ).draggable({
							revert : true,
							zIndex:9999
						});
						$('.label-dropable').droppable({
							hoverClass: "drop-hover",
							tolerance : 'pointer',
							drop: function( event, ui ) {
								ui.draggable.css('display','none');
								_thread = ui.draggable.attr('data-id');
								_label = jQuery(this).attr('data-title');
								_active = jQuery.trim(jQuery(".emails-group-container").find('a.active').html());
								if(_thread!=undefined && _thread!="" && _label!=undefined && _label!="" && _active!=undefined && _active!=""){
									jQuery.ajax({
										type:'POST',
										url:'<?php echo $this->config->base_url();?>dashboard/moveThreadDiff',
										data:{thread:ui.draggable.attr('data-id'),label:_label,active:_active},
										cache:false,
										success:function(res){
											if(res>0){
												ui.draggable.remove();
											} else {
												ui.draggable.css('display','block');
												ui.draggable.css('top','0px');
												ui.draggable.css('left','0px');
											}
										}
									});
								}
							}
						});
						$("#attach_droppable").droppable({
							hoverClass: "drop-hover",							
							drop: function( event, ui ) {
								var doc_file = ui.draggable.find('a').attr('href');
								//jQuery("#emailDocUrl").val(doc_file);
								var doc_name = ui.draggable.html();
								if($(this).find('ul').length>0){
									$(this).find('ul').append("<li class='attachment-list-item'>"+doc_name+" <span class='remove-attachment hide pull-right'><a class='' onclick='deleteMe(jQuery(this))'><i class='glyph-icon icon-close'></i></a></span></li>");
								} else {
									$(this).append("<ul class='attachment-list'><li class='attachment-list-item'>"+doc_name+"<span class='remove-attachment hide pull-right'><a class='' onclick='deleteMe(jQuery(this))'><i class='glyph-icon icon-close'></i></a></span></li></ul>");
								}
								jQuery("#emailDocUrl").val($(this).html());
								initAttachRemove();
							}
						});
						
						$( ".droppable" ).droppable({
							hoverClass: "drop-hover",							
							drop: function( event, ui ) {
								jQuery("#other_list_boxes").find('table').find('tr').find('td').removeClass("active");
								if(jQuery("#marketLead").length>0){
									jQuery("#marketLead").get(0).reset();
								}								
								jQuery(".messages-list-leads").find('.message-item').each(function(){
									jQuery(this).removeClass('message-active');
									jQuery(this).find('h5').addClass('c-dark');
									jQuery(this).find('h4').addClass('c-dark');
									jQuery(this).find('p').addClass('c-gray');
									jQuery(this).find('a').addClass('c-dark');
								});
								jQuery("#subject").empty();
								jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;"><img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt=""></div>');
								jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
									jQuery(this).removeClass('active');
								});								
								if(jQuery(this).hasClass('old_lead')){									
									_neLD= ui.draggable.attr('data-id');
									_dateEmail = ui.draggable.attr('data-date');
									if(_dateEmail==undefined){
										_dateEmail = '';
									}
									_newObject = jQuery(this);
									if(jQuery(this).attr('data-id')!=undefined && parseInt(jQuery(this).attr('data-id'))>0 && ui.draggable.attr('data-id')!=undefined && ui.draggable.attr('data-id')!="" && !ui.draggable.hasClass('fromEmails') && !jQuery(this).hasClass('salesFDroppable')){										
										ui.draggable.remove();
										jQuery(this).find('td').eq(0).append('<div id="onFlyAddLoader" class="glyph-icon remove-border tooltip-button icon-spin-1 icon-spin float-left mrg0A" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
										jQuery.ajax({
											type:'POST',
											url:'<?php echo $this->config->base_url();?>dashboard/linkWithMessage',
											data:{old_thread:jQuery(this).attr('data-id'),thread:ui.draggable.attr('data-id'),date:_dateEmail},
											cache:false,
											success:function(res){
												__data = jQuery.parseJSON(res);
												jQuery("#onFlyAddLoader").remove();
												if(__data.send>0){
													ui.draggable.remove();
													_newObject.addClass('active');
													_aObject = _newObject.find('a');
													threadDetail(_aObject,"",1);
												} else {
													ui.draggable.css('top','0px');
													ui.draggable.css('left','0px');
													alert("Server busy, Please try after sometime.");
													getEmails('messages_container','INBOX',jQuery(".emails-group-container").find("a").eq(0));
												}
												/*removeAlert();*/											
											}
										});
									} else if(jQuery(this).attr('data-id')!=undefined && parseInt(jQuery(this).attr('data-id'))>0 && ui.draggable.attr('data-id')!=undefined && ui.draggable.attr('data-id')!="" && ui.draggable.hasClass('fromEmails') && !jQuery(this).hasClass('salesFDroppable')){											
										jQuery(this).find('td').eq(0).append('<div id="onFlyAddLoader" class="glyph-icon remove-border tooltip-button icon-spin-1 icon-spin float-left mrg0A" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
										jQuery.ajax({
											type:'POST',
											url:'<?php echo $this->config->base_url();?>dashboard/oldlinkWithMessage',
											data:{old_thread:jQuery(this).attr('data-id'),thread:ui.draggable.attr('data-id'),date:_dateEmail},
											cache:false,
											success:function(res){
												__data = jQuery.parseJSON(res);
												jQuery("#onFlyAddLoader").remove();
												if(__data.send>0){													
													_newObject.addClass('active');
													_aObject = _newObject.find('a');
													threadDetail(_aObject,"",1);
												} else {
													alert("Server busy, Please try after sometime.");
												}																				
											}
										});
									} else if(ui.draggable.hasClass('driveDragable') && jQuery(this).attr('data-id')!=undefined && parseInt(jQuery(this).attr('data-id'))>0 && jQuery("#gmail_message_modal").hasClass('sb-active')==false){
										jQuery(this).find('td').eq(0).append('<div id="onFlyAddLoader" class="glyph-icon remove-border tooltip-button icon-spin-1 icon-spin float-left mrg0A" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
										jQuery.ajax({
											type:'POST',
											url:'<?php echo $this->config->base_url();?>dashboard/move_drive_file',
											data:{old_thread:jQuery(this).attr('data-id'),drive:ui.draggable.find('a.drive_file_click').attr('data-file-id'),date:_dateEmail},
											cache:false,
											success:function(res){
												__data = jQuery.parseJSON(res);
												jQuery("#onFlyAddLoader").remove();
												if(__data.send>0){													
													_newObject.addClass('active');
													_aObject = _newObject.find('a');
													threadDetail(_aObject,"",1);
												} else {
													alert("Server busy, Please try after sometime.");
												}																				
											}
										});
									}else {
										ui.draggable.css('top','0px');
										ui.draggable.css('left','0px');
									}
								} else if(jQuery(this).attr('id')=="new_lead_drop"){
									jQuery("#list_boxes").empty();
									jQuery(this).css('border','1px solid #56b2fe');
									if(ui.draggable.attr('data-id')!=undefined || ui.draggable.attr('data-id')!=""){
										_sender = ui.draggable.find('h5').html();
										_anchor = ui.draggable.find('a').html();
										_onclick = ui.draggable.find('.media-body').attr('onclick');
										_subject = ui.draggable.find('h4').html();
										findThread(ui.draggable.attr('data-id'),'',1);
										jQuery(this).empty().html('<div class="new_draggable"><strong><a href="javscript:void(0)" onclick="'+_onclick+'">'+_anchor+'</a></strong><p>'+_subject+"</p></div>");
										jQuery("#lead_gmail_message_id").val(ui.draggable.attr('data-id'));
										jQuery("#newLeadFormElement").css("display","block");
										ui.draggable.remove();
										jQuery("#marketOwner").focus();
										jQuery(".new_draggable").draggable({
											stop: function( event, ui ) {
												//ui.draggable.remove();
												jQuery(this).remove();
												window.location = window.location.href;
											}
										});
									} else {
										ui.draggable.css('top','0px');
										ui.draggable.css('left','0px');
									}									
								}								
							}
						}); 
					}
					function driveFileDraggable(){
						jQuery(document).ready(function(){
							$( ".driveDragable" ).draggable({
								revert : true,
								helper: "clone",
								zIndex: 9999
							});
						});
					}
					
					function removeFromBox(g,thread){
						if(jQuery.trim(thread)!=""){
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $this->config->base_url();?>leads/removeFromBox',
								data:{thread:thread,g:g},
								cache:false,
								success:function(res){
									_data = jQuery.parseJSON(res);
									if(parseInt(_data.send)>0){
										jQuery("#other_list_boxes").find('table').find('tr.'+thread).remove();										
									}
								}
							});
						}
					}
					function salesEmailDroppable(){
						jQuery(".salesFDroppable").droppable({
							hoverClass: "drop-hover",							
							drop: function( event, ui ) {
								if(jQuery(this).attr('data-c')!=undefined && parseInt(jQuery(this).attr('data-c'))>0 && ui.draggable.attr('data-id')!=undefined && ui.draggable.attr('data-id')!=""){
									_dateEmail = ui.draggable.attr('data-date');
									if(_dateEmail==undefined){
										_dateEmail = '';
									}
									if(jQuery("#activityPerson").val()!=""){
										jQuery.ajax({
											type:'POST',
											url:__baseUrl+'dashboard/linkWithMessage',
											data:{old_thread:leadGlobal,c_id:jQuery(this).attr('data-c'),p:jQuery("#activityPerson").val(),thread:ui.draggable.attr('data-id'),date:_dateEmail},
											cache:false,
											success:function(res){
												if(res!=""){
													_send = jQuery.parseJSON(res);
													if(_send.send>0){
														ui.draggable.remove();
														threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
													} else{
														alert("Please try after sometime");
													}
												}
											}
										});
									} else {
										alert("Please select person before drag email.");
									}
								}
							  }
						});						
					}
					function deleteSalesInvitedC(d){
						con = confirm("Are you sure?");
						if(con){
							jQuery.ajax({
								type:'POST',
								url:__baseUrl+'opportunity/delete_c_sales',
								data:{l:leadGlobal,c:d},
								cache:false,
								success:function(data){
									if(data>0){
										threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
									}
								}
							});
						}
					}
					function salesActivityList(l){
						jQuery("#activityTable").find("tbody.main_active").empty();
						if(l.length>0){
							for(i=0;i<l.length;i++){
								_cID = l[i].company.id;
								_cName = l[i].company.company_name;
								_person = "";
								_activity = "";
								_date = "";
								_note = "";
								if(l[i].activities.length>0){
									_person = l[i].activities[0].firstName+" "+l[i].activities[0].lastName;
									_activity = salesActivities[l[i].activities[0].type];
									_date = l[i].activities[0].activity_date;
									_note = l[i].activities[0].note;
								}
								_tr = "<tr class='master salesFDroppable' data-c='"+_cID+"' ><td><input name='rd_company' type='radio' value='"+_cID+"' onclick='findMyContactList("+_cID+")'/>&nbsp;<a href='javascript://' onclick='deleteSalesInvitedC("+_cID+")'><i class='glyph-icon'><img src='"+__baseUrl+"public/images/discard.png' style='opacity:0.55'></i></a></td><td><a href='javascript://' class='showActivity'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a>&nbsp;<a href='javascript://' class='showActivity'>"+_cName+"</a></td><td>"+_activity+"</td><td>"+_date+"</td><td>"+_person+"</td>";
								if(_activity!='' && l[i].activities[0].type=="3" && l[i].activities[0].email_id!=0){
									if(l[i].activities[0].subject==""){
										_tr += "<td><a style='color:#56b2fe;text-decoration:underline;' href='javascript://' onclick='findOwnThread(\""+l[i].activities[0].email_id+"\",jQuery(this),2);'>View Email</a></td></tr>";
									}else {
										_tr += "<td><a style='color:#56b2fe;text-decoration:underline;' href='javascript://' onclick='findOwnThread(\""+l[i].activities[0].email_id+"\",jQuery(this),2);'>"+l[i].activities[0].subject+"</a></td></tr>";
									}									
								}else {
									_tr += "<td>"+_note+"</td></tr>";
								}
								jQuery("#activityTable").find("tbody.main_active").append(_tr);
								_cList="<table class='table table-bordered'><thead><tr><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody></tbody></table>";
								_cActivites="<table class='table table-bordered'><thead><tr><th>Activity</th><th>Date</th><th>Person</th><th>Note</th></tr></thead></table>";
								if(l[i].people.length>0){
									_cList="";
									_tr = "";
									for(p=0;p<l[i].people.length;p++){
										_name = l[i].people[p].first_name+" "+l[i].people[p].last_name;
										_phone = l[i].people[p].phone;
										if(_phone==""){
											_phone = l[i].people[p].telephone;
										} else if(l[i].people[p].telephone!=""){
											_phone += "<br/>"+l[i].people[p].telephone;
										}
										_tr +="<tr><td>"+_name+"</td><td>"+l[i].people[p].job_title+"</td><td>"+_phone+"</td></tr>";
									}
									_cList="<table class='table table-bordered'><thead><tr><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>"+_tr+"</tbody></table>";
								}
								if(l[i].activities.length>0){
									_cActivites="";
									_tr = "";
									for(al=1;al<l[i].activities.length;al++){
										_person = l[i].activities[al].firstName+" "+l[i].activities[al].lastName;
										_activity = salesActivities[l[i].activities[al].type];
										_date = l[i].activities[al].activity_date;
										_note = l[i].activities[al].note;
										if(_activity!='' && l[i].activities[al].type=="3" && l[i].activities[al].email_id!=0){
											if(l[i].activities[al].subject==""){
												_note= "<a class='btn' href='javascript://' onclick='findOwnThread("+l[i].activities[al].email_id+",jQuery(this),2);'>View Email</a>";
											}else {
												_note= "<a class='btn' href='javascript://' onclick='findOwnThread("+l[i].activities[al].email_id+",jQuery(this),2);'>"+l[i].activities[al].subject+"</a>";
											}									
										}
										_tr +="<tr><td>"+_activity+"</td><td>"+_date+"</td><td>"+_person+"</td><td>"+_note+"</td></tr>";
									}
									_cActivites="<table class='table table-bordered'><thead><tr><th>Activity</th><th>Date</th><th>Person</th><th>Note</th></tr></thead><tbody>"+_tr+"</tbody></table>";
								}
								_newTr = "<tr style='display:none;'><td colspan='2'>"+_cList+"</td><td colspan='4'>"+_cActivites +"</td></tr>";
								jQuery("#activityTable").find("tbody.main_active").append(_newTr);
							}
							salesEmailDroppable();
						}
						toggleCompanySales();
					}
					
					function docFileDraggable(){
						jQuery(document).ready(function(){
							$( ".docDragable" ).draggable({
								revert : true,
								helper: "clone",
								zIndex: 9999
							});
							$( ".emailDragable" ).draggable({
								revert : true,
								helper: "clone",
								zIndex: 9999
							});
							$( ".docDropable" ).droppable({
								hoverClass: "drop-hover",	
								drop: function( event, ui ) {
									/*var doc_file = ui.draggable.find('a').attr('href');*/
									var doc_file = ui.draggable.find('a').attr('data-href');
									var doc_name = ui.draggable.find('a').text();
									_id = jQuery(this).find('ul').attr('data-id');
									mainParent = jQuery(this);
									jQuery("#mainDocWaitBox").modal("show");
									if(doc_file!="" && doc_name!="" && _id!="" && _id!=undefined && doc_name!=undefined && doc_file!=undefined){
										jQuery.ajax({
											url:'<?php echo $this->config->base_url()?>leads/fileInsert',
											type:'POST',
											data:{file_name:doc_name,doc_url:doc_file,d:_id},
											cache:false,
											success:function(data){
												jQuery("#mainDocWaitBox").modal("hide");
												_data = jQuery.parseJSON(data);
												if(_data.error=="0"){
													if(_data.box.mimeType=="application/pdf" || _data.box.mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || _data.box.mimeType=="application/msword" || _data.box.mimeType=="image/jpeg"){
														url = "https://docs.google.com/file/d/"+_data.box.id+"/preview";
													} else {
														url = _data.box.alternateLink;
													}
													translated = escapedString(url);
													jQuery(mainParent).find('ul').append("<li><img src='"+_data.box.iconLink+"'/><a data-href='"+_data.box.alternateLink+"' data-mime='"+_data.box.mimeType+"' href='javascript://' onclick='open_drive_files(\""+translated+"\");' target='_BLANK'>"+_data.box.title+"</a></li>");
													driveFileDraggable();
												} else {
													alert("Please try later");
												}
											}
										});
									}
								}
							});
						});
					}
					function  findOwnThread(thread,object, flag){
						if(typeof(flag)==="undefined"){
							flag=0;
						}
						jQuery('.mCSB_container').find('.message-item').each(function(){
							jQuery(this).removeClass('message-active');
							jQuery(this).find('h5').addClass('c-dark');
							jQuery(this).find('h4').addClass('c-dark');
							jQuery(this).find('p').addClass('c-gray');
							jQuery(this).find('a').addClass('c-dark');
						});						
						if(flag==0){							
							object.parent().parent().parent().find('h5').removeClass('c-dark');
							object.parent().parent().parent().find('h4').removeClass('c-dark');
							object.parent().parent().parent().find('p').removeClass('c-gray');
							object.parent().parent().parent().find('a').removeClass('c-dark');
							object.parent().parent().parent().find('a').css('color','#FFF');
							object.parent().parent().parent().addClass('message-active');
							jQuery("#gmail_message").hide();
							jQuery("#from_regular").hide();
							jQuery("#from_litigation").hide();
							if(jQuery("#myDashboardComposeEmails").length>0){
								jQuery("#myDashboardComposeEmails").get(0).reset();
							}
							$('#all_type_list tbody td').removeClass('active');
							$('#all_type_list tbody tr').removeClass('active');
							$('#other_list_boxes').empty();
						}						
						$('#other_list_boxes .message td').removeClass('active');
						if(!flag) {
							$('#all_type_list tbody td').removeClass('active');
						}
						jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
							jQuery(this).removeClass('active');
							jQuery("#marketLead").get(0).reset();
						});
						if(flag) {
							object.parent().addClass('active');
						}
						 var iframeHeight = jQuery('#message-detail').height() - jQuery('#message-detail .panel-heading').outerHeight() + 12;
						 iframeHeight = 296;
						 jQuery('#displayEmail').html('<iframe src="'+__baseUrl+'users/own_server_email/'+jQuery.trim(thread)+'" scrolling="yes" width="100%" height="' + iframeHeight + '">');						
					}
					_leadPatent = "";
					_lT = "";					 
					function updatePatenteesData(_ddata){
						__HTMLL = '	<div id="TextBoxesGroup"><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Assets:</h2>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" style="width:100% !important"><span class="mainColor mainFont">1. Number of patents you wish to sell:</span></p>'+
						'</div><div id="patentNumbers" style="margin-bottom: 5px !important;"></div>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop"><span class="mainColor mainFont">2. How many are Standard Essential?</span></p>'+
						'<input id="essnt" onkeypress="return isNumber(event)" class="p_tet noPadding  mainFont  p_100 mainTextCenter"  maxlength="3" name="essnt"  type="text" value="" placeholder=""  /></div><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Price:</h2>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">3. Your total asking price ($): </span></p>'+
						'<input id="u_upfront" onkeypress="return isNumber(event)" onkeyup="inputKeyUp(event,jQuery(this))" class="p_tet  noPadding  mainFont p_100 mainTextCenter" maxlength="9" name="u_upfront" required="" type="text" value="" placeholder=" " /></div>'+											
						'<div class="cHalf" id="alert_message" style="display:none">'+
						'</div><h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Market:</h2>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">4. Number of expected licensees (total):</span></p>'+
						'<input id="n_lic" onkeypress="return isNumber(event)" onchange="inputKeyUp(event,jQuery(this))"  class="p_tet noPadding  mainFont p_100 mainTextCenter" maxlength="3" name="n_lic" type="text" value="" placeholder=" " /></div>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">5.Relating Technologies: </span></p>'+
						'<input id="textbox1" class="tertext mainFont" name="Technologies" type="text" value="" /></div>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">6.Relating Standards: </span></p>'+
						'<input id="Standards" class="tertext mainFont" name="Standards"  type="text" value="" /></div>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">7.Relating Markets: </span></p>'+
						'<input id="Markets" class="tertext mainFont" name="Markets"  type="text" value="" /></div>'+
						'<div class="cHalf">'+
						'<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">8.Relating Products: </span></p>'+
						'<input id="Products" class="tertext mainFont" name="Products"  type="text" value="" /></div>'+						
						'<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 100% !important;"><span class="mainColor mainFont">9. Potential interested licensees under your patents:</span></p></div>'+
						'<div id="TaxtBoxesGroup" style="margin-bottom: 5px !important;">'+
						'<div id="TaxtBoxesGroup1">'+
						'<div class="cHalf"><input id="textbox1" class="tertext mainFont" name="n_name[]"  type="text" value="" placeholder="Name" /></div>'+
						'<div class="cHalf"><input id="textbox1" class="tertext mainFont" name="r_lice[]"  type="text" value="" placeholder="Reason it may desire a license" /></div>'+
						'<div class="patenteespara mainColor mainWidth mainMarginBottom mainFont">Is there existing Evidence of Use? <input id="" style="padding-top: 0px; margin-bottom: 3px;margin-left: 10px;" name="evidence_e1" type="radio" value="Yes" /> YES <input id="" style="padding-top: 0px; margin-bottom: 3px;" name="evidence_e1" type="radio" value="No" /> NO</div>'+
						'<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">'+
						'</div>'+
						'</div>'+
						'</div>'+
						'<h2 class="headingPOP1 mainColor">Seller\’s Info:</h2>'+
						'<div class="cHalf"><input id="companyname" class="tertext mainFont" name="companyname"  type="text" value="" placeholder="Seller\’s Name " /></div>'+
						'<div class="cHalf"><input id="Broker" class="tertext mainFont" name="Broker"  type="text" value="" placeholder="Broker " /></div>'+
						'<div class="cHalf"><input id="fname" class="tertext mainFont" name="fname"  type="text" value="" placeholder="First Name " /></div>'+
						'<div class="cHalf"><input id="lname" class="tertext mainFont" name="lname"  type="text" value="" placeholder="Last Name " /></div>'+
						'<div class="cHalf"><input type="text" name="email" value="" class="tertext mainFont" id="email" required   placeholder="Email Address"></div>'+
						'<div class="cHalf"><textarea class="tertext" id="address" style="width: 100%; height: 100px !important; padding: 10px;" cols="4" name="address" rows="4" placeholder="Note"></textarea></div>'+
						'</div>';
						counterPatent = 1;
						jQuery("#patentees_data").html(__HTMLL);											
						if(_ddata!=null && typeof _ddata.popup_type!="undefined" && parseInt(_ddata.popup_type)==4){
							jQuery("#TextBoxesGroup").before("<a target='_BLANK' href='http://www.synpat.com/sellerform/?sr="+snp+"' class='btn btn-primary btn-block'>Synpat.com</a>");
							_anotherLicense = jQuery.parseJSON(_ddata.other_field);
							if(_anotherLicense.patent_list!=undefined){
								jQuery("#patentNumbers").empty();
								for(i=0;i<_anotherLicense.patent_list.length;i++){
									var newTextBoxDiv = $('<div/>').attr("id", 'patentNumbers' + counterPatent);	
									_country = _anotherLicense.patent_list[i].country;
									_application = _anotherLicense.patent_list[i].application;
									_patent = _anotherLicense.patent_list[i].patent;
									newTextBoxDiv.html(' <div class="cHalf" ><input type="text" name="country_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:146px !important;" id="textbox' + counterPatent + '" value="'+_country+'"    placeholder="Country"  > <input type="text" name="patent_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:146px !important;" value="'+_patent+'"   id="textbox' + counterPatent + '"    placeholder="Patents"  > <input type="text" name="application_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter" style="display:inline-block !important;width:146px !important;" value="'+_application+'"   id="textbox' + counterPatent + '"    placeholder="Applications"  ></div>'); 
									jQuery("#patentNumbers").append(newTextBoxDiv);
									if(jQuery("#patentNumbers").find('.rem').length>1){
										jQuery("#patentNumbers").find('.rem').css('display','inline-block');
									}			  
									counterPatent++;
								}
							}
							jQuery("#n_patents").val(_anotherLicense.n_patents);
							jQuery("#essnt").val(_anotherLicense.essnt);
							jQuery("#n_lic").val(_anotherLicense.n_lic);
							jQuery("#u_upfront").val(_anotherLicense.u_upfront);
							jQuery("#textbox1").val(_anotherLicense.technologies);
							jQuery("#Standards").val(_anotherLicense.standards);
							jQuery("#Markets").val(_anotherLicense.markets);
							jQuery("#Products").val(_anotherLicense.products);							
							if(_anotherLicense.another_license.length>0){								
								counter =1; 
								jQuery("#TaxtBoxesGroup").empty()
								for(i=0;i<_anotherLicense.another_license.length;i++){									
									jQuery("#TaxtBoxesGroup").find('.adem').css('display','none');			  
									var newTextBoxDiv = $('<div/>').attr("id", 'TaxtBoxesGroup' + counter);	
									_name = _anotherLicense.another_license[i].name;
									_r_lice = _anotherLicense.another_license[i].lice;
									ychecked="";
									nchecked="";
									if(_anotherLicense.another_license[i].evidence=="No"){
										nchecked = "CHECKED='CHECKED'";
									}
									if(_anotherLicense.another_license[i].evidence=="Yes"){
										ychecked = "CHECKED='CHECKED'";
									}
									newTextBoxDiv.html(' <div class="cHalf" ><input type="text" name="n_name[]" value="'+_name+'" class="tertext mainFont" id="textbox' + counter + '"    placeholder="Name "  ></div><div class="cHalf"> <input type="text" name="r_lice[]" value="'+_r_lice+'" class="tertext mainFont" id="textbox' + counter + '"    placeholder="Reason it may desire a license "  ></div><div class="cHalf patenteespara mainColor mainWidth mainMarginBottom mainFont" >Is there existing Evidence of Use?&nbsp;<input type="radio" name="evidence_e' + counter + '" '+ychecked+' value="Yes" class="" id="" style="margin-bottom:3px;margin-left: 10px;"> YES <input type="radio" name="evidence_e' + counter +  '" value="No" '+nchecked+' class="" id="" style="margin-bottom:3px;" > NO  </div>'); 
									jQuery("#TaxtBoxesGroup").append(newTextBoxDiv);
									counter++;
								}
							}
							jQuery("#companyname").val(_ddata.type_name);
							jQuery("#Broker").val(_ddata.broker);
							jQuery("#fname").val(_ddata.first_name);
							jQuery("#lname").val(_ddata.last_name); 
							jQuery("#email").val(_ddata.email_address);
							jQuery("#address").val(_ddata.note);							
						}
					}
					function leadRunEntry(oldLead,newLead){
						jQuery.ajax({
							type:'POST',
							url:'<?php echo $this->config->base_url()?>dashboard/lead_login',
							data:{o:oldLead,n:newLead},
							cache:false,
							success:function(data){								
							}
						});
					}
					
					function threadDetail(object,ato){
						mainLogBox = 0;
						__dashFlag = false;
						$('#all_type_list tbody td').removeClass('active');
						$('#all_type_list tbody tr').removeClass('active');
						$('.message-item').removeClass('message-active');
						$('#other_list_boxes .message td').removeClass('active');
						if(object.attr('data-id')!=undefined){
							g = object.attr('data-id');
							t = object.attr('data-type');
							object.parent().find('tr').removeClass('active');							
							object.addClass('active');
						} else {
							g = object.parent().parent().attr('data-id');
							t = object.parent().parent().attr('data-type');
							object.parent().parent().parent().addClass('active');
						}
						if(___FLAG===1){
							if(jQuery('#from_litigation').is(':visible')){
								submitData();
							} else if(jQuery('#from_regular').is(':visible')){
								submitDataMarket();
							}else if(jQuery('#from_nonacquistion').is(':visible')){
								submitFromData('from_nonacquistion');
							}
						}
						jQuery("#sales_acititity").addClass("hide").removeClass("show");
						jQuery("#from_litigation").find("#clipboard").css('display','none').addClass('hide').val('');
						jQuery("#from_regular").find("#clipboard").css('display','none').addClass('hide').val('');
						jQuery("#from_nonacquistion").find("#clipboard").css('display','none').addClass('hide').val('');
						___FLAG = 0;
						
						leadRunEntry(leadGlobal,g);
						if(jQuery("#search_lead_box").is(':visible')){
							jQuery("#all_type_list").find("tbody").find("tr").each(function(){
								
								if(jQuery(this).attr("data-id")==g){
									jQuery(this).addClass('active');
								}
							});
						}
						jQuery("#search_lead_box").css("display","none");
						if(jQuery("#all_type_list_wrapper>.row").css("display")=="block"){								
							if(jQuery("#all_type_list").find('tbody').find('tr.active').length>0){								
								jQuery("#all_type_list").find('tbody').html(__TableDT);												
								tableDT = $('#all_type_list').DataTable( {
									destroy: true,
									"scrollY": "270px",
									scrollX:        true,
									scrollCollapse: true,searching:true,
									"paging": false,
									/*"aoColumnDefs" : [ {'bSortable' : false,'aTargets' : [ 0 ]} ],*/
									columns: [
										{ "width": "130px" },
										{ "width": "20px" },
										{ "width": "50px" },
										{ "width": "50px" },  
										{ "width": "50px" },  
										{ "width": "50px" },  
										{ "width": "50px" },  
									],
									"fnInitComplete": function(oSettings, json) {
										jQuery("#all_type_list").find('tbody').find('tr').removeClass('active')
										_index = jQuery("#all_type_list").find('tbody').find('tr[data-id="'+g+'"]').index();
										jQuery("#all_type_list").find('tbody').find('tr').eq(_index).addClass('active');				
										_scrollTop = jQuery("#all_type_list").find('tbody').find('tr.active').offset();
										if(jQuery("#dashboard_charts").is(':visible')){
											jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-263.5);
										} else {
											jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106);
										}										
									},
									"fnDrawCallback": function() {
										
									}
								});
							}
						}
						
						jQuery(".messages-list-leads").find('.message-item').each(function(){
							jQuery(this).removeClass('message-active');
							jQuery(this).find('h5').addClass('c-dark');
							jQuery(this).find('h4').addClass('c-dark');
							jQuery(this).find('p').addClass('c-gray');
							jQuery(this).find('a').addClass('c-dark');
						});
						jQuery('.message-detail-title').css('display','none');
						jQuery('.message-detail-right').hide();
						jQuery("#subject").empty();
						jQuery(".message-detail-subject").empty();
						if(typeof(ato)==="undefined"){
							jQuery("#other_list_boxes").empty();
						}
						jQuery("#other_list_boxes").find('table').find('tr').removeClass("activetr");
						jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
											'<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">'+
										'</div>');
                        if(jQuery("#marketLead").length>0){
                            jQuery("#marketLead").get(0).reset();    
                        }
						if(jQuery("#leadForm").length>0){
                            jQuery("#leadForm").get(0).reset();    
                        }
                        if(jQuery("#formLitigation").length>0){
                            jQuery("#formLitigation").get(0).reset();    
                        }
						if(jQuery("#myDashboardComposeEmails").length>0){
                            jQuery("#myDashboardComposeEmails").get(0).reset();    
                        }
						jQuery("#gmail_message_modal").hide();
						jQuery("#gmail_message_modal").find("#attach_droppable").empty();
						jQuery("#from_regular").find("#litigation_doc_list").empty();
						jQuery("#from_litigation").find("#litigation_doc_list").empty();
						jQuery("#from_nonacquistion").find("#litigation_doc_list").empty();
						mainIndex = jQuery("#all_type_list").find('tbody').find('tr.active').index();
						jQuery('.pager-text').html((mainIndex+1)+"/"+ totalCC);
						if(parseInt(g)>0){
							jQuery("#bottom_form_market").css('display','block');
							jQuery("#from_regular,#from_litigation,#from_nonacquistion").css('display','none');
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $this->config->base_url();?>leads/findBoxList',
								data:{boxes:g,t:t},
								cache:false,
								success:function(res){
									jQuery("#from_regular").find("#scrap_patent_data_market").find('tbody').empty();
									jQuery("#from_litigation").find("#scrap_patent_data_market").find('tbody').empty();
									jQuery("#from_nonacquistion").find("#scrap_patent_data_market").find('tbody').empty();
									jQuery("#bottom_form_market").css('display','none');
									_data = jQuery.parseJSON(res);	
									_lT = _data;
									if(_data.detail.length>0){										
										/** Code for sneeze fix */
										$('#dashboard-page').append(
											'<div id="dashboardPageOverlay" style="background:#ffffff; height:100%; left: 0; position: absolute; top: 0; width: 100%"></div>'
										);
										setTimeout(function() {
											$('#dashboardPageOverlay').remove();
										}, 500);

										snapGlobal= _data.detail[0].litigation.file_url;
										_st = '<?php echo $this->session->userdata['type'];?>';
										_sp = '<?php echo $this->session->userdata['id'];?>';
										leadGlobal = _data.detail[0].litigation.id;
										snp = _data.detail[0].litigation.serial_number;
                                        leadNameGlobal = _data.detail[0].litigation.lead_name;
										snapGlobalFileID = _data.detail[0].litigation.spreadsheet_id;
										jQuery(".topbar-lead-name").css('width','440px;').html(leadNameGlobal);
										jQuery("#from_regular").find("#serialNumber").html(_data.detail[0].litigation.serial_number);
										jQuery("#from_nonacquistion").find("#serialNumber").html(_data.detail[0].litigation.serial_number);
										<?php $openDocket = true;
											if((int)$this->session->userdata['type']!=9){
												if(!in_array(2,$this->session->userdata['modules_assign'])){
													$openDocket = false;
												}
											}
												if($openDocket===true):
										?>
										
										if(jQuery("#open_opportunity").is(':visible')){
											jQuery("#docket_frame").html('<iframe width="100%" height="600px" scrolling="yes" src="<?php echo $this->config->base_url();?>opportunity/dummy_opportunity/'+leadGlobal+'"></iframe>');
											jQuery("#open_opportunity").find('#docketLabel').html("Docket - "+leadNameGlobal);
										}
										<?php endif;?>
										<?php $openEOU = true;
											if((int)$this->session->userdata['type']!=9){
												if(!in_array(3,$this->session->userdata['modules_assign'])){
													$openEOU = false;
												}
											}
												if($openEOU===true):
										?>
										if(jQuery("#open_eou_folder").is(':visible')){
											jQuery("#eou_frame").html('<iframe width="100%" height="600px" scrolling="yes" src="<?php echo $this->config->base_url();?>opportunity/eou_in_folder/'+leadGlobal+'"></iframe>');
										}
										<?php endif;?>
										<?php $openTimeline = true;
											if((int)$this->session->userdata['type']!=9){
												if(!in_array(7,$this->session->userdata['modules_assign'])){
													$openTimeline = false;
												}
											}
												if($openTimeline===true):
										?>
										
										 var timeLineTable = "";
											_dataTimeLine = _data.detail[0].timeLine;																   
											for(i=0;i<_dataTimeLine.length;i++){
												
												switch(jQuery.trim(_dataTimeLine[i].message)){
													case "PPA drafted":
														_draft_ppa_date = _dataTimeLine[i].create_date;
													break;
													case "Executed PPA":
														_executed_ppa = _dataTimeLine[i].create_date;
														
													break;
													case "PPA executed":
														_executed_ppa = _dataTimeLine[i].create_date;
														
													break;													
													case "Send request to CIPO for uploading damages report.":
														_upload_damage_report = _dataTimeLine[i].create_date;
													break;
													case "Send request to CIPO to start work on DD":
														_cipo_start_work_dd = _dataTimeLine[i].create_date;
													break;
													case "NDA created":
														_nda_created = _dataTimeLine[i].create_date;
													break; 
													case "NDA approved":
														_nda_approved = _dataTimeLine[i].create_date;
													break;
													case "Execute NDA by PD":
														_nda_execute = _dataTimeLine[i].create_date;
													break;
													case "CIPO approved NDA":
														_nda_approved = _dataTimeLine[i].create_date;
													break;
													case "Send request to CIPO for NDA approval":
														_send_req_nda_approval = _dataTimeLine[i].create_date;
													break; 
													case "EOU confirmed":
														_eou_confirmed = _dataTimeLine[i].create_date;
													break;
													case "NDA shared with Sellers":
														_nda_shared = _dataTimeLine[i].create_date;
													break; 
													case "Insert list of Assets":
														_list_of_assets_send = _dataTimeLine[i].create_date;
													break; 
													case "CIPO Approved assets.":
														_list_of_assets_approve = _dataTimeLine[i].create_date;
													break;
													case "Drafted PLA":
														_draft_pla = _dataTimeLine[i].create_date;
													break;
													case "Drafted Participant":
														_draft_participant = _dataTimeLine[i].create_date;
													break;
												}
												timeLineTable += '<div class="tl-row">'+
																   '<div class="tl-item float-right"><div class="tl-bullet bg-red"></div>'+
																		'<div class="popover right">'+
																			  '<div class="arrow"></div>'+
																				'<div style="cursor: pointer;" class="popover-content">';
										  
												_colorClass = "";
												_label = "";
												if(_dataTimeLine[i].hasOwnProperty('leadType')){
													switch(_dataTimeLine[i].leadType){
														case 'Litigation':
															_colorClass = "bg-yellow";
														break;
														
														case 'Market': 
															_colorClass = "bg-green";
														break;
														
														case 'General':
															_colorClass = "label-info";
														break;
														
														case 'SEP':
															_colorClass = "bg-warning";
														break;
													}
													_label = (_dataTimeLine[i].lead_name!="")?_dataTimeLine[i].lead_name:_dataTimeLine[i].plantiffs_name;
												}
												timeLineTable += '<div class="tl-label bs-label '+_colorClass+'">'+_label+'</div>';
												_userImage = "<?php echo $this->config->base_url()?>public/upload/user.png";
												if(_dataTimeLine[i].profile_pic!=""){
													_userImage = _dataTimeLine[i].profile_pic;
												}
												timeLineTable += 	'<div class="tl-container"><p class="tl-content">'+_dataTimeLine[i].message+'</p>'+
																		'<div class="tl-footer clearfix">'+
																			'<div class="tl-timeuser">'+
																				_dataTimeLine[i].create_date+"&nbsp;&nbsp;&nbsp;"+_dataTimeLine[i].name+
																			'</div>'+
																			'<img width="28" src="'+_userImage+'"/>'+
																		'</div>' +
																	'</div>' +
																'</div>'+
															'</div>'+
														'</div>'+
												'</div>';
											}
                        				jQuery(".timeline-box").html(timeLineTable);
				                        // Timeline events
				                        timelineItemBindEvents();
										<?php endif;?>
										jQuery("#taskType").val(_data.detail[0].litigation.type);
										<?php $openTask = true;
											if((int)$this->session->userdata['type']!=9){
												if(!in_array(6,$this->session->userdata['modules_assign'])){
													$openTask = false;
												}
											}
												if($openTask===true):
										?>
										 var taskTable = "";
										_dataTask = _data.detail[0].task;
										for(i=0;i<_dataTask.length;i++){       
											_colors_array="";
											switch(_dataTask[i].type){
												case 'Litigation':
													_colors_array = "bg-yellow";
												break;
												case 'Market':
												case 'NON':
												case 'INT':
													_colors_array = "bg-green";
												break;
												case 'General':
													_colors_array = "label-info";
												break;
												case 'SEP':
													_colors_array = "bg-warning";
												break;
											}
											if(parseInt(_dataTask[i].userType)==9 && _dataTask[i].approved_type=='LEAD'){
												_userName = "System";
											} else {
												_userName = _dataTask[i].userName;
											}
											_img = '<img title="'+_dataTask[i].uuserName+'" src="<?php echo $this->config->base_url();?>public/upload/user.png" width="28" />';
											if(_dataTask[i].profile_pic!=""){
												_img = '<img title="'+_dataTask[i].uuserName+'" src="'+_dataTask[i].profile_pic+'" width="28" />';
											}
											_leadName = (_dataTask[i].lead_name!="")?_dataTask[i].lead_name:_dataTask[i].plantiffs_name;
											taskTable +='<li>'+
														'<a href="javascript://" onclick="approvedFile('+_dataTask[i].approved_id+')">'+
															'<span class="tl-label bs-label '+_colors_array+'">'+_leadName+'</span>'+
															'<span class="todo-container">'+
																'<span class="todo-content" for="todo-1" title="'+_dataTask[i].subject+'">'+_dataTask[i].subject+'</span>'+
																'<span class="todo-footer clearfix">'+
																	'<span class="todo-footer-dateuser"> '+moment(new Date (_dataTask[i].create_date)).format("MMM D, YYYY")+'	&nbsp;&nbsp;&nbsp;'+_userName+'</span>'+_img+
																'</span>'+
															'</span>'+
														'</a>'+
														'</li>';
										}										
										jQuery("#task_list").find('ul.todo-box').html(taskTable);
										_dataTask = _data.detail[0].task_i;
										if(_dataTask.length>0){
											jQuery("#my_c_task_list").find('ul.todo-box').empty();
											taskTable="";
											for(i=0;i<_dataTask.length;i++){       
												_colors_array="";
												switch(_dataTask[i].type){
													case 'Litigation':
														_colors_array = "bg-yellow";
													break;
													case 'Market':
													case 'NON':
													case 'INT':
														_colors_array = "bg-green";
													break;
													case 'General':
														_colors_array = "label-info";
													break;
													case 'SEP':
														_colors_array = "bg-warning";
													break;
												}
												if(parseInt(_dataTask[i].userType)==9 && _dataTask[i].approved_type=='LEAD'){
													_userName = "System";
												} else {
													_userName = '<?php echo $this->session->userdata['name']?>';
												}
												_img = '<img title="'+_dataTask[i].userName+'" src="<?php echo $this->config->base_url();?>public/upload/user.png" width="28" />';
												if(_dataTask[i].profile_pic!=""){
													_img = '<img title="'+_dataTask[i].userName+'" src="'+_dataTask[i].profile_pic+'" width="28" />';
												}
												_leadName = (_dataTask[i].lead_name!="")?_dataTask[i].lead_name:_dataTask[i].plantiffs_name;
												taskTable +='<li>'+
															'<a href="javascript://" onclick="approvedFile('+_dataTask[i].approved_id+')">'+
																'<span class="tl-label bs-label '+_colors_array+'">'+_leadName+'</span>'+
																'<span class="todo-container">'+
																	'<span class="todo-content" for="todo-1" title="'+_dataTask[i].subject+'">'+_dataTask[i].subject+'</span>'+
																	'<span class="todo-footer clearfix">'+
																		'<span class="todo-footer-dateuser"> '+moment(new Date (_dataTask[i].create_date)).format("MMM D, YYYY")+'	&nbsp;&nbsp;&nbsp;'+_userName+'</span>'+_img+'</span>'+
																'</span>'+
															'</a>'+
															'</li>';
											}
											
											jQuery("#my_c_task_list").find('ul.todo-box').html(taskTable);
										} else {
											jQuery("#my_c_task_list").find('ul.todo-box').empty();
										}
										<?php endif;?>
										/*Comment Box*/
										_senddt = '<?php echo $this->session->userdata['id'];?>';
										<?php 
											$openTask = true;
											if((int)$this->session->userdata['type']!=9){
												if(!in_array(4,$this->session->userdata['modules_assign'])){
													$openTask = false;
												}
											}
											if($openTask===true):
										?>
										/*Patentees Details*/
										if(typeof _data.detail[0].patentees!="undefined"){
											_ddata = _data.detail[0].patentees;
											updatePatenteesData(_ddata);
										}
										/*Patentees Details*/
										jQuery("#commentComment1").val('');
										jQuery("#commentComment2").val('');
										jQuery("#commentComment3").val('');
                                        jQuery("#leadType").val(_data.detail[0].litigation.type);
										jQuery("#commentID").val('0');
										jQuery("select[name='attractiveness']").find('option').each(function(){ 
											jQuery(this).removeAttr('SELECTED');
										});
										if(jQuery("#btnComments:hidden").length>0){
											jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>");
										} else {
											jQuery("#btnComments").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>");
										}		
										if(_data.detail[0].comment.length>0){
											_tr ="";
											jQuery("#btnComments:hidden").find("table").find('tbody').html('');
											for(i=0;i<_data.detail[0].comment.length;i++){
												_flag= 0;
												_comment = _data.detail[0].comment[i];												
												if(_comment.user_id==_senddt){
													_flag = 1;
													_commentID = _comment.id;
													_commentText1 = _comment.comment1;													
													_commentText2 = _comment.comment2;													
													_commentText3 = _comment.comment3;
													_commentAttractive = _comment.attractive;
													jQuery("#commentComment1").val(_commentText1);
													jQuery("#commentComment2").val(_commentText2);
													jQuery("#commentComment3").val(_commentText3);
													jQuery("#commentId").val(_commentID);
													jQuery("select[name='attractiveness']").find('option').each(function(){ 
														if(jQuery(this).attr('value')==_commentAttractive){
															jQuery(this).attr('SELECTED','SELECTED');
														} else {
															jQuery(this).removeAttr('SELECTED');
														}
													});
												}
												if(_flag== 0){
													createdDate="";
                                                    updatedDate = "";
                                                    if(_comment.created == null || _comment.created == '0000-00-00 00:00:00'){
                                                        createdDate = "";
                                                    }else{
                                                        createdDate = moment(new Date(_comment.created)).format('MM-D-YY');
                                                    }
                                                    
                                                    if(_comment.updated == null || _comment.updated == '0000-00-00 00:00:00'){
                                                        updatedDate = "";
                                                    }else{
                                                        var updatedDate = moment(new Date(_comment.updated)).format('MM-D-YY');
                                                    }
                                                    comment1 = nl2br(_comment.comment1);
                                                    comment2 = nl2br(_comment.comment2);
                                                    comment3 = nl2br(_comment.comment3);
													_tr ='<tr>'+
														'<td>'+comment1.linkify()+' </td>'+
														'<td>'+comment2.linkify()+' </td>'+
														'<td>'+comment3.linkify()+' </td>'+
                                                        '<td><span class="label alert">'+_comment.attractive+'</span></td>	'+
                                                        '<td>'+createdDate+'</td>'+
														'<td>'+updatedDate+'</td>'+
                                                        '<td>'+_comment.name+'</td>'+				
													'</tr>';
													if(jQuery("#btnComments:hidden").length>0){
														jQuery("#btnComments:hidden").find("table").find('tbody').append(_tr);
													} else {
														jQuery("#btnComments").find("table").find('tbody').append(_tr);
													}
													
													
												}	
												if(_data.detail[0].comment.length==1 && _flag==1){
													jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>");
												}
											}
										} else {
											jQuery("#btnComments:hidden").find("#comment_upper_list").empty().html("<table class='table'><thead><tr><th>Are there >10 potential licensees? Who?</th><th>Will licensees want to pay the expected fee? Why?</th><th>Seller's concerns + Your general observations</th><th>Attractiveness</th><th>Created</th><th>Updated</th><th>By</th></tr></thead><tbody><tr><td colspan='7'>No record found!</td></tr></tbody></table>");
										}
										<?php endif;?>
										/*End Comment*/
										<?php 
											$openLeadDT = false;
											if((int)$this->session->userdata['type']!=9){
												
												if(in_array(12,$this->session->userdata['modules_assign']) || in_array(13,$this->session->userdata['modules_assign']) || in_array(14,$this->session->userdata['modules_assign']) || in_array(15,$this->session->userdata['modules_assign']) || in_array(16,$this->session->userdata['modules_assign'])){
													$openLeadDT = true;
												} 
											}else {
												$openLeadDT = true;
											}
											
											if($openLeadDT===true):
										?>
										salesActivityList(_data.detail[0].sales_activity);
										_mainButtonParentElement = "";
										if(_data.detail[0].litigation.type!='Litigation' && _data.detail[0].litigation.type!='NON' && _data.detail[0].litigation.type!='INT'){
											jQuery("#from_litigation").hide();
											jQuery("#from_nonacquistion").hide();
											jQuery("#from_regular").show();
											_mainButtonParentElement = "from_regular";										
										} else if(_data.detail[0].litigation.type=='NON' || _data.detail[0].litigation.type=='INT'){
											jQuery("#from_litigation").hide();
											jQuery("#from_nonacquistion").show();
											jQuery("#from_regular").hide();
											_mainButtonParentElement = "from_nonacquistion";
										} else if (_data.detail[0].litigation.type=='Litigation'){ 
				                            jQuery("#from_litigation").show();
								            jQuery("#from_regular").hide();
								            jQuery("#from_nonacquistion").hide();
											_mainButtonParentElement = "from_litigation";
										}
										<?php endif;?>
										_leadPatent = _data.detail[0].litigation.patent_data;
										jQuery("#mytimeline").empty();
										__timeline = [];
										_createDD = moment(new Date(_data.detail[0].litigation.create_date)).format("D.MM.YYYY");
										$("#timeline-html-wrap:hidden").empty();  
										__timeline.push({'start':new Date(_data.detail[0].litigation.create_date),'content': _data.detail[0].litigation.lead_name+' created'});
										<?php 
												$openTIMELINE = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(10,$this->session->userdata['modules_assign'])){
														$openTIMELINE = false;
													}
												}
												if($openTIMELINE===true):
											?>
                                            jQuery(document).ready(function(){												
                                                var items = new vis.DataSet(__timeline);
												_endDate = new Date(_data.detail[0].litigation.create_date);
												_endDate.setFullYear(_endDate.getFullYear()+1);
												var container = document.getElementById('mytimeline');
												var options = {
													maxHeight: '300px',
													type: 'point',
													selectable:false,
													showMajorLabels: false,
													zoomMin: 1000 * 60 * 60 * 24,             
													zoomMax: 1000 * 60 * 60 * 24 * 31 * 3     
												};
												
												if(typeof(timeline)=="object"){
												  timeline.destroy();
												}
												timeline= new vis.Timeline(container);
												timeline.setOptions(options);
												timeline.setItems(items);
												timeline.moveTo(new Date(),{animate:true});
                                            });
											<?php endif;?>
									
										
										<?php 
												$openBoxList = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(15,$this->session->userdata['modules_assign'])){
														$openBoxList = false;
													}
												}
												if($openBoxList===true):
											?>
												jQuery("#"+_mainButtonParentElement).find('.button-list').empty();
												for(bt=0;bt<_data.detail[0].buttons.length;bt++){
												_aC = "";
												if(jQuery("#"+_mainButtonParentElement).find('.button-list').find('div.row').length>0){
													_aC = "mrg5T";
												}
												
												switch(_data.detail[0].buttons[bt].button_id){
													case 'DRIVE':
														_statusMessage ="";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														_refrence = _data.detail[0].buttons[bt].reference_id;
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="drive_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
														
														if(_data.detail[0].buttons[bt].btnStatus=='0' || _data.detail[0].buttons[bt].renewable=='1'){
														jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="driveMode('+_data.detail[0].buttons[bt].id+',\''+_mainButtonParentElement+'\',\''+_refrence+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else {
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																jQuery("#"+_mainButtonParentElement).find("#drive_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].buttons[bt].update_date)).format('MM-D-YY')+"</span> "+_statusMessage);
															}
														}
													break;
													case 'EMAIL':
														_statusMessage ="";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														_refrence = _data.detail[0].buttons[bt].reference_id;
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="email_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
														if(_data.detail[0].buttons[bt].btnStatus=='0' || _data.detail[0].buttons[bt].renewable=='1'){
														jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="emailMode('+_data.detail[0].buttons[bt].id+',\''+_mainButtonParentElement+'\',\''+_refrence+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else {
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																jQuery("#"+_mainButtonParentElement).find("#email_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].buttons[bt].update_date)).format('MM-D-YY')+"</span> "+_statusMessage);
															}
														}
													break;		
													case 'TASK':
														_statusMessage ="";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														_refrence = _data.detail[0].buttons[bt].reference_id;
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="task_button'+_data.detail[0].buttons[bt].id+'"></div></div>');
														if(_data.detail[0].buttons[bt].btnStatus=='0' || _data.detail[0].buttons[bt].renewable=='1'){
															jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " data-status="'+_data.detail[0].buttons[bt].status_message+'" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="taskMode('+_data.detail[0].buttons[bt].id+',\''+_mainButtonParentElement+'\',\''+_refrence+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_'+_data.detail[0].buttons[bt].id+'" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else {
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																jQuery("#"+_mainButtonParentElement).find("#task_button"+_data.detail[0].buttons[bt].id).addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].buttons[bt].update_date)).format('MM-D-YY')+"</span> "+_statusMessage);
															}
														}
														
													break;
													case 'SELLER':
														_statusMessage = "Seller Info Done";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="assign_task_market"></div></div>');
														if(_data.detail[0].litigation.seller_info==1){							
															jQuery("#"+_mainButtonParentElement).find("#assign_task_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="assign_task_mode(2,\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} 
														else if(_data.detail[0].litigation.seller_info==2)
														{
															 if(_data.detail[0].litigation.seller_info_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.seller_info_text == null)
															 {
																jQuery("#"+_mainButtonParentElement).find("#assign_task_market").html(_statusMessage);
															 }
															 else
															 { 
																jQuery("#"+_mainButtonParentElement).find("#assign_task_market").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.seller_info_text)).format('MM-D-YY')+"</span> "+_statusMessage);
																__timeline.push({'start':new Date(_data.detail[0].litigation.seller_info_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
															 }
																				
														} else if(_data.detail[0].litigation.seller_info==0){
															jQuery("#"+_mainButtonParentElement).find("#assign_task_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="assign_task_mode(1,\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
													break;
													case 'SELLER_IS_INTERSTED':
														_statusMessage = "Seller like the deal";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="seller_deal_for_market"></div></div>');
														if(_data.detail[0].litigation.seller_like!="" && _data.detail[0].litigation.seller_like!=null){
															
															jQuery("#"+_mainButtonParentElement).find("#seller_deal_for_market").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.seller_like)).format('MM-D-YY')+"</span> "+_statusMessage);											
														} else {
															jQuery("#"+_mainButtonParentElement).find("#seller_deal_for_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="sellerInterested(\''+_mainButtonParentElement+'\')"><b>'+_data.detail[0].buttons[bt].name+'</b></a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
														
													break;
													case 'FUNDING':
														_statusMessage = "Funding Successful";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="funding_successful"></div></div>');
														if(_data.detail[0].litigation.funding_trnsfr!="" && _data.detail[0].litigation.funding_trnsfr!=null){
															jQuery("#"+_mainButtonParentElement).find("#funding_successful").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.funding_trnsfr)).format('MM-D-YY')+"</span> "+_statusMessage);												
														} else {
															jQuery("#"+_mainButtonParentElement).find("#funding_successful").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="fundingTransfer(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_funding_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
													break;
													case 'CLAIM_ILLUS': 
														_statusMessage = "Claim Illustration done";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														if(_data.detail[0].litigation.claim_illus!=null){
															_statusMessage = moment( new Date(_data.detail[0].litigation.claim_illus)).format('MM-D-YY')+" "+_statusMessage;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="claim_illus"></div></div>');
														if(_data.detail[0].litigation.claim_status_dd=="1" && _data.detail[0].buttons[bt].renewable=='0'){
															jQuery("#"+_mainButtonParentElement).find("#claim_illus").removeClass('btn').removeClass('btn-mwidth').addClass('btn-blink').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="claimIllusStatusChange(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else if(_data.detail[0].litigation.claim_status_dd=="2" && _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#claim_illus").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
															jQuery("#"+_mainButtonParentElement).find("#claim_illus").addClass('staRenewalAction').removeClass('btn-blink').html(_statusMessage);
															}
														} else {															
															jQuery("#"+_mainButtonParentElement).find("#claim_illus").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="claimIllus(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}													
													break;
													case 'TECHNICAL_DD':
														_statusMessage = "Techinical Due Dilligence Done";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														if(_data.detail[0].litigation.technical_dd!=null){
															_statusMessage = moment( new Date(_data.detail[0].litigation.technical_dd)).format('MM-D-YY')+" "+_statusMessage;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="technical_dd"></div></div>');
														if(_data.detail[0].litigation.technical_status_dd=="1" && _data.detail[0].buttons[bt].renewable=='0'){
															
															jQuery("#"+_mainButtonParentElement).find("#technical_dd").removeClass('btn').removeClass('btn-mwidth').addClass('btn-blink').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="technicalStatusChange(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else if(_data.detail[0].litigation.technical_status_dd=="2" && _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#technical_dd").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																jQuery("#"+_mainButtonParentElement).find("#technical_dd").addClass('staRenewalAction').removeClass('btn-blink').html(_statusMessage);
															}
														} else {
															
															jQuery("#"+_mainButtonParentElement).find("#technical_dd").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="technicalDD(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_technical_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}													
													break;
													case 'LEGAL_DD':
														_statusMessage = "Legal Due Dilligence Done";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														if(_data.detail[0].litigation.legal_dd!=null){
															_statusMessage = moment( new Date(_data.detail[0].litigation.legal_dd)).format('MM-D-YY')+" "+_statusMessage;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="legal_dd"></div></div>');
														if(_data.detail[0].litigation.legal_status_dd=="1" && _data.detail[0].buttons[bt].renewable=='0'){
															
															jQuery("#"+_mainButtonParentElement).find("#legal_dd").removeClass('btn').removeClass('btn-mwidth').addClass('btn-blink').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="legalStatusChange(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_legal_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														} else if(_data.detail[0].litigation.legal_status_dd=="2" && _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#legal_dd").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																jQuery("#"+_mainButtonParentElement).find("#legal_dd").addClass('staRenewalAction').removeClass('btn-blink').html(_statusMessage);
															}
														} else  {
															
															jQuery("#"+_mainButtonParentElement).find("#legal_dd").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth " title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="legalDD(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_legal_dd_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
														
													
													break;
													case 'PROPOSAL':
														/*
														_statusMessage = "Proposal letter created";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="request_for_proposal_market"></div></div>');
														if(_data.detail[0].litigation.send_proposal_letter==1 && _data.detail[0].buttons[bt].renewable=='0'){
															jQuery("#"+_mainButtonParentElement).find("#request_for_proposal_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="sendRequestForProposalLetterMarket(2)">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_prospect_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');							
														} else if(_data.detail[0].litigation.send_proposal_letter==2 && _data.detail[0].buttons[bt].renewable=='0'){
															 if(_data.detail[0].litigation.send_proposal_letter_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.send_proposal_letter_text == null){
															  jQuery("#"+_mainButtonParentElement).find("#request_for_proposal_market").html(_statusMessage);   
															 }else{
																jQuery("#"+_mainButtonParentElement).find("#request_for_proposal_market").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.send_proposal_letter_text)).format('MM-D-YY')+"</span> "+_statusMessage);
																__timeline.push({'start':new Date(_data.detail[0].litigation.send_proposal_letter_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
															 }
															 
														} else if(_data.detail[0].litigation.send_proposal_letter==0){
															jQuery("#"+_mainButtonParentElement).find("#request_for_proposal_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="sendRequestForProposalLetterMarket(1)">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_prospect_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}*/
													break;
													case 'PATENT_LIST':
														_statusMessage = "Patent Spreadsheet created";
														if(_data.detail[0].buttons[bt].status_message!=""){
															
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="create_patent_list_market"></div></div>');
														if(_data.detail[0].litigation.create_patent_list==1 && _data.detail[0].buttons[bt].renewable=='0'){
															jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="spreadsheet_box_mode(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_patent_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
															
														} else if(_data.detail[0].litigation.create_patent_list==2 && _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																if(_data.detail[0].litigation.create_patent_list_text == null || _data.detail[0].litigation.create_patent_list_text == '0000-00-00 00:00:00')
																{
																	jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").addClass('staRenewalAction').removeClass('btn').removeClass('btn-mwidth').html(_statusMessage); 
																}
																else
																{
																	jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass('btn').removeClass('btn-mwidth').addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.create_patent_list_text)).format('MM-D-YY')+"</span> "+_statusMessage); 
																	__timeline.push({'start':new Date(_data.detail[0].litigation.create_patent_list_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																}
															}
															
														} else if(_data.detail[0].litigation.create_patent_list==0 ||  _data.detail[0].buttons[bt].renewable=='1'){
															jQuery("#"+_mainButtonParentElement).find("#create_patent_list_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default renewable btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="spreadsheet_box_mode(\'\''+_mainButtonParentElement+'\'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_patent_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
															
														}
														
														if(_data.detail[0].litigation.spreadsheet_id!=""){
															
															jQuery("#"+_mainButtonParentElement).find("#acquisitionSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id);
														}
														if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id==""){
															
															findWorksheetMode(jQuery("#"+_mainButtonParentElement).find("#acquisitionSpreadsheetId"),undefined,'from_nonacquistion');
														}
														
														if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id!=""){
															findWorksheetMode(jQuery("#"+_mainButtonParentElement).find("#acquisitionSpreadsheetId"), _data.detail[0].litigation.worksheet_id,'from_nonacquistion');
														}
													break;
													case 'REVIEW':
														_statusMessage = "Review";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="forward_to_review_market"></div></div>');
														if(_data.detail[0].litigation.complete==1 || _data.detail[0].litigation.complete==2){
															 if(_data.detail[0].litigation.forward_to_review_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.forward_to_review_text == null) {
																jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").html(_statusMessage);     
															}  else {
																jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").html('<span class="date-style">' + moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format('MM-D-YY')+"</span> "+_statusMessage);
																__timeline.push({'start':new Date(_data.detail[0].litigation.forward_to_review_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
															}
														} else { 
															jQuery("#"+_mainButtonParentElement).find("#forward_to_review_market").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" onclick="forward_to_review_mode(\''+_mainButtonParentElement+'\')" href="javascript://">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_review_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
													break;
													case 'SCHEDULE':
														/*jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="schedule1stCallMarket"></div></div>');
														if(_data.detail[0].litigation.embed_code!=""){
															jQuery("#"+_mainButtonParentElement).find("#schedule1stCallMarket").before(_data.detail[0].litigation.embed_code);							
														} 
														jQuery("#"+_mainButtonParentElement).find("#schedule1stCallMarket").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript://" onclick="scheduleCallMode(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a>');*/														
													break;
													case 'NDA_TERMSHEET':
														_statusMessage = "NDA and TermSheet created";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="ndaTermSheetMarket"></div></div>');
														if(_data.detail[0].litigation.nda_term_sheet==1 &&  _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																 if(_data.detail[0].litigation.nda_term_sheet_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.nda_term_sheet_text == null){
																	jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html(_statusMessage);
																}else{
																	jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.nda_term_sheet_text)).format('MM-D-YY')+"</span> "+_statusMessage);
																	__timeline.push({'start':new Date(_data.detail[0].litigation.nda_term_sheet_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																}	
															}
														} else if(_data.detail[0].litigation.nda_term_sheet==2 &&  _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].buttons[bt].status_message_fill!=""){
																jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
															} else {
																if(_data.detail[0].litigation.nda_term_sheet_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.nda_term_sheet_text == null)
																{
																   jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html(_statusMessage);
																}else{
																	jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").addClass('staRenewalAction').html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.nda_term_sheet_text)).format('MM-D-YY')+"</span> "+_statusMessage);
																	__timeline.push({'start':new Date(_data.detail[0].litigation.nda_term_sheet_text),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																}
															}
														} else if(_data.detail[0].litigation.nda_term_sheet==0 ||  _data.detail[0].buttons[bt].renewable=='1'){
															jQuery("#"+_mainButtonParentElement).find("#ndaTermSheetMarket").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript:void(0);" onclick="createPartNDATermsheetMode(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_NDA_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
													break;
													case 'APPROVED_LEAD':
														_statusMessage = "Synpat like the deal";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="approved_lead"></div></div>');
														if(_data.detail[0].litigation.status==1 || _data.detail[0].litigation.status==2){
															jQuery("#"+_mainButtonParentElement).find("#approved_lead").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.synpat_like)).format('MM-D-YY')+"</span> "+_statusMessage);
														} else {
															jQuery("#"+_mainButtonParentElement).find("#approved_lead").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" title="'+_data.detail[0].buttons[bt].description+'" href="javascript:void(0);" onclick="approvedLead(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_approved_lead" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
														}
													break;
													case 'EXECUTE_NDA':
														_statusMessage = "NDA Executed successfully by CIPO";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="execute_nda"></div></div>');
														if(typeof(_data.detail[0].report)!='undefined' && typeof(_data.detail[0].report.id)!='undefined'){
															if(_data.detail[0].report.executed_nda==0){
																jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="executeNDA(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_execute_nda" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Executing NDA" style="color:#222222;"></div>');
															} else if(_data.detail[0].report.executed_nda==1){
																jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">' + moment( new Date(_nda_execute)).format('MM-D-YY')+'</span> Waiting for Admin to execute NDA');
															} else if(_data.detail[0].report.executed_nda==2 && _data.detail[0].report.nda_execute==0){
																jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">' + moment( new Date(_send_req_nda_approval)).format('MM-D-YY')+'</span> Waiting for CIPO to execute NDA');
															} else if(_data.detail[0].report.executed_nda==2 && _data.detail[0].report.nda_execute==2){
																jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation._nda_approved)).format('MM-D-YY')+"</span> "+_statusMessage);
																if(_nda_approved!="undefined"){
																	__timeline.push({'start':new Date(_nda_approved),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																}
																
															} 
														} else {
															jQuery("#"+_mainButtonParentElement).find("#execute_nda").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="executeNDA(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_execute_nda" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Executing NDA" style="color:#222222;"></div>');
														}
													break;
													case 'EOU':
														_statusMessage = "Seller EOU is in the Lead folder";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="seller_rou"></div></div>');
														if(typeof(_data.detail[0].report)!='undefined' && typeof(_data.detail[0].report.id)!='undefined'){
															if(_data.detail[0].report.eou_folder==0){
																jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="eouConfirmation(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_seller_eou" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Seller eou" style="color:#222222;"></div>');
															} else if(_data.detail[0].report.eou_folder==2){
																if(typeof(_eou_confirmed)!='undefined'){
																	jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<span class="date-style">'+moment(new Date(_eou_confirmed)).format("MM-D-YY")+'</span> '+_statusMessage);
																	__timeline.push({'start':new Date(_eou_confirmed),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																} else {
																	jQuery("#"+_mainButtonParentElement).find("#seller_rou").html(_statusMessage);
																}
															}
														} else {
															jQuery("#"+_mainButtonParentElement).find("#seller_rou").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="eouConfirmation(\''+_mainButtonParentElement+'\')">Seller\'s EOU in Folder</a><div id="loader_seller_eou" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Seller eou" style="color:#222222;"></div>');
														}
													break;
													case 'DRAFT_PPA':
														_statusMessage = "PPA has been successfully drafted";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="draft_a_ppa"></div></div>');
														if((typeof(_data.detail[0].report)!='undefined' && typeof(_data.detail[0].report.id)!='undefined') ||  _data.detail[0].litigation.ppa_id!="" &&  _data.detail[0].buttons[bt].renewable=='0'){
															if(_data.detail[0].litigation.ppa_id!=""){
																jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<span class="date-style">'+moment(new Date(_data.detail[0].litigation.ppa_text_date)).format("MM-D-YY")+'</span> '+_statusMessage);
																__timeline.push({'start':new Date(_data.detail[0].litigation.ppa_text_date),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
															} else {
																if(_data.detail[0].report.draft_a_ppa==0 ||  _data.detail[0].buttons[bt].renewable=='1'){
																	jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<a class="btn btn-default btn-mwidth renewable" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="draft_a_ppa(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>');
																} else if(_data.detail[0].report.draft_a_ppa==2 &&  _data.detail[0].buttons[bt].renewable=='0'){
																	if(_data.detail[0].buttons[bt].status_message_fill!=""){
																		jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass('staRenewalAction').html(_data.detail[0].buttons[bt].status_message_fill);
																	} else {
																		if(typeof(_draft_ppa_date)!='undefined'){
																			jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass('staRenewalAction').html('<span class="date-style">'+moment(new Date(_draft_ppa_date)).format("MM-D-YY")+'</span> '+_statusMessage);
																			__timeline.push({'start':new Date(_draft_ppa_date),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																		} else {
																			jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").addClass('staRenewalAction').html(_statusMessage);
																		}
																	}
																}
															}															
														} else {
															jQuery("#"+_mainButtonParentElement).find("#draft_a_ppa").html('<a class="btn btn-default btn-mwidth renewable" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="draft_a_ppa(\''+_mainButtonParentElement+'\')">'+_data.detail[0].buttons[bt].name+'</a><div id="loader_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>');
														}
													break;
													case 'EXECUTE_PPA':
														_statusMessage = "PPA has successfully executed";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="execute_a_ppa"></div></div>');
														if((typeof(_data.detail[0].report)!='undefined' && typeof(_data.detail[0].report.id)!='undefined') || _data.detail[0].litigation.execute_ppa!='0'){
															if(_data.detail[0].litigation.execute_ppa!='0'){
																/*jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html(_statusMessage);*/
																if(typeof(_executed_ppa)!='undefined'){
																	jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<span class="date-style">'+moment(new Date(_executed_ppa)).format("MM-D-YY")+'</span> '+_statusMessage);
																	__timeline.push({'start':new Date(_executed_ppa),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																} else {
																	jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html(_statusMessage);
																}
															} else {
																if(_data.detail[0].report.execute_ppa==0){
																	jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="execute_ppa(\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-execute_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Execute a PPA" style="color:#222222;"></div>');
																} else if(_data.detail[0].report.execute_ppa==1){
																	jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<p class="label-after-btn is-blink"><i class="glyph-icon icon-caret-right"></i> <span>Waiting for CEO for execute PPA</span></p>');
																} else if(_data.detail[0].report.execute_ppa>1 && (_data.detail[0].report.execute_ppa==2 || _data.detail[0].report.execute_ppa==3)){
																	if(typeof(_executed_ppa)!='undefined'){
																		jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<span class="date-style">'+moment(new Date(_executed_ppa)).format("MM-D-YY")+'</span> '+_statusMessage);
																		__timeline.push({'start':new Date(_executed_ppa),'content': _data.detail[0].litigation.lead_name+': '+_statusMessage});
																	} else {
																		jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html(_statusMessage);
																	}
																}
															}															
														} else {
															jQuery("#"+_mainButtonParentElement).find("#execute_a_ppa").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="execute_ppa(\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-execute_ppa" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Execute a PPA" style="color:#222222;"></div>');
														}
													break;
													case 'PPA_EXECUTE':
														_statusMessage = "PPA has successfully executed by CEO";
														if(_data.detail[0].buttons[bt].status_message!=""){
															_statusMessage = _data.detail[0].buttons[bt].status_message;
														}
														jQuery("#"+_mainButtonParentElement).find('.button-list').append('<div class="row '+_aC+'" data-item-idd="'+_data.detail[0].buttons[bt].id+'"><div class="col-sm-12" id="ppa_execute"></div></div>');
														if((typeof(_data.detail[0].report)!='undefined' && typeof(_data.detail[0].report.id)!='undefined') || _data.detail[0].litigation.ppa_execute!='0'){
															
															if(_data.detail[0].litigation.ppa_execute!='0'){
																jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.ppa_date)).format('MM-D-YY')+"</span> "+_statusMessage);
															} else if(typeof(_data.detail[0].report)!='undefined'){
																if(_data.detail[0].report.execute_ppa==2 || _data.detail[0].report.execute_ppa==1){
																	jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>');
																} else if(_data.detail[0].report.execute_ppa==3){
																	jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.ppa_date)).format('MM-D-YY')+"</span> "+_statusMessage);
																}
															}	else{
																jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\''+_mainButtonParentElement+'\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>');
															}														
														} else {
															jQuery("#"+_mainButtonParentElement).find("#ppa_execute").html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" title="'+_data.detail[0].buttons[bt].description+'" onclick="ppaExecuted(\'from_regular\');">'+_data.detail[0].buttons[bt].name+'</a><div id="spinner-loader-ppa_executed" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="PPA Executed" style="color:#222222;"></div>');
														}
													break;
												}
											}
										<?php endif;?>
										if(_data.detail[0].litigation.type!='Litigation' && _data.detail[0].litigation.type!='NON' && _data.detail[0].litigation.type!='INT' && jQuery("#marketOwner").length>0){
											<?php 
												$openFillForm = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(12,$this->session->userdata['modules_assign'])){
														$openFillForm = false;
													}
												}
											if($openFillForm===true):
											?>
											jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);
    										jQuery("#marketProspects").val(_data.detail[0].litigation.no_of_prospects);
    										jQuery("#marketExpectedPrice").val(_data.detail[0].litigation.expected_price);
    										jQuery("#marketProspectsName").val(_data.detail[0].litigation.prospects_name);
											jQuery("#marketlead_name").val(_data.detail[0].litigation.lead_name);
    										jQuery("#marketTechnologies").val(_data.detail[0].litigation.technologies);
    										jQuery("#marketNo_of_us_patents").val(_data.detail[0].litigation.no_of_us_patents);
    										jQuery("#marketno_of_non_us_patents").val(_data.detail[0].litigation.no_of_non_us_patents);
    										jQuery("#marketFileUrl").val(_data.detail[0].litigation.file_url);
                                            jQuery("#marketSellerInfo").val(_data.detail[0].litigation.seller_info);
                                            jQuery("#marketProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);
                                            jQuery("#marketCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);
                                            if(_data.detail[0].litigation.complete==1){
                                                if(_data.detail[0].litigation.forward_to_review_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.create_patent_list_text == null)
                                                {
                                                    jQuery("#forward_to_review").html(" Review");
                                                }else{
                                                    jQuery("#forward_to_review").html('<span class="date-style">' + moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format('MM-D-YY')+"</span> Review");
                                                }
    										}
											_mainValue = _data.detail[0].litigation.market_data;
											jQuery("#marketMarketData").val(_mainValue);
											if(_mainValue!=""){
												marketData = _mainValue.split(',');
												if(marketData.length>0){
													$("#marketBoxList").empty();
													$("#marketBoxList").append("<ul class='todo-box-1'></ul>");
													for(mk=0;mk<marketData.length;mk++){
														$("#marketBoxList").find("ul.todo-box-1").append("<li>"+marketData[mk]+"</li>");
													}
												}
											}
											jQuery("#showSellerName").html(_data.detail[0].litigation.seller_contact);
											jQuery("#marketSellerContact").val(_data.detail[0].litigation.seller_contact);
											jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);
											jQuery("#sellerBtn").attr("onclick","openContactForFrom(1,'from_regular');");
											jQuery("#showBrokerFirm").html(_data.detail[0].litigation.broker_contact);
											jQuery("#marketBrokerContact").val(_data.detail[0].litigation.broker_contact);
											jQuery("#marketBroker").val(_data.detail[0].litigation.broker);
											jQuery("#brokerFirmBtn").attr("onclick","openContactForFrom(2,'from_regular');");
											jQuery("#showBrokerPerson").html(_data.detail[0].litigation.broker_person_contact);
											jQuery("#marketBrokerPersonContact").val(_data.detail[0].litigation.broker_person_contact);
											jQuery("#marketBrokerPerson").val(_data.detail[0].litigation.broker_person);
											jQuery("#brokerPersonBtn").attr("onclick","openContactForFrom(3,'from_regular');");
											jQuery("#showNameFirst").html(_data.detail[0].litigation.person_name_1);
											jQuery("#marketPersonName1").val(_data.detail[0].litigation.person_name_1);
											jQuery("#marketPersonTitle1").val(_data.detail[0].litigation.person_title_1);
											jQuery("#showNameBtn").attr("onclick","openContactForFrom(4,'from_regular');");
											jQuery("#showNameSecond").html(_data.detail[0].litigation.person_name_2);
											jQuery("#marketPersonName2").val(_data.detail[0].litigation.person_name_2);
											jQuery("#marketPersonTitle2").val(_data.detail[0].litigation.person_title_2);
											jQuery("#showNameSecondBtn").attr("onclick","openContactForFrom(5,'from_regular');");
											jQuery("#from_regular").find("#marketComplete").val(_data.detail[0].litigation.complete);
                                            jQuery("#marketBroker").val(_data.detail[0].litigation.broker);
                                            jQuery("#marketAddress").val(_data.detail[0].litigation.address);
                                            jQuery("#from_regular").find("#taskLeadId").val(_data.detail[0].litigation.id);
                                            jQuery("#marketLeadId").val(_data.detail[0].litigation.id);
                                            jQuery("#marketPersonName1").val(_data.detail[0].litigation.person_name_1);
                                            jQuery("#marketPersonName2").val(_data.detail[0].litigation.person_name_2);
                                            jQuery("#marketRelatesTo").val(_data.detail[0].litigation.relates_to);  
                                            jQuery("#marketupfront_price").val(_data.detail[0].litigation.upfront_price);  
											<?php endif;?>                                           
											<?php 
												$openDriveBox = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(14,$this->session->userdata['modules_assign'])){
														$openDriveBox = false;
													}
												}
											if($openDriveBox===true):
											?>
											jQuery("#from_regular").find("#litigation_doc_list").empty();
											jQuery("#from_regular").find("#litigation_doc_list").addClass('docDropable').append("<ul class='todo-box-1 ' data-id='"+_data.detail[0].litigation.id+"'></ul>");
											<?php endif;?>							
											<?php if($openFillForm===true):?>											
											if(_data.detail[0].litigation.spreadsheet_id!=""){												
												jQuery("#from_regular").find("#marketSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id);
											}
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id==""){
												findWorksheetMode(jQuery("#from_regular").find("#marketSpreadsheetId"),0,_mainButtonParentElement);
											}											
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id!=""){
												findWorksheetMode(jQuery("#from_regular").find("#marketSpreadsheetId"), _data.detail[0].litigation.worksheet_id,_mainButtonParentElement);
											}
											<?php endif;?>											
                                        	jQuery("#scrap_patent_data_market").find('tbody').empty();
											jQuery("#taskLeadId").val(_data.detail[0].litigation.id);
											jQuery("#marketLeadId").val(_data.detail[0].litigation.id);
											jQuery("#marketPatentData").val(_data.detail[0].litigation.patent_data);
										} else if((_data.detail[0].litigation.type=='NON' || _data.detail[0].litigation.type=='INT') && jQuery("#acquisitionOwner").length>0){											
											<?php 
												$openFillForm = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(12,$this->session->userdata['modules_assign'])){
														$openFillForm = false;														
													}
												}												
											if($openFillForm===true):
											?>											
											jQuery("#acquisitionOwner").val(_data.detail[0].litigation.plantiffs_name);
    										jQuery("#acquisitionProspects").val(_data.detail[0].litigation.no_of_prospects);
    										jQuery("#acquisitionExpectedPrice").val(_data.detail[0].litigation.expected_price);
    										jQuery("#acquisitionProspectsName").val(_data.detail[0].litigation.prospects_name);
											jQuery("#acquisitionlead_name").val(_data.detail[0].litigation.lead_name);
    										jQuery("#acquisitionTechnologies").val(_data.detail[0].litigation.technologies);
    										jQuery("#acquisitionNo_of_us_patents").val(_data.detail[0].litigation.no_of_us_patents);
    										jQuery("#acquisitionno_of_non_us_patents").val(_data.detail[0].litigation.no_of_non_us_patents);
    										jQuery("#acquisitionFileUrl").val(_data.detail[0].litigation.file_url);
                                            jQuery("#acquisitionSellerInfo").val(_data.detail[0].litigation.seller_info);
                                            jQuery("#acquisitionProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);
                                            jQuery("#acquisitionCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);
                                            jQuery("#acquisitionType").val(_data.detail[0].litigation.type);
                                            jQuery("#acquisitionCreateDate").val(moment(new Date(_data.detail[0].litigation.create_date)).format("YYYY-MM-DD"));
                                            _update = "";
											if(_data.detail[0].litigation.update_date!="" && _data.detail[0].litigation.update_date!="0000-00-00 00:00:00"){
												_update = moment(new Date(_data.detail[0].litigation.update_date)).format("YYYY-MM-DD");
											}
											jQuery("#acquisitionUpdateDate").val(_update);
											_nextAction = "";
											if(_data.detail[0].litigation.next_action!="" && _data.detail[0].litigation.next_action!="0000-00-00 00:00:00"){
												_nextAction = moment(new Date(_data.detail[0].litigation.next_action)).format("YYYY-MM-DD");
											}
                                            jQuery("#acquisitionNextAction").val(_nextAction);
                                            if(_data.detail[0].litigation.complete==1){
                                                if(_data.detail[0].litigation.forward_to_review_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.create_patent_list_text == null)
                                                {
                                                    jQuery("#forward_to_review").html(" Review");
                                                }else{
                                                    jQuery("#forward_to_review").html('<span class="date-style">' + moment(new Date(_data.detail[0].litigation.forward_to_review_text)).format('MM-D-YY')+"</span> Review");
                                                }
    										}
											_mainValue = _data.detail[0].litigation.market_data;
											jQuery("#acquisitionMarketData").val(_mainValue);
											if(_mainValue!=""){
												marketData = _mainValue.split(',');
												if(marketData.length>0){
													$("#acquisitionBoxList").empty();
													$("#acquisitionBoxList").append("<ul class='todo-box-1'></ul>");
													for(mk=0;mk<marketData.length;mk++){
														$("#acquisitionBoxList").find("ul.todo-box-1").append("<li>"+marketData[mk]+"</li>");
													}
												}
											}
											jQuery("#from_nonacquistion").find("#showSellerName").html(_data.detail[0].litigation.seller_contact);
											jQuery("#acquisitionSellerContact").val(_data.detail[0].litigation.seller_contact);
											jQuery("#acquisitionOwner").val(_data.detail[0].litigation.plantiffs_name);
											jQuery("#from_nonacquistion").find("#sellerBtn").attr("onclick","openContactForFrom(1,'from_nonacquistion');");									
											jQuery("#from_nonacquistion").find("#showBrokerFirm").html(_data.detail[0].litigation.broker_contact);
											jQuery("#acquisitionBrokerContact").val(_data.detail[0].litigation.broker_contact);
											jQuery("#acquisitionBroker").val(_data.detail[0].litigation.broker);
											jQuery("#from_nonacquistion").find("#brokerFirmBtn").attr("onclick","openContactForFrom(2,'from_nonacquistion');");							
											jQuery("#from_nonacquistion").find("#showBrokerPerson").html(_data.detail[0].litigation.broker_person_contact);
											jQuery("#acquisitionBrokerPersonContact").val(_data.detail[0].litigation.broker_person_contact);
											jQuery("#acquisitionBrokerPerson").val(_data.detail[0].litigation.broker_person);
											jQuery("#from_nonacquistion").find("#brokerPersonBtn").attr("onclick","openContactForFrom(3,'from_nonacquistion');");
											jQuery("#from_nonacquistion").find("#showNameFirst").html(_data.detail[0].litigation.person_name_1);
											jQuery("#acquisitionPersonName1").val(_data.detail[0].litigation.person_name_1);
											jQuery("#acquisitionPersonTitle1").val(_data.detail[0].litigation.person_title_1);
											jQuery("#from_nonacquistion").find("#showNameBtn").attr("onclick","openContactForFrom(4,'from_nonacquistion');");
											jQuery("#from_nonacquistion").find("#showNameSecond").html(_data.detail[0].litigation.person_name_2);
											jQuery("#acquisitionPersonName2").val(_data.detail[0].litigation.person_name_2);
											jQuery("#acquisitionPersonTitle2").val(_data.detail[0].litigation.person_title_2);
											jQuery("#from_nonacquistion").find("#showNameSecondBtn").attr("onclick","openContactForFrom(5,'from_nonacquistion');");
                                            jQuery("#from_nonacquistion").find("#marketComplete").val(_data.detail[0].litigation.complete);
                                            jQuery("#acquisitionBroker").val(_data.detail[0].litigation.broker);
                                            jQuery("#acquisitionAddress").val(_data.detail[0].litigation.address);
                                            jQuery("#from_nonacquistion").find("#taskLeadId").val(_data.detail[0].litigation.id);
                                            jQuery("#acquisitionLeadId").val(_data.detail[0].litigation.id);
                                            jQuery("#acquisitionPersonName1").val(_data.detail[0].litigation.person_name_1);
                                            jQuery("#acquisitionPersonName2").val(_data.detail[0].litigation.person_name_2);
                                            jQuery("#acquisitionRelatesTo").val(_data.detail[0].litigation.relates_to);  
											<?php endif;?>
                                            leadGlobal = _data.detail[0].litigation.id;
                                            leadNameGlobal = _data.detail[0].litigation.lead_name;
											<?php 
												$openDriveBox = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(14,$this->session->userdata['modules_assign'])){
														$openDriveBox = false;
													}
												}
											if($openDriveBox===true):
											?>
											jQuery("#from_nonacquistion").find("#litigation_doc_list").empty();
											jQuery("#from_nonacquistion").find("#litigation_doc_list").addClass('docDropable').append("<ul class='todo-box-1 ' data-id='"+_data.detail[0].litigation.id+"'></ul>");							<?php endif;?>							
											<?php if($openFillForm===true):?>											
											if(_data.detail[0].litigation.spreadsheet_id!=""){
												jQuery("#from_nonacquistion").find("#acquisitionSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id);
											}
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id==""){
												findWorksheetMode(jQuery("#from_nonacquistion").find("#acquisitionSpreadsheetId"),0,_mainButtonParentElement);
											}
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id!=""){
												findWorksheetMode(jQuery("#from_nonacquistion").find("#acquisitionSpreadsheetId"), _data.detail[0].litigation.worksheet_id,_mainButtonParentElement);
											}
											<?php endif;?>											
                                        	jQuery("#scrap_patent_data_market").find('tbody').empty();
											jQuery("#taskLeadId").val(_data.detail[0].litigation.id);
											jQuery("#acquisitionLeadId").val(_data.detail[0].litigation.id);
											jQuery("#acquisitionPatentData").val(_data.detail[0].litigation.patent_data);
										}  else if(_data.detail[0].litigation.type=='Litigation' && jQuery("#litigationleadName").length > 0){
											<?php 
												$openFillForm = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(12,$this->session->userdata['modules_assign'])){
														$openFillForm = false;
													}
												}
												if($openFillForm===true):
											?>
                                            jQuery("#litigationCaseName").val(_data.detail[0].litigation.case_name);
    										jQuery("#litigationLitigationStage").val(_data.detail[0].litigation.litigation_stage);
    										jQuery("#litigationMarketIndustry").val(_data.detail[0].litigation.market_industry);
    										jQuery("#litigationCaseType").val(_data.detail[0].litigation.case_type);
    										jQuery("#litigationCaseNumber").val(_data.detail[0].litigation.case_number);
    										jQuery("#litigationCause").val(_data.detail[0].litigation.cause);
    										jQuery("#litigationNoOfPatent").val(_data.detail[0].litigation.no_of_patent);
    										jQuery("#litigationFillingDate").val(_data.detail[0].litigation.filling_date);
    										jQuery("#litigationActiveDefendants").val(_data.detail[0].litigation.active_defendants);
                                            jQuery("#litigationLeadAttorney").val(_data.detail[0].litigation.LeadAttorney);
                                            jQuery("#litigationFileUrl").val(_data.detail[0].litigation.file_url);
                                            jQuery("#from_litigation").find("#litigationleadName").val(_data.detail[0].litigation.lead_name);
                                            jQuery("#litigationId").val(_data.detail[0].litigation.id);
                                            jQuery("#from_litigation").find("#litigationLeadAttorney").val(_data.detail[0].litigation.lead_attorney);
                                            jQuery("#litigationScrapperData").val(_data.detail[0].litigation.scrapper_data);
											jQuery("#litgationPatentData").val(_data.detail[0].litigation.patent_data);
                                            jQuery("#litigationOriginalDefendants").val(_data.detail[0].litigation.original_defendants);
                                            jQuery("#litigationCourt").val(_data.detail[0].litigation.court);
                                            jQuery("#litigationLinkToPacer").val(_data.detail[0].litigation.link_to_pacer);
                                            jQuery("#litigationLinkToRPX").val(_data.detail[0].litigation.link_to_rpx);
                                            jQuery("#litigationProspects").val(_data.detail[0].litigation.no_of_prospects);
                                            jQuery("#litigationExpectedPrice").val(_data.detail[0].litigation.expected_price);
                                            jQuery("#litigationSellerInfo").val(_data.detail[0].litigation.seller_info);
                                            jQuery("#litigationProposal_letter").val(_data.detail[0].litigation.send_proposal_letter);
                                            jQuery("#litigationCreate_patent_list").val(_data.detail[0].litigation.create_patent_list);
                                            jQuery("#litigationUpfront_price").val(_data.detail[0].litigation.upfront_price);
                                            jQuery("#from_litigation").find("#litigationComplete").val(_data.detail[0].litigation.complete);
                                            if(_data.detail[0].litigation.complete==1){
                                                if(_data.detail[0].litigation.forward_to_review_text == '0000-00-00 00:00:00' || _data.detail[0].litigation.forward_to_review_text == null){
                                                    jQuery("#forward_to_review").html("Review");
                                                }else{
                                                    jQuery("#forward_to_review").html('<span class="date-style">' + moment( new Date(_data.detail[0].litigation.forward_to_review_text)).format('MM-D-YY')+"</span> Review");
                                                }
    										}
											<?php endif;?>
                                            jQuery("#taskLeadId").val(_data.detail[0].litigation.id);                                            
                                            leadGlobal = _data.detail[0].litigation.id;    
                                            leadNameGlobal = _data.detail[0].litigation.lead_name;   
											snapGlobal = _data.detail[0].litigation.file_url;
											<?php 
												$openFillForm = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(12,$this->session->userdata['modules_assign'])){
														$openFillForm = false;
													}
												}
												if($openFillForm===true):
											?>
					                        if(_data.detail[0].litigation.defendants=="" && _data.detail[0].litigation.court_docket_entries==""){
											  
												_skeltonTable = '<div class="col-sm-12 float-left" style="margin-top:5px;width:100%;padding:0;">'+
																	'<div style="width:100%;">'+
																	'<div class="col-sm-12" id="tablesOtherData">'+
															'				<h3 class="title-hero">  '+
															'					Litigation Campaign'+
															'				</h3>'+
															'				<div class="example-box-wrapper">'+
															'					<ul class="nav-responsive nav nav-tabs">'+
															'						<li class="active"><a href="#tab1" data-toggle="tab">Cases</a></li>'+
															'						<li class=""><a href="#tab2" data-toggle="tab">Defendants</a></li>'+
															'						<li><a href="#tab3" data-toggle="tab">Patents</a></li>'+
															'						<li><a href="#tab4" data-toggle="tab">Accused Products</a></li>'+
															'						<li><a href="#tab5" data-toggle="tab">Docket Entries</a></li>'+
															'					</ul>'+
															'					<div class="tab-content">'+
															'						<div class="tab-pane active" id="tab1">'+
															'							<table id="datatable-hide-columns'+_data.detail[0].litigation.id+'"  class="table table-striped table-bordered " cellspacing="0" width="100%">'+
															'								<thead>'+
															'								<tr>'+
															'									<th>Date Filed</th>'+
															'									<th>Case Name</th>'+
															'									<th>Docket Number</th>'+
															'									<th>Termination Date</th>'+
															'								</tr>'+
															'								</thead>'+
															'								<tbody></tbody>'+
															'							</table>'+
															'						</div>'+
															'						<div class="tab-pane " id="tab2">'+
															'							<table id="datatable-hide-columns'+_data.detail[0].litigation.id+'1" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
															'								<thead>'+
															'								<tr>'+
															'									<th>Date Filed</th>'+
															'									<th>Defandants</th>'+
															'									<th>Litigation</th>'+
															'									<th>Termination Date</th>'+
															'								</tr>'+
															'								</thead>'+
															'								<tbody></tbody>'+
															'							</table>'+
															'						</div>'+
															'						<div class="tab-pane" id="tab3">'+
															'							<table id="datatable-hide-columns'+_data.detail[0].litigation.id+'2" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
															'								<thead>'+
															'								<tr>'+
															'									<th>Patent #</th>'+
															'									<th>Title</th>'+
															'									<th>Est. Priority Date</th>'+
															'								</tr>'+
															'								</thead>'+
															'								<tbody></tbody>'+
															'							</table>'+
															'						</div>'+
															'						<div class="tab-pane" id="tab4">'+
															'							<table id="datatable-hide-columns'+_data.detail[0].litigation.id+'3" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
															'								<thead>'+
															'								<tr>'+
															'									<th>Date Filed</th>'+
															'									<th>Defandants</th>'+
															'									<th>Accused Products</th>'+
															'								</tr>'+
															'								</thead>'+
															'								<tbody></tbody>'+
															'							</table>'+
															'						</div>	'+
															'						<div class="tab-pane" id="tab5">'+
															'							<table id="datatable-hide-columns'+_data.detail[0].litigation.id+'4" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
															'								<thead>'+
															'									<tr>'+
															'										<th>Entry #</th>'+
															'										<th>Date Filed</th>'+
															'										<th>Date Entered</th>'+
															'										<th>Entry description</th>'+
															'									</tr>'+
															'								</thead>'+
															'								<tbody></tbody>'+
															'							</table>'+
															'						</div>'+
															'					</div>'+
															'				</div>'+
																	'	</div>'+
																	'</div>'+                                           
																'</div>';							
												jQuery("#from_litigation").find("#show_data").html(_skeltonTable);
												
											   
																	
												jQuery(function() { "use strict";
													jQuery(".tabs").tabs();
												});

												jQuery(function() { "use strict";
													jQuery(".tabs-hover").tabs({
														event: "mouseover"
													});
												});
												tabDropInit();
												
												_cUT = '<?php echo $this->session->userdata['type']?>';
												if(parseInt(_cUT)==9){
													_mainData = "";
													if(_data.detail[0].litigation.scrapper_data!="" && _data.detail[0].litigation.scrapper_data!=null){
														_mainData = jQuery.parseJSON(_data.detail[0].litigation.scrapper_data);	
													}												
													if(_mainData!="" && _mainData.output!=undefined){
														_outPut = _mainData.output;
														_leadAttorney = _outPut.LeadAttorney;
														_leadAttorney = _leadAttorney.replace(/(\r\n|\n|\r)/gm,"");
														_pacer = _outPut.pacer;
														_caseType = _outPut.casetype;
														_caseNumber = _outPut.data1;
														_market = _outPut.market;
														_stringFiled =  _outPut.data2;
														_title = _outPut.title;
														_pantiffString = _title.split('v.');				
														_tables = _outPut.Tables;
														if(_tables[1]!=undefined){
															if(_tables[1].length>0){
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id).find('tbody').empty();
																for(i=0;i<_tables[1].length;i++){
																	_dateFiled = _tables[1][i][0];
																	_caseName = _tables[1][i][1];
																	_docketNumber = _tables[1][i][2];
																	_terminationDate = _tables[1][i][3];
																	jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+">tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_caseName+'</td><td>'+_docketNumber+'</td><td>'+_terminationDate+'</td></tr>');
																}
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id).DataTable({
																	"paging": false,
																	"searching":false
																});
															} else {
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id).find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
															}														
															if(_tables[2].length>0){
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"1").find('tbody').empty();
																_activeDefandants = 0;
																for(i=0;i<_tables[2].length;i++){
																	_dateFiled = _tables[2][i][0];
																	_defandants = _tables[2][i][1];
																	_litigation = _tables[2][i][2];
																	_terminationDate = _tables[2][i][3];
																	if(_terminationDate==""){
																		_activeDefandants++;
																	}
																	jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"1>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_litigation+'</td><td>'+_terminationDate+'</td></tr>');
																}
																jQuery("#from_litigation").find("#litigationActiveDefendants").val(_activeDefandants);
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"1").DataTable( {
																	"paging": false,
																	"searching":false
																});
															} else {
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"1").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
															}
															
															if(_tables[3].length>0){
																jQuery("#from_litigation").find("#litigationNoOfPatent").val(_tables[3].length);
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"2").find('tbody').empty();
																for(i=0;i<_tables[3].length;i++){
																	_patent = _tables[3][i][0];
																	_title = _tables[3][i][1];
																	_priority_date = _tables[3][i][2];
																	jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"2>tbody").append('<tr><td>'+_patent+'</td><td>'+_title+'</td><td>'+_priority_date+'</td></tr>');
																}
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"2").DataTable( {
																	"paging": false,
																	"searching":false
																});
															} else {
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"2").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
															}
															
															if(_tables[4].length>0){
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"3").find('tbody').empty();
																for(i=0;i<_tables[4].length;i++){ 
																	_dateFiled = _tables[4][i][0];
																	_defandants = _tables[4][i][1];
																	_accusedProduct = _tables[4][i][2];
																	jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"3>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_accusedProduct+'</td></tr>');
																}
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"3").DataTable({
																	"paging": false,
																	"searching":false
																});
															} else {
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"3").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
															}
															
															if(_outPut.docket_entries_table.length>0){
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"4").find('tbody').empty();
																for(i=0;i<_outPut.docket_entries_table.length;i++){
																	__data = _outPut.docket_entries_table[i]
																	_entry = __data[1];
																	_dateFiled = __data[2];
																	_dateEntered =__data[3];
																	jQuery("#from_litigation").find =__data[4];
																	jQuery("#datatable-hide-columns"+_data.detail[0].litigation.id+"4>tbody").append('<tr><td>'+_entry+'</td><td>'+_dateFiled+'</td><td>'+_dateEntered+'</td><td>'+_entryDescription+'</td></tr>');
																}
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"4").DataTable( {
																	"paging": false							
																});
															} else {
																jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"4").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
															}
														}
													} else {
														jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"4").DataTable( {
															destroy:true,
															"paging": false,
															"language": {
																"emptyTable": "No record found!"
															}
														});
														jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"3").DataTable({
															destroy:true,
															"paging": false,
															"searching":false,
															"language": {
																"emptyTable": "No record found!"
															}
														});
														jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"1").DataTable( {
															destroy:true,
															"paging": false,
															"searching":false,
															"language": {
																"emptyTable": "No record found!"
															}
														});
														jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id+"2").DataTable( {
															destroy:true,
															"paging": false,
															"searching":false,
															"language": {
																"emptyTable": "No record found!"
															}
														});
														jQuery("#from_litigation").find("#datatable-hide-columns"+_data.detail[0].litigation.id).DataTable({
															destroy:true,
															"paging": false,
															"searching":false,
															"language": {
																"emptyTable": "No record found!"
															}
														});
													}
												} else {
													jQuery("#from_litigation").find("#show_data").html('');
												}
											} else {
												jQuery("#from_litigation").find("#show_data").html('<div class="col-sm-12 noPadding" style="overflow-y:scroll;overflow:x:none;height:400px;"><div class="col-sm-6 noPadding" id="defendant"><img src="'+_data.detail[0].litigation.court_docket_entries+'" style="width:490px;"/></div><div class="col-sm-6" id="court_docket"><img src="'+_data.detail[0].litigation.court_docket_entries+'" style="width:490px;"/></div></div>');
											}	
											<?php endif;?>											
											
																						
											<?php if($openFillForm===true):?>											
											if(_data.detail[0].litigation.spreadsheet_id!=""){
												jQuery("#from_litigation").find("#litigationSpreadsheetId").val(_data.detail[0].litigation.spreadsheet_id);
											}
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id==""){
												findWorksheet(jQuery("#from_litigation").find("#litigationSpreadsheetId"));
											}											
											if(_data.detail[0].litigation.spreadsheet_id!="" && _data.detail[0].litigation.worksheet_id!=""){
												findWorksheet(jQuery("#from_litigation").find("#litigationSpreadsheetId"), _data.detail[0].litigation.worksheet_id);				
											}
											<?php endif;?>
											
											<?php 
												$openDriveBox = true;
												if((int)$this->session->userdata['type']!=9){
													if(!in_array(14,$this->session->userdata['modules_assign'])){
														$openDriveBox = false;
													}
												}
											if($openDriveBox===true):
											?>	
											jQuery("#from_litigation").find("#litigation_doc_list").empty();
											jQuery("#from_litigation").find("#litigation_doc_list").addClass('docDropable').append("<ul class='todo-box-1' data-id='"+_data.detail[0].litigation.id+"'></ul>");	
											<?php endif;?>
											jQuery("#from_litigation").find("#scrap_patent_data").find('tbody').empty();
											jQuery("#from_litigation").find("#litigationId").val(_data.detail[0].litigation.id);
											jQuery("#from_litigation").find("#litigationPatentData").val(_data.detail[0].litigation.patent_data);									
											/**/
										}
					                    windowResize();
					                    // Body scrollable
					                    checkBodyScrollable();
									}
								}
							}).done(function(){
								jQuery.ajax({
									type:'POST',
									url:'<?php echo $this->config->base_url();?>leads/findEmailBoxes',
									data:{boxes:leadGlobal,type:0},
									cache:false,
									success:function(res){
										_data = jQuery.parseJSON(res);	
										if(_data.boxes.length>0){
											_table = jQuery('<table/>').addClass('table').css({border: 'none'})
											_tbody = jQuery('<tbody/>');												
											for(i=0;i<_data.boxes.length;i++){												
												_showData = "";
												_d = _data.boxes[i];
												_receivedDate = "";
												_messageIDDD = "";
												_email="";
												__a = "<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+",\""+_d.id+"\")'><i class='glyph-icon icon-close'></i></a>";												
												if(_d.content!=""){
													_contents = jQuery.parseJSON(_d.content);
													if(_contents.length>0){
														for(c=0;c<_contents.length;c++){
															_content = _contents[c];
															header = _content.header;
															if(header.length>0){
																_showData="";
																for(h=0;h<header.length;h++){
																	if(header[h].name=="Subject"){
																		_showData="<i class='glyph-icon icon-envelope pull-left' style='color:#2196f3'></i> <a class='pull-left pad5L' style='width:82%' href='javascript:void(0)' onclick='findOwnThread(\""+_d.id+"\",jQuery(this),1);'>"+header[h].value+"</a>";
																	}
																	if(header[h].name=="From"){	
																		_email = header[h].value;
																	}
																	if(header[h].name=="Date"){
																		_receivedDate = header[h].value;
																	}
																	if(header[h].name=="Message-ID"){
																		_messageIDDD = header[h].value;	
																	}
																}
															}
															if(c==_contents.length-1){
																if(_showData!=""){
																	_tr = jQuery('<tr/>').attr('data-i-id',_d.user_id).addClass('message');
																	jQuery(_tr).addClass(_d.id).addClass(_content.message_id).addClass('emailDragable').addClass('fromEmails').attr('email',_email).attr('data-message-id',_messageIDDD).attr('data-id',_d.thread_id).append("<td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_showData+"<span class='pull-right email-close hide' >"+__a+"</span></td>");
																	if(ato!=undefined){
																		if(_content.message_id==ato){
																			jQuery(_tr).addClass('activetr');
																		}
																	}
																	if(_d.sent_from==1){
																		jQuery(_tr).addClass('grey');
																	}
																	jQuery(_tbody).append(_tr);
																}
															}															
														}														
													}
												}
												if(_d.file_attach!="" && _d.file_attach!="0"){
													_files = _d.file_attach.split(',');
													if(_files.length>0){
														for(f=0;f<_files.length;f++){
															if(_files[f]!=""){
																filename = _files[f].indexOf('upload');
																if(filename>0){
																	filename = _files[f].substr(filename + 7);
																	translated = escapedString(_files[f]);
																	_showData="<a data-href='"+translated+"' href='javascript://'  target='_BLANK'><i class='glyph-icon icon-file' style='color:#2196f3'></i> "+filename+"</a>";
																	_tr = jQuery('<tr/>').addClass('attach');
																	jQuery(_tr).addClass(_content.message_id).addClass('docDragable').append("<td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_showData+"</td>");
																	jQuery(_tr).find('a').attr('data-mime','').attr("onclick","open_drive_files(\""+translated+"\")");
																	jQuery(_tbody).append(_tr);															
																}																	
															}
														}
													}
												}	
												
											}
											jQuery(_table).append(_tbody);
											jQuery("#other_list_boxes").empty().append(_table);
											jQuery('tr.message').each(function (){
												if(jQuery(this).next().hasClass('attach')){
													jQuery(this).find('td').css('border-bottom-width','0px');
													jQuery(this).next().find('td').css('border-top-width','0px');
												}
											});	
											initHoverEmailClose();											
										} else {
											jQuery("#other_list_boxes").empty();
										}										
									}
								}).done(function(){
									jQuery.ajax({
										type:'POST',
										url:'<?php echo $this->config->base_url();?>leads/findDriveFiles',
										data:{boxes:leadGlobal},
										cache:false,
										success:function(res){
											_data = jQuery.parseJSON(res);
											_drive = _data.drive;
											if(_drive.length>0){	
												_container = "";
												if(jQuery("#from_regular").is(":visible")){
													_container = "#from_regular";
												} else if(jQuery("#from_litigation").is(":visible")){
													_container = "#from_litigation";
												} else if(jQuery("#from_nonacquistion").is(":visible")){
													_container = "#from_nonacquistion";
												}
												jQuery(_container).find("#litigation_doc_list>ul").empty();
												for(d=0;d<_drive.length;d++){													
													if(_drive[d].mimeType=="application/pdf" || _drive[d].mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || _drive[d].mimeType=="application/msword" || _drive[d].mimeType=="image/jpeg" || _drive[d].mimeType=="image/png"){
														url = "https://docs.google.com/file/d/"+_drive[d].id+"/preview";
														jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable "><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" target="_BLANK" href="javascript://" class="drive_file_click" data-mime="'+_drive[d].mimeType+'" onclick="open_drive_files(\''+url+'\')">'+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>');
														
													} else {
														jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable"><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" data-mime="'+_drive[d].mimeType+'" target="_BLANK" href="javascript://" class="drive_file_click"   onclick="open_drive_files(\''+_drive[d].alternateLink+'\')">'+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>');
													}
													
												}
											}  
											docFileDraggable();
											driveFileDraggable();
											initHoverEmailClose();
										}
									});
								});
							});
							enableActionRightAgain();												
						}
					} 
					
					function opportunityRedirect(thread){
						window.location = '<?php echo $this->config->base_url()?>opportunity/dummy_opportunity/'+thread;
					}
					_referenceString = "";
					function findThread(thread,object,flag){
						__dashFlag = true;
						leadGlobal= 0;
						leadNameGlobal="";
						if(typeof(flag)==="undefined"){
							flag=0;
						}
						jQuery('.mCSB_container').find('.message-item').each(function(){
							if(jQuery(this).attr('data-id')==jQuery.trim(thread)){
								if(flag==0){							
									object.parent().parent().parent().find('a').css('color','#FFF');
									object.parent().parent().parent().addClass('message-active');
									object.parent().parent().parent().find('h5').removeClass('c-dark');
									object.parent().parent().parent().find('h4').removeClass('c-dark');
									object.parent().parent().parent().find('p').removeClass('c-gray');
									object.parent().parent().parent().find('a').removeClass('c-dark');
									jQuery("#gmail_message").hide();
									jQuery("#from_regular").hide();
									jQuery("#from_litigation").hide();
									jQuery("#from_nonacquistion").hide();
									if(jQuery("#myDashboardComposeEmails").length>0){
										jQuery("#myDashboardComposeEmails").get(0).reset();
									}
									$('#all_type_list tbody tr').removeClass('active');
									$('#all_type_list tbody td').removeClass('active');
									$('#other_list_boxes').empty();
								}
							} else {
								jQuery(this).removeClass('message-active');
								jQuery(this).find('h5').addClass('c-dark');
								jQuery(this).find('h4').addClass('c-dark');
								jQuery(this).find('p').addClass('c-gray');
								jQuery(this).find('a').addClass('c-dark');
							}
							
						});
						$('#other_list_boxes .message td').removeClass('active');
						if(!flag) {
							$('#all_type_list tbody td').removeClass('active');
						}
						if(flag) {
							object.parent().addClass('active');
						}
						jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
							jQuery(this).removeClass('active');
							jQuery("#marketLead").get(0).reset();
						});
						 var iframeHeight = jQuery('#message-detail').height() - jQuery('#message-detail .panel-heading').outerHeight() + 12;
						 iframeHeight = 296;
						 jQuery('#displayEmail').html('<iframe src="'+__baseUrl+'users/email/'+jQuery.trim(thread)+'" scrolling="yes" width="100%" height="' + iframeHeight + '">');
					}
					
					jQuery(document).ready(function(){						
						jQuery('#emailOpenModal').on('hidden.bs.modal', function () {
							jQuery('body').attr('onselectstart','return false');
							document.oncontextmenu=new Function("return false");
						});
					});
					
					function composeEmail(ds){
						if(leadGlobal==0){
							alert("Please select lead first");
						} else {
							if(ds==undefined){
								jQuery("#emailDocUrl").val('');
								jQuery("#attach_droppable").empty();
							}
							jQuery("#anotherSys").val(0);	
							jQuery.ajax({
								type:'POST',
								url:"<?php echo $this->config->base_url()?>dashboard/search_contact",
								cache:false,
								success:function(data){
									availableTags = jQuery.parseJSON(data);	
									jQuery("#emailCC").css("width","725px");
									$('body').append('<div class="modal-backdrop modal-backdrop-drive"></div>');
									jQuery("#gmail_message_modal").css('display','block')
										.addClass("sb-active")
										.animate({ textIndent:0}, {
											step: function(now,fx) {
											  $(this).css('transform','translate(350px)');
											},
											duration:'slow'
										}, 'linear');
								}
							});
							jQuery('body').removeAttr('onselectstart');
							document.oncontextmenu=new Function("return true");
							$('.dropdown-toggle').dropdown();							
							jQuery("#emailThreadId").val('');
							jQuery("#emailMessageId").val('');
							jQuery("#attach_droppable").empty();
							jQuery("#emailSubject").removeAttr("readonly");
							jQuery("#gmail_message").css("display","block");
							jQuery(".gmail-modal").css("display","block");
							if(mainLogBox==1){
								jQuery("#legal_log").val(1);
							} else {
								jQuery("#legal_log").val(0);
							}
							jQuery("#gmail_message_modal").find('h4').html("Compose Message: "+leadNameGlobal);
							jQuery("#messageLeadId").val(leadGlobal);
							jQuery('body').data('modalzindex', jQuery("#gmail_message_modal").css('z-index'));
							jQuery("#emailTo").focus();
						}						
					}
					jQuery(document).ready(function(){						
						jQuery('#gmail_message_modal').on('hidden.bs.modal', function () {
							jQuery('body').attr('onselectstart','return false');
							document.oncontextmenu=new Function("return false");
						});
					});
					function discardEmail(){
						jQuery("#myDashboardComposeEmails").get(0).reset();
						jQuery("#emailThreadId").val('');
						jQuery("#emailMessageId").val('');
						jQuery("#gmail_message_modal").hide();
						jQuery("#attach_droppable").empty();
						jQuery("#emailTo").focus();
						checkBodyScrollable();
					 	windowResize();
					}
					
					function removeAlert(){
						jQuery('.alert-close-btn').click(function(e){
							e.preventDefault();
							jQuery(this).parent().remove();
						});
					}
					___check = "";
					
					function getEmails(object,type,mainObject){
						jQuery(".emails-group-container").find('.list-group-item').removeClass('active');
						mainObject.addClass('active');
						_string = '<div class="loading-spinner" id="loading_spinner_heading_messages" style="display:none;"><img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt=""></div>';
						jQuery("."+object).html(_string);						
						jQuery("#loading_spinner_heading_messages").css('display','block');
						jQuery.ajax({
							type:'POST',
							url:'<?php echo $this->config->base_url()?>users/getOldEmails',
							data:{type:type},
							cache:false,
							success:function(data){
								jQuery("#loading_spinner_heading_messages").css('display','none');
								jQuery("."+object).html(data);
								if(type!='DRAFT'){
									initDragDrop();
								}								
							}
						});
					}					
					
					function getNewEmails(object,type,mainObject){
						jQuery(".emails-group-container").find('.list-group-item').removeClass('active');
						mainObject.addClass('active');
						_string = '<div class="loading-spinner" id="loading_spinner_heading_messages" style="display:none;"><img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt=""></div>';
						jQuery("."+object).html(_string);						
						jQuery("#loading_spinner_heading_messages").css('display','block');
						runRetrieveNew();
					}
					
					function openNewLeadDialogueBox(){
						jQuery("#from_litigation").hide();
						jQuery("#from_regular").hide();
						jQuery("#newLeadFormElement").modal('show');
						jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");
						return false;
					}
					__TableDT = "";
					function toggleTableFilter($link) {
						var $filterRow = $link.parent().parent().parent().parent().parent().parent().parent().parent().prev();
						$filterRow.toggle();
						$('#all_type_list_wrapper .dataTables_scrollBody').toggleClass('is-small');

						return false;
					}

					function leadsTableOneLineCells() {
						$('.one-line-cell').each(function(index, obj) {
							var $obj = $(obj);
							$obj.html($obj.html().replace(/ /g, '&nbsp;'));
						});
					}
				</script>
		<div class="page-mailbox" id="main-content">
						<div data-equal-height="true" class="row" style="margin-right:-9px;">
							<div class="col-md-8 list-messages" style='padding:0px;max-height:302px;'>
								<div class="row row-width" style="margin:0;">
									<div class="col-width" style='padding:0px; width:73px;'>
										
										<div class="panel panel-default panel-no-margin panel-blue-border" style="height:302px;">
											<?php if($openEmailBox===true):?>
											<button class="btn btn-default btn-block text-left" type="button" onclick="composeEmail()" style="padding-left:8px; margin-bottom:-1px; margin-top: -1px; border-left: medium none; border-right: medium none;">Compose</button>
											<div class="list-group emails-group-container">
												<a href="javascript:void(0);" data-title="INBOX" onclick="getEmails('messages_container','INBOX',jQuery(this));" class="list-group-item active ">Inbox</a>
												<a href="javascript:void(0);" data-title="STARRED" onclick="getEmails('messages_container','STARRED',jQuery(this));" class="list-group-item label-dropable">Starred</a>
												<a href="javascript:void(0);" data-title="LEAD" onclick="getEmails('messages_container','LEAD',jQuery(this));" class="list-group-item ">Leads</a>
												<a href="javascript:void(0);" data-title="NONLEAD" onclick="getEmails('messages_container','NONLEAD',jQuery(this));" class="list-group-item label-dropable">NonLead</a>
												<a href="javascript:void(0);" data-title="DRAFT" onclick="getEmails('messages_container','DRAFT',jQuery(this));" class="list-group-item">Draft</a>
												<a href="javascript:void(0);" data-title="SENT" onclick="getEmails('messages_container','SENT',jQuery(this));" class="list-group-item">Sent</a>
												<a href="javascript:void(0);" data-title="TRASH" onclick="getEmails('messages_container','TRASH',jQuery(this));" class="list-group-item label-dropable">Trash</a>
												<a href="javascript:void(0);" onclick="getNewEmails('messages_container','',jQuery(this));" class="list-group-item btn-primary btn-block">Retreive</a>
											</div>
											<?php endif;?>
										</div>
									</div>
									<div class="col-xs-6" style='padding:0px; margin-top:0; margin-left: -1px; z-index:1;'>
										<div class="panel panel-default panel-no-margin panel-blue-border" style="height:302px;">
											<?php if($openEmailBox===true):?>
											<div class="messages messages-list-leads">
												<div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-list" >
												<div style="max-height:300px;min-height:300px; overflow-x:hidden !important; overflow-y:auto !important; max-width:100%;" id="mCSB_116" class="mCustomScrollBox mCS-dark-2">
												<div style="max-width:100%; overflow:hidden;" class="mCSB_container messages_container">
													<div class="loading-spinner" id="loading_spinner_heading_messages" style='display:none;'>
														<img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt="">
													</div>
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
																		$date = date('M d, Y',strtotime($date));
																	}
																	if($header->name=="Message-ID"){
																		$messageIDDD = $header->value;
																	}
																}
															
													?>
															<div class="message-item media  draggable" data-date='<?php echo $date?>' data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>">										
																<div class="message-item-right">
																	<div class="media">																
																		<div class="media-body"  onclick="findThread('<?php echo $message['message_id']?>',jQuery(this));">
																			<h5 class="c-dark">
																				<?php 
																					if(in_array(strtoupper('unread'),$message['labelIds'])){
																				?>
																						<strong><a class="c-dark" href="javascript:void(0)"><?php echo $from;?></a></strong>
																				<?php
																					} else {
																				?>
																						<a class="c-dark" style='font-weight:normal' href="javascript:void(0)"><?php echo $from;?></a>
																				<?php
																					}
																				?>
																				
																			</h5>
																			<h4 class="c-dark"><?php echo $subject;?></h4>
																			<div>
																				<span class="message-item-date"><?php echo $date;?></span>
																				&nbsp;
																				<?php 
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
																					if($countAttachments>0):
																				?>
																				<strong><i class="glyph-icon icon-paperclip"></i> <?php echo $countAttachments;?></strong>
																				<?php endif;?>
																			</div>
																		</div>
																	</div>																
																</div>
															</div>
													<?php
																}
															
															}
															}
													
														}
													?>
												</div>
												</div></div>
											</div>  
											<?php endif;?>
										</div>
									</div>
									<div class="col-width list-messages" style='padding: 0px; margin-top: 0; margin-left: -1px; margin-right: -1px; width:617px;'>
										<div class="panel panel-default panel-no-margin panel-blue-border" id="old_lead">										
											<div class="messages">
												<div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-boxlist" >
													<div style="height:300px; overflow-x:hidden !important; overflow-y:auto !important; max-width:100%;" id="mCSB_116" class="mCustomScrollBox mCS-dark-2">
														<script>
															var tableDT ="";
															$(document).ready(function() {
																tableDT = $('#all_type_list').DataTable( {
																	"scrollY": "270px",
																	scrollX:        true,
																	scrollCollapse: true,searching:true,
																	"paging": false,
																	"fnInitComplete": function(oSettings, json) {
																		__TableDT = jQuery("#all_type_list").find('tbody').html();
																	}
																}); 																
															});
														</script>
														<table class="table" id="all_type_list" style="border-left:none; border-right:none;width:599px !important;">
															<thead>
																<tr>
																	<th class="no-sort" style="text-align:center; border-right:none; border-left:none;width:200px;">
																		<div class="prelative">
																			<?php $openCreateLead = true;
																				if((int)$this->session->userdata['type']!=9){
																					if(!in_array(17,$this->session->userdata['modules_assign'])){
																						$openCreateLead = false;
																					}
																				}
																				if($openCreateLead===true):
																			?>
																			<a href='javascript://' class='btn btn-primary pabsolute' style='left: 0px; padding: 0px; min-width: 0px; border-radius: 3px; width: 18px; line-height: 16px; height: 18px;' onclick='return openNewLeadDialogueBox();'>+</a>
																			<?php endif;?>
																			<span>Lead</span>
																			<a href='javascript://' class='pabsolute' style='right: 0px; padding: 0px; min-width: 0px; border-radius: 3px; width: 18px; line-height: 16px; height: 18px;' onclick='return toggleTableFilter($(this));'>
																				<i class="glyph-icon icon-search"></i>
																			</a>
																		</div>
																	</th>
																	<th style="text-align:left; border-right:none; border-left:none;width:45px;"><span title="Type">Type</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;width:60px;" ><span title="Info">Info</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;width:60px;"><span title="Seller">Seller</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;width:60px;"><span title="Synpat">Synpat</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;width:60px;"><span title="PPA">PPA</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;width:60px;"><span title="Close">Close</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;"><span title="Broker">Broker</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;"><span title="Seller">Seller</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;"><span title="Title1">Title1</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;"><span title="Title2">Title2</span></th>
																	<th style="text-align:left; border-right:none; border-left:none;"><span title="Tech. / Markets">Tech. / Markets</span></th>
																</tr>
															</thead>
															<tbody>
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
																	if($message->seller_info_text!="" && $message->seller_info_text!=null){
																		$sellerInfo = date('m d,y',strtotime($message->seller_info_text));
																	}
																	$sellerLike = "";
																	if($message->seller_like!="" && $message->seller_like!=null){
																		$sellerLike = date('m d,y',strtotime($message->seller_like));
																	}
																	$synpatLike = "";
																	if($message->synpat_like!="" && $message->synpat_like!=null){
																		$synpatLike = date('m d,y',strtotime($message->synpat_like));
																	}
																	$ppa = "";
																	if($message->ppa_date!="" && $message->ppa_date!=null){
																		$ppa = date('m d,y',strtotime($message->ppa_date));
																	}
																	$fundingTrnsfr = "";
																	if($message->funding_trnsfr!="" && $message->funding_trnsfr!=null){
																		$fundingTrnsfr = date('m d,y',strtotime($message->funding_trnsfr));
																	}
																	$sellerClass = "";
																	if($message->seller_info==1){
																		$sellerClass = "btn-blink";
																	}
																	if($mainFlag == 0){
														?>			
																	
																	<tr class="border-blue-alt droppable old_lead <?php echo $main;?>" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" onclick="threadDetail(jQuery(this));" <?php if($stage=="Oppt."):?>ondblclick="opportunityRedirect('<?php echo $message->id?>');"<?php endif;?>>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:200px;" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" class=""><label><a style='text-align:left;' title="<?php echo $message->lead_name;?>" class='btn' href="javascript:void(0);"><?php echo substr($message->lead_name,0,30);?></a></label></td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:45px;"><?php echo $type;?></td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;" class='<?php echo $sellerClass;?> one-line-cell'><?php echo $sellerInfo;?></td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">
																			<div style="white-space:nowrap;">
																				<?php echo $sellerLike;?>
																			</div>
																		</td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">
																			<div style="white-space:nowrap;">
																				<?php echo $synpatLike;?>
																			</div>
																		</td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">
																			<div style="white-space:nowrap;">
																				<?php echo $ppa;?>
																			</div>
																		</td>
																		<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">
																			<div style="white-space:nowrap;">
																				<?php echo $fundingTrnsfr;?>
																			</div>
																		</td>
																		<td class="one-line-cell" style="padding:3px 2px; border-right:none; border-left:none;"><?php echo $message->broker_person_contact;?></td>
																		<td class="one-line-cell" style="padding:3px 2px; border-right:none; border-left:none;">
																			<?php 
																				if(empty($message->seller_contact)){
																					echo $message->plantiffs_name;
																				} else {
																					echo $message->seller_contact;
																				}
																				?>
																		</td>
																		<td class="one-line-cell" style="padding:3px 2px; border-right:none; border-left:none;"><?php echo $message->person_name_1;?></td>
																		<td class="one-line-cell" style="padding:3px 2px; border-right:none; border-left:none;"><?php echo $message->person_name_2;?></td>
																		<td class="one-line-cell" style="padding:3px 2px; border-right:none; border-left:none;"><?php echo $message->relates_to;?></td>
																	</tr>											
														<?php
																	} 
																}
															}
														?>		
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-6 list-messages" style='padding:0px; margin-top: 0;'>
										<div class="panel panel-default panel-no-margin panel-blue-border" style='min-height:302px;max-height:302px;height:302px;overflow-y:scroll; margin-right:2px;'>
											<div id="other_list_boxes"></div>
										</div>
									</div>
								</div>
							</div>							
							<div class="col-lg-4 col-md-4 email-hidden-sm detail-message" style="padding:0px; margin-top:0; margin-left:-8px; border: 1px solid #2196f3; height: 302px; background:#ffffff;" id="displayEmail"></div>
						</div>
					</div>
		<?php
					
		else:		
		?>
		<script>		
			window.location = '<?php echo $service->createAuthUrl()?>';			
		</script>
	<?php
		endif;
		die;
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */