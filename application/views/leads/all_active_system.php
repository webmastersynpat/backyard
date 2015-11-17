<style> 
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

</style>
<input type="hidden" name="acitivity" id="acitivity" value="<?php echo $activity?>"/>
<input type="hidden" name="email" id="email" value="<?php echo $email?>"/>
<div class='col-lg-12'>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" >
	<thead>
		<tr>                         
			<th>Lead Name</th>  
		</tr>
	</thead>
	<tbody>
		<?php 
			if(count($lead)>0){
				foreach($lead as $lit){					
	?>
				<tr data-item-idd="<?php echo $lit->id;?>" class="master">
					<td><a href="javascript://" class="showActivity"><i class="glyph-icon icon-play" title="Contacts" style=""></i></a><a href="javascript://" class="showActivity"><?php echo $lit->lead_name?></a></td>					
				</tr>
				<tr style="display:none">
					<?php 
						if(count($lit->box_list)>0):							
							foreach($lit->box_list as $box){
					?>
							<td>
								<table  class="table table-striped table-bordered">
								<thead><tr><th>Company Name</th></tr></thead>								
								<tbody>
								<tr><td>
								<a href="javascript://" class="showCActivity"><i class="glyph-icon icon-play" title="Contacts" style=""></i></a> <a href="javascript://" class="showCActivity"><?php echo $box['company']->company_name;?></a>
								<?php if(count($box['people'])>0): ?>
								<table style='display:none' class="table table-striped table-bordered">
								<thead><tr><th style='width:10px;'>#</th><th>Name</th></tr></thead>								
								<tbody>
								<?php foreach($box['people'] as $people){?>
								<tr><td><input type="checkbox" value="<?php echo $people->id?>" data-c='<?php echo $box['company']->id?>' data-lead='<?php echo $lit->id?>' onclick='assignedEmailToNewP(jQuery(this))'/></td><td><?php echo $people->first_name." ".$people->last_name?></td></tr>
								<?php }?>
								</tbody>								
								</table>
								<?php endif;?>
								</td></tr></tbody></table>
							</td>
					<?php
							}
					?>					
					<?php endif;?>
				</tr>
	<?php
				}
			}
		?>
	</tbody>
</table>
</div>
<script>
jQuery(document).ready(function(){
	if($("tr.master a.showActivity").length>0){
		$("tr.master a.showActivity").unbind("click");
		$("tr.master a.showActivity").click(function(){
			$(this).parent().parent().next("tr").toggle();
		});
	}
	if($("a.showCActivity").length>0){
		$("a.showCActivity").unbind("click");
		$("a.showCActivity").click(function(){
			$(this).parent().find("table").toggle();
		});
	}
});
function assignedEmailToNewP(o){
	lead = o.attr('data-lead');
	c_id = o.attr('data-c');
	p = o.val();
	thread = jQuery("#email").val();
	_mainActivity = jQuery("#acitivity").val();
	jQuery.ajax({type:"POST",url:__baseUrl+"dashboard/oldlinkWithMessage",data:{old_thread:lead,c_id:c_id,p:p,thread:thread,t:_mainActivity},cache:false,success:function(et){if(et!=""){__data=jQuery.parseJSON(et);if(__data.send){window.parent.refreshAcquisitionAndSalesActivity();window.parent.closeSlideBarLeftMessagePredfined();}else{alert("Please try after sometime")}}else{alert("Please try after sometime")}}});
}
</script>