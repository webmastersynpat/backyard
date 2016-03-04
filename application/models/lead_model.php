<?php
class lead_model extends CI_Model{
	
	public $table_lead = 'litigations';
	public $table_comment = 'other_comments';
	public $table_box = 'box_list';
    public $table_history = 'history';
    public $table = 'users'; 
   	public $table_assign_lead = 'assign_leads';	
	public $table_assign_lead_type = 'assign_lead_type';
	public $table_gmail = "messages_gmail";
	public $table_requests = 'approval_requests';
	public $table_chart = "lead_chart";
	public $table_acquisition = "acquisition";
	public $table_stage = 'lead_stages';
	public $table_level = 'lead_levels';
	public $table_report = 'lead_reports';
	public $table_buttons = 'buttons';
	public $table_lead_buttons = 'lead_buttons';
	public $table_lead_event = 'lead_event';
	public $table_docket_buttons = 'docket_buttons';
	public $table_contacts_access = 'contacts_access';
	public $table_task_conversation = 'task_conversation';
	public $table_task_flag = 'task_message_flag';
	public $table_contacts = 'contacts';
	public $table_company = 'company';
	public $table_invitees = 'invitees';
	public $table_sales_activity = 'sales_activity_log_detail';
	public $table_presale_broker = 'presale_broker';
	public $table_presales_activity = 'presale_activity_log_detail';
	public $table_acquisition_company = 'acquisition_company';
	public $table_acquisition_activity = 'acquisition_activity_log_detail';
	public $table_lead_template = 'lead_template_files';
	public $table_precontacts = 'pre_contacts';
	public $table_precompanies = 'pre_companies';
	public $table_free_precontacts = 'free_precontacts';
	public $table_litigation_scrap = 'litigation_scrap';
	public $table_campaign = 'campaigns';
	public $table_campaign_list = 'campaign_sender_lists';
	public $table_sector = "sectors";
	public $table_company_sector = 'company_sector';
	public $table_campaign_process = 'campaign_process';
	public $table_sales_broker_lead_company = 'sales_broker_lead_company';
	public function __construct() {
		parent::__construct();		
		
	}
	
	public function insertPreSalesBrokerLeadCompany($data){
		$this->db->insert($this->table_presale_broker, $data);
		return $this->db->insert_id();
	}
	
	public function insertSalesBrokerLeadCompany($data){
		$this->db->insert($this->table_sales_broker_lead_company, $data);
		return $this->db->insert_id();
	}
	
	public function deleteSalesBrokerLeadCompany($data){
		$this->db->where('lead_id',$data['lead_id']);
		$this->db->where('sales_company_id',$data['sales_company_id']);
		$this->db->delete($this->table_sales_broker_lead_company);
		return $this->db->affected_rows();
	}
	
	public function insertCampaignProcess($data){
		$this->db->insert($this->table_campaign_process, $data);
		return $this->db->insert_id();
	}
	
	function deleteCampaignProcess($id){
		$query = $this->db->select('count(cl.id) as countID')->from($this->table_campaign_process.' as cp')->join($this->table_campaign_list.' as cl','cl.campaign_id = cp.campaign_id')->where('cp.id',$id)->where('send','0')->get();
		if($query->num_rows()>0){
			$count = $query->first_row();
			if($count->countID==0){
				$this->db->where('id',$id);
				$this->db->delete($this->table_campaign_process);
				return $this->db->affected_rows();
			} else {
				return 1;
			}
		} else {
			return 1;
		}
	}
	
	
	public function insertFreePreContacts($data){
		$this->db->insert($this->table_free_precontacts, $data);
		return $this->db->insert_id();
	}
	
	
	public function findEmailDate($id){
		$result = $this->db->select('date_received')->from($this->table_box)->where('id',$id)->get();
		if($result->num_rows()>0){
			$data = $result->first_row();
			return $data->date_received;
		} else {
			return date('Y-m-d H:i:s');
		}
	}
	
	public function insertLitigationScrap($data){
		$this->db->insert($this->table_litigation_scrap, $data);
		return $this->db->insert_id();
	}
	
	function deleteLitigationScrap($id){
		$this->db->where('id',$id);
		$this->db->delete($this->table_litigation_scrap);
		return $this->db->affected_rows();
	}
	
	function deleteFreePreContact($id){
		$this->db->where('id',$id);
		$this->db->delete($this->table_free_precontacts);
		return $this->db->affected_rows();
	}
	
	public function updateSalesCompanyStage($leadID,$contactID,$data){
		$this->db->where("lead_id",$leadID);
		$this->db->where("contact_id",$contactID);
		$this->db->update($this->table_invitees,$data);
		return $this->db->affected_rows();
	}
	
	public function insertCampaign($data){
		$this->db->insert($this->table_campaign, $data);
		return $this->db->insert_id();
	}
	
	public function insertCampaignList($data){
		$this->db->insert($this->table_campaign_list, $data);
		return $this->db->insert_id();
	}
	
	public function updateCampaignList($campainID,$email,$data){
		$this->db->where("campaign_id",$campainID);
		$this->db->where("address",$email);
		$this->db->update($this->table_campaign_list,$data);
	}
	
	public function delete_address_from_campaign($campaignID,$email){
		$this->db->where("campaign_id",$campaignID);
		$this->db->where("address",$email);
		$this->db->delete($this->table_campaign_list);
		echo $this->db->last_query();		
		return $this->db->affected_rows();
	}
	
	public function checkCampaignProcess($campaignID){
		$data = $this->db->select('count(cp.id) as countID')->from($this->table_campaign_process.' as cp')->where('campaign_id',$campaignID)->get()->row->countID;
		return $data;
	}
	
	public function getCampaignListByEmailInProccess($email,$campaignID){
		$data = array();
		$query = $this->db->select('distinct(cp.campaign_id) as campaign_id, cp.id as campaignProcessID,cl.address,cp.subject,cp.type,cp.user_id,cp.main_activity,cp.campaign_date,cp.lead_id,cl.id as campaignListID')->from($this->table_campaign_process.' as cp')->join($this->table_campaign_list.' as cl','cl.campaign_id = cp.campaign_id')->where('(cl.send="1") AND (cl.proccessed = 0 OR cl.proccessed IS null)')->where('address',$email)->where('cl.campaign_id',$campaignID)->order_by("cp.id","DESC")->get();
		/*echo $this->db->last_query();*/
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row){ 
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getRecentCampaignProcess(){
		$data = array();
		$query = $this->db->select('distinct(cp.campaign_id) as campaign_id, cp.id as campaignProcessID,cl.address,cp.subject,cp.type,cp.user_id,cp.main_activity,cp.campaign_date,cp.lead_id,cl.id as campaignListID')->from($this->table_campaign_process.' as cp')->join($this->table_campaign_list.' as cl','cl.campaign_id = cp.campaign_id')->where('(cl.send="1") AND (cl.proccessed = 0 OR cl.proccessed IS null)')->order_by("cp.id","DESC")->get();
		/*echo $this->db->last_query();*/
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row){ 
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getCampaign($id=0,$campaignType=0){
		$data= array();
		if($id==0 && $campaignType>0){
			$query = $this->db->select("*")->from($this->table_campaign)->where('id IN (SELECT distinct(campaign_id) FROM '.$this->table_campaign_list.' WHERE send=0)')->where("campaign_type",$campaignType)->order_by("start_date","DESC")->get();
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row){ 
					$data[] = $row;
				}
			}
		} else if($id>0){
			$query = $this->db->select("*")->from($this->table_campaign)->where("id",$id)->get();
			if ($query->num_rows() > 0) {
				$campaign = $query->first_row();
				$list = array();
				$query = $this->db->select("*")->from($this->table_campaign_list)->where("campaign_id",$campaign->id)->get();
				if ($query->num_rows() > 0) {
					foreach($query->result() as $row){
						$list[] = $row;
					}
				}
				$data = array("campaign"=>$campaign,"list"=>$list);
			}
		}
		return $data;
	}
	
	public function saveTaskConversation($data){
		$this->db->insert($this->table_task_conversation, $data);
		return $this->db->insert_id();
	}
	
	public function saveLeadEvent($data){
		$this->db->insert($this->table_lead_event, $data);
		return $this->db->insert_id();
	}
	
	public function saveTaskConversationFlag($data){
		/*$this->deleteTaskConversation($data['task_id']);*/
		$this->db->insert($this->table_task_flag, $data);
		return $this->db->insert_id();
	}
	
	public function saveLeadTemplate($data){
		$this->db->insert($this->table_lead_template, $data);
		return $this->db->insert_id();
	}
	
	public function deleteTaskConversation($taskID){
		$this->db->where('task_id',$taskID);
		$this->db->delete($this->table_task_flag);
	}
	
	function deleteLeadTemplate($id){
		$this->db->where('id',$id);
		$this->db->delete($this->table_lead_template);
		return $this->db->affected_rows();
	}
	
