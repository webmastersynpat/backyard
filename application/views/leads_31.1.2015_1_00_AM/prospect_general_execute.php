<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<div class="panel dashboard-box">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>Proactive General</li>");
});
</script>
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

					<div class="row">
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

				</div>

				<?php 

					if(count($results)>0){												

				?>

				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />

				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>

				<!-- <table class="table" id='record-list'>

					<tbody>

						<?php foreach($results as $data):?>

						<tr>

							<td><div class="lbl">Owner: </div> <?php echo $data['litigation']->plantiffs_name;?></td>

							<td><div class="lbl">No of Patents: </div> <?php echo $data['litigation']->no_of_prospects;?></td>

							<td><div class="lbl">Relates To: </div> <?php echo $data['litigation']->relates_to;?></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name1: </div> <?php echo $data['litigation']->person_name_1;?></td>

							<td><div class="lbl">Person Title1: </div> <?php echo $data['litigation']->person_title_1;?></td>

							<td></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name2: </div> <?php echo $data['litigation']->person_name_2;?></td>

							<td><div class="lbl">Person Title2: </div> <?php echo $data['litigation']->person_title_2;?></td>

							<td></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name3: </div> <?php echo $data['litigation']->person_name_3;?></td>

							<td><div class="lbl">Person Title3: </div> <?php echo $data['litigation']->person_title_3;?></td>

							<td></td>

						</tr>

						<tr>

							<td colspan="2"><div class="lbl">Address: </div><?php echo $data['litigation']->address;?></td>

							<td><div class="lbl">Portfolio Number: </div><?php echo $data['litigation']->portfolio_number;?></td>

						</tr>

						<?php endforeach;?>

					</tbody>

				</table>
 -->
				<div id="topPart" class="form-horizontal form-flat">
					<?php foreach($results as $data):?>
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group input-string-group">
								<label class="control-label">Patent Owner:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
							</div>
							<div class="form-group">
								<label class="control-label">Address:</label>
								<div><?php echo $data['litigation']->address;?></div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group input-string-group">
								<label class="control-label">Relates To:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->relates_to;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group">
								<label class="control-label">Person Name1:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_name_1;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group">
								<label class="control-label">Person Title1:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_title_1;?>" readonly="readonly">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group input-string-group">
								<label class="control-label">Number of Patents:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_prospects;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group">
								<label class="control-label">Person Name2:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_name_2;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group">
								<label class="control-label">Person Title2:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_title_2;?>" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="row">
				  		<div class="col-xs-4">
							<div class="form-group input-string-group">
								<label class="control-label">
									Name of Lead:
								</label>
								<input type="text" class="form-control" tabindex="11" readonly="readonly">
							</div>
				  		</div>
				  	</div>
					<?php endforeach;?>
				</div>

				<?php } else {?>

					<p class="alert">No record found!</p>

				<?php }?>

			</div>			

			<div class="example-box-wrapper mrg10T">

				<div class="table-responsive">

					<h3 class="title-hero">User comment list</h3>		

					<table class="table table-hover" id='comment-list'>

						<thead>

							<tr>

								<th width="15%">Comment By</th>

								<th>Comment</th>

								<th width="10%">Attractiveness</th>

								

							</tr>

						</thead>

						<tbody>

							<?php /* if(count($results)>0){?>

							<tr>

								<td><?php echo $results[0]['litigation']->userName;?></td>

								<td>

									<?php echo $results[0]['litigation']->comment;?>

								</td>

								<td></td>

							</tr>

							<?php } */ ?>

							<?php if(count($results)>0){ if(count($results[0]['comment'])>0){?>

							<?php foreach($results[0]['comment'] as $comment):?>

							<tr>

								<td width="15%"><?php echo $comment->name?></td>

								<td ><?php echo $comment->comment?></td>

								<td width="10%"><span class="label alert <?php if($comment->attractive=='High'):?>label-success<?php elseif($comment->attractive=='Medium'):?>label-primary<?php elseif($comment->attractive=='Low'):?>label-warning<?php else:?>label-danger<?php endif;?>"><?php echo $comment->attractive?></span></td>					

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

			

			<div class="example-box-wrapper text-center">

				<p>

					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->

					<button  type="button" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK')" class="btn btn-primary  btn-sm">+ Email Proposal</button>

					<button class="btn btn-primary  btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>

					<button class="btn btn-primary  <?php if((int)$results[0]['litigation']->status==0):?><?php else:?><?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>

					<!--<button class="btn btn-primary btn-sm" onclick="" type="button">Save</button>-->
					
					<span style='display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>

					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->
				</p>

			</div>

			<?php } ?>

		</div>

	</div>

</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>

<script>

_res = "";

	jQuery("#btnApproved").click(function(){

		if(jQuery(this).attr('disabled')==undefined){

			jQuery.ajax({

				type:'POST',

				url:'<?php echo $Layout->baseUrl?>leads/change_status_lead',

				data:{token:jQuery("#otherParentId").val()},

				cache:false,

				success:function(res){

					_data = jQuery.parseJSON(res);

					if(parseInt(_data.rows)==0){

						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-primary').addClass('btn-default');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').removeClass('btn-default').addClass('btn-primary');

					}

				}

			});

		}

	});

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'General'},

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

						jQuery("#btnApproved").html('Approved Lead');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved');

					}

					// _tr ='<tr>'+

					// 	'<td><b>Owner: </b> '+_data.plantiffs_name+'</td>'+

					// 	'<td><b>No of Patents: </b> '+_data.no_of_prospects+'</td>'+

					// 	'<td><b>Relates To: </b> '+_data.relates_to+'</td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name1: </b> '+_data.person_name_1+'</td>'+

					// 	'<td><b>Person Title1: </b> '+_data.person_title_1+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name2: </b>'+_data.person_name_2+'</td>'+

					// 	'<td><b>Person Title2: </b> '+_data.person_title_2+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name3: </b> '+_data.person_name_3+'</td>'+

					// 	'<td><b>Person Title3: </b> '+_data.person_title_3+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td colspan="2"><b>Address: </b>'+_data.address+'</td>'+

					// 	'<td><b>Portfolio Number: </b>'+_data.portfolio_number+'</td>'+

					// '</tr>';

					_tr = '<div class="row">' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Patent Owner:</label>' +
										'<input type="text" class="form-control" value="'+_data.plantiffs_name+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group">' +
										'<label class="control-label">Address:</label>' +
										'<div>'+_data.address+'</div>' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Relates To:</label>' +
										'<input type="text" class="form-control" value="'+_data.relates_to+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Person Name1:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_name_1+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Person Title1:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_title_1+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Number of Patents:</label>' +
										'<input type="text" class="form-control" value="'+_data.no_of_prospects+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Person Name2:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_name_2+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">Person Title2:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_title_2+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="row">' +
						  		'<div class="col-xs-4">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">' +
											'Name of Lead:' +
										'</label>' +
										'<input type="text" class="form-control" tabindex="11" readonly="readonly" style="width: 30px;">' +
									'</div>' +
						  		'</div>' +
						  	'</div>';

					// jQuery("#record-list>tbody").html(_tr);
					jQuery('#topPart').html(_tr);

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

					

					/*Comments*/

					_trData = "";

					response = response.results[0].comment;

					if(response.length>0){	

						/*_trData ='<tr>'+

										'<td width="15%">'+response.results[0].litigation.userName+'</td>'+

										'<td>'+response.results[0].litigation.comment+'</td>'+

										'<td width="10%"></td>'+

									'</tr>';*/

						for(i=0;i<response.length;i++){

							_label = '';

							if(response[i].attractive=="High"){

								_label = 'label-success';

							} else if(response[i].attractive=="Medium"){

								_label = 'label-primary';

							} else if(response[i].attractive=="Low"){

								_label = 'label-warning';

							} else if(response[i].attractive=="Disapproved"){

								_label = 'label-danger';

							}

							_trData+='<tr>'+

										'<td width="15%">'+response[i].name+'</td>'+

										'<td>'+response[i].comment+'</td>'+

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

		_number = jQuery("#record-list>tbody>tr").eq(0).find('td').eq(0).html();

		_names = _number.split("<b>Owner: </b>");

		if(_names.length>1){

			_names = jQuery.trim(_names[1]);

		}

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',

			data:{name:_names,type:'General'},

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

</script>



