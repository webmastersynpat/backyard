<?php
class opportunity_model extends CI_Model{
	public $table_assign = "assign_leads";
	public $table_user = "users";
	public $table_leads = "litigations";
	public $table_sectors = 'sectors';
	public $table_stage = 'lead_stages';
	public $table_level = 'lead_levels';
	public $table_report = 'lead_reports';
	public $table_share_doc = 'share_docs';
	public $table_doc_list = 'document_lists';
	public $table_technologies = 'technologies';
	public $table_requests = 'approval_requests';
	public $table_eou_data = 'eou_datas';
	public $table_sep_data = 'sep_datas';
	public $table_sep_another_data = 'sep_another_datas';
	public $table_contacts = 'contacts';
	public $table_assets = 'assets';
	public $table_invitees = 'invitees';
	public $table_acquisition_company = 'acquisition_company';
	public $table_acquisition_activity_log_detail = 'acquisition_activity_log_detail';
	public $table_invitees_in_sectors = 'invitees_in_sectors';
	public $table_potential = 'potential_syndicates';
	public $table_commitment = 'commitments';
	public $table_chart_left = 'chart_lefts';
	public $table_chart_middle = 'chart_middles';
	public $table_chart_right = 'chart_rights';
	public $table_comparable = 'comparables';
	public $table_damages = 'damages';
	public $table_sales_activity_log_detail = 'sales_activity_log_detail';
	public $table_presales_activity_log_detail = 'presale_activity_log_detail';
	public $table_presale_broker = 'presale_broker';
	
	
	function __construct() {
		parent::__construct();
	}
	
	
	function myButtonList(){
		return '';
	}
	
	function deletePotential($leadID){
		$this->db->delete($this->table_potential,array('lead_id'=>$leadID));		
	}
	
	function savePotential($data){
		$this->db->insert($this->table_potential,$data);
		return $this->db->insert_id();
	}
	
