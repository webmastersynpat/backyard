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