	function getLeadTemplates($leadID,$type=0){
		$boxQuery = $this->db->select("*")->from($this->table_lead_template)->where('lead_id',$leadID)->where('type',$type)->get();
		$boxData =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxData[] = $box;
			}
		}
		return $boxData;
	}
	
	public function emailSearch($from,$to,$subject,$has,$doesntHave){
		$searchString = "";
		$stringInc = false;		
		if(!empty($from)){
			$explodeFrom = explode(',',$from);
			for($i=0;$i<count($explodeFrom);$i++){
				if(!empty($explodeFrom[$i])){
					$searchString .= "content LIKE '%".$explodeFrom[$i]."%' OR ";
				}				
			}
			$searchString = substr($searchString,0,-3);
			$stringInc = true;
		}
		if(!empty($to)){
			if($stringInc == true){
				$searchString .= "OR ";
			}
			$explodeTo = explode(',',$to);
			for($i=0;$i<count($explodeTo)-1;$i++){
				$searchString .= "content LIKE '%".$explodeTo[$i]."%' OR ";
			}
			$searchString = substr($searchString,0,-3);
			$stringInc = true;
		}
		if(!empty($subject)){
			if($stringInc == true){
				$searchString .= "AND ";
			}
			$searchString .= "content LIKE '%".$subject."%' ";
			$stringInc = true;
		}
		if(!empty($has)){
			if($stringInc == true){
				$searchString .= "AND ";
			}
			$searchString .= "content LIKE '%".$has."%' ";
			$stringInc = true;
		}
		if(!empty($doesntHave)){
			if($stringInc == true){
				$searchString .= "AND ";
			}
			$searchString .= "content NOT LIKE '%".$doesntHave."%' ";
			$stringInc = true;
		}
		$data = array();
		$searchString = trim($searchString);
		if(!empty($searchString)){
			/*if(!empty($from)){
				$searchString .=" AND b.user_id = (SELECT id FROM ".$this->table_contacts." as c WHERE c.email='".$from."')";
			}*/
			$data = array();
			$query = $this->db->select('b.*, a.company_id as aCompanyID, a.type as aType, s.company_id as sCompanyID,s.type as sType, p.company_id as pCompanyID,p.type as pType,l.lead_name,a.id as aID, s.id as sID, p.id as pID')->from($this->table_box.' as b')->join($this->table_acquisition_activity.' as a','b.id = a.email_id','left')->join($this->table_sales_activity.' as s','b.id = s.email_id','left')->join($this->table_presales_activity.' as p','b.id = p.email_id','left')->join($this->table_lead.' as l','l.id = b.lead_id')->where($searchString)->where("l.status IN ('0','1','2')")->order_by('b.id','desc')->get();
			/*echo $this->db->last_query();*/
			if(count($query->num_rows())>0){
				foreach($query->result() as $row){
					$activityType = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($row->id);
					$activity = 0;
					if(count($activityType)==0){
						$activityType = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($row->id);
						if(count($activityType)>0){
							$activity = 1;
						} else {
							$activityType = $this->lead_model->getPersonCompanyDetailFromPreSalesActivityLogByEmailID($row->id);
							if(count($activityType)>0){
								$activity = 3;
							}
						}
					} else {
						$activity = 2;
					}
					$row->from_activity = $activity;
					$data[] = $row;
				}
			}			
		}
		return $data;
	}
	
	public function fincImapEmailWithMessageID($messageID){
		$data = array();
			$query = $this->db->select('DISTINCT(b.id),b.*, a.company_id as aCompanyID, a.type as aType, s.company_id as sCompanyID,s.type as sType, p.company_id as pCompanyID,p.type as pType,l.lead_name,a.id as aID, s.id as sID, p.id as pID')->from($this->table_box.' as b')->join($this->table_acquisition_activity.' as a','b.id = a.email_id','left')->join($this->table_sales_activity.' as s','b.id = s.email_id','left')->join($this->table_presales_activity.' as p','b.id = p.email_id','left')->join($this->table_lead.' as l','l.id = b.lead_id')->where('b.content LIKE "%'.$messageID.'%"')->where("l.status IN ('0','1','2')")->where('b.sent_from','1')->order_by('b.id','ASC')->get();
			/*echo $this->db->last_query();*/
			if(count($query->num_rows())>0){
				$row = $query->first_row();
				foreach($query->result() as $row){
					$activityType = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($row->id);
					$activity = 0;
					if(count($activityType)==0){
						$activityType = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($row->id);
						if(count($activityType)>0){
							$activity = 1;
						} else {
							$activityType = $this->lead_model->getPersonCompanyDetailFromPreSalesActivityLogByEmailID($row->id);
							if(count($activityType)>0){
								$activity  = 3;
							}
						}
					} else {
						$activity = 2;
					}
					$row->from_activity = $activity;
					$data[] = $row;
				}
			}
			return $data;
	}
	
	public function getEmailList($limit){
		$data = array();
		$query = $this->db->select('b.*, a.company_id as aCompanyID, a.type as aType, s.company_id as sCompanyID,s.type as sType,l.lead_name')->from($this->table_box.' as b')->join($this->table_acquisition_activity.' as a','b.id = a.email_id','left')->join($this->table_sales_activity.' as s','b.id = s.email_id','left')->join($this->table_lead.' as l','l.id = b.lead_id')->where("l.status IN ('0','1','2')")->order_by('b.id','desc')->limit($limit,0)->get();
		if(count($query->num_rows())>0){
			foreach($query->result() as $row){
				$activityType = $this->lead_model->getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($row->id);
				$activity = 0;
				if(count($activityType)==0){
					$activityType = $this->lead_model->getPersonCompanyDetailFromSalesActivityLogByEmailID($row->id);
					if(count($activityType)>0){
						$activity = 1;
					} else {
						$activityType = $this->lead_model->getPersonCompanyDetailFromPreSalesActivityLogByEmailID($row->id);
						if(count($activityType)>0){
							$activity = 3;
						}
					}
				} else {
					$activity = 2;
				}
				$row->from_activity = $activity;
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getMessageTaskList($userID,$leadID=0){
		if($leadID==0){
			$queryMySend = $this->db->select("DISTINCT(task_id) as taskID")->from($this->table_task_conversation.' as c')->where('c.from_u',$userID)->order_by('c.id','DESC')->get();
		} else {
			$queryMySend = $this->db->select("DISTINCT(task_id) as taskID")->from($this->table_task_conversation.' as c')->join($this->table_requests.' as r','r.id = c.task_id')->where('c.from_u',$userID)->where('r.lead_id',$leadID)->order_by('c.id','DESC')->get();
		}
		$sendMessage = array();
		$countSendUnread = 0;
		$receiveMessage = array();
		$countReceieveUnread = 0;
		if(count($queryMySend->num_rows())>0){
			foreach($queryMySend->result() as $task){
				$queryTask = $this->db->select('c.from_u as sentUser,c.task_id as taskID,c.create_c,r.*,l.type as leadType,l.lead_name,u.name as fromUserName,u1.name as userName,u1.profile_pic,c.message as userMessage')->from($this->table_task_conversation.' as c')->join($this->table_requests.' as r','r.id = c.task_id')->join($this->table_lead.' as l','l.id = r.lead_id')->join('users as u','u.id=c.from_u')->join('users as u1','u1.id=r.user_id')->where('c.task_id',$task->taskID)->limit(1,0)->order_by('c.id','DESC')->get();
				/*echo $this->db->last_query()."<br/>";*/
				if ($queryTask->num_rows() > 0) {
					$data = $queryTask->first_row();
					if($data->sentUser==$userID){
						$sendMessage[] = $data;
						$countSendUnread++;
					}
				}
			}
		}
		if($leadID==0){
			$queryMyReceive = $this->db->select("DISTINCT(task_id) as taskID,f.message_id as messageID")->from($this->table_task_flag.' as f')->where('f.user_id',$userID)->where('f.status <> 2')->order_by('f.message_id','DESC')->get();
		} else {
			$queryMyReceive = $this->db->select("DISTINCT(task_id) as taskID,f.message_id as messageID")->from($this->table_task_flag.' as f')->join($this->table_requests.' as r','r.id = f.task_id')->where('f.user_id',$userID)->where('f.status <> 2')->where('r.lead_id',$leadID)->order_by('f.message_id','DESC')->get();
		}
		
		if(count($queryMyReceive->num_rows())>0){
			foreach($queryMyReceive->result() as $task){
				$currentMessage = $this->db->select("c.id as messageID")->from($this->table_task_conversation.' as c')->where('c.task_id',$task->taskID)->order_by('c.id','DESC')->get()->row();
				if(count($currentMessage)>0){
					$queryTask = $this->db->select('f.user_id as receiveUser,f.task_id as taskID,c.create_c,f.status as unRead,r.*,l.type as leadType,l.lead_name,u.name as fromUserName,u1.name as userName,u1.profile_pic,c.message as userMessage')->from($this->table_task_flag.' as f')->join($this->table_task_conversation.' as c', 'f.message_id= c.id')->join($this->table_requests.' as r','r.id = f.task_id')->join($this->table_lead.' as l','l.id = r.lead_id')->join('users as u','u.id=c.from_u')->join('users as u1','u1.id=r.user_id')->where('f.message_id',$currentMessage->messageID)->order_by('f.flag_id','DESC')->get();
					/*echo $this->db->last_query()."<br/>";*/
					if ($queryTask->num_rows() > 0) {
						foreach($queryTask->result() as $data){
							if($data->receiveUser==$userID){
								$receiveMessage[] = $data;
								if($data->unRead=="1"){
									$countReceieveUnread++;
								}
							}
						}					
					}
				}
			}
		}
		return array("receive"=>$receiveMessage,'countReceieve'=>$countReceieveUnread,"sent"=>$sendMessage,'countSend'=>$countSendUnread);
	}
	
	
	public function getFlagConversations($userID,$flag=true,$leadID = 0){		
		$taskCount = (object) array("countNotify"=>0);
		$taskICount = (object) array("countNotify"=>0);
		if($flag==true && $leadID==0){
			$taskList = $this->waitingCalApproval($userID);
			$taskCount = $this->waitingApprovalCount($userID);
			$taskIList = $this->waitingICalApproval($userID);
			$taskICount = $this->waitingIApprovalCount($userID);
		} else {			
			$taskList = $this->getAllTaskMeFromLead($leadID);
			$taskCount = $this->getAllTaskFromLeadCount($leadID);
			$taskIList = $this->myTaskApproval($leadID);
			$taskICount = $this->myTaskApprovalCount($leadID);
		}		
		$returnArray = $this->getMessageTaskList($userID,$leadID);
		$returnArray['taskList'] = $taskList;
		$returnArray['taskCount'] = $taskCount->countNotify;
		$returnArray['taskICount'] = $taskICount->countNotify;
		$returnArray['taskIList'] = $taskIList;
        /*$allArray['task_i'] = $this->myTaskApproval($row->id);*/		
		return $returnArray;
	}	
	
	function waitingApprovalCount($userID){
		$query = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.from_user_id')->where('a.user_id',$userID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get()->row();
		return $query;
	}
	
	function waitingIApprovalCount($userID){
		$query = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.user_id')->where('a.from_user_id',$userID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get()->row();
		return $query;
	}
	
	function waitingICalApproval($userID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.id,l.lead_name,l.type,l.create_date,uu.name as userName, uu.type as userType,u.name as uuserName,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.from_user_id')->join($this->table.' as uu','uu.id=a.user_id')->where('a.from_user_id',$userID)->where('a.status <> 2')->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	function waitingCalApproval($userID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.id,l.lead_name,l.type,l.create_date,u.name as userName, u.type as userType,uu.name as uuserName,uu.profile_pic')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.from_user_id')->join($this->table.' as uu','uu.id=a.user_id')->where('a.user_id',$userID)->where('a.status <> 2')->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	function waitingApproval($userID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.*,u.name as userName, u.type as userType')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.from_user_id')->where('a.user_id',$userID)->where('a.status <> 2')->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	
	
	
	
	function deleteTask($taskID){
		$this->db->where('task_id',$taskID);
		$this->db->delete($this->table_task_flag);
		$this->db->where('task_id',$taskID);
		$this->db->delete($this->table_task_conversation);
		$this->db->where('id',$taskID);
		$this->db->delete($this->table_requests);
	}
	
	
	
	
	
	function countUnreadMessage($userID){
		$query = $this->db->select('count(*) as messageCount')->from($this->table_task_flag.' as f')->where('f.user_id',$userID)->where('f.status','1')->get()->row();
		return $query->messageCount;
	}
	
	public function getTaskConversation($taskID){
		$this->updateTaskFlagConversation($taskID,$this->session->userdata['id'], array('status'=>'0'));
		$listQuery = $this->db->select("c.*,u1.id as fromUser, u1.name as fromUserName, u1.profile_pic")->from($this->table_task_conversation.' as c')->join('users as u1','u1.id=c.from_u')->where("c.task_id",$taskID)->order_by('c.id', 'ASC')->get();
		$list = array();
		if(count($listQuery->num_rows())>0){
			$count = 0;
			foreach($listQuery->result() as $box){
				$list[] = $box;				
			}
		}
		return $list;
	}
	
	public function getTaskConversationList($taskID){
		$listQuery = $this->db->select("c.*,u1.id as fromUser, u1.name as fromUserName, u1.profile_pic")->from($this->table_task_flag.' as c')->join('users as u1','u1.id=c.user_id')->where("c.task_id",$taskID)->order_by('c.flag_id', 'ASC')->get();
		$list = array();
		if(count($listQuery->num_rows())>0){
			$count = 0;
			foreach($listQuery->result() as $box){
				$list[] = $box;				
			}
		}
		return $list;
	}
	
	public function updateTaskFlagConversation($taskID,$userID,$data){
		$this->db->where('task_id',$taskID);
		$this->db->where('user_id',$userID);
		$this->db->update($this->table_task_flag, $data);
		
	}
	
	public function getContactToken(){
		$listQuery = $this->db->select("*")->from($this->table_contacts_access)->get();
		$data = array();
		if(count($listQuery->num_rows())>0){
			$data = $listQuery->first_row();
		}
		return $data;
	}
	
	
	public function findLucidData($leadID){
		$listQuery = $this->db->select("*")->from($this->table_chart)->where('lead_id',$leadID)->get();
		$list =array();
		if(count($listQuery->num_rows())>0){
			foreach($listQuery->result() as $box){
				$list[] = $box;
			}
		}
		return $list;
	}
	
	public function insertChart($data){
		// Inserting in Table(Litigation) 
		$this->db->insert($this->table_chart, $data);
		return $this->db->insert_id();
	}
	public function updateChart($id,$data){
		// Inserting in Table(Litigation) 
		$this->db->where('lead_id', $id);
		$this->db->update($this->table_chart, $data);
		return $id;
	}
	
	public function updateChartWithLead($leadID,$patent,$data){
		$this->db->where('lead_id', $leadID);
		$this->db->where('patent', $patent);
		$this->db->update($this->table_chart, $data);
		return $leadID;
	}
	
	public function from_litigation_insert($data){
		// Inserting in Table(Litigation) 
		$this->db->insert($this->table_lead, $data);
		return $this->db->insert_id();
	}
	public function from_litigation_update($id,$data){
		// Inserting in Table(Litigation) 
		$this->db->where('id', $id);
		$this->db->update($this->table_lead, $data);
		
		return $id;
	}
	
	public function insertPassLead($data){
		$this->db->insert($this->table_pass,$data);
		return $this->db->insert_id(); 
	}
	
	public function insertBox($data){
		$this->db->insert($this->table_box,$data);
		return $this->db->insert_id();
	}
	
	public function updateBox($id,$data){
		// Inserting in Table(Litigation) 
		$this->db->where('id', $id);
		$this->db->update($this->table_box, $data);
		
		return $id;
	}
	
	public function insertGmailMessages($data){
		$this->db->insert($this->table_gmail,$data);
		return $this->db->insert_id();
	}
	
	public function getPassLead(){		
		$data = array("message"=>array(),"lead"=>array());		
		return $data;
	}
	
	public function getGmailMessages($userID){
		$query= $this->db->select("*")->from($this->table_gmail)->where("user_id",$userID)->order_by('id','DESC')->get();
		$data = array();
		if($query->num_rows()>0){
			$data = $query->first_row();			
		}
		return $data;
	}
	
	public function removeFromBox($leadID,$threadID){
		$this->db->where('lead_id',$leadID);
		$this->db->where('id',$threadID);
		$this->db->delete($this->table_box);
		return $this->db->affected_rows();
	}
	public function removeFromAcquisition($leadID,$threadID){
		$this->db->where('lead_id',$leadID);
		$this->db->where('email_id',$threadID);
		$this->db->delete($this->table_acquisition_activity);
		return $this->db->affected_rows();
	}
	public function removeFromSales($leadID,$threadID){
		$this->db->where('lead_id',$leadID);
		$this->db->where('email_id',$threadID);
		$this->db->delete($this->table_sales_activity);
		return $this->db->affected_rows();
	}	
	function deleteLead($leadID){
		$this->db->where('id',$leadID);
		$this->db->delete($this->table_lead);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_box);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_acquisition_company);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_presales_activity);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_acquisition_activity);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_sales_activity);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_invitees);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_acquisition);
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_assign_lead);
		$this->db->where('parent_id',$leadID);
		$this->db->delete('other_comments');
		$queryRequest = $this->db->select('*')->from($this->table_requests)->where('lead_id',$leadID)->get();
		if($queryRequest->num_rows()>0){
			foreach ($queryRequest->result() as $row) {
				$taskID = $row->id;
				$this->db->where('task_id',$taskID);
				$this->db->delete($this->table_task_flag);
				$this->db->where('task_id',$taskID);
				$this->db->delete($this->table_task_conversation);
				$this->db->where('id',$taskID);
				$this->db->delete($this->table_requests);				
				$this->deleteApprovalRequest($leadID);
				$this->deleteHistory($leadID);
				$this->deleteLeadButtons($leadID);
			}	
		}
		return $this->db->affected_rows();
	}	
	public function findIncompleteList($type){
		$query = $this->db->select('*')->from($this->table_lead)->where('complete','0')->where('type',$type)->get();
		$data = array();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$boxQuery = $this->db->select("*")->from($this->table_box)->where('lead_id',$row->id)->get();
				$boxData =array();
				if(count($boxQuery->num_rows())>0){
					foreach($boxQuery->result() as $box){
						$boxData[] = $box;
					}
				}
				$row->box_list = $boxData;
				$data[] = $row;
			}			
		}
		return $data;
	}
	function getAllEmails(){
		$boxQuery = $this->db->select("*")->from($this->table_box)->where('date_received','0000-00-00 00:00:00')->get();
		$boxData =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxData[] = $box;
			}
		}
		return $boxData;
	}	
	function searchLeads($searchData){
		$data = array();
		$conditions = "";
		$flag = 0;
		if(!empty($searchData['lead_name'])){
			$flag = 1;
			$conditions = " lead_name LIKE '%".$this->db->escape_like_str($searchData['lead_name'])."%' ";
		}
		if(!empty($searchData['plantiffs_name'])){
			if($flag==1){
				$conditions .= " OR plantiffs_name LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'  OR seller_contact  LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'";
			} else {
				$flag = 1;
				$conditions = "  plantiffs_name LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'  OR seller_contact  LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'";
			}			
		}
		if(!empty($searchData['plantiffs_name'])){
			if($flag==1){
				$conditions .= " OR plantiffs_name LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'  OR seller_contact  LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'";
			} else {
				$flag = 1;
				$conditions = "  plantiffs_name LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'  OR seller_contact  LIKE '%".$this->db->escape_like_str($searchData['plantiffs_name'])."%'";
			}			
		}
		if(!empty($searchData['person_name_1'])){
			if($flag==1){
				$conditions .= " OR person_name_1 LIKE '%".$this->db->escape_like_str($searchData['person_name_1'])."%'  ";
			} else {
				$flag = 1;
				$conditions = "  person_name_1 LIKE '%".$this->db->escape_like_str($searchData['person_name_1'])."%' ";
			}			
		}
		if(!empty($searchData['person_name_2'])){
			if($flag==1){
				$conditions .= " OR person_name_2 LIKE '%".$this->db->escape_like_str($searchData['person_name_2'])."%'  ";
			} else {
				$flag = 1;
				$conditions = "  person_name_2 LIKE '%".$this->db->escape_like_str($searchData['person_name_2'])."%' ";
			}			
		}
		if(!empty($searchData['broker'])){
			if($flag==1){
				$conditions .= " OR broker_contact LIKE '%".$this->db->escape_like_str($searchData['broker'])."%'  ";
			} else {
				$flag = 1;
				$conditions = "  broker_contact LIKE '%".$this->db->escape_like_str($searchData['broker'])."%' ";
			}			
		}
		if(!empty($searchData['broker_person'])){
			if($flag==1){
				$conditions .= " OR broker_person_contact LIKE '%".$this->db->escape_like_str($searchData['broker_person'])."%'  ";
			} else {
				$flag = 1;
				$conditions = "  broker_person_contact LIKE '%".$this->db->escape_like_str($searchData['broker_person'])."%' ";
			}			
		}
		if(!empty($searchData['relates_to'])){
			if($flag==1){
				$conditions .= " OR relates_to LIKE '%".$this->db->escape_like_str($searchData['relates_to'])."%'  ";
			} else {
				$flag = 1;
				$conditions = "  relates_to LIKE '%".$this->db->escape_like_str($searchData['relates_to'])."%' ";
			}			
		}
		if(!empty($searchData['serial_number'])){			
			$conditions = "  serial_number ='".$this->db->escape_like_str($searchData['serial_number'])."'";						
		}
		if((int)$this->session->userdata['type']!=9){
			$sql = "SELECT l.* FROM ".$this->table_lead." as l INNER JOIN ".$this->table_assign_lead." as al ON al.lead_id = l.id WHERE ".$conditions." AND al.pd_id= ".$this->session->userdata['id']." AND l.status IN ('0','1','2') AND l.type <> 'INT' ORDER BY l.lead_name ASC";
		} else {
			$sql = "SELECT l.* FROM ".$this->table_lead." as l WHERE ".$conditions." AND l.status IN ('0','1','2') ORDER BY l.lead_name ASC";
		}
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}	
	public function getAllLeads(){
		$query = $this->db->select('*')->from($this->table_lead)->where("status IN ('0','1','2')")->order_by('lead_name','ASC')->get();
		$data = array();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}			
		}
		return $data;
	}	
	public function findIncompleteANDCompleteList($type=''){
		//$query = $this->db->select('*')->from($this->table_lead)->where('type',$type)->get();
        $data = array();
		if($this->session->userdata['type']==9){
			$query = $this->db->select('*')->from($this->table_lead)->where("status IN ('0','1','2')")->order_by('lead_name','ASC')->get();
			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					$boxQuery = $this->db->select("*")->from($this->table_box)->where('lead_id',$row->id)->order_by('lead_id','DESC')->get();
					$boxData =array();
					if(count($boxQuery->num_rows())>0){
						foreach($boxQuery->result() as $box){
							$boxData[] = $box;
						}
					}
					$row->box_list = $boxData;
					$data[] = $row;
				}			
			}
		} else {
			$checkUserAssignLead = $this->checkUserLeads($userID);
			$checkUserAssignLeadType = $this->checkUserAssignLeadType($userID);
			$data = array();
			/*$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
			*/
			$query = "";
			if(count($checkUserAssignLead)>0 && count($checkUserAssignLeadType)>0){
				$leadType = implode('","', $checkUserAssignLeadType);
				$leadAssigned = implode(',', $checkUserAssignLead);
				$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.type IN (".$leadType.")")->or_where("l.id IN (".$leadAssigned.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
			} else if(count($checkUserAssignLead)>0){
				$leadAssigned = implode(',', $checkUserAssignLead);
				$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.id IN (".$leadAssigned.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
			} else if(count($checkUserAssignLeadType)>0){
				$leadType = implode('","', $checkUserAssignLeadType);
				$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.type IN (".$leadType.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
			}
			if(is_object($query) && $query->num_rows()>0){
				foreach ($query->result() as $row) {
					$boxQuery = $this->db->select("*")->from($this->table_box)->where('lead_id',$row->id)->order_by('lead_id','DESC')->get();
					$boxData =array();
					if(count($boxQuery->num_rows())>0){
						foreach($boxQuery->result() as $box){
							$boxData[] = $box;
						}
					}
					$row->box_list = $boxData;
					$data[] = $row;
				}
			}
		}
		
		return $data;
	}	
	public function getSalesActivityCompaniesByLead($leadID){
		$data = array();
		$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		if ($getInviteesCompanies->num_rows() > 0) {
			foreach ($getInviteesCompanies->result() as $company) {
				$data[] = $company;
			}
		}
		return $data;
	}
	public function getAcquisitionActivityCompaniesByLead($leadID){
		$data = array();
		$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_acquisition_company.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		if ($getInviteesCompanies->num_rows() > 0) {
			foreach ($getInviteesCompanies->result() as $company) {
				$data[] = $company;
			}
		}
		return $data;
	}
	public function getPreSaleActivityCompaniesByLead($leadID){
		$data = array();
		$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.pre_sale_activity DESC')->get();
		if ($getInviteesCompanies->num_rows() > 0) {
			foreach ($getInviteesCompanies->result() as $company) {
				$data[] = $company;
			}
		}
		return $data;
	}
	function findCallData($activityID,$t){
		$data = array();
		$query = "";
		if($t==1){
			$query = $this->db->select("s.*,CONCAT((p.first_name),(' '),(p.last_name)) as personName")->from($this->table_sales_activity.' as s')->join($this->table_contacts.' as p','p.id = s.contact_id')->where('s.id',$activityID)->get();
		} else if($t==2){
			$query = $this->db->select("s.*,CONCAT((p.first_name),(' '),(p.last_name)) as personName")->from($this->table_acquisition_activity.' as s')->join($this->table_contacts.' as p','p.id = s.contact_id')->where('s.id',$activityID)->get();
		}
		if(is_object($query)){
			if($query->num_rows()>0){
				$data = $query->first_row();
			}
		}
		return $data;
	}	
	function getSalesContatcsByLead($leadID){
		$persons = array();
		$getInviteesCompanies = $this->db->select("c.id,c.company_name,s.id as sectorID, s.name as sectorName")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->join($this->table_company_sector.' as cs', 'c.id = cs.company_id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		if ($getInviteesCompanies->num_rows() > 0) {
			foreach ($getInviteesCompanies->result() as $company) {
				$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$company->id)->order_by("first_name DESC")->get();
				if($getPersons->num_rows()>0){
					foreach ($getPersons->result() as $p) {
						$p->company_name = $company->company_name;
						$p->sectorName = $company->sectorName;
						$persons[] = $p;
					}
				}
			}
		}
		return $persons;
	}	
	public function allLeadsWithActivity($activity){
		/*2=>Acquisition,1=>Sales Activity*/
		$query = $this->db->select('id,lead_name')->from($this->table_lead)->where("status IN ('0','1','2')")->order_by('lead_name ASC')->get();
		$data = array();		
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$companyData = array();	
				if($activity==2){
					$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_acquisition_company.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$row->id)->order_by('i.last_activity DESC')->get();
					if ($getInviteesCompanies->num_rows() > 0) {
						foreach ($getInviteesCompanies->result() as $company) {
							$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$company->id)->order_by("first_name DESC")->get();
							$persons =array();
							if($getPersons->num_rows()>0){
								foreach ($getPersons->result() as $p) {
									$persons[] = $p;
								}
							}				
							$companyData[]=array("company"=>$company,"people"=>$persons);
						}
					}
				} else if($activity==3){
					$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$row->id)->order_by('i.pre_sale_activity DESC')->get();
					if ($getInviteesCompanies->num_rows() > 0) {
						foreach ($getInviteesCompanies->result() as $company) {
							$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$company->id)->order_by("first_name DESC")->get();
							$persons =array();
							if($getPersons->num_rows()>0){
								foreach ($getPersons->result() as $p) {
									$persons[] = $p;
								}
							}				
							$companyData[]=array("company"=>$company,"people"=>$persons);
						}
					}
				} else {
					$getInviteesCompanies = $this->db->select("c.id,c.company_name")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$row->id)->order_by('i.last_activity DESC')->get();
					if ($getInviteesCompanies->num_rows() > 0) {
						foreach ($getInviteesCompanies->result() as $company) {
							$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$company->id)->order_by("first_name DESC")->get();
							$persons =array();
							if($getPersons->num_rows()>0){
								foreach ($getPersons->result() as $p) {
									$persons[] = $p;
								}
							}				
							$companyData[]=array("company"=>$company,"people"=>$persons);
						}
					}
				}				
				$row->box_list = $companyData;
				$data[] = $row;
			}			
		}
		return $data;
	}
	function checkUserLeads($userID){
		$data = array();
		$query = $this->db->select('l.lead_id as id')->from($this->table_assign_lead.' as l')->where('l.pd_id',$userID)->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$data[] =  $row->id;
			}
		}/*
		$findUserEmail = $this->db->select('u.email')->from($this->table.' as u')->where('u.id',$userID)->get()->row()->email;
		if(!empty($findUserEmail) && $findUserEmail!=null){
			$getContactDetails = $this->db->select('c.*')->from($this->table_contacts.' as c')->where('trim(c.email)',$findUserEmail)->get();
			if($getContactDetails->num_rows()>0){
				$contact = $getContactDetails->first_row();
				<!--Leads from sales-->
				$findAllContactsBelongsToContactCompany = $this->db->select("c.id")->from($this->table_contacts.' as c')->where("c.company_id",$contact->company_id)->get();
				$allContacts = array();
				if($findAllContactsBelongsToContactCompany->num_rows()>0){
					foreach($findAllContactsBelongsToContactCompany->result() as $row){
						$allContacts[] = $row->id;
					}
					if(count($allContacts)>0){
						$findCompanyAssociateToThisContacts = $this->findCompaniesAssociate($allContacts);
						$findCompanyAssociateToThisContacts[] = $contact->company_id;
						if(count($findCompanyAssociateToThisContacts)>0){
							$companies = implode(',',$findCompanyAssociateToThisContacts);
							$getLeadsFromSales = $this->db->select('DISTINCT(i.lead_id) as lead_id')->from($this->table_invitees.' as i')->where('i.contact_id IN ('.$companies.')')->get();
							if($getLeadsFromSales->num_rows()>0){
								foreach($getLeadsFromSales->result() as $row){
									$data[] = $row->lead_id;
								}
							}
						}
					}
				}
			}
		}*/
		return $data;
	}	
	function checkUserAssignLeadType($userID){
		$data = array();
		$query = $this->db->select('l.lead_type as type')->from($this->table_assign_lead_type.' as l')->where('l.user_id',$userID)->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$data[] =  '"'.$row->type.'"';
			}
		}
		return $data;
	}	
	public function findIncompleteANDCompleteListAccUser($userID){
		//$query = $this->db->select('*')->from($this->table_lead)->where('type',$type)->get();
        /*$query = $this->db->select('l.*')->from($this->table_lead.' as l')->join($this->table_assign_lead .' as al','al.lead_id = l.id')->where('al.pd_id',$userID)->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->get();*/
		$checkUserAssignLead = $this->checkUserLeads($userID);
		$checkUserAssignLeadType = $this->checkUserAssignLeadType($userID);
		$data = array();
        /*$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
		*/
		$query = "";
		if(count($checkUserAssignLead)>0 && count($checkUserAssignLeadType)>0){
			$leadType = implode('","', $checkUserAssignLeadType);
			$leadAssigned = implode(',', $checkUserAssignLead);
			$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.type IN (".$leadType.")")->or_where("l.id IN (".$leadAssigned.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
		} else if(count($checkUserAssignLead)>0){
			$leadAssigned = implode(',', $checkUserAssignLead);
			$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.id IN (".$leadAssigned.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
		} else if(count($checkUserAssignLeadType)>0){
			$leadType = implode('","', $checkUserAssignLeadType);
			$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.type IN (".$leadType.")")->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->order_by('l.lead_name ASC')->get();
		}
		if(is_object($query) && $query->num_rows()>0){
			foreach ($query->result() as $row) {
				$boxQuery = $this->db->select("*")->from($this->table_box)->where('lead_id',$row->id)->order_by('lead_id','DESC')->get();
				$boxData =array();
				if(count($boxQuery->num_rows())>0){
					foreach($boxQuery->result() as $box){
						$boxData[] = $box;
					}
				}
				$row->box_list = $boxData;
				$data[] = $row;
			}			
		}
		return $data;
	}
	
	public function from_count_incomplete_litigation($type,$complete){
		$query = $this->db->select('count(*) as leadCount')->from($this->table_lead)->where('complete',$complete)->where('type',$type)->where('status','0')->get();
		$data = array();
		if($query->num_rows()>0){
			$data =$query->first_row();
		}
		return $data;
	}
	
	public function record_count($type,$complete=1) {
		$this->db->where('type',$type);
		$this->db->where('status','0');
		$this->db->where('complete',$complete);
        return $this->db->count_all_results($this->table_lead);
    }
	
	function findBoxList($leadID,$type=0){
		$boxQuery = $this->db->select("*")->from($this->table_box)->where('lead_id',$leadID)->where('type',$type)->order_by('date_received','DESC')->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxList[] = $box;
			}
		}
		return $boxList;
	}
	
	function findBoxByThread($threadID){
		$boxQuery = $this->db->select("*")->from($this->table_box)->where('thread_id',$threadID)->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxList[] = $box;
			}
		}
		return $boxList;
	}
	
	function findBoxByMessageID($messsageID){
		$boxQuery = $this->db->select("*")->from($this->table_box)->where('message_id',$messsageID)->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxList[] = $box;
			}
		}
		return $boxList;
	}
	
	function findBoxNewById($threadID){
		$boxQuery = $this->db->select("*")->from($this->table_box)->where('id',$threadID)->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			$boxList[] = $boxQuery->first_row();			
		}
		return $boxList;
	}
	
	function findAllBoxList(){
		$boxQuery = $this->db->select("*")->from($this->table_box)->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxList[] = $box;
			}
		}
		
		return $boxList;
	}
	function findAllBoxThreadList(){
		$boxQuery = $this->db->select("thread_id")->from($this->table_box)->get();
		$boxList =array();
		if(count($boxQuery->num_rows())>0){
			foreach($boxQuery->result() as $box){
				$boxList[] = $box;
			}
		}
		
		return $boxList;
	}
	
	
	public function checkUserCreatedLeadFromLitigation(){
		$userID = $this->session->userdata['id'];
		$start = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
		$finish = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
		$query = $this->db->select('count(*) as leads')->from($this->table_lead)->where('type','Litigation')->where('date_format(create_date,"%Y-%m-%d")>=',$start)->where('date_format(create_date,"%Y-%m-%d")<=',$finish)->where('user_id',$userID)->get();
		return $query->row();
		
	}
	public function findAllLitigationWithPaging($type,$limit,$start,$complete=1){
		$this->db->limit($limit, $start);
		$this->db->order_by('id','DESC');		
		$query = $this->db->select('l.*,u.name as userName')->from($this->table_lead.' as l')->join('users as u','u.id=l.user_id','left outer')->where('l.type',$type)->where('l.status','0')->where('complete',$complete)->order_by('l.id','DESC')->get();

	
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $allArray = array();
				$allArray['litigation'] = $row;
				$allArray['comment'] = $this->getLitigationComments($row->id,$type);
				$data[] = $allArray;
            }            
        }
		
		return $data;
	}
    public function findOneLitigationWithPaging($lead_id,$type){
		$query = $this->db->select('l.*,u.name as userName')->from($this->table_lead.' as l')->join('users as u','u.id=l.user_id','left outer')->where('l.type',$type)->where('l.status','0')->where('l.id',$lead_id)->order_by('l.id','DESC')->get();
       
		
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $allArray = array();
				$allArray['litigation'] = $row;
				$allArray['comment'] = $this->getLitigationComments($row->id,$type);
				$data[] = $allArray;
            }            
        }
		
		return $data;
	}
	
	public function myTaskApprovalCount($leadID){
		$data = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.user_id')->where('a.from_user_id',$this->session->userdata['id'])->where('a.lead_id',$leadID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get()->row();		
		return $data;
	}
	
	public function myTaskApproval($leadID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.create_date as taskCreateDate,l.*,u.name as userName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.user_id')->where('a.from_user_id',$this->session->userdata['id'])->where('a.lead_id',$leadID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		/*echo $this->db->last_query();*/
		return $data;
	}
	
	public function findOneLitigationWithIncomplete($lead_id,$type){
		$query = $this->db->select('l.*')->from($this->table_lead.' as l')->where('l.type',$type)->where('l.id',$lead_id)->order_by('l.id','DESC')->get();
		
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $allArray = array();
				$allArray['litigation'] = $row;
				$allArray['comment'] = $this->getLitigationComments($row->id,$type);
                $allArray['timeLine'] = $this->getAllUserHistory(0,$row->id,0);
                $allArray['task'] = $this->getAllTaskFromLead($row->id);
                $allArray['task_i'] = $this->myTaskApproval($row->id);
                $allArray['acquisitions'] = $this->getAcquisitionData($row->id);
                $allArray['stage'] = $this->checkStage($row->id);
                $allArray['level'] = $this->checkLevel($row->id);
                $allArray['report'] = $this->checkLeadReport($row->id);
                $allArray['buttons'] = $this->checkButtonList($row->id);
                $allArray['sales_activity'] = $this->getSalesActivity($row->id);
                $allArray['presales_activity'] = $this->getPreSalesActivity($row->id);
                $allArray['broker_as_companies'] = $this->findLeadCompanyBrokerDetails($row->id);
				$data[] = $allArray;
            }            
        }
		
		return $data;
	}
	
	function c_my_contact_list($cID,$select){
		$persons =array();
		$getPersons = $this->db->select($select)->from($this->table_contacts.' as c')->where("c.company_id",$cID)->order_by("c.first_name DESC")->get();
		if($getPersons->num_rows()>0){
			foreach ($getPersons->result() as $p) {
				$persons[] = $p;
			}
		}
		return $persons;
	}
	
	function getContactByEmail($email){
		$persons = array();
		$email = trim($email);
		if($email!="" && !empty($email))
		$getPersons = $this->db->select('*')->from($this->table_contacts.' as c')->where("c.email",trim($email))->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	function getContactFullContactID($fullContact){
		$persons = array();
		$fullContact = trim($fullContact);
		if($fullContact!="" && !empty($fullContact))
		$getPersons = $this->db->select('*')->from($this->table_contacts.' as c')->where("c.fullContact",trim($fullContact))->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	function getContactById($ID){
		$persons = array();
		$getPersons = $this->db->select('*')->from($this->table_contacts.' as c')->where("c.id",$ID)->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	function getPreContactByEmail($email){
		$persons = array();
		$email = trim($email);
		if($email!="" && !empty($email))
		$getPersons = $this->db->select('*')->from($this->table_precontacts.' as c')->where("c.email",trim($email))->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	function getPreContactFullContactID($fullContact){
		$persons = array();
		$fullContact = trim($fullContact);
		if($fullContact!="" && !empty($fullContact))
		$getPersons = $this->db->select('*')->from($this->table_precontacts.' as c')->where("c.fullcontact",trim($fullContact))->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	public function updateSalesActivity($id,$data){
		$this->db->where('id',$id);
		$this->db->update($this->table_sales_activity, $data);
		if($this->db->affected_rows()>0){
			/*$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);*/
			/*$this->db->update($this->table_invitees,array('last_activity'=>$data['activity_date']));*/
		}
		return $id;
	}
	
	public function insetSalesActivity($data){
		$this->db->insert($this->table_sales_activity, $data);
		$id = 0;
		if($this->db->insert_id()>0){
			$id = $this->db->insert_id();
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_invitees,array('last_activity'=>$data['activity_date']));
			/*echo $this->db->last_query();*/
		}
		return $id;
	}
	
	public function updatePreSaleActivity($id,$data){
		$this->db->where('id',$id);
		$this->db->update($this->table_presales_activity, $data);
		if($this->db->affected_rows()>0){
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_invitees,array('pre_sale_activity'=>$data['activity_date']));
		}
		return $id;
	}
	
	public function updateAcquistionActivity($id,$data){
		$this->db->where('id',$id);
		$this->db->update($this->table_acquisition_activity, $data);
		if($this->db->affected_rows()>0){
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_acquisition_company,array('last_activity'=>$data['activity_date']));
		}
		return $id;
	}
	
	public function insertAcquistionActivity($data){
		$this->db->insert($this->table_acquisition_activity, $data);
		$id = 0;
		if($this->db->insert_id()>0){
			$id = $this->db->insert_id();
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_acquisition_company,array('last_activity'=>$data['activity_date']));
		}
		return $id;
	}
	
	public function insertPreSaleActivity($data){
		$this->db->insert($this->table_presales_activity, $data);
		$id = 0;
		if($this->db->insert_id()>0){
			$id = $this->db->insert_id();
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_invitees,array('pre_sale_activity'=>$data['activity_date']));
		}
		return $id;
	}
	
	function findSalesActivityCompanies($leadID){
		$companyData  = array();
		$getInviteesCompanies = $this->db->select("c.company_name as orgName")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('c.company_name ASC')->get();
		if ($getInviteesCompanies->num_rows() > 0) {
			 foreach ($getInviteesCompanies->result() as $row) {
				$companyData[] = $row;
			 }
		}
		return $companyData;
	}
	
	public function findBrokerDetails($brokerID){
		$data = array();
		if($brokerID>0){
			$query = $this->db->select("c.*,co.company_name , co.id as companyID")->from($this->table_contacts.' as c')->join($this->table_company.' as co','co.id = c.company_id')->where('c.id',$brokerID)->get();
			if ($query->num_rows() > 0) {
				$data = $query->first_row();
			}
		}
		return $data;
	}
	
	function findLeadCompanyBrokerDetails($leadID){
		$userID = $this->session->userdata['id'];
		$type = $this->session->userdata['type'];
		$details = array();
		if($userID>0 && $type!=9 && $type!=8){
			$userEmail = $this->session->userdata['email'];
			$contactDetails = $this->getContactByEmail($userEmail);
			if(count($contactDetails)>0){
				$query = $this->db->select("co.company_name , co.id as companyID, sblc.sales_company_id as SBLCID")->from($this->table_company.' as co')->join($this->table_sales_broker_lead_company.' as sblc','sblc.broker_company_id = co.id')->where('sblc.broker_company_id',$contactDetails->company_id)->where('sblc.lead_id',$leadID)->get();
				foreach ($query->result() as $row) {
					$details[] = $row;
				}
			}			
		} else {
			$query = $this->db->select("co.company_name , co.id as companyID, sblc.sales_company_id as SBLCID")->from($this->table_company.' as co')->join($this->table_sales_broker_lead_company.' as sblc','sblc.broker_company_id = co.id')->where('sblc.lead_id',$leadID)->get();
			foreach ($query->result() as $row) {
				$details[] = $row;
			}
		}
		return $details;
	}
	
	function findCompaniesAssociate($contacts = array()){
		$companies = array();
		if(count($contacts)>0){
			$contact  = implode(',',$contacts);
			$findCompaniesIDs = $this->db->select('DISTINCT(c.id) as companyID')->from($this->table_company.' as c')->where('c.broker IN('.$contact.')')->get();
			if($findCompaniesIDs->num_rows()>0){
				foreach($findCompaniesIDs->result() as $row){
					$companies[] = $row->companyID;
				}
			}
		}
		return $companies;
	}
	
	function getPreSalesBrokerListByLead($leadID){
		$getPersons = $this->db->select("c.*,co.company_name")->from($this->table_contacts.' as c')->join($this->table_presale_broker.' as pb', 'pb.broker_id= c.id')->join($this->table_company.' as co','co.id = c.company_id')->where('pb.lead_id',$leadID)->order_by("c.first_name DESC")->get();
		$persons =array();
		if($getPersons->num_rows()>0){
			foreach ($getPersons->result() as $p) {
				$persons[] = $p;
			}
		}
		return $persons;
	}
	
	function getPreSalesActivity($leadID){
		$userType = $this->session->userdata['type'];
		$getInviteesCompanies = (object)array();
		if($userType<8){					
			$getInviteesCompanies = $this->db->select("c.*,i.stage")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.pre_sale_activity DESC')->get();
			 
		} else {
			$getInviteesCompanies = $this->db->select("c.*")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.pre_sale_activity DESC')->get();
		}
		$companyData = array();		
		if (is_a($getInviteesCompanies, 'CI_DB_mysql_result') && $getInviteesCompanies->num_rows() > 0) {
            foreach ($getInviteesCompanies->result() as $row) {
				$getPersons = $this->db->select("c.*,co.company_name")->from($this->table_contacts.' as c')->join($this->table_presale_broker.' as pb', 'pb.broker_id= c.id')->join($this->table_company.' as co','co.id = c.company_id')->where("pb.company_id",$row->id)->where('pb.lead_id',$leadID)->order_by("c.first_name DESC")->get();
				$persons =array();
				if($getPersons->num_rows()>0){
					foreach ($getPersons->result() as $p) {
						$persons[] = $p;
					}
				}
				$activities = array();
				$getActivities = $this->db->select("s.*,c.first_name as firstName, c.last_name as lastName")->from($this->table_presales_activity." as s")->join($this->table_contacts.' as c','c.id = s.contact_id')->where('s.company_id',$row->id)->where('lead_id',$leadID)->order_by('s.activity_date DESC')->get();
				if($getActivities->num_rows()>0){
					foreach ($getActivities->result() as $a) {
						$email = array();
						if($a->email_id>0){
							$email = $this->findBoxNewById($a->email_id);
						}
						$a->email = $email;
						$activities[] = $a;
					}
				}
				/*$row->broker_details = $this->findBrokerDetails($row->broker);*/
				
				$companyData[]=array("company"=>$row,"people"=>$persons,"activities"=>$activities);
			}
		}
		return $companyData;
	}
	
	function getSalesActivity($leadID){
		$userType = $this->session->userdata['type'];
		$getInviteesCompanies = (object)array();
		if($userType<8){
			/*Get only those leads which are assigned to user*/
			/*
			$findUserEmail = $this->db->select('u.email')->from($this->table.' as u')->where('u.id',$this->session->userdata['id'])->get()->row()->email;
			if(!empty($findUserEmail) && $findUserEmail!=null){
				$getContactDetails = $this->db->select('c.*')->from($this->table_contacts.' as c')->where('trim(c.email)',$findUserEmail)->get();
				if($getContactDetails->num_rows()>0){
					$contact = $getContactDetails->first_row();
					$findAllContactsBelongsToContactCompany = $this->db->select("c.id")->from($this->table_contacts.' as c')->where("c.company_id",$contact->company_id)->get();
					$allContacts = array();
					if($findAllContactsBelongsToContactCompany->num_rows()>0){
						foreach($findAllContactsBelongsToContactCompany->result() as $row){
							$allContacts[] = $row->id;
						}
						if(count($allContacts)>0){
							$findCompanyAssociateToThisContacts = $this->findCompaniesAssociate($allContacts);
							$findCompanyAssociateToThisContacts[] = $contact->company_id;
							if(count($findCompanyAssociateToThisContacts)>0){
								$companies = implode(',',$findCompanyAssociateToThisContacts);
								$getInviteesCompanies = $this->db->select("c.*,i.stage")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->where('c.id IN ('.$companies.')')->order_by('i.last_activity DESC')->get();
							}
						}
					}
				}
			}*/			
			$getInviteesCompanies = $this->db->select("c.*,i.stage")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		} else {
			$getInviteesCompanies = $this->db->select("c.*,i.stage")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		}
		$companyData = array();		
		if (is_a($getInviteesCompanies, 'CI_DB_mysql_result') && $getInviteesCompanies->num_rows() > 0) {
            foreach ($getInviteesCompanies->result() as $row) {
				$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$row->id)->order_by("first_name DESC")->get();
				$persons =array();
				if($getPersons->num_rows()>0){
					foreach ($getPersons->result() as $p) {
						$persons[] = $p;
					}
				}
				$activities = array();
				$getActivities = $this->db->select("s.*,c.first_name as firstName, c.last_name as lastName")->from($this->table_sales_activity." as s")->join($this->table_contacts.' as c','c.id = s.contact_id')->where('s.company_id',$row->id)->where('lead_id',$leadID)->order_by('s.activity_date DESC')->get();
				/*echo $this->db->last_query();*/
				if($getActivities->num_rows()>0){
					foreach ($getActivities->result() as $a) {
						$email = array();
						if($a->email_id>0){
							$email = $this->findBoxNewById($a->email_id);
						}
						$a->email = $email;
						$activities[] = $a;
					}
				}
				/*$row->broker_details = $this->findBrokerDetails($row->broker);*/
				
				$companyData[]=array("company"=>$row,"people"=>$persons,"activities"=>$activities);
			}
		}
		return $companyData;
	}	
	
	function getAcquisitionActivity($leadID){
		$userType = $this->session->userdata['type'];
		$getInviteesCompanies = (object)array();
		if($userType<8){
			/*Get only those leads which are assigned to user*/
			$findUserEmail = $this->db->select('u.email')->from($this->table.' as u')->where('u.id',$this->session->userdata['type'])->get()->row()->email;
			if(!empty($findUserEmail) && $findUserEmail!=null){
				$getContactDetails = $this->db->select('c.*')->from($this->table_contacts.' as c')->where('trim(c.email)',$findUserEmail)->get();
				if($getContactDetails->num_rows()>0){
					$contact = $getContactDetails->first_row();
					$findAllContactsBelongsToContactCompany = $this->db->select("c.id")->from($this->table_contacts.' as c')->where("c.company_id",$contact->company_id)->get();
					$allContacts = array();
					if($findAllContactsBelongsToContactCompany->num_rows()>0){
						foreach($findAllContactsBelongsToContactCompany->result() as $row){
							$allContacts[] = $row->id;
						}
						if(count($allContacts)>0){
							$findCompanyAssociateToThisContacts = $this->findCompaniesAssociate($allContacts);
							$findCompanyAssociateToThisContacts[] = $contact->company_id;
							if(count($findCompanyAssociateToThisContacts)>0){
								$companies = implode(',',$findCompanyAssociateToThisContacts);
								$getInviteesCompanies = $this->db->select("c.*")->from($this->table_acquisition_company.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->where('c.id IN ('.$companies.')')->order_by('i.last_activity DESC')->get();
							}
						}
					}
				}
			}			
		} else {
			$getInviteesCompanies = $this->db->select("c.*")->from($this->table_acquisition_company.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		}
		$companyData = array();
		if (is_a($getInviteesCompanies, 'CI_DB_mysql_result') && $getInviteesCompanies->num_rows() > 0) {
            foreach ($getInviteesCompanies->result() as $row) {
				$getPersons = $this->db->select("*")->from($this->table_contacts)->where("company_id",$row->id)->order_by("first_name DESC")->get();
				$persons =array();
				if($getPersons->num_rows()>0){
					foreach ($getPersons->result() as $p) {
						$persons[] = $p;
					}
				}
				$activities = array();
				$getActivities = $this->db->select("s.*,c.first_name as firstName, c.last_name as lastName")->from($this->table_acquisition_activity." as s")->join($this->table_contacts.' as c','c.id = s.contact_id')->where('s.company_id',$row->id)->where('lead_id',$leadID)->order_by('s.activity_date DESC')->get();
				if($getActivities->num_rows()>0){
					foreach ($getActivities->result() as $a) {
						$email = array();
						if($a->email_id>0){
							$email = $this->findBoxNewById($a->email_id);
						}
						$a->email = $email;
						$activities[] = $a;
					}
				}
				$row->broker_details = $this->findBrokerDetails($row->broker);
				$companyData[]=array("company"=>$row,"people"=>$persons,"activities"=>$activities);
			}
		}
		return $companyData;
	}
	
	function companyListAssignedUser($userID){
		
	}
	
	function findLeadButtonData($leadID,$buttonID){
		$query = $this->db->select("b.button_id,b.type,b.name,b.description,b.status_message,b.reference_id,l.id,l.status as btnStatus,l.update_date,l.renewable,l.status_message_fill")->from($this->table_buttons .' as b')->join($this->table_lead_buttons .' as l','l.button_id = b.id')->where('l.lead_id',$leadID)->where('l.id',$buttonID)->order_by('l.sort ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row();
        }
		return $data;
	}
	
	function checkButtonList($leadID){
		$query = $this->db->select("b.button_id,b.type,b.name,b.description,b.status_message,b.reference_id,l.id,l.status as btnStatus,l.update_date,l.renewable,l.status_message_fill,l.blink,l.send_task")->from($this->table_buttons .' as b')->join($this->table_lead_buttons .' as l','l.button_id = b.id')->where('l.lead_id',$leadID)->order_by('l.sort ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	function checkDocketButtonList($leadID){
		$query = $this->db->select("b.button_id,b.type,b.name,b.description,b.status_message,b.reference_id,l.id,l.status as btnStatus,l.update_date,l.renewable,l.blink,l.send_task")->from($this->table_buttons .' as b')->join($this->table_docket_buttons .' as l','l.button_id = b.id')->where('l.lead_id',$leadID)->order_by('l.sort ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	function findButtonByButtonID($type,$buttonID){
		$query = $this->db->select('*')->from($this->table_buttons)->where('type',$type)->where('button_id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function findLeadButtonByButtonID($leadID,$buttonID){
		$query = $this->db->select('*')->from($this->table_lead_buttons)->where('lead_id',$leadID)->where('button_id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		/*echo $this->db->last_query();*/
		return $data;
	}
	
	function findLeadButtonByID($leadID,$buttonID){
		$query = $this->db->select('*')->from($this->table_lead_buttons)->where('lead_id',$leadID)->where('id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function findDocketButtonByID($leadID,$buttonID){
		$query = $this->db->select('*')->from($this->table_docket_buttons)->where('lead_id',$leadID)->where('id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function findOriginalButtonByButtonID($buttonID){
		$query = $this->db->select('*')->from($this->table_buttons)->where('id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function findButtonID($buttonID,$leadID){
		$query = $this->db->select('*')->from($this->table_lead_buttons)->where('lead_id',$leadID)->where('id',$buttonID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	public function updateButton($data,$id)
    {
        $this->db->where('id',$id)->update($this->table_lead_buttons,$data);
		return $this->db->affected_rows();
    }
	
	public function updateButtonBID($data,$id)
    {
        $this->db->where('button_id',$id)->update($this->table_lead_buttons,$data);
		return $this->db->affected_rows();
    }
	
	function updateDocketButtonBID($data,$id){
		$this->db->where('button_id',$id)->update($this->table_docket_buttons,$data);		
		return $this->db->affected_rows();
	}
	
	public function insertPreContacts($data){
		 $this->db->insert($this->table_precontacts,$data);
		return $this->db->insert_id();
	}
	
	public function insertPreCompanies($data){
		 $this->db->insert($this->table_precompanies,$data);
		return $this->db->insert_id();
	}
	
	public function updatePreContacts($data,$id){
		$this->db->where('id',$id)->update($this->table_precontacts,$data);
		return $this->db->affected_rows();
	}
	
	public function updatePreContactByFullContact($id,$data){
		$this->db->where('fullcontact',$id)->update($this->table_precontacts,$data);
		return $this->db->affected_rows();
	}
	
	public function updateContactByFullContact($id,$data){
		$this->db->where('fullcontact',$id)->update($this->table_contacts,$data);
		return $this->db->affected_rows();
	}
	public function updateContact($data,$id){
		$this->db->where('id',$id)->update($this->table_contacts,$data);
		return $this->db->affected_rows();
	}
	
	public function insertLeadButton($data){
       $this->db->insert($this->table_lead_buttons,$data);
       return $this->db->insert_id();
    }
	
	public function insertDocketButton($data){
       $this->db->insert($this->table_docket_buttons,$data);
       return $this->db->insert_id();
    }
	
	public function updateDocketButton($data,$id)
    {
        $this->db->where('id',$id)->update($this->table_docket_buttons,$data);
		
		return $this->db->affected_rows();
    }
	
	function checkStage($leadID){
		$query = $this->db->select('*')->from($this->table_stage)->where('lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function checkLevel($leadID){
		$query = $this->db->select('*')->from($this->table_level)->where('lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function checkLeadReport($leadID){
		$query = $this->db->select('*')->from($this->table_report)->where('lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function getAcquisitionData($leadID){
		$query = $this->db->select('a.*')->from($this->table_acquisition.' as a')->where('a.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	function getPersonCompanyDetailFromAcquisitionActivityLogByEmailID($emailID){
		$query = $this->db->select('contact_id,company_id')->from($this->table_acquisition_activity)->where('email_id',$emailID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function getPersonCompanyDetailFromSalesActivityLogByEmailID($emailID){
		$query = $this->db->select('contact_id,company_id')->from($this->table_sales_activity)->where('email_id',$emailID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function getPersonCompanyDetailFromPreSalesActivityLogByEmailID($emailID){
		$query = $this->db->select('contact_id,company_id')->from($this->table_presales_activity)->where('email_id',$emailID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();      
        }
		return $data;
	}
	
	function findAllDocket(){
		$data = array();
		$query = $this->db->select('DISTINCT(a.lead_id)')->from($this->table_acquisition.' as a')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getPreContacts(){
		$data = array();
		$query = $this->db->select('*')->from($this->table_precontacts)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getPreCompanies(){
		$data = array();
		$query = $this->db->select('*')->from($this->table_precompanies)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getLitigationScrap(){
		$data = array();
		$query = $this->db->select('*')->from($this->table_litigation_scrap)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getFreePreContacts(){
		$data = array();
		$query = $this->db->select('*')->from($this->table_free_precontacts)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getAllTaskFromLeadCount($leadID){
		$query = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id')->join($this->table.' as u','u.id=a.user_id')->join($this->table.' as uu','uu.id=a.from_user_id')->where('lead_id',$leadID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->where('a.user_id',$this->session->userdata['id'])->order_by('a.id','DESC')->get()->row();
		return $query;
	}
	
	function getAllTaskMeFromLead($leadID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.id,l.lead_name,l.type,l.create_date,uu.name as userName, u.name as uuserName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id')->join($this->table.' as u','u.id=a.user_id')->join($this->table.' as uu','uu.id=a.from_user_id')->where('lead_id',$leadID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->where('a.user_id',$this->session->userdata['id'])->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	
	function getAllTaskFromLead($leadID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.id,l.lead_name,l.type,l.create_date,uu.name as userName, u.name as uuserName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id')->join($this->table.' as u','u.id=a.user_id')->join($this->table.' as uu','uu.id=a.from_user_id')->where('lead_id',$leadID)->where('a.status <> 2')->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	
	function deletePreContact($id){
		$this->db->where('id',$id);
		$this->db->delete($this->table_precontacts);
		return $this->db->affected_rows();
	}
	
	function deletePreCompany($id){
		$this->db->where('id',$id);
		$this->db->delete($this->table_precompanies);
		return $this->db->affected_rows();
	}
	
	function deleteApprovalRequest($leadID){
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_requests);
	}
	
	function deleteHistory($leadID){
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_history);
	}
	
	function deleteLeadButtons($leadID){
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_lead_buttons);
	}
	
	function deleteSalesActivity($emailID){
		$this->db->where('email_id',$emailID);
		$this->db->delete($this->table_sales_activity);
	}
	
	function deleteAcquisitionActivity($emailID){
		$this->db->where('email_id',$emailID);
		$this->db->delete($this->table_acquisition_activity);
	}
	
	function getAllUserHistory($userID,$leadID=0,$opportunity_id=0){
		$query = "";	
		if($leadID==0 && $opportunity_id==0){	
			$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->join($this->table.' as u','u.id = h.user_id')->where('h.user_id',$userID)->order_by('h.id','DESC')->get();
		} else if($leadID>0 && $opportunity_id==0){	
			$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table.' as u','u.id = h.user_id')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.lead_id',$leadID)->order_by('h.id','DESC')->get();
		} else {
			$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->join($this->table_assign_lead.' aa a ', 'a.lead_id=h.lead_id')->join($this->table.' as u','u.id = h.user_id')->where('l.status','2')->where('h.lead_id',$leadID)->order_by('h.id','DESC')->get();
		}	
		$data = array();	
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		}
		return $data;
	}
	public function getLitigationComments($litigationID,$type){
		$query = $this->db->select('c.*,u.name,u.email')->from('other_comments as c')->where('c.parent_id',$litigationID)->where('c.type',$type)->join('users as u','u.id=c.user_id','left outer')->order_by('c.id','DESC')->get();
		
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function from_litigation_comment($data){
		// Inserting in Table(Litigation) 
		$this->db->insert($this->table_comment, $data);
		return $this->db->insert_id();
	}
	
	public function updateLeadStatus($data){
		$this->db->where('id', $data['id']);
		unset($data['id']);
		$this->db->update($this->table_lead,$data);	
		return $this->db->affected_rows();
	}
	
	public function from_litigation_update_comment($data){
        $id = $data['id'];
		unset($data['id']);
		$this->db->where('id', $id);		
		$this->db->update($this->table_comment,$data);	
		return $id;	
	}
	
	function userLeadTeamNote($leadID,$userID){
		$data = array();
		$query = $this->db->select('*')->from($this->table_comment)->where('parent_id',$leadID)->where('user_id',$userID)->get();
		if($query->num_rows()>0){
			$data = $query->first_row();
		}
		return $data;
	}
	
	public function from_litigation_update_comment_by_lead($leadID,$data){
		$this->db->where('parent_id', $leadID);		
		$this->db->update($this->table_comment,$data);	
		return $this->db->affected_rows();	
	}
	
	public function findLeadByType($leadType){
		$query = $this->db->select('l.*')->from($this->table_lead .' as l')->where('l.type',$leadType)->get();       
		$data = array();
		if ($query->num_rows() > 0) {
           foreach($query->result() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function getLeadData($leadID){
		$query = $this->db->select('l.*,u.name')->from($this->table_lead .' as l')->join('users as u', 'u.id = l.user_id')->where('l.id',$leadID)->get();
       
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	public function findSerialNumber($serialNumber){
		$query = $this->db->select('l.*')->from('litigations as l')->where('l.serial_number',trim($serialNumber))->get();
		
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	public function findPortfolioWithSerial($serialNumber){
		$query = $this->db->select('a.*,l.lead_name,l.serial_number')->from($this->table_acquisition.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->where('l.serial_number',trim($serialNumber))->get();		
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	public function findLeadByName($leadName){
		$query = $this->db->select('l.*')->from($this->table_lead .' as l')->where('trim(l.lead_name)',trim($leadName))->get();
        $data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	
	public function checkDataFromSameOwnerToday($ownerName,$date,$type){
		$query = $this->db->select('count(*) as portfolio')->from($this->table_lead)->where('plantiffs_name',$ownerName)->where('type',$type)->where('date_format(create_date,"%Y-%m-%d")',$date)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	public function getUserById($ID){
		 $query = $this->db->select('u.*')->from($this->table.' as u')->where('u.id',$ID)->get();
		 $data = array();
		 if($query->num_rows()>0){
			 $data = $query->first_row();
		 }
		 return $data;
	}
	
    public function getUserPageAssign($url){
		$user_session_id = $this->session->userdata('id');
		$where = "( p.page_url = '$url')";
        $query = $this->db->select('u.*')->from('pages as p')->join('user_page_access_level as up', 'up.page_id = p.id')->join('users as u', 'u.id = up.user_id')->where($where)->get();
		$data=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}
		/*Find Admin Users*/
		$query = $this->db->select('*')->from('users')->where('type','9')->get();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}
		return $data;
	}
}
?>