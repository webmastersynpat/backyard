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
	border: medium none !important;
    margin-bottom: 0;
    padding: 0;
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
</style>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/modal/modal.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/dialog/dialog.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/dialog/dialog-demo.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Create</a></li><li class='active'>From Market</li>");
});
</script>
<script>
    function findWorksheet(o){
			v = o.find('option:selected').val();
			t = o.find('option:selected').text();
			if(v!=""){
				jQuery.ajax({
					url:'<?php echo $Layout->baseUrl?>leads/findWorksheetList',
					type:'POST',
					data:{v:v},
					cache:false,
					success:function(data){
						_d = jQuery.parseJSON(data);
						if(_d!=undefined  && _d.length>0){
							jQuery("#generalWorksheetId").empty().append("<option va;ue=''>-- Select Worksheet --</option>");
							for(i=0;i<_d.length;i++){
								jQuery("#generalWorksheetId").append("<option value='"+_d[i].id+"' data-href='"+_d[i].full+"'>"+_d[i].text+"</option>");
							}
						}
					}
				});
			}
		}
		
		function findWorksheetUrl(o){
			u = o.find('option:selected').attr('data-href');
			if(u!=""){
				jQuery("#litigationFileUrl").val(u);
			}					
		}
</script>
<div class="panel dashboard-box">
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
										<!--<a href='javascript:void(0);' onclick="window.open('https://mail.google.com','_BLANK')" class='btn btn-success'>Open Gmail</a>-->
										
				<?php if(!empty($auth_url)):?>
				<a href="<?php echo $auth_url;?>" class="link-blue " style="position:relative; top:5px;">
                        <!-- <span class="glyph-icon icon-separator">
                            <i class="glyph-icon icon-google-plus"></i>
                        </span> -->
                        <span class="button-content">
                            Retrieve new Messages
                        </span>
                </a>
				<script>
					interval = 1000*60*1;
					setInterval("retrieve_new_messgaes()",interval);
					function  retrieve_new_messgaes(){
						if ($.active == 0) {
							jQuery.ajax({
								url:'<?php echo $Layout->baseUrl?>leads/find_new_email_messages',
								cache:false,
								success:function(data){
									
								}
							});
						}
					}
					
				</script>
				<?php else:				?>			
				<?php /*echo anchor('leads/logout_gmail','Retrieve new messages',array('class'=>'btn btn-success mrg5B mrg5T'));*/?>
				<div class="mrg5T" style="display: inline-block;">
					<a href="<?php echo $Layout->baseUrl;?>leads/retrieve_new_messgaes" class="link-blue  mrg5B">
                        <!-- <span class="glyph-icon icon-separator">
                            <i class="glyph-icon icon-google-plus"></i>
                        </span> -->
                        <span class="button-content">
                            Retrieve new Messages
                        </span>
                    </a>
				</div>
				<button class="btn pull-right btn-default mrg5B" type='button' onclick="passMessageOrLead();">
					<span>Pass</span>
                </button>
				<button class="btn pull-right mrg5B mrg5R btn-default" onclick="replyLeadsEmail();">
                    <span>Reply</span>
                </button>
               	<!--<a href="javascript:void(0);" id="btnMakeTask" class="btn btn-link link-blue mrg5B pull-right" onclick="" style="display: none;">Make Task</a>-->
				<!-- <button class="btn pull-right mrg5B mrg5R btn-warning" data-toggle="modal" onclick="makeTask()">Task</button> -->

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
								<?php echo form_open('leads/create_task',array('class'=>'form-horizontal','role'=>'form','style'=>'margin-top:20px;','id'=>"createTask"));?>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Create Task</h4>
                                </div>
                                <div class="modal-body">
                                    
										<div class="form-group">
											<label class="col-sm-2 control-label" for="litigationLinkToPacer">Select User: </label>
											<div class="col-sm-10">
												
												<dl class="dropdown"> 
													<dt>
													<a href="#">
													  <span class="hida">Select Users</span>    
													  <p class="multiSel"></p>  
													</a>
													</dt>
												  
													<dd>
														<div class="mutliSelect">
															<ul>
																<?php 
																	if(count($users)>0){
																		foreach($users as $user){
																?>
																		<li><input type="checkbox" name="task[user_id][]" id="taskUserId" value="<?php echo $user->id?>"><?php echo $user->name;?></li>
																<?php		
																		}									
																	}
																?> 																
															</ul>
														</div>
													</dd>
												</dl>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="litigationLinkToPacer">Note: </label>
											<div class="col-sm-10">
												<?php echo form_textarea(array('name'=>'task[note]','required'=>'required','id'=>'taskNode','placeholder'=>'Add Note','class'=>'form-control','rows'=>4,'cols'=>29));?>	
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for=""></label>
											<div class="col-sm-offset-2 col-sm-10">
												<input type="hidden" name="task[lead_id]" id="taskLeadId" value=""/>
												
											</div>
										</div>
								
										
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
								<?php echo form_close();?>
                            </div>
                        </div>
                    </div>
				<!-- <link rel="stylesheet" type="text/css" href="http://themes-lab.com/pixit/admin/assets/css/style.css"> 
				<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/js-init/moment-with-locales.min.js"></script>-->
			
				<style>
					.drop-hover{border:1px dashed #2196F3 !important;}
					/*#new_lead_drop.drop-hover {border:2px dashed #ff0000 !important; height: 51px; padding: 4px 7px;}*/
					#new_lead_drop.drop-hover {box-shadow: 0 0 1px #2196F3 inset;}
				</style>
				<!--<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
				
				<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>-->
				
					
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
				
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
                
                    <script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/js-core/jquery-cookie.js"></script>
                    <script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datepicker/datepicker.js"></script>
                    <script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/modal/modal.js"></script>
				
				<script>
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
					
					function replyLeadsEmail(){
						jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
						_flag = 0;
						_type = "";
						_MessageID = "";
						_id = "";
						_email = "";
						_subject = "";
						jQuery(".messages-list-leads").find('.message-item').each(function(){
							if(jQuery(this).hasClass('message-active')){
								_flag = 1;
								_type = "message";
								_id = jQuery(this).attr('data-id');
								_MessageID = jQuery(this).attr('data-message-id');
								_email = jQuery(this).find('h5').find('a').html();								
								_subject = jQuery(this).find('h4').html();								
							}
						});
						if(_flag==0){
							jQuery("#messages-boxlist").find("ul.todo-box").find("li").each(function(){
								if(jQuery(this).hasClass('active')){
									_flag = 1;
									_type = "lead";
									_id = jQuery("#lead_gmail_message_id").val();
								}
							});
						}
						if(_flag==0){
							html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';
							jQuery("#contentPart").find('.alert').remove();
							jQuery("#contentPart>.panel").before(html);
							removeAlert();
						} else  {
							jQuery("#emailThreadId").val(_id);
							jQuery("#emailMessageId").val(_MessageID);
							_nn = _email.substr(0,_email.indexOf("<"));
							_ss = _email.substr(_email.indexOf("<"));
							_newem = _ss.substr(1,_ss.indexOf(">")-1)
							jQuery("#emailTo").val(_newem).attr("readonly","readonly");
							jQuery("#emailSubject").val(_subject).attr("readonly","readonly");
							jQuery("#emailName").val(jQuery.trim(_nn));
							jQuery("#gmail_message").css("display","block");
							jQuery("#emailMessage").focus();
						}
					}
					function composeEmail(){
						jQuery("#myDashboardEmails").get(0).reset();
						jQuery("#emailThreadId").val('');
						jQuery("#emailMessageId").val('');
						jQuery("#gmail_message").css("display","block");
						jQuery("#emailTo").focus();
					}
					
					function discardEmail(){
						jQuery("#myDashboardEmails").get(0).reset();
						jQuery("#emailThreadId").val('');
						jQuery("#emailMessageId").val('');
						jQuery("#gmail_message").css("display","none");
						jQuery("#emailTo").focus();
					}
					___check = "";
					function threadLabelChanged(type,object){
						_flag = 0;
						_id = "";
						jQuery("div.messages_container").find('.message-item').each(function(){
							if(jQuery(this).hasClass('message-active')){
								_flag = 1;
								_id = jQuery(this).attr('data-id');							
							}
						});
						if(_flag==0){
							html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';
							jQuery("#contentPart").find('.alert').remove();
							jQuery("#contentPart>.row").before(html);
							removeAlert();
						} else  {
							jQuery.ajax({
								url:'<?php echo $Layout->baseUrl?>/dashboard/modifyLabel',
								type:'POST',
								data:{token:_id,label:type},
								cache:false,
								success:function(data){
									if(type=="Starred"){
										if(data=="Starred"){
											object.attr('data-original-title','Starred');
											object.html("<i class='glyph-icon icon-star'></i>");
										} else {
											object.attr('data-original-title','No Starred');
											object.html("<i class='glyph-icon icon-star-o'></i>");
										}
									}
									if(type=="Unread" && data=="Starred"){
										jQuery("div.messages_container").find('.message-item').each(function(){
											if(jQuery(this).hasClass('message-active')){
												jQuery(this).find('h5').addClass('c-dark').html("<strong>"+jQuery(this).find('h5').html()+"</strong>");
												jQuery(this).find('h5').find('a').removeAttr('style');
											}
										});										
									}
									_object = jQuery(".emails-group-container").find('a.active');
									getEmails('messages_container',_object.html().toUpperCase(),_object);
									/*if(type=='Trash'){
										_object = jQuery(".emails-group-container>a").eq(4);
										getEmails('messages_container','TRASH',_object);
									}
									if(type=='Spam'){
										_object = jQuery(".emails-group-container").find('a.active');
										getEmails('messages_container',_object.html().toUpperCase(),_object);
									}*/
								}
							});
						}						
					}
					function getEmails(object,type,mainObject){
						jQuery(".emails-group-container").find('.list-group-item').removeClass('active');
						mainObject.addClass('active');
						_string = '<div class="loading-spinner" id="loading_spinner_heading_messages" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div>';
						jQuery("."+object).html(_string);
						jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
										'</div>');
						jQuery('.message-detail-title').css('display','none');
						jQuery('.message-detail-right').css('display','none');
						jQuery("#loading_spinner_heading_messages").css('display','block');
						jQuery.ajax({
							type:'POST',
							url:'<?php echo $Layout->baseUrl?>users/getEmails',
							data:{type:type},
							cache:false,
							success:function(data){
								jQuery("#loading_spinner_heading_messages").css('display','none');
								jQuery("."+object).html(data);
							}
						});
					}
					function printEmail(object){
						_flag = 0;
						_id = "";
						jQuery("div.messages_container").find('.message-item').each(function(){
							if(jQuery(this).hasClass('message-active')){
								_flag = 1;
								_id = jQuery(this).attr('data-id');							
							}
						});
						if(_flag==0){
							html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';
							jQuery("#contentPart").find('.alert').remove();
							jQuery("#contentPart>.row").before(html);
							removeAlert();
						} else  {
							window.open("https://mail.google.com/mail/u/0/?ui=2&ik=62926f1489&view=pt&search=inbox&msg="+_id+"&siml="+_id,"_BLANK");
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
						
						  var title = $(this).closest('.mutliSelect').find('input[type="checkbox"]:checked').length,
							  //title = $(this).val() + ",";
							title = title+" selected";
						  if ($(this).is(':checked')) {
							  var html = '<span title="' + title + '">' + title + '</span>';
							  $('.multiSel').html(html);
							  $(".hida").hide();
						  } 
						  else {
							 var html = '<span title="' + title + '">' + title + '</span>';
							  $('.multiSel').html(html);
							  var ret = $(".hida");
							  $('.dropdown dt a').append(ret);
							  
						  }
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
						
						$( ".draggable" ).draggable({
							revert : function(event, ui) {
								// on older version of jQuery use "draggable"
								// $(this).data("draggable")
								// on 2.x versions of jQuery use "ui-draggable"
								// $(this).data("ui-draggable")
								$(this).data("draggable").originalPosition = {
									top : 0,
									left : 0
								};
								// return boolean
								return !event;
								// that evaluate like this:
								// return event !== false ? false : true;
							}
						});
						$( ".droppable" ).droppable({
							hoverClass: "drop-hover",
							
							drop: function( event, ui ) {
								jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
								jQuery("#marketLead").get(0).reset();
								jQuery(".messages-list-leads").find('.message-item').each(function(){
									jQuery(this).removeClass('message-active');
									jQuery(this).find('h5').addClass('c-dark');
									jQuery(this).find('h4').addClass('c-dark');
									jQuery(this).find('p').addClass('c-gray');
									jQuery(this).find('a').addClass('c-dark');
								});
								jQuery("#subject").empty();
								jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
													'<i class="bg-primary"></i>'+
													'<i class="bg-primary"></i>'+
													'<i class="bg-primary"></i>'+
													'<i class="bg-primary"></i>'+
													'<i class="bg-primary"></i>'+
													'<i class="bg-primary"></i>'+
												'</div>');
								jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
									jQuery(this).removeClass('active');
								});
								if(jQuery(this).hasClass('old_lead')){
									_neLD= ui.draggable.attr('data-id');
									_newObject = jQuery(this);
									if(jQuery(this).attr('data-id')!=undefined && parseInt(jQuery(this).attr('data-id'))>0 && ui.draggable.attr('data-id')!=undefined && ui.draggable.attr('data-id')!=""){
										jQuery.ajax({
											type:'POST',
											url:'<?php echo $Layout->baseUrl;?>leads/linkWithMessage',
											data:{old_thread:jQuery(this).attr('data-id'),thread:ui.draggable.attr('data-id')},
											cache:false,
											success:function(res){
												__data = jQuery.parseJSON(res);
												if(__data.send>0){
													ui.draggable.remove();
													/*jQuery("#old_lead").find('.alert').remove();
													html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Email add in box.</p></div>';
													jQuery("#contentPart>.panel").before(html);*/
													_newObject.addClass('active');
													_aObject = _newObject.find('a');
													threadDetail(_aObject,_neLD);
												} else {
													ui.draggable.css('top','0px');
													ui.draggable.css('left','0px');
													html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please try after sometime.</p></div>';
													jQuery("#contentPart>.panel").before(html);
												}
												removeAlert();											
											}
										});
									} else {
										ui.draggable.css('top','0px');
										ui.draggable.css('left','0px');
									}
								} else if(jQuery(this).attr('id')=="new_lead_drop"){
									jQuery("#list_boxes").empty();
									jQuery(this).css('border','1px solid #56b2fe');
									if(ui.draggable.attr('data-id')!=undefined || ui.draggable.attr('data-id')!=""){
										_sender = ui.draggable.find('h5').html();
										_anchor = jQuery(_sender).find('a').html();
										_onclick = jQuery(_sender).find('a').attr('onclick');
										_subject = ui.draggable.find('h4').html();
										findThread(ui.draggable.attr('data-id'),'',1);
										jQuery(this).empty().html('<div class="new_draggable"><strong><a href="javscript:void(0)" onclick="'+_onclick+'">'+_anchor+'</a></strong><p>'+_subject+"</p></div>");
										jQuery("#lead_gmail_message_id").val(ui.draggable.attr('data-id'));
										ui.draggable.remove();
										jQuery("#marketOwner").focus();
										jQuery(".new_draggable").draggable({
											stop: function( event, ui ) {
												//ui.draggable.remove();
												jQuery(this).remove();
												window.location = window.location.href;
											}
										});
									} else {
										ui.draggable.css('top','0px');
										ui.draggable.css('left','0px');
									}									
								}								
							}
						});
					});
					
					function threadDetail(object,ato){
						g = object.parent().parent().attr('data-id');
						object.parent().parent().parent().find('li').removeClass('active');
						object.parent().parent().addClass('active');
						jQuery(".messages-list-leads").find('.message-item').each(function(){
							jQuery(this).removeClass('message-active');
							jQuery(this).find('h5').addClass('c-dark');
							jQuery(this).find('h4').addClass('c-dark');
							jQuery(this).find('p').addClass('c-gray');
							jQuery(this).find('a').addClass('c-dark');
						});
						jQuery('.message-detail-title').css('display','none');
						jQuery("#subject").empty();
						jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
						jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
										'</div>');
						jQuery("#marketLead").get(0).reset();
						
						if(parseInt(g)>0){
							jQuery("#bottom_form_market").css('display','block');
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>leads/findBoxList',
								data:{boxes:g},
								cache:false,
								success:function(res){
									jQuery("#bottom_form_market").css('display','none');
									_data = jQuery.parseJSON(res);
									
									if(_data.detail.length>0){
										jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);
										jQuery("#marketProspects").val(_data.detail[0].litigation.no_of_prospects);
										jQuery("#marketExpectedPrice").val(_data.detail[0].litigation.expected_price);
										jQuery("#marketProspectsName").val(_data.detail[0].litigation.prospects_name);
										jQuery("#marketlead_name").val(_data.detail[0].litigation.lead_name);
										jQuery("#marketTechnologies").val(_data.detail[0].litigation.technologies);
										jQuery("#marketNo_of_us_patents").val(_data.detail[0].litigation.no_of_us_patents);
										jQuery("#marketno_of_non_us_patents").val(_data.detail[0].litigation.no_of_non_us_patents);
										jQuery("#litigationFileUrl").val(_data.detail[0].litigation.file_url);
										snapGlobal= _data.detail[0].litigation.file_url;
										_st = '<?php echo $this->session->userdata['type'];?>';
										_sp = '<?php echo $this->session->userdata['id'];?>';
										if(_data.detail[0].comment.length>0){
											_list = "";
											for(c=0;c<_data.detail[0].comment.length;c++){
												_flag = 0;
												if(_data.detail[0].comment[c].user_id==_sp){
													_flag=1;
													_commentAttractive = _data.detail[0].comment[c].attractive;
													jQuery("#marketComment").val(_data.detail[0].comment[c].comment);
													jQuery("#commentId").val(_data.detail[0].comment[c].id);
													jQuery("select[name='market[attractive]']").find('option').each(function(){ 
														if(jQuery(this).attr('value')==_commentAttractive){
															jQuery(this).attr('SELECTED','SELECTED');
														} else {
															jQuery(this).removeAttr('SELECTED');
														}
													});
												}
												/*if(parseInt(_st)==9){*/
												if(_flag==0){
													_list +="<tr><td>"+_data.detail[0].comment[c].name+"</td><td>"+_data.detail[0].comment[c].comment+"</td><td>"+_data.detail[0].comment[c].attractive+"</td></tr>";
												} else {
													_flag=0;
												}
											}
											/*if(parseInt(_st)==9){	*/											
											if(_list!=""){		
												jQuery("#form_details").find('table.table').remove();
												jQuery("#list_boxes").before("<table class='table table-bordered '><thead><tr><th>Comment By</th><th>Comment</th><th>Attractiveness</th></tr></thead><tbody>"+_list+"</tbody></table>");
											}
											
										}										
										jQuery("#id").val(_data.detail[0].litigation.id);
										if(_data.boxes.length>0){
											_table = jQuery('<table/>').addClass('table').addClass('table-bordered');
											_tbody = jQuery('<tbody/>');											
											for(i=0;i<_data.boxes.length;i++){
												_tr = jQuery('<tr/>');
												_showData = "";
												_d = _data.boxes[i];
												_receivedDate = "";
												__a = "";
												if(_d.type=="Message"){
													/*__a = "<a href='javascript:void(0);' onclick='makeTask("+g+",\""+_d.parent_id+"\")'>Make Task</a> | <a href='javascript:void(0);' onclick='removeFromBox("+g+",\""+_d.parent_id+"\")'>Remove From Box</a> | ";*/
													__a = "<a href='javascript:void(0);' onclick='removeFromBox("+g+",\""+_d.parent_id+"\")'>Remove From Box</a>";
													if(_d.header.length>0){
														for(h=0;h<_d.header.length;h++){
															if(_d.header[h].name=="Subject"){
																_showData="<i class='glyph-icon icon-envelope'></i> <a href='javascript:void(0)' onclick='findThread(\""+_d.parent_id+"\",jQuery(this),1);'>"+_d.header[h].value+"</a>";
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
													_showData="<i class='glyph-icon icon-file'> <a href='https://mail.google.com/mail/u/0/?ui=2&view=att&th="+_d.message_id+"&disp=safe&realattid="+_d.realAttachID+"' target='_BLANK'>"+_d.filename+"</a>";
												}
												if(_showData!=""){
													jQuery(_tr).addClass(_d.message_id).append("<td>"+_showData+"</td><td class='text-right'>"+moment(new Date(_receivedDate)).format('lll')+' | ' + __a+"</td>");
													if(ato!=undefined){
														if(_d.message_id==ato){
															jQuery(_tr).addClass('activetr');
														}
													}
													jQuery(_tbody).append(_tr);
												}												
											}
											jQuery(_table).append(_tbody);
											jQuery("#list_boxes").empty().append("<h3 class='title-hero'>Contents</h3>").append(_table);
										} else {
											jQuery("#list_boxes").empty();
										}
                                        
                                        jQuery("#marketPatentData").val(_data.detail[0].litigation.patent_data);
                                        
            //                             jQuery("#scrap_patent_data").handsontable("destroy"); 
            //                             if(_data.detail[0].litigation.patent_data!=""){
            //         						var $container = jQuery("#scrap_patent_data");
            //         						$container.handsontable({						
            //         							startRows: 1,
            //         							data:jQuery.parseJSON(_data.detail[0].litigation.patent_data),
            //         							startCols: 9,		
            //         							colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
            //         							manualColumnResize: false,
            //         							manualRowResize: false,
            //         							minSpareRows: 1,
												// columnSorting: true,
            //         							persistentState: false,
            //         							contextMenu: false,
            //         							fixedRowsTop: 0,
            //         							columns: [
            //         										{renderer: coverRenderer},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{renderer: renderReadOnly},
            //         										{}
            //         									]
            //         						});
            //         					}
                                        
									}
								}
							});
						}
					}
					
					function makeTask(){
						/*jQuery("#create_task").css('display','block');
						jQuery("#taskLeadId").val(g);
						jQuery("#taskUserId").focus();*/
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
							jQuery("#taskLeadId").val(_leadID);
							jQuery("#taskUserId").focus();
						} else {
							html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select lead first.</p></div>';
							jQuery("#contentPart").find('.alert').remove();
							jQuery("#contentPart>.panel").before(html);
							removeAlert();
						}						
					}
					
					function removeFromBox(g,thread){
						if(jQuery.trim(thread)!=""){
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl?>leads/removeFromBox',
								data:{thread:thread,g:g},
								cache:false,
								success:function(res){
									_data = jQuery.parseJSON(res);
									if(parseInt(_data.send)>0){
										jQuery("#list_boxes").find('table').find('tr.'+thread).remove();
										/*html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Successfully remove from box.</p></div>';
										jQuery("#contentPart").find('.alert').remove();
										jQuery("#contentPart>.panel").before(html);
										removeAlert();*/
									} else {
										html='<div class="alert alert-danger"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please try after sometime.</p></div>';
										jQuery("#contentPart").find('.alert').remove();
										jQuery("#contentPart>.panel").before(html);
										removeAlert();
									}
								}
							});
						}
					}
					
					function findThread(thread,object, flag){
						if(typeof(flag)==="undefined"){
							flag=0;
						}
						jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
						if(flag==0){
							if(object.parent().parent().attr('id')!="new_lead_drop"){
								jQuery('.messages-list-leads').find('.message-item').each(function(){
									jQuery(this).removeClass('message-active');
									jQuery(this).find('h5').addClass('c-dark');
									jQuery(this).find('h4').addClass('c-dark');
									jQuery(this).find('p').addClass('c-gray');
									jQuery(this).find('a').addClass('c-dark');
								});
								// object.parent().parent().parent().parent().parent().parent().find('h5').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('h4').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('p').removeClass('c-gray');
								// object.parent().parent().parent().parent().parent().parent().find('a').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('a').css('color','#FFF');
								// object.parent().parent().parent().parent().parent().parent().addClass('message-active');
								object.parent().parent().parent().find('h5').removeClass('c-dark');
								object.parent().parent().parent().find('h4').removeClass('c-dark');
								object.parent().parent().parent().find('p').removeClass('c-gray');
								object.parent().parent().parent().find('a').removeClass('c-dark');
								object.parent().parent().parent().find('a').css('color','#FFF');
								object.parent().parent().parent().addClass('message-active');
							}
						}
						jQuery(".message-detail-subject").empty();
						jQuery('.message-detail-right').hide();
						jQuery('.message-detail-title').hide();
						jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
										'</div>');
						jQuery("#loading_spinner").show();
						jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
							jQuery(this).removeClass('active');
							jQuery("#marketLead").get(0).reset();
						});
						jQuery.ajax({
							type:'POST',
							url:'<?php echo $Layout->baseUrl?>opportunity/findThread',
							data:{thread:jQuery.trim(thread)},
							cache:false,
							success:function(res){
								_data = jQuery.parseJSON(res);
								jQuery("#loading_spinner").hide();
								for(i=0;i<_data.length;i++){
									headers = _data[i].header;
									subject = "";
									from = "";
									to = "";
									date = "";
									email = "";
									for(j=0;j<headers.length;j++){
										if(headers[j].name=='Subject'){
											subject = headers[j].value;
										}
										if(headers[j].name=='From'){
											from = headers[j].value;
										}
										if(headers[j].name=='To'){
											to = headers[j].value;
										}
										if(headers[j].name=='Authentication-Results'){
											raw = headers[j].value;
											raw = raw.split('smtp.mail=');
											if(raw==2){
												email = raw[1];
											}
										}
										if(headers[j].name=='Received'){
											date = headers[j].value;
											_da = date.split(';')
											if(_da.length==2){
												date = _da[1];
											}
										}
									}
									body = _data[i].body;
									_attachmentString ="";
									if(_data[i].attachments.length>0){
										_attachmentString='<div class="message-attache">';
										for(a=0;a<_data[i].attachments.length;a++){
											attachmentData  = _data[i].attachments[a];
											_attachmentString +='<div class="media">'+
                                                '<i class="fa fa-paperclip pull-left fa-2x"></i>'+
                                                '<div class="media-body">'+
                                                    '<div><a target="_BLANK" href="https://mail.google.com/mail/u/0/?ui=2&view=att&th='+_data[i].message_id+'&disp=safe&realattid='+attachmentData.realAttachID+'" dataId="'+_data[i].message_id+'" dataMime="'+attachmentData.mimeType+'" dataAttached="'+attachmentData.attachmentId+'" dataFileName="'+attachmentData.filename+'" db="getGmailAttachment(jQuery(this))"; class="strong text-regular">'+attachmentData.filename+'</a>'+
                                                    '</div>'+
                                                    '<span>'+bytesToSize(attachmentData.size)+'</span>'+
                                                    '<div class="clearfix"></div>'+
                                                '</div>'+
                                            '</div>';
										}
										_attachmentString +="</div>";										
									}
									_messagesString = "";
									if(i==0){
										jQuery("#message-detail .panel-heading").find('.message-detail-subject').html(subject);
										jQuery('.message-detail-title').show();
										jQuery('.message-detail-title.is-date').css({ display: 'inline-block' });
										$('.message-detail-from').html(from);
										$('.message-detail-to').html(to);
										$('.message-detail-date').html(moment(new Date(date)).format('lll'));
										jQuery('#message-detail .message-detail-right').css({display: 'inline-block'});
									}
									_messagesString +='<div class="row">'+
															'<div class="col-md-12 col-sm-12 col-xs-12">'+
															'    <div class="p-20">';
									if(i>0){
										_messagesString +='<h3 class="message-title">'+subject+'</h3>';
									}
									_messagesString +=		'        <div class="message-item media">'+
															'            <div class="message-item-right">'+
															'                <div class="media">'+
															'                    <div class="media-body">'+
															'                        <small class="pull-right">'+moment(new Date(date)).format('lll')+'</small>'+
															'                        <h5 class="c-dark"><strong>'+from+'</strong></h5>'+
															'                        <p class="c-gray">'+email+'</p>'+
															'                    </div>'+
															'                </div>'+
															'            </div>'+
															'        </div>'+
															'    </div>'+
															'   <div class="message-body">'+body+_attachmentString+
															'    </div>'+
															'</div>'+
														'</div>';
									jQuery("#message-detail .panel-body").empty().append(_messagesString);
									if(i>0 && i<_data.length-1){
										jQuery("#message-detail .panel-body").append('<div class="message-between"></div>');
									}									
								}
							}
						});
					} 
					function bytesToSize(bytes) {
					   if(bytes == 0) return '0 Byte';
					   var k = 1000;
					   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
					   var i = Math.floor(Math.log(bytes) / Math.log(k));
					   return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
					}
					function getGmailAttachment(object){
						jQuery.ajax({
							type:'POST',
							url:'<?php echo $Layout->baseUrl;?>opportunity/viewFile',
							data:{message_id:object.attr('dataId'),mimeType:object.attr('dataMime'),attachment:object.attr('dataAttached'),filename:object.attr('dataFileName')},
							cache:false,
							success:function(res){
								_data  = jQuery.parseJSON(res);
								if(_data.url!=""){
									window.open(url,'_BALNK');
								}
							}
						}); 
					}
				</script>
				<div class="col-md-12" style='padding:0px;'>					
					<div class="page-mailbox" id="main-content">
						<div data-equal-height="true" class="row">
							<div class="col-lg-1 mrg15T" style='padding:0px;'>
								<div class="panel panel-default panel-no-margin panel-no-border">
									<button class="btn btn-default btn-block" type="button" onclick="composeEmail()">Compose</button>
									<div class="list-group emails-group-container mrg5T">
										<a href="javascript:void(0);" onclick="getEmails('messages_container','INBOX',jQuery(this));" class="list-group-item text-center active">Inbox</a>
										<a href="javascript:void(0);" onclick="getEmails('messages_container','STARRED',jQuery(this));" class="list-group-item text-center">Starred</a>
										<a href="javascript:void(0);" onclick="getEmails('messages_container','DRAFT',jQuery(this));" class="list-group-item text-center">Draft</a>
										<a href="javascript:void(0);" onclick="getEmails('messages_container','SENT',jQuery(this));" class="list-group-item text-center">Sent</a>
										<a href="javascript:void(0);" onclick="getEmails('messages_container','TRASH',jQuery(this));" class="list-group-item text-center">Trash</a>
									</div>
								</div>
							</div>
							<div class="col-md-3 list-messages mrg15T">
								<div class="panel panel-default panel-no-margin">
									<div class="messages">
										<div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-list" >
										<div style="height:300px; overflow-x:hidden !important; overflow-y:auto !important; max-width:100%;" id="mCSB_116" class="mCustomScrollBox mCS-dark-2">
										<div style="" class="mCSB_container messages-list-leads">
											<?php 
												if(count($messages)>0){	
													foreach($messages as $message){	
														$mainFlag = 0;
														if(count($pass_lead['message'])>0){
															foreach($pass_lead['message'] as $pass){																
																if($pass->thread_id==$message['message_id']){
																	$mainFlag = 1;
																}
																														
															}
														}
														if($mainFlag==0){
														$flag = 0;
														if(count($incomplete)>0){
															foreach($incomplete as $inCom){
																if(count($inCom->box_list)>0){
																	foreach($inCom->box_list as $in){
																		if($in->thread_id==$message['message_id']){
																			$flag = 1;
																		}
																	}
																}																
															}
														}
                                                        if(count($boxList)>0){
                                                       	    foreach($boxList as $inCom){																
																if($inCom->thread_id==$message['message_id']){
																	$flag = 1;
																}																															
															}
                                                        }
														if($flag==0){
														$from ="";													
														$subject="";													
														$date = "";	
														$messageIDDD ="";
														foreach($message['header'] as $header){
															if($header->name=="From"){	
																$from = $header->value;
															}
															if($header->name=="Subject"){
																$subject = $header->value;	
															}
															if($header->name=="Date"){
																$date = $header->value;
																$date = date('M d, Y',strtotime($date));
																/*if($date){
																	$date = explode(';',$date);
																	$date = $date[1];
																	$date = date('d M',strtotime($date));
																}*/
															}
															if($header->name=="Message-ID"){
																$messageIDDD = $header->value;
															}
														}
											?>
													<div class="message-item media  draggable" data-id="<?php echo $message['message_id']?>" data-message-id="<?php echo $messageIDDD;?>">
														<!-- <div class="pull-left text-center">
															<div class="pos-rel message-checkbox">
																<div class=" ui-checkbox"><input type="checkbox" value="<?php echo $message['message_id']?>" data-style="flat-red"></div>
															</div>
														</div> -->
														<div class="message-item-right">
															<div class="media">
																<!--<img width="50" class="pull-left" alt="avatar 3" src="assets/img/avatars/avatar3_big.png">-->
																<div class="media-body" onclick="findThread('<?php echo $message['message_id']?>',jQuery(this));">
																	<h5 class="c-dark"><strong><a class="c-dark" href="javascript:void(0)"><?php echo $from;?></a></strong></h5>
																	<h4 class="c-dark"><?php echo $subject;?></h4>
																	<div>
																		<?php echo $date;?>
																		&nbsp;
																		<?php 
																			if(count($message['attachments'])>0):
																		?>
																		<strong><i class="glyph-icon icon-paperclip"></i> <?php echo count($message['attachments']);?></strong>
																		<?php endif;?>
																	</div>
																</div>
															</div>
															<!-- <p class="f-14 c-gray" style='text-align:left;'><?php echo substr(strip_tags($message['rawMessage']), 0, 70);?></p> -->
														</div>
													</div>
											<?php
														}
													}
													}
											?>
											<?php
												}
											?>
										</div>
										</div></div>
									</div>  
								</div>
							</div>
							<div class="col-lg-2 list-messages mrg15T">
								<div class="panel panel-default panel-no-margin">
									<!-- <h4 class="list-messages-title">Drop here new lead</h4> -->
									<div id="new_lead_drop" class="panel-body droppable">
										<h4 class="list-messages-title">Drop here new lead</h4>
									</div>
								</div>
								<div class="panel panel-default panel-no-margin mrg5T" id="old_lead">
									<div class="messages">
										<div data-padding="90" data-height="window" class="withScroll mCustomScrollbar _mCS_116" id="messages-boxlist" >
											<div style="height:242px; overflow-x:hidden !important; overflow-y:auto !important; max-width:100%;" id="mCSB_116" class="mCustomScrollBox mCS-dark-2">
												<ul  class="todo-box">
													<?php 
														if(count($incomplete)>0){
															foreach($incomplete as $message){
																$mainFlag = 0;
																if(count($pass_lead['lead'])>0){
																	foreach($pass_lead['lead'] as $pass){																
																		if($pass->thread_id==$message->id){
																			$mainFlag = 1;
																		}
																																
																	}
																}
																if($mainFlag == 0){
													?>			
																<li data-id="<?php echo $message->id?>" class="border-blue-alt droppable old_lead"><label><a href="javascript:void(0);" onclick="threadDetail(jQuery(this));"><?php echo $message->lead_name;?></a></label></li>													
													<?php
															} 
															}
														}
													?>													
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 email-hidden-sm detail-message">
								<div data-padding="40" data-height="window" class="panel panel-default panel-no-margin withScroll mCustomScrollbar _mCS_117" id="message-detail" style="height:302px;"><div style="height:100%; overflow-x:hidden !important;overflow-y;scroll !important; max-width:100%;" id="mCSB_117" class="mCustomScrollBox mCS-dark-2"><div class="mCSB_container">
									<div class="panel-heading messages message-result">
										<h2 class="message-detail-title is-subject p-t-20 w-500">
											<span class="message-detail-subject"></span>
										</h2>

										<div class="row">
											<div class="col-xs-6">
												<h2 class="message-detail-title p-t-20 w-500">
													<strong><span class="message-detail-from"></span></strong>
												</h2>
												<h2 class="message-detail-title p-t-20 w-500">
													to: <span class="message-detail-to"></span>
													<div role="group" class="message-detail-buttons-left btn-group">
	  													<div role="group" class="btn-group">
	    													<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
	      														<span class="caret"></span>
	    													</button>
	    													<div role="menu" class="dropdown-menu" style="width:350px;">
	    														<div class="row">
	    															<div class="col-xs-3"><label>from:</label></div>
	    															<div class="col-xs-9"><strong class="message-detail-from"></strong></div>
	    														</div>
	    														<div class="row">
	    															<div class="col-xs-3"><label>to:</label></div>
	    															<div class="col-xs-9"><span class="message-detail-to"></span></div>
	    														</div>
	    														<div class="row">
	    															<div class="col-xs-3"><label>date:</label></div>
	    															<div class="col-xs-9"><span class="message-detail-date"></span></div>
	    														</div>
	    														<div class="row">
	    															<div class="col-xs-3"><label>subject:</label></div>
	    															<div class="col-xs-9"><span class="message-detail-subject"></span></div>
	    														</div>
	    													</div>
	  													</div>
													</div>
												</h2>
											</div>
											<div class="col-xs-6 text-right">
												<!-- <button class="btn pull-right btn-danger">
													<span>Reply</span>
													<i class="glyph-icon icon-left-right"></i>
												</button> -->
												<h2 class="message-detail-title p-t-20 w-500 is-date">
													<span class="message-detail-date"></span>
												</h2>
												<div class="message-detail-right">
													<a data-placement="bottom" title="" class="message-detail-star tooltip-button" onclick="threadLabelChanged('Starred',jQuery(this));" href="javascript://" data-original-title="Starred"><i class="glyph-icon icon-star"></i></a>
													<!-- <a href="#" class="message-detail-star tooltip-button is-active" title="From favorite" data-placement="bottom">
														<i class="glyph-icon icon-star"></i>
													</a> -->
													<div role="group" class="message-detail-buttons-right btn-group">
	  													<button data-placement="bottom" title="" onclick="replyEmail();" class="btn btn-default tooltip-button" type="button" data-original-title="Reply">
															<i class="glyph-icon icon-reply"></i>
	  													</button>
	  													<div role="group" class="btn-group">
	    													<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
	      														<span class="caret"></span>
	    													</button>
	    													<ul role="menu" class="dropdown-menu">
	      														<li><a onclick="replyEmail();" href="javascript://"><i class="glyph-icon icon-reply"></i> Reply</a></li>
	      														<li><a onclick="forwardEmail();" href="javascript://"><i class="glyph-icon icon-long-arrow-right"></i> Forward</a></li>
	      														<li><a onclick="printEmail(jQuery(this))" href="javascript://">Print</a></li>
	      														<li><a onclick="threadLabelChanged('Trash',jQuery(this));" href="javascript://">Delete</a></li>
	      														<li><a onclick="threadLabelChanged('Spam',jQuery(this));" href="javascript://">Report spam</a></li>
	      														<li><a onclick="threadLabelChanged('Unread',jQuery(this))" href="javascript://">Mark as unread</a></li>
	    													</ul>
	  													</div>
													</div>
												</div>
											</div>
										</div>

										<!-- <div class="row">
											<div class="col-xs-12">
												<h2 class="message-detail-title p-t-20 w-500">
													<strong>From:</strong> <span id="message_detail_from"></span>
												</h2>
											</div>
											<div class="col-xs-12">
												<h2 class="message-detail-title p-t-20 w-500">
													<strong>To:</strong> <span id="message_detail_to"></span>
												</h2>
											</div>
										</div>
										<h2 class="message-detail-title p-t-20 w-500">
											<strong>Subject:</strong> <span id="subject"></span>
										</h2>
										<h2 class="message-detail-title p-t-20 w-500">
											<strong>Date:</strong> <span id="message_detail_date"></span>
										</h2> -->
									</div>
									<div class="panel-body messages message-result message_detail">
										<div class="loading-spinner" id="loading_spinner" style='display:none;'>
											<i class="bg-primary"></i>
											<i class="bg-primary"></i>
											<i class="bg-primary"></i>
											<i class="bg-primary"></i>
											<i class="bg-primary"></i>
											<i class="bg-primary"></i>
										</div>
									</div>
								</div></div></div>
							</div>
						</div>
					</div>
				</div>
			<?php endif;?>
			<style>
				.control-label{text-align:left;}
			</style>
			<div id="form_details">
			<?php echo form_open_multipart('leads/reply_email',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'','id'=>"marketLeadReply" ,"onsubmit"=>"return dataValidate();"));?>
			
            <div id="gmail_message" style='margin-top:20px; display:none;'>
				<h4>Compose / Reply</h4>
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
			<?php echo form_open('leads/market',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'margin-top:10px;','id'=>"marketLead","onsubmit"=>"return dataValidate();"));?>
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
						<div class="col-xs-6">
							<div class="form-group input-string-group">
								<label for="generalOwner" class="control-label">Seller / Owner:</label>
								<?php echo form_input(array('name'=>'general[plantiffs_name]','id'=>'generalOwner','placeholder'=>'','class'=>'form-control',"tabindex"=>"1"));?>
							</div>
							<div class="form-group input-string-group">
								<label for="generalOwner" class="control-label">Broker:</label>
								<?php echo form_input(array('name'=>'general[broker]','id'=>'generalBroker','placeholder'=>'','class'=>'form-control',"tabindex"=>"1"));?>
							</div>
							<div class="form-group mrg10T">
								<!-- <label for="generalProspectsName" class="col-sm-2 control-label">Address:</label> -->
								<div class="col-sm-12" style="padding-right: 10px;">
									<?php echo form_textarea(array('name'=>'general[address]','id'=>'generalAddress','placeholder'=>'Address','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:69px !important;',"tabindex"=>"4"));?>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group input-string-group">
								<label for="generalProspects" class="control-label">Tech. / Markets:</label>
								<?php echo form_input(array('name'=>'general[relates_to]','id'=>'generalRelatesTo','placeholder'=>'','class'=>'form-control',"tabindex"=>"2"));?>
							</div>
							<div class="form-group input-string-group">
								<label for="generalExpectedPrice" class="control-label">Name1, Title1:</label>
								<?php echo form_input(array('name'=>'general[person_name_1]','id'=>'generalPersonName1','placeholder'=>'','class'=>'form-control',"tabindex"=>"5"));?>
							</div>
							<!-- <div class="form-group input-string-group bigmr">
								<label for="generalTechnologies" class="control-label">Person Title1:</label>
								<?php echo form_input(array('name'=>'general[person_title_1]','id'=>'generalPersonTitle1','placeholder'=>'','class'=>'form-control',"tabindex"=>"7"));?>
							</div> -->
							<div class="form-group input-string-group">
								<label for="generalExpectedPrice" class="control-label">Name2, Title2:</label>
								<?php echo form_input(array('name'=>'general[person_name_2]','id'=>'generalPersonName2','placeholder'=>'','class'=>'form-control',"tabindex"=>"6"));?>
							</div>
							<!-- <div class="form-group input-string-group bigmr">
								<label for="generalTechnologies" class="control-label">Person Title2:</label>
								<?php echo form_input(array('name'=>'general[person_title_2]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control',"tabindex"=>"8"));?>
							</div> -->
							<!--<div class="form-group input-string-group nomr">
								<label for="generalProspects" class="control-label">Number of Patents:</label>
								<?php echo form_input(array('name'=>'general[no_of_prospects]','id'=>'generalProspects','placeholder'=>'','class'=>'form-control',"tabindex"=>"3"));?>
							</div>-->
							<!-- <div class="form-group">
								<label for="generalExpectedPrice" class="control-label">Person Name3:</label>
								<?php echo form_input(array('name'=>'general[person_name_3]','id'=>'generalPersonName3','placeholder'=>'','class'=>'form-control input-string'));?>
							</div>
							<div class="form-group">
								<label for="generalTechnologies" class="control-label">Person Title3:</label>
								<?php echo form_input(array('name'=>'general[person_title_3]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control input-string'));?>
							</div> -->
						</div>
						<div class="col-width" style="width:180px;">
							<div class="form-group input-string-group">
								<label for="marketProspects" class="control-label">N. of Prospects:</label>
								<?php echo form_input(array('name'=>'market[no_of_prospects]','id'=>'marketProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
							</div>
							<div class="form-group input-string-group">
								<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
								<?php echo form_input(array('name'=>'market[expected_price]','id'=>'marketExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4', 'style'=>'min-width:38px;'));?>
							</div>
							<div class="form-group input-string-group">
								<label for="marketNo_of_us_patents" class="control-label">N. of US Patents:</label>
								<?php echo form_input(array('name'=>'market[no_of_us_patents]','type'=>'digit','id'=>'marketNo_of_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
							</div>
							<div class="form-group input-string-group">
								<label for="marketNo_of_non_us_patents" class="control-label">N. of Non-US Patents:</label>
								<?php echo form_input(array('name'=>'market[no_of_non_us_patents]','id'=>'marketno_of_non_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2'));?>
							</div>
						</div>
						<div class="col-width" style="width: 200px;">
							<div class="clearfix">
								<div class="todo-list-custom fright">
									<div class="row mrg5T">
										<div class="col-sm-10">To Do</div>
										<div class="col-sm-2">Done</div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-10"><a class="btn btn-default btn-mwidth" href="javascript:void(0);">Collect Seller's info</a></div>
										<div class="col-sm-2"><input type="checkbox" name="market[sellers_info]" id="marketSellersInfo"/></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-10"><a class="btn btn-default btn-mwidth" href="javascript:void(0);">Send Proposal Letter</a></div>
										<div class="col-sm-2"><input type="checkbox" name="market[proposal_letter]" id="marketProposalLetter"/></div>
									</div>
									<div class="row mrg5T">
										<div class="col-sm-10"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="spreadsheet_box();">Create Patent List</a></div>
										<div class="col-sm-2"><input type="checkbox" name="market[patent_list]" id="marketPatentList"/></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					
					
					
					
					
					
					<!--
					<div class="col-xs-5" style="width:41%;">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group input-string-group">
									<label for="marketOwner" class="control-label">Seller:</label>
									<?php echo form_input(array('name'=>'market[plantiffs_name]','id'=>'marketOwner','placeholder'=>'','class'=>'form-control'));?>
								</div>
							</div>
							<div class="col-sm-1"></div>
							<div class="col-sm-5">
								<div class="form-group input-string-group">
									<label for="marketTechnologies" class="control-label">Tech. / Markets:</label>
									<?php echo form_input(array('name'=>'market[technologies]','id'=>'marketTechnologies','placeholder'=>'','class'=>'form-control'));?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-7" style="width:59%;">
						<div class="row">
							<div class="col-xs-6">
								<div class="col-xs-5">
									<div class="form-group input-string-group noborder">
										<label for="marketProspects" class="control-label">N. of Prospects:</label>
										<?php echo form_input(array('name'=>'market[no_of_prospects]','id'=>'marketProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2', 'style'=>'max-width: 30px; min-width: 30px;'));?>
									</div>
								</div>
								<div class="col-xs-7">
									<div class="form-group input-string-group noborder">
										<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
										<?php echo form_input(array('name'=>'market[expected_price]','id'=>'marketExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4', 'style'=>'max-width: 38px; min-width: 30px;'));?>
									</div>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="row">
									<div class="col-xs-5">
										<div class="form-group input-string-group noborder">
											<label for="marketNo_of_us_patents" class="control-label">N. of US Patents:</label>
									         <?php echo form_input(array('name'=>'market[no_of_us_patents]','type'=>'digit','id'=>'marketNo_of_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2', 'style'=>'max-width: 30px; min-width: 30px;'));?>
										</div>
									</div>
		                            <div class="col-xs-7">
										<div class="form-group input-string-group noborder">
											<label for="marketNo_of_non_us_patents" class="control-label">N. of Non-US Patents:</label>
									         <?php echo form_input(array('name'=>'market[no_of_non_us_patents]','id'=>'marketno_of_non_us_patents','placeholder'=>'','class'=>'form-control','maxlength'=>'2', 'style'=>'max-width: 30px; min-width: 30px;'));?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->
				</div>
                
                <div class="row mrg5T">
					<div class="col-xs-12">
						<div class="form-group input-string-group nomr">
							<label for="marketProspectsName" class="control-label">Prospect's Names:</label>
							<?php echo form_input(array('name'=>'market[prospects_name]','id'=>'marketProspectsName','placeholder'=>"",'class'=>'form-control'));?>
						</div>
					</div>
				</div>
                
				<div class="row mrg10T">
					<div class="col-xs-10" style="width:89%;">
						<div class="row">
							<!-- <label class="col-sm-1 control-label" for="litigationLinkToPacer">Comment: </label> -->
							<div class="col-sm-12">
								<?php echo form_textarea(array('name'=>'comment[comment]','id'=>'marketComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>15));?>	
							</div>
						</div>
					</div>
					<div class="col-xs-2" style="width:11%;">
						<select  class="form-control pull-right" name="market[attractive]" required="required">
							<option value="">Attractiveness</option>
							<option value="High" >High</option>
							<option value="Medium">Medium</option>
							<option value="Low">Low</option>
							<option value="Disapproved">Disapproved</option> 
						</select>
					</div>
				</div>
				<div class="mrg15T">
					<div class="row">
						<div class="col-xs-5">
							<div class="form-group">
								<label for="marketProspectsName" class="control-label" style="float:left;"><strong>Name of Lead:</strong></label>
								<?php echo form_input(array('name'=>'market[lead_name]','required'=>'required','id'=>'marketlead_name','placeholder'=>'','class'=>'form-control input-string', 'style'=>'float: left; margin-left:5px; margin-top:3px; width: 66.6666%;'));?>
							</div>
						</div>
						<div class="col-xs-7 text-right">
							<label style="display: inline-block; margin-top: 8px; margin-right: 30px;">
								<input type="checkbox" value="1" name="market[complete]" /> Lead is complete and ready to be forwarded for review
							</label>
							<input type="hidden" name="market[gmail_message_id]" id="lead_gmail_message_id" value=""/>
							<input type="hidden" name="market[id]" id="id" value="0"/>
							<input type="hidden" name="comment[comment_id]" id="commentId" value="0"/>
                            <input type="hidden" name="market[patent_data]" value="" id="marketPatentData"/>
							<button type="submit" class="btn btn-primary btn-mwidth pull-right">Save</button>
						</div>
					</div>
				</div>
				<div class="row mrg10T">
					<!--div class="col-xs-9">
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">
								<strong>Patent File Url:</strong>
							</label>
							<input type="textbox" class="form-control input-string" name="market[file_url]" id="litigationFileUrl" value=""/>
						</div>						
					</div-->
                   <!-- <div class="col-xs-2">
                        <div class="form-group">
                            <label for="generalTechnologies" class="control-label pull-left">
                    			<strong></strong>
                    		</label>
                        </div>
                        <a  class="btn-primary btn" onclick="spreadsheet_box();">Create Spreadsheet</a>				
                    </div>	
                    <div class="col-xs-8">-->
                    <div class="col-xs-6">
						<div class="row mrg10T">
							<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
									<label for="generalTechnologies" class="control-label" style="margin-top: 2px;">
										<strong>Select Spreadsheet:</strong>
									</label>
									<select name="general[spreadsheet_id]" id="generalSpreadsheetId" class="form-control" onchange="findWorksheet(jQuery(this));">
										<option value="">-- Select Spreadsheet --</option>
										<?php 
											foreach($listOfFiles as $files){
										?>
											<option value="<?php echo $files->id?>"><?php echo $files->title?></option>
										<?php
											}
										?>
									</select>
									<input type="hidden" class="form-control input-string"  name="general[file_url]" id="litigationFileUrl" value=""/>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
									<label for="generalTechnologies" class="control-label" style="margin-top: 2px;">
										<strong>Select Worksheet:</strong>
									</label>
									<select name="general[worksheet_id]" id="generalWorksheetId" class="form-control" onchange="findWorksheetUrl(jQuery(this))"></select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-2" style="margin-top:13px; width:12%;">
						<div class="form-group">
							<!--<label for="marketProspectsName" class="control-label" style="float:left;"><strong>Lead type:</strong></label>-->
							<select class="form-control" name="market[type]">
								<option value="Market">From Market</option>
								<option value="General">From Proactive General</option>
								<option value="SEP">From Proactive SEP</option>
							</select>
						</div>
					</div>
					<div class="col-xs-4 mrg10T" style="width:38%;">
						<button type="button" class="btn btn-default btn-mwidth pull-right" onclick="findPatentFromSheet()" tabindex="13">Import / Update Data</button>
						<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;">
						  
						</div>
					</div>
				</div>
			<?php echo form_close()?>
			<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.css">
			<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.css">
			<script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/lib/bootstrap-typeahead.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jquery.autoresize.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/extensions/jquery-ui-1.8.23.draggable.min.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
			<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
			<script>
				function findPatentFromSheet(){
					if(jQuery("#litigationFileUrl").val()!=""){
						snapGlobal= jQuery("#litigationFileUrl").val();
						jQuery("#loadingLabel").html('<i style="color: rgb(34, 34, 34);" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A"></i>');
						jQuery.ajax({
							url:'<?php echo $Layout->baseUrl?>leads/googleSpreadSheet',
							type:'POST',
							data:{file_url:jQuery("#litigationFileUrl").val()},
							cache:false,
							success:function(data){
								if(data!=""){
									jQuery("#loadingLabel").html('');
									// jQuery("#scrap_patent_data").handsontable("destroy");
									// var $container = jQuery("#scrap_patent_data");
									// $container.handsontable({						
									// 	startRows: 1,
									// 	data:jQuery.parseJSON(data),
									// 	startCols: 9,		
									// 	colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
									// 	manualColumnResize: false,
									// 	manualRowResize: false,
									// 	minSpareRows: 1,
									// 	columnSorting: true,
									// 	persistentState: false,
									// 	contextMenu: false,
									// 	fixedRowsTop: 0,
									// 	columns: [
									// 				{renderer: coverRenderer},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{renderer: renderReadOnly},
									// 				{}
									// 			]
									// });
								} else {
									jQuery("#loadingLabel").html('Error while importing');
								}
							}
						});
					}
				}
				jQuery(document).ready(function(){
					// var $container = jQuery("#scrap_patent_data");
					// $container.handsontable({
					//  //  data:jQuery.parseJSON(_ddd),
					// 	startRows: 1,
					// 	startCols: 9,								
					// 	colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
					// 	manualColumnResize: false,
					// 	manualRowResize: false,
					// 	minSpareRows: 1,
					// 	columnSorting: true,
					// 	persistentState: false,
					// 	contextMenu: false,
					// 	fixedRowsTop: 0,
					// 	columns: [
					// 				{renderer: coverRenderer},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{renderer: renderReadOnly},
					// 				{}
					// 			]
					// });
				});	
				function coverRenderer(instance, td, row, col, prop, value, cellProperties){
					if(value!=null && value!=""){
						var escaped = Handsontable.helper.stringify(value);
						var a = jQuery("<a/>");
						jQuery(a).attr('href',"javascript://");
						jQuery(a).attr('onclick',"getGooglePatent('"+escaped+"')");						
						//Handsontable.Dom.empty(td);
						//jQuery(td).html(a);
						td.innerHTML = "<a href='javascript://' class='btn' onclick='getGooglePatent(\""+jQuery.trim(escaped)+"\")'>"+escaped+"</a>";
					//	cellProperties.readOnly = true;
						return td;
					}						
				}
				function renderReadOnly(instance, td, row, col, prop, value, cellProperties){
					if(value!=null && value!=""){
						td.innerHTML = value;
						cellProperties.readOnly = true;
						return td; 
					}				
				}
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
								//jQuery("#scrapSlidebarTitle").html(patent +'<span class="caret"></span>');
								jQuery("#loading_spinner_heading_google_scrap").css('display','none');									
								jQuery("#scrapGooglePatent").html(data);	
							}
						});
					}						
				}
				function dataValidate(){
					var $container = $("#scrap_patent_data");
					hst = $container.data('handsontable');
					jQuery("#marketPatentData").val(JSON.stringify(hst.getData()));
					return true;
				}
                function refreshHST(){
						// jQuery("#scrap_patent_data").handsontable("destroy"); 
						// var $container = jQuery("#scrap_patent_data");
						// $container.handsontable({						
						// 	startRows: 1,
						// 	startCols: 9,		
						// 	colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
						// 	manualColumnResize: false,
						// 	manualRowResize: false,
						// 	minSpareRows: 1,
						// 	columnSorting: true,
						// 	persistentState: false,
						// 	contextMenu: false,
						// 	fixedRowsTop: 0,
						// 	columns: [
						// 				{renderer: coverRenderer},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{renderer: renderReadOnly},
						// 				{}
						// 			]
						// });
					}
			</script>
            <!--  Clear Button Patent Data -->
            <div style="clear:both;" class="mrg10T clearfix">
            	<a href="#" class='link-blue pull-right' style='text-decoration:none;' onclick="refreshHST()">
            		<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
            		Clear Table
            	</a>
            </div>
            <div class="mrg5T" style='margin-top:5px;width:100%;padding:0;' id="patent_data">
				<!-- <div class="panel panel-no-margin">
					<div class="panel-body"> -->
						<div class="example-box-wrapper">
							<!-- <div class="handsontable" id="scrap_patent_data" > -->
							<!-- </div> -->
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Patent</th>
										<th>* * * * * Notes * * * * *</th>
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
									<tr>
										<td>1</td>
										<td>2</td>
										<td>3</td>
										<td>4</td>
										<td>5</td>
										<td>6</td>
										<td>7</td>
										<td>8</td>
										<td>9</td>
									</tr>
								</tbody>
							</table>
						</div>
					<!-- </div>
				</div> -->
			</div>
            
				<div class="col-xs-12">
					<div id="list_boxes">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<?php echo $Layout->element('timeline');?>
</div>

<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="create_spreadsheet">   
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
                            <label style="float:left;" class="control-label">Spreadsheet:</label>
                            <input type="text" name="spreadsheet"  id="spreadsheet" value="" required="required"/>
                        </div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                <button  class="btn btn-default" type="button" onclick="create_spreadsheet();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>
<script>
 function spreadsheet_box(){
    jQuery('#create_spreadsheet').modal('show');
}
function create_spreadsheet(){
   var spreadsheet = jQuery('#spreadsheet').val();
   if(spreadsheet == "")
   {
     jQuery('#spreadsheet').css('border-color','#ff0000');
   }
   if(spreadsheet != '')
   {
        jQuery.ajax({
            type:'POST',
            url:'<?php echo $Layout->baseUrl?>index.php/leads/createLeadPatentSpreadSheet',
            data:{'n':spreadsheet},
            success:function(response){
                obj = JSON.parse(response);
                alert(obj.message);
                jQuery('#create_spreadsheet').modal('hide');
            }
        });
   }
}
</script>