<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>All Users Activities</li>");
});
</script>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<!-- <h2 class="toptitle">Activity</h2> -->
	<div class="panel dashboard-box">
        <div class="panel-body">
            <!-- <h3 class="title-hero">
                Left timeline
            </h3> -->
            <div class="example-box-wrapper">
				
				<div class="row">
					<?php 
						$getTimeLine = $user;
						if(count($getTimeLine)>0){
							foreach($getTimeLine as $timeline){
					?>
								<div class="col-lg-12">
									<h5><?php echo $timeline['user']->name?></h5>
									<div class="row">
										<div class="col-lg-12" id="timeline_<?php echo $timeline['user']->id?>"></div>
										<script>
											__timeline<?php echo $timeline['user']->id?> = [];
										</script>
										<?php 
											if(count($timeline['history'])>0){
										?>
											<script>
										<?php
												foreach($timeline['history'] as $history){
													$label = "";
													$colorClass="";
													$label = (!empty($history->lead_name))?$history->lead_name:$history->plantiffs_name;
													if(isset($history->leadType)){
														switch($history->leadType){
															case 'Litigation':
																$colorClass = "bg-yellow";
															break;
															
															case 'Market':
																$colorClass = "bg-green";
															break;
															
															case 'General':
																$colorClass = "label-info";
															break;
															
															case 'SEP':
																$colorClass = "bg-warning";
															break;
														}
													}
										?>
												__timeline<?php echo $timeline['user']->id?>.push({'start':new Date('<?php echo $history->create_date?>'),'content': '<span class="tl-label bs-label <?php echo $colorClass;?>"><?php echo $label;?></span><span class="todo-content"><?php echo $history->message;?></span>'});
										<?php
												}
										?>
												jQuery(document).ready(function(){
													var items = new vis.DataSet(__timeline<?php echo $timeline['user']->id?>);
													var container = document.getElementById('timeline_<?php echo $timeline['user']->id?>');
													/*var container = jQuery('timeline_<?php echo $timeline['user']->id?>');*/
													var options = {
														maxHeight: '300px',
														type: 'point',
														selectable:false,														
														showMajorLabels: false,
														zoomMin: 1000 * 60 * 60 * 24,             // one day in milliseconds
														zoomMax: 1000 * 60 * 60 * 24 * 31 * 3     // about three months in milliseconds
													};
													// create the timeline
													timeline_<?php echo $timeline['user']->id ?> = new vis.Timeline(container);
													timeline_<?php echo $timeline['user']->id ?>.setOptions(options);
													timeline_<?php echo $timeline['user']->id ?>.setItems(items);
													timeline_<?php echo $timeline['user']->id ?>.moveTo(new Date(),{animate:true});								
												});
											</script>
										<?php
											} else {
										?>
										<p class='alert alert-info'>No activities found!</p>
										<?php
											}
										?>
									</div>
								</div>
					<?php
							}
						}
					?>					
				</div>
            </div>
        </div>
    </div>
	</div>
</div>