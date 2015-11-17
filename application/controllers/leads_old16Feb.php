<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leads extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(!isset($this->session->userdata['type'])){
			$this->session->set_flashdata('error','Please login first!');
			redirect('login');
		}
		$this->clear_cache();
		$this->layout->layout='default';
		$this->load->model('lead_model');
		$this->load->model('email_model');
		$this->load->model('user_model');
		$this->load->model('general_model');
		$this->load->model('opportunity_model');
	}
	
	public function clear_cache(){
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
	public function index()
	{
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('client/index');
	}
	public function litigation(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$litigationData = $this->input->post();
			
            $litigationData['litigation']['user_id']= $this->session->userdata['id'];
			$litigationData['litigation']['create_date']= date('Y-m-d H:i:s');
			$litigationData['litigation']['type']= 'Litigation';
			//$litigationData['litigation']['lead_name']= 'Litigation';
            $attractive = $litigationData['litigation']['attractive'];
			unset($litigationData['litigation']['attractive']);
			if(!isset($litigationData['comment'])){
				$litigationData['comment'] = 0;
			}
			if(!isset($litigationData['complete'])){
				$litigationData['complete'] = 0;
			}
			$user_id  = $this->session->userdata['id'];
			if((int)$litigationData['litigation']['id']==0){
				$saveLitigation = $this->lead_model->from_litigation_insert($litigationData['litigation']);
				$lead_id = $this->db->insert_id();				
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
							/*$this->general_model->updateFolderIDByLead($folderID,$lead_id);*/
							$this->lead_model->from_litigation_update($lead_id,array('folder_id'=>$getFolderInfo));
							$service->setPermissions( $getFolderInfo, SHARE_WITH_GOOGLE_EMAIL );						
							$service->setPermissions( $getFolderInfo, SHARE_WITH_GOOGLE_EMAIL_ANOTHER );						
						}
					}						
					/**/
					$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Created lead From Litigation.','create_date'=>$litigationData['litigation']['create_date']));
					$this->session->set_flashdata('message','Record added.');
				}			
			}else {
				$saveLitigation = $this->lead_model->from_litigation_update($litigationData['litigation']['id'],$litigationData['litigation']);
				$lead_id = $saveLitigation;
				$this->session->set_flashdata('message','Record updated.');
				$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Update lead From Litigation.','create_date'=>$litigationData['litigation']['create_date']));
				
			}
			
			if($saveLitigation>0){			 
				if((int)$litigationData['other']['id']==0){
					$this->lead_model->from_litigation_comment(array('parent_id'=>$saveLitigation,'type'=>'Litigation','user_id'=>$this->session->userdata['id'],'comment1'=>$litigationData['comment']['comment1'],'comment2'=>$litigationData['comment']['comment2'],'comment3'=>$litigationData['comment']['comment3'],'created'=>date('Y-m-d H:i:s'),'attractive'=>$attractive));
                    				
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$saveLitigation,'message'=>'Insert comment.','create_date'=>date('Y-m-d H:i:s')));
				} else {
				    $this->lead_model->from_litigation_update_comment(array('id'=>$litigationData['other']['id'],'comment1'=>$litigationData['comment']['comment1'],'comment2'=>$litigationData['comment']['comment2'],'comment3'=>$litigationData['comment']['comment3'],'attractive'=>$attractive));
                    				
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$saveLitigation,'message'=>'Update comment.','create_date'=>date('Y-m-d H:i:s')));
				}				
				if(isset($litigationData['litigation']['complete'])){
					redirect('leads/lead_forward/'.$saveLitigation);
				} else {
					redirect('leads/litigation');
				}           
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/litigation');
			}
		}
		$this->load->library("DriveServiceHelper");
		$service = new DriveServiceHelper();
		$data['listOfFiles'] = $service->getFileIDFromChildern("0B_Do1Yd0xSmXfmpoQ3BmMldhVTNSQ2R6dm03bjZFLUMteHc2UDBFRkhDR2d0YW5rMERobUU");
		$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('Litigation',0);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Litigation';
		$this->layout->render('leads/litigation',$data);
	}
 
	public function lead_forward($id){
        $url = 'leads/litigation_review';
        $data['userPageAssigned'] = $this->lead_model->getUserPageAssign($url);
        $data['LeadForward'] = $this->lead_model->getLeadData($id);
        if(count($data['userPageAssigned'])== 0){           
            redirect('leads/litigation');
        }
	    $this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Forward Lead';
		$this->layout->render('leads/lead_forward',$data);
	}
	public function sent_lead(){
        $lead = $this->input->post();
        $type = "LEAD_FORWARD";
        $url = $this->config->base_url().'leads/litigation_review/'.$lead['lead_id'];
	    $getData = $this->general_model->getTaskAccToType('LEAD_FORWARD');
		$subject="Lead Forward";
		$message = "Lead forward to you.";
		if(count($getData)>0){
			$subject = $getData->subject;
			$message = $getData->message;
		}
		/*End Checking*/
		$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$lead['lead_forward'],'lead_id'=>$lead['lead_id'],'doc_url'=>$url,'type'=>$type,'status'=>'0'));
          
       $msg = "Forwarded a Lead";
       $user_history = array('lead_id'=>$lead['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>$msg,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
       $this->user_model->addUserHistory($user_history);
       redirect('leads/litigation');
	}
	function litigation_execute(){
		if((int)$this->session->userdata['type']==9){			
			$perPage = 1;
			$start = 1;
			$data["total_rows"] = $this->lead_model->record_count('Litigation',2);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('Litigation',$perPage,$start-1,2);	
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['templateData'] = $this->email_model->getTemplate();
			$this->layout->auto_render=false;		
			$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('Litigation',2);
			$this->layout->title_for_layout = 'Backyard From Litigation';
			$this->layout->render('leads/litigation_execute',$data);
		} else {
			$this->session->set_flashdata('error','You are not authorize for this page.');
			redirect('dashboard');
		}
	}
	
	public function litigation_review($id=null){
	   if($id==null)
       {
            $data = array();
    		$perPage = 1;
    		$start = 1;
    		$data["total_rows"] = $this->lead_model->record_count('Litigation');
    		$data['results'] = $this->lead_model->findAllLitigationWithPaging('Litigation',$perPage,$start-1);	
    		$data['current_page'] = $start; 
			$data['no_pagination'] = false;
    		$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
       }
       else
       {
            $data['no_pagination'] = true;
            $data["total_rows"] = 1;
            $data['current_page'] = 1;
			$data['no_of_pages'] = 1;
           /* $type = $this->db->query('select type from litigations where id='.$id)->row()->type;*/
            $data['results'] = $this->lead_model->findOneLitigationWithPaging($id,'Litigation');
       }
	    $data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('Litigation',1);
        $this->layout->auto_render=false;		
        $this->layout->title_for_layout = 'Backyard From Litigation';
        $this->layout->render('leads/litigation_review',$data);
		
	}
	
	public function litigation_record(){
		if(isset($_POST) && count($_POST)>0){
			$data = array('results'=>array());
			try{
				$data['current_page'] = $this->input->post('token');
				$type = $this->input->post('type');
				if(!empty($data['current_page'])){
					$data['current_page'] = base64_decode($data['current_page']);
				}				
				$perPage = 1;
				$data["total_rows"] = $this->lead_model->record_count($type,$this->input->post('complete'));
				$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
				if($this->input->post('level')=='next'){
					$start = (int)$data['current_page']+1;		
				} else {
					$start = (int)$data['current_page']-1;		
				}		
				if($start<=0){
					$start = 1;
				}		
				$data['current_page'] = $start;	
				if($data['current_page']>=0){
					if($data['current_page']<=$data['no_of_pages']){						
						$data['results']= $this->lead_model->findAllLitigationWithPaging($type,$perPage,$start-1,$this->input->post('complete'));
                       // 
                        if(count($data['results'])>0){
                            //echo count($data['results']);
                            //$data['timeLine'] = $this->user_model->getAllUserHistory(0,$data['results'][0]['litigation']->id,0);
                            $data['timeLine'] = $this->user_model->getAllUserHistory(0,$data['results'][0]['litigation']->id,0);
                        } else{
                            $data['timeLine'] = array();
                        }                        		
						if(count($data['results'])>0){
							if($type=="Market"){
								if(isset($data['results'][0]['litigation']->id)){
									$listBox = $this->lead_model->findBoxList($data['results'][0]['litigation']->id);
									$data['boxes']=array();
									if(count($listBox)>0){
										foreach($listBox as $box){
											$this->load->library('DriveServiceHelper');
											if(session_id() == '') {
												session_start();
											}
											$service = new GmailServiceHelper();
											if(isset($_SESSION['access_token'])){
												$service->setAccessToken($_SESSION['access_token']);
												$list = $service->findThreadData($box->thread_id);
												if(count($list)>0){
													foreach($list as $m){
														$data['boxes'][] = array("message_id"=>$m['message_id'],"header"=>$m['header'],"type"=>"Message","parent_id"=>$box->thread_id);
														if(count($m['attachments'])>0){
															foreach($m['attachments'] as $attachment){
																$data['boxes'][] = array("message_id"=>$m['message_id'],'realAttachID'=>$attachment['realAttachID'],'size'=>$attachment['size'],"attachmentId"=>$attachment['attachmentId'],"filename"=>$attachment['filename'],"mimeType"=>$attachment['mimeType'],"type"=>"Attachment","header"=>$m['header'],"parent_id"=>$box->thread_id);
															}									
														}
													}
												}
											}
										}
									}
									$data['results'][0]['market'] = 	$data['boxes'];			
								}
							}
                          //  print_r($lead_history);
						}
						$data['token'] = base64_encode($data['current_page']);
					}
				}  else {
					$data['current_page'] = 0;
					$data['total_rows'] = 0;
					$data['no_of_pages'] = 0;
				}
			} catch(Exception $e){
			
			}
			echo json_encode($data);
		}
		die;
	}
	
	
	public function litigation_record_incomplete(){
		if(isset($_POST) && count($_POST)>0){
			$data = array('results'=>array());
			try{
				$data['current_page'] = $this->input->post('token');				
				$type = $this->input->post('type');
				if(!empty($data['current_page'])){
					$data['current_page'] = base64_decode($data['current_page']);
				}				
				$perPage = 1;
				$data["total_rows"] = $this->lead_model->record_count($type,0);
				$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
				if($this->input->post('level')=='next'){
					$start = (int)$data['current_page']+1;		
				} else {
					$start = (int)$data['current_page']-1;		
				}		
				if($start<=0){
					$start = 1;
				}
				$data['current_page'] = $start;	
				if($data['current_page']>=0){	
					if($data['current_page']<=$data['no_of_pages']){					
						$data['results']= $this->lead_model->findAllLitigationWithPaging($type,$perPage,$start-1,0);
                        if(count($data['results'])>0){
                            //echo count($data['results']);
                            //$data['timeLine'] = $this->user_model->getAllUserHistory(0,$data['results'][0]['litigation']->id,0);
                            $data['timeLine'] = $this->user_model->getAllUserHistory(0,$data['results'][0]['litigation']->id,0);
							$this->load->library('DriveServiceHelper');
							$service = new DriveServiceHelper();
							if(!empty($data['results'][0]['litigation']->folder_id)){
								$data['drive'] = $service->getFileIDFromChildern($data['results'][0]['litigation']->folder_id);
							} else {
								$data['drive'] = array();
							}
                        } else{
                            $data['timeLine'] = array();
                            $data['drive'] = array();
                        }  		
						if(count($data['results'])>0){
							if($type=="Market"){
								if(isset($data['results'][0]['litigation']->id)){
									$listBox = $this->lead_model->findBoxList($data['results'][0]['litigation']->id);
									$data['boxes']=array();
									if(count($listBox)>0){
										foreach($listBox as $box){
											$this->load->library('DriveServiceHelper');
											if(session_id() == '') {
												session_start();
											}
											$service = new GmailServiceHelper();
											if(isset($_SESSION['access_token'])){
												$service->setAccessToken($_SESSION['access_token']);
												$list = $service->findThreadData($box->thread_id);
												if(count($list)>0){
													foreach($list as $m){
														$data['boxes'][] = array("message_id"=>$m['message_id'],"header"=>$m['header'],"type"=>"Message","parent_id"=>$box->thread_id);
														if(count($m['attachments'])>0){
															foreach($m['attachments'] as $attachment){
																$data['boxes'][] = array("message_id"=>$m['message_id'],'realAttachID'=>$attachment['realAttachID'],'size'=>$attachment['size'],"attachmentId"=>$attachment['attachmentId'],"filename"=>$attachment['filename'],"mimeType"=>$attachment['mimeType'],"type"=>"Attachment","header"=>$m['header'],"parent_id"=>$box->thread_id);
															}									
														}
													}
												}
											}
										}
									}
									$data['results'][0]['market'] = 	$data['boxes'];			
								}
							}
						}
						$data['token'] = base64_encode($data['current_page']);
					}
				} else {
					$data['current_page'] = 0;
					$data['total_rows'] = 0;
					$data['no_of_pages'] = 0;
				}
			} catch(Exception $e){
			
			}
			echo json_encode($data);
		}
		die;
	}
	
	public function logout_gmail(){
		session_start();
		unset($_SESSION['access_token']);
		redirect('leads/market');
	}
	
	public function market(){
		$data = array();
		$this->load->library('user_agent');
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
			$attractive = $marketData['market']['attractive'];
			unset($marketData['market']['attractive']);
			if((int)$marketData['market']['id']==0){
			    $this->load->library('DriveServiceHelper');
				$saveMarket = $this->lead_model->from_litigation_insert($marketData['market']);
				$leadFolderID = $service->getFileIdByName('Leads');
				if($leadFolderID){
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $leadFolderID );
					$getFolderInfo = $service->createSubFolder($litigationData['market']['lead_name'].'_folder',$fileParent);
					if($getFolderInfo){
						/*Save Folder ID  in DB*/
						/*$this->general_model->updateFolderIDByLead($folderID,$lead_id);*/
						$this->lead_model->from_litigation_update($saveMarket,array('folder_id'=>$getFolderInfo));
						$service->setPermissions( $getFolderInfo, SHARE_WITH_GOOGLE_EMAIL );						
						$service->setPermissions( $getFolderInfo, SHARE_WITH_GOOGLE_EMAIL_ANOTHER );						
					}
				}
				if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])){
					session_start();
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
					$filesAttachment = substr($filesAttachment,0,-1);
					$this->lead_model->insertBox(array("lead_id"=>$saveMarket,"thread_id"=>$gmailMessageID,"content"=>json_encode($getMessageData),"file_attach"=>$filesAttachment));
				}
				/*$this->lead_model->insertBox(array("lead_id"=>$saveMarket,"thread_id"=>$gmailMessageID));*/
				$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Create a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			} else {
				$id = $marketData['market']['id'];
				unset($marketData['market']['id']);
				unset($marketData['market']['gmail_message_id']); 
				$saveMarket = $this->lead_model->from_litigation_update($id,$marketData['market']);
				$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
			
			if($saveMarket>0){
				$this->session->set_flashdata('message','Record added.');	
				if(count($marketData['comment'])> 0){
					if($marketData['comment']['comment_id']=="0"){
						$this->lead_model->from_litigation_comment(array('parent_id'=>$saveMarket,'type'=>'Market','user_id'=>$this->session->userdata['id'],'comment1'=>$marketData['comment']['comment1'],'comment2'=>$marketData['comment']['comment2'],'comment3'=>$marketData['comment']['comment3'],'created'=>date('Y-m-d H:i:s'),'attractive'=>$attractive));
						$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Insert a comment in Lead From market','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					} else {
						$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update a comment in Lead From market','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->lead_model->from_litigation_update_comment(array('id'=>$marketData['comment']['comment_id'],'comment1'=>$marketData['comment']['comment1'],'comment2'=>$marketData['comment']['comment2'],'comment3'=>$marketData['comment']['comment3'],'attractive'=>$attractive));
					}					
					
				    $this->user_model->addUserHistory($user_history);
				}				
				redirect('leads/market');
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/market');
			}
		} else {
			$this->load->library('DriveServiceHelper');
			session_start();
			$service = new GmailServiceHelper();
			$data = array();
			if(!isset($_SESSION['access_token'])){	
				if(!isset($_REQUEST['code'])){
					$data['auth_url'] = $service->createAuthUrl();
					$data['messages'] = array();
					$_SESSION['clicked_url'] = "market";
				} else {
					$service->clientAuthenticate($_REQUEST['code']);
					if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']=="dashboard"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						$_SESSION['access_token'] = $service->getAccessToken();						
						redirect("dashboard");
					}	
					if(isset($_SESSION['clickedd_url']) && $_SESSION['clickedd_url']=="allGlobal"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['another_access_token'] = $service->getAccessToken();						
						$_SESSION['access_token'] = $service->getAccessToken();						
						redirect("users/allGlobal");
					}
					/*
					if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_review"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['access_token'] = $service->getAccessToken();
						$_SESSION['another_access_token'] = $service->getAccessToken();
						redirect("leads/market_review");
					}
                    if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_execute"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['access_token'] = $service->getAccessToken();
						$_SESSION['another_access_token'] = $service->getAccessToken();
						redirect("leads/market_execute");
					}
					$_SESSION['access_token'] = $service->getAccessToken();
					$userInfoArray = 	$service->getAuthUserEmail();					
					if(isset($userInfoArray->email)){
						if($userInfoArray->email==$this->session->userdata('email')){						
							$service->setAccessToken($_SESSION['access_token']);
							$data['messages'] = $service->messageList(100,"INBOX");							
							$data['auth_url'] = "";
						} else {
							unset($_SESSION['GMAIL_MeSSAGE']);
							unset($_SESSION['access_token']);
							$data['messages'] = array();
							$data['auth_url'] = $service->createAuthUrl();
							$this->session->set_flashdata('error','Please login with only your register email address to retrieve messages.');
							redirect('leads/market');
						}	
					} else {
						unset($_SESSION['GMAIL_MeSSAGE']);
						unset($_SESSION['access_token']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedddd_url']);
						$data['messages'] = array();
						$data['auth_url'] = $service->createAuthUrl();
						redirect('leads/market');
					}
					*/
				}				
			} else {

				if(isset($_SESSION['clickedd_url']) && $_SESSION['clickedd_url']=="allGlobal"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						$_SESSION['access_token'] = $service->getAccessToken();
					}
					redirect("users/allGlobal");
				}
				if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']=="dashboard"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						$_SESSION['access_token'] = $service->getAccessToken();
					}					
					redirect("dashboard");
				}
				/*
				if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_review"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['access_token'] = $service->getAccessToken();
						$_SESSION['another_access_token'] = $service->getAccessToken();
					}
					redirect("leads/market_review");
				}
                if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_execute"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['access_token'] = $service->getAccessToken();
						$_SESSION['another_access_token'] = $service->getAccessToken();
					}
					redirect("leads/market_execute");
				}				
				$service->setAccessToken($_SESSION['access_token']);
				$data['messages'] = $service->messageList(100,'INBOX');
				$data['auth_url'] = "";
				*/
			}
		}
		/*Find Incomplete Market Lead List*/
		$this->load->library("DriveServiceHelper");
		$service = new DriveServiceHelper();
		$data['listOfFiles'] = $service->getFileIDFromChildern("0B_Do1Yd0xSmXfmpoQ3BmMldhVTNSQ2R6dm03bjZFLUMteHc2UDBFRkhDR2d0YW5rMERobUU");
		/*
		$data['incomplete'] = $this->lead_model->findIncompleteANDCompleteList('Market');
		$data['boxList'] = $this->lead_model->findAllBoxList();
		$data['pass_lead'] = $this->lead_model->getPassLead();
		*/
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Market';
		$this->layout->render('leads/market',$data);
	}
	
	/*
	
	
	
	public function other(){
		$data = array();
		$this->load->library('user_agent');
		if(isset($_POST) && count($_POST)>0){
			$marketData = $this->input->post();			
		
			$checkData = $this->lead_model->checkDataFromSameOwnerToday($marketData['market']['plantiffs_name'],date('Y-m-d'),'Market');
			$portfolioIncrementNumber = (int)$checkData->portfolio + 1;
			$portfolioNumber = 'PN'.date('mdy').'-'.$portfolioIncrementNumber;
		
			$marketData['market']['user_id']= $this->session->userdata['id'];
			$marketData['market']['create_date']= date('Y-m-d H:i:s');
			$marketData['market']['type']= 'Market';
			$marketData['market']['portfolio_number']= $portfolioNumber;
			$gmailMessageID = $marketData['market']['gmail_message_id'];
			unset($marketData['market']['gmail_message_id']);
			if(!isset($marketData['market']['complete'])){
				$marketData['market']['complete'] = 0;
			}
			$attractive = $marketData['market']['attractive'];
			unset($marketData['market']['attractive']);
			if((int)$marketData['market']['id']==0){
			     
				$saveMarket = $this->lead_model->from_litigation_insert($marketData['market']);
				$this->lead_model->insertBox(array("lead_id"=>$saveMarket,"thread_id"=>$gmailMessageID));
				$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Create a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			} else {
				$id = $marketData['market']['id'];
				unset($marketData['market']['id']);
				unset($marketData['market']['gmail_message_id']); 
				$saveMarket = $this->lead_model->from_litigation_update($id,$marketData['market']);
				$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>'Update a lead from Market.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
			
			if($saveMarket>0){
				$this->session->set_flashdata('message','Record added.');	
				if(!empty($marketData['comment']['comment'])){
					if($marketData['comment']['comment_id']=="0"){
						$this->lead_model->from_litigation_comment(array('parent_id'=>$saveMarket,'type'=>'Market','user_id'=>$this->session->userdata['id'],'comment'=>$marketData['comment']['comment'],'created'=>date('Y-m-d H:i:s'),'attractive'=>$attractive));
						$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Insert a comment in Lead From market','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					} else {
						$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update a comment in Lead From market','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->lead_model->from_litigation_update_comment(array('id'=>$marketData['comment']['comment_id'],'comment'=>$marketData['comment']['comment'],'attractive'=>$attractive));
					}					
					
				    $this->user_model->addUserHistory($user_history);
				}				
				redirect('leads/market');
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/market');
			}
		} else {
			$this->load->library('DriveServiceHelper');
			session_start();
			$service = new GmailServiceHelper();
			$data = array();
			if(!isset($_SESSION['access_token'])){	
				if(!isset($_REQUEST['code'])){
					$data['auth_url'] = $service->createAuthUrl();
					$data['messages'] = array();
					$_SESSION['clicked_url'] = "market";
				} else {
					$service->clientAuthenticate($_REQUEST['code']);
					if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']=="dashboard"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						redirect("dashboard");
					}	
					if(isset($_SESSION['clickedd_url']) && $_SESSION['clickedd_url']=="allGlobal"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['another_access_token'] = $service->getAccessToken();						
						redirect("users/allGlobal");
					}
					if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_review"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['access_token'] = $service->getAccessToken();
						redirect("leads/market_review");
					}
                    if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_execute"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['access_token'] = $service->getAccessToken();
						redirect("leads/market_execute");
					}
					$_SESSION['access_token'] = $service->getAccessToken();
					$userInfoArray = 	$service->getAuthUserEmail();

					if(isset($userInfoArray->email)){
						if($userInfoArray->email==$this->session->userdata("email")){
							$service->setAccessToken($_SESSION['access_token']);
							$data['messages'] = $service->messageList();
							if(count($data['messages'])>0){
								$_SESSION['GMAIL_MeSSAGE'] = $data['messages'];						
							}
							$data['auth_url'] = "";
						} else {
							unset($_SESSION['GMAIL_MeSSAGE']);
							unset($_SESSION['access_token']);
							$data['messages'] = array();
							$data['auth_url'] = $service->createAuthUrl();
							$this->session->set_flashdata('error','Please login with only your registered email address to retrieve messages.');
							redirect('leads/market');
						}	
					} else {
						unset($_SESSION['GMAIL_MeSSAGE']);
						unset($_SESSION['access_token']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedddd_url']);
						$data['messages'] = array();
						$data['auth_url'] = $service->createAuthUrl();
						redirect('leads/market');
					}
				}				
			} else {

				if(isset($_SESSION['clickedd_url']) && $_SESSION['clickedd_url']=="allGlobal"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
					}
					redirect("users/allGlobal");
				}
				if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']=="dashboard"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
					}					
					redirect("dashboard");
				}
				if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_review"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['access_token'] = $service->getAccessToken();
					}
					redirect("leads/market_review");
				}
                if(isset($_SESSION['clickedddd_url']) && $_SESSION['clickedddd_url']=="market_execute"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['access_token'] = $service->getAccessToken();
					}
					redirect("leads/market_execute");
				}
				if(isset($_SESSION['GMAIL_MeSSAGE'])  && count($_SESSION['GMAIL_MeSSAGE'])>0){
					$data['messages'] = $_SESSION['GMAIL_MeSSAGE'];
				} else {
					$service->setAccessToken($_SESSION['access_token']);
					$data['messages'] = $service->messageList();
					if(count($data['messages'])>0){
						$_SESSION['GMAIL_MeSSAGE'] = $data['messages'];						
					}
				}
				
				$data['auth_url'] = "";
			}
		}

		$this->load->library("DriveServiceHelper");
		$service = new DriveServiceHelper();
		$data['listOfFiles'] = $service->getFileIDFromChildern("0B_Do1Yd0xSmXfmpoQ3BmMldhVTNSQ2R6dm03bjZFLUMteHc2UDBFRkhDR2d0YW5rMERobUU");
		$data['incomplete'] = $this->lead_model->findIncompleteList('Market');
		$data['boxList'] = $this->lead_model->findAllBoxList();
		$data['pass_lead'] = $this->lead_model->getPassLead();
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Other';
		$this->layout->render('leads/other',$data);
	}
	
	
	*/
	
	
	
	
	
	
	function find_new_email_messages(){
		
		/*$this->load->library('DriveServiceHelper');
		session_start();
		$service = new GmailServiceHelper();
		if(isset($_SESSION['access_token'])){
			$service->setAccessToken($_SESSION['access_token']);
			$data['messages'] = $service->messageList();
			if(count($data['messages'])>0){
				$_SESSION['GMAIL_MeSSAGE'] = $data['messages'];						
			}
		}*/
		die;
	}
	
	function retrieve_new_messgaes(){
		$this->load->library('DriveServiceHelper');
		session_start();
		$service = new GmailServiceHelper();
		if(isset($_SESSION['access_token'])){
			$service->setAccessToken($_SESSION['access_token']);
			$data['messages'] = $service->messageList();
			if(count($data['messages'])>0){
				$_SESSION['GMAIL_MeSSAGE'] = $data['messages'];						
			}
		}
		redirect('leads/market');
	}
	
	public function findBoxList(){
		$data = array('detail'=>array(),'boxes'=>array());
		if(isset($_POST) && count($_POST)>0){
		  $type = "Market";
            if($this->input->post('t')!=false && $this->input->post('t')!=""){
                $type =$this->input->post('t'); 
            }
			$data['detail'] = $this->lead_model->findOneLitigationWithIncomplete($this->input->post('boxes'),$type);
			$data['boxes']= $this->lead_model->findBoxList($this->input->post('boxes'));
			/*$listBox = $this->lead_model->findBoxList($this->input->post('boxes'));			
			$data['boxes']=array();
			if(count($listBox)>0){
				foreach($listBox as $box){
					$this->load->library('DriveServiceHelper');
					if(session_id() == '') {
						session_start();
					}
					$service = new GmailServiceHelper();
					if(isset($_SESSION['access_token'])){
						$service->setAccessToken($_SESSION['access_token']);
						$list = $service->findThreadData($box->thread_id);
						if(count($list)>0){
							foreach($list as $m){
								$data['boxes'][] = array("message_id"=>$m['message_id'],"header"=>$m['header'],"type"=>"Message","parent_id"=>$box->thread_id);
								if(count($m['attachments'])>0){
									foreach($m['attachments'] as $attachment){
										$data['boxes'][] = array("message_id"=>$m['message_id'],'realAttachID'=>$attachment['realAttachID'],'size'=>$attachment['size'],"attachmentId"=>$attachment['attachmentId'],"filename"=>$attachment['filename'],"mimeType"=>$attachment['mimeType'],"type"=>"Attachment","header"=>$m['header'],"parent_id"=>$box->thread_id);
									}									
								}
							}
						}
					}
				}
			}*/
		}		
		echo json_encode($data);		
		die;
	}
	
	function passLead(){
		if(isset($_POST) && count($_POST)>0){
			$send = $this->lead_model->insertPassLead(array("type"=>$this->input->post('type'),"thread_id"=>$this->input->post('g')));
			if($send>0){
				$id = $this->input->post('g');
				$msg = "Pass a lead";
				if($this->input->post('type')=="message"){
					$id = 0;
					$msg = "Pass a email message";
				}
				$user_history = array('lead_id'=>$id,'user_id'=>$this->session->userdata['id'],'message'=>$msg,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				echo json_encode(array("send"=>"1"));
			} else {
				echo json_encode(array("send"=>"0"));
			}
		} else {
			echo json_encode(array("send"=>"0"));
		}
		die;
	}
	
	function removeFromBox(){
		if(isset($_POST) && count($_POST)>0){
			$removeFromBox = $this->lead_model->removeFromBox($this->input->post('g'),$this->input->post('thread'));
			if($removeFromBox>0){
				$user_history = array('lead_id'=>$this->input->post('g'),'user_id'=>$this->session->userdata['id'],'message'=>"Remove message from box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				echo json_encode(array('send'=>'1'));
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}		
		die;
	}
	
	function create_task(){
		if(isset($_POST) && count($_POST)>0){
			$task = $this->input->post('task');		
			if(count($task['user_id'])>0){
				foreach($task['user_id'] as $user){					
					$getData = $this->general_model->getTaskAccToType('MARKET_FORWARD');
					$subject="Fw: Task";
					$message = "Froward task from market.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					/*End Checking*/
					$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$task['note'],'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user,'lead_id'=>$task['lead_id'],'doc_url'=>$this->config->base_url()."leads/market/".$task['lead_id'],'type'=>'MARKET_FORWARD','status'=>'0'));					
					$user_history = array('lead_id'=>$task['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Created a task",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}				
				$this->session->set_flashdata('message','Created a task.');
			} else {
				$this->session->set_flashdata('message','Please select user.');
			}
			
		}		
		redirect('leads/market');
	}
	
	function reply_email(){
		if(isset($_POST) && count($_POST)>0){
			session_start();
			$this->load->library('DriveServiceHelper');
			$email = $this->input->post('email');
			if(isset($_SESSION['access_token'])){
				$fileName="";
				$fileSrc = "";
				if(isset($_FILES) && count($_FILES)>0 && !empty($_FILES['email']['name']['attachment'])){
					$fileSrc = $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$_FILES['email']['name']['attachment'];
					if(move_uploaded_file($_FILES['email']["tmp_name"]['attachment'], $fileSrc)){
						$fileName = $_FILES['email']['name']['attachment'];
					}
				}
				$email['fileName'] = $fileName;
				$email['fileSrc'] = $fileSrc;
				$service = new GmailServiceHelper();
				$service->setAccessToken($_SESSION['access_token']);
				$send  = $service->sendMessage($email);
				if(isset($send->labelIds[0]) && $send->labelIds[0]=="SENT"){
					$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Email send",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					$this->session->set_flashdata('message','Message send successfully');
					redirect('leads/market');
				} else {
					$this->session->set_flashdata('error','Please try after sometime.');
					redirect('leads/market');
				}				
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
					redirect('leads/market');
			}
		} else {
			$this->session->set_flashdata('error','Please select message first');
			redirect('leads/market');
		}		
	}
	
	function linkWithMessage(){
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			session_start();
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['access_token']);
			$getMessageData = $service->findThreadData($box['thread']);
			$filesAttachment = "";
			/**/
			if(count($getMessageData)>0){
				foreach($getMessageData as $message){
					foreach($message['attachments'] as $attachments){
						$attachmentID = $attachments['attachmentId'];
						$filename =  $attachments['filename'];
						$attachmentsData = $service->downloadAttachments($box['thread'],$attachmentID);
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
			if(!empty($filesAttachment)){
				$filesAttachment = substr($filesAttachment,0,-1);
			}
			$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],"thread_id"=>$box['thread'],"content"=>json_encode($getMessageData),"file_attach"=>$filesAttachment));
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
		die;
	}
	
	function sendRequestURL(){
		$data =array();
		if(isset($_POST) && count($_POST)>0){
			if($this->input->post('t')=='c3luUGF0TWFya2V0'){
				if(!isset($_SESSION)){
					session_start();
				}
				$_SESSION['clickedddd_url']="market_review";
				unset($_SESSION['clicked_url']);
				$data =array(base64_encode(rand()));
			}
		}
		echo json_encode($data);
		die;
	}
    
    function sendRequestURLMarketExecute(){
		$data =array();
		if(isset($_POST) && count($_POST)>0){
			if($this->input->post('t')=='c3luUGF0TWFya2V0'){
				if(!isset($_SESSION)){
					session_start();
				}
				$_SESSION['clickedddd_url']="market_execute";
				unset($_SESSION['clicked_url']);
				$data =array(base64_encode(rand()));
			}
		}
		echo json_encode($data);
		die;
	}
	
	function market_review($id=null){
		$data = array();
		$perPage = 1;
		$start = 1;
		if($id==null){
			$data["total_rows"] = $this->lead_model->record_count('Market',1);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('Market',$perPage,$start-1,1);
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['no_pagination'] = false;
		} else {
			$data['no_pagination'] = true;
            
            $data['current_page'] = 1;
           /* $type = $this->db->query('select type from litigations where id='.$id)->row()->type;*/
            $data['results'] = $this->lead_model->findOneLitigationWithPaging($id,'Market');
			$data['no_of_pages'] = count($data['results']);
			$data["total_rows"] = count($data['results']);
		}
		if(!isset($_SESSION)){
			session_start();
		}
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION['access_token'])){			
			$data['auth_url'] = $service->createAuthUrl();
		} else {
			$data['auth_url'] = '';
		}
		if(count($data['results'])>0){
			if(isset($data['results'][0]['litigation']->id)){
				$listBox = $this->lead_model->findBoxList($data['results'][0]['litigation']->id);
				$data['boxes']=array();
				if(count($listBox)>0){
					foreach($listBox as $box){
						$this->load->library('DriveServiceHelper');
						if(session_id() == '') {
							session_start();
						}
						$service = new GmailServiceHelper();
						if(isset($_SESSION['access_token'])){
							$service->setAccessToken($_SESSION['access_token']);
							$list = $service->findThreadData($box->thread_id);
							if(count($list)>0){
								foreach($list as $m){
									$data['boxes'][] = array("message_id"=>$m['message_id'],"header"=>$m['header'],"type"=>"Message","parent_id"=>$box->thread_id);
									if(count($m['attachments'])>0){
										foreach($m['attachments'] as $attachment){
											$data['boxes'][] = array("message_id"=>$m['message_id'],'realAttachID'=>$attachment['realAttachID'],'size'=>$attachment['size'],"attachmentId"=>$attachment['attachmentId'],"filename"=>$attachment['filename'],"mimeType"=>$attachment['mimeType'],"type"=>"Attachment","header"=>$m['header'],"parent_id"=>$box->thread_id);
										}									
									}
								}
							}
						}
					}
				}
				$data['results'][0]['market'] = 	$data['boxes'];			
			}
		}			
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('Market',1);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Market';
		$this->layout->render('leads/market_review',$data);
	}
	
	function market_execute(){
		if((int)$this->session->userdata['type']==9){			
			$perPage = 1;
			$start = 1;
			$data["total_rows"] = $this->lead_model->record_count('Market',2);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('Market',$perPage,$start-1,2);	
			if(!isset($_SESSION)){
    			session_start();
    		}
    		$this->load->library('DriveServiceHelper');
    		$service = new GmailServiceHelper();
    		if(!isset($_SESSION['access_token'])){			
    			$data['auth_url'] = $service->createAuthUrl();
    		} else {
    			$data['auth_url'] = '';
    		}
            if(count($data['results'])>0){
				if(isset($data['results'][0]['litigation']->id)){
					$listBox = $this->lead_model->findBoxList($data['results'][0]['litigation']->id);
					$data['boxes']=array();
					if(count($listBox)>0){
						foreach($listBox as $box){
							$this->load->library('DriveServiceHelper');
							if(session_id() == '') {
								session_start();
							}
							$service = new GmailServiceHelper();
							if(isset($_SESSION['access_token'])){
								$service->setAccessToken($_SESSION['access_token']);
								$list = $service->findThreadData($box->thread_id);
								if(count($list)>0){
									foreach($list as $m){
										$data['boxes'][] = array("message_id"=>$m['message_id'],"header"=>$m['header'],"type"=>"Message","parent_id"=>$box->thread_id);
										if(count($m['attachments'])>0){
											foreach($m['attachments'] as $attachment){
												$data['boxes'][] = array("message_id"=>$m['message_id'],'realAttachID'=>$attachment['realAttachID'],'size'=>$attachment['size'],"attachmentId"=>$attachment['attachmentId'],"filename"=>$attachment['filename'],"mimeType"=>$attachment['mimeType'],"type"=>"Attachment","header"=>$m['header'],"parent_id"=>$box->thread_id);
											}									
										}
									}
								}
							}
						}
					}
					$data['results'][0]['market'] = 	$data['boxes'];			
				}
			}
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('Market',2);
			$this->layout->auto_render=false;		
			$this->layout->title_for_layout = 'Backyard From Market';
			$this->layout->render('leads/market_execute',$data);
		} else {
			$this->session->set_flashdata('error','You are not authorize for this page.');
			redirect('dashboard');
		}
	}
	
	public function prospect_general(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$generalData = $this->input->post();
			/*Create Portfolio Number*/
			$checkData = $this->lead_model->checkDataFromSameOwnerToday($generalData['general']['plantiffs_name'],date('Y-m-d'),'General');
			$portfolioIncrementNumber = (int)$checkData->portfolio + 1;
			$portfolioNumber = 'PN'.date('mdy').'-'.$portfolioIncrementNumber;
			/*End Creating*/
			$generalData['general']['user_id']= $this->session->userdata['id'];
			$generalData['general']['create_date']= date('Y-m-d H:i:s');
			$generalData['general']['type']= 'General';
			$generalData['general']['portfolio_number']= $portfolioNumber;
			if(!isset($generalData['general']['complete'])){
				$generalData['general']['complete'] = 0;
			}
			$attractive = $generalData['general']['attractive'];
			unset($generalData['general']['attractive']);
			if((int)$generalData['general']['id']==0){
				unset($generalData['general']['id']);
				$saveMarket = $this->lead_model->from_litigation_insert($generalData['general']);
				if($saveMarket>0){
					$this->session->set_flashdata('message','Record added.');	
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Create a lead from Proactive General.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}
			} else {
				$saveMarket = $this->lead_model->from_litigation_update($generalData['general']['id'],$generalData['general']);
				$this->session->set_flashdata('message','Record updated.');	
				$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update lead from Proactive General.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
			
			if($saveMarket>0){
				if((int)$generalData['other']['id']==0){					
					$this->lead_model->from_litigation_comment(array('parent_id'=>$saveMarket,'type'=>'General','user_id'=>$this->session->userdata['id'],'attractive'=>$attractive,'comment'=>$generalData['general']['comment'],'created'=>date('Y-m-d H:i:s')));
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Insert comment.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}	else {
					$this->lead_model->from_litigation_update_comment(array("id"=>$generalData['other']['id'],'parent_id'=>$saveMarket,'type'=>'General','user_id'=>$this->session->userdata['id'],'attractive'=>$attractive,'comment'=>$generalData['general']['comment']));
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update comment.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}				
				redirect('leads/prospect_general');
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/prospect_general');
			}			
		}
		$this->load->library("DriveServiceHelper");
		$service = new DriveServiceHelper();
		$data['listOfFiles'] = $service->getFileIDFromChildern("0B_Do1Yd0xSmXfmpoQ3BmMldhVTNSQ2R6dm03bjZFLUMteHc2UDBFRkhDR2d0YW5rMERobUU");
		$data['incomplete_leads'] = $this->lead_model->from_count_incomplete_litigation('General',0);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Proactive General';
		$this->layout->render('leads/prospect_general',$data);
	}
	
	function prospect_general_review($id=null){
		$data = array();
		$perPage = 1;
		$start = 1;
		if($id==null){
			$data["total_rows"] = $this->lead_model->record_count('General',1);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('General',$perPage,$start-1,1);
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['no_pagination'] = false;
		} else {
			$data['no_pagination'] = true;
            $data["total_rows"] = 1;
            $data['current_page'] = 1;
			
           /* $type = $this->db->query('select type from litigations where id='.$id)->row()->type;*/
            $data['results'] = $this->lead_model->findOneLitigationWithPaging($id,'General');
			$data['no_of_pages'] = count($data['results']);
		}
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('General',1);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Proactive General';
		$this->layout->render('leads/prospect_general_review',$data);
	}
	
	function prospect_general_execute(){
		if((int)$this->session->userdata['type']==9){			
			$perPage = 1;
			$start = 1;
			$data["total_rows"] = $this->lead_model->record_count('General',2);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('General',$perPage,$start-1,2);	
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$this->layout->auto_render=false;		
			$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('General',2);
			$this->layout->title_for_layout = 'Backyard From Proactive General';
			$this->layout->render('leads/prospect_general_execute',$data);
		} else {
			$this->session->set_flashdata('error','You are not authorize for this page.');
			redirect('dashboard');
		}
	}
	
	public function proactive_sep(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$generalData = $this->input->post();
			/*Create Portfolio Number*/
			$checkData = $this->lead_model->checkDataFromSameOwnerToday($generalData['general']['plantiffs_name'],date('Y-m-d'),'SEP');
			$portfolioIncrementNumber = (int)$checkData->portfolio + 1;
			$portfolioNumber = 'PN'.date('mdy').'-'.$portfolioIncrementNumber;
			/*End Creating*/
			$generalData['general']['user_id']= $this->session->userdata['id'];
			$generalData['general']['create_date']= date('Y-m-d H:i:s');
			$generalData['general']['type']= 'SEP';
			$generalData['general']['portfolio_number']= $portfolioNumber;
			if(!isset($generalData['general']['complete'])){
				$generalData['general']['complete'] = 0;
			}
			$attractive = $generalData['general']['attractive'];
			unset($generalData['general']['attractive']);
			if((int)$generalData['general']['id']==0){
				unset($generalData['general']['id']);
				$saveMarket = $this->lead_model->from_litigation_insert($generalData['general']);
				if($saveMarket>0){
					$this->session->set_flashdata('message','Record added.');	
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Create a lead from Proactive SEP.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}
			} else {
				$saveMarket = $this->lead_model->from_litigation_update($generalData['general']['id'],$generalData['general']);
				$this->session->set_flashdata('message','Record updated.');	
				$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update lead from Proactive SEP.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
			if($saveMarket>0){
				if((int)$generalData['other']['id']==0){					
					$this->lead_model->from_litigation_comment(array('parent_id'=>$saveMarket,'type'=>'SEP','user_id'=>$this->session->userdata['id'],'attractive'=>$attractive,'comment'=>$generalData['general']['comment'],'created'=>date('Y-m-d H:i:s')));
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Insert comment.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}	else {
					$this->lead_model->from_litigation_update_comment(array("id"=>$generalData['other']['id'],'parent_id'=>$saveMarket,'type'=>'SEP','user_id'=>$this->session->userdata['id'],'attractive'=>$attractive,'comment'=>$generalData['general']['comment']));
					$user_history = array('lead_id'=>$saveMarket,'user_id'=>$this->session->userdata['id'],'message'=>'Update comment.','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}				
				redirect('leads/proactive_sep');
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/proactive_sep');
			}			
		}
		$this->load->library("DriveServiceHelper");
		$service = new DriveServiceHelper();
		$data['listOfFiles'] = $service->getFileIDFromChildern("0B_Do1Yd0xSmXfmpoQ3BmMldhVTNSQ2R6dm03bjZFLUMteHc2UDBFRkhDR2d0YW5rMERobUU");
		$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('SEP',0);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Proactive SEP';
		$this->layout->render('leads/prospect_sep',$data);
	}
	
	public function proactive_sep_review($id=null){
		$data = array();
		$perPage = 1;
		$start = 1;
		if($id==null){
			$data["total_rows"] = $this->lead_model->record_count('SEP',1);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('SEP',$perPage,$start-1,1);
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['no_pagination'] = false;
		} else {
			$data['no_pagination'] = true;
            $data["total_rows"] = 1;
            $data['current_page'] = 1;
			
           /* $type = $this->db->query('select type from litigations where id='.$id)->row()->type;*/
            $data['results'] = $this->lead_model->findOneLitigationWithPaging($id,'SEP');
			$data['no_of_pages'] = count($data['results']);
		}
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('SEP',1);
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard From Proactive SEP Review';
		$this->layout->render('leads/prospect_sep_review',$data);
	}
	
	public function proactive_sep_execute(){
		if((int)$this->session->userdata['type']==9){			
			$perPage = 1;
			$start = 1;
			$data["total_rows"] = $this->lead_model->record_count('SEP',2);
			$data['results'] = $this->lead_model->findAllLitigationWithPaging('SEP',$perPage,$start-1,2);	
			$data['current_page'] = $start;
			$data['no_of_pages'] = ceil($data["total_rows"]/$perPage);
			$data['incomplete_leads'] =$this->lead_model->from_count_incomplete_litigation('SEP',2);
			$this->layout->auto_render=false;		
			$this->layout->title_for_layout = 'Backyard From Proactive SEP';
			$this->layout->render('leads/prospect_sep_execute',$data);
		} else {
			$this->session->set_flashdata('error','You are not authorize for this page.');
			redirect('dashboard');
		}
		
	}
	
	public function createNDATermsheet(){
		$data = array("error"=>"1","message"=>"Please try  after sometime");
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('v');
			$getLeadData = $this->lead_model->getLeadData($leadID);
			if(count($getLeadData)>0){
				$ndaID = "";
				$term_sheet = "";
				if(!empty($getLeadData->nda_id)){
					$ndaID = $getLeadData->nda_id;
					$data['nda'] = $ndaID;
				}
				if(!empty($getLeadData->term_sheet)){
					$term_sheet = $getLeadData->term_sheet;
					$data['term_sheet'] = $term_sheet;
				}
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				if(empty($ndaID)){
					/*Create NDA*/
					$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
					if($parentFolderID){
						$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('NDA',$findLeadData->type);
						if(count($getNDAFileNameWithAccordingLeadType)>0){
							/*Create Object*/
							$saveDocFIle = $getNDAFileNameWithAccordingLeadType->doc_id;
							$fileID = (object) array("id"=>$saveDocFIle);
						} else {
							$fileID = $service->getFileNameFromChildern($parentFolderID,'7 SynPat - NDA to disclose the PPP - Master');
						}
						if(!empty($fileID)){
							$folderID = $getLeadData->folder_id;
							$fileParent = new Google_Service_Drive_ParentReference();
							$fileParent->setId( $folderID );
							$getFileInfo = $service->copyFile($fileID->id,$fileParent);
							if($getFileInfo){
								$data['error'] = 0;
								$data['nda'] = $getFileInfo->alternateLink;
								$saveLead = $this->lead_model->from_litigation_update($leadID,array("nda_id"=>$getFileInfo->alternateLink));								
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"NDA created for ".$getLeadData->lead_name,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								$service->setPermissions( $getFileInfo->id, SHARE_WITH_GOOGLE_EMAIL_ANOTHER );
								$service->setAdditionalPermissions($getFileInfo->id,$this->session->userdata['email'],'reader','anyone',array('emailMessage'=>'Testing this message at the time of sharing lead NDA'));
							}
						}
					}
				}
				if(empty($term_sheet)){
					$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
					if($parentFolderID){
						$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('NDA',$findLeadData->type);
						if(count($getNDAFileNameWithAccordingLeadType)>0){
							/*Create Object*/
							$saveDocFIle = $getNDAFileNameWithAccordingLeadType->doc_id;
							$fileID = (object) array("id"=>$saveDocFIle);
						} else {
							$fileID = $service->getFileNameFromChildern($parentFolderID,'7 SynPat - NDA to disclose the PPP - Master');
						}
						if(!empty($fileID)){
							$folderID = $getLeadData->folder_id;
							$fileParent = new Google_Service_Drive_ParentReference();
							$fileParent->setId( $folderID );
							$getFileInfo = $service->copyFile($fileID->id,$fileParent);
							if($getFileInfo){
								$data['error'] = 0;
								$data['term_sheet'] = $getFileInfo->alternateLink;
								$saveLead = $this->lead_model->from_litigation_update($leadID,array("nda_id"=>$getFileInfo->alternateLink));								
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"NDA created for ".$getLeadData->lead_name,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								$service->setPermissions( $getFileInfo->id, SHARE_WITH_GOOGLE_EMAIL_ANOTHER );
								$service->setAdditionalPermissions($getFileInfo->id,$this->session->userdata['email'],'reader','anyone',array('emailMessage'=>'Testing this message at the time of sharing lead NDA'));
							}
						}
					}
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	public function letter_proposal(){
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();
		/*Find File From Master Document*/
		$name = $this->input->post('name');
		if(empty($name)){
			$name = "Synpat - Proposal Letter to ".date('YYMM')."_Proposal";
		} else{
			$name = "Synpat - Proposal Letter to ".$name;
		}
		$fileID = false;		
		$leadFolderID = false;
		$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
		if(count($leadInfo)>0){
			if(!empty($leadInfo->folder_id)){
				$leadFolderID = $leadInfo->folder_id;
			}
		}
		$findFile = $service->getFileNameFromChildern($leadFolderID,$name);
		if(is_object($findFile)){
			$data['link'] = $findFile->alternateLink;
			if(isset($_POST['ds'])){
				$update_data = array('send_proposal_letter'=>'2','send_proposal_letter_text'=>date('Y-m-d H:i:s'));
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			}
		} else {
			switch($this->input->post('type')){
				case 'Litigation':								
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');	
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'1 SynPat - Proposal Letter to Sellers - Litigation');
								if($fileID){
									$fileID = $fileID->id;
								}
							}
						}	
					}				
				break;
				case 'Market':
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);	
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');	
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');						
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'1 SynPat - Proposal Letter to Sellers - Litigation');
								if($fileID){	
									$fileID = $fileID->id;		
								}
							}
						}
					}
				//	$fileID = $service->getFileIdByName('1 SynPat - Proposal Letter to Sellers - Proactive');
				break;
				case 'General':
				case 'SEP':
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);	
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');	
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');
							
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'1 SynPat - Proposal Letter to Sellers - Litigation');
								if($fileID){	
									$fileID = $fileID->id;		
								}
							}
						}
					}
				break;
			}
			
			/*End Find File*/
			/*Find Lead Folder*/
			$data = array();	
			if($leadFolderID && $fileID){
				/*Find File*/
				$file = $service->getFileNameFromChildern($leadFolderID,$name);
				/*End Find*/
				if($file){
					$data['link'] = $file->alternateLink;
				} else {
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $leadFolderID );
					$getFileInfo = $service->copyFile($fileID,$name,$fileParent);
					if($getFileInfo){
						$service->setPermissions( $getFileInfo->id, SHARE_WITH_GOOGLE_EMAIL );
						$service->setPermissions( $getFileInfo->id, $this->session->userdata['email'] );
						$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('lead_id'),'message'=>'Create a letter proposal','create_date'=>date('Y-m-d H:i:s')));
						$data['link'] = $getFileInfo->alternateLink;
						if(isset($_POST['ds'])){
							$update_data = array('send_proposal_letter'=>'1');
							$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
						}
					} else {
						$data['link'] = '';
					}
				}				
			} else {
				$data['link'] = "";
			}
		}
		echo json_encode($data);		
		die;
	}
	
	public function email_proposal_history(){
		$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('lead_id'),'message'=>'Create a email proposal','create_date'=>date('Y-m-d H:i:s')));
	}
	
	public function create_presentation_file(){
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();
		$fileData = $service->createPresentaionFile('US23123452');
		if($fileData){
			$service->setPermissions( $fileData, SHARE_WITH_GOOGLE_EMAIL );
			$getFileInfo = $service->getFileInfo($fileData);
			echo "<pre>";
			print_r($getFileInfo);
		}
		die;
	}
	
	public function email_send(){
		if(isset($_POST)&& count($_POST)>0){
			/*Send Email*/
			$emailData = $this->input->post();
			$saveData = $this->email_model->save($emailData['email']);
			if($saveData>0){
				$this->load->library('email');
				$name = $this->session->userdata['name'];
				$email = $this->session->userdata['email'];
				$this->email->from($email, $name);
				$this->email->to($emailData['email']['to']);
				$this->email->subject("Email Proposal");
				$this->email->message($emailData['email']['template']);
				if($this->email->send()){     
				   $this->session->set_flash('Message Send Successfully.');
				   redirect('leads/litigation');   
				} 
			}			
		}
	}
	
	public function comment(){
		if(isset($_POST) && count($_POST)>0){
			$commentData = $this->input->post();
          //  print_r($commentData);
            $patent_data = $commentData['litigation']['patent_data'];
          //  print_r($commentData);
			$commentData['other']['user_id']= $this->session->userdata['id'];
			$commentData['other']['created']= date('Y-m-d H:i:s');
			//$commentData['other']['type']= 'Litigation';
			if(isset($commentData['litigation']['complete'])){
				$this->lead_model->from_litigation_update($commentData['other']['parent_id'],array('complete'=>2,'status'=>'0','patent_data' => $patent_data));
			}
            else
            {
                $this->lead_model->from_litigation_update($commentData['other']['parent_id'],array('patent_data' => $patent_data));
            }
			if($commentData['other']['id']==0){
				$saveComment = $this->lead_model->from_litigation_comment($commentData['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['other']['parent_id'],'message'=>'Add a comment','create_date'=>date('Y-m-d H:i:s')));
			} else {
				$saveComment = $this->lead_model->from_litigation_update_comment($commentData['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['other']['parent_id'],'message'=>'Update comment','create_date'=>date('Y-m-d H:i:s')));
			}
			if($saveComment>0){
				echo json_encode(array('message'=>'Records added','id'=>$saveComment));
			} else {
				echo json_encode(array('error'=>'Try after sometime.'));
			}
		}
		die;
	}
	
    public function insert_patent_data(){
        if(isset($_POST) && count($_POST)>0)
        {
            $patent_data = $this->input->post();
         //   echo '<pre>';
           // print_r($patent_data);
            $this->lead_model->from_litigation_update($patent_data['parent_id'],array('patent_data' => $patent_data['patent_data']));
        }
        die;
    }
    
	function change_status_lead(){
		if(isset($_POST) && count($_POST)>0){
			$leadID =$_POST['token'];
			$updatedRows = $this->lead_model->updateLeadStatus(array('id'=>$leadID,'status'=>'1'));
			$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Approved Lead','create_date'=>date('Y-m-d H:i:s')));
			/*Create Task for admin to create opportunity*/
			$getAdminUsers = $this->user_model->findAdminUsers();
			foreach($getAdminUsers as $user){
				$this->opportunity_model->sendApprovalRequest(array('lead_id'=>$leadID,'user_id'=>$user->id,"doc_url"=>$this->config->base_url()."general/create_an_opportunity","status"=>0,"type"=>"CREATE_OPPORTUNITY"));
			}
			echo json_encode(array('rows'=>$updatedRows));
		}
		die;
	}
	
	public function scrapData(){
		$getPatentNumber = $this->input->post('scrap_data');
		if(!empty($getPatentNumber)){
			$url = "https://www.google.com/patents/".$getPatentNumber;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$content = curl_exec($ch);
			curl_close($ch);
			$content = str_replace("/patents/css/","http://www.google.co.in/patents/css/",$content);
			$content = str_replace("/books/javascript/","http://www.google.co.in/books/javascript/",$content);
			$content = str_replace("/googlebooks/images/kennedy/page_left.png","http://www.google.co.in/googlebooks/images/kennedy/page_left.png",$content);
			$content = str_replace("/googlebooks/images/kennedy/page_right.png","http://www.google.co.in/googlebooks/images/kennedy/page_right.png",$content);
		}
		echo $content;
		die;
	}
	
	public function findWorksheetList(){
		if($_POST && count($_POST)):
			$pathInclude = get_include_path().PATH_SEPARATOR;
			set_include_path($pathInclude.$_SERVER['DOCUMENT_ROOT']."/application/libraries/ZendGdata-1.12.9/library");
			$u = "webmaster@synpat.com";
			$p = "l3n0v0@123";
			$ss_id = $this->input->post('v');
			$this->load->library('Google_Spreadsheet',array('user'=>$u,'pass'=>$p));
			$allWorkSheet = $this->google_spreadsheet->getSpreadSheetWithKey($ss_id);
			echo json_encode($allWorkSheet);
		endif;
		die;
	}
	
	public function googleSpreadSheet(){
		$pathInclude = get_include_path().PATH_SEPARATOR;
		
		set_include_path($pathInclude.$_SERVER['DOCUMENT_ROOT']."/application/libraries/ZendGdata-1.12.9/library");
		$u = "webmaster@synpat.com";
		$p = "l3n0v0@123";
		$this->load->library('Google_Spreadsheet',array('user'=>$u,'pass'=>$p));
		/*$string = "https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=1319867773";*/
		$allPatentFromSheet = array();
		$asset_data=array();
		$string = $this->input->post('file_url');
		if(!empty($string)){
			$ss_id = explode("/",$string);
			$ss_id =$ss_id[count($ss_id)-2];
			$workSheetString = explode("#",$string);
			$sheetID = 0;
			if(count($workSheetString)>1){
				$workSheetString = $workSheetString[1];
				if(!empty($workSheetString)){
					$sheetExplode = explode("=",$workSheetString);
					if(count($sheetExplode)>1){
						$sheetID = $sheetExplode[1];
					}
				}
			}		
			$allWorkSheet = $this->google_spreadsheet->getSpreadSheetWithKey($ss_id);
			$sheetName = "";
			$sheetInnerID = "";
			foreach($allWorkSheet as $worksheet){
				if(trim($worksheet['link'])==trim($sheetID)){
					$sheetName = $worksheet['text'];
					$sheetInnerID = $worksheet['id'];
					break;
				}
			}
			if(!empty($sheetName)){
				$this->google_spreadsheet->spreadSheetID($ss_id);
				$this->google_spreadsheet->useWorksheet($sheetName);
				$asset_data = $this->google_spreadsheet->getRows();
				for($i=0;$i<count($asset_data);$i++){
					foreach($asset_data[$i] as $key=>$patent){
						$content = $this->executeCurl($patent);						
						
						$this->load->library('simple_html_dom');
						$title=null;
						$needle = '<span class="patent-title">';
						if(strpos($content, $needle,0) !== false) {
							$startPost = strpos($content, $needle);
							$anotherNeedle = '<span class="patent-number">';
							if (stripos($content, $anotherNeedle,1) !== false) {
								$endPost =  stripos($content, $anotherNeedle);										
								$dataPatent =  substr($content,$startPost,$endPost);
								$html = $this->simple_html_dom->load($dataPatent,true,true);
								$flag=0;
								foreach($html->find('<span class="patent-title">') as $asTable) {
									foreach($asTable->find('invention-title') as $title){
										$title =$title->innertext;
									}									
								}
							}
						}
						$title = strip_tags($title);
						/*if($patent=='US6693665'){
							echo "A".$title."B";
							die;
						}*/
						
						$needle = '<table class="patent-bibdata">';
						$key = array();
						$value = array();		
						if(strpos($content, $needle,0) !== false) {
							$startPost = strpos($content, $needle);
							$anotherNeedle = '<div class="number-and-title">';
							if (stripos($content, $anotherNeedle,1) !== false) {
								$endPost =  stripos($content, $anotherNeedle);										
								$dataPatent =  substr($content,$startPost,$endPost);									
								$html = $this->simple_html_dom->load($dataPatent,true,true);
								$flag=0;
								foreach($html->find('<table class="patent-bibdata">') as $asTable) {		
									foreach($asTable->find('tr') as $tr){	
										$c=0;
										foreach($tr->find('td') as $td){												
											if( stripos($td->innertext, 'External Links:')!==false){
												$flag=1;
											}
											if($flag==0){
												switch($c){
													case 0:
														if(stripos($td->innertext,'<span class="patent-tooltip-anchor patent-question-icon"></span>')!==false){
															$key[] = substr($td->innertext,0,stripos($td->innertext,'<span class="patent-tooltip-anchor patent-question-icon"></span>'));
														} else {
															$key[] = $td->innertext;
														}														
													break;
													case 1:
														if($td->find('a')){
															$anchor_text = "";
															foreach($td->find('a') as $anchor){
																$anchor_text .= $anchor->innertext.", ";
															}																
															$value[] = substr($anchor_text,0,-2);		
														} else if($td->find('span')){
															$anchor_text = "";
															foreach($td->find('span') as $spP){
																foreach($spP->find('span') as $sp){
																	$anchor_text .= $sp->innertext.", ";
																}																
															}
															$value[] = substr($anchor_text,0,-2);		
														} else {
															$value[] = $td->innertext;		
														}										
													break;														
												}													
											}
											$c++;
										}																			
									}
								}
							}
						}				
						$newKey = array();
						$newVal = array();
						/*if(is_array($key))
						{
							$key = array_unique($key);
							$key = array_values($key);
						}
						
						if(is_array($value) && count($value)>0)
						{
							unset($value[8]);
							$value = array_values($value);
						}*/
						if(count($key)>0 && count($key)>10){
							for($k=0;$k<count($key)-2;$k++){
								$newKey[]=trim($key[$k]);
								if(isset($value[$k])){
									$newVal[]=trim($value[$k]);
								} else {
									$newVal[]="";
								}
								
							}
						}
						$newPubData = array();
						if(is_array($newKey) && is_array($newVal) && count($newKey) > 0 && count($newVal) > 0){
							$newPubData = array_combine($newKey, $newVal);
						}
						$applicationNumber ="";
						$originalAssignee ="";
						$currentAssignee =null;
						$priority ="";
						$feeStatus ="";
						$family ="";
						$notes = null;
						if(count($newPubData)>0){
							if(isset($newPubData['Application number'])){
								$applicationNumber = strip_tags($newPubData['Application number']);
							}
							if(isset($newPubData['Original Assignee'])){
								$originalAssignee = strip_tags($newPubData['Original Assignee']);
							}
							if(isset($newPubData['Publication number'])){
								$family = strip_tags($newPubData['Publication number']);
								$family = explode(',',$family);
								$family = implode(',',array_filter($family,'strlen'));
							}
							if(isset($newPubData['Priority date'])){
								$priority = strip_tags($newPubData['Priority date']);
							}
							if(isset($newPubData['Fee status'])){
								$feeStatus = strip_tags($newPubData['Fee status']);
							}
						}
						$allPatentFromSheet[] = array($patent,$notes,$currentAssignee,$applicationNumber,$title,$originalAssignee,$priority,$feeStatus,$family);
					}	
				}					
			}
		}
		echo json_encode($allPatentFromSheet);
		die;
	}
	
	function embedScheduleCall(){
		if(isset($_POST) && count($_POST)){
			$embedCode = $this->input->post('n');
			$update_data = array('embed_code'=>$embedCode);
			$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
	
	function createLeadPatentSpreadSheet(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$spreadSheetName = $this->input->post('n');
			if(!empty($spreadSheetName)){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$leadFolderID = false;
				$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
				if(count($leadInfo)>0){
					if(!empty($leadInfo->folder_id)){
						$leadFolderID = $leadInfo->folder_id;
					}
				}				
				if($leadFolderID){
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId($leadFolderID);
					$getFileInfo = $service->createSpreadSheetFile("Synpat - Patents of Interest(".ucfirst($spreadSheetName).")",$fileParent);
					if($getFileInfo){
						$getFile = $service->getFileInfo($getFileInfo);
						if(is_object($getFile)){
							$service->setPermissions( $getFile->id, SHARE_WITH_GOOGLE_EMAIL );
							$service->setPermissions( $getFile->id, $this->session->userdata['email'] );
							if(isset($_POST['ds'])){
								$update_data = array('create_patent_list'=>'1');
								$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
							}					    
							$data = array("url"=>$getFile->alternateLink,"error"=>0,"message"=>"Successfully created.");
						} else {
							$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
						}
					
					} else {
						$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
					}
				} else {
					$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
				}
			} else {
				$data = array("url"=>'',"error"=>1,"message"=>"Please enter filename.");
			}
		}
		echo json_encode($data);
		die;
	}
    
    function update_createPatentStatus(){
        $update_data = array('create_patent_list'=>'2','create_patent_list_text'=>date('Y-m-d H:i:s'));
		$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
    }
    
	function executeCurl($getPatentNumber){
		$content = "";
		if(!empty($getPatentNumber)){
			$url = "https://www.google.com/patents/".$getPatentNumber;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$content = curl_exec($ch);
			curl_close($ch);			
		}
		return $content;
	}
    
    public function assign_lead(){
        if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['ds'])){
				$update_data = array('seller_info'=>'2','seller_info_text'=>date('Y-m-d H:i:s'));
                $saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			}
            $user_list = $this->user_model->findAdminUsers();
            foreach($user_list as $users => $user)
            {
                $user_id = $user->id;
                $data = array('lead_id'=>$_POST['lead_id'],'user_id'=>$user_id,'subject'=>'Regarding Proposal Letter','message'=>'Create a Proposal Letter','doc_url'=>$this->config->base_url(),"execution_date"=>date('Y-m-d'),"type"=>"MARKET_PROPOSAL","status"=>0);
                $this->opportunity_model->sendApprovalRequest($data);
                $user_id = $this->session->userdata['id'];
                $this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$_POST['lead_id'],'message'=>'Successfully Task Assigned','create_date'=>date('Y-m-d H:i:s')));
                echo 'Successfully Task Assigned';
                die;
            }
        }
    }
    
    public function forward_to_review(){
        if(isset($_POST) && count($_POST)>0){
            $update_data = array('complete'=>1);
            $saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
            $user_id = $this->session->userdata['id'];
            $this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$_POST['lead_id'],'message'=>'Lead Forward to Review','create_date'=>date('Y-m-d H:i:s')));
        }
        die;
    }
}

/* End of file leads.php */
/* Location: ./application/controllers/leads.php */