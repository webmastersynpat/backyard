<div class="col-md-2 col-sm-2 col-xs-2" id="task_list">
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
	
		$checkTask = checkUserCreatedLeadFromLitigation();
		if((int)$checkTask->leads==0 || (int)$checkTask->leads<3){
			$checkApprovalSend = checkApprovalSend();
			if((int)$checkApprovalSend->sendTask==0){
				$allAdminUsers = findAdminUsers();
				if(count($allAdminUsers)>0){
					$getData = getTaskAccToType("LEAD");
					$subject="Create leads for this week";
					$message = "You have to create 3 leads per week.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					$requestArray = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'parent_id'=>0,'subject'=>$subject,'message'=>$message,'from_user_id'=>$allAdminUsers[0]->id,'doc_url'=>$Layout->baseUrl."leads/litigation","execution_date"=>date('Y-m-d'),"type"=>"LEAD","status"=>0);
					sendApprovalRequest($requestArray);
				}				
			}			
		}
	}	
}
	$waiting_approval = getUserTaskList();
	
?>
<script>_allParent="";function enterData(b,a){if(b==1){jQuery(".other_damages").css("display","none");jQuery(".other_docs").css("display","none");jQuery("#otherDamages_"+a).css("display","block")}else{if(b==2){jQuery(".other_docs").css("display","none");jQuery(".other_damages").css("display","none");jQuery("#otherDOCS_"+a).css("display","block")}}}function approvedFile(d){if(d!=""&&d>0){var f=$("#sb-site");var e="bg-default";var c="60";var b="dark";var a='<div id="refresh-overlay" class="ui-front loader ui-widget-overlay '+e+" opacity-"+c+'"><img src="<?php echo $Layout->baseUrl?>public/images/spinner/loader-'+b+'.gif" alt="" /></div>';if($("#refresh-overlay").length){$("#refresh-overlay").remove()}$(f).append(a);$("#refresh-overlay").fadeIn("fast");jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl?>opportunity/find_task",data:{token:d},cache:false,success:function(g){_data=jQuery.parseJSON(g);_allParent=_data;$("#refresh-overlay").fadeOut("fast");$(".glyph-icon",this).removeClass("icon-spin");if(_data!=undefined){if(_data.hasOwnProperty("type")){if(parseInt(_data.userType)==9&&_data.approved_type=="LEAD"){jQuery("#replyFromUser").html("Admin")}else{jQuery("#replyFromUser").html(_data.userName)}jQuery("#replyReceived").html(moment(new Date(_data.receivedData)).format("MMM D, YY"));jQuery("#replySubject").html(_data.subject);jQuery("#replyInputSubject").val(_data.subject);if(_data.doc_url!=""){jQuery("#replyLink").html("<a target='_BLANK' style='color:#3498db;' href='"+_data.doc_url+"'>Document Link</a>")}else{jQuery("#replyLink").html("No docs")}__message=_data.message;if(_data.hasOwnProperty("parents")&&_data.parents.length>0){for(i=0;i<_data.parents.length;i++){_pa=_data.parents[i];__message+="<br/><hr/>FROM: "+_pa.userName+"<br/>"+_pa.message}}jQuery("#replyMessage").html(__message);jQuery("#replyExecutionDate").html(_data.executionDate);jQuery("#replyLeadId").val(_data.id);jQuery("#replyParentId").val(_data.approved_id);jQuery("#replyType").val(_data.approved_type);jQuery("#formOPen").removeAttr("onclick").hide();jQuery("#replyForwardTask").show();jQuery("#replyCompleteTask").show();if(_data.approved_type=="OTHER_DOCS"){jQuery("#replyForwardTask").hide();jQuery("#replyCompleteTask").show();jQuery("#formOPen").show();jQuery("#formOPen").attr("onclick","enterData(2,"+_data.approved_id+")")}else{if(_data.approved_type=="ORDER_DAMAGES"){jQuery("#formOPen").show();jQuery("#formOPen").attr("onclick","enterData(1,"+_data.approved_id+")");jQuery("#replyForwardTask").hide();jQuery("#replyCompleteTask").show()}}if(_data.id===null){jQuery("#replyFlag").val(1)}else{jQuery("#replyFlag").val(0)}jQuery("#replyTaskModal").modal("show")}else{alert("Please try after sometime")}}else{alert("Please try after sometime")}}})}}function forwardEnabled(a){if(a.is(":checked")){jQuery("#forwardUserTo").show();jQuery("#forwardMessageTo").show();jQuery("#forwardExecutionDate").show();jQuery("#replyButton").html("Forward").show();jQuery("#replyCompleteTask").hide()}else{jQuery("#forwardUserTo").hide();jQuery("#forwardMessageTo").hide();jQuery("#forwardExecutionDate").hide();jQuery("#replyButton").hide();jQuery("#replyCompleteTask").show()}}function taskComplete(a){if(a.is(":checked")){jQuery("#replyButton").html("Confirm Completion").show();jQuery("#forwardUserTo").hide();jQuery("#forwardMessageTo").hide();jQuery("#forwardExecutionDate").hide()}else{jQuery("#replyButton").hide();jQuery("#forwardUserTo").hide();jQuery("#forwardMessageTo").hide();jQuery("#forwardExecutionDate").hide()}};</script>
<div class="dashboard-box bg-white content-box">
<div class="content-wrapper">
<div class="clearfix mrg6B">
<a href="javascript://" onclick="openTaskModal()" class="btn btn-success btn-block">
Create new task
</a>
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="createTaskModalLabel">Create a new task</h4>
</div>
<div class="modal-body">
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="button" class="btn btn-primary">Save</button>
</div>
</div>
</div>
</div>
</div>
<ul class="todo-box">
<?php
					
					if(count($waiting_approval)>0){
						for($i=0;$i<count($waiting_approval);$i++){
							$name = "";
							switch($waiting_approval[$i]->approved_type){
								case 'NDA':
									$name = "NDA approval for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'PPA':
									$name = "PPA approval for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'DD_FILE_MAKER':
									$name = "Create DD File Maker for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'MARKET_RESEARCH':
									$name = "Do Market Research for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'ASSETS':
									$name = "List of Assets for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'DD':
									$name = "Enter DD data in Docket for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'ORDER_DAMAGES':
									$name = "Upload order damages report for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'OTHER_DOCS':
									$name = "PPA, PLA, RTP, PPP report for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'LEAD':
									$name  ="Create Leads for this week"; 
								break;
								case 'LEAD_FORWARD':
									$name  ="One lead is forward to you";
								break;
								case 'CREATE_OPPORTUNITY':
									$name  ="Create opportunity from Lead";
								break;
								case 'NDA_EXECUTE_APPROVAL':
									$name  ="NDA Execute for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'opportunity':
								case 'lead':
									$name = $waiting_approval[$i]->subject;
								break;
								default:
									$name = $waiting_approval[$i]->plantiffs_name;
								break;
							}
							
							if((int)$waiting_approval[$i]->userType==9 && $waiting_approval[$i]->approved_type=='LEAD'){
								$userName = "Admin";
							} else {
								$userName = $waiting_approval[$i]->userName;
							}
				?>
<li>
<?php
							
							switch($waiting_approval[$i]->type){
								case 'Litigation':
									$colors_array = "bg-yellow";
								break;
								
								case 'Market':
									$colors_array = "bg-green";
								break;
								
								case 'General':
									$colors_array = "label-info";
								break;
								
								case 'SEP':
									$colors_array = "bg-warning";
								break;
							}
							$leadName = (!empty($waiting_approval[$i]->lead_name))?$waiting_approval[$i]->lead_name:$waiting_approval[$i]->plantiffs_name;
						?>
<a href="javascript://" onclick="approvedFile(<?php echo $waiting_approval[$i]->approved_id;?>)">
<?php 
								if(!empty($leadName)) {
									echo '<span class="tl-label bs-label <?php echo $colors_array; ?>"><?php echo $leadName;?></span>';
								}
							?>
<span for="todo-1" title="<?php echo $waiting_approval[$i]->subject;?>"><?php echo $waiting_approval[$i]->subject;?></span>
<span>From: <?php echo $userName;?> <i class="glyph-icon icon-clock-o"></i> <?php echo date("M d, H:i A",strtotime($waiting_approval[$i]->taskCreateDate));?></span>
</a>
</li>
<?php
					} } else {
				?>
<li class='border-red'>Empty</li>
<?php
					
					}
				
				?>
</ul>
</div>
</div>
</div>