<?php
class outsource_model extends CI_Model{
	
	public $table = 'outsource_user';
	public $table_project = 'outsource_project';
	public $table_project_data = 'outsource_project_data';
	public $table_project_data_drop_history = 'outsource_project_data_drop_history';
	public $table_project_columns = 'outsource_columns';
	public $table_user_projects = 'outsource_project_users';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function insertProject($data){
		$this->db->insert($this->table_project,$data);
		return $this->db->insert_id();
	}
	
	public function insertProjectData($data){
		$this->db->insert($this->table_project_data,$data);
		return $this->db->insert_id();
	}
	
	public function insertColumns($data){
		$this->db->insert($this->table_project_columns,$data);
		return $this->db->insert_id();
	}
	
	public function insertUserProject($data){
		$this->db->insert($this->table_user_projects,$data);
		return $this->db->insert_id();
	}
	
	function updateProfile($data,$id){
		$this->db->where('id',$id);
		$this->db->update($this->table,$data);
		return $this->db->affected_rows();
	}
	
	function findUserDetails($userID){
		$query = $this->db->select("*")->from($this->table)->where('id',$userID)->get();
		$data = array();
		if($query->num_rows()>0){
			$data = $query->first_row();
		}
		return $data;
	}
	
	public function login($email,$password){
		$query = $this->db->select("*")->from($this->table)->where('email',$email)->where('password',$password)->where('status',0)->get();
		$data = array();
		if($query->num_rows()>0){
			$data = $query->first_row();
		}
		return $data;
	}
	
	public function findUserProjectByUserID($userID){
		$query = $this->db->select("*")->from($this->table_user_projects.' as up')->join($this->table_project.' as p','p.id = up.project_id')->where('up.user_id',$userID)->where('p.status',1)->order_by('p.id','DESC')->get();
		$data = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function checkAllBounceEmailByProjectIDAndUserID($projectID,$userID){
		$queryProjectData = $this->db->select("*")->from($this->table_project_data_drop_history.' as pd')->where('pd.project_id', $projectID)->where('pd.user_id', $userID)->where('pd.pass', 1)->get();
		$data = array();
		if($queryProjectData->num_rows()>0){
			foreach($queryProjectData->result() as $row){
				$data[] = $row;
			}
			/*$this->db->where('project_id',$projectID);
			$this->db->where('user_id',$userID);
			$this->db->update($this->table_project_data_drop_history,array('pass'=>0));*/
		}
		return $data;
	}
	
	public function checkProjectDataWithMessageID($messageID){
		$queryProjectData = $this->db->select("*")->from($this->table_project_data.' as pd')->where('message_id', $messageID)->order_by('pd.row','ASC')->order_by('pd.col','ASC')->get();
		$data = array();
		if($queryProjectData->num_rows()>0){
			$data = $queryProjectData->first_row();
		}		
		return $data;
	}
	
	public function updateFormData($dataArray,$rowID){
		$this->db->where('id',$rowID);
		$this->db->update($this->table_project_data,$dataArray);
		return $this->db->affected_rows();
	}
	
	public function uploadDataToDropHistory($data){
		$this->db->insert($this->table_project_data_drop_history,$data);
		return $this->db->insert_id();
	}
	
	public function findProjectDataWithRowCol($row,$col,$projectID){
		$queryProjectData = $this->db->select("*")->from($this->table_project_data.' as pd')->where('pd.project_id',$projectID)->where('pd.row',$row)->where('pd.col',$col)->where('enter_by', "Free")->order_by('pd.row','ASC')->order_by('pd.col','ASC')->get();
		$data = array();
		if($queryProjectData->num_rows()>0){
			$data = $queryProjectData->first_row();
		}
		return $data;
	}
	
	public function findMyEntryCurrentMonth($userID,$projectID){
		$queryProjectEntry  = $this->db->select('(SELECT count(id) FROM '.$this->table_project_data.' WHERE col=4 and user_id='.(int)$userID.' AND project_id='.(int)$projectID.') as column4,
		(SELECT count(id) FROM '.$this->table_project_data.' WHERE col=5 and user_id='.(int)$userID.' AND project_id='.(int)$projectID.') as column5,
		(SELECT count(id) FROM '.$this->table_project_data.' WHERE col=6 and user_id='.(int)$userID.' AND project_id='.(int)$projectID.') as column6')->from($this->table_project_data.' as p')->where('user_id',$userID)->where('project_id',$projectID)->get();
		$data = array();
		if($queryProjectEntry->num_rows()>0){
			$data = $queryProjectEntry->first_row();
		}
		return $data;
	}
	
	public function findProjectFullDetailWithColumnsEncryptID($encryptProjectID){
		$queryProject  = $this->db->select("*")->from($this->table_project.' as p')->where('md5(id)="'.$encryptProjectID.'"')->get();
		$dataDetail = array('project_details'=>array(),'column_heading'=>array(),'project_data'=>array());
		if($queryProject->num_rows()>0){
			$dataDetail['project_details'] = $queryProject->first_row();
			$queryColumn = $this->db->select("*")->from($this->table_project_columns.' as c')->where('c.project_id',$dataDetail['project_details']->id)->order_by('c.column_num','ASC')->get();
			if($queryColumn->num_rows()>0){
				foreach($queryColumn->result() as $row){
					$dataDetail['column_heading'][] = $row;
				}
			}
			$queryProjectData = $this->db->select("*")->from($this->table_project_data.' as pd')->where('pd.project_id',$dataDetail['project_details']->id)->order_by('pd.row','ASC')->order_by('pd.col','ASC')->get();
			if($queryProjectData->num_rows()>0){
				foreach($queryProjectData->result() as $row){
					$dataDetail['project_data'][] = $row;
				}
			}
		}
		return $dataDetail;
	}
}
	