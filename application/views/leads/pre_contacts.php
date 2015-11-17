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
<div class='col-lg-12'>
<div class="row" style='margin-bottom:10px;'>
<?php 
	$companies = getAllCompanies();
?>
<div class='col-xs-12'>Select the contact, then select the company to be associated with (you may create a new contact using the next button) and click ADD Contact. <select class="" id="inviteeCompanyId" name="invitee[company_id]" class="form-control" style='width:150px;'><?php 												
												if(count($companies)>0){
													foreach($companies as $company){
											?> <option data-b='<?php echo $company->sectorID?>' data-bn='<?php echo $company->sectorName?>' value="<?php echo $company->id;?>"><?php echo $company->company_name;?></option> <?php
													}
												}
											?> </select> <a style='width:100px;display:inline-block;' href='javascript://' onclick="addPreToContact()" class='btn btn-primary btn-block mrg5R'>Add Contact</a> <a href='javascript://' onclick="openAddForm()" class='btn btn-primary btn-block mrg5R' style='width:100px;display:inline-block;'>Add Company</a> <a href='javascript://' onclick="deletePreContact()" class='btn btn-primary btn-block mrg5R' style='width:100px;display:inline-block;'>Delete</a>		</div>
		
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" >
	<thead>
		<tr>     
			<th>#</th>
			<th>First Name</th>  
			<th>Last Name</th>  
			<th>Email</th>  
			<th>Job Title</th>  
			<th>Company</th>  
			<th>Shared Con.</th>  
			<th>LinkedIn Url</th>  
		</tr>
	</thead>
	<tbody>
		<?php 
			if(count($pre_contacts)>0){
				foreach($pre_contacts as $lit){					
	?>
				<tr data-i="<?php echo $lit->id;?>">
					<td data-first='<?php echo $lit->first_name?>' data-last='<?php echo $lit->last_name?>' data-title='<?php echo $lit->job_title?>' data-company='<?php echo $lit->company_name?>' data-email='<?php echo $lit->email?>' data-shared='<?php echo $lit->c_c?>' data-url='<?php echo $lit->profile_url?>'><input name='contact_id' value='<?php echo $lit->id;?>' type="radio" /></td>
					<td><?php echo $lit->first_name?></td>
					<td><?php echo $lit->last_name?></td>
					<td><?php echo $lit->email?></td>
					<td><?php echo $lit->job_title?></td>
					<td><?php echo $lit->company_name?></td>
					<td><?php echo $lit->c_c?></td>
					<td><a href='<?php echo $lit->profile_url?>' target='_BLANK'><?php echo $lit->profile_url?></a></td>
				</tr>			
	<?php
				}
			}
		?>
	</tbody>
