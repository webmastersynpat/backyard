<?php echo doctype('html5'); ?>
<html lang="en">
<head>
<?php echo meta($meta); ?>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php echo $title_for_layout; ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>.spinner{margin:0;width:70px;height:18px;margin:-35px 0 0 -9px;position:absolute;top:50%;left:50%;text-align:center}.spinner>div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}</style>
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $Layout->baseUrl; ?>public/images/icons/favicon.ico">
 <link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style_new_without_compress.css">
 <style>
	.modal {
		height: 1px;
	}

	.ui-datepicker {
		min-width: 220px;
	}
	.ui-datepicker-today a {
		outline: 1px solid #1E88E5;
	}

	.modal,
	.modal-dialog {
		transition: none !important;
	}


	#timmerPopup {

	}
	#timmerPopup:after {
		background-color: rgba(0,0,0,0.5);
		content: ' ';
		height: 100%;
		left: 0;
		position: fixed;
		top: 0;
		width: 100%;
	}

	/** Left messages column for 14inch display */
	@media (max-width: 1600px) {
		.col-width.list-messages {
			width: 600px !important;
		}
	}


	/** Slidebar */
	.sb-slidebar {
		display: block !important;
		margin-left: 0 !important;
		margin-right: 0 !important;
		margin-top: 79px;
		text-indent: 0 !important;
		transform: none !important;
		-moz-transform: none !important;
		-webkit-transform: none !important;
		width: 1000px;
	}
	.sb-slidebar.sb-left {
		left: -1000px;
		right: auto;
	}
	.sb-slidebar.sb-right {
		left: auto !important;
		right: -1000px;
	}
	.sb-slidebar-top-buttons {
		float: right;
		margin-right: 10px;
		text-align: center;
		width: 40px;
	}
	.sb-slidebar-top-buttons a {
		display: inline-block;
		float: none !important;
	}

	.sb-slidebar .ui-resizable-handle.ui-resizable-e,
	.sb-slidebar .ui-resizable-handle.ui-resizable-w {
		background: #dfe8f1;
	    cursor: e-resize;
	    display: none !important;
	    height: 100%;
	    opacity: 0.5;
	    position: absolute;
	    top: 0;
	    width: 10px;
	}
	.sb-slidebar .ui-resizable-handle.ui-resizable-e:before,
	.sb-slidebar .ui-resizable-handle.ui-resizable-e:after,
	.sb-slidebar .ui-resizable-handle.ui-resizable-w:before,
	.sb-slidebar .ui-resizable-handle.ui-resizable-w:after {
		background-color: #000;
		content: ' ';
		height: 20px;
		margin-top: -50px;
		position: absolute;
		top: 50%;
		width: 1px;
	}
	.sb-slidebar .ui-resizable-handle.ui-resizable-e:before,
	.sb-slidebar .ui-resizable-handle.ui-resizable-w:before {
		left: 3px;
	}
	.sb-slidebar .ui-resizable-handle.ui-resizable-e:after,
	.sb-slidebar .ui-resizable-handle.ui-resizable-w:after {
		right: 3px;
	}
	.sb-slidebar.sb-left .ui-resizable-handle.ui-resizable-e:hover,
	.sb-slidebar.sb-left .ui-resizable-handle.ui-resizable-w:hover {
		opacity: 1;
	}

	.sb-slidebar .ui-resizable-handle.ui-resizable-e {
		left: auto;
		right: 0;
	}
	.sb-slidebar .ui-resizable-handle.ui-resizable-w {
		left: 0;
		right: auto;
	}
	.sb-slidebar.sb-left .ui-resizable-handle.ui-resizable-e {
		display: block !important;
	}
	.sb-slidebar.sb-right .ui-resizable-handle.ui-resizable-w {
		display: block !important;
	}

	.sb-slidebar.ui-resizable-resizing:after {
		content: '1';
	    height: 100%;
	    left: 0;
	    position: absolute;
	    top: 0;
	    width: 100%;
	    z-index: 1;
	}


	/** Table sort activity */
	#sortingActivity_wrapper .dataTables_scrollBody {
		height: auto !important;
		max-height: 400px;
	}
	#sortingActivity_filter label {
		display: block;
		position: relative;
	}
	#sortingActivity_filter label:after {
		content: '\f002';
		font-family: FontAwesome;
	    font-weight: normal;
	    font-style: normal;
		top: 5px;
		position: absolute;
		right: 5px;
	}
	#sortingActivity_filter input {
		box-shadow: none !important;
    	height: 26px;
    	margin-bottom: 3px;
    	padding-right: 21px;
	}
 </style>

