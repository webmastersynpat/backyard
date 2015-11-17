<?php 
	$openTask = true;
	if((int)$this->session->userdata['type']!=9){  
		if(!in_array(6,$this->session->userdata['modules_assign'])){
			$openTask = false;
		}
	}
	if($openTask===true):
?>

<style>
	#task_list .dashboard-box, #my_c_task_list .dashboard-box {
		overflow-y: auto !important;
	}
</style>
<div class="col-md-2 col-sm-2 col-xs-2 hide" id="my_c_task_list" >
	<div class="dashboard-box  bg-white content-box">
		<div class="content-wrapper" style="margin-top:1px;">
			<div class="clearfix mrg6B">
				<h3 class='font-black font-size-17 text-center'>Tasks that I Created</h3>
			</div>
			<ul class="todo-box">
				<?php 
					$mycreatedTaskList = getUserMyCreatedTaskList();
					if(count($mycreatedTaskList)>0){
						for($i=0;$i<count($mycreatedTaskList);$i++){
							if((int)$mycreatedTaskList[$i]->userType==9 && $mycreatedTaskList[$i]->approved_type=='LEAD'){
								$userName = "System";
							} else {
								$userName = $mycreatedTaskList[$i]->userName;
							}
						?>
					<li>
						<?php
							$colors_array="";
							switch($mycreatedTaskList[$i]->type){
								case 'Litigation':
									$colors_array = "bg-yellow";
								break;
								
								case 'Market':
								case 'NON':
								case 'INT':
									$colors_array = "bg-green";
								break;
								
								case 'General':
									$colors_array = "label-info";
								break;
								
								case 'SEP':
									$colors_array = "bg-warning";
								break;
							}
							$leadName = (!empty($mycreatedTaskList[$i]->lead_name))?$mycreatedTaskList[$i]->lead_name:$mycreatedTaskList[$i]->plantiffs_name;
						?>
							<a href="javascript://" onclick="approvedFile(<?php echo $mycreatedTaskList[$i]->approved_id;?>,1)" >
								<?php 
									if(!empty($leadName)) {
										echo '<span class="tl-label bs-label '.$colors_array.'">'.$leadName.'</span>';
									}
								?>
								<span class="todo-container">
									<span class="todo-content" for="todo-1" title="<?php echo $mycreatedTaskList[$i]->subject;?>"><?php echo $mycreatedTaskList[$i]->subject;?></span>
									<span class="todo-footer clearfix">
										<span class="todo-footer-dateuser" style="opacity:0.8">
											<?php echo date("M d, Y",strtotime($mycreatedTaskList[$i]->taskCreateDate));?>
											<?php echo "&nbsp;&nbsp;&nbsp;".$this->session->userdata['name'];?>
											<?php
												if($mycreatedTaskList[$i]->notifyStatus==1){											
													echo '&nbsp;&nbsp;&nbsp;<span class="btn-blink" style="color:red;"><b>Completed</></span>';
												}
											?>
										</span>
                                        <?php 
    										if(!empty($mycreatedTaskList[$i]->profile_pic)):
    									?>
    									<img src="<?php echo $mycreatedTaskList[$i]->profile_pic?>" title="<?php echo $mycreatedTaskList[$i]->userName?>"  width="28"/>
    									<?php
    										else:
    									?>
    									<img src="<?php echo $Layout->baseUrl?>public/upload/user.png" title="<?php echo $mycreatedTaskList[$i]->userName?>" width="28" />
    									<?php
    										endif;
    									?>    
									</span>
								</span>
							</a>
							</li>
				<?php
					} } else {
				?>
					<li class='border-red' style='padding:6px;'>Empty</li>
				<?php					
					}
				
				?>
			</ul>
		</div>
	</div>
