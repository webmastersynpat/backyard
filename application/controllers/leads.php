<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leads extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('DriveServiceHelper'); 
		$service = new GmailServiceHelper();
		if(!isset($this->session->userdata['type']) || empty($this->session->userdata['email'])){
			/*$this->session->set_flashdata('error','Please login first!');*/
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
		$this->layout->layout='default';
		$this->load->model('lead_model');
		$this->load->model('email_model');
		$this->load->model('user_model');
		$this->load->model('general_model');
		$this->load->model('opportunity_model');
		$this->load->model('client_model');
	}
	/*
	public function clear_cache(){
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }*/
	public function index()
	{
		$this->layout->auto_render=false;		
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('client/index');
	}
	
	function deleteFreePreContact(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->deleteFreePreContact($_POST['id']);
		}
		echo $data;
		die;
	}
	
	function findSalesCompaniesPeopleInLead(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->getSalesContatcsByLead($_POST['l']);
		}
		echo json_encode($data);
		die;
	}
	
	function findSalesCompaniesInLead(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->client_model->getAllCompaniesWithMem($_POST['l']);
		}
		echo json_encode($data);
		die;
	}
	
	function import_file_dr1(){
		$url = "https://api.fullcontact.com/v2/cardReader?webhookUrl=http://backyard.synpat.com/leads/import_data_from_full_contact_webhook&apiKey=8c3cd4e4ac3c5abf&verified=medium";
		
		$imageAsBase64 = base64_encode(file_get_contents('http://goo.gl/0SHjZ4')); 
		$requestBody = array(); 
		$requestBody['front'] = $imageAsBase64;
		$connection = curl_init(); 
		curl_setopt($connection, CURLOPT_URL, $url); 
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($connection, CURLOPT_POST, true); 
		curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode($requestBody)); 
		curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($connection);
		echo "<pre>";
		echo "1";
		print_r($result);
		die;
	}
	
	function import_data_from_full_contact_webhook(){
		$saveData = array('message'=>$this->input->post());
		$data = $this->lead_model->insertFreePreContacts($saveData);
	}
	
	function fetch_list_full_contacts($page=0){
		$data = $this->retrieveContactFromFullContactsAPI(0);
		die;
	}
	
	function retrieveContactFromFullContactsAPI($page){
		$url = "https://api.fullcontact.com/v2/cardReader?page=".$page."&apiKey=8c3cd4e4ac3c5abf";
		$data = array();
		$connection = curl_init(); 
		curl_setopt($connection, CURLOPT_URL, $url); 
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($connection);
		if(!empty($result)){
			$data = json_decode($result,true);
			if(count($data)>0){
				if($data['count']>0){
					for($i=0;$i<count($data['results']);$i++){
						if(isset($data['results'][$i]['contact'])){
							$row = $data['results'][$i]['contact'];
							$email = "";
							if(isset($row['emails'][0]['value']) && !empty($row['emails'][0]['value'])){
								$email = $row['emails'][0]['value'];
							}
							$insert = true;
							$fullContact= $data['results'][$i]['contactId'];
							if(!empty($email)){
								$checkData =  $this->lead_model->getContactFullContactID($fullContact);
								if(count($checkData)==0){
									$checkFromPreContacts = $this->lead_model->getPreContactFullContactID($fullContact);
									if(count($checkFromPreContacts)>0){
										$insert = false;
										/*Update PreContact*/
										$this->updatePreContactByFullContact($data['results'][$i]);
									}
								} else {
									$insert = false;
									/*Update Contact*/
									$this->updateContactByFullContact($data['results'][$i]);
								}
							}
							if($insert===true && !empty($data['results'][$i]['contactId'])):
								$insertData = $this->arrangeCardData($data['results'][$i]);
								if(count($insertData)>0 && trim($insertData['fullcontact'])!=''){
									$this->lead_model->insertPreContacts($insertData);
								}						
							endif;
						}
					}
					if($page<$data['totalPages']-1){
						$data = $this->retrieveContactFromFullContactsAPI((int)$page+1);
					}
				}		
			}
		}
		return $data;
	}
	
	function updatePreContactByFullContact($contactDetail){
		$insertData = $this->arrangeCardData($contactDetail);
		if(count($insertData)>0){
			$fullContact= $contactDetail['contactId'];
			$this->lead_model->updatePreContactByFullContact($fullContact,$insertData);
		}	
	}
	
	function arrangeCardData($contactDetail){
		$insertData = array();
		if(!empty($contactDetail['contactId'])){
			$address="";
			$row = $contactDetail['contact'];
			$email = "";
			if(isset($row['emails'][0]['value']) && !empty($row['emails'][0]['value'])){
				$email = $row['emails'][0]['value'];
			}
			if(isset($row['addresses'])){
				if(isset($row['addresses'][0]['streetAddress']) && !empty($row['addresses'][0]['streetAddress'])){
					$address = $row['addresses'][0]['streetAddress'];
				}
				if(isset($row['addresses'][0]['locality']) && !empty($row['addresses'][0]['locality'])){
					$address .= ', '.$row['addresses'][0]['locality'];
				}
				if(isset($row['addresses'][0]['region']) && !empty($row['addresses'][0]['region'])){
					$address .= ', '.$row['addresses'][0]['region'];
				}
				if(isset($row['addresses'][0]['postalCode']) && !empty($row['addresses'][0]['postalCode'])){
					$address .= ', '.$row['addresses'][0]['postalCode'];
				}
				if(isset($row['addresses'][0]['country']) && !empty($row['addresses']['country'])){
					$address .= ', '.$row['addresses'][0]['country'];
				}
			}
			$firstName ='';
			$lastName ='';
			if(isset($row['name']['givenName']) && !empty($row['name']['givenName'])){
				$firstName = $row['name']['givenName'];
			}
			if(isset($row['name']['familyName']) && !empty($row['name']['familyName'])){
				$lastName = $row['name']['familyName'];
			}						
			$company = "";
			if(isset($row['organizations'][0]['name']) && !empty($row['organizations'][0]['name'])){
				$company = $row['organizations'][0]['name'];
			}
			$title = "";
			if(isset($row['organizations'][0]['title']) && !empty($row['organizations'][0]['title'])){
				$title = $row['organizations'][0]['title'];
			}
			$telephone = "";
			$cellphone = "";
			if(isset($row['phoneNumbers']) && count($row['phoneNumbers'])>0){
				for($p=0;$p<count($row['phoneNumbers']);$p++){
					if(isset($row['phoneNumbers'][$p]['type']) && $row['phoneNumbers'][$p]['type']=="Work"){
						$cellphone = $row['phoneNumbers'][$p]['value'];
					}
					if(isset($row['phoneNumbers'][$p]['type']) && $row['phoneNumbers'][$p]['type']=="General/phone"){
						$telephone = $row['phoneNumbers'][$p]['value'];
					}
				}
			}	
			$fullContact= $contactDetail['contactId'];
			$imgCard ='';
			if(count($row['photos'])>0){
				foreach($row['photos'] as $photo){
					if($photo['type']=='BusinessCard'){
						$imgCard = $photo['value'];
					}
				}
			}
			$linkedIN = "";
			$twitter = "";
			if(isset($row['accounts']) && count($row['accounts'])>0){
				foreach($row['accounts'] as $account){
					if($account['domain']=='twitter.com'){
						$twitter = $account['urlString'];
					}
					if($account['domain']=='linkedin.com'){
						$linkedIN = $account['urlString'];
					}
				}
			}
			$insertData = array('first_name'=>$firstName,'last_name'=>$lastName,'email'=>$email,'company_name'=>$company,'job_title'=>$title,'address'=>$address,'telephone'=>$telephone,'fullcontact'=>$fullContact,'cellphone'=>$cellphone,'profile_url'=>$linkedIN,'twitter'=>$twitter,'img_card'=>$imgCard);
		}
		return $insertData;
	}
	
	function updateContactByFullContact($contactDetail){
		$insertData = $this->arrangeCardData($contactDetail);
		$insertData['phone'] = $insertData['cellphone'];
		$insertData['linkedin_url'] = $insertData['profile_url'];
		unset($insertData['company_name']);
		unset($insertData['cellphone']);
		unset($insertData['profile_url']);
		if(count($insertData)>0){
			$fullContact= $contactDetail['contactId'];
			$this->lead_model->updateContactByFullContact($fullContact,$insertData);
		}		
	}
	
	function import_file_dr(){
		$data = array('error'=>'','data'=>'');
		if(isset($_POST) && count($_POST)>0){
			if(!empty($_POST['id'])){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$getFileInfo = $service->getFileInfo($_POST['id']);	
				$fileNameImage = '';
				if(is_object($getFileInfo) && isset($getFileInfo->title) && !empty($getFileInfo->title)){
					$downloadContent = $service->downloadFile($getFileInfo);
					if($downloadContent!=null){
						$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/businesscards/'.$getFileInfo->title, "w+");
						fwrite($fh, $downloadContent);
						fclose($fh);
						$filePath = $_SERVER['DOCUMENT_ROOT'].'/public/upload/businesscards/'.$getFileInfo->title;
						if(is_readable($filePath) ){
							$fileNameImage = 'http://backyard.synpat.com/public/upload/businesscards/'.$getFileInfo->title;
							$applicationId = 'synpat_business_cards';
							$password = 'Ec605h+GvehNKmxnLaSRgagj';
							$url = 'http://cloud.ocrsdk.com/processImage?language=english&exportFormat=rtf';
							$curlHandle = curl_init();
							curl_setopt($curlHandle, CURLOPT_URL, $url);
							curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
							curl_setopt($curlHandle, CURLOPT_POST, 1);
							curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
							$post_array['my_file'] = new CurlFile($filePath,$getFileInfo->mimeType,$getFileInfo->title);
							/*array(
							  "my_file"=>"@".$filePath,
							);*/
							curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $post_array); 
							$response = curl_exec($curlHandle);
							if($response == TRUE) {
								$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
								curl_close($curlHandle);
								$xml = simplexml_load_string($response);
								if($httpCode == 200) {
									$arr = $xml->task[0]->attributes();
									$taskStatus = $arr["status"];
									if($taskStatus == "Queued") {
										// Task id
										$taskid = $arr["id"];  
										$url = 'http://cloud.ocrsdk.com/getTaskStatus';
										$qry_str = "?taskid=".$taskid;
										while(true)
										{
											sleep(5);
											$curlHandle = curl_init();
											curl_setopt($curlHandle, CURLOPT_URL, $url.$qry_str);
											curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
											curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
											$response = curl_exec($curlHandle);
											$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
											curl_close($curlHandle);
											$xml = simplexml_load_string($response);
											if($httpCode == 200) {
												$arr = $xml->task[0]->attributes();
												$taskStatus = $arr["status"];
												if($taskStatus == "Queued" || $taskStatus == "InProgress") {
													continue;
												}
												if($taskStatus == "Completed") {
													break;
												}
												if($taskStatus == "ProcessingFailed") {
													$data = array('error'=>"Task processing failed: ".$arr["error"],'data'=>'');
													break;
												}
												$data = array('error'=>"Unexpected task status ".$taskStatus,'data'=>'');
											} else {
												if(property_exists($xml, "message")) {
													$data = array('error'=>$xml->message,'data'=>'');
												} else {
													$data = array('error'=>$response,'data'=>'');
												}
											}
										}
										if(isset($arr["resultUrl"]) && !empty($arr["resultUrl"])){
											$url = $arr["resultUrl"];  
											$curlHandle = curl_init();
											curl_setopt($curlHandle, CURLOPT_URL, $url);
											curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
											curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
											$response = curl_exec($curlHandle);
											curl_close($curlHandle);	
											$fileNameRtf = time().'.rtf';
											$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/businesscards/'.$fileNameRtf, "w+");
											fwrite($fh, $response);
											fclose($fh);
											$this->load->library('RtfHtml');
											$reader = new RtfReader();
											$rtf = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/public/upload/businesscards/'.$fileNameRtf);
											$reader->Parse($rtf);
											/*$reader->root->dump();*/
											$formatter = new RtfHtml();
											$content =  $formatter->Format($reader->root);
											$data = array('error'=>'','data'=>$content,'file'=>$fileNameImage);
										} else {
											$data = array('error'=>"Process failed",'data'=>'');
										}										
									} else {
										$data = array('error'=>$taskStatus,'data'=>'');
									}									
								} else {
									if(property_exists($xml, "message")) {
										$data = array('error'=>$xml->message,'data'=>'');
									} else {
										$data = array('error'=>$response,'data'=>'');
									}
								}
							} else {
								$errorText = curl_error($curlHandle);
								curl_close($curlHandle);
								$data = array('error'=>$errorText,'data'=>'');
							}
						}						
					}
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function litigation(){
		redirect('dashboard');
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$litigationData = $this->input->post();			
            $litigationData['litigation']['user_id']= $this->session->userdata['id'];
			$litigationData['litigation']['create_date']= date('Y-m-d H:i:s');
			$litigationData['litigation']['type']= 'Litigation';
			//$litigationData['litigation']['lead_name']= 'Litigation';
            $attractive = $litigationData['litigation']['attractive'];
			unset($litigationData['litigation']['attractive']);
			if(!isset($litigationData['comment'])){
				$litigationData['comment'] = 0;
			}
			if(!isset($litigationData['complete'])){
				$litigationData['complete'] = 0;
			}
			$user_id  = $this->session->userdata['id'];
			if((int)$litigationData['litigation']['id']==0){
				$saveLitigation = $this->lead_model->from_litigation_insert($litigationData['litigation']);
				$lead_id = $this->db->insert_id();				
				if($lead_id>0){
					/*Create Folder in Drive*/
					$this->load->library("DriveServiceHelper");
					$service = new DriveServiceHelper();
					$leadFolderID = $service->getFileIdByName('Leads');
					if($leadFolderID){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $leadFolderID );
						$getFolderInfo = $service->createSubFolder($litigationData['litigation']['lead_name'].'_folder',$fileParent);
						if($getFolderInfo){
							/*Save Folder ID  in DB*/
							$this->lead_model->from_litigation_update($lead_id,array('folder_id'=>$getFolderInfo));
						}
					}						
					/**/
					$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Created lead From Litigation.','create_date'=>$litigationData['litigation']['create_date']));
					$this->session->set_flashdata('message','Record added.');
				}			
			}else {
				$saveLitigation = $this->lead_model->from_litigation_update($litigationData['litigation']['id'],$litigationData['litigation']);
				$lead_id = $saveLitigation;
				$this->session->set_flashdata('message','Record updated.');
				$this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$saveLitigation,'message'=>'Update lead From Litigation.','create_date'=>$litigationData['litigation']['create_date']));
			}
			if($saveLitigation>0){			 
				          
			} else {
				$this->session->set_flashdata('error','Please try after sometime.');
				redirect('leads/litigation');
			}
		}
		redirect("dashboard");
	}
 

	public function market(){		
		$data = array();
		$this->load->library('user_agent');
		if(isset($_POST) && count($_POST)>0){
			redirect("dashboard");
		} else {
			$this->load->library('DriveServiceHelper');
			if(!isset($_SESSION)){
				session_start();
			}
			$service = new GmailServiceHelper();
			$data = array();
			if(!isset($_SESSION['access_token'])){	
				if(!isset($_REQUEST['code']) && !isset($_GET['error'])){
					$data['auth_url'] = $service->createAuthUrl();
					$data['messages'] = array();
					$_SESSION['clicked_url'] = "dashboard";
				}else if(isset($_GET['error'])){
					$_SESSION['guess_login'] = 1;
					redirect("dashboard");
				} else {
					$service->clientAuthenticate($_REQUEST['code']);
					if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']="dashboard"){
						unset($_SESSION['clicked_url']);
						unset($_SESSION['clickedd_url']);
						unset($_SESSION['clickedddd_url']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						$_SESSION['access_token'] = $service->getAccessToken();						
						redirect("dashboard");
					}
				}				
			} else {
				if(isset($_SESSION['clicked_url']) && $_SESSION['clicked_url']="dashboard"){
					unset($_SESSION['clicked_url']);
					unset($_SESSION['clickedd_url']);
					unset($_SESSION['clickedddd_url']);
					if(isset($_REQUEST['code'])){
						$service->clientAuthenticate($_REQUEST['code']);
						$_SESSION['another_access_token'] = $service->getAccessToken();
						$_SESSION['access_token'] = $service->getAccessToken();
					}					
					redirect("dashboard");
				}
			}
		}
		redirect("dashboard");
	}
	
	
	public function findBoxList(){
		$data = array('detail'=>array(),'boxes'=>array());
		if(isset($_POST) && count($_POST)>0){
		  $type = "Market";
            if($this->input->post('t')!=false && $this->input->post('t')!=""){
                $type =$this->input->post('t'); 
            }
			
			$data['detail'] = $this->lead_model->findOneLitigationWithIncomplete($this->input->post('boxes'),$type);
			if(isset($data['detail'][0]['litigation']) && (int)$data['detail'][0]['litigation']->serial_number!=0){				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://synpat.com/file_get.php',
					CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						's' => $data['detail'][0]['litigation']->serial_number
					)
				));
				
				$resp = curl_exec($curl);
				if($resp){
					$data['detail'][0]['patentees'] = json_decode($resp);
				}
			} else {
				$data['detail'][0]['patentees'] = array();
			}
			$this->load->model('customer_model');
			$category_list = $this->customer_model->categoryList(0);
			if(count($data['detail'][0]['acquisitions'])>0 && !empty($data['detail'][0]['acquisitions']->store_name)){
				$urlName ="";
				if($data['detail'][0]['acquisitions']->category>0){
					if(count($category_list)>0){
						for($cc=0;$cc<count($category_list);$cc++){
							if($category_list[$cc]->id==$data['detail'][0]['acquisitions']->category){
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
						$urlName ='/departments/'.$urlName.'-'.$data['detail'][0]['acquisitions']->category.'/'.$data['detail'][0]['litigation']->serial_number.'/';						
					}
				}
				$data['detail'][0]['portfolio'] = $urlName;		
			}
		}		
		echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);		
		die;
	}
	
	public function findAcquisitionAndSalesData(){
		$data = array('acquisition'=>array(),'sales_activity'=>array(),'presales_activity'=>array());
		if(isset($_POST) && count($_POST)>0){
			$data['sales_activity'] = $this->lead_model->getSalesActivity($this->input->post('boxes'));
			$data['presales_activity'] = $this->lead_model->getPreSalesActivity($this->input->post('boxes'));
			$data['broker_as_companies'] = $this->lead_model->findLeadCompanyBrokerDetails($this->input->post('boxes'));
			$data['acquisition'] = $this->lead_model->getAcquisitionActivity($this->input->post('boxes'));
		}
		echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);		
		die;
	}
	
	public function change_order(){
		if(isset($_POST) && count($_POST)>0){
			$data = $this->input->post('spliting');
			if(!empty($data) && count($data)>0){				
				foreach($data as $val){
					if(!empty($val)){
						$newString = explode("_",$val);
						if(count($newString)==2){
							$this->lead_model->updateButton(array("sort"=>$newString[1]),$newString[0]);
						}
					}
				}				
			}
		}
		die;
	}
	
	public function findEmailBoxes(){
		$data = array('boxes'=>array());
		
			$openEmailBox = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(14,$this->session->userdata['modules_assign'])){
					$openEmailBox = false;
				}
			}
			if($openEmailBox===true):
		
		if(isset($_POST) && count($_POST)>0){
			/*$data['boxes']= $this->lead_model->findBoxList($this->input->post('boxes'),$this->input->post('type'));*/
			$data['acquisition']= $this->lead_model->getAcquisitionActivity($this->input->post('boxes'));
		}
		endif;
		echo json_encode($data);		
		die;
	}
	
	
	public function findLeadButtonData(){
		$data = array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->findLeadButtonData($this->input->post('l'),$this->input->post('b'));
		}
		echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
		die;
	}
	
	function all_active_system($activityType,$emailID){
		$data['lead'] = $this->lead_model->allLeadsWithActivity($activityType);
		$data['activity'] = $activityType;
		$data['email'] = $emailID;
		$this->layout->layout='opportunity';
		$this->layout->auto_render=false;	
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('leads/all_active_system',$data);
	}
	
	function pre_contacts(){
		$data['pre_contacts'] = $this->lead_model->getPreContacts();
		$data['contacts'] = $this->client_model->getAllContacts();
		$data['free_pre_contacts'] = $this->lead_model->getFreePreContacts();
		/*$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();		
		$data['drive'] = $service->getFileIDFromChildern("0B61I0m5ybHrFaWx4MFl2X0kyemc");*/
		$this->layout->layout='opportunity';
		$this->layout->auto_render=false;	
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('leads/pre_contacts',$data);
	}
	
	function pre_companies(){
		$data['pre_contacts'] = $this->lead_model->getPreCompanies();
		$data['contacts'] = $this->client_model->getAllCompaniesWithMem();
		$this->layout->layout='opportunity';
		$this->layout->auto_render=false;	
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('leads/pre_companies',$data);
	}
	
	function litigation_scrap(){
		$data['litigation_scrap'] = $this->lead_model->getLitigationScrap();
		$this->layout->layout='opportunity';
		$this->layout->auto_render=false;	
		$this->layout->title_for_layout = 'Backyard Litigaion Scrap';
		$this->layout->render('leads/litigation_scrap',$data);
	}
	
	function deleteLitigationScrap(){
		if(isset($_POST) && count($_POST)>0){
			echo $this->lead_model->deleteLitigationScrap($this->input->post('id'));
		}
		die;
	}
	
	function remove_precontacts(){
		if(isset($_POST) && count($_POST)>0){
			echo $this->lead_model->deletePreContact($this->input->post('id'));
		}
		die;
	}
	
	function remove_precompanies(){
		if(isset($_POST) && count($_POST)>0){
			echo $this->lead_model->deletePreCompany($this->input->post('id'));
		}
		die;
	}
	
	public function findDriveFiles(){
		$data = array('drive'=>array());		
		$openDriveBox = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(14,$this->session->userdata['modules_assign'])){
				$openDriveBox = false;
			}
		}
		if($openDriveBox===true):		
			if(isset($_POST) && count($_POST)>0){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$findLeadData = $this->lead_model->getLeadData($this->input->post('boxes'));
				if(count($findLeadData)>0 && !empty($findLeadData->folder_id)){
					$data['drive'] = $service->getFileIDFromChildern($findLeadData->folder_id);
				}			
			}
		endif;
		echo json_encode($data);		
		die;
	}
	
	function findDriveFilesSubFolder(){
		$data = array('drive'=>array());		
		$openDriveBox = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(14,$this->session->userdata['modules_assign'])){
				$openDriveBox = false;
			}
		}
		if($openDriveBox===true):
			if(isset($_POST) && count($_POST)>0){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				if(!empty($this->input->post('f'))){
					$data['drive'] = $service->getFileIDFromChildern($this->input->post('f'));
				}		
			}
		endif;
		echo json_encode($data);		
		die;
	}
	
	function scanning_document_files(){
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();		
		$data['drive'] = $service->getFileIDFromChildern("0B61I0m5ybHrFbDdvZTdTU1lVTEk");
		$this->layout->layout='opportunity';
		$this->layout->auto_render=false;	
		$this->layout->title_for_layout = 'Backyard Leads';
		$this->layout->render('leads/scanning_document_files',$data);
	}
	
	
	function file_import_in_companies(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$post = $this->input->post();
			if(isset($post['file_url']) && !empty($post['file_url'])){
				$url = explode('/',$post['file_url']);
				$fileID = '';
				if(count($url)>1){
					$fileID = $url[count($url)-2]; 
				}
				if(!empty($fileID)){
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();		
					$fileGet =  $service->getFileInfo($fileID);
					if(is_object($fileGet) && isset($fileGet->title)){
						$spreadSheetService = new SpreadsheetServiceHelper();
						$spreadsheet = $spreadSheetService->getSpreadsheetById($fileGet->id);
						$allWorkSheet = $spreadSheetService->getAllWorkSheets();
						$workSheetName = "";			
						if(empty($workSheetName)){
							$workSheetName = "Sheet1";
						}						
						$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
						$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());
						if(count($asset_data)>0){
							for($i=0;$i<count($asset_data);$i++){								
								$address = '';								
								if(isset($asset_data[$i]['webaddress'])){
									$address = $asset_data[$i]['webaddress'];
								}								
								$data = $this->lead_model->insertPreCompanies(array('company_name'=>$asset_data[$i]['companyname'],'web_address'=>$address));
							}
						}
					}
				}
			}
		}
		echo $data;
		die;
	}
	
	function file_import_in_contacts(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$post = $this->input->post();
			if(isset($post['file_url']) && !empty($post['file_url'])){
				$url = explode('/',$post['file_url']);
				$fileID = '';
				if(count($url)>1){
					$fileID = $url[count($url)-2]; 
				}
				if(!empty($fileID)){
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();		
					$fileGet =  $service->getFileInfo($fileID);
					if(is_object($fileGet) && isset($fileGet->title)){
						$spreadSheetService = new SpreadsheetServiceHelper();
						$spreadsheet = $spreadSheetService->getSpreadsheetById($fileGet->id);
						$allWorkSheet = $spreadSheetService->getAllWorkSheets();
						$workSheetName = "";			
						if(empty($workSheetName)){
							$workSheetName = "Sheet1";
						}
						
						$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
						$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());	
						
						if(count($asset_data)>0){
							for($i=0;$i<count($asset_data);$i++){
								$profile_url='';
								if(isset($asset_data[$i]['linkedin'])){
									$profile_url= $asset_data[$i]['linkedin'];
								}
								$address = '';
								$address1 = '';
								$city = '';
								$state = '';
								if(isset($asset_data[$i]['address'])){
									$address = $asset_data[$i]['address'];
								}
								if(isset($asset_data[$i]['address1'])){
									$address1 = $asset_data[$i]['address1'];
								}
								if(isset($asset_data[$i]['city'])){
									$city = $asset_data[$i]['city'];
								}
								if(isset($asset_data[$i]['state'])){
									$state = $asset_data[$i]['state'];
								}
								$data = $this->lead_model->insertPreContacts(array('company_name'=>$asset_data[$i]['company'],'job_title'=>$asset_data[$i]['title'],'first_name'=>$asset_data[$i]['firstname'],'last_name'=>$asset_data[$i]['lastname'],'email'=>$asset_data[$i]['email'],'cellphone'=>$asset_data[$i]['phone'],'address'=>$address.', '.$address1.', '.$city.', '.$state,'profile_url'=>$profile_url));
								
							}
						}
					}
				}
			}
		}
		echo $data;
		die;
		
		/*
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();		
		$fileGet =  $service->getFileNameFromChildern("0B61I0m5ybHrFUHNFMjRiSWFsSWM","High Tech Current Members - 7-02-15");
		if(is_object($fileGet)){
			$spreadSheetService = new SpreadsheetServiceHelper();
			$spreadsheet = $spreadSheetService->getSpreadsheetById($fileGet->id);
			$allWorkSheet = $spreadSheetService->getAllWorkSheets();
			$workSheetName = "";			
			if(empty($workSheetName)){
				$workSheetName = "Sheet1";
			}
			
			$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
			$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());	
			
			if(count($asset_data)>0){
				$this->load->model('customer_model');
				for($i=0;$i<count($asset_data);$i++){
					$companyExist = $this->customer_model->getCompanyData($asset_data[$i]['company']);
					$companyID = 390;
					if(count($companyExist)>0){
						$companyID = $companyExist->id;
						echo "COmpanyID: ".$companyID."<br/>";
					} else {
						$companyID = $this->customer_model->insertCompany(array('company_name'=>$asset_data[$i]['company'],'start_date'=>'0000-00-00 00:00:00','end_date'=>'0000-00-00 00:00:00'));
					}
					if($companyID>0){
						$data = $this->client_model->insert(array('company_id'=>$companyID,'job_title'=>$asset_data[$i]['title'],'first_name'=>$asset_data[$i]['firstname'],'last_name'=>$asset_data[$i]['lastname'],'email'=>$asset_data[$i]['email'],'phone'=>$asset_data[$i]['phone'],'address'=>$asset_data[$i]['address'].', '.$asset_data[$i]['address1'].', '.$asset_data[$i]['city'].', '.$asset_data[$i]['state']));
						echo $data."<br/>";
					}
				}
			}
		}	
		die;*/
	}
	
	function removeFromBox(){
		if(isset($_POST) && count($_POST)>0){
			$removeFromBox = $this->lead_model->removeFromBox($this->input->post('g'),$this->input->post('thread'));
			if($removeFromBox>0){
				/*Delete Acquisition Activity*/
				$this->lead_model->removeFromAcquisition($this->input->post('g'),$this->input->post('thread'));
				/*Delete Sales*/
				$this->lead_model->removeFromSales($this->input->post('g'),$this->input->post('thread'));
				/*End*/
				$user_history = array('lead_id'=>$this->input->post('g'),'user_id'=>$this->session->userdata['id'],'message'=>"Remove message from box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				echo json_encode(array('send'=>'1'));
			} else {
				echo json_encode(array('send'=>'0'));
			}
		} else {
			echo json_encode(array('send'=>'0'));
		}		
		die;
	}
	
	function delete_drive(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$fileMoved = $service->moveFile($this->input->post('d'),"0B61I0m5ybHrFfjJ4ZEYtNEJrc091eEV3d2VIXzQtN05WWUpWX2NtSVhRdFVmVVVBN3E3akU");
			if(is_object($fileMoved)){
				$data = 1;
			} 
		}
		echo $data;
		die;
	}
	
	function linkWithMessage(){
		if(isset($_POST) && count($_POST)>0){
			$box = $_POST;
			if(!isset($_SESSION)){
				session_start();
			}
			$this->load->library('DriveServiceHelper');
			$service = new GmailServiceHelper();
			$service->setAccessToken($_SESSION['access_token']);
			$getMessageData = $service->findThreadData($box['thread']);
			$filesAttachment = "";
			/**/
			if(count($getMessageData)>0){
				foreach($getMessageData as $message){
					foreach($message['attachments'] as $attachments){
						$attachmentID = $attachments['attachmentId'];
						$filename =  $attachments['filename'];
						$attachmentsData = $service->downloadAttachments($box['thread'],$attachmentID);
						if(is_object($attachmentsData)){
							$rawData = $attachmentsData->data;
							$sanitizedData = strtr($rawData,'-_', '+/');
							$data = strtr($rawData, array('-' => '+', '_' => '/'));
							$body = base64_decode($sanitizedData);
							$fh = fopen($_SERVER['DOCUMENT_ROOT'].'/public/upload/'.$filename, "w+");
							fwrite($fh, $body);
							fclose($fh);	
							$filesAttachment .=$this->config->base_url()."public/upload/".$filename.",";
						}
					}
				}
			}
			if(!empty($filesAttachment)){
				$filesAttachment = substr($filesAttachment,0,-1);
			}
			$sendData = $this->lead_model->insertBox(array("lead_id"=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],"thread_id"=>$box['thread'],"content"=>json_encode($getMessageData),"file_attach"=>$filesAttachment));
			if($sendData>0){
				$user_history = array('lead_id'=>$box['old_thread'],'user_id'=>$this->session->userdata['id'],'message'=>"Add email into box.",'opportunity_id'=>0,'create_date'=>date('Y-m-d H:i:s'));
				$this->user_model->addUserHistory($user_history);
				echo json_encode(array('send'=>1));
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	
	function sendRequestURL(){
		$data =array();
		if(isset($_POST) && count($_POST)>0){
			if($this->input->post('t')=='c3luUGF0TWFya2V0'){
				if(!isset($_SESSION)){
					session_start();
				}
				$_SESSION['clickedddd_url']="market_review";
				unset($_SESSION['clicked_url']);
				$data =array(base64_encode(rand()));
			}
		}
		echo json_encode($data);
		die;
	}
    
    function sendRequestURLMarketExecute(){
		$data =array();
		if(isset($_POST) && count($_POST)>0){
			if($this->input->post('t')=='c3luUGF0TWFya2V0'){
				if(!isset($_SESSION)){
					session_start();
				}
				$_SESSION['clickedddd_url']="market_execute";
				unset($_SESSION['clicked_url']);
				$data =array(base64_encode(rand()));
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function createNDATermsheet(){
		$data = array("error"=>"1","message"=>"Please try  after sometime");
		if(isset($_POST) && count($_POST)>0){
			$leadID = $this->input->post('v');
			$getLeadData = $this->lead_model->getLeadData($leadID);
          	if(count($getLeadData)>0){
				$ndaID = "";
				$term_sheet = "";				
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				if(empty($ndaID)){					
					/*Create NDA*/
					$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);	
					if($parentFolderID){
						$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('NDA',$getLeadData->type);
						if(count($getNDAFileNameWithAccordingLeadType)>0){
							/*Create Object*/
							$saveDocFIle = $getNDAFileNameWithAccordingLeadType->doc_id;
							$fileID = (object) array("id"=>$saveDocFIle);
						} else {
							$fileID = $service->getFileNameFromChildern($parentFolderID,'NDA with Patent Owners - SynPat - Master');
						} 
						if(!empty($fileID) && !empty($getLeadData->folder_id)){
							$folderID = $getLeadData->folder_id; 
							$fileParent = new Google_Service_Drive_ParentReference();
							$fileParent->setId( $folderID );
							
							$getFileInfo = $service->copyFile($fileID->id,"NDA with Seller - ".$getLeadData->lead_name,$fileParent);
							if($getFileInfo){
								$data['error'] = 0;
								$data['nda'] = $getFileInfo->alternateLink;
								$saveLead = $this->lead_model->from_litigation_update($leadID,array("nda_id"=>$getFileInfo->alternateLink));
								$updateDate = date('Y-m-d H:i:s');
								$originalButtonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"NDA_TERMSHEET");
								if(count($originalButtonData)>0){
									$leadButtonData = $this->lead_model->findLeadButtonByButtonID($getLeadData->id,$originalButtonData->id);
									if(count($leadButtonData)>0){
										$newStatus = "";									
										$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
										$newStatus = $leadButtonData->status_message_fill.$newStatus;	
										$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
									}
								}		
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"NDA created for ".$getLeadData->lead_name,'opportunity_id'=>0,'create_date'=>$updateDate);
								$this->user_model->addUserHistory($user_history);									
								$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");								
							}
						}
					}
				}
				if(empty($term_sheet)){
					$parentFolderID = $service->getFileIdByName(MASTER_FOLDER);
					if($parentFolderID){
						$getNDAFileNameWithAccordingLeadType = $this->opportunity_model->doc_list('TIMESHEET',$getLeadData->type);
						if(count($getNDAFileNameWithAccordingLeadType)>0){
							/*Create Object*/
							$saveDocFIle = $getNDAFileNameWithAccordingLeadType->doc_id;
							$fileID = (object) array("id"=>$saveDocFIle);
						} else {
							$fileID = $service->getFileNameFromChildern($parentFolderID,'Acquisition Term Sheet - Master');
						}
						if(!empty($fileID) && !empty($getLeadData->folder_id)){
							$folderID = $getLeadData->folder_id;
							$fileParent = new Google_Service_Drive_ParentReference();
							$fileParent->setId( $folderID );
							$getFileInfo = $service->copyFile($fileID->id,"Acquisition Term Sheet - ".$getLeadData->lead_name,$fileParent);
							if($getFileInfo){
								$data['error'] = 0;
								$data['term_sheet'] = $getFileInfo->alternateLink;
                                $created_date = date('Y-m-d H:i:s');
								$saveLead = $this->lead_model->from_litigation_update($leadID,array("term_sheet"=>$getFileInfo->alternateLink,'nda_term_sheet'=>2,'nda_term_sheet_text'=>$created_date));								
								$user_history = array('lead_id'=>$leadID,'user_id'=>$this->session->userdata['id'],'message'=>"Termsheet created for ".$getLeadData->lead_name,'opportunity_id'=>0,'create_date'=>$created_date);
                                $data['date_created'] = $created_date;
								$this->user_model->addUserHistory($user_history);
								$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
							}
						}
					}
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function updateLeadData(){
		$data = array("error"=>1);
		if(isset($_POST) && count($_POST)>0){
			$lead_id = $this->input->post("lead_id");
			$send_proposal_letter = $this->input->post("send_proposal_letter");
			if($send_proposal_letter<2){
				$send_proposal_letter = "2";
			} else if($send_proposal_letter>2){
				$send_proposal_letter = "2";
			}
			$update_data = array('send_proposal_letter'=>$send_proposal_letter ,'send_proposal_letter_text'=>date('Y-m-d H:i:s'));
			$savedData = $this->lead_model->from_litigation_update($lead_id,$update_data);
			if($savedData){
				$data = array("error"=>"0","date_created"=>date("m d, y", strtotime($update_data['send_proposal_letter_text'])));
			} 
		}
		echo json_encode($data);
		die;		
	}
	public function letter_proposal(){
		$this->load->library('DriveServiceHelper');
		$service = new DriveServiceHelper();
		/*Find File From Master Document*/
		$name = $this->input->post('name');
		if(empty($name)){
			$name = "Acquisition Proposal Letter - ".date('YYMM')."_Proposal";
		} else{
			$name = "Acquisition Proposal Letter - ".$name;
		}
		$fileID = false;		
		$leadFolderID = false;
		$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
		if(count($leadInfo)>0){
			if(!empty($leadInfo->folder_id)){
				$leadFolderID = $leadInfo->folder_id;
			}else {
		        $this->load->library("DriveServiceHelper");
					$service = new DriveServiceHelper();
					$mainFolderID = $service->getFileIdByName('Leads');
                	if($mainFolderID){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId( $mainFolderID );
						$getFolderInfo = $service->createSubFolder($leadInfo->lead_name.'_folder',$fileParent);
						if($getFolderInfo){
							/*Save Folder ID  in DB*/
							$this->lead_model->from_litigation_update($_POST['lead_id'],array('folder_id'=>$getFolderInfo));
							
						}
					}
	       	}
		}       
		$findFile = $service->getFileNameFromChildern($leadFolderID,$name);
		if(is_object($findFile)){
			$data['link'] = $findFile->alternateLink;
			if(isset($_POST['ds'])){
				$update_data = array('send_proposal_letter'=>'1',"proposal_id"=>$findFile->id);
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			}
		} else {
			switch($this->input->post('type')){
				case 'Litigation':								
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');							
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'Proposal Letter to Sellers - Proactive');
								if($fileID){
									$fileID = $fileID->id;
								}
							}
						}	
					}				
				break;
				case 'Market':
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);	
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');	
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');						
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'Proposal Letter to Sellers - Proactive');
								if($fileID){	
									$fileID = $fileID->id;		
								}
							}
						}
					}
				
				break;
				case 'General':
				case 'SEP':
					$parentFolderID = $service->getFileIdByName(BACKUP_FOLDER);	
					if($parentFolderID){
						$getFolderID = $service->getFileNameFromChildern($parentFolderID,'Operations');	
						if($getFolderID){	
							$innerFolderID = $service->getFileNameFromChildern($getFolderID->id,'Master Documents');
							
							if($innerFolderID){	
								$fileID = $service->getFileNameFromChildern($innerFolderID->id,'Proposal Letter to Sellers - Proactive');
								if($fileID){	
									$fileID = $fileID->id;		
								}
							}
						}
					}
				break;
			}

			$data = array();	
			if($leadFolderID && $fileID){
				/*Find File*/
				$file = $service->getFileNameFromChildern($leadFolderID,$name);
				/*End Find*/
				if($file){
					$data['link'] = $file->alternateLink;
					$update_data = array('send_proposal_letter'=>'1',"proposal_id"=>$file->id);
					$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
				} else {
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $leadFolderID );
					$getFileInfo = $service->copyFile($fileID,$name,$fileParent);
					if($getFileInfo){
						
						$service->setAdditionalPermissions( $getFileInfo->id, "","reader","anyone");
						$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('lead_id'),'message'=>'Create a letter proposal','create_date'=>date('Y-m-d H:i:s')));
						$data['link'] = $getFileInfo->alternateLink;
						if(isset($_POST['ds'])){
							$update_data = array('send_proposal_letter'=>'1',"proposal_id"=>$getFileInfo->id);
							$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
						}
					} else {
						$data['link'] = '';
					}
				}				
			} else {
				$data['link'] = "";
			}
		}
		echo json_encode($data);		
		die;
	}
	
	public function email_proposal_history(){
		$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$this->input->post('lead_id'),'message'=>'Create a email proposal','create_date'=>date('Y-m-d H:i:s')));
	}
	
	public function comment(){
		if(isset($_POST) && count($_POST)>0){
			$commentData = $this->input->post();
    
            $patent_data = $commentData['litigation']['patent_data'];
          
			$commentData['other']['user_id']= $this->session->userdata['id'];
			$commentData['other']['created']= date('Y-m-d H:i:s');
			
			if(isset($commentData['litigation']['complete'])){
				$this->lead_model->from_litigation_update($commentData['other']['parent_id'],array('complete'=>2,'status'=>'0','patent_data' => $patent_data));
			}
            else
            {
                $this->lead_model->from_litigation_update($commentData['other']['parent_id'],array('patent_data' => $patent_data));
            }
			if($commentData['other']['id']==0){
				$saveComment = $this->lead_model->from_litigation_comment($commentData['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['other']['parent_id'],'message'=>'Add a comment','create_date'=>date('Y-m-d H:i:s')));
			} else {
				$saveComment = $this->lead_model->from_litigation_update_comment($commentData['other']);
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$commentData['other']['parent_id'],'message'=>'Update comment','create_date'=>date('Y-m-d H:i:s')));
			}
			if($saveComment>0){
				echo json_encode(array('message'=>'Records added','id'=>$saveComment));
			} else {
				echo json_encode(array('error'=>'Try after sometime.'));
			}
		}
		die;
	}
	
    public function insert_patent_data(){
        if(isset($_POST) && count($_POST)>0)
        {
            $patent_data = $this->input->post();
			$this->lead_model->from_litigation_update($patent_data['parent_id'],array('patent_data' => $patent_data['patent_data']));
        }
        die;
    }
    
	function change_status_lead(){
		if(isset($_POST) && count($_POST)>0){
			$leadID =$_POST['token'];
			$dateUpdate = date('Y-m-d H:i:s');
			$updatedRows = $this->lead_model->updateLeadStatus(array('id'=>$leadID,'status'=>'1','complete'=>'2','synpat_like'=>$dateUpdate));
			$getLeadData = $this->lead_model->getLeadData($leadID);
			$buttonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"APPROVED_LEAD");
			$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Approved Lead','create_date'=>date('Y-m-d H:i:s')));
			/*Create Task for admin to create opportunity*/
			$getAdminUsers = $this->user_model->findAdminUsers();
			foreach($getAdminUsers as $user){
				$this->opportunity_model->sendApprovalRequest(array('lead_id'=>$leadID,'user_id'=>$user->id,"doc_url"=>$this->config->base_url()."general/create_an_opportunity","status"=>0,"type"=>"CREATE_OPPORTUNITY"));
			}
			echo json_encode(array('rows'=>$updatedRows,'button_data'=>$buttonData,'date_update'=>date('m d,y',strtotime($dateUpdate))));
		}
		die;
	}
	
	function sellerInterested(){
		if(isset($_POST) && count($_POST)>0){
			$leadID =$_POST['token'];
			$dateUpdate = date('Y-m-d H:i:s');
			$updatedRows = $this->lead_model->from_litigation_update($leadID,array('seller_like'=>$dateUpdate));
			$getLeadData = $this->lead_model->getLeadData($leadID);
			$buttonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"SELLER_IS_INTERSTED");
			$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Seller like the Deal','create_date'=>date('Y-m-d H:i:s')));			
			echo json_encode(array('rows'=>$updatedRows,'button_data'=>$buttonData,'date_update'=>date('m d,y',strtotime($dateUpdate))));
		}
		die;
	}
	
	function fundsTransfer(){
		if(isset($_POST) && count($_POST)>0){
			$leadID =$_POST['token'];
			$dateUpdate = date('Y-m-d H:i:s');
			$updatedRows = $this->lead_model->from_litigation_update($leadID,array('funding_trnsfr'=>$dateUpdate));
			$getLeadData = $this->lead_model->getLeadData($leadID);
			$buttonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"FUNDING");
			$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Funds Transfer','create_date'=>date('Y-m-d H:i:s')));	
			echo json_encode(array('rows'=>$updatedRows,'button_data'=>$buttonData,'date_update'=>date('m d,y',strtotime($dateUpdate))));
		}
		die;
	}
	
	public function scrapData($getPatentNumber){
		/*$getPatentNumber = $this->input->post('scrap_data');*/
		if(!empty($getPatentNumber)){
			$url = "https://patents.google.com/patent/".$getPatentNumber."/en";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20150101 Firefox/31.0");
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$content = curl_exec($ch);
			curl_close($ch);
			$content = str_replace("/patents/css/","http://www.google.co.in/patents/css/",$content);
			$content = str_replace("/books/javascript/","http://www.google.co.in/books/javascript/",$content);
			$content = str_replace("/googlebooks/images/kennedy/page_left.png","http://www.google.co.in/googlebooks/images/kennedy/page_left.png",$content);
			$content = str_replace("/googlebooks/images/kennedy/page_right.png","http://www.google.co.in/googlebooks/images/kennedy/page_right.png",$content);
		
		}
		echo $content;
		die;
		$data['scrap_data'] = $content;
		$this->layout->layout='scrap_data';
		$this->layout->auto_render=false;		 
		$this->layout->title_for_layout = 'Backyard From Patent Detail';
		$this->layout->render('leads/scrap_data',$data);				
	}
	
	public function findWorksheetList(){
		if($_POST && count($_POST)):
			$this->load->library('DriveServiceHelper');
			$service = new SpreadsheetServiceHelper();
			$ss_id = $this->input->post('v');
			$spreadsheet = $service->getSpreadsheetById($ss_id);
			$allWorkSheet = $service->getAllWorkSheets();
			echo json_encode($allWorkSheet);			
		endif;
		die;
	}
	
	public function findWorksheetListFromUrl(){
		if($_POST && count($_POST)):
			$this->load->library('DriveServiceHelper');
			$service = new SpreadsheetServiceHelper();
			$ss_id = $this->input->post('v');
			$ss_id = str_replace("https://docs.google.com/spreadsheets/d/","",$ss_id);
			$ss_id = str_replace("https://docs.google.com/a/synpat.com/spreadsheets/d/","",$ss_id);
			$ss_id = str_replace("/edit?usp=drivesdk","",$ss_id);	
			$ss_id = str_replace("/edit#gid=0","",$ss_id);	
			$spreadsheet = $service->getSpreadsheetById($ss_id);
			$allWorkSheet = $service->getAllWorkSheets();
			echo json_encode($allWorkSheet);			
		endif;
		die;
	}
	
	public function searchAllPatentFiles(){
		$listFiles = array();
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$q = "title contains 'Patent List' and mimeType = 'application/vnd.google-apps.spreadsheet'";
			$listFiles = $service->searchFileIDFromChildern($this->input->post('f'),$q);
		}
		echo json_encode($listFiles);	
		die;
	}
	
	public function findPatentsAll(){
		if ( ! session_id() ) { 
			session_start();
			
		}
		unset($_SESSION['overAllArray']);
		$asset_data = array();
		$string = $this->input->post('file_url');
		$fileID = $this->input->post('file_id');
		$this->load->library('DriveServiceHelper');
		$service = new SpreadsheetServiceHelper();
		if(empty($string)){
			$driveService = new DriveServiceHelper();
			$getInfo = $driveService->getFileInfo($fileID);
			if($getInfo){
				$string = $getInfo->alternateLink;
			}
		}
		if(!empty($string)){
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
			
			$spreadsheet = $service->getSpreadsheetById($ss_id);
			$allWorkSheet = $service->getAllWorkSheets();
			$sheetName = "";
			$sheetInnerID = "";
			foreach($allWorkSheet as $worksheet){
				if(trim($worksheet['link'])==trim($sheetID)){
					$sheetName = $worksheet['text'];
					$sheetInnerID = $worksheet['id'];
					break;
				}
			}
			if(!empty($sheetName)){
				$listFeed = $service->getWorkSheetByName($sheetName);
				$asset_data =$service->getAllRows($listFeed->getEntries());
			}
		}
		echo json_encode($asset_data);
		die;
	}
	
	
	public function googleSpreadSheet(){
		$openPatentTable = true;
		if((int)$this->session->userdata['type']!=9){
			if(!in_array(16,$this->session->userdata['modules_assign'])){
				$openPatentTable = false;
			}
		}
		$allPatentFromSheet = array();
		if($openPatentTable===true):		
		$asset_data=array();
		$patent = $this->input->post('p');
		if(!empty($patent)){
				$getData = $this->lead_model->getLeadData($this->input->post('boxes'));
				$patentData = "";
				if(count($getData)){
					$patentData = json_decode($getData->patent_data);
				}
				$overAllArray = array();
						$content = $this->executeCurl($patent);		
						$this->load->library('simple_html_dom');
						$title=null;
						$needle = '<span class="patent-title">';
						if(strpos($content, $needle,0) !== false) {
							$startPost = strpos($content, $needle);
							$anotherNeedle = '<span class="patent-number">';
							if (stripos($content, $anotherNeedle,1) !== false) {
								$endPost =  stripos($content, $anotherNeedle);										
								$dataPatent =  substr($content,$startPost,$endPost);
								$html = $this->simple_html_dom->load($dataPatent,true,true);
								$flag=0;
								foreach($html->find('<span class="patent-title">') as $asTable) {
									foreach($asTable->find('invention-title') as $title){
										$title =$title->innertext;
									}									
								}
							}
						}
						$title = strip_tags($title);						
						$needle = '<table class="patent-bibdata">';
						$key = array();
						$value = array();		
						if(strpos($content, $needle,0) !== false) {
							$startPost = strpos($content, $needle);
							$anotherNeedle = '<div class="number-and-title">';
							if (stripos($content, $anotherNeedle,1) !== false) {
								$endPost =  stripos($content, $anotherNeedle);										
								$dataPatent =  substr($content,$startPost,$endPost);									
								$html = $this->simple_html_dom->load($dataPatent,true,true);
								$flag=0;
								foreach($html->find('<table class="patent-bibdata">') as $asTable) {		
									foreach($asTable->find('tr') as $tr){	
										$c=0;
										foreach($tr->find('td') as $td){												
											if( stripos($td->innertext, 'External Links:')!==false){
												$flag=1;
											}
											if($flag==0){
												switch($c){
													case 0:
														if(stripos($td->innertext,'<span class="patent-tooltip-anchor patent-question-icon"></span>')!==false){
															$key[] = substr($td->innertext,0,stripos($td->innertext,'<span class="patent-tooltip-anchor patent-question-icon"></span>'));
														} else {
															$key[] = $td->innertext;
														}														
													break;
													case 1:
														if($td->find('a')){
															$anchor_text = "";
															foreach($td->find('a') as $anchor){
																$anchor_text .= $anchor->innertext.", ";
															}																
															$value[] = substr($anchor_text,0,-2);		
														} else if($td->find('span')){
															$anchor_text = "";
															foreach($td->find('span') as $spP){
																foreach($spP->find('span') as $sp){
																	$anchor_text .= $sp->innertext.", ";
																}																
															}
															$value[] = substr($anchor_text,0,-2);		
														} else {
															$value[] = $td->innertext;		
														}										
													break;														
												}													
											}
											$c++;
										}																			
									}
								}
							}
						}
						/*echo $content;*/
						$forwardArray = array();						
						$needle = '<a id="forward-citations"></a>';
						if (strpos($content, $needle,0) !== false) {
							$startPost = strpos($content, $needle);
							$anotherNeedle = '<a id="classifications"></a>';
							if (strpos($content, $anotherNeedle,1) !== false) {
								$subContent = substr($content,$startPost);				
								$endPost =  strpos($subContent, $anotherNeedle);
								$dataPatent =  substr($content,$startPost,$endPost);
								$html = str_get_html($dataPatent);				
								
								$n=0;
								foreach($html->find('<table class="patent-data-table">') as $asTable) {											
									foreach($asTable->find('tr') as $tr){	
										if($n>0){
											$anchor = "";
											$fillingDate = "";
											$publicationDate = "";
											$applicant = "";
											$title ="";							
											$c=0;
											foreach($tr->find('td') as $td){
												switch($c){
													case 0:
														$citedPatent="";
														foreach($td->find('a') as $anchor){
															$citedPatent .= $anchor->innertext.", ";
														}																
														$citedPatent = substr($citedPatent,0,-2);
													break;
													case 1:
														$fillingDate = $td->innertext;														
													break;
													case 2:
														$publicationDate = $td->innertext;
													break;
													case 3:
														$applicant = $td->innertext;
													break;
													case 4:
														$title = $td->innertext;
													break;
												}
												$c++;
											}
											$forwardArray[] = $applicant;													
											$overAllArray[] = $applicant;													
										}
										$n++;
									}
			
								}
							}
						}
						$referenced = array_count_values($forwardArray);						
						
						$newKey = array();
						$newVal = array();
						if(count($key)>0 && count($key)>10){
							for($k=0;$k<count($key)-2;$k++){
								$newKey[]=trim($key[$k]);
								if(isset($value[$k])){
									$newVal[]=trim($value[$k]);
								} else {
									$newVal[]="";
								}
								
							}
						}
						$newPubData = array();
						if(is_array($newKey) && is_array($newVal) && count($newKey) > 0 && count($newVal) > 0){
							$newPubData = array_combine($newKey, $newVal);
						}
						$applicationNumber ="";
						$originalAssignee ="";
						$currentAssignee =null;
						$priority ="";
						$feeStatus ="";
						$family ="";
						$question1 = null;
						$question2 = null;
						$question3 = null;
						if(count($newPubData)>0){
							if(isset($newPubData['Application number'])){
								$applicationNumber = strip_tags($newPubData['Application number']);
							}
							if(isset($newPubData['Original Assignee'])){
								$originalAssignee = strip_tags($newPubData['Original Assignee']);
							}
							if(isset($newPubData['Publication number'])){
								$family = strip_tags($newPubData['Publication number']);
								$family = explode(',',$family);
								$family = implode(',',array_filter($family,'strlen'));
							}
							if(isset($newPubData['Priority date'])){
								$priority = strip_tags($newPubData['Priority date']);
							}
							if(isset($newPubData['Fee status'])){
								$feeStatus = strip_tags($newPubData['Fee status']);
							}
						}
						if(is_array($patentData) && count($patentData)>0){
							foreach($patentData as $pp){
								if(isset($pp[1])){
									$question1 = $pp[1];
								}
								if(isset($pp[2])){
									$question2 = $pp[2];
								}
								if(isset($pp[3])){
									$question3 = $pp[3];
								}
								if(isset($pp[4])){
									$currentAssignee = $pp[4];
								}
							}
						}
						if ( ! session_id() ) { 
							session_start();
						}
						if(isset($_SESSION['overAllArray'])){
							$getOldArrayData = $_SESSION['overAllArray'];
						} else{
							$getOldArrayData = array();
						}
						
						if(is_array($getOldArrayData)){
							if(count($getOldArrayData)==0){
								$getOldArrayData = $overAllArray;
							} else {
								for($i=0;$i<count($overAllArray);$i++){
									array_push($getOldArrayData,$overAllArray[$i]);
								}
							}
						} else {
							$getOldArrayData = $overAllArray;
							
						}
						
						$_SESSION['overAllArray'] = $getOldArrayData;
						
						$allPatentFromSheet = array($patent,$question1,$question2,$question3,$currentAssignee,$applicationNumber,$title,$originalAssignee,$priority,$feeStatus,$family,$referenced,array_count_values($getOldArrayData));						
			
		}
		endif;
		echo json_encode($allPatentFromSheet);
		die;
	}
	
	function embedScheduleCall(){
		if(isset($_POST) && count($_POST)){
			$embedCode = $this->input->post('embed_code');			
			$getData = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($getData)>0){
				if(!empty($getData->embed_code)){
					$embedCode = $getData->embed_code.",".$embedCode;
				}
			}
			$update_data = array('embed_code'=>$embedCode);
			$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			echo "1";
		} else {
			echo "0";
		}
		die;
	}
	/*
	function create_spreadsheet_for_hole(){
		$data = array("url"=>'');
		if(isset($_POST) && count($_POST)>0){
			$lead = $this->input->post('lead');
			$activity = $this->input->post('activity');
			$records = $this->input->post('rec');
			if(!empty($records)){
				$list = json_decode($records);
				if(count($list)>0){
					
					$this->load->library('DriveServiceHelper');
					$service = new DriveServiceHelper();
					$leadFolderID = false;
					$leadInfo = $this->lead_model->getLeadData($lead);
					if(count($leadInfo)>0){
						if(!empty($leadInfo->folder_id)){
							$leadFolderID = $leadInfo->folder_id;
						}
					}
					if($leadFolderID){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId($leadFolderID);
						$fileName = "Hole Data - ".$leadInfo->lead_name;
						$getFile = $service->copyFile('1f7kpUyr7KUZONyrFUcWhIS_rwKHc2ZbQ_SYF7alBPvY',$fileName,$fileParent);
						$updateDate = date('Y-m-d H:i:s');
						if($getFile){
							$data['url'] = $getFile->alternateLink;
							$spreadSheetService = new SpreadsheetServiceHelper();
							$spreadsheet = $spreadSheetService->getSpreadsheetById($getFile->id);
							$newListFeed = $spreadSheetService->getWorkSheetByName("Sheet1");
							for($i=0;$i<count($list);$i++){
								$phone=$list[$i]->telephone;
								if(!empty($phone)){
									$phone = "Client property";
								}
								$email = $list[$i]->email;
								if(!empty($email)){
									$email = "Client property";
								}
								$linkedin = $list[$i]->linkedin;
								if(!empty($linkedin)){
									$linkedin = "Client property";
								}
								$newListFeed->insert(array("name"=>$list[$i]->name,"title"=>$list[$i]->title,"company"=>$list[$i]->company,"phone"=>$phone,"email"=>$email,"linkedin"=>$linkedin));
							}
							
							$this->lead_model->from_litigation_update($leadInfo->id,array('hole_spreadsheet'=>$getFile->alternateLink));
							$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadInfo->id,'message'=>'Create hole spreadsheet','create_date'=>date('Y-m-d H:i:s')));
						}
					}
				}
			}
		}
		echo json_encode($data);
		die;
	}*/
	
	function create_spreadsheet_for_hole(){
		$data = array("url"=>'');
		if(isset($_POST) && count($_POST)>0){
			$lead = $this->input->post('lead');
			$activity = $this->input->post('activity');
			$records = $this->input->post('rec');
			if(!empty($records)){
				$list = json_decode($records);
				if(count($list)>0){
					$this->load->model('outsource_model');
					/*Create Project*/
					$leadInfo = $this->lead_model->getLeadData($lead);
					$projectID = $this->outsource_model->insertProject(array('project_name'=>"Holes - ".$leadInfo->lead_name,'row_count'=>count($list),'col_count'=>6,'created_date'=>date('Y-m-d H:i:s')));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>1,"heading"=>"Name","price"=>'0.00','status'=>0));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>2,"heading"=>"Title","price"=>'0.00','status'=>0));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>3,"heading"=>"Company","price"=>'0.00','status'=>0));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>4,"heading"=>"Email","price"=>'0.20'));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>5,"heading"=>"Phone","price"=>'0.20'));
					$this->outsource_model->insertColumns(array('project_id'=>$projectID,'column_num'=>6,"heading"=>"Linkedin","price"=>'0.20'));
					$n=1;
					for($i=0;$i<count($list);$i++){
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>1,'data'=>$list[$i]->name,'contact_id'=>$list[$i]->contact_id,'enter_by'=>'Client','created_date'=>date('Y-m-d H:i:s')));
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>2,'data'=>$list[$i]->title,'contact_id'=>$list[$i]->contact_id,'enter_by'=>'Client','created_date'=>date('Y-m-d H:i:s')));
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>3,'data'=>$list[$i]->company,'contact_id'=>$list[$i]->contact_id,'enter_by'=>'Client','created_date'=>date('Y-m-d H:i:s')));
						$email = $list[$i]->email;
						$type="Free";
						if(!empty($email)){
							$type="Client";
						}
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>4,'data'=>$email,'contact_id'=>$list[$i]->contact_id,'enter_by'=>$type,'created_date'=>date('Y-m-d H:i:s')));
						$phone=$list[$i]->telephone;
						$type="Free";
						if(!empty($phone)){
							$type="Client";
						}
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>5,'data'=>$phone,'contact_id'=>$list[$i]->contact_id,'enter_by'=>$type,'created_date'=>date('Y-m-d H:i:s')));
						$type="Free";
						$linkedin = $list[$i]->linkedin;
						if(!empty($linkedin)){
							$type="Client";
						}
						$this->outsource_model->insertProjectData(array('project_id'=>$projectID,'row'=>$n,'col'=>6,'data'=>$linkedin,'contact_id'=>$list[$i]->contact_id,'enter_by'=>$type,'created_date'=>date('Y-m-d H:i:s')));
						$n++;
					}
					$data = array("url"=>$projectID);
					$this->lead_model->from_litigation_update($leadInfo->id,array('hole_spreadsheet'=>$projectID));
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadInfo->id,'message'=>'Create hole spreadsheet','create_date'=>date('Y-m-d H:i:s')));
				}
			}
		}
		echo json_encode($data);
		die;
	}
	
	
	function createLeadPatentSpreadSheet(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$spreadSheetName = $this->input->post('n');
			if(!empty($spreadSheetName)){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$leadFolderID = false;
				$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
				if(count($leadInfo)>0){
					if(!empty($leadInfo->folder_id)){
						$leadFolderID = $leadInfo->folder_id;
					}
				}				
				if($leadFolderID){					
					/*Check File*/
					$existFile = $service->getFileNameFromChildern($leadFolderID,"Patent List - ".ucfirst($spreadSheetName));
					if($existFile==false){
						$fileParent = new Google_Service_Drive_ParentReference();
						$fileParent->setId($leadFolderID);
						$getFileInfo = $service->createSpreadSheetFile("Patent List - ".ucfirst($spreadSheetName),$fileParent);
						if($getFileInfo){
							$getFile = $service->getFileInfo($getFileInfo);
							if(is_object($getFile)){
								$updateDate = date('Y-m-d H:i:s');
								$originalButtonData = $this->lead_model->findButtonByButtonID($leadInfo->type,"PATENT_LIST");
								if(isset($_POST['ds'])){
									$updateLeadData = array('create_patent_list'=>'2','spreadsheet_id'=>$getFile->id,'spreadsheet_name'=>$getFile->title,'file_url'=>$getFile->alternateLink,'create_patent_list_text' => $updateDate);
									$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$updateLeadData);
								}	
								
								if(count($originalButtonData)>0){
									$leadButtonData = $this->lead_model->findLeadButtonByButtonID($_POST['lead_id'],$originalButtonData->id);
									if(count($leadButtonData)>0){
										$newStatus = "";									
										$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
										$newStatus = $leadButtonData->status_message_fill.$newStatus;	
										$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
									}
								}
								$data = array("url"=>$getFile->alternateLink,"spread_sheet_id"=>$getFile->id,"error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
							} else {
								$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
							}
						
						} else {
							$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
						}
					} else {
						$updateDate = date('Y-m-d H:i:s');
						$originalButtonData = $this->lead_model->findButtonByButtonID($leadInfo->type,"PATENT_LIST");
						if(isset($_POST['ds'])){
							$updateLeadData = array('create_patent_list'=>'2','spreadsheet_id'=>$existFile->id,'spreadsheet_name'=>$existFile->title,'file_url'=>$existFile->alternateLink,'create_patent_list_text' => $updateDate);
							$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$updateLeadData);
						}	
						if(count($originalButtonData)>0){
							$leadButtonData = $this->lead_model->findLeadButtonByButtonID($_POST['lead_id'],$originalButtonData->id);
							if(count($leadButtonData)>0){
								$newStatus = "";									
								$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
								$newStatus = $leadButtonData->status_message_fill.$newStatus;	
								$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
							}
						}	
						$data = array("url"=>$existFile->alternateLink,"spread_sheet_id"=>$existFile->id,"error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
					}
				} else {
					$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
				}
			} else {
				$data = array("url"=>'',"error"=>1,"message"=>"Please enter filename.");
			}
		}
		echo json_encode($data);
		die;
	}
	
	function legalStatusChange(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				$update_data = array('legal_status_dd'=>'2','legal_dd' => date('Y-m-d H:i:s'));
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
				if($saveMarket>0){
					$data = array("error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
				} else {
					$data = array("error"=>1,"message"=>"Server busy, Try after sometime.");
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	function btn_status_change(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$btnInfo = $this->lead_model->findLeadButtonByID($_POST['lead_id'],$_POST['b']);
			if(count($btnInfo)==0){
				$btnInfo = $this->lead_model->findDocketButtonByID($_POST['lead_id'],$_POST['b']);
			}
			$update_data = array('blink'=>'2','status'=>1,'update_date' => date('Y-m-d H:i:s'));
			$saveMarket = $this->lead_model->updateButton($update_data,$btnInfo->id);
			if($saveMarket>0){
				$data = array("error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
			} else {
				$saveMarket = $this->lead_model->updateDocketButton($update_data,$btnInfo->id);
				if($saveMarket>0){
					$data = array("error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
				} else {
					$data = array("error"=>1,"message"=>"Server busy, Try after sometime.");
				} 
			}	
		}
		echo json_encode($data);
		die;
	}
	
	function technicalStatusChange(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				$update_data = array('technical_status_dd'=>'2','technical_dd' => date('Y-m-d H:i:s'));
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
				if($saveMarket>0){
					$data = array("error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
				} else {
					$data = array("error"=>1,"message"=>"Server busy, Try after sometime.");
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	function claimIllusStatusChange(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				$update_data = array('claim_status_dd'=>'2','claim_illus' => date('Y-m-d H:i:s'));
				$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
				if($saveMarket>0){
					$data = array("error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
				} else {
					$data = array("error"=>1,"message"=>"Server busy, Try after sometime.");
				}				
			}
		}
		echo json_encode($data);
		die;
	}
	
	function createLegalDD(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$leadFolderID = false;
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				if(!empty($leadInfo->folder_id)){
					$leadFolderID = $leadInfo->folder_id;
				}
			}				
			if($leadFolderID){				
				$fileParent = new Google_Service_Drive_ParentReference();
				$fileParent->setId($leadFolderID);
	
				$fileName = "Legal Due Dilligence - ".$leadInfo->lead_name;
				$getFile = $service->copyFile('1vYY9A5d1xQOsuiFEDFw_nPqPniiopHX10-s_6J6hcr8',$fileName,$fileParent);
				if($getFile){	
					$updateDate = date('Y-m-d H:i:s');
					if(isset($_POST['ds'])){
						$updateLead = array('legal_status_dd'=>'1','legal_dd' => $updateDate);
						$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$updateLead);
					}
					$originalButtonData = $this->lead_model->findButtonByButtonID($leadInfo->type,"LEGAL_DD");
					if(count($originalButtonData)>0){
						$leadButtonData = $this->lead_model->findLeadButtonByButtonID($leadInfo->id,$originalButtonData->id);
						if(count($leadButtonData)>0){
							$newStatus = "";									
							$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
							$newStatus = $leadButtonData->status_message_fill.$newStatus;	
							$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
						}
					}
					/*Get Patent File*/
					$asset_data = array();
					$ss_id = $leadInfo->spreadsheet_id;
					if(!empty($ss_id)){
						$this->load->library('DriveServiceHelper');
						$spreadSheetService = new SpreadsheetServiceHelper();
						$spreadsheet = $spreadSheetService->getSpreadsheetById($ss_id);
						$allWorkSheet = $spreadSheetService->getAllWorkSheets();
						$workSheetName = "";
						if(!empty($leadInfo->worksheet_id)){
							foreach($allWorkSheet as $worksheet){
								if($worksheet['id']==$leadInfo->worksheet_id){
									$workSheetName = $worksheet['text'];
								}
							}
						}
						if(empty($workSheetName)){
							$workSheetName = "Sheet1";
						}
						
						$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
						$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());	
						if(count($asset_data)>0){
							/*New SpreadSheet*/
							$newSpreadsheet = $spreadSheetService->getSpreadsheetById($getFile->id);
							$allWorkSheet = $spreadSheetService->getAllWorkSheets();
							$newListFeed = $spreadSheetService->getWorkSheetByName("Legal DD");
							$newSheetData =$spreadSheetService->getAllRows($newListFeed->getEntries());	
							$a=2;
							for($i=0;$i<count($asset_data);$i++){
								$patent ='=hyperlink(concatenate("https://patents.google.com/patent/",importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"),"/en"),importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"))';
								$newListFeed->insert(array("bibliographicinformation"=>$patent));
								$a++;
							}
						}
					}
					/*End Patent File*/
					
											
					$data = array("url"=>$getFile->alternateLink,"spread_sheet_id"=>$getFile->id,"mimeType"=>$getFile->mimeType,'iconLink'=>$getFile->iconLink,'title'=>$getFile->title,"pp"=>count($asset_data),"f_t"=>'Legal',"error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
					
				} else {
					$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
				}
			} else {
				$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
			}
		}
		echo json_encode($data);
		die; 
	}
	
	function createClaimillustration(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$leadFolderID = false;
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				if(!empty($leadInfo->folder_id)){
					$leadFolderID = $leadInfo->folder_id;
				}
			}				
			if($leadFolderID){				
				$fileParent = new Google_Service_Drive_ParentReference();
				$fileParent->setId($leadFolderID);
	
				$fileName = "Claim Illustration - ".$leadInfo->lead_name;
				$getFile = $service->copyFile('1wzKB9Ot8TQaBw7vcKBB-prigY36Q85pyn2R_pbhj6g4',$fileName,$fileParent);
				$updateDate = date('Y-m-d H:i:s');
				if($getFile){
					if(isset($_POST['ds'])){
						$updateInfo = array('claim_status_dd'=>'1','claim_illus_file'=>$getFile->id,'claim_illus' => $updateDate);
						$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$updateInfo);
					}
					$originalButtonData = $this->lead_model->findButtonByButtonID($leadInfo->type,"CLAIM_ILLUS");
					if(count($originalButtonData)>0){
						$leadButtonData = $this->lead_model->findLeadButtonByButtonID($leadInfo->id,$originalButtonData->id);
						if(count($leadButtonData)>0){
							$newStatus = "";									
							$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
							$newStatus = $leadButtonData->status_message_fill.$newStatus;	
							$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
						}
					}
					
					
					
					/*End Patent File*/
	
					/*Get Patent File*/
					$ss_id = $leadInfo->spreadsheet_id;
					if(!empty($ss_id)){
						$this->load->library('DriveServiceHelper');
						$spreadSheetService = new SpreadsheetServiceHelper();
						$spreadsheet = $spreadSheetService->getSpreadsheetById($ss_id);
						$allWorkSheet = $spreadSheetService->getAllWorkSheets();
						$workSheetName = "";
						if(!empty($leadInfo->worksheet_id)){
							foreach($allWorkSheet as $worksheet){
								if($worksheet['id']==$leadInfo->worksheet_id){
									$workSheetName = $worksheet['text'];
								}
							}
						}
						if(empty($workSheetName)){
							$workSheetName = "Sheet1";
						}
						$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
						$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());	
						if(count($asset_data)>0){
							/*New SpreadSheet*/
							$newSpreadsheet = $spreadSheetService->getSpreadsheetById($getFile->id);
							$newListFeed = $spreadSheetService->getWorkSheetByName("Sheet1");
	
							$a=2;
							$fileParent = new Google_Service_Drive_ParentReference();
							$fileParent->setId($leadFolderID);
							$subfolderCreate = $service->createSubFolder("Illustration",$fileParent);
							for($i=0;$i<count($asset_data);$i++){
								$p = "";
								if(isset($asset_data[$i]['patent'])){
									$p = $asset_data[$i]['patent'];
								} else if(isset($asset_data[$i]['patents'])){
									$p = $asset_data[$i]['patents'];
								}
								/*Create Sub folder*/
								
								if($subfolderCreate){
									$fileParent = new Google_Service_Drive_ParentReference();
									$fileParent->setId($subfolderCreate);
									$name = "Synpat - Claim Illustration - ".$leadInfo->lead_name." - ".$p;
									$getPresentationFile = $service->createPresentaionFile($name,$fileParent);
									if($getPresentationFile){
										/*Create Slide Files*/
										$service->setAdditionalPermissions( $getPresentationFile, "","reader","anyone");
										$getPresentationFileInfo = $service->getFileInfo($getPresentationFile);
										$fileUrl = $getPresentationFileInfo->alternateLink;
										$url = '=hyperlink("'.$fileUrl.'","Chart")';
										$patent ='=hyperlink(concatenate("https://patents.google.com/patent/",importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"),"/en"),importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"))';
										$newListFeed->insert(array("patents"=>$patent,"url"=>$url));
										$a++;
									}
								}
							}
						}
					}
					/*End Patent File*/														    
					$data = array("url"=>$getFile->alternateLink,"spread_sheet_id"=>$getFile->id,"mimeType"=>$getFile->mimeType,'iconLink'=>$getFile->iconLink,'title'=>$getFile->title,"pp"=>count($asset_data),"f_t"=>'Claim',"error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));					
				
				} else {
					$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
				}
			} else {
				$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
			}
		}
		echo json_encode($data);
		die;
	}
	
	
	
	function createTechnicalDD(){
		$data = array("url"=>'',"error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$this->load->library('DriveServiceHelper');
			$service = new DriveServiceHelper();
			$leadFolderID = false;
			$leadInfo = $this->lead_model->getLeadData($_POST['lead_id']);
			if(count($leadInfo)>0){
				if(!empty($leadInfo->folder_id)){
					$leadFolderID = $leadInfo->folder_id;
				}
			}				
			if($leadFolderID){				
				$fileParent = new Google_Service_Drive_ParentReference();
				$fileParent->setId($leadFolderID);
	
				$fileName = "Technical Due Dilligence - ".$leadInfo->lead_name;
				$getFile = $service->copyFile('1n7IczwCjb5XavWGesFEYbDk3ttPDw8lIPUcykyu25oY',$fileName,$fileParent);
				if($getFile){
					$updateDate = date('Y-m-d H:i:s');
					if(isset($_POST['ds'])){
						$updateLeadInfo = array('technical_status_dd'=>'1','technical_file'=>$getFile->id,'technical_dd' => $updateDate);
						$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$updateLeadInfo);
					}
					$originalButtonData = $this->lead_model->findButtonByButtonID($leadInfo->type,"TECHNICAL_DD");
					if(count($originalButtonData)>0){
						$leadButtonData = $this->lead_model->findLeadButtonByButtonID($leadInfo->id,$originalButtonData->id);
						if(count($leadButtonData)>0){
							$newStatus = "";									
							$newStatus = '<span class="date-style">'.date('m-d-y',strtotime($updateDate)).'</span>'.$originalButtonData->status_message.' <br/>';
							$newStatus = $leadButtonData->status_message_fill.$newStatus;	
							$this->lead_model->updateButton(array('status'=>1,'update_date'=>$updateDate,'status_message_fill'=>$newStatus),$leadButtonData->id);
						}
					}
										
					/*End Patent File*/
					/*Get Patent File*/
					$ss_id = $leadInfo->spreadsheet_id;
					$asset_data  = array();
					if(!empty($ss_id)){
						$this->load->library('DriveServiceHelper');
						$spreadSheetService = new SpreadsheetServiceHelper();
						$spreadsheet = $spreadSheetService->getSpreadsheetById($ss_id);
						$allWorkSheet = $spreadSheetService->getAllWorkSheets();
						$workSheetName = "";
						if(!empty($leadInfo->worksheet_id)){
							foreach($allWorkSheet as $worksheet){
								if($worksheet['id']==$leadInfo->worksheet_id){
									$workSheetName = $worksheet['text'];
								}
							}
						}
						if(empty($workSheetName)){
							$workSheetName = "Sheet1";
						}
						$listFeed = $spreadSheetService->getWorkSheetByName($workSheetName);
						$asset_data =$spreadSheetService->getAllRows($listFeed->getEntries());	
						if(count($asset_data)>0){
							/*New SpreadSheet*/
							$newSpreadsheet = $spreadSheetService->getSpreadsheetById($getFile->id);
							$newListFeed = $spreadSheetService->getWorkSheetByName("Sheet1");
							/*$newSheetData =$spreadSheetService->getAllRows($newListFeed->getEntries());	*/
							$a=2;
							for($i=0;$i<count($asset_data);$i++){
								$patent ='=hyperlink(concatenate("https://patents.google.com/patent/",importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"),"/en"),importrange("'.$ss_id.'","Sheet1!A'.$a.':A'.$a.'"))';
								$newListFeed->insert(array("patent"=>$patent));
								$a++;
							}
						}
					}
					/*End Patent File*/														    
					$data = array("url"=>$getFile->alternateLink,"spread_sheet_id"=>$getFile->id,"mimeType"=>$getFile->mimeType,'iconLink'=>$getFile->iconLink,'title'=>$getFile->title,"pp"=>count($asset_data),"f_t"=>'Technical',"error"=>0,"message"=>"Successfully created.",'date_created' => date("m d, y"));
					
				
				} else {
					$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
				}
			} else {
				$data = array("url"=>'',"error"=>1,"message"=>"File not created. Please try after sometime.");
			}
		}
		echo json_encode($data);
		die;
	}
	
	
    
    function update_ndaTimeStampStatus(){
        $update_data = array('nda_term_sheet_text'=>date('Y-m-d H:i:s'));
		$saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
    }
    
	function executeCurl($getPatentNumber){
		$content = "";
		if(!empty($getPatentNumber)){
			$url = "https://www.google.com/patents/".$getPatentNumber;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$content = curl_exec($ch);
			curl_close($ch);			
		}
		return $content;
	}
	
	public function post_seller_info(){ 
		$data = array("error"=>1,"message"=>"Please try after sometime.");
		if(isset($_POST) && count($_POST)>0){
			$dateUpdate = date('Y-m-d H:i:s');
			$update_data = array('seller_info'=>'1','seller_info_text'=>$dateUpdate);
			$updatedRows = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
			$data = array('rows'=>$updatedRows,'date_created'=>date('m d,y',strtotime($dateUpdate)));
		}
		echo json_encode($data);
        die;
	}
    
    public function assign_lead(){
        $data = array("error"=>1,"message"=>"Please try after sometime.");
        if(isset($_POST) && count($_POST)>0){
			if(isset($_POST['ds'])){
				$dateUpdate = date('Y-m-d H:i:s');
				$update_data = array('seller_info'=>'2','seller_info_text'=>$dateUpdate);
                $updatedRows = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
				$leadID = $_POST['lead_id'];
				$getLeadData = $this->lead_model->getLeadData($leadID);
				$buttonData = $this->lead_model->findButtonByButtonID($getLeadData->type,"SELLER");
				$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$leadID,'message'=>'Funds Transfer','create_date'=>date('Y-m-d H:i:s')));	
				$data = array('rows'=>$updatedRows,'button_data'=>$buttonData,'date_created'=>date('m d,y',strtotime($dateUpdate)));
			}
        }
        echo json_encode($data);
        die;
    }
    
    public function forward_to_review(){
        if(isset($_POST) && count($_POST)>0){
            $update_data = array('complete'=>1,'forward_to_review_text'=>date('Y-m-d H:i:s'));
            $saveMarket = $this->lead_model->from_litigation_update($_POST['lead_id'],$update_data);
            $user_id = $this->session->userdata['id'];
            $create_date = date('Y-m-d H:i:s');
            $this->user_model->addUserHistory(array('user_id'=>$user_id,'lead_id'=>$_POST['lead_id'],'message'=>'Lead Forward to Review','create_date'=>$create_date));
            $data = array('date_created' => $create_date,'msg' => '1');
            
        } else { 
            $data = array('msg' => '2');
		}

        echo json_encode($data);
        die;
    }
	
	public function fileInsert(){
		$data = array("error"=>1);
		if(isset($_POST) && count($_POST)>0){			
			$getLeadData = $this->lead_model->getLeadData($this->input->post('d'));
			if(count($getLeadData)>0 && $getLeadData->folder_id!=""){
				$this->load->library('DriveServiceHelper');
				$service = new DriveServiceHelper();
				$parent = new Google_Service_Drive_ParentReference();
				if($this->input->post('f')=="" || $this->input->post('f')=="undefined"){
					$parent->setId($getLeadData->folder_id);
				} else if($this->input->post('f')!=""){
					$parent->setId($this->input->post('f'));
				} else{
					$parent->setId($getLeadData->folder_id);
				}
				$change=0;
				if(isset($_POST['change']) && $_POST['change']==1){
					$change = $_POST['change'];
				}
				$fileName = stripslashes($this->input->post('doc_url'));
				if(!empty($fileName) && $change==0){
					$fileName = str_replace($this->config->base_url(),$_SERVER['DOCUMENT_ROOT'].'/',$fileName);
					$fileName = str_replace("http://backyard.synpat.com/",$_SERVER['DOCUMENT_ROOT'].'/',$fileName);
				}
				
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mimeType =  finfo_file($finfo, $fileName);
				finfo_close($finfo);
				$convert=true;
				if($mimeType=="application/pdf" || $mimeType=="image/jpeg" || $mimeType=="image/png" || $mimeType=="image/gif" || $mimeType=="image/bmp"){
					$convert=false;
				}
				
				$docName = stripslashes($this->input->post('file_name'));
				$getUploadFileData = $service->insertFile($docName,$mimeType,$fileName,$parent,$convert);
				if($getUploadFileData){
					$service->setAdditionalPermissions( $getUploadFileData->id, $this->session->userdata['email'],"reader","anyone",array('emailMessage'=>'File has been share with you .','sendNotificationEmails'=>true));
					$data['error'] = 0;
					$data['box'] = $getUploadFileData;
				}
			}			
		}
		echo json_encode($data);
		die;
	}
	
	function getCallData(){
		$data= array();
		if(isset($_POST) && count($_POST)>0){
			$data = $this->lead_model->findCallData($this->input->post('activity'),$this->input->post('t'));
		}
		echo json_encode($data);
		die;
	}
	
	function callinout(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$call = $this->input->post('call');
			if(count($call)>0 && isset($call['lead_id']) && isset($call['company_id']) && isset($call['person']) && (int) $call['lead_id']>0 && (int) $call['company_id']>0  && (int) $call['person']>0){
				$call['contact_id'] = (int)$call['person'];		
				/*$call['activity_date'] = date('Y-m-d H:i:s',strtotime($call['execution_date'].' '.$call['time_start'].':'.$call['time_end'].':00'));*/
				$call['activity_date'] = date('Y-m-d H:i:s');
				$call['note'] = $call['purpose'].' - '. $call['note'];
				$call['user_id'] = $call['from_user_id'];
				$call['subject'] = "";
				$activity = $call['main_activity'];
				unset($call['person']);
				unset($call['execution_date']);
				unset($call['time_start']);
				unset($call['time_end']);
				unset($call['participant']);
				unset($call['purpose']);
				unset($call['main_activity']);
				unset($call['from_user_id']);
				if($activity=="2"){
					if($call['id']>0){
						$data = $this->lead_model->updateAcquistionActivity($call['id'],$call);
					} else {
						$data = $this->lead_model->insertAcquistionActivity($call);
					}					
				} else {
					if($call['id']>0){
						$data = $this->lead_model->updateSalesActivity($call['id'],$call);
					} else {
						$data = $this->lead_model->insetSalesActivity($call);
					}					
				}
				$message="";
				if($call['type']==1){
					$message = "Call In";
				} else if($call['type']==2){
					$message = "Call Out";
 				} else if($call['type']==37){
					$message = "Conference Call";
				}
				if($data>0){
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$call['lead_id'],'message'=>$message,'create_date'=>$call['activity_date']));
				}
			}			
		}
		echo $data;
		die; 
	}
	
	function sales_acititity_data(){
		$data = 0;
		if(isset($_POST) && count($_POST)>0){
			$activity = $this->input->post('activity');
			if(count($activity)>0 && isset($activity['lead_id']) && isset($activity['c_id']) && isset($activity['person']) && (int) $activity['lead_id']>0 && (int) $activity['c_id']>0  && (int) $activity['person']>0){
				$activity['company_id'] = $activity['c_id'];
				$activity['contact_id'] = (int)$activity['person'];				
				$activity['user_id'] = $this->session->userdata['id'];
				$activity['activity_date'] = date('Y-m-d H:i:s');
				unset($activity['c_id']);
				unset($activity['person']);
				if($_POST['name']['main_type']=="2"){
					$data = $this->lead_model->insertAcquistionActivity($activity);
				} else {
					$data = $this->lead_model->insetSalesActivity($activity);
				}				
				if($data>0){
					$this->user_model->addUserHistory(array('user_id'=>$this->session->userdata['id'],'lead_id'=>$activity['lead_id'],'message'=>'Activity in sale','create_date'=>$activity['activity_date']));
				}
			}			
		}
		echo $data;
		die;
	}
}

/* End of file leads.php */
/* Location: ./application/controllers/leads.php */