<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<style>
.todo-box li.active{
	background:#2196F3 !important;color:#FFF;
}
.todo-box li.active a{
	color:#FFF;
}

.dropdown dd, .dropdown dt {
    margin:0px;
    padding:0px;
}
.dropdown ul {
    margin: -1px 0 0 0;
}
.dropdown dd {
    position:relative;
}
.dropdown a, 
.dropdown a:visited {
    color:#000;
    text-decoration:none;
    outline:none;
    font-size: 12px;
}
.dropdown dt a {
    background-color:#efefef;
    display:block;
    padding: 8px 20px 5px 10px;
    min-height: 25px;
    line-height: 24px;
    overflow: hidden;
    border:1px solid #eaeaea;
    width:205px;
}
.dropdown dt a span, .multiSel span {
    cursor:pointer;
    display:inline-block;
    padding: 0 3px 2px 0;
}
.dropdown dd ul {
    background-color: #efefef;
    border:0;
    color:#000;
    display:none;
    left:0px;
    padding: 2px 15px 2px 5px;
    position:absolute;
    top:2px;
    width:210px;
    list-style:none;
    height: 100px;
    overflow: auto;z-index:1;
}
.dropdown span.value {
    display:none;
}
.dropdown dd ul li a {
    padding:5px;
    display:block;
}
.dropdown dd ul li a:hover {
    background-color:#efefef;
}

#messages-boxlist .todo-box li {
	/*border: medium none !important;
    margin-bottom: 0;
    padding: 0;*/
}
#messages-boxlist .todo-box li label { 
	display: block;
}
#messages-boxlist .todo-box li a {
	display: block;
    padding-bottom: 4px;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 4px;
}

#mainPanelBox {
	overflow-y: scroll !important;
}

.overflow-link {
    overflow: hidden;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 106px;
}

</style>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/modal/modal.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/dialog/dialog.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/dialog/dialog-demo.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Create</a></li><li class='active'>From Regular</li>");
    jQuery("#taskType").val('Market');
});
</script>
<script>
    function findWorksheetMarket(o,p){
			/*v = o.find('option:selected').val();
			t = o.find('option:selected').text();*/
			v = o.val();	
			if(v!="" && v!=undefined){
				jQuery.ajax({
					url:'<?php echo $Layout->baseUrl?>leads/findWorksheetList',
					type:'POST',
					data:{v:v},
					cache:false,
					success:function(data){
						_d = jQuery.parseJSON(data);
						if(_d!=undefined  && _d.length>0){
							
							jQuery("#marketWorksheetId").empty().append("<option va;ue=''>-- Select Worksheet --</option>");
							for(i=0;i<_d.length;i++){
								_selected="";
								if(typeof p == 'string' || typeof p =='number'){
									if(p==_d[i].id){
										_selected = "SELECTED='SELECTED'";
									}
								}
								jQuery("#marketWorksheetId").append("<option "+_selected+" value='"+_d[i].id+"' data-href='"+_d[i].full+"'>"+_d[i].text+"</option>");
							}
							/*if(typeof p == 'string' || typeof p =='number'){
								jQuery("#marketWorksheetId").attr("disabled","disabled");
							}*/
						}
					}
				});
			}
		}
		
		function findWorksheetUrlMarket(o){
			u = o.find('option:selected').attr('data-href');
			if(u!=""){
				jQuery("#marketFileUrl").val(u);
			}					
		}
		_mainMessageArray = "";
		function removeAlert(){
			jQuery('.alert-close-btn').click(function(e){
				e.preventDefault();
				jQuery(this).parent().remove();
			});
		}
					
		function passMessageOrLead(){
			jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
			_flag = 0;
			_type = "";
			_id = "";
			jQuery(".messages-list-leads").find('.message-item').each(function(){
				if(jQuery(this).hasClass('message-active')){
					_flag = 1;
					_type = "message";
					_id = jQuery(this).attr('data-id');
				}
			});
			if(_flag==0){
				jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){
					if(jQuery(this).hasClass('active')){
						_flag = 1;
						_type = "lead";
						_id = jQuery(this).attr('data-id');
					}
				});
			}
			if(_flag==0){
				html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message or lead to pass.</p></div>';
				jQuery("#contentPart").find('.alert').remove();
				jQuery("#contentPart>.panel").before(html);
				removeAlert();
			} else  {
				jQuery.ajax({
					type:'POST',
					url:'<?php echo $Layout->baseUrl?>leads/passLead',
					data:{type:_type,g:_id},
					cache:false,
					success:function(res){
						_data = jQuery.parseJSON(res);
						if(_data.send>0){
							window.location = window.location.href;
						} else {
							html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message or lead to pass.</p></div>';
							jQuery("#contentPart").find('.alert').remove();
							jQuery("#contentPart>.panel").before(html);
							removeAlert();
						}
					}
				});
			}
		}			
		jQuery(document).ready(function(){						
			$(".dropdown dt a").on('click', function () {
				$(".dropdown dd ul").slideToggle('fast');
			});

			$(".dropdown dd ul li a").on('click', function () {
				$(".dropdown dd ul").hide();
			});

			function getSelectedValue(id) {
			   return $("#" + id).find("dt a span.value").html();
			}
			$(document).bind('click', function (e) {
				var $clicked = $(e.target);
				if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
			});
			$('.mutliSelect input[type="checkbox"]').on('click', function () {				
				var title = $(this).closest('.mutliSelect').find('input[type="checkbox"]:checked').length;
				title = title+" selected";
				if ($(this).is(':checked')) {
					var html = '<span title="' + title + '">' + title + '</span>';
					$('.multiSel').html(html);
					$(".hida").hide();
				} else {
					var html = '<span title="' + title + '">' + title + '</span>';
					$('.multiSel').html(html);
					var ret = $(".hida");
					$('.dropdown dt a').append(ret);					  
				}
			});
		});
		_checkS ='<?php echo $this->session->userdata['type']?>';
		if(parseInt(_checkS)==9){
			_lastUri = '<?php echo $this->uri->segment('3')?>';
			if(parseInt(_lastUri)!=""){
				jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){
					if(parseInt(jQuery(this).attr('data-id'))== parseInt(_lastUri)){
						_aObject = jQuery(this).find('a');	
						if(_aObject!=undefined){
							threadDetail(_aObject);
						}
					}
				});
			}
		}
						
					
		function makeTask(){
			_flag = 0;
			_leadID = 0;
			jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
			jQuery("#messages-boxlist").find('ul.todo-box').find('li').each(function(){
				if(jQuery(this).hasClass('active')){
					_flag=1;
					_leadID = jQuery("#id").val();
				}
			});
			if(_flag==1 && _leadID>0){
				jQuery(".bs-example-modal-sm").modal();
				jQuery("#marketLeadId").val(_leadID);
				jQuery("#taskUserId").focus();
			} else {
				html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select lead first.</p></div>';
				jQuery("#contentPart").find('.alert').remove();
				jQuery("#contentPart>.panel").before(html);
				removeAlert();
			}						
		}
	</script>
