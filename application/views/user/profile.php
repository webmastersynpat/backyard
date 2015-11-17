
<style>
   body {
	overflow: auto !important;
	overflow-x: hidden !important;
	min-width: 0;
	width: 100% !important;
}

    #page-content {
        padding: 0;
		background:#fff;
    }
	.form-horizontal>.form-group{
		margin-left:0px;margin-right:0px;
	}


	/** Tables */
	#timelineDataTable textarea.form-control {
		border: #dfe8f1 solid 1px !important;
		box-shadow: none;
		height: 25px;
		line-height: 1.4;
		padding: 3px;
		width: 100%;
	}


	.ui-datepicker {
		width: 200px;
	}


	/** Print styles */
	@media print {
		#frmUserTimeLog > .row,
		#frmUserTimeLog > .col-lg-12 {
			display: none !important;
		}
		.timeline-wrapper-inner {
			width: 100% !important;
		}
		.timeline-wrapper-inner + .pull-right {
			display: none !important;
		}

		.dataTables_scrollHead {
			display: none !important;
		}
		.dataTables_scrollBody {
			border-top: solid 1px #d1c8c8 !important;
			height: auto !important;
			overflow: visible !important;
		}
		.dataTables_scrollBody thead tr,
		.dataTables_scrollBody thead th {
			height: auto !important;
		}
		.dataTables_scrollBody thead th div {
			font-weight: bold !important;
			height: auto !important;
			padding-top: 5px !important;
			padding-bottom: 5px !important;
		}

		.dataTables_info {
			display: none !important;
		}
	}
</style>
<script>
function findUserTimeLog(o){
	if(o.val()!=""){
		/*document.getElementById('frmUserTimeLog').submit();*/
	}
}
var updateLogs=[];function saveFlagUpdate(a){if(jQuery.inArray(a,updateLogs)=="-1"){updateLogs.push(a);updateLogData(a)}else{updateLogData(a)}}function updateLogData(a){_aH=jQuery("#actualHrs"+a).val();_c=jQuery("#comment"+a).val();jQuery.ajax({url:"<?php echo $Layout->baseUrl;?>users/updateLogHr",type:"POST",data:{i:a,ah:_aH,c:_c},cache:false,success:function(b){if(b!="0"){jQuery("#"+a).find("td").eq(6).html(b)}}})}
jQuery(document).ready(function(){window.parent.jQuery("#user_mobile_number").val('<?php echo $userData->phone_number?>')})
</script>


<div class="col-xs-12">

 <div class="timeline-wrapper col-lg-12"  id='timeLineWrapper'> <div class="row"> <div class="col-lg-12"> <div> <b><big>Time Flow</big></b> </div> <div class="timeline-wrapper-inner" style='height:500px;width:80%;float:left'> 
 <?php 
	$getMyLogTime = getMyLogTime($user_id,$from,$to);
	echo form_open_multipart('users/profile',array('class'=>'form-horizontal','role'=>'form','id'=>'frmUserTimeLog'));
