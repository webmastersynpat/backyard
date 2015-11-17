<?php
class client_model extends CI_Model{
	public $table = "contacts";
	public $table_invite_sector = "invitees_in_sectors";
	public $table_sector = "sectors";
	public $table_company = 'company';
	public $table_customers = 'customers';
	public $table_company_sector = 'company_sector';
	function __construct() {
		parent::__construct();
	}
	function insert($data){
		// Inserting in Table(Litigation) 
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	
	function update($id,$data){
		$this->db->where('id', $id);
		$this->db->update($this->table,$data);	
		return $this->db->affected_rows();
	}
	
	function getAllClients(){
		$query = $this->db->select('*')->from($this->table)->order_by('name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getInfo($contactID){
		$query = $this->db->select('*')->from($this->table)->where('id',$contactID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();         
        }
		return $data;
	}
	
	function find_contact_by_email($emailID){
		$query = $this->db->select('*')->from($this->table)->where('email',$emailID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();         
        }
		return $data;
	}
	function find_contact_by_linkedin($linkedIN){
		$query = $this->db->select('*')->from($this->table)->where('linkedin_url',$linkedIN)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();         
        }
		return $data;
	}
	
	function deleteContact($ID){
		$this->db->delete($this->table,array("id"=>$ID));
		return $this->db->affected_rows();
	}
	
	function deleteCompany($ID){
		$this->db->delete($this->table_company_sector,array("company_id"=>$ID));
		$this->db->delete($this->table_company,array("id"=>$ID));
		return $this->db->affected_rows();
	}
	
	
	function find_contact($ID){
		$query = $this->db->select("c.*,CONCAT((c.first_name),(' '),(c.last_name)) as name")->from($this->table.' as c')->where("id",$ID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();         
        }
		return $data;
	}
	
	function findSectorDataName($sectorName){
		$queryMySend = $this->db->select("*")->from($this->table_sector.' as c')->where('LOWER(c.name)',strtolower(trim($sectorName)))->get()->row();
		return $queryMySend;
	}
	
	function getAllCompaniesWithMem(){
		$query = $this->db->select("co.*,s.id as sectorID, s.name as sectorName, (SELECT COUNT(id) FROM ".$this->table_customers." as cus  WHERE cus.company_id = co.id) as userCount")->from($this->table_company.' as co')->join($this->table_company_sector.' as cs', 'co.id = cs.company_id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->order_by('co.company_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$usersList = $this->getAllContactBelongToCompany($row->id);
				$row->company_users = $usersList;
				$data[] = $row;
            }            
        }		
		return $data;
	}
	
	function getAllContactBelongToCompany($companyID){
		$query = $this->db->select("c.*,CONCAT((c.first_name),(' '),(c.last_name)) as name")->from($this->table.' as c')->where('c.company_id',$companyID)->order_by('c.first_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$data[] = $row;
            }            
        }		
		return $data;
	}
	
	function getAllContacts(){
		$query = $this->db->select("c.*,CONCAT((c.first_name),(' '),(c.last_name)) as name, co.company_name as company_name,s.id as sectorID, s.name as sectorName, co.id as companyID")->from($this->table.' as c')->join($this->table_company.' as co', 'co.id = c.company_id','left')->join($this->table_company_sector.' as cs', 'co.id = cs.company_id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->order_by('c.first_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$data[] = $row;
            }            
        }		
		return $data;
	}
	
	function getAllAutoCompleteContacts(){
		$query = $this->db->select("c.id,CONCAT((c.first_name),(' '),(c.last_name),(': '),(co.company_name)) as label, c.email as `value`")->from($this->table.' as c')->join($this->table_company.' as co', 'co.id = c.company_id')->join($this->table_company_sector.' as cs', 'co.id = cs.company_id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->order_by('c.first_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$data[] = $row;
            }            
        }		
		return $data;
	}
	
	function getAllContactsWithSectors(){
		$query = $this->db->select('*')->from($this->table)->order_by('name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$newArray['contact'] = $row;
				$querySector = $this->db->select('s.*')->from($this->table_invite_sector.'  as i')->join($this->table_sector.' as s', 's.id = i.market_id')->where('i.invite_id',$row->id)->get();
                if ($querySector->num_rows() > 0) {
					foreach ($querySector->result() as $sector) {
						$newArray['sector'][] = $sector;
					} 
				} else {
					$newArray['sector']= array();
				}
				$data[] = $newArray;
            }            
        }
		
		return $data;
	}
	
	function getContactListBySectorID($sectorID){
		$query = $this->db->select('c.*')->from($this->table.' as c ')->join($this->table_invite_sector .' as ivs ', 'ivs.invite_id = c.id')->where('ivs.market_id',$sectorID)->order_by('c.name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
				$newArray['contact'] = $row;
				$querySector = $this->db->select('s.*')->from($this->table_invite_sector.'  as i')->join($this->table_sector.' as s', 's.id = i.market_id')->where('i.invite_id',$row->id)->get();
                if ($querySector->num_rows() > 0) {
					foreach ($querySector->result() as $sector) {
						$newArray['sector'][] = $sector;
					} 
				} else {
					$newArray['sector']= array();
				}
				$data[] = $newArray;
            }            
        }		
		return $data;
	}
}
?>