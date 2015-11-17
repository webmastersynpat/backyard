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
		$this->load->model('general_model');
		$this->layout->auto_render=false;	
		$this->layout->layout='default';
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));		
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
			if(isset($post['search']) && !empty($post['search'])){
				$q = $post['search']." ";
			}
			if(isset($post['search_from']) && !empty($post['search_from'])){
				$q .= "from:(".$post['search_from'].") ";
			}
			if(isset($post['search_to']) && !empty($post['search_to'])){
				$q .= "to:(".$post['search_to'].") ";
			}
			if(isset($post['search_subject']) && !empty($post['search_subject'])){
				$q .= "subject:".$post['search_subject']." ";
			}
			if(isset($post['has']) && !empty($post['has'])){
				$q .= $post['has']." ";
			}
			if(isset($post['doesnt_have']) && !empty($post['doesnt_have'])){
				$q .= "-{".$post['doesnt_have']."} ";
			}
			$q = trim($q);
			echo $q;
			if(!empty($q)){
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
		$this->layout->title_for_layout = 'Predefined Messages';
		$this->layout->layout='opportunity';
		$this->layout->render('user/lead_templates',$data);
	}
	
	public function save_new_template(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['temp']) && !empty($_POST['temp'])){
				$data = $this->general_model->insertTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'type'=>'2','template_name'=>$_POST['name']));
				$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Create predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
			}
		} 
		echo $data;
		die;
	}
	
	public function update_template(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['temp']) && !empty($_POST['temp'])){
				$data = $this->general_model->updateTemplate(array('template_html'=>$_POST['temp'],'subject'=>$_POST['subject'],'template_name'=>$_POST['name']),$_POST['id']);
				if($data==0){
					$data = $_POST['id'];
				}
				$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>"Update predefined template",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
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
	
	
	public function search_email($gmailMessageID = null){
		$findThreadData = array();
		if($gmailMessageID!=null){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$service->setAccessToken($_SESSION['another_access_token']);			
			$findThreadData = $service->findThreadData($gmailMessageID);
		}
		$data['thread_detail'] = $findThreadData;
		$data['type'] = 1;
		$this->layout->title_for_layout = 'Backyard Email Detail';
		$this->layout->layout='email';
		// $this->layout->layout='email';
		$this->layout->render('user/search_email',$data);
	}
	
	
	public function email($gmailMessageID = null){
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
						$messageBody = nl2br($messageBody);
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
	
	public function predefined_templates($t=1){
		$this->layout->title_for_layout = 'Predefined Messages';
		$this->layout->layout='opportunity';
		$data['t'] = $t;
		$this->layout->render('user/predefined_templates',$data);
	}
	
	public function own_server_email($thread=null){
		$findThreadData = array();
		if($thread!=null){
			$findThreadData =  $this->lead_model->findBoxNewById($thread);			
		}
		$data['thread_detail'] = $findThreadData;
		$data['type'] = 2;
		$data['person_email_detail'] = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($thread);
		if(count($data['person_email_detail'])==0){
			$data['person_email_detail'] = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($thread);
		}
		$this->layout->title_for_layout = 'Backyard Email Detail';
		$this->layout->layout='email';
		$this->layout->render('user/email',$data);
	}
	
	public function profile(){
		$data['user_id'] = $this->session->userdata['id'];
		$data['from'] = '';
		$data['to'] = '';
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
	
	
	
	public function update(){
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
				$updateData = $this->simpleloginsecure->update($this->session->userdata['id'],$user['password'],$profilePIC,$user['phone_number'],true);
			} else {
				$updateData = $this->simpleloginsecure->updateProfilePic($this->session->userdata['id'],$profilePIC,$user['phone_number'],true);
			}
			if($updateData){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
			}
			redirect('users/profile');
		}
	}
	

	
	
	public function getEmails(){
		$type = $this->input->post('type');
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();
		}		
		if(!empty($type)){
			$service->setAccessToken($_SESSION['another_access_token']);
			if(strtolower($type)!='lead'){
				$emails = $service->newMessageList(30,$type);
				$_SESSION[$type] = $emails;
			} else {
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
				}
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
			$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
			$boxList = $this->lead_model->findAllBoxThreadList();
			$pass_lead = $this->lead_model->getPassLead();	
			echo json_encode(array("emails"=>$emails,"boxList"=>$boxList,"pass_lead"=>$pass_lead));
		}
	}
	
	function insert_event(){
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			if(!isset($_SESSION)){
				session_start();
			}
			$service->setAccessToken($_SESSION['another_access_token']);
			$event = $this->input->post("event");
			$email = $this->input->post("email");
			if(!empty($email)){
				$allEmails = explode(',',$email);
				foreach($allEmails as $email){
					if(trim($email)!=""){
						$event['attendees'][] = array("email"=>$email);
					}					
				}
			}	
			$sTime = $event['start_time'];
			$sT = explode(" ",$sTime);
			if(count($sT)==2){
				if(trim($sT[1])=="PM"){
					$explodeM = explode(":",$sT[0]);
					if(count($explodeM)==2){
						$mT = (int)$explodeM[0] + 12;
					} else {
						$mT = (int)$explodeM[0];
					}
					$sD = date('Y-m-d',strtotime($event['start_date']));
					$sD = $sD.'T'.$mT.':'.$explodeM[1].':00-07:00';
					$event['start']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				}
			}
			$eTime = $event['end_time'];
			$eT = explode(" ",$eTime);
			if(count($eT)==2){
				if(trim($eT[1])=="PM"){
					$explodeM = explode(":",$eT[0]);
					if(count($explodeM)==2){
						$mT = (int)$explodeM[0] + 12;
					} else {
						$mT = (int)$explodeM[0];
					}
					$sD = date('Y-m-d',strtotime($event['end_date']));
					$sD = $sD.'T'.$mT.':'.$explodeM[1].':00-07:00';
					$event['end']=array('dateTime'=>$sD,'timeZone'=> 'America/Los_Angeles');
				}
			}
			$event['recurrence'] = array('RRULE:FREQ=DAILY;COUNT=2');
			$event['reminders'] = array(
									'useDefault' => FALSE,
									'overrides' => array(
									  array('method' => 'email', 'minutes' => 24 * 60),
									  array('method' => 'sms', 'minutes' => 10),
									),
								  );
			$startDate = $event['start_date']." ".$event['start_time'];
			unset($event['end_time']);
			unset($event['end_date']);
			unset($event['start_time']);
			unset($event['start_date']);
			$eventRes = $service->insert_event($event);
			if(is_object($eventRes)){
				if(isset($eventRes->htmlLink)){
					$this->lead_model->saveLeadEvent(array("lead_id"=>$this->input->post("lead_id"),"subject"=>$event['summary'],"event_link"=>$eventRes->htmlLink,"date"=>$startDate));
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
			if(count($emails)==0 && $type=="LEAD"){
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
			}
			
			$incomplete = $this->lead_model->findIncompleteANDCompleteList('Market');
			/*$boxList = $this->lead_model->findAllBoxList();*/
			$boxList = $this->lead_model->findAllBoxThreadList();
			/*$pass_lead = $this->lead_model->getPassLead();*/
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
				<div class="message-item media draggable" data-date='<?php echo $date?>' data-message-thread-id="<?php echo $message['thread_id']?>" data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>">														
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
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			
				
			}
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
?> <tr class="border-blue-alt droppable old_lead <?php echo $main;?>" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" onclick="threadDetail(jQuery(this))" <?php if($stage=="Oppt."):?>ondblclick="opportunityRedirect('<?php echo $message->id?>');"<?php endif;?>> <td style="padding:3px 2px;border-right:0;border-left:none;width:200px" data-id="<?php echo $message->id?>" data-type="<?php echo $message->type?>" class=""><label><a style='text-align:left' title="<?php echo $message->lead_name;?>" class='btn' href="javascript:void(0)"><?php echo substr($message->lead_name,0,30);?></a></label></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:45px"><?php echo $type;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px" class='<?php echo $sellerClass;?> one-line-cell'><?php echo $sellerInfo;?></td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $sellerLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $synpatLike;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $ppa;?> </div> </td> <td style="padding:3px 2px;border-right:0;border-left:none;width:71px"> <div style="white-space:nowrap"> <?php echo $fundingTrnsfr;?> </div> </td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"><?php echo $message->broker_person_contact;?></td> <td class="one-line-cell" style="padding:3px 2px;border-right:0;border-left:none"> <?php 
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