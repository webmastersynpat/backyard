<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getUserNotification'))
{
    function getUserNotification(){
		$CI = &get_instance();
		$user_session_id = $CI->session->userdata('id');
		$query = $CI->db->select('*')->from('notifications')->where('user_id',$user_session_id)->order_by('id','DESC')->get();
		$data=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}	
		return $data;
    }
}
if ( ! function_exists('getUserTimeLine'))
{	
	function getUserTimeLine($userID,$leadID,$opportunityID){
		$CI = &get_instance();
		$CI->load->model('user_model');
		$getTimelineData = $CI->user_model->getAllUserHistory($userID,$leadID,$opportunityID);
		return $getTimelineData;
	}
}

if ( ! function_exists('getLeadDetail'))
{	
	function getLeadDetail($leadID){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getLeadData = $CI->lead_model->getLeadData($leadID);
		return $getLeadData;
	}
}

if ( ! function_exists('getMyLogTime'))
{	
	function getMyLogTime($userID,$from,$to){
		$CI = &get_instance();
		$CI->load->model('user_model');
		$getLogTimeData = $CI->user_model->getMyLogTime($userID,$from,$to);
		return $getLogTimeData;
	}
}

if ( ! function_exists('getFlagConversations'))
{	
	function getFlagConversations($userID){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getMesageList = $CI->lead_model->getFlagConversations($userID);
		return $getMesageList;
	}
}

if ( ! function_exists('getMessageTaskList'))
{	
	function getMessageTaskList($userID){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getMesageList = $CI->lead_model->getMessageTaskList($userID);
		return $getMesageList;
	}
}

if ( ! function_exists('getAllBackyardModules'))
{	
	function getAllBackyardModules(){
		$CI = &get_instance();
		$CI->load->model('user_model');
		$getModulesData = $CI->user_model->getAllBackyardModules();
		return $getModulesData;
	}
}
if ( ! function_exists('get_menu_arr'))
{
	function get_menu_arr(){
		$CI = &get_instance();
		$user_session_id = $CI->session->userdata('id');
		$query = $CI->db->select('*')->from('user_page_access_level as u')->join('pages','u.page_id=pages.id')->where('u.user_id',$user_session_id)->get();
		//echo $CI->db->last_query();
		$data=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}	
		return $data;
    }
}
if ( ! function_exists('getUserPageAssigned'))
{	
	function getUserPageAssigned(){
		/*Check Which Page Assign to him*/
		$CI = &get_instance();
		$user_session_id = $CI->session->userdata('id');
		$where = "( p.page_url = 'leads/litigation' OR p.page_url = 'leads/market')";
		$query = $CI->db->select('p.*')->from('pages as p')->join('user_page_access_level as u', 'u.page_id = p.id')->where('u.user_id',$user_session_id)->where($where)->get();
		$data=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
		}	
		return $data;
	}
}
if ( ! function_exists('getUserTaskList'))
{	
	function getUserTaskList($lead_id=null){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$user_session_id = $CI->session->userdata('id');
		$getTaskData = $CI->opportunity_model->waitingApproval($user_session_id);
		return $getTaskData;
	}
}

if ( ! function_exists('getUserMyCreatedTaskList'))
{	
	function getUserMyCreatedTaskList($lead_id=null){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$user_session_id = $CI->session->userdata('id');
		$getTaskData = $CI->opportunity_model->myTaskApproval($user_session_id);
		return $getTaskData;
	}
}


