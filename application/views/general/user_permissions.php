<?php 
	$modules = getAllBackyardModules();
?>
<script>
_moduleList = <?php echo json_encode($modules);?>;
</script>
<!-- jQueryUI Spinner -->
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>User Permissions</li>");
});
</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/spinner/spinner.js"></script>
<script type="text/javascript">
    /* jQuery UI Spinner */
    $(function() { "use strict";
        $(".spinner-input").spinner();
    });
</script>
<!-- jQueryUI Autocomplete -->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/autocomplete/autocomplete.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/autocomplete/menu.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/autocomplete/autocomplete-demo.js"></script>
<!-- Touchspin -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/touchspin/touchspin.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/touchspin/touchspin.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/touchspin/touchspin-demo.js"></script>
<!-- Input switch -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/input-switch/inputswitch.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/input-switch/inputswitch.js"></script>
<script type="text/javascript">
    /* Input switch */
    $(function() { "use strict";
        $('.input-switch').bootstrapSwitch();
    });
</script>
<!-- Textarea -->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/textarea/textarea.js"></script>
<script type="text/javascript">
    /* Textarea autoresize */
    $(function() { "use strict";
        $('.textarea-autosize').autosize();
    });
</script>
<!-- Multi select -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/multi-select/multiselect.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/multi-select/multiselect.js"></script>
<script type="text/javascript">
    /* Multiselect inputs */
    $(function() { "use strict";
        $(".multi-select").multiSelect();
        $(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
    });
</script>
<!-- Uniform -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/uniform/uniform.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/uniform/uniform.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/uniform/uniform-demo.js"></script>
<!-- Chosen -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/widgets/chosen/chosen.css">-->
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/chosen/chosen.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/chosen/chosen-demo.js"></script>
<script>
_pageList = '';
_userList = '';

_leadList = '';
<?php 

	if(count($pages_list)>0):

?>

_pageList = <?php echo json_encode($pages_list);?>;

<?php endif;?>

<?php 

	if(count($leads)>0):

?>

_leadList = <?php echo json_encode($leads);?>;

<?php endif;?>

<?php 

	if(count($users)>0):

?>

_userList = <?php echo json_encode($users);?>;

<?php endif;?>

</script>

<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<!-- <h3 class="title-hero">User Pages Permission</h3> -->
			<!-- <p>Select select user and select multiple page by pressig Ctrl.</p> -->
			<?php 
				if(count($pages_list)==0){
			?>
			<p class="alert alert-warning">No files found from Master Document of Google Drive. Please upload files in Master document folder and after upload refresh this page.</p>
			<?php
				}
			?>
			<?php 
			if($this->session->flashdata('message')){
			?>
				<?php echo $this->session->flashdata('message');?>
			<?php
				}
			?>
			
			<div class="row">
				<div class="col-xs-6">
					<?php echo form_open('general/user_permissions',array('class'=>"form-horizontal form-flat"))?>
					<h3 class="title-hero">According to User</h3>
					<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                <label class="control-label">Select User:</label>
	                    <select name="page[user_id]" required class="form-control" onchange="getUserPageList(jQuery(this));">
							<option value="">-- Select User --</option>
	                        <?php 
								if(count($users)>0){
									foreach($users as $user){
							?>
									<option value="<?php echo $user->id?>"><?php echo $user->name?></option>
							<?php
									}
								}
							?>
	                    </select>
		            </div>
					<div class="row">
		                <label class="col-sm-12 control-label">List of Leads:</label>
		                <div class="col-sm-12" style="padding-right:12px;">
		                    <select multiple class="multi-select" name="page[lead_id][]" id="leadLeadID">
		                        <?php 
									foreach($leads as $list){
								?>
									<option value="<?php echo $list->id?>"><?php echo $list->lead_name;?></option>
								<?php
									}
								?>
		                    </select>
		                </div>
		            </div>
					<div class="row">
		                <label class="col-sm-12 control-label">List of Module:</label>
		                <div class="col-sm-12" style="padding-right:12px;">
		                    <select multiple class="multi-select" name="page[module_id][]" id="moduleModuleID">
		                        <?php 
									foreach($modules as $module){
								?>
									<option value="<?php echo $module->id;?>"><?php echo $module->name;?></option>
								<?php
									}
								?>
		                    </select>
		                </div>
		            </div>
					<?php /* ?>
					<div class="row">
		                <label class="col-sm-12 control-label">List of Pages:</label>
		                <div class="col-sm-12" style="padding-right:12px;">
		                    <select multiple class="multi-select" name="page[page_id][]" id="pagePageID">
		                        <?php 
									foreach($pages_list as $list){
								?>
									<option value="<?php echo $list->id?>"><?php echo $list->page_name;?></option>
								<?php
									}
								?>
		                    </select>
		                </div>
		            </div><?php */?>
	            	<div class="mrg5T">
						<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
			<?php /* ?><div class="row mrg15T">
				<div class="col-xs-6">
					<?php echo form_open('general/user_permissions_page',array('class'=>"form-horizontal form-flat"))?>
					<h3 class="title-hero">According to Page</h3>
					<div class="form-group input-string-group select-string-group" style="margin-left: 0;">
		                <label class="control-label">List of Pages:</label>
	                    <select  name="page[page_id]" required class="form-control" id="pagePageID" onchange="getPageUserList(jQuery(this));">
							<option value="">-- Select Page --</option>
	                        <?php 
								foreach($pages_list as $list){
							?>
								<option value="<?php echo $list->id?>"><?php echo $list->page_name;?></option>
							<?php
								}
							?>
	                    </select>
		            </div>
					<div class="row">
		                <label class="col-sm-12 control-label">Select User:</label>
		                <div class="col-sm-12" style="padding-right:12px;">
		                    <select multiple class="multi-select" id="pageUserID" name="page[user_id][]"  >
		                        <?php 
									if(count($users)>0){
										foreach($users as $user){
								?>
										<option value="<?php echo $user->id?>"><?php echo $user->name?></option>
								<?php
										}
									}
								?>
		                    </select>
		                </div>
		            </div>
	            	<div class="mrg5T">
						<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
	            	</div>
					<?php echo form_close();?>
				</div>
			</div>*/?>
		</div>
	</div>
</div>

<script>

	function getUserPageList(object){

		if(object.val()!=""){

			jQuery.ajax({

				type:'POST',

				url:'<?php echo $Layout->baseUrl?>general/getUserModuleLeadList',

				data:{token:object.val()},

				cache:false,

				success:function(res){

					_data = jQuery.parseJSON(res);

					if(_data.leads!="undefined"){

						if(_data.leads.length>0){

							_option="";							

							for(i=0;i<_leadList.length;i++){
								
								_selected="";

								for(j=0;j<_data.leads.length;j++){
									
									
									if(_data.leads[j].lead_id==_leadList[i].id){

										_selected = "selected='selected'";

									}

								}
								
								_option +='<option '+_selected+' value="'+_leadList[i].id+'">'+_leadList[i].lead_name+'</option>';

							}

							jQuery("#leadLeadID").html(_option);

							$(".multi-select").multiSelect();

							$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

						} else {
							jQuery("#leadLeadID").multiSelect('deselect_all').multiSelect('refresh');							
							$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
						}
						
						if(_data.module.length>0){
							if(_moduleList.length>0){

							_option="";							

							for(i=0;i<_moduleList.length;i++){

								_selected="";

								for(j=0;j<_data.module.length;j++){
									if(_data.module[j].module_id==_moduleList[i].id){

										_selected = "selected='selected'";

									}

								}

								_option +='<option '+_selected+' value="'+_moduleList[i].id+'">'+_moduleList[i].name+'</option>';

							}

							jQuery("#moduleModuleID").html(_option);

							$(".multi-select").multiSelect();

							$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

							}
						}	 else {
							jQuery("#moduleModuleID").multiSelect('deselect_all').multiSelect('refresh');
							$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
						}

					} else {

						jQuery("#leadLeadID").multiSelect('deselect_all').multiSelect('refresh');
						jQuery("#moduleModuleID").multiSelect('deselect_all').multiSelect('refresh');

						$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

					}

				}

			});

		}

	}

	

	function getPageUserList(object){

		if(object.val()!=""){

			jQuery.ajax({

				type:'POST',

				url:'<?php echo $Layout->baseUrl?>general/getPageUserList',

				data:{token:object.val()},

				cache:false,

				success:function(res){

					_data = jQuery.parseJSON(res);

					if(_data.length>0){

						if(_userList.length>0){

							_option="";							

							for(i=0;i<_userList.length;i++){

								_selected="";

								for(j=0;j<_data.length;j++){

									if(_data[j].user_id==_userList[i].id){

										_selected = "selected='selected'";

									}

								}

								_option +='<option '+_selected+' value="'+_userList[i].id+'">'+_userList[i].name+'</option>';

							}

							jQuery("#pageUserID").html(_option);

							$(".multi-select").multiSelect();

							$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

						}

					} else {

						jQuery("#pageUserID").multiSelect('deselect_all').multiSelect('refresh');

						$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

					}

				}

			});

		}

	}

</script>