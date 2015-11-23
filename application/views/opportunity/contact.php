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
		_h = window.parent.$(window).height() - 120;
		jQuery(".multi-select").multiSelect();
		jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
		___table = $('#datatable-contacts-sharing')
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
		$('#datatable-contacts-sharing_wrapper .dataTables_scrollBody').height(height - 60);
	}

	$(function() {
		parent.open_contact_listResize();
	})

</script>
<style>
div.dataTables_filter label{
	/*float:left;*/
}
</style>




<div class="row" style='width:100%;'>	
	<div class="col-xs-12" style='width:100%;'>		
		<div class="row" style='margin-bottom:10px;'>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="window.parent.openCContact()" class='btn btn-primary btn-block mrg5R'>Manage Company</a></div>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="openAddForm()" class='btn btn-primary btn-block mrg5R'>Add Contact</a></div>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="editGoogleContact()" class='btn btn-primary btn-block mrg5R'>Edit</a></div>
		<?php if($this->session->userdata['type']=='9'):?>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="deleteGoogleContact()" class='btn btn-primary btn-block'>Delete</a>		</div>
		<?php endif;?>
		<div class="col-xs-2" style=''><a style='' href='http://synpatnew.com/connects/search.php' target='_BLANK' class='btn btn-primary btn-block'>Scraper</a>		</div>
		<div class="col-xs-2" style=''><a style='' href='javascript://' onclick="window.parent.openPreContacts()" class='btn btn-primary btn-block'>PreContacts</a>		</div>
		</div>
		<table class="table" class="table" id="datatable-contacts-sharing">
			<thead> 
				<tr>
					<th>#</th>
					<th style="width:120px;">Name</th>
					<th style="width:120px;">Job Title</th>
					<th style="width:120px;">Company</th>
					<th style="width:120px;">Phone Number</th>
					<th style="width:120px;">Sectors</th>
					<?php /* ?><th>Action</th><?php */?>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(count($contacts)>0){
						foreach($contacts as $contact){
							
				?>
				<tr>
					<td style="width:10px;"><input type="radio" name="vendor_select" data-name="<?php echo $contact->first_name." ".$contact->last_name;?>" data-title="<?php echo (string)$contact->job_title; ?>" data-company="<?php echo (string)$contact->company_name;?>" value="<?php echo $contact->id;?>"/></td>
					<td style="width:120px;"><?php echo (!empty($contact->first_name))?$contact->first_name." ".$contact->last_name:'';?><?php if($contact->gateway>0):?>&nbsp;&nbsp;<i class="glyph-icon icon-key tooltip-button" title="" data-placement="bottom" data-original-title="Gateway"></i> <?php endif;?></td>
					<td style="width:120px;"><?php echo (string)$contact->job_title; ?></td>
					<td style="width:120px;"><?php echo (string)$contact->company_name;?></td>
					<td style="width:120px;"><?php 
						$d=0;
						if(!empty($contact->phone)){
							echo '<a href="javascript://" onclick=\'callFromLandline("'.$contact->phone.'");\'>'.$contact->phone."</a>";
							$d =1;
						}
						if(!empty($contact->telephone)){
							if($d==1){
								echo ",<br/>";
							}
							echo '<a href="javascript://" onclick=\'callFromLandline("'.$contact->telephone.'");\'>'.$contact->telephone."</a>";
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
	function openAddForm(){
		window.parent.jQuery("#contactFormSubmit").get(0).reset();
		window.parent.jQuery("#contactFormSubmit").find("#inviteeId").val("");
		window.parent.jQuery("#contactFormSubmit").find("#marSector").find("option").removeAttr("selected");
		// window.parent.jQuery("#addContactForm").css('z-index','9999');
		window.parent.jQuery(".multi-select").multiSelect('refresh');
		window.parent.jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
		window.parent.jQuery("#addContactForm").modal("show");
	}
	function deleteGoogleContact(){
		if(jQuery("input[name='vendor_select']").is(":checked")){
			contactID = jQuery("input[name='vendor_select']:checked").val();
			res = confirm("Are you sure?");
			if(res){
				jQuery.ajax({
					type:'POST',
					url:'<?php echo $this->config->base_url();?>opportunity/deleteContact',
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
	
	function editGoogleContact(){
		if(jQuery("input[name='vendor_select']").is(":checked")){
			jQuery("#new_company").removeClass("show").addClass("hide");
			contactID = jQuery("input[name='vendor_select']:checked").val();
			window.parent.editContact(contactID);
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