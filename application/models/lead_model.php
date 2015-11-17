<?php
class lead_model extends CI_Model{
	
	public $table_lead = 'litigations';
	public $table_comment = 'other_comments';
	public $table_box = 'box_list';
    public $table_history = 'history';
    public $table = 'users'; 
   	public $table_assign_lead = 'assign_leads';	
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
	public $table_invitees = 'invitees';
	public $table_contacts = 'contacts';
	public $table_company = 'company';
	public $table_sales_activity = 'sales_activity_log_detail';
	public $table_acquisition_company = 'acquisition_company';
	public $table_acquisition_activity = 'acquisition_activity_log_detail';
	public $table_lead_template = 'lead_template_files';
	public $table_precontacts = 'pre_contacts';
	public function __construct() {
		parent::__construct();		
		
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
	
	public function getMessageTaskList($userID,$leadID=0){
		if($leadID==0){
			$queryMySend = $this->db->select("DISTINCT(task_id) as taskID")->from($this->table_task_conversation.' as c')->where('c.from_u',$userID)->get();
		} else {
			$queryMySend = $this->db->select("DISTINCT(task_id) as taskID")->from($this->table_task_conversation.' as c')->join($this->table_requests.' as r','r.id = c.task_id')->where('c.from_u',$userID)->where('r.lead_id',$leadID)->get();
		}
		$sendMessage = array();
		$countSendUnread = 0;
		$receiveMessage = array();
		$countReceieveUnread = 0;
		if(count($queryMySend->num_rows())>0){
			foreach($queryMySend->result() as $task){
				$queryTask = $this->db->select('c.from_u as sentUser,c.task_id as taskID,c.create_c,r.*,l.type as leadType,l.lead_name,u.name as fromUserName,u1.name as userName,u1.profile_pic')->from($this->table_task_conversation.' as c')->join($this->table_requests.' as r','r.id = c.task_id')->join($this->table_lead.' as l','l.id = r.lead_id')->join('users as u','u.id=r.from_user_id')->join('users as u1','u1.id=r.user_id')->where('c.task_id',$task->taskID)->limit(1,0)->order_by('c.id','DESC')->get();
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
					$queryTask = $this->db->select('f.user_id as receiveUser,f.task_id as taskID,c.create_c,f.status as unRead,r.*,l.type as leadType,l.lead_name,u.name as fromUserName,u1.name as userName,u1.profile_pic')->from($this->table_task_flag.' as f')->join($this->table_task_conversation.' as c', 'f.message_id= c.id')->join($this->table_requests.' as r','r.id = f.task_id')->join($this->table_lead.' as l','l.id = r.lead_id')->join('users as u','u.id=r.from_user_id')->join('users as u1','u1.id=r.user_id')->where('f.message_id',$currentMessage->messageID)->order_by('f.flag_id','DESC')->get();
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
		if($flag==true && $leadID==0){
			$taskList = $this->waitingCalApproval($userID);
			$taskCount = $this->waitingApprovalCount($userID);
		} else {			
			$taskList = $this->getAllTaskFromLead($leadID);
			$taskCount = $this->getAllTaskFromLeadCount($leadID);
		}		
		$returnArray = $this->getMessageTaskList($userID,$leadID);
		$returnArray['taskList'] = $taskList;
		$returnArray['taskCount'] = $taskCount->countNotify;		
		return $returnArray;
	}	
	
	
	function deleteTask($taskID){
		$this->db->where('task_id',$taskID);
		$this->db->delete($this->table_task_flag);
		$this->db->where('task_id',$taskID);
		$this->db->delete($this->table_task_conversation);
		$this->db->where('id',$taskID);
		$this->db->delete($this->table_requests);
	}
	
	function waitingApprovalCount($userID){
		$query = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id','left')->join($this->table.' as u','u.id=a.from_user_id')->where('a.user_id',$userID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get()->row();
		return $query;
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
	
	public function findIncompleteANDCompleteList($type){
		//$query = $this->db->select('*')->from($this->table_lead)->where('type',$type)->get();
        $query = $this->db->select('*')->from($this->table_lead)->where("status IN ('0','1','2')")->get();
		$data = array();
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
	
	public function allLeadsWithActivity($activity){
		/*2=>Acquisition,1=>Sales Activity*/
		$query = $this->db->select('id,lead_name')->from($this->table_lead)->where("status IN ('0','1','2')")->get();
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
	
	public function findIncompleteANDCompleteListAccUser($userID){
		//$query = $this->db->select('*')->from($this->table_lead)->where('type',$type)->get();
        /*$query = $this->db->select('l.*')->from($this->table_lead.' as l')->join($this->table_assign_lead .' as al','al.lead_id = l.id')->where('al.pd_id',$userID)->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->get();*/
        $query = $this->db->select('l.*')->from($this->table_lead.' as l')->where("l.status IN ('0','1','2')")->where('l.type <> "INT"')->get();
		$data = array();
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
		$getPersons = $this->db->select('*')->from($this->table_contacts.' as c')->where("c.email",$email)->get();
		if($getPersons->num_rows()>0){
			$persons = $getPersons->first_row();
		}   
		return $persons;
	}
	
	public function insetSalesActivity($data){
		$this->db->insert($this->table_sales_activity, $data);
		if($this->db->insert_id()>0){
			$this->db->where('contact_id',$data['company_id']);
			$this->db->where('lead_id',$data['lead_id']);
			$this->db->update($this->table_invitees,array('last_activity'=>$data['activity_date']));
		}
		return $this->db->insert_id();
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
	
	function getSalesActivity($leadID){
		$getInviteesCompanies = $this->db->select("c.*")->from($this->table_invitees.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		$companyData = array();		
		if ($getInviteesCompanies->num_rows() > 0) {
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
				if($getActivities->num_rows()>0){
					foreach ($getActivities->result() as $a) {
						$activities[] = $a;
					}
				}
				
				$companyData[]=array("company"=>$row,"people"=>$persons,"activities"=>$activities);
			}
		}
		return $companyData;
	}
	
	function getAcquisitionActivity($leadID){
		$getInviteesCompanies = $this->db->select("c.*")->from($this->table_acquisition_company.' as i')->join($this->table_company.' as c','c.id=i.contact_id')->where("i.lead_id",$leadID)->order_by('i.last_activity DESC')->get();
		$companyData = array();		
		if ($getInviteesCompanies->num_rows() > 0) {
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
				$companyData[]=array("company"=>$row,"people"=>$persons,"activities"=>$activities);
			}
		}
		return $companyData;
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
	
	public function updatePreContacts($data,$id){
		$this->db->where('id',$id)->update($this->table_precontacts,$data);
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
	
	function getAllTaskFromLeadCount($leadID){
		$query = $this->db->select('count(*) as countNotify')->from($this->table_requests.' as a')->join($this->table_lead.' as l','l.id=a.lead_id')->join($this->table.' as u','u.id=a.user_id')->join($this->table.' as uu','uu.id=a.from_user_id')->where('lead_id',$leadID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->where('a.user_id',$this->session->userdata['id'])->order_by('a.id','DESC')->get()->row();
		return $query;
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