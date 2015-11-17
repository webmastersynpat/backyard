<div class="row"><?php echo $Layout->element('task');?><div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<div class="panel dashboard-box">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>From Market</li>");
});
</script>
    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="table-responsive">

				<div class="" style='margin-bottom:5px;'>

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

					<ul class="pager">
                        <li class="next"><a href="javascript:void(0)" <?php echo $next;?> onclick="record('next');" >Next <i class="glyph-icon icon-angle-right"></i></a></li>
                        <li class="previous"><a href="javascript:void(0)" <?php echo $previous;?> onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i> Previous</a></li>
                    </ul>

				</div>

				<?php 

					if(count($results)>0){												

				?>

				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />

				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>

				<table class="table" id='record-list'>

					<tbody>

						<?php foreach($results as $data):?>

						<tr>
							<td><b>Seller:</b> <?php echo $data['litigation']->plantiffs_name;?></td>
							<td><b>No of Prospects:</b> <?php echo $data['litigation']->no_of_prospects;?></td>
							<td><b>Expected Price:</b> <?php echo $data['litigation']->expected_price;?></td>
						</tr>
						<tr>
							<td><b>Technologies/Markets:</b> <?php echo $data['litigation']->technologies;?></td>
							<td><b>Prospect Name:</b> <?php echo $data['litigation']->prospects_name;?></td>
							<td></td>
						</tr>

						<?php endforeach;?>

					</tbody>

				</table>

				<?php } else {?>

					<p class="alert">No record found!</p>

				<?php }?>

			</div>			

			<div class="example-box-wrapper" style="margin-top: 10px;">

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

								<td><?php echo $comment->comment?></td>

								<td width="10%" class="text-right"><span class="label alert <?php if($comment->attractive=='High'):?>label-success<?php elseif($comment->attractive=='Medium'):?>label-primary<?php elseif($comment->attractive=='Low'):?>label-warning<?php else:?>label-danger<?php endif;?>"><?php echo $comment->attractive?></span></td>					

							</tr>

							<?php endforeach;?>

							<?php } }

							?>							

						</tbody>

					</table>

				</div>

			</div>			
			<div class="col-sm-12 pull-left" style="padding:0px" >
					<?php 
						if(count($results)>0){
							if(count($results[0]['market'])>0){
					?>
							<h3>Content</h3>
							<table class='table table-bordered' id="boxesList">
								<tbody>
					<?php
								foreach($results[0]['market'] as $box){
					?>
									<tr>
										<?php 
											if($box['type']=="Message"){
												$subject = "";
												$date = "";
												foreach($box['header'] as $header){
													if($header->name=="Subject"){
														$subject = $header->value;
													}
													if($header->name=="Date"){
														$date = $header->value;
													}
												}
										?>
											<td><i class='glyph-icon icon-envelope'> <?php echo $subject;?></i></td>
											<td style='width:100px;'><?php echo date('M d',strtotime($date));?></td>
										<?php
											} else if($box['type']="Attachment"){
												
												$date = "";
												foreach($box['header'] as $header){
													if($header->name=="Date"){
														$date = $header->value;
													}
												}
										?>
												<td><i class='glyph-icon icon-file'></i> <a href='https://mail.google.com/mail/u/0/?ui=2&view=att&th=<?php echo $box['message_id'];?>&disp=safe&realattid=<?php echo $box['realAttachID'];?>' target="_blank"><?php echo $box['filename'];?></a></td>
												<td style='width:100px;'><?php echo date('M d',strtotime($date));?></td>
										<?php
											}
										?>
									</tr>
					<?php
								}
					?>
								</tbody>
							</table>
					<?php
							}
						}
					?>
					
				</div>
				</div>
			

			<?php 



					if(count($results)>0){		

			?>

			

			<div class="example-box-wrapper text-center">

				<p>
					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->

					<button  type="button" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK')" class="btn btn-primary btn-sm">+ Email Proposal</button>

					<button class="btn btn-primary btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>

					<button class="btn btn-primary<?php if((int)$results[0]['litigation']->status==0):?><?php else:?><?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>
					<span style='display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>					

					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->
				</p>

			</div>

			<?php } ?>

		</div>

	</div>

</div>
</div><?php echo $Layout->element('timeline');?></div>
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

						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-sm');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').addClass('btn-sm');

					}

				}

			});

		}

	});

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'Market'},

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

						jQuery("#btnApproved").html('Approved Lead').addClass('btn-sm');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').addClass('btn-sm');

					}

					_tr ='<tr>'+

							'<td><b>Seller:</b> '+_data.plantiffs_name+'</td>'+

							'<td><b>No of Prospects:</b> '+_data.no_of_prospects+'</td>'+

							'<td><b>Expected Price:</b> '+_data.expected_price+'</td>'+

						'</tr>'+	

						'<tr>'+

							'<td><b>Technologies/Markets:</b> '+_data.technologies+'</td>'+

							'<td><b>Prospect Name:</b> '+_data.prospects_name+'</td>'+

						'</tr>';				

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

					jQuery("#boxesList>tbody").empty();
					if(response.results[0].market.length>0){
						for(i=0;i<response.results[0].market.length;i++){
							_d = response.results[0].market[i];
							_header = _d.header;
							_showData = "";
							_receivedDate = "";
							if(_d.type=="Message"){
								if(_d.header.length>0){
									for(h=0;h<_d.header.length;h++){
										if(_d.header[h].name=="Subject"){
											_showData="<i class='glyph-icon icon-envelope'></i> "+_d.header[h].value;
										}
										if(_d.header[h].name=="Date"){
											_receivedDate = _d.header[h].value;																
										}
									}
								}
							} else if(_d.type=="Attachment"){
								if(_d.header.length>0){
									for(h=0;h<_d.header.length;h++){
										if(_d.header[h].name=="Date"){
											_receivedDate = _d.header[h].value;																
										}
									}
								}
								_showData="<i class='glyph-icon icon-file'>"+_d.filename;
							}
							if(_showData!=""){
								_tr = '<tr><td>'+_showData+'</td><td style="width:100px;">'+$.datepicker.formatDate('M dd', new Date(_receivedDate))+'</td></tr>';
								jQuery("#boxesList>tbody").append(_tr);
							}							
						}
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

		_names = _number.split("<b>Seller:</b>");

		if(_names.length>1){

			_names = jQuery.trim(_names[1]);

		}

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',

			data:{name:_names,type:'Market'},

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



