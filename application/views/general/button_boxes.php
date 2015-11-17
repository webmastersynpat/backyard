<style>
	div.dataTables_filter label {
		float: right;
	}
	div.dataTables_filter input {
	    box-shadow: none;
	    float: left;
	    height: 24px;
	    margin-bottom: 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}

	table .btn-primary,
	table .btn-danger {
		background: none !important;
		border: none !important;
		display: inline;
		line-height: 1;
		padding: 0;
	}
	table .btn-primary {
		color: #2196f3 !important;
	}
	table .btn-danger {
		color: #d9534f !important;
	}
</style> 
<?php
$buttons = array('DRIVE'=>'Drive Button','EMAIL'=>'Email Button','TASK'=>'Task Button','CREATE_DOCKET'=>'Create a Docket in the Store','SELLER'=>'Seller Info','PROPOSAL'=>'Proposal Letter','PATENT_LIST'=>'Patent List','TECHNICAL_DD'=>'Start Technical Due Dilligence','LEGAL_DD'=>'Start Legal Due Dilligence','CLAIM_ILLUS'=>'Start Claim Illustration','REVIEW'=>'Forward to Review','SCHEDULE'=>'Schedule 1st Call','NDA_TERMSHEET'=>'NDA + TermSheet','SELLER_IS_INTERSTED'=>'Seller like the deal','APPROVED_LEAD'=>'Synpat like the deal','EXECUTE_NDA'=>'Execute NDA','EOU'=>'Seller EOU in Folder','DRAFT_PPA'=>'Draft a PPA','EXECUTE_PPA'=>'Execute a PPA','PPA_EXECUTE'=>'PPA Execute','FUNDING'=>'Funding Successful');

?>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Button Boxes</li>");
});