</div>
<div class="col-md-2 col-sm-2 col-xs-2" id="task_list" >
<?php 
    $checkLead = $this->uri->segment(3);
	if((int)$this->session->userdata['type']!=9){
	$pageAssigned = getUserPageAssigned();
	$litigationCreate = 0; 
	$marketCreate = 0;
	if(count($pageAssigned)>0){		
		foreach($pageAssigned as $page){
			if($page->page_url=='leads/litigation'){
				$litigationCreate = 1;
			}
			if($page->page_url=='leads/market'){
				$marketCreate = 1;
			}
		}
	}
	if($litigationCreate>0){
		/*Check 3 task for this week*/
		$checkTask = checkUserCreatedLeadFromLitigation();
		if((int)$checkTask->leads==0 || (int)$checkTask->leads<3){
			/*Create task for user for 3 leads*/
			/*Check today approval send*/
			$checkApprovalSend = checkApprovalSend();
			if((int)$checkApprovalSend->sendTask==0){
				$allAdminUsers = findAdminUsers();
				if(count($allAdminUsers)>0){
					$getData = getTaskAccToType("LEAD");
					$subject="Create leads for this week";
					$message = "You have to create 3 leads per week.";
					if(count($getData)>0){
						$subject = $getData->subject;
						$message = $getData->message;
					}
					$requestArray = array('lead_id'=>0,'user_id'=>$this->session->userdata['id'],'parent_id'=>0,'subject'=>$subject,'message'=>$message,'from_user_id'=>$allAdminUsers[0]->id,'doc_url'=>$Layout->baseUrl."leads/litigation","execution_date"=>date('Y-m-d'),"type"=>"LEAD","status"=>0);
					sendApprovalRequest($requestArray);
				}				
			}			
		}
	}	
}
	$waiting_approval = getUserTaskList();
	
?>
<script>
__dashFlag = true;
setInterval(callnotification,300000); 
_UserProfile ='<?php echo $this->session->userdata['profile_pic']?>';

