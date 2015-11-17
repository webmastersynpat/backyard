<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>User Timeline TableView</li>");
});
</script>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel dashboard-box">
			<div class="panel-body">
				<div class="example-box-wrapper">
					<div class="row">
						<div class="col-lg-12">						
							<?php echo form_open('general/user_timeline_table',array('class'=>"form-horizontal form-flat", 'style'=>'margin-bottom: 0;'));?>
								<div class="row row-width">
									<div class="col-width" style="width: 20%;">
										<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
											<label class="control-label">User:</label>
												<select name="general[user]" id="generalUser" class='form-control' required="required" style="width:250px ">
												<option value="">-- Select User</option>
											<?php  
												if(count($users)>0):
													foreach($users as $user):
														$checked="";
														if($viewUserID==$user->id){
															$checked='SELECTED="SELECTED"';
														}
											?>
											
												
												<option value="<?php echo $user->id;?>" <?php echo $checked;?> ><?php echo $user->name;?></option>
										 <?php 		endforeach;
												endif;
										?>
											</select>
										</div>
										<div class="mrg15T">
											<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
										</div>
									</div>
								</div>
							<?php echo form_close();?>
						</div>
						<div class="col-lg-12">
							<?php if($viewUserID>0):?>
							<div class="timeline-wrapper-inner mrg20T" style='height:500px;width:100%;float:left'>
									<?php 										
										$getMyLogTime = getMyLogTime($viewUserID);
										if(count($getMyLogTime['all_work'])>0){
									?>
										<table id="timelineDataTableView" class='table dataTable no-footer' style='width:100% !important;'>
										<thead>
											<tr>
												<th>Date</th> 
												<th>Start</th>
												<th>End</th>
												<th>Duration</th>
												<th>Leads</th>
												<th>Adjustments</th>
												<th>Total Time</th>
												<th>Comment</th>
											</tr>
										</thead>
										<tbody>
									<?php
										for($i=0;$i<count($getMyLogTime['all_work']);$i++){
									?>
										<tr id="<?php echo $getMyLogTime['all_work'][$i]->id;?>">
											<td><?php echo date('Y-m-d',strtotime($getMyLogTime['all_work'][$i]->login_date))?></td>
											<td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->login_date));?></td>
											<td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->logout_date));?></td>
											<td><?php echo $getMyLogTime['all_work'][$i]->hrsWorked?></td>
											<td>
												<?php 
													if((int)$getMyLogTime['all_work'][$i]->lead_id>0){
														$getLeadDetail = getLeadDetail($getMyLogTime['all_work'][$i]->lead_id);
														if(count($getLeadDetail)>0){
															echo $getLeadDetail->lead_name;
														}
													}
												?>
											</td>
											<td><?php echo $getMyLogTime['all_work'][$i]->actual_hrs;?>
											</td>
											<td>
												<?php
													$duration = $getMyLogTime['all_work'][$i]->hrsWorked;
													$adjustedlHrs = $getMyLogTime['all_work'][$i]->actual_hrs;
													if(!empty($adjustedlHrs)){
														$adjustedlHrs = $adjustedlHrs.":00";
													}
													$totalHrs = $duration;
													if($duration!='' && $duration!='00:00:00'){
														$d = strtotime($duration);
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("-".$h." hour  -".$m." minutes",$d));
														} else if($lengthOfAdjustedHrs==8){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("+".$h." hour  +".$m." minutes",$d));
														}
													} else{
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9 || $lengthOfAdjustedHrs==8){
															$totalHrs = $adjustedlHrs;
														}
													}
													echo $totalHrs;
												?>
											</td>
											<td style='width:430px;'><?php echo $getMyLogTime['all_work'][$i]->comment?></td>
										</tr>
									<?php
										}
									?>
										</tbody>
										</table>

											
										<script>
											var timelineDataTableView = $('#timelineDataTableView').DataTable({
												"autoWidth": true,
    											"paging": false,
												"searching":false,
    											"scrollY": 400,
												"order": [[ 0, "desc" ]]
											});
										</script>
									<?php
										}
									?>
									<div class="col-lg-12" style='border-top:1px solid #d1c8c8;width:99%'></div>
								</div>
								<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>