?>
 <?php if($this->session->userdata['type']==9):?>
									<div class="row form-flat"> 
										<div class="col-xs-2">
											<?php 
												$users = getAllUsersIncAdmin();
											?>
											<select class='form-control' name="profile[selected_user]" id="selectedUser" onchange="findUserTimeLog(jQuery(this))">
												<option>--Select User--</option> 
												<?php 
													if(count($users)>0){
														foreach($users as $user){
												?> 
												<option value="<?php echo $user->id?>" <?php if($user_id==$user->id):?> selected='selected' <?php endif;?>>
												<?php echo $user->name?>
												</option> 
												<?php
														}
													}
												?> 
											</select>
										</div>
										<div class="col-xs-2">
											<div class="form-group input-string-group" style="margin-left:10px;">
												<label for="profileInfoFrom" class="control-label">From:</label>
												<input type="text" value='<?php echo $from?>' name="profile[from]" id="profileInfoFrom" class="form-control is-date">
											</div>
										</div>
										<div class="col-xs-2">
											<div class="form-group input-string-group">
												<label for="profileInfoTo" class="control-label">To:</label>
												<input type="text" value='<?php echo $to?>' name="profile[to]" id="profileInfoTo" class="form-control is-date">
											</div>
										</div>
										<div class="col-xs-6">
											<button type="submit" class="btn btn-primary btn-mwidth">Search</button>
											<button class="btn btn-default btn-mwidth" onclick="return printTable()">Print</button>
										</div>
									</div>
									<?php endif;?>
									<div class="col-lg-12 mrg10T"> <label><strong>Total time spent in current month: <?php echo ($getMyLogTime['totalHoursCurrent']->totalHrsWorked!=null)?$getMyLogTime['totalHoursCurrent']->totalHrsWorked:'';?></strong></label> </div> <div class="col-lg-12 mrg10T"> <label><strong>Total time spend in previous month: <?php echo ($getMyLogTime['totalHours']->totalHrsWorked!=null)?$getMyLogTime['totalHours']->totalHrsWorked:'';?></strong></label> </div>
 
 <?php 
										
										if(count($getMyLogTime['all_work'])>0){
									?> <table id="timelineDataTable" class='table dataTable no-footer' style='width:100%!important'>
									<thead> 
										<tr>
											<th>Date</th>
											<th><div style="width:45px;">Start</div></th>
											<th><div style="width:45px;">End</div></th>
											<th><div style="width:65px;">Duration</div></th>
											<th><div style="width:100px;">Leads</div></th>
											<th><div style="width:90px;">Adjustments</div></th>
											<th><div style="width:80px;">Total Time</div></th>
											<th>Comment</th>
										</tr>
									</thead>
									<tbody> <?php
										for($i=0;$i<count($getMyLogTime['all_work']);$i++){
									?> <tr id="<?php echo $getMyLogTime['all_work'][$i]->id;?>"> <td><div style="white-space:nowrap;"><?php echo date('Y-m-d',strtotime($getMyLogTime['all_work'][$i]->login_date))?></div></td> <td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->login_date));?></td> <td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->logout_date));?></td> <td><?php
									$logoutDate = date('H:i',strtotime($getMyLogTime['all_work'][$i]->logout_date));
									if($logoutDate!='00:00'){echo $getMyLogTime['all_work'][$i]->hrsWorked;}
									?></td> <td> <?php 
													if((int)$getMyLogTime['all_work'][$i]->lead_id>0){
														$getLeadDetail = getLeadDetail($getMyLogTime['all_work'][$i]->lead_id);
														if(count($getLeadDetail)>0){
															echo $getLeadDetail->lead_name;
														}
													}
												?> </td> 
									<td>
									<select  id="actualHrs<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:73px' onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)">
									<option value="">-- Adjust. --</option>
									<option value="-00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:15")?'SELECTED="SELECTED"':'';?>>-00:15</option>
									<option value="-00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:30")?'SELECTED="SELECTED"':'';?>>-00:30</option>
									<option value="-00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:45")?'SELECTED="SELECTED"':'';?>>-00:45</option>
									<option value="-01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:00")?'SELECTED="SELECTED"':'';?>>-01:00</option>
									<option value="-01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:15")?'SELECTED="SELECTED"':'';?>>-01:15</option>
									<option value="-01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:30")?'SELECTED="SELECTED"':'';?>>-01:30</option>
									<option value="-01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:45")?'SELECTED="SELECTED"':'';?>>-01:45</option>
									<option value="-02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:00")?'SELECTED="SELECTED"':'';?>>-02:00</option>
									<option value="-02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:15")?'SELECTED="SELECTED"':'';?>>-02:15</option>
									<option value="-02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:30")?'SELECTED="SELECTED"':'';?>>-02:30</option>
									<option value="-02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:45")?'SELECTED="SELECTED"':'';?>>-02:45</option>
									<option value="-03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:00")?'SELECTED="SELECTED"':'';?>>-03:00</option>
									<option value="-03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:15")?'SELECTED="SELECTED"':'';?>>-03:15</option>
									<option value="-03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:30")?'SELECTED="SELECTED"':'';?>>-03:30</option>
									<option value="-03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:45")?'SELECTED="SELECTED"':'';?>>-03:45</option>
									<option value="-04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:00")?'SELECTED="SELECTED"':'';?>>-04:00</option>
									<option value="-04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:15")?'SELECTED="SELECTED"':'';?>>-04:15</option>
									<option value="-04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:30")?'SELECTED="SELECTED"':'';?>>-04:30</option>
									<option value="-04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:45")?'SELECTED="SELECTED"':'';?>>-04:45</option>
									<option value="-05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-05:00")?'SELECTED="SELECTED"':'';?>>-05:00</option>
									<option value="00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="00:15")?'SELECTED="SELECTED"':'';?>>00:15</option>
									<option value="00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="00:30")?'SELECTED="SELECTED"':'';?>>00:30</option>
									<option value="00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="00:45")?'SELECTED="SELECTED"':'';?>>00:45</option> 
									<option value="01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:00")?'SELECTED="SELECTED"':'';?>>01:00</option>
									<option value="01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:15")?'SELECTED="SELECTED"':'';?>>01:15</option>
									<option value="01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:30")?'SELECTED="SELECTED"':'';?>>01:30</option> 
									<option value="01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:45")?'SELECTED="SELECTED"':'';?>>01:45</option>
									<option value="02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:00")?'SELECTED="SELECTED"':'';?>>02:00</option> 
									<option value="02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:15")?'SELECTED="SELECTED"':'';?>>02:15</option>
									<option value="02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:30")?'SELECTED="SELECTED"':'';?>>02:30</option>
									<option value="02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:45")?'SELECTED="SELECTED"':'';?>>02:45</option>
									<option value="03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:00")?'SELECTED="SELECTED"':'';?>>03:00</option>
									<option value="03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:15")?'SELECTED="SELECTED"':'';?>>03:15</option>
									<option value="03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:30")?'SELECTED="SELECTED"':'';?>>03:30</option>
									<option value="03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:45")?'SELECTED="SELECTED"':'';?>>03:45</option>
									<option value="04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:00")?'SELECTED="SELECTED"':'';?>>04:00</option> 
									<option value="04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:15")?'SELECTED="SELECTED"':'';?>>04:15</option>
									<option value="04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:30")?'SELECTED="SELECTED"':'';?>>04:30</option>
									<option value="04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:45")?'SELECTED="SELECTED"':'';?>>04:45</option> 
									<option value="05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="05:00")?'SELECTED="SELECTED"':'';?>>05:00</option>
									</select> 
									</td> 
									<td> <?php
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
												?> </td>
												
									<td style='width:430px'><textarea class='form-control' onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)" placeholder="Comment" name="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" id="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:400px;width:100%;height:25px;border:0px'><?php echo $getMyLogTime['all_work'][$i]->comment?></textarea></td> </tr> <?php
										}
									?> </tbody> </table> 
