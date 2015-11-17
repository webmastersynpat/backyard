<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/multi-select/multiselect.js"></script>

<div class="row">
<?php echo $Layout->element('dummy_task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<style>
	.dataTables_filter > label {
		border-bottom: 1px solid #ddd;
		float: right !important;
		margin-bottom: 10px;
		margin-top: -5px;
	}
	.dataTables_filter > label > label {
		background: none repeat scroll 0 0 #ffffff;
	    float: left;
	    padding-bottom: 5px;
	    padding-top: 0 !important;
	    position: relative;
	    top: 8px;
	}
	.dataTables_filter > label > input {
		border-bottom: none !important;
		height: 19px;
		margin-top: 5px;
	}

	input[type=checkbox] {
		margin: 0;
	}
</style>

<style id="tempStyles">
	.tab-pane {
		display: block;
	}
</style>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Opportunity</a></li><li class='active'>Working on Opportunity</li>");
});
</script>
<?php 
	$active = 0;
	if(count($lead_stage)>0){
		$active = (int) $lead_stage->stage;
	}
	$opportunityType ="";
	$opportunityName = "";
	$sellerName = "";
	if(count($lead_data)>0){
		$opportunityType = $lead_data->type;
		$sellerName = $lead_data->plantiffs_name;
		$opportunityName = "";
		switch($lead_data->type){
			case 'Litigation':
				$opportunityName = $lead_data->case_name." - ".$lead_data->case_number;
			break;
			case 'Market':
			case 'General':
			case 'SEP':
				$opportunityName = $lead_data->plantiffs_name." - ".$lead_data->relates_to." - ".$lead_data->portfolio_number;
			break;
			default:
				$opportunityName = $lead_data->plantiffs_name;
			break;
		}
	}
