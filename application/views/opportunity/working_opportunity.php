<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<!-- jQueryUI Tabs -->

<!--<link rel="stylesheet" type="text/css" href="../../assets/widgets/tabs-ui/tabs.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs-ui/tabs.js"></script>
<script type="text/javascript">
    /* jQuery UI Tabs */

    $(function() { "use strict";
        $(".tabs").tabs();
    });

    $(function() { "use strict";
        $(".tabs-hover").tabs({
            event: "mouseover"
        });
    });
</script>

<!-- Boostrap Tabs -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/tabs/tabs.js"></script>

<!-- Tabdrop Responsive -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/tabs/tabs-responsive.js"></script>
<script type="text/javascript">
    /* Responsive tabs */
    $(function() { "use strict";
        $('.nav-responsive').tabdrop();
    });
</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/multi-select/multiselect.js"></script>
<script type="text/javascript">
    /* Multiselect inputs */

    $(function() { "use strict";
        $(".multi-select").multiSelect();
        $(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
    });
</script>
<?php 
	$active = 0;
	if(count($lead_stage)>0){
		$active = (int) $lead_stage->stage;
	}
	echo $active;
?>

<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">	
			<?php 
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
			<form class='form-inline form-flat'>
				<div class='form-group'>
					<label></label>
					<input type='text' class='form-control input-string' disabled value="<?php echo $opportunityType?>"/>
				</div>
				<div class='form-group'>
					<label></label>
					<input type='text' class='form-control input-string' disabled value="<?php echo $opportunityName?>"/>
				</div>
				<div class='form-group'>
					<label></label>
					<input type='text' class='form-control input-string' disabled value="<?php echo $sellerName?>"/>
				</div>
			</form>
			<ul class="list-group list-group-separator row list-group-icons">
				<li class="col-md-4 <?php if($active<=4):?>active<?php endif;?>">
					<a href="#tab-create-an-opportunity" data-toggle="tab" class="list-group-item">
						<i class="glyph-icon font-red icon-bullhorn"></i>
						Create an Opportunity
					</a>
				</li>
				<li class="col-md-4 <?php if($active>3):?>active<?php endif;?>">
					<a href="#tab-sign-ppa" data-toggle="tab" class="list-group-item">
						<i class="glyph-icon icon-dashboard"></i>
						Sign a PPA
					</a>
				</li>
				<li class="col-md-4">
					<a href="#tab-syndicate" data-toggle="tab" class="list-group-item">
						<i class="glyph-icon font-primary icon-camera"></i>
						Syndicate
					</a>
				</li>				
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade <?php if(($active==0 || $active==1) ||(  $active>=2 && $active<5)):?>active in<?php endif;?> " id="tab-create-an-opportunity">
					<div id="form-wizard-3" class="form-wizard">
						<ul>
							<li class="<?php if($active==1 || $active==0):?>active<?php endif;?>">
								<a href="#step-1" data-toggle="tab">
									<label class="wizard-step">1</label>
									<!--<span class="wizard-description">
										User details
										<small>Gather the user details</small>
									</span>-->
								</a>
							</li>
							<li class="<?php if($active==2):?>active<?php endif;?>">
								<a href="#step-2" data-toggle="tab">
									<label class="wizard-step">2</label>
									<!--<span class="wizard-description">
										Contact information
										<small>Confirm contact details</small>
									</span>-->
								</a>
							</li>
							<li class="<?php if($active>2):?>active<?php endif;?>">
								<a href="#step-3" data-toggle="tab">
									<label class="wizard-step">3</label>
								    <!--<span class="wizard-description">
										Business support
										<small>Establish business description</small>
								    </span>-->
								</a>
							</li>
							<li class="">
								<a href="#step-4" data-toggle="tab">
									<label class="wizard-step">4</label>
									<!--<span class="wizard-description">
										Final steps
										<small>Finish and send the email</small>
									</span>-->
								</a>
							</li>
						</ul>
						<div class="tab-content">							
							<div class="tab-pane <?php if($active==1 || $active==0):?>active<?php endif;?>" id="step-1">
								<?php echo form_open('opportunity/acquisition',array('class'=>'form-horizontal form-flat mrg10T','role'=>'form'));?>
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
									<div class="col-xs-4">
										<div class="row">
											<label for="acquisitionMarketSector" class="col-sm-12 control-label">Market Sector</label>
											<div class="col-sm-12 mrg5T">
												<select required name="acquisition[market_sector]" class="form-control custom-select" id="acquisitionMarketSector">
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
										<div class="row mrg10T">
											<label for="acquisitionSellerUpFront" class="col-sm-12 control-label">Seller Upfront Price ($M)</label>
											<div class="col-sm-12">
												<?php echo form_input(array('name'=>'acquisition[seller_upfront]','id'=>'acquisitionSellerUpFront','value'=>$sellerUpfront,'placeholder'=>'','class'=>'form-control input-string'));?>
											</div>
										</div>
										<div class="row mrg10T">
											<label for="numberOfPatent" class="col-sm-12 control-label">Number of Patent</label>
											<div class="col-sm-12">
												<?php echo form_input(array('name'=>'acquisition[no_of_patent]','id'=>'numberOfPatent','placeholder'=>'','value'=>$noOfPatent,'class'=>'form-control input-string'));?>
											</div>
										</div>
									</div>
									<div class="col-xs-8">
										<div class="row">
											<label for="acquisitionTechnologies" class="col-sm-12 control-label">Technologies</label>
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
									</div>
								</div>
								<div class="row mrg10T">
									<label class="col-sm-2"></label>
									<div class="col-sm-10">
										<button class="btn btn-primary float-right" type="submit">Next</button>
									</div>
								</div>
									<input type="hidden" name="acquisition[lead_id]" value="<?php echo $lead_id;?>"/>
									
								<?php echo form_close();?>
							</div>
							<div class="tab-pane <?php if($active==2):?>active<?php endif;?>" id="step-2">
								
								<?php if($active>1):?>
								<script>
									_token = '<?php echo $lead_id;?>';
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
														if(jQuery("#table_list>tbody>tr").length==1){
															jQuery("#table_list>tbody").append("<tr><td style='border:0px;'><button class='btn btn-info' id='cipoApproval' onclick='cipoApproval();'>CIPO Approval</button><span style='display:none;float:none;' id='spinner-loader-nda-cipo' class=glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A title='Please wait....' data-original-title='icon-spin-6'></span></td></tr>");
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
														window.location = window.location.href;
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
														window.location = window.location.href;
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
														window.location = window.location.href;
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
														window.location = window.location.href;
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
								<div class='col-lg-12'>
								<div id="table_list">
									<?php if(isset($lead_level) && count($lead_level)==0 || $lead_level->level>=1):?>
									<div>
										<div>
											<button onclick="sendNDA();" class='btn btn-black'>Draft an NDA</button>
											<span style='display:none;float:none;' id="spinner-loader-nda" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
										</div>
									</div>
									<?php endif;?>
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
									<div class="mrg10T">
										<div>
											<button id="cipoApproval" onclick="cipoApproval()" class='btn <?php echo $color;?>'>CIPO Approval</button>
											<span style='display:none;float:none;' id="spinner-loader-nda-cipo" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>=2 && $lead_report->cipo_approved==2):?>
									<?php 
									/*if(count($lead_contacts)>0){*/
									?>
									<div class="mrg10T">
										<div>
											<div class="row">
												<label class="col-sm-12 control-label">Share NDA with Seller:</label>
												<div class="col-sm-12">
													<!--<select name="share[users]" id="shareUsers" multiple data-placeholder="Click to see available options..." class="chosen-select">
														<optgroup label="Contacts List">
															<?php 
																if(count($lead_contacts)>0){
																	foreach($lead_contacts as $contact){
																		$selected="";
																		foreach($doc_shared as $doc){
																			if($doc->contact_id==$contact->id){
																				$selected='SELECTED="SELECTED"';
																			}
																		}
															?>
																	<option <?php echo $selected;?> value="<?php echo $contact->id?>"><?php echo $contact->name;?></option>
															<?php
																	}
																}
															?>
														</optgroup>															
													</select>-->
													<script>
														var ___table ;
														$(document).ready(function() {		
															___table = $('#datatable-contacts-sharing').DataTable({								
																"searching":false,
																"autoWidth": false,
																"scrollCollapse": true,
																"columnDefs": [
																	{ "width": "5%" },
																	{ "width": "20%" },
																	{ "width": "75%" },
																]
															});
														});
													</script>
													<table class="table" class="table" id="datatable-contacts-sharing">
														<thead>
															<tr>
																<th>#</th>
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
												<div class='col-sm-12'>
													<a href='javascript://' onclick="shareWithUsers();" class='btn btn-primary'>Go</a>
												</div>
											</div>
										</div>
									</div>
									<?php /*} else {
									?>
										<tr><td style='border:0px;'><button id="sharedDocs" onclick="sharedDocs()" class='btn btn-primary'>Shared DOCS</button>
										<span style='display:none;float:none;' id="spinner-loader-nda-shared" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
										</td></tr>
									<?php												
									}*/ ?>
									<?php endif;?>
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
									<div class="row mrg10T">
										<div>
											<button class='btn <?php echo $color;?>' <?php if((int)$lead_report->executed_nda<1):?>onclick="executeNDA();" <?php endif;?>>Execute NDA</button>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($lead_level) && count($lead_level)>0 && $lead_level->level>4 && $lead_report->executed_nda==2):?>
									<div>
										<div style='border:0px;'>
											<!--
											<button class='btn <?php if((int)$lead_report->nda_execute==0):?>btn-primary <?php elseif((int)$lead_report->nda_execute==2):?> btn-success<?php endif;?>' <?php if((int)$lead_report->nda_execute==0):?>onclick="ndaExecuted();<?php endif;?>">NDA Executed</button>
											-->
											<button class='btn <?php if((int)$lead_report->nda_execute==0):?>btn-primary <?php elseif((int)$lead_report->nda_execute==2):?> btn-success<?php endif;?>' onclick="javascript:void(0)" type="button">NDA Executed</button>
										</div>
									</div>
									<?php endif;?>
								</div>
								</div>
								</div>
								<?php endif;?>
							</div>
							<div class="tab-pane <?php if($active>2):?>active<?php endif;?>" id="step-3">
								
								<?php 
									   
								if($active>2):?>
								
								<div>
									<button class='btn <?php if($lead_report->eou_folder=='0'):?>btn-primary<?php elseif($lead_report->eou_folder=='2'):?>btn-success <?php endif;?>' <?php if($lead_report->eou_folder=='0'):?>onclick="eouConfirmation();" <?php endif;?>>Seller's EOU in Folder</button>
									<button type="button" class='btn btn-primary' onclick="sendEouData();">Save</button>
								</div>
								<?php 
									if($lead_report->eou_folder=='2'){
								?>
									<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.css">
									<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.css">
									<!--<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery.handsontable.removeRow.css">
									<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery.handsontable.controller.css">-->
									<script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
									<script src="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.js"></script>
									<script src="<?php echo $Layout->baseUrl?>public/assets/lib/bootstrap-typeahead.js"></script>
									<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jquery.autoresize.js"></script>
									<script src="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery-ui-1.8.23.draggable.min.js"></script>
									<!--<script src="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery.handsontable.controller.js"></script>-->
									<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
									<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
									<!--<script src="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery.handsontable.removeRow.js"></script>-->
									<style>
									/*.wtSpreader{left:0px !important;}*/
									</style>
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
												colWidths: [200,200, 100],
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
									<div class="example-box-wrapper">
										<div class="form-horizontal">
											<div class="row mrg10T">
												<div id="eou_data" class='col-sm-6 dataTable'>
													
												</div>
											</div>
											<!-- <div style="clear:both; padding-top: 33px;">
												<div class="row">
													<div class="col-sm-12">
													</div>
												</div>
											</div> -->
										</div>
									</div>
								<?php
									}
								?>
								<?php endif;?>
							</div>
							<div class="tab-pane" id="step-4">
								<?php if($active>2):?>
									<div class="example-box-wrapper">
										<?php 
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
													colWidths: [200,200, 100,100],
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
													colWidths: [200,200, 100,100],
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
										?>
										<div class="form-horizontal">
										<div class="row">
											<div class="col-sm-12">
												<?php 
													$sepNumber ="";
													if((int)$acquisition['acquisition']->no_of_sep>0){
														$sepNumber = $acquisition['acquisition']->no_of_sep;
													}
													$potentialLicensees ="";
													if((int)$acquisition['acquisition']->no_of_potential_licensees>0){
														$potentialLicensees = $acquisition['acquisition']->no_of_potential_licensees;
													}
												?>
												<label for="sepNumbers" class="control-label" style="float:left;">How many SEP:</label>
												<input type="text" name="sep[numbers]" id="sepNumbers" placeholder="" class="form-control input-string" value="<?php echo $sepNumber;?>" style="float: left; width: auto; margin-top: 4px;" />
											</div>
										</div>
										<div class="row mrg10T">
											<div id="sepHoldData" style='float:left; width:600px;' class='col-sm-6 dataTable'>													
											</div>
										</div>											
										<div class="row mrg10T">
											<div class="col-sm-12">
												<label for="sepNumbers" class="control-label" style="float:left;">Number of Potential Licensees:</label>
												<input type="text" name="sep[potential_licensees]" id="sepPotentialLicensees" placeholder="" value="<?php echo $potentialLicensees;?>" class="form-control input-string" style="float: left; width: auto; margin-top: 4px;" />
											</div>
										</div>
										<div class="row mrg10T">
											<div id="potentialHoldData" style='float:left; width:500px;' class='col-sm-6 dataTable'>													
											</div>
										</div>
										<div class="row mrg10T">
											<div class="col-sm-3">
												<button type="submit" class="btn btn-primary">Save</button>
											</div>
										</div>
										<input type="hidden" name="sep[sep_another_data]" id="sepAnotherData"/>
										<input type="hidden" name="sep[sep_data]" id="sepData"/>
										<input type="hidden" name="sep[lead_id]" value="<?php echo $lead_id;?>" id="sepLeadID"/>
										</div>
										<?php echo form_close();?>
										<?php } ?>
									</div>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade <?php if($active>3):?> active in<?php endif;?>" id="tab-sign-ppa">
					<div class="example-box-wrapper">
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
									"columnDefs": [
										{ "width": "5%" },
										{ "width": "55%" },
										{ "width": "20%" },
										{ "width": "20%" },
									]
								});
							});
						</script>
						<div id="form-wizard-4" class="form-wizard">
							<ul>
								<li class="<?php if(count($approval_request_assets)==0):?>active<?php endif;?>">
									<a href="#step-11" data-toggle="tab">
										<label class="wizard-step">1</label>								 
									</a>
								</li>
								<li class="<?php if(count($approval_request_assets)>0):?> active <?php endif;?>">
									<a href="#step-21" data-toggle="tab">
										<label class="wizard-step">2</label>								 
									</a>
								</li>
								<li>
									<a href="#step-31" data-toggle="tab">
										<label class="wizard-step">3</label>								 
									</a>
								</li>
								<li>
									<a href="#step-41" data-toggle="tab">
										<label class="wizard-step">4</label>								  
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane <?php if((int)$active==5 && count($approval_request_assets)==0):?>active<?php endif;?>" id="step-11">
									<?php if((int)$active>=5):?>
									<div class="example-box-wrapper">
										<div class="row">
											<div class="col-xs-4">
												<div class="row">
													<div class="col-sm-12">
														<button type='button' <?php if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==0):?>onclick="draft_a_ppa();"<?php endif;?> class='btn <?php if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==0):?>btn-black<?php else: ?>btn-success<?php endif;?>'>Draft a PPA</button>
														<span style='display:none;float:none;' id="spinner-loader-ppa" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
														<a class='btn btn-primary' href="http://www.synpat.com/store/<?php echo $acquisition['acquisition']->store_name;?>" target="_BLANK"><?php echo $acquisition['acquisition']->store_name;?></a>
													</div>
												</div>
												
												<?php 
													if(count($lead_report)>0 && (int)$lead_report->draft_a_ppa==2 && $acquisition['acquisition']->store_name!=""):
												?>
												<!-- <div class="row mrg10T">
													<div class="col-sm-12">
													</div>
												</div> -->
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
														$container3.handsontable({																startRows: 1,																startCols: 1,																colHeaders: ["List Of Assets"],																minSpareCols: 0,																minSpareRows: 1,																colWidths: [300],																manualColumnResize: true,																contextMenu: false,																columns: [																			{},																																					]
															
														});
														jQuery("#listOfAssets").data('handsontable').loadData(__data3);
													});		
													function approvalList(){
														if(_token==0 || _token==""){
															alert("Please try after sometime.");
														} else {
															var handsontable = jQuery("#listOfAssets").handsontable("getData");
															_assetsData = JSON.stringify(handsontable);
															jQuery.ajax({
																type:'POST',
																url:'<?php echo $Layout->baseUrl?>opportunity/approvalList',
																data:{token:_token,asset_data:_assetsData},
																cache:false,
																success:function(res){
																	_data  = jQuery.parseJSON(res);
																	if(_data.url!=""){
																		window.open(_data.url,"_BLANK");
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
																		window.location = window.location.href;
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
												<div class="row mrg10T">
													<div class="col-xs-12">
														<div id="listOfAssets" style='float:left; min-height:56px; height: auto; width:100%;' class='mrg10R dataTable'>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xs-8">
												<div class="row">
													<div class="col-xs-12">
														<a href='javascript://' <?php if(count($assets_data)==0):?>onclick="approvalList();"<?php  endif;?> class='btn <?php if(count($assets_data)==0):?>btn-black<?php elseif(count($assets_data)>0 && count($approval_request_assets)==0):?>btn-warning<?php elseif(count($assets_data)>0 && count($approval_request_assets)>0):?>btn-primary<?php endif;?>'><?php if(count($approval_request_assets)==0):?>Send to CIPO<?php else:?>List Approved by CIPO<?php endif;?></a>
														<a href="http://claimcharts.info/order" target="_BLANK" class='btn btn-black'>Order CC + PAR</a>
														<a href="javascript:void(0)" target="_BLANK" onclick="insertEmbedCode();" class='btn btn-black'>Insert embedding CC + PAR Code</a>
													</div>
												</div>
												<div class="row mrg10T">
													<div id="embedCode" class='col-sm-12' style='display:none;'>															
														<?php echo form_open('opportunity/embedCode',array('class'=>'form-horizontal form-flat'));?>															
														<div class="row">
															<div class="col-xs-5">
																<label style="float:left;" class="control-label" for="emebedCCCode">CC embed Code</label>
																<input type="text" name="embed['cc_code']" value="<?php echo $acquisition['acquisition']->cc_embed_code?>" class="form-control input-string" id="emebedCCCode" style="float: left; width: 150px; margin-top: 4px;" />
															</div>
															<div class="col-xs-5">
																<label style="float:left;" class="control-label" for="emebedPARCode">PAR embed Code</label>
																<input type="text" name="embed['par_code']" value="<?php echo $acquisition['acquisition']->par_embed_code?>" class="form-control input-string" id="emebedPARCode" style="float: left; width: 150px; margin-top: 4px;" />
															</div>
															<div class="col-xs-2">
																<button type="submit" class="btn btn-primary">Save</button>
															</div>
														</div>
														<div class="row mrg10T">
															<div class="col-sm-12">
															</div>
														</div>
														<input type="hidden" name="embed['lead_id']" value="<?php echo $lead_id;?>" id='embedLeadID'/>
														<?php echo form_close();?>
													</div>
												</div>
											</div>
										</div>
										<?php endif;?>
									</div>
									<?php endif;?>
								</div>
								<div class="tab-pane <?php if(count($approval_request_assets)>0):?> active <?php endif;?>" id="step-21">
									<div class="example-box-wrapper">
									<?php if(count($approval_request_assets)>0):?>
									<div class="row">
										<div class="col-sm-12">
										<button class="btn <?php if($lead_report->execute_ppa==0):?>btn-black<?php elseif($lead_report->execute_ppa==1):?> btn-warning <?php elseif($lead_report->execute_ppa>1):?>btn-success<?php endif;?>" <?php if($lead_report->execute_ppa==0):?>onclick="execute_ppa();<?php endif;?>">Execute a PPA</button>
										<?php 
											if($lead_report->execute_ppa>1):
										?>
										<button class="btn <?php if($lead_report->start_dd==0):?>btn-black<?php elseif($lead_report->start_dd==1):?> btn-warning <?php elseif($lead_report->start_dd==2):?>btn-success<?php endif;?>" onclick="startDD();">Start DD</button>
										<button class="btn <?php if(count($approval_request_file_maker)==0):?>btn-black <?php elseif(count($approval_request_file_maker)>0):?>btn-success<?php endif;?>" <?php if(count($approval_request_file_maker)==0):?>onclick="dueDilligenceFileMaker();"<?php endif;?>>Due Diligence 2 (File Marker)</button>
										<button class="btn <?php if(count($approval_request_market_research)==0):?>btn-black <?php elseif(count($approval_request_market_research)>0):?>btn-success <?php endif;?>" <?php if(count($approval_request_market_research)==0):?>onclick="startMarketResearch();"<?php endif;?>>Start Market Research</button>
										<?php endif;?>
									</div> 
									<?php endif;?>
									</div>
									<div class="example-box-wrapper mrg10T" id="form_invitees" style='<?php if($lead_report->execute_ppa<=1):?>display:none;<?php endif;?>'>
									<?php if(count($approval_request_assets)>0):?>
										<div class="">
											<div class="row">
												<div class="col-sm-12">
													<ul class="list-group list-group-separator" id="marketSector">
													<?php 
														foreach($market_sectors as $sector){
													?>
														<li> <input type="checkbox" value="<?php echo $sector->id;?>" onclick="checkListContacts(jQuery(this))"/><?php echo $sector->name;?></li>
													<?php
														}
													?>
													</ul>
												</div>
												<div class="col-sm-12">
													<?php echo form_open('opportunity/invitees',array('class'=>"form-inline bordered-row"));?>
													<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-contacts">
														<thead>
															<tr>
																<th style='max-width:5% !important;min-width:5% !important'>#</th>
																<th style='width:55%'>Company Name</th>
																<th style='width:20%'>Status</th>
																<th style='width:20%'>Person in Charge</th>
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
													<div class="form-group col-sm-12" style='margin-top:5px;'>
														<button class='btn btn-primary' type="submit">Save</button>
													</div>
													<input type="hidden" name="invite[lead_id]" id="inviteLeadId" value="<?php echo $lead_id;?>"/>
													<?php echo form_close();?>
												</div>
											</div>
											<div class="row">
												<?php echo form_open('opportunity/add_contact',array('class'=>"form-inline form-flat"));?>
												<div class="col-sm-4">
													<div class="row">
														<label class="col-xs-12 control-label">Company Name:</label>
														<div class="col-sm-12">
															 <input type="text" name="invitees[company_name]" id="inviteesCompanyName" class="form-control input-string" required placeholder=""/>
														</div>
													</div>
													<div class="row mrg10T">
														<label class="col-xs-12 control-label">Person In Charge:</label>
														<div class="col-sm-12">
															<input type="text" name="invitees[person_in_charge]" id="inviteesPersonInCharge" required class="form-control input-string" placeholder=""/>
														</div>
													</div>
													<div class="row mrg10T">
														<label class="col-xs-12 control-label">Telephone:</label>
														<div class="col-sm-12">
															<input type="text" name="invitees[telephone]" id="inviteesTelephone" class="form-control input-string" required placeholder=""/>
														</div>
													</div>
													<div class="row mrg10T">
														<label class="col-xs-12 control-label">Email:</label>
														<div class="col-sm-12">
															<input type="email" name="invitees[email]" id="inviteesEmail" class="form-control input-string" required placeholder=""/>
														</div>
													</div>
													<div class="mrg10T">
														<button class='btn btn-primary' type="submit">Save</button>
													</div>
												</div>
												<div class="col-sm-8">
													<label class="control-label">Sectors:</label>
													<div class="mrg5T">
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
												<input type="hidden" name="invitees[lead_id]" id="inviteesLeadId" value="<?php echo $lead_id;?>"/>
												<input type="hidden" name="invitees[id]" id="inviteesId" value="0"/>
												<?php echo form_close();?>
											</div>
										</div>
									<?php endif;?>
									</div>
								</div>
								<div class="tab-pane" id="step-31">
									<?php if(count($approval_request_assets)>0):?>
									<div>
										<button class="btn <?php if(count($lead_report)>0 && $lead_report->execute_ppa==2):?> btn-black <?php elseif(count($lead_report)>0 && $lead_report->execute_ppa==3):?> btn-success <?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->execute_ppa==2):?>onclick="ppaExecuted();"<?php endif;?>>PPA Executed</button>
										<button class="btn <?php if(count($lead_report)>0 && $lead_report->order_damage==0):?>btn-black <?php elseif(count($lead_report)>0 && $lead_report->order_damage==1):?> btn-success <?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->order_damage==0):?>onclick="orderDamagesByCIPO();"<?php endif;?>>Order Damages Report by CIPO</button>
										<button class="btn <?php if(empty($acquisition['acquisition']->option_expiration_data)):?>btn-black <?php elseif($acquisition['acquisition']->option_expiration_data):?> btn-success<?php endif;?>" <?php if(empty($acquisition['acquisition']->option_expiration_data)):?>onclick="insertDataDocket();"<?php endif;?>>Insert Data for Docket</button>
										<button class="btn <?php if(count($lead_report)>0 && $lead_report->other_doc==0):?>btn-black <?php elseif(count($lead_report)>0 && $lead_report->other_doc>0):?> btn-success<?php endif;?>" <?php if(count($lead_report)>0 && $lead_report->other_doc==0):?>onclick="uploadDocumentByCIPO();"<?php endif;?>>Upload Document by CIPO</button>
									</div>
									<div class="example-box-wrapper hide" id="insert_data_docket">
										<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datepicker/datepicker.js"></script>
										<script type="text/javascript">
											/* Datepicker bootstrap */

											$(function() { "use strict";
												$('.bootstrap-datepicker').bsdatepicker({
													format: 'yyyy-mm-dd'
												});
											});

										</script>
										<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datepicker-ui/datepicker.js"></script>
										<?php echo form_open('opportunity/docket_entry',array('class'=>"form-horizontal bordered-row","enctype"=>"multipart/form-data"));
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
										<div class="col-sm-4">
											<div class="form-group">
												<div class="col-sm-12" style='margin-top:5px;'>
													<label class="col-sm-4">Option Expiration Date</label>
													<input type="text" name="docket[option_expiration_data]" value="<?php echo $optionExpirationDate;?>" data-date-format="yyyy-mm-dd" id="dockeOptionExpirationDate" class="bootstrap-datepicker form-control" required />
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-12" style='margin-top:5px;'>
												<label class="col-sm-4">Image Left</label>
													<input type="file" name="docket[image_left]" id="docketImageLeft" required class="form-control" />
													<?php if(!empty($imageLeft)):?>
													<img src="<?php echo $imageLeft?>"/>
													<?php endif;?>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-12" style='margin-top:5px;'>
												<label class="col-sm-4">Image Middle</label>
													<input type="file" name="docket[image_middle]" id="docketImageMiddle" required class="form-control" />
													<?php if(!empty($imageMiddle)):?>
													<img src="<?php echo $imageMiddle?>"/>
													<?php endif;?>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-12" style='margin-top:5px;'>
												<label class="col-sm-4">Image Right</label>
													<input type="file" name="docket[image_right]" id="docketImageRight" required class="form-control" />
													<?php if(!empty($imageRight)):?>
													<img src="<?php echo $imageRight?>"/>
													<?php endif;?>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-12" style='margin-top:5px;'>
												<label class="col-sm-4">Seller Asking Price</label>
													<input type="text" name="docket[seller_asking_price]" value="<?php echo $sellerAskingPrice;?>" id="docketSellerAskingPrice" class="form-control" required />
												</div>
											</div>
											<div class="form-group col-sm-12" style='margin-top:5px;'>
												<button class='btn btn-primary' type="submit">Save</button>
											</div>
										</div>
										<input type="hidden" name="docket[lead_id]" value="<?php echo $lead_id;?>"/>
										<?php echo form_close();?>
									</div>
									<?php endif;?>
								</div>
								<div class="tab-pane" id="step-41">
									Lorem ipsum dolor sic amet dixit tu.
								</div>
							</div>
						</div>
					</div>
				</div>				
				<div class="tab-pane fade" id="tab-syndicate">
					<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress,</p>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>