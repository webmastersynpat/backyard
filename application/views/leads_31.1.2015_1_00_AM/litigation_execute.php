
<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs-ui/tabs.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script type="text/javascript">
    /* jQuery UI Tabs */

    $(function() { "use strict";
        $(".tabs").tabs();
    });

    $(function() { "use strict";
        $(".tabs-hover").tabs({
            event: "mouseover"
        });
    });
</script>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>From Litigation</li>");
});
</script>

<!-- Boostrap Tabs -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs.js"></script>

<!-- Tabdrop Responsive -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs-responsive.js"></script>
<script type="text/javascript">
    /* Responsive tabs */
    $(function() { "use strict";
        $('.nav-responsive').tabdrop();
    });
</script>

<script type="text/javascript">

    /* Datatables hide columns */
	var ___table,___table1,___table2,___table3,___table4;
    $(document).ready(function() {
		
         ___table = $('#datatable-hide-columns').DataTable( {
            
            "paging": false
        } );
		 ___table1 = $('#datatable-hide-columns1').DataTable( {
            
            "paging": false
        } );
		 ___table2 = $('#datatable-hide-columns2').DataTable( {
            
            "paging": false 
        } );
		 ___table3 = $('#datatable-hide-columns3').DataTable( {
            
            "paging": false 
        } );
		___table4 = $('#datatable-hide-columns4').DataTable( {
            
            "paging": false 
        } );
        $('#datatable-hide-columns_filter').hide();
        $('#datatable-hide-columns2_filter').hide();
        $('#datatable-hide-columns1_filter').hide();
        $('#datatable-hide-columns3_filter').hide();

      
    } );

   

</script>
<!-- <div id="page-title">
    <h2>Litigation Execute</h2>
    <p>Execute list of leads from litigation.</p>