?>
	<div class="panel dashboard-box">
    	<div class="panel-body">
    		<div class="example-box-wrapper">
				<?php 
				if($this->session->flashdata('message')){
				?>
					<?php echo $this->session->flashdata('message');?>
				<?php					
					}
				?>
				<?php 
					if($this->session->flashdata('error')){
				?>
					<?php echo $this->session->flashdata('error');?>
				<?php					
					}
				?>
    			<div class="row" style="margin-bottom: 15px;">
    				<div class="col-xs-9">
						<form class="form-horizontal form-flat">
							<div class="row">
								<div class="col-xs-4">
									<div class="form-group input-string-group bigmr">
										<label class="control-label">Opportunity Type:</label>
										<input type="text" disabled="" class="form-control" value="<?php echo $opportunityType?>" placeholder="Opportunity Type">
									</div>
								</div>
								<div class="col-xs-4">
									<div class="form-group input-string-group bigmr">
										<label class="control-label">Opportunity Name:</label>
										<input type="text" disabled="" class="form-control" value="<?php echo $opportunityName?>" placeholder="Opportunity Name">
									</div>
								</div>
								<div class="col-xs-4">
									<div class="form-group input-string-group nomr">
										<label class="control-label">Seller's Name:</label>
										<input type="text" disabled="" class="form-control" value="<?php echo $sellerName?>" placeholder="Seller's Name">
									</div>
								</div>
							</div>
						</form>
    				</div>
    				<!--<div class="col-xs-3 text-right">
    					<a href="#" class="btn btn-primary btn-small">PD's Page</a>
    				</div>-->
    			</div>

				<ul id="mainTabs" class="list-group list-group-separator row list-group-icons">
					<li class="col-md-3  <?php if($active<=4):?>active<?php endif;?>">
						<a class="list-group-item" data-toggle="tab" href="#createOpportunityTab">
							<i class="glyph-icon font-red icon-bullhorn"></i>
							Create an Opportunity
						</a>
					</li>
					<li class="col-md-3 <?php if($active>3):?>active<?php endif;?>">
						<a class="list-group-item" data-toggle="tab" href="#signPPATab">
							<i class="glyph-icon icon-dashboard"></i>
							Sign a PPA
						</a>
					</li>
					<li class="col-md-3">
						<a class="list-group-item" data-toggle="tab" href="#syndicateTab">
							<i class="glyph-icon font-success icon-camera"></i>
							Syndicate
						</a>
					</li>
					<li class="col-md-3">						
						<a class="list-group-item" data-toggle="tab" href="#sharingDocumentsTab">
							<i class="glyphicon glyph-icon icon-file-word-o font-primary"></i>
							Sharing Documents
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="createOpportunityTab" class="tab-pane <?php if(($active==0 || $active==1) ||(  $active>=2 && $active<5)):?>active in<?php endif;?> ">
						<div class="form-wizard">
							<ul>
								<li class="<?php if($active==1 || $active==0):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">1</label>
									</a>
								</li>
								<li class="<?php if($active==2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">2</label>
									</a>
								</li>
								<li class="<?php if($active>2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">3</label>
									</a>
								</li>
								<li class="<?php if($active>2 && isset($lead_report->eou_folder) && (int)$lead_report->eou_folder==2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">4</label>
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-xs-3">
											<div class="step-container <?php if($active>=0):?>active<?php endif;?>">
												<?php echo form_open('opportunity/acquisition',array('class'=>'form-horizontal form-flat','role'=>'form'));?>
												<?php 
													$marketID = "";
													$sellerUpfront = "";
													$noOfPatent = "";
													$assignedData = array();
													$technologiesData = array();
													if(isset($acquisition['acquisition']) && count($acquisition['acquisition'])>0){
														$marketID = $acquisition['acquisition']->market_sector;
														$sellerUpfront = $acquisition['acquisition']->seller_upfront;
														$noOfPatent = $acquisition['acquisition']->no_of_patent;
														if(count($acquisition['assigned'])>0){
															$assignedData = $acquisition['assigned'];
														}
														if(count($acquisition['technologiesData'])>0){
															$technologiesData = $acquisition['technologiesData'];
														}
													} else if(count($lead_data)>0){												
														if((int)$lead_data->no_of_patent!=0){
															$noOfPatent = $lead_data->no_of_patent;
														} else {
															$noOfPatent = $lead_data->no_of_prospects;
														}
														$sellerUpfront = $lead_data->expected_price;												
													}										
												?>
													<div class="row">
														<div class="col-sm-12">
															<div class="form-group input-string-group select-string-group nomr">
																<label class="control-label" for="acquisitionMarketSector">Market Sector:</label>
																<select class="form-control custom-select" required="" id="acquisitionMarketSectorW" name="acquisition[market_sector]">
																	<option value="">-- Select Market Sector --</option>
																	<?php 
																		if(count($market_sectors)>0){
																			foreach($market_sectors as $sector):
																				$selected = "";
																				if($marketID==$sector->id){
																					$selected = 'SELECTED="SELECTED"';
																				}
																	?>
																		<option <?php echo $selected ;?> value="<?php echo $sector->id?>"><?php echo $sector->name?></option>
																	<?php
																			endforeach;
																		}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-sm-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label" for="acquisitionSellerUpFront">Seller Upfront Price ($M):</label>
																<?php echo form_input(array('name'=>'acquisition[seller_upfront]','id'=>'acquisitionSellerUpFront','value'=>$sellerUpfront,'placeholder'=>'','class'=>'form-control'));?>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-sm-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label" for="numberOfPatent">Number of Patent:</label>
																<?php echo form_input(array('name'=>'acquisition[no_of_patent]','id'=>'numberOfPatent','placeholder'=>'','value'=>$noOfPatent,'class'=>'form-control'));?>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Technologies:</label>
														<div class="col-sm-12 mrg5T">
															<select multiple class="multi-select" id="acquisitionTechnologies" name="acquisition[technologies][]">
																<?php 
																	if(count($technologies)>0){
																		foreach($technologies as $technology):
																			$selected = "";
																			foreach($technologiesData as $tech){
																				
																				if($technology->id==$tech->technology_id){
																					$selected = "SELECTED='SELECTED'";
																				}
																			}
																?>
																		<option <?php echo $selected;?> value="<?php echo $technology->id?>"><?php echo $technology->name;?></option>
																<?php
																		endforeach;
																	}
																?>
															</select>
														</div>
													</div>
												<div class="row mrg10T">
													<div class="col-sm-12">
														<button class="btn btn-primary" type="submit">Save</button>
													</div>
												</div>
													<input type="hidden" name="acquisition[lead_id]" value="<?php echo $lead_id;?>"/>
													
												<?php echo form_close();?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container <?php if($active>1):?>active<?php endif;?>">
												<?php if(isset($acquisition['acquisition']) && count($acquisition['acquisition'])>0):?>
												<script>
													_token = '<?php echo $lead_id;?>';
													function taskMessageBox(_data){
														var refreshParent = $("#sb-site");
														var loaderTheme = "bg-default";
														var loaderOpacity = "60";
														var loaderStyle = "dark";
														var loader = '<div id="refresh-overlay" class="ui-front loader ui-widget-overlay ' + loaderTheme + ' opacity-' + loaderOpacity + '"><img src="<?php echo $Layout->baseUrl?>public/images/spinner/loader-' + loaderStyle + '.gif" alt="" /></div>';
														if ( $('#refresh-overlay').length ) {
															$('#refresh-overlay').remove();
														}
														__mainEntityFollow = _data.lead_id;
														$(refreshParent).append(loader);
														$('#refresh-overlay').fadeIn('fast');
														jQuery("#taskUserId").val(_data.user_id);
														jQuery("#taskSubject").val(_data.subject);
														jQuery("#taskMessage").val(_data.message);
														jQuery("#taskExecutionDate").val(_data.execution_date);
														jQuery("#taskDocUrl").val(_data.doc_url);
														jQuery("#taskParentId").val(_data.parent_id);
														jQuery("#taskFromUserId").val(_data.from_user_id);
														jQuery("#taskLeadId").val(_data.lead_id);
														jQuery("#taskType").val(_data.type);
														jQuery("#taskId").val(_data.task_id);
														jQuery('#createTaskModal1').modal('show');
													}
													function sendNDA(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery('#spinner-loader-nda').css('display','inline-block');
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/nda',
																data:{token:_token},
																cache:false,
																success:function(res){
																	jQuery('#spinner-loader-nda').css('display','none');
																	_data = jQuery.parseJSON(res);
																	if(_data.url!=""){
																		window.open(_data.url,'_BLANK');
																		if(jQuery("#embedCIPOApproval").find("#cipoApproval").length==0){
																			jQuery("#embedCIPOApproval").append("<tr><td style='border:0px;'><a href='javascript:void(0)' class='btn btn-info' id='cipoApproval' onclick='cipoApproval();'>CIPO Approval</a><span style='display:none;float:none;' id='spinner-loader-nda-cipo' class=glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A title='Please wait....' data-original-title='icon-spin-6'></span></td></tr>");
																		}
																		
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													
													function cipoApproval(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery('#spinner-loader-nda-cipo').css('display','inline-block');	
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/cipo_approval',
																data:{token:_token},
																cache:false,
																success:function(res){
																	jQuery('#spinner-loader-nda-cipo').css('display','none');
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		jQuery("#cipoApproval").removeClass('btn-info').addClass('btn-warning');
																		taskMessageBox(_data);
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													
													function sharedDocs(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery('#spinner-loader-nda-shared').css('display','inline-block');	
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/shared_docs',
																data:{token:_token},
																cache:false,
																success:function(res){
																	jQuery('#spinner-loader-nda-shared').css('display','none');
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		//window.location = window.location.href;
																		taskMessageBox(_data)
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													function shareWithUsers(){
														if(jQuery('input[name="share[users][]"]:checked').length==0){
															alert('Please select contact from list');
														} else {
															var sUsers =[];
															jQuery('input[name="share[users][]"]:checked').each(function(){
																sUsers.push(jQuery(this).val());
															});
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/share_with_users',
																data:{token:_token,share:sUsers,type:'NDA'},
																cache:false,
																success:function(res){
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		//window.location = window.location.href;
																		taskMessageBox(_data)
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													
													function executeNDA(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/executeNDA',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		//window.location = window.location.href;
																		taskMessageBox(_data);
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													function ndaExecuted(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/ndaExecuted',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		//window.location = window.location.href;
																		taskMessageBox(_data);
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
													function eouConfirmation(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/eouConfirmation',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data = jQuery.parseJSON(res);
																	if(parseInt(_data.send)>0){
																		window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}
																}
															});
														}
													}
												</script>
												<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/chosen/chosen.js"></script>
												<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/chosen/chosen-demo.js"></script>
												<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datatable/datatable.js"></script>
												
													<div class="row">
														<div class="col-xs-3"></div>
														<div class="col-xs-6">
															<!--<a href="#" class="btn btn-black">Draft an NDA</a>-->
															<?php if(isset($lead_level) && (count($lead_level)==0 || $lead_level->level>=1)):?>
															<?php 
																$flag = 0;
																if(!isset($lead_report)){
																	$flag=1;
																} else if(isset($lead_report)){
																	if(!is_object($lead_report)){
																		$flag=1;
																	} else if(count($lead_report)==0){
																		$flag=1;
																	}
																}
															?>
															<a href="javascript:void(0)" <?php if($flag==1):?> onclick="sendNDA();" <?php endif;?>  class='btn btn-block <?php if($flag==1):?>btn-black <?php else:?> btn-success <?php endif;?>'>Draft an NDA</a>
															<span style='display:none;float:none;' id="spinner-loader-nda" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
															<?php endif;?>
														</div>
													</div>
													<div class="row mrg10T" id="embedCIPOApproval">
														<div class="col-xs-3"></div>
														<div class="col-xs-6">
															<!--<a href="#" class="btn btn-primary">CIPO approval</a>-->
															<?php if(isset($lead_level) && count($lead_level)>0 && ($lead_level->level>=1)):
																$color = 'btn-info';
																if(count($lead_report)>0){
																	if((int)$lead_report->cipo_approved==1){
																		$color = 'btn-warning';
																	} else if((int)$lead_report->cipo_approved==2){
																		$color = 'btn-success';
																	}
																}
															?>
															<?php if($lead_report->draft_nda>0):?>
															<a href="javascript:void(0)" id="cipoApproval" <?php if((int)$lead_report->cipo_approved<1):?>onclick="cipoApproval()"<?php endif;?> class='btn btn-block <?php echo $color;?>'>CIPO Approval</a>
															<span style='display:none;float:none;' id="spinner-loader-nda-cipo" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
															<?php endif;?>
															<?php endif;?>
														</div>
													</div>
													
													<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>=2 && $lead_report->cipo_approved==2):?>
													<div class="mrg10T"><label>Share NDA with Seller:</label></div>
													<div class="form-horizontal form-flat">
														<style>
															/*div.dataTables_scrollHeadInner{width:100% !important; box-sizing: border-box !important; padding-right: 17px !important; }
															div.dataTables_scrollHeadInner>table.dataTable{width:100% !important}*/
															#datatable-contacts-sharing_info{display:none;}
														</style>
														<script>
															var ___table ;
															$(document).ready(function() {		
																___table = $('#datatable-contacts-sharing').DataTable({								
																	"searching":true,
																	"autoWidth": true,
																	"paging": false,
																	"sScrollY": "200px",
																	"sScrollX": "100%",
																	"sScrollXInner": "100%",
																	"columnDefs": [
																		{ "width": "5%" },
																		{ "width": "20%" },
																		{ "width": "75%" },
																	]
																});
															});
														</script>
														<div class="mrg10T"></div>
														<table class="table" class="table" id="datatable-contacts-sharing">
															<thead>
																<tr>
																	<th><div class="text-center">x</div></th>
																	<th>Name</th>
																	<th>Company Name</th>
																</tr>
															</thead>
															<tbody>
																<?php 
																	if(count($lead_contacts)>0){
																		foreach($lead_contacts as $contact){
																			$selected="";
																			foreach($doc_shared as $doc){
																				if($doc->contact_id==$contact->id){
																					$selected='CHECKED="CHECKED"';
																				}
																			}
																?>
																<tr>
																	<td><input <?php echo $selected;?> type="checkbox" name="share[users][]" id="shareUsers"  value="<?php echo $contact->id?>"/></td><td> <?php echo $contact->name;?></td>
																	<td><?php echo $contact->company_name;?></td>
																</tr>
																<?php
																		}
																	}
																?>
															</tbody>
														</table>
													</div>
													<div class=''>
														<a href='javascript://' onclick="shareWithUsers();" class='btn btn-primary'>Go</a>
													</div>
													<?php endif;?>
													<div class="row mrg10T">
														<div class="col-xs-3"></div>
														<div class="col-xs-6">
															<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>3):
																	$color = 'btn-black';
																	if(count($lead_report)>0){
																		if((int)$lead_report->executed_nda==1){
																			$color = 'btn-warning';
																		} else if((int)$lead_report->executed_nda==2){
																			$color = 'btn-success';
																		}
																	}
																?>
															<!--<a href="#" class="btn btn-primary">Execute NDA</a>-->
															<button class='btn btn-block <?php echo $color;?>' <?php if((int)$lead_report->executed_nda<1):?>onclick="executeNDA();" <?php endif;?>>Execute NDA</button>
															<?php endif;?>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-xs-3"></div>
														<div class="col-xs-6">
															<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>4 && $lead_report->executed_nda==2):?>
															<!--<a href="#" class="btn btn-primary">NDA Executed</a>-->
															<button class='btn btn-block <?php if((int)$lead_report->nda_execute==0):?>btn-primary <?php elseif((int)$lead_report->nda_execute==2):?> btn-success<?php endif;?>' onclick="javascript:void(0)" type="button">NDA Executed</button>
															<?php endif;?>
														</div>
													</div>
												
												<?php endif;?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container <?php if($active>2):?>active<?php endif;?>">
												<?php if($active>2):?>
												<div class="row">
														<div class="col-xs-3"></div>
													<div class="col-xs-6">
														<!--<a href="#" class="btn btn-success">Seller's EOU in Folder</a>-->
														<button class='btn btn-block <?php if($lead_report->eou_folder=='0'):?>btn-primary<?php elseif($lead_report->eou_folder=='2'):?>btn-success <?php endif;?>' <?php if($lead_report->eou_folder=='0'):?>onclick="eouConfirmation();" <?php endif;?>>Seller's EOU in Folder</button>
													</div>
												</div>
												<?php 
													if($lead_report->eou_folder=='2'){
												?>
												<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.css">
												<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.css">
												<script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/lib/bootstrap-typeahead.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jquery.autoresize.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery-ui-1.8.23.draggable.min.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
												<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
												<script>
													function sendEouData(){
														var handsontable = jQuery("#eou_data").handsontable("getData");
														_eouData = JSON.stringify(handsontable);
														if(_eouData){
															if(_token==0 || _token==""){
																alert("Please try after sometime.");
															} else {
																jQuery.ajax({
																	type:'POST',
																	url:'<?php echo $Layout->baseUrl?>opportunity/sendEouData',
																	data:{eou_data:_eouData,token:_token},
																	cache:false,
																	success:function(res){
																		_data  = jQuery.parseJSON(res);
																		if(parseInt(_data.send)>0){
																			window.location = window.location.href;
																		} else {
																			alert("Please try after sometime.");
																		}
																	}
																});
															}
														} else {
															alert('Please enter data');
														}
													}
													jQuery(document).ready(function(){
														__data=[
															<?php 
															if(count($eou_data)>0):
																for ($i = 0; $i < count($eou_data); $i++) {
																	echo "['" . $eou_data[$i]->company . "','" . $eou_data[$i]->product. "','" . $eou_data[$i]->quality. "']";
																	if ($i < count($eou_data) - 1) {
																		echo ",";
																	}
																}
															endif;
															?>
															];	
														var $container = jQuery("#eou_data");
														$container.handsontable({
															startRows: 1,
															startCols: 3,
															colHeaders: ['Company', 'Product','Quality'],
															minSpareCols: 0,
															minSpareRows: 1,
															colWidths: [90,80, 60],
															manualColumnResize: true,
															contextMenu: false,
															columns: [
																		{},
																		{},
																		{
																		  type: 'dropdown',
																		  source: ["H", "M", "L"]
																		}
																	]
														});
														jQuery("#eou_data").data('handsontable').loadData(__data);
														/*jQuery("#eou_data").find('table').parent().css('left','10px');*/
													});
													
													
												</script>
												<form class="form-horizontal form-flat">
													<div class="clearfix">
														<label style="float:left;" class="control-label">How many SEP:</label>
														<input type="text" value="value" style="float: left; width: 100px; margin-top: 4px;" class="form-control input-string">
													</div>
												</form>
												<div id="eou_data" class='dataTable mrg10T'>
													
												</div>
												<!--<table class="table mrg10T">
													<thead>
														<tr>
															<th>Company</th>
															<th>Product</th>
															<th>Quality</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																Microsoft
															</td>
															<td>
																Xbox
															</td>
															<td>
																H/M/L
															</td>
														</tr>
													</tbody>
												</table>-->
												<div class="mrg10T">
													<button type="button" class='btn btn-primary' onclick="sendEouData();">Save</button>
												</div>
												<?php
													}
												?>
												<?php endif;?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container <?php if($active>2):?>active<?php endif;?>">
												<?php if($active>2):
													if($lead_report->eou_folder=='2'){
												?>
													<script>
													function sendEouData(){
														var handsontable = jQuery("#eou_data").handsontable("getData");
														_eouData = JSON.stringify(handsontable);
														if(_eouData){
															if(_token==0 || _token==""){
																alert("Please try after sometime.");
															} else {
																jQuery.ajax({
																	type:'POST',
																	url:'<?php echo $Layout->baseUrl?>opportunity/sendEouData',
																	data:{eou_data:_eouData,token:_token},
																	cache:false,
																	success:function(res){
																		_data  = jQuery.parseJSON(res);
																		if(parseInt(_data.send)>0){
																			window.location = window.location.href;
																		} else {
																			alert("Please try after sometime.");
																		}
																	}
																});
															}
														} else {
															alert('Please enter data');
														}
													}
													jQuery(document).ready(function(){
														__data1=[
															<?php 
															if(count($sep_data)>0):
																for ($i = 0; $i < count($sep_data); $i++) {
																	echo "['" . $sep_data[$i]->standard . "','" . $sep_data[$i]->product. "','" . $sep_data[$i]->eou. "','" . $sep_data[$i]->in_folder. "']";
																	if ($i < count($sep_data) - 1) {
																		echo ",";
																	}
																}
															endif;
															?>
															];	
															__data2=[
															<?php 
															if(count($sep_another_data)>0):
																for ($i = 0; $i < count($sep_another_data); $i++) {
																	echo "['" . $sep_another_data[$i]->company . "','" . $sep_another_data[$i]->product. "','" . $sep_another_data[$i]->eou. "','" . $sep_another_data[$i]->in_folder. "']";
																	if ($i < count($sep_another_data) - 1) {
																		echo ",";
																	}
																}
															endif;
															?>
															];	
														var $container2 = jQuery("#sepHoldData");
														$container2.handsontable({
															startRows: 1,
															startCols: 4,
															colHeaders: ['Standard', 'Product','EOU','In Folder'],
															minSpareCols: 0,
															minSpareRows: 1,
															colWidths: [70,60, 30,70],
															manualColumnResize: true,
															contextMenu: false,
															columns: [
																		{},
																		{},
																		{},
																		{
																		  type: 'dropdown',
																		  source: ["Yes", "No"]
																		}
																	]
														});
														jQuery("#sepHoldData").data('handsontable').loadData(__data1);
														var $container1 = jQuery("#potentialHoldData");
														$container1.handsontable({
															startRows: 1,
															startCols: 4,
															colHeaders: ['Company', 'Product','EOU','In Folder'],
															minSpareCols: 0,
															minSpareRows: 1,
															colWidths: [70,60, 30,70],
															manualColumnResize: true,
															contextMenu: false,
															columns: [
																		{},
																		{},
																		{},
																		{
																		  type: 'dropdown',
																		  source: ["Yes", "No"]
																		}
																	]
														});
														jQuery("#potentialHoldData").data('handsontable').loadData(__data2);
														
													});
													
													function checkAllData(){
														var handsontable = jQuery("#sepHoldData").handsontable("getData");
														_sepData = JSON.stringify(handsontable);
														var handsontable = jQuery("#potentialHoldData").handsontable("getData");
														_sep_another_Data = JSON.stringify(handsontable);
														jQuery("#sepAnotherData").val(_sep_another_Data);
														jQuery("#sepData").val(_sepData);
														return true;
													}
												</script>
												<?php											
													echo form_open('opportunity/sep_data',array('class'=>"form-horizontal form-flat",'onsubmit'=>'return checkAllData()'));
													$sepNumber ="";
													if((int)$acquisition['acquisition']->no_of_sep>0){
														$sepNumber = $acquisition['acquisition']->no_of_sep;
													}
													$potentialLicensees ="";
													if((int)$acquisition['acquisition']->no_of_potential_licensees>0){
														$potentialLicensees = $acquisition['acquisition']->no_of_potential_licensees;
													}
												?>
													<!--<table class="table mrg10T">
														<thead>
															<tr>
																<th>Standard</th>
																<th>Product</th>
																<th>EOU</th>
																<th>In folder</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	802.11g
																</td>
																<td>
																	modem
																</td>
																<td>
																	yes/no
																</td>
																<td>
																	yes/no
																</td>
															</tr>
														</tbody>
													</table>-->
													<div class="clearfix mrg10T" style="clear: both;">
														<label style="float:left;" class="control-label">Number of Potential Licenses:</label>
														<input type="text" style="float: left; width: 100px; margin-top: 4px;" value="<?php echo $potentialLicensees;?>" class="form-control input-string">
													</div>
													<div id="sepHoldData" style='float:left;' class='dataTable mrg10T'>													
													</div>
													<!--<div class="text-right mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>-->
													<div id="potentialHoldData" style='float:left;' class=' dataTable mrg10T'>													
													</div>
													<!--<table class="table mrg10T">
														<thead>
															<tr>
																<th>Company</th>
																<th>Product</th>
																<th>EOU</th>
																<th>In folder</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Microsoft
																</td>
																<td>
																	modem
																</td>
																<td>
																	yes/no
																</td>
																<td>
																	yes/no
																</td>
															</tr>
														</tbody>
													</table>-->
													<div class="mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>
													<input type="hidden" name="sep[sep_another_data]" id="sepAnotherData"/>
													<input type="hidden" name="sep[sep_data]" id="sepData"/>
													<input type="hidden" name="sep[lead_id]" value="<?php echo $lead_id;?>" id="sepLeadID"/>
												<?php echo form_close();
												}
												endif;?>
											</div>
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div id="signPPATab" class="tab-pane <?php if($active>3):?> active in<?php endif;?>">
						<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
						<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
						<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
						<script>
							function draft_a_ppa(){
								if(_token==0 || _token==""){
									alert("Please try after sometime.");
								} else {
									jQuery("#spinner-loader-ppa").css('display','inline-block');
									jQuery.ajax({
										type:'POST',
										url:'<?php echo $Layout->baseUrl?>opportunity/draft_a_ppa',
										data:{token:_token},
										cache:false,
										success:function(res){
											jQuery("#spinner-loader-ppa").css('display','none');
											_data  = jQuery.parseJSON(res);
											if(_data.url!=""){
												window.open(_data.url,"_BLANK");	/*											window.location = window.location.href;*/
											} else {
												alert("Please try after sometime.");
											}
										}
									});
								}
							}
							var ___table ;
							$(document).ready(function() {		
								___table = $('#datatable-contacts').DataTable({								
									"searching":false,
									"autoWidth": false,
									"scrollY": "300px",
									"scrollCollapse": true,
									"paging": false,
									"columnDefs": [
										{ "width": "5%" },
										{ "width": "55%" },
										{ "width": "20%" },
										{ "width": "20%" },
									]
								});
							});
						</script>
						<div class="form-wizard">
							<ul>
								<li class="<?php if((int)$active>=5 &&count($approval_request_assets)==0):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">1</label>
									</a>
								</li>
								<li class="<?php if(count($approval_request_assets)>0 && (isset($lead_report->execute_ppa) && $lead_report->execute_ppa<3)):?> active <?php endif;?>">
									<a>
										<label class="wizard-step">2</label>
									</a>
								</li>
								<li class="<?php if(isset($lead_report->execute_ppa) && $lead_report->execute_ppa==3):?> active <?php endif;?>">
									<a>
										<label class="wizard-step">3</label>
									</a>
								</li>
								<li class="">
									<a>
										<label class="wizard-step">4</label>
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-xs-3">
											<div class="step-container <?php if((int)$active==5 && count($approval_request_assets)==0):?>active<?php endif;?>">
												<?php if((int)$active>=5):?>
												<!--<div>
													<a href="#" class="btn btn-success">Draft a PPA</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">DEMO DDMM</a>
												</div>-->
												<div class="row">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<button type='button' <?php if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==0):?>onclick="draft_a_ppa();"<?php endif;?> class='btn btn-block <?php if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==0):?>btn-black<?php else: ?>btn-success<?php endif;?>'>Draft a PPA</button>
													</div>
												</div>
												<span style='display:none;float:none;' id="spinner-loader-ppa" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
												<?php 
													if(!empty($acquisition['acquisition']->store_name)):
												?>
												<div class="mrg10T">
													<a class='btn btn-primary' href="http://www.synpat.com/store/<?php echo $acquisition['acquisition']->store_name;?>" target="_BLANK"><?php echo $acquisition['acquisition']->store_name;?></a>
												</div>
												<?php endif;?>
												<?php if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==2):?>
												<script>
													jQuery(document).ready(function(){
														__data3=[
													<?php 
													if(count($assets_data)>0):
														for ($i = 0; $i < count($assets_data); $i++) {
															echo "['" . $assets_data[$i]->name . "']";
															if ($i < count($assets_data) - 1) {
																echo ",";
															}
														}
													endif;
													?>
													];	
														var $container3 = jQuery("#listOfAssets");															
														$container3.handsontable({																startRows: 1,																startCols: 1,																colHeaders: ["List Of Assets"],																minSpareCols: 0,																minSpareRows: 1,																colWidths: [230],																manualColumnResize: true,																contextMenu: false,																columns: [																			{},																																					]
															
														});
														jQuery("#listOfAssets").data('handsontable').loadData(__data3);
													});		
													function approvalList(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery("#spinner-loader-assets-cipo").css('display','inline-block');
															var handsontable = jQuery("#listOfAssets").handsontable("getData");
															_assetsData = JSON.stringify(handsontable);
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/approvalList',
																data:{token:_token,asset_data:_assetsData},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	jQuery("#spinner-loader-assets-cipo").css('display','none');
																	if(_data.url!=""){
																		window.open(_data.url,"_BLANK");
																		taskMessageBox(_data);
																	} else {
																		alert("Please try after sometime.");
																	}
																	
																	/*if(parseInt(_data.send)>0){
																		window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}*/
																}
															});
														}
													}
													function execute_ppa(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/execute_ppa',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);
																		//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}
																	
																	/*if(parseInt(_data.send)>0){
																		window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}*/
																}
															});
														}
													}
													function startDD(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/startDD',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);
																		//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}
																	
																	/*if(parseInt(_data.send)>0){
																		window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}*/
																}
															});
														}
													}
													function editInviteesData(id){
														if(__invitees!=undefined){
															_invitees = __invitees;
															for(i=0;i<_invitees.length;i++){
																if(_invitees[i]['contact'].id==id){
																	jQuery("#inviteesCompanyName").val(_invitees[i]['contact'].company_name);
																	jQuery("#inviteesPersonInCharge").val(_invitees[i]['contact'].person_in_charge);
																	jQuery("#inviteesTelephone").val(_invitees[i]['contact'].telephone);
																	jQuery("#inviteesEmail").val(_invitees[i]['contact'].email);
																	/*jQuery("#inviteesLeadId").val(_invitees[i]['contact'].lead_id);*/
																	jQuery("#inviteesId").val(_invitees[i]['contact'].id);
																	jQuery("#marketSector>option").each(function(){
																		__id = jQuery(this).attr('value');
																		if(_invitees[i]['sector'].length>0){
																			for(j=0;j<_invitees[i]['sector'].length;j++){																					
																				if(_invitees[i]['sector'][j].id==__id){
																					jQuery(this).attr('selected','selected');
																				}
																			}
																		}
																	});
																	jQuery(".multi-select").multiSelect('refresh');
																	jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
																}																	
															}
														}
													}
													function dueDilligenceFileMaker(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/dueDiligenceFileMaker',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}
													}
													function startMarketResearch(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/startMarketResearch',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}
													}
													function ppaExecuted(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/ppaExecuted',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}
													}
													function orderDamagesByCIPO(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/orderDamagesByCIPO',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}
													}
													function insertDataDocket(){
														jQuery("#insert_data_docket").removeClass('hide').addClass('display-block');
													}
													function uploadDocumentByCIPO(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/uploadDocumentByCIPO',
																data:{token:_token},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.send>0){
																		taskMessageBox(_data);//window.location = window.location.href;
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}															
													}
													function checkListContacts(object){
														__val = object.val();
														if(__val>0){
															jQuery("#marketSector>li").each(function(){
																jQuery(this).find('input[type="checkbox"]').attr("checked",false);
															});
															object.attr("checked", true);
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/getCheckListContact',
																data:{token:__val},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.length>0){
																		___table.destroy();	
																		jQuery("#datatable-contacts").find('tbody').empty();
																		__invitees = _data;
																		for(i=0;i<_data.length;i++){
																			__name = (_data[i].contact.person_in_charge!="")?_data[i].contact.person_in_charge:_data[i].contact.name;
																			jQuery("#datatable-contacts>tbody").append('<tr><td style="max-width:5% !important;min-width:5% !important"><input type="checkbox" name="invite[contact_id][]" value="'+_data[i].contact.id+'"/></td><td style="width:55%"><a href="javascript://" onclick="editInviteesData('+_data[i].contact.id+');">'+_data[i].contact.company_name+'</a></td><td style="width:20%">Membership</td><td style="width:20%">'+__name+'</td></tr>');
																		}
																		___table =jQuery("#datatable-contacts").DataTable( {
																			"searching":false,
																			"autoWidth": false,
																			"scrollY": "300px",
																			"scrollCollapse": true,
																			"columnDefs": [
																				{ "width": "5%" },
																				{ "width": "55%" },
																				{ "width": "20%" },
																				{ "width": "20%" },
																			]
																		});
																	} else {
																		alert("Please try after sometime.");
																	}																		
																}
															});
														}															
													}
													function insertEmbedCode(){
														jQuery("#embedCode").css('display','block');
													}
												</script>
												<!--<table class="table table-bordered mrg10T">
													<thead>
														<tr>
															<th>List of Assets</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Asset 1</td>
														</tr>
													</tbody>
												</table>-->
												<div id="listOfAssets" style='float:left; min-height:56px; height: auto; width:100%;' class='mrg10T dataTable'>
												</div>
												
												<div style="clear:both;"></div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary">List Approved by CIPO</a>-->
														<a href='javascript://' <?php if(count($assets_data)==0):?>onclick="approvalList();"<?php  endif;?> class='btn btn-block <?php if(count($assets_data)==0):?>btn-black<?php elseif(count($assets_data)>0 && count($approval_request_assets)==0):?>btn-warning<?php elseif(count($assets_data)>0 && count($approval_request_assets)>0):?>btn-success<?php endif;?>'><?php if(count($approval_request_assets)==0):?>Send to CIPO<?php else:?>List Approved by CIPO<?php endif;?></a>
														<span style='display: none; position: absolute; right: -20px; top: 8px;' id="spinner-loader-ppa" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<a  href="http://claimcharts.info/order" target="_BLANK" class="btn btn-black btn-block">Order CC + PAR</a>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<a href="javascript:void(0)"  onclick="insertEmbedCode();" class="btn btn-black btn-block">Insert embedding CC + PAR Code</a>
													</div>
												</div>
												<div class="row mrg10T">
													<div id="embedCode" class='col-sm-12' style='display:none;'>															
														<?php echo form_open('opportunity/embedCode',array('class'=>'form-flat'));?>															
														<div class="row mrg10T">
															<label style="float:left;" class="control-label" for="emebedCCCode">CC embed Code</label>
															<input type="text" name="embed['cc_code']" value="<?php echo $acquisition['acquisition']->cc_embed_code?>" class="form-control input-string" id="emebedCCCode" style="float: left; width: 150px; margin-top: 4px;" />
														</div>
														<div class="row mrg10T">
															<label style="float:left;" class="control-label" for="emebedPARCode">PAR embed Code</label>
															<input type="text" name="embed['par_code']" value="<?php echo $acquisition['acquisition']->par_embed_code?>" class="form-control input-string" id="emebedPARCode" style="float: left; width: 150px; margin-top: 4px;" />
														</div>
														<div class="row mrg10T">
															<button type="submit" class="btn btn-primary">Save</button>
														</div>
														<input type="hidden" name="embed['lead_id']" value="<?php echo $lead_id;?>" id='embedLeadID'/>
														<?php echo form_close();?>
													</div>
												</div>
												<?php if($lead_report->execute_ppa>=2):?>
												<?php if(count($approval_request_assets)>0):?>
												<div class="mrg10T">
													<label class="control-label mrg10T"><b>List Of Invitees</b></label>
													<?php 
														foreach($market_sectors as $sector){
													?>
														<div class="checkbox">
															<label>
																<input type="checkbox" value="<?php echo $sector->id;?>" onclick="checkListContacts(jQuery(this))"/>&nbsp;
																<?php echo $sector->name;?>
															</label>
														</div>
													<?php
														}
													?>
												</div>
												<?php echo form_open('opportunity/invitees',array('class'=>"form-flat"));?>
												<table class="table table-bordered mrg10T" id="datatable-contacts">
													<thead>
														<tr>
															<th style='width:14%'><div class="text-center">x</div></th>
															<th style='width:36%'>Company Name</th>
															<th style='width:20%'>Status</th>
															<th style='width:30%'>Person in Charge</th>
														</tr>
													</thead>
													<tbody>
														<?php 
																if(count($contacts_in)>0):
															?>		
																<script>
																	__invitees = '<?php echo json_encode($contacts_in);?>';
																	__invitees = jQuery.parseJSON(__invitees);
																</script>	
															<?php		
																	foreach($contacts_in as $invitee){
																		$checked="";
																		if(count($invitees)>0){
																			foreach($invitees as $inv){
																				if($invitee['contact']->id==$inv->contact_id){
																					$checked="CHECKED='CHECKED'";
																				}
																			}
																		}
															?>
																	<tr>
																		<td style='max-width:5% !important;min-width:5% !important'><input type="checkbox" <?php echo $checked;?> name="invite[contact_id][]" value="<?php echo $invitee['contact']->id;?>"/></td>
																		<td style='width:55%'><a href="javascript://" onclick="editInviteesData(<?php echo $invitee['contact']->id;?>);"><?php echo $invitee['contact']->company_name;?></a></td>
																		<td style='width:20%'>Membership</td>
																		<td style='width:20%'><?php echo (!empty($invitee['contact']->person_in_charge))?$invitee['contact']->person_in_charge:$invitee['contact']->name;?></td>
																	</tr>
															<?php  } endif;?>
													</tbody>
												</table>
												<div class="mrg10T">
													<button class='btn btn-primary' type="submit">Save</button>
												</div>
												<input type="hidden" name="invite[lead_id]" id="inviteLeadId" value="<?php echo $lead_id;?>"/>
												<?php echo form_close();?>
												<?php echo form_open('opportunity/add_contact',array('class'=>"form-flat"));?>
													<div class="mrg10T">
														<label class="control-label mrg10T"><b>Create a new Contact</b></label>
													</div>
													<div class="row mrg10T">
														<div class="col-xs-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label">Company Name:</label>
																<input type="text" name="invitees[company_name]" id="inviteesCompanyName" class="form-control" required placeholder=""/>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-xs-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label">Person in Charge:</label>
																<input type="text" name="invitees[person_in_charge]" id="inviteesPersonInCharge" required class="form-control" placeholder=""/>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-xs-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label">Phone:</label>
																<input type="text" name="invitees[telephone]" id="inviteesTelephone" class="form-control" required placeholder=""/>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<div class="col-xs-12">
															<div class="form-group input-string-group nomr">
																<label class="control-label">E-mail:</label>
																<input type="email" name="invitees[email]" id="inviteesEmail" class="form-control" required placeholder=""/>
															</div>
														</div>
													</div>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Markets</label>
														<div class="col-sm-12 mrg5T">
															<select multiple required="required" class="multi-select" id="marketSector" name="market[sector][]">
																<?php 
																	if(count($market_sectors)>0){
																		foreach($market_sectors as $sector){
																?>
																			<option value="<?php echo $sector->id;?>"><?php echo $sector->name;?></option>
																<?php
																		}
																	}
																?>
															</select>
														</div>
													</div>
													<div class="mrg10T">
														<button class='btn btn-primary' type="submit">Save</button>
													</div>
													<input type="hidden" name="invitees[lead_id]" id="inviteesLeadId" value="<?php echo $lead_id;?>"/>
													<input type="hidden" name="invitees[id]" id="inviteesId" value="0"/>
												<?php echo form_close();?>
												<?php endif;?>												
												<?php endif;?>												
												<?php endif;?>
												<?php endif;?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container <?php if(count($approval_request_assets)>0):?> active <?php endif;?>">
												<?php if(count($approval_request_assets)>0):?>
												<div class="row">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-success">Execute a PPA</a>-->
														<button class="btn btn-block <?php if($lead_report->execute_ppa==0):?>btn-black<?php elseif($lead_report->execute_ppa==1):?> btn-warning <?php elseif($lead_report->execute_ppa>1):?>btn-success<?php endif;?>" <?php if($lead_report->execute_ppa==0):?>onclick="execute_ppa();<?php endif;?>">Execute a PPA</button>
													</div>
												</div>
												<?php 
													if($lead_report->execute_ppa>1):
												?>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary">Start DD</a>-->
														<button type="button" class="btn btn-block <?php if($lead_report->start_dd==0):?>btn-black<?php elseif($lead_report->start_dd==1):?> btn-warning <?php elseif($lead_report->start_dd==2):?>btn-success<?php endif;?>" <?php if($lead_report->start_dd==0):?>onclick="startDD();"<?php endif;?>>Start DD</button>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary">Due Diligence 2 (FileMaker)</a>-->
														<button  type="button"  class="btn btn-block <?php if(count($approval_request_file_maker)==0):?>btn-black <?php elseif(count($approval_request_file_maker)>0):?>btn-success<?php endif;?>" <?php if(count($approval_request_file_maker)==0):?>onclick="dueDilligenceFileMaker();"<?php endif;?>>Due Diligence 2 (File Marker)</button>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary">Start Market Research</a>-->
														<button class="btn btn-block <?php if(count($approval_request_market_research)==0):?>btn-black <?php elseif(count($approval_request_market_research)>0):?>btn-success <?php endif;?>" <?php if(count($approval_request_market_research)==0):?>onclick="startMarketResearch();"<?php endif;?>>Start Market Research</button>
													</div>
												</div>
												<?php endif;?>
												<?php endif;?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container <?php if(count($lead_report)>0 && $lead_report->execute_ppa>=2):?> active <?php endif;?>">
												<?php if(count($approval_request_assets)>0 && $lead_report->execute_ppa>=2):?>
												<div class="row">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-success">PPA Executed</a>-->
														<button class="btn btn-block <?php if(count($lead_report)>0 && $lead_report->execute_ppa==2):?> btn-black <?php elseif(count($lead_report)>0 && $lead_report->execute_ppa==3):?> btn-success <?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->execute_ppa==2):?>onclick="ppaExecuted();"<?php endif;?>>PPA Executed</button>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary">Order Damages Report by CIPO</a>-->
														<button class="btn btn-block <?php if(count($lead_report)>0 && $lead_report->order_damage==0):?>btn-black <?php elseif(count($lead_report)>0 && $lead_report->order_damage==1):?> btn-success <?php else:?> btn-warning <?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->order_damage==0):?>onclick="orderDamagesByCIPO();"<?php endif;?>>Order Damages Report by CIPO</button>
													</div>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<button class="btn btn-block <?php if(empty($acquisition['acquisition']->option_expiration_data)):?>btn-black <?php elseif($acquisition['acquisition']->option_expiration_data):?> btn-success<?php endif;?>" <?php if(empty($acquisition['acquisition']->option_expiration_data)):?>onclick="insertDataDocket();"<?php endif;?>>Insert Data for Docket</button>
													</div>
												</div>
												<div class="mrg10T hide" id="insert_data_docket">
													<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datepicker/datepicker.js"></script>
													<script type="text/javascript">
														/* Datepicker bootstrap */

														$(function() { "use strict";
															$('.bootstrap-datepicker').bsdatepicker({
																format: 'yyyy-mm-dd'
															});
														});

													</script>
												<?php 
													echo form_open('opportunity/docket_entry',array('class'=>"form-flat","enctype"=>"multipart/form-data"));
													$optionExpirationDate = "";
													$imageLeft ="";
													$imageMiddle ="";
													$imageRight ="";
													$sellerAskingPrice="";
													if(!empty($acquisition['acquisition']->option_expiration_data)){
														$optionExpirationDate = $acquisition['acquisition']->option_expiration_data;
													}
													if(!empty($acquisition['acquisition']->image_left)){
														$imageLeft = $acquisition['acquisition']->image_left;
													}
													if(!empty($acquisition['acquisition']->image_middle)){
														$imageMiddle = $acquisition['acquisition']->image_middle;
													}
													if(!empty($acquisition['acquisition']->image_right)){
														$imageRight = $acquisition['acquisition']->image_right;
													}
													if(!empty($acquisition['acquisition']->seller_asking_price)){
														$sellerAskingPrice = $acquisition['acquisition']->seller_asking_price;
													}
													?>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Option Expiration Date</label>
														<div class="col-sm-12">
															<input type="text" name="docket[option_expiration_data]" value="<?php echo $optionExpirationDate;?>" data-date-format="yyyy-mm-dd" id="dockeOptionExpirationDate" class="bootstrap-datepicker form-control input-string" required />
														</div>
													</div>
													<div class="mrg10T">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">Image Left</span>
								                                <span class="fileinput-exists">Change</span>
																<input type="file" name="docket[image_left]" id="docketImageLeft" required  />
																<?php if(!empty($imageLeft)):?>
																<img src="<?php echo $imageLeft?>"/>
																<?php endif;?>
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">Image Middle</span>
								                                <span class="fileinput-exists">Change</span>
								                                <input type="file" name="docket[image_middle]" id="docketImageMiddle" required />
																<?php if(!empty($imageMiddle)):?>
																<img src="<?php echo $imageMiddle?>"/>
																<?php endif;?>
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">Image Right</span>
								                                <span class="fileinput-exists">Change</span>
								                               <input type="file" name="docket[image_right]" id="docketImageRight" required  />
																<?php if(!empty($imageRight)):?>
																<img src="<?php echo $imageRight?>"/>
																<?php endif;?>
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Sellers Asking Price</label>
														<div class="col-sm-12">
															<input type="text" name="docket[seller_asking_price]" value="<?php echo $sellerAskingPrice;?>" id="docketSellerAskingPrice" class="form-control input-string" required />
														</div>
													</div>
													<div class="mrg10T">
														<button class='btn btn-primary' type="submit">Save</button>
													</div>
													<input type="hidden" name="docket[lead_id]" value="<?php echo $lead_id;?>"/>
												</form>
												</div>
												<div class="row mrg10T">
													<div class="col-xs-2"></div>
													<div class="col-xs-8">
														<!--<a href="#" class="btn btn-primary mrg10T">Upload Document by CIPO</a>-->
														<button class="btn btn-block <?php if(count($lead_report)>0 && $lead_report->other_doc==0):?>btn-black <?php elseif(count($lead_report)>0 && $lead_report->other_doc>0):?> btn-success<?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->other_doc==0):?>onclick="uploadDocumentByCIPO();"<?php endif;?>>Upload Document by CIPO</button>
													</div>
												</div>
												<?php endif;?>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane">
									1
								</div>
								<div class="tab-pane">
									3
								</div>
								<div class="tab-pane">
									4
								</div>
							</div>
						</div>
					</div>
					<div id="syndicateTab" class="tab-pane">
						Syndicate
					</div>
					<div id="sharingDocumentsTab" class="tab-pane">
						<div class="form-wizard">
							<ul>
								<li class="<?php if($active==1 || $active==0):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">1</label>
									</a>
								</li>
								<li class="<?php if($active==2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">2</label>
									</a>
								</li>
								<li class="<?php if($active>2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">3</label>
									</a>
								</li>
								<li class="<?php if($active>2 && isset($lead_report->eou_folder) && (int)$lead_report->eou_folder==2):?>active<?php endif;?>">
									<a>
										<label class="wizard-step">4</label>
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-xs-3">
											<div class="step-container">
												<div>NDA:</div>
												<div class="form-horizontal form-flat">
													<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>=2 && $lead_report->cipo_approved==2):?>
														<!--<div class="mrg10T"><label>Share NDA with Seller:</label></div>-->
														<style>
															/*div.dataTables_scrollHeadInner{width:100% !important; box-sizing: border-box; padding-left: 17px; }
															div.dataTables_scrollHeadInner>table.dataTable{width:100% !important}*/
															#datatable-contacts-sharing_info{display:none;}
														</style>
														<script>
															var ___table ;
															$(document).ready(function() {		
																___table = $('#datatable-contacts-sharing_tab').DataTable({								
																	"searching":true,
																	"autoWidth": true,
																	"paging": false,
																	"sScrollY": "200px",
																	"sScrollX": "100%",
																	"sScrollXInner": "100%",
																	"columnDefs": [
																		{ "width": "10%" },
																		{ "width": "20%" },
																		{ "width": "70%" },
																	]
																});
															});
														</script>
														
														<table class="table" class="table" id="datatable-contacts-sharing_tab">
															<thead>
																<tr>
																	<th><div class="text-center">x</div></th>
																	<th>Name</th>
																	<th>Company Name</th>
																</tr>
															</thead>
															<tbody>
																<?php 
																	if(count($lead_contacts)>0){
																		foreach($lead_contacts as $contact){
																			$selected="";
																			foreach($doc_shared as $doc){
																				if($doc->contact_id==$contact->id){
																					$selected='CHECKED="CHECKED"';
																				}
																			}
																?>
																<tr>
																	<td><input <?php echo $selected;?> type="checkbox" name="share[users][]" id="shareUsers"  value="<?php echo $contact->id?>"/></td><td> <?php echo $contact->name;?></td>
																	<td><?php echo $contact->company_name;?></td>
																</tr>
																<?php
																		}
																	}
																?>
															</tbody>
														</table>
														<!--<div class=''>
															<a href='javascript://' onclick="shareWithUsers();" class='btn btn-primary'>Go</a>
														</div>-->
													<?php endif;?>
												</div>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<div>PPA:</div>
												<table class="table table-bordered mrg10T">
													<thead>
														<tr>
															<th>Head 1</th>
															<th>Head 2</th>
															<th>Head 3</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td></td>
															<td></td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
    	</div>
    </div>
</div>
<?php echo $Layout->element('timeline');?>
</div>


<script>
	jQuery(function() {
		jQuery('.multi-select').multiSelect('refresh');
		$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');


		// Hide tab panes
		setTimeout(function() {
			$('#tempStyles').remove();
		}, 300);
	});

	// Datatable hack
	setInterval(function() {
		var $filterSearchCell = $('.dataTables_filter').parent();
		if(!$filterSearchCell.hasClass('is-hacked')) {
			$filterSearchCell.parent().addClass('mrg10T');
			$filterSearchCell.prev().remove();
			$filterSearchCell.removeClass('col-sm-6').addClass('col-sm-12');
			$filterSearchCell.find('label:not(".control-label")').css({ float: 'none', display: 'block' });
			$('.dataTables_filter input[type="search"]')
				.addClass('form-control')
				.addClass('input-string')
				.css({ marginLeft: '0', width: '40%' })
				.before('<label class="control-label" style="float:left;">Search:</label>');
			$filterSearchCell.addClass('is-hacked');

			// Remove paging
			$('#datatable-contacts-sharing').next().remove();
		}
	}, 500);
</script>