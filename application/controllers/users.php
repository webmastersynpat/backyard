<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($this->session->userdata['type']) || empty($this->session->userdata['email'])){
			/*$this->session->set_flashdata('error','Please login first!');*/
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']); 
			} else {
				redirect('login');
			}
		}
		$this->load->model('user_model');
		$this->load->model('lead_model');
		$this->load->model('client_model');
		$this->load->model('customer_model');
		$this->load->model('general_model');		
		$this->load->model('acquisition_model');
		$this->load->model('opportunity_model');
		if(!isset($this->session->userdata['signature']) || empty($this->session->userdata['signature'])){
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
		}
		
		$this->layout->auto_render=false;	
		$this->layout->layout='default';
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));		
	}
	
	public function anyDoLogin(){
		$loginURL = "https://sm-prod.any.do/j_spring_security_check";
		echo $loginURL."<br/>";
		$curl = curl_init();
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Keep-Alive: 300"; 
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
		$header[] = "Accept-Language: en-us,en;q=0.5"; 
		$header[] = "Pragma: "; // browsers keep this blank.
		curl_setopt($curl, CURLOPT_URL, $loginURL); 
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate,sdch'); 
		curl_setopt($curl, CURLOPT_COOKIESESSION, true); 
		curl_setopt($curl, CURLOPT_POST, true); 
		curl_setopt(CURLOPT_POSTFIELDS, array(
				'j_username' => "webmaster@synpat.com",
				'j_password'=>"N@mish2512",
				'_spring_security_remember_me'=>"on",
			)); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 180000); 		
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		echo "<pre>";
		print_r($resp);
		die;
	}
	
	public function countUnseenMeesagesImap(){
		$count = 0;
		$hostname = 'imap.gmail.com:993';
		$this->config->load('config');
		$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl','folder'=>'INBOX');
		$this->load->library('Imap',$params);
		if($this->imap->isConnected()){
			/*$messages = $this->imap->imapSearch('UNSEEN');*/
			$messages = $this->imap->getMessages();
			$count = count($messages);
		}
		echo $count;
		die;
	}
	
	public function imap_emails(){
		$messages  = array();
		/*$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';*/
		$hostname = 'imap.gmail.com:993';
		$this->config->load('config');
		$params = array('mailbox'=>$hostname,'username'=>$this->config->item('license_email'),'password'=>$this->config->item('license_password'),'encryption'=>'ssl');
		$this->load->library('Imap',$params);
		if($this->imap->isConnected()){
			$this->imap->selectFolder('INBOX');
			$messages = $this->imap->getMessages();
		}
		echo json_encode($messages);
		die;		
	}
	
	public function contacts_in_c(){
		$data = array();
		$post = $this->input->post();
		if(count($post)>0){
			$data = $this->client_model->getAllContactBelongToCompany($post['c']);
		}
		echo json_encode($data);
		die;
	}
	
	function company_list(){
		$this->load->model('customer_model');
		$companyList = $this->customer_model->companyList();
		echo json_encode($companyList);
		die;
	}
	
	function search_gmail(){
		$post = $this->input->post();
		if(count($post)>0){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$service->setAccessToken($_SESSION['another_access_token']);
			$q = "";
			$from="";
			$to="";
			$subject="";
			$has="";
			$doesntHave="";
			if(isset($post['search']) && !empty($post['search'])){
				$q = $post['search']." ";
			}
			
			if(isset($post['search_from']) && !empty($post['search_from'])){
				$q .= "from:(".$post['search_from'].") ";
				$from = $post['search_from'];
			}
			if(isset($post['search_to']) && !empty($post['search_to'])){
				$q .= "to:(".$post['search_to'].") ";
				$to = $post['search_to'];
			}
			if(isset($post['search_subject']) && !empty($post['search_subject'])){
				$q .= "subject:".$post['search_subject']." ";
				$subject = $post['search_subject'];
			}
			if(isset($post['has']) && !empty($post['has'])){
				$q .= $post['has']." ";
				$has =  $post['has'];
			}
			if(isset($post['doesnt_have']) && !empty($post['doesnt_have'])){
				$q .= "-{".$post['doesnt_have']."} ";
				$doesntHave = $post['doesnt_have'];
			}
			$q = trim($q);
			echo $q;
			if(!empty($q)){
				if($post['search']=="in:lead"){
					/*Search from emails database*/
					$emails = $this->lead_model->emailSearch($from,$to,$subject,$has,$doesntHave);
					if(count($emails)>0){
						foreach($emails as $message){
							$from = "";
							$subject = "";
							$data = "";
							$_dateD = "";
							$messageIDDD = "";
							$threadID = "";
							$messageID = "";
							$id=$message->id;
							$countAttachments = 0;
							$content = json_decode($message->content);					
							switch($message->account_type){
								case 1:
									$headers = $content[0]->header;
									foreach($headers as $header){					
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
									$threadID = $message->thread_id;
									$messageID = $message->message_id;
									$parts = $content[0]->parts;
									$countAttachments = 0;
									if(isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related")){
										for($i=1;$i<count($parts);$i++){
											$attachmentID = $parts[$i]->getBody()->getAttachmentId();
											if(!empty($attachmentID)){
												$countAttachments++;
											}
										}
									}
								break;
								case 2:
									$date = $content->header->Date;
									$subject = $content->header->subject;
									$from = $content->header->from[0]->personal;
									$messageIDDD = $content->header->message_id;
									$countAttachments = 0;
									$parts = $message->file_attach;
									$countAttachments = explode(',',$parts);
									$countAttachments = count($countAttachments)-1;
								break;
							}
						?>
							<div class="message-item media draggable" data-date='<?php echo $date?>' data-message-thread-id="<?php echo $threadID?>" data-id="<?php echo $messageID?>" data-message-id="<?php echo $messageIDDD;?>" data-task="0" data-acompany="<?php echo $message->aCompanyID?>" data-scompany="<?php echo $message->sCompanyID?>" data-atype="<?php echo $message->aType?>" data-stype="<?php echo $message->sType?>" data-lead="<?php echo $message->lead_id?>" data-lead-name="<?php echo $message->lead_name?>" data-send="<?php echo $message->account_type?>" data-type="<?php echo $message->from_activity?>">														
					<div class="message-item-right">
						<div class="media">																
							<div class="media-body" onclick="findOwnThread(<?php echo $id;?>,jQuery(this),2,2);">
								<h5 class="c-dark">								
									<a class="c-dark" style='font-weight:normal' href="javascript:void(0)"><?php echo $from;?></a>
								</h5>
								<h4 class="c-dark"><?php echo $subject;?></h4>
								<div>
									<span class="message-item-date"><?php echo date('M d, Y',strtotime($date));?></span>
									&nbsp;									
									<?php 
										
										if($countAttachments>0):
									?>
									<strong><i class="glyph-icon icon-paperclip"></i> <?php echo $countAttachments;?></strong>
									<?php endif;?>
									<!--<a href='javascript://' onclick="enableTask(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon icon-plus"></i></a>
									--><a href='javascript://' onclick="moveEmailToTrash(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon"><img src="http://backyard.synpat.com/public/images/discard.png" style="opacity:0.55;width:10px"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
						<?php
						}
					}
			}else{
				$emails = $service->searchEmails($q);
				foreach($emails as $message){
				$from ="";													
				$subject="";													
				$date = "";	
				$_dateD = "";
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
				<div class="message-item media draggable" data-date='<?php echo $date?>' data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>">														
					<div class="message-item-right">
						<div class="media">																
							<div class="media-body" onclick="findNewThread('<?php echo $message['message_id']?>',jQuery(this));">
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
									<span class="message-item-date"><?php echo date('M d, Y',strtotime($date));?></span>
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
										
										/*if(count($message['attachments'])>0):*/
										if($countAttachments>0){
									?>
									<strong><i class="glyph-icon icon-paperclip"></i> <?php echo $countAttachments;?></strong>
										<?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
				}
			}
			} else{
				echo "<p>No messages matched your search.</p>";
			}			
		}
		die;
	}
	
	function template_file_content(){
		if(isset($_POST) && count($_POST)>0 && isset($_POST['name'])){
			if(!empty($_POST['name'])){
				$fileName = str_replace($this->config->base_url(),$_SERVER['DOCUMENT_ROOT']."/",$_POST['name']);
				if(file_exists($fileName)){
					if($_POST['s']==1){
						echo file_get_contents($fileName);
					} else if($_POST['s']==2){
						$fileContent = file_get_contents($fileName);
						echo strip_tags($fileContent);
					}
					
				}
			}  
		}
		die;
	}
	
	function lead_templates($leadID = 0, $s=1){
		$data['lead_templates'] = array();
		if($leadID>0){
			$data['lead_templates_email'] = $this->lead_model->getLeadTemplates($leadID,0);
			$data['lead_templates_linkedin'] = $this->lead_model->getLeadTemplates($leadID,1);
		}
		$data['s'] = $s;
		$data['acquisition'] = $this->acquisition_model->getData($leadID);
		$data['category_list'] = $this->customer_model->categoryList(0);
		$data['lead_data'] = $this->lead_model->getLeadData($leadID);
		$this->layout->title_for_layout = 'Predefined Messages';
		$this->layout->layout='opportunity';
		$this->layout->render('user/lead_templates',$data);
	}
	
	function delete_lead_template(){
		$data = 0;
		if(isset($_POST['id']) && (int)$_POST['id']>0){
			$data = $this->lead_model->deleteLeadTemplate($_POST['id']);
		}
		echo $data;
	}
	
	function find_message_template_stage(){
		$data= array();
		if($this->input->post('st')!="" && (int)$this->input->post('lead')>0){
			$data = $this->general_model->checkLeadTemplateStage($this->input->post('st'),$this->input->post('lead'));
		}
		echo json_encode($data);
	}
	
	function saveTemplateScript(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$stage = $this->input->post('stage');
			if(!empty($stage)){
				if((int)$this->input->post('type')==1){
					if($this->input->post('lead')>0){
						$checkTemplate = $this->general_model->checkLeadTemplateStage($this->input->post('stage'),$this->input->post('lead'));
						if(count($checkTemplate)>0){
							$data = $this->general_model->updateTemplate(array('template_html'=>$_POST['template'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$this->input->post('stage'),'stage'=>$this->input->post('stage')),$checkTemplate->id);
							$user_history = array('lead_id'=>$this->input->post('lead'),'user_id'=>$this->session->userdata['id'],'message'=>"Template updated in lead bank",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
						} else {
							$data = $this->general_model->insertTemplate(array('template_html'=>$_POST['template'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$this->input->post('stage'),'stage'=>$this->input->post('stage'),'lead_id'=>$this->input->post('lead')));
							$user_history = array('lead_id'=>$this->input->post('lead'),'user_id'=>$this->session->userdata['id'],'message'=>"Save template in lead bank",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
						}						
					}
				} else {
					$checkTemplate = $this->general_model->checkBankTemplateStage($this->input->post('stage'));
					if(count($checkTemplate)>0){
						$data = $this->general_model->updateBankTemplate(array('template_html'=>$_POST['template'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$this->input->post('stage'),'stage'=>$this->input->post('stage')),$checkTemplate->id);
						$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Template updated in skeleton",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					} else {
						$data = $this->general_model->insertBankTemplate(array('template_html'=>$_POST['template'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$this->input->post('stage'),'stage'=>$this->input->post('stage')));
						$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Save template in skeleton",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					}
					
				}
			}
		}
		echo $data;
		die;
	}
	
	public function save_new_template(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['temp']) && !empty($_POST['temp'])){
				if($this->input->post('activity_type')=="208"){
					$data = $this->general_model->insertBankTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$_POST['name']));
					$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Create predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				} else if($this->input->post('activity_type')=="7"){
					$data = $this->general_model->insertTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$_POST['name'],'lead_id'=>$this->input->post('lead_id')));
					$user_history = array('lead_id'=>$this->input->post('lead_id'),'user_id'=>$this->session->userdata['id'],'message'=>"Create predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				}
			}
		} 
		echo $data;
		die;
	}
	
	public function update_template(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['temp']) && !empty($_POST['temp'])){
				if($this->input->post('activity_type')=="208"){
					$data = $this->general_model->updateBankTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'template_name'=>$_POST['name']),$_POST['id']);
					if($data==0){
						$data = $_POST['id'];
					}
					$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Update predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
				} else if($this->input->post('activity_type')=="7"){
					$data = $this->general_model->updateTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'template_name'=>$_POST['name']),$_POST['id']);
					if($data==0){
						$data = $_POST['id'];
					}
					$user_history = array('lead_id'=>$this->input->post('lead_id'),'user_id'=>$this->session->userdata['id'],'message'=>"Update predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				}
			}
		}
		echo $data;
		die;
	}
	
	public function delete_predefined_template(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$this->general_model->delete_template($_POST['id']);
			$data = 1;
		}
		echo $data;
		die;
	}
	
	public function save_template_file(){
		$data = "";
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['temp']) && !empty($_POST['temp']) && isset($_POST['lead_id']) && (int)$_POST['lead_id']>0){				
				$template = $this->input->post('temp');
				$lead_id = $this->input->post('lead_id');
				$urlName ="";
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
				$fileName = $lead_id.time().".html";
				$fh = fopen($_SERVER['DOCUMENT_ROOT']."/public/upload/html/".$fileName,"w+");
				fwrite($fh,$template);
				fclose($fh);
				$data = $this->lead_model->saveLeadTemplate(array('name'=>$_POST['name'],"lead_id"=>$lead_id ,"file_name"=>$this->config->base_url()."public/upload/html/".$fileName,'subject'=>$_POST['subject'],'type'=>$_POST['type']));	
				$user_history = array('lead_id'=>$lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"Create predefined template in lead",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}			
		}
		echo $data;
		die;
	}
	
	
	
	
	function leadLogTime(){
		$timeLine="";$timehours="";
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('l');
			$getMyLogTime = array('all_work'=>array());
			if((int)$leadID>0){
				$getMyLogTime = $this->user_model->getMyLogTimeWithLead($this->session->userdata['id'],$leadID);	
				$getLeadDetail = $this->lead_model->getLeadData($leadID);
			}?>
			
			<div class="row"> <div class="col-lg-12"> <div> <b><big>Time Flow</big></b> </div> <div class="timeline-wrapper-inner" style='height:500px;width:73%;float:left'> <?php 
										
										if(count($getMyLogTime['all_work'])>0){
									?> <table id="timelineDataTable" class='table dataTable no-footer' style='width:100%!important'> <thead> <tr> <th>Date</th> <th>Start</th> <th>End</th> <th>Duration</th> <th>Leads</th> <th>Adjustments</th> <th>Total Time</th> <th>Comment</th> </tr> </thead> <tbody> <?php
										for($i=0;$i<count($getMyLogTime['all_work']);$i++){
									?> <tr id="<?php echo $getMyLogTime['all_work'][$i]->id;?>"> <td><?php echo date('Y-m-d',strtotime($getMyLogTime['all_work'][$i]->login_date))?></td> <td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->login_date));?></td> <td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->logout_date));?></td> <td><?php echo $getMyLogTime['all_work'][$i]->hrsWorked?></td> <td> <?php echo $getLeadDetail->lead_name;?> </td> <td><select name="actualHrs<?php echo $getMyLogTime['all_work'][$i]->id;?>" id="actualHrs<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:73px' onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)"> <option value="">-- Adjust. --</option> <option value="-00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:15")?'SELECTED="SELECTED"':'';?>>-00:15</option> <option value="-00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:30")?'SELECTED="SELECTED"':'';?>>-00:30</option> <option value="-00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:45")?'SELECTED="SELECTED"':'';?>>-00:45</option> <option value="-01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:00")?'SELECTED="SELECTED"':'';?>>-01:00</option> <option value="-01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:15")?'SELECTED="SELECTED"':'';?>>-01:15</option> <option value="-01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:30")?'SELECTED="SELECTED"':'';?>>-01:30</option> <option value="-01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:45")?'SELECTED="SELECTED"':'';?>>-01:45</option> <option value="-02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:00")?'SELECTED="SELECTED"':'';?>>-02:00</option> <option value="-02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:15")?'SELECTED="SELECTED"':'';?>>-02:15</option> <option value="-02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:30")?'SELECTED="SELECTED"':'';?>>-02:30</option> <option value="-02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:45")?'SELECTED="SELECTED"':'';?>>-02:45</option> <option value="-03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:00")?'SELECTED="SELECTED"':'';?>>-03:00</option> <option value="-03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:15")?'SELECTED="SELECTED"':'';?>>-03:15</option> <option value="-03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:30")?'SELECTED="SELECTED"':'';?>>-03:30</option> <option value="-03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:45")?'SELECTED="SELECTED"':'';?>>-03:45</option> <option value="-04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:00")?'SELECTED="SELECTED"':'';?>>-04:00</option> <option value="-04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:15")?'SELECTED="SELECTED"':'';?>>-04:15</option> <option value="-04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:30")?'SELECTED="SELECTED"':'';?>>-04:30</option> <option value="-04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:45")?'SELECTED="SELECTED"':'';?>>-04:45</option> <option value="-05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-05:00")?'SELECTED="SELECTED"':'';?>>-05:00</option> <option value="00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="00:15")?'SELECTED="SELECTED"':'';?>>00:15</option> <option value="00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="0:30")?'SELECTED="SELECTED"':'';?>>00:30</option> <option value="00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="0:45")?'SELECTED="SELECTED"':'';?>>00:45</option> <option value="01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:00")?'SELECTED="SELECTED"':'';?>>01:00</option> <option value="01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:15")?'SELECTED="SELECTED"':'';?>>01:15</option> <option value="01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:30")?'SELECTED="SELECTED"':'';?>>01:30</option> <option value="01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:45")?'SELECTED="SELECTED"':'';?>>01:45</option> <option value="02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="2:00")?'SELECTED="SELECTED"':'';?>>02:00</option> <option value="02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:15")?'SELECTED="SELECTED"':'';?>>02:15</option> <option value="02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:30")?'SELECTED="SELECTED"':'';?>>02:30</option> <option value="02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:45")?'SELECTED="SELECTED"':'';?>>02:45</option> <option value="03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:00")?'SELECTED="SELECTED"':'';?>>03:00</option> <option value="03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:15")?'SELECTED="SELECTED"':'';?>>03:15</option> <option value="03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:30")?'SELECTED="SELECTED"':'';?>>03:30</option> <option value="03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:45")?'SELECTED="SELECTED"':'';?>>03:45</option> <option value="04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:00")?'SELECTED="SELECTED"':'';?>>04:00</option> <option value="04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:15")?'SELECTED="SELECTED"':'';?>>04:15</option> <option value="04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:30")?'SELECTED="SELECTED"':'';?>>04:30</option> <option value="04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:45")?'SELECTED="SELECTED"':'';?>>04:45</option> <option value="05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="05:00")?'SELECTED="SELECTED"':'';?>>05:00</option> </select> </td> <td> <?php
													$duration = $getMyLogTime['all_work'][$i]->hrsWorked;
													$adjustedlHrs = $getMyLogTime['all_work'][$i]->actual_hrs;
													if(!empty($adjustedlHrs)){
														$adjustedlHrs = $adjustedlHrs.":00";
													}
													$totalHrs = $duration;
													if($duration!='' && $duration!='00:00:00'){
														$d = strtotime($duration);
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("-".$h." hour  -".$m." minutes",$d));
														} else if($lengthOfAdjustedHrs==8){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("+".$h." hour  +".$m." minutes",$d));
														}
													} else{
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9 || $lengthOfAdjustedHrs==8){
															$totalHrs = $adjustedlHrs;
														}
													}
													echo $totalHrs;
												?> </td> <td style='width:430px'><textarea class='form-control' onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)" placeholder="Comment" name="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" id="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:400px;height:25px;border:0px'><?php echo $getMyLogTime['all_work'][$i]->comment?></textarea></td> </tr> <?php
										}
									?> </tbody> </table> <script>var timelineDataTable=$("#timelineDataTable").DataTable({autoWidth:true,paging:false,searching:false,destroy:true,oLanguage: {sEmptyTable:"No record found!"},scrollY:400,order:[[0,"desc"]]});</script> <?php
										}
									?> <div class="col-lg-12" style='border-top:1px solid #d1c8c8;width:99%'></div> </div> <div class='pull-right' style="width:25%;margin-top:8px"> <div class="row"> <div class="col-lg-12" style="width:285px"> <select class='form-control' onchange="findLeadHrs(jQuery(this))"> <option>--Select Lead--</option> <?php 
														if(count($getMyLogTime['allLeadsWorked'])>0){
															foreach($getMyLogTime['allLeadsWorked'] as $lead){
													?> <option value="<?php echo $lead->lead_id?>"><?php echo $lead->lead_name?></option> <?php
															}
														}
													?> </select>
									<?php $timehours  = $this->user_model->findFullHoursForLead($leadID,$this->session->userdata['id']);?>
													<label class='show mrg10T'><strong>Total Time Spent in this Lead: <?php echo $timehours;?></strong></label> </div> <div class="col-lg-12 mrg10T"> <label><strong>Total time spent in current month: <?php echo ($getMyLogTime['totalHoursCurrent']->totalHrsWorked!=null)?$getMyLogTime['totalHoursCurrent']->totalHrsWorked:'';?></strong></label> </div> <div class="col-lg-12 mrg10T"> <label><strong>Total time spend in previous month: <?php echo ($getMyLogTime['totalHours']->totalHrsWorked!=null)?$getMyLogTime['totalHours']->totalHrsWorked:'';?></strong></label> </div> </div> </div> </div> </div> <div class="mrg25T"> <b><big>Activity Flow</big></b> </div> <div id="mytimeline" class="mrg10T"></div>
								<?php 
									$getTimeLine = $this->user_model->getAllUserHistory($this->session->userdata['id'],$leadID,0);
								?>
								<script>__timelineD =[];</script>
								<?php 
								$checkURIController = "leads";
							if(count($getTimeLine)>0){
								foreach($getTimeLine as $timeline){	
									$label = "";
									$colorClass="";
									if($checkURIController=="leads"){
										$label = "Leads";
										$colorClass= "bg-yellow";
									} else if($checkURIController=="opportunity"){
										$label =  "Opportunity";
										$colorClass= "label-info";
									} else {
										if($timeline->opportunity_id==0){
											$label =  "Leads";
											$colorClass= "bg-yellow";
										} else {
											$label =  "Opportunity";
											$colorClass= "label-info";
										}
									}
									
									if(isset($timeline->leadType)){
										switch($timeline->leadType){
											case 'Litigation':
												$colorClass = "bg-yellow";
											break;
											
											case 'Market':
												$colorClass = "bg-green";
											break;
											
											case 'General':
												$colorClass = "label-info";
											break;
											
											case 'SEP':
												$colorClass = "bg-warning";
											break;
										}
										$label = (!empty($timeline->lead_name))?$timeline->lead_name:$timeline->plantiffs_name;
									}
								
						?>
<script>__timelineD.push({'start':new Date('<?php echo $timeline->create_date?>'),'content':'<span class="tl-label bs-label <?php echo $colorClass;?>"><?php echo $label;?></span><span class="todo-content"><?php echo $timeline->message;?></span>'});</script>
			<?php 
								}
			?>
			<script>jQuery(document).ready(function(){var items=new vis.DataSet(__timelineD);var container=document.getElementById('mytimeline');var options={maxHeight:'300px',type:'point',selectable:false,showMajorLabels:false,zoomMin:1000*60*60*24,zoomMax:1000*60*60*24*31*3};if(typeof timeline!='string'){timeline.destroy();}timeline=new vis.Timeline(container);timeline.setOptions(options);timeline.setItems(items);timeline.moveTo(new Date(),{animate:true});});</script>
			
			<?php
							}
		}
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
	
	
	function search_email($gmailMessageID = null){
		$findThreadData = array();
		if($gmailMessageID!=null){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$checkFromThread = $this->lead_model->findBoxByMessageID($gmailMessageID);
			if(count($checkFromThread)>0){
				redirect('users/own_server_email/'.$checkFromThread[0]->id);
			}
			$service->setAccessToken($_SESSION['another_access_token']);			
			$findThreadData = $service->findThreadData($gmailMessageID);
		}
		$data['thread_detail'] = $findThreadData;
		$data['type'] = 3;
		$this->layout->title_for_layout = 'Backyard Email Detail';
		$this->layout->layout='email';
		// $this->layout->layout='email';
		$this->layout->render('user/email',$data);
	}
	
	function getServiceAccountCalendar(){
		$time = new DateTime;
		$timeMin = $time->format(DateTime::ATOM);
		$this->load->library('DriveServiceHelper');
		$service = new CalendarServiceHelper();
		/*$colors = $service->getColor();
		$availableColor = array();
		foreach ($colors->getCalendar() as $key => $color) {
		  $availableColor[] = $color->getBackground();
		}*/
		$calendarList = $service->getCalendarList();
		$eventsAllUsersList = array();
		$eventUserAndColor = array();
		foreach($calendarList as $calendar){
			$userID = $calendar->id;
			$_name = explode('@',$userID);
			$userForegroundColor = $calendar->foregroundColor;
			$userBackgroundColor = $calendar->backgroundColor;
			$userTimezone = $calendar->timeZone;
			$userEtag = $calendar->etag;
			$eventList = $service->getEventsList($calendar->id,$timeMin);
			$eventUserAndColor[] = array('user_id'=>$userEtag,'backgroundColor'=>$userBackgroundColor,'color'=>$userForegroundColor,'time_zone'=>$userTimezone,'name'=>$userID);
			foreach($eventList as $event){				
				/*$allDay = false;$allDay = true;*/
				if($event->start->dateTime!=null && $event->end->dateTime!=null){
					$eventsAllUsersList[] = array('user_id'=>$userEtag,'backgroundColor'=>$userBackgroundColor,'color'=>$userForegroundColor,'start'=>$event->start->dateTime,'end'=>$event->end->dateTime,'event_id'=>$event->id,'id'=>$event->etag,'title'=>$_name[0]);
				}
				
			}
		}
		/*$fileName = $_SERVER['DOCUMENT_ROOT'].'/backyardgithub/public/upload/events.json';*/		$fileName = $_SERVER['DOCUMENT_ROOT'].'/public/upload/events.json';
		$f = fopen($fileName,"w+");
		$data= json_encode($eventsAllUsersList);
		fwrite($f,$data);
		fclose($f);
		$fileName = $_SERVER['DOCUMENT_ROOT'].'/public/upload/user_event.json';
		/*$fileName = $_SERVER['DOCUMENT_ROOT'].'/backyardgithub/public/upload/user_event.json';*/
		$f = fopen($fileName,"w+");
		$data= json_encode($eventUserAndColor);
		fwrite($f,$data);
		fclose($f);
		die;
	}
	
	function company_calendar(){
		$this->layout->title_for_layout = 'Company Calendar';
		$this->layout->layout='calendar';
		$data=array();
		$this->layout->render('user/company_calendar',$data);
	}
	
	function email($gmailMessageID = null){
		$findThreadData = array();
		if($gmailMessageID!=null){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$service->setAccessToken($_SESSION['another_access_token']);			
			$emails = $_SESSION['STARRED'];
			$findIDFlag = 0;
			$message = "";
			/*$gmailMessageID = $this->input->post('thread');	*/
			if(count($emails)>0){
				foreach($emails as $email){
					if($email['message_id'] == $gmailMessageID){
						$findIDFlag = 1;
						$message = $email['content'];
					}
				}
			}
			if($findIDFlag==0){
				$emails = $_SESSION['INBOX'];
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $gmailMessageID){
							$findIDFlag = 1;
							$message = $email['content'];
						}
					}
				}
			}			
			if($findIDFlag==0){
				$emails = $_SESSION['TRASH'];
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $gmailMessageID){
							$findIDFlag = 1;
							$message = $email['content'];
						}
					}
				}
			}
			if($findIDFlag==0){
				$emails = $_SESSION['LEAD'];
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $gmailMessageID){
							$findIDFlag = 1;
							$message = $email['content'];
						}
					}
				}
			}
			if($findIDFlag==0){
				$emails = $_SESSION['SENT'];
				if(count($emails)>0){
					foreach($emails as $email){
						if($email['message_id'] == $gmailMessageID){
							$findIDFlag = 1;
							$message = $email['content'];
						}
					}
				}
			}
			$filesAttachment = "";			
					
			$attachmentArray = array();
			$attachments = array();	
			$content = array();
			if(is_object($message)>0){
					$messageBody = '';
					$attachmentsConnect = array();
					$parts = $message->getPayload()->getParts();
					$header = $message->getPayload()->getHeaders();
					if(count($parts)>0){
						if($parts[0]->mimeType=='text/plain' || $parts[0]->mimeType=='text/html'){						
							if(isset($parts[1]) && $parts[1]->mimeType=='text/html'){
								$rawBody = $parts[1]->getBody();
							}  else {
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
					}
					
					if(isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related" || $parts[0]->mimeType=="multipart/mixed")){
						for($i=1;$i<count($parts);$i++){ 
							$fileName = $parts[$i]->filename;
							$realAttachID = "";
							$partHeaders= $parts[$i]->getHeaders();
							$attachmentID = $parts[$i]->getBody()->getAttachmentId();
							if(!in_array($attachmentID,$attachmentArray)){
								$attachmentArray[] = $attachmentID ;
								foreach($partHeaders as $h){
									if($h->name=='X-Attachment-Id'){
										$realAttachID= $h->value;
									}
								}
								$mimeType = $parts[$i]->mimeType;
								
								$fileSize = $parts[$i]->getBody()->getSize();
								$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
							}
						}
					} else if(isset($parts[1]) && $parts[1]->mimeType=="text/calendar"){
						$fileName = $parts[1]->filename;
						$realAttachID = "";
						$partHeaders= $parts[1]->getHeaders();
						$attachmentID = $parts[1]->getBody()->getAttachmentId();
						$mimeType = $parts[1]->mimeType;								
						$fileSize = $parts[1]->getBody()->getSize();
						$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
					}
					$findThreadData[] = array("message_id"=>$message->id,"labelIds"=>$message->labelIds,"header"=>$header,"body"=>$messageBody,"attachments"=>$attachments,"content"=>$message,"parts"=>$parts);				
			}			
		}
		
		$data['thread_detail'] = $findThreadData;
		$data['type'] = 1;
		$this->layout->title_for_layout = 'Backyard Email Detail';
		$this->layout->layout='email';
		// $this->layout->layout='email';
		$this->layout->render('user/email',$data);
	}
	
	public function predefined_templates($t=1,$lead_id=0){
		$this->layout->title_for_layout = 'Predefined Messages';
		$this->layout->layout='opportunity';
		$data['t'] = $t;
		$data['lead_id'] = $lead_id;
		$this->layout->render('user/predefined_templates',$data);
	}
	
	public function moveAllToLeadBankTemplate(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$lead_id = $this->input->post('lead_id');
			if($lead_id>0){
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
			}
		}
		echo $data;
		die;
	}
	
	function own_server_email($thread=null){
		$findThreadData = array();
		if($thread!=null){
			$findThreadData =  $this->lead_model->findBoxNewById($thread);			
		}
		$data['thread_detail'] = $findThreadData;
		$data['ID'] = $thread;
		$data['type'] = 2;
		$data['person_email_detail'] = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($thread);
		if(count($data['person_email_detail'])==0){
			$data['person_email_detail'] = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($thread);
		}
		$this->layout->title_for_layout = 'Backyard Email Detail';
		$this->layout->layout='email';
		$this->layout->render('user/email',$data);
	}
	
	function profile(){
		
		$data['user_id'] = $this->session->userdata['id'];
		$data['from'] = '';
		$data['to'] = '';
		$data['lead'] = '';
		$data['post'] = 0;
		$data['activity_type'] = 0;
		if(isset($_POST) && count($_POST)>0){			
			if((int)$_POST['profile']['selected_user']>0){
				if($this->session->userdata['type']==9){
					$data['user_id'] = $_POST['profile']['selected_user'];
				}
			}
			if(!empty($_POST['profile']['from'])){
				$data['from'] = $_POST['profile']['from'];
			}
			if(!empty($_POST['profile']['to'])){
				$data['to'] = $_POST['profile']['to'];
			}
			if(!empty($_POST['profile']['lead'])){
				$data['lead'] = $_POST['profile']['lead'];
			}
			$activity_type= 0;
			if(!empty($_POST['profile']['activity_type'])){
				$activity_type = $_POST['profile']['activity_type'];
			}
			$data['activity_type'] = $activity_type;
			$data['post'] = 1;
		}
		$data['userData'] = $this->user_model->getUserData($this->session->userdata['id']);
		$this->layout->title_for_layout = 'Backyard User Profile';
		$this->layout->layout='opportunity';
		$this->layout->render('user/profile',$data);
	}
	
	
	
	
	function linkWithMessage(){
			$box['thread'] = $this->input->get('thread');
			$box['old_thread'] = $this->input->get('old_thread');
			if(!isset($_SESSION)){
				session_start();
			}
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['another_access_token']);
			$getMessageData = $service->findThreadData($box['thread']);			
			$listOfLabels = $service->listLabels();			
			$filesAttachment = "";
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
			/**/
			$gmailMessageID = $box['thread'];
			$attachmentArray = array();
			if(count($getMessageData)>0){
				foreach($getMessageData as $message){
					foreach($message['attachments'] as $attachments){
						$attachmentID = $attachments['attachmentId'];
						if(!in_array($attachmentID,$attachmentArray)){
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
			}
			if(!empty($filesAttachment)){
				$filesAttachment = substr($filesAttachment,0,-1);
			}			
			$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],"thread_id"=>$box['thread'],"content"=>json_encode($getMessageData),"file_attach"=>$filesAttachment));
			if($sendData>0){
				if(!empty($labelID)){
					$service->modifyThreadRemove($gmailMessageID,"me",'INBOX');
					$service->modifyThreadRemove($gmailMessageID,"me",'UNREAD');
					$service->modifyThread($gmailMessageID,"me",$labelID);
				}
				$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				echo json_encode(array('send'=>1));
			} else {
				echo json_encode(array('send'=>0));
			}
		
		die;
	}
	
	
	function notificationEmail(){
		if(isset($_POST) && count($_POST)>0){
			$type = $this->input->post('type');
			$this->user_model->errorNotification(array("user_id"=>$this->session->userdata('id'),"create_date"=>date('Y-m-d h:i:s'),'type'=>$type));
		}
		die;
	}
	
	
	
	function update(){
		if(isset($_POST) && count($_POST)>0){
			$profilePIC = "";
			$user = $this->input->post('user');
			if(isset($_FILES) && !empty($_FILES['user']['name']['profile_pic'])){
				$target_file = $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$_FILES['user']['name']['profile_pic'];
				if(move_uploaded_file($_FILES['user']["tmp_name"]['profile_pic'], $target_file)){
					$profilePIC= $this->config->base_url().'public/upload/'.$_FILES['user']['name']['profile_pic'];
				}
			} else {
				$profilePIC = $user['old_pic'];
			}
			if(!empty($user['password'])){
				$updateData = $this->simpleloginsecure->update($this->session->userdata['id'],$user['password'],$profilePIC,$user['phone_number'],$user['direct_number'],$user['mobile_number'],$user['email_for_signature'],true);
			} else {
				$updateData = $this->simpleloginsecure->updateProfilePic($this->session->userdata['id'],$profilePIC,$user['phone_number'],$user['direct_number'],$user['mobile_number'],$user['email_for_signature'],true);
			}
						
			if($updateData){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
			}
			redirect('users/profile');
		}
	}
	
	function getMoreRecordsInEmail(){
		$type = $this->input->post('t');
		$records = $this->input->post('r');
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();
		}	
		if(!empty($type)){
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
			$getOldEmails = $_SESSION[$type];
			$token = $getOldEmails[count($getOldEmails)-1]['pageToken'];
			if(!empty($token)){
				$nextPageEmails = $service->newMessageList($records,$type,$token);
				$allEmails = array_merge($getOldEmails, $nextPageEmails);
				$_SESSION[$type] = $allEmails;
			}
		}
		die;
	}
	
	
	public function getEmails(){
		$type = $this->input->post('type');
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();
		}		
		if(!empty($type)){
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
			if(strtolower($type)!='lead'){
				$emails = $service->newMessageList(30,$type);
				$_SESSION[$type] = $emails;
			} else {
				/*
				$service->setAccessToken($_SESSION['another_access_token']);
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
					$service->setAccessToken($_SESSION['another_access_token']);
					$emails = $service->newMessageList(30,$labelID);
					$_SESSION[$type] = $emails ;
				}*/
				/*Get Leads emails from database*/				
			}
		}
		die;		
	}
	
	public function getCurrentOldEmails(){		
		$type = $this->input->post('stype');
		if(!empty($type)){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$emails = $_SESSION[$type];
			/*$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
			$boxList = $this->lead_model->findAllBoxThreadList();
			$pass_lead = $this->lead_model->getPassLead();	*/
			$boxList = array();
			$pass_lead = array();
			echo json_encode(array("emails"=>$emails,"boxList"=>$boxList,"pass_lead"=>$pass_lead));
		}
	}
	
	function getCalendarColors(){
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();
		}
		$service->setAccessToken($_SESSION['another_access_token']);
		$colors = $service->getColor();
		$availableColor = array();
		foreach ($colors->getCalendar() as $key => $color) {
		  $availableColor[] = $color->getBackground();
		  /*print "colorId : {$key}<br/>";
		  print "  Background: {$color->getBackground()}\n";
		  print "  Foreground: {$color->getForeground()}\n";*/
		}
		echo json_encode($availableColor);
		die;
	}
	
	function insert_event(){
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');			
			if(!isset($_SESSION)){
				session_start();
			}
			/*
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['another_access_token']);*/
			$service = new CalendarServiceHelper();
			$event = $this->input->post("event");
			$email = $this->input->post("email");
			if(!empty($email)){
				$allEmails = explode(',',$email);
				foreach($allEmails as $email){
					if(trim($email)!=""){
						$event['attendees'][] = array("email"=>trim($email));
					}					
				}
			}	
			if(isset($event['color']) && !empty($event['color'])){
				$event['colorId'] = $event['color'];
			}
			unset($event['color']);
			$sTime = $event['start_time'];
			$sT = explode(" ",$sTime);
			if(count($sT)==2){
				if(trim($sT[1])=="PM"){
					$explodeM = explode(":",$sT[0]);
					if(count($explodeM)==2){
						if((int)$explodeM[0]<12){
							$mT = (int)$explodeM[0] + 12;
						} else {
							$mT = (int)$explodeM[0];
						}
						
					} else {
						$mT = (int)$explodeM[0];
					}
					$sD = date('Y-m-d',strtotime($event['start_date']));
					$time = strtotime($sD.$mT.':'.$explodeM[1]);
					$m = date('G:i',$time);
					$sD = $sD.'T'.$m.':00-08:00';
					$event['start']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				} else {
					$explodeM = explode(":",$sT[0]);
					$sD = date('Y-m-d',strtotime($event['start_date']));
					$first = $explodeM[0];
					if($first==12){
						$first = 00;
					}
					$time = strtotime($sD.$first.':'.$explodeM[1]);
					$m = date('G:i',$time);
					$sD = $sD.'T'.$m.':00-08:00';
					$event['start']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				}
			}
			$eTime = $event['end_time'];
			$eT = explode(" ",$eTime);
			if(count($eT)==2){
				if(trim($eT[1])=="PM"){
					$explodeM = explode(":",$eT[0]);
					if(count($explodeM)==2){
						if((int)$explodeM[0]<12){
							$mT = (int)$explodeM[0] + 12;
						} else {
							$mT = (int)$explodeM[0];
						}						
					} else {
						$mT = (int)$explodeM[0];
					}
					$sD = date('Y-m-d',strtotime($event['end_date']));
					$time = strtotime($sD.$mT.':'.$explodeM[1]);
					$m = date('G:i',$time);
					$sD = $sD.'T'.$m.':00-08:00';
					$event['end']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				} else {
					$explodeM = explode(":",$eT[0]);
					$sD = date('Y-m-d',strtotime($event['end_date']));
					$first = $explodeM[0];
					if($first==12){
						$first = 00;
					}
					$time = strtotime($sD.$first.':'.$explodeM[1]);
					$m = date('G:i',$time);
					$sD = $sD.'T'.$m.':00-08:00';
					$event['end']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				}
			}
			/*$event['recurrence'] = array('RRULE:FREQ=DAILY;COUNT=2');*/
			$event['reminders'] = array(
									'useDefault' => FALSE,
									'overrides' => array(
									  array('method' => 'email', 'minutes' => 24 * 60),
									  array('method' => 'sms', 'minutes' => 10),
									),
								  );
			$startDateEvent = $event['start_date']." ".$event['start_time'];
			unset($event['end_time']);
			unset($event['end_date']);
			unset($event['start_time']);
			unset($event['start_date']);
			$eventRes = $service->insert_event($event);
			if(is_object($eventRes)){
				if(isset($eventRes->htmlLink)){
					if($this->input->post("lead_id")>0):
					$this->lead_model->saveLeadEvent(array("lead_id"=>$this->input->post("lead_id"),"subject"=>$event['summary'],"event_link"=>$eventRes->htmlLink,"date"=>$startDateEvent));
					$startDate = date('Y-m-d H:i:s');
					$user_history = array('lead_id'=>$this->input->post("lead_id"),'user_id'=>$this->session->userdata['id'],'message'=>"Create an event",'opportunity_id'=>0,'create_date'=>$startDate);
					$this->user_model->addUserHistory($user_history);
					
					if(isset($_POST['acitivity_event_type']) && (int)$_POST['acitivity_event_type']>0){
						$email = $this->input->post("email");
						if(!empty($email)){	
							/*Only one user in activities entry*/
							$emailTo = explode(',',$this->input->post("email"));
							if(count($emailTo)>1){
								$emailCurrent = $emailTo[0];
								$emailTo = array();
								$emailTo[] = $emailCurrent;
							}
							$leadID = $this->input->post("lead_id");
							$activityType = $_POST['acitivity_event_type'];
							$subject = $event['summary'];
							$description = $event['description'];
							$event= array();
							for($t=0;$t<count($emailTo);$t++){
								$toEmail = trim($emailTo[$t]);
								if(!empty($toEmail)){
									$getContactDetail = $this->lead_model->getContactByEmail(trim($emailTo[$t]));
									if(count($getContactDetail)>0 && $getContactDetail->email!=""){
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
										$event['type'] = 11;
										$dateCreate = new DateTime($startDateEvent, new DateTimeZone('America/Los_Angeles'));
 										$eventDate = $dateCreate->format('Y-m-d H:i:s');
										/*$event['note'] = "<a href='".$eventRes->htmlLink."' target='_BLANK'><i class='glyph-icon icon-calendar'></i>&nbsp;&nbsp;".$event['summary']." and ".$startDate."</a>";*/
										$note = "<a href='".$eventRes->htmlLink."' target='_BLANK'><i class='glyph-icon icon-calendar'></i>&nbsp;&nbsp;".$subject." and ".$eventDate."</a><br/><a href='".$eventRes->hangoutLink."' target='_BLANK'><i class='glyph-icon icon-video-camera'>&nbsp;Join meeting</i></a>";
										if(!empty($description)){
											$note .="<br/>".$description;
										}
										$event['note'] = $note;
										$event['user_id'] = $this->session->userdata['id'];
										$event['email_id'] = 0;
										$event['subject'] = $subject;
										$event['lead_id'] = $leadID;
										$event['activity_date'] = $startDate;
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
					endif;
					echo json_encode(array('link'=>$eventRes->htmlLink));
				}					
			}
		}
		die;
	}
	
	
	public function getOldEmails(){
		$type = $this->input->post('type');
		if(!empty($type)){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$emails = $_SESSION[$type];
			/*if(count($emails)==0 && $type=="LEAD"){
				$service->setAccessToken($_SESSION['another_access_token']);
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
					$service->setAccessToken($_SESSION['another_access_token']);
					$emails = $service->messageList(30,$labelID);
					$_SESSION[$type] = $emails ;
				}
			}*/
			
			$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
			/*$boxList = $this->lead_model->findAllBoxList();*/
			$boxList = $this->lead_model->findAllBoxThreadList();
			/*$pass_lead = $this->lead_model->getPassLead();*/
			if($type!="LEAD"):
			foreach($emails as $message){

				$from ="";													
				$subject="";													
				$date = "";	
				$_dateD = "";
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
				<div class="message-item media draggable" data-date='<?php echo $date?>' data-message-thread-id="<?php echo $message['thread_id']?>" data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>" data-task="0">														
					<div class="message-item-right">
						<div class="media">																
							<div class="media-body" onclick="findThread('<?php echo $message['message_id']?>',jQuery(this));">
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
									<span class="message-item-date"><?php echo date('M d, Y',strtotime($date));?></span>
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
										
										/*if(count($message['attachments'])>0):*/
										if($countAttachments>0):
									?>
									<strong><i class="glyph-icon icon-paperclip"></i> <?php echo $countAttachments;?></strong>
									<?php endif;?>
									<!--<a href='javascript://' onclick="enableTask(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon icon-plus"></i></a>
									--><a href='javascript://' onclick="moveEmailToTrash(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon"><img src="http://backyard.synpat.com/public/images/discard.png" style="opacity:0.55;width:10px"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			
				
			}
			else:
				$emails = $this->lead_model->getEmailList(100);
				foreach($emails as $message){
					$from = "";
					$subject = "";
					$data = "";
					$_dateD = "";
					$messageIDDD = "";
					$threadID = "";
					$messageID = "";
					$id=$message->id;					
					$content = json_decode($message->content);					
					switch($message->account_type){
						case 1:
									$headers = $content[0]->header;
									foreach($headers as $header){					
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
									$threadID = $message->thread_id;
									$messageID = $message->message_id;
									$parts = $content[0]->parts;
									$countAttachments = 0;
									if(isset($parts[0]) && ($parts[0]->mimeType=="multipart/alternative" || $parts[0]->mimeType=="multipart/related")){
										for($i=1;$i<count($parts);$i++){	$attachmentID = $parts[$i]->getBody()->getAttachmentId();
											if(!empty($attachmentID)){
												$countAttachments++;
											}
										}
									}
								break;
								case 2:
									$date = $content->header->Date;
									$subject = $content->header->subject;
									$from = $content->header->from[0]->personal;
									$messageIDDD = $content->header->message_id;
									$countAttachments = 0;
									$parts = $message->file_attach;
									$countAttachments = explode(',',$parts);
									$countAttachments = count($countAttachments)-1;
								break;
					}
				?>
					<div class="message-item media draggable" data-date='<?php echo $date?>' data-message-thread-id="<?php echo $threadID?>" data-id="<?php echo $messageID?>" data-message-id="<?php echo $messageIDDD;?>" data-task="0" data-acompany="<?php echo $message->aCompanyID?>" data-scompany="<?php echo $message->sCompanyID?>" data-atype="<?php echo $message->aType?>" data-stype="<?php echo $message->sType?>" data-lead="<?php echo $message->lead_id?>" data-lead-name="<?php echo $message->lead_name?>" data-send="<?php echo $message->account_type?>" data-type="<?php echo $message->from_activity?>">														
					<div class="message-item-right">
						<div class="media">																
							<div class="media-body" onclick="findOwnThread(<?php echo $id;?>,jQuery(this),2,1);">
								<h5 class="c-dark">								
									<a class="c-dark" style='font-weight:normal' href="javascript:void(0)"><?php echo $from;?></a>
								</h5>
								<h4 class="c-dark"><?php echo $subject;?></h4>
								<div>
									<span class="message-item-date"><?php echo date('M d, Y',strtotime($date));?></span>
									&nbsp;									
									<?php 
										if($countAttachments>0):
									?>
									<strong><i class="glyph-icon icon-paperclip"></i> <?php echo$countAttachments;?></strong>
									<?php endif;?>
									<!--<a href='javascript://' onclick="enableTask(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon icon-plus"></i></a>
									--><a href='javascript://' onclick="moveEmailToTrash(jQuery(this))" style='float:right;width:15px;'><i class="glyph-icon"><img src="http://backyard.synpat.com/public/images/discard.png" style="opacity:0.55;width:10px"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
			endif;
		}
		die;		
	}
    
	public function findThread(){
		$findThreadData = array();
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			if(!isset($_SESSION)){
				session_start();
			}
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['another_access_token']);
			$findThreadData =  $service->findThreadData($this->input->post('thread'));			
		}
		echo json_encode($findThreadData);
		die;
	}
	
    public function ajax_call(){
        /*echo 'test';*/
    }
	
	public function setGlobal(){
		if(!isset($_SESSION)){
			session_start();
		}
		unset($_SESSION['clicked_url']);
		$_SESSION['clickedd_url'] = "allGlobal";
	}
	public function is_session_started(){
		if ( php_sapi_name() !== 'cli' ) {
			if ( version_compare(phpversion(), '5.4.0', '>=') ) {
				return session_status() === PHP_SESSION_NONE ? TRUE : FALSE;
			} else {
				return session_id() === '' ? FALSE : TRUE;
			}
		}
		return FALSE;
	}
	
	public function findSystemLeads(){
		$incomplete = array();
		if((int)$this->session->userdata['type']!=9){
			$incomplete = $this->lead_model->findIncompleteANDCompleteListAccUser($this->session->userdata['id']);
		} else{
			$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
		}
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
			$createDate = date('m d,y',strtotime($message->create_date));
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
?> <tr class="border-blue-alt droppable old_lead <?php echo $main;?>" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" onclick="threadDetail(jQuery(this))" <?php if($stage=="Oppt."):?>ondblclick="opportunityRedirect('<?php echo $message->id?>');"<?php endif;?>> <td style="padding:3px 2px;border-right:0;border-left:none;width:200px" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" class=""><label><a style='text-align:left' title="<?php echo $message->lead_name;?>" class='btn' href="javascript:void(0)"><?php echo substr($message->lead_name,0,30);?></a></label></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:45px"><?php echo $type;?></td><td style="padding:3px 2px;border-right:0;border-left:none;width:71px" class='one-line-cell'><?php echo $createDate;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px" class='<?php echo $sellerClass;?> one-line-cell'><?php echo $sellerInfo;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $sellerLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $synpatLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $ppa;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $fundingTrnsfr;?> </div> </td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->broker_person_contact;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"> <?php 
						if(empty($message->seller_contact)){
							echo $message->plantiffs_name;
						} else {
							echo $message->seller_contact;
						}
						?> </td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->person_name_1;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->person_name_2;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->relates_to;?></td> </tr> <?php
			} 
		}
	}
	die;
														
	}
	
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */