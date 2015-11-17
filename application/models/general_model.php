<?php
class general_model extends CI_Model{
	
	public $table_document = 'document_lists';
	public $table_sector = 'sectors';
	public $table_technology = 'technologies';
	public $table_acquisition = "acquisition";
	public $table_page = 'pages';
	public $table_users = 'users';
	public $table_lead = 'litigations';
	public $table_assign_lead = 'assign_leads';
    public $table_task = 'automate_task';
	public $table_button = "buttons";
	public $table_button_stage = "stages";
	public $table_email_template = "email_template";
	public $table_sector_department = "map_sectors_departments";
	public $table_category = "category";
	public $table_company = "company";
	public $table_transaction = "transaction";
	public function __construct() {
		parent::__construct();
	}
	
	
	public function getTaskAccToType($type){
		$query = $this->db->select('*')->from($this->table_task)->where('task_type',$type)->get();
		$data = array();
		if ($query->num_rows() > 0) {
			$data = $query->first_row();
        }
		return $data;
	}	
	
	public function getEmailTemplates(){
		$query = $this->db->select('*')->from($this->table_email_template)->order_by("id","DESC")->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllTransactions(){
		$query = $this->db->select('t.*, l.lead_name as leadName, co.company_name as companyName')->from($this->table_transaction.' as t')->join($this->table_lead.' as l', 'l.id=t.project_id','left outer')->join($this->table_company.' as co','co.id = t.contact_id')->order_by("t.id","DESC")->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllTransactionByContactID($contactID){
		$data = array();
		$query = $this->db->select('t.project_id as leadID,l.lead_name as leadName, l.serial_number, (select sum(t1.amount) from '.$this->table_transaction.' as t1 where t1.project_id = t.project_id and t1.contact_id='.(int) $contactID.' and t1.amt_type=1 and t1.category_id<>"SynPat") as amt, (select sum(t2.amount) from '.$this->table_transaction.' as t2 where t2.project_id = t.project_id and t2.contact_id='.(int) $contactID.' and t2.amt_type=2 and t2.category_id<>"SynPat") as revenueShare, (select count(*) from '.$this->table_transaction.' as t2 where t2.project_id = t.project_id and t2.contact_id='.(int) $contactID.' and t2.category_id="Seller") as isSeller')->from($this->table_transaction.' as t')->join($this->table_lead.' as l', 'l.id=t.project_id')->where('t.contact_id',$contactID)->where('t.category_id <>"SynPat"')->group_by('t.project_id')->order_by("t.id","DESC")->get(); 
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$acquisition = $this->getAcquisitionData($row->leadID);
				$participant = $this->findParticipant($row->leadID);
				$licensees = $this->findRegularlicensees($row->leadID);
				$validate = $this->findValidateLicense($row->leadID);
				$data[] = array('transaction'=> $row,'acquisition'=>$acquisition,'participant'=>$participant,'licensees'=>$licensees,'validate'=>$validate);
            }            
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
	
	public function assignOpportunity($data){
		$this->db->insert($this->table_assign_lead, $data);
		return $this->db->insert_id();
	}
	
	function updateFolderID($folderID,$id){
		$this->db->where('id', $id);
		$this->db->update($this->table_assign_lead,array('folder_id'=>$folderID));				
	}
	
	function updateFolderIDByLead($folderID,$id){
		$this->db->where('lead_id', $id);
		$this->db->update($this->table_assign_lead,array('folder_id'=>$folderID));				
	}	
	
	public function getAllDocFiles(){
		$query = $this->db->select('*')->from($this->table_document)->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function findParticipant($leadID){
		$query = $this->db->select('*')->from($this->table_transaction)->where('project_id',$leadID)->where('reference_id',0)->where('category_id','Participant')->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function findRegularlicensees($leadID){
		$query = $this->db->select('*')->from($this->table_transaction)->where('project_id',$leadID)->where('category_id','Regular License')->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function findValidateLicense($leadID){
		$query = $this->db->select('*')->from($this->table_transaction)->where('project_id',$leadID)->where('category_id','Late License')->get(); 
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function insertTransaction($data){
		$this->db->insert($this->table_transaction, $data);
		return $this->db->insert_id();
	}
	
	public function insertDoc($data){
		$this->db->insert($this->table_document, $data);
		return $this->db->insert_id();
	}
	public function deleteExistingDoc($where)
    {
        $this->db->delete($this->table_document,$where);
    }
	public function getPagesList(){
		$query = $this->db->select('*')->from($this->table_page)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function checkLeadAssigned($leadID){
	   
		$query = $this->db->select('*')->from($this->table_assign_lead)->where('lead_id',$leadID)->get();
        
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row();          
        }
		return $data;
	}
	
	public function findAllOpportunity($status){
		$query = $this->db->select('*')->from($this->table_lead)->where('status',$status)->order_by('create_date','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllSector(){
		$query = $this->db->get($this->table_sector);
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function updateSector($id,$data){
		$this->db->where('id', $id);
		$this->db->update($this->table_sector,$data);	
		return $id;
	}
	
	public function findSector($sectorID){
		$query = $this->db->select('*')->from($this->table_sector)->where('id',$sectorID)->get();        
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row();          
        }
		return $data;
	}
	
	public function findCategory($categoryID){
		$query = $this->db->select('*')->from($this->table_category)->where('id',$categoryID)->get();        
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row();          
        }
		return $data;
	}
	
	function deleteSectorDepartment($sectorId){
		 $this->db->delete($this->table_sector_department,array("sector_id"=>$sectorId));
	}
	
	function delete_category($id){
		 $this->db->delete($this->table_category,array("id"=>$id));
	}
	
	function delete_template($id){
		 $this->db->delete($this->table_email_template,array("id"=>$id));
	}
	
	
	public function insertSectorDepartment($data){
		$this->db->insert($this->table_sector_department, $data);
		return $this->db->insert_id();
	}
	
	public function insertCategory($data){
		$this->db->insert($this->table_category, $data);
		return $this->db->insert_id();
	}
	
	function updateCategory($id,$data){
		$this->db->where('id', $id);
		$this->db->update($this->table_category,$data);	
		return $id;
	}
	
	public function getSectorDepartments($sectorId){
		$query = $this->db->select('*')->from($this->table_sector_department)->where('sector_id',$sectorId)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllParentCategory(){
		$query = $this->db->select('*')->from($this->table_category)->where('type','0')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllSubCategory(){
		$query = $this->db->select('*')->from($this->table_category)->where('type','1')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getSectorDepartmentsName($sectorId){
		$query = $this->db->select('*')->from($this->table_category)->where('id IN (SELECT category_id FROM '.$this->table_sector_department.' WHERE sector_id='.$sectorId.')')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function getAllTechnology(){
		$query = $this->db->get($this->table_technology);
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	public function from_market_insert($data){
		// Inserting in Table(Litigation) 
		$this->db->insert('markets', $data);
		return $this->db->insert_id();
	}
	
	public function record_count() {
        return $this->db->count_all("litigations");
    }	
	
	public function findAllLitigationWithPaging($limit,$start){
		$this->db->limit($limit, $start);
		$this->db->order_by('id','DESC');
		$query = $this->db->select('l.*')->from('litigations as l')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $allArray = array();
				$allArray['litigation'] = $row;
				$allArray['comment'] = $this->getLitigationComments($row->id,'Litigation');
				$data[] = $allArray;
            }            
        }
		return $data;
	}
	
	public function getLitigationComments($litigationID,$type){
		$query = $this->db->select('c.*,u.name,u.email')->from('other_comments as c')->where('c.parent_id',$litigationID)->where('c.type','Litigation')->join('users as u','u.id=c.user_id','left outer')->order_by('c.id','DESC')->get();
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
		$this->db->insert('other_comments', $data);
		return $this->db->insert_id();
	}
	
	public function get_all_opp(){
		// Select all leads
		$query = $this->db->select('l.*, al.pd_id as userAssigned, al.opp_name as opportunityName')->from($this->table_lead.' as l')->join($this->table_assign_lead.' as al','al.lead_id = l.id')->where('l.status','2')->order_by('l.create_date','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }		return $data;
	}
    public function get_all_leads(){
		// Select all leads
		$query = $this->db->select('l.*, u.name as userName')->from($this->table_lead.' as l')->join($this->table_users.' as u','u.id = l.user_id')->order_by('l.create_date','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
    public function get_lead_by_id($id){
		$query = $this->db->select('l.*, al.pd_id as userAssigned, al.opp_name as opportunityName')->from($this->table_lead.' as l')->join($this->table_assign_lead.' as al','al.lead_id = l.id')->where('l.id',$id)->order_by('l.create_date','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
    
    public function delete_opp($id){        
        $this->db->where('id', $id)->update($this->table_lead,array('status'=>'3'));
    }
    
	
	public function insertSector($data)
    {
       $this->db->insert($this->table_sector,$data);
       return $this->db->insert_id();
    }
	
	public function insertTechnology($data)
    {
       $this->db->insert($this->table_technology,$data);
       return $this->db->insert_id();
    }
	
    public function insertTask($data)
    {
       $this->db->insert($this->table_task,$data);
       return $this->db->insert_id();
    }
    
    public function updateTask($data,$id)
    {
        $this->db->where('id',$id)->update($this->table_task,$data);
		return $this->db->affected_rows();
    }
    
    public function getAllTaskList(){
        $query = $this->db->select('*')->from($this->table_task)->get();
		$data = array();
     //   echo $query->num_rows();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
       // print_r($data);
		return $data;
    }
	
    public function getTask($id){
        $query = $this->db->select('*')->from($this->table_task)->where('id',$id)->get()->row();
		return $query;
    }
	
	
	public function getStage($id){
        $buttonsList = array();
		$stageData = $this->db->select('s.*, b.name as buttonName, b.type,')->from($this->table_button_stage.' as s')->join($this->table_button.' as b','b.id = s.button_id')->where('s.id',$id)->get()->row();
		if(count($stageData)>0){
			$buttonsList = $this->getAllButtonList($stageData->type);
		}
		return array('detail'=>$stageData,'buttons'=>$buttonsList);
    }	
	 
    public function delete_task($id){
		$this->db->delete($this->table_task,array("id"=>$id));
    }	
	
	public function insertButton($data){
       $this->db->insert($this->table_button,$data);
       return $this->db->insert_id();
    }
	
	
	public function insertTemplate($data){
		$this->db->insert($this->table_email_template,$data);
        return $this->db->insert_id();
	}
	public function getAllTemplate(){
		$query = $this->db->select('*')->from($this->table_email_template)->get();
		$data = array();   
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }     
		return $data;
	}
	
	public function insertButtonStage($data){
       $this->db->insert($this->table_button_stage,$data);
       return $this->db->insert_id();
    }
	
	public function updateButtonStage($data,$id){
        $this->db->where('id',$id)->update($this->table_button_stage,$data);
		return $this->db->affected_rows();
    }
    
    public function updateButton($data,$id){
        $this->db->where('id',$id)->update($this->table_button,$data);
		return $this->db->affected_rows();
    }
	
	public function updateTemplate($data,$id){		
		$this->db->where('id',$id)->update($this->table_email_template,$data);
		return $this->db->affected_rows();
	}
	
	public function findListButtonByType($type){
		$query = $this->db->select('sort as orderNo')->from($this->table_button)->where('type',$type)->order_by('sort DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {           
            $data = $query->first_row();                     
        }
		return $data;
	}	
    
	public function getAllTemplates(){
		$query = $this->db->select('*')->from($this->table_email_template)->get();
		$data = array(); 
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getTemplateBYType($type){
		$query = $this->db->select('*')->from($this->table_email_template)->where('type',$type)->get();
		$data = array();
		if ($query->num_rows() > 0) {           
            $data = $query->first_row();                     
        }
		return $data;
	}
	
    public function getAllButtonList($type){
        $query = $this->db->select('*')->from($this->table_button)->where('type',$type)->order_by('sort ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
    }
	public function getAllButtonStageList($type){
        $query = $this->db->select('s.*,b.type')->from($this->table_button_stage .' as s')->join($this->table_button.' as b', 'b.id = s.button_id')->where('b.type',$type)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
    }
	
	
    public function getButton($id){
        $query = $this->db->select('*')->from($this->table_button)->where('id',$id)->get()->row();
		return $query;
    }
	
	 public function getTemplate($id){
        $query = $this->db->select('*')->from($this->table_email_template)->where('id',$id)->get()->row();
		return $query;
    }
	
	
    public function delete_button($id){
		$this->db->delete($this->table_button,array("id"=>$id));
    }
	
	public function delete_button_stages($id){
		$this->db->delete($this->table_button_stage,array("id"=>$id));
    }
	
	public function delete_sector($id){
		$this->db->delete($this->table_sector,array("id"=>$id));
    }
	
	public function delete_technology($id){
		$this->db->delete($this->table_technology,array("id"=>$id));
    }
}
?>