/*setInterval(getLoggedTime,600000);*/
function callnotification(){
	jQuery.ajax({
		type:'POST',
		url:'<?php echo $this->config->base_url()?>dashboard/callnotification',
		data:{f:__dashFlag,l:leadGlobal},
		cache:false,
		success:function(data){
			_data = jQuery.parseJSON(data);	
			if(_data.taskCount>0){
				if(jQuery("#task-btn").find('span').length>0){
					jQuery("#task-btn").find('span').html(_data.taskCount);
				} else {
					jQuery("#task-btn").css('position','relative').find('i').before("<span class='bs-badge badge-absolute bg-red' style='top:-8px;left:-10px;padding:0 7px 0 5px'>"+_data.taskCount+"</span>");
				}
			}else {
				jQuery("#task-btn").find('span').remove();								
			}	
			
			if(_data.taskList.length>0){
				jQuery("#task_list").find('ul.todo-box').empty();
				
				for(t=0;t<_data.taskList.length;t++){
					_task = _data.taskList[t];
					if((__dashFlag==true && leadGlobal==0) || (leadGlobal==_task.id && __dashFlag==false)){					
						_colorClass = "";						
						switch(_task.type){
							case 'Litigation':
								_colorClass = "bg-yellow";
							break;
							case 'Market': 
							case 'NON': 
							case 'INT': 
								_colorClass = "bg-green";
							break;
							
							case 'General':
								_colorClass = "label-info";
							break;
							
							case 'SEP':
								_colorClass = "bg-warning";
							break;
						}
						_userName = "System";
						if(parseInt(_task.userType)==9 && _task.approved_type=='LEAD'){
							_userName = "System";
						} else {
							_userName = _task.userName;
						}
						li = '<li><a href="javascript://" onclick="approvedFile('+_task.approved_id+')" >'+
									'<span class="tl-label bs-label '+_colorClass+'">'+_task.lead_name+'</span>'+
									'<span class="todo-container">'+
										'<span class="todo-content" for="todo-1" title="'+_task.subject+'">'+_task.subject+'</span>'+
										'<span class="todo-footer clearfix">'+
											'<span class="todo-footer-dateuser" style="opacity:0.8">'+moment(new Date(_task.taskCreateDate)).format('MMM D, YYYY')+'&nbsp;&nbsp;&nbsp;'+_userName;
										if(_task.notifyStatus==1){											
											li +='&nbsp;&nbsp;&nbsp;<span class="btn-blink" style="color:red;"><b>Completed</></span>';
										}
											
						li	+='</span>';
											if(_task.profile_pic!=""){
												li+='<img src="'+_task.profile_pic+'" width="28" title="'+_task.uuserName+'"/>';
											} else {
												li+='<img src="<?php echo $Layout->baseUrl?>public/upload/user.png" width="28" title="'+_task.uuserName+'"/>';
											}
											   
										li+='</span>'+ 
											'</span>'+
											'</a></li>';
						
						jQuery("#task_list").find('ul.todo-box').append(li);
					} else {
						jQuery("#task-btn").find("span").remove();
					}					
				}
			}else{
				jQuery("#task_list").find('ul.todo-box').empty();
			}
			if(_data.countReceieve>0){
				if(jQuery("#btn-notify-user").find('span').length>0){
					jQuery("#btn-notify-user").find('span').html(_data.countReceieve);
				} else {
					jQuery("#btn-notify-user").find('i').before("<span class='bs-badge badge-absolute bg-red' style='padding:0 7px 0 5px;left:10px;'>"+_data.countReceieve+"</span>");
				}
			}else {
				jQuery("#btn-notify-user").find('span').remove();								
			}	
			if(_data.countSend>0){
				if(jQuery("#btn-my-notify-user").find('span').length>0){
					jQuery("#btn-my-notify-user").find('span').html(_data.countSend);
				} else {
					jQuery("#btn-my-notify-user").find('i').before("<span class='bs-badge badge-absolute bg-red' style='padding:0 7px 0 5px;left:10px;'>"+_data.countSend+"</span>");
				}
			}else {
				jQuery("#btn-my-notify-user").find('span').remove();								
			}
			jQuery("#notifications-btn").find('ul.todo-box').empty();
			if(_data.receive.length>0){			
				for(i=0;i<_data.receive.length;i++){
					switch(_data.receive[i].leadType){
						case 'Litigation':
							_colorClass = "bg-yellow";
						break;
						
						case 'Market': 
						case 'NON': 
						case 'INT': 
							_colorClass = "bg-green";
						break;
						
						case 'General':
							_colorClass = "label-info";
						break;
						
						case 'SEP':
							_colorClass = "bg-warning";
						break;
					}
					_userImage = "<?php echo $this->config->base_url()?>public/upload/user.png";
					if(_data.receive[i].profile_pic!=""){
						_userImage = _data.receive[i].profile_pic;
					}
					li = '<li>'+
								'<a href="javascript://" onclick="openMessageBox('+_data.receive[i].taskID+')" style="text-decoration:none;">'+
								'<span class="tl-label bs-label bg-green">'+_data.receive[i].lead_name+'</span>'+
									'<span class="todo-container">'+
										'<span class="todo-content" for="todo-1" title="'+_data.receive[i].subject+'">'+_data.receive[i].subject+'</span>'+
										'<span class="todo-footer clearfix">'+
											'<span class="todo-footer-dateuser">'+moment(new Date(_data.receive[i].create_c)).format('MMM DD,YY')+'&nbsp;&nbsp;&nbsp;'+_data.receive[i].fromUserName+'</span><img title="'+_data.receive[i].userName+'" src="'+_userImage+'" width="28"/>'+
										'</span>'+
									'</span>'+
								'</a>'+
							'</li>';
					
					jQuery("#notifications-btn").find('ul.todo-box').append(li);
				}
			} else {
				jQuery("#notifications-btn").find('ul.todo-box').append("<li><p class='alert'>No record for You!</p></li>");
			}
			
			jQuery("#my-notifications-btn").find('ul.todo-box').empty();
			if(_data.sent.length>0){			
				for(i=0;i<_data.sent.length;i++){
					switch(_data.sent[i].leadType){
						case 'Litigation':
							_colorClass = "bg-yellow";
						break;
						
						case 'Market': 
						case 'NON': 
						case 'INT': 
							_colorClass = "bg-green";
						break;
						
						case 'General':
							_colorClass = "label-info";
						break;
						
						case 'SEP':
							_colorClass = "bg-warning";
						break;
					}
					_userImage = "<?php echo $this->config->base_url()?>public/upload/user.png";
					if(_data.sent[i].profile_pic!=""){
						_userImage = _data.sent[i].profile_pic;
					}
					li = '<li>'+
								'<a href="javascript://" onclick="openMessageBox('+_data.sent[i].taskID+')" style="text-decoration:none;">'+
								'<span class="tl-label bs-label bg-green">'+_data.sent[i].lead_name+'</span>'+
									'<span class="todo-container">'+
										'<span class="todo-content" for="todo-1" title="'+_data.sent[i].subject+'">'+_data.sent[i].subject+'</span>'+
										'<span class="todo-footer clearfix">'+
											'<span class="todo-footer-dateuser">'+moment(new Date(_data.sent[i].create_c)).format('MMM DD,YY')+'&nbsp;&nbsp;&nbsp;'+_data.sent[i].fromUserName+'</span><img title="'+_data.sent[i].userName+'" src="'+_userImage+'" width="28"/>'+
										'</span>'+
									'</span>'+
								'</a>'+
							'</li>';
					
					jQuery("#my-notifications-btn").find('ul.todo-box').append(li);
				}
			} else {
				jQuery("#my-notifications-btn").find('ul.todo-box').append("<li><p class='alert'>No record for You!</p></li>");
			}
		}
	});
}

