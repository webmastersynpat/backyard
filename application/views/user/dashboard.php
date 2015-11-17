<style>table.show{display:table!important}tr.aggregate{display:none}.drop-hover{border:1px dashed #ff0000!important}#new_lead_drop.drop-hover{box-shadow:0 0 1px #f00 inset}.form-horizontal .control-label{text-align:left}.overflow-link{overflow:hidden;text-decoration:none;text-overflow:ellipsis;white-space:nowrap;width:106px}tr.salesFDroppable.ui-droppable.drop-hover{border:0}tr.salesFDroppable.ui-droppable.drop-hover td{background:#ff0000!important;color:#fff}tr.salesFDroppable.ui-droppable.drop-hover td a{color:#fff}tr.grey td{background:#d1c8c8}table.dataTable thead th.sorting:after,table.dataTable thead th.sorting_asc:after,table.dataTable thead th.sorting_desc:after{display:none!important}#all_type_list_wrapper .DTFC_LeftHeadWrapper{top:-1px!important}#all_type_list_wrapper .dataTable tbody tr{height:auto!important}#all_type_list_wrapper .DTFC_LeftBodyLiner{overflow-x:hidden;width:211px!important}#all_type_list_wrapper .DTFC_LeftBodyLiner table{margin-bottom:1px!important}#all_type_list_wrapper .DTFC_LeftBodyLiner tbody tr label{display:block}#all_type_list_wrapper .DTFC_LeftBodyLiner tbody tr a{display:block;height:18px!important;line-height:1.2;overflow:hidden;text-overflow:ellipsis;width:200px;white-space:nowrap}.sales-activity-notes{max-height:60px;max-height:55px;max-width:308px;overflow:hidden}.sales-activity-notes.is-open{max-height:10000px;overflow:visible}.sales-activity-notes-content{overflow-x:auto}.sales-activity-notes-icon{display:block;font-size:18px;line-height:18px;margin-top:5px;text-align:center}.sales-activity-notes-icon .icon-angle-down{display:inline-block}.sales-activity-notes-icon .icon-angle-up{display:none}.sales-activity-notes-icon.is-open .icon-angle-down{display:none}.sales-activity-notes-icon.is-open .icon-angle-up{display:inline-block}.sales-activity-checkbox{margin-right:4px;position:relative;top:2px}.sales-activity-icon{font-size:14px;margin-right:-3px}#all_type_list tbody .drop-hover td{background-color:#d9534f!important}</style>
<script>jQuery(document).ready(function(){<?php if($this->session->userdata['initialise_email']=='0'):?>intialiseAfter();<?php endif;?>});function salesActivityNotesIconClick(b){var a=b.prev();a.toggleClass("is-open");b.toggleClass("is-open");return false}function checkSalesActivityNotesIcon(){$(".sales-activity-notes-icon").each(function(c,e){var b=$(e),a=b.prev();if(a.length){var d=a.find(".sales-activity-notes-content");if(d.length){if(d.outerHeight()<=60){b.hide()}else{b.show()}}}})}setInterval(checkSalesActivityNotesIcon,300);
function openAllCompanies(){
	_ma = jQuery("#activityMainType").val();
	_container = "";
	if(_ma=="1"){
		_container ='activityTable';
	} else {
		_container ='aquisitionTable';
	}
	jQuery("#"+_container).find('tbody').find('tr.master').each(function(){
		jQuery(this).next().toggle();
	});
}
</script>
<div class="row"><div id="dashboard_charts" class="col-md-12 col-sm-12 col-xs-12"></div>
<?php echo $Layout->element('task');?>
<?php 
	$class=" col-md-8 col-sm-8 col-xs-8";
	if((int)$this->session->userdata['type']!=9){
		if(!in_array(7,$this->session->userdata['modules_assign']) && !in_array(6,$this->session->userdata['modules_assign'])){
			$class=" col-md-12 col-sm-12 col-xs-12";
		} else if(!in_array(7,$this->session->userdata['modules_assign'])){
			$class=" col-md-10 col-sm-10 col-xs-10";
		} else if(!in_array(6,$this->session->userdata['modules_assign'])){
			$class=" col-md-10 col-sm-10 col-xs-10";
		}
	}
?>
<div class="<?php echo $class;?>" id="contentPart">
<script>
	jQuery(window).load(function(){
		setTimeout(function(){			
			<?php 
				if(session_id() == '') {
					session_start();
				}
				$ses = 0;
				if(isset($_SESSION['guess_login']) && $_SESSION['guess_login']!=""){
					$ses = $_SESSION['guess_login'];
				}
				if($ses=="0"):
				if(!empty($auth_url)){
			?>				
				jQuery.ajax({
					url:__baseUrl+'dashboard/sendRequestURL',
					type:'POST',
					data:{t:'<?php echo base64_encode('synPatMarket')?>'},
					cache:false,
					success:function(data){
						_data = jQuery.parseJSON(data);
						if(_data.length>0){
							window.location = '<?php echo $auth_url;?>';
						}
					}
				});
			<?php
				} else {
			?>
				
				jQuery("#myEmailsRetrieve").slideDown(function() {
					jQuery.cookie('show_default_email', true);
					windowResize();
				});
				if(jQuery("#myEmailsRetrieve").find('div').length==0){
					retrieveMyEmail();
				} else {
					setInterval(runRetrieveNew,300000);
				}
				
			<?php
				}
				else:
			?>
				jQuery("#myEmailsRetrieve").slideDown(function() {
					jQuery.cookie('show_default_email', true);
					windowResize();
				});
				if(jQuery("#myEmailsRetrieve").find('div').length==0){
					retrieveMyEmail();
				} else {
					setInterval(runRetrieveNew,300000);
				}
			<?php 
				endif;
			?>
		},1000);
	});
</script>

<div class="row">
    <div class="col-md-12">
        <div id="dashboard-page" class="dashboard-box  bg-white content-box"> 
				<?php 
					if(isset($_SESSION['guess_login']) && $_SESSION['guess_login']=="0"):
					if(!empty($auth_url)){
				?>
				<script>
					function sendRequestURL(){
						jQuery.ajax({
							url:'<?php echo $Layout->baseUrl;?>dashboard/sendRequestURL',
							type:'POST',
							data:{t:'<?php echo base64_encode('synPatMarket')?>'},
							cache:false,
							success:function(data){
								_data = jQuery.parseJSON(data);
								if(_data.length>0){
									window.location = '<?php echo $auth_url;?>';
								}
							}
						});
					}
				</script>
					<a href="javascript:void(0);" onclick="sendRequestURL()"><img width="200" src="https://developers.google.com/accounts/images/sign-in-with-google.png"/></a>
				<?php 
					}
					endif;
				?>

				<?php /*if(count($emails)>0){ */?>
				<div class="col-md-12" id="dashboard_message_data">
					
				</div>
				<?php /* } */?>

				<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="newAggregateRefrencedApplicant">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
								<h4 id="" class="modal-title">Referenced Applicant</h4>
							</div>
							<div class="modal-body" onselectstart="return true">
								<table class="aggregate_data table"></table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default btn-mwidth" type="button"  onclick="referencedCheckCancel()">Clear Selection</button>
								<button class="btn btn-default btn-mwidth" type="button"  onclick="referencedSelectAll()">Select All</button>
								<button type="button" onclick="referencedCheckApply()" id="leadBtnSave" class="btn btn-primary btn-mwidth pull-right">Remove Unselected</button>
							</div>
						</div>
					</div>
				</div>

				<?php 
					$openCreateLead = true;
					if((int)$this->session->userdata['type']!=9){
						if(!in_array(17,$this->session->userdata['modules_assign'])){
							$openCreateLead = false;
						}
					}
					if($openCreateLead===true):
				?>

				<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="newLeadFormElement" style='left:0px;'>
					<div class="modal-dialog">
						<div class="modal-content">
								<div class="modal-header">
								<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
								<h4 id="" class="modal-title">New Lead</h4>
							</div>
							<div class="modal-body">
								<div class="form-horizontal form-flat">
									<div class="row">
										<div class="col-xs-12">
											<div class="form-group input-string-group">
												<label for="marketProspectsName" class="control-label" style="float:left;"><strong>Name of Lead:</strong></label>
												<?php echo form_input(array('name'=>'popup[lead_name]','required'=>'required','id'=>'popupLeadname','placeholder'=>'','class'=>'form-control input-string', 'style'=>'float: left; margin-left:5px;width: 66.6666%;'));?>
											</div>
										</div>	
										<div class="col-xs-12  mrg5T">
											<div class="form-group input-string-group select-string-group">
												<label for="marketProspectsName" class="control-label" style="float:left; margin-top:2px;"><strong>Type of Lead:</strong></label>
												<select class="form-control" name="popup[type]" id="popupType">
													<option value="">Select Lead Type</option>
													<option value="Litigation">From Litigation</option>
													<option value="Market">From Market</option>
													<option value="General">From Proactive General</option>
													<option value="SEP">From Proactive SEP</option>
													<option value="NON">Non Acquisition</option>
													<?php 
														if((int)$this->session->userdata['type']==9):
													?>
													<option value="INT">Internal</option>
													<?php endif;?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>
								<button type="button" onclick="getNewDataFromPopup()" id="leadBtnSave" class="btn btn-primary btn-mwidth pull-right">Save</button>
								<div id="loader_new_lead" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>
							</div>				
						</div>
					</div>
				</div>	
				<?php endif;?>
				<?php
					$openPatentTable = true;
					if((int)$this->session->userdata['type']!=9){
						if(!in_array(15,$this->session->userdata['modules_assign'])){
							$openPatentTable = false;
						}
					}
					if($openPatentTable===true):
				?>
				<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="create_scheduleCall"> 
					<div class="modal-dialog">
						<div class="modal-content">
							<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
								<div class="modal-header">
								<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
								<h4 id="" class="modal-title">Embed Schedule code</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group input-string-group nomr">
											<label for="spreadsheet" class="control-label">Embed Code:</label>
											<textarea type="text" class="form-control is-big" placeholder="" id="embed_code" value="" name="embed_code" required="required"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>
								<div class="loading-spinner" id="loading_spinner_schedulecall" style="display:none;">
									<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
								</div>
								<button  class="btn btn-primary btn-mwidth pull-right" type="button" onclick="save_embedCode();">Save</button>
							</div>
							</form>				
						</div>
					</div>
				</div>
				
				<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="scheduleCallMarket">   
					<div class="modal-dialog">
						<div class="modal-content">
							<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
								<div class="modal-header">
								<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
								<h4 id="" class="modal-title">Schedule Embed Code</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="clearfix">
											<label style="float:left;" class="control-label">Insert Embed Code From Google:</label>
											<textarea type="text" name="market[embed_code]" class='form-control' rows="4" cols="10"  id="marketEmbedCode" value="" required="required"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
								<button  class="btn btn-primary btn-mwidth pull-right" type="button" onclick="saveScheduleCallMarket();">Save</button>
							</div>
							</form>				
						</div>
					</div>
				</div>				
				<?php endif;?>				
				<div aria-hidden="false" role="dialog" class="modal fade in" data-backdrop="static" data-keyboard="false" id="mainDocWaitBox">
					<div class="modal-dialog">
						<div class="modal-content">
							<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
								<div class="modal-header">
								<h4 id="" class="modal-title">File Uploading to Google Drive</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group input-string-group nomr" >
											The file was transferred to the lead folder and is currently being converted into a google document. It will be seen in the lead folder upon completion. Please 10 seconds and click on the lead name in order to refresh the lead folder.
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
							</div>
							</form>				
						</div>
					</div>
				</div>
				<div  class="row">
					<div class="col-lg-12">
						<div class="col-lg-12">
							<div class="loading-spinner" id="bottom_form_market" style='display:none;'>
								<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
							</div>
						</div>
						<div class="panel-body" id="from_litigation" style='display:none;'>
							<?php 
								$openLeadDetails = true;
								$openDriveBox = true;
								$openButtonList = true;
								$openPatentTable = true;
								if((int)$this->session->userdata['type']!=9){
									if(!in_array(12,$this->session->userdata['modules_assign'])){
										$openLeadDetails = false;
									}
								}
								if((int)$this->session->userdata['type']!=9){
									if(!in_array(14,$this->session->userdata['modules_assign'])){
										$openDriveBox = false;
									}
								}
								if((int)$this->session->userdata['type']!=9){
									if(!in_array(15,$this->session->userdata['modules_assign'])){
										$openButtonList = false;
									}
								}
								if((int)$this->session->userdata['type']!=9){
									if(!in_array(16,$this->session->userdata['modules_assign'])){
										$openPatentTable = false;
									}
								}
							?>

							<?php echo form_open('dashboard/litigation',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>'formLitigation', 'style'=>'margin-bottom: 0;'));?>
							
							<div class="row">
								<div class="col-xs-10">
									<div class="row">
										<div class="loading-spinner" id="loading_spinner_form_litigation" style="display:none;">
											<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
										</div>
										<?php if((int)$this->session->userdata['type']==9):?>
										<div class="col-width text-center" style="width:25px;">
											<a href="#" onclick="cancelImport()" class="link-blue" style="display: inline-block; margin-top:5px; text-decoration:none;">
												<i style="font-size:16px;" class="glyph-icon icon-trash-o"></i>
											</a>
										</div>
										<div class="col-sm-8">
											<input name="litigation1[import_url]" id="lititgationImportURL" class="form-control" placeholder="Paste URL"/>
										</div>
										<div class="col-width" style="width: 230px;">
											<div class="row">
												<div class="col-xs-12">
													<button type="button" id="btnImport" class="btn btn-default float-left btn-mwidth" onclick="importDataFromExternalUrl();">
														<div id="loader" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
														Import
													</button>
												</div>
												<div class="col-xs-4" style="display: none;">
													<a href="javascript://" id="cancelImport" class="btn btn-default float-left btn-block" style='display:none' onclick="cancelImport();">Cancel</a>
												</div>
											</div>
										</div>
										<?php endif;?>
										<?php 
											
											if($openLeadDetails===true):
										?>
										<div class="col-xs-7">
											<div class="row row-width">
												<div class="col-xs-12" style="padding-right: 4px;">
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationLinkToPacer"><strong><a href='javascript:void(0)' onclick='getPreLeadDetails();'>Lead Form</a>(S/N <span id="serialNumber"></span>):</strong></label>
														<?php echo form_input(array('name'=>'litigation[lead_name]','required'=>'required','id'=>'litigationleadName','placeholder'=>'','maxlength'=>27,'class'=>'form-control input-string is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Case Name:</label>
														<?php echo form_input(array('name'=>'litigation[case_name]','id'=>'litigationCaseName','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Litigation Stage:</label>
														<?php echo form_input(array('name'=>'litigation[litigation_stage]','id'=>'litigationLitigationStage','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Market/Industry:</label>
														<?php echo form_input(array('name'=>'litigation[market_industry]','id'=>'litigationMarketIndustry','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Case Type:</label>
														<?php echo form_input(array('name'=>'litigation[case_type]','id'=>'litigationCaseType','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseNumber">Case Number:</label>
														<?php echo form_input(array('name'=>'litigation[case_number]','id'=>'litigationCaseNumber','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Cause:</label>
														<?php echo form_input(array('name'=>'litigation[cause]','id'=>'litigationCause','placeholder'=>'','class'=>'form-control is-big'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Court:</label>
														<?php echo form_input(array('name'=>'litigation[court]','id'=>'litigationCourt','placeholder'=>'','class'=>'form-control'));?>
													</div>
												</div>
												<div class="col-width" style="padding-right: 4px; width: 175px;">
													<div class="form-group input-string-group nomr" style="border-bottom:0px;">
														<label class="control-label" for="litigationNoOfPatent">Patents:</label>
														<a class="btn" href="javascript:void(0)" id="selectListP" onclick="getListPrePatent(jQuery(this));">Select</a>
														<?php echo form_input(array('name'=>'litigation[no_of_patent]','type'=>'hidden','id'=>'litigationNoOfPatent','placeholder'=>'','class'=>'form-control is-small'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label for="marketExpectedPrice" class="control-label">Upfront Price($M):</label>
														<?php echo form_input(array('name'=>'litigation[upfront_price]','id'=>'litigationUpfront_price','placeholder'=>'','class'=>'form-control', 'maxlength'=>'3'));?>
														<?php echo form_input(array('name'=>'litigation[expected_price]','type'=>'hidden','id'=>'litigationExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label for="marketProspects" class="control-label">Prospects:</label>
														<?php echo form_input(array('name'=>'litigation[no_of_prospects]','id'=>'litigationProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Filling Date:</label>
														<?php echo form_input(array('name'=>'litigation[filling_date]','id'=>'litigationFillingDate','placeholder'=>'yyyy-mm-dd','class'=>'form-control is-date'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Active Defendants:</label>
														<?php echo form_input(array('name'=>'litigation[active_defendants]','id'=>'litigationActiveDefendants','placeholder'=>'','class'=>'form-control is-small'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationCaseName">Original Defendants:</label>
														<?php echo form_input(array('name'=>'litigation[original_defendants]','id'=>'litigationOriginalDefendants','placeholder'=>'','class'=>'form-control is-small'));?>
													</div>
													<div class="form-group input-string-group nomr">
														<label class="control-label" for="litigationLinkToPacer">Link to Pacer:</label>
														<?php echo form_input(array('name'=>'litigation[link_to_pacer]','id'=>'litigationLinkToPacer','placeholder'=>'','class'=>'form-control'));?>
													</div>
		                                            <div class="form-group input-string-group nomr">
		            									<label class="control-label" for="litigationLinkToPacer">Link to RPX:</label>
		            									<?php echo form_input(array('name'=>'litigation[link_to_rpx]','id'=>'litigationLinkToRPX','placeholder'=>'','class'=>'form-control'));?>
		            								</div>
												</div>
											</div>
										</div>
										<?php 	endif;?>
										<?php if($openDriveBox===true && $openLeadDetails===true):?>
										<div class="col-sm-5 col-md-5 col-xs-5">
											<div class="row">
												<?php  if($openLeadDetails===true):?>
												<div class="col-xs-6">
													<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">Plaintiff's Lead Attorney:</label>
													<?php echo form_textarea(array('name'=>'litigation[lead_attorney]','id'=>'litigationLeadAttorney','placeholder'=>'Plaintiff\'s Lead Attorney','class'=>'form-control','rows'=>3,'cols'=>29,'style'=>'height:218px !important;','autocomplete'=>false));?>
												</div>
												<?php 	endif;?>
												<?php if($openDriveBox===true):?>
												<div class="col-xs-6">
													<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">Lead's File Folder: <select class="" style='width:100px;' onchange="findThisDriveFile(jQuery(this))" id="clipboard"></select></label>
													<div class="panel google-box-list" style='height:218px;overflow-y:scroll;overflow-x:hidden;' id="litigation_doc_list"></div> 
												</div>
												<?php endif;?>
											</div>
										</div>	
										<?php endif;?>
									</div>									
								</div>
								<div class="col-xs-2">
									<div style="margin-right:-1px; margin-top:-2px;">
										<div class="clearfix">
											<?php  if($openLeadDetails===true):?>
											<input type='hidden' name="litigation[scrapper_data]" id="litigationScrapperData" class='form-control'/>	
											<input type='hidden' name="other[id]" id="commentID" value="0" class='form-control'/>	
											<input type="hidden" name="litigation[complete]" value="" id="litigationComplete"/>
											<input type="hidden" name="litigation[type]" value="Litigation" id="litgationType"/>
											<?php endif;?>
											<?php  if($openLeadDetails===true || $openPatentTable===true):?>
											<input type='hidden' name="litigation[id]" id="litigationId" value="0" class='form-control'/>
											<?php endif;?>
											<?php  if($openPatentTable===true):?>
											<input type="hidden" name="litigation[patent_data]" value="" id="litigationPatentData"/>
											<?php endif;?>
											<?php  if($openButtonList===true):?>
											<input type="hidden" name="litigation[seller_info]" value="1" id="litigationSellerInfo"/>
											<input type="hidden" name="litigation[send_proposal_letter]" value="" id="litigationProposal_letter"/>
											<input type="hidden" name="litigation[create_patent_list]" value="" id="litigationCreate_patent_list"/>
											<?php endif;?>
											<?php  if($openLeadDetails===true || $openPatentTable===true):?>
												<button type="button" class="btn btn-primary btn-block pull-left" onclick="submitData()" style='width:48%;margin-right:5px;'>Save</button>
												<button type="button" onclick="moveEmails()" class="btn btn-primary btn-block pull-left" style='width:48%;margin-top:0px;'>Move Emails</button>
											<?php endif;?>
										</div>
										<div class=" clearfix mrg5T">
											<?php  if($openButtonList===true):?>
											<div class="todo-list-custom button-list" style="height:218px; padding-left: 4px; border: solid 1px #dfe8f1;width:100%;"></div>
											<?php endif;?>
										</div>										
									</div>
								</div>
							</div>
							<div class="row row-width mrg10T">
								<div style="clear:both;" class="clearfix">
									<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="displayPatentTable('from_litigation')">
										<i class="glyph-icon icon-cube" style="font-size:16px;"></i>
											Open Patent Table
									</a> 
									<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displaySaleActivityTable('from_litigation',jQuery(this))">
										<i class="glyph-icon icon-cubes" style="font-size:16px;"></i>
											Sales Activity
									</a>
									<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displayAquisitionActivityTable('from_litigation',jQuery(this))">
										<i class="glyph-icon icon-rocket" style="font-size:16px;"></i>
											Aquisition Activity
									</a>
									<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displayLitigationCampaign('from_litigation',jQuery(this))">
										<i class="glyph-icon icon-table" style="font-size:16px;"></i>
											Litigation Campaign
									</a>
								</div>
							</div>
							<div class="row row-width mrg10T openPatentDetail hide">
								<div class="col-xs-12">
									<div class="row mrg10T">
										<div class="col-xs-6">
											<div class="form-group input-string-group select-string-group">
												<label for="litigationSpreadsheetId" class="control-label" style="margin-top:2px;">
													<strong>Select Spreadsheet:</strong>
												</label>
												<select name="litigation[spreadsheet_id]" id="litigationSpreadsheetId" class="form-control" onchange="findWorksheet(jQuery(this));">
													<option value="">-- Select Spreadsheet --</option>
												</select>												
											</div>
										</div>
										<div class="col-xs-6">
											<div class="form-group input-string-group select-string-group" style='/*position:relative;left:-9999px*/'>
												<label for="generalTechnologies" class="control-label" style="margin-top:2px;">
													Select Worksheet:
												</label>
												<?php  if($openLeadDetails===true):?>
												<select name="litigation[worksheet_id]" id="litigationWorksheetId" class="form-control" onchange="findWorksheetUrlMarket(jQuery(this),jQuery('#litigationFileUrl'))"></select>
												<?php endif;?>
												<?php  if($openLeadDetails===true || $openPatentTable===true):?>
												<input type="hidden" class="form-control input-string"  name="litigation[file_url]" id="litigationFileUrl" value=""/>												
												<?php 	endif;?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-width" style="width:600px;">
									<div style="clear:both; margin-top:17px;" class="clearfix">
										<?php  if($openLeadDetails===true):?>
										<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="refreshHSTTable('from_litigation')">
											<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
											Clear Table
										</a>
										<a href="#" id="loadingLink" class='link-blue pull-right' style='text-decoration:none;' onclick="findPatentFromSheetForm('from_litigation',1)">
											<i class="glyph-icon icon-recycle" style="font-size:16px;"></i>
											Import / Update Data
										</a>
										&nbsp;
										<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;"></div>
										<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openExcelSheet()">
											<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
											Open Patent List
										</a>
										<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openAggregateReferencedApplicant('from_litigation')">
											<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
											Referring Applicants
										</a>
										<?php 	endif;?>
									</div>
								</div>
							</div>	
							<?php  if($openLeadDetails===true || $openPatentTable===true):?>
							<div class="mrg5T hide patentTable" style='margin-top:5px;width:100%;padding:0;' id="patent_data">
								<div class="example-box-wrapper">
									<table class="table table-bordered" id="scrap_patent_data_market">
										<thead>
											<tr>
												<th>Patent</th>
												<th>Technology/Market</th>
												<th># of Licensees</th>
												<th>Post Grant</th>
												<th>Current Assignee</th>
												<th>Application</th>
												<th>Title</th>
												<th>Original Assignee</th>
												<th>Priority</th>
												<th>File</th>  
												<th>Family</th>
												<th>Referenced</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
							<?php 	endif;?>

								<?php if((int)$this->session->userdata['type']==9):?>
								<div class="mrg15T" style='margin-top:5px;width:100%;' id="show_data">
									<div style='width:100%;'>
										<div class='' id="tablesOtherData">
											<h3 class="title-hero">  
												Litigation Campaign
											</h3>
											<div class="example-box-wrapper">
												<ul class="nav-responsive nav nav-tabs">
													<li class="active"><a href="#tab1" data-toggle="tab">Cases</a></li>
													<li class=""><a href="#tab2" data-toggle="tab">Defendants</a></li>
													<li><a href="#tab3" data-toggle="tab">Patents</a></li>
													<li><a href="#tab4" data-toggle="tab">Accused Products</a></li>
													<li><a href="#tab5" data-toggle="tab">Docket Entries</a></li>
												</ul>
												<div class="tab-content">
													<div class="tab-pane active" id="tab1">
														<table id="datatable-hide-columns"  class="table table-striped table-bordered " cellspacing="0" width="100%">
															<thead>
															<tr>
																<th>Date Filed</th>
																<th>Case Name</th>
																<th>Docket Number</th>
																<th>Termination Date</th>
															</tr>
															</thead>
															<tbody> 
																
															</tbody>
														</table>
													</div>
													<div class="tab-pane " id="tab2">
														<table id="datatable-hide-columns1" class="table table-striped table-bordered " cellspacing="0" width="100%">
															<thead>
															<tr>
																<th>Date Filed</th>
																<th>Case Name</th>
																<th>Litigation</th>
																<th>Termination Date</th>
															</tr>
															</thead>
															<tbody> 
																
															</tbody>
														</table>
													</div>
													<div class="tab-pane" id="tab3">
														<table id="datatable-hide-columns2" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead>
															<tr>
																<th>Patent #</th>
																<th>Title</th>
																<th>Est. Priority Date</th>
															</tr>
															</thead>
															<tbody> 
																
															</tbody>
														</table>
													</div>
													<div class="tab-pane" id="tab4">
														<table id="datatable-hide-columns3" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead>
															<tr>
																<th>Date Filed</th>
																<th>Defandants</th>
																<th>Accused Products</th>
															</tr>
															</thead>
															<tbody> 
																
															</tbody>
														</table>
													</div>	
													<div class="tab-pane" id="tab5">
														<table id="datatable-hide-columns4" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead>
																<tr>
																	<th>Entry #</th>
																	<th>Date Filed</th>
																	<th>Date Entered</th>
																	<th>Entry Description</th>
																</tr>
															</thead>
															<tbody>
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>		<?php endif;?>			
							</form>
						</div>
						<!-- From Litigation End -->
					</div>
					<div class="col-lg-12">
						<div class="panel-body" id="from_regular" style='display:none'>
								<?php echo form_open('dashboard/market',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>"marketLead","onsubmit"=>"return dataValidateMarket();"));?>
									<div class="row">										
										<div class="row">
											<div class="col-xs-10">												
												<div class="row">
													<div class="loading-spinner" id="loading_spinner_form_market" style="display:none;">
														<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
													</div>
													<?php if($openLeadDetails===true):?>
													<div class="col-xs-7">
														<div class="row row-width">
															<div class="col-xs-12" style="padding-right: 4px;">
																<div class="form-group input-string-group nomr">
																	<label for="marketProspectsName" class="control-label" style="padding-left:2px;"><a href='javascript:void(0)' onclick='getPreLeadDetails();'>Lead Form</a> (S/N <span id="serialNumber"></span>):</label>
																	<?php echo form_input(array('name'=>'market[lead_name]','required'=>'required','id'=>'marketlead_name','placeholder'=>'','class'=>'form-control input-string','maxlength'=>27,'style'=>'font-weight:bold'));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><a id="sellerBtn" href='javascript:void(0)' onclick="openContactForFrom(1,'from_regular');">Seller / Owner:</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'market[plantiffs_name]','id'=>'marketOwner'));?>
																	<?php echo form_input(array('type'=>'text','name'=>'market[seller_contact]','id'=>'marketSellerContact','class'=>'form-control input-string'));?>
																</div>																
																<div class="form-group input-string-group nomr" >
																	<label for="marketExpectedPrice" class="control-label" style="padding-left:2px;"><a id="showNameBtn" href='javascript:void(0)' onclick="openContactForFrom(4,'from_regular');">Name, Title (1):</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'market[person_name_1]','id'=>'marketPersonName1','placeholder'=>'','class'=>'form-control',"tabindex"=>"5"));?>
																	<?php echo form_input(array('type'=>'text','name'=>'market[person_title_1]','id'=>'marketPersonTitle1','placeholder'=>'','class'=>'form-control input-string',"tabindex"=>"5"));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketExpectedPrice" class="control-label" style="padding-left:2px;"><a id="showNameSecondBtn" href='javascript:void(0)' onclick="openContactForFrom(5,'from_regular');">Name, Title (2):</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'market[person_name_2]','id'=>'marketPersonName2','placeholder'=>'','class'=>'form-control',"tabindex"=>"6"));?>
																	<?php echo form_input(array('type'=>'text','name'=>'market[person_title_2]','id'=>'marketPersonTitle2','placeholder'=>'','class'=>'form-control input-string',"tabindex"=>"6"));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><!--<a id="brokerFirmBtn" href='javascript:void(0)' onclick="openContactForFrom(2,'from_regular');"></a>-->Broker Firm:</label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'market[broker]','id'=>'marketBroker'));?>
																	<?php echo form_input(array('type'=>'text','readonly'=>'readonly','name'=>'market[broker_contact]','id'=>'marketBrokerContact','class'=>'form-control input-string'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><a id="brokerPersonBtn" href='javascript:void(0)' onclick="openContactForFrom(3,'from_regular');">Broker Person:</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'market[broker_person]','id'=>'marketBrokerPerson'));?>
																	<?php echo form_input(array('type'=>'text','name'=>'market[broker_person_contact]','id'=>'marketBrokerPersonContact','class'=>'form-control input-string'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketProspects" class="control-label" style="padding-left:2px;">Technology:</label>
																	<?php echo form_input(array('name'=>'market[relates_to]','id'=>'marketRelatesTo','placeholder'=>'','class'=>'form-control',"tabindex"=>"2",'style'=>'font-weight:bold'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="optionExpirationDate" class="control-label" style="padding-left:2px;">Option Expiration Date:</label>
																	<input type="text" id="optionExpirationDate" name='market[option_expiration_date]' placeholder="" class="form-control is-date" tabindex="2" style="font-weight: bold;">
																</div>
															</div>
															<div class="col-width" style="padding-right: 4px; width:175px;">
																<div class="form-group input-string-group nomr" style="border-bottom:0px;">
																	<label for="marketNo_of_us_patents" class="control-label"># of Patents:</label>
																	<a class="btn" href="javascript:void(0)" id="selectListP" onclick="getListPrePatent(jQuery(this));">Select</a>
																	<?php echo form_input(array('name'=>'market[no_of_us_patents]','type'=>'hidden','id'=>'marketNo_of_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketNo_of_non_us_patents" class="control-label">Upfront Price:</label>
																	<?php echo form_input(array('name'=>'market[expected_price]','type'=>'text','id'=>'marketupfront_price','placeholder'=>'','class'=>'form-control','maxlength'=>'3'));?>
																	<?php echo form_input(array('name'=>'market[no_of_non_us_patents]','type'=>'hidden','id'=>'marketno_of_non_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
																	<?php echo form_input(array('name'=>'market[upfront_price]','id'=>'marketExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4', 'style'=>'min-width:38px;'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketProspects" class="control-label"># of Prospects:</label>
																	<?php echo form_input(array('name'=>'market[no_of_prospects]','id'=>'marketProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketProspects" class="control-label">Markets:</label>
																	<a id="marketBtn" href='javascript:void(0)' onclick="openMarketButton('from_regular');" class='btn'>Select</a>
																	<div id="marketBoxList" class="panel google-box-list" style='height:87px; margin-bottom:0;overflow-y:scroll;overflow-x:hidden;border-bottom:0px;'></div>
																</div>
															</div>
														</div>
													</div> 
													<?php endif;?>
													<div class="col-xs-5">
														<div class="row">
															<?php if($openLeadDetails===true):?>
															<div class="col-xs-6">
																<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">Seller's Address / General Notes:</label>
																<?php echo form_textarea(array('name'=>'market[address]','id'=>'marketAddress','placeholder'=>'Address','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:218px !important;',"tabindex"=>"4",'autocomplete'=>false));?>
															</div>
															<?php endif;?>
															<?php if($openDriveBox===true):?>
															<div class="col-xs-6">
																<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">Lead's File Folder: <select onchange="findThisDriveFile(jQuery(this))" class="" style='width:100px;' id="clipboard"></select></label>
																<div id="litigation_doc_list" class="panel google-box-list" style="height:218px; margin-bottom:0;overflow-y:scroll;overflow-x:hidden;">
																	
																</div>
															</div>
															<?php endif;?>
														</div>
													</div>
												</div>												
											</div>
											<div class="col-xs-2">
												<div style=" margin-top:-2px;">
													<div class="clearfix">
													<?php if( $openLeadDetails===true):?>
														<input type="hidden" name="market[gmail_message_id]" id="lead_gmail_message_id" value=""/>
														<input type="hidden" name="comment[comment_id]" id="commentId" value="0"/>
														<?php endif;?>
														
														<?php if( $openPatentTable===true):?>
														
														<input type="hidden" name="market[patent_data]" value="" id="marketPatentData"/>
														<?php endif;?>
														<?php if($openButtonList===true):?>
														<input type="hidden" name="market[seller_info]" value="1" id="marketSellerInfo"/>
	                                                    <input type="hidden" name="market[complete]" value="" id="marketComplete"/>
														<input type="hidden" name="market[send_proposal_letter]" value="" id="marketProposal_letter"/>
														<input type="hidden" name="market[create_patent_list]" value="" id="marketCreate_patent_list"/>
														<input type="hidden" name="market[market_data]" value="" id="marketMarketData"/>
														<?php endif;?>
														<?php if($openPatentTable===true || $openLeadDetails===true):?>
														<input type="hidden" name="market[id]" id="marketLeadId" value="0"/>
														<button type="button" onclick="submitDataMarket()" class="btn btn-primary btn-block pull-left" style='width:48%;margin-right:5px;'>Save</button>
														<button type="button" onclick="moveEmails()" class="btn btn-primary btn-block pull-left" style='width:48%;margin-top:0px;'>Move Emails</button>
														<?php endif;?>
													</div>
													<div class="clearfix mrg5T">
														<?php if($openButtonList===true):?>
														<div class="todo-list-custom fright button-list" style="height:218px; border: solid 1px #dfe8f1;width:100%">
															
														</div>
														<?php endif;?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row row-width mrg10T">
										<div style="clear:both;" class="clearfix">
											<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="displayPatentTable('from_regular')">
												<i class="glyph-icon icon-cube" style="font-size:16px;"></i>
													Open Patent Table
											</a>
											<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displaySaleActivityTable('from_regular',jQuery(this))">
												<i class="glyph-icon icon-cubes" style="font-size:16px;"></i>
													Sales Activity
											</a>
											<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displayAquisitionActivityTable('from_regular',jQuery(this))">
												<i class="glyph-icon icon-rocket" style="font-size:16px;"></i>
													Aquisition Activity
											</a>
										</div>
									</div>
									<div class="row row-width mrg10T openPatentDetail hide">
										<div class="col-xs-12">
											<div class="row mrg10T">
												<div class="col-xs-6">
													<div class="form-group input-string-group select-string-group">
														<label for="marketSpreadsheetId" class="control-label" style="margin-top:2px;">
															<strong>Select Spreadsheet:</strong>
														</label>
														<select name="market[spreadsheet_id]" id="marketSpreadsheetId" class="form-control" onchange="findWorksheetMarket(jQuery(this));">
															<option value="">-- Select Spreadsheet --</option>
														</select>												
													</div>
												</div>
												<div class="col-xs-6">
													<div class="form-group input-string-group select-string-group" style='/*position:relative;left:-9999px*/'>
														<?php if( $openLeadDetails===true):?>
														<label for="marketTechnologies" class="control-label" style="margin-top: 2px;">
															Select Worksheet:
														</label>
														<select name="market[worksheet_id]" id="marketWorksheetId" class="form-control" onchange="findWorksheetUrlMarket(jQuery(this),jQuery('#marketFileUrl'))"></select>
														<?php endif;?>
														<?php if($openPatentTable===true || $openLeadDetails===true):?>
														<input type="hidden" class="form-control input-string"  name="market[file_url]" id="marketFileUrl" value=""/>
														<?php endif;?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-width" style="width:600px;">
											<div style="clear:both; margin-top:17px;" class="clearfix">
												<?php if( $openLeadDetails===true):?>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="refreshHSTTable('from_regular')">
													<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
													Clear Table
												</a>
												<a href="#" id="loadingLink" class='link-blue pull-right' style='text-decoration:none;' onclick="findPatentFromSheetForm('from_regular',1)">
													<i class="glyph-icon icon-recycle" style="font-size:16px;"></i>
													Import / Update Data
												</a>
												&nbsp;
												<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;"></div>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openExcelSheet()">
													<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
													Open Patent List
												</a>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openAggregateReferencedApplicant('from_regular')">
													<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
													Referring Applicants
												</a>
												<?php endif;?>
											</div>
										</div>					
									</div>
								<?php echo form_close()?>

								<!--  Clear Button Patent Data -->
								<?php if($openPatentTable===true):?>
								<div class="mrg5T hide patentTable" style='margin-top:5px;width:100%;padding:0;' id="patent_data">				
									<div class="example-box-wrapper">					
										<table class="table table-bordered" id="scrap_patent_data_market">
											<thead>
												<tr>
													<th>Patent</th>
													<th>Technology/Market</th>
													<th># of Licensees</th>
													<th>Post Grant</th>
													<th>Current Assignee</th>
													<th>Application</th>
													<th>Title</th>
													<th>Original Assignee</th>
													<th>Priority</th>
													<th>File</th>   
													<th>Family</th>
													<th>Referenced</th>
												</tr>
											</thead>
											<tbody>							
											</tbody>
										</table>
									</div>
								</div>
								<?php endif;?>
						</div>
					</div>
					
					<?php 
						if($this->session->userdata['type']="9"):
					?>
					<div class="col-lg-12">
						<div class="panel-body" id="from_nonacquistion" style='display:none'>
								<?php echo form_open('dashboard/lead_form',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>"leadForm","onsubmit"=>"return dataValidateLeadForm();"));?>
									<div class="row">										
										<div class="row">
											<div class="col-xs-10">												
												<div class="row">
													<div class="loading-spinner" id="loading_spinner_form_market" style="display:none;">
														<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
													</div>
													<?php if($openLeadDetails===true):?>
													<div class="col-xs-7">
														<div class="row row-width">
															<div class="col-xs-12" style="padding-right: 4px;">
																<div class="form-group input-string-group nomr">
																	<label for="acquisitionlead_name" class="control-label" style="padding-left:2px;"><a href='javascript:void(0)' onclick='getPreLeadDetails();'>Lead Form</a> (S/N <span id="serialNumber"></span>):</label>
																	<?php echo form_input(array('name'=>'acquisition[lead_name]','required'=>'required','id'=>'acquisitionlead_name','placeholder'=>'','class'=>'form-control input-string','maxlength'=>27,'style'=>'font-weight:bold'));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><a id="sellerBtn" href='javascript:void(0)' onclick="openContactForFrom(1,'from_nonacquistion');" class=''>Party:</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'acquisition[plantiffs_name]','id'=>'acquisitionOwner'));?>
																	<?php echo form_input(array('type'=>'text','name'=>'acquisition[seller_contact]','id'=>'acquisitionSellerContact','class'=>'form-control input-string'));?>
																</div>																
																<div class="form-group input-string-group nomr" >
																	<label for="marketExpectedPrice" class="control-label" style="padding-left:2px;"><a id="showNameBtn" href='javascript:void(0)' onclick="openContactForFrom(4,'from_nonacquistion');" class=''>Name, Title (1):</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'acquisition[person_name_1]','id'=>'acquisitionPersonName1','placeholder'=>'','class'=>'form-control',"tabindex"=>"5"));?>
																	<?php echo form_input(array('type'=>'text','name'=>'acquisition[person_title_1]','id'=>'acquisitionPersonTitle1','placeholder'=>'','class'=>'form-control input-string',"tabindex"=>"5"));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketExpectedPrice" class="control-label" style="padding-left:2px;"><a id="showNameSecondBtn" href='javascript:void(0)' onclick="openContactForFrom(5,'from_nonacquistion');" class=''>Name, Title (2):</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'acquisition[person_name_2]','id'=>'acquisitionPersonName2','placeholder'=>'','class'=>'form-control',"tabindex"=>"6"));?>
																	<?php echo form_input(array('type'=>'text','name'=>'acquisition[person_title_2]','id'=>'acquisitionPersonTitle2','placeholder'=>'','class'=>'form-control input-string',"tabindex"=>"6"));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><!--<a id="brokerFirmBtn" href='javascript:void(0)' onclick="openContactForFrom(2,'from_nonacquistion');" class=''></a>-->Representative Firm:</label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'acquisition[broker]','id'=>'acquisitionBroker'));?>
																	<?php echo form_input(array('type'=>'text','readonly'=>'readonly','name'=>'acquisition[broker_contact]','id'=>'acquisitionBrokerContact','class'=>'form-control input-string'));?>
																</div>
																<div class="form-group input-string-group nomr" >
																	<label for="marketOwner" class="control-label" style="padding-left:2px;"><a id="brokerPersonBtn" href='javascript:void(0)' onclick="openContactForFrom(3,'from_nonacquistion');" class=''>Representative Person:</a></label>
																	<?php echo form_input(array('type'=>'hidden','name'=>'acquisition[broker_person]','id'=>'acquisitionBrokerPerson'));?>
																	<?php echo form_input(array('type'=>'text','name'=>'acquisition[broker_person_contact]','id'=>'acquisitionBrokerPersonContact','class'=>'form-control input-string'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketProspects" class="control-label" style="padding-left:2px;">Topic:</label>
																	<?php echo form_input(array('name'=>'acquisition[relates_to]','id'=>'acquisitionRelatesTo','placeholder'=>'','class'=>'form-control',"tabindex"=>"2",'style'=>'font-weight:bold'));?>
																</div>
															</div>
															<div class="col-width" style="padding-right: 4px; width:175px;">
																<div class="form-group input-string-group nomr">
																	<label for="marketNo_of_us_patents" class="control-label">Created:</label>
																	<?php echo form_input(array('name'=>'acquisition[create_date]','type'=>'text','id'=>'acquisitionCreateDate','placeholder'=>'','class'=>'form-control','readonly'=>'readonly'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketNo_of_non_us_patents" class="control-label">Updated:</label>
																	<?php echo form_input(array('name'=>'acquisition[update_date]','id'=>'acquisitionUpdateDate','placeholder'=>'','class'=>'form-control is-date'));?>
																</div>
																<div class="form-group input-string-group nomr">
																	<label for="marketExpectedPrice" class="control-label">Next Action:</label>
																	<?php echo form_input(array('name'=>'acquisition[next_action]','id'=>'acquisitionNextAction','placeholder'=>'','class'=>'form-control is-date', 'style'=>'min-width:38px;'));?>
																</div>																
																<div class="form-group input-string-group nomr">
																	<label for="marketProspects" class="control-label">Address:</label>
																	<a id="marketBtn" href='javascript:void(0)' onclick="openMarketButton('from_nonacquistion');" class='btn'>Select</a>
																	<div id="acquisitionBoxList" class="panel google-box-list" style='height:91px; margin-bottom:0;overflow-y:scroll;overflow-x:hidden;border-bottom:0px;'></div>
																</div>
															</div>
														</div>
														<div class="form-group input-string-group nomr">
															<label for="acquisitionOptionExpirationDate" class="control-label" style="padding-left:2px;">Option Expiration Date:</label>
															<input type="text" id="acquisitionOptionExpirationDate" name='acquisition[option_expiration_date]' placeholder="" class="form-control is-date" tabindex="2" style="font-weight: bold;">
														</div>
													</div> 
													<?php endif;?>
													<div class="col-xs-5">
														<div class="row">
															<?php if($openLeadDetails===true):?>
															<div class="col-xs-6">
																<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">General Notes:</label>
																<?php echo form_textarea(array('name'=>'acquisition[address]','id'=>'acquisitionAddress','placeholder'=>'General Notes','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:218px !important;',"tabindex"=>"4",'autocomplete'=>false));?>
															</div>
															<?php endif;?>
															<?php if($openDriveBox===true):?>
															<div class="col-xs-6">
																<label style="display: block; margin-bottom:4px; margin-top:7px; margin-bottom: 12px;">Lead's File Folder: <select onchange="findThisDriveFile(jQuery(this))" class="" style='width:100px;' id="clipboard"></select></label>
																<div id="litigation_doc_list" class="panel google-box-list" style="height:218px; margin-bottom:0;overflow-y:scroll;overflow-x:hidden;">
																</div>
															</div>
															<?php endif;?>
														</div>
													</div>
												</div>												
											</div>
											<div class="col-xs-2">
												<div style=" margin-top:-2px;">
													<div class="clearfix">
													<?php if( $openLeadDetails===true):?>
														<input type="hidden" name="acquisition[gmail_message_id]" id="acquisition_gmail_message_id" value=""/>
														<input type="hidden" name="comment[comment_id]" id="commentId" value="0"/>
														<?php endif;?>
														
														<?php if( $openPatentTable===true):?>
														
														<input type="hidden" name="acquisition[patent_data]" value="" id="acquisitionPatentData"/>
														<?php endif;?>
														<?php if($openButtonList===true):?>
														<input type="hidden" name="acquisition[seller_info]" value="1" id="acquisitionSellerInfo"/>
	                                                    <input type="hidden" name="acquisition[complete]" value="" id="acquisitionComplete"/>
														<input type="hidden" name="acquisition[send_proposal_letter]" value="" id="acquisitionProposal_letter"/>
														<input type="hidden" name="acquisition[create_patent_list]" value="" id="acquisitionCreate_patent_list"/>
														<input type="hidden" name="acquisition[market_data]" value="" id="acquisitionMarketData"/>
														<input type="hidden" name="acquisition[type]" value="" id="acquisitionType"/>
														<?php endif;?>
														<?php if($openPatentTable===true || $openLeadDetails===true):?>
														<input type="hidden" name="acquisition[id]" id="acquisitionLeadId" value="0"/>
														<button type="button" onclick="submitFromData('from_nonacquistion')" class="btn btn-primary btn-block pull-left" style='width:48%;margin-right:5px;'>Save</button>
														<button type="button" onclick="moveEmails()" class="btn btn-primary btn-block pull-left" style='width:48%;margin-top:0px;'>Move Emails</button>
														<?php endif;?>
													</div>
													<div class="clearfix mrg5T">
														<?php if($openButtonList===true):?>
														<div class="todo-list-custom fright button-list" style="height:218px; border: solid 1px #dfe8f1;width:100%">
															
														</div>
														<?php endif;?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row row-width mrg10T">
										<div style="clear:both;" class="clearfix">
											<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="displayPatentTable('from_nonacquistion')">
												<i class="glyph-icon icon-cube" style="font-size:16px;"></i>
													Open Patent Table
											</a>
											<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displaySaleActivityTable('from_nonacquistion',jQuery(this))">
												<i class="glyph-icon icon-cubes" style="font-size:16px;"></i>
													Sales Activity
											</a>
											<a href="#" class='link-blue pull-right actBtn' style='text-decoration:none; margin-left:15px;' onclick="displayAquisitionActivityTable('from_nonacquistion',jQuery(this))">
												<i class="glyph-icon icon-rocket" style="font-size:16px;"></i>
													Aquisition Activity
											</a>
										</div>
									</div>
									<div class="row row-width mrg10T openPatentDetail hide">
										<div class="col-xs-12">
											<div class="row mrg10T">
												<div class="col-xs-6">
													<div class="form-group input-string-group select-string-group">
														<label for="acquisitionSpreadsheetId" class="control-label" style="margin-top:2px;">
															<strong>Select Spreadsheet:</strong>
														</label>
														<select name="acquisition[spreadsheet_id]" id="acquisitionSpreadsheetId" class="form-control" onchange="findWorksheetMode(jQuery(this),'','from_nonacquistion');">
															<option value="">-- Select Spreadsheet --</option>
														</select>												
													</div>
												</div>
												<div class="col-xs-6">
													<div class="form-group input-string-group select-string-group" style='/*position:relative;left:-9999px*/'>
														<?php if( $openLeadDetails===true):?>
														<label for="marketTechnologies" class="control-label" style="margin-top: 2px;">
															Select Worksheet:
														</label>
														<select name="acquisition[worksheet_id]" id="acquisitionWorksheetId" class="form-control" onchange="findWorksheetUrlMarket(jQuery(this))"></select>
														<?php endif;?>
														<?php if($openPatentTable===true || $openLeadDetails===true):?>
														<input type="hidden" class="form-control input-string"  name="acquisition[file_url]" id="acquisitionFileUrl" value=""/>														
														<?php endif;?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-width" style="width:600px;">
											<div style="clear:both; margin-top:17px;" class="clearfix">
												<?php if( $openLeadDetails===true):?>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="refreshHSTTable('from_nonacquistion')">
													<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
													Clear Table
												</a>
												<a href="#" id="loadingLink" class='link-blue pull-right' style='text-decoration:none;' onclick="findPatentFromSheetForm('from_nonacquistion',1)">
													<i class="glyph-icon icon-recycle" style="font-size:16px;"></i>
													Import / Update Data
												</a>
												&nbsp;
												<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;"></div>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openExcelSheet()">
													<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
													Open Patent List
												</a>
												<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openAggregateReferencedApplicant('from_nonacquistion')">
													<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
													Referring Applicants
												</a>
												<?php endif;?>
											</div>
										</div>					
									</div>
								<?php echo form_close()?>

								<!--  Clear Button Patent Data -->
								<?php if($openPatentTable===true):?>
								<div class="mrg5T hide patentTable" style='margin-top:5px;width:100%;padding:0;' id="patent_data">				
									<div class="example-box-wrapper">					
										<table class="table table-bordered" id="scrap_patent_data_market">
											<thead>
												<tr>
													<th>Patent</th>
													<th>Technology/Market</th>
													<th># of Licensees</th>
													<th>Post Grant</th>
													<th>Current Assignee</th>
													<th>Application</th>
													<th>Title</th>
													<th>Original Assignee</th>
													<th>Priority</th>
													<th>File</th>  
													<th>Family</th>
													<th>Referenced</th>
												</tr>
											</thead>
											<tbody>							
											</tbody>
										</table>
									</div>
								</div>
								<?php endif;?>
						</div>
					</div>
					<script>
						var salesActivities = {'1':'Call In','2':'Call Out','3':'Email Sent','4':'Send Letter','5':'LinkedIn Message','6':'Email Received'};
						$(function() { "use strict";
							$('.date_calendar').datepicker({
								format: 'yyyy-mm-dd'
							});
						});
					</script>
					<div class="col-lg-12 hide" id="sales_acititity">
						<div class="panel-body">
							<div class="row activity-filter">
								<div class="col-xs-2">
									<a id='btnActivityAll' class="btn btn-primary btn-block" onclick="getCustomerListSalesActivity()">Manage Customers</a>
								</div>
								<div class="col-xs-10">
									<form id="frm_sales_activity" method="post">
									<input type="hidden" name="name[main_type]"value="1" id="activityMainType"/>
									<div class="row form-horizontal form-flat">
										<div class="col-xs-12">
											<div class="col-xs-2" style="margin-top: 2px;">
												<div class="form-group">
													<select class="form-control" name="activity[type]" id="activityType" onchange="checkActivityLog();" style="text-align: left;">
														<option value=''>-- Select Activity --</option>
														<option value="1">Call in</option>
														<option value="2">Call out</option>
														<option value="9">Compose</option>														
														<option value="3">Send an email campaign</option>
														<option value="5">LinkedIn message</option>
														<!--<option value="6">Email Received</option>-->
														<option value="4">Send a letter</option>
														<optgroup label="------------">
														<option value="7">Manage email templates</option>
														</optgroup>
														
													</select>
													<input type="hidden" name="activity[person]" id="activityPerson" value=""/>
												</div>
											</div>											
											<div class="col-xs-7" style="margin-top: 4px;">
												<div class="form-group input-string-group nomr">
													<label for="activityNote" class="control-label">Note:</label>
													<input type="text" required name="activity[note]" class="form-control input-string" id="activityNote" style="text-align: left;">
												</div>
											</div>
											<div class="col-xs-2">
												<a id="btnSaveActivity" class="btn btn-primary btn-block" href='javascript://' onclick='saveActivity()'><!--<i class="glyph-icon icon-save" title="Save" ></i>-->Save</a>
											</div>
											<div class="col-xs-1">
												<a id="btnSortingActivity" class="btn btn-primary btn-block" href='javascript://' onclick='tableSortActivity()'><!--<i class="glyph-icon icon-save" title="Save" ></i>-->Sorting</a>
											</div>
										</div>
									</div>
										<input type="hidden" id="activityLeadId" name="activity[lead_id]" value="0"/>
										<input type="hidden" id="activityCid" name="activity[c_id]" value="0"/>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<table class="table table-bordered" id="activityTable">
										<thead>
											<tr>
												<th width="66px">#</th>
												<th><a onclick='openAllCompanies()' href='javascript://'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a> Customer</th>												
												<th width="100px">Activity</th>
												<th width="110px">Date</th>
												<th width="120px">Person</th>
												<th width="400px">Note</th>
											</tr>
										</thead>
										<tbody class='main_active'>											
										</tbody>
									</table>
								</div>
								<div class="col-xs-12">
									<table class="table table-bordered" id="aquisitionTable">
										<thead>
											<tr>
												<th width="66px">#</th>
												<th><a onclick='openAllCompanies()' href='javascript://'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a> Customer</th>												
												<th width="100px">Activity</th>
												<th width="110px">Date</th>
												<th width="120px">Person</th>
												<th width="400px">Note</th>
											</tr>
										</thead>
										<tbody class='main_active'>											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>					
					<?php endif;?>
					<div class="col-lg-12">
						<div class="panel-body" id="search_lead_box" style=''>
							<div class="row">
								<?php echo form_open('dashboard/search',array("class"=>"form-horizontal form-flat","id"=>'search_form'))?>
								<div class="col-xs-10">
									<div class="row">
										<div class="col-xs-7">
											<div class="row row-width">
												<div class="col-xs-12" style="padding-right: 4px; width: 432px;">
													<div class="form-group input-string-group nomr">
														<label for="searchlead_name" class="control-label" style="margin-left:2px;">Serial Number:</label>
														<input type="text" name="search[serial_number]" value="" id="searchlead_name" placeholder="" class="form-control input-string" maxlength="27" style="font-weight: bold; width: 252px; text-align: left;">
													</div>
													<div class="form-group input-string-group nomr">
														<label for="searchlead_name" class="control-label" style="margin-left:2px;">Lead Name:</label>
														<input type="text" name="search[lead_name]" value="" id="searchlead_name" placeholder="" class="form-control input-string" maxlength="27" style="font-weight: bold; width: 252px; text-align: left;">
													</div>
													<div class="form-group input-string-group nomr" style="">
														<label for="searchOwner" class="control-label" style="margin-left:2px;">Seller / Owner:</label>	
														<input type="text" name="search[plantiffs_name]" class="form-control input-string" value="" id="searchOwner">
													</div>																
													<div class="form-group input-string-group nomr" style="">
														<label for="searchPersonName1" class="control-label">Name, Title (1):</label>
														<input type="text" name="search[person_name_1]" value="" id="searchPersonName1" placeholder="" class="form-control" tabindex="5" style="width: 326px; text-align: left;">
													</div>
													<div class="form-group input-string-group nomr" style="">
														<label for="searchPersonName2" class="control-label">Name, Title (2):</label>
														<input type="text" name="search[person_name_2]" value="" id="searchPersonName2" placeholder="" class="form-control" tabindex="6" style="width: 326px; text-align: left;">
													</div>
													<div class="form-group input-string-group nomr" style="">
														<label for="searchBroker" class="control-label" style="margin-left:2px;">Broker Firm:</label>
														<input type="text" name="search[broker]" class="form-control input-string" value="" id="searchBroker">
													</div>
													<div class="form-group input-string-group nomr" style="">
														<label for="searchBrokerPerson" class="control-label" style="margin-left:2px;">Broker Person:</label>
														<input type="text" name="search[broker_person]" class="form-control input-string" value="" id="searchBrokerPerson">
													</div>
													<div class="form-group input-string-group nomr">
														<label for="searchtRelatesTo" class="control-label">Technology:</label>
														<input type="text" name="search[relates_to]" value="" id="searchtRelatesTo" placeholder="" class="form-control" tabindex="2" style="font-weight: bold; width: 345px; text-align: left;">
													</div>
												</div>												
											</div>
										</div>
									</div>
								</div>
								<div class='col-xs-2'>
									<div style='margin-top:-2px'>
										<div class='clearfix'>
											<button class='btn btn-primary btn-block' type='button' onclick='searchForm()'>Search</button>
										</div>
									</div>
								</div>
								</form>
								<div class='col-xs-12 mrg20T' id="s_result">
									
								</div>
							</div>
						</div>
					</div>					
				</div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/views/user/dashboard.js"></script>
<script>
	function moveEmails(){
		if(leadGlobal>0){
			if(jQuery('#displayEmail').find('iframe').length>0){
				_src = jQuery('#displayEmail').find('iframe').attr('src');
				_eID = _src.split('/').pop();
				_mainActivity = parseInt(jQuery("#activityMainType").val());
				if(parseInt(_eID) && _mainActivity>0){
					getAllLeadsPointing(_mainActivity,_eID);
				} else {
					alert("Please select person whom you want to assign email.");
				}
			} else {
				alert("Please select email you want to move.");
			}
		} else {
			alert("Please select lead first.");
		}
	}
</script>
</div>
<?php echo $Layout->element('timeline');?>
</div>