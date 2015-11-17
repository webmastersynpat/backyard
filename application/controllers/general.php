<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class General extends CI_Controller {	
	function __construct(){
		parent::__construct(); 
		if(!isset($this->session->userdata['type']) || empty($this->session->userdata['email'])){
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']);
			} else {
				redirect('login');
			}
		} else if((int)$this->session->userdata['type']!=9){
			$this->session->set_flashdata('error','You are not authorized user to view this page.');
			redirect('dashboard');
		}
		$this->layout->auto_render=false;
		$this->layout->layout='default';
		$this->load->model('general_model');
		$this->load->model('user_model');
		$this->load->model('lead_model');
		$this->load->model('notification_model');		
		$this->load->model('acquisition_model');		
		$this->load->model('opportunity_model');
		$this->load->model('customer_model');
		$this->load->model('client_model');
	}
	
	function add_sectors(){
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['sector']) && count($_POST['sector'])>0){
				if($_POST['sector']['id']==0){
					$sector = $this->general_model->insertSector($_POST['sector']);
					if($sector>0){
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');	
					} else{
						$this->session->set_flashdata('message','<p class="alert alert-success">Please try after sometime.</p>');
					}
				} else {
					$sector = $this->general_model->updateSector($_POST['sector'],$_POST['sector']['id']);
				}				
			}
		}
		redirect('general/manage_sectors');
	}
	
	function add_category(){
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['category']) && count($_POST['category'])>0){
				if($_POST['category']['id']==0){
					$category = $this->general_model->insertCategory($_POST['category']);
					if($category>0){
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');	
					} else{
						$this->session->set_flashdata('message','<p class="alert alert-success">Please try after sometime.</p>');
					}
				} else {
					$category = $this->general_model->updateCategory($_POST['category'],$_POST['category']['id']);
				}				
			}
		}
		redirect('general/manage_sectors');	
	}
	
	function add_subcategory(){
		if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['subcategory']) && count($_POST['subcategory'])>0){
				if($_POST['subcategory']['id']==0){
					$subcategory = $this->general_model->insertCategory($_POST['subcategory']);
					if($subcategory>0){
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');	
					} else{
						$this->session->set_flashdata('message','<p class="alert alert-success">Please try after sometime.</p>');
					}
				} else {
					$subcategory = $this->general_model->updateCategory($_POST['subcategory'],$_POST['subcategory']['id']);
				}				
			}
		}
		redirect('general/manage_sectors');
	}
	
	function transaction(){
		if(isset($_POST) && count($_POST)>0){
			$data = $this->input->post();
			if(isset($data['transaction']['category_id'])){
				switch($data['transaction']['category_id']){
					case 'Membership':
					break;
					case 'Participant':
						if((int)$data['transaction']['project_id']>0 && !empty($data['transaction']['amount']) && !empty($data['transaction']['payment_date'])){
							foreach($data['transaction']['contact_id'] as $contact){
								$transactionData = array("project_id"=>$data['transaction']['project_id'],"category_id"=>$data['transaction']['category_id'],"contact_id"=>$contact,"amount"=>$data['transaction']['amount'],"amt_type"=>1,"date"=>date("Y-m-d H:i:s"),"payment_date"=>$data['transaction']['payment_date'],"note"=>$data['transaction']['note']);
								$insertData = $this->general_model->insertTransaction($transactionData);
								if($insertData>0){
									$this->session->set_flashdata('message','<p class="alert alert-success">Transaction Added!</p>');	
								} else {
									$this->session->set_flashdata('message','<p class="alert alert-success">System not able to save data</p>');	
								}
							}
						}						
					break;
					case 'Regular License':
					case 'Late License':
						$insertData = 0;
						if((int)$data['transaction']['project_id']>0 && !empty($data['transaction']['amount']) && !empty($data['transaction']['payment_date'])){
							foreach($data['transaction']['contact_id'] as $contact){
								$transactionData = array("project_id"=>$data['transaction']['project_id'],"category_id"=>$data['transaction']['category_id'],"contact_id"=>$contact,"amount"=>$data['transaction']['amount'],"amt_type"=>1,"date"=>date("Y-m-d H:i:s"),"payment_date"=>$data['transaction']['payment_date'],"note"=>$data['transaction']['note']);
								$insertData = $this->general_model->insertTransaction($transactionData);
							}
						}
						if($insertData>0){
							/* Acc to business model 1/3 transfer to SynPat , 1/3 transfer to Seller and 1/3 transfer to all Participants*/
							$amountSplit = $data['transaction']['amount']/3;
							/*SynPat Transaction*/
							$transactionData = array("project_id"=>$data['transaction']['project_id'],"category_id"=>"SynPat","contact_id"=>1,"amount"=>round($amountSplit,2),"amt_type"=>2,"date"=>date("Y-m-d H:i:s"),"payment_date"=>$data['transaction']['payment_date'],"reference_id"=>$insertData,"note"=>$data['transaction']['note']);
							$this->general_model->insertTransaction($transactionData);
							/*Seller Transaction*/
							$findLeadData = $this->lead_model->getLeadData($data['transaction']['project_id']);
							if((int)$findLeadData->plantiffs_name>0){
								$findSeller = $this->customer_model->getCompanyDataByID($findLeadData->plantiffs_name);
								if(count($findSeller)>0){
									$transactionData = array("project_id"=>$data['transaction']['project_id'],"category_id"=>"Seller","contact_id"=>$findSeller->id,"amount"=>round($amountSplit,2),"amt_type"=>2,"date"=>date("Y-m-d H:i:s"),"payment_date"=>$data['transaction']['payment_date'],"reference_id"=>$insertData,"note"=>$data['transaction']['note']);
									$this->general_model->insertTransaction($transactionData);
								}
							}
							/*Participant Transaction*/
							$findAllParticipant = $this->general_model->findParticipant($data['transaction']['project_id']);
							if(count($findAllParticipant)>0){
								$particpantAmount = $amountSplit / count($findAllParticipant);
								foreach($findAllParticipant as $particpant){
									$transactionData = array("project_id"=>$data['transaction']['project_id'],"category_id"=>"Participant","contact_id"=>$particpant->contact_id,"amount"=>round($particpantAmount,2),"amt_type"=>2,"date"=>date("Y-m-d H:i:s"),"payment_date"=>$data['transaction']['payment_date'],"reference_id"=>$insertData,"note"=>$data['transaction']['note']);
									$this->general_model->insertTransaction($transactionData);
								}
							}
							$this->session->set_flashdata('message','<p class="alert alert-success">Transaction Added!</p>');	
						} else {
							$this->session->set_flashdata('message','<p class="alert alert-success">Transaction Added!</p>');
						}
					break;
				}				
			}
			redirect('general/transaction');
		}
		$this->layout->title_for_layout = 'Add transaction';
		$data['leads'] = $this->lead_model->getAllLeads();
		$data['contacts'] = $this->client_model->getAllCompaniesWithMem();
		$data['transactions'] = $this->general_model->getAllTransactions();
		$this->layout->render('general/transaction',$data);
	}
	
	
	function customer_request(){
		$data['customer_request'] = $this->customer_model->getAllCustomerRequest();
		$data['company_list'] = $this->customer_model->companyList();
		$data['sector_list'] = $this->general_model->getAllSector();
		$this->layout->title_for_layout = 'SynPat Customer Request Data';
		$this->layout->render('general/customer_request',$data);
	}
	
	function add_new_customer(){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$data = 0;
			if((int)$getData['customer']['request']>0){
				$getRequest = $this->customer_model->getCustomerRequest($getData['customer']['request']);
				if(count($getRequest)>0){
					unset($getData['customer']['request']);
					$getData['customer']['first_name'] = $getRequest->first_name;
					$getData['customer']['last_name'] = $getRequest->last_name;
					$getData['customer']['email'] = $getRequest->email;
					$getData['customer']['phone_number'] = $getRequest->phone_number;
					$getData['customer']['password'] = $getRequest->password;
					$getData['customer']['status'] = 0;
					$getData['customer']['create_date'] = $getRequest->create_date;
					$data = $this->customer_model->insertCustomer($getData['customer']);
					if($data>0){						
						$getData['customer']['phone'] = $getData['customer']['phone_number'];
						unset($getData['customer']['phone_number']);
						unset($getData['customer']['type']);
						unset($getData['customer']['status']);
						unset($getData['customer']['create_date']);
						unset($getData['customer']['password']);
						$this->client_model->insert($getData['customer']);
						$this->customer_model->deleteCustomerRequest($getRequest->id);
					}
				}
				if($data>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');	
				}else{
					$this->session->set_flashdata('message','<p class="alert alert-success">Server busy,Please try after sometime.</p>');	
				}
				redirect('general/customer_request');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-success">Invalid request!</p>');	
			}		
		}else{
			$this->session->set_flashdata('message','<p class="alert alert-success">Invalid request!</p>');	
			redirect('general/customer_request');
		}		
	}
	
	function forms_data(){
		$this->layout->title_for_layout = 'SynPat Forms Data';
		$this->layout->render('general/forms_data');
	}
	
	function changeUserSend(){
		if(isset($_POST) && count($_POST)>0){
			$chk = $this->input->post('chk');
			$userID = $this->input->post('u');
			$this->user_model->updateUserData(array("send_email"=>$chk),$userID);
		}
		die;
	}
	
	function change_lead_type(){
		$type = $this->input->post('c');
		$leadID = $this->input->post('lead');
		if(!empty($type) && !empty($leadID) && $leadID>0){
			$updateLead = $this->lead_model->from_litigation_update($leadID,array("type"=>$type));
			$updateLead = $this->lead_model->from_litigation_update_comment_by_lead($leadID,array("type"=>$type));
			echo $updateLead;
		} else {
			echo "-1";
		}
	}
	
	function lead_manage_all(){
		$data = $this->input->post();
		if(count($data)>0){
			if(count($data['delete_all'])>0){
				foreach($data['delete_all'] as $lead){
					if((int)$data['delete_flag']==1){
						$checkLeadData = $this->lead_model->getLeadData($lead);
						$status = '3';
						if(count($checkLeadData)>0){							
							if($checkLeadData->status=='3'){
								if(!empty($checkLeadData->folder_id)){
									$this->load->library('DriveServiceHelper');
									$service = new DriveServiceHelper();
									$deleteFiles = $service->deleteFilesFolder($checkLeadData->folder_id);
								}
								$dataDeletion = $this->lead_model->deleteLead($lead);	
								$this->lead_model->deleteApprovalRequest($lead);	
								$this->lead_model->deleteHistory($lead);	
								$this->acquisition_model->deleteAcqusition($lead);	
									
							} else {
								$this->lead_model->from_litigation_update($lead,array("status"=>$status));
							}
						}
					} else if((int)$data['retreive_flag']==1){
						$this->lead_model->from_litigation_update($lead,array("status"=>'0'));
					}
				}
			}
		}
		redirect('general/manage_leads');
	}
	
	function manage_opportunity_type(){
		if(isset($_POST) && count($_POST)>0){
			$opportunityData = $this->input->post();
			$opportunity = $opportunityData['opportunity'];
			$saveID = 0;
            $where = array('file_type'=>'NDA');
			$this->general_model->deleteExistingDoc($where);
			
			$where = array('file_type'=>'TIMESHEET');
			$this->general_model->deleteExistingDoc($where);
            
            $where = array('file_type'=>'PPA');
			$this->general_model->deleteExistingDoc($where);
            
            $where = array('file_type'=>'RTP');
			$this->general_model->deleteExistingDoc($where);

            $where = array('file_type'=>'PLA');
			$this->general_model->deleteExistingDoc($where);

            $where = array('file_type'=>'LTA');
			$this->general_model->deleteExistingDoc($where);
            
            $where = array('file_type'=>'PLTS');
			$this->general_model->deleteExistingDoc($where);
            
            $where = array('file_type'=>'ITP');
			$this->general_model->deleteExistingDoc($where);
                        
			if(isset($opportunity['from_litigation_nda']) && !empty($opportunity['from_litigation_nda'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'NDA','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_nda']));
			}
			if(isset($opportunity['from_market_nda']) && !empty($opportunity['from_market_nda'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'NDA','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_nda']));
			}
			if(isset($opportunity['proactive_general_nda']) && !empty($opportunity['proactive_general_nda'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'NDA','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_nda']));
			}
			if(isset($opportunity['proactive_sep_nda']) && !empty($opportunity['proactive_sep_nda'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'NDA','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_nda']));
			}
			if(isset($opportunity['from_litigation_timesheet']) && !empty($opportunity['from_litigation_timesheet'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'TIMESHEET','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_timesheet']));
			}
			if(isset($opportunity['from_market_timesheet']) && !empty($opportunity['from_market_timesheet'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'TIMESHEET','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_timesheet']));
			}
			if(isset($opportunity['proactive_general_timesheet']) && !empty($opportunity['proactive_general_timesheet'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'TIMESHEET','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_timesheet']));
			}
			if(isset($opportunity['proactive_sep_timesheet']) && !empty($opportunity['proactive_sep_timesheet'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'TIMESHEET','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_timesheet']));
			}
			if(isset($opportunity['from_litigation_ppa']) && !empty($opportunity['from_litigation_ppa'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PPA','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_ppa']));
			}
			if(isset($opportunity['from_market_ppa']) && !empty($opportunity['from_market_ppa'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PPA','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_ppa']));
			}
			if(isset($opportunity['proactive_general_ppa']) && !empty($opportunity['proactive_general_ppa'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PPA','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_ppa']));
			}
			if(isset($opportunity['proactive_sep_ppa']) && !empty($opportunity['proactive_sep_ppa'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PPA','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_ppa']));
			}
			if(isset($opportunity['from_litigation_rtp']) && !empty($opportunity['from_litigation_rtp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'RTP','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_rtp']));
			}
			if(isset($opportunity['from_market_rtp']) && !empty($opportunity['from_market_rtp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'RTP','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_rtp']));
			}
			if(isset($opportunity['proactive_general_rtp']) && !empty($opportunity['proactive_general_rtp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'RTP','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_rtp']));
			}
			if(isset($opportunity['proactive_sep_rtp']) && !empty($opportunity['proactive_sep_rtp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'RTP','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_rtp']));
			}
			if(isset($opportunity['from_litigation_pla']) && !empty($opportunity['from_litigation_pla'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLA','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_pla']));
			}
			if(isset($opportunity['from_market_pla']) && !empty($opportunity['from_market_pla'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLA','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_pla']));
			}
			if(isset($opportunity['proactive_general_pla']) && !empty($opportunity['proactive_general_pla'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLA','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_pla']));
			}
			if(isset($opportunity['proactive_sep_pla']) && !empty($opportunity['proactive_sep_pla'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLA','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_pla']));
			}
			if(isset($opportunity['from_litigation_lta']) && !empty($opportunity['from_litigation_lta'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'LTA','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_lta']));
			}
			if(isset($opportunity['from_market_lta']) && !empty($opportunity['from_market_lta'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'LTA','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_lta']));
			}
			if(isset($opportunity['proactive_general_lta']) && !empty($opportunity['proactive_general_lta'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'LTA','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_lta']));
			}
			if(isset($opportunity['proactive_sep_lta']) && !empty($opportunity['proactive_sep_lta'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'LTA','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_lta']));
			}            
            if(isset($opportunity['from_litigation_plts']) && !empty($opportunity['from_litigation_plts'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLTS','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_plts']));
			}
            if(isset($opportunity['from_market_plts']) && !empty($opportunity['from_market_plts'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLTS','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_plts']));
			}
            if(isset($opportunity['proactive_general_plts']) && !empty($opportunity['proactive_general_plts'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLTS','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_plts']));
			}
            if(isset($opportunity['proactive_sep_plts']) && !empty($opportunity['proactive_sep_plts'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'PLTS','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_plts']));
			}            
            if(isset($opportunity['from_litigation_itp']) && !empty($opportunity['from_litigation_itp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'ITP','opportunity_type'=>'Litigation','doc_id'=>$opportunity['from_litigation_itp']));
			}
            if(isset($opportunity['from_market_itp']) && !empty($opportunity['from_market_itp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'ITP','opportunity_type'=>'Market','doc_id'=>$opportunity['from_market_itp']));
			}
            if(isset($opportunity['proactive_general_itp']) && !empty($opportunity['proactive_general_itp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'ITP','opportunity_type'=>'General','doc_id'=>$opportunity['proactive_general_itp']));
			}            
            if(isset($opportunity['proactive_sep_itp']) && !empty($opportunity['proactive_sep_itp'])){
				$saveID = $this->general_model->insertDoc(array('file_type'=>'ITP','opportunity_type'=>'SEP','doc_id'=>$opportunity['proactive_sep_itp']));
			}            
			if($saveID>0){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');					
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
			}
			redirect('general/manage_opportunity_type');			
		}		
		$this->layout->title_for_layout = 'Backyard Manage Opportunity';
		/*Load Google Drive Library
		  Get list of all documents in the Master Document
		*/
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();
		$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);
		if($parentFolderID){
			$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');
			if($getFolderID){
				$opportunitesData = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');
				if($opportunitesData){
					$data['master_list'] = $service->getFileIDFromChildern($opportunitesData->id);
				}
			}
		} else {
			$data['master_list'] = $service->getAllFiles();
		}	
		$data['doc_list'] = $this->general_model->getAllDocFiles();
		$this->layout->render('general/manage_opportunity_type',$data);
	}

	public function user_permissions(){
		if(isset($_POST) && count($_POST)>0){
			$pagesData = $this->input->post('page');
			/*Delete User Old Page Level*/
			$this->user_model->delete_module($pagesData['user_id']);
			$this->user_model->delete_assign_lead($pagesData['user_id']);
			/*End Delete*/
			$saveID = 0;
			foreach($pagesData['lead_id'] as $page){
				$saveID = $this->user_model->insert_assign_lead(array('pd_id'=>$pagesData['user_id'],'lead_id'=>$page));
			}
			foreach($pagesData['module_id'] as $page){
				$saveID = $this->user_model->insert_module(array('user_id'=>$pagesData['user_id'],'module_id'=>$page));
			}
			if($saveID>0){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');					
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
			}
			redirect('general/user_permissions');			
		}
		$this->layout->title_for_layout = 'Backyard User Permission';
		$data = array();
		$data['users'] = $this->user_model->getAllUsers();
		$data['pages_list'] = $this->general_model->getPagesList();
		$data['leads'] = $this->user_model->findIncompleteANDCompleteList();		
		$this->layout->render('general/user_permissions',$data);
	}

	public function useractivities(){
		$data['user']= $this->user_model->getallactivities();
		$this->layout->title_for_layout = 'Backyard User Activities';
		$this->layout->render('general/user_activities',$data);		
	}

	public function user_permissions_page(){
		if(isset($_POST) && count($_POST)>0){
			$pagesData = $this->input->post('page');
			/*Delete User Old Page Level*/
			$this->user_model->deleteAccPages($pagesData['page_id']);
			/*End Delete*/
			$saveID = 0;
			foreach($pagesData['user_id'] as $user){
				$saveID = $this->user_model->page_level_insert(array('page_id'=>$pagesData['page_id'],'user_id'=>$user));
			}
			if($saveID>0){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');					
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
			}
			redirect('general/user_permissions');			
		}
		$this->layout->title_for_layout = 'Backyard User Permission';
		$data = array();
		$data['users'] = $this->user_model->getAllUsers();
		$data['pages_list'] = $this->general_model->getPagesList();
		
		$this->layout->render('general/user_permissions',$data);
	}	

	public function add_user(){
		if(isset($_POST) && count($_POST)>0){
			$userData = $this->input->post();
			$createUser = $this->simpleloginsecure->create($userData['user']['name'],$userData['user']['email'],$userData['user']['password'],$userData['user']['phone_number'],$userData['user']['type'],false);
			if($createUser===true){
				$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
			}
			redirect('general/add_user');			
		}
		$data['user_list'] = $this->user_model->getAllUsersIncAdmin();
		$this->layout->title_for_layout = 'Backyard Add User';
		$this->layout->render('general/add_user',$data);
	}

	public function manage_leads(){
		$user_id = $this->session->userdata['id'];
		$data['lead_list'] = $this->general_model->get_all_leads();
		$data['users'] = $this->user_model->getAllUsers();
		$this->layout->title_for_layout = 'Manage Leads';
		$this->layout->render('general/manage_leads',$data);
	}
	
	public function delete_lead($id){
		$checkLeadData = $this->lead_model->getLeadData($id);
		$status = '3';
		if(count($checkLeadData)>0){
			if($checkLeadData->status=='3'){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$deleteFiles = $service->deleteFilesFolder($checkLeadData->folder_id);
				if($deleteFiles){
					$data = $this->lead_model->deleteLead($id);
				}
			} else {
				$this->lead_model->from_litigation_update($id,array("status"=>$status));
			}
		}
        redirect('general/manage_leads');
	}
	
	public function revive_lead($id){
		$this->lead_model->from_litigation_update($id,array("status"=>'0'));
		redirect('general/manage_leads');
	}
		
    public function delete_opp($id){
        $this->general_model->delete_opp($id);
        redirect('general/create_an_opportunity');
        
    }
	
	public function view_lead($id){
        $data['lead_list'] = $this->general_model->get_lead_by_id($id);
        $this->layout->title_for_layout = 'View Leads';
		$this->layout->render('general/view_lead',$data);
    }

	public function create_an_opportunity(){
		if(isset($_POST) && count($_POST)>0){
			$createOpportunity = $this->input->post();
			/*Check Lead Already Assigned*/
			$checkData =$this->general_model->checkLeadAssigned($createOpportunity['opportunity']['lead_id']);
			/*End Check Lead*/
			if(count($checkData)==0){
				$saveData = $this->general_model->assignOpportunity($createOpportunity['opportunity']);
				$user_history = array('lead_id'=>$createOpportunity['opportunity']['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Create an opportunity from lead",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));				
				$this->user_model->addUserHistory($user_history);
				/*Check DB Message and Subject*/
				$getData = $this->general_model->getTaskAccToType('CREATE_OPPORTUNITY');
				$subject="New Opportunity for you";
				$message = "Work on this new opportunity.";
				if(count($getData)>0){
					$subject = $getData->subject;
					$message = $getData->message;
				}
				/*End Checking*/
				$approval_req = array('subject'=>$subject,'message'=>$message,'execution_date'=>date('Y-m-d'),'lead_id'=>$createOpportunity['opportunity']['lead_id'],'from_user_id'=>$this->session->userdata['id'],'user_id'=>$createOpportunity['opportunity']['pd_id'],'parent_id'=>0,'status'=>0,'type'=>"CREATE_OPPORTUNITY",'doc_url'=>$this->config->base_url()."opportunity/working_opportunity/".$createOpportunity['opportunity']['lead_id']);
				$this->opportunity_model->sendApprovalRequest($approval_req);  
				if($saveData>0){
					/**Change Status of Lead From Open to Active */
					$getLeadData = $this->lead_model->getLeadData($createOpportunity['opportunity']['lead_id']);
					if(count($getLeadData)>0){
						$this->load->library('DriveServiceHelper');
						$this->lead_model->updateLeadStatus(array('id'=>$createOpportunity['opportunity']['lead_id'],'status'=>'2'));
						$this->notification_model->insert(array('user_id'=>$createOpportunity['opportunity']['pd_id'],'message'=>'New Opportunity has been assigned by the Admin.'));
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
					} else {
						$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
					}				
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-danger">Please try after sometime!</p>');
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Lead already assigned!</p>');
			}
			redirect('general/create_an_opportunity');				
		}
		$data['lists'] = $this->general_model->findAllOpportunity('1');		
		$data['lead_list'] = $this->general_model->get_all_opp();
		$data['users'] = $this->user_model->getAllUsers();
		$this->layout->title_for_layout = 'Backyard Create an Opportunity';
		$this->layout->render('general/create_an_opportunity',$data);
	}

	public function manage_sectors($id=0, $type=3){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();			
			/*Add Sector*/
			if($getData['sector']['id']>0){
				$id = $getData['sector']['id'];
			}			
			if($id>0){
				$this->general_model->deleteSectorDepartment($id);
				if(isset($getData['mar']['category_id']) && count($getData['mar']['category_id'])>0){
					foreach($getData['mar']['category_id'] as $category){
						$this->general_model->insertSectorDepartment(array("sector_id"=>$id,"category_id"=>$category));
					}
				}
				$this->session->set_flashdata('message','<p class="alert alert-success">Sector saved.</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Server busy, Please try after sometime.</p>');
			}			
			redirect('general/manage_sectors');		
		} else {
			if ($type==1):
				if($id>0):
					$data['sector_data'] = $this->general_model->findSector($id);
					$data['sector_category'] = $this->general_model->getSectorDepartments($id);
				endif;
			elseif ($type==0):
				if($id>0):
					$data['category_data'] = $this->general_model->findCategory($id);
					$data['cat_category'] = $this->customer_model->categoryList($id);
				endif;
			elseif ($type==2):
				if($id>0):
					$data['subcategory_data'] = $this->general_model->findCategory($id);
				endif;
			endif;
		}
		$data['categories'] = $this->general_model->getAllParentCategory();
		$data['sectors'] = $this->general_model->getAllSector();
		$data['subcategory'] = $this->general_model->getAllSubCategory();	
		/*$data['category'] = $this->general_model->getAllParentCategory();*/
		$this->layout->layout='opportunity';
		$this->layout->title_for_layout = 'Backyard Manage Sector';
		$this->layout->render('general/manage_sectors',$data);
	}
	
	public function getDataFromAjax($id=0,$type){
		$data = array('list'=>array(),'subcategory_data'=>array());
		if($id>0){
			switch($type){
				case 1:
					$data['list'] = $this->general_model->getSectorDepartments($id);
				break;
				case 0:
					$data['list'] = $this->customer_model->categoryList($id);
				break;
				case 2:
					$data['subcategory_data'] = $this->general_model->findCategory($id);
				break;
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function manage_category($id=0){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			if($getData['category']['id']>0){
				$id = $getData['category']['id'];
			}			
			if($id>0){				
				if(isset($getData['mar']['category_id']) && count($getData['mar']['category_id'])>0){
					foreach($getData['mar']['category_id'] as $category){
						$this->general_model->updateCategory($category,array("parent"=>"0"));
					}
					foreach($getData['mar']['category_id'] as $category){
						$this->general_model->updateCategory($category,array("parent"=>$id));
					}
				}
				$this->session->set_flashdata('message','<p class="alert alert-success">Category saved saved.</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Server busy, Please try after sometime.</p>');
			}			
		} 
		redirect('general/manage_sectors');
	}
	
	
	public function manage_technologies(){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$id = $this->general_model->insertTechnology($getData['technology']);
			if($id>0){
				$this->session->set_flashdata('message','<p class="alert alert-success">Technology added.</p>');
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-danger">Server busy, Please try after sometime.</p>');
			}
			redirect('general/manage_technologies');		
		} else {
			$data['technology'] = $this->general_model->getAllTechnology();
			$this->layout->title_for_layout = 'Backyard Manage Technology';
			$this->layout->render('general/manage_technologies',$data);
		}
	}

	public function index(){
	}

	public function userList(){
		$data['user_list'] = $this->user_model->getAllUsers();
		$this->layout->title_for_layout = 'Backyard User List';
		$this->layout->render('general/user_list',$data);
	}

	public function getListOfFiles(){
		$token = $this->input->post('token');
		$data = array();
		if($token && !empty($token)){
			$data = $this->general_model->getListOfFiles($token);
		}
		echo json_encode($data);
		die;
	}	

	public function getUserPageList(){
		$token = $this->input->post('token');
		$data = array();
		if($token && !empty($token)){
			$data = $this->user_model->getUserPageList($token);
		}
		echo json_encode($data);
		die;
	}

	public function getPageUserList(){
		$token = $this->input->post('token');
		$data = array();
		if($token && !empty($token)){
			$data = $this->user_model->getPageUserList($token);
		}
		echo json_encode($data);
		die;
	}
	
	public function getUserModuleLeadList(){
		$token = $this->input->post('token');
		$data = array();
		if($token && !empty($token)){
			$data['module'] = $this->user_model->getUserModuleList($token);
			$data['leads'] = $this->user_model->getUserLeadList($token);
		}
		echo json_encode($data);
		die;
	}
	
    public function assign_opp(){
        $user_id = $_REQUEST['user_id'];
        $data = array('pd_id'=>$user_id);
        $lead_id = $_REQUEST['lead_id'];
        $this->db->where('lead_id',$lead_id)->update('assign_leads',$data);
		echo "Successfully Assigned";
    }
	
	public function email_templates($id=null){
		if(isset($_POST) && count($_POST)>0){           
           $data = $this->input->post();
            if((int)$this->input->post('id')==0){
                /*Insert*/
                $data = $this->input->post();
				
                unset($data['id']);				
                $saveData = $this->general_model->insertTemplate($data['general']);
                if($saveData>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}
                redirect('general/email_templates');
            } else { 
                /*Update*/
                $id = $data['id'];
				
                $update = $this->general_model->updateTemplate($data['general'],$id);
				if($update>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Updated!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}                
                redirect('general/email_templates');
            }
        }  else {
            if($id!=null){
                $data['id'] = $id;
                $data['update_data'] = $this->general_model->getTemplate($id);
            }
        }
        $data['templates'] = $this->general_model->getAllTemplates();
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://appadmin.synpat.com/Users/getLicenseData',
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'license_number' => '12345',
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		
		if($resp){
			$data['acordion_text'] = json_decode($resp);
		} else {
			$data['acordion_text'] = array();
		}
	
       	$this->layout->title_for_layout = 'Backyard Button Boxes';    
    	$this->layout->render('general/email_templates',$data);  
	}
	
	
	
	function accordion(){
		if(isset($_POST) && count($_POST)>0){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/saveAccordionTemplateText',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'license_number' => '12345',
					'portfolio_text'=>$this->input->post('portfolio_text'),
					'syndication_tab'=>$this->input->post('syndication_text'),
					'simulator_text'=>$this->input->post('simulator_text'),
					'document_text'=>$this->input->post('suggestions_text'),
					'due_diligence_tab'=>$this->input->post('due_diligence_text'),
					'claim_chart_tab'=>$this->input->post('claim_chart_text'),
					'impact_tab'=>$this->input->post('impact_text'),					
				)
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);			
		} 
		redirect('general/email_templates');		
	}
	
	public function button_boxes($id=null){
		if(isset($_POST) && count($_POST)>0){           
           $data = $this->input->post();
            if((int)$this->input->post('id')==0){
                /*Insert*/
                unset($data['id']);
				if(isset($data['general']['drive']) && !empty($data['general']['drive'])){
					$data['general']['reference_id'] = $data['general']['drive'];
					unset($data['general']['drive']);
					unset($data['general']['template']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['drive']);
				}
				if(isset($data['general']['template']) && !empty($data['general']['template'])){
					$data['general']['reference_id'] = $data['general']['template'];
					unset($data['general']['template']);
					unset($data['general']['drive']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['template']);
				}
				if(isset($data['general']['message']) && !empty($data['general']['message'])){
					$data['general']['reference_id'] = $data['general']['message'];
					unset($data['general']['template']);
					unset($data['general']['drive']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['message']);
				}
				$orderNumber = $this->general_model->findListButtonByType($data['general']['type']);
				if(count($orderNumber)>0){
					$orderNumber = $orderNumber->orderNo + 1;
				} else {
					$orderNumber = 1;
				}
				$data['general']['sort'] = $orderNumber;
				$saveData = $this->general_model->insertButton($data['general']);
                if($saveData>0){
					/*Get All Lead From Type*/
					if($data['general']['type']=="DOCKET"){
						$findAllDocket = $this->lead_model->findAllDocket();
						if(count($findAllDocket)>0){
							foreach($findAllDocket as $lead){
								$this->lead_model->insertDocketButton(array("lead_id"=>$lead->lead_id,"sort"=>0,"button_id"=>$saveData));
							}
						}
					} else {
						$findList =  $this->lead_model->findLeadByType($data['general']['type']);
						if(count($findList)>0){
							foreach($findList as $lead){
								$this->lead_model->insertLeadButton(array("lead_id"=>$lead->id,"sort"=>0,"button_id"=>$saveData));
							}
						}	
					}  			
					/**/
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}
               redirect('general/button_boxes');
            } else {
                /*Update*/
                $id = $data['id'];
				if(isset($data['general']['drive']) && !empty($data['general']['drive'])){
					$data['general']['reference_id'] = $data['general']['drive'];
					unset($data['general']['drive']);
					unset($data['general']['template']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['drive']);
				}
				if(isset($data['general']['template']) && !empty($data['general']['template'])){
					$data['general']['reference_id'] = $data['general']['template'];
					unset($data['general']['template']);
					unset($data['general']['drive']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['template']);
				}
				if(isset($data['general']['message']) && !empty($data['general']['message'])){
					$data['general']['reference_id'] = $data['general']['message'];
					unset($data['general']['template']);
					unset($data['general']['drive']);
					unset($data['general']['message']);
				} else {
					unset($data['general']['message']);
				}
				if(!isset($data['general']['blink'])){
					$data['general']['blink'] = 0;
				}
				if(!isset($data['general']['send_task'])){
					$data['general']['send_task']=0;
				}
				
				$update = $this->general_model->updateButton($data['general'],$id);
			
				if($update>0){
					/*Get All Lead From Type*/
					$blink = 0;
					$sendTask=0;
					if(isset($data['general']['blink'])){
						$blink = $data['general']['blink'];
					}
					if(isset($data['general']['send_task'])){
						$sendTask = $data['general']['send_task'];
					}
					if($data['general']['type']=="DOCKET"){						
						$this->lead_model->updateDocketButtonBID(array("blink"=>$blink,"send_task"=>$sendTask),$id);
					} else {
						$this->lead_model->updateButtonBID(array("blink"=>$blink,"send_task"=>$sendTask),$id);	
					}  		
				
					/**/
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Updated!</p>');
				} else {
				
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}
				
                redirect('general/button_boxes');
            }
        }  else {
            if($id!=null){
                $data['id'] = $id;
                $data['update_data'] = $this->general_model->getButton($id);
            }
        }
		if($this->input->get('data')=="copy"){
			$data['id'] = 0;			
		}
		/*Find Files From Drive*/
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();
		$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
		if($parentFolderID){
			$data['list'] = $service->getFileIDFromChildern($parentFolderID);	
		} else { 
			$data['list'] = array();
		}
		$data['templates'] = $this->general_model->getEmailTemplates();
		/*End*/
        $data['litigation'] = $this->general_model->getAllButtonList('Litigation');
        $data['market'] = $this->general_model->getAllButtonList('Market');
        $data['proactive'] = $this->general_model->getAllButtonList('General');        
        $data['non'] = $this->general_model->getAllButtonList('NON');        
        $data['int'] = $this->general_model->getAllButtonList('INT');        
        $data['doc'] = $this->general_model->getAllButtonList('DOCKET');        
    	$this->layout->title_for_layout = 'Backyard Button Boxes';    
    	$this->layout->render('general/button_boxes',$data);            
	}
	
	public function findButtonsByType(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$type = $this->input->post('d');
			$data = $this->general_model->getAllButtonList($type);
		}
		echo json_encode($data);
		die;
	}
	
	public function manage_stages($id=null){
		if(isset($_POST) && count($_POST)>0){           
           $data = $this->input->post();
            if((int)$this->input->post('id')==0){
                /*Insert*/
                $data = $this->input->post();
                unset($data['id']);
				$saveData = $this->general_model->insertButtonStage($data['general']);
                if($saveData>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}
                redirect('general/manage_stages');
            } else {
                /*Update*/
                $id = $data['id'];
                $update = $this->general_model->updateButtonStage($data['general'],$id);
				if($update>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Updated!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}                
                redirect('general/manage_stages');
            }
        }  else {
            if($id!=null){
                $data['id'] = $id;
                $data['update_data'] = $this->general_model->getStage($id);
            }
        }
        $data['litigation'] = $this->general_model->getAllButtonStageList('Litigation');
        $data['market'] = $this->general_model->getAllButtonStageList('Market');
        $data['proactive'] = $this->general_model->getAllButtonStageList('General');        
    	$this->layout->title_for_layout = 'Backyard Stages';    
    	$this->layout->render('general/manage_stages',$data);            
	}
    
	public function change_order(){
		if(isset($_POST) && count($_POST)>0){
			$data = $this->input->post('spliting');
			if(!empty($data) && count($data)>0){				
				foreach($data as $val){
					if(!empty($val)){
						$newString = explode("_",$val);
						if(count($newString)==2){
							$this->general_model->updateButton(array("sort"=>$newString[1]),$newString[0]);
						}
					}
				}				
			}
		}
		die;
	}
	
	public function modifyUser($userID,$status){
		$this->user_model->change_status($userID,$status);
		redirect('general/add_user');
	}
	
	public function user_activities(){		
		$data['user'] = $this->user_model->getallactivities();
		$this->layout->title_for_layout = 'Users Activities';    
    	$this->layout->render('general/user_activities',$data);
	}
	
	public function user_timeline(){		
		$data['user'] = $this->user_model->getallactivities();
		$this->layout->title_for_layout = 'Users Timelines';    
    	$this->layout->render('general/user_timeline',$data);
	}
	
	public function user_timeline_table(){
		$data['viewUserID'] = 0;
		if(isset($_POST) && count($_POST)>0){
			$general = $this->input->post('general');
			$data['viewUserID'] = $general['user'];
		}
		$data['users'] = $this->user_model->getAllUsersIncAdmin();
		$this->layout->title_for_layout = 'Users Timelines Table View';    
    	$this->layout->render('general/user_timeline_table',$data);
	}
	
    public function manage_task($id=null){
        if(isset($_POST) && count($_POST)>0){           
           $data = $this->input->post();
            if((int)$this->input->post('id')==0){
                /*Insert*/
                $data = $this->input->post();
                unset($data['id']);
                $saveData = $this->general_model->insertTask($data['general']);
                if($saveData>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}
                redirect('general/manage_task');
            } else {
                /*Update*/
                $id = $data['id'];
                $update = $this->general_model->updateTask($data['general'],$id);
				if($update>0){
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Updated!</p>');
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				}                
                redirect('general/manage_task');
            }
        }  else {
            if($id!=null){
                $data['id'] = $id;
                $data['update_data'] = $this->general_model->getTask($id);
               // echo $this->db->last_query();
            }
        }
        $data['ApprovalCipoList'] = $this->general_model->getAllTaskList();        
    	$this->layout->title_for_layout = 'Backyard Task List';    
    	$this->layout->render('general/manage_task',$data);            
	}
    
    public function delete_task($id=null){
		$this->general_model->delete_task($id);
		$this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
		redirect('general/manage_task');
    }
	
	public function delete_sector($id = null){
		if($id!=null){			
			$this->general_model->deleteSectorDepartment($id);
			$this->general_model->delete_sector($id);
			$this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');			
		}
		redirect('general/manage_sectors');
	}
	public function delete_category($id = null){
		if($id!=null){			
			$this->general_model->delete_category($id);
			$this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');			
		}
		redirect('general/manage_sectors');
	}
	
	public function delete_subcategory($id = null){
		if($id!=null){			
			$this->general_model->delete_category($id);
			$this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
		}		
		redirect('general/manage_sectors');
	}	
	
	
	public function delete_technology($id = null){
		if($id!=null){
			$this->general_model->delete_technology($id);
			$this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
			redirect('general/manage_technologies');
		}
	}
	
	public function delete_button($id = null){
		if($id!=null){
			 $this->general_model->delete_button($id);
		   $this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
		   redirect('general/button_boxes');
		}
	}
	
	public function delete_button_stages($id = null){
		if($id!=null){
			 $this->general_model->delete_button_stages($id);
		   $this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
		   redirect('general/manage_stages');
		}
	}
	public function delete_template($id = null){
		if($id!=null){
			 $this->general_model->delete_template($id);
		   $this->session->set_flashdata('message','<p class="alert alert-success">Successfully Deleted!</p>');
		   redirect('general/email_templates');
		}
	}
}
/* End of file general.php */
/* Location: ./application/controllers/general.php */