<script type="text/javascript">
	jQuery(document).ready(function() {
        jQuery('#manageCustomerRequest').DataTable( {
            "paging": false,
			 "oLanguage": {
				"sEmptyTable":     "No new request"
			}
        });
		jQuery(".multi-select").multiSelect("refresh");jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>')
		jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Store Customer Request</li>");
    });
	
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<div id="request_panel" class='col-lg-12'>
			<table class='table' id="manageCustomerRequest" style='width:100%'>
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Phone Number</th>
						<th>Company Name</th>
						<th>Company Address</th>
						<th>Date</th>		
						<th>Action</th>		
					</tr>	
				</thead>
				<tbody>
					<?php 
						if(count($customer_request)>0){
							foreach($customer_request as $customer){
					?>
								<tr>
									<td><?php echo $customer->first_name;?></td>
									<td><?php echo $customer->last_name;?></td>
									<td><?php echo $customer->email;?></td>
									<td><?php echo $customer->phone_number;?></td>
									<td><?php echo $customer->company_name;?></td>
									<td><?php echo $customer->company_address;?></td>
									<td><?php echo date('M d, Y',strtotime($customer->create_date));?></td>
									<td><a href='javascript://' onclick="activateUser(<?php echo $customer->id;?>)"><i class="glyph-icon icon-check" title="" data-original-title=".icon-check"></i></a> &nbsp;&nbsp;&nbsp; <a href='javascript://' onclick="deleteCustomerRequest(<?php echo $customer->id;?>)"><i class="glyph-icon icon-remove" title="" data-original-title=".icon-check"></i></a></td>
								</tr>
					<?php
							}
						}
					?>
				</tbody>
			</table>
			</div>
			<div class="col-lg-12 hide" id="assignCompany">
				<h3 class="title-hero">Assign Company</h3>
				<?php echo form_open('general/add_new_customer',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'))?>
				<div class="row row-width">
					<div class="col-lg-6">
						<div class="form-group input-string-group" style="margin-left: 0;border-bottom:0px;">
		                    <label class="control-label">Type :</label>
		                    <select class='form-control' id="customerType" name="customer[type]">
								<option value='0'>Customer</option>
								<option value='1'>Broker</option>
							</select>
		                </div>
						<div class="form-group input-string-group" style="margin-left: 0;border-bottom:0px;">
		                    <label class="control-label">Company :</label>
		                    <select class='form-control' id="assignCompanyId" name="customer[company_id]">
								<?php foreach($company_list as $company):?>
								<option  value="<?php echo $company->id?>"><?php echo $company->company_name?></option>
								<?php endforeach;?>
							</select>
		                </div>
					</div>					
					<div class='col-lg-1 pull-left mrg20T'>
						<button type="submit" class='btn  btn-primary btn-block'>Save</button>
					</div>
				</div>
				<input type="hidden" name="customer[request]" id="customerRequest" value="0"/>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>

<script>
function activateUser(i){
	jQuery('#customerRequest').val(i);
	jQuery("#request_panel").removeClass("show").addClass("hide");
	jQuery("#assignCompany").removeClass("hide").addClass("show");
}
function deleteCustomerRequest(i){
	conf = confirm("Are you sure?");
	if(conf){
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+"customers/deleteCustomerRequest",data:{s:i},cache:false,success:function(res){
				if(res>0){
					window.location = window.location.href;
				} else{
					alert("Please try after sometime.");
				}
			}
		});
	}
}
function checkGeneralCompanyChange(a){jQuery("#companyGeneralSector").find("option").removeAttr("selected");jQuery(companyGeneralSector).find("option").removeAttr("SELECTED");if(a.val()>0){jQuery("#companyCompanyName").val("");if(jQuery("#assignCompanyId>option:selected").attr("data-b")!=undefined&&jQuery("#assignCompanyId>option:selected").attr("data-b")>0){jQuery("#companyGeneralSector").val(jQuery("#assignCompanyId>option:selected").attr("data-b"));checkGeneralSector(jQuery("#companyGeneralSector"))}}}
function checkGeneralCompanyName(a){if(a.val()!=""){jQuery("#assignCompanyId").find("option").removeAttr("selected");jQuery("#assignCompanyId").find("option").removeAttr("SELECTED");jQuery("#companyGeneralSector").find("option").removeAttr("selected");jQuery("#companyGeneralSector").find("option").removeAttr("SELECTED")}}
function checkGeneralSector(a){jQuery("#preferenceGeneralDepartments").multiSelect("destroy");jQuery("#preferenceGeneralDepartments").find("option").remove();if(a.val()>0){jQuery.ajax({type:"POST",url:__baseUrl+"customers/find_departments",data:{s:a.val()},cache:false,success:function(b){if(b!=""){_data=jQuery.parseJSON(b);if(_data.length>0){for(i=0;i<_data.length;i++){jQuery("#preferenceGeneralDepartments").append("<option value='"+_data[i].id+"'>"+_data[i].name+"</option>")}jQuery("#preferenceGeneralDepartments").multiSelect();jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');}}}});}}
</script>