</div> -->
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<div class="table-responsive">
				<div class="row">
					<?php 
						$previous = "disabled='disabled'";
						if($current_page>1){
							$previous ="";
						}
						$next = "disabled='disabled'";
						if($total_rows>1 && ($current_page+1)<=$no_of_pages){
							$next ="";
						}
					?>
                    <div class="col-xs-9"></div>
                    <div class="col-xs-2">
	                    <ul class="pager create-lead-pager clearfix">
	                        <li class="next"><a href="javascript:void(0)" <?php echo $next;?> onclick="record('next');" >Next <i class="glyph-icon icon-angle-right"></i></a></li>
	                        <li class="previous"><a href="javascript:void(0)" <?php echo $previous;?> onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i> Previous</a></li>
	                    </ul>
                    </div>
                    <div class="col-xs-1">
						<div class="pager-text">
							5/14
						</div>
                    </div>
				</div>
				<?php 
					if(count($results)>0){												
				?>
				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />
				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>

				<div id="topPart" class="form-horizontal form-flat">
					<div class="row mrg10T">
						<div class="col-xs-9">
							<?php foreach($results as $data):?>
							<div class="row">
								<div class="col-xs-7">
									<div class="form-group input-string-group">
										<label class="control-label">Case Name:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->case_name;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Litigation Stage:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->litigation_stage;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Market/Industry:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->market_industry;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Case Number:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->case_number;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Plaintiff's Name:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
									</div>
								</div>
								<div class="col-xs-5">
									<div class="form-group input-string-group">
										<label class="control-label">Number of patents</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_patent;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Filling Date:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->filling_date;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Number of active defandant:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->active_defendants;?>" readonly="readonly">
									</div>
									<div class="form-group input-string-group">
										<label class="control-label">Number of original defandant:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->original_defendants;?>" readonly="readonly">
									</div>
									<div class="form-group">
										<label class="control-label">Link to Pacer:</label>
										<a href="http://google.com" target="_blank">Some Link</a>
									</div>
								</div>
							</div>
							<?php endforeach;?>
							<?php } else {?>
								<p class="alert">No record found!</p>
							<?php }?>
						</div>
						<div class="col-xs-3">
							<div class="form-flat mrg5T">
								<!-- <textarea class="form-control" rows="5" placeholder="Plaintiff's Lead Attorney" style="height: 123px !important;"> -->
									<?php echo $data['litigation']->lead_attorney?>
								<!-- </textarea> -->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-9">
							<div class="row">
						  		<div class="col-xs-7">
									<div class="form-group input-string-group">
										<label class="control-label">
											Name of Lead:
										</label>
										<input type="text" tabindex="11" class="form-control"  readonly="readonly">
									</div>
						  		</div>
							</div>
						</div>
				  	</div>
				</div>


			<div class="example-box-wrapper" style="margin-top:10px;">
				<div class="table-responsive">
					<h3 class="title-hero">User comment list</h3>		
					<table class="table table-hover" id='comment-list'>
						<thead>
							<tr>
								<th width="15%">Comment By</th>
								<th width="50%">Comment</th>
								<th width="25%">Date</th>
								<th width="10%">Attractiveness</th>
								
							</tr>
						</thead>
						<tbody>
							<?php /*if(count($results)>0){?>
							<tr>
								<td><?php echo $results[0]['litigation']->userName;?></td>y								<td>
									<?php echo $results[0]['litigation']->comment;?>
								</td>
								<td></td>
							</tr>
							<?php } */?>
							<?php if(count($results)>0){ if(count($results[0]['comment'])>0){?>
							<?php foreach($results[0]['comment'] as $comment):?>
							<tr>
								<td width="15%"><?php echo $comment->name?></td>
								<td><?php echo $comment->comment?></td>
								<td><?php echo date("M d",strtotime($comment->created));?></td>
								<td width="10%"><span class="label alert <?php if($comment->attractive=='High'):?>label-success<?php elseif($comment->attractive=='Medium'):?>label-primary<?php elseif($comment->attractive=='Low'):?>label-warning<?php elseif($comment->attractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $comment->attractive?></span></td>					
							</tr>
							<?php endforeach;?>
							<?php } }
							?>							
						</tbody>
					</table>
				</div>
			</div>			
			
			<?php 

					if(count($results)>0){		
			?>
			<div class="example-box-wrapper" id="show_data">
				<div class="row">
				<?php 
					if(!empty($results[0]['litigation']->defendants) || !empty($results[0]['litigation']->court_docket_entries)):
				?>
				<div class="col-sm-12 noPadding" style='overflow-y:scroll;overflow:x:none;height:400px;'>
					<div class="row">
						<div class="col-sm-6 noPadding" id="defendant">
							<img src="<?php echo $results[0]['litigation']->defendants?>" style='width:100%;'>
						</div>
						<div class="col-sm-6" id="court_docket">
							<img src="<?php echo $results[0]['litigation']->court_docket_entries?>"  style="width:100%; ">
						</div>
					</div>
				</div>
				<?php else:
				$outPut = $results[0]['litigation']->scrapper_data;
					if(!empty($outPut)){
						$outPut = json_decode($results[0]['litigation']->scrapper_data);
						
					}
				?>
				<div class="col-sm-12 float-left" style='margin-top:5px;width:100%;padding:0;'>
					<div style='overflow-y:scroll;width:100%;height:400px;'>
						<div class='col-sm-12' id="tablesOtherData">
							<div class="panel">
								<div class="panel-body">
									<h3 class="title-hero">  
										Litigation Campaign
									</h3>
									<div class="example-box-wrapper">
										<ul class="nav-responsive nav nav-tabs">
											<li class="active"><a href="#tab1" data-toggle="tab">CASES</a></li>
											<li class=""><a href="#tab2" data-toggle="tab">DEFENDANTS</a></li>
											<li><a href="#tab3" data-toggle="tab">PATENTS</a></li>
											<li><a href="#tab4" data-toggle="tab">ACCUSED PRODUCTS</a></li>
											<li><a href="#tab5" data-toggle="tab">DOCKET ENTRIES</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab1">
												<table id="datatable-hide-columns"  class="table table-striped table-bordered " cellspacing="0" width="100%">
													<thead>
													<tr>
														<th>DATE FILED</th>
														<th>CASE NAME</th>
														<th>DOCKET NUMBER</th>
														<th>TERMINATION DATE</th>
													</tr>
													</thead>
													<tbody> 
														<?php 
															if(is_object($outPut)){
																
																if(isset($outPut->output->Tables)){
																	$dataOutPut = $outPut->output->Tables;
																	if(isset($dataOutPut->{'1'}) && count($dataOutPut->{'1'})>0){

																		for($i=0;$i<count($dataOutPut->{'1'});$i++){
														?>
														<tr>
															<td><?php echo $dataOutPut->{'1'}[$i][0];?></td>
															<td><?php echo $dataOutPut->{'1'}[$i][1];?></td>
															<td><?php echo $dataOutPut->{'1'}[$i][2];?></td>
															<td><?php echo $dataOutPut->{'1'}[$i][3];?></td>
														</tr>
														<?php
																		}
																	}
																}																
															}
														?>
													</tbody>
												</table>
											</div>
											<div class="tab-pane " id="tab2">
												<table id="datatable-hide-columns1" class="table table-striped table-bordered " cellspacing="0" width="100%">
													<thead>
													<tr>
														<th>DATE FILED</th>
														<th>DEFANDANTS</th>
														<th>LITIGATION</th>
														<th>TERMINATION DATE</th>
													</tr>
													</thead>
													<tbody> 
														<?php 
															if(is_object($outPut)){
																if(isset($outPut->output->Tables)){
																	$dataOutPut = $outPut->output->Tables;
																	if(isset($dataOutPut->{'2'}) && count($dataOutPut->{'2'})>0){
																		for($i=0;$i<count($dataOutPut->{'2'});$i++){
														?>
														<tr>
															<td><?php echo $dataOutPut->{'2'}[$i][0];?></td>
															<td><?php echo $dataOutPut->{'2'}[$i][1];?></td>
															<td><?php echo $dataOutPut->{'2'}[$i][2];?></td>
															<td><?php echo $dataOutPut->{'2'}[$i][3];?></td>
														</tr>
														<?php
																		}
																	}
																}																
															}
														?>
													</tbody>
												</table>
											</div>
											<div class="tab-pane" id="tab3">
												<table id="datatable-hide-columns2" class="table table-striped table-bordered" cellspacing="0" width="100%">
													<thead>
													<tr>
														<th>PATENT #</th>
														<th>TITLE</th>
														<th>EST. PRIORITY DATE</th>
													</tr>
													</thead>
													<tbody> 
														<?php 
															if(is_object($outPut)){
																if(isset($outPut->output->Tables)){
																	$dataOutPut = $outPut->output->Tables;
																	if(isset($dataOutPut->{'3'}) && count($dataOutPut->{'3'})>0){
																		for($i=0;$i<count($dataOutPut->{'3'});$i++){
														?>
														<tr>
															<td><?php echo $dataOutPut->{'3'}[$i][0];?></td>
															<td><?php echo $dataOutPut->{'3'}[$i][1];?></td>
															<td><?php echo $dataOutPut->{'3'}[$i][2];?></td>
														</tr>
														<?php
																		}
																	}
																}																
															}
														?>
													</tbody>
												</table>
											</div>
											<div class="tab-pane" id="tab4">
												<table id="datatable-hide-columns3" class="table table-striped table-bordered" cellspacing="0" width="100%">
													<thead>
													<tr>
														<th>DATE FILED</th>
														<th>DEFANDANTS</th>
														<th>ACCUSED PRODUCTS</th>
													</tr>
													</thead>
													<tbody> 
														<?php 
															if(is_object($outPut)){
																if(isset($outPut->output->Tables)){
																	$dataOutPut = $outPut->output->Tables;
																	if(isset($dataOutPut->{'4'}) && count($dataOutPut->{'4'})>0){
																		for($i=0;$i<count($dataOutPut->{'4'});$i++){
														?>
														<tr>
															<td><?php echo $dataOutPut->{'4'}[$i][0];?></td>
															<td><?php echo $dataOutPut->{'4'}[$i][1];?></td>
															<td><?php echo $dataOutPut->{'4'}[$i][2];?></td>
														</tr>
														<?php
																		}
																	}
																}																
															}
														?>
													</tbody>
												</table>
											</div>	
											<div class="tab-pane" id="tab5">
												<table id="datatable-hide-columns4" class="table table-striped table-bordered" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>ENTRY #</th>
															<th>DATE FILED</th>
															<th>DATE ENTERED</th>
															<th>ENTRY DESCRIPTION</th>
														</tr>
													</thead>
													<tbody>
														<?php 
															if(is_object($outPut)){
																if(isset($outPut->output->docket_entries_table)){
																	$dataOutPut = $outPut->output->docket_entries_table;
																	if(isset($dataOutPut) && count($dataOutPut)>0){
																		for($i=0;$i<count($dataOutPut);$i++){
														?>
														<tr>
															<td><?php echo $dataOutPut[$i][1];?></td>
															<td><?php echo $dataOutPut[$i][2];?></td>
															<td><?php echo $dataOutPut[$i][3];?></td>
															<td><?php echo $dataOutPut[$i][4];?></td>
														</tr>
														<?php
																		}
																	}
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
						</div>
					</div>
				</div>
				
				<?php endif;?>
				</div>
			</div>
			<div class="example-box-wrapper text-center">
				<p>
					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->
					<button  type="button" onclick="emailProposal();" class="btn btn-primary btn-sm">+ Email Proposal</button>
					<button class="btn btn-primary btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>
					<button class="btn btn-primary <?php if((int)$results[0]['litigation']->status==0):?> <?php else:?> <?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>  type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>
					<span style=' display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->
				</p>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
_res = "";
	
	jQuery("#btnApproved").click(function(){
		jQuery("#spinner-loader").css('display','inline-block');		
		if(jQuery(this).attr('disabled')==undefined){
			if(parseInt(jQuery("#otherParentId").val())>0){
				jQuery.ajax({
					type:'POST',
					url:'<?php echo $Layout->baseUrl?>leads/change_status_lead',
					data:{token:jQuery("#otherParentId").val()},
					cache:false,
					success:function(response){
						jQuery("#spinner-loader").css('display','none');
						_data = jQuery.parseJSON(response);
						if(_data.rows>0){
							alert('Lead approved successfully.');
							window.location= window.location.href;
						} else {
							alert('Please try after sometime!');
						}
					}
				});
			}
		}
		
	});
	function record(level){
		jQuery.ajax({
			type:'POST', 
			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',
			data:{token:jQuery("#token").val(),level:level,type:'Litigation'},
			cache:false,
			success:function(response){
				response = jQuery.parseJSON(response);
				if(response.results.length>0){
					_data = response.results[0].litigation;	
					if(jQuery("#otherParentId").length>0){
						jQuery("#otherParentId").val(_data.id);
					}
					if(jQuery("#sendLitigation").length>0){
						jQuery("#sendLitigation").val(_data.id);
					}
					if(parseInt(_data.status)==0){
						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-primary').addClass('btn-default');
					} else {
						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').removeClass('btn-default').addClass('btn-primary');
					}

					_tr = '<div class="row mrg10T">' +
								'<div class="col-xs-9">' +
									'<div class="row">' +
										'<div class="col-xs-7">' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Case Name:</label>' +
												'<input type="text" class="form-control" value="'+_data.case_name+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Litigation Stage:</label>' +
												'<input type="text" class="form-control" value="'+_data.litigation_stage+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Market/Industry:</label>' +
												'<input type="text" class="form-control" value="'+_data.market_industry+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Case Number:</label>' +
												'<input type="text" class="form-control" value="'+_data.case_number+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Plaintiff\'s Name:</label>' +
												'<input type="text" class="form-control" value="'+_data.plantiffs_name+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
										'</div>' +
										'<div class="col-xs-5">' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Number of patents</label>' +
												'<input type="text" class="form-control" value="'+_data.no_of_patent+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Filling Date:</label>' +
												'<input type="text" class="form-control" value="'+_data.filling_date+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Number of active defandant:</label>' +
												'<input type="text" class="form-control" value="'+_data.active_defendants+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label">Number of original defandant:</label>' +
												'<input type="text" class="form-control" value="'+_data.original_defendants+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group">' +
												'<label class="control-label">Link to Pacer:</label> ' +
												'<a href="http://google.com" target="_blank">Some Link</a>' +
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-3">' +
									'<div class="form-flat mrg5T">' +
										_data.lead_attorney +
									'</div>' +
								'</div>' + 
							'</div>' +
							'<div class="row">' +
								'<div class="col-xs-9">' +
									'<div class="row">' +
								  		'<div class="col-xs-7">' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label" for="generalTechnologies">' +
													'Name of Lead:' +
												'</label>' +
												'<input type="text" tabindex="11" class="form-control" readonly="readonly" style="width: 30px;">' +
											'</div>' +
								  		'</div>' +
									'</div>' +
								'</div>' +
						  	'</div>';
					jQuery("#topPart").html(_tr);

					_tr ='<tr>'+
												'<td>'+_data.case_name+'</td>'+
												'<td>'+_data.case_number+'</td>'+
												'<td><div class="lbl">No of patents</div> '+_data.no_of_patent+'</td>'+												
											'</tr>'+
											'<tr>'+
												'<td><div class="lbl">Plaintiff\'s Name</div> '+_data.plantiffs_name+'</td>'+
												'<td><div class="lbl">Filling Date</div> '+_data.filling_date+'</td>'+
												'<td><div class="lbl">No of original defandant</div> '+_data.original_defendants+'</td>'+
											'</tr>'+
											'<tr>'+
												'<td><div class="lbl">Litigation Stage: '+_data.litigation_stage+'</td>'+
												'<td><div class="lbl">Market/Industry: '+_data.market_industry+'</td>'+
												'<td><div class="lbl">No of active defandant: '+_data.active_defendants+'</td>'+
											'</tr>'+
											'<tr><td colspan="3"><div class="lbl">Plantiff Attorney</div><br/><form class="form-flat"><textarea class="form-control" style="margin-top: 10px;" rows="5">'+_data.lead_attorney+'</textarea></form></td></tr>';
					if(_data.defendants=="" && _data.court_docket_entries==""){
						_skeltonTable = '<div class="col-sm-12 float-left" style="margin-top:5px;width:100%;padding:0;">'+
											'<div style="width:100%;">'+
											'	<div class="col-sm-12" id="tablesOtherData">'+
											'		<div class="panel">'+
											'			<div class="panel-body">'+
											'				<h3 class="title-hero">  '+
											'					Litigation Campaign'+
											'				</h3>'+
											'				<div class="example-box-wrapper">'+
											'					<ul class="nav-responsive nav nav-tabs">'+
											'						<li class="active"><a href="#tab1" data-toggle="tab">CASES</a></li>'+
											'						<li class=""><a href="#tab2" data-toggle="tab">DEFENDANTS</a></li>'+
											'						<li><a href="#tab3" data-toggle="tab">PATENTS</a></li>'+
											'						<li><a href="#tab4" data-toggle="tab">ACCUSED PRODUCTS</a></li>'+
											'						<li><a href="#tab5" data-toggle="tab">DOCKET ENTRIES</a></li>'+
											'					</ul>'+
											'					<div class="tab-content">'+
											'						<div class="tab-pane active" id="tab1">'+
											'							<table id="datatable-hide-columns'+_data.id+'"  class="table table-striped table-bordered " cellspacing="0" width="100%">'+
											'								<thead>'+
											'								<tr>'+
											'									<th>DATE FILED</th>'+
											'									<th>CASE NAME</th>'+
											'									<th>DOCKET NUMBER</th>'+
											'									<th>TERMINATION DATE</th>'+
											'								</tr>'+
											'								</thead>'+
											'								<tbody> </tbody>'+
											'							</table>'+
											'						</div>'+
											'						<div class="tab-pane " id="tab2">'+
											'							<table id="datatable-hide-columns'+_data.id+'1" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
											'								<thead>'+
											'								<tr>'+
											'									<th>DATE FILED</th>'+
											'									<th>DEFANDANTS</th>'+
											'									<th>LITIGATION</th>'+
											'									<th>TERMINATION DATE</th>'+
											'								</tr>'+
											'								</thead>'+
											'								<tbody></tbody>'+
											'							</table>'+
											'						</div>'+
											'						<div class="tab-pane" id="tab3">'+
											'							<table id="datatable-hide-columns'+_data.id+'2" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
											'								<thead>'+
											'								<tr>'+
											'									<th>PATENT #</th>'+
											'									<th>TITLE</th>'+
											'									<th>EST. PRIORITY DATE</th>'+
											'								</tr>'+
											'								</thead>'+
											'								<tbody> </tbody>'+
											'							</table>'+
											'						</div>'+
											'						<div class="tab-pane" id="tab4">'+
											'							<table id="datatable-hide-columns'+_data.id+'3" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
											'								<thead>'+
											'								<tr>'+
											'									<th>DATE FILED</th>'+
											'									<th>DEFANDANTS</th>'+
											'									<th>ACCUSED PRODUCTS</th>'+
											'								</tr>'+
											'								</thead>'+
											'								<tbody></tbody>'+
											'							</table>'+
											'						</div>	'+
											'						<div class="tab-pane" id="tab5">'+
											'							<table id="datatable-hide-columns'+_data.id+'4" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
											'								<thead>'+
											'									<tr>'+
											'										<th>ENTRY #</th>'+
											'										<th>DATE FILED</th>'+
											'										<th>DATE ENTERED</th>'+
											'										<th>ENTRY DESCRIPTION</th>'+
											'									</tr>'+
											'								</thead>'+
											'								<tbody></tbody>'+
											'							</table>'+
											'						</div>'+
											'					</div>'+
											'				</div>'+
											'			</div>'+
											'		</div>'+
											'	</div>'+
											'</div>'+
										'</div>';							
						jQuery("#show_data").html(_skeltonTable);				
						jQuery(function() { "use strict";
							jQuery(".tabs").tabs();
						});

						jQuery(function() { "use strict";
							jQuery(".tabs-hover").tabs({
								event: "mouseover"
							});
						});
						jQuery(function() { "use strict";
							jQuery('.nav-responsive').tabdrop();
						});
						_mainData = jQuery.parseJSON(_data.scrapper_data);				
						_outPut = _mainData.output;
						_leadAttorney = _outPut.LeadAttorney;
						_leadAttorney = _leadAttorney.replace(/(\r\n|\n|\r)/gm,"");
						_pacer = _outPut.pacer;
						_caseType = _outPut.casetype;
						_caseNumber = _outPut.data1;
						_market = _outPut.market;
						_stringFiled =  _outPut.data2;
						_title = _outPut.title;
						_pantiffString = _title.split('v.');				
						_tables = _outPut.Tables;
						if(_tables[1]!=undefined){
							if(_tables[1].length>0){
								jQuery("#datatable-hide-columns"+_data.id).find('tbody').empty();
								for(i=0;i<_tables[1].length;i++){
									_dateFiled = _tables[1][i][0];
									_caseName = _tables[1][i][1];
									_docketNumber = _tables[1][i][2];
									_terminationDate = _tables[1][i][3];
									jQuery("#datatable-hide-columns"+_data.id+">tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_caseName+'</td><td>'+_docketNumber+'</td><td>'+_terminationDate+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id).DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id).find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[2].length>0){
								jQuery("#datatable-hide-columns"+_data.id+"1").find('tbody').empty();
								_activeDefandants = 0;
								for(i=0;i<_tables[2].length;i++){
									_dateFiled = _tables[2][i][0];
									_defandants = _tables[2][i][1];
									_litigation = _tables[2][i][2];
									_terminationDate = _tables[2][i][3];
									if(_terminationDate==""){
										_activeDefandants++;
									}
									jQuery("#datatable-hide-columns"+_data.id+"1>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_litigation+'</td><td>'+_terminationDate+'</td></tr>');
								}
								jQuery("#litigationActiveDefendants").val(_activeDefandants);
								jQuery("#datatable-hide-columns"+_data.id+"1").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"1").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[3].length>0){
								jQuery("#litigationNoOfPatent").val(_tables[3].length);
								jQuery("#datatable-hide-columns"+_data.id+"2").find('tbody').empty();
								for(i=0;i<_tables[3].length;i++){
									_patent = _tables[3][i][0];
									_title = _tables[3][i][1];
									_priority_date = _tables[3][i][2];
									jQuery("#datatable-hide-columns"+_data.id+"2>tbody").append('<tr><td>'+_patent+'</td><td>'+_title+'</td><td>'+_priority_date+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"2").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"2").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[4].length>0){
								jQuery("#datatable-hide-columns"+_data.id+"3").find('tbody').empty();
								for(i=0;i<_tables[4].length;i++){ 
									_dateFiled = _tables[4][i][0];
									_defandants = _tables[4][i][1];
									_accusedProduct = _tables[4][i][2];
									jQuery("#datatable-hide-columns"+_data.id+"3>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_accusedProduct+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"3").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"3").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_outPut.docket_entries_table.length>0){
								jQuery("#datatable-hide-columns"+_data.id+"4").find('tbody').empty();
								for(i=0;i<_outPut.docket_entries_table.length;i++){
									__data = _outPut.docket_entries_table[i]
									_entry = __data[1];
									_dateFiled = __data[2];
									_dateEntered =__data[3];
									_entryDescription =__data[4];
									jQuery("#datatable-hide-columns"+_data.id+"4>tbody").append('<tr><td>'+_entry+'</td><td>'+_dateFiled+'</td><td>'+_dateEntered+'</td><td>'+_entryDescription+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"4").DataTable( {
									"paging": false							
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"4").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
						}
					
					} else {
						jQuery("#show_data").html('<div class="col-sm-12 noPadding" style=\'overflow-y:scroll;overflow:x:none;height:400px;\'><div class="col-sm-6 noPadding" id="defendant"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div><div class="col-sm-6" id="court_docket"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div></div>');
					}
					jQuery("#record-list>tbody").html(_tr);
					jQuery("#token").val(response.token);					
					if((parseInt(response.current_page)+1)<=parseInt(response.no_of_pages)){
						jQuery("#next").removeAttr('disabled');
					} else {
						jQuery("#next").attr('disabled','disabled');
					}
					if((parseInt(response.current_page)-1)!=0){
						jQuery("#prev").removeAttr('disabled');
					} else {
						jQuery("#prev").attr('disabled','disabled');
					}
					jQuery("#defendant").html('<img src="'+_data.defendants+'" style="width:490px;"/>');
					jQuery("#court_docket").html('<img src="'+_data.court_docket_entries+'" style="width:490px;"/>');
					/*Comments*/
					_trData = "";
					response = response.results[0].comment;
					if(response.length>0){					
						for(i=0;i<response.length;i++){
							_label = '';
							if(response[i].attractive=="High"){
								_label = 'label-success';
							} else if(response[i].attractive=="Medium"){
								_label = 'label-warning';
							} else if(response[i].attractive=="Low"){
								_label = 'label-danger';
							}
							_trData+='<tr>'+
										'<td width="15%">'+response[i].name+'</td>'+
										'<td >'+response[i].comment+'</td>'+
										'<td>'+response[i].created+'</td>'+
										'<td width="10%"><span class="label alert '+_label+'">'+response[i].attractive+'</span></td>'+
									'</tr>';
						}
					} else {
						_trData="<td colspan='3'><p class='alert'>No record found!</p></td>";
					}
					jQuery("#comment-list>tbody").html(_trData);
					
				}
			}
		});
	}
	
	function sendRequestForProposalLetter(){
		jQuery("#spinner-loader").css('display','inline-block');
		_number = jQuery("#record-list>tbody>tr").eq(jQuery("#record-list>tbody>tr").length-1).find('td').eq(0).html();
		_names = _number.split('<div class="lbl">Lead Name </div>');
		if(_names.length>1){
			_names = jQuery.trim(_names[1]);
		}
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',
			data:{name:_names,type:'Litigation',lead_id:jQuery("#otherParentId").val()},
			cache:false,
			success:function(res){
				jQuery("#spinner-loader").css('display','none');
				_res = jQuery.parseJSON(res);
				if(_res.link!=""){
					window.open(_res.link,"_blank","toolbar=yes, scrollbars=yes, resizable=yes,width=600, height=500")
				} else {
					alert('Please try after some time!');
				}
			}
		});
	}
	function emailProposal(){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>leads/email_proposal_history',
			data:{lead_id:jQuery("#otherParentId").val()},
			cache:false,
			success:function(res){
				
			}
		});
		window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK');
		window.location = window.location.href;
	}
</script>
</div>
<?php echo $Layout->element('timeline');?>
</div>