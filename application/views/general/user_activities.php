<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>All Users Activities</li>");
});
</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/timeline/vis.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/timeline/vis.css" />
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
					<table class="table">
						<tbody>
						<tr>
					<?php 
						$getTimeLine = $user;
						if(count($getTimeLine)>0){
							foreach($getTimeLine as $timeline){
					?>								
									<td valign="top">
										<div class="col-lg-12">
											<h3><?php echo $timeline['user']->name?></h3>
											<div class="timeline-box timeline-box-left">
												
												<?php 
													if(count($timeline['history'])>0){
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
														<div class="tl-row">
															<div class="tl-item float-right">
															   <div class="tl-bullet bg-red"></div>
																<div class="popover right">
																	<div class="arrow"></div>
																	<div class="popover-content" style="cursor: pointer;">
																		<div class="tl-label bs-label <?php echo $colorClass;?>">
																			<?php echo $label;?>
																		</div>
																		<div class="tl-container">
																			<p class="tl-content">
																				<?php echo $history->message;?>
																			</p>
																			<div class="tl-footer clearfix">
																				<div class="tl-timeuser">
																					<?php echo date('d M,y H:i',strtotime($history->create_date));?>
																					<?php echo "&nbsp;&nbsp;&nbsp;".$timeline['user']->name; /*if($this->session->userdata['id']==$timeline->userID){ echo "you";} else { echo $timeline->name;}*/?>
																				</div>
																				<?php 
																					if(!empty($history->profile_pic)):
																				?>
																				<img src="<?php echo $history->profile_pic?>" alt="<?php echo $timeline['user']->name?>" width="28"/>
																				<?php
																					else:
																				?>
																				<img src="<?php echo $Layout->baseUrl?>public/upload/user.png" alt="<?php echo $timeline['user']->name?>" width="28" />
																				<?php
																					endif;
																				?>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
												<?php
														}
													} else {
												?>
												<p class='alert alert-info'>No activities found!</p>
												<?php
													}
												?>
											</div>
										</div>
									</td>
								
					<?php
							}
						}
					?>	
						</tr>
						</tbody>
					</table>
				</div>
            </div>
        </div>
    </div>
	</div>
</div>