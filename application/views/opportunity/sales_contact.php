<style>
	body { min-width: 0; }
	#page-content { background: #ffffff; }

	#datatable-contacts_wrapper {
		margin-top: 3px;
	}
	#datatable-contacts_wrapper .dataTables_filter label {
		padding-top: 2px;
	}
	#datatable-contacts_wrapper .dataTables_filter input {
	    box-shadow: none;
	    float: right;
	    height: 24px;
	    margin: -4px 0 2px 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}

	#datatable-contacts_wrapper .dataTables_scroll {
		background: none;
		clear: both;
	}
	#datatable-contacts_wrapper .dataTables_info {
		display: none;
	}
</style>
<script>
	var ___table ;
	jQuery(document).ready(function(){
		_h = window.parent.$(window).height() - 120;
		___table = $('#datatable-contacts')
			.DataTable({
				"searching":true,
				"autoWidth": true,
				"paging": false,
				// "sScrollY": _h+"px",
				"sScrollY": 100,
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
	});


	window.resizeDataTable = function(height) {
		$('#datatable-contacts_wrapper .dataTables_scrollBody').height(height - 35);
	}

	$(function() {
		parent.open_sales_listResize();
	})

</script>

<div class="row">
	<div class="col-xs-12" style="overflow-x: auto; overflow-y: auto;">
		<!--script>
			var ___table = "";
			jQuery(document).ready(function(){
				___table =jQuery("#datatable-contacts").DataTable( {
							"searching":true,
							"scrollY": true,
							"scrollX": true,
							"scrollCollapse": true,
							"paging": false
						});
			});
			
		</script-->
		<table class="table" id="datatable-contacts" width="99% !important">
			<thead>
				<tr>
					<th style="width:30px;"><div class="text-center">x</div></th>
					<th>Selected</th>
					<th>Name</th>
					<th>No Of Users</th>
					<th>Sector</th>
					<th>Department</th>
					<th>Phone</th>
				</tr>
			</thead> 
			<tbody>
				<?php 
						if(count($companies)>0):					
							foreach($companies as $invitee){
								$selected="";
								if(count($selected_sales_companies)){
									foreach($selected_sales_companies as $sCompany){
										if($sCompany->id==$invitee->id){
											$selected="style='color:red'";
										}
									}
								}
					?>
							<tr id="<?php echo $invitee->id;?>" <?php echo $selected;?>>
								<td style="width:30px;"><input type="radio"  name="invite[contact_id]" value="<?php echo $invitee->id;?>" onclick='getDataContact(jQuery(this),<?php echo $invitee->id;?>,"<?php echo $invitee->company_name;?>")'/></td>
								<td><?php if(!empty($selected)):?>X<?php endif;?></td>
								<td><?php echo $invitee->company_name;?></td>
								<td><?php echo count($invitee->company_users); ?></td>
								<td><?php echo $invitee->sectorName;?></td>
								<td>
								<?php 
									$departments = findMyPreferenceWithName($invitee->id);
									if(count($departments)>0){
										$d=0;
										foreach($departments as $deptt){
											echo $deptt->name;if($d<count($departments)-1){echo ", ";}
											$d++;
										}
									}
								?></td>
								<td><?php echo $invitee->telephone;?></td>
							</tr>
					<?php  } endif;?>
			</tbody>
		</table>
	</div>
</div>
<script>
	function getDataContact(o,i,cName){
		_c = jQuery("#"+i).find("td").eq(1).text();
		_p = jQuery("#"+i).find("td").eq(2).text();
		_mainActivity = window.parent.jQuery('#activityMainType').val();
		_targetBody = "";
		if(_mainActivity==1){
			sendCompanyToInvitees(i);
			_targetBody = "activityTable";
		} else if(_mainActivity==2) {
			_targetBody = "aquisitionTable";
			sendCompanyToAcquisition(i);
		}
		
		_cID = i;
		if(_targetBody!="")
		jQuery.ajax({
			url:__baseUrl+'users/contacts_in_c',
			type:'POST',
			data:{c:i},
			cache:false,
			success:function(data){				
				people = jQuery.parseJSON(data);
				_tr="";
				if(people.length>0){
					for(p=0;p<people.length;p++){_name=people[p].first_name+" "+people[p].last_name;_phone=people[p].phone;if(_phone==""){_phone=people[p].telephone;if(_phone!=''){_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';}}else{if(people[p].telephone!=""){_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';_phone+='<br/><a href="javascript://" onclick=\'callFromLandline("'+people[p].telephone+'")\'>'+people[p].telephone+'</a>';} else {_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';}}_sLinks='';if(people[p].email!=''){_sLinks='<a href="javascript://" onclick="flagSaleActivity(1,jQuery(this))" data-attr-em="'+people[p].email+'" class="sales-activity-icon"><i class="glyph-icon icon-envelope-square"></i></a>';}if(people[p].linkedin_url!=''){_sLinks +='&nbsp;&nbsp;<a href="javascript://" onclick="flagSaleActivity(2,jQuery(this))" data-attr-linkedin="'+people[p].linkedin_url+'" class="sales-activity-icon"><i class="glyph-icon icon-linkedin"></i></a>';}_tr+="<tr class='salesFDroppable' data-c='"+_cID+"' data-p='"+people[p].id+"'><td style='border-left:0px; width:65px;'><input name='sales_person[]' class='sales-activity-checkbox' data-attr-em='"+people[p].email+"' data-attr-linkedin='"+people[p].linkedin_url+"' data-attr-name='"+_name+"' data-attr-c-name='"+cName+"'  type='checkbox' value='"+people[p].id+"'/>"+_sLinks+"</td><td>"+_name+"</td><td>"+people[p].job_title+"</td><td style=''>"+_phone+"</td></tr>";}
				}
				window.parent.jQuery("#"+_targetBody).find("tbody.main_active").append('<tr class="master "  data-c="'+_cID+'"><td style="width:65px;"><a href="javascript://" onclick="deleteSalesInvitedC('+_cID+')"><i class="glyph-icon"><img src="'+__baseUrl+'public/images/discard.png" style="opacity:0.55"></i></a></td><td style="width:234px;"><a href="javascript://" class="showActivity"><i class="glyph-icon icon-play" title="Contacts" style="" ></i></a>&nbsp;<a href="javascript://" class="showActivity">'+cName+'</a></td>'+
												'<td></td>'+
												'<td></td>'+
												'<td></td>'+
												'<td></td>'+
											'</tr>'+
											'<tr style="display:none;">'+
												'<td colspan="2"><table class="table table-bordered"><thead><tr><th>#</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>'+_tr+'</tbody></table></td>'+
												'<td colspan="4">'+
													'<table class="table" style="border:0px;">'+
													'<tbody>'+
															'<tr>'+
																'<td style="width: 100px;"></td>'+
																'<td style="width: 110px;"></td>'+
																'<td style="width: 120px;"></td>'+
																'<td style="border-right:0px; width: 400px;"></td>'+
															'</tr>'+
														'</tbody>'+
													'</table>'+
												'</td>'+
											'</tr>');
		window.parent.toggleCompanySales();
			}
		});		
		
	}
	
	function sendCompanyToInvitees(i){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>opportunity/invite_company',
			data:{company:i,l:<?php echo $lead_id?>},
			cache:false,
			success:function(){
				
			}
		})
	}
	function sendCompanyToAcquisition(i){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>opportunity/acquisition_company',
			data:{company:i,l:<?php echo $lead_id?>},
			cache:false,
			success:function(){
				
			}
		})
	}
</script>