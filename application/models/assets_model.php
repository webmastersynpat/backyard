<?php
class assets_model extends CI_Model{
	
	public $table = 'assets';
	public $table_user_page_level = 'user_page_access_level';
	public function __construct() {
		parent::__construct();
	}
	
	public function getList($lead_id){
		$query = $this->db->select('*')->from($this->table)->where('lead_id =',$lead_id)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	public function getAllList(){
		$query = $this->db->select('*')->from($this->table)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}	
	public function create($data){
		return $this->db->insert($this->table, $data); 
	}
}
	