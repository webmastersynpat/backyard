<?php
	$openTask = true;
	if((int)$this->session->userdata['type']!=9){
		if(!in_array(7,$this->session->userdata['modules_assign'])){
			$openTask = false;
		}
	}
	if($openTask===true):

$checkURIFunction = $this->uri->segment(2);
$checkURIController = $this->uri->segment(1);
$checkLead = $this->uri->segment(3);
$getTimeLine = array();
if($checkURIController=="leads"){
	if($checkURIFunction!="litigation"){
		if(empty($checkLead) && isset($results)){
			if(count($results)>0){
				$checkLead = $results[0]['litigation']->id;
			}
		}
		if(!empty($checkLead)){
			$getTimeLine = getUserTimeLine($this->session->userdata['id'],$checkLead,0);
		}		
	} 
} else if($checkURIController=="dashboard"){
	$getTimeLine = getUserTimeLine($this->session->userdata['id'],0,0);
} else if($checkURIController=="opportunity"){
	if(!empty($checkLead)){
		$getTimeLine = getUserTimeLine($this->session->userdata['id'],$checkLead,0);
	}
}

?>
<script>__mainEntityFollow=0;__mainEntityType="<?php echo $checkURIController;?>";<?php 
	if(!empty($checkLead)):
?>
__mainEntityFollow='<?php echo $checkLead;?>';<?php endif;?></script>
<div class="col-md-2 col-sm-2 col-xs-2" id="activity">
<div class="panel dashboard-box-1">
<div class="panel-body">
<div class="example-box-wrapper">
<div class="timeline-box timeline-box-left">

<?php 
							if(count($getTimeLine)>0){
								foreach($getTimeLine as $timeline){	
									$label = "";
									$colorClass="";
									if($checkURIController=="leads"){
										$label = "Leads";
										$colorClass= "bg-yellow";
									} else if($checkURIController=="opportunity"){
										$label =  "Opportunity";
										$colorClass= "label-info";
									} else {
										if($timeline->opportunity_id==0){
											$label =  "Leads";
											$colorClass= "bg-yellow";
										} else {
											$label =  "Opportunity";
											$colorClass= "label-info";
										}
									}
									
									if(isset($timeline->leadType)){
										switch($timeline->leadType){
											case 'Litigation':
												$colorClass = "bg-yellow";
											break;
											
											case 'Market':
												$colorClass = "bg-green";
											break;
											
											case 'General':
												$colorClass = "label-info";
											break;
											
											case 'SEP':
												$colorClass = "bg-warning";
											break;
										}
										$label = (!empty($timeline->lead_name))?$timeline->lead_name:$timeline->plantiffs_name;
									}
								
						?>

<div class="tl-row">
<div class="tl-item float-right">
<div class="tl-bullet bg-red"></div>
<div class="popover right">
<div class="arrow"></div>
<div class="popover-content" style="cursor:pointer">
<div class="tl-label bs-label <?php echo $colorClass;?>">
<?php echo $label;?>
</div>
<div class="tl-container">
<p class="tl-content">
<?php echo $timeline->message;?>
</p>
<div class="tl-footer clearfix">
<div class="tl-timeuser">
<?php echo date('d M,y H:i',strtotime($timeline->create_date));?>
<?php echo "&nbsp;&nbsp;&nbsp;".$timeline->name;?>
</div>
<?php 
															if(!empty($timeline->profile_pic)):
														?>
<img src="<?php echo $timeline->profile_pic?>" width="28"/>
<?php
															else:
														?>
<img src="<?php echo $Layout->baseUrl?>public/upload/user.png" width="28" />
<?php
															endif;
														?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
								}
							}
						?>
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="activityInfoModal" tabindex="-1" role="dialog" aria-labelledby="activityInfoModal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="createTaskModalLabel">Activity info</h4>
</div>
<div class="modal-body">
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<script>function timelineItemBindEvents(){$(".tl-row .popover-content").off("click").on("click",function(){$("#activityInfoModal").find(".modal-title").html("Lead Name: "+jQuery(this).find(".tl-label").html());_content=jQuery(this).find(".tl-content").html().split("by");$("#activityInfoModal").find(".modal-body").html("<p>Activity: "+_content[0]+"</p><p>By: "+jQuery(this).find(".tl-timeuser").first().html().split("&nbsp;&nbsp;&nbsp;")[1]+"</p><p>Date: "+jQuery(this).find(".tl-timeuser").first().html().split("&nbsp;&nbsp;&nbsp;")[0]+"</p>");$("#activityInfoModal").modal("show")})}$(function(){timelineItemBindEvents()});</script>
<?php endif;?>