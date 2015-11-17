<?php 
if((int)$this->session->userdata['type']!=9){
	$pageAssigned = getUserPageAssigned();
	$litigationCreate = 0;
	$marketCreate = 0;
	if(count($pageAssigned)>0){		
		foreach($pageAssigned as $page){
			if($page->page_url=='leads/litigation'){
				$litigationCreate = 1;
			}
			if($page->page_url=='leads/market'){
				$marketCreate = 1;
			}
		}
	}
	if($litigationCreate>0){
		/*Check 3 task for this week*/
		$checkTask = checkUserCreatedLeadFromLitigation();
		if((int)$checkTask->leads==0 || (int)$checkTask->leads<3){
			/*Create task for user for 3 leads*/
			/*Check today approval send*/
			$checkApprovalSend = checkApprovalSend();
			echo "<pre>";
			print_r($checkApprovalSend);
			if((int)$checkApprovalSend->sendTask==0){
				$requestArray = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'doc_url'=>$Layout->baseUrl."leads/litigation","type"=>"LEAD","status"=>0);
				sendApprovalRequest($requestArray);
			}			
		}
	}	
}
$findWaitingApprovalList = waitingApproval();
echo "<pre>";
print_r($findWaitingApprovalList);
die;

?>