if ( ! function_exists('checkUserCreatedLeadFromLitigation'))
{	
	function checkUserCreatedLeadFromLitigation(){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getLeadCreated = $CI->lead_model->checkUserCreatedLeadFromLitigation();
		return $getLeadCreated;
	}
}
if ( ! function_exists('sendApprovalRequest'))
{	
	function sendApprovalRequest($data){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$getLeadCreated = $CI->opportunity_model->sendApprovalRequest($data);
		return $getLeadCreated;
	}
}
if ( ! function_exists('waitingApproval'))
{	
	function waitingApproval(){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$getApproval = $CI->opportunity_model->waitingApproval($CI->session->userdata['id']);
		return $getApproval;
	}
}
if ( ! function_exists('checkApprovalSend'))
{	
	function checkApprovalSend(){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$getApproval = $CI->opportunity_model->checkApprovalSend($CI->session->userdata['id']);
		return $getApproval;
		
	}
}
if ( ! function_exists('getAllUsersIncAdmin'))
{	
	function getAllUsersIncAdmin(){
		$CI = &get_instance();
		$CI->load->model('user_model');
		$getUserList = $CI->user_model->getAllUsersIncAdmin();
		return $getUserList;
		
	}
}
if ( ! function_exists('findAdminUsers'))
{	
	function findAdminUsers(){
		$CI = &get_instance();
		$CI->load->model('user_model');
		$getUserList = $CI->user_model->findAdminUsers();
		return $getUserList;
		
	}
}
if ( ! function_exists('getTaskAccToType'))
{	
	function getTaskAccToType($type){
		$CI = &get_instance();
		$CI->load->model('general_model');
		$getData = $CI->general_model->getTaskAccToType($type);
		return $getData;
		
	}
}


if ( ! function_exists('findIncompleteANDCompleteList'))
{	
	function findIncompleteANDCompleteList($type){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getData = $CI->lead_model->findIncompleteANDCompleteList($type);
		return $getData;
		
	}
}

if ( ! function_exists('getPassLead'))
{	
	function getPassLead(){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getData = $CI->lead_model->getPassLead();
		return $getData;
		
	}
}

if ( ! function_exists('findAllBoxList'))
{	
	function findAllBoxList(){
		$CI = &get_instance();
		$CI->load->model('lead_model');
		$getData = $CI->lead_model->findAllBoxList();
		return $getData;
		
	}
}


if ( ! function_exists('myEmails'))
{	
	function myEmails($token,$type){
		$CI = &get_instance();
		$CI->load->library('DriveServiceHelper');		
		$service = new GmailServiceHelper();
		$data = array();
		if(empty($token)){
			$data['auth_url'] = $service->createAuthUrl();
			$data['messages'] =array();							
		} else{
			$data['auth_url']="";
			$service->setAccessToken($token);
			$data['messages'] = $service->messageList(100,$type);	
			unset($_SESSION['clickedd_url']);			
		}
		return $data;
	}
}
if ( ! function_exists('getAllMarketSectors'))
{	
	function getAllMarketSectors(){
		$CI = &get_instance();
		$CI->load->model('opportunity_model');
		$market_sectors = $CI->opportunity_model->getAllMarketSectors();
		return $market_sectors;
		
	}
}

if ( ! function_exists('getAllCategories'))
{	
	function getAllCategories(){
		$CI = &get_instance();
		$CI->load->model('customer_model');
		$market_sectors = $CI->customer_model->categoryList(0);
		return $market_sectors;
		
	}
}

if ( ! function_exists('getAllSubCategories'))
{	
	function getAllSubCategories(){
		$CI = &get_instance();
		$CI->load->model('general_model');
		$allSubCategories = $CI->general_model->getAllSubCategory();
		return $allSubCategories;
		
	}
}

if ( ! function_exists('getAllCompanies'))
{	
	function getAllCompanies(){
		$CI = &get_instance();
		$CI->load->model('customer_model');
		$list = $CI->customer_model->companyList();
		return $list;
	}
}
if ( ! function_exists('getAllTemplates'))
{	
	function getAllTemplates(){
		$CI = &get_instance();
		$CI->load->model('general_model');
		$list = $CI->general_model->getAllTemplates();
		return $list;
	}
}
if ( ! function_exists('getUsersByActDeactCompanies'))
{	
	function getUsersByActDeactCompanies($companyID,$status){
		$CI = &get_instance();
		$CI->load->model('customer_model');
		$count = $CI->customer_model->getUsersByActDeactCompanies($companyID,$status);
		return $count;
	}
}
if ( ! function_exists('findMyPreferenceWithName'))
{	
	function findMyPreferenceWithName($companyID){
		$CI = &get_instance();
		$CI->load->model('customer_model');
		$count = $CI->customer_model->findMyPreferenceWithName($companyID);
		return $count;
	}
}