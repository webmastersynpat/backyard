<style>
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

	#datatable-company-sharing_wrapper .dataTables_filter label {
		padding-top: 2px;
	}
	#datatable-company-sharing_wrapper .dataTables_filter input {
	    box-shadow: none;
	    float: right;
	    height: 24px;
	    margin: -4px 0 2px 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}

	#datatable-company-sharing_wrapper .dataTables_scroll {
		background: none;
		clear: both;
	}
	#datatable-company-sharing_wrapper .dataTables_info {
		display: none;
	}


	/** Modal c users */
	#modal_c_users {
	    left: 50%;
	    margin-left: -35%;
	    margin-top: 0 !important;
	    right: auto;
	    top: 77px;
	    width: 70% !important;
	}
	#modal_c_users .modal-dialog {
		width: 100%;
	}

	/** Web address */
	.web-address {
		overflow-x: hidden;
	}
	.web-address span {
		display: block;
		-ms-word-break: break-all;
    	word-break: break-all;
     	word-break: break-word;
		-webkit-hyphens: auto;
   		-moz-hyphens: auto;
        hyphens: auto;
	}

</style>
<script>
	var ___table ;
	jQuery(document).ready(function(){
		_h = window.parent.$(window).height() - 120;
		___table = $('#datatable-company-sharing')
			.DataTable({								
				"searching":true,
				// "autoWidth": true,
				"paging": false,
				// "sScrollY": _h+"px",
				"sScrollY": 100,
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
			jQuery('.web-address').click(function(){
				href = jQuery(this).text();
				if(href.indexOf('http')>=0){
					window.open(href,"_BLANK");
				} else {
					window.open('http://'+href,"_BLANK");
				}
				
			});
	});


	window.resizeDataTable = function(height) {
		$('#datatable-company-sharing_wrapper .dataTables_scrollBody').height(height - 60);
	}

	$(function() {
		window.parent.open_ccompany_listResize();
	})
	function findMyPeople(o){
		/*jQuery('.companyUsers').removeClass('show').addClass('hide');
		jQuery("#company-"+o.attr('data-id')).removeClass('hide').addClass('show');*/
		jQuery("#cUsers").html(window.cUser[o.attr('data-id')]);

		jQuery("#modal_c_users").modal("show");
	}
	window.cUser = [];
	function addCompaniesBulk(){
		if(jQuery("#bulk_company").hasClass('hide')){
			jQuery("#bulk_company").removeClass('hide').addClass('show');
			jQuery("#datatable-company-sharing_wrapper").addClass('hide').removeClass('show');
		} else {
			jQuery("#bulk_company").addClass('hide').removeClass('show');
			jQuery("#datatable-company-sharing_wrapper").removeClass('hide').addClass('show');
		}
	}
	function addCompanyInBulk(){
		jQuery("#buttonADDBulk").addClass('hide').removeClass("show");
		jQuery("#addContactTask").removeClass('hide').addClass("show");
		frm = jQuery("#addCompanyBulk");
		jQuery.ajax({
			url:frm.attr("action"),
			type:'POST',
			data:frm.serializeArray(),
			cache:false,
			success:function(data){
				if(data>0){
					window.parent.document.getElementById("companyFormIframe").contentWindow.location.reload();
				} else{
					alert('Please try after sometime.');
					jQuery("#buttonADDBulk").removeClass('hide').addClass("show");
					jQuery("#addContactTask").addClass('hide').removeClass("show");
				}
			}
		});
	}
</script>
<style>
div.dataTables_filter label{
	/*float:left;*/
}
</style>
<div class="row">	
	<div class="col-xs-12">		
		<div class="row">
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="openAddForm()" class='btn btn-primary btn-block mrg5R'>Add Company</a></div>
		<!--<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="editGoogleContact()" class='btn btn-primary btn-block mrg5R'>Edit</a></div>-->
		<?php if($this->session->userdata['type']=='9'):?>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="deleteGoogleContact()" class='btn btn-primary btn-block'>Delete</a>		</div>
		<?php endif;?>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="addCompaniesBulk()" class='btn btn-primary btn-block'>Add Multiple Companies</a>		</div>
		<div class="col-xs-2" style=''><a style='' href='http://synpatnew.com/connects/search.php' target='_BLANK' class='btn btn-primary btn-block'>Scraper</a>		</div>
		</div>
		<table class="table mrg10T" class="table" id="datatable-company-sharing">
			<thead> 
				<tr>
					<th style="width:40px;">#</th>
					<th style="width:100px;">Company</th>
					<th style="width:60px;"># of Users</th>
					<th style="width:120px;">Web address</th>
					<th style="width:120px;">Sectors</th>
					<th style="width:120px;">Departments</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(count($companies)>0){
						foreach($companies as $contact){
							
				?>
				<tr>
					<td style="width:40px;"><!--<input type="radio" class='pull-left' name="vendor_select" data-name="<?php echo $contact->company_name;?>" value="<?php echo $contact->id;?>"/>--> <input type="checkbox" class='pull-left' name="vendor_select_bulk[]" data-name="<?php echo $contact->company_name;?>" value="<?php echo $contact->id;?>"/></td>
					<td style="width:100px;"><a href='javascript://' onclick='findMyPeople(jQuery(this));' data-id='<?php echo $contact->id;?>'><i class="glyph-icon icon-play" title="Contacts" style=""></i></a> <a href='jsvscript://' onclick="editGoogleContact(<?php echo $contact->id;?>)"><?php echo $contact->company_name;?></a> <?php if($contact->userCount>0):?><i class="glyph-icon icon-sitemap tooltip-button" title="" data-placement="bottom" data-original-title="Member"></i><?php endif;?></td>
					<td style="width:60px;"><?php echo count($contact->company_users); ?></td>
					<td style="width:120px;"><div class="web-address"><span><a href='javascript://'><?php echo (string)$contact->web_address;?></a></span></div></td>
					<td style="width:120px;"><?php echo $contact->sectorName;?></td>				
					<td style="width:120px;">
						<?php 
									$departments = findMyPreferenceWithName($contact->id);
									if(count($departments)>0){
										$d=0;
										foreach($departments as $deptt){
											echo $deptt->name;if($d<count($departments)-1){echo ", ";}
											$d++;
										}
									}
								?>
					</td>				
				</tr>
					<script>
						html = "<table class='table'><thead><tr><th>Name</th><th>Work Phone</th><th>Mobile Phone</th></tr></thead><tbody>";
								<?php 
									if(count($contact->company_users)>0){
										for($i=0;$i<count($contact->company_users);$i++){
								?>
									html+="<tr>"+
										"<td><a href='javascript://' onclick='window.parent.editContact(<?php echo $contact->company_users[$i]->id;?>);'><?php $string = str_replace('"','',$contact->company_users[$i]->name);$string = str_replace('"','',$string); echo $string;?></a></td>"+
										"<td><a href='javascript://' onclick='callFromLandline(\"<?php echo $contact->company_users[$i]->phone;?>\")'><?php echo $contact->company_users[$i]->phone;?></a></td>"+
										"<td><a href='javascript://' onclick='callFromLandline(\"<?php echo $contact->company_users[$i]->telephone;?>\")'><?php echo $contact->company_users[$i]->telephone;?></a></td>"+
									"</tr>"; 
								<?php
										}
									}
								?>
							html+="</tbody></table>";
						window.cUser[<?php echo $contact->id;?>] = html;
					</script>
				<?php
							
						}
					}
				?>
			</tbody>
		</table>
		<div class='col-lg-12 hide' id='bulk_company'>
			<div class="col-xs-12">
				<?php echo form_open('opportunity/addCompanyBulk',array('id'=>'addCompanyBulk','class'=>'form-flat'));?>
				<div class="row"> <div class="col-xs-12"> <div class="form-group input-string-group"> <label class="control-label">List of COmpanies:</label> <textarea name="company[list]" id="companyList" class="form-control" style="width: 692px;height:400px; text-align: left;"></textarea> </div> </div> </div>
				<div class="mrg5T"> <button type="button" onclick="addCompanyInBulk()" id="buttonADDBulk" class="btn btn-primary btn-mwidth">Save</button> <img src="http://backyard.synpat.com/public/images/ajax-loader.gif" class='hide' alt="" id="addContactTask" > </div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-opened-header fade" id="modal_c_users" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:100%;z-index:999999">
	<div class="modal-dialog" style=''>
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-9px;margin-right:-5px;float:left;"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div style='max-height:300px;overflow-y:scroll' id="cUsers"></div>
			</div>
		<div class="modal-footer"></div>
		</div>
	</div>
</div>
<script> 
	__backSpace = "";
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
	function deleteGoogleContact(){
		if(jQuery("input[name='vendor_select']").is(":checked")){
			contactID = jQuery("input[name='vendor_select']:checked").val();
			res = confirm("Are you sure?");
			if(res){
				jQuery.ajax({
					type:'POST',
					url:'<?php echo $this->config->base_url();?>opportunity/deleteCompany',
					data:{delete_link:contactID},
					cache:false,
					success:function(data){
						window.location = window.location.href;						
					}
				});
			}
		} else {
			if(jQuery("input[name='vendor_select_bulk[]']:checked").length>0){
				contactID = "";
				jQuery("input[name='vendor_select_bulk[]']:checked").each(function(){
					contactID += jQuery(this).val()+',';
				});
				contactID =contactID.substring(0,contactID.length-1);
				res = confirm("Are you sure?");
				if(res){
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $this->config->base_url();?>opportunity/deleteCompanyInBulk',
						data:{delete_link:contactID},
						cache:false,
						success:function(data){
							window.location = window.location.href;						
						}
					});
				}
			} else {
				alert("Please select contact first");
			}
		}
	}
	
	function editGoogleContact(contactID){
		if(contactID>0){
			jQuery("#new_company").removeClass("show").addClass("hide");
			/*contactID = jQuery("input[name='vendor_select']:checked").val();*/
			jQuery.ajax({
			type:'POST',
			url:'<?php echo $this->config->base_url();?>opportunity/findCompany',
			data:{edit_link:contactID},
			cache:false,
			success:function(data){
				_data = jQuery.parseJSON(data);
				__backSpace = _data;
				if(typeof(_data.company_name)!="undefined"){
					window.parent.jQuery("#company_users_show_table").empty();
					window.parent.jQuery("#companyJobTitle").val(_data.company_name);
					window.parent.jQuery("#companyTelephone").val(_data.telephone);
					window.parent.jQuery("#companyEmail").val(_data.email);
					window.parent.jQuery("#companyWebAddress").val(_data.web_address);	
					window.parent.jQuery("#companyStreet").val(_data.street);
					window.parent.jQuery("#companyStreet").val(_data.city);
					window.parent.jQuery("#companyState").val(_data.state);
					window.parent.jQuery("#companyZip").val(_data.zip);
					window.parent.jQuery("#companyCountry").val(_data.country);
					window.parent.jQuery("#companyId").val(_data.id);	
					window.parent.jQuery("#companySector").val(_data.sectorID);	
					window.parent.checkSector(_data.sectorID,1);
					if(_data.companyUsers.length>0){
						_table = jQuery("<table/>").addClass('table');
						_thead = jQuery("<thead/>");
						_tr = jQuery("<tr/>");
						jQuery(_tr).append("<th>Name</th>");
						jQuery(_tr).append("<th>Work Phone</th>");
						jQuery(_tr).append("<th>Mobile Phone</th>");
						jQuery(_thead).append(_tr);
						jQuery(_table).append(_thead);
						_tbody = jQuery("<tbody/>");
						for(u=0;u<_data.companyUsers.length;u++){
							_tr = jQuery("<tr/>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].name+"</td>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].phone+"</td>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].telephone+"</td>");
							jQuery(_tbody).append(_tr);
						}
						jQuery(_table).append(_tbody);
						window.parent.jQuery("#company_users_show_table").css({height:'250px',overflowY:'scroll'}).append(_table);
					}
					window.parent.jQuery(".multi-select").multiSelect('refresh');
					window.parent.jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
					window.parent.jQuery("#addCCompanyForm").css('z-index','9999');
					window.parent.jQuery("#addCCompanyForm").modal("show");
				}
			} 
			});
		} else {
			alert("Please select contact first");
		}		
	}

	function refreshContactTableHeader() {
		$('#datatable-contacts-sharing_wrapper .sorting_asc, #datatable-contacts-sharing_wrapper .sorting_desc').trigger('click');
		setTimeout(function() {
			$('#datatable-contacts-sharing_wrapper .sorting_asc, #datatable-contacts-sharing_wrapper .sorting_desc').trigger('click');
		}, 300);
	}
</script>