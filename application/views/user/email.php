<?php 
	$subject = "";
	$from = "";
	$to = "";
	$cc = "";
	$date = "";
	$email = "";
	$delieveredTo="";
	$star="";
	$_attachmentString="";
	$_messagesString = "";
	$_referenceString="";
	$stringUL = "";
	$attr = "";
	$threadID = "";
	$messageID = "";
	$id = 0;
	$userID = 0;
	if($type==1){
		for($i=0;$i<count($thread_detail);$i++){
			$threadID = $thread_detail[$i]->thread_id;
			$messageID = $thread_detail[$i]->message_id;
			$headers = $thread_detail[$i]['header'];
			$labelIds = $thread_detail[$i]['labelIds'];										
			for($j=0;$j<count($headers);$j++){												
				if($headers[$j]->name=='Subject'){
				$subject = $headers[$j]->value;
				}
				if($headers[$j]->name=='From'){
					$from = $headers[$j]->value;
					/*$from = str_replace("<","",$from);
					$from = str_replace(">","",$from);*/
				}
				if($headers[$j]->name=='To'){
					$to = $headers[$j]->value;
					/*$to = str_replace("<","",$to);
					$to = str_replace(">","",$to);*/
				}
				if($headers[$j]->name=='Delivered-To'){
					$delieveredTo = $headers[$j]->value;
				}
				if($headers[$j]->name=='References'){
					$_rFlag = 1;
					$_referenceString = $headers[$j]->value;
				}
				if($headers[$j]->name=='Cc'){
					$cc = $headers[$j]->value;
				}
				if($headers[$j]->name=='Authentication-Results'){
					$raw = $headers[$j]->value;
					$raw = explode('smtp.mail=',$raw);
					if(count($raw)==2){
						$email = $raw[1];
					}
				}
				if($headers[$j]->name=='Received'){
					$date = $headers[$j]->value;
					$_da = explode(';',$date);
					if(count($_da)==2){
						$date = $_da[1];
					}
				}
			}
			if($from=="" && $email!=""){
				$from=$email;											
			}
			if($to=="" && $delieveredTo!=""){
				$to = $delieveredTo;											
			}
			if(in_array("STARRED",$labelIds)){
				$star = '<i class="glyph-icon icon-star"></i>';
				$attr = 'Starred';
			} else {
				$star = '<i class="glyph-icon icon-star-o"></i>';
				$attr = 'No Starred';
			}
			$body = $thread_detail[$i]['body'];	
			if(count($thread_detail[$i]['attachments'])>0){
				for($a=0;$a<count($thread_detail[$i]['attachments']);$a++){
					$attachmentData  = $thread_detail[$i]['attachments'][$a];
					$_attachmentString .='<li><i class="glyph-icon icon-file" style="color:#2196f3"></i> <a target="_BLANK" href="https://mail.google.com/mail/u/0/?ui=2&view=att&th='.$thread_detail[$i]->message_id.'&disp=safe&realattid='.$attachmentData['realAttachID'].'" dataId="'.$thread_detail[$i]->message_id.'" dataMime="'.base64_decode($attachmentData['mimeType']).'" dataAttached="'.$attachmentData->attachmentId.'" dataFileName="'.$attachmentData['filename'].'" db="getGmailAttachment(jQuery(this))"; class="strong text-regular"><strong>'.$attachmentData['filename'].'</strong></a></li>';
				}
			}
			$_messagesString .='<div class="row">'.
									'<div class="col-md-12 col-sm-12 col-xs-12">'.
									'    <div class="p-20">';
			if($i>0){
				$_messagesString .='<h3 class="message-title">'.$subject.'</h3>';
			}
			$_messagesString .=		'        <div class="message-item media">'.
									'            <div class="message-item-right">'.
									'                <div class="media">'.
									'                    <div class="media-body">'.
									'                        <p class="c-gray"></p>'.
									'                    </div>'.
									'                </div>'.
									'            </div>'.
									'        </div>'.
									'    </div>'.
									'   <div class="message-body" id="message-body">'.$body.
									'    </div>'.
									'</div>'.
								'</div>';
			
			if($i>0 && $i<count($thread_detail)-1){
				$_messagesString .='<div class="message-between"></div>';
			}
			if($i==count($thread_detail)-1){
				$stringUL = "<ul class='todo-box-1'>".$_attachmentString."</ul>";
			}
		}
	} else {
		$mainData = $thread_detail;
		if(count($mainData)>0){
			for($m=0;$m<count($mainData);$m++){
				$id = $mainData[$m]->id;
				$userID = $mainData[$m]->user_id;
				$thread_detail = json_decode($mainData[$m]->content);	
								
				$threadID = $mainData[$m]->thread_id;
				$messageID = $mainData[$m]->message_id;
				for($i=0;$i<count($thread_detail);$i++){
					$headers = $thread_detail[$i]->header;
					$labelIds = $thread_detail[$i]->labelIds;										
					for($j=0;$j<count($headers);$j++){												
						if($headers[$j]->name=='Subject'){
						$subject = $headers[$j]->value;
						}
						if($headers[$j]->name=='From'){
							$from = $headers[$j]->value;
							/*$from = str_replace("<","",$from);
							$from = str_replace(">","",$from);*/
						}
						if($headers[$j]->name=='To'){
							$to = $headers[$j]->value;
							/*$to = str_replace("<","",$to);
							$to = str_replace(">","",$to);*/
						}
						if($headers[$j]->name=='Delivered-To'){
							$delieveredTo = $headers[$j]->value;
						}
						if($headers[$j]->name=='References'){
							$_rFlag = 1;
							$_referenceString = $headers[$j]->value;
						}
						if($headers[$j]->name=='Cc'){
							$cc = $headers[$j]->value;
						}
						if($headers[$j]->name=='Authentication-Results'){
							$raw = $headers[$j]->value;
							$raw = explode('smtp.mail=',$raw);
							if(count($raw)==2){
								$email = $raw[1];
							}
						}
						if($headers[$j]->name=='Received'){
							$date = $headers[$j]->value;
							$_da = explode(';',$date);
							if(count($_da)==2){
								$date = $_da[1];
							}
						}
					}
					if($from=="" && $email!=""){
						$from=$email;											
					}
					if($to=="" && $delieveredTo!=""){
						$to = $delieveredTo;											
					}
					if(in_array("STARRED",$labelIds)){
						$star = '<i class="glyph-icon icon-star"></i>';
						$attr = 'Starred';
					} else {
						$star = '<i class="glyph-icon icon-star-o"></i>';
						$attr = 'No Starred';
					}
					$body = $thread_detail[$i]->body;	
					if($i==0){
						$_files = explode(',',$mainData[$m]->file_attach);						
						if(count($_files)>0){
							for($a=0;$a<count($_files);$a++){
								if($_files[$a]!=""){
									$filename = stristr($_files[$a],'upload');
									$filenameSub=$_files[$a];
									if($filename){
										$filenameSub = substr($_files[$a],$filename+7);
									}								
								$_attachmentString .="<tr class='attach ".$thread_detail[$i]->message_id."'><td><a data-href='".$_files[$a]."' href='javascript://' onclick='window.parent.open_drive_files(\"".$_files[$a]."\")' target='_BLANK'><i class='glyph-icon icon-file' data-mime='' style='color:#2196f3'></i> ".$filenameSub."</a></td></tr>";
								}
							}
						}
					}
					
					$_messagesString .='<div class="row">'.
											'<div class="col-md-12 col-sm-12 col-xs-12">'.
											'    <div class="p-20">';
					if($i>0){
						$_messagesString .='<h3 class="message-title">'.$subject.'</h3>';
					}
					$_messagesString .=		'        <div class="message-item media">'.
											'            <div class="message-item-right">'.
											'                <div class="media">'.
											'                    <div class="media-body">'.
											'                        <p class="c-gray"></p>'.
											'                    </div>'.
											'                </div>'.
											'            </div>'.
											'        </div>'.
											'    </div>'.
											'   <div class="message-body" id="message-body">'.$body.
											'    </div>'.
											'</div>'.
										'</div>';
					
					if($i>0 && $i<count($thread_detail)-1){
						$_messagesString .='<div class="message-between"></div>';
					}
					if($m==count($mainData)-1){
						$stringUL = "<table><tbody>".$_attachmentString."</tbody></table>";
						
					}
				}
			}
		}
	}
