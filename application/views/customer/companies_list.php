<script type="text/javascript">

	jQuery(document).ready(function() {

        jQuery('#datatable-user').DataTable( {

            "paging": false

        });

    });

</script>
<div class="panel dashboard-box">

    <div class="panel-body">

		<div class="example-box-wrapper">
	
			<div class="example-box-wrapper">
				<h3 class="title-hero">Companies List</h3>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-user">

					<thead>

						<tr>
							<!--<th>#</th>-->
							<th>Name</th>
							<th>IP Deptt Address</th>
							<th>IP Deptt Telephone</th>
							<th>Bank Name</th>
							<th>Bank Account No</th>
							<th>Routing No</th>
							<th>Membership</th>
							<th></th>
						</tr>
					</thead>

					<tbody>

						<?php if(count($companies)>0){

								$i=1;

								foreach($companies as $comp){

						?>
						<tr>
							<!--<td><?php echo $i;?></td>-->
							<td><?php echo $comp->company_name;?></td>
							<td><p><?php echo $comp->company_address;?></p></td>							
							<td><?php echo $comp->telephone;?></td>
							<td><?php echo $comp->bank_name;?></td>
							<td><?php echo $comp->bank_account_no;?></td>
							<td><?php echo $comp->routing_no;?></td>
							<td><?php if($comp->membership==0):echo 'Vistor';elseif($comp->membership==1):echo 'Licensees';elseif($comp->membership==2):echo 'Participants';endif;?></td>
							<td><?php echo anchor('/customers/get_all_customers/'.$comp->id,'Users List',array('style'=>'color:#56B2fe'))?> &nbsp;&nbsp;/&nbsp;&nbsp; <?php
							$getCount = getUsersByActDeactCompanies($comp->id,1);
							if($getCount>0){
								echo anchor('/customers/activate_all_users/'.$comp->id,'Activate',array('style'=>'color:#56B2fe'));
							} else {
								echo anchor('/customers/deactivate_all_users/'.$comp->id,'Deactivate',array('style'=>'color:#56B2fe'));
							}
							?>
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