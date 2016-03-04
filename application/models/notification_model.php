<?php
class notification_model extends CI_Model{
	public $table = "notifications";
	function __construct() {
		parent::__construct();
	}
	function insert($data){
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
}
?>