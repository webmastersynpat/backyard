<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<style>
.todo-box li.active{
	background:#56b2fe !important;color:#FFF;
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
				<a href="<?php echo $auth_url;?>" class="btn bg-google">
                        <!-- <span class="glyph-icon icon-separator">
                            <i class="glyph-icon icon-google-plus"></i>
                        </span> -->
                        <span class="button-content">
                            Retrieve new Messages
                        </span>
                    </a>
				<?php else:				?>			
				<?php /*echo anchor('leads/logout_gmail','Retrieve new messages',array('class'=>'btn btn-success mrg5B mrg5T'));*/?>
				<a href="<?php echo $Layout->baseUrl;?>leads/retrieve_new_messgaes" class="btn bg-google mrg5B">
                        <!-- <span class="glyph-icon icon-separator">
                            <i class="glyph-icon icon-google-plus"></i>
                        </span> -->
                        <span class="button-content">
                            Retrieve new Messages
                        </span>
                    </a>
				<button class="btn pull-right btn-primary mrg5B" type='button' onclick="passMessageOrLead();">
					<span>Pass</span>
					<i class="glyph-icon icon-arrow-right"></i>
                </button>
				<button class="btn pull-right mrg5B mrg5R btn-danger" onclick="replyLeadsEmail();">
                        <span>Reply</span>
                        <i class="glyph-icon icon-left-right"></i>
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
				<?php if($this->agent->is_browser('Safari')):?>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
				
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
				<?php endif;?>
				<style>
					.drop-hover{border:1px dashed #ff0000 !important;}
					/*#new_lead_drop.drop-hover {border:2px dashed #ff0000 !important; height: 51px; padding: 4px 7px;}*/
					#new_lead_drop.drop-hover {box-shadow: 0 0 1px #ff0000 inset;}
				</style>
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
						_id = "";
						_email = "";
						_subject = "";
						jQuery(".messages-list-leads").find('.message-item').each(function(){
							if(jQuery(this).hasClass('message-active')){
								_flag = 1;
								_type = "message";
								_id = jQuery(this).attr('data-id');
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
							_nn = _email.substr(0,_email.indexOf("<"));
							_ss = _email.substr(_email.indexOf("<"));
							_newem = _ss.substr(1,_ss.indexOf(">")-1)
							jQuery("#emailTo").val(_newem);
							jQuery("#emailSubject").val(_subject);
							jQuery("#emailName").val(jQuery.trim(_nn));
							jQuery("#gmail_message").css("display","block");
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
								$(this).data("uiDraggable").originalPosition = {
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
							jQuery.ajax({
								type:'POST',
								url:'<?php echo $Layout->baseUrl;?>leads/findBoxList',
								data:{boxes:g},
								cache:false,
								success:function(res){
									_data = jQuery.parseJSON(res);
									
									if(_data.detail.length>0){
										jQuery("#marketOwner").val(_data.detail[0].litigation.plantiffs_name);
										jQuery("#marketProspects").val(_data.detail[0].litigation.no_of_prospects);
										jQuery("#marketExpectedPrice").val(_data.detail[0].litigation.expected_price);
										jQuery("#marketProspectsName").val(_data.detail[0].litigation.prospects_name);
										jQuery("#marketlead_name").val(_data.detail[0].litigation.lead_name);
										jQuery("#marketTechnologies").val(_data.detail[0].litigation.technologies);
										_st = '<?php echo $this->session->userdata['type'];?>';
										_sp = '<?php echo $this->session->userdata['id'];?>';
										if(_data.detail[0].comment.length>0){
											_list = "";
											for(c=0;c<_data.detail[0].comment.length;c++){
												_flag = 0;
												if(_data.detail[0].comment[c].user_id==_sp){
													_flag=1;
													jQuery("#marketComment").val(_data.detail[0].comment[c].comment);
													jQuery("#commentId").val(_data.detail[0].comment[c].id);
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
													jQuery(_tr).addClass(_d.message_id).append("<td>"+_showData+"</td><td width='270px;' class='text-right'>"+moment(new Date(_receivedDate)).format('lll')+' | ' + __a+"</td>");
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
						jQuery("#subject").empty();
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
										jQuery("#message-detail .panel-heading").find('#subject').html(subject);
										jQuery('.message-detail-title').show();
										$('#message_detail_from').html(from);
										$('#message_detail_to').html(to);
										$('#message_detail_date').html(moment(new Date(date)).format('lll'));
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
							<div class="col-md-3 list-messages">
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
														}
											?>
													<div class="message-item media  draggable" data-id="<?php echo $message['message_id']?>">
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
							<div class="col-lg-3 list-messages">
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
										<div class="row">
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
										</h2>
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
			<?php echo form_open_multipart('leads/reply_email',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'','id'=>"marketLead"));?>
			<div id="gmail_message" style='margin-top:20px; display:none;'>
				<h4>Gmail Message</h4>
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
								<button type="submit" class="btn btn-primary pull-right">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
			<?php echo form_open('leads/market',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'margin-top:20px;','id'=>"marketLead"));?>
				<div class="row">
					<div class="col-xs-10">
						<div class="row">
							<div class="col-sm-4">
								<div class="row">
									<label for="marketOwner" class="col-sm-12 control-label"><strong>Seller:</strong></label>
									<div class="col-sm-12">
										<?php echo form_input(array('name'=>'market[plantiffs_name]','id'=>'marketOwner','placeholder'=>'','class'=>'form-control input-string'));?>		
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="row">
									<label for="marketTechnologies" class="col-sm-12 control-label"><strong>Tech. / Markets:</strong></label>
									<div class="col-sm-12">
										<?php echo form_input(array('name'=>'market[technologies]','id'=>'marketTechnologies','placeholder'=>'','class'=>'form-control input-string'));?>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row">
									<label for="marketProspects" class="col-sm-12 control-label"><strong>N. of Prospects:</strong></label>
									<div class="col-sm-12">
										<?php echo form_input(array('name'=>'market[no_of_prospects]','id'=>'marketProspects','placeholder'=>'','class'=>'form-control input-string'));?>										  
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row">
									<label for="marketExpectedPrice" class="col-sm-12 control-label"><strong>Expected Price:</strong></label>
									<div class="col-sm-12">
										<?php echo form_input(array('name'=>'market[expected_price]','id'=>'marketExpectedPrice','placeholder'=>'','class'=>'form-control input-string'));?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-2">
						<select required="required" class="form-control" name="market[attractive]" required="required">
							<option value="">Attractiveness</option>
							<option value="High" >High</option>
							<option value="Medium">Medium</option>
							<option value="Low">Low</option>
							<option value="Disapproved">Disapproved</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10">
						<div class="row">
							<!-- <label class="col-sm-1 control-label" for="litigationLinkToPacer">Comment: </label> -->
							<div class="col-sm-12  mrg10T">
								<?php echo form_textarea(array('name'=>'comment[comment]','id'=>'marketComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>29));?>	
							</div>
						</div>
					</div>
					<div class="col-xs-2">
						<div class="row">
							<!-- <label for="marketProspectsName" class="col-sm-12 control-label">Prospect's Name:</label> -->
							<div class="col-sm-12">
								<?php echo form_textarea(array('name'=>'market[prospects_name]','id'=>'marketProspectsName','placeholder'=>"Prospect's Name",'class'=>'form-control mrg10T'));?>
							</div>
						</div>
					</div>
				</div>
				<div class="mrg15T">
					<div class="row">
						<div class="col-xs-5">
							<div class="form-group">
								<label for="marketProspectsName" class="control-label" style="float:left;"><strong>Name of Lead:</strong></label>
								<?php echo form_input(array('name'=>'market[lead_name]','required'=>'required','id'=>'marketlead_name','placeholder'=>'','class'=>'form-control input-string', 'style'=>'float: left; margin-left:5px; width: 66.6666%;'));?>
							</div>
						</div>
						<div class="col-xs-7 text-right">
							<div style="display: inline-block; margin-top: 8px; margin-right: 30px;">
								<input type="checkbox" value="1" name="market[complete]" /> Lead is complete and ready to be forwarded for review
							</div>
							<input type="hidden" name="market[gmail_message_id]" id="lead_gmail_message_id" value=""/>
							<input type="hidden" name="market[id]" id="id" value="0"/>
							<input type="hidden" name="comment[comment_id]" id="commentId" value="0"/>
							<button type="submit" class="btn btn-primary pull-right">Save</button>
						</div>
					</div>
				</div>
			<?php echo form_close()?>
				
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
