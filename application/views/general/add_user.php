<style>
	div.dataTables_filter label {
		float: right;
	}
	div.dataTables_filter input {
	    box-shadow: none;
	    float: left;
	    height: 24px;
	    margin-bottom: 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}
</style>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Add User</li>");
});
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">

			<div class="row row-width">
				<div class="col-width" style="width: 400px;">
					<h3 class="title-hero">Add New User </h3>
					<!-- <p>Select select user and select multiple page by pressig Ctrl.</p> -->

					<?php 
					if($this->session->flashdata('message')){
					?>
						<?php echo $this->session->flashdata('message');?>
					<?php					
						}
					?>
					<?php echo form_open('general/add_user',array('class'=>"form-horizontal form-flat"))?>
					<div class="example-box-wrapper">
						<div class="form-group input-string-group" style="margin-left: 0;">
		                    <label class="control-label">Name:</label>
	                        <input type="text" name="user[name]" id="userName" class='form-control'/>
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Email:</label>
	                        <input type="email" name="user[email]" id="userEmail" class='form-control' autocomplete="off" />
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Password:</label>
	                        <input type="password" name="user[password]" id="userPassword" class='form-control' autocomplete="off" />
		                </div>
						<div class="form-group input-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Phone Number:</label>
	                        <input type="text" name="user[phone_number]" id="userPhone" class='form-control'/>
		                </div>
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Type:</label>
	                        <select name="user[type]" id="userType" class='form-control'>
								<option value="1" selected="selected">PD/Reviewer</option>
								<option value="8">CIPO</option>
								<option value="9">Admin</option>
							</select>
		                </div>
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Insider/Outside:</label>
	                        <select name="user[flag]" id="userType" class='form-control'>
								<option value="1" selected="selected">Insider</option>
								<option value="0">Outsider</option>
							</select>
		                </div>
						<div class="mrg5T">
							<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
			</div>

			<div class="row mrg15T">
				<div class="col-xs-12">
					<h3 class="title-hero" style="margin-bottom: -20px;">Users List</h3>
					<!-- <p></p> -->

					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-user">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone Number</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($user_list)>0){
									$i=1;
									foreach($user_list as $user){
							?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $user->name;?></td>
								<td><?php echo $user->email;?></td>
								<td><?php echo $user->phone_number;?></td>
								<td>
								
								<a href="<?php echo $Layout->baseUrl;?>general/modifyUser/<?php echo $user->id?>/<?php echo $user->status?>" title="<?php if((int) $user->status==0):?> deactivate <?php else:?> activate<?php endif;?>"><i class="glyph-icon <?php if((int) $user->status==0):?>icon-mail-forward <?php else:?> icon-mail-reply <?php endif;?>"></i></a>
									<?php 
										$checked="";
										if($user->send_email==1){
											$checked='checked="checked"';
										}
									?>
									<input class='mrg20L' type="checkbox" onchange="alertFlag(jQuery(this),<?php echo $user->id;?>);" <?php echo $checked;?> />
								</td>
							</tr>
							<?php	
										$i++;
									}
								} else {
							?>
								<tr><td colspan="3">No record found!</td></tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>



<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
        jQuery('#datatable-user').DataTable( {
            "paging": false
        });
    });
</script>
<script>
	function alertFlag(o,id){
		chk = 0;
		if(o.prop('checked')){
			chk = 1;
		}
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>general/changeUserSend',
			data:{chk:chk,u:id},
			cache:false,
			success:function(res){
				
			}
		});
	}
	function getUserPageList(object){
		if(object.val()!=""){
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>general/getUserPageList',
				data:{token:object.val()},
				cache:false,
				success:function(res){
					_data = jQuery.parseJSON(res);
					if(_data.length>0){
						if(_pageList.length>0){
							_option="";							
							for(i=0;i<_pageList.length;i++){
								_selected="";
								for(j=0;j<_data.length;j++){
									if(_data[j].page_id==_pageList[i].id){
										_selected = "selected='selected'";
									}
								}
								_option +='<option '+_selected+' value="'+_pageList[i].id+'">'+_pageList[i].page_name+'</option>';
							}
							jQuery("#pagePageID").html(_option);
							$(function() { "use strict";
								$(".multi-select").multiSelect();
								$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
							});
						}
					} else {
						jQuery("#pagePageID").multiSelect('deselect_all').multiSelect('refresh');
					}
				}
			});
		}
	}
</script>