?>
<style>body{min-width:0}#page-content{padding:0}.message-result .message-detail-right a{color:#222}.message-result .message-detail-right a:hover{color:#56b2fe}.dropdown-menu li>a,.ui-menu li>a{color:#222;text-decoration:none}.dropdown-menu .row>div{margin-bottom:0!important}</style>
<div data-padding="40" data-height="window" class="panel panel-default panel-no-margin withScroll mCustomScrollbar _mCS_117" id="message-detail" style="height:300px;max-height:300px;border:0;overflow-y:auto">
<div id="mCSB_117" class="mCustomScrollBox mCS-dark-2">
<div class="mCSB_container">
<div class="panel-heading messages message-result">
<h2 class="message-detail-title is-subject p-t-20 w-500 show">
<span class="message-detail-subject">
<?php echo $subject;?>
</span>
</h2>
<div class="row">
<div class="col-xs-6">
<h2 id="messageDetailTitleSubject" class="message-detail-title p-t-20 w-500 show">
<strong><span class="message-detail-from show"><?php echo $from;?></span></strong>
</h2>
<h2 class="message-detail-title p-t-20 w-500 show">
to: <span class="message-detail-to"><?php echo $to?></span>
<div class="message-detail-buttons-left btn-group" role="group">
<div class="btn-group" role="group">
<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
<span class="caret"></span>
</button>
<div class="dropdown-menu" role="menu" style="width:300px">
<div class="row">
<div class="col-xs-3">
<label>from:</label>
</div>
<div class="col-xs-9">
<strong class="message-detail-from"><?php echo $from;?></strong>
</div>
</div>
<div class="row">
<div class="col-xs-3">
<label>to:</label>
</div>
<div class="col-xs-9">
<span class="message-detail-to"><?php echo $to?></span>
</div>
</div>
<div class="row">
<div class="col-xs-3">
<label>cc:</label>
</div>
<div class="col-xs-9">
<span class="message-detail-cc"><?php echo $cc;?></span>
</div>
</div>
<div class="row">
<div class="col-xs-3">
<label>date:</label>
</div>
<div class="col-xs-9">
<span class="message-detail-date"><?php echo $date;?></span>
</div>
</div>
<div class="row">
<div class="col-xs-3">
<label>subject:</label>
</div>
<div class="col-xs-9">
<span class="message-detail-subject"><?php echo $subject;?></span>
</div>
</div>
</div>
</div>
</div>
</h2>
</div>
<div class="col-xs-6 text-right">
<h2 class="message-detail-title p-t-20 w-500 is-date show">
<span class="message-detail-date"><?php echo $date;?></span>
</h2>
<div class="message-detail-right show">
<a href="javascript://" onclick="threadLabelChanged('Starred',jQuery(this))" data-original-title="<?php echo $attr;?>" class="message-detail-star tooltip-button" title="To favorite" data-placement="bottom">
<?php echo $star;?>
</a>
<div class="message-detail-buttons-right btn-group" role="group">
<button type="button" class="btn btn-default tooltip-button" onclick="replyEmail()" title="Reply" data-placement="bottom">
<i class="glyph-icon icon-reply"></i>
</button>
<div class="btn-group" role="group">
<button type="button" class="btn btn-default dropdown-toggle eReply" data-toggle="dropdown" aria-expanded="false">
<span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
<li><a href="javascript://" onclick="openEmailDetails()" class='eReply'> Email Open</a></li>
<li><a href="javascript://" onclick="replyEmailAll()" class='eReply'><i class="glyph-icon icon-reply"></i> Reply All</a></li>
<li><a href="javascript://" onclick="printEmail(jQuery(this))">Print</a></li>
<li><a href="javascript://" onclick="threadLabelChanged('Trash',jQuery(this))">Delete</a></li>
<li><a href="javascript://" onclick="threadLabelChanged('Spam',jQuery(this))">Report spam</a></li>
<li><a href="javascript://" onclick="threadLabelChanged('Unread',jQuery(this))">Mark as unread</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="panel-body messages message-result message_detail">
<div class="loading-spinner" id="loading_spinner" style='display:none'>
<img src="<?php echo $this->config->base_url()?>public/images/ajax-loader.gif" alt="">
</div>
<?php echo $_messagesString.$stringUL;?>
</div>
</div>
</div>
</div>
<script>function extractEmails (text){return text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);}_person_id=0;_company_id=0;<?php if(count($person_email_detail)>0):?>_person_id="<?php echo $person_email_detail->contact_id?>";_company_id="<?php echo $person_email_detail->company_id?>";<?php endif;?>_MessageThreadID="<?php echo $threadID;?>";_MessageID="<?php echo $messageID;?>";_subject="<?php echo $subject;?>";_id="<?php echo $id?>";_userID=<?php echo $userID?>;_flag=0;_type="";_email='<?php echo $from;?>';function printEmail(b){_flag=0;_id="";window.parent.jQuery("div.messages_container").find(".message-item").each(function(){if(jQuery(this).hasClass("message-active")){_flag=1;_id=jQuery(this).attr("data-id")}});if(_flag==0){window.parent.jQuery("#other_list_boxes>table").find("tr").each(function(){jQuery(this).find("td").each(function(){if(jQuery(this).hasClass("active")){_flag=2}})})}if(_flag==0){html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';jQuery("#contentPart").find(".alert").remove();jQuery("#contentPart>.row").before(html);removeAlert()}else{if(_flag==1){window.open("https://mail.google.com/mail/u/0/?ui=2&ik=62926f1489&view=pt&search=inbox&msg="+_id+"&siml="+_id,"_BLANK")}else{if(_flag==2){window.print()}}}}function threadLabelChanged(c,d){_flag=0;_id="";window.parent.jQuery("div.messages_container").find(".message-item").each(function(){if(jQuery(this).hasClass("message-active")){_flag=1;_id=jQuery(this).attr("data-id")}});if(_flag==0){html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';window.patent.jQuery("#contentPart").find(".alert").remove();window.parent.jQuery("#contentPart>.row").before(html);window.parent.removeAlert()}else{_object=window.parent.jQuery(".emails-group-container").find("a.active");_active=_object.html().toUpperCase();jQuery.ajax({url:"<?php echo $this->config->base_url()?>/dashboard/modifyLabel",type:"POST",data:{token:_id,label:c,active:_active},cache:false,success:function(a){if(a=="Starred"){alert("Message moved successfully");window.parent.getEmails("messages_container",_active,_object)}else{alert("Please try after sometime")}}})}}function openEmailDetails(){window.parent.jQuery("#emailOpenContent").html(jQuery("#mCSB_117").html());window.parent.$(".dropdown-toggle").dropdown();window.parent.jQuery("#emailOpenModal").modal("show");window.parent.jQuery("body").removeAttr("onselectstart");window.parent.document.oncontextmenu=new Function("return true")}

