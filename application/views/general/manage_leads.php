<!--<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>-->
<script type="text/javascript">
	jQuery(document).ready(function() {
        jQuery('#manageLeads').DataTable( {
            "paging": false
        });
		jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Manage Leads</li>");
    });
	
</script>
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
</style>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<a class="btn btn-primary btn-mwidth" onclick="confirmAll()">Delete Selected</a>
					<a class="btn btn-primary btn-mwidth" onclick="retrieveAll()">Revive Selected</a>
				</div>
			</div>
			<?php echo form_open('general/lead_manage_all',array("id"=>'formLeadManage',"name"=>"formLeadManage"));?>
			<table class='table' id="manageLeads" style='width:80%'>
			<thead>
			<tr>
				<th>#</th>
				<th>Lead Name</th>
				<th>Seller's Name</th>
				<th>Broker's Name</th>
				<th>Type</th>
				<th>Status</th>
				<th>Action</th>
				<th>Created date</th>				
				<th>User Name</th>				
			</tr>	
			</thead>
			<tbody>
		<?php
		foreach($lead_list as $lead){
			?>
			<tr>
				<td><input type="checkbox" name="delete_all[]" value="<?php echo $lead->id;?>"/></td>
				<td><?php echo $lead->lead_name;?></td>
				<td><?php echo $lead->seller_contact;?></td>
				<td><?php echo $lead->broker_contact;?></td>
				<td>
					<select class="form-control" onchange="changeLeadType(jQuery(this),<?php echo $lead->id;?>);" name="popup[type]" data-original="<?php echo $lead->type;?>" id="popupType" style="width: 230px; text-align: left;">
						<option value="">Select Lead Type</option>
						<option value="Litigation" <?php if($lead->type=="Litigation"):?> SELECTED="SELECTED" <?php endif;?>>From Litigation</option>
						<option value="Market" <?php if($lead->type=="Market"):?> SELECTED="SELECTED" <?php endif;?>>From Market</option>
						<option value="General" <?php if($lead->type=="General"):?> SELECTED="SELECTED" <?php endif;?>>From Proactive General</option>
						<option value="SEP" <?php if($lead->type=="SEP"):?> SELECTED="SELECTED" <?php endif;?>>From Proactive SEP</option>
						<option value="NON" <?php if($lead->type=="NON"):?> SELECTED="SELECTED" <?php endif;?>>Non Acquisition</option>
						<option value="INT" <?php if($lead->type=="INT"):?> SELECTED="SELECTED" <?php endif;?>>Internal</option>
					</select>
				</td>				
				<td>
				<?php 
					if((int)$lead->status==0){
						echo "RAW";
					}
					if((int)$lead->status==1){
						echo "IN PROCESS";
					}
					if((int)$lead->status==2){
						echo "CONVERTED";
					}
					if((int)$lead->status==3){
						echo "DELETED";
					}
				?>
				</td>
				<td>
					<a  class='mrg20R' href='<?php echo $Layout->baseUrl;?>dashboard/index/<?php echo $lead->id;?>' title="View"><i class='glyph-icon icon-eye'></i></a>     
					<a class='mrg20R' title="Deactivate" onclick="confirmBox('<?php echo $Layout->baseUrl;?>general/delete_lead/<?php echo $lead->id;?>')" href='javascript://'><i class='glyph-icon icon-close'></i></a>       
					<a class='mrg10R' title="Activate" href='<?php echo $Layout->baseUrl;?>general/revive_lead/<?php echo $lead->id;?>'><i class='glyph-icon icon-mail-reply'></i></a>
				</td>
				<td>
					<?php echo $lead->create_date;?>
				</td>
				<td>
					<?php echo $lead->userName;?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>	
		</table>
		<input type="hidden" name="delete_flag" id="delete_flag" value="0"/>
		<input type="hidden" name="retreive_flag" id="retreive_flag" value="0"/>
		</form>
		</div>
	</div>
</div>
<script>
	function changeLeadType(object,n){
		if(object.val()!=""){
			if(object.attr('data-original')!=object.val()){
				jQuery.ajax({
					url:'<?php echo $this->config->base_url()?>general/change_lead_type',
					data:{c:object.val(),lead:n},
					type:'POST',
					cache:false,
					success:function(data){
						if(data>0){
							object.attr('data-original',object.val());
						}
					}
				});
			}
		}
	}
	function confirmAll(){
		res = confirm("Are you sure?");
		if(res){
			jQuery("#delete_flag").val(1);
			jQuery("#retreive_flag").val(0);
			document.formLeadManage.submit();
		}
	}
	
	function retrieveAll(){
		res = confirm("Are you sure?");
		if(res){
			jQuery("#delete_flag").val(0);
			jQuery("#retreive_flag").val(1);
			document.formLeadManage.submit();
		}
	}
	
	function confirmBox(u){
		res = confirm("Are you sure?");
		if(res){
			window.location = u;
		}
	}
</script>