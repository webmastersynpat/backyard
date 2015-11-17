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
$buttons = array('SELLER'=>'Seller Info','PROPOSAL'=>'Proposal Letter','PATENT_LIST'=>'Patent List','REVIEW'=>'Forward to Review','SCHEDULE'=>'Schedule 1st Call','NDA_TERMSHEET'=>'NDA + TermSheet','APPROVED_LEAD'=>'Approved Lead','EXECUTE_NDA'=>'Execute NDA','EOU'=>'Seller EOU in Folder','DRAFT_PPA'=>'Draft a PPA','EXECUTE_PPA'=>'Execute a PPA','PPA_EXECUTE'=>'PPA Execute');

?>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Button Boxes</li>");
});
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

			<?php echo form_open('general/manage_stages',array('class'=>"form-horizontal form-flat", 'style'=>'margin-bottom: 0;'));
				$name = "";
				$type="";
				$buttonsID = "";
				$buttonList = array();
				if(isset($update_data)){
					if(count($update_data['detail'])>0){
						$name = $update_data['detail']->name;
						$type= $update_data['detail']->type;
						$buttonsID = $update_data['detail']->button_id;
					}
					if(count($update_data['buttons'])>0){
						$buttonList = $update_data['buttons'];
					}
				}
			?>           
			<div class="example-box-wrapper">
				<div class="row row-width">
					<div class="col-width" style="width: 400px;">
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Name</label>
		                    <input type="text" name="general[name]" id="stagesName" class='form-control' value="<?php echo $name;?>" required="required"/>
		                </div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Lead Type:</label>
		                    <select name="other[type]" id="generalType" class='form-control' required="required" onchange="findButtonList(jQuery(this))">
								<option value="">-- Select Type</option>
								<option value="Litigation" <?php if($type=="Litigation"):?> SELECTED="SELECTED" <?php endif;?>>From Litigation</option>
								<option value="Market" <?php if($type=="Market"):?> SELECTED="SELECTED" <?php endif;?>>From Market</option>
								<option value="General" <?php if($type=="General"):?> SELECTED="SELECTED" <?php endif;?>>From Proactive / SEP</option>
							</select>
						</div>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Buttons:</label>
		                    <select name="general[button_id]" id="generalButtonId" class='form-control' required="required">
								<option value="">-- Select Button</option>
								<?php 
									if(count($buttonList)>0){
										foreach($buttonList as $button){
								?>
										<option value="<?php echo $button->id?>" <?php if($button->id==$buttonsID):?> SELECTED='SELECTED' <?php endif;?>><?php echo $button->name?></option>
								<?php
										}
									}
								?>
							</select>
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
				function findButtonList(object){
					if(object.val()!=""){
						jQuery.ajax({
							url:'<?php echo $Layout->baseUrl?>general/findButtonsByType',
							type:'POST',
							data:{d:object.val()},
							cache:false,
							success:function(data){
								_data = jQuery.parseJSON(data);
								if(_data.length){
									jQuery("#generalButtonId").empty().append('<option value="">-- Select Button</option>');
									for(i=0;i<_data.length;i++){
										jQuery("#generalButtonId").append('<option value="'+_data[i].id+'">'+_data[i].name+'</option>');
									}
								}
							}
						});
					} else {
						
					}					
				}
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
				});
			</script>
	        <div class="row">
				<h3 class="title-hero mrg15T " style="">Stages In Different Lead Type</h3>
				<div class='col-lg-4'>
					<h5>From Litigation</h5>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" width="33%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>
								<th width="80px">Action</th>
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
										<td><a class="btn btn-xs" href="/general/manage_stages/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='/general/delete_button_stages/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class='col-lg-4'>
					<h5>From Market</h5>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_market" width="33%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>
								<th width="80px">Action</th>
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
										<td><a class="btn btn-xs" href="/general/manage_stages/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='/general/delete_button_stages/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class='col-lg-4'>
					<h5>From Proactive / SEP</h5>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_proactive" width="33%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Type</th>
								<th width="80px">Action</th>
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
										<td><a class="btn btn-xs" href="/general/manage_stages/<?php echo $lit->id?>"><i class='glyph-icon icon-edit'></i></a><a href='/general/delete_button_stages/<?php echo $lit->id?>'><i class='glyph-icon icon-close'></i></a></td>
									</tr>
						<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div/>
		</div>
	</div>
</div>


