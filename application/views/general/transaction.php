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

	table .btn-primary,
	table .btn-danger {
		background: none !important;
		border: none !important;
		display: inline;
		line-height: 1;
		padding: 0;
	}
	table .btn-primary {
		color: #2196f3 !important;
	}
	table .btn-danger {
		color: #d9534f !important;
	}
</style>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Transactions</li>");
});
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<?php 
			if($this->session->flashdata('message')){
			?>
				<?php echo $this->session->flashdata('message');?>
			<?php					
				}
			?>

			<?php echo form_open('general/transaction',array('class'=>"form-horizontal form-flat", 'style'=>'margin-bottom: 0;'));?>           
			<div class="example-box-wrapper">
				<div class="row row-width">
					<div class="col-width" style="width: 600px;">
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Lead</label>
		                    <select name="transaction[project_id]" id="transactionProjectId" class='form-control'>
								<option value="">-- Select Lead --</option>
								<?php 
									if(count($leads)>0){
										foreach($leads as $lead){
								?>
										<option value="<?php echo $lead->id?>"><?php echo $lead->lead_name?></option>
								<?php
										}
									}
								?>
							</select>
		                </div>
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Type</label>
		                    <select name="transaction[category_id]" id="transactionCategoryId" onchange="checkType(jQuery(this))" class='form-control'>
								<option value="">-- Select Type --</option>
								<option value="Membership">Membership</option>								
								<option value="Participant">Participant</option>								
								<option value="Regular License">Regular License</option>								
								<option value="Late License">Late License</option>								
							</select>
		                </div>
						<div class="form-group input-string-group select-string-group mrg5T" style="margin-left: 0;">
		                    <label class="control-label">Contact</label>
		                    <select name="transaction[contact_id][]" id="transactionContactId"  class='form-control'>
								<?php 
									if(count($contacts)>0){
										foreach($contacts as $contact){
								?>
										<option value="<?php echo $contact->id?>"><?php echo $contact->company_name;?></option>
								<?php
										}
									}
								?>
							</select>
		                </div>						
						<div class="form-group input-string-group " style="margin-left: 0;">
		                    <label class="control-label">Amount:</label>
		                    <input type="text" name="transaction[amount]" placeholder="" class='form-control' id="transactionAmount" />
						</div>
						<div class="form-group input-string-group " style="margin-left: 0;">
		                    <label class="control-label">Payment Date:</label>
		                    <input type="text" name="transaction[payment_date]" placeholder="Date" class='form-control date_calendar' id="transactionPaymentDate" />
						</div>
						<div class="form-group input-string-group " style="margin-left: 0;">
		                    <label class="control-label">Note:</label>
		                    <textarea type="text" name="transaction[note]" placeholder="Note" class='form-control' id="transactionNote"></textarea>
						</div>
						<div class="mrg5T">
		                    <input type="hidden" name="id" value="0"/>
							<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>			
	        <div class="row">
				<h3 class="title-hero mrg15T " style="">Transactions List</h3>
				<div class='col-lg-12' style='height:450px;overflow-y:scroll'>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" width="33%">
						<thead>
							<tr>
								<th>Lead Name</th>
								<th>Company Name</th>
								<th>Type</th>
								<th>Amount ($m)</th>
								<th>Payment Date</th>
							</tr>
						</thead>
						<tbody>	
							<?php 
								if(count($transactions)>0){
									for($i=0;$i<count($transactions);$i++){
							?>
							<tr>
								<td><?php echo $transactions[$i]->leadName;?></td>
								<td><?php echo $transactions[$i]->companyName;?></td>
								<td><?php echo $transactions[$i]->category_id;?></td>
								<td><?php if($transactions[$i]->amt_type==1):echo "(-) "; elseif($transactions[$i]->amt_type==2):echo "(+) ";endif;?><?php echo $transactions[$i]->amount;?></td>
								<td><?php echo $transactions[$i]->payment_date;?></td>
							</tr>
							<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function checkType(o){
	switch (o.val()){
		case 'Participant':
			jQuery("#transactionContactId").prop("multiple",true);
			jQuery("#transactionContactId").multiSelect();jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>')
		break;
		default:
		jQuery("#transactionContactId").prop("multiple",false);
		jQuery("#transactionContactId").multiSelect("destroy");
		break;
	}
}
</script>

