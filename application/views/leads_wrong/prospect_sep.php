<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<div class="panel dashboard-box">
    <div class="panel-body">
	<script>
		jQuery(document).ready(function(){
			jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Create</a></li><li class='active'>Proactive SEP</li>");
		});
		_senddt = '<?php echo $this->session->userdata['id'];?>';
					___data = '';

					function record(level){
						jQuery("#formProspectSep").get(0).reset();
						jQuery.ajax({

							type:'POST',

							url:'<?php echo $Layout->baseUrl?>leads/litigation_record_incomplete',

							data:{token:jQuery("#token").val(),level:level,type:'SEP'},

							cache:false,

							success:function(response){				

								response = jQuery.parseJSON(response);

								___data = response;

								if(response.results.length>0){

									_data = response.results[0].litigation;	

									if(jQuery("#otherParentId").length>0){

										jQuery("#otherParentId").val(_data.id);

									}

									if(jQuery("#sendLitigation").length>0){

										jQuery("#sendLitigation").val(_data.id);

									}

									jQuery("#generalOwner").val(_data.plantiffs_name);
									jQuery("#generalRelatesTo").val(_data.relates_to);
									jQuery("#generalProspects").val(_data.no_of_prospects);
									jQuery("#generalAddress").val(_data.address);
									jQuery("#generalPersonName1").val(_data.person_name_1);
									jQuery("#generalPersonName2").val(_data.person_name_2);
									jQuery("#generalPersonTitle1").val(_data.person_title_1);
									jQuery("#generalPersonTitle2").val(_data.person_title_2);
									jQuery("#generallead_name").val(_data.lead_name);
									jQuery("#generalId").val(_data.id);
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

									_flag= 0;

									for(i=0;i<response.results[0].comment.length;i++){

										_comment = response.results[0].comment[i];

										if(_comment.user_id==_senddt){

											_flag = 1;

											_commentID = _comment.id;

											_commentText = _comment.comment;

											_commentAttractive = _comment.attractive;

											jQuery("#generalComment").val(_commentText);

											jQuery("#commentID").val(_commentID);

											jQuery("select[name='general[attractive]']").find('option').each(function(){ 

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

										jQuery("#generalComment").val('');

										jQuery("#commentID").val(0);

										jQuery("select[name='general[attractive]']").find('option').each(function(){ 

											if(jQuery(this).attr('value')==''){

												jQuery(this).attr('SELECTED','SELECTED');

											} else {

												jQuery(this).removeAttr('SELECTED');

											}

										});

									}					

								}

							}

						});

					}
				</script>
	</script>
		<div class="example-box-wrapper">
			<?php 
				if($this->session->flashdata('message')){
			?>
				<p class='alert alert-success'><?php echo $this->session->flashdata('message');?></p>
			<?php					
				}
			?>
			<?php 
				if($this->session->flashdata('error')){
			?>
				<p class='alert alert-danger'><?php echo $this->session->flashdata('error');?></p>
			<?php					
				}
			?>
			<div class="" style='margin-bottom:5px;'>				
				<ul class="pager">
					<li class="next"><a href="javascript:void(0)" onclick="record('next');" >Next <i class="glyph-icon icon-angle-right"></i></a></li>
					<li class="previous"><a href="javascript:void(0)" onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i> Previous</a></li>
				</ul>					
			</div>
			<?php echo form_open('leads/proactive_sep',array('id' => 'formProspectSep', 'class'=>'form-horizontal form-flat','role'=>'form'));?>
				<div class="row">
					<div class="col-xs-4">
						<div class="form-group">
							<label for="generalOwner" class="control-label">Patent Owner:</label>
							<?php echo form_input(array('name'=>'general[plantiffs_name]','id'=>'generalOwner','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:67%"));?>
						</div>
						<div class="row mrg10T">
							<!-- <label for="generalProspectsName" class="col-sm-2 control-label">Address:</label> -->
							<div class="col-sm-12">
								<?php echo form_textarea(array('name'=>'general[address]','id'=>'generalAddress','placeholder'=>'Address','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:53px !important'));?>
							</div>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="col-xs-12">
							<label for="generalProspects" class="control-label">Relates To:</label>
							<?php echo form_input(array('name'=>'general[relates_to]','id'=>'generalRelatesTo','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:72%"));?>
						</div>
						<div class="col-xs-12 mrg5T">
							<label for="generalExpectedPrice" class="control-label">Person Name1:</label>
							<?php echo form_input(array('name'=>'general[person_name_1]','id'=>'generalPersonName1','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:63%"));?>
						</div>
						<div class="col-xs-12 mrg5T">
							<label for="generalTechnologies" class="control-label">Person Title1:</label>
							<?php echo form_input(array('name'=>'general[person_title_1]','id'=>'generalPersonTitle1','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:67%"));?>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="form-group">
							<label for="generalProspects" class="control-label">Number of Patents:</label>
							<?php echo form_input(array('name'=>'general[no_of_prospects]','id'=>'generalProspects','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:55%"));?>
						</div>
						<div class="form-group">
							<label for="generalExpectedPrice" class="control-label">Person Name2:</label>
							<?php echo form_input(array('name'=>'general[person_name_2]','id'=>'generalPersonName2','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:63%"));?>
						</div>
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">Person Title2:</label>
							<?php echo form_input(array('name'=>'general[person_title_2]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control input-string',"style"=>"width:67%"));?>
						</div>
						<!-- <div class="form-group">
							<label for="generalExpectedPrice" class="control-label">Person Name3:</label>
							<?php echo form_input(array('name'=>'general[person_name_3]','id'=>'generalPersonName3','placeholder'=>'','class'=>'form-control input-string'));?>
						</div>
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">Person Title3:</label>
							<?php echo form_input(array('name'=>'general[person_title_3]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control input-string'));?>
						</div> -->
					</div>
				</div>
				
				<div class="row mrg10T">
					<div class="col-xs-10">
						<div class="row">
							<!-- <label class="col-sm-2 control-label" for="litigationLinkToPacer">Comment</label> -->
							<div class="col-sm-12">
								<?php echo form_textarea(array('name'=>'general[comment]','id'=>'generalComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>29));?>	
							</div>
						</div>
					</div>
					<div class="col-xs-2">
						<select required="required" class="form-control" name="general[attractive]">
							<option value="">Attractiveness</option>
							<option value="High">High</option>
							<option value="Medium">Medium</option>
							<option value="Low">Low</option>
							<option value="Disapproved">Disapproved</option>
						</select>
					</div>
				</div>				
			  	<div class="row mrg10T">
			  		<div class="col-xs-5">
						<div class="form-group">
							<label for="generalTechnologies" class="control-label"><strong>Name of Lead:</strong></label>
							<?php echo form_input(array('name'=>'general[lead_name]','id'=>'generallead_name','placeholder'=>'','class'=>'form-control input-string'));?>
						</div>
			  		</div>
					<div class="col-xs-7 text-right">
						<div style="display: inline-block; margin-top: 7px; margin-right: 30px;">
							<input type="checkbox" value="1" name="general[complete]"> Lead is complete and ready to be forwarded for review					
							<input type="hidden" name="general[id]" value="0" id="generalId"/>
							<input type="hidden" name="other[id]" value="0" id="commentID"/>
						</div>
					  	<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div>
			  	</div>
			<?php echo form_close()?>
			<input type="hidden" name="token" id="token" value="0" />
		</div>
	</div>
</div>

</div>
<?php echo $Layout->element('timeline');?>
</div>
