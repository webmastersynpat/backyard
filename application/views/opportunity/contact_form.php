<style>
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

	#datatable-contacts-sharing_wrapper .dataTables_filter label {
		padding-top: 2px;
	}
	#datatable-contacts-sharing_wrapper .dataTables_filter input {
	    box-shadow: none;
	    float: right;
	    height: 24px;
	    margin: -4px 0 2px 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}

	#datatable-contacts-sharing_wrapper .dataTables_scroll {
		background: none;
		clear: both;
	}
	#datatable-contacts-sharing_wrapper .dataTables_info {
		display: none;
	}
</style>
<script>
	var ___table ;
	jQuery(document).ready(function(){
		jQuery(".multi-select").multiSelect();
		jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
		___table = $('#datatable-contacts-sharing')
			.DataTable({								
				"searching":true,
				"autoWidth": true,
				"paging": false,
				"sScrollY": "780px",
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
	});
</script>
<style>
div.dataTables_filter label{
	float:left;
}
</style>
<div class="row" style='width:100%;'>	
		<div class="col-xs-12" style='width:100%;'>		
		<table class="table" class="table" id="datatable-contacts-sharing">
			<thead>
				<tr>
					<th>#</th>
					<th style="width:120px;">Name</th>
					<th style="width:120px;">Job Title</th>
					<th style="width:120px;">Company</th>
					<th style="width:120px;">Phone Number</th>
					<th style="width:120px;">Sectors</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(count($contacts)>0){
						foreach($contacts as $contact){
							$checked = "";
							if(count($lead_data)>0){
								switch((int)$type){
									case 1:
										if(!empty($lead_data->plantiffs_name)){
											if($contact->id==$lead_data->plantiffs_name){
												$checked = "CHECKED='CHECKED'";
											}
										}
									break;
									case 2:
										if(!empty($lead_data->broker)){
											if($contact->id==$lead_data->broker){
												$checked = "CHECKED='CHECKED'";
											}
										}
									break;
									case 3:
										if(!empty($lead_data->broker_person)){
											if($contact->id==$lead_data->broker_person){
												$checked = "CHECKED='CHECKED'";
											}
										}
									break;
									case 4:
										if(!empty($lead_data->person_title_1)){
											if($contact->id==$lead_data->person_title_1){
												$checked = "CHECKED='CHECKED'";
											}
										}
									break;
									case 5:
										if(!empty($lead_data->person_title_2)){
											if($contact->id==$lead_data->person_title_2){
												$checked = "CHECKED='CHECKED'";
											}
										}
									break;
								}
							}
				?>
				<tr>
					<td style="width:10px;"><input type="radio" name="vendor_select" data-name="<?php echo $contact->first_name." ".$contact->last_name;?>" data-title="<?php echo $contact->job_title; ?>" data-company-id="<?php echo $contact->companyID;?>" data-company="<?php echo $contact->company_name;?>" value="<?php echo $contact->id;?>" onchange="getVendor(jQuery(this),<?php echo $type;?>,'<?php echo $parentElement?>');" <?php echo $checked?> /></td>
					<td style="width:120px;"><?php echo (!empty($contact->first_name))?$contact->first_name." ".$contact->last_name:'';?></td>
					<td style="width:120px;"><?php echo (string)$contact->job_title; ?></td>
					<td style="width:120px;"><?php echo (string)$contact->company_name;?></td>
					<td style="width:120px;"><?php 
						$d=0;
						if(!empty($contact->phone)){
							echo $contact->phone;
							$d =1;
						}
						if(!empty($contact->telephone)){
							if($d==1){
								echo ",<br/>";
							}
							echo $contact->telephone;
						}
					?></td>
					<td style="width:120px;"><?php echo $contact->sectorName;?></td>			
				</tr>
				<?php
							
						}
					}
				?>
			</tbody>
		</table>
	</div>
</div>

<script>
	__backSpace = "";
	
	function getVendor(v,type,parentElement){		
		switch(parentElement){				
			case 'from_regular':
				switch(parseInt(type)){
					case 1:
						window.parent.$("#marketOwner").val(v.attr('data-company-id'));
						/*window.parent.$("#"+parentElement).find("#showSellerName").html(v.attr('data-company'));*/
						window.parent.$("#marketSellerContact").val(v.attr('data-company'));
					break;
					case 2:
					case 3:
						window.parent.$("#marketBroker").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showBrokerFirm").html(v.attr('data-company'));*/
						window.parent.$("#marketBrokerContact").val(v.attr('data-company'));					
						window.parent.$("#marketBrokerPerson").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showBrokerPerson").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#marketBrokerPersonContact").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
					case 4:
						window.parent.$("#marketPersonTitle1").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showNameFirst").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#marketPersonName1").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
					case 5:
						window.parent.$("#marketPersonTitle2").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showNameSecond").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#marketPersonName2").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
				}
			break;
			case 'from_nonacquistion':
				switch(parseInt(type)){
					case 1:
						window.parent.$("#acquisitionOwner").val(v.attr('data-company-id'));
						/*window.parent.$("#"+parentElement).find("#showSellerName").html(v.attr('data-company'));*/
						window.parent.$("#acquisitionSellerContact").val(v.attr('data-company'));
					break;
					case 2:
					case 3:
						window.parent.$("#acquisitionBroker").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showBrokerFirm").html(v.attr('data-company'));*/
						window.parent.$("#acquisitionBrokerContact").val(v.attr('data-company'));				
						window.parent.$("#acquisitionBrokerPerson").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showBrokerPerson").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#acquisitionBrokerPersonContact").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
					case 4:
						window.parent.$("#acquisitionPersonTitle1").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showNameFirst").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#acquisitionPersonName1").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
					case 5:
						window.parent.$("#acquisitionPersonTitle2").val(v.val());
						/*window.parent.$("#"+parentElement).find("#showNameSecond").html(v.attr('data-name')+","+v.attr('data-title'));*/
						window.parent.$("#acquisitionPersonName2").val(v.attr('data-name')+","+v.attr('data-title'));
					break;
				}
			break;
		}
		
	}
	
	

	function refreshContactTableHeader() {
		$('#datatable-contacts-sharing_wrapper .sorting_asc, #datatable-contacts-sharing_wrapper .sorting_desc').trigger('click');
		setTimeout(function() {
			$('#datatable-contacts-sharing_wrapper .sorting_asc, #datatable-contacts-sharing_wrapper .sorting_desc').trigger('click');
		}, 300);
	}
</script>