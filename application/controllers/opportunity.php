<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Opportunity extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('DriveServiceHelper');
		$service = new GmailServiceHelper();
		if(!isset($this->session->userdata['type']) || empty($this->session->userdata['email'])){
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['find_user']) && !empty($_SESSION['find_user']['type'])){
				$this->session->set_userdata($_SESSION['find_user']);
			} else {
				redirect('login');
			}
		}
		/*$this->clear_cache();*/
		$this->load->model('opportunity_model');
		$this->load->model('client_model');
		$this->load->model('acquisition_model');
		$this->load->model('user_model');
		$this->load->model('lead_model');
		$this->load->model('notification_model');
		$this->load->model('client_model');
		$this->load->model('general_model');
		$this->load->model('customer_model');
		$this->layout->auto_render=false;	
		$this->layout->layout='default';		
	}
	/*
	public function clear_cache(){
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
	*/
	/*
	function generate_comparables_pdf(){
		
		$links = array('http://www.sec.gov/Archives/edgar/containers/fix068/855683/000112528206002245/b412754ex10_10.txt','http://www.sec.gov/Archives/edgar/containers/fix049/1081197/000089161899001414/0000891618-99-001414.txt','http://www.sec.gov/Archives/edgar/data/1096325/000088981200003853/0000889812-00-003853-0001.txt','http://www.sec.gov/Archives/edgar/data/1074828/000119983514000047/exhibit_10-52.htm','http://www.sec.gov/Archives/edgar/data/1386262/000114036109009648/form10k.htm','http://www.sec.gov/Archives/edgar/containers/fix061/1107947/0001005477-00-002036.txt','http://www.sec.gov/Archives/edgar/data/1322705/000119312505187045/dex106.htm','http://www.sec.gov/Archives/edgar/data/1322705/000119312505187045/dex107.htm','http://www.sec.gov/Archives/edgar/data/1381272/000119312506261688/dex1010.htm');
		$links = array('http://www.sec.gov/Archives/edgar/containers/fix068/855683/000112528206002245/b412754ex10_10.txt');
		$curl = curl_init();
		$postData = array('links'=>$links,'paper'=>"letter",'orientation'=>"portrait");
		echo "<pre>";
		print_r($postData);
		die;
		
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://synpat.com/generate_pdf/generate_pdf.php',
			CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'data' => json_encode($postData)
			)
		));
		
		$resp = curl_exec($curl);
		echo $resp;
		die;
		$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/comparables.pdf', "w+");
		fwrite($fh, $resp);
		fclose($fh);
		echo $_SERVER['DOCUMENT_ROOT'].'/public/upload/comparables.pdf';
		die;
		echo "<pre>";
		print_r($resp);
		die;
	}*/
	
	public function google_contact(){
		$data = array();
		$this->load->library('DriveServiceHelper');
		$gmailContact = new GmailServiceHelper();
		if(!isset($_SESSION)){
			session_start();
		}
		if($gmailContact->checkExpiredToken()){
			if($_SESSION['another_access_token']!=""){
				$google_token= json_decode($_SESSION['another_access_token']);
				if(isset($google_token->refresh_token)){						
					$gmailContact->refreshToken($google_token->refresh_token);
					$newToken = json_decode($gmailContact->getAccessToken());
					$google_token->id_token = $newToken->id_token;
					$google_token->access_token = $newToken->access_token;
					$google_token->created = $newToken->created;
					$google_token = json_encode($google_token);						
					$_SESSION['another_access_token'] = $google_token;
					$_SESSION['access_token'] = $google_token;
				}	
			}
		}			
		$gmailContact->setAccessToken($_SESSION['another_access_token']);
		$allFeed = $gmailContact->getAllContacts();		
		try{
			if(count($allFeed)>0){
				$pathInclude = get_include_path().PATH_SEPARATOR;
				set_include_path($pathInclude.$_SERVER['DOCUMENT_ROOT']."/application/libraries/ZendGdata-1.12.9/library");
				$this->load->library('Google_Contact');
				$feed = new Zend_Gdata_App_Feed();
				$feed->setMajorProtocolVersion(null);
				$feed->setMinorProtocolVersion(null);
				$feed->transferFromXML($allFeed);
				$feed->setHttpClient(new Zend_Http_Client());
				$results = array();				
				foreach($feed as $entry){
					$xml = simplexml_load_string($entry->getXML());	
					
					$obj = new stdClass;
					$obj->id = $entry->getEditLink()->href;
					$obj->name = (string) $entry->title;
					$obj->orgName = (string) $xml->organization->orgName; 
					$obj->orgTitle = (string) $xml->organization->orgTitle; 
					foreach ($xml->email as $e) {
					  $obj->emailAddress[] = (string) $e['address'];
					}
					
					if(isset($xml->userDefinedField)){
						foreach($xml->userDefinedField as $e){
							$obj->sectors[] = (string) $e['value'];
						}
					} else {
						$obj->sectors = "";
					}
					foreach ($xml->phoneNumber as $p) {
					  $obj->phoneNumber[] = (string) $p;
					}
					foreach ($xml->website as $w) {
					  $obj->website[] = (string) $w['href'];
					}
					$results[] = $obj;  
				}
				$data['contacts'] = $results;
			}
		} catch(Exception $e){
			
		}
		$this->layout->layout='opportunity';	
		$this->layout->title_for_layout = 'Backyard Opportunity List';
		$this->layout->render('opportunity/google_contact',$data);
	}
	
	
	function full_copy( $source, $target ) {
		if ( is_dir( $source ) ) {
			if(!is_dir($target)){
				@mkdir( $target );
			}			
			$d = dir( $source );
			while ( FALSE !== ( $entry = $d->read() ) ) {
				if ( $entry == '.' || $entry == '..' ) {
					continue;
				}
				$Entry = $source . '/' . $entry; 
				if ( is_dir( $Entry ) ) {
					$this->full_copy( $Entry, $target . '/' . $entry );
					continue;
				}
				copy( $Entry, $target . '/' . $entry );
			}

			$d->close();
		}else {
			copy( $source, $target );
		}
	}
	
	
	public function getStoreDIRList(){
		$storeDirectories = array('store','dd');
		foreach($storeDirectories as $storeDirectorie){
			$dir = $_SERVER['DOCUMENT_ROOT'].'/../'.$storeDirectorie.'/';
			$directoryList = scandir($dir);
			$src = $_SERVER['DOCUMENT_ROOT'].'/../'.$storeDirectorie.'/12345/';
			$srcDirectoryList = scandir($src);
			for($i=2;$i<count($directoryList);$i++){
				if($directoryList[$i]!='12345'){
					$destination = $_SERVER['DOCUMENT_ROOT'].'/../'.$storeDirectorie.'/'.$directoryList[$i];
					if(is_dir($destination)){
						foreach($srcDirectoryList as $directory){
							if($directory!='license.php' && $directory!='.' && $directory!='..'&& $directory!='uploads'){
								$this->full_copy( $src.$directory, $destination.'/'.$directory );
							}
						}					
					}
				}
			}
		}
		die;
	}
	
	function viewFile(){
		$messageID = $this->input->post('message_id');
		$attachmentID = $this->input->post('attachment');
		$mimeType = base64_decode($this->input->post('mimeType'));
		$this->load->library('DriveServiceHelper');
		if(!isset($_SESSION)){
			session_start();
		}
		$service = new GmailServiceHelper();
		$service->setAccessToken($_SESSION['access_token']);
		$findThreadData =  $service->downloadAttachments($messageID,$attachmentID);	
		$fileName = time().$this->input->post('filename');
		$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$fileName, "w+");
		fwrite($fh, base64_decode($findThreadData->data));
		fclose($fh);
		echo json_encode(array("url"=>$this->config->base_url()."public/upload/".$fileName));
		die;
	}
	
	public function index()	{			
		$this->layout->title_for_layout = 'Backyard Client List';
		$this->layout->render('client/index');
	}	
	public function opportunity_list(){		
		$userID = $this->session->userdata['id'];
		if((int)$this->session->userdata['type']!=9){
			$data['projects'] = $this->opportunity_model->getMyAssignedLeads($userID);
		} else {
			$data['projects'] = $this->opportunity_model->getAllLeads();
		}		 
		$this->layout->title_for_layout = 'Backyard Opportunity List';
		$this->layout->render('opportunity/opportunity_list',$data);
	}
	
	public function eou_in_folder($id=null){
		$this->layout->layout='opportunity';	
		$data = array();
		/*if($id!=null){
			$data['lead_id'] = $id;
			$leadData = $this->lead_model->getLeadData($id);
			if(count($leadData)>0){
				$data['eou_data'] =$this->opportunity_model->getAllEouData($id);
				$data['acquisition'] =$this->acquisition_model->getData($id);
			}			
		}*/
		$data['eou_data'] =$this->user_model->getAllDueDilligenceProject();
		$this->layout->title_for_layout = 'EOU in Folder';
		$this->layout->render('opportunity/eou_in_folder',$data);
	} 
	
	function save_accordion_text(){
	    $data ="";
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			if((int)$postData['lead_id']>0){
				$getAcqData = $this->acquisition_model->getData($postData['lead_id']);	
				if(count($getAcqData)>0){
					unset($postData['lead_id']);
					$postData['license_number'] = $getAcqData['acquisition']->store_name;
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/update_accordion_data',
						CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => $postData
					));
					// Send the request & save response to $resp
					$resp = curl_exec($curl);
					if($resp){
						$data =$resp;
					}
				}				
			}
		}
		echo $data;
		die;
	}
	
	public function save_pre_lead(){
		$data = $this->input->post();
		if(count($data)>0){
			$serialNumber = $data['serial_number'];
			$leadID = $data['lead_id'];
			$getLeadData = $this->lead_model->getLeadData($leadID);
			$leadName = "";
			if(count($getLeadData)>0){
				$leadName = $getLeadData->lead_name;
			}
			$data['n_name'] = json_encode($data['n_name']);
			$data['r_lice'] = json_encode($data['r_lice']);
			$data['r_link'] = json_encode($data['r_link']);
			$data['country_n'] = json_encode($data['country_n']);
			$data['application_n'] = json_encode($data['application_n']);
			$data['patent_n'] = json_encode($data['patent_n']);
			$data['lead_name'] = $leadName;
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://synpat.com/pre_lead.php',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS =>$data
			));
			$resp = curl_exec($curl);

			if($resp){
				$data =$resp;
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Update pre lead data','create_date'=>date('Y-m-d H:i:s')));
			} else {
				$data= array();
			}
			if(isset($serialNumber) && isset($leadID) && (int)$leadID>0 && (int)$serialNumber>0){
				redirect("opportunity/all_list?alx=".$serialNumber."&plx=".$leadID);			
			} else {
				redirect("opportunity/all_list");			
			}	
		} else {
			redirect("opportunity/all_list");
		}			
	}
	
	public function pre_lead_on_fly(){
		$data = $this->input->post();
		if(count($data)>0){
			if(isset($data['serial_number'])){
				$serialNumber = $data['serial_number'];
				$leadID = 0;
				if(isset($data['lead_id'])){
					$leadID = $data['lead_id'];
					$getLeadData = $this->lead_model->getLeadData($leadID);
					$leadName = "";
					if(count($getLeadData)>0){
						$leadName = $getLeadData->lead_name;
					}
					$data['n_name'] = json_encode($data['n_name']);
					$data['r_lice'] = json_encode($data['r_lice']);
					$data['r_link'] = json_encode($data['r_link']);
					$data['country_n'] = json_encode($data['country_n']);
					$data['application_n'] = json_encode($data['application_n']);
					$data['patent_n'] = json_encode($data['patent_n']);
					$data['lead_name'] = $leadName;
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://synpat.com/pre_lead.php',
						CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS =>$data
					));
					$resp = curl_exec($curl);
					if($resp){
						$data =$resp;
						$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Update pre lead data','create_date'=>date('Y-m-d H:i:s')));
					} else {
						$data= array();
					}
				}
			}			
		}
		die;
	}
	
	public function potential_syndicate(){
		$data = "0";
		if(isset($_POST) && count($_POST)>0){
			$postData = $this->input->post();
			if((int)$postData['l']>0){
				$getAcqData = $this->acquisition_model->getData($postData['l']);
				if(count($getAcqData)){
					$postData['license_number'] = $getAcqData['acquisition']->store_name;
					$postData['potential_syndicate'] = $postData['p'];
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/potential_syndicate',
						CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => $postData
					));
					// Send the request & save response to $resp
					$resp = curl_exec($curl);
					if($resp){
						$data =$resp;
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	public function assign_sales_company_to_broker(){
		$data= 0;
		if(isset($_POST) && count($_POST)>0){
			$salesCompanies = $this->input->post('companies');
			if(!empty($salesCompanies)){
				$salesC = json_decode($salesCompanies);
				$leadID = $this->input->post('l');
				$brokerCompany = $this->input->post('broker_company');
				if(count($salesC)>0 && is_array($salesC) && (int) $leadID>0 && (int)$brokerCompany>0){
					for($i=0;$i<count($salesC);$i++){
						/*Assign broker to sales companies*/
						if((int)$salesC[$i]>0){
							$this->lead_model->deleteSalesBrokerLeadCompany(array('lead_id'=>$leadID,'sales_company_id'=>$salesC[$i]));
							$data = $this->lead_model->insertSalesBrokerLeadCompany(array('lead_id'=>$leadID,'broker_company_id'=>$brokerCompany,'sales_company_id'=>$salesC[$i]));
						}						
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function assign_presales_company_to_broker(){
		$data= 0;
		if(isset($_POST) && count($_POST)>0){
			$salesCompanies = $this->input->post('companies');
			if(!empty($salesCompanies)){
				$salesC = json_decode($salesCompanies);
				$leadID = $this->input->post('l');
				$brokerCompanyRaw = $this->input->post('broker_company');
				$brokerCompany = array();
				if(!empty($brokerCompanyRaw)){
					$brokerCompany = json_decode($brokerCompanyRaw);
				}
				
				if(count($salesC)>0 && is_array($salesC) && (int) $leadID>0 && count($brokerCompany)>0){
					for($i=0;$i<count($salesC);$i++){
						/*Assign broker to sales companies*/
						if((int)$salesC[$i]>0){
							for($j=0;$j<count($brokerCompany);$j++){
								if((int)$brokerCompany[$j][0]>0){
									$data = $this->lead_model->insertPreSalesBrokerLeadCompany(array('lead_id'=>$leadID,'broker_company_id'=>$brokerCompany[$j][1],'company_id'=>$salesC[$i],'broker_id'=>$brokerCompany[$j][0]));
								}
							}
						}						
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function sales_pre_contact($id=0,$activity=0){
		$data = array();
		if($id>0){
			$data['companies'] = $this->client_model->getAllContacts();
			$data['selected_sales_companies'] = $this->lead_model->getPreSalesBrokerListByLead($id);
			$data['selected_acquisition_companies'] = array();
			$data['lead_id'] = $id;
			$data['activity'] = $activity;
			$this->layout->layout='opportunity';
			$this->layout->title_for_layout = 'Backyard';
			$this->layout->render('opportunity/sales_pre_contact',$data);
		} else {
			echo 'Sorry page not found.';
			die;
		}
	}
	
	public function sales_contact($id=0,$activity=0){
		$data = array();
		if($id>0){
			/*Implement which companies assign to LoggedIn User*/
			/*
				if($this->session->userdata['type']<8){
					$data['companies'] = $this->lead_model->companyListAssignedUser($this->session->userdata['id']);
				}
				
			*/
			/**/
			$data['companies'] = $this->customer_model->companyList();
			$data['selected_sales_companies'] = array();
			$data['selected_acquisition_companies'] = array();
			/*$data['market_sectors'] = $this->opportunity_model->getAllMarketSectors();	*/
			if($activity==1 || $activity==2){
				$data['selected_sales_companies'] = $this->lead_model->getSalesActivityCompaniesByLead($id);
				$data['selected_acquisition_companies'] = $this->lead_model->getAcquisitionActivityCompaniesByLead($id);
			}
			
			$data['lead_id'] = $id;
			$data['activity'] = $activity;
			$this->layout->layout='opportunity';
			$this->layout->title_for_layout = 'Backyard';
			$this->layout->render('opportunity/sales_contact',$data);
		} else {
			echo 'Sorry page not found.';
			die;
		}
		
	}
	
	public function delete_c_sales(){
		$data =0;
		if(isset($_POST) && count($_POST)>0){
			$companyID = $this->input->post("c");
			$leadID = $this->input->post("l");
			$explodeCompany = explode(',',$companyID);
			if(count($explodeCompany)>0){
				foreach($explodeCompany as $company){
					if((int)$company>0){
						$delete = $this->opportunity_model->deleteInviteesByLeadAndCompany($leadID,$company);
						$checkCompanyAlreadyInvite = $this->customer_model->getCompanyDataByID($company);	
						$acquisitionData = $this->acquisition_model->getData($leadID);	
						$curl = curl_init();				
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://appadmin.synpat.com/Users/delete_single_invitee',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'license_number' => $acquisitionData['acquisition']->store_name,
								'company_name'=>$checkCompanyAlreadyInvite->company_name
							)
						));
						$resp = curl_exec($curl);
						$data=1;
					}
				}
			}			
		}
		echo $data;
		die;
	}
	
	public function delete_c_acquisition(){
		$data =0;
		if(isset($_POST) && count($_POST)>0){
			$companyID = $this->input->post("c");
			$leadID = $this->input->post("l");
			$delete = $this->opportunity_model->deleteAcquisitionByLeadAndCompany($leadID,$companyID);			
			$data=1;
		}
		echo $data;
		die;
	}
	
	public function invitees_in_bulk(){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$leadID = $getData['lead_id'];
			$companies = json_decode($getData['companies']);
			if(count($companies)>0){
				$acquisitionData = $this->acquisition_model->getData($leadID);		
				foreach($companies as $companyID){
					if((int)$companyID>0){
						$checkCompanyAlreadyInvite = $this->opportunity_model->checkCompanyInSales($leadID,$companyID);
						if(count($checkCompanyAlreadyInvite)==0){
							$saveData = $this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$companyID));
							$checkCompanyAlreadyInvite = $this->customer_model->getCompanyDataByID($companyID);	
							$acquisitionData = $this->acquisition_model->getData($leadID);				
							$curl = curl_init();				
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://appadmin.synpat.com/Users/add_single_invitee',
								CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									'license_number' => $acquisitionData['acquisition']->store_name,
									'company_name'=>$checkCompanyAlreadyInvite->company_name
								)
							));
							$resp = curl_exec($curl);
							echo $saveData;
						} else {
							echo $checkCompanyAlreadyInvite->id;
						}
					}
				}
			}
		}
		die;
	}
	
	
	public function invite_company(){
		if(isset($_POST) && count($_POST)>0){
			$companyID = $this->input->post("company");
			$leadID = $this->input->post("l");
			$checkCompanyAlreadyInvite = $this->opportunity_model->checkCompanyInSales($leadID,$companyID);
			if(count($checkCompanyAlreadyInvite)==0){
				$saveData = $this->opportunity_model->insertInvitees(array('lead_id'=>$leadID,'contact_id'=>$companyID));
				$checkCompanyAlreadyInvite = $this->customer_model->getCompanyDataByID($companyID);	
				$acquisitionData = $this->acquisition_model->getData($leadID);				
				$curl = curl_init();				
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/add_single_invitee',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'license_number' => $acquisitionData['acquisition']->store_name,
						'company_name'=>$checkCompanyAlreadyInvite->company_name
					)
				));
				$resp = curl_exec($curl);
				echo $saveData;
			} else {
				echo $checkCompanyAlreadyInvite->id;
			}			
		}
		die;
	}
	
	function acquisition_company(){
		if(isset($_POST) && count($_POST)>0){
			$companyID = $this->input->post("company");
			$leadID = $this->input->post("l");
			$checkCompanyAlreadyAcquisition = $this->opportunity_model->checkCompanyInAcquisition($leadID,$companyID);
			if(count($checkCompanyAlreadyAcquisition)==0){
				$saveData = $this->opportunity_model->insertAcquisitionCompany(array('lead_id'=>$leadID,'contact_id'=>$companyID));
				echo $saveData;
			} else {
				echo $checkCompanyAlreadyAcquisition->id;
			}			
		}
		die;		
	}
	
	public function docket(){
	   $this->layout->layout='docket';	
	   $user_id  = $this->session->userdata['id'];
       $oppData['opp']['create_date']= date('Y-m-d H:i:s');
		if ($this->uri->segment(3) === FALSE){
			$lead_id = 0;
		} else {
			$lead_id = $this->uri->segment(3);
		}
		if($lead_id>0){
		  /*$data['timeline'] = getUserTimeLine('',$lead_id,'');*/
		  $data['timeline'] = $this->user_model->getAllUserHistory('',$lead_id,'');
		
			$createFlag = 0;
			$getAcData = $this->acquisition_model->getData($lead_id);
			if(count($getAcData)>0){
				if(empty($getAcData['acquisition']->store_name)){
					$createFlag = 1;
				}
			} else {
				$createFlag = 1;
			}
			$data['lead_data'] = $this->lead_model->getLeadData($lead_id);
		
			if($createFlag ==1){
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/demo_portfolio',
					CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'access_code' => '12345',
						'license_number' => str_replace(' ','_',$data['lead_data']->lead_name),
						'serial_number' => $data['lead_data']->serial_number,
						'asking_price'=>0,
						'license_status'=>'0'
					)
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp){
					$getData = json_decode($resp);
					if(count($getData)>0){
						$storeName = $getData->name;
						$storeID = $getData->id;
						if($storeID>0 && !empty($storeName)){
							$getAcqData = array();
							$getAcqData['acquisition']['create_date'] = date('Y-m-d H:i:s');
							$getAcqData['acquisition']['store_name'] = $storeName;
							$getAcqData['acquisition']['lead_id'] = $lead_id;
							$reportArray = array('lead_id'=>$lead_id);
							$this->load->library('DriveServiceHelper');
							$service = new DriveServiceHelper();
							if($data['lead_data']->ppa_id!=""){
								$getFileInfo = $service->getFileInfo($data['lead_data']->ppa_id);
								$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
								$url = $getFileInfo->alternateLink;
								$getAcqData['acquisition']['ppa'] = $url;
								$reportArray['draft_a_ppa'] = 2;
							}
							if($data['lead_data']->ppa_date!=null){
								$reportArray['execute_ppa'] = 3;
							}
							if($data['lead_data']->ppa_text_date!=null){
								$reportArray['execute_ppa'] = 2;
							}
							$saveData = $this->acquisition_model->insertAcquisition($getAcqData['acquisition']);
							/*Insert Docket Buttons*/
							$getButtonList = $this->general_model->getAllButtonList("DOCKET");
							if(count($getButtonList)>0){
								foreach($getButtonList as $button){
									$this->lead_model->insertDocketButton(array("lead_id"=>$lead_id,"sort"=>$button->sort,"button_id"=>$button->id));
								}
							}
							/*End Insert Docket Buttons*/
							
							/*Check Left Chart*/
							$curl = curl_init();
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://synpat.com/file_get.php',
								CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									's'=>$data['lead_data']->serial_number
								)
							));	
							$response = curl_exec($curl);
							
							if($response){
								$getPreData = json_decode($response,true);
								
								if(count($getPreData)>0){
									$otherData = json_decode($getPreData['other_field'],true);
									
									$mainLeftChartData = array();
									if(isset($otherData['patent_list']) && count($otherData['patent_list'])>0){
										for($pl=0;$pl<count($otherData['patent_list']);$pl++){
											$mainLeftChartData[] = array($otherData['patent_list'][$pl]['country'],$otherData['patent_list'][$pl]['patent'],$otherData['patent_list'][$pl]['application']);
										}
									}
									
									if(count($mainLeftChartData)>0){
										$curl = curl_init();
										curl_setopt_array($curl, array(
											CURLOPT_RETURNTRANSFER => 1,
											CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_update_left_chart',
											CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
											CURLOPT_POST => 1,
											CURLOPT_POSTFIELDS => array(
												'license_number' => $storeName,
												'chart_left'=>json_encode($mainLeftChartData)
											)
										));
										// Send the request & save response to $resp
										$respChart = curl_exec($curl);
										/*chart Left*/
										$chartLeftData = $mainLeftChartData;
										if(count($chartLeftData)>0){
											$this->opportunity_model->deleteLeftChart($lead_id);
											for($i=0;$i<count($chartLeftData);$i++){
												if(!empty($chartLeftData[$i]->{'0'})){
													$this->opportunity_model->saveChartLeft(array('lead_id'=>$lead_id,'country'=>$chartLeftData[$i]->{'0'},'applications'=>$chartLeftData[$i]->{'1'},'patents'=>$chartLeftData[$i]->{'2'}),false);
												} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
													$this->opportunity_model->saveChartLeft(array('lead_id'=>$lead_id,'country'=>$chartLeftData[$i]['0'],'applications'=>$chartLeftData[$i]['1'],'patents'=>$chartLeftData[$i]['2']),false);
												}							
											}
										}
																			
									}
								}
							}
							
							/*End Left Chart*/
		
							$update = $this->opportunity_model->updateLevel($lead_id,array('level'=>5));
							if($update==0){
								$this->opportunity_model->insertLevel(array('lead_id'=>$lead_id,'level'=>5));
							}
							$updateReport = $this->opportunity_model->updateReport($lead_id,$reportArray);
							if($updateReport==0){
								$this->opportunity_model->insertReport($reportArray);
							}
							
		
						} else if(!empty($getData->error)){
							echo "<p class='alert alert-danger'>".$getData->error."</p>";
							die;
						}						
					} else {
						echo "<p class='alert alert-danger'>Server is busy, Please try after sometime.</p>";
						die;
					}
				}
			}
			$data['acquisition'] = $this->acquisition_model->getData($lead_id);
			$checkStage = $this->opportunity_model->checkStage($lead_id);
			if(count($checkStage)==0){
				/*Insert Stage*/
				$this->opportunity_model->insertStage(array('lead_id'=>$lead_id,'stage'=>1));
				$checkStage = $this->opportunity_model->checkStage($lead_id);
			} else {
				/*if Stage is 2 then Check on Which Level it is*/
				if((int)$checkStage->stage>=2){		
					$checkLevel = $this->opportunity_model->checkLevel($lead_id);        
					$data['lead_level'] = $checkLevel;
				}
				if( isset($checkLevel) && count($checkLevel)>0 && (int)$checkLevel->level>2){
					$data['lead_contacts'] = array();
					$data['doc_shared'] = array();
        
				} else {
					$data['lead_contacts'] = array();
					$data['doc_shared'] = array();
				}
			}
				
			$data['buttons'] = $this->lead_model->checkDocketButtonList($lead_id);
			if(count($data['acquisition'])>0 && isset($data['acquisition']['acquisition']) && count($data['acquisition']['acquisition'])>0):
				if(count($data['buttons'])==0){
					$getButtonList = $this->general_model->getAllButtonList("DOCKET");
					if(count($getButtonList)>0){
						foreach($getButtonList as $button){
							$this->lead_model->insertDocketButton(array("lead_id"=>$lead_id,"sort"=>$button->sort,"button_id"=>$button->id));
						}
					}
					$data['buttons'] = $this->lead_model->checkDocketButtonList($lead_id);
				}
			endif;
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/getLicenseData',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'license_number' => $data['acquisition']['acquisition']->store_name,
				)
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			if($resp){
				$data['acordion_text'] = json_decode($resp);
			} else {
				$data['acordion_text'] = array();
			}
			
			$data['lead_stage'] = $checkStage;        
			$data['lead_report'] = $this->opportunity_model->checkLeadReport($lead_id);
			$getFolderID = $folderID = $data['lead_data']->folder_id;
			if(!empty($getFolderID) && $getFolderID!=false){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$data['docs_list']['doc_info'] =  $service->getFileInfo($getFolderID);						
				$data['docs_list']['list'] = $service->getFileIDFromChildern($getFolderID);
				if($data['lead_data']->image_folder!="" && $data['lead_data']->image_folder!=null){
					$data['docs_list']['images'] = $service->getFileIDFromChildern($data['lead_data']->image_folder);
				} else {
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $data['lead_data']->folder_id );
					$getImageFolderInfo = $service->createSubFolder('image',$fileParent);
					$this->lead_model->from_litigation_update($lead_id,array("image_folder"=>$getImageFolderInfo));
					$data['docs_list']['images'] = $service->getFileIDFromChildern($getImageFolderInfo);
				}
				
			} else {
				$data['docs_list'] = array();
			}			
			
			$data['assets_data'] =$this->opportunity_model->getAllAssets($lead_id);
			if(count($data['lead_data'])>0){
				$patentScraperData = json_decode($data['lead_data']->patent_data);
				if(count($patentScraperData)>0){
					foreach($patentScraperData as $patent){
						if($patent['0']!=null && $patent['0']!=""){
							$data['patent_data'][] = $patent['0'];
						}						
					}
				} else {
					$data['patent_data'] = array();
				}
			} else {
				$data['patent_data'] = array();
			}
			/*$data['market_sectors'] = $this->opportunity_model->getAllMarketSectors();*/
			$data['technologies'] = $this->opportunity_model->getAllTechnologies();
			$data['potential'] = $this->opportunity_model->getPotential($lead_id);
			/*$data['commitment'] = $this->opportunity_model->getCommitment($lead_id);*/
			$data['chart_left'] = $this->opportunity_model->getChartLeft($lead_id);
			$data['chart_middle'] = $this->opportunity_model->getChartMiddle($lead_id);
			$data['chart_right'] = $this->opportunity_model->getChartRight($lead_id);
			$data['comparables'] = $this->opportunity_model->getComparable($lead_id);
			$data['damages'] = $this->opportunity_model->getDamages($lead_id);
			$data['lead_id'] = $lead_id;
			$data['category_list'] = $this->customer_model->categoryList(0);			
			$data['button_list'] = $this->opportunity_model->myButtonList($lead_id);			
			$this->layout->title_for_layout = 'Backyard Working on Opportunity';
			$this->layout->render('opportunity/docket',$data);
		} else {
			redirect('dashboard');
		}
	}
	
	function drive_mode(){
		$data =array('status'=>'','url'=>'');
		if(isset($_POST) && count($_POST)>0){
			$driveMode = $this->input->post();
			$getLeadData = $this->lead_model->getLeadData($driveMode['l']);
			if(count($getLeadData)>0){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$getFileDetail = $service->getFileInfo($driveMode['r']);
				if($getFileDetail){
					$folderID = $getLeadData->folder_id;
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $folderID );
					$title =  $getFileDetail->title;
					$strPos = stripos($title,"SynPat");
					if($strPos!==false){
						$title = substr($title,$strPos);
						$title = str_replace("Master",$getLeadData->lead_name,$title);
						$strPos = stripos($title,$getLeadData->lead_name);
						if($strPos===false){
							$title = $title." - ".$getLeadData->lead_name;
						}
					}
					$getFileInfo = $service->copyFile($getFileDetail->id,$title,$fileParent);
					if(is_object($getFileInfo)){
						$updateDate = date('Y-m-d H:i:s');
						$update = $this->lead_model->updateDocketButton(array('file_name'=>$title,'file_url'=>$getFileInfo->alternateLink,'status'=>1,'update_date'=>$updateDate),$driveMode['b']);
						if($update){
							$data['status'] = $updateDate;
							$data['url'] = $getFileInfo->alternateLink;
						}
					}
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	function task_mode(){
		$data =array('status'=>'');
		if(isset($_POST) && count($_POST)>0){
			$emailMode = $this->input->post();
			$getLeadData = $this->lead_model->getLeadData($emailMode['l']);
			if(count($getLeadData)>0){
				$updateDate = date('Y-m-d H:i:s');
				$update = $this->lead_model->updateDocketButton(array('status'=>1,'update_date'=>$updateDate),$emailMode['b']);
				if($update){
					$data['status'] = $updateDate;
				}
			}
		}
		echo json_encode($data);
		die;
	}
	
	
	function email_mode(){
		$data =array('status'=>'','detail'=>'');
		if(isset($_POST) && count($_POST)>0){
			$emailMode = $this->input->post();
			$findTemplate = $this->general_model->getTemplate($emailMode['r']);
			if(count($findTemplate)>0){
				$data['detail'] = $findTemplate;
				$updateDate = date('Y-m-d H:i:s');
				$update = $this->lead_model->updateDocketButton(array('status'=>1,'update_date'=>$updateDate),$emailMode['b']);
				if($update){
					$data['status'] = $updateDate;
				}
			}			
		}
		echo json_encode($data);
		die;
	}
	
	
	public function nda(){
		if(isset($_POST) && count($_POST)>0){
			$lead_id = $this->input->post('token');
			if(!empty($lead_id) && $lead_id!=0){
				$findLeadData = $this->opportunity_model->getLeadData($lead_id);
				if(count($findLeadData)>0){
					switch($findLeadData->type){
						case 'Litigation':
							$plantiffName = $findLeadData->case_name." - ".$findLeadData->case_number;
						break;
						case 'Market':
						case 'General':
						case 'SEP':
							$plantiffName = $findLeadData->plantiffs_name." - ".$findLeadData->relates_to." - ".$findLeadData->portfolio_number;
						break;
						default:
							$plantiffName = $findLeadData->plantiffs_name.'_NDA';
						break;
					}				
			
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
			
					$folderID = $this->opportunity_model->assigned_lead_folder_ID($lead_id);
					if($folderID===false){
						$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);
						if($parentFolderID){
							$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');
							if($getFolderID){
								$opportunitesData = $service->getFileNameFromChildern($getFolderID->id,PASTING_FOLDER);
								if($opportunitesData){
									$parentFolderID = $opportunitesData->id;
								} else {
									$parentFolderID = false;
								}							
							} else {
								$parentFolderID = false;
							}			
						}
						
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $parentFolderID );
						$folderID = $service->createSubFolder($findLeadData->lead_name.'_folder',$fileParent);
						if($folderID){
							/*Save Folder ID  in DB*/
							$this->general_model->updateFolderIDByLead($folderID,$lead_id);
			
						}
					}
			
					$fileID = $service->getFileNameFromChildern($folderID,$plantiffName);					
					if($fileID===false){
						/*Save File From Master Document to Opportunity Folder*/
			
						$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
						if($parentFolderID){
							$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('NDA',$findLeadData->type);
							if(count($getNDAFileNameWithAccordingLeadType)>0){
								/*Create Object*/
								$saveDocFIle = $getNDAFileNameWithAccordingLeadType->doc_id;
								$fileID = (object) array("id"=>$saveDocFIle);
							} else {
								$fileID = $service->getFileNameFromChildern($parentFolderID,'7 SynPat - NDA to disclose the PPP - Master');
							}
							if(!empty($fileID)){
								$fileParent = new Google_Service_Drive_ParentReference();
								$fileParent->setId( $folderID );
								$getFileInfo = $service->copyFile($fileID->id,$plantiffName,$fileParent);
								if($getFileInfo){
									/*Change Level*/
			
									$user_history = array('lead_id'=>$lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"NDA created",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
									$this->user_model->addUserHistory($user_history);	
			
									$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
									$leadLevel = $this->opportunity_model->checkLevel($findLeadData->id);
			
									$this->acquisition_model->updateData($lead_id,array("nda_id"=>$getFileInfo->id));
									
                                    
            
									if(count($leadLevel)==0){
										$this->opportunity_model->insertLevel(array('lead_id'=>$findLeadData->id,'level'=>1));
									} else {
										$this->opportunity_model->updateLevel($findLeadData->id,array('level'=>1));
									}
									/*End Change Level*/
									/*Lead Report Card*/
									$checkLeadReport = $this->opportunity_model->checkLeadReport($findLeadData->id);
									if(count($checkLeadReport)==0){
										$this->opportunity_model->insertReport(array('lead_id'=>$findLeadData->id,'draft_nda'=>1));
									} else {
										$this->opportunity_model->updateReport(array('lead_id'=>$findLeadData->id,'draft_nda'=>1));
									}
									$url = $getFileInfo->alternateLink;
									echo json_encode(array('url'=>$url));
								} else {
									echo json_encode(array('url'=>''));
								}
							} else {
								echo json_encode(array('url'=>''));
							}
						} else {
							echo json_encode(array('url'=>''));
						}
					} else {
					   $service->setAdditionalPermissions($fileID->id,$this->session->userdata['email'],"reader","anyone",array("emailMessage"=>'NDA for '.$findLeadData->lead_name));
						$leadLevel = $this->opportunity_model->checkLevel($findLeadData->id);
						/*Update Acquisition*/
						$this->acquisition_model->updateData($lead_id,array("nda_id"=>$fileID->id));
						
                        
                        /*End Update Acquisition*/
						if(count($leadLevel)==0){
							$this->opportunity_model->insertLevel(array('lead_id'=>$findLeadData->id,'level'=>1));
						} else {
							$this->opportunity_model->updateLevel($findLeadData->id,array('level'=>1));
						}
						/*End Change Level*/
						/*Lead Report Card*/
						$checkLeadReport = $this->opportunity_model->checkLeadReport($findLeadData->id);
						if(count($checkLeadReport)==0){
							$this->opportunity_model->insertReport(array('lead_id'=>$findLeadData->id,'draft_nda'=>1));
						} else {
							$this->opportunity_model->updateReport(array('lead_id'=>$findLeadData->id,'draft_nda'=>1));
						}
						$url = $fileID->alternateLink;
						echo json_encode(array('url'=>$url));
					}
				} else {
					echo json_encode(array('url'=>''));
				}
			} else {
				echo json_encode(array('url'=>''));
			}
		}
		die;
	}
	
	public function cipo_approval(){
		if(isset($_POST) && count($_POST)>0){
			$lead_id = $this->input->post('token');
			if(!empty($lead_id) && $lead_id!=0){
				$findLeadData = $this->opportunity_model->getLeadData($lead_id);
				/*Update Lead Report*/
				$this->opportunity_model->updateReport($findLeadData->id,array('cipo_approved'=>1));
				/*End Update*/
			
				
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				/*$folderID = $service->getFileIdByName($findLeadData->lead_name.'_folder');*/
                $folderID = $this->opportunity_model->assigned_lead_folder_ID($lead_id);
				if($folderID){
			        $getAcquisitionData = $this->acquisition_model->getData($lead_id);
				
                    
                    $getFileInfo = $service->getFileInfo($getAcquisitionData['acquisition']->nda_id);
					if($getFileInfo){						
						$url = $getFileInfo->alternateLink;	
						$CIPOEmailAddress = $this->user_model->findUserByType('8');
						if(count($CIPOEmailAddress)>0){
			
							$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
							/*Send Notification to CIPO with link*/
							$sendID = $this->notification_model->insert(array('user_id'=>$CIPOEmailAddress->id,'message'=>'Waiting for your approval for <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));
							/*Check DB Message and Subject*/
							$getData = $this->general_model->getTaskAccToType('NDA');
							$subject="NDA Approval";
							$message = "Waiting for you to approve NDA.";
							if(count($getData)>0){
								$subject = $getData->subject;
								$message = $getData->message;
							}
							/*End Checking*/
							$executionDate = date('Y-m-d');							
							$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'user_id'=>$CIPOEmailAddress->id,'from_user_id'=>$this->session->userdata['id'],'lead_id'=>$lead_id,'execution_date'=>$executionDate,'doc_url'=> $url,'parent_id'=>'0','type'=>'NDA','status'=>'0'));						
							$user_history = array('lead_id'=>$lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to CIPO for NDA approval",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);	
							$updateLeadLevel = $this->opportunity_model->updateLevel($lead_id,array('level'=>2));
							$this->opportunity_model->updateReport($lead_id,array('cipo_approved'=>1));
							if($sendID>0){
								echo json_encode(array('send'=>$sendID,'subject'=>$subject,'message'=>$message,'user_id'=>$CIPOEmailAddress->id,'from_user_id'=>$this->session->userdata['id'],'lead_id'=>$lead_id,'execution_date'=>$executionDate,'doc_url'=> $url,'parent_id'=>'0','type'=>'NDA','status'=>'0','task_id'=>$approvalRequest));
							} else {
								echo json_encode(array('send'=>0));
							}
							/*Send Notification to CIPO with Link*/
						} else {
							echo json_encode(array('send'=>0));
						}				
						/*END PERMISSION RIGHT*/
					} else {
						echo json_encode(array('send'=>0));
					}	
				} else {
					echo json_encode(array('send'=>0));
				}	
			} else {
				echo json_encode(array('send'=>0));
			}	
		}else {
			echo json_encode(array('send'=>0));
		}	
		die;
	}	
	public function approved_doc($approvedID){
		if($approvedID>0){
		$checkApprovedData = $this->opportunity_model->checkApprovalData($approvedID,$this->session->userdata['id']);
		if(count($checkApprovedData)>0){
			if($this->session->userdata['type']=='8'){
				$update = $this->opportunity_model->updateApprovalData($approvedID,array('status'=>1));
				switch($checkApprovedData->type){
					case 'NDA':						
					$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('cipo_approved'=>2));
					$updateLeadLevel = $this->opportunity_model->updateLevel($checkApprovedData->lead_id,array('level'=>3));
					if($updateLeadLevel){
						$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"CIPO approved NDA",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
						return 1;
			
					} else {
			
						return 0;
					}
					break;
                    case 'PLA':						
					$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('draft_pla'=>2));
					if($updateLeadReport){
						$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"CIPO approved PLA",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
						return 1;
			
					} else {
			
						return 0;
					}
					break;
                    case 'Participant':						
					$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('draft_participant'=>2));
					if($updateLeadReport){
						$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"CIPO approved Participant Request",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
						return 1;
			
					} else {
			
						return 0;
					}
					break;
					case 'NDA_EXECUTE_APPROVAL':
						$updateReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('nda_execute'=>2));
						$updateStage = $this->opportunity_model->updateStage($checkApprovedData->lead_id,array('stage'=>3));
						if($updateReport){
							$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"CIPO executed NDA",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
							$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
							return 1;
						} else {
			
							return 0;
						}
					break;
					case 'DD':
					$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('start_dd'=>2));
					if($updateLeadReport){
						$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
						return 1;
					} else {
			
						return 0;
					}
					break;
					case 'ASSETS':
			
						$findLeadData = $this->lead_model->getLeadData($checkApprovedData->lead_id);						
						$asset_data = array();
						$this->load->library('DriveServiceHelper');
						$service = new SpreadsheetServiceHelper();
						$ss_id = $findLeadData->spreadsheet_id;
						$spreadsheet = $service->getSpreadsheetById($ss_id);
						$allWorkSheet = $service->getAllWorkSheets();
						$sheetID = $findLeadData->worksheet_id;
						$sheetName = "Sheet1";
						if(!empty($findLeadData->worksheet_id)){
							foreach($allWorkSheet as $worksheet){
								if(trim($worksheet['id'])==trim($sheetID)){
									$sheetName = $worksheet['text'];
									break;
								}
							}
						}
						if(!empty($sheetName)){
							$listFeed = $service->getWorkSheetByName($sheetName);
							$asset_data =$service->getAllRows($listFeed->getEntries());
						}
						
						if(count($asset_data)>0){
							$this->opportunity_model->deleteAssetData($checkApprovedData->lead_id );
							$insertData = 0;
							for($i=0;$i<count($asset_data);$i++){
								$insertData = $this->opportunity_model->insertAssetData(array('lead_id'=>$checkApprovedData->lead_id,'name'=>$asset_data[$i]['title']));
							}
							if($insertData>0){
								$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"CIPO Approved assets.",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
								return 1;
							} else {
								//$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
								return 0;
							}
						} else {
							return 0;
						}
						/*END*/
					break;
				}
			} else if($this->session->userdata['type']=='9'){
				$update = $this->opportunity_model->updateApprovalData($approvedID,array('status'=>1));
				switch($checkApprovedData->type){
					case 'NDA':								
						$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('executed_nda'=>2));
						$cipoUser = $this->user_model->findUserByType('8');
						$url = "";
						$getData = $this->lead_model->getLeadData($checkApprovedData->lead_id);
						if(count($getData)>0){
							$url = $getData->nda_id;
						}						
						/*Check DB Message and Subject*/
						$getData = $this->general_model->getTaskAccToType('NDA_EXECUTE_APPROVAL');
						$subject="NDA Approval Execution";
						$message = "NDA Approval Execution.";
						if(count($getData)>0){
							$subject = $getData->subject;
							$message = $getData->message;
						}
						/*End Checking*/
						$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('from_user_id'=>$this->session->userdata['id'],'subject'=>$subject,'message'=>$message,'parent_id'=>0,'execution_date'=>date("Y-m-d"),'lead_id'=>$checkApprovedData->lead_id,'user_id'=>$cipoUser->id,'type'=>'NDA_EXECUTE_APPROVAL','doc_url'=>$url,'status'=>'0'));
						$insertData = $this->notification_model->insert(array('user_id'=>$cipoUser->id,'message'=>'<a href="'.$url.'" target="_BLANK">NDA</a> executed by CEO.'));
						if($updateLeadReport){
							$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"NDA approved ",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);	
							$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
							return 1;
						} else {
							return 0;
						}
					break;
					case 'PPA':
						$updateLeadReport = $this->opportunity_model->updateReport($checkApprovedData->lead_id,array('execute_ppa'=>2));
						if($updateLeadReport){
							$user_history = array('lead_id'=>$checkApprovedData->lead_id,'user_id'=>$this->session->userdata['id'],'message'=>"PPA executed ",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);
							$this->session->set_flashdata('message','<p class="alert alert-success">Approved</p>');
							//echo json_encode(array('send'=>1));
							return 1;
						} else {
							return 0;
						}
					break;
				}
			}
		} else {
	
			return 0;
		}
	}
		
	}
	
	public function executeNDA(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
	
			$getData = $this->lead_model->getLeadData($leadID);
			if(count($getData)>0 && !empty($getData->nda_id)){				
				$findAllAdminUsers = $this->user_model->findAdminUsers();
				if(count($findAllAdminUsers)>0){
					$insertData = 0;
					foreach($findAllAdminUsers as $user){					
						$insertData = $this->notification_model->insert(array('user_id'=>$user->id,'message'=>'Execute NDA <a href="'.$getData->nda_id.'" target="_BLANK">NDA</a>'));	
						$getNDAData = $this->general_model->getTaskAccToType('NDA');
						$subject="NDA Execute";
						$message = "NDA Execute.";
						if(count($getNDAData)>0){
							$subject = $getNDAData->subject;
							$message = $getNDAData->message;
						}
						/*End Checking*/
						$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'parent_id'=>0,'execution_date'=>date('Y-m-d'),'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $getData->nda_id,'type'=>'NDA','status'=>'0'));
					}
				}
				if($insertData>0){
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Execute NDA by PD",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					$update = $this->opportunity_model->updateLevel($leadID,array('level'=>5));
					if($update==0){
						$this->opportunity_model->insertLevel(array('lead_id'=>$leadID,'level'=>5));
					}
					$updateReport = $this->opportunity_model->updateReport($leadID,array('executed_nda'=>1));
					if($updateReport==0){
						$this->opportunity_model->insertReport(array('lead_id'=>$leadID,'executed_nda'=>1));
					}
					$buttonData = $this->lead_model->findButtonByButtonID($getData->type,"EXECUTE_NDA");
					echo json_encode(array('send'=>1,'subject'=>$subject,'message'=>$message,'parent_id'=>0,'execution_date'=>date('Y-m-d'),'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $getData->nda_id,'type'=>'NDA','status'=>'0','task_id'=>$approvalRequest,'button_data'=>$buttonData,'date_update'=>date('m d,y')));
				} else {
					echo json_encode(array('send'=>0,'message'=>'Not able to send task to users.'));
				}
			} else {
				echo json_encode(array('send'=>0,'message'=>'NDA not created. Please create NDA first.'));
			}
		} else {
			echo json_encode(array('send'=>0,'message'=>'Try to send request again.'));
		}  
		die;
	}
	
	public function ndaExecuted(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$updateReport = $this->opportunity_model->updateReport($leadID,array('nda_execute'=>2));
			$updateStage = $this->opportunity_model->updateStage($leadID,array('stage'=>3));
			if($updateStage){
				echo json_encode(array('send'=>1));
			} else {
				echo json_encode(array('send'=>0));
			}
		}else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function eouConfirmation(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$updateReport = $this->opportunity_model->updateReport($leadID,array('eou_folder'=>2));	
			$this->opportunity_model->updateStage($leadID,array('stage'=>5));
			if($updateReport){
				$date = date('Y-m-d H:i:s');
				$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"EOU confirmed",'opportunity_id'=>1,'create_date'=>$date);
				$this->user_model->addUserHistory($user_history);	
				echo json_encode(array('send'=>1,'created'=>date('d-m-y', strtotime($date))));
			} else {
				echo json_encode(array('send'=>0));
			}
		}else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
    
    public function draft_pla(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('lead_id');
            $findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$plantiffName = $findLeadData->lead_name.'_PLA';
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$folderID = $findLeadData->folder_id;
				if(!empty($folderID)){
					$fileID = $service->getFileNameFromChildern($folderID,$plantiffName);
					if($fileID===false){
						$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
						if($parentFolderID){
							$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('PLA',$findLeadData->type);
							if(count($getNDAFileNameWithAccordingLeadType)>0){								
								$fileID = (object)array("id"=>$getNDAFileNameWithAccordingLeadType->doc_id) ;
							} else {
								$fileID = $service->getFileNameFromChildern($parentFolderID,'PLA - Patent License Agreement - Master');
							}
							if(!empty($fileID)){
								$fileParent = new Google_Service_Drive_ParentReference();
								$fileParent->setId( $folderID );
								$getFileInfo = $service->copyFile($fileID->id,$plantiffName,$fileParent);
								if($getFileInfo){
									$updateReport = $this->opportunity_model->updateReport($leadID,array('draft_pla'=>2));
									$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
									$this->acquisition_model->updateData($leadID,array("pla"=>$getFileInfo->alternateLink));														
									$url = $getFileInfo->alternateLink;
									$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"PLA Created",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
									$this->user_model->addUserHistory($user_history);	
									echo json_encode(array('url'=>$url));
								} else {
									echo json_encode(array('url'=>''));
								}
							} else {
								echo json_encode(array('url'=>''));
							}
						} else {
							echo json_encode(array('url'=>''));
						}
					} else {
						$updateReport = $this->opportunity_model->updateReport($leadID,array('draft_pla'=>2));
						$service->setAdditionalPermissions( $fileID->id, "","reader","anyone");
						$this->acquisition_model->updateData($leadID,array("pla"=>$fileID->alternateLink));
						$url = $fileID->alternateLink;
						echo json_encode(array('url'=>$url));
					}
				} else {
					echo json_encode(array('url'=>''));
				}				
			} else {
				echo json_encode(array('url'=>''));
			}
		} else {
			echo json_encode(array('url'=>''));
		}
		die;
	}
    
	public function draft_participant(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('lead_id');
            $findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$plantiffName = $findLeadData->lead_name.' - RTP';
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$folderID = $findLeadData->folder_id;
				if(!empty($folderID)){
					$fileID = $service->getFileNameFromChildern($folderID,$plantiffName);
					if($fileID===false){
						$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
						if($parentFolderID){
							$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('RTP',$findLeadData->type);
							if(count($getNDAFileNameWithAccordingLeadType)>0){								
								$fileID = (object)array("id"=>$getNDAFileNameWithAccordingLeadType->doc_id) ;
							} else {
								$fileID = $service->getFileNameFromChildern($parentFolderID,'5 SynPat - Request to Participate - Master');
							}
							if(!empty($fileID)){
								$fileParent = new Google_Service_Drive_ParentReference();
								$fileParent->setId( $folderID );
								$getFileInfo = $service->copyFile($fileID->id,$plantiffName,$fileParent);
								if($getFileInfo){
									$updateReport = $this->opportunity_model->updateReport($leadID,array('draft_participant'=>2));
									$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
									$this->acquisition_model->updateData($leadID,array("rtp"=>$getFileInfo->alternateLink));
									$fileID = $getFileInfo->id;									
									$url = $getFileInfo->alternateLink;
									$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"RTP Created",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
									$this->user_model->addUserHistory($user_history);	
									echo json_encode(array('url'=>$url));
								} else {
									echo json_encode(array('url'=>''));
								}
							} else {
								echo json_encode(array('url'=>''));
							}
						} else {
							echo json_encode(array('url'=>''));
						}
					} else {
						$updateReport = $this->opportunity_model->updateReport($leadID,array('draft_participant'=>2));
						$service->setAdditionalPermissions( $fileID->id, "","reader","anyone");
						$this->acquisition_model->updateData($leadID,array("rtp"=>$fileID->alternateLink));
						$fileID = $getFileInfo->id;									
						$url = $getFileInfo->alternateLink;
						echo json_encode(array('url'=>$url));
					}
				} else {
					echo json_encode(array('url'=>''));
				}				
			} else {
				echo json_encode(array('url'=>''));
			}
		} else {
			echo json_encode(array('url'=>''));
		}
		die;
	}
    	
	public function sendEouData(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$eouData = json_decode($this->input->post('eou_data'));
			$sep = $this->input->post('sep');
			$potential = $this->input->post('potential');
			$update = $this->acquisition_model->updateData($leadID,array('no_of_sep'=>$sep,'no_of_potential_licensees'=>$potential));
			if(count($eouData)>1){
				$this->opportunity_model->deleteEouData($leadID );
				$insertData = 0;
				for($i=0;$i<count($eouData)-1;$i++){
					$insertData = $this->opportunity_model->insertEouData(array('lead_id'=>$leadID,'standard'=>$eouData[$i]->{'0'},'company'=>$eouData[$i]->{'1'},'product'=>$eouData[$i]->{'2'},'eou'=>$eouData[$i]->{'3'},'quality'=>$eouData[$i]->{'4'},'in_folder'=>$eouData[$i]->{'5'},'created'=>date('Y-m-d'),'user_id'=>$this->session->userdata['id']));
				}
				if($insertData>0){				
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send EOU",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);	
					echo json_encode(array('send'=>1));
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	function sep_data(){		
		if(isset($_POST) && count($_POST)>0){			
			$getData = $this->input->post();			
			if(isset($getData['sep']['lead_id']) && (int) $getData['sep']['lead_id']>0){				
				$leadID = $getData['sep']['lead_id'];
				$this->acquisition_model->updateData($leadID,array('no_of_sep'=>$getData['sep']['numbers'],'no_of_potential_licensees'=>$getData['sep']['potential_licensees']));
				$sepData = json_decode($getData['sep']['sep_data']);				
				$sepAnotherData = json_decode($getData['sep']['sep_another_data']);
				if(count($sepData)>1){
					$this->opportunity_model->deleteSepData($leadID );
					for($i=0;$i<count($sepData)-1;$i++){
						$insertData = $this->opportunity_model->insertSepData(array('lead_id'=>$leadID,'standard'=>$sepData[$i]->{'0'},'product'=>$sepData[$i]->{'1'},'eou'=>$sepData[$i]->{'2'},'in_folder'=>$sepData[$i]->{'3'}  ));
					}
				} 
				if(count($sepAnotherData)>1){
					$this->opportunity_model->deleteSepAnotherData($leadID );
					for($i=0;$i<count($sepAnotherData)-1;$i++){
						$insertData = $this->opportunity_model->insertSepAnotherData(array('lead_id'=>$leadID,'company'=>$sepAnotherData[$i]->{'0'},'product'=>$sepAnotherData[$i]->{'1'},'eou'=>$sepAnotherData[$i]->{'2'},'in_folder'=>$sepAnotherData[$i]->{'3'}  ));
					}					
				}
				redirect('opportunity/docket/'.$leadID);
			} else {
				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid data</p>');
			redirect('dashboard');
		}	
	}
	
	public function upload_image_file(){
		$data =0;
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('l');
			$fileName = $this->input->post('n');
			if((int)$leadID>0){
				$findLeadData = $this->opportunity_model->getLeadData($leadID);
				if(count($findLeadData)>0){
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
					$folderID = $findLeadData->image_folder;	 							
					if(!empty($folderID)){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $folderID );
						$fileURL = $this->input->post('i');
						$pathInfo = pathinfo($fileURL);
						if(count($pathInfo)>0){
							if(!empty($pathInfo['extension'])){
								$fileName = $fileName.".".$pathInfo['extension'];
								
								$finfo = new finfo(FILEINFO_MIME);
								$mimeType =  $finfo->buffer(file_get_contents($fileURL)); 
								if(!empty($mimeType)){
									$m = explode(";",$mimeType);
									$mimeType = $m[0];
								}
								 
								if(empty($mimeType)){
									$mimeType = "image/jpeg";
								}								
								$convert=false;								
								$getUploadFileData = $service->insertFile($fileName,$mimeType,$fileURL,$fileParent,$convert);
								if($getUploadFileData){
									/*$service->setAdditionalPermissions( $getUploadFileData->id, $this->session->userdata['email'],"reader","anyone",array('emailMessage'=>'File has been share with you .','sendNotificationEmails'=>true));*/									
									$service->setAdditionalPermissions( $getUploadFileData->id, "","reader","anyone");
									if(isset($getUploadFileData->id) && !empty($getUploadFileData->id)){
										$downloadContent = $service->downloadFile($getUploadFileData);
										if($downloadContent!=null){
											/*$filename = $getUploadFileData->title;*/
											$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$fileName, "w+");
											fwrite($fh, $downloadContent);
											fclose($fh);
											$data = 1;
										}
									}									
								}
							}							
						}						
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	public function draft_a_ppa(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			if((int)$leadID>0){
				$findLeadData = $this->opportunity_model->getLeadData($leadID);
				if(count($findLeadData)>0){
					$sellerAskingPrice= 0;
					$getAcqusitionData = $this->acquisition_model->getData($leadID);
					if(count($getAcqusitionData)>0){
						$sellerAskingPrice = $getAcqusitionData['acquisition']->seller_upfront;
					}
					if(count($findLeadData)>0){
						$plantiffName = "Patent Purchase Agreement with Seller - ".$findLeadData->lead_name;
						$this->load->library('DriveServiceHelper');
						$service = new DriveServiceHelper();
						$folderID = $findLeadData->folder_id;								
						if(!empty($folderID)){
	
							$fileID = false;
							if($fileID===false){
								/*Save File From Master Document to Opportunity Folder*/
								$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
								if($parentFolderID){
									$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('PPA',$findLeadData->type);
									if(count($getNDAFileNameWithAccordingLeadType)>0){										
										$fileID = (object)array("id"=>$getNDAFileNameWithAccordingLeadType->doc_id) ;
									} else {
										switch($findLeadData->type){
											/*case 'Litigation':
												$fileID = $service->getFileNameFromChildern($parentFolderID,'3 SynPat - Patent Purchase Agreement (PPA) - Litigated - Master');
											break;*/
											default:
												$fileID = $service->getFileNameFromChildern($parentFolderID,'PPA - Patent Purchase Agreement - Master');
											break;
										}										
									}
									if(!empty($fileID)){
										$fileParent = new Google_Service_Drive_ParentReference();
										$fileParent->setId( $folderID );
										$getFileInfo = $service->copyFile($fileID->id,$plantiffName,$fileParent);
										if(is_object($getFileInfo)){
											$dateUpdated = date('Y-m-d H:i:s');
	
											$this->lead_model->from_litigation_update($leadID,array("ppa_id"=>$getFileInfo->id,'ppa_text_date'=>$dateUpdated));
											$this->acquisition_model->updateData($leadID,array("ppa_id"=>$getFileInfo->id));
											$fileID = $getFileInfo->id;
											/*Create PPP*/
											/*$filePPPOID = $service->getFileNameFromChildern($folderID,"Program Procedure and Policies (PPP) - ".$findLeadData->lead_name);*/
											$filePPPOID = false;
											$alternateUrlPPP="";
											if($filePPPOID==false){
												$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('PPP',$findLeadData->type);
												$PPPfileID="";
												if(count($getNDAFileNameWithAccordingLeadType)>0){												
													$PPPfileID = (object)array("id"=>$getNDAFileNameWithAccordingLeadType->doc_id) ;
												} else {
													$PPPfileID = $service->getFileNameFromChildern($parentFolderID,'PPP - Program Procedure and Policies - Master 12-9-14');
												}												
												if(!empty($PPPfileID)){
													$PPPName = "Program Procedure and Policies (PPP) - ".$findLeadData->lead_name;
													$getPPPFileInfo = $service->copyFile($PPPfileID->id,$PPPName,$fileParent);
													if(is_object($getPPPFileInfo)){
														$alternateUrlPPP = $getPPPFileInfo->id;
													}
												}
											} else {
												$alternateUrlPPP = $filePPPOID->id;
											}
											$originalButtonData = $this->lead_model->findButtonByButtonID($findLeadData->type,"DRAFT_PPA");
											if(count($originalButtonData)>0){
												$leadButtonData = $this->lead_model->findLeadButtonByButtonID($findLeadData->id,$originalButtonData->id);
												if(count($leadButtonData)>0){
													$newStatus = "";									
													$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($dateUpdated)).'</span>'.$originalButtonData->status_message.' <br/>';
													$newStatus = $leadButtonData->status_message_fill.$newStatus;	
													$this->lead_model->updateButton(array('status'=>1,'update_date'=>$dateUpdated,'status_message_fill'=>$newStatus),$leadButtonData->id);
												}
											}
											$this->opportunity_model->updateReport($findLeadData->id,array('draft_a_ppa'=>2));
											$this->opportunity_model->updateStage($findLeadData->id,array('stage'=>'5'));
											$url = $getFileInfo->alternateLink;
											$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"PPP Created",'opportunity_id'=>1,'create_date'=>$dateUpdated);
											$this->user_model->addUserHistory($user_history);
											$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"PPA drafted ",'opportunity_id'=>1,'create_date'=>$dateUpdated);
											$this->user_model->addUserHistory($user_history);	
											echo json_encode(array('url'=>$url,'date_updated'=>$dateUpdated,"ppp_url"=>$alternateUrlPPP));
										} else {
											echo json_encode(array('url'=>''));
										}
									} else {
										echo json_encode(array('url'=>''));
									}
								} else {
									echo json_encode(array('url'=>''));
								}
							} else {
								$dateUpdated = date('Y-m-d H:i:s');
								$this->lead_model->from_litigation_update($findLeadData->id,array("ppa_id"=>$fileID->id,'ppa_text_date'=>$dateUpdated));
								$this->opportunity_model->updateReport($findLeadData->id,array('draft_a_ppa'=>2));
								$url = $fileID->alternateLink;										
								$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
								echo json_encode(array('url'=>$url,'date_updated'=>$dateUpdated));
							}
						} else {
							echo json_encode(array('url'=>''));
						}
					} else {
						echo json_encode(array('url'=>''));
					}							
				} else {
					echo json_encode(array('url'=>''));
				}
			} else {
				echo json_encode(array('url'=>''));
			}
		} else {
			echo json_encode(array('url'=>''));
		}
		die;
	}
	
	public function startUpdatePatent(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->lead_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$this->load->library('DriveServiceHelper');
				$service = new SpreadsheetServiceHelper();
				$ss_id = $findLeadData->spreadsheet_id;
				if(empty($ss_id)){
					$string = $findLeadData->file_url;
					$ss_id = explode("/",$string);
					$ss_id =$ss_id[count($ss_id)-2];
					$workSheetString = explode("#",$string);
					$sheetID = 0;
					if(count($workSheetString)>1){
						$workSheetString = $workSheetString[1];
						if(!empty($workSheetString)){
							$sheetExplode = explode("=",$workSheetString);
							if(count($sheetExplode)>1){
								$sheetID = $sheetExplode[1];
							}
						}
					}
				}
				if(!empty($ss_id)):
				$spreadsheet = $service->getSpreadsheetById($ss_id);
				$allWorkSheet = $service->getAllWorkSheets();
				$sheetID = $findLeadData->worksheet_id;
				$sheetName = "Sheet1";
				if(!empty($findLeadData->worksheet_id)){
					foreach($allWorkSheet as $worksheet){
						if(trim($worksheet['id'])==trim($sheetID)){
							$sheetName = $worksheet['text'];
							break;
						}
					}
				}
				$asset_data = array();
				if(!empty($sheetName)){
					$listFeed = $service->getWorkSheetByName($sheetName);
					$asset_data =$service->getAllRows($listFeed->getEntries());
				}
				
				if(count($asset_data)>0){
					$this->opportunity_model->deleteAssetData($findLeadData->id );
					$insertData = 0;
					for($i=0;$i<count($asset_data);$i++){
						if(isset($asset_data[$i]['patent'])){
							$insertData = $this->opportunity_model->insertAssetData(array('lead_id'=>$findLeadData->id,'name'=>$asset_data[$i]['patent']));
						} else if(isset($asset_data[$i]['patents'])){
							$insertData = $this->opportunity_model->insertAssetData(array('lead_id'=>$findLeadData->id,'name'=>$asset_data[$i]['patents']));
						}						
					}
				}
				$acquisitionData = $this->acquisition_model->getData($leadID);
				if(count($acquisitionData)>0){
					/*$patentData = json_decode($findLeadData->patent_data);*/
					$patentData = $this->opportunity_model->getAllAssets($leadID);
					if(count($patentData)>0){
						$curl = curl_init();
						// Set some options - we are passing in a useragent too here
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://appadmin.synpat.com/Users/updatePatentList',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'license_number' => $acquisitionData['acquisition']->store_name,
								'patent_data'=>json_encode($patentData)
							)
						));						
						// Send the request & save response to $resp
						$resp = curl_exec($curl);	
						/*echo $resp;
						die;*/
						if($resp){
							$data = json_decode($resp);
							if($data->send==1){
								$data = 1;
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Updated list of Patentees in Store",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
							}
						}
					}					
				}
				endif;
			}
		}
		echo $data;
		die;
	}
	
	public function updateIllustration(){
		$data = 0;
		$dataFindIllustration = false;
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$leadInfo = $this->lead_model->getLeadData($leadID);
			if(count($leadInfo)>0){
				$this->load->library('DriveServiceHelper');
				$service = new SpreadsheetServiceHelper();
				$fileID = "";
				$fileName = "Claim Illustration - ".$leadInfo->lead_name;
				$leadFolderID = $leadInfo->folder_id;
				$driveService = new DriveServiceHelper();
				$fileInfo = $driveService->getFileNameFromChildern($leadFolderID,$fileName);
				if($fileInfo!=""){
					$fileID = $fileInfo;
				}				
				if(is_object($fileID)){
					$ss_id = $fileID->getId();
					$spreadsheet = $service->getSpreadsheetById($ss_id);
					$allWorkSheet = $service->getAllWorkSheets();
					$sheetName = "Sheet1";
					if(!empty($sheetName)){
						$listFeed = $service->getWorkSheetByName($sheetName);
						$asset_data =$service->getAllRows($listFeed->getEntries());
						$inventionsData = array();
						for($i=0;$i<count($asset_data);$i++){
							$patent = "";
							if(isset($asset_data[$i]['patents'])){
								$patent = $asset_data[$i]['patents'];
							} else if(isset($asset_data[$i]['patent'])){
								$patent = $asset_data[$i]['patent'];
							}
							if(!empty($patent)){
								$illustrationFileName = 'Synpat - Claim Illustration - '.$leadInfo->lead_name.' - '.$patent;
								$driveService = new DriveServiceHelper();
								$illuFolder = $driveService->getFileNameFromChildern($leadFolderID,"Illustration");
								$id = $illuFolder->getId();
								if(is_object($illuFolder) && !empty($id)){
									$driveService = new DriveServiceHelper();
									$getIllustrationFileInfo = $driveService->getFileNameFromChildern($illuFolder->getId(),$illustrationFileName);
									$illuID = $getIllustrationFileInfo->getId();
									if(is_object($getIllustrationFileInfo)&& !empty($illuID)){
										$inventionsData[] = array("patent_id"=>$patent,"file"=>$getIllustrationFileInfo->alternateLink);
										$dataFindIllustration = true;
									}
								}
							}
						}
						if(count($inventionsData)>0){
							$acquisitionData = $this->acquisition_model->getData($leadID);
							if(count($acquisitionData) && !empty($acquisitionData['acquisition']->store_name)){
								$curl = curl_init();
								curl_setopt_array($curl, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_URL => 'http://appadmin.synpat.com/Users/updateillustrationData',
									CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
									CURLOPT_POST => 1,
									CURLOPT_POSTFIELDS => array(
										'license_number' => $acquisitionData['acquisition']->store_name,
										'inventions_data'=>json_encode($inventionsData)
									)
								));
								
								$resp = curl_exec($curl);
								if($resp){
									$data = $resp;				
								} 
							}
						}
					}
				}
			}
		}
		if($dataFindIllustration ===false){
			echo "1";
		} else {
			echo $data;
		}
		
		die;
	}
	
	public function startInventionData(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$leadInfo = $this->lead_model->getLeadData($leadID);
			if(count($leadInfo)>0){
				$this->load->library('DriveServiceHelper');
				$service = new SpreadsheetServiceHelper();
				$fileID = "";
				if(!empty($leadInfo->technical_file)){
					$fileID = $leadInfo->technical_file;
				} else {
					$fileName = "Technical Due Dilligence - ".$leadInfo->lead_name;
					$leadFolderID = $leadInfo->folder_id;
					$driveService = new DriveServiceHelper();
					$fileInfo = $driveService->getFileIdByName($fileName);
					if($fileInfo!=""){
						$fileID = $fileInfo;
					}
				}
				if(!empty($fileID)){
					$ss_id = $fileID;
					$spreadsheet = $service->getSpreadsheetById($ss_id);
					$allWorkSheet = $service->getAllWorkSheets();
					$sheetName = "Sheet1";
					if(!empty($sheetName)){
						$listFeed = $service->getWorkSheetByName($sheetName);
						$asset_data =$service->getAllRows($listFeed->getEntries());
						$inventionsData = array();
						for($i=0;$i<count($asset_data);$i++){
							$s=0;
							$inLineArray = array();
							foreach($asset_data[$i] as $key=>$patent){
								if($s<7){
									$inLineArray[] = $patent;
								}
								$s++;
							}
							$inventionsData[] = array("patent_id"=>$inLineArray[0],"problem"=>$inLineArray[1],"solution"=>$inLineArray[2],"system"=>$inLineArray[3],"products"=>$inLineArray[4],"companies"=>$inLineArray[5],"p_infringe"=>$inLineArray[6]);
						}
						
						if(count($inventionsData)){
							$acquisitionData = $this->acquisition_model->getData($leadID);
							if(count($acquisitionData) && !empty($acquisitionData['acquisition']->store_name)){
								$curl = curl_init();
								curl_setopt_array($curl, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_URL => 'http://appadmin.synpat.com/Users/updateInventionData',
									CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
									CURLOPT_POST => 1,
									CURLOPT_POSTFIELDS => array(
										'license_number' => $acquisitionData['acquisition']->store_name,
										'inventions_data'=>json_encode($inventionsData)
									)
								));
								// Send the request & save response to $resp
								$resp = curl_exec($curl);
								if($resp){
									$data = $resp;
									$this->load->library('DriveServiceHelper');
									$service = new DriveServiceHelper();
									$getFileInfo = $service->getFileInfo($leadInfo->technical_file);
									if($getFileInfo){
										$service->deletePermission($getFileInfo->id,"anyoneWithLink");
										$getDueLogTime = $this->user_model->getDueData($leadID);
										if(count($getDueLogTime)>0){
											$this->user_model->update_due_logtime(array("permmission"=>0),$getDueLogTime->id);
										}										
									}									
								} 
							}
						}
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	public function approvalList(){
		if(isset($_POST) && count($_POST)>0){
		  
			$leadID = $this->input->post('token');
			$asset_data = json_decode($this->input->post('asset_data'));
			if($leadID>0){
				/*Find Sheet*/
				$insertData = 0;
				$asset_data = array();
				$leadData = $this->lead_model->getLeadData($leadID);
				$this->load->library('DriveServiceHelper');
				$service = new SpreadsheetServiceHelper();
				$ss_id = $leadData->spreadsheet_id;
				$spreadsheet = $service->getSpreadsheetById($ss_id);
				$allWorkSheet = $service->getAllWorkSheets();
				$sheetID = $leadData->worksheet_id;
				$sheetName = "Sheet1";
				if(!empty($leadData->worksheet_id)){
					foreach($allWorkSheet as $worksheet){
						if(trim($worksheet['id'])==trim($sheetID)){
							$sheetName = $worksheet['text'];
							break;
						}
					}
				}
				if(!empty($sheetName)){
					$listFeed = $service->getWorkSheetByName($sheetName);
					$asset_data =$service->getAllRows($listFeed->getEntries());
				}
				
				for($i=0;$i<count($asset_data);$i++){
					foreach($asset_data[$i] as $key=>$patent){
						if(!empty($patent)){
							$insertData = $this->opportunity_model->insertAssetData(array('lead_id'=>$leadID,'name'=>$patent));
						}						
					}
				}
				if($insertData>0){
					$CIPOEmailAddress = $this->user_model->findUserByType('8');
					$url = $leadData->file_url;	
					$sendID = $this->notification_model->insert(array('user_id'=>$CIPOEmailAddress->id,'message'=>'Waiting for your approval for <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));
					$this->acquisition_model->updateData($leadID,array("assets_id"=>$leadData->spreadsheet_id));
					$getData = $this->general_model->getTaskAccToType('ASSETS');
					$subject="Assets";
					$message = "Approve list of Assets.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$CIPOEmailAddress->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'ASSETS','status'=>'0'));
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Insert list of Assets",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);	
					echo json_encode(array('url'=>$url,'subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$CIPOEmailAddress->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'ASSETS','status'=>'0','task_id'=>$approvalRequest));
				} else {
					echo json_encode(array('url'=>''));
				}
			} else {
				echo json_encode(array('url'=>''));
			}			
		} else {
			echo json_encode(array('url'=>''));
		}
		die;
	}
	
	public function execute_ppa(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
            $acquisitionData = $this->acquisition_model->getData($leadID);
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$folderID = $findLeadData->folder_id;
			if(!empty($folderID)){
				if(count($acquisitionData)>0 && isset($acquisitionData['acquisition']) && count($acquisitionData['acquisition'])>0){
					$fileID = $service->getFileInfo($acquisitionData['acquisition']->ppa_id);
				} else if($findLeadData->ppa_id!=""){
					$fileID = $service->getFileInfo($findLeadData->ppa_id);
				} else {
					$fileID = false;
				}				
				if($fileID){
					$url = $fileID->alternateLink;
					$this->lead_model->from_litigation_update($findLeadData->id,array("execute_ppa"=>2));
					$adminUsers = $this->user_model->findAdminUsers();		
					if(count($adminUsers)>0){
						$send = 0;
						foreach($adminUsers as $user){
							$sendID = $this->notification_model->insert(array('user_id'=>$user->id,'message'=>'Waiting for your approval for <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));
							$updateReport = $this->opportunity_model->updateReport($leadID,array('execute_ppa'=>1));
							/*Check DB Message and Subject*/
							$getData = $this->general_model->getTaskAccToType('PPA');
							$subject="Execute PPA";
							$message = "PPA Execute.";
							if(count($getData)>0){
								$subject = $getData->subject;
								$message = $getData->message;
							}
							/*End Checking*/
							$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'PPA','status'=>'0'));
							
							if($approvalRequest>0){
								$send=1;
							}
						}
						if($send>0){
							$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Executed PPA",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);	
							echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'PPA','status'=>'0','task_id'=>$approvalRequest));
						} else {
							echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'PPA','status'=>'0'));
						}
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	public function ppaExecuted(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){	
				$dateUpdate = date('Y-m-d H:i:s');
				$updateReport = $this->opportunity_model->updateReport($leadID,array('execute_ppa'=>3));
				$updateReport = $this->lead_model->from_litigation_update($findLeadData->id,array("ppa_execute"=>2,'ppa_date'=>$dateUpdate));
				if($updateReport){
					$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"PPA Executed",'opportunity_id'=>1,'create_date'=>$dateUpdate);
					$this->user_model->addUserHistory($user_history);
					$getLeadData = $this->lead_model->getLeadData($leadID);
					$buttonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"PPA_EXECUTE");
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Funds Transfer','create_date'=>date('Y-m-d H:i:s')));	
					echo json_encode(array('send'=>'1','button_data'=>$buttonData,'date_update'=>date('m d,y',strtotime($dateUpdate))));
				} else {
					echo json_encode(array('send'=>'0'));
				}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	public function patents_charts_from_lead(){
		$left_chart_patent = $this->input->post('left_chart_patent');
		$leadID = $this->input->post('lead');
		if((int)$leadID>0){
			if(!empty($left_chart_patent)){
				$leadData = $this->lead_model->getLeadData($leadID);
				$acquisitionData = $this->acquisition_model->getData($leadID);
				if(count($acquisitionData)>0){
					$chartLeftData = json_decode($left_chart_patent,true);
					if(count($chartLeftData)>0){
						$chartArray = array();
						$this->opportunity_model->deleteLeftChart($leadID);
						for($i=0;$i<count($chartLeftData);$i++){
							if(!empty($chartLeftData[$i]->{'0'})){
								$chartArray[] =array($chartLeftData[$i]->{'0'},$chartLeftData[$i]->{'2'},$chartLeftData[$i]->{'1'});
								$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]->{'0'},'patents'=>$chartLeftData[$i]->{'1'},'applications'=>$chartLeftData[$i]->{'2'}),false);
							} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
								$chartArray[] =array($chartLeftData[$i]['0'],$chartLeftData[$i]['2'],$chartLeftData[$i]['1']);
								$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]['0'],'patents'=>$chartLeftData[$i]['1'],'applications'=>$chartLeftData[$i]['2']),false);
							}							
						}
						$chartArray[] = array(null,null,null);
						$left_chart_patent = json_encode($chartArray);
					}
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_update_left_chart',
						CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => array(
							'license_number' => $acquisitionData['acquisition']->store_name,
							'chart_left'=>$left_chart_patent
						)
					));
					// Send the request & save response to $resp
					$resp = curl_exec($curl);	
					if($resp){						
						/*$getData = json_decode($resp);
						$chartLeftData = json_decode($left_chart_patent,true);
						if(count($chartLeftData)>0){
							$this->opportunity_model->deleteLeftChart($leadID);
							for($i=0;$i<count($chartLeftData);$i++){
								if(!empty($chartLeftData[$i]->{'0'})){
									$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]->{'0'},'applications'=>$chartLeftData[$i]->{'1'},'patents'=>$chartLeftData[$i]->{'2'}),false);
								} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
									$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]['0'],'applications'=>$chartLeftData[$i]['1'],'patents'=>$chartLeftData[$i]['2']),false);
								}							
							}
						}*/
					}
				}
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://synpat.com/license_update_left_chart.php',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'serial_number' => $leadData->serial_number,
						'chart_left'=>$this->input->post('left_chart_patent'),
						'lead_name' =>$leadData->lead_name
					)
				));
				$resp = curl_exec($curl);	
				if($resp){
					/*End API Request*/
					
				}				
			}
		}
		redirect('dashboard/findLeadPrePatent/'.$leadID);
	}
	
	
	function docket_save(){
		if(isset($_POST) && count($_POST)>0){
			$docketData = $this->input->post();
			$other = $docketData['other'];
			$leadID = $docketData['other']['lead_id'];
			$acquisitionData = $this->acquisition_model->getData($leadID);
			$activeButton = 0;
			if(isset($docketData['acquisition']['active_button'])){
				$activeButton = $docketData['acquisition']['active_button'];
			}
			if(isset($docketData['embed'])){
				/*Embedding Code*/
				if(!empty($docketData['embed']['order_name']) && (!empty($docketData['embed']['cc_embed_code']) || !empty($docketData['embed']['par_embed_code']))){
					if(count($acquisitionData)>0){
						/*API for APPDMIN*/
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_update_embed_code',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'license_number' => $acquisitionData['acquisition']->store_name,
								'claimchart'=>$docketData['embed']['cc_embed_code'],
								'prior_art'=>$docketData['embed']['par_embed_code'],
								'active_button'=>$activeButton,
							)
						));
						// Send the request & save response to $resp
						$resp = curl_exec($curl);
						if($resp){
							/*End API Request*/
							$getDataRes = json_decode($resp);
							if(count($getDataRes)>0 && (int)$getDataRes->send>0){

								$saveData = $this->acquisition_model->updateData($leadID,array('cc_embed_code'=>$docketData['embed']['cc_embed_code'],'order_name'=>$docketData['embed']['order_name'],'par_embed_code'=>$docketData['embed']['par_embed_code']));
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Insert embed coded.",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								if($saveData){  
									$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
								} else {
									$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');				
								}
							
							} else {
								$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
							
							}								
						} else {
							$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
							
						}
					}
				}
			}
			/*End Embedding Code*/
			/*Other Data*/
			
			if(count($acquisitionData)>0){
				$other = $docketData['other'];
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				/*'damage'=>$other['damage'],
						'syndication_file'=>$other['syndication_file'],*/
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_update_other_docs',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',   
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'license_number' => $acquisitionData['acquisition']->store_name,
						'ppa'=>$other['ppa'],
						'pla'=>$other['pla'],
						'rtp'=>$other['rtp'],
						'ppp'=>$other['ppp'],
						'sla'=>$other['sla'],
						'damage'=>'',
						'syndication_file'=>'',
						'potential_data'=>$other['potential_data'],
						'commitment_data'=>$other['commitment_data'],
						'chart_left'=>$other['chart_left'],
						'chart_middle'=>$other['chart_middle'],
						'chart_right'=>$other['chart_right'],
						'comparable'=>$other['comparable'],
						'damages'=>$other['damages']
					)
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);	
				
				if($resp){
					/*End API Request*/
					$getData = json_decode($resp);
					
					if(count($getData)>0 && (int)$getData->send>0){
						$activeButton = 0;
						$potential_participants = 0;
						$final_participants = 0;
						if(isset($docketData['acquisition']['active_button'])){
							$activeButton = $docketData['acquisition']['active_button'];
						}
						if(!empty($docketData['acquisition']['final_participants'])){
							$final_participants = $docketData['acquisition']['final_participants'];
						}
						if(!empty($docketData['acquisition']['potential_participants'])){
							$potential_participants = $docketData['acquisition']['potential_participants'];
						}
						$saveData = $this->acquisition_model->updateData($leadID,array('ppa'=>$other['ppa'],'pla'=>$other['pla'],'rtp'=>$other['rtp'],'ppp'=>$other['ppp'],'sla'=>$other['sla'],'syndication'=>'','damage'=>'','category'=>$docketData['acquisition']['category'],'active_button'=>$activeButton,'potential_participants'=>$potential_participants,'final_participants'=>$final_participants,'regular_license_starts'=>$docketData['acquisition']['regular_license_starts'],'late_license_starts'=>$docketData['acquisition']['late_license_starts'],'store'=>$docketData['acquisition']['store'],'cost_price'=>$docketData['acquisition']['cost_price']));
						/*Link update in invitation template*/
						if((int)$docketData['acquisition']['category']>0){
							$getTemplate = $this->general_model->findInvitationTemplateByLead($leadID);
							if(count($getTemplate)>0){
								$template = $getTemplate->template_html;					
								$urlName ="";
								$category_list = $this->customer_model->categoryList(0);
								$lead_data = $this->lead_model->getLeadData($leadID);
								if(!empty($acquisitionData['acquisition']->store_name)):				
								if($acquisitionData['acquisition']->category>0){
									if(count($category_list)>0){
										for($cc=0;$cc<count($category_list);$cc++){
											if($category_list[$cc]->id==$acquisitionData['acquisition']->category){
												$urlName = $category_list[$cc]->name;
												$urlName = str_replace('','_',$urlName);
												$urlName = str_replace('-','_',$urlName);
												$urlName = str_replace('&',' ',$urlName);
												$urlName = str_replace('&amp;',' ',$urlName);
												$urlName = preg_replace("/[^a-zA-Z0-9_\s-]/", "_", $urlName);
												$urlName = preg_replace('/-/','_',$urlName);
												$urlName = preg_replace('/[\s,\-!]/',' ',$urlName);
												$urlName = preg_replace('/\s+/','_',$urlName);
											}
										}
									}
									if(!empty($urlName)){
										$urlName ='/departments/'.$urlName.'-'.$acquisitionData['acquisition']->category.'/'.$lead_data->serial_number.'/';
									}
								}
								endif;
								if(!empty($urlName)){
									$urlName = "http://www.synpat.com".$urlName;
								}
								$template =  str_replace('link-data-href=""',"href='".$urlName."'",$template);
								$getTemplate->template_html = $template;
								$this->general_model->updateTemplate((array)$getTemplate,$getTemplate['id']);
							}
						}
						/*End link update in invitation*/
						/*Insert Data Potential*/
						$potentialSyndicateData = json_decode($other['potential_data']);
						if(count($potentialSyndicateData)>0){
							$this->opportunity_model->deletePotential($leadID);
							for($i=0;$i<count($potentialSyndicateData);$i++){
								if(!empty($potentialSyndicateData[$i]->{'0'})){
									$this->opportunity_model->savePotential(array('lead_id'=>$leadID,'participate'=>$potentialSyndicateData[$i]->{'0'},'fees'=>$potentialSyndicateData[$i]->{'1'}),false);
								} else if(!is_object($potentialSyndicateData[$i]) && isset($potentialSyndicateData[$i]['0']) && !empty($potentialSyndicateData[$i]['0'])){
									$this->opportunity_model->savePotential(array('lead_id'=>$leadID,'participate'=>$potentialSyndicateData[$i]['0'],'fees'=>$potentialSyndicateData[$i]['1']),false);
								}							
							}
						}
						/*chart Left*/
						$chartLeftData = json_decode($other['chart_left']);
						$chartData = json_encode(array(array(null,null,null)));
						if(count($chartLeftData)>0){
							$chartArray = array();
							$this->opportunity_model->deleteLeftChart($leadID);
							for($i=0;$i<count($chartLeftData);$i++){
								if(!empty($chartLeftData[$i]->{'0'})){
									$chartArray[] = array($chartLeftData[$i]->{'0'},$chartLeftData[$i]->{'2'},$chartLeftData[$i]->{'1'});
									$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]->{'0'},'applications'=>$chartLeftData[$i]->{'1'},'patents'=>$chartLeftData[$i]->{'2'}),false);
								} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
									$this->opportunity_model->saveChartLeft(array('lead_id'=>$leadID,'country'=>$chartLeftData[$i]['0'],'applications'=>$chartLeftData[$i]['1'],'patents'=>$chartLeftData[$i]['2']),false);
									$chartArray[] = array($chartLeftData[$i]['0'],$chartLeftData[$i]['2'],$chartLeftData[$i]['1']);
								}							
							}
							$chartData = json_encode($chartArray);
						}
						$leadData = $this->lead_model->getLeadData($leadID);
						
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'http://synpat.com/license_update_left_chart.php',
							CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'serial_number' => $leadData->serial_number,
								'chart_left'=>$chartData,
								'lead_name' =>$leadData->lead_name
							)
						));
						$resp = curl_exec($curl);	
						
						if($resp){
							/*End API Request*/
							
						}
						
						
						
						/*Chart Middle*/					
						$chartMiddleData = json_decode($other['chart_middle']);
						if(count($chartMiddleData)>0){
							$this->opportunity_model->deleteMiddleChart($leadID);
							for($i=0;$i<count($chartMiddleData);$i++){
								if(!empty($chartMiddleData[$i]->{'0'})){
									$this->opportunity_model->saveChartMiddle(array('lead_id'=>$leadID,'technologies'=>$chartMiddleData[$i]->{'0'},'data'=>$chartMiddleData[$i]->{'1'}),false);
								} else if(!is_object($chartMiddleData[$i]) && isset($chartMiddleData[$i]['0']) && !empty($chartMiddleData[$i]['0'])){
									$this->opportunity_model->saveChartMiddle(array('lead_id'=>$leadID,'technologies'=>$chartMiddleData[$i]['0'],'data'=>$chartMiddleData[$i]['1']),false);
								}							
							}
						}
						
						$chartRightData = json_decode($other['chart_right']);
						if(count($chartRightData)>0){
							$this->opportunity_model->deleteRightChart($leadID);
							for($i=0;$i<count($chartRightData);$i++){
								if(!empty($chartRightData[$i]->{'0'})){
									$this->opportunity_model->saveChartRight(array('lead_id'=>$leadID,'Year'=>$chartRightData[$i]->{'0'},'data'=>$chartRightData[$i]->{'1'}),false);
								} else if(!is_object($chartRightData[$i]) && isset($chartRightData[$i]['0']) && !empty($chartRightData[$i]['0'])){
									$this->opportunity_model->saveChartRight(array('lead_id'=>$leadID,'Year'=>$chartRightData[$i]['0'],'data'=>$chartRightData[$i]['1']),false);
								}							
							}
						}
						
						$chartComparableData = json_decode($other['comparable']);
						if(count($chartComparableData)>0){
							$this->opportunity_model->deleteComparable($leadID);
							for($i=0;$i<count($chartComparableData);$i++){
								if(!empty($chartComparableData[$i]->{'0'})){
									$this->opportunity_model->saveComparable(array('lead_id'=>$leadID,'file_name'=>$chartComparableData[$i]->{'0'},'file_link'=>$chartComparableData[$i]->{'1'}),false);
								} else if(!is_object($chartComparableData[$i]) && isset($chartComparableData[$i]['0']) && !empty($chartComparableData[$i]['0'])){
									$this->opportunity_model->saveComparable(array('lead_id'=>$leadID,'file_name'=>$chartComparableData[$i]['0'],'file_link'=>$chartComparableData[$i]['1']),false);
								}							
							}
						}
						
						$chartDamagesData = json_decode($other['damages']);
						
						if(count($chartDamagesData)>0){
							$this->opportunity_model->deleteDamages($leadID);
							
							for($i=0;$i<count($chartDamagesData);$i++){
								if(!empty($chartDamagesData[$i]->{'0'})){
									$this->opportunity_model->saveDamage(array('lead_id'=>$leadID,'file_name'=>$chartDamagesData[$i]->{'0'},'file_link'=>$chartDamagesData[$i]->{'1'}),false);
								} else if(!is_object($chartDamagesData[$i]) && isset($chartDamagesData[$i]['0']) && !empty($chartDamagesData[$i]['0'])){
									$this->opportunity_model->saveDamage(array('lead_id'=>$leadID,'file_name'=>$chartDamagesData[$i]['0'],'file_link'=>$chartDamagesData[$i]['1']),false);
								}							
							}
						}
						
						
						$userID = $this->session->userdata['id'];
						if(isset($other['id'])){
							$approvalRequest = $this->opportunity_model->updateApprovalData($acquisitionData['acquisition']->id,array('status'=>'1'));
						}									
						$updateReport = $this->opportunity_model->updateReport($leadID,array('other_doc'=>2));
						$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Other docs uploaded",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);	
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
					}								
				}
			}
			$imageLeft = "";
			$imageMiddle = "";
			$imageRight = "";
			$imageFour ="";
			$imageFive ="";
			$imageSix ="";
			$imageSeven ="";
			$imageEight ="";
			$imageNine ="";
			$imageTen ="";
			$imageEleven ="";
			$imageTwelve ="";
			if(isset($docketData['other']['image_left'])){
				$imageLeft = $this->config->base_url().'public/upload/'.$docketData['other']['image_left'];
			}
			if(isset($docketData['other']['image_middle'])){
				$imageMiddle = $this->config->base_url().'public/upload/'.$docketData['other']['image_middle'];
			}
			if(isset($docketData['other']['image_right'])){
				$imageRight = $this->config->base_url().'public/upload/'.$docketData['other']['image_right'];
			}
			if(isset($docketData['other']['image_four'])){
				$imageFour = $this->config->base_url().'public/upload/'.$docketData['other']['image_four'];
			}
			if(isset($docketData['other']['image_five'])){
				$imageFive = $this->config->base_url().'public/upload/'.$docketData['other']['image_five'];
			}
			if(isset($docketData['other']['image_six'])){
				$imageSix = $this->config->base_url().'public/upload/'.$docketData['other']['image_six'];
			}
			if(isset($docketData['other']['image_seven'])){
				$imageSeven = $this->config->base_url().'public/upload/'.$docketData['other']['image_seven'];
			}
			if(isset($docketData['other']['image_eight'])){
				$imageEight = $this->config->base_url().'public/upload/'.$docketData['other']['image_eight'];
			}
			if(isset($docketData['other']['image_nine'])){
				$imageNine = $this->config->base_url().'public/upload/'.$docketData['other']['image_nine'];
			}
			if(isset($docketData['other']['image_ten'])){
				$imageTen = $this->config->base_url().'public/upload/'.$docketData['other']['image_ten'];
			}
			if(isset($docketData['other']['image_eleven'])){
				$imageEleven = $this->config->base_url().'public/upload/'.$docketData['other']['image_eleven'];
			}
			if(isset($docketData['other']['image_twelve'])){
				$imageTwelve = $this->config->base_url().'public/upload/'.$docketData['other']['image_twelve'];
			}			
			$do['docket']['image_left'] = $imageLeft;
			$do['docket']['image_middle'] = $imageMiddle;
			$do['docket']['image_right'] = $imageRight;
			$do['docket']['image_four'] = $imageFour;
			$do['docket']['image_five'] = $imageFive;
			$do['docket']['image_six'] = $imageSix;
			$do['docket']['image_seven'] = $imageSeven;
			$do['docket']['image_eight'] = $imageEight;
			$do['docket']['image_nine'] = $imageNine;
			$do['docket']['image_ten'] = $imageTen;
			$do['docket']['image_eleven'] = $imageEleven;
			$do['docket']['image_twelve'] = $imageTwelve;
			$do['docket']['option_expiration_data'] = $docketData['docket']['option_expiration_data'];
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$do['docket']['seller_asking_price'] = $findLeadData->expected_price;
				if(count($acquisitionData)>0){
					
					/*API for APPDMIN*/
					$curl = curl_init();
					// Set some options - we are passing in a useragent too here
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/update_docket_data', 
						CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
						CURLOPT_POST => 1, 
						CURLOPT_POSTFIELDS => array(
							'license_number' => $acquisitionData['acquisition']->store_name,
							'image_left'=>$imageLeft,
							'image_middle'=>$imageMiddle,
							'image_right'=>$imageRight,
							'image_four'=>$imageFour,
							'image_five'=>$imageFive,
							'image_six'=>$imageSix,
							'image_seven'=>$imageSeven,
							'image_eight'=>$imageEight,
							'image_nine'=>$imageNine,
							'image_ten'=>$imageTen,
							'image_eleven'=>$imageEleven,
							'image_twelve'=>$imageTwelve,
							'expiration_date'=>$do['docket']['option_expiration_data'],
							'asking_price'=>$do['docket']['seller_asking_price'],
							'person_name'=>$docketData['docket']['person_name'],
							'person_email'=>$docketData['docket']['person_email'],
							'person_phone'=>$docketData['docket']['person_phone'],
							'person_picture'=>$this->config->base_url().'public/upload/'.$docketData['docket']['person_picture'],
						)
					));
					// Send the request & save response to $resp
					$resp = curl_exec($curl);
					
					if($resp){
						$getData = json_decode($resp);						
						if(count($getData)>0 && (int)$getData->send>0){
							$saveData = $this->acquisition_model->updateData($leadID,$do['docket']);
							if($saveData){
								$this->lead_model->from_litigation_update($leadID,array("option_expiration_date"=>$do['docket']['option_expiration_data']));
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Upload example images.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
							}							
						}
					}
					/*END API*/
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid Lead</p>');				
			}			
			
			/*End Image Files*/			
			/*redirect('opportunity/docket/'.$leadID);*/
			die;
		} else {
			echo "Invalid Request";
			die;
		}
	}
	
	
	
	public function startDD(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$acquisitionData = $this->acquisition_model->getData($leadID);
				if(count($acquisitionData)>0){
					
					$patentData = $this->opportunity_model->getAllAssets($leadID);
					/*$patentData = json_decode($findLeadData->patent_data);*/
										
				/*API for APPDMIN*/
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://appadmin.synpat.com/Users/add_due_diligence_patent',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'license_number' => $acquisitionData['acquisition']->store_name,
						'patent_data'=>json_encode($patentData)
					)
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp){
				
				
				}
				
				/*END API*/
				$cipoUsers = $this->user_model->findUserByType('8');		
				if(count($cipoUsers)>0){
					$acquisitionData = $this->acquisition_model->getData($leadID);
					if(count($acquisitionData)>0){
						$url = "http://www.synpat.com/store/".$acquisitionData['acquisition']->store_name."/due_diligence.php";
						$sendID = $this->notification_model->insert(array('user_id'=>$cipoUsers->id,'message'=>'Start DD <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));
						$updateReport = $this->opportunity_model->updateReport($leadID,array('start_dd'=>1));
						/*Check DB Message and Subject*/
						$getData = $this->general_model->getTaskAccToType('DD');
						$subject="Start DD";
						$message = "Start DD.";
						if(count($getData)>0){
							$subject = $getData->subject;
							$message = $getData->message;
						}
						/*End Checking*/						
						$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'DD','status'=>'0'));
						if($approvalRequest>0){
							$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to CIPO to start work on DD",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);	
							echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'DD','status'=>'0','task_id'=>$approvalRequest,'create_date'=>date('Y-m-d H:i:s')));
						} else {
							echo json_encode(array('send'=>'0'));
						}
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}
			}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;		
	}
	
	function updateAllInvitees($lead_id){
		$acquisitionData = $this->acquisition_model->getData($lead_id);
		$getAllContact = $this->lead_model->findSalesActivityCompanies($lead_id);
		
		if(count($acquisitionData)>0){
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/add_invitees',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'license_number' => $acquisitionData['acquisition']->store_name,
					'invitees'=>json_encode($getAllContact)
				)
			));
			
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			echo "<pre>";
			print_r($resp);
			die;
			if($resp){		
			}							
		}
	}
	
	
	public function invitees(){
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			if(isset($getData['invite']['lead_id']) && (int) $getData['invite']['lead_id']>0){
				if(count($getData['invite']['contact_id'])>0){
					/*$saveData = 0;
					$deleteOldData = $this->opportunity_model->deleteInvitees($getData['invite']['lead_id']);
					$contactsUpdate = array();
					$getAllContact = array();
					if(!isset($_SESSION)){
						session_start();
					}
					$contacts = $_SESSION['all_contacts'];
					foreach($getData['invite']['contact_id'] as $contactID){
						$saveData = $this->opportunity_model->insertInvitees(array('lead_id'=>$getData['invite']['lead_id'],'contact_id'=>$contactID));
						if(count($contacts)>0){
							foreach($contacts as $contact){
								if($contact->id==$contactID){
									$getAllContact[] = $contact;
								}
							}
						}						
					}*/
					/*$getAllContact = $this->opportunity_model->findInvitees(implode(",",$getData['invite']['contact_id']));*/
					if($saveData>0){
						/*Send Invitees*/
						$acquisitionData = $this->acquisition_model->getData($getData['invite']['lead_id']);
						if(count($acquisitionData)>0){
							$curl = curl_init();
							// Set some options - we are passing in a useragent too here
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://appadmin.synpat.com/Users/add_invitees',
								CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									'license_number' => $acquisitionData['acquisition']->store_name,
									'invitees'=>json_encode($getAllContact)
								)
							));
							// Send the request & save response to $resp
							$resp = curl_exec($curl);
							if($resp){		
							}							
						}
						/*End Invitees*/
						$user_history = array('lead_id'=>$getData['invite']['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Added list of invitees",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);	
						$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
						redirect('opportunity/docket/'.$getData['invite']['lead_id']);
					}else{
						$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
						redirect('opportunity/docket/'.$getData['invite']['lead_id']);
					}
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
					redirect('opportunity/docket/'.$getData['invite']['lead_id']);
				}				
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');
			redirect('dashboard');
		}		
	}
	
	function addCompanyBulk(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$listCompany =  explode(PHP_EOL,$getData['company']['list']);
			if(count($listCompany)>0){
				foreach($listCompany as $company){
					$getCompanyId = $this->customer_model->insertCompany(array('company_name'=>$company));
					if($getCompanyId>0){
						$this->customer_model->addCompanySector(array("sector_id"=>0,"company_id"=>$getCompanyId));
						$data = 1;
					}
				}
				
			}
		}
		echo $data;
		die;
	}
	
	public function add_company(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$message = "";
			if(isset($getData['company'])){
				unset($getData['company']['street']);
				unset($getData['company']['zip']);
				if((int)$getData['company']['id']==0){
					unset($getData['company']['id']);
					$check = $this->customer_model->checkCompanyExist(trim($getData['company']['company_name']));
					if($check==0){
						$sectorId = $getData['company']['sector'];
						unset($getData['company']['sector']);
						$getCompanyId = $this->customer_model->insertCompany($getData['company']);
						if($getCompanyId>0){
							$message = "Insert new company.";
							$getData['company']['id'] = $getCompanyId;
							$this->customer_model->addCompanySector(array("sector_id"=>$sectorId,"company_id"=>$getCompanyId));
						} 
					}else {
						$getCompanyDetail = $this->customer_model->getCompanyData(trim($getData['company']['company_name']));
						if(count($getCompanyDetail)>0){
							$sectorId = $getData['company']['sector'];
							unset($getData['company']['sector']);
							$this->customer_model->updateCompanyData($getCompanyDetail->id,$getData['company']);
							$message = "Update company data.";
							if($sectorId>0){
								$this->customer_model->updateCompanySectorWithCompanyID($getCompanyDetail->id,array("sector_id"=>$sectorId));
							}
							$getData['company']['id'] = $getCompanyDetail->id;
						}
					}
				} else {
					$getCompanyDetail = $this->customer_model->getCompanyDataByID((int)$getData['company']['id']);
					unset($getData['company']['id']);
					if(count($getCompanyDetail)>0){
						$sectorId = $getData['company']['sector'];
						unset($getData['company']['sector']);
						$this->customer_model->updateCompanyData($getCompanyDetail->id,$getData['company']);
						$message = "Update company data.";
						if($sectorId>0){
							$this->customer_model->updateCompanySectorWithCompanyID($getCompanyDetail->id,array("sector_id"=>$sectorId));
						}
						$getData['company']['id'] = $getCompanyDetail->id;
					}
				}
				if((int)$getData['company']['id']>0){
					/*Delete Preferences*/
					$this->customer_model->deleteCustomerPreference($getData['company']['id']);
					if(isset($getData['preferences']) && is_array($getData['preferences']['departments']) && count($getData['preferences']['departments'])>0){
						foreach($getData['preferences']['departments'] as $dept){
							$this->customer_model->insertCustomerPreference(array("customer_id"=>$getData['company']['id'],"preference_id"=>$dept));
						}							
					}
					if(isset($getData['sub']) && is_array($getData['sub']['departments']) && count($getData['sub']['departments'])>0){
						foreach($getData['sub']['departments'] as $dept){
							$this->customer_model->insertCustomerPreference(array("customer_id"=>$getData['company']['id'],"preference_id"=>$dept));
						}							
					}
				}
				if((int)$getData['company']['id']>0){
					$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>$message,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);	
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
					$data = (int)$getData['company']['id'] ;
				}				
			}
		}
		echo $data; 
		die;
	}
	
	public function add_contact(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$getData = $this->input->post();
			$message = "";
			if(isset($getData['invitee']) && count($getData['invitee'])>0){
				$recordID = 0;
				if((int)$getData['invitee']['id']==0){
					if($getData['invitee']['company_id']==""){
						if(!empty($getData['invitee']['company_name'])){
							$companyID = $this->customer_model->insertCompany(array('company_name'=>$getData['invitee']['company_name'],'start_date'=>'0000-00-00 00:00:00','end_date'=>'0000-00-00 00:00:00'));
						}
						$getData['invitee']['company_id'] = $companyID;
					}
					if(isset($getData['invitee']['company_name'])){
						unset($getData['invitee']['company_name']);
					}
					$saveData = $this->client_model->insert($getData['invitee']);
					$recordID = $saveData;
					$message = "New record in contacts";
				} else {
					if(isset($getData['invitee']['company_name'])){
						unset($getData['invitee']['company_name']);
					}
					$recordID = $getData['invitee']['id'];
					unset($getData['invitee']['id']);
					if(!isset($getData['invitee']['gateway'])){
						$getData['invitee']['gateway'] = 0;
					}
					if(!isset($getData['invitee']['no_contact'])){
						$getData['invitee']['no_contact'] = 0;
					}
					$saveData = $this->client_model->update($recordID,$getData['invitee']);	
					$saveData =$recordID;
					$message = "Update contacts";					
				}				
				if($saveData>0){
				
					$user_history = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'message'=>$message,'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);	
					$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
					$data = $recordID ;
				}
			}
		}	
		echo $data;
		die;		
	}
	
	public function dueDiligenceFileMaker(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$acquisitionData = $this->acquisition_model->getData($leadID);
				if(count($acquisitionData)>0){
					$url = "http://www.synpat.com/store/".$acquisitionData['acquisition']->store_name."/due.php";
					$adminUsers = $this->user_model->findAdminUsers();
					if(count($adminUsers)>0){
						$send = 0;
						foreach($adminUsers as $user){
							$sendID = $this->notification_model->insert(array('user_id'=>$user->id,'message'=>'Due Diligence FileMaker <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));
							/*Check DB Message and Subject*/
							$getData = $this->general_model->getTaskAccToType('DD_FILE_MAKER');
							$subject="DD File Maker";
							$message = "DD File Maker.";
							if(count($getData)>0){
								$subject = $getData->subject;
								$message = $getData->message;
							}
							/*End Checking*/
							$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'DD_FILE_MAKER','status'=>'0'));
							if($approvalRequest>0){
								$send = 1;
							}
						}
						if($send>0){
							$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to Admin for Due diligence filemaker.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
							$this->user_model->addUserHistory($user_history);	
							echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'DD_FILE_MAKER','status'=>'0','task_id'=>$approvalRequest));
						} else {
							echo json_encode(array('send'=>'0'));
						}
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}				
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	public function startMarketResearch(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$adminUsers = $this->user_model->findAdminUsers();
				if(count($adminUsers)>0){
					$send = 0;
					$acquisitionData = $this->acquisition_model->getData($leadID);
					$url="";
					if(count($acquisitionData)>0){
						$url = "http://www.synpat.com/store/".$acquisitionData['acquisition']->store_name."/due.php";
					}
					foreach($adminUsers as $user){
						$sendID = $this->notification_model->insert(array('user_id'=>$user->id,'message'=>'Market Research <a href="'.$url.'" target="_BLANK">'.$url.'</a>'));		
						/*Check DB Message and Subject*/
						$getData = $this->general_model->getTaskAccToType('MARKET_RESEARCH');
						$subject="Market Research";
						$message = "Market Research.";
						if(count($getData)>0){
							$subject = $getData->subject;
							$message = $getData->message;
						}
						/*End Checking*/						
						$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'MARKET_RESEARCH','status'=>'0'));
						if($approvalRequest>0){
							$send = 1;
						}
					}
					if($send>0){
						$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to Admin for market research.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);	
						echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$user->id,'lead_id'=>$leadID,'doc_url'=> $url,'type'=>'MARKET_RESEARCH','status'=>'0','task_id'=>$approvalRequest));
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	public function orderDamagesByCIPO(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$cipoUsers = $this->user_model->findUserByType('8');
				if(count($cipoUsers)>0){
					$sendID = $this->notification_model->insert(array('user_id'=>$cipoUsers->id,'message'=>'Upload Damages Report'));
					/*Check DB Message and Subject*/
					$getData = $this->general_model->getTaskAccToType('ORDER_DAMAGES');
					$subject="Order Damages";
					$message = "Order Damages.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					/*End Checking*/
					$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> '','type'=>'ORDER_DAMAGES','status'=>'0'));
					$updateReport = $this->opportunity_model->updateReport($leadID,array('order_damage'=>1));
					if($updateReport){
						$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to CIPO for uploading damages report.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);	
						echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> '','type'=>'ORDER_DAMAGES','status'=>'0','task_id'=>$approvalRequest,'create_date'=>date('Y-m-d H:i:s')));
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	function uploadImageFile($tmp,$target){
		$imageFile = "";
		$target_file = $_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$target;
		if(move_uploaded_file($tmp, $target_file)){
			$imageFile= $this->config->base_url().'public/upload/'.$target;
		}
		return $imageFile;
	}
	
	public function docket_entry(){
		if(isset($_POST) && count($_POST)>0){
			$docket = $this->input->post();
			$imageLeft = "";
			$imageMiddle = "";
			$imageRight = "";
			$imageFour ="";
			$imageFive ="";
			$imageSix ="";
			$imageSeven ="";
			$imageEight ="";
			$imageNine ="";
			$imageTen ="";
			$imageEleven ="";
			$imageTwelve ="";
			if(isset($_FILES) && !empty($_FILES['docket']['name']['image_left'])){
				$imageLeft = $this->uploadImageFile($_FILES['docket']["tmp_name"]['image_left'],$_FILES['docket']['name']['image_left']);
			}
			if(isset($_FILES) && !empty($_FILES['docket']['name']['image_middle'])){
				$imageMiddle= $this->uploadImageFile($_FILES['docket']["tmp_name"]['image_middle'],$_FILES['docket']['name']['image_middle']);
			}
			if(isset($_FILES) && !empty($_FILES['docket']['name']['image_right'])){
				$imageRight= $this->uploadImageFile($_FILES['docket']["tmp_name"]['image_right'],$_FILES['docket']['name']['image_right']);
			}
			
			$docket['docket']['image_left'] = $imageLeft;
			$docket['docket']['image_middle'] = $imageMiddle;
			$docket['docket']['image_right'] = $imageRight;
			
			
			/*Save to DOCKET*/
			$findLeadData = $this->opportunity_model->getLeadData($docket['docket']['lead_id']);
			if(count($findLeadData)>0){
				$docket['docket']['seller_asking_price'] = $findLeadData->expected_price;
				$acquisitionData = $this->acquisition_model->getData($docket['docket']['lead_id']);
				if(count($acquisitionData)>0){
					/*API for APPDMIN*/
					$curl = curl_init();
					// Set some options - we are passing in a useragent too here
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://appadmin.synpat.com/Users/update_docket_data', 
						CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => array(
							'license_number' => $acquisitionData['acquisition']->store_name,
							'image_left'=>$imageLeft,
							'image_middle'=>$imageMiddle,
							'image_right'=>$imageRight,
							'expiration_date'=>$docket['docket']['option_expiration_data'],
							'asking_price'=>$docket['docket']['seller_asking_price'],
						)
					));
					// Send the request & save response to $resp
					$resp = curl_exec($curl);
					if($resp){
						$getData = json_decode($resp);
						if(count($getData)>0 && (int)$getData->send>0){
							$saveData = $this->acquisition_model->updateData($docket['docket']['lead_id'],$docket['docket']);
							if($saveData){
								$user_history = array('lead_id'=>$docket['docket']['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Upload example images.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
								$this->user_model->addUserHistory($user_history);	
								$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
							} else {
								$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
							}
							redirect('opportunity/docket/'.$docket['docket']['lead_id']);
						} else {
							$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
							redirect('opportunity/docket/'.$docket['docket']['lead_id']);
						}
					} else {
						$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
						redirect('opportunity/docket/'.$docket['docket']['lead_id']);
					}
					/*END API*/
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
					redirect('dashboard');
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime.</p>');
			redirect('dashboard');
		}
	}
	
	public function uploadDocumentByCIPO(){
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('token');
			$findLeadData = $this->opportunity_model->getLeadData($leadID);
			if(count($findLeadData)>0){
				$cipoUsers = $this->user_model->findUserByType('8');
				if(count($cipoUsers)>0){
					$sendID = $this->notification_model->insert(array('user_id'=>$cipoUsers->id,'message'=>'Upload Other Docs'));
					/*Check DB Message and Subject*/
					$getData = $this->general_model->getTaskAccToType('OTHER_DOCS');
					$subject="Other Docs";
					$message = "Other Docs.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					/*End Checking*/
					$approvalRequest = $this->opportunity_model->sendApprovalRequest(array('subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> '','type'=>'OTHER_DOCS','status'=>'0'));
					$updateReport = $this->opportunity_model->updateReport($leadID,array('other_doc'=>1));
					if($updateReport){
						$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Send request to CIPO for upload documents.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);	
						echo json_encode(array('send'=>'1','subject'=>$subject,'message'=>$message,'execution_date'=>date("Y-m-d"),'parent_id'=>0,'from_user_id'=>$this->session->userdata['id'],'user_id'=>$cipoUsers->id,'lead_id'=>$leadID,'doc_url'=> '','type'=>'OTHER_DOCS','status'=>'0','task_id'=>$approvalRequest));
					} else {
						echo json_encode(array('send'=>'0'));
					}
				} else {
					echo json_encode(array('send'=>'0'));
				}
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}
		die;
	}
	
	public function getCheckListContact(){
		$getData = array();
		if(isset($_POST) && count($_POST)>0){
			$sector_id = $this->input->post('token');
			$selected = array();
			if(!empty($sector_id)){
				$sector_id = json_decode($sector_id );
				if(!isset($_SESSION)){
					session_start();
				}
				$contacts = $_SESSION['all_contacts'];	
				if(count($contacts)>0){
					foreach($contacts as $contact){
						if(is_array($contact->sectors)){
							foreach($contact->sectors as $sector){
								if(in_array($sector,$sector_id)){
									if(!in_array($contact->id,$selected)){
										$selected[] = $contact->id;
										$getData[] = $contact;
									}
									
								}
							}
						}
					}
				}				
				/*$getData = $this->client_model->getContactListBySectorID($sector_id);*/
			}
		}
		echo json_encode($getData);
		die;
	}
	
	public function damages_report(){
		if(isset($_POST) && count($_POST)>0){
			$other = $this->input->post('other');
			if((int)$other['lead_id']>0){
				$findLeadData = $this->opportunity_model->getLeadData($other['lead_id']);
				if(count($findLeadData)>0){
					if(!empty($other['damages_report'])){
						/*Send API Request*/
						$acquisitionData = $this->acquisition_model->getData($other['lead_id']);
						if(count($acquisitionData)>0){
							/*API for APPDMIN*/
							$curl = curl_init();
							// Set some options - we are passing in a useragent too here
							curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_URL => 'http://appadmin.synpat.com/Users/license_update',
								CURLOPT_USERAGENT => 'Send Request for create demo portfolio',
								CURLOPT_POST => 1,
								CURLOPT_POSTFIELDS => array(
									'license_number' => $acquisitionData['acquisition']->store_name,
									'damage'=>$other['damages_report']
								)
							));
							// Send the request & save response to $resp
							$resp = curl_exec($curl);
							if($resp){
								$getData = json_decode($resp);
								/*End API Request*/
								if(count($getData)>0 && (int)$getData->send>0){
									$saveData = $this->acquisition_model->updateData($other['lead_id'],array('other_damages'=>$other['damages_report']));
									$userID = $this->session->userdata['id'];
									$approvalRequest = $this->opportunity_model->updateApprovalData($other['id'],array('status'=>'1'));
									$updateReport = $this->opportunity_model->updateReport($other['lead_id'],array('order_damage'=>2));
									if($saveData){
										$user_history = array('lead_id'=>$other['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Damages reports uploaded",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
										$this->user_model->addUserHistory($user_history);	
										$this->session->set_flashdata('message','<p class="alert alert-success">Record Saved!</p>');
									} else {
										$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');				
									}
									redirect('dashboard');
								} else {
									$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
									redirect('dashboard');
								}								
							} else {
								$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
								redirect('dashboard');
							}						
						} else {
							$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
							redirect('dashboard');
						}					
					} else {
						$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
						redirect('dashboard');
					}
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Please try after sometime!</p>');	
					redirect('dashboard');
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid request!</p>');	
				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid request!</p>');	
			redirect('dashboard');
		}
	}
	
	public function task(){
		if(isset($_POST) && count($_POST)>0){
			$task = $this->input->post('task');
			$record=0;
			$type="";
			$email="";
			if(count($task)>0 && (int)$task['lead_id']>0){
				
				if((int)$task['id']==0){
					unset($task['id']);
					$sendEmail = 0;
					if(isset($task['send_email']) && $task['send_email']!=""){
						$sendEmail = $task['send_email'];
					}
					unset($task['send_email']);
					if(isset($task['record']) && $task['record'] >0){
						$record= $task['record'];
						$type= $task['type'];
					}
					unset($task['record']);
					unset($task['type']);
					if(!isset($task['execution_date']) || empty($task['execution_date'])){
						$task['execution_date'] = date('Y-m-d');
					}
					$send = $this->opportunity_model->sendApprovalRequest($task);
					$checkUserData = $this->user_model->getUserData($task['user_id']);
					$activities = $this->input->post('activity');
					if(!empty($activities['activity_type']) && (int)$activities['activity_type']>0){
						$personID = $activities['person_id'];
						$companyID = $activities['company_id'];
						if((int)$personID>0 && (int)$companyID>0){
							$act['lead_id'] = $task['lead_id'];
							$act['contact_id'] = $personID;
							$act['company_id'] = $companyID;
							$act['note'] = $task['subject'];
							$act['activity_date'] = date('Y-m-d H:i:s');
							$act['user_id'] = $task['user_id'];
							$act['subject'] = "";
							$act['task_id'] = $send;
							$act['type'] = 10;
							switch((int)$activities['activity_type']){
								case 1:
									$this->lead_model->insetSalesActivity($act);
								break;
								case 2:
									$this->lead_model->insertAcquistionActivity($act);
								break;
								case 3:
									$this->lead_model->insertPreSaleActivity($act);
								break;
							}
						}
					}
					if(count($checkUserData) && (int)$sendEmail==1){
						/*$leadData = $this->lead_model->getLeadData($task['lead_id']);*/
						/*$this->load->library('email');
						$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
						$this->email->to($checkUserData->email); 
						$this->email->set_mailtype('html'); 
						$this->email->subject($task['subject']);*/
						if(!isset($_SESSION)){
							session_start();
						}
						$email = array();
						$email['to'] = $checkUserData->email;
						$email['to_name'] = $checkUserData->name;
						$email['subject'] =  $task['subject'];
						$email['cc'] = '';
						$email['bcc'] = '';
						$email['thread_id'] = '';
						$email['message_id'] = '';
						$email['from_name'] = $this->session->userdata['name'];
						$email['from_email'] = $this->session->userdata['email'];
						$email['reference'] = '';
						$message = '<html><head><title>New Task</title></head><body>'.$task['message'];
						if(isset($task['doc_url']) && !empty($task['doc_url'])){
							$message .='<p>Document URL: <a href="'.$task['doc_url'].'">'.$task['doc_url'].'</a></p>';
						}
						$message .='<p>For Backyard <a href="'.$this->config->base_url().'dashboard/index/'.$task['lead_id'].'">Click here</a>.</p>';
						$signature = "";
						if(!empty($_POST['other']['signature'])){
							$signature = $_POST['other']['signature'];
						}
						$message .=$signature.'</body></html>';						
						$email['message'] = $message;
						$this->load->library('DriveServiceHelper');
						$email['href'] = array();
						$service = new GmailServiceHelper();
						$service->setAccessToken($_SESSION['another_access_token']);
						$send  = $service->sendMessage($email);
						/*$this->email->message($message);	
						$this->email->send();*/
					}
				} else {
					unset($task['record']);
					unset($task['type']);
					$send = $this->opportunity_model->updateApprovalData($task['id'],$task);
				}				
				if($send>0){
					if($record>0){
						if(!empty($type) && !empty($email)){
							$allEmails = $email;
							$dataArray = array("lead_id"=>$task['lead_id'],"type"=>$type,"total_patent"=>0,"create_date"=>date('Y-m-d H:i:s'),"expert"=>$allEmails,'file_url'=>$task['doc_url']);
							$this->user_model->due_logtime($dataArray);
						}
					}
					$user_history = array('lead_id'=>$task['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Create a task",'opportunity_id'=>1,'create_date'=>date('Y-m-d H:i:s'));
					$this->user_model->addUserHistory($user_history);
					$this->session->set_flashdata('message','<p class="alert alert-success">Task Send!</p>');					
					$this->load->library('user_agent');
					if(isset($_POST['other']['return'])){
						/*redirect('opportunity/docket/'.$task['lead_id']);*/
					}  else if ($this->agent->is_referral()){
						/*redirect($this->agent->referrer());*/
					} else {
						/*redirect('dashboard/index/'.$task['lead_id']);*/
					}					
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Server busy please try after sometime.</p>');
					if(isset($_POST['other']['return'])){
						/*redirect('opportunity/docket/'.$task['lead_id']);*/
					} else {
						/*redirect('dashboard/index/'.$task['lead_id']);*/
					}						
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid data!</p>');
				/*redirect('dashboard');*/	
			}		
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid data!</p>');
			/*redirect('dashboard');*/	
		}
		die;
	}
	
	public function find_task(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){			
			if($this->input->post('token')!=""){
				$data = $this->opportunity_model->findTask($this->input->post('token'));
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function reply_task(){
		if(isset($_POST) && count($_POST)>0){
			$task = $this->input->post('reply');
			if(count($task)>0 && isset($task['forward']) && (int)$task['forward']==1){
				/* Change Status of Parent ID*/
				$parentTask= array('status'=>2,"completion_date"=>date('Y-m-d'));
				$updated = $this->opportunity_model->updateApprovalData($task['parent_id'],$parentTask);
				if($updated>0){
					if(empty($task['execution_date'])){
						$task['execution_date'] = date('Y-m-d');
					}
					$newTask= array("from_user_id"=>$task['from_user_id'],"lead_id"=>$task['lead_id'],"execution_date"=>$task['execution_date'],"message"=>$task['message'],"subject"=>$task['subject'],"user_id"=>$task['user_id'],"status"=>0,"type"=>$task['type'],"parent_id"=>$task['parent_id']);
					$send = $this->opportunity_model->sendApprovalRequest($newTask);
					if($send>0){
						$user_history = array('lead_id'=>$task['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Forward a task",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
						$sendEmail = 0;
						if(isset($task['send_email']) && $task['send_email']!=""){
							$sendEmail = $task['send_email'];
						}
						unset($task['send_email']);
						$checkUserData = $this->user_model->getUserData($task['user_id']);
						if(count($checkUserData) && (int)$sendEmail==1){
							$leadData = $this->lead_model->getLeadData($task['lead_id']);
							$this->load->library('email');
							$this->email->from($this->session->userdata['email'], $this->session->userdata['name']);
							$this->email->to($checkUserData->email); 
							$this->email->set_mailtype('html'); 
							$this->email->subject($task['subject']);
							$this->email->message('<html><head><title>New Task</title></head><body>'.$task['message'].'<p><a href="'.$this->config->base_url().'dashboard/index/'.$leadData->id.'">Click</a> href.</p></body></html>');	
							$this->email->send();
						}
						$this->session->set_flashdata('message','<p class="alert alert-success">Task Created!</p>');
						$this->load->library('user_agent');
						if ($this->agent->is_referral()){
							redirect($this->agent->referrer());
						} else {
							redirect('dashboard/index/'.$task['lead_id']);
						}					
					} else {
						$this->session->set_flashdata('message','<p class="alert alert-warning">Server busy please try after sometime!</p>');
						redirect('dashboard/index/'.$task['lead_id']);	
					}
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Server busy please try after sometime!</p>');
					redirect('dashboard/index/'.$task['lead_id']);	
				}
			}  else if(count($task)>0 && isset($task['reply']) &&(int) $task['reply']==2){
				$parentTask = array('status'=>2,"completion_date"=>date('Y-m-d'));
				$updated = $this->opportunity_model->updateApprovalData($task['parent_id'],$parentTask);
				$this->lead_model->deleteTask($task['parent_id']);
				if($updated>0){
					$user_history = array('lead_id'=>$task['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Delete a task",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					$this->approved_doc($task['parent_id']);
					$this->session->set_flashdata('message','<p class="alert alert-success">Remove Task</p>');
					$this->load->library('user_agent');
					if ($this->agent->is_referral()){
						redirect($this->agent->referrer());
					} else {
						redirect('dashboard/index/'.$task['lead_id']);
					}
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Server busy please try after sometime!</p>');
					redirect('dashboard/index/'.$task['lead_id']);
				}
			} else if(count($task)>0 && isset($task['complete']) &&(int) $task['complete']==1){
				/*Change Status to complete and Insert completion date*/
				$parentTask = array('status'=>1,"completion_date"=>date('Y-m-d'));
				$updated = $this->opportunity_model->updateApprovalData($task['parent_id'],$parentTask);
				if($updated>0){
					$user_history = array('lead_id'=>$task['lead_id'],'user_id'=>$this->session->userdata['id'],'message'=>"Complete a task",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
						$this->user_model->addUserHistory($user_history);
					$this->approved_doc($task['parent_id']);
					$this->session->set_flashdata('message','<p class="alert alert-success">Task Completed!</p>');
					$this->load->library('user_agent');
					if ($this->agent->is_referral()){
						redirect($this->agent->referrer());
					} else {
						redirect('dashboard/index/'.$task['lead_id']);
					}
				} else {
					$this->session->set_flashdata('message','<p class="alert alert-warning">Server busy please try after sometime!</p>');
					redirect('dashboard/index/'.$task['lead_id']);
				}
			} else {
				$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid data!</p>');
				redirect('dashboard');	
			}			
		} else {
			$this->session->set_flashdata('message','<p class="alert alert-warning">Invalid data!</p>');
			redirect('dashboard');	
		}
	}
	
	function deleteContact(){
		$post = $this->input->post('delete_link');
		if($post!=""){
			$this->client_model->deleteContact($post);
			if(!isset($_SESSION)){
				session_start();
			}
			unset($_SESSION['all_contacts']);
		} else{			
		}
		die;
	}
	
	function deleteCompany(){
		$post = $this->input->post('delete_link');
		if($post!=""){
			$this->client_model->deleteCompany($post);			
		} else{		
		}
		die;
	}
	
	function deleteCompanyInBulk(){
		$post = $this->input->post('delete_link');
		if($post!=""){
			$contacts = explode(',',$post);
			foreach($contacts as $contact){
				$contact = trim($contact);
				if(!empty($contact)){
					$this->client_model->deleteCompany($contact);	
				}
			}					
		} else{		
		}
		die;
	}
	
	function findContact($ID =null){
		$post = $this->input->post('edit_link');
		$data = array();
		if((int)$post>0){
			$data = $this->client_model->find_contact($post);
		}
		echo json_encode($data);
		die;
	}
	
	function findCompany($ID=null){
		$post = $this->input->post('edit_link');
		$data = array();
		if((int)$post>0){
			$data = $this->customer_model->getCompanyDataByID($post);
		}
		echo json_encode($data);
		die;
	}
    
	public function market_form($leadID=null,$t=null){
		if($leadID!=null && $t!=null){
			$data = array();
			$data['lead_data'] = $this->lead_model->getLeadData($leadID);
			$data['t'] = $t;
			$this->layout->layout='opportunity';	
			$this->layout->title_for_layout = 'Market';
			$this->layout->render('opportunity/market_form',$data);
		} else {
			echo "Sorry page not found!";
			die;
		}
	}
	
	public function contact_form($leadID=null,$type=null,$parent=null){
		if($leadID!=null && $type!=null && $parent!=null){
			$data = array();
			if($parent!='company_form'){
				$data['lead_data'] = $this->lead_model->getLeadData($leadID);
			}			
			$data['type'] = $type;
			$data['parentElement'] = $parent;
			$data['contacts'] = $this->client_model->getAllContacts();
			$this->layout->layout='opportunity';	
			$this->layout->title_for_layout = 'Contact';
			$this->layout->render('opportunity/contact_form',$data);
		} else {
			echo "Server busy, Please try after sometime.";
			die;
		}
	}
	
	public function emailSetting(){
		$service_url = 'https://www.google.com/accounts/ClientLogin';
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, "Email=webmaster@synpat.com&Passwd=l3n0v0@123&accountType=GOOGLE&service=apps");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		$response = json_decode($curl_response);
		//print_r($curl_response);
		$authString =  substr($curl_response,strrpos($curl_response,"Auth"));
		$str = explode("=",$authString);
		$service_url = 'https://apps-apis.google.com/a/feeds/emailsettings/2.0/synpat.com/uzi/signature';
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/atom+xml','Authorization: GoogleLogin '.$authString,'| tidy -xml -indent -quiet'));
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		curl_close($curl);
		if(!empty($curl_response)){
			$xmlString =  substr($curl_response,strrpos($curl_response,"<?xml"));
			$xml = new SimpleXmlElement($xmlString);
			$alias_email = $xml->children('apps', true)->property[0]->attributes();
			if(count($alias_email)>0){
				echo $alias_email['value'];
			}
		}
		die;
	}
	
    public function contact(){   
		$data = array();
		$data['contacts'] = $this->client_model->getAllContacts();
		$this->layout->layout='opportunity';	
		$this->layout->title_for_layout = 'Contact';
		$this->layout->render('opportunity/contact',$data);		
    }
	
	public function companies(){
		$data = array();
		$data['companies'] = $this->client_model->getAllCompaniesWithMem();
		$this->layout->layout='opportunity';	
		$this->layout->title_for_layout = 'Companies';
		$this->layout->render('opportunity/companies',$data);	
	}
	
	public function all_list(){
		$serialNumber = $this->input->get('alx', 0);
		$leadNumber = $this->input->get('plx', 0);
		if($serialNumber==0 && $leadNumber>0){
			$getLeadData = $this->lead_model->getLeadData($leadNumber);
			if(count($getLeadData)>0){
				$serialNumber = $getLeadData->serial_number;
			}
		}
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://synpat.com/file_all.php',
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				's' => 0,
				'serial_number'=>$serialNumber
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$data['lists'] = array();
		if($resp!=""){
			$getAllData = json_decode($resp);
			if(isset($getAllData->all_list) && isset($getAllData->single_data)){
				$data['lists'] = $getAllData->all_list;
				$data['single_data'] = $getAllData->single_data;
			}
			
			$data['lead_number'] = $leadNumber;
			$data['serial_number'] = $serialNumber;
		}	
		$this->layout->layout='opportunity';	
		$this->layout->title_for_layout = 'Synpat.com Patent Form List';
		$this->layout->render('opportunity/all_list',$data);
	}
	
	public function delete_request_lead(){
		$data = "0";
		if(isset($_POST) && count($_POST) > 0){
            $getData = $this->input->post();
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/delete_request_lead',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => $getData['i']
				)
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			if($resp){
				$data = $resp;
			}
		}
		echo $data;
		die;
	}
	
	public function c_l(){
		$data = "0";
		/*if(isset($_POST) && count($_POST) > 0){
            $getData = $this->input->post();
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://synpat.com/count_leads.php',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					's' => $getData['s']
				)
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			if($resp){
				$data = $resp;
			}
		}*/
		echo $data;
		die;
	}
	
	public function delete_web_lead(){
		$data = "0";
		if(isset($_POST) && count($_POST) > 0){
            $getData = $this->input->post();
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://synpat.com/delete_request_lead.php',
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => $getData['i']
				)
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			if($resp){
				$data = $resp;
			}
		}
		echo $data;
		die;
	}
	
	public function sr_attached(){
		if(isset($_POST) && count($_POST) > 0){
            $getData = $this->input->post();
			if($getData['sr']!="0"){
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://synpat.com/update_data.php',
					CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'id' => $getData['contact'],
						"serial_number"=>$getData['sr']
					)
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				if($resp){
					echo $resp;
				}
			}
		}
		die;
	}
    
}

/* End of file opportunity.php */
/* Location: ./application/controllers/opportunity.php */