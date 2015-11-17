<!-- jQueryUI Spinner -->
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Document Selections</li>");
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
_masterDocument = {};
<?php 
	if(count($master_list)>0){
?>
_masterDocument = <?php echo json_encode($master_list);?>;
<?php
	}
?>
</script>


<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">    
    		<h3 class="title-hero">Document Selections</h3>

			<?php 

				if(count($master_list)==0){

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

			<?php echo form_open('general/manage_opportunity_type',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0;'))?>
			<div class="example-box-wrapper">
				<div class="hide-columns">
					<table class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th>Buttons</th>
							<th>From Litigation</th>
							<th>From Market</th>
							<th>Proactive General</th>
							<th>Proactive SEP</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td>NDA</td>
								<td>
									<select name="opportunity[from_litigation_nda]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){		

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='NDA' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?>  value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_nda]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='NDA' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?>  value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_nda]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='NDA' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option  <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_nda]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){		

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='NDA' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>
							<tr>
							<td>TERMSHEET</td>
								<td>
									<select name="opportunity[from_litigation_timesheet]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){		

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='TIMESHEET' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?>  value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_timesheet]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='TIMESHEET' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?>  value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_timesheet]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='TIMESHEET' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option  <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_timesheet]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){		

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='TIMESHEET' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>
							<tr>

								<td>PPA</td>

								<td>

									<select name="opportunity[from_litigation_ppa]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){		

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PPA' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_ppa]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PPA' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_ppa]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PPA' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_ppa]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PPA' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

							<tr>

								<td>RTP</td>

								<td>

									<select name="opportunity[from_litigation_rtp]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='RTP' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_rtp]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='RTP' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_rtp]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){				

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='RTP' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_rtp]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='RTP' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

							

							

							<tr>

								<td>PLA</td>

								<td>

									<select name="opportunity[from_litigation_pla]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLA' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_pla]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLA' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_pla]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){				

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLA' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_pla]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLA' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

							

							<tr>

								<td>Letter to Anchor</td>

								<td>

									<select name="opportunity[from_litigation_lta]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='LTA' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_lta]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='LTA' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_lta]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){				

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='LTA' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_lta]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='LTA' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

							

							<tr>

								<td>Proposal Letter to Sellers</td>

								<td>

									<select name="opportunity[from_litigation_plts]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLTS' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_plts]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLTS' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_plts]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){				

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLTS' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_plts]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='PLTS' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

							<tr>

								<td>Invitation to Participate</td>

								<td>

									<select name="opportunity[from_litigation_itp]" class="form-control">

										<option value="">-- Select From Litigation --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='ITP' && $doc->opportunity_type=='Litigation' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[from_market_itp]" class="form-control">

										<option value="">-- Select From Market --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='ITP' && $doc->opportunity_type=='Market' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_general_itp]" class="form-control">

										<option value="">-- Select Proactive General --</option>

									<?php 								

										foreach($master_list as $list){				

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='ITP' && $doc->opportunity_type=='General' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

								<td>

									<select name="opportunity[proactive_sep_itp]" class="form-control">

										<option value="">-- Select Proactive SEP --</option>

									<?php 								

										foreach($master_list as $list){	

											$select = "";

											if(count($doc_list)>0){

												foreach($doc_list as $doc){

													if($doc->file_type=='ITP' && $doc->opportunity_type=='SEP' && $doc->doc_id== $list->id){

														$select = "SELECTED='SELECTED'";

													}

												}

											}

									?>

										<option <?php echo $select;?> value="<?php echo $list->id?>"><?php echo $list->title;?></option>

									<?php											

										}

									?>

									</select>

								</td>

							</tr>

						</tbody>

					</table>

				</div>

				<div class="form-group" style='margin-top:5px; margin-bottom: 0;'>

                    <div class="col-sm-6" style='padding:0px;'>

						<button type="submit" class="btn btn-primary btn-mwidth">Save</button>

					</div>

				</div>

			</div>

			<?php echo form_close();?>

		</div>

	</div>

</div>

<script>

	function getListOfFiles(object){

		if(object.val()!=""){

			jQuery.ajax({

				type:'POST',

				url:'<?php echo $Layout->baseUrl?>general/getListOfFiles',

				data:{token:object.val()},

				cache:false,

				success:function(res){

					_data = jQuery.parseJSON(res);

					if(_data.length>0){

						if(_masterDocument.length>0){

							_option="";							

							for(i=0;i<_masterDocument.length;i++){

								_selected="";

								for(j=0;j<_data.length;j++){

									if(_data[j].doc_id==_masterDocument[i].id){

										_selected = "selected='selected'";

									}

								}

								_title = _masterDocument[i].title;

								_option +='<option '+_selected+' value="'+_masterDocument[i].id+'">'+_title.substring(0,25)+'</option>';

							}

							jQuery("#opportunityMasterDocuments").html(_option);

							$(function() { "use strict";

								$(".multi-select").multiSelect();

								$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');

							});

						}

					} else {

						jQuery("#opportunityMasterDocuments").multiSelect('deselect_all').multiSelect('refresh');

					}

				}

			});

		}

	}

</script>