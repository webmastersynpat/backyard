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
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Manage Task</li>");
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

			<?php echo form_open('general/manage_task',array('class'=>"form-horizontal form-flat", 'style'=>'margin-bottom: 0;'))?>           
			<div class="example-box-wrapper">
				<div class="row row-width">
					<div class="col-width" style="width: 400px;">
						<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                    <label class="control-label">Task:</label>
		                    <select name="general[task_type]" id="generalTaskType" class='form-control' required="required">
								<option value="">-- Select Task --</option>
								<?php 
									$value="";
									if(isset($update_data)){
										$value = $update_data->task_type;
									}
								?>
								<option value="NDA" <?php if($value=="NDA"):?> SELECTED="SELECTED" <?php endif;?>>NDA Approval</option>
								<option value="PPA" <?php if($value=="PPA"):?> SELECTED="SELECTED" <?php endif;?>>PPA Approval</option>
		                        <option value="MARKET_RESEARCH" <?php if($value=="MARKET_RESEARCH"):?> SELECTED="SELECTED" <?php endif;?>>Market Research</option>
		                        <option value="ASSETS" <?php if($value=="ASSETS"):?> SELECTED="SELECTED" <?php endif;?>>Assets Approval</option>
		                        <option value="DD" <?php if($value=="DD"):?> SELECTED="SELECTED" <?php endif;?>>Start DD</option>
		                        <option value="ORDER_DAMAGES" <?php if($value=="ORDER_DAMAGES"):?> SELECTED="SELECTED" <?php endif;?>>Order Damages Report</option>
		                        <option value="OTHER_DOCS" <?php if($value=="OTHER_DOCS"):?> SELECTED="SELECTED" <?php endif;?>>Other Docs</option>
		                        <option value="LEAD" <?php if($value=="LEAD"):?> SELECTED="SELECTED" <?php endif;?>>Create a Lead</option>
		                        <option value="LEAD_FORWARD" <?php if($value=="LEAD_FORWARD"):?> SELECTED="SELECTED" <?php endif;?>>Lead Forward</option>
		                        <option value="MARKET_FORWARD" <?php if($value=="MARKET_FORWARD"):?> SELECTED="SELECTED" <?php endif;?>>Market Forward</option>
		                        <option value="CREATE_OPPORTUNITY" <?php if($value=="CREATE_OPPORTUNITY"):?> SELECTED="SELECTED" <?php endif;?>>Work on New Opportunity</option>
		                        <option value="NDA_EXECUTE_APPROVAL" <?php if($value=="NDA_EXECUTE_APPROVAL"):?> SELECTED="SELECTED" <?php endif;?>>NDA Execute Approval</option>
							</select>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Subject</label>
		                    <input type="text" name="general[subject]" id="userSubject" class='form-control' value="<?php  if(isset($update_data)) : echo $update_data->subject; endif;?>" required="required"/>
		                </div>
						<div class="form-group mrg5T" style="margin-left: 0; padding-right: 11px;">
		                    <label class="control-label" style="display: block;">Message</label>
		                    <textarea name="general[message]" id="userMessage" class="form-control" rows="8" cols="82" required="required"><?php  if(isset($update_data)) : echo $update_data->message; endif; ?></textarea>
		                </div>
						<div class="mrg5T">
		                    <input type="hidden" name="id" value="<?php if(isset($id)): echo $id; else: echo "0"; endif; ?>"/>
							<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>

	        <h3 class="title-hero mrg15T" style="margin-bottom: -20px;">Approval CIPO List</h3>
	        <!-- <p></p> -->
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-user">
				<thead>
					<tr>
						<th>#</th>
						<th>Subject</th>
						<th>Message</th>                            
	                    <th width="80px">Action</th>
					</tr>
				</thead>
				<tbody>
							<?php if(count($ApprovalCipoList)>0){

							$i=1;

							foreach($ApprovalCipoList as $cipo_list){

					?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $cipo_list->subject;?></td>
						<td><?php echo $cipo_list->message;?></td>                            
	                    <td class="text-center">
							<a href="<?php echo site_url().'general/manage_task/'.$cipo_list->id;?>" class="btn btn-primary"  title="Edit">
								<i class="glyph-icon icon-edit"></i>
							</a>								
	                        <a href="<?php echo site_url().'general/delete_task/'.$cipo_list->id;?>" class="btn btn-danger" title="Delete">
	                            <i class="glyph-icon icon-close"></i>
	                        </a>
	                    </td>
					</tr>
					<?php	
								$i++;
							}
						}
					?>
				</tbody>
			</table>

		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
        jQuery('#datatable-user').DataTable( {
            "paging": false,
			"language": {
				"emptyTable":     "No data found"
			}
        });
    });
</script>
