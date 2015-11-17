<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Manage Technologies</li>");
});
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
		    <h3 class="title-hero">Manage Technologies</h3>
		    <!-- <p>Add or Delete Sectors.</p> -->
			<?php 
			if($this->session->flashdata('message')){
			?>
				<?php echo $this->session->flashdata('message');?>
			<?php					
				}
			?>
			<?php echo form_open('general/manage_technologies',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'))?>
				<div class="row row-width">
					<div class="col-width" style="width:400px;">
						<div class="form-group input-string-group" style="margin-left: 0;">
		                    <label class="control-label">Enter Technology Name:</label>
		                    <input type="text" name="technology[name]" id="technologyName" required class='form-control'/>
		                </div>
					</div>
				</div>
				<div class="mrg5T">
					<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
				</div>
			<?php echo form_close();?>
		</div>
		<div class="example-box-wrapper">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
				<thead>
					<tr>
						<th>Name</th>
						<th width="50px">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					if(count($technology)>0):
					$i=0;
					foreach($technology as $tech):
				?>				
				<tr class="<?php if($i%2==0):?>even<?php else:?>odd<?php endif;?> gradeX">
					<td><?php echo $tech->name?></td>
					<td class="text-center"><?php echo anchor('general/delete_technology/'.$tech->id,'<i class="glyph-icon icon-close"></i>', array("class"=>''))?></td>
				</tr>
				<?php $i++; endforeach;?>
				<?php else:?>
				<tr colspan="2"><p class='alert'>No record found!</p></tr>
				<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
</div>