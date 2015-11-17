<?php
class Customer_model extends CI_Model{
	
	public $table_customer = 'customers';
	public $table_company = 'company';
	public $table_company_sector = 'company_sector';
	public $table_sector = 'sectors';
	public $table = "contacts";
	public $table_category = 'category';
	public $table_preference = 'preferences';
	public $table_wishlist = 'wishlist';
	public $table_customer_request = 'customer_request';
	
	public function checkEmailFromRequest($email){
		$queryMySend = $this->db->select("count(*) as userCount")->from($this->table_customer_request.' as c')->where('c.email',$email)->get()->row();
		return $queryMySend->userCount;
	}
	
	
	public function getCustomerRequest($Id){
		$queryMySend = $this->db->select("*")->from($this->table_customer_request.' as c')->where('c.id',$Id)->get()->row();
		return $queryMySend;
	}
	
	public function deleteCustomerRequest($ID){
		$this->db->delete($this->table_customer_request,array('id'=>$ID));	
		return $this->db->affected_rows();
	}
	public function checkEmail($email, $mode = 1){
		if($mode==1){
			$queryMySend = $this->db->select("count(*) as userCount")->from($this->table_customer.' as c')->where('c.email',$email)->get()->row();
			return $queryMySend->userCount;		
		} else {
			$queryMySend = $this->db->select("*")->from($this->table_customer.' as c')->where('c.email',$email)->get()->row();
			return $queryMySend;	
		}		
	}
	
	public function checkUserWithIDAndEmail($customerID,$email){
		$queryMySend = $this->db->select("*")->from($this->table_customer.' as c')->where('c.email',$email)->where('id',$customerID)->get()->row();
		return $queryMySend;	
	}
	
	public function checkActivationCode($code){
		$queryMySend = $this->db->select("*")->from($this->table_customer.' as c')->where('c.activation_code',$code)->get()->row();
		return $queryMySend;
	}
	
	public function checkCompanyExist($companyName){
		$queryMySend = $this->db->select("count(*) as companyCount")->from($this->table_company.' as c')->where('LOWER(c.company_name)',strtolower(trim($companyName)))->get()->row();
		return $queryMySend->companyCount;		
	}
	
	public function getCompanyData($companyName){
		$queryMySend = $this->db->select("*")->from($this->table_company.' as c')->where('LOWER(c.company_name)',strtolower(trim($companyName)))->get()->row();
		return $queryMySend;		
	}
	