<script>
	var timelineDataTable=$("#timelineDataTable").DataTable({
		// autoWidth:true,
		paging:false,
		searching:false,
		scrollY:'600px',
		order:[[0,"desc"]]
		// aoColumns: [
	 //      	{ "sWidth": "90px" },
	 //      	{ "sWidth": "55px" },
	 //      	{ "sWidth": "55px" },
	 //      	{ "sWidth": "80px" },
	 //      	{ "sWidth": "100px" },
	 //      	{ "sWidth": "80px" },
	 //      	{ "sWidth": "80px" }
	 //    ]
	});

	var _windowResizeTimeout = null;
	$(window).on('resize', function() {
		clearTimeout(_windowResizeTimeout);
		_windowResizeTimeout = setTimeout(function() {
			resizeTimelineTable();
		}, 200);
	});
	function resizeTimelineTable() {
		// console.log(new Date().getTime());
		$('.dataTables_scrollHeadInner thead th:first-child').trigger('click');
		setTimeout(function() {
			$('.dataTables_scrollHeadInner thead th:first-child').trigger('click');
		}, 100);
	}

	$('.is-date').datepicker({dateFormat:"yy-mm-dd"});


	function printTable() {
		window.print();
		return false;
	}

</script> 

									<?php
										}
									?> <div class="col-lg-12" style='border-top:1px solid #d1c8c8;width:99%'></div><?php echo form_close();?> </div> <div class='pull-right' style="width:17%;margin-top:8px"> <div class="row">
									<div class="mrg25T"> <b><big>Profile Detail:</big></b> </div> <div class="" style='' id="mainHolder">	 
		<div style='padding-left:5px;'>
			<?php 
				echo form_open_multipart('users/update',array('class'=>'form-horizontal form-flat','role'=>'form'));
			?>
			<div class="clearfix">
				<label class="control-label">Email:</label>
				<input type='email' readonly class='form-control input-string' name='user[email]' value='<?php echo $userData->email;?>'/> 
			</div>
			<div class="clearfix mrg10T">
				<label class="control-label">Phone Number:</label>
				<input type='text' class='form-control input-string' name='user[phone_number]' value='<?php echo $userData->phone_number;?>'/> 
			</div>
			<div class="clearfix mrg10T">
				<label class="control-label">New Password:</label>
				<input type='password' class='form-control input-string' name='user[password]'/> 
			</div>
			<div class="clearfix mrg10T">
				<label class="control-label">Confirm Password:</label>
				<input type='password' class='form-control input-string' name='user[confirm_password]'/> 
			</div>	
			<div class="form-group mrg10T">
				<label class='control-label'>Profile Pic:</label>				
				<input type='file' class='form-control' name='user[profile_pic]'/>
				<p class="help-text mrg5T">Please upload image size 80x80.</p>
				<?php 
					if(!empty($userData->profile_pic)){
				?>
						<img src='<?php echo $userData->profile_pic;?>' style="margin-top:5px"/>
				<?php
					}
				?>				
			</div>	
			<div class="form-group mrg15T">
				<!-- <label class='col-sm-2 control-label'></label> -->
				<div class='text-center'>
					<button name="btnSubmit" type='submit' class="btn btn-primary">Update profile</button>
				</div>
			</div>	
			<input type='hidden' name='user[token]' value='<?php echo $userData->id;?>' />
			<input type='hidden' name='user[old_pic]' value='<?php echo $userData->profile_pic;?>' />
			<?php echo form_close();?>
		</div>
	</div>
									 </div> </div> </div> </div>
									
									</div> </div>  


<div class="">
   
</div>