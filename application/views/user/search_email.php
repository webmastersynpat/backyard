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
		for($i=0;$i<count($thread_detail);$i++){
			$headers = $thread_detail[$i]['header'];
			$labelIds = $thread_detail[$i]['labelIds'];										
			for($j=0;$j<count($headers);$j++){												
					if($headers[$j]->name=='Subject'){
					$subject = $headers[$j]->value;
					}
					if($headers[$j]->name=='From'){
						$from = $headers[$j]->value;
					}
					if($headers[$j]->name=='To'){
						$to = $headers[$j]->value;
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
	
?>

<style>
    body {
        min-width: 0;
    }

    #page-content {
        padding: 0;
    }

	/*.message-detail-buttons-right > .btn { float: left; }*/
	/*.message-detail-buttons-right > .btn-group { float: right; margin-left: -1px; }*/

	.message-result .message-detail-right a {
		color: #222;
	}
	.message-result .message-detail-right a:hover{
		color: #56b2fe;
	}
	.dropdown-menu li>a, .ui-menu li>a {
		color: #222;
		text-decoration: none;
	}
	.dropdown-menu .row > div { margin-bottom: 0 !important; }
</style> 
<div data-padding="40" data-height="window" class="panel panel-default panel-no-margin withScroll mCustomScrollbar _mCS_117" id="message-detail" style="height:300px; max-height:300px;border:0;overflow-y:auto;"> 
    <!-- <div style="overflow-x:hidden !important;overflow-y:scroll !important; min-height:300px;" id="mCSB_117" class="mCustomScrollBox mCS-dark-2">  -->
    <div id="mCSB_117" class="mCustomScrollBox mCS-dark-2"> 
        <div class="mCSB_container"> 
            <div class="panel-heading messages message-result">
                 <h2 class="message-detail-title is-subject p-t-20 w-500 show">
                      <span class="message-detail-subject">
                          <?php echo $subject;?>
                      </span> 
                 </h2> 
                <!-- <div class="row row-width">  -->
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
                    <!-- <div class="col-width text-right" style="width:120px"> -->
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
                                        <!--<li><a href="javascript://" onclick="replyEmailAll()" class='eReply'><i class="glyph-icon icon-reply"></i> Reply All</a></li>
                                        <li><a href="javascript://" onclick="printEmail(jQuery(this))">Print</a></li>
                                        <li><a href="javascript://" onclick="threadLabelChanged('Trash',jQuery(this))">Delete</a></li>
                                        <li><a href="javascript://" onclick="threadLabelChanged('Spam',jQuery(this))">Report spam</a></li>
                                        <li><a href="javascript://" onclick="threadLabelChanged('Unread',jQuery(this))">Mark as unread</a></li>-->
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
<script>function openEmailDetails() { window.parent.jQuery("#emailOpenContent").html(jQuery("#mCSB_117").html()); window.parent.$(".dropdown-toggle").dropdown(); window.parent.jQuery("#emailOpenModal").modal("show"); window.parent.jQuery("body").removeAttr("onselectstart"); window.parent.document.oncontextmenu = new Function("return true") }jQuery(document).ready(function(){$(".dropdown-toggle").dropdown();});</script>