	public function getAllCustomerRequest(){
		$query = $this->db->select("*")->from($this->table_customer_request)->order_by('id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function insertCustomerRequest($data){
		$this->db->insert($this->table_customer_request, $data);
		return $this->db->insert_id();
	}
	
	public function insertCustomer($data){
		$this->db->insert($this->table_customer, $data);
		return $this->db->insert_id();
	}
	
	public function addCompanySector($data){
		$this->db->insert($this->table_company_sector, $data);
		return $this->db->insert_id();
	}
	
	public function updateCompanySectorWithCompanyID($companyID,$data){
		$this->db->where("company_id",$companyID);
		$this->db->update($this->table_company_sector, $data);
		return $this->db->affected_rows();
	}
	
	
	public function insertCustomerPreference($data){
		$this->db->insert($this->table_preference, $data);
		return $this->db->insert_id();
	}
	
	public function deleteCustomerPreference($customerID){
		$this->db->delete($this->table_preference,array('customer_id'=>$customerID));	
		return $this->db->affected_rows();
	}
	
	public function insertCompany($data){
		$this->db->insert($this->table_company, $data);
		return $this->db->insert_id();
	}
	
	public function insertWishlist($data){
		$this->db->insert($this->table_wishlist, $data);
		return $this->db->insert_id();
	}
	
	public function checkWishList($productID,$customerID){
		$queryMySend = $this->db->select("*")->from($this->table_wishlist.' as w')->where('w.customer_id',$customerID)->where('w.portfolio_id',$productID)->get()->row();
		return $queryMySend;
	} 
	
	public function deleteWishlist($ID){
		$this->db->delete($this->table_wishlist,array('id'=>$ID));	
		return $this->db->affected_rows();
	}
	
	public function updateUserInfo($userID,$data){
		$this->db->where('id',$userID);
		$this->db->update($this->table_customer,$data);
		return $this->db->affected_rows();
	}
	
	public function updateCompanyData($companyID,$data){
		$this->db->where('id',$companyID);
		$this->db->update($this->table_company,$data);
		return $this->db->affected_rows();
	}
	
	public function deleteCustomer($customerID){
		$this->db->delete($this->table_customer,array('id'=>$customerID));	
		return $this->db->affected_rows();
	}
	
	public function login($email,$password){
		$query = $this->db->select("c.*,cc.id as company_id, cc.company_name, cc.company_address,cc.telephone,cc.bank_name,cc.bank_account_no,cc.routing_no,cc.membership,cc.start_date,cc.end_date")->from($this->table_customer .' as c')->join($this->table_company.' as cc','c.company_id=cc.id')->where("c.email",$email)->where("c.password",$password)->get();
		$data = array();
		if(count($query->num_rows())>0){
			$data = $query->first_row();
		}
		return $data;
	}
	
	public function checUserDetail($userID){
		$query = $this->db->select("c.*,cc.id as company_id, cc.company_name, cc.company_address,cc.telephone,cc.bank_name,cc.bank_account_no,cc.routing_no,cc.membership,cc.start_date,cc.end_date")->from($this->table_customer .' as c')->join($this->table_company.' as cc','c.company_id=cc.id')->where("c.id",$userID)->get();
		$data = array();
		if(count($query->num_rows())>0){
			$data = $query->first_row();
		}
		return $data;
	}
	
	public function categoryList($categoryParent){
		$query = $this->db->select("*")->from($this->table_category)->where("parent",$categoryParent)->order_by('id','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function categoryListWithMoreThanOne($listCategories){
		$query = $this->db->select("*")->from($this->table_category)->where("parent IN (".$listCategories.")")->order_by('id','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function getCustomerWishListIDs($customerID){
		$query = $this->db->select("portfolio_id")->from($this->table_wishlist)->where("customer_id",$customerID)->order_by('id','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row->portfolio_id;
            }
        }
		return $data; 
	}
	
	public function getUsersList($companyID){
		$query = $this->db->select("*")->from($this->table_customer)->where("company_id",$companyID)->order_by('first_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function findMyPreference($customerID){
		$query = $this->db->select("*")->from($this->table_preference)->where("customer_id",$customerID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function findMyPreferenceWithName($customerID){
		$query = $this->db->select("c.*")->from($this->table_preference.' as p')->join($this->table_category.' as c','c.id = p.preference_id')->where("p.customer_id",$customerID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	public function getCompanyDataByID($companyID){
		$query = $this->db->select("c.*,s.name as sectorName, s.id as sectorID")->from($this->table_company.' as c')->join($this->table_company_sector.' as cs','cs.company_id = c.id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->where('c.id',$companyID)->order_by('c.company_name','ASC')->get();		
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
			$data->companyUsers = $this->getAllContactBelongToCompany($companyID);
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
	
	public function companyList(){
		$query = $this->db->select("c.*,s.name as sectorName, s.id as sectorID")->from($this->table_company.' as c')->join($this->table_company_sector.' as cs','cs.company_id = c.id','left')->join($this->table_sector.' as s', 's.id = cs.sector_id','left')->order_by('c.company_name','ASC')->get();
		
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
	
	public function customersList($companyID){
		if($companyID!=null){
			$query = $this->db->select("*")->from($this->table_customer)->where("company_id",$companyID)->order_by('first_name','ASC')->get();
		} else {
			$query = $this->db->select("*")->from($this->table_customer)->order_by('first_name','ASC')->get();
		}		
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;  
	}
	
	public function getAllCustomerCompanyList(){
		$query = $this->db->select("distinct(c.id) as companyID,c.*")->from($this->table_customer.' as cu')->join($this->table_company.' as c','c.id = cu.company_id')->order_by('company_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data; 
	}
	
	function getUsersByActDeactCompanies($companyID,$status){
		$query = $this->db->select("count(*) as users")->from($this->table_customer)->where("company_id",$companyID)->where("status",$status)->get()->row();
		return $query->users;
	}
}

?>