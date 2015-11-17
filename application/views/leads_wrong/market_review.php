<div class="row"><?php echo $Layout->element('task');?><div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<div class="panel dashboard-box">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Review</a></li><li class='active'>From Market</li>");
});
</script>
    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="table-responsive">

				<div class="" style='margin-bottom:5px;'>

					<?php 

						$previous = "disabled='disabled'";

						if((int)$current_page>1){						

							$previous ="";

						}

						$next = "disabled='disabled'";

						

						if($total_rows>1 && ($current_page+1)<=$no_of_pages){

							$next ="";

						}

					?>

					<!--<button type="button" id="prev" onclick="record('prev');" <?php echo $previous;?> class="glyph-icon tooltip-button demo-icon icon-angle-left"><i class="fa fa-chevron-left"></i></button>

					<button type="button" id="next" onclick="record('next');" <?php echo $next;?> class="glyph-icon tooltip-button demo-icon icon-angle-right"><i class="fa fa-chevron-right"></i></button>-->

					<ul class="pager">
                        <li class="next"><a href="javascript:void(0)" <?php echo $next;?> onclick="record('next');" >Next <i class="glyph-icon icon-angle-right"></i></a></li>
                        <li class="previous"><a href="javascript:void(0)" <?php echo $previous;?> onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i> Previous</a></li>
                    </ul>

				</div>

				<?php 

					if(count($results)>0){												

				?>

				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />

				<!-- <table class="table" id='record-list'>

					<tbody>

						<?php foreach($results as $data):?>

						<tr>
							<td><b>Seller:</b> </td>
							<td><b>No of Prospects:</b> </td>
							<td><b>Expected Price:</b> </td>
						</tr>
						<tr>
							<td><b>Technologies/Markets:</b> </td>
							<td><b>Prospect Name:</b> </td>
							<td></td>
						</tr>

						<?php endforeach;?>

					</tbody>

				</table> -->


				<div class="row">
					<div class="col-xs-10">
						<div class="row">
							<div class="col-sm-4">
								<div class="row">
									<label class="col-sm-12 control-label" for="marketOwner"><strong>Seller:</strong></label>
									<div class="col-sm-12">
										<?php echo $data['litigation']->plantiffs_name;?>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="row">
									<label class="col-sm-12 control-label" for="marketTechnologies"><strong>Tech. / Markets:</strong></label>
									<div class="col-sm-12">
										<?php echo $data['litigation']->technologies;?>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row">
									<label class="col-sm-12 control-label" for="marketProspects"><strong>N. of Prospects:</strong></label>
									<div class="col-sm-12">
										<?php echo $data['litigation']->no_of_prospects;?>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row">
									<label class="col-sm-12 control-label" for="marketExpectedPrice"><strong>Expected Price:</strong></label>
									<div class="col-sm-12">
										<?php echo $data['litigation']->expected_price;?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-2">
						<div class="row">
							<label class="col-sm-12 control-label" for="marketOwner"><strong>Prospect Name:</strong></label>
							<div class="col-sm-12">
								<?php echo $data['litigation']->prospects_name;?>
							</div>
						</div>
					</div>
				</div>

				<?php } else {?>

					<p class="alert">No record found!</p>

				<?php }?>

			</div>

			

			<?php 

				if(count($results)>0){								

					$litigationID = $results[0]['litigation']->id;

					

			?>

			<div class="widget-content padding">

				<div id="horizontal-form">

				<?php echo form_open('leads/comment',array('class'=>'form-horizontal bordered-row form-flat','role'=>'form','id'=>'leadComment'));?>

					<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $litigationID;?>"/>

					<?php 
						$commentID = 0;
						$commentText ="";
						$commentAttractive = "";
						$commentUser = 0;
						$creattorCommentText = "";
						$creattorAttractive = "";
						$creattorUser = "";
						$creattorDate = "";
						if(count($results)>0){
							if(count($results[0]['comment'])>0){
								foreach($results[0]['comment'] as $comment):
									if($comment->user_id==$this->session->userdata['id']){
										$commentID = $comment->id;
										$commentText = $comment->comment;
										$commentAttractive = $comment->attractive;
										$commentUser = $comment->user_id;
									}
									if($results[0]['litigation']->user_id==$comment->user_id){
										$creattorCommentText = $comment->comment;
										$creattorAttractive = $comment->attractive;
										$creattorUser = $comment->name;
										$creattorDate = $comment->created;
									}
								endforeach;
							}
						}
					?>
					<?php if($results[0]['litigation']->user_id!=$this->session->userdata['id']):?>
						<div class="table-responsive">
						<h3 class="title-hero">User comment list</h3>		
						<table class="table table-hover" id='comment-list'>
							<thead>
								<tr>
									<th width="15%">Comment By</th>
									<th>Comment</th>
									<th>Date</th>
									<th width="10%">Attractiveness</th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="15%"><?php echo $creattorUser?></td>
									<td><?php echo $creattorCommentText?></td>
									<td><?php echo date('M d',strtotime($creattorDate));?></td>
									<td class="text-right" width="10%"><span class="label alert <?php if($creattorAttractive=='High'):?>label-success<?php elseif($creattorAttractive=='Medium'):?>label-primary<?php elseif($creattorAttractive=='Low'):?>label-warning<?php elseif($creattorAttractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $creattorAttractive?></span></td>					
								</tr>
							</tbody>
						</table>
						</div>					
					<?php endif;?>

					<div class="row mrg10T">
						<div class="col-sm-10 " style=''>
							<div class="form-group">
							<label class="sr-only" for="litigationCaseName">Comment</label>
							<div class="lbl">Notes:</div><br>
							<div class="mrg5T"></div>
								<?php echo form_textarea(array('name'=>'other[comment]','id'=>'litigationComment','placeholder'=>'','class'=>'form-control','rows'=>'5','value'=>$commentText));?>				
							</div>
						</div>
						<div class="col-xs-2">				
							<div class="form-group" style="margin-top:23px;">
								<label class="sr-only" for="litigationCaseName">Attractive</label>						
								<select name="other[attractive]" class="form-control" required="required">
									<option value="">Attractiveness</option>
									<option <?php if($commentAttractive=='High'):?>SELECTED="SELECTED"<?php endif;?> value="High">High</option>
									<option <?php if($commentAttractive=='Medium'):?>SELECTED="SELECTED"<?php endif;?> value="Medium">Medium</option>
									<option  <?php if($commentAttractive=='Low'):?>SELECTED="SELECTED"<?php endif;?>value="Low">Low</option>
									<option  <?php if($commentAttractive=='Disapproved'):?>SELECTED="SELECTED"<?php endif;?>value="Disapproved">Disapproved</option>
								</select>			
							</div>	
						</div>
					</div>
					<div class="col-xs-12">
						<div class="col-sm-5">
							<div class="form-group">
								<label style="float:left;" class="control-label"><strong>Name of Lead:</strong></label>
								<input type="text" style="float: left; margin-left:5px; width: 66.6666%;" value="<?php echo $results[0]['litigation']->lead_name;?>" readonly="readonly" class="form-control input-string" placeholder="" id="marketlead_name" required="required" value="">
							</div>
						</div>
						<div class="col-sm-2"></div>
						<div class="col-sm-5">
							<div class="form-group text-right">
								<div style="display: inline-block; margin-top: 7px; margin-right: 30px;">
									<!--<input type="checkbox" value="1"> Complete-->
									<input type="hidden" class="form-control" id="litigationScrapperData">
								</div>
								<input type="hidden" name="other[type]" value="Market"/>
								<input type="hidden" name="other[id]" id="commentID" value="<?php echo $commentID;?>"/>
								<button type="button" onclick="userComment();" class="btn btn-primary float-right">Save</button>							
							</div>
						</div>
					</div>
				<?php echo form_close();?>				

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

			<?php } ?>			

		</div>

	</div>