function getLoggedTime(){
	jQuery.ajax({
		type:'GET',
		url:'<?php echo $Layout->baseUrl?>dashboard/m_time',
		cache:false,
		success:function(data){
			jQuery("#nc_t").html("("+data+")");
		}
	});
}

_allParent = "";
function enterData(t,ID){
	if(t==1){
		jQuery('.other_damages').css('display','none');
		jQuery('.other_docs').css('display','none');
		jQuery("#otherDamages_"+ID).css('display','block');
	} else if(t==2){
		jQuery('.other_docs').css('display','none');
		jQuery('.other_damages').css('display','none');
		jQuery("#otherDOCS_"+ID).css('display','block');
	}
}
	function approvedFile(token,o){
		if(token!="" && token>0){
			var lead_url = "";
			var refreshParent = $("#sb-site");
			var loaderTheme = "bg-default";
			var loaderOpacity = "60";
			var loaderStyle = "dark";
			var loader = 
				'<div id="refresh-overlay" class="ui-front loader ui-widget-overlay ' + loaderTheme + ' opacity-' + loaderOpacity + '">' +
					'<div class="loading-spinner is-window">' +
						'<img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="">' +
					'</div>' +
				'</div>';
			if ( $('#refresh-overlay').length ) {
				$('#refresh-overlay').remove();
			}
			$(refreshParent).append(loader);
			$('#refresh-overlay').fadeIn('fast');
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>opportunity/find_task',
				data:{token:token},
				cache:false,
				success:function(res){					
					_data = jQuery.parseJSON(res);
					_allParent = _data;
					$('#refresh-overlay').fadeOut('fast');
					$('.glyph-icon', this).removeClass('icon-spin');
					if(_data!=undefined){						
						if (_data.hasOwnProperty('type')) {
							if(parseInt(_data.userType)==9 && _data.approved_type=='LEAD'){
								jQuery("#replyFromUser").html("Admin");
							} else {
								jQuery("#replyFromUser").html(_data.userName);
							}					
							jQuery("#replyToUser").html(_data.toUserName);							
							jQuery("#replyReceived").html(moment(new Date(_data.receivedData)).format('MMM D, YY'));
							jQuery("#replySubject").html(_data.subject);
							jQuery("#replyInputSubject").val(_data.subject);
							if(_data.doc_url!=""){
								jQuery("#replyLink").html("<a target='_BLANK' style='color:#3498db;' href='"+_data.doc_url+"'>Document Link</a>");
							} else {
								jQuery("#replyLink").html("No docs");
							}							
							__message = _data.message;
							if(_data.hasOwnProperty('parents') && _data.parents.length>0){
								for(i=0;i<_data.parents.length;i++){
									_pa = _data.parents[i];
									__message += "<br/><hr/>FROM: "+_pa.userName+"<br/>"+_pa.message;
								}
							}
							jQuery("#ticket__body p").html(__message);
							_executionDate = "";
							if(_data.executionDate!=null && _data.executionDate!='0000-00-00'){
								_executionDate = moment(new Date(_data.executionDate)).format('MMM D, YY');
							}							
							jQuery("#replyExecutionDate").html(_executionDate);
							jQuery("#replyLeadId").val(_data.id);
							_leadName = "";
							if(_data.lead_name!="" && _data.lead_name!=null){
								_leadName = _data.lead_name;
							} else if(_data.plantiffs_name!="" && _data.plantiffs_name!=null){
								_leadName = _data.plantiffs_name;
							}
                            lead_url = "<?php echo $Layout->baseUrl; ?>dashboard/index/"+_data.id;
                            jQuery("#replyTaskModalLabel").html("<a href='"+lead_url+"'>"+_leadName.toUpperCase()+"</a>");
            				jQuery("#replyParentId").val(_data.approved_id);
							jQuery("#replyType").val(_data.approved_type);							
							jQuery("#formOPen").removeAttr('onclick').hide();
							jQuery("#replyForwardTask").show();
							jQuery("#replyCompleteTask").show();
							if(_data.notifyStatus==1){
								jQuery("#replyComplete").attr("checked","checked");
							} else {
								jQuery("#replyComplete").removeAttr("checked");
							}
							if(typeof o!="undefined"){
								jQuery("#deleteTask").css("display","block");
								jQuery("#replyComplete").attr("disabled","disabled");
							} else {
								jQuery("#deleteTask").css("display","none");
								jQuery("#replyComplete").removeAttr("disabled");
							}
							if(_data.approved_type=="OTHER_DOCS"){
								jQuery("#replyForwardTask").hide();
								jQuery("#replyCompleteTask").show();
								jQuery("#formOPen").show();
								jQuery("#formOPen").attr('onclick','enterData(2,'+_data.approved_id+')');
							} else if(_data.approved_type=="ORDER_DAMAGES"){
								jQuery("#formOPen").show();
								jQuery("#formOPen").attr('onclick','enterData(1,'+_data.approved_id+')');
								jQuery("#replyForwardTask").hide();
								jQuery("#replyCompleteTask").show();
							}
							if(_data.id===null){
								jQuery("#replyFlag").val(1);
								jQuery("#replyForwardTask").hide();
							} else {
								jQuery("#replyFlag").val(0);								
							}
							_h = jQuery(window).height()-50;
							jQuery('#replyTaskModal').find('.modal-body').css('height',_h+'px');
							jQuery('#replyTaskModal').modal('show');
							messageListCheck();
						} else {
							alert("Please try after sometime");
						}						
					} else {
						alert("Please try after sometime");
					}
				}
			});
		}
	}
	
	function goToMainTask(){
		jQuery('#messagTaskModal').modal('hide');
		approvedFile(jQuery("#replyParentId").val());
	}
	function messageListCheck(){
		jQuery("#messageListTask").css('display','block');
		_taskID = jQuery("#replyParentId").val();
		
		if(_taskID>0){
			jQuery("#messageTaskID").val(_taskID);
			jQuery.ajax({
				url:'<?php echo $Layout->baseUrl?>dashboard/task_conversation',
				type:'POST',
				data:{c:_taskID},
				cache:false,
				success:function(data){
					jQuery("#messageListTask").css('display','none');
					_dd = jQuery.parseJSON(data);
					if(_dd.length>0){
						jQuery("#message-inbox-task-list").empty();
						
						for(i=0;i<_dd.length;i++){
							_profilePic = '<?php echo $this->config->base_url();?>public/upload/user.png';
							if(_dd[i].profile_pic!=""){
								_profilePic = _dd[i].profile_pic;
							}
							_string = '<div class="comment">'+
								'<div class="comment_content">'+
									'<div class="meta">'+
										'<a href="#"><img src="'+_profilePic+'" alt="'+_dd[i].fromUserName+'" width="28px"></a>'+
										'<ul class="userinfo" style="width: 92%;">'+
											'<li class="pull-left"><h6><a href="javascript://">'+_dd[i].fromUserName+'</a></h6></li>' +
											'<li class="fright">Posted on '+moment(new Date(_dd[i].create_c)).format('MMM D, YY h:mm')+'</li>'+
										'</ul>'+
									'</div>'+
									'<div id="ticket_'+_dd[i].id+'_body" class="message-inbox-detail">'+
										'<p style="">'+_dd[i].message+'</p>'+
									'</div>'+
								'</div>'+
								'<a id="ticket_'+_dd[i].id+'_body_button" href="" class="message-inbox-toggler"><span></span></a>'+
							'</div>';
							console.log(_string);
							jQuery("#message-inbox-task-list").append(_string);
						}

						/** Message inbox detail */
						$('.message-inbox-toggler').off('click').on('click', function(e) {
							$(this).toggleClass('is-open');
							$(this).parent().find('.message-inbox-detail').toggleClass('is-open');

							return false;
						});

						$('#expand_collapse_button').off('click').on('click', function() {
							var $this = $(this);

							if(!$this.hasClass('is-open')) {
								$this.parent().parent().find('.message-inbox-detail').addClass('is-open');
								$this.parent().parent().find('.message-inbox-toggler').addClass('is-open');
								$this.addClass('is-open').html('- Collapse All');
							}
							else {
								$this.parent().parent().find('.message-inbox-detail').removeClass('is-open');
								$this.parent().parent().find('.message-inbox-toggler').removeClass('is-open');
								$this.removeClass('is-open').html('+ Expand All');
							}

							return false;
						});
					} else {
						jQuery("#message-inbox-task-list").empty();
					}
				}
			});
		}
	}
	function expand(elid){
		$('#'+elid).slideToggle(600);
		$('#'+elid + '_button' ).removeClass( 'expand' );
		$('#'+elid + '_button' ).addClass( 'collapsee' );
		$('#'+elid + '_button' ).attr('href',$('#'+elid + '_button' ).attr('href').replace( 'expand', 'collapse' ));
	}
	function collapse(elid){
		$('#'+elid).slideToggle(600);
		$('#'+elid + '_button' ).removeClass( 'collapsee' );
		$('#'+elid + '_button' ).addClass( 'expand' );
		$('#'+elid + '_button' ).attr('href',$('#'+elid + '_button' ).attr('href').replace( 'collapse', 'expand' ));
	}
	function collapseAll(){
		$('div[id^=ticket_]').each(function(){
			jQuery(this).slideToggle(600);
			elid = jQuery(this).attr('id');
			$('#'+elid + '_button' ).removeClass( 'collapsee' );
			$('#'+elid + '_button' ).addClass( 'expand' );
			$('#'+elid + '_button' ).attr('href',$('#'+elid + '_button' ).attr('href').replace( 'collapse', 'expand' ));
		});
		$( '#expand_collapse_button' ).attr('href',$( '#expand_collapse_button' ).attr('href').replace( 'collapse', 'expand' ));
		$( '#expand_collapse_button' ).html( '+ Expand All' );
	}
	function expandAll(){
		$('div[id^=ticket_]').each(function(){
			jQuery(this).slideToggle(600);
			elid = jQuery(this).attr('id');
			$('#'+elid + '_button' ).removeClass( 'expand' );
			$('#'+elid + '_button' ).addClass( 'collapsee' );
			$('#'+elid + '_button' ).attr('href',$('#'+elid + '_button' ).attr('href').replace( 'expand', 'collapse' ));
		});
		$( '#expand_collapse_button' ).attr('href',$( '#expand_collapse_button' ).attr('href').replace( 'expand', 'collapse' ));
		$( '#expand_collapse_button' ).html( '- Collapse All' );
	}
	
	function removeTaskConversation(){
		_taskID = jQuery("#replyParentId").val();
		if(_taskID>0){
			jQuery("#senTaskConversation").removeAttr('onclick');
			jQuery.ajax({
				url:'<?php echo $Layout->baseUrl?>dashboard/message_task_conversation_remove',
				type:'POST',
				data:{c:_taskID},
				cache:false,
				success:function(){
					jQuery('#messagTaskModal').modal('show');
				}
			});
		}
	}
	
	function sendMessageTaskFor(){
		jQuery("#loading_task_conversation").show();
		if(jQuery("#messageReplyTask").val()!=""){			
			jQuery("#senTaskConversation").removeAttr('onclick');
			_searilise = jQuery("#formTaskConversation").serialize();
			jQuery.ajax({
				url:'<?php echo $Layout->baseUrl?>dashboard/message_task_conversation_send',
				type:'POST',
				data:_searilise,
				cache:false,
				success:function(data){
					_data = jQuery.parseJSON(data);
					if(_data.length>0){
						jQuery("#messageConversationTask").val('');
						jQuery("#loading_task_conversation").hide();
						jQuery("#senTaskConversation").attr('onclick','sendMessageTaskFor()');
						jQuery("#message-inbox-task-list").empty();
						for(i=0;i<_data.length;i++){
							_profilePic = '<?php echo $this->config->base_url();?>public/upload/user.png';
							if(_data[i].profile_pic!=""){
								_profilePic = _data[i].profile_pic;
							}
							_string = '<div class="comment">'+
								'<div class="comment_content">'+
									'<div class="meta">'+
										'<a href="javascript://"><img src="'+_profilePic+'" width="28px" alt="'+_data[i].fromUserName+'"></a>'+
										'<ul class="userinfo" style="width: 92%;">'+
											'<li class="pull-left"><h6><a href="javascript://">'+_data[i].fromUserName+'</a></h6></li><li class="fright">Posted on '+moment(new Date(_data[i].create_c)).format('MMM D, YY h:mm')+'</li>'+
										'</ul>'+
									'</div>'+
									'<div id="ticket_'+_data[i].id+'_body" style="overflow: visible; display: none;" class="message-inbox-detail">'+
										'<p style="">'+_data[i].message+'</p>'+
									'</div>'+
								'</div>'+
								'<a id="ticket_'+_data[i].id+'_body_button" href="javascript: expand(\'ticket_'+_data[i].id+'_body\' )" class="expander expand"><span></span></a>'+
							'</div>';
							jQuery("#message-inbox-task-list").append(_string);
						}					
					} else {
						jQuery("#loading_task_conversation").hide();
						jQuery("#senTaskConversation").attr('onclick','sendMessageTaskFor()');
						alert("Server busy, Please refresh page");
					}
				}
			});			
		} else {
			jQuery("#loading_task_conversation").hide();
			alert("Please enter message");
			jQuery("#senTaskConversation").attr('onclick','sendMessageTaskFor');
		}
	}
	function forwardEnabled(object){
		if(object.is(':checked')){
			jQuery("#forwardUserTo").show();
			jQuery("#forwardMessageTo").show();
			jQuery("#forwardExecutionDate").show();
			jQuery("#replyButton").html('Forward').show();
			jQuery("#replyCompleteTask").hide();
		} else {
			jQuery("#forwardUserTo").hide();
			jQuery("#forwardMessageTo").hide();
			jQuery("#forwardExecutionDate").hide();
			jQuery("#replyButton").hide();
			jQuery("#replyCompleteTask").show();
		}
	}
	function taskMyComplete(object){
		if(object.is(':checked')){
			jQuery("#replyButton").html('Confirm Removal').show();
			jQuery("#forwardUserTo").hide();
			jQuery("#forwardMessageTo").hide();
			jQuery("#forwardExecutionDate").hide();
		} else{
			jQuery("#replyButton").hide();
			jQuery("#forwardUserTo").hide();
			jQuery("#forwardMessageTo").hide();
			jQuery("#forwardExecutionDate").hide();
		}
	}
	function taskComplete(object){
		if(object.is(':checked')){
			jQuery("#replyButton").html('Confirm Completion').show();
			jQuery("#forwardUserTo").hide();
			jQuery("#forwardMessageTo").hide();
			jQuery("#forwardExecutionDate").hide();
		} else{
			jQuery("#replyButton").hide();
			jQuery("#forwardUserTo").hide();
			jQuery("#forwardMessageTo").hide();
			jQuery("#forwardExecutionDate").hide();
		}
	}