function showAnotherFields(v){
	if(v!=""){
		if(v=="DRIVE"){
			jQuery("#extra_fields_drive").css('display','');
			jQuery("#extra_fields_email").css('display','none');
			jQuery("#extra_fields_task").css('display','none');
		} else if(v=="EMAIL"){
			jQuery("#extra_fields_drive").css('display','none');
			jQuery("#extra_fields_task").css('display','none');
			jQuery("#extra_fields_email").css('display','');
		} else if(v=='TASK'){
			jQuery("#extra_fields_email").css('display','none');
			jQuery("#extra_fields_drive").css('display','none');
			jQuery("#extra_fields_task").css('display','');
		} else {
			jQuery("#extra_fields_email").css('display','none');
			jQuery("#extra_fields_drive").css('display','none');
			jQuery("#extra_fields_task").css('display','none');
		}
	}
}
</script>

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

			<?php echo form_open('general/button_boxes',array('class'=>"form-horizontal form-flat", 'style'=>'margin-bottom: 0;'))?>           
			<div class="example-box-wrapper">
				<div class="row row-width">
					<div class="col-width" style="width: 400px;">
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Button ID:</label>
		                    <select name="general[button_id]" id="generalButtonId" class='form-control' required="required" onchange="showAnotherFields(jQuery(this).val())">
								<option value="">-- Select Button ID</option>
								<?php 
									$value="";
									if(isset($update_data)){
										$value = $update_data->button_id;
									}
								?>
								<option value="DRIVE" <?php if($value=="DRIVE"):?> SELECTED="SELECTED" <?php endif;?>>Drive Button</option>
								<option value="EMAIL" <?php if($value=="EMAIL"):?> SELECTED="SELECTED" <?php endif;?>>Email Button</option>
								<option value="TASK" <?php if($value=="TASK"):?> SELECTED="SELECTED" <?php endif;?>>Task Button</option>
								<option value="CREATE_DOCKET" <?php if($value=="CREATE_DOCKET"):?> SELECTED="SELECTED" <?php endif;?>>Create a Docket in the Store</option>
								<option value="SELLER" <?php if($value=="SELLER"):?> SELECTED="SELECTED" <?php endif;?>>Seller Info</option>
								<option value="PROPOSAL" <?php if($value=="PROPOSAL"):?> SELECTED="SELECTED" <?php endif;?>>Proposal Letter</option>
								<option value="PATENT_LIST" <?php if($value=="PATENT_LIST"):?> SELECTED="SELECTED" <?php endif;?>>Patent List</option>
								<option value="TECHNICAL_DD" <?php if($value=="TECHNICAL_DD"):?> SELECTED="SELECTED" <?php endif;?>>Start Technical Due Dilligence</option>
								<option value="LEGAL_DD" <?php if($value=="LEGAL_DD"):?> SELECTED="SELECTED" <?php endif;?>>Start Legal Due Dilligence</option>
								<option value="CLAIM_ILLUS" <?php if($value=="CLAIM_ILLUS"):?> SELECTED="SELECTED" <?php endif;?>>Start Claim Illustration</option>
								<option value="REVIEW" <?php if($value=="REVIEW"):?> SELECTED="SELECTED" <?php endif;?>>Forward to Review</option>
								<option value="SCHEDULE" <?php if($value=="SCHEDULE"):?> SELECTED="SELECTED" <?php endif;?>>Schedule 1st Call</option>
								<option value="NDA_TERMSHEET" <?php if($value=="NDA_TERMSHEET"):?> SELECTED="SELECTED" <?php endif;?>>NDA + TermSheet</option>
								<option value="SELLER_IS_INTERSTED" <?php if($value=="SELLER_IS_INTERSTED"):?> SELECTED="SELECTED" <?php endif;?>>Seller like the deal</option>
								<option value="APPROVED_LEAD" <?php if($value=="APPROVED_LEAD"):?> SELECTED="SELECTED" <?php endif;?>>Synpat like the deal</option>
								<option value="EXECUTE_NDA" <?php if($value=="EXECUTE_NDA"):?> SELECTED="SELECTED" <?php endif;?>>Execute NDA</option>
								<option value="EOU" <?php if($value=="EOU"):?> SELECTED="SELECTED" <?php endif;?>>Seller EOU in Folder</option>
								<option value="DRAFT_PPA" <?php if($value=="DRAFT_PPA"):?> SELECTED="SELECTED" <?php endif;?>>Draft a PPA</option>
								<option value="EXECUTE_PPA" <?php if($value=="EXECUTE_PPA"):?> SELECTED="SELECTED" <?php endif;?>>Execute a PPA</option>
								<option value="PPA_EXECUTE" <?php if($value=="PPA_EXECUTE"):?> SELECTED="SELECTED" <?php endif;?>>PPA Execute</option>
								<option value="FUNDING" <?php if($value=="FUNDING"):?> SELECTED="SELECTED" <?php endif;?>>Funding Successful</option>
							</select>
						</div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Lead Type:</label>
		                    <select name="general[type]" id="generalType" class='form-control' required="required">
								<option value="">-- Select Type</option>
								<?php 
									$value="";
									if(isset($update_data)){
										$value = $update_data->type;
									}
								?>
								<option value="Litigation" <?php if($value=="Litigation"):?> SELECTED="SELECTED" <?php endif;?>>From Litigation</option>
								<option value="Market" <?php if($value=="Market"):?> SELECTED="SELECTED" <?php endif;?>>From Market</option>
								<option value="General" <?php if($value=="General"):?> SELECTED="SELECTED" <?php endif;?>>From Proactive / SEP</option>
								<option value="NON" <?php if($value=="NON"):?> SELECTED="SELECTED" <?php endif;?>>Non Acquisition</option>
								<option value="INT" <?php if($value=="INT"):?> SELECTED="SELECTED" <?php endif;?>>Internal</option>
								<option value="DOCKET" <?php if($value=="DOCKET"):?> SELECTED="SELECTED" <?php endif;?>>Docket</option>
							</select>
						</div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0; display: <?php if(!isset($update_data) || (isset($update_data) && $update_data->button_id!="DRIVE")):?>none<?php endif;?>" id="extra_fields_drive">
		                    <label class="control-label">Drive Master Folder:</label>
		                    <select name="general[drive]" id="generalDrive" class='form-control' >
								<option value="">-- Select File--</option>
								<?php 
									$value="";
									if(isset($update_data) && $update_data->button_id=="DRIVE" ){
										$value = $update_data->reference_id;
									}
								?>
								<?php 
									if(count($list)>0){
										for($i=0;$i<count($list);$i++){
								?>
											<option value="<?php echo $list[$i]->id?>" <?php if($value==$list[$i]->id):?> SELECTED="SELECTED" <?php endif;?>><?php echo $list[$i]->title?></option>
								<?php								
										}
									}
								?>
							</select>
						</div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0; display:<?php if(!isset($update_data) || (isset($update_data) && $update_data->button_id!="EMAIL")):?>none<?php endif;?>" id="extra_fields_email">
		                    <label class="control-label">Email Template:</label>
		                    <select name="general[template]" id="generalTemplate" class='form-control' >
								<option value="">-- Select Email Template--</option>
								<?php 
									$value="";
									if(isset($update_data) && $update_data->button_id=="EMAIL" ){
										$value = $update_data->reference_id;
									}
								?>
								<?php 
									if(count($templates)>0){
										for($i=0;$i<count($templates);$i++){
								?>
											<option value="<?php echo $templates[$i]->id?>" <?php if($value==$templates[$i]->id):?> SELECTED="SELECTED" <?php endif;?>><?php echo $templates[$i]->subject?></option>
								<?php								
										}
									}
								?>
							</select>
						</div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0; display:<?php if(!isset($update_data) || (isset($update_data) && $update_data->button_id!="TASK")):?>none<?php endif;?>" id="extra_fields_task">
		                    <label class="control-label">Message:</label>
							<?php 
									$value="";
									if(isset($update_data) && $update_data->button_id=="TASK" ){
										$value = $update_data->reference_id;
									}									
								?>
		                    <textarea name="general[message]" id="generalMessage" class='form-control' ><?php echo $value;?></textarea>
						</div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Name</label>
		                    <input type="text" name="general[name]" id="generalName" class='form-control' value="<?php  if(isset($update_data)) : echo $update_data->name; endif;?>" required="required"/>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Description</label>
		                    <textarea name="general[description]" id="generalDescription" class='form-control' rows="5" cols="25" required="required"><?php  if(isset($update_data)) : echo $update_data->description; endif;?></textarea>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Status Message</label>
		                    <input type="text" name="general[status_message]" id="generalStatusMessage" class='form-control' value="<?php  if(isset($update_data)) : echo $update_data->status_message; endif;?>" required="required"/>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left:0; border:0px;">
							<label class="control-label">Blink</label>
							<input type="checkbox" class='pull-left' style='margin:9px 15px 3px 10px' value="1" name="general[blink]" <?php  if(isset($update_data) && $update_data->blink==1):?> CHECKED='CHECKED' <?php endif;?>/>
							<label class="control-label">Task</label>
							<input type="checkbox" class='pull-left' style='margin:9px 15px 3px 10px' value="1" name="general[send_task]" <?php  if(isset($update_data) && $update_data->send_task==1):?> CHECKED='CHECKED' <?php endif;?>/>
						</div>
						<div class="mrg5T">
		                    <input type="hidden" name="id" value="<?php if(isset($id)): echo $id; else: echo "0"; endif; ?>"/>
							<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
			<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/interactions-ui/resizable.js"></script>
			<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/interactions-ui/draggable.js"></script>
			<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/interactions-ui/sortable.js"></script>
			<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/interactions-ui/selectable.js"></script>
			<script>
				jQuery(document).ready(function(){
					jQuery("#db_from_litigation").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
							/*console.log("Start: "+start_pos);*/
						},
						update: function (event, ui) {
							/*var start_pos = ui.item.data('start_pos');
							var end_pos = ui.item.index();
							console.log("Start: "+start_pos+"End: "+end_pos);*/
							var newArray = [];
							jQuery("#db_from_litigation>tbody>tr").each(function(index){
								/*console.log(jQuery(this).attr('data-item-idd')+" now: "+index);*/
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
							
							/*console.log(JSON.stringify(newArray));*/
						},
						stop: function (event,ui){
							/*var end_pos = ui.item.index();
							console.log("End: "+end_pos);*/
						}
					});
					jQuery("#db_from_market").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
						},
						update: function (event, ui) {
							var newArray = [];
							jQuery("#db_from_market>tbody>tr").each(function(index){
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
						}
					});
					jQuery("#db_from_proactive").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
						},
						update: function (event, ui) {
							var newArray = [];
							jQuery("#db_from_proactive>tbody>tr").each(function(index){
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
						}
					});
					jQuery("#db_from_acq").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
						},
						update: function (event, ui) {
							var newArray = [];
							jQuery("#db_from_acq>tbody>tr").each(function(index){
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
						}
					});
					jQuery("#db_from_int").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
						},
						update: function (event, ui) {
							var newArray = [];
							jQuery("#db_from_int>tbody>tr").each(function(index){
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
						}
					});
					
					jQuery("#db_from_doc").sortable({
						items: "tbody> tr",
						zIndex: 9999,
						start: function(event, ui) {
							var start_pos = ui.item.index();
							ui.item.data('start_pos', start_pos);
						},
						update: function (event, ui) {
							var newArray = [];
							jQuery("#db_from_int>tbody>tr").each(function(index){
								newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
							});
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>general/change_order',
								data:{spliting:newArray},
								cache:false,
								success:function(){
									
								}
							});
						}
					});
				});
			</script>
	        <div class="row">
				<!--<h3 class="title-hero mrg15T " style="">Buttons In Diffrent Lead Type</h3>-->
				<div class="row">
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>From Litigation</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th>                            
								<th>Status Message</th>                            
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($litigation)>0){
									foreach($litigation as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>" ><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>' ><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>From Market</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_market" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th>             
								<th>Status Message</th>                            								
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($market)>0){
									foreach($market as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>From Proactive / SEP</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_proactive">
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th> 
								<th>Status Message</th>
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($proactive)>0){
									foreach($proactive as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				</div>
				<div class="row">
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>From Non Acquistions</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_acq" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th> 
								<th>Status Message</th>
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($non)>0){
									foreach($non as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>From Internal</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_int" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th> 
								<th>Status Message</th>
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($int)>0){
									foreach($int as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				
				<div class='col-lg-12'>
					<h3 class='font-blue mrg10T mrg10B'>Docket</h3>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_doc" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>                            
								<th>Description</th> 
								<th>Status Message</th>
								<th>Button ID</th>                            
								<th width="120px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(count($doc)>0){
									foreach($doc as $lit){
						?>
									<tr data-item-idd="<?php echo $lit->id;?>">
										<td><?php echo $lit->name?></td>
										<td><?php echo $lit->type?></td>
										<td><?php echo $lit->description?></td>
										<td><?php echo $lit->status_message?></td>
										<td><?php echo $buttons[$lit->button_id]?></td>
										<td><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>?data=copy" ><i class='glyph-icon icon-copy'></i></a><a class="btn btn-xs mrg20R" href="<?php echo $Layout->baseUrl;?>general/button_boxes/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='<?php echo $Layout->baseUrl;?>general/delete_button/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				
				</div>
			</div/>
		</div>
	</div>
</div>


