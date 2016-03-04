<?php
class user_model extends CI_Model{	
	public $table = 'users';	
	public $table_history = 'history';
	public $table_user_page_level = 'user_page_access_level';
	public $table_assign_lead = 'assign_leads';	
	public $table_assign_lead_type = 'assign_lead_type';	
	public $table_lead = 'litigations';
	public $table_asign_module = 'assign_modules';
	public $table_module = 'modules';
	public $table_error = 'error_notify';
	public $table_log_time = 'user_logtime';
	public $table_contact_log = 'user_contact_log';
	public $table_due_logtime = 'due_logtime';
	public $table_signature = 'signature';
	
	public function __construct() {
		parent::__construct();
	}
	function addUserHistory($data){
		if($data['lead_id']>0){
			$checkLeadLogin = $this->db->select('*')->from($this->table_log_time)->where('lead_id',$data['lead_id'])->where('user_id',$data['user_id'])->order_by('id','desc')->get()->row();
			if(count($checkLeadLogin)>0 && strtotime($checkLeadLogin->login_date)>strtotime($data['create_date'])){
				$data['create_date'] = date('Y-m-d H:i:s',strtotime('1 seconds',strtotime($checkLeadLogin->login_date)));
			}
		}
		$this->db->insert($this->table_history,$data);
		return $this->db->insert_id();
	}
	
	function addContactLog($data){
		$this->db->insert($this->table_contact_log,$data);
	   return $this->db->insert_id();
	}
	
	function due_logtime($data){
		$this->db->insert($this->table_due_logtime,$data);
	   return $this->db->insert_id();
	}
	
	function update_due_logtime($data,$id){
		$this->db->where('id', $id);
		$this->db->update($this->table_due_logtime,$data);
	}
	
	function updateSignature($data,$id){
		$this->db->where('id', $id);
		$this->db->update($this->table_signature,$data);
	}
	
	function signature(){
		$data = array();
		$query = $this->db->select('*')->from($this->table_signature." as d")->get();
		if ($query->num_rows() > 0) {  
			$data = $query->first_row(); 
		}
		return $data;
	}
	
	function getDueData($leadID){
		$data = array();
		$query = $this->db->select('d.*')->from($this->table_due_logtime." as d")->where("lead_id",$leadID)->get();
		if ($query->num_rows() > 0) {  
			$data = $query->first_row(); 
		}
		return $data;
	}
	
