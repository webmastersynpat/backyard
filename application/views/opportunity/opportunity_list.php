<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">

<div class="panel dashboard-box">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Opportunity</a></li><li class='active'>List</li>");
});
</script>
    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="responsive-table">
				<h3 class="title-hero">Opportunity List</h3>
				<?php 

					if(count($projects)>0){
			
                    
				?>

					<table class='table table-bordered mrg5T'>

						

				<?php

						foreach($projects as $project){

				?>

							<tr>

								<td>

									<?php 

										$name = "";

										switch($project->type){

											case 'Litigation':

												$name = $project->case_name." - ".$project->case_number;

											break;

											case 'Market':

											case 'General':

											case 'SEP':

												$name = $project->opp_name." - ".$project->relates_to." - ".$project->portfolio_number;

											break;

											default:

												$name = $project->opp_name;

											break;

										}

										

									/*echo anchor('opportunity/working_opportunity/'.$project->lead_id,$name);*/
									echo anchor('opportunity/dummy_opportunity/'.$project->lead_id,$name);?>

								</td>

							</tr>

				<?php

						}

				?>

					</table>

				<?php

					} else {

				?>

					<p class='alert alert-warning'>No opportunites found!</p>

				<?php } ?>

			</div>

		</div>

	</div>

</div>

	</div>
<?php echo $Layout->element('timeline');?>
</div>