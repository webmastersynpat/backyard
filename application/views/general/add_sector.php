<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Add Sectors</li>");
});
</script> 
<div class="panel dashboard-box">
    <div class="panel-body">
		<?php 
			if($this->session->flashdata('message')){
				echo $this->session->flashdata('message');	
			}
		?>
		<div class="example-box-wrapper" id="sector">
		    <h3 class="title-hero">Sector</h3>
			<?php echo form_open('general/add_sectors',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'));?>
			<?php 
					$id =0;
					$name = "";
					if(isset($sector_data) && count($sector_data)>0){
						$name = $sector_data->name;
						$id = $sector_data->id;
					}					
				?>
			<div class="row row-width">
				<div class="col-width" style="width:400px;">
					<div class="form-group input-string-group" style="margin-left: 0;">
						<label class="control-label">Enter Sector Name:</label>
						<input type="text" name="sector[name]" value="<?php echo $name; ?>" id="sectorName" required class='form-control'/>
					</div>
				</div>
			</div>
			<div class="mrg5T">
				<input type="hidden" name="sector[id]" id="sectorId" value="<?php echo $id;?>"/>
				<input type="hidden" name="trig_case" value="1"/>
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
					if(count($sectors)>0):
					$i=0;
					foreach($sectors as $sector):
				?>				
				<tr class="<?php if($i%2==0):?>even<?php else:?>odd<?php endif;?> gradeX">
					<td><?php echo $sector->name?></td>
					<td class="text-center"><?php echo anchor('general/add_sectors/'.$sector->id,'<i class="glyph-icon icon-edit"></i>', array("class"=>''))?> / <?php echo anchor('general/delete_sector/'.$sector->id,'<i class="glyph-icon icon-close"></i>', array("class"=>'','onclick' => "return confirm('Do you want delete this record')"))?></td>
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