</script>
	<div class="dashboard-box  bg-white content-box">
		<div class="content-wrapper" style="margin-top:1px;">
			<div class="clearfix mrg6B">
				<!--<a href="javascript://" onclick="openTaskModal()" class="btn btn-create btn-block" >
					Create new task
				</a>-->
				<h3 class='font-black font-size-17 text-center'>Tasks Created For Me</h3>
				<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
  					<div class="modal-dialog">
    					<div class="modal-content">
      						<div class="modal-header">
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						<h4 class="modal-title" id="createTaskModalLabel">Create new task</h4>
      						</div>
      						<div class="modal-body">
      						</div>
      						<div class="modal-footer">
        						<button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>
        						<button type="button" class="btn btn-primary btn-mwidth">Save</button>
      						</div>
    					</div>
  					</div>
				</div>
			</div>
			<ul class="todo-box">
				<?php
					
					if(count($waiting_approval)>0){
						for($i=0;$i<count($waiting_approval);$i++){
							$name = "";
							switch($waiting_approval[$i]->approved_type){
								case 'NDA':
									$name = "NDA approval for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'PPA':
									$name = "PPA approval for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'DD_FILE_MAKER':
									$name = "Create DD File Maker for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'MARKET_RESEARCH':
									$name = "Do Market Research for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'ASSETS':
									$name = "List of Assets for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'DD':
									$name = "Enter DD data in Docket for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'ORDER_DAMAGES':
									$name = "Upload order damages report for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'OTHER_DOCS':
									$name = "PPA, PLA, RTP, PPP report for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'LEAD':
									$name  ="Create Leads for this week"; 
								break;
								case 'LEAD_FORWARD':
									$name  ="One lead is forward to you";
								break;
								case 'CREATE_OPPORTUNITY':
									$name  ="Create opportunity from Lead";
								break;
								case 'NDA_EXECUTE_APPROVAL':
									$name  ="NDA Execute for ".$waiting_approval[$i]->plantiffs_name;
								break;
								case 'opportunity':
								case 'lead':
									$name = $waiting_approval[$i]->subject;
								break;
								default:
									$name = $waiting_approval[$i]->plantiffs_name;
								break;
							}
							
							if((int)$waiting_approval[$i]->userType==9 && $waiting_approval[$i]->approved_type=='LEAD'){
								$userName = "System";
							} else {
								$userName = $waiting_approval[$i]->userName;
							}
				?>
					<li>
						<?php
							$colors_array="";
							switch($waiting_approval[$i]->type){
								case 'Litigation':
									$colors_array = "bg-yellow";
								break;
								
								case 'Market':
								case 'NON':
								case 'INT':
									$colors_array = "bg-green";
								break;
								
								case 'General':
									$colors_array = "label-info";
								break;
								
								case 'SEP':
									$colors_array = "bg-warning";
								break;
							}
							$leadName = (!empty($waiting_approval[$i]->lead_name))?$waiting_approval[$i]->lead_name:$waiting_approval[$i]->plantiffs_name;
						?>
							<a href="javascript://" onclick="approvedFile(<?php echo $waiting_approval[$i]->approved_id;?>)" >
								<?php 
									if(!empty($leadName)) {
										echo '<span class="tl-label bs-label '.$colors_array.'">'.$leadName.'</span>';
									}
								?>
								<span class="todo-container">
									<span class="todo-content" for="todo-1" title="<?php echo $waiting_approval[$i]->subject;?>"><?php echo $waiting_approval[$i]->subject;?></span>
									<span class="todo-footer clearfix">
										<span class="todo-footer-dateuser">
											<?php echo date("M d, Y",strtotime($waiting_approval[$i]->taskCreateDate));?>
											<?php echo "&nbsp;&nbsp;&nbsp;".$userName;?>
											<?php
												if($waiting_approval[$i]->notifyStatus==1){											
													echo '&nbsp;&nbsp;&nbsp;<span class="btn-blink" style="color:red;"><b>Completed</></span>';
												}
											?>
										</span>
                                        <?php 
    										if(!empty($this->session->userdata['profile_pic'])):
    									?>
    									<img src="<?php echo $this->session->userdata['profile_pic']?>" name width="28"/>
    									<?php
    										else:
    									?>
    									<img src="<?php echo $Layout->baseUrl?>public/upload/user.png" width="28" />
    									<?php
    										endif;
    									?>    
									</span>
								</span>
							</a>
						
						
						
					</li>
				<?php
					} } else {
				?>
					<li class='border-red' style='padding:6px;'>Empty</li>
				<?php
					
					}
				
				?>					
			</ul>					
		</div>			
	 </div>
</div>
<?php endif;?>