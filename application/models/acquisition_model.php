<?php

class acquisition_model extends CI_Model{
	
	public $table_acquisition = "acquisition";
	public $table_acquisition_assigned = "acquisition_assigned";
	public $table_acquisition_technologies = 'acquisition_technologies';
	public $table_leads = 'litigations';
	public $table_category = 'category';
	
	function __construct() {
		parent::__construct();
	}
	
	function insertAcquisition($data){
		$this->db->insert($this->table_acquisition, $data);
		return $this->db->insert_id();
	}
	
	function insertAcquisitionAssigned($data){
		$this->db->insert($this->table_acquisition_assigned, $data);
		return $this->db->insert_id();
	}
	
	function deleteAcqusition($leadID){
		$this->db->where('lead_id',$leadID);
		$this->db->delete($this->table_acquisition);
	}
	
	function insertAcquisitionTechnologies($data){
		$this->db->insert($this->table_acquisition_technologies, $data);
		return $this->db->insert_id();
	}
	
	function updateData($leadID,$data){
		$this->db->where('lead_id',$leadID);
		$this->db->update($this->table_acquisition,$data);
		return $this->db->affected_rows();
	}
	
	function getData($leadID){
		$query = $this->db->select('a.*')->from($this->table_acquisition.' as a')->where('a.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data['acquisition'] = $query->first_row();
			$data['assigned'] = array();
			$data['technologiesData'] = array();			
        }
		return $data;
	}
	
	public function findPortfolios($category){
		$query = $this->db->select('a.*,l.lead_name,l.serial_number,c.name as categoryName')->from($this->table_acquisition.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_category.' as c','c.id=a.category','left')->where('a.category',$category)->get();
		$data = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
                $data[] = $row;
            }
		}
		return $data;
	}
	
	public function getAllPortfoliosWithIDs($docketIDs){
		$getList = array();
		if(count($docketIDs)>0){
			$implodeIDs = implode(',',$docketIDs);
			if(!empty($implodeIDs)){
				$query = $this->db->select('a.*,l.lead_name,l.serial_number,c.name as categoryName')->from($this->table_acquisition.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_category.' as c','c.id=a.category','left')->where('a.id IN ('.$implodeIDs.')')->get();
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$getList[] = $row;
					}
				}
			}
		}
		return $getList;
	}
}
?>