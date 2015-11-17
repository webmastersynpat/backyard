<?php
class email_model extends CI_Model{
	public $table = "email_templates";
	function __construct() {
		parent::__construct();
	}
	function getTemplate(){
		$getData = $this->db->get($this->table);
		$templateData = array();
		if($getData->num_rows() > 0){
			$templateData = $getData->result();
		}
		return $templateData;
	}
}
?>