<div id="mainPanelBox" class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper">
			<?php 
				if($this->session->flashdata('message')){
			?>
				<p class='alert alert-danger'><?php echo $this->session->flashdata('message');?></p>
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
			<style>
				.control-label{text-align:left;}
			</style>
			<div id="form_details">
			<?php echo form_open_multipart('leads/reply_email',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'','id'=>"marketLeadReply" ,"onsubmit"=>"return dataValidate();"));?>
			
            <div id="gmail_message" class="mrg20T mrg10B" style='display:none;'>
				<h4>Reply Message</h4>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<label class="col-sm-1 control-label" for="litigationLinkToPacer">To: </label>
							<div class="col-sm-11">
								<?php echo form_input(array('name'=>'email[to]','id'=>'emailTo','required'=>'required','placeholder'=>'Email','class'=>'form-control'));?>		
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<label class="col-sm-1 control-label" for="litigationLinkToPacer">Name: </label>
							<div class="col-sm-11">
								<?php echo form_input(array('name'=>'email[to_name]','id'=>'emailName','required'=>'required','placeholder'=>'Name','class'=>'form-control'));?>		
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<label class="col-sm-1 control-label" for="litigationLinkToPacer">Subject: </label>
							<div class="col-sm-11">
								<?php echo form_input(array('name'=>'email[subject]','id'=>'emailSubject','required'=>'required','placeholder'=>'Subject','class'=>'form-control'));?>		
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<label class="col-sm-1 control-label" for="litigationLinkToPacer">Attachment: </label>
							<div class="col-sm-11">
								<?php echo form_upload(array('name'=>'email[attachment]','id'=>'emailSubject','placeholder'=>'Subject','class'=>'form-control'));?>		
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<label class="col-sm-1 control-label" for="litigationLinkToPacer">Message: </label>
							<div class="col-sm-11">
								<?php echo form_textarea(array('name'=>'email[message]','id'=>'emailMessage','required'=>'required','placeholder'=>'Message','class'=>'form-control','rows'=>4,'cols'=>29));?>	
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pull-left">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="hidden" name="email[thread_id]" id="emailThreadId"/>
								<input type="hidden" name="email[message_id]" id="emailMessageId"/>
                                <!--input type="hidden" name="litigation[patent_data]" value="" id="litgationScrapperData"/-->								
								<button type="submit" class="btn btn-primary pull-right">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
			<?php echo form_open('leads/market',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>"marketLead","onsubmit"=>"return dataValidateMarket();"));?>
				<div class="">
					<div class="row row-width">
						<div class="col-xs-6">
							<div class="form-group input-string-group">
								<label for="marketProspectsName" class="control-label" style="float:left;"><strong>Lead Name:</strong></label>
								<?php echo form_input(array('name'=>'market[lead_name]','required'=>'required','id'=>'marketlead_name','placeholder'=>'','class'=>'form-control input-string'));?>
							</div>
						</div>
						<div class="col-xs-6">
							<div style="margin-right:-200px;">
								<div class="row row-width">
									<div class="col-width" style="width: 100px;">
										<div class="form-group input-string-group">
											<label for="marketProspects" class="control-label">Prospects:</label>
											<?php echo form_input(array('name'=>'market[no_of_prospects]','id'=>'marketProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
										</div>
									</div>
									<div class="col-width" style="width: 172px;">
										<div class="form-group input-string-group">
											<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
											<?php echo form_input(array('name'=>'market[expected_price]','id'=>'marketExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4'));?>
										</div>
									</div>
									<div class="col-width" style="width: 107px;">
										<div class="form-group input-string-group">
											<label for="marketNo_of_us_patents" class="control-label">US Patents:</label>
											<?php echo form_input(array('name'=>'market[no_of_us_patents]','type'=>'digit','id'=>'marketNo_of_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
										</div>
									</div>
									<div class="col-width" style="width: 135px;">
										<div class="form-group input-string-group">
											<label for="marketNo_of_non_us_patents" class="control-label">Non-US Patents:</label>
											<?php echo form_input(array('name'=>'market[no_of_non_us_patents]','id'=>'marketno_of_non_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-width" style="width:201px;">&nbsp;</div>
						<!-- <div class="col-width" style="width:180px;">&nbsp;</div> -->
						<div class="col-width" style="width:150px;">
							<input type="hidden" name="market[gmail_message_id]" id="lead_gmail_message_id" value=""/>
							<input type="hidden" name="market[id]" id="marketLeadId" value="0"/>
							<input type="hidden" name="comment[comment_id]" id="commentId" value="0"/>
                            <input type="hidden" name="market[patent_data]" value="" id="marketPatentData"/>
                            <input type="hidden" name="market[complete]" value="" id="marketComplete"/>
                            <input type="hidden" name="market[seller_info]" value="1" id="marketSellerInfo"/>
                            <input type="hidden" name="market[send_proposal_letter]" value="" id="marketProposal_letter"/>
                            <input type="hidden" name="market[create_patent_list]" value="" id="marketCreate_patent_list"/>
                            
							<button type="submit" class="btn btn-primary btn-mwidth pull-right" style="margin-right:-3px;">Save</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="loading-spinner" id="bottom_form_market" style='display:none;'>
                            <i class="bg-blue"></i>
                            <i class="bg-blue"></i>
                            <i class="bg-blue"></i>
                            <i class="bg-blue"></i>
                            <i class="bg-blue"></i>
                            <i class="bg-blue"></i>
                        </div>
					</div>
					<div class="row row-width">
						<div class="col-xs-12">
							<div class="row row-width">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label for="marketOwner" class="control-label" style="margin-left:2px;">Seller / Owner:</label>
										<?php echo form_input(array('name'=>'market[plantiffs_name]','id'=>'marketOwner','placeholder'=>'','class'=>'form-control',"tabindex"=>"1"));?>
									</div>
									<div class="form-group input-string-group">
										<label for="marketOwner" class="control-label" style="margin-left:2px;">Broker:</label>
										<?php echo form_input(array('name'=>'market[broker]','id'=>'marketBroker','placeholder'=>'','class'=>'form-control',"tabindex"=>"1"));?>
									</div>
									<div class="form-group mrg10T">
										<!-- <label for="generalProspectsName" class="col-sm-2 control-label">Address:</label> -->
										<div class="col-sm-12" style="padding-right: 10px;">
											<?php echo form_textarea(array('name'=>'market[address]','id'=>'marketAddress','placeholder'=>'Address','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:75px !important;',"tabindex"=>"4"));?>
										</div>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label for="marketProspects" class="control-label">Tech. / Markets:</label>
										<?php echo form_input(array('name'=>'market[relates_to]','id'=>'marketRelatesTo','placeholder'=>'','class'=>'form-control',"tabindex"=>"2"));?>
									</div>
									<div class="form-group input-string-group">
										<label for="marketExpectedPrice" class="control-label">Name, Title (1):</label>
										<?php echo form_input(array('name'=>'market[person_name_1]','id'=>'marketPersonName1','placeholder'=>'','class'=>'form-control',"tabindex"=>"5"));?>
									</div>
									<div class="form-group input-string-group">
										<label for="marketExpectedPrice" class="control-label">Name, Title (2):</label>
										<?php echo form_input(array('name'=>'market[person_name_2]','id'=>'marketPersonName2','placeholder'=>'','class'=>'form-control',"tabindex"=>"6"));?>
									</div>
									<div class="form-group mrg10T" style="padding-right:9px;">
										<select class="form-control" name="market[type]">
		                                    <option value="">Select Lead Type</option>
											<option value="Market">From Market</option>
											<option value="General">From Proactive General</option>
											<option value="SEP">From Proactive SEP</option>
										</select>
									</div>
								</div>
								<div class="col-width" style="padding-right:9px; width:200px;">
									<div id="litigation_doc_list" class="panel google-box-list" style="height:147px;overflow-y:scroll;overflow-x:hidden;">
										
									</div>
								</div>
							</div>
							<div class="row" style="padding-right:8px; padding-left:2px;">
								<!-- <label class="col-sm-1 control-label" for="litigationLinkToPacer">Comment: </label> -->
								<div class="col-sm-4">
									<label class="control-label" for="marketProspectsName">Are there >10 potential licensees? Who?</label>
									<?php echo form_textarea(array('name'=>'comment[comment1]','id'=>'commentComment1','class'=>'form-control','rows'=>4,'cols'=>15));?>	
								</div>
								<div class="col-sm-4">
									<label class="control-label" for="marketProspectsName">Will licensees want to pay the expected fee? Why?</label>
									<?php echo form_textarea(array('name'=>'comment[comment2]','id'=>'commentComment2','class'=>'form-control','rows'=>4,'cols'=>15));?>	
								</div>
								<div class="col-sm-4">
									<label class="control-label" for="marketProspectsName">Seller's concerns + Your general observations</label>
									<?php echo form_textarea(array('name'=>'comment[comment3]','id'=>'commentComment3','class'=>'form-control','rows'=>4,'cols'=>15));?>	
								</div>
							</div>
						</div>
						<div class="col-width" style="width: 154px;">
							<div class="clearfix" style="margin-top:3px;">
								<div class="todo-list-custom fright">
									<div class="row">
										<div class="col-sm-12" id="assign_task_market"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="assign_task_market(1);">Collect Seller's info</a><div id="loader_seller_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-12" id="request_for_proposal_market"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="sendRequestForProposalLetterMarket(1)">Send Proposal Letter</a><div id="loader_prospect_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-12" id="create_patent_list_market"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="spreadsheet_box_market();">Create Patent List</a><div id="loader_patent_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-12" id="forward_to_review_market"><a class="btn btn-default btn-mwidth" onclick="forward_to_review_market();" href="javascript:void(0);">Forward to Review</a><div id="loader_review_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-12" id="schedule1stCallMarket"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="scheduleCallMarket()">Schedule 1st Call</a></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-12" id="ndaTermSheetMarket"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="createPartNDATermsheetMarket()">NDA + TermSheet</a><div id="loader_NDA_market" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
									</div>
								</div>
							</div>
							<div class="mrg5T">
								<div style="margin-right:-1px;">
									<select  class="form-control pull-right" name="market[attractive]">
										<option value="">Attractiveness</option>
										<option value="High" >High</option>
										<option value="Medium">Medium</option>
										<option value="Low">Low</option>
										<option value="Disapproved">Disapproved</option> 
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
                
                <!-- <div class="row mrg5T">
					<div class="col-xs-12">
						<div class="form-group input-string-group nomr">
							<label for="marketProspectsName" class="control-label">Prospect's Names:</label>
							<?php echo form_input(array('name'=>'market[prospects_name]','id'=>'marketProspectsName','placeholder'=>"",'class'=>'form-control'));?>
						</div>
					</div>
				</div> -->
                
				<div class="row row-width mrg10T">
					<div class="col-xs-12">
						<div class="row mrg10T">
							<!-- <div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
									<label for="marketTechnologies" class="control-label" style="margin-top: 2px;">
										Select Spreadsheet:
									</label>
									<select name="market[spreadsheet_id]" id="marketSpreadsheetId" class="form-control" onchange="findWorksheet(jQuery(this));">
										<option value=""> Select Spreadsheet </option>
										<?php 
											foreach($listOfFiles as $files){
										?>
											<option value="<?php echo $files->id?>"><?php echo $files->title?></option>
										<?php
											}
										?>
									</select>
									<input type="hidden" class="form-control input-string"  name="market[file_url]" id="litigationFileUrl" value=""/>
								</div>
							</div> -->
							<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
									<label for="marketTechnologies" class="control-label" style="margin-top: 2px;">
										Select Worksheet:
									</label>
									<select name="market[worksheet_id]" id="marketWorksheetId" class="form-control" onchange="findWorksheetUrlMarket(jQuery(this))"></select>
									<input type="hidden" class="form-control input-string"  name="market[file_url]" id="marketFileUrl" value=""/>
									<input type="hidden" class="form-control input-string"  name="market[spreadsheet_id]" id="marketSpreadsheetId" value=""/>
								</div>
							</div>
						</div>
					</div>
					<div class="col-width" style="width:382px;">
						<div style="clear:both; margin-top:17px;" class="clearfix">
			            	<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="refreshHSTMarket()">
			            		<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
			            		Clear Table
			            	</a>
			            	<a href="#" id="loadingLink" class='link-blue pull-right' style='text-decoration:none;' onclick="findPatentFromSheetMarket()">
			            		<i class="glyph-icon icon-recycle" style="font-size:16px;"></i>
			            		Import / Update Data
			            	</a>
			            	&nbsp;
			            	<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;"></div>
			            	<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openPatentListMarket()">
			            		<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
			            		Open Patent List
			            	</a>
			            </div>
					</div>					
				</div>
			<?php echo form_close()?>
			
			<script>
					function backSwitchMarket(){
						jQuery("#scrap_patent_data_market").find('.clickakble').dblclick(function(){
							switchToEditMarket(jQuery(this));
						});
					}
					_editable =false;
					
					function switchToEditMarket(object)
					{
						_editable=false;
						switchBackMarket();						
						if(object.attr('id')!='Container_Edittable'  && jQuery("*:focus").attr('id')!="Container_Edittable"){
							_editable=true;
							_html = object.html();
							object.html("<input type='text' class='form-control' id='Container_Edittable' style='width:400px;'/>");
							object.find('input').val(_html).focus().click(function(){
								_editable=true;
							});
							backClickMarket();
						}						
					}
					function backClickMarket(){
						jQuery("#scrap_patent_data_market").click(function(event){
							if(jQuery(this).attr('id')!="Container_Edittable"){
								_editable=false;
								switchBackMarket();
							} else {
								_editable=true;
							}
						});
					}
					
					function switchBackMarket()
					{
						if(_editable==false && jQuery("*:focus").attr('id')!="Container_Edittable"){
							jQuery("#scrap_patent_data_market").find('.clickakble').each(function(){
								if(jQuery(this).find('#Container_Edittable').length>0){
									_val = jQuery(this).find('#Container_Edittable').val();
									jQuery(this).html(_val);
									jQuery(this).find('#Container_Edittable').remove();
								}
								jQuery("#scrap_patent_data_market").unbind("click");
							});
						}
					}
				_mainData = "";
				function findPatentFromSheetMarket(){
					if(jQuery("#marketFileUrl").val()!=""){
						snapGlobal= jQuery("#marketFileUrl").val();
						jQuery('#loadingLink').addClass('overflow-link');
						jQuery("#loadingLabel").html('<i style="color: rgb(34, 34, 34); position: static;" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A"></i>');
						jQuery.ajax({
							url:'<?php echo $Layout->baseUrl?>leads/googleSpreadSheet',
							type:'POST',
							data:{file_url:jQuery("#marketFileUrl").val()},
							cache:false,
							success:function(data){
								if(data!=""){
									jQuery('#loadingLink').removeClass('overflow-link');
									jQuery("#loadingLabel").html('');
									jQuery("#scrap_patent_data_market").find('tbody').empty();
									_data = jQuery.parseJSON(data);
									if(_data.length>0){
										for(i=0;i<_data.length;i++){
											_tr = jQuery("<tr/>");
											_columns = _data[i];
											for(j=0;j<_columns.length;j++){
												_class="";
												if(j==1 || j==2){
													_class="clickakble";
												}
												if(_columns[j]!=null){
													jQuery(_tr).append("<td class='"+_class+"'>"+_columns[j]+"</td>");
												} else {
													jQuery(_tr).append("<td class='"+_class+"'></td>");
												}
												if(j==0){
													if(_columns[j]!=null && _columns[j]!=""){
														var escaped = _columns[j];
														td = "<a href='javascript://' class='btn' onclick='getGooglePatent(\""+jQuery.trim(escaped)+"\")'>"+escaped+"</a>";
														jQuery(_tr).find('td').eq(0).html(td);
													}	
												}
											}
											jQuery("#scrap_patent_data_market").find('tbody').append(_tr);
										}
										backSwitchMarket();
									} else {
										jQuery("#scrap_patent_data_market").find('tbody').append("<tr><<td colspan='9'>No able to import data</td>/tr>");
									}									
								} else {
									jQuery('#loadingLink').removeClass('overflow-link');
									// jQuery("#loadingLabel").html('Error while importing');
									alert('Error while importing');
								}
							}
						});
					}
				}
				jQuery(document).ready(function(){
					
				});	
				function getGooglePatent(patent){
					if(patent!=""){
						jQuery("#scrapGoogleData").find('.pad15A').html('<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div><div id="scrapGooglePatent"></div>');
						jQuery("#loading_spinner_heading_google_scrap").css('display','block');
						jQuery("#scrapGoogleData").addClass("sb-active").animate({ textIndent:0}, {
																		step: function(now,fx) {
																		  $(this).css('transform','translate(-350px)');
																		},
																		duration:'slow'
																	},'linear');
						jQuery.ajax({
						url:'<?php echo $Layout->baseUrl?>leads/scrapData',
							type:'POST',
							data:{scrap_data:patent},
							cache:false,
							success:function(data){										
								jQuery("#loading_spinner_heading_google_scrap").css('display','none');									
								jQuery("#scrapGooglePatent").html(data);	
							}
						});
					}						
				}
				function dataValidateMarket(){
					_editable=false;
					switchBackMarket();
					mainArray = [];
					if($("#scrap_patent_data_market").find("tbody").find("tr").length>0){
						$("#scrap_patent_data_market").find("tbody").find("tr").each(function(){
							if(jQuery(this).find("td").length>1){
								_innerArray = [];
								jQuery(this).find("td").each(function(){
									if(jQuery(this).find('a').length>0){
										_innerArray.push(jQuery(this).find('a').html());
									} else {
										_innerArray.push(jQuery(this).html());
									}									
								});
								mainArray.push(_innerArray);						
							} else {
								_innerArray = [];
								$("#scrap_patent_data_market").find("th").each(function(){
									_innerArray.push(null);
								});
								mainArray.push(_innerArray);
							}
						});
					} else {
						_innerArray = [];
						$("#scrap_patent_data_market").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
					jQuery("#marketPatentData").val(JSON.stringify(mainArray));
					return true;
				}
                function refreshHSTMarket(){
					jQuery("#scrap_patent_data_market").find('tbody').empty();					
				}
			</script>
            <!--  Clear Button Patent Data -->
            
            <div class="mrg5T" style='margin-top:5px;width:100%;padding:0;' id="patent_data">				
				<div class="example-box-wrapper">					
					<table class="table table-bordered" id="scrap_patent_data_market">
						<thead>
							<tr>
								<th>Patent</th>
								<th>Notes</th>
								<th>Current Assignee</th>
								<th>Application</th>
								<th>Title</th>
								<th>Original Assignee</th>
								<th>Priority</th>
								<th>File</th>  
								<th>Family</th>
							</tr>
						</thead>
						<tbody>							
						</tbody>
					</table>
				</div>
			</div>
            
				<!--<div class="col-xs-12">
					<div id="list_boxes">
						
					</div>
				</div>-->
			</div>
		</div>
	</div>
</div>
</div>

<?php echo $Layout->element('timeline');?>
</div>

<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="create_spreadsheet_market">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">Create Spreadsheet</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="clearfix">
                            <label style="float:left;" class="control-label">SpreadSheet Name:</label>
                            <input type="text" name="spreadsheet" class='form-control'  id="spreadsheet" value="" required="required"/>
                        </div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                <button  class="btn btn-default" type="button" onclick="create_spreadsheet_market();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>

<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="scheduleCallMarket">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">Schedule Embed Code</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="clearfix">
                            <label style="float:left;" class="control-label">Insert Embed Code From Google:</label>
                            <textarea type="text" name="market[embed_code]" class='form-control' rows="4" cols="10"  id="marketEmbedCode" value="" required="required"></textarea>
                        </div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                <button  class="btn btn-default" type="button" onclick="saveScheduleCallMarket();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>
<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="nda_termsheet_market">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">NDA +  Termsheet</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group input-string-group nomr" id="nda_termsheet_html_market">
							
						</div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>				
            </div>
			</form>				
        </div>
	</div>
</div>
<script>
	// var makeTimelineXMLInterval = null,
	// 	mainPanelBoxWidth = 0;

	// function makeTimelineXML() {
	// 	$('#my-timeline').html('');
	// 	$('#my-timeline').timelinexml({ 
	// 		src : 'timeline.xml',
	// 		showLatest : false, 
	// 		selectLatest : false,
	// 		eventTagName : "event",
	// 		dateTagName : "date",
	// 		titleTagName : "title",
	// 		thumbTagName : "thumb",
	// 		contentTagName : "content",
	// 		linkTagName : "link",
	// 		htmlEventClassName : "timeline-event",
	// 		htmlDateClassName : "timeline-date",
	// 		htmlTitleClassName : "timeline-title",
	// 		htmlContentClassName : "timeline-content",
	// 		htmlLinkClassName : "timeline-link",
	// 		htmlThumbClassName : "timeline-thumb"
	// 	});
	// }

	// $(function() {
	// 	setTimeout(function() {
	// 		makeTimelineXML();

	// 		makeTimelineXMLInterval = setInterval(function() {
	// 			if($('#mainPanelBox').width() !== mainPanelBoxWidth) {
	// 				makeTimelineXML();
	// 				mainPanelBoxWidth = $('#mainPanelBox').width();
	// 			}
	// 		}, 700);

	// 	}, 1000);

	// 	$('#activity-btn, #task-btn').on('click', function() {
	// 		clearInterval(makeTimelineXMLInterval);
	// 		makeTimelineXML();

	// 		makeTimelineXMLInterval = setInterval(function() {
	// 			if($('#mainPanelBox').width() !== mainPanelBoxWidth) {
	// 				makeTimelineXML();
	// 				mainPanelBoxWidth = $('#mainPanelBox').width();
	// 			}
	// 		}, 700);
	// 	});
	// });

    function spreadsheet_box_market(){
        $("#loader_patent_market").show();
        jQuery('#spreadsheet').val(jQuery('#marketlead_name').val());
		jQuery('#create_spreadsheet_market').modal('show'); 
        $("#loader_patent_market").hide();       
    }
    
    function scheduleCallMarket(){
    	jQuery("#scheduleCallMarket").modal("show");
    	window.open("https://www.google.com/calendar/render?tab=mc&pli=1#h","_BLANK");
    }
    
	function save_embedCode(){
		var embed_code = jQuery('#marketEmbedCode').val();
		if(embed_code == ''){
			jQuery('#marketEmbedCode').css('border-color','#ff0000');
		}
		if(jQuery('#litigationId').val()!=""){
			jQuery("#loading_spinner_schedulecall").css('display','block');
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>index.php/leads/embedScheduleCall',
				data:{'embed_code':embed_code,lead_id:jQuery('#litigationId').val()},
				success:function(response){
					jQuery("#loading_spinner_spreadsheet").css('display','none');
					if(response==1){    
						jQuery("#schedule1stCallMarket").html(jQuery('#marketEmbedCode').val());
						jQuery('#scheduleCallMarket').modal('hide');
					}  else {
						alert("Please try after sometime.");   
					}              
				}
			});
		} else {
			alert("Please try after sometime.");   
		}	
	}
    function createPartNDATermsheetMarket(){
    	var lead_id = jQuery('#marketLeadId').val();
		if(lead_id!=0 && lead_id!=""){
			jQuery("#spinner-loader-nda-timesheet").css('display','block');  
           $("#loader_NDA_market").show();     
			jQuery.ajax({
				url:'<?php echo $Layout->baseUrl;?>leads/createNDATermsheet',
				type:'POST',
				data:{v:lead_id},
				cache:false,
				success:function(response){
					jQuery("#spinner-loader-nda-timesheet").css('display','none');     
					_response = jQuery.parseJSON(response);
                    $("#loader_NDA_market").hide();
					if(_response.error=="0"){
					   jQuery("#ndaTermSheetMarket").removeClass('btn-blink');
						jQuery("#nda_termsheet_html_market").html("<div class='col-lg-12'><ul class='todo-list-custom'><li><a href='"+_response.nda+"' target='_BLANK'>NDA</a></li><li><a href='"+_response.term_sheet+"' target='_BLANK'>Termsheet</a></li></ul></div>");
						jQuery("#nda_termsheet_market").modal("show");
                        jQuery("#ndaTermSheetMarket").html('<span class="date-style">' +moment( new Date(_response.date_created)).format('MM-D-YY')+"</span> NDA and TermSheet created");
					} else {
						alert("Please try after sometime.");
					}
				}
			});
		}
    }
	
	
    
    function create_spreadsheet_market(){
        
       jQuery("#marketCreate_patent_list").val('1');
       var lead_id = jQuery('#marketLeadId').val();
       
       
       var spreadsheet = jQuery('#spreadsheet').val();
       if(spreadsheet == "")
       {
         jQuery('#spreadsheet').css('border-color','#ff0000');
       }
       if(spreadsheet != '')
       {
        $("#loader_patent_market").show();
            jQuery.ajax({
                type:'POST',
                url:'<?php echo $Layout->baseUrl?>leads/createLeadPatentSpreadSheet',
                data:{'n':spreadsheet,'lead_id':lead_id,ds:1},
                success:function(response){
                    $("#loader_patent_market").hide();
                    obj = JSON.parse(response);
                    if(obj.url != '' && obj.error==0){  
                        jQuery("#marketCreate_patent_list").val('2');
    					$('#create_spreadsheet_market').modal('hide');
                        jQuery("#create_patent_list_market").find('a').addClass('btn-blink'); 
                        jQuery("#create_patent_list_market").html('<span class="date-style">'+moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> Patent file created"); 
                        window.open(obj.url,'_blank');
						jQuery("#marketSpreadsheetId").val(obj.spread_sheet_id);
						findWorksheetMarket(jQuery("#marketSpreadsheetId"));
                    }
                    else
                    {
                        alert(obj.message);   
                    }
                    //jQuery('#create_spreadsheet').modal('hide');
                }
            });
       }
       
       
    }
    
    function saveScheduleCallMarket(){
    	var lead_id = jQuery('#marketLeadId').val();
        var embedCode = jQuery('#litigationEmbedCode').val();
        if(embedCode==""){
    		jQuery('#litigationEmbedCode').css('border-color','#ff0000');
        }
    	if(embedCode!=''){
            jQuery.ajax({
                type:'POST',
                url:'<?php echo $Layout->baseUrl?>leads/embedScheduleCall',
                data:{'n':embedCode,'lead_id':lead_id,ds:1},
                success:function(response){
                    if(response=="1"){                    
                        jQuery("#schedule1stCallMarket").html(embedCode);
    					jQuery("#schedule1stCallMarket").modal("hide");
                    }  else {
                        alert("Please try after sometime.");   
                    }
                }
            });
        }
    }
    
    function assign_task_market(type){
        var lead_id = jQuery('#marketLeadId').val();
		jQuery("#marketSellerInfo").val(type)
        var seller_info = jQuery("#marketSellerInfo").val();
        if(type== 1)
        {
            jQuery("#assign_task_market").addClass("btn-blink");
        } else {
			if(lead_id > 0)
			{ 
			 $("#loader_seller_market").show();
				jQuery.ajax({
				type:'POST',
				url: '<?php echo $Layout->baseUrl; ?>index.php/leads/assign_lead',
				data:{'lead_id':lead_id,'base_url':'<?php echo $Layout->baseUrl; ?>index.php/leads/market',ds:1},
				success:function(response){
				    $("#loader_seller_market").hide();
				        obj = jQuery.parseJSON(response);
						jQuery("#assign_task_market a").hide();
						jQuery("#assign_task_market").html('<span class="date-style">'+moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> Seller Info Done");
						jQuery("#assign_task_market").removeClass("btn-blink");
						jQuery("#assign_task_market").addClass("btn btn-mwidth");
					}
				});
			}
		}
        
    }
    
    function sendRequestForProposalLetterMarket(type){
        var lead_id = jQuery('#marketLeadId').val();        
    	jQuery("#spinner-loader").css('display','inline-block');    	
    	_names = jQuery("#marketlead_name").val();
        jQuery("#marketProposal_letter").val(type);
        if(lead_id > 0) {
            if(type == 1){
                $("#loader_prospect_market").show();
                
				jQuery.ajax({        
        		type:'POST',        
        		url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',        
        		data:{name:_names,type:'Litigation',"lead_id":lead_id,ds:1,send_proposal_letter:1},        
        		cache:false,        
        		success:function(res){  
        		  $("#loader_prospect_market").hide();
                  jQuery("#request_for_proposal_market").addClass("btn-blink");
            			jQuery("#spinner-loader").css('display','none');            
            			_res = jQuery.parseJSON(res);
                       // alert(_res);
            			if(_res.link!=""){                            
                            jQuery("#request_for_proposal_market").addClass("btn-blink").attr('onclick','sendRequestForProposalLetter(2)');
            				window.open(_res.link,"_blank","toolbar=yes, scrollbars=yes, resizable=yes,width=600, height=500")
            			} else {
            				alert('Please try after some time!');
            			}
            		}
        	   });
            } else if(type == 2) {
                $("#loader_prospect_market").show();
				jQuery.ajax({        
        		type:'POST',        
        		url:'<?php echo $Layout->baseUrl?>index.php/leads/updateLeadData',        
        		data:{"lead_id":lead_id,send_proposal_letter:2},        
        		cache:false,        
        		success:function(res){
        		  $("#loader_prospect_market").hide();
            			jQuery("#spinner-loader").css('display','none');            
						_res = jQuery.parseJSON(res);
						if(_res.error==0){
							jQuery("#request_for_proposal_market").removeClass("btn-blink");
							jQuery("#request_for_proposal_market").html('<span class="date-style">'+_res.date_created+"</span> Proposal letter created");
							 jQuery("#request_for_proposal_market").addClass("btn btn-mwidth");
						} else {
							alert("Please try after sometime.");
						}
            		}
        	   });
            }
        } else {
			alert("Please try after sometime.");
		}    
        
    }
    
    function forward_to_review_market(){
        var lead_id = jQuery('#marketLeadId').val();
        if(lead_id > 0)
        {
            $("#loader_review_market").show();
            jQuery.ajax({
                type:'POST',
                url: '<?php echo $Layout->baseUrl; ?>leads/forward_to_review',
                data:{'lead_id':lead_id},
                success:function(response){
                    $("#loader_review_market").hide();
                    obj = jQuery.parseJSON(response);
					jQuery("#marketComplete").val(1);
                    jQuery("#forward_to_review_market a").hide();
                    jQuery("#forward_to_review_market").html('<span class="date-style">'+moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> Review");
                    jQuery("#forward_to_review_market").addClass("btn btn-mwidth");
                }
            });
        }
    }
    
    function openPatentListMarket(){
        var patent_url = jQuery("#marketFileUrl").val();
        if(patent_url != '')
        {
            window.open(patent_url,'_blank');   
        }
    }

</script>