</table>
</div>
<script>
	var ___table ;
	jQuery(document).ready(function(){
		___table = $('#db_from_litigation')
			.DataTable({								
				"searching":false,
				"autoWidth": true,
				"paging": false,
				// "sScrollY": _h+"px",
				"sScrollY": 100,
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
	});


	window.resizeDataTable = function(height) {
		$('#db_from_litigation_wrapper .dataTables_scrollBody').height(height - 60);
	}

	$(function() {
		parent.open_prefined_listResize();
	})


jQuery(document).ready(function(){
	jQuery('#db_from_litigation').find("tbody").find("tr>td").dblclick(function(e){
		e.stopPropagation();
        var currentEle = $(this);
        var value = $(this).html();
        updateVal(currentEle, value);
	});
});
function updateVal(currentEle, value) {
	jQuery('#db_from_litigation').find("tbody").find("tr>td").find('.thVal').each(function(){
		$(this).parent().html($(this).val().trim());	
		$(".thVal").remove();
	});
    $(currentEle).html('<input class="thVal" type="text" value="' + value + '" />');
    $(".thVal").focus();
    $(".thVal").keyup(function (event) {
        if (event.keyCode == 13) {
			_pObject = $(this).parent().parent();
			$(this).parent().html($(this).val().trim());			
            $(".thVal").remove();
			jQuery.ajax({
				type:'POST',
				url:__baseUrl+'customers/update_pre_contacts',
				data:{id:_pObject.attr('data-i'),first_name:_pObject.find('td').eq(1).html(),last_name:_pObject.find('td').eq(2).html(),email:_pObject.find('td').eq(3).html(),job_title:_pObject.find('td').eq(4).html(),company:_pObject.find('td').eq(5).html(),c_c:_pObject.find('td').eq(6).html(),profile_url:_pObject.find('td').eq(7).html()},
				cache:false,
				success:function(res){
					
				}
			});
        }
    });
}
function refreshCompanyList(){jQuery.ajax({type:'POST',url:__baseUrl+'users/company_list',cache:false,success:function(d){if(d!=""){_d = jQuery.parseJSON(d);if(_d.length>0){_option='';for(i=0;i<_d.length;i++){_option+='<option data-b="'+_d[i].sectorID+'" data-bn="'+_d[i].sectorName+'" value="'+_d[i].id+'">'+_d[i].company_name+'</option>';}if(_option!=""){jQuery("#inviteeCompanyId").html(_option);}}}}});}
function openAddForm(){
	window.parent.jQuery("#ccompanyFormSubmit").get(0).reset();
	window.parent.jQuery("#ccompanyFormSubmit").find("#companyId").val("");
	window.parent.jQuery("#ccompanyFormSubmit").find("#marSector").find("option").removeAttr("selected");
	window.parent.jQuery("#addCCompanyForm").css('z-index','9999');
	window.parent.jQuery(".multi-select").multiSelect('refresh');
	window.parent.jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
	window.parent.jQuery("#company_users_show_table").empty();
	window.parent.jQuery("#addCCompanyForm").modal("show");
}
function addPreToContact(){
	var iframe = window.parent.$('#companyFormIframe').contents();
	/*if(iframe.find('input[name="vendor_select"]').is(':checked')){*/
	if(jQuery('#inviteeCompanyId').val()!=""){
		company_id = jQuery('#inviteeCompanyId').val();
		if(jQuery('input[name="contact_id"]').is(':checked')){
			inputObject = jQuery('input[name="contact_id"]:checked');
			firstName = inputObject.parent().attr('data-first');
			lastName = inputObject.parent().attr('data-last');
			jobTitle = inputObject.parent().attr('data-title');
			email = inputObject.parent().attr('data-email');
			linked_in = inputObject.parent().attr('data-url');
			 var invitee = {};
			 invitee['job_title'] = jobTitle;
			 invitee['first_name'] = firstName;
			 invitee['last_name'] = lastName;
			 invitee['telephone'] = '';
			 invitee['phone'] = '';
			 invitee['email'] = email;
			 invitee['web_address'] = '';
			 invitee['linkedin_url'] = linked_in;
			 invitee['street']= '';
			 invitee['city'] = '';
			 invitee['state'] = '';
			 invitee['zip'] = '';
			 invitee['country'] = '';
			 invitee['note'] = '';
			 invitee['company_id']=company_id;
			 invitee['id']=0;
			jQuery.ajax({type:"POST",url:__baseUrl+"opportunity/add_contact",data:{invitee:invitee},cache:false,success:function(b){if(b>0){removePreContact(inputObject.val());inputObject.parent().parent().remove();document.getElementById("contactFormIframe").contentWindow.location.reload();alert("Contact added.")}}});
		} else {
			alert("Please select contact to move.");
		}
	} else {
		alert("Please select a company to move contact.");
	}
}
function deletePreContact(){
	if(jQuery('input[name="contact_id"]').is(':checked')){
		removePreContact(jQuery('input[name="contact_id"]:checked').val());
		jQuery('input[name="contact_id"]:checked').parent().parent().remove();
	} else {
		alert("Please select contact for delete");
	}
}
function removePreContact(preContactID){
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'leads/remove_precontacts',
		data:{id:preContactID},
		success:function(res){
			
		}
	});
}
</script>