</div></div><?php echo $Layout->element('timeline');?></div>

<script>

_res = "";

_senddt = '<?php echo $this->session->userdata['id'];?>';

	function userComment(){

		_form = jQuery("#leadComment").serialize();

		jQuery.ajax({

			url:'<?php echo $Layout->baseUrl?>leads/comment',

			type:'POST',

			data:_form,

			cache:false,			

			success:function(response){

				response = jQuery.parseJSON(response);

				if(response.message!=undefined){

					jQuery("#horizontal-form").before('<p class="alert alert-success">'+response.message+'</p>');

					jQuery("#commentID").val(response.id);

				} else if(response.error!=undefined){

					jQuery("#horizontal-form").before('<p class="alert alert-danger">'+response.message+'</p>');

				}

				//jQuery("#leadComment")[0].reset();				

			}

		});

	}

	

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

					

					_flag= 0;

					for(i=0;i<response.results[0].comment.length;i++){

						_comment = response.results[0].comment[i];

						if(_comment.user_id==_senddt){

							_flag = 1;

							_commentID = _comment.id;

							_commentText = _comment.comment;

							_commentAttractive = _comment.attractive;

							jQuery("#litigationComment").val(_commentText);

							jQuery("#commentID").val(_commentID);

							jQuery("select[name='other[attractive]']").find('option').each(function(){ 

								if(jQuery(this).attr('value')==_commentAttractive){

									jQuery(this).attr('SELECTED','SELECTED');

								} else {

									jQuery(this).removeAttr('SELECTED');

								}

							});

						}

						if(_comment.user_id==_data.user_id){

							/*Creator Comment*/

							jQuery("#comment-list>tbody").html('<tr>'+
									'<td width="15%">'+_comment.name+'</td>'+
									'<td>'+_comment.comment+' </td>'+
									'<td>'+$.datepicker.formatDate('M dd', new Date(_comment.created))+'</td>'+
									'<td class="text-right" width="10%"><span class="label alert label-success">'+_comment.attractive+'</span></td>	'+				
								'</tr>');

							
						}						

					}

					if(_flag==0){

						jQuery("#litigationComment").val('');

						jQuery("#commentID").val(0);

						jQuery("select[name='other[attractive]']").find('option').each(function(){ 

							if(jQuery(this).attr('value')==''){

								jQuery(this).attr('SELECTED','SELECTED');

							} else {

								jQuery(this).removeAttr('SELECTED');

							}

						});

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
				}

			}

		});

	}

</script>