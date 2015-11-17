<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
						<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
						<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
<script>
var ___table = "";
jQuery(document).ready(function(){
	___table =jQuery("#datatable-task-log").DataTable( {
				"searching":false,
				"scrollY": "554px",
				"scrollX": true,
				"scrollCollapse": true,
				"paging": false,
				"oLanguage": {
					"sEmptyTable":     "<p class='alert alert-info'>No record found!</p>"
				}				
			});
});
function changePermission(o,l,i){
	$d=0;
	if(o.prop('checked')){
		$d=1;
	}
	jQuery.ajax({
		url:'<?php echo $Layout->baseUrl?>/opportunity/file_permission_technical',
		type:"POST",
		data:{d:$d,t:l,i:i},
		cache:false,
		success:function(data){
			
		}
	});
}
</script>
<div class="col-lg-12">
	<table class="table" id="datatable-task-log" width="99% !important">
		<thead>
			<tr>
				<th>Lead</th>
				<th>Type</th>
				<th>Date</th>
				<th>Expert</th>
				<th># Patents</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($i=0;$i<count($eou_data);$i++){
			?>
			<tr>
				<td><?php echo $eou_data[$i]->lead_name?></td>
				<td><?php echo $eou_data[$i]->type?></td>
				<td><?php echo $eou_data[$i]->create_date?></td>
				<td><?php echo $eou_data[$i]->expert?></td>
				<td><?php echo $eou_data[$i]->total_patent?></td>
				<td><input type="checkbox" onchange="changePermission(jQuery(this),<?php echo $eou_data[$i]->lead_id?>,<?php echo $eou_data[$i]->id?>)" <?php if($eou_data[$i]->permmission==1):?>checked='checked'<?php endif;?> /></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</div>