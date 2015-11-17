<script src="/public/js-core/bootstrap.min.js"></script>

<div class="col-md-2 col-sm-2 col-xs-2" id="task_list" >
<!-- <h2 class="toptitle">Task</h2> -->
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
			if((int)$checkApprovalSend->sendTask==0){
				$requestArray = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'doc_url'=>$Layout->baseUrl."leads/litigation","type"=>"LEAD","status"=>0);
				sendApprovalRequest($requestArray);
			}			
		}
	}	
}
	$waiting_approval = getUserTaskList();
	
?>
<script>
function enterData(t,ID){
	if(t==1){
		jQuery('.other_damages').css('display','none');
		jQuery('.other_docs').css('display','none');
		jQuery("#otherDamages_"+ID).css('display','block');
	} else if(t==2){
		jQuery('.other_docs').css('display','none');
		jQuery('.other_damages').css('display','none');
		jQuery("#otherDOCS_"+ID).css('display','block');
	}
}

	function approvedFile(token){
		if(token!="" && token>0){
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>opportunity/approved_doc',
				data:{token:token},
				cache:false,
				success:function(res){
					_data = jQuery.parseJSON(res);
					if(parseInt(_data.send)>0){
						window.location = window.location.href;
					} else{
						alert('Please try after sometime.');
					}
				}
			});
		}
	}
</script>
	<div class="dashboard-box  bg-white content-box">
		<div class="content-wrapper">
			<div class="clearfix mrg6B">
				<a href="#createTaskModal1" class="btn btn-success btn-block" data-toggle="modal" data-target="#createTaskModal1">
					Create new task
				</a>
				<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
  					<div class="modal-dialog">
    					<div class="modal-content">
    						<form class="form-horizontal form-flat">
	      						<div class="modal-body">
									<div class="clearfix">
										<label class="control-label" style="float:left;">Create task for</label>
										<input type="text" class="form-control input-string" style="float: left; width: 477px; margin-top: 4px;">
									</div>
									<div class="clearfix mrg10T">
										<label class="control-label" style="float:left;">To begin on</label>
										<input type="text" class="form-control input-string bootstrap-datepicker" placeholder="mm/dd/yyyy" style="float: left; width: 496px; margin-top: 4px;">
									</div>
									<div class="clearfix mrg10T">
										<label class="control-label" style="float:left;">Subject:</label>
										<input type="text" class="form-control input-string" style="float: left; width: 514px; margin-top: 4px;">
									</div>
									<div class="form-group mrg10T">
										<textarea class="form-control" placeholder="Text of task"></textarea>
									</div>
	      						</div>
	      						<div class="modal-footer">
		      						<a href="#" class="btn btn-xs btn-success btn-approve">
										<i class="glyph-icon icon-check"></i>
									</a>
									Done
	        						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        						<button type="button" class="btn btn-primary">Save</button>
	      						</div>
    						</form>
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
								default:
									$name = $waiting_approval[$i]->plantiffs_name;
								break;
							}
				?>
					<li>
						<?php
							$colors_array = array (
							    0 => "bg-yellow",
							    1 => "bg-green",
							    2 => "bg-purple",
							    3 => "label-info"
							);
						?>
						<a href="#replyTaskModal" class="todo-box-link" data-toggle="modal" data-target="#replyTaskModal">
							<span class="tl-label bs-label <?php echo $colors_array[rand(0, 3)] ?>">Projectname</span>
							<br>
							<!-- <label for="todo-1" title="<?php echo $name;?>"></label> -->
							<div><?php echo substr($name,0,50);?></div>
							<!-- <span class="bs-label bg-warning" title="">Waiting</span> -->
							<div class="row">
								<div class="col-xs-6">
									From: Uzi Aloush
								</div>
								<div class="col-xs-6">
									22 Jan, 22:25PM
								</div>
							</div>
						</a>
						<!--span class="todo-box-opts clearfix">
							<?php 
								if($waiting_approval[$i]->approved_type!='LEAD' && $waiting_approval[$i]->approved_type!='LEAD_FORWARD' && $waiting_approval[$i]->approved_type!='CREATE_OPPORTUNITY'):
							?>
							 <a href="javascript://" onclick="approvedFile(<?php echo $waiting_approval[$i]->approved_id;?>)" class="btn btn-xs btn-success float-right btn-approve" title="Approved Doc">
								<i class="glyph-icon icon-check"></i>
							</a>
							<?php endif;?>
							<?php 
								if(!empty($waiting_approval[$i]->doc_url)):?>
									<a href="<?php echo $waiting_approval[$i]->doc_url?>" title="View" target="_BLANK" class="btn btn-xs btn-danger float-right" title="">
										<i class="glyph-icon icon-eye"></i>
									</a> 
							<?php endif;?>
							<?php 
								if($waiting_approval[$i]->approved_type=="ORDER_DAMAGES"){
							?>
								<a href='javascript:void(0)' class="btn btn-xs btn-danger float-right" onclick="enterData(1,<?php echo $waiting_approval[$i]->approved_id;?>);" title="Enter URL for damages report">
								<i class="glyph-icon icon-edit"></i></a>
							<?php
								} else if($waiting_approval[$i]->approved_type=="OTHER_DOCS"){
							?>
								<a href='javascript:void(0)' class="btn btn-xs btn-danger float-right" onclick="enterData(2,<?php echo $waiting_approval[$i]->approved_id;?>);" title="Enter URL for PPA,PLA,RLA">
								<i class="glyph-icon icon-edit"></i></a>
							<?php
								}
							?>
						</span-->
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