<script>var __baseUrl="<?php echo $Layout->baseUrl; ?>",snapGlobal="",leadGlobal=0,snp=0,leadNameGlobal="",mainIndex=-1,totalCC=0,systemLoginSession=0,chDa=1;</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/assets.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/script.js?v=34878"></script>
<!--script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/script_uncompress.js"></script-->
<script>function nl2br(c,b){var a=(b||typeof b==="undefined")?"<br />":"<br>";return(c+"").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,"$1"+a+"$2")}function retrieveMyEmail(){jQuery("#myEmailsRetrieve").html('<p class=\'login-text pad10T\' style=\'text-align:center;\'><div class="loading-spinner"><img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt=""></div></p>');jQuery.ajax({url:"<?php echo $Layout->baseUrl?>dashboard/retreiveDashboardEmails",cache:false,success:function(d){jQuery("#onFly").remove();jQuery("#myEmailsRetrieve").html(d);if(jQuery("#myEmailsRetrieve").find("#messages-list").length==0){<?php $openEmailBox = true;
					if((int)$this->session->userdata['type']!=9){
						if(!in_array(11,$this->session->userdata['modules_assign'])){
							$openEmailBox = false;
						}
					}
						if($openEmailBox===true):
					?>jQuery("#myEmailsRetrieve").html("<div class='col-xs-12'><a class='btn btn-default' href='javascript://' onclick='retrieveMyEmail();'>Retry</a></div>");<?php endif;?>}else{<?php 
						if($this->session->userdata['initialise_email']=='0'):
					?>runRetrieveNew();<?php endif;?>setInterval(runRetrieveNew,300000)}totalCC=jQuery("#myEmailsRetrieve").find("table").find("tbody").find("tr.mainLead").length;jQuery(".pager-text").html("0/"+totalCC);windowResize()},error:function(a,c,b){if(a.status=="504"){retrieveMyEmail()}}}).done(function(){_initLead="<?php echo $this->uri->segment(3)?>";if(parseInt(_initLead)>0){activateLead(parseInt(_initLead))}})}function checkBodyScrollable(){}jQuery(document).ready(function(){jQuery("#activity-btn").click(function(a){a.preventDefault();jQuery("#activity").toggle();jQuery("#contentPart").removeClass("col-md-8").removeClass("col-sm-8").removeClass("col-xs-8");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").removeClass("col-xs-12");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");if(jQuery("#task_list").css("display")=="block"||jQuery("#task_list").css("display")==="undefined"){if(jQuery("#activity").css("display")=="none"){jQuery.cookie("ryt_sidebar_hide","true");jQuery.cookie("ryt_sidebar_show","");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}else{jQuery.cookie("ryt_sidebar_hide","");jQuery.cookie("ryt_sidebar_show","true");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8")}}else{if(jQuery("#activity").css("display")=="none"){jQuery.cookie("ryt_sidebar_hide","true");jQuery.cookie("ryt_sidebar_show","");jQuery("#contentPart").addClass("col-md-12").addClass("col-sm-12").addClass("col-xs-12")}else{jQuery.cookie("ryt_sidebar_hide","");jQuery.cookie("ryt_sidebar_show","true");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}}});jQuery("#task-btn").click(function(a){a.preventDefault();jQuery("#my_c_task_list").css("display","none");jQuery("#task_list").toggle();jQuery("#contentPart").removeClass("col-md-8").removeClass("col-sm-8").removeClass("col-xs-8");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").removeClass("col-xs-12");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");if(jQuery("#activity").css("display")=="block"||jQuery("#activity").css("display")==="undefined"){if(jQuery("#task_list").css("display")=="none"){jQuery.cookie("left_sidebar_hide","true");jQuery.cookie("left_sidebar_show","");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}else{jQuery.cookie("left_sidebar_hide","");jQuery.cookie("left_sidebar_show","true");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8")}}else{if(jQuery("#task_list").css("display")=="none"){jQuery.cookie("left_sidebar_hide","true");jQuery.cookie("left_sidebar_show","");jQuery("#contentPart").addClass("col-md-12").addClass("col-sm-12").addClass("col-xs-12")}else{jQuery.cookie("left_sidebar_hide","");jQuery.cookie("left_sidebar_show","true");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}}});
					jQuery("#task-i-btn").click(function(a){a.preventDefault();jQuery("#task_list").css("display","none");jQuery("#my_c_task_list").toggle();jQuery("#contentPart").removeClass("col-md-8").removeClass("col-sm-8").removeClass("col-xs-8");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").removeClass("col-xs-12");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");if(jQuery("#activity").css("display")=="block"||jQuery("#activity").css("display")==="undefined"){if(jQuery("#my_c_task_list").css("display")=="none"){jQuery.cookie("left_sidebar_hide","true");jQuery.cookie("left_sidebar_show","");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}else{jQuery.cookie("left_sidebar_hide","");jQuery.cookie("left_sidebar_show","true");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8")}}else{if(jQuery("#my_c_task_list").css("display")=="none"){jQuery.cookie("left_sidebar_hide","true");jQuery.cookie("left_sidebar_show","");jQuery("#contentPart").addClass("col-md-12").addClass("col-sm-12").addClass("col-xs-12")}else{jQuery.cookie("left_sidebar_hide","");jQuery.cookie("left_sidebar_show","true");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}}});_formOpen='';jQuery("#btnFormCollapse").click(function(a){if(_formOpen==''){if(jQuery('#from_regular').is(':visible')){_formOpen='from_regular';jQuery('#from_regular').slideUp();} else if(jQuery('#from_litigation').is(':visible')){_formOpen='from_litigation';jQuery('#from_litigation').slideUp();} else if(jQuery('#from_nonacquistion').is(':visible')){_formOpen='from_nonacquistion';jQuery('#from_nonacquistion').slideUp();}} else {jQuery('#'+_formOpen).slideDown();_formOpen='';}});
					jQuery("#btnEmail").click(function(a){if(!jQuery("#myEmailsRetrieve").is(":visible")){jQuery("#myEmailsRetrieve").slideDown(function(){jQuery.cookie("show_default_email",true);windowResize();jQuery("#all_type_list_wrapper .sorting:first").trigger("click");setTimeout(function(){jQuery("#all_type_list_wrapper .sorting:first").trigger("click")},300);checkBodyScrollable()});if(jQuery("#myEmailsRetrieve").find("div").length==0){retrieveMyEmail()}}else{jQuery("#myEmailsRetrieve").slideUp(function(){jQuery.cookie("show_default_email","");windowResize()})}return false});if(jQuery.cookie("ryt_sidebar_hide")=="true"){jQuery("div#activity.col-md-2.col-sm-2.col-xs-2").css("display","none");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").removeClass("col-xs-12");jQuery("#contentPart").removeClass("col-md-8").removeClass("col-sm-8").removeClass("col-xs-8");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}if(jQuery.cookie("ryt_sidebar_show")=="true"){jQuery("div#activity.col-md-2.col-sm-2.col-xs-2").css("display","block");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8")}if(jQuery.cookie("left_sidebar_hide")=="true"){jQuery("#task_list").css("display","none");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}if(jQuery.cookie("left_sidebar_show")=="true"){jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").removeClass("col-xs-12");jQuery("#contentPart").removeClass("col-md-8").removeClass("col-sm-8").removeClass("col-xs-8");jQuery("#contentPart").addClass("col-md-10").addClass("col-sm-10").addClass("col-xs-10")}if(jQuery.cookie("left_sidebar_hide")=="true"&&jQuery.cookie("ryt_sidebar_hide")=="true"){jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8");jQuery("#contentPart").addClass("col-md-12").addClass("col-sm-12").addClass("col-xs-12")}if(jQuery.cookie("left_sidebar_show")=="true"&&jQuery.cookie("ryt_sidebar_show")=="true"){jQuery("#task_list").css("display","block");jQuery("#contentPart").removeClass("col-md-10").removeClass("col-sm-10").removeClass("col-xs-10");jQuery("#contentPart").removeClass("col-md-12").removeClass("col-sm-12").addClass("col-xs-12");jQuery("#contentPart").addClass("col-md-8").addClass("col-sm-8").addClass("col-xs-8")}jQuery("[data-toggle=dropdown]").dropdown()});function intialiseAfter(){jQuery.ajax({url:__baseUrl+"dashboard/intialiseAfter",type:"POST",data:{inta:1},cache:false,success:function(a){}})}function sendCurrentOldLeadLL(a){_globalAjax= jQuery.ajax({type:"POST",url:__baseUrl+"users/getCurrentOldEmails",data:{stype:a},cache:false,success:function(b){if(b!=""){_dataEmail=jQuery.parseJSON(b);if(_dataEmail.emails.length>0){___HTML="";jQuery('#loading_spinner_heading_messages').css('display','none');for(i=0;i<_dataEmail.emails.length;i++){_mainFlag=0;_innerFlag=1;jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").each(function(){if(jQuery(this).attr("data-id")==_dataEmail.emails[i].message_id){_innerFlag=0}});if(_innerFlag==1){if(_mainFlag==0){___from="";___subject="";___date="";_dtEm='';___messageIDDD="";for(h=0;h<_dataEmail.emails[i].header.length;h++){____header=_dataEmail.emails[i].header[h];if(____header.name=="From"){___from=____header.value}if(____header.name=="Subject"){___subject=____header.value}if(____header.name=="Date"){_dtEm=____header.value;___date=moment(new Date(____header.value)).format("MMM D, YYYY")}if(____header.name=="Message-ID"){___messageIDDD=____header.value}}___attachments=0;if(_dataEmail.emails[i].parts.length>0){if(_dataEmail.emails[i].parts[0].mimeType=="multipart/alternative"||_dataEmail.emails[i].parts[0].mimeType=="multipart/related"){for(p=1;p<_dataEmail.emails[i].parts.length;p++){if(_dataEmail.emails[i].parts[p].filename!=""){___attachments++}}}}_eBT=1;if(jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").length>0){dt = new Date(jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").eq(0).attr('data-date'));if(new Date(_dtEm)>dt){_eBT=0;}}else{_eBT=0;} if(_eBT==0){___HTML+='<div class="message-item media draggable" data-id="'+_dataEmail.emails[i].message_id+'" data-message-id="'+___messageIDDD+'" data-date="'+_dtEm+'"><div class="message-item-right"><div class="media"><div class="media-body" onclick="findThread(\''+_dataEmail.emails[i].message_id+'\',jQuery(this));"><h5 class="c-dark">';___FF=0;if(_dataEmail.emails[i].labelIds.length>0){for(l=0;l<_dataEmail.emails[i].labelIds.length;l++){if(_dataEmail.emails[i].labelIds[l]=="UNREAD"){___FF=1}}}if(___FF==1){___HTML+='<strong><a class="c-dark" href="javascript:void(0)">'+___from+"</a></strong>"}else{___HTML+='<a class="c-dark" style="font-weight:normal" href="javascript:void(0)">'+___from+"</a>"}___HTML+='</h5><h4 class="c-dark">'+___subject+"</h4><div><span class='message-item-date'>"+___date+"</span>&nbsp;";if(___attachments>0){___HTML+='<strong><i class="glyph-icon icon-paperclip"></i>'+___attachments+"</strong>"}___HTML+="</div></div></div></div></div>"}}}}if(___HTML!=""){if(jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").length>0){jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").eq(0).before(___HTML)}else{jQuery("#mCSB_116").find("div.messages_container").append(___HTML)}initDragDrop()}}}}})}_globalAjax="";window.onbeforeunload=function(a){if(typeof typeof _globalAjax=='object'){_globalAjax.abort();return null}else{return null}}; function checkPageRefresh(){_cWI = window.location.href;if(_cWI.indexOf('dashboard')>=0){leadGlobal=0;leadNameGlobal='';__dashFlag=true;jQuery('#all_type_list').find('tbody>tr').removeClass('active');jQuery('.DTFC_Cloned').find('tbody>tr').removeClass('active');jQuery("#mCSB_116").find('div').removeClass('message-active');jQuery("#other_list_boxes").empty();jQuery("#displayEmail").empty();jQuery("#sales_acititity").removeClass("show").addClass("hide");jQuery(".openPatentDetail").removeClass('show').addClass('hide');jQuery('#from_regular').css('display','none');jQuery('#from_litigation').css('display','none');jQuery('#from_nonacquistion').css('display','none');jQuery('#search_form').get(0).reset();jQuery('#search_results').empty();jQuery('#search_lead_box').css('display','block');callnotification();} else{window.location = __baseUrl}}_allEmails = ['INBOX','STARRED','DRAFT','SENT','TRASH','LEAD'];_allLists = ['Inbox','Starred','Draft','Sent','Trash','Leads'];function runRetrieveNew(){callAjax(0)};function callAjax(t){_globalAjax=jQuery.ajax({type:"POST",url:__baseUrl+"users/getEmails",data:{type:_allEmails[t]},cache:false,success:function(a){if(t==0){if(jQuery(".emails-group-container").find("a.active").length>0&&jQuery.trim(jQuery(".emails-group-container").find("a.active").html())==_allLists[t]){sendCurrentOldLeadLL(_allEmails[0]);} else if(jQuery(".emails-group-container").find("a.active").length>0&&jQuery.trim(jQuery(".emails-group-container").find("a.active").html())=="Retreive"){sendCurrentOldLeadLL(_allEmails[0]);}}},error:function(c,a,b){if(t==0){sendCurrentOldLeadLL(_allEmails[0]);}}}).fail(function(){_globalAjax=jQuery.ajax({type:"POST",url:__baseUrl+"users/notificationEmail",data:{type:"INBOX"},cache:false,success:function(){}});}).done(function(){m = t+ 1;if(m<_allLists.length){callAjax(m);}});
} </script>
</head>
<input type="hidden" name="status" value=""/>
<body class='closed-sidebar'>
<div id="sb-site">
<div class="sb-slidebar bg-black sb-left sb-style-overlay">
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad10A">
</div>
</div>
</div>
<div id="scrapGoogleData" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarGoogle()"><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this))" style='float:right'><i class="glyph-icon icon-arrows-h"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-1" class="popover-title" id="scrapSlidebarTitle">
<span class="caret"></span>
</a>
<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style='display:none'>
<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
</div>
<div id="scrapGooglePatent"></div>
</div>
</div>
</div>
<div id="scrapLucidData" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLucid()"><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this))" style='float:right'><i class="glyph-icon icon-arrows-h"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-1" class="popover-title" id="scrapLucidDataTitle">
<span class="caret"></span>
</a>
<div class="loading-spinner" id="loading_spinner_heading_lucid_scrap" style='display:none'>
<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
</div>
<div id="scrapLucid"></div>
</div>
</div>
</div>
<div id="excelData" class="sb-slidebar bg-white sb-left sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftGoogle()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this))" style='float:right'><i class="glyph-icon icon-arrows-h"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div class="loading-spinner" id="loading_spinner_heading_excel_scrap" style='display:none'>
<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
</div>
<div id="excelSheet"></div>
</div>
</div>
</div>
<div id="open_files_gd" class="sb-slidebar bg-white sb-left sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftDrive()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this))" style='float:right'><i class="glyph-icon icon-arrows-h"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div class="loading-spinner" id="loading_spinner_heading_excel_scrap" style='display:none'>
<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
</div>
<div id="open_drive_files"></div>
</div>
</div>
</div>
<div id="open_contact_gd" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftContact()"><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))"><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div id="open_contact_list"></div>
</div>
</div>
</div>
<div id="advanced_search" class="sb-slidebar bg-white sb-left sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeAdvancedSearch()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
	<div class="scrollable-content scrollable-slim-sidebar">
		<div class="pad15A">
			<h3>Gmail search</h3>
			<style>
				#advanced_search_form {
					overflow-x: hidden;
					overflow-y: auto;
				}
				#advanced_search_form .panel-group {
					margin-right: 10px;
				}
				#advanced_search_form .panel-collapse .panel-body {
					max-height: 150px;
					overflow-y: auto;
				}
			</style>
			<form id="advanced_search_form" class="form-horizontal form-flat mrg10T">
			  	<div class="row">
			    	<div class="col-xs-4">
						<div class="form-group" style="margin-right: 10px;">
							<label class="control-label">Search:</label>
			  				<select class="form-control mrg5T" name="search">
			    				<option value="in:anywhere">All Mail</option>
			    				<option value="in:inbox">Inbox</option>
			    				<option value="in:trash">Trash</option>
			    				<option value="in:spam">Spam</option>
			    				<option value="in:sent">Sent Mail</option>
			  				</select>
			  			</div>
				      	<div class="form-group input-string-group">
							<label class="control-label">From:</label>
							<input type="text" name='search_from' id='search_from' class="form-control">
						</div>
						<div class="form-group input-string-group">
							<label class="control-label">To:</label>
							<input type="text" name='search_to' id='search_to' class="form-control">
						</div>
						<div class="form-group input-string-group">
							<label class="control-label">Subject:</label>
							<input type="text" name='search_subject' class="form-control">
						</div>
						<div class="form-group input-string-group">
							<label class="control-label">Has the words:</label>
							<input type="text" name='has' class="form-control">
						</div>
						<div class="form-group input-string-group">
							<label class="control-label">Doesn't have:</label>
							<input type="text" name='doesnt_have' class="form-control">
						</div>					
					  	<div class="mrg10T" style="margin-right:10px;">
					  		<div class="row">
					  			<div class="col-xs-6">
					  				<button type="button" onclick='findEmailsFromSearchCriteria()' class="btn btn-primary btn-block">Search</button>
					  			</div>
					  			<div class="col-xs-6">
					  				<button type="button" onclick="refreshContacts()" class="btn btn-default btn-block">Refresh Contacts</button>
					  			</div>
					  		</div>
					  	</div>
			    	</div>
			    	<div class="col-xs-8">
			      		<div class="panel-group">
  						  	<div class="panel panel-default">
  						    	<div class="panel-heading" role="tab" id="emailListSearch">
  						   		</div>
							</div>
						</div>
			    	</div>
			    </div>
			</form>
		</div>
	</div>
</div>
<div id="open_ccompany_gd" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftCCompany()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div id="open_ccompany_list"></div>
</div>
</div>
</div>
<div id="open_prefined_message" class="sb-slidebar bg-white sb-right sb-style-overlay" style="width: 1350px;right:-1350px;">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftMessagePredfined()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div id="open_prefined_message_list"></div>
</div>
</div>
</div>
<div id="open_sales_gd" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftSales()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div id="open_sales_list"></div>
</div>
</div>
</div>
<div id="open_manage_sector" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftSector()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div id="open_manage_sector_list"></div>
</div>
</div>
</div>
<div id="open_all_list" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="closeSlideBarRightList()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A"> 
<div id="open_list"></div>
</div>
</div>
</div>
<div id="open_template_editor" class="sb-slidebar bg-white sb-left sb-style-overlay">
 <div class="modal-header" style='height:65px;'>
<button type="button" class="close" onclick="closeTemplateEditor()"><span aria-hidden="true">&times;</span></button>
<button type="button" onclick="updateTemplate();" class="btn btn-primary btn-mwidth pull-right mrg10R">Save</button>
<button type="button" onclick='saveTemplate()' class="btn btn-primary btn-mwidth pull-right mrg10R">Save a new template</button>
<!--<button type="button" class="btn btn-primary btn-mwidth pull-right mrg10R" onclick="sendEmailImap();">Send</button></div>-->
<button type="button" class="btn btn-primary btn-mwidth pull-left mrg10R" onclick="saveToFileFolder();">Add this template to Lead</button>
<div class="form-group nomr" style='float:left'>
	<label for="template_file_name" class="control-label mrg5T" style='float:left;'>As a:</label>
	<select name="template_type" id="template_type" class="form-control input-string" style='float:left;width:160px;'>
		<option value="0">Html Message</option>
		<option value="1">Text Message</option>   
	</select>
</div>

</div>
<div class="scrollable-content scrollable-slim-sidebar">
	<form class="form-horizontal form-flat">
		<div class="pad15A">
			<div class="form-group input-string-group nomr mrg10B">
				<label for="template_file_name" class="control-label">Template file name:</label>
				<input type="text" name="template_file_name" id="template_file_name" class="form-control input-string"/>
			</div>
			<div class="form-group input-string-group nomr mrg10B">
				<label for="template_file_name" class="control-label">Subject:</label>
				<input type="text" name="template_subject" id="template_subject" class="form-control input-string"/>
			</div>			
			<textarea name='templateEditor' id='templateEditor' class='wysiwyg-editor'></textarea>
			<input type='hidden' name='template_id' id='template_id' value='0'/>
		</div>
	</form>
</div>
</div>
<div id="open_invitation" class="sb-slidebar bg-white sb-right sb-style-overlay">
	<div class="sb-slidebar-top-buttons">
		<a href='javascript:void(0)' class="btn" onclick="close_all_invitation()" style='float:right'><i class="glyph-icon icon-close"></i></a>
		<a href='javascript:void(0)' class="btn nm" onclick="changeSlidebarSide(jQuery(this))" style='float:right'><i class="glyph-icon icon-exchange"></i></a>
	</div>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<form class="form-horizontal form-flat" id="frm_calendar_event">
<div class="row">
<div class="col-xs-12">
<input type="text" name="event[summary]" id="eventSummary" required class="form-control input-string" placeholder="Untitled event">
</div>
</div>
<div class="row mrg10T">
<div class="col-xs-5">
<div class="row">
<div class="col-xs-6">
<input type="text" name="event[start_date]" id="eventStartDate" required class="form-control date_calendar input-string" placeholder="From date">
</div>
<div class="col-xs-6">
<input type="text" name="event[start_time]" id="eventStartTime" required class="form-control time-calendar input-string" placeholder="From time">
</div>
</div>
</div>
<div class="col-xs-2 text-center">
<label>to</label>
</div>
<div class="col-xs-5">
<div class="row">
<div class="col-xs-6">
<input type="text" name='event[end_date]' id="eventEndDate" required class="form-control date_calendar input-string" placeholder="To date">
</div>
<div class="col-xs-6">
<input type="text" name='event[end_time]' id="eventEndTime" required class="form-control time-calendar input-string" placeholder="To time">
</div>
</div>
</div>
</div>
<div class="col-xs-12">
<div class="form-group input-string-group nomr mrg5T">
<label for="eventLocation" class="control-label" style="margin-left:0">Where:</label>
<input type="text" name="event[location]" value="" id="eventLocation" placeholder="" class="form-control input-string">
</div>
<div class="clear"></div>
<div class="mrg10T">
<label>Description</label>
<textarea cols="29" rows="4" name="event[description]" class="form-control" style="height:218px!important" tabindex="4" autocomplete=""></textarea>
</div>
</div>
<div class="clear"></div>
<div class="mrg10T" id="attendeesPlaceholder">
<label>Add guests</label>
<div class="col-xs-12">
<input type="text" name="email" id="attendeeEmail" class="form-control input-string" placeholder="Enter guest email adresses">
</div>
<div class="clear"></div>
<!--<div class="clearfix mrg10T">
<button type="button" onclick="addAttendees()" class="btn btn-primary btn-mwidth pull-right">Add</button>
</div>-->
</div>
<div class="clear"></div>
<div class="mrg10T">
<input type="hidden" name="lead_id" id="lead_id" value="0"/>
<button type="button" onclick="insertEvent()" id="btnEvent" class="btn btn-primary btn-mwidth pull-right">Send</button>
<button type="button" onclick="refreshContacts()" class="btn btn-primary btn-mwidth pull-right mrg20R">Refresh Contacts</button>
<a href="javascript://" class="mrg10R pull-right" onclick="close_all_invitation()" style="color:#222;font-size:20px;margin-top:2px"><i class="glyph-icon"><img src="<?php echo $Layout->baseUrl;?>public/images/discard.png" style="opacity:0.55"></i></a>
</div>
<div class="clear"></div>
<div class='mrg10T'>
	<iframe id='eventPredefinedMessage' width="100%" height="350px"></iframe>
</div>
</form>
</div>
</div>
</div>
<script>$(function(){$(".date_calendar").datepicker({format:"yyyy-mm-dd"});$(".time-calendar").timepicker()});</script>
<div class="modal modal-opened-header fade" id="timmerPopup" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:40%;z-index:999999">
<div class="modal-dialog" style=''>
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-9px;margin-right:-5px"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<p>The timer stopped counting after 20 minutes of idle screen. Click any button if you wish to continue the timer's operation. If you like to adjust the timeline, please <a onclick='jQuery("#timmerPopup").modal("hide");getTimeLIne()' href="javascript://" style='color:56b2fe!important'>click here</a>.</p>
</div>
<div class="modal-footer"></div>
</div>
</div>
</div>
<div class="modal modal-opened-header fade" id="moveEmailPopup" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:40%;z-index:999999"><div class="modal-dialog" style=''><div class="modal-content"><div class="modal-header"></div><div class="modal-body"><p>Please wait......</p></div><div class="modal-footer"></div></div></div></div>
<div class="modal modal-opened-header fade" id="sortingPopup" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="left:50%; margin-left:-300px; margin-top:150px !important; width:600px;z-index:999999"><div class="modal-dialog" style=''><div class="modal-content" style='border:1px solid #d9534f'><div class="modal-header"><button type="button" class="close" style='margin-top:-12px' onclick="jQuery('#sortingPopup').find('.modal-body').html('');jQuery('#sortingPopup').modal('hide')"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"></div></div></div></div>
<div id="loading">
<div class="loading-spinner is-window">
<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
</div>
</div>
<div id="page-wrapper">
<?php echo $Layout->element('topbar'); ?>
<?php echo $Layout->element('left_sidebar'); ?>
<div id="page-content-wrapper">
<div id="page-content">
<?php echo $contents_for_layout; ?>
</div>
</div>
</div>
</div>
<input type="hidden" id="user_mobile_number" value="<?php echo $this->session->userdata['phone_number']?>"/>
</body>
</html>