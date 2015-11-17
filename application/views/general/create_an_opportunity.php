<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Create Opportunity</li>");
});
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<div class="row row-width">
				<div class="col-width" style="width: 400px;">
					<h3 class="title-hero">Create an Opportunity</h3>
					<!-- <p>Select Opportunity from list.</p> -->

					<?php 
					if($this->session->flashdata('message')){
					?>
						<?php echo $this->session->flashdata('message');?>
					<?php
						}
					?>
					<?php echo form_open('general/create_an_opportunity',array('class'=>"form-horizontal form-flat"))?>
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Select a Lead:</label>
	                        <select name="opportunity[lead_id]" id="opportunityLeadId" required class="form-control" onchange="getLead(jQuery(this));">
								<option value="">-- Select a Lead --</option>
	                            <?php 
									if(count($lists)>0){
										foreach($lists as $list){
								?>
										<option data-attr="<?php echo $list->type;?>" data-lead_name="<?php echo $list->lead_name;?>" value="<?php echo $list->id?>">
											<?php echo $list->lead_name?>
										</option>
								<?php
										}
									}
								?>
	                        </select>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Opportunity Type:</label>
	                        <input name="opportunity[type]" id="opportunityType" required readonly class="form-control" />
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
							<label class="control-label">Opportunity Name:</label>
	                        <input name="opportunity[opp_name]" id="opportunityopp_name" required class="form-control" />
		                </div>
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Select a Program Director:</label>
	                        <select name="opportunity[pd_id]" required class="form-control" >
								<option value="">-- Select User --</option>
	                            <?php 
									if(count($users)>0){
										foreach($users as $user){
								?>
										<option value="<?php echo $user->id?>"><?php echo $user->name?></option>
								<?php
										}
									}
								?>
	                        </select>
		                </div>
						<div class="mrg5T">
							<button type="submit" class="btn btn-primary btn-mwidth">Create Opportunity</button>
						</div>
					<?php echo form_close();?>
				</div>
			</div>
			<div class="row mrg15T">
				<div class="col-xs-12">
					<h3 class="title-hero">Manage Opportunity</h3>
    				<!-- <p></p> -->

    				<table class='table' id="manageLeads">
					<thead>
						<tr>
							<th>Opportunity Name</th>
							<th>Assign to</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($lead_list as $lead)
						{
				        ?>
							<tr>
								<td>
									<?php echo (empty($lead->opportunityName))?$lead->lead_name:$lead->opportunityName;?>
								</td>
								<td>
									<select name="assign_user[]" class="form-control" onchange="assign_opp(jQuery(this));"  style='width:50%;'>
										<option value="">-- Select User --</option>
								<?php 
									foreach($users as $user){
										$SELECTED ="";
										if($lead->userAssigned == $user->id){
											$SELECTED = "SELECTED='SELECTED'";
										}
										?>
										<option <?php echo $SELECTED;?> value="<?php echo $user->id?>" data-lead_id="<?php echo $lead->id;?>"><?php echo $user->name?></option>
										<?php
									}
				                    
								?>
								</select><input type="submit" name="assign_opp" class="btn-primary btn btn-mwidth" value="Save" class="form-control" id="save<?php echo $lead->id; ?>" style='width:20%;display: none;'/><div id="loader" style="display: none;"><img src="http://preloaders.net/preloaders/257/Solid%20snake.gif"/></div>
								</td>
								<td>
				                    <a href="delete_opp/<?php echo $lead->id; ?>">Delete</a>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
    

			

<script>
	function getLead(object){
		jQuery("#opportunityType").val(object.find('option:selected').attr('data-attr'));
		jQuery("#opportunityopp_name").val(object.find('option:selected').attr('data-lead_name'));
	}
    function assign_opp(object){
        var lead_id = object.find('option:selected').attr('data-lead_id');
        var user_id = object.find('option:selected').attr('value');
       // var user_id = jQuery(this).attr('data-user_id');
        //alert("Lead id is "+lead_id);
        //alert("User id is "+user_id);
        jQuery("#save"+lead_id).show();
        jQuery("#save"+lead_id).click(function(){
                jQuery("#loader").show();
               $.post('assign_opp',{"action":"assign_opp","lead_id":lead_id,"user_id":user_id},function(response){
                alert(response);
            });
            jQuery("#loader").hide();
        })
    }
</script>