function replyEmail() {
    if (window.parent.leadGlobal != 0) {
        window.parent.jQuery("#emailDocUrl").val("");
        window.parent.jQuery("#attach_droppable").empty();
        _flag = 1;
        _type = "message";

        window.parent.jQuery("#eventCid").val(_company_id);
        window.parent.jQuery("#eventPid").val(_person_id);
        window.parent.jQuery("#eventT").val(window.parent.jQuery("#activityMainType").val());

        if (_subject.indexOf("RE:") == -1) {
            _subject = "RE: " + _subject
        }

        window.parent.jQuery("#other_list_boxes>table").find("tr").each(function() {
            jQuery(this).find("td").each(function() {
                if (jQuery(this).hasClass("active")) {
                    if (window.parent._CS != _userID) {
                        _flag = 2;
                        window.parent.jQuery("#emailSubject").val(_subject);
                        window.parent.jQuery("#emailThreadId").val("");
                        window.parent.jQuery("#emailMessageId").val("");
                        if (window.parent.availableTags.length > 0) {
                            $("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');
                            
                            // jQuery("#gmail_message_modal").css("display", "block").addClass("sb-active").animate({
                            //     textIndent: 0
                            // }, {
                            //     step: function(b, a) {
                            //         $(this).css("transform", "translate(350px)")
                            //     },
                            //     duration: "slow"
                            // }, "linear");
							window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

                            window.parent.jQuery("#gmail_message").css("display", "block");
                            window.parent.jQuery(".gmail-modal").css("display", "block");
                            window.parent.jQuery("body").removeAttr("onselectstart");
                            window.parent.document.oncontextmenu = new Function("return true");
                            window.parent.$(".dropdown-toggle").dropdown();
                            if (_email.indexOf("<") >= 0) {
                                _nn = _email.substr(0, _email.indexOf("<"));
                                _ss = _email.substr(_email.indexOf("<"));
                                _newem = _ss.substr(1, _ss.indexOf(">") - 1)
                            } else {
                                _nn = _email;
                                _newem = _email
                            }
                            window.parent.jQuery("#emailName").val(jQuery.trim(_nn));
                            window.parent.jQuery("#emailTo").val(_newem + ", ");
                            window.parent.jQuery("#emailCC").css("width", "725px").val("");
                            window.parent.jQuery("#emailReference").val("<?php echo $_referenceString;?>");
                            window.parent.findDataRemove.push(_newem);
                            window.parent.jQuery("#attach_droppable").empty();
                            window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + window.parent.leadNameGlobal);
                            window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
                            window.parent.jQuery("#emailMessage").focus()
                        } else {
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php echo $this->config->base_url()?>dashboard/search_contact",
                                cache: false,
                                success: function(a) {
                                    window.parent.availableTags = jQuery.parseJSON(a);
                                    window.parent.jQuery("#emailCC").css("width", "725px").val("");
                                    window.parent.$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');

                                    // window.parent.jQuery("#gmail_message_modal").css("display", "block").addClass("sb-active").animate({
                                    //     textIndent: 0
                                    // }, {
                                    //     step: function(f, b) {
                                    //         $(this).css("transform", "translate(350px)")
                                    //     },
                                    //     duration: "slow"
                                    // }, "linear");
									window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

                                    window.parent.jQuery("#gmail_message").css("display", "block");
                                    window.parent.jQuery(".gmail-modal").css("display", "block");
                                    window.parent.jQuery("body").removeAttr("onselectstart");
                                    window.parent.document.oncontextmenu = new Function("return true");
                                    window.parent.$(".dropdown-toggle").dropdown();
                                    if (_email.indexOf("<") >= 0) {
                                        _nn = _email.substr(0, _email.indexOf("<"));
                                        _ss = _email.substr(_email.indexOf("<"));
                                        _newem = _ss.substr(1, _ss.indexOf(">") - 1)
                                    } else {
                                        _nn = _email;
                                        _newem = _email
                                    }
                                    window.parent.jQuery("#emailName").val(jQuery.trim(_nn));
                                    window.parent.jQuery("#emailTo").val(_newem + ", ");
                                    window.parent.jQuery("#emailReference").val("<?php echo $_referenceString;?>");
                                    window.parent.findDataRemove.push(_newem);
                                    window.parent.jQuery("#attach_droppable").empty();
                                    window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + window.parent.leadNameGlobal);
                                    window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
                                    window.parent.jQuery("#emailMessage").focus()
                                }
                            })
                        }
                    }
                }
            })
        });
        if (_flag == 0) {
            html = '<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';
            window.parent.jQuery("#contentPart").find(".alert").remove();
            window.parent.jQuery("#contentPart>.row").before(html);
            window.parent.removeAlert()
        } else {
            if (_flag == 1) {
                window.parent.jQuery("#emailThreadId").val(_MessageThreadID);
                window.parent.jQuery("#emailMessageId").val(_MessageID);
                window.parent.jQuery("#emailReference").val("<?php echo $_referenceString;?>");
                if (_email.indexOf("<") >= 0) {
                    _nn = _email.substr(0, _email.indexOf("<"));
                    _ss = _email.substr(_email.indexOf("<"));
                    _newem = _ss.substr(1, _ss.indexOf(">") - 1)
                } else {
                    _nn = _email;
                    _newem = _email
                }
                if (_newem != window.parent._cmU) {
                    window.parent.jQuery("#emailTo").val(_newem + ", ");
                    window.parent.findDataRemove.push(_newem)
                } else {
                    _allEmails = jQuery(".message-detail-to").eq(0).html();
                    _emailss = _allEmails.split(",");
                    _newem = "";
                    stringMainEmails = [];
                    for (ems = 0; ems < _emailss.length; ems++) {
                        _currentEms = _emailss[ems].substr(_emailss[ems].indexOf("<"));
                        _currentEms = _currentEms.substr(1, _currentEms.length - 1);
                        var c = "></";
                        var d = new RegExp(c, "g");
                        _currentEms = _currentEms.replace(d, "^^^");
                        if (_currentEms != "") {
                            _modifyAllEmails = _currentEms.split("^^^");
                            if (_modifyAllEmails.length > 0) {
                                for (mda = 0; mda < _modifyAllEmails.length; mda++) {
                                    var c = ">";
                                    var d = new RegExp(c, "g");
                                    _newString = _modifyAllEmails[mda].replace(d, "");
                                    if (jQuery.inArray(_newString, stringMainEmails) != -1) {} else {
                                        stringMainEmails.push(_newString);
                                        _newem += _newString + ","
                                    }
                                }
                            }
                        }
                    }
                    if (_newem != "") {
                        window.parent.jQuery("#emailTo").val(_newem);
                        window.parent.findDataRemove.push(_newem)
                    }
                }
                window.parent.jQuery("#emailSubject").val(_subject).attr("readonly", "readonly");
                window.parent.jQuery("#emailName").val(jQuery.trim(_nn));
                if (window.parent.availableTags.length == 0) {
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo $this->config->base_url()?>dashboard/search_contact",
                        cache: false,
                        success: function(a) {
                            window.parent.availableTags = jQuery.parseJSON(a);
                            window.parent.jQuery("#emailCC").css("width", "725px").val("");
                            window.parent.$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');

                            // window.parent.jQuery("#gmail_message_modal").css("display", "block").addClass("sb-active").animate({
                            //     textIndent: 0
                            // }, {
                            //     step: function(f, b) {
                            //         $(this).css("transform", "translate(350px)")
                            //     },
                            //     duration: "slow"
                            // }, "linear");
							window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

                            window.parent.jQuery("#gmail_message").css("display", "block");
                            window.parent.jQuery(".gmail-modal").css("display", "block");
                            window.parent.jQuery("body").removeAttr("onselectstart");
                            window.parent.document.oncontextmenu = new Function("return true");
                            window.parent.$(".dropdown-toggle").dropdown();
                            window.parent.jQuery("#attach_droppable").empty();
                            window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + window.parent.leadNameGlobal);
                            window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
                            window.parent.jQuery("#emailMessage").focus();
                            window.parent.checkBodyScrollable()
                        }
                    })
                } else {
                    window.parent.$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');

                    // window.parent.jQuery("#gmail_message_modal").css("display", "block").addClass("sb-active").animate({
                    //     textIndent: 0
                    // }, {
                    //     step: function(b, a) {
                    //         $(this).css("transform", "translate(350px)")
                    //     },
                    //     duration: "slow"
                    // }, "linear");
					window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

                    window.parent.jQuery("#emailCC").css("width", "725px").val("");
                    window.parent.jQuery("#gmail_message").css("display", "block");
                    window.parent.jQuery(".gmail-modal").css("display", "block");
                    window.parent.jQuery("body").removeAttr("onselectstart");
                    window.parent.document.oncontextmenu = new Function("return true");
                    window.parent.$(".dropdown-toggle").dropdown();
                    window.parent.jQuery("#attach_droppable").empty();
                    window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + window.parent.leadNameGlobal);
                    window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
                    window.parent.jQuery("#emailMessage").focus();
                    window.parent.checkBodyScrollable()
                }
            }
        }
    } else {
        alert("Replying is available after associating the message with existing Lead.")
    }
}

