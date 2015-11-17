<script type="text/javascript">

	jQuery(document).ready(function() {

        jQuery('#datatable-user').DataTable( {

            "paging": false

        });

    });

</script>
<div class="panel">

    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="example-box-wrapper">
				<h3 class="title-hero">Customers List</h3>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-user">

					<thead>

						<tr>
							<th>#</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>
							<th>Phone Number</th>
						</tr>
					</thead>

					<tbody>

						<?php if(count($users_list)>0){

								$i=1;

								foreach($users_list as $user){

						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $user->first_name;?></td>
							<td><p><?php echo $user->last_name;?></p></td>							
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