	function findInviteesCompanies($leadID){
		$query = $this->db->select("*")->from($this->table_sales_activity_company)->where('id IN (SELECT DISTINCT(company_id) FROM '.$this->table_sales_activity_log.' WHERE lead_id='.(int)$leadID.') ')->order_by('company_name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getPotential($leadID){
		$query = $this->db->select("*")->from($this->table_potential)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}

	function saveCommitment($data){
		$this->db->insert($this->table_commitment,$data);	
		return $this->db->insert_id();		
	}	
	
	function getCommitment($leadID){
		$query = $this->db->select("*")->from($this->table_commitment)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	function deleteLeftChart($leadID){
		$this->db->delete($this->table_chart_left,array('lead_id'=>$leadID));		
	}
	function saveChartLeft($data){	
		$this->db->insert($this->table_chart_left,$data);
		return $this->db->insert_id();
	}	
	
	function getChartLeft($leadID){
		$query = $this->db->select("*")->from($this->table_chart_left)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function deleteMiddleChart($leadID){
		$this->db->delete($this->table_chart_middle,array('lead_id'=>$leadID));		
	}
	
	function saveChartMiddle($data){
		$this->db->insert($this->table_chart_middle,$data);
		return $this->db->insert_id();		
	}

	function getChartMiddle($leadID){
		$query = $this->db->select("*")->from($this->table_chart_middle)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function deleteRightChart($leadID){
		$this->db->delete($this->table_chart_right,array('lead_id'=>$leadID));		
	}
	
	function saveChartRight($data){	
		$this->db->insert($this->table_chart_right,$data);	
		return $this->db->insert_id();
	}
	
	function getChartRight($leadID){
		$query = $this->db->select("*")->from($this->table_chart_right)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	
	function deleteComparable($leadID){
		$this->db->delete($this->table_comparable,array('lead_id'=>$leadID));		
	}
	
	function saveComparable($data){	
		$this->db->insert($this->table_comparable,$data);	
		return $this->db->insert_id();
	}
	
	function getComparable($leadID){
		$query = $this->db->select("*")->from($this->table_comparable)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	
	function deleteDamages($leadID){
		$this->db->delete($this->table_damages,array('lead_id'=>$leadID));		
	}
	
	function saveDamage($data){	
		$this->db->insert($this->table_damages,$data);
		return $this->db->insert_id();
	}
	
	function getDamages($leadID){
		$query = $this->db->select("*")->from($this->table_damages)->where("lead_id",$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function insertInvitees($data){
		$this->db->insert($this->table_invitees,$data);
		return $this->db->insert_id();		
	}
	
	function insertAcquisitionCompany($data){
		$this->db->insert($this->table_acquisition_company,$data);
		return $this->db->insert_id();		
	}
	
	function findInvitees($contacts){
		$query = $this->db->select("*")->from($this->table_contacts)->where("id IN(".$contacts.")")->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	function checkAllLeadsByContact($contactID){
		$data = array(); 
		$activity=0;
		$queryContact = $this->db->select("*")->from($this->table_contacts)->where("id",$contactID)->get();
		if ($queryContact->num_rows() > 0) {
            $contact = $queryContact->first_row();
			$query = $this->db->select("distinct(l.id) as id, l.lead_name")->from($this->table_invitees.' as s')->join($this->table_leads.' as l','l.id=s.lead_id')->where('s.contact_id',$contact->company_id)->where('l.status <"3"')->get();
			if ($query->num_rows() > 0) {
				$activity = 1;
				foreach ($query->result() as $row) {	
					$leadIds[] = $row->id;
					$row->activity = 1;
					$data[] = $row;
				}
			}
			$query = $this->db->select("distinct(l.id) as id, l.lead_name")->from($this->table_acquisition_company.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->where('a.contact_id',$contact->company_id)->where('l.status <"3"')->get();
			if ($query->num_rows() > 0) {
				$activity = 2;
				foreach ($query->result() as $row) {
					/*if(!in_array($row->id,$leadIds)){*/
						$row->activity = 2;
						$data[] = $row;
					/*}*/	
				}
			}
		}
		return $data;
	}
	
	function checkAllLeadsFromEmailActivityByID($emailID){
		$data = array();
		$checkAquisitionActivity = $this->db->select('*')->from($this->table_acquisition_activity_log_detail)->where('email_id',$emailID)->get();
		if ($checkAquisitionActivity->num_rows() > 0) {
			$row = $checkAquisitionActivity->first_row();
			$row->activity = 2;
			$data = $row;
		} else {
			$checkSalesActivity = $this->db->select('*')->from($this->table_sales_activity_log_detail)->where('email_id',$emailID)->get();
			if ($checkSalesActivity->num_rows() > 0) {
				$row = $checkSalesActivity->first_row();
				$row->activity = 1;
				$data = $row;
			} else {
				$checkPreSalesActivity = $this->db->select('*')->from($this->table_presales_activity_log_detail)->where('email_id',$emailID)->get();
				if ($checkPreSalesActivity->num_rows() > 0) {
					$row = $checkPreSalesActivity->first_row();
					$row->activity = 3;
					$data = $row;
				}
			}
		}
		return $data;
	}
	
	function checkAllLeadsFromEmailActivity($email){
		$list = array();  
		$activity=0;
		$queryContact = $this->db->select("*")->from($this->table_contacts)->where("email",$email)->get();
		$contact = array();
		$leadIds = array();
		$data = array();
		if ($queryContact->num_rows() > 0) {
            $contact = $queryContact->first_row();
			$query = $this->db->select("distinct(l.id) as id, l.lead_name")->from($this->table_invitees.' as s')->join($this->table_leads.' as l','l.id=s.lead_id')->where('s.contact_id',$contact->company_id)->where('l.status <"3"')->get();
			
			if ($query->num_rows() > 0) {
				$activity = 1;
				foreach ($query->result() as $row) {	
					$leadIds[] = $row->id;
					$row->activity = 1;
					$data[] = $row;
				}
			}
			$query = $this->db->select("distinct(l.id) as id, l.lead_name")->from($this->table_acquisition_company.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->where('a.contact_id',$contact->company_id)->where('l.status <"3"')->get();
			if ($query->num_rows() > 0) {
				$activity = 2;
				foreach ($query->result() as $row) {
					/*if(!in_array($row->id,$leadIds)){*/
						$row->activity = 2;
						$data[] = $row;
					/*}*/	
				}
			}
			$query = $this->db->select("distinct(l.id) as id, l.lead_name")->from($this->table_presale_broker.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->where('a.broker_id',$contact->company_id)->where('l.status <"3"')->get();
			if ($query->num_rows() > 0) {
				$activity = 3;
				foreach ($query->result() as $row) {
					/*if(!in_array($row->id,$leadIds)){*/
						$row->activity = 3;
						$data[] = $row;
					/*}*/	
				}
			}
        }
		return array('list'=>$data,'contact'=>$contact,'activity'=>$activity);
	} 
	
	public function checkCompanyInSales($leadID,$companyID){
		$query = $this->db->select("*")->from($this->table_invitees)->where("lead_id",$leadID)->where('contact_id',$companyID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	public function checkCompanyInAcquisition($leadID,$companyID){
		$query = $this->db->select("*")->from($this->table_acquisition_company)->where("lead_id",$leadID)->where('contact_id',$companyID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();
        }
		return $data;
	}
	
	function deleteInvitees($recordID){
		$this->db->delete($this->table_invitees,array('lead_id'=>$recordID));		
	}
	
	function deleteAcquisitionCompany($recordID){
		$this->db->delete($this->table_acquisition_company,array('lead_id'=>$recordID));		
	}
	
	function deleteInviteesByLeadAndCompany($recordID,$companyID){
		$this->db->delete($this->table_invitees,array('lead_id'=>$recordID,'contact_id'=>$companyID));		
	}
	
	function deleteAcquisitionByLeadAndCompany($recordID,$companyID){
		$this->db->delete($this->table_acquisition_company,array('lead_id'=>$recordID,'contact_id'=>$companyID));	
		return $this->db->affected_rows();
	}
	
	
	function insertInviteesInSector($data){
		$this->db->insert($this->table_invitees_in_sectors,$data);
		return $this->db->insert_id();		
	}
	
	function deleteInviteesInSector($recordID){
		$this->db->delete($this->table_invitees_in_sectors,array('invite_id'=>$recordID));		
	}
	
	
	function waitingApproval($userID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.status as notifyStatus,a.message,a.create_date as taskCreateDate,l.*,u.name as userName, u.type as userType')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_user.' as u','u.id=a.from_user_id')->where('a.user_id',$userID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		/*echo $this->db->last_query();*/
		return $data;
	}
	function myTaskApproval($userID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.status as notifyStatus,a.create_date as taskCreateDate,l.*,u.name as userName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_user.' as u','u.id=a.user_id')->where('a.from_user_id',$userID)->where('a.status <> 2')->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		/*echo $this->db->last_query();*/
		return $data;
	}
	
	
	
	
	
	
	/*
	function waitingApproval($userID,$lead_id=null){
	       if($lead_id > 0){
	           $query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.create_date as taskCreateDate,l.*,uu.name as userName, u.name as uuserName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->join($this->table_user.' as u','u.id=a.user_id')->join($this->table_user.' as uu','uu.id=a.from_user_id')->where('lead_id',$lead_id)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
               
	       
	       }else{
	           $query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.create_date as taskCreateDate,l.*,u.name as userName, u.type as userType,u.profile_pic')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_user.' as u','u.id=a.from_user_id')->where('a.user_id',$userID)->where('a.status',0)->where('date_format(a.execution_date,"%Y-%m-%d")<="'.date("Y-m-d").'"')->order_by('a.id','DESC')->get();
	       }
		
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		
		return $data;
	}
	*/
	function checkApprovalSend($userID){
		$query = $this->db->select('count(*) as sendTask')->from($this->table_requests)->where('user_id',$userID)->where('status',0)->where('date_format(create_date,"%Y-%m-%d")',date('Y-m-d'))->get();
		return $query->row();
	}
	
	function findTask($taskID){
		$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.create_date as receivedData,a.execution_date as executionDate,a.status as notifyStatus,a.email_id as emailID,l.*,u.name as userName, u1.name as toUserName,u.type as userType')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id','left')->join($this->table_user.' as u','u.id=a.from_user_id')->join($this->table_user.' as u1','u1.id=a.user_id')->where('a.id',$taskID)->order_by('a.id','DESC')->get();
		/*echo $this->db->last_query();*/
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row(); 
		   if(count($data)>0 && (int)$data->parent_id>0){
			   $categories = array();
			   $this->findParentsTask($categories,$data->parent_id);
			   $data->parents = $categories;
		   }
        }
		return $data;
	}
	
	function findParentsTask(&$categories,$parentID){
		if((int)$parentID>0){
			$query = $this->db->select('a.doc_url,a.id as approved_id,a.type as approved_type,a.subject, a.user_id as toUserID, a.from_user_id as fromUserID, a.parent_id,a.execution_date,a.completion_date,a.message,a.create_date,l.*,u.name as userName')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->join($this->table_user.' as u','u.id=a.from_user_id')->where('a.id',$parentID)->order_by('a.id','DESC')->get();
			if ($query->num_rows() > 0) {
				$currentData = $query->first_row(); 
				if(count($currentData)>0){
					$categories[] = $currentData;
					$this->findParentsTask($categories,$currentData->parent_id);
				}
			}
		}
	}
	
	function findApprovalRequestNDA($leadID,$status,$type){
		$query = $this->db->select('a.doc_url,a.id as approved_id,l.*')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->where('`a`.`doc_url` <> ""')->where('a.type',$type)->where('a.status',$status)->where('a.lead_id',$leadID)->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row(); 
        }
		return $data;
	}
	
    function chkNdaExecuteBy($lead_id){
        $query = $this->db->select('*')->from($this->table_report)->where('lead_id',$lead_id)->get();
        if($query->num_rows()>0){
            $data=$query->first_row();
        }
        return $data;
    }
	
	function findApprovalRequest($leadID,$status,$type){
		$query = $this->db->select('a.doc_url,a.id as approved_id,l.*')->from($this->table_requests.' as a')->join($this->table_leads.' as l','l.id=a.lead_id')->where('a.type',$type)->where('a.status',$status)->where('a.lead_id',$leadID)->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
           $data = $query->first_row(); 
        }
		return $data;
	}
	
	function shareDoc($contactID,$type,$leadID,$fileID){
		$query = $this->db->select('s.*')->from($this->table_share_doc.' as s')->where('s.contact_id',$contactID)->where('s.type',$type)->where('s.lead_id',$leadID)->where('s.file_id',$fileID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row(); 
        }
		return $data;
	}
	
	function getAllSharedDocsWithID($leadID,$fileID){
		$query = $this->db->select('s.*')->from($this->table_share_doc.' as s')->where('s.file_id',$fileID)->where('s.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getAllSharedDocsWithContactID($contactID,$leadID,$fileID){
		$query = $this->db->select('s.*')->from($this->table_share_doc.' as s')->where('s.file_id',$fileID)->where('s.contact_id',$contactID)->where('s.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getAllSharedDocs($leadID,$type){
		$query = $this->db->select('s.*')->from($this->table_share_doc.' as s')->where('s.type',$type)->where('s.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
	}
	
	function getAllInviteesData($leadID){
		$query = $this->db->select('i.*')->from($this->table_invitees.' as i')->where('i.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
		return $data;
		
	}
	
	function checkApprovalData($approvedID,$userID){
		$query = $this->db->select('a.*')->from($this->table_requests.' as a')->where('a.user_id',$userID)->where('a.id',$approvedID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row(); 
        }
		return $data;
	}
	
	function doc_list($fileType,$opportunityType){
		$query = $this->db->select('*')->from($this->table_doc_list)->where('file_type',$fileType)->where('opportunity_type',$opportunityType)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();           
        }
		return $data;
	}
	
	function insertLevel($data){
		$this->db->insert($this->table_level,$data);
		return $this->db->insert_id();
	}
	
	function updateLevel($id,$data){
		$this->db->where('lead_id', $id);
		$this->db->update($this->table_level,$data);	
		return $this->db->affected_rows();

	}
	
	function insertReport($data){
		$this->db->insert($this->table_report,$data);
		return $this->db->insert_id();
	}
	
	function updateReport($id,$data){
		$this->db->where('lead_id', $id);
		$this->db->update($this->table_report,$data);	
		return $this->db->affected_rows();

	}
	
	function insertShareDoc($data){
		$this->db->insert($this->table_share_doc,$data);
		return $this->db->insert_id();
	}
	
	function sendApprovalRequest($data){
		$data['create_date'] = date('Y-m-d');
		$this->db->insert($this->table_requests,$data);
		return $this->db->insert_id();
	}
	
	function updateApprovalData($id,$data){
		$this->db->where('id', $id);
		$this->db->update($this->table_requests,$data);	
		return $this->db->affected_rows();
	}
	
	function getMyAssignedLeads($userID){
		$query = $this->db->select('a.*,l.*')->from($this->table_leads." as l")->join($this->table_assign.' as a','l.id=a.lead_id')->where('a.pd_id',$userID)->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }			
		return $data;
	}
	
	function assigned_lead_folder_ID($leadID){
		$query = $this->db->select('l.*')->from($this->table_assign." as l")->where('l.lead_id',$leadID)->get();
		if ($query->num_rows() > 0) {
			$getData = $query->first_row();
			return $getData->folder_id;
		} else{
			return false;
		}
	}
	
	function getAllLeads(){
		$query = $this->db->select('l.*,a.*')->from($this->table_leads." as l")->join($this->table_assign.' as a','l.id=a.lead_id')->order_by('a.id','DESC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getLeadData($leadID){
		$query = $this->db->select('l.*')->from($this->table_leads." as l")->where('l.id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            $data = $query->first_row();        
        }
		return $data;
	}
	
	function getAllMarketSectors(){
		$query = $this->db->select('*')->from($this->table_sectors)->order_by('name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getAllTechnologies(){
		$query = $this->db->select('*')->from($this->table_technologies)->order_by('name','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getAllEouData($leadID){
		$query = $this->db->select('e.*,u.name as userName')->from($this->table_eou_data.' as e')->join($this->table_user.' as u','u.id = e.user_id')->where('lead_id',$leadID)->order_by('company','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getAllSepData($leadID){
		$query = $this->db->select('*')->from($this->table_sep_data)->where('lead_id',$leadID)->order_by('standard','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getAllSepAnotherData($leadID){
		$query = $this->db->select('*')->from($this->table_sep_another_data)->where('lead_id',$leadID)->order_by('company','ASC')->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getAllAssets($leadID){
		$query = $this->db->select('*')->from($this->table_assets)->where('lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function getPDUserForLead($leadID){
		$query = $this->db->select('*')->from($this->table_assign)->where('lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
           $data= $query->first_row();         
        }
		return $data;
	}
	
	function getAllUserShareList($leadID){
		$query = $this->db->select('distinct(s.contact_id) as contactID, c.*')->from($this->table_share_doc.' as s')->join($this->table_contacts.' as c','c.id = s.contact_id')->where('s.lead_id',$leadID)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }            
        }
		return $data;
	}
	
	function deleteAssetData($leadID){
		$this->db->delete($this->table_assets,array('lead_id'=>$leadID));		
	}
	
	function insertAssetData($data){
		$this->db->insert($this->table_assets, $data);
		return $this->db->insert_id();
	}
	
	
	function deleteEouData($leadID){
		$this->db->delete($this->table_eou_data,array('lead_id'=>$leadID));		
	}
	
	function insertEouData($data){
		$this->db->insert($this->table_eou_data, $data);
		return $this->db->insert_id();
	}
	
	function deleteSepData($leadID){
		$this->db->delete($this->table_sep_data,array('lead_id'=>$leadID));		
	}
	
	function insertSepData($data){
		$this->db->insert($this->table_sep_data, $data);
		return $this->db->insert_id();
	}
	
	function deleteSepAnotherData($leadID){
		$this->db->delete($this->table_sep_another_data,array('lead_id'=>$leadID));		
	}
	
	function insertSepAnotherData($data){
		$this->db->insert($this->table_sep_another_data, $data);
		return $this->db->insert_id();
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
	
	function updateStage($leadID,$data){
		$this->db->where('lead_id',$leadID);
		$this->db->update($this->table_stage,$data);
		return $this->db->affected_rows();
	}
	
	function insertStage($data){
		$this->db->insert($this->table_stage, $data);
		return $this->db->insert_id();
	}
}
?>