<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customers extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('customer_model');
		$this->load->model('acquisition_model');
		$this->load->model('lead_model');
		$this->load->model('general_model');
		$this->load->model('client_model');
		$this->load->model('user_model');
		$this->layout->auto_render=false;	
		$this->layout->layout='default';
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));		
	}
	
	public function direct_scrap_contacts(){
		$name = explode(' ',$this->input->get('first_name'));
		$saveData = array('first_name'=>$name[0],'last_name'=>$name[1],'email'=>$this->input->get('email'),'company_name'=>$this->input->get('company'),'profile_url'=>$this->input->get('url'),'job_title'=>$this->input->get('job_title'));
		$data = $this->lead_model->insertPreContacts($saveData);
		if($data>0){
		?>
		<script>window.close()</script>
		<?php
		}
		die;
	}
	
	public function free_precontacts(){	
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			if(!empty($this->input->post('text'))){
				$saveData = array('message'=>$this->input->post('text'));
				$data = $this->lead_model->insertFreePreContacts($saveData);
			}
		}
		echo $data;
		die;
	}
	
	public function litigation_scrap(){	
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			if(!empty($this->input->post('text')) && !empty($this->input->post('url'))){
				$saveData = array('data'=>$this->input->post('text'),'url'=>$this->input->post('url'));
				$data = $this->lead_model->insertLitigationScrap($saveData);
			}
		}
		echo $data;
		die;
	}
	
	public function campaign(){
		$data = array('email'=>array(),'linked_in'=>array()); 
		if(isset($_POST) && count($_POST)>0){
			if((int)$_POST['id']>0){
				$data = $this->lead_model->getCampaign($_POST['id'],$_POST['campaign_type']);
			}else{
				$data['email'] = $this->lead_model->getCampaign($_POST['id'],1);
				$data['linked_in'] = $this->lead_model->getCampaign($_POST['id'],2);
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function delete_address_from_campaign(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$campaignID = $_POST['campaign_id'];
					$to = $_POST['to'];
			$data = $this->lead_model->delete_address_from_campaign($campaignID,$to);
		}
		echo $data;
		die;
	}
	
	public function start_campaign(){
		$campaign_id = 0; 
		
		if(isset($_POST) && count($_POST)>0){
			$campaign = json_decode($_POST['campaign_data'],true);
		  $personList = explode(',',$campaign['person']);
			 $company = explode(',',$campaign['company']);
		   $emailList = explode(',',$campaign['email']);
			   $campaign_id = $this->lead_model->insertCampaign(array("lead_id"=>$campaign['lead_id'],"lead_name"=>$campaign['lead_name'],"campaign_type"=>$campaign['campaign_type'],"template_file"=>$campaign['template_file'],"user_id"=>$campaign['user_id'],"subject"=>$campaign['subject'],"body"=>$campaign['body'],"start_date"=>$campaign['start_date']));
			  
			   if($campaign_id>0){				   
				  if(count($emailList)>0){
					   for($i=0;$i<count($emailList);$i++){
						   if(!empty(trim($emailList[$i]))){
							   $this->lead_model->insertCampaignList(array("campaign_id"=>$campaign_id,"person_name"=>trim($personList[$i]),"company"=>trim($company[$i]),"address"=>trim($emailList[$i])));
						   }						   
					   }
				   }
			   }
		}
		echo $campaign_id;
		die;
	}
	
	public function pre_contacts(){
		$data = 0; 
		if(isset($_POST) && count($_POST)>0){
			$saveData = array('first_name'=>$_POST['first_name'],'last_name'=>$_POST['last_name'],'email'=>$_POST['email'],'company_name'=>$_POST['company'],'profile_url'=>$_POST['url'],'c_c'=>$_POST['c_c'],'job_title'=>$_POST['job_title']);
			$data = $this->lead_model->insertPreContacts($saveData);
		}
		echo $data;
		die;
	}
	
	function update_pre_contacts(){
		$data = 0; 
		if(isset($_POST) && count($_POST)>0){
			if($_POST['t']=='pre-contact'){
				$saveData = array('first_name'=>$_POST['first_name'],'last_name'=>$_POST['last_name'],'email'=>$_POST['email'],'company_name'=>$_POST['company'],'profile_url'=>$_POST['url'],'address'=>$_POST['address'],'job_title'=>$_POST['job_title'],'telephone'=>$_POST['telephone'],'cellphone'=>$_POST['phone']);
				$data = $this->lead_model->updatePreContacts($saveData,$_POST['id']);
			} else if($_POST['t']=='contact'){
				$saveData = array('first_name'=>$_POST['first_name'],'last_name'=>$_POST['last_name'],'email'=>$_POST['email'],'linkedin_url'=>$_POST['url'],'address'=>$_POST['address'],'job_title'=>$_POST['job_title'],'telephone'=>$_POST['telephone'],'phone'=>$_POST['phone']);
				$data = $this->lead_model->updateContact($saveData,$_POST['id']);
			}
		}
		echo $data;
		die;
	}
	
	function save_process_campaign(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){			
			if(isset($_POST['lead_id']) && (int)$_POST['lead_id']>0){
				if(isset($_POST['to'])){
					$to = trim($_POST['to']);
					if(!empty($to)){
						$checkCampaign = $this->lead_model->checkCampaignProcess($this->input->post('campaign_id'));
						if($checkCampaign==0){
							$data = $this->lead_model->insertCampaignProcess($this->input->post());
						}						
					}
				}
			}
		}
		echo $data;
	}
	
	function findEmailImapSearch(){
		$folder = '[Gmail]/Sent Mail';
		$emailAddress = 'webmaster@synpat.com';
		$time = date('D, d M Y',strtotime('-5 days',strtotime('now')));
		$data = array('send'=>0);
		$hostname = 'imap.gmail.com:993';
		$this->config->load('config');
		$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl','folder'=>$folder);
		$this->load->library('Imap',$params);
		$s = 0;
		if($this->imap->isConnected()){
			/*$this->imap->selectFolder('[Gmail]/Sent Mail');*/
			/*$messages = $this->imap->getMessages();*/
			echo $q = "TO (".$emailAddress.") SINCE  ".$time."  00:00:00";
			$messages = $this->imap->imapSearch($q);
			echo "<pre>";
			print_r($messages);
		}
		die;
	}
	
	function findEmailImapSendBox($subject,$toUsers,$date,$leadID,$type=1,$sendFrom =0,$userID){
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
				for($m=count($messages)-1;$m>=0;$m--){
					if($messages[$m]['subject']==$subject){						
						$toSendBox = $messages[$m]['to'];
						foreach($toSendBox as $to){
							if(count($toUsers)>0){
								for($i=0;$i<count($toUsers)-1;$i++){
									if(!empty(trim($toUsers[$i])) == trim($to)){
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
	
	public function update_campaign_addresss_send(){
		$data = 0; 
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['campaign_id']) && (int)$_POST['campaign_id']>0){
				if(isset($_POST['to'])){
					$to = trim($_POST['to']);
					if(!empty($to)){
						$data = $this->lead_model->updateCampaignList($_POST['campaign_id'],$to,array('send'=>1));
					}
				}
			}
		}
		echo $data ;
		die;
	}
	
	public function sales_activity_email_save(){
		$data = 0; 
		if(isset($_POST) && count($_POST)>0){			
			if(isset($_POST['lead_id']) && (int)$_POST['lead_id']>0){
				if(isset($_POST['to'])){
					$to = trim($_POST['to']);
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
							if(isset($_POST['body'])){
								$note = $_POST['body'];
							}
							if(isset($_POST['subject'])){
								$subject = $_POST['subject'];
							}
							if(isset($_POST['file_html']) && !empty($_POST['file_html'])){
								$note = $note."<br/><a href='".$_POST['file_html']."' target='_blank' style='color:#2196f3'>File</a>";
							}
							
							$date = date('Y-m-d H:i:s');
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
							$saveData = array('lead_id'=>$_POST['lead_id'],'company_id'=>$getContact->company_id,'contact_id'=>$getContact->id,'type'=>$_POST['type'],'note'=>$note,'user_id'=>$_POST['user_id'],'subject'=>$subject,'activity_date'=>$date,"email_id"=>$email_id);		
						
							if((int)$_POST['main_activity']==2){
								$data = $this->lead_model->insertAcquistionActivity($saveData);
							} else {
								/*$saveData['note'] = '<div>'.$note.'</div>';*/
								$data = $this->lead_model->insetSalesActivity($saveData);
							}
							if($data>0){
								$this->lead_model->updateCampaignList($_POST['campaign_id'],$to,array('send'=>1,'proccessed'=>1));
								$user_history = array('lead_id'=>$_POST['lead_id'],'user_id'=>$_POST['user_id'],'message'=>$message,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
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
	
	
	
	function docketDocumentSalesActivity(){
		$data = array("error"=>1,"id"=>0,"message"=>"Server busy, Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['le']) && (int)$_POST['le']>0 && (int)$_POST['p']>0){
				$getContact = $this->client_model->getInfo($_POST['p']);
				$getLeadInfo = $this->lead_model->getLeadData($_POST['le']);
				if(count($getContact)>0){
					$note = $_POST['m'];
					if(isset($_POST['file'])&& !empty($_POST['file'])){
						$note = $note.'<br/><a  href="javascript://" data-file-id="'.$_POST['i'].'" onclick=\'open_drive_files("'.trim($_POST['file']).'");\' data-href="'.$_POST['file'].'" data-mime="'.$_POST['mime'].'" target="_blank" style="color:#2196f3"><i class="glyph-icon tooltip-button pull-left icon-file-o"></i>&nbsp;&nbsp;'.$_POST['title'].'</a>';				
						$saveData = array('lead_id'=>$_POST['le'],'company_id'=>$getContact->company_id,'contact_id'=>$getContact->id,'type'=>6,'note'=>$note,'user_id'=>$this->session->userdata['id'],'subject'=>$getLeadInfo->lead_name,'activity_date'=>date('Y-m-d H:i:s'));
						$dataSales = $this->lead_model->insetSalesActivity($saveData);
						if($dataSales>0){
							$data = array("error"=>0,"id"=>$dataSales,"message"=>"");
							$user_history = array('lead_id'=>$_POST['le'],'user_id'=>$this->session->userdata['id'],'message'=>'Add Email in sales activity','opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
						}
					}	
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function create_account(){
		$post = $this->input->post();
		if(isset($post['email']) && !empty($post['email'])){
			/*$checkUser = $this->customer_model->checkEmail($post['email']);			
			if($checkUser==0){
				unset($post['confirm_password']);
				unset($post['captcha']);
				$post['create_date'] = date('Y-m-d H:i:s');
				$post['password'] = md5($post['password']);
				$checkCompany = $this->customer_model->checkCompanyExist($post['company_name']);
				if($checkCompany==0){
					$companyID = $this->customer_model->insertCompany(array('company_name'=>$post['company_name'],'company_address'=>$post['company_address']));
					if($companyID>0){
						$post['company_id'] = $companyID;
						unset($post['company_name']);
						unset($post['company_address']);
						$id = $this->customer_model->insertCustomer($post);
						if($id>0){
							echo json_encode(array("error"=>'User saved successfully',"id"=>$id));
						} else {
							echo json_encode(array("error"=>'Server is busy. Please try after sometime.',"id"=>0));
						}
					} else {
						echo json_encode(array("error"=>'Server busy, Please try after sometime.',"id"=>0));
					}
				} else {
					echo json_encode(array("error"=>'Company already exist.',"id"=>0));
				}
			} else {
				echo json_encode(array("error"=>'Email is already exist.',"id"=>0));
			}*/
			/*Store In Temporary Data*/
			$checkUserFromTempTable = $this->customer_model->checkEmailFromRequest($post['email']);
			if($checkUserFromTempTable==0){
				unset($post['confirm_password']);
				unset($post['captcha']);
				$post['create_date'] = date('Y-m-d H:i:s');
				$post['password'] = md5($post['password']);
				$id=$this->customer_model->insertCustomerRequest($post);
				if($id>0){
					echo json_encode(array("error"=>'User saved successfully',"id"=>$id));
				} else {
					echo json_encode(array("error"=>'Server is busy. Please try after sometime.',"id"=>0));
				}
			} else {
				echo json_encode(array("error"=>'Email is already exist.',"id"=>0));
			}
		} else {
			echo json_encode(array("error"=>'Data is not valid. Please try after sometime.',"id"=>0));
		}
		die;
	}
	
	public function delete_customer(){
		$post = $this->input->post();
		$data = 0;
		if(isset($post['token']) && !empty($post['token'])){
			$decodeToken = base64_decode($post['token']);
			$explodeToken = explode('&&&',$decodeToken);
			if(count($explodeToken)==3){
				if((int)$explodeToken[1]>0){
					$data = $this->customer_model->deleteCustomer($explodeToken[1]);
				}
			}
		}
		echo $data;
		die;
	}
	
	function deleteCustomerRequest(){
		$post = $this->input->post();
		$data = 0;
		if(isset($post['s']) && !empty($post['s'])){
			if((int)$post['s']>0){
				$data = $this->customer_model->deleteCustomerRequest($post['s']);
			}
		}
		echo $data;
		die;
	}
	
	public function login(){
		$post = $this->input->post();
		if(isset($post['email']) && !empty($post['email']) && isset($post['password']) && !empty($post['password'])){
			$checkUser = $this->customer_model->login($post['email'],md5($post['password']));			
			if(count($checkUser)>0){				
				if((int)$checkUser->status==1){
					echo json_encode(array("error"=>'Please make sure the email address and password you provided relate to an activated account. If you have not yet received an activation email you may either contact SynPat customer relations by phone or at customers@synpat.com.',"id"=>0));
				} else if((int)$checkUser->status==0){
					echo json_encode(array("error"=>'',"id"=>1,"data"=>$checkUser));
				} else {
					echo json_encode(array("error"=>'You account is suspended. Please wait.',"id"=>0));
				}				
			} else {
				echo json_encode(array("error"=>'Email or Password is wrong.',"id"=>0));
			}
		} else {
			echo json_encode(array("error"=>'Data is not valid. Please try after sometime.',"id"=>0));
		}
		die;
	}
	
	public function user_bulk_add_and_personal_password(){
		$postData = $this->input->post();
		$post = json_decode($postData ['post_data']);
		$successUser = array();
		$errorUser = array();
		$error = "";
		if(isset($post->first_name) && count($post->first_name)>0){
			for($i=0;$i<count($post->first_name);$i++){
				if(!empty($post->first_name[$i]) && !empty($post->email_address[$i])){
					$checkUser = $this->customer_model->checkEmail($post->email_address[$i]);			
					if($checkUser==0){
						$user = array();
						$user['first_name'] = $post->first_name[$i];
						$user['last_name'] = $post->last_name[$i];
						$user['email'] = $post->email_address[$i];
						$user['phone_number'] = $post->phone_number[$i];
						$user['password'] = md5('123456');
						$user['status'] = $post->status;
						$user['company_id'] = $post->company_id;
						$user['create_date'] = date('Y-m-d H:i:s');
						$userID = $this->customer_model->insertCustomer($user);
						if($userID>0){
							unset($user['create_date']);
							unset($user['status']);
							unset($user['password']);
							$user['phone'] = $user['phone_number'];
							unset($user['phone_number']);
							$this->client_model->insert($user);
							$successUser[] = $user['email'];
							$error = "";
						} else {
							$error = "Server busy, Please try after sometime.";
						}
					} else {
						$errorUser[] = $post->email_address[$i];
					}
				}
			}
		}
		if(isset($post->change_password) && $post->change_password=='1'){
			if(isset($post->old_password) && !empty($post->old_password) && !empty($post->new_password)){
				$checkUserInfo = $this->customer_model->checUserDetail($post->login_user);
				if(count($checkUserInfo)>0){
					if(md5($post->old_password) == $checkUserInfo->password){
						if(!empty($post->new_password) && !empty($post->confirm_password) && $post->new_password == $post->confirm_password){
							$data['password'] = md5($post->new_password);
							$updateUserInfo = $this->customer_model->updateUserInfo($checkUserInfo->id,$data);
							if($updateUserInfo>0){
								$errorPersonalInfo = "";
								$successPersonalInfo = "Successfully update your password.";
							} else {
								$errorPersonalInfo = "Server busy, Please try after sometime.";
								$successPersonalInfo = "";
							}
						} else {
							$errorPersonalInfo = "Password and confirm password not match.";
							$successPersonalInfo = "";
						}
					} else {
						$errorPersonalInfo = "Old password doesn't match.";
							$successPersonalInfo = "";
					}
				} else {
					$errorPersonalInfo = "Server busy, Please try after sometime.";
					$successPersonalInfo = "";
				}
			} else {
				$errorPersonalInfo = "Old password cannot be left blank.";
				$successPersonalInfo = "";
			}
		}
		if(isset($post->change_details) && $post->change_details=='1'){
			if(!empty($post->my_first_name) && !empty($post->my_phone_number)){
				$checkUserInfo = $this->customer_model->checUserDetail($post->login_user);
				$updateUserInfo = $this->customer_model->updateUserInfo($checkUserInfo->id,array('first_name'=>$post->my_first_name,'last_name'=>$post->my_last_name,'phone_number'=>$post->my_phone_number));
				if($updateUserInfo>0){
					$errorPersonalInfo = "";
					$successPersonalInfo = "Successfully update your information.";
				} else {
					$errorPersonalInfo = "Server busy, Please try after sometime.";
					$successPersonalInfo = "";
				}
			}
		}
		if(isset($post->company_address) && !empty($post->company_address) && !empty($post->telephone) && !empty($post->bank_name) && !empty($post->bank_account_no) && !empty($post->routing_no)){
			$this->customer_model->updateCompanyData($post->company_id,array("company_address"=>$post->company_address,'telephone'=>$post->telephone,'bank_name'=>$post->bank_name,'bank_account_no'=>$post->bank_account_no,'routing_no'=>$post->routing_no));
		}
		echo json_encode(array("all_messages"=>array("personal_detail"=>array("error"=>$errorPersonalInfo,"success"=>$successPersonalInfo)),"userDetail"=>array("error"=>$error,"errorUser"=>$errorUser,"successUser"=>$successUser)));
		die;
	}
	
	public function check_activation_code(){
		$post = $this->input->post();
		$data = array();
		if(isset($post['code']) && !empty($post['code'])){
			$data= $this->customer_model->checkActivationCode($post['code']);
		}
		echo json_encode($data);
		die;
	}
	
	public function update_password(){
		$post = $this->input->post();
		$dataUpdated = 0;
		if(isset($post['activation_code']) && !empty($post['activation_code'])){
			$data= $this->customer_model->checkActivationCode($post['activation_code']);
			if(count($data)>0){
				if($post['new_password'] == $post['confirm_password']){
					$dataUpdated = $this->customer_model->updateUserInfo($data->id,array('password'=>md5(trim($post['new_password'])),'activation_code'=>''));
					if($dataUpdated==0){
						$dataUpdated = $this->customer_model->updateUserInfo($data->id,array('activation_code'=>$post['activation_code']));
					}
				}
			}			
		}
		echo $dataUpdated;
		die;
	}
	
	public function check_user_email(){
		$post = $this->input->post();
		$data = array();
		if(isset($post['email']) && !empty($post['email'])){
			$checkUserInfo= $this->customer_model->checkEmail($post['email'],0);			
			if(count($checkUserInfo)>0){
				$userData['activation_code'] = md5($checkUserEmail->email."@@".mt_rand(15,80));
				$userUpdate = $this->customer_model->updateUserInfo($checkUserInfo->id,$userData);
				if($userUpdate>0){
					$data = $this->customer_model->checkEmail($post['email'],0);
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function dashboard(){
		$post = $this->input->post();
		$data = array("users"=>array(),"wishlist"=>array(),"preferences"=>array(),"portfolios"=>array());
		if(count($post)>0){
			if(isset($post['company_id']) && (int) $post['company_id']>0){
				$data['users'] = $this->customer_model->getUsersList($post['company_id']);
			}
			if(isset($post['customer_id']) && (int)$post['customer_id']>0){
				$getAllPortfolioIDs = $this->customer_model->getCustomerWishListIDs($post['customer_id']);
				if(count($getAllPortfolioIDs)>0){
					$data['wishlist'] = $this->acquisition_model->getAllPortfoliosWithIDs($getAllPortfolioIDs);						
				}
				$data['preferences'] = $this->customer_model->findMyPreferenceWithName($post['customer_id']);
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function usersListInCompany(){
		$post = $this->input->post();
		$getUsersList = array();
		if(count($post)>0){
			if(isset($post['company_id']) && (int) $post['company_id']>0){
				$getUsersList = $this->customer_model->getUsersList($post['company_id']);
			}
		}
		echo json_encode($getUsersList);
		die;
	}
	
	public function insertMyPreference(){
		$post= $this->input->post();
		$data = 0;
		if(isset($post['all_preference'])){
			$preference = json_decode($post['all_preference']);
			if(count($preference)>0){
				$this->customer_model->deleteCustomerPreference($post['customer_id']);
				foreach($preference as $pref){
					if((int)$pref>0){
						$data = $this->customer_model->insertCustomerPreference(array("customer_id"=>$post['customer_id'],"preference_id"=>$pref));
					}
				}
			}
		}
		$getMyPreference = $this->customer_model->findMyPreference($post['customer_id']);
		echo json_encode(array("preference"=>$getMyPreference,"data"=>$data));
		die;
	}
	
	function addToWishList(){
		$post= $this->input->post();
		$data = "";
		if(isset($post['token'])){
			$token =  $post['token'];
			$decodeValue = base64_decode($token);
			$explodeDecodeValue = explode('&&&',$decodeValue);
			if(count($explodeDecodeValue)==3){
				$checkUser = $this->customer_model->checkUserWithIDAndEmail($explodeDecodeValue[1],$explodeDecodeValue[2]);
				if(count($checkUser)>0){
					$checkProductInWhishList = $this->customer_model->checkWishList($explodeDecodeValue[0],$explodeDecodeValue[1]);
					if(count($checkProductInWhishList)==0){
						$saveToWishList = $this->customer_model->insertWishlist(array('portfolio_id'=>$explodeDecodeValue[0],'customer_id'=>$explodeDecodeValue[1]));
						if($saveToWishList>0){
							$data = "This product is added in your wishlist.";
						}
					} else {
						$data = "This product is already in your wishlist.";
					}					
				} else {
					$data = "Server busy, Please try after sometime.";
				}
			} else {
				$data = "Server busy, Please try after sometime.";
			}
		}
		echo $data;
		die;
	}
	
	function removeToWishList(){
		$post= $this->input->post();
		$data = 0;
		if(isset($post['token'])){
			$token =  $post['token'];
			$decodeValue = base64_decode($token);
			$explodeDecodeValue = explode('DELETE',$decodeValue);
			if(count($explodeDecodeValue)==3){
				$checkUser = $this->customer_model->checUserDetail($explodeDecodeValue[2]);
				if(count($checkUser)>0){
					$checkProductInWhishList = $this->customer_model->checkWishList($explodeDecodeValue[1],$explodeDecodeValue[2]);
					if(count($checkProductInWhishList)>0){
						$saveToWishList = $this->customer_model->deleteWishlist($checkProductInWhishList->id);
						if($saveToWishList>0){
							$data = $saveToWishList;
						}
					}				
				} 
			} 
		}
		echo $data;
		die;
	}
	
	function getCustomerWishList(){
		$post= $this->input->post();
		$getData = array();
		if(isset($post['customer_id']) && (int)$post['customer_id']>0){
			$getAllPortfolioIDs = $this->customer_model->getCustomerWishListIDs($post['customer_id']);
			if(count($getAllPortfolioIDs)>0){
				$getData = $this->acquisition_model->getAllPortfoliosWithIDs($getAllPortfolioIDs);	
			}
		}
		echo json_encode($getData);
		die;
	}
	
	public function getPreference($customerID){
		$post= $this->input->post();
		$getMyPreference = array();
		if(isset($post['customer_id'])){
			$getMyPreference = $this->customer_model->findMyPreference($post['customer_id']);
		}
		echo json_encode($getMyPreference);
		die;
	}
	
	public function getCategoryList(){
		$post = $this->input->post();
		$category = 0;
		if(isset($post['category'])){
			$cat = explode('-',$post['category']);
			if(count($cat)==2){
				$category = (int)$cat[1];
			}
		}
		$getCategory = $this->customer_model->categoryList(0);
		if(count($getCategory)>0){
			for($i=0;$i<count($getCategory);$i++){
				$getCategory[$i]->child_list = $this->customer_model->categoryList($getCategory[$i]->id);
			}
		}
		$productList = array();
		if($category>0){
			$productList = $this->acquisition_model->findPortfolios($category);			
		}
		$invitationProduct= array();
		if($post['invitation']>0){
			$invitationProduct= $this->lead_model->findPortfolioWithSerial($post['invitation']);
		}
		echo json_encode(array('categories'=>$getCategory,'products'=>$productList,'invitation_product'=>$invitationProduct));
		die;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function deactivate_all_users($ID=null){
		if($ID!=null){
			$getAllUsersInCompany = $this->customer_model->customersList($companyID);
			if(count($getAllUsersInCompany)>0){
				foreach($getAllUsersInCompany as $user){
					$this->customer_model->updateUserInfo($user->id,array('status'=>1));
				}
			}
		}
		redirect('customers/companies_list');
	}
	public function activate_all_users($ID=null){
		if($ID!=null){
			$getAllUsersInCompany = $this->customer_model->customersList($companyID);
			if(count($getAllUsersInCompany)>0){
				foreach($getAllUsersInCompany as $user){
					$this->customer_model->updateUserInfo($user->id,array('status'=>0));
				}
			}
		}
		redirect('customers/companies_list');
	}
	
	
	public function companies_list(){
		if(!isset($this->session->userdata['type'])){
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']);
			} else {
				redirect('login');
			}
		} else if((int)$this->session->userdata['type']!=9){
			$this->session->set_flashdata('error','You are not authorized user to view this page.');
			redirect('dashboard');
		}
		$this->layout->auto_render=false;
		$this->layout->layout='default';		
		$data['companies'] = $this->customer_model->getAllCustomerCompanyList();
		$this->layout->title_for_layout = 'SynPat Companies List';
		$this->layout->render('customer/companies_list',$data);
	}
	
	public function get_all_customers($companyID = null){
		$data['users_list'] = $this->customer_model->customersList($companyID);
		$this->layout->auto_render=false;
		$this->layout->layout='default';		
		$this->layout->title_for_layout = 'Customers List';
		$this->layout->render('customer/get_all_customers',$data);
	}
	
	public function find_departments(){
		$data= array('dep'=>array(),'c_d'=>array());
		if(isset($_POST) && count($_POST)>0){
			if((int)$this->input->post('s')>0){
				$data['dep'] = $this->general_model->getSectorDepartmentsName($this->input->post('s'));
			}
			if((int)$this->input->post('c')>0){
				$data['c_d'] = $this->customer_model->findMyPreference($this->input->post('c'));
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function find_sub_deptt(){
		$data= array('dep'=>array(),'c_d'=>array());
		if(isset($_POST) && count($_POST)>0){
			if(count($this->input->post('s'))>0){
				$deptt = implode(',',$this->input->post('s'));
				$data['dep'] = $this->customer_model->categoryListWithMoreThanOne($deptt);
			}
			if((int)$this->input->post('c')>0){
				$data['c_d'] = $this->customer_model->findMyPreference($this->input->post('c'));
			}
		}
		echo json_encode($data);
		die;
	}
	public function myTransaction(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			if((int)$this->input->post('customer_id')>0){
				$data = $this->general_model->getAllTransactionByContactID($this->input->post('customer_id'));
			}
		}
		echo json_encode($data,true);
		die;
	}
	
	public function last_page_no(){
		$resultOver = $this->general_model->getCountInsertedData();
		echo $resultOver + 1;
		die;
	}
	
	public function extension_linkedin_scrap(){
		$data = array('page_no'=>0,'saved'=>0);
		if(isset($_POST) && count($_POST)>0){
			$scrap = $this->input->post('scrap');
			$saved = 0;
			if(!empty($scrap)){
				$result = json_decode($scrap);
				if(count($result)>0){
					for($i=0;$i<count($result);$i++){
						$record = array("name"=>(string)$result[$i]->name,"company_name"=>(string)$result[$i]->company_name,"job_title"=>(string)$result[$i]->job_title,"profile_url"=>(string)$result[$i]->profile_url,"c_c"=>(int)$result[$i]->c_c,"distance"=>(int)$result[$i]->distance);
						$saved = $this->general_model->insertLinkedinContacts($record);
					}
				}
			}
			$this->general_model->updatePageNo(1,array('page_no'=>$this->input->post('page_no')));
			$resultOver = $this->general_model->getCountInsertedData();
			$data = array('page_no'=>$resultOver+1,'saved'=>$saved);
		}
		echo json_encode($data);
		die;
	}
	
	function bounce_email(){
		$fileName = $_SERVER['DOCUMENT_ROOT'].'/public/upload/bounce.json';
		$f = fopen($fileName,"w+");
		$data= $_POST;
		fwrite($f,json_encode($data));
		fclose($f);
		if($data['event']=="dropped"){
			$messageID = $data['Message-Id'];
			$this->load->model('outsource_model');
			$checkDropEmail = $this->outsource_model->checkProjectDataWithMessageID($messageID);
			if(count($checkDropEmail)>0){
				$this->outsource_model->updateFormData(array('data'=>'','enter_by'=>'Free','user_id'=>0,'recorded_date'=>'0000-00-00 00:00:00','message_id'=>''),$checkDropEmail->id);
				/*$this->load->model('client_model');*/
				unset($checkDropEmail->id);
				$this->outsource_model->uploadDataToDropHistory($checkDropEmail);
				$this->client_model->update($checkDropEmail->contact_id,array('email'=>'','new_email'=>0));
			}
		}		
		die;
	}
	
	public function changePhoneFormating(){
		$allContacts = $this->client_model->getAllContacts();
		foreach($allContacts as $contact){
			$phone = $contact->phone;			
			$originalPhone = $phone;			
			/*$phone = str_replace('+','',$phone);
			$phone = str_replace('(','',$phone);
			$phone = str_replace(')','',$phone);
			$phone = str_replace('-','',$phone);
			$phone = str_replace('.','',$phone);
			$phone = str_replace(' ','',$phone);
			if(strlen($phone)==10){
				$phone = '+1'.$phone;
			} else if(strlen($phone)>10){
				$phone = '+'.$phone;
			}*/
			$telephone = $contact->telephone;			
			$originalTelephone = $telephone;
			/*$telephone = str_replace('+','',$telephone);
			$telephone = str_replace('(','',$telephone);
			$telephone = str_replace(')','',$telephone);
			$telephone = str_replace('-','',$telephone);
			$telephone = str_replace('.','',$telephone);
			$telephone = str_replace(' ','',$telephone);
			if(strlen($telephone)==10){
				$telephone = '+1'.$telephone;
			} else if(strlen($telephone)>10){
				$telephone = '+'.$telephone;
			}
			if(!empty($phone)){
				$phoneAll = substr($phone,0,-4);
				$phoneFormat = substr($phone,strlen($phone)-4);
				$phone = $phoneAll." ".$phoneFormat;
				$phoneAll = substr($phone,0,-8);
				$phoneFormat = substr($phone,strlen($phone)-8);
				$phone = $phoneAll." ".$phoneFormat;
				$phone = trim($phone);
			}
			if(!empty($telephone)){
				$phoneAll = substr($telephone,0,-4);
				$phoneFormat = substr($telephone,strlen($telephone)-4);
				$telephone = $phoneAll." ".$phoneFormat;
				$phoneAll = substr($telephone,0,-8);
				$phoneFormat = substr($telephone,strlen($telephone)-8);
				$telephone = $phoneAll." ".$phoneFormat;
				$telephone = trim($telephone);
			}
			$this->lead_model->updateContact(array('phone'=>$phone,'telephone'=>$telephone),$contact->id);*/
			echo "Name: ".$contact->first_name." ".$contact->last_name."<br/>----Orginal".$originalPhone."<br/>---- Phone: ".$phone." <br/>----Original Telephone".$originalTelephone."<br/>---- Telephone: ".$telephone."<br/>";
		}
	}
}
/* End of file customers.php */
/* Location: ./application/controllers/customers.php */