function replyEmailAll(){
		if(window.parent.leadGlobal!=0) {
			window.parent.jQuery("#eventCid").val(_company_id);
			window.parent.jQuery("#eventPid").val(_person_id);
			window.parent.jQuery("#eventT").val(window.parent.jQuery("#activityMainType").val());
			window.parent.jQuery("#emailDocUrl").val("");
			window.parent.jQuery("#attach_droppable").empty();

			_cc="";

			if(window.parent.availableTags.length==0) {
				jQuery.ajax({
					type:"POST",
					url:"<?php echo $this->config->base_url()?>dashboard/search_contact",
					cache:false,
					success:function(a){window.parent.availableTags=jQuery.parseJSON(a)}}
				)}

				_flag=1;
				_type="message";

				if(_subject.indexOf("RE:")==-1) {
					_subject="RE: "+_subject
				}

				if(window.parent._CS!=_userID) {
					_flag=2;
					window.parent.jQuery("#emailSubject").val(_subject);
					window.parent.jQuery("#emailThreadId").val("");
					window.parent.jQuery("#emailMessageId").val("");

					// window.parent.jQuery("#gmail_message_modal").css("display","block").addClass("sb-active").animate(
					// 	{
					// 		textIndent: 0
					// 	},
					// 	{
					// 		step:function(b,a){$(this).css("transform","translate(350px)")
					// 	},
					// 	duration:"slow"
					// },"linear");
						// console.log(1);
					window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

					window.parent.jQuery("#gmail_message").css("display","block");
					window.parent.jQuery(".gmail-modal").css("display","block");
					window.parent.jQuery("body").removeAttr("onselectstart");

					document.oncontextmenu=new Function("return true");
					window.parent.$(".dropdown-toggle").dropdown();

					if(_email.indexOf("<")>=0){
						_nn=_email.substr(0,_email.indexOf("<"));
						_ss=_email.substr(_email.indexOf("<"));
						_newem=_ss.substr(1,_ss.indexOf(">")-1)
					}
					else {
						_nn=_email;_newem=_email
					}

					_cc=jQuery(".message-detail-cc").eq(0).html();
					_newccEm="";

					if(jQuery.trim(_cc)!="") {
						_nn=_cc.substr(0,_cc.indexOf("<"));
						_ss=_cc.substr(_cc.indexOf("<"));
						_newccEm=_ss.substr(1,_ss.indexOf(">")-1)
					}

					window.parent.jQuery("#emailReference").val("<?php echo $_referenceString;?>");
					window.parent.findDataRemove.push(_newem);
					window.parent.jQuery("#emailName").val(jQuery.trim(_nn));

					if(_newem!="") {
						window.parent.findDataRemove.push(_newem);
						window.parent.jQuery("#emailTo").val(_newem+", ")
					}

					if(_newccEm!="") {
						window.parent.findDataCCRemove.push(_ccEmailput);
						window.parent.jQuery("#emailCC").val(_newccEm+", ")
					}

					window.parent.jQuery("#attach_droppable").empty();
					window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: "+window.parent.leadNameGlobal);
					window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
					window.parent.jQuery("#emailMessage").focus()
				}

				if(_flag==0) {
					html='<div class="alert alert-success"><a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a><p>Please select message for reply</p></div>';

					window.parent.jQuery("#contentPart").find(".alert").remove();
					window.parent.jQuery("#contentPart>.row").before(html);
					window.parent.removeAlert()
				}
				else {
					if(_flag==1) {
						if(window.parent.availableTags.length==0) {
							jQuery.ajax({
								type:"POST",
								url:"<?php echo $this->config->base_url()?>dashboard/search_contact",
								cache:false,
								success:function(a) {
									window.parent.availableTags=jQuery.parseJSON(a)
								}
							})
						}

						window.parent.jQuery("#emailThreadId").val(_MessageThreadID);
						window.parent.jQuery("#emailMessageId").val(_MessageID);

						_allEmails=jQuery(".message-detail-to").eq(0).html();
						_emailss=_allEmails.split(",");
						_newem="";
						stringMainEmails=[];
						_cc=jQuery(".message-detail-cc").eq(0).html();

						for(ems=0;ems<_emailss.length;ems++) {
							_currentEms=_emailss[ems].substr(_emailss[ems].indexOf("<"));
							_currentEms=_currentEms.substr(1,_currentEms.length-1);

							var c="></";
							var d=new RegExp(c,"g");

							_currentEms=_currentEms.replace(d,"^^^");

							if(_currentEms!="") {
								_modifyAllEmails=_currentEms.split("^^^");

								if(_modifyAllEmails.length>0) {
									for(mda=0;mda<_modifyAllEmails.length;mda++) {
										var c=">";
										var d=new RegExp(c,"g");
										_newString=_modifyAllEmails[mda].replace(d,"");

										if(jQuery.inArray(_newString,stringMainEmails)!=-1) {

										}
										else {
											if(_newString!=window.parent._cmU) {
												stringMainEmails.push(_newString);
												_newem+=_newString+","
											}
										}
									}
								}
							}
						}

						_ccEmailput="";

						if(_cc!="") {
							_ccEmailss=_cc.split(",");
							stringMainCCEmails=[];

							for(ems=0;ems<_ccEmailss.length;ems++) {
								_currentEms=_ccEmailss[ems].substr(_ccEmailss[ems].indexOf("<"));
								_currentEms=_currentEms.substr(1,_currentEms.length-1);

								var c="></";
								var d=new RegExp(c,"g");

								_currentEms=_currentEms.replace(d,"^^^");

								if(_currentEms!=""){
									_modifyAllEmails=_currentEms.split("^^^");

									if(_modifyAllEmails.length>0) {
										for(mda=0;mda<_modifyAllEmails.length;mda++) {
											var c=">";
											var d=new RegExp(c,"g");

											_newString=_modifyAllEmails[mda].replace(d,"");

											if(jQuery.inArray(_newString,stringMainCCEmails)!=-1) {

											}
											else {
												stringMainCCEmails.push(_newString)
												;_ccEmailput+=_newString+","
											}
										}
									}
								}
							}
						}

						_nn=_email.substr(0,_email.indexOf("<"));
						_ss=_email.substr(_email.indexOf("<"));
						_fromem=_ss.substr(1,_ss.indexOf(">")-1);

						if(_fromem!=window.parent._cmU) {
							_newem+=_fromem+","
						}

						if(_newem!="") {
							window.parent.jQuery("#emailTo").val(_newem);
							window.parent.findDataRemove.push(_newem)
						}

						if(_ccEmailput!="") {
							window.parent.findDataCCRemove.push(_ccEmailput);
							window.parent.jQuery("#emailCC").val(_ccEmailput)
						}

						window.parent.jQuery("#emailSubject").val(_subject).attr("readonly","readonly");
						window.parent.jQuery("#emailName").val("<?php echo $this->session->userdata['name']?>");

						// window.parent.jQuery("#gmail_message_modal").css("display","block").addClass("sb-active").animate(
						// 	{
						// 		textIndent:0
						// 	},
						// 	{
						// 		step:function(b,a){$(this).css("transform","translate(350px)")
						// 	},
						// 	duration:"slow"
						// },"linear");
						// console.log(2);
						window.parent.openSlidebar(window.parent.jQuery("#gmail_message_modal"));

						window.parent.jQuery("#gmail_message").css("display","block");
						window.parent.jQuery(".gmail-modal").css("display","block");
						window.parent.jQuery("body").removeAttr("onselectstart");
						document.oncontextmenu=new Function("return true");
						window.parent.$(".dropdown-toggle").dropdown();
						window.parent.jQuery("#gmail_message_modal").find("h4").html("Reply Message: "+window.parent.leadNameGlobal);
						window.parent.jQuery("#messageLeadId").val(window.parent.leadGlobal);
						window.parent.jQuery("#emailReference").val("<?php echo $_referenceString;?>");
						window.parent.jQuery("#emailMessage").focus();
						window.parent.checkBodyScrollable()
					}
				}
			}
			else {
				alert("Replying is available after associating the message with existing Lead.")
			}
		}

		jQuery(document).ready(function(){
			jQuery(".dropdown-toggle").dropdown()
		});
	</script>