	function getAllDueDilligenceProject(){
		$data = array();	
		$query = $this->db->select('d.*,l.lead_name,l.id as lead_id')->from($this->table_due_logtime." as d")->join($this->table_lead." as l","l.id = d.lead_id")->order_by("d.id","DESC")->get();
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		} 
		return $data;
	}
	
	function errorNotification($data){
	   $this->db->insert($this->table_error,$data);
	   return $this->db->insert_id();
	}
	
	function getAllBackyardModules(){
		$data = array();	
		$query = $this->db->select('*')->from($this->table_module)->get();
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		} 
		return $data;
	}
	
	public function insertLogTime($data){
		$this->db->insert($this->table_log_time,$data);
		
		return $this->db->insert_id();
	}
	 
	function updateUserData($data,$userID){
		$this->db->where('id',$userID);
		$this->db->update($this->table,$data);
	}
	
	public function updateLogTime($id,$data){
		$getData = $this->db->select("*")->from($this->table_log_time)->where('user_id',$id)->order_by('id','desc')->get()->row();
		$this->db->where('id',$getData->id);
		$this->db->update($this->table_log_time,$data);
		/*
		if(count($getData)>0 && $getData->logout_date=="0000-00-00 00:00:00"){
			$this->db->where('id',$getData->id);
			$this->db->update($this->table_log_time,$data);
		}
		*/
	}
	
	function updateAllLogTime($userID){
		$query = $this->db->select("*")->from($this->table_log_time)->where('user_id',$userID)->where('logout_date','0000-00-00 00:00:00')->where('date_format(login_date,"%Y-%m-%d")<>"'.date('Y-m-d').'"')->get();
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$logoutDate = strtotime('+10 minutes',strtotime($row->login_date));
				$this->db->where('id',$row->id);
				$this->db->update($this->table_log_time,array('logout_date'=>date('Y-m-d H:i:s',$logoutDate)));
				
			}
		}
	}
	
	public function updateLeadLogTime($id,$leadID,$data){
		$getData = $this->db->select("*")->from($this->table_log_time)->where('user_id',$id)->where('lead_id',$leadID)->order_by('id','desc')->get()->row();
		if(count($getData)>0 && $getData->logout_date=="0000-00-00 00:00:00"){
			$this->db->where('id',$getData->id);
			$this->db->update($this->table_log_time,$data);
		} 
	}
	
	public function updateLogTimeById($id,$data){		
		$this->db->where('id',$id);
		$this->db->update($this->table_log_time,$data);
		return $this->db->affected_rows();		
	}
	
	public function checkLastLog($id){
		$getData = $this->db->select("*")->from($this->table_log_time)->where('user_id',$id)->order_by('id','desc')->get()->row();
		if(count($getData)>0 && $getData->logout_date=="0000-00-00 00:00:00"){
			return false;
		} else{
			return true;
		}
	}
	
	
	
	public function checkLastTimeLoglead($leadID,$date,$userID){
		$getData = $this->db->select('*')->from($this->table_log_time)->where('lead_id',$leadID)->where('login_date >=',$date)->where('user_id',$userID)->order_by('id','desc')->get()->row();
		$this->db->last_query();
		return $getData;
	}
	
	public function getLog($id){
		$getData = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('id',$id)->order_by('id','desc')->get()->row();
		return $getData;
	}
	
	function getMyLogTime($userID,$from,$to,$lead,$activityType){
		$allWork = array();	
		/*$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked')->from($this->table_log_time)->where('user_id',$userID)->where('logout_date <> "0000-00-00 00:00:00"')->get();*/
		$this->db->limit(1000);
		if(!empty($from) && !empty($to)){
			if(!empty($lead) && (int) $lead>0){
				$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m-%d")>="'.$from.'" AND date_format(login_date,"%Y-%m-%d")<="'.$to.'"')->where('lead_id',$lead)->order_by('id','DESC')->get();
				/*echo $this->db->last_query();*/
				$queryCurrent = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalRowHours")->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m-%d")>="'.$from.'" AND date_format(login_date,"%Y-%m-%d")<="'.$to.'"')->where('logout_date <> "0000-00-00 00:00:00"')->where('lead_id',$lead)->get();
			} else {
				$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m-%d")>="'.$from.'" AND date_format(login_date,"%Y-%m-%d")<="'.$to.'"')->order_by('id','DESC')->get();
				/*echo $this->db->last_query();*/
				$queryCurrent = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalRowHours")->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m-%d")>="'.$from.'" AND date_format(login_date,"%Y-%m-%d")<="'.$to.'"')->where('logout_date <> "0000-00-00 00:00:00"')->get();
			}
			
		} else {
			if(!empty($lead) && (int) $lead>0){
				$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('user_id',$userID)->where('lead_id',$lead)->order_by('id','DESC')->get();
				$queryCurrent = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalRowHours")->from($this->table_log_time)->where('user_id',$userID)->where('lead_id',$lead)->where('logout_date <> "0000-00-00 00:00:00"')->get();
			} else {
				$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m")>="'.date('Y-m').'"')->order_by('id','DESC')->get();
				$queryCurrent = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalRowHours")->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m")>="'.date('Y-m').'"')->where('logout_date <> "0000-00-00 00:00:00"')->get();
			}
		}
		
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$activitiesData = array();
				if($row->login_date!='0000-00-00 00:00:00' && $row->logout_date !='0000-00-00 00:00:00'){
					$queryAct = $this->db->select('*')->from($this->table_history)->where('date_format(create_date,"%Y-%m-%d %H:%i:%s")>="'.$row->login_date.'" AND date_format(create_date,"%Y-%m-%d %H:%i:%s")<="'.$row->logout_date.'"')->where('user_id',$userID)->order_by('id','DESC')->get();
					if($queryAct->num_rows() > 0){
						foreach ($queryAct->result() as $rowAct) {
							$activitiesData[] = $rowAct;
						}  
					}
				}
				$row->activities_record = $activitiesData;
				$allWork[] = $row; 
			}
		}
		$totalHrsQuery = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalHrsWorked")->from($this->table_log_time)->where('user_id',$userID)->where('logout_date <> "0000-00-00 00:00:00"')->where('date_format(login_date,"%Y-%m")="'.date('Y-m',strtotime('-1 months',strtotime('now'))).'"')->get();
		$totalHours = "";
		$totalThisHours = "";
		if ($queryCurrent->num_rows() > 0) {  
			$totalThisHours = $queryCurrent->first_row();
		}
		if ($totalHrsQuery->num_rows() > 0) {  
			$totalHours = $totalHrsQuery->first_row();
		}
		$totalHrsCurrentQuery = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalHrsWorked")->from($this->table_log_time)->where('user_id',$userID)->where('logout_date <> "0000-00-00 00:00:00"')->where('date_format(login_date,"%Y-%m")="'.date('Y-m').'"')->get();
		$totalHoursCurrent = "";
		if ($totalHrsCurrentQuery->num_rows() > 0) {  
			$totalHoursCurrent = $totalHrsCurrentQuery->first_row();
		}
		$allLeadsWorked = array();
		/*$allLeadsQuery = $this->db->select("DISTINCT(u.lead_id), l.lead_name")->from($this->table_log_time.' as u')->join($this->table_lead.' as l', 'l.id=u.lead_id')->where('u.user_id',$userID)->where('u.lead_id <> 0')->order_by('lead_name','ASC')->get();*/
		
		/*if ($allLeadsQuery->num_rows() > 0) {  
			foreach ($allLeadsQuery->result() as $row) {  
				$allLeadsWorked[] = $row; 
			}
		}*/
		return array('all_work'=>$allWork,"resultHours"=>$totalThisHours,'totalHours'=>$totalHours,'totalHoursCurrent'=>$totalHoursCurrent,'allLeadsWorked'=>$allLeadsWorked);
	}
	
	function getMyLogTimeWithLead($userID,$leadID){
		$allWork = array();	
		$query = $this->db->select('login_date, logout_date, TIMEDIFF(logout_date , login_date ) as hrsWorked, id, actual_hrs, comment,lead_id')->from($this->table_log_time)->where('user_id',$userID)->where('lead_id',$leadID)->order_by('id','DESC')->get();
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$allWork[] = $row; 
			}
		}
		$totalHrsQuery = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalHrsWorked")->from($this->table_log_time)->where('user_id',$userID)->get();
		$totalHours = "";
		if ($totalHrsQuery->num_rows() > 0) {  
			$totalHours = $totalHrsQuery->first_row();
		}
		$totalHrsCurrentQuery = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalHrsWorked")->from($this->table_log_time)->where('user_id',$userID)->where('date_format(login_date,"%Y-%m-%d")>"'.date('Y-m-01').'"')->get();
		$totalHoursCurrent = "";
		if ($totalHrsCurrentQuery->num_rows() > 0) {  
			$totalHoursCurrent = $totalHrsCurrentQuery->first_row();
		}
		$allLeadsQuery = $this->db->select("DISTINCT(u.lead_id), l.lead_name")->from($this->table_log_time.' as u')->join($this->table_lead.' as l', 'l.id=u.lead_id')->where('u.user_id',$userID)->where('u.lead_id <> 0')->order_by('lead_name','ASC')->get();
		$allLeadsWorked = array();
		if ($allLeadsQuery->num_rows() > 0) {  
			foreach ($allLeadsQuery->result() as $row) {  
				$allLeadsWorked[] = $row; 
			}
		}
		return array('all_work'=>$allWork,'totalHours'=>$totalHours,'totalHoursCurrent'=>$totalHoursCurrent,'allLeadsWorked'=>$allLeadsWorked);
	}
	
	function findFullHoursForLead($leadID,$userID){
		$leadsWorked = "";
		$allLeadsQuery = $this->db->select("SEC_TO_TIME(SUM(TIMEDIFF(logout_date , login_date ))) as totalHrsWorked")->from($this->table_log_time.' as u')->where('u.user_id',$userID)->where('u.lead_id',$leadID)->get();
		$allLeadsWorked = array();
		if ($allLeadsQuery->num_rows() > 0) {  
			$leadsData = $allLeadsQuery->first_row(); 
			$leadsWorked =  $leadsData->totalHrsWorked;
		}
		return $leadsWorked; 
	}
	
	function findAllInsiderUser($flag){
		$data = array();	
		$query = $this->db->select('*')->from($this->table)->where('flag',$flag)->get();
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		}
		return $data;
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
	
	function getUserTimeLineWithSearch($userID,$from,$to,$leadID){
		$query = "";	
		if(!empty($from) && !empty($to)){
			if(!empty($leadID) && (int) $leadID>0){
				$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table.' as u','u.id = h.user_id')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.lead_id',$leadID)->where('h.user_id',$userID)->where('date_format(h.create_date,"%Y-%m-%d")>="'.$from.'" AND date_format(h.create_date,"%Y-%m-%d")<="'.$to.'"')->order_by('h.id','DESC')->get();
			} else {
				$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table.' as u','u.id = h.user_id')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.user_id',$userID)->where('date_format(h.create_date,"%Y-%m-%d")>="'.$from.'" AND date_format(h.create_date,"%Y-%m-%d")<="'.$to.'"')->order_by('h.id','DESC')->get();
			}
		} else {
			if(!empty($leadID) && (int) $leadID>0){
				$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table.' as u','u.id = h.user_id')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.lead_id',$leadID)->where('h.user_id',$userID)->order_by('h.id','DESC')->get();
			} else {
				$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table.' as u','u.id = h.user_id')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.user_id',$userID)->order_by('h.id','DESC')->get();
			}
		}
		$data = array();	
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		}
		return $data;
	}
	
	public function change_status($userID,$status){
		$this->db->where('id', $userID);
		if($status==0){
			$status = 1;
		} else {
			$status = 0;
		}
		$this->db->update($this->table,array('status'=>$status));
	}
	
	public function getallactivities(){
		$query = $this->db->select('*')->from($this->table)->order_by('name','ASC')->get();
		$fullData = array();
		if ($query->num_rows() > 0) {			
			foreach($query->result() as $row){
				$user = $row;
				$queryS = $this->db->select("h.*,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType")->from($this->table_history .' as h')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->where('h.user_id',$user->id)->order_by('h.create_date','DESC')->get();
				$history = array();
				if ($queryS->num_rows() > 0) {
					foreach ($queryS->result() as $rows) {
						$history[] = $rows;
					}
				}
				$fullData[] = array("user"=>$user,"history"=>$history);
			}
		}
		/*$query = $this->db->select('h.*,u.name,u.id as userID,u.profile_pic,l.id as leadID,l.lead_name,l.plantiffs_name,l.type as leadType')->from($this->table_history.' as h')->join($this->table_lead.' as l ', 'l.id=h.lead_id')->join($this->table.' as u','u.id = h.user_id')->order_by('h.id','DESC')->get();
		$data = array();	
		if ($query->num_rows() > 0) {  
			foreach ($query->result() as $row) {  
				$data[] = $row; 
			}
		}*/
		return $fullData;
	}
	public function getAllUsers(){
		$query = $this->db->select('*')->from($this->table)->where('type <>','9')->order_by('name','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllUsersIncAdmin(){
		$query = $this->db->select('*')->from($this->table)->order_by('name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getUserData($userID){
		$query = $this->db->select('*')->from($this->table)->where('id',(int)$userID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data =  $query->first_row();             
        }
		return $data;
	}
	
	public function page_level_insert($data){
		$this->db->insert($this->table_user_page_level,$data);
		return $this->db->insert_id();
	}
	
	public function insert_module($data){
		$this->db->insert($this->table_asign_module,$data);
		return $this->db->insert_id();
	}
	
	public function insert_assign_lead_type($data)  {
		$this->db->insert($this->table_assign_lead_type,$data);
		return $this->db->insert_id();
	}
	
	public function insert_assign_lead($data){
		$this->db->insert($this->table_assign_lead,$data);
		return $this->db->insert_id();
	}
	
	public function delete_module($userID){
		$this->db->where('user_id', $userID);
		$this->db->delete($this->table_asign_module);
	}
	
	public function delete_assign_lead($userID){
		$this->db->where('pd_id', $userID);
		$this->db->delete($this->table_assign_lead);
	}
	
	public function delete_assign_lead_type($userID){
		$this->db->where('user_id', $userID);
		$this->db->delete($this->table_assign_lead_type);
	}
	
	public function delete($userID){
		$this->db->where('user_id', $userID);
		$this->db->delete($this->table_user_page_level);
	}
	
	
	
	public function deleteAccPages($pageID){
		$this->db->where('page_id', $pageID);
		$this->db->delete($this->table_user_page_level);
	}
	
	
	public function getUserModuleList($userID){
		$query = $this->db->select('*')->from($this->table_asign_module)->where('user_id',(int)$userID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function findIncompleteANDCompleteList(){
		 $query = $this->db->select('id,lead_name,type,user_id,create_date')->from($this->table_lead)->where("status IN ('0','1','2')")->get();
		$data = array();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getUserLeadList($userID){
		$query = $this->db->select('lead_id')->from($this->table_assign_lead)->where('pd_id',(int)$userID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getUserLeadType($userID){
		$query = $this->db->select('lead_type')->from($this->table_assign_lead_type)->where('user_id',(int)$userID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	
	public function getUserPageList($userID){
		$query = $this->db->select('*')->from($this->table_user_page_level)->where('user_id',(int)$userID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getPageUserList($pageID){
		$query = $this->db->select('*')->from($this->table_user_page_level)->where('page_id',(int)$pageID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function findUserByType($type){
		$query = $this->db->select('*')->from($this->table)->where('type',$type)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data =  $query->first_row();       
        }
		return $data;
	}
	
	public function findAdminUsers(){
		$query = $this->db->select('*')->from($this->table)->where('type','9')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }    
        }
		return $data;
	}
}
?>