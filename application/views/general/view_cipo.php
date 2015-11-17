<div id="page-title">

    <h2>Add New User </h2>

    <p>Select select user and select multiple page by pressig Ctrl.</p>

</div>

<div class="panel">

    <div class="panel-body">

		<div class="example-box-wrapper">

			<?php 

			if($this->session->flashdata('message')){

			?>

				<?php echo $this->session->flashdata('message');?>

			<?php					

				}

			?>

			<?php echo form_open('general/add_user',array('class'=>"form-horizontal bordered-row"))?>

			<div class="example-box-wrapper">

			

				<div class="form-group">



                    <label class="col-sm-3 control-label">Name</label>



                    <div class="col-sm-6">



                        <input type="text" name="user[name]" id="userName" class='form-control'/>



                    </div>



                </div>



				



				<div class="form-group">



                    <label class="col-sm-3 control-label">Email</label>



                    <div class="col-sm-6">



                        <input type="email" name="user[email]" id="userEmail" class='form-control'/>



                    </div>



                </div>

				

				<div class="form-group">



                    <label class="col-sm-3 control-label">Password</label>



                    <div class="col-sm-6">



                        <input type="password" name="user[password]" id="userPassword" class='form-control'/>



                    </div>



                </div>

				

				<div class="form-group">



                    <label class="col-sm-3 control-label">Phone Number</label>



                    <div class="col-sm-6">



                        <input type="text" name="user[phone_number]" id="userPhone" class='form-control'/>



                    </div>



                </div>

				

				<div class="form-group">



                    <label class="col-sm-3 control-label">Type</label>



                    <div class="col-sm-6">



                        <select name="user[type]" id="userType" class='form-control'>

							<option value="1" selected="selected">PD/Reviewer</option>

							<option value="8">CIPO</option>

							<option value="9">Admin</option>

						</select>



                    </div>



                </div>



				<div class="form-group">



                    <label class="col-sm-3 control-label"></label>



                    <div class="col-sm-6">



						<button type="submit" class="btn btn-primary">Save</button>



					</div>



				</div>

			</div>

			<?php echo form_close();?>

		</div>

	</div>

</div>

<hr/>

<div id="page-title">

    <h2>Users List</h2>

    <p></p>

</div>

<div class="panel">

    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="example-box-wrapper">

				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-user">

					<thead>

						<tr>

							<th>#</th>

							<th>Name</th>

							<th>Email</th>

							<th>Phone Number</th>

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