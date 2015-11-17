<?php echo doctype('html5'); ?>
<html  lang="en">
<head>
<?php echo meta($meta); ?>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php echo $title_for_layout; ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
        /* Loading Spinner */
        .spinner{margin:0;width:70px;height:18px;margin:-35px 0 0 -9px;position:absolute;top:50%;left:50%;text-align:center}.spinner > div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}
    </style>
<!-- Favicons -->

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $Layout->baseUrl; ?>public/images/icons/favicon.ico">


<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style.css">

<script>
	var __baseUrl = '<?php echo $Layout->baseUrl; ?>',
	snapGlobal="",
    leadGlobal =  0, 
	snp = 0,
    leadNameGlobal =  "",
	mainIndex = -1,
	totalCC = 0,
	systemLoginSession = 0,
	chDa = 1;
</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/javascript_lib_level1.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/script.js"></script>
<script>
	
	function nl2br (str, is_xhtml) {
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
		return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	}
	function retrieveMyEmail(){
        jQuery("#myEmailsRetrieve").html(
        	"<p class='login-text pad10T' style='text-align:center;'>" + 
        		'<div class="loading-spinner">' +
					'<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">' +
				'</div>' +
        	"</p>");
		jQuery.ajax({			
			url:'<?php echo $Layout->baseUrl?>dashboard/retreiveDashboardEmails',
			cache:false,
			success:function(data){
				jQuery("#onFly").remove();
				jQuery("#myEmailsRetrieve").html(data);					
				if(jQuery("#myEmailsRetrieve").find('#messages-list').length==0){
					<?php $openEmailBox = true;
					if((int)$this->session->userdata['type']!=9){
						if(!in_array(11,$this->session->userdata['modules_assign'])){
							$openEmailBox = false;
						}
					}
						if($openEmailBox===true):
					?>
					jQuery("#myEmailsRetrieve").html("<div class='col-xs-12'><a class='btn btn-default' href='javascript://' onclick='retrieveMyEmail();'>Retry</a></div>");
					<?php endif;?>
				} else {
					<?php 
						if($this->session->userdata['initialise_email']=='0'):
					?>
						runRetrieveNew();
					<?php endif;?>
					setInterval(runRetrieveNew,300000);
				}			
				totalCC = jQuery("#myEmailsRetrieve").find('table').find('tbody').find('tr.mainLead').length;			
				jQuery('.pager-text').html("0/"+ totalCC);
                windowResize();
				
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ){
				if(XMLHttpRequest.status=="504")
				retrieveMyEmail();
				}
		}).done(function(){
			_initLead = '<?php echo $this->uri->segment(3)?>';
			if(parseInt(_initLead)>0){
				activateLead(parseInt(_initLead));
			}
		});
	}

	
    /** Body Scrollable */
    function checkBodyScrollable() {       
    }

	
    jQuery(document).ready(function(){
    	jQuery("#activity-btn").click(function(e){
    		e.preventDefault();
    		jQuery("#activity").toggle();          
    		jQuery("#contentPart").removeClass('col-md-8').removeClass('col-sm-8').removeClass('col-xs-8');
    		jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').removeClass('col-xs-12');
    		jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    		if(jQuery("#task_list").css('display')=="block" || jQuery("#task_list").css('display')==='undefined'){
    			if(jQuery("#activity").css('display')=="none"){
    			     jQuery.cookie('ryt_sidebar_hide','true');
                    jQuery.cookie('ryt_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			} else {
    			 jQuery.cookie('ryt_sidebar_hide','');
                    jQuery.cookie('ryt_sidebar_show','true');
    			    jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    				jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
    			}
    		} else {          
    			if(jQuery("#activity").css('display')=="none"){	
    			 jQuery.cookie('ryt_sidebar_hide','true');
                    jQuery.cookie('ryt_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-12').addClass('col-sm-12').addClass('col-xs-12');
    			} else {
    		      jQuery.cookie('ryt_sidebar_hide','');
                    jQuery.cookie('ryt_sidebar_show','true');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			}
    		}		
    	});
    	jQuery("#task-btn").click(function(e){
    		e.preventDefault();
			jQuery("#my_c_task_list").css('display','none');
    		jQuery("#task_list").toggle();
    		jQuery("#contentPart").removeClass('col-md-8').removeClass('col-sm-8').removeClass('col-xs-8');
    		jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').removeClass('col-xs-12');
    		jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    		if(jQuery("#activity").css('display')=="block" || jQuery("#activity").css('display')==='undefined'){
    			if(jQuery("#task_list").css('display')=="none"){
    			 jQuery.cookie('left_sidebar_hide','true');
                    jQuery.cookie('left_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			} else {    			 
                    jQuery.cookie('left_sidebar_hide','');
                    jQuery.cookie('left_sidebar_show','true');
    				jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    				jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
    			}
    		} else {
    			if(jQuery("#task_list").css('display')=="none"){
    			 jQuery.cookie('left_sidebar_hide','true');
                    jQuery.cookie('left_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-12').addClass('col-sm-12').addClass('col-xs-12');
    			} else {
    			 jQuery.cookie('left_sidebar_hide','');
                    jQuery.cookie('left_sidebar_show','true');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			}
    		}
    	});
		
		jQuery("#task-i-btn").click(function(e){
    		e.preventDefault();
			jQuery("#task_list").css('display','none');
    		jQuery("#my_c_task_list").toggle();
    		jQuery("#contentPart").removeClass('col-md-8').removeClass('col-sm-8').removeClass('col-xs-8');
    		jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').removeClass('col-xs-12');
    		jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    		if(jQuery("#activity").css('display')=="block" || jQuery("#activity").css('display')==='undefined'){
    			if(jQuery("#my_c_task_list").css('display')=="none"){
    			 jQuery.cookie('left_sidebar_hide','true');
                    jQuery.cookie('left_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			} else {
    			 
                    jQuery.cookie('left_sidebar_hide','');
                    jQuery.cookie('left_sidebar_show','true');
    				jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    				jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
    			}
    		} else {
    			if(jQuery("#my_c_task_list").css('display')=="none"){
    			 jQuery.cookie('left_sidebar_hide','true');
                    jQuery.cookie('left_sidebar_show','');
    				jQuery("#contentPart").addClass('col-md-12').addClass('col-sm-12').addClass('col-xs-12');
    			} else {
    			 jQuery.cookie('left_sidebar_hide','');
                    jQuery.cookie('left_sidebar_show','true');
    				jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
    			}
    		}			
    	});


        /** Dashboard charts */        
        jQuery('#btnCharts').click(function(e) {
            if(!jQuery("#dashboard_charts").is(':visible')) {
                jQuery("#dashboard_charts").slideDown(function() {
                    jQuery.cookie('show_dashboard_charts', true);
                    windowResize();
                    checkBodyScrollable();
                });
            }
            else {
                jQuery("#dashboard_charts").slideUp(function() {
                    jQuery.cookie('show_dashboard_charts', '');
                    windowResize();
                    checkBodyScrollable();
                });
            }

            return false;
        });


        /** Default email */
        jQuery('#btnEmail').click(function(e) {
            if(!jQuery("#myEmailsRetrieve").is(':visible')) {				
				jQuery("#myEmailsRetrieve").slideDown(function() {
                    jQuery.cookie('show_default_email', true);
                    windowResize();
                    jQuery('#all_type_list_wrapper .sorting:first').trigger('click');
                    setTimeout(function() {
                        jQuery('#all_type_list_wrapper .sorting:first').trigger('click');
                    }, 300);
                    checkBodyScrollable();
                });
				if(jQuery("#myEmailsRetrieve").find('div').length==0){					
					retrieveMyEmail();
				}
            }
            else {
                jQuery("#myEmailsRetrieve").slideUp(function() {
                    jQuery.cookie('show_default_email', '');
                    windowResize();
                });
            }

            return false;
        });

		
        /* right sidebar cookie */
        if(jQuery.cookie('ryt_sidebar_hide') == 'true')
        {
             jQuery("div#activity.col-md-2.col-sm-2.col-xs-2").css('display','none');
            jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
            jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').removeClass('col-xs-12');
            jQuery("#contentPart").removeClass('col-md-8').removeClass('col-sm-8').removeClass('col-xs-8');
            jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
        }
        if(jQuery.cookie('ryt_sidebar_show') == 'true')
        {
            jQuery("div#activity.col-md-2.col-sm-2.col-xs-2").css('display','block');
            jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
    		jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
        }
        /* left sidebar cookie */
        if(jQuery.cookie('left_sidebar_hide') == 'true')
        {
            jQuery('#task_list').css('display','none');
            jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
        }
        if(jQuery.cookie('left_sidebar_show') == 'true')
        {
            jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
            jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').removeClass('col-xs-12');
            jQuery("#contentPart").removeClass('col-md-8').removeClass('col-sm-8').removeClass('col-xs-8');
    		jQuery("#contentPart").addClass('col-md-10').addClass('col-sm-10').addClass('col-xs-10');
        }
        if(jQuery.cookie('left_sidebar_hide') == 'true' && jQuery.cookie('ryt_sidebar_hide') == 'true')
        {
            jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
            jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
    		jQuery("#contentPart").addClass('col-md-12').addClass('col-sm-12').addClass('col-xs-12');
        }
        if(jQuery.cookie('left_sidebar_show') == 'true' && jQuery.cookie('ryt_sidebar_show') == 'true')
        {
            jQuery('#task_list').css('display','block');
            jQuery("#contentPart").removeClass('col-md-10').removeClass('col-sm-10').removeClass('col-xs-10');
            jQuery("#contentPart").removeClass('col-md-12').removeClass('col-sm-12').addClass('col-xs-12');
            jQuery("#contentPart").addClass('col-md-8').addClass('col-sm-8').addClass('col-xs-8');
    		
        }
        jQuery('[data-toggle=dropdown]').dropdown()
    });
	
	function intialiseAfter(){
		jQuery.ajax({
			url:'<?php echo $Layout->baseUrl?>dashboard/intialiseAfter',
			type:'POST',
			data:{inta:1},
			cache:false,
			success:function(data){
			}
		});
	}
	
	
	function sendCurrentOldLeadLL(label){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $this->config->base_url();?>users/getCurrentOldEmails',
			data:{stype:label},
			cache:false,
			success:function(res){
				if(res!=""){
					_dataEmail = jQuery.parseJSON(res);
					if(_dataEmail.emails.length>0){
						___HTML = "";
						for(i=0;i<_dataEmail.emails.length;i++){
							_mainFlag = 0;
							_innerFlag = 1;
							jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").each(function(){
								if(jQuery(this).attr("data-id")==_dataEmail.emails[i].message_id){
									_innerFlag=0;
								}
							});	
							
							if(_innerFlag==1){
								
									/*Box List*/
									if(_dataEmail.boxList.length>0){										
									}
									
									/*Pass Lead*/
									if(_mainFlag==0){										
									}
									
									/*Start Flag*/
									
									if(_mainFlag==0){
										___from ="";													
										___subject="";													
										___date = "";	
										___messageIDDD ="";
										for(h=0;h<_dataEmail.emails[i].header.length;h++){
											____header = _dataEmail.emails[i].header[h];
											if(____header.name=="From"){	
												___from = ____header.value;
											}
											if(____header.name=="Subject"){
												___subject = ____header.value;	
											}
											if(____header.name=="Date"){
												___date = moment(new Date(____header.value)).format('MMM D, YYYY');
											}
											if(____header.name=="Message-ID"){
												___messageIDDD = ____header.value;
											}
										}
										___attachments = 0;
										if(_dataEmail.emails[i].parts.length>0){
											if(_dataEmail.emails[i].parts[0].mimeType=="multipart/alternative" || _dataEmail.emails[i].parts[0].mimeType=="multipart/related"){
												for(p=1;p<_dataEmail.emails[i].parts.length;p++){
													if(_dataEmail.emails[i].parts[p].filename!=""){
														___attachments++;
													}
												}
											}
										}	
										/*HTML*/
									___HTML+='<div class="message-item media draggable" data-id="'+_dataEmail.emails[i].message_id+'" data-message-id="'+___messageIDDD+'"><div class="message-item-right"><div class="media"><div class="media-body" onclick="findThread(\''+_dataEmail.emails[i].message_id+'\',jQuery(this));"><h5 class="c-dark">';
									___FF = 0; 
									if(_dataEmail.emails[i].labelIds.length>0){
										for(l=0;l<_dataEmail.emails[i].labelIds.length;l++){
											if(_dataEmail.emails[i].labelIds[l]=="UNREAD"){
												___FF = 1;
											}
										}
									}
									if(___FF==1){
										___HTML +='<strong><a class="c-dark" href="javascript:void(0)">'+___from+'</a></strong>';
									} else {
										___HTML +='<a class="c-dark" style="font-weight:normal" href="javascript:void(0)">'+___from+'</a>';
									}
									___HTML +='</h5><h4 class="c-dark">'+___subject+'</h4><div>'+___date+'&nbsp;';
									if(___attachments>0){
										___HTML +='<strong><i class="glyph-icon icon-paperclip"></i>'+___attachments+'</strong>';
									}
									___HTML +='</div></div></div></div></div>';
										/*END HTML*/
										
									}
									/*End Flag*/
								
							}
						}
						if(___HTML!=""){
							if(jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").length>0	){
								jQuery("#mCSB_116").find("div.messages_container").find("div.message-item").eq(0).before(___HTML);
							} else {
								jQuery("#mCSB_116").find("div.messages_container").append(___HTML)
							}							
							initDragDrop();
						}
					}
				}
			}
		});
	}
	_globalAjax = "";
	function runRetrieveNew(){ 
		_globalAjax = jQuery.ajax({
			type:'POST',
			url:'<?php echo $this->config->base_url()?>users/getEmails',
			data:{type:'INBOX'},
			cache:false,
			success:function(data){
				if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Inbox"){
					sendCurrentOldLeadLL('INBOX');
				}
			},
			error:function(xhr,st,me){
				if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Inbox"){
					sendCurrentOldLeadLL('INBOX');
				}
			}
		}).fail(function(){
			_globalAjax = jQuery.ajax({
				type:'POST',
				url:'<?php echo $this->config->base_url()?>users/notificationEmail',
				data:{type:'INBOX'},
				cache:false,
				success:function(){
					
				}
			});
		}).done(function(){
			_globalAjax = jQuery.ajax({
				type:'POST',
				url:'<?php echo $this->config->base_url()?>users/getEmails',
				data:{type:'STARRED'},
				cache:false,
				success:function(data){
					if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Starred"){
						sendCurrentOldLeadLL('STARRED');
					}					
				}
			}).fail(function(){
				_globalAjax = jQuery.ajax({
					type:'POST',
					url:'<?php echo $this->config->base_url()?>users/notificationEmail',
					data:{type:'STARRED'},
					cache:false,
					success:function(){
						
					}
				});
			}).done(function(){
				_globalAjax = jQuery.ajax({
					type:'POST',
					url:'<?php echo $this->config->base_url()?>users/getEmails',
					data:{type:'DRAFT'},
					cache:false,
					success:function(data){
						if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Draft"){
							sendCurrentOldLeadLL('DRAFT');
						}							
					}
				}).fail(function(){
					_globalAjax = jQuery.ajax({
						type:'POST',
						url:'<?php echo $this->config->base_url()?>users/notificationEmail',
						data:{type:'DRAFT'},
						cache:false,
						success:function(){
							
						}
					});
				}).done(function(){
					_globalAjax = jQuery.ajax({
						type:'POST',
						url:'<?php echo $this->config->base_url()?>users/getEmails',
						data:{type:'SENT'},
						cache:false,
						success:function(data){
							if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Sent"){
								sendCurrentOldLeadLL('SENT');
							}							
						}
					}).fail(function(){
						_globalAjax = jQuery.ajax({
							type:'POST',
							url:'<?php echo $this->config->base_url()?>users/notificationEmail',
							data:{type:'SENT'},
							cache:false,
							success:function(){
								
							}
						});
					}).done(function(){
						_globalAjax = jQuery.ajax({
							type:'POST',
							url:'<?php echo $this->config->base_url()?>users/getEmails',
							data:{type:'TRASH'},
							cache:false,
							success:function(data){
								if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Trash"){	
									sendCurrentOldLeadLL('TRASH');
								}														
							}
						}).fail(function(){
							_globalAjax = jQuery.ajax({
								type:'POST',
								url:'<?php echo $this->config->base_url()?>users/notificationEmail',
								data:{type:'TRASH'},
								cache:false,
								success:function(){
									
								}
							});
						}).done(function(){
							_globalAjax = jQuery.ajax({
							type:'POST',
							url:'<?php echo $this->config->base_url()?>users/getEmails',
							data:{type:'LEAD'},
							cache:false,
							success:function(data){
								if(jQuery(".emails-group-container").find('a.active').length>0 && jQuery.trim(jQuery(".emails-group-container").find('a.active').html())=="Leads"){
									sendCurrentOldLeadLL('LEAD');
								}														
							}
						}).fail(function(){
							_globalAjax = jQuery.ajax({
								type:'POST',
								url:'<?php echo $this->config->base_url()?>users/notificationEmail',
								data:{type:'LEAD'},
								cache:false,
								success:function(){
									
								}
							});
						});
					});
				});
			});
		});
	});
	}	
	
	window.onbeforeunload = function(e) {  
		if(typeof _globalAjax=="object"){
			_globalAjax.abort();
			return null;
		} else {
			return null;
		}	   
	};
</script>
</head>

<input type="hidden" name="status" value=""/>
    <body class='closed-sidebar'  onselectstart="return false">
    <div id="sb-site">
    <div class="sb-slidebar bg-black sb-left sb-style-overlay">
    <div class="scrollable-content scrollable-slim-sidebar">
        <div class="pad10A">
            
        </div>
    </div>
	</div>

<div class="sb-slidebar bg-black sb-right sb-style-overlay">
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-1" class="popover-title">
    
</a>
<div id="sidebar-toggle-1" class="collapse in">
    
</div>

<div class="clear"></div>

<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-6" class="popover-title">
   
</a>
<div id="sidebar-toggle-6" class="collapse in">

    <ul class="files-box">
           
    </ul>

</div>

<div class="clear"></div>

<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-3" class="popover-title">
   
</a>
<div id="sidebar-toggle-3" class="collapse in">
    <ul class="progress-box">
           
    </ul>

</div>

<div class="clear"></div>

<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-4" class="popover-title">
    
</a>
<div id="sidebar-toggle-4" class="collapse in">
    <ul class="notifications-box notifications-box-alt">
            
    </ul>
</div>
</div>
</div>
</div>


<div id="scrapGoogleData"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:1020px;margin-top:80px;'>
<a href='javascript:void(0)' class="btn" onclick="closeSlideBarGoogle();"><i class="glyph-icon icon-close"></i></a>
<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this));" style='float:right'><i class="glyph-icon icon-arrows-h "></i></a>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-1" class="popover-title" id="scrapSlidebarTitle">
    
    <span class="caret"></span>
</a>
<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style='display:none;'>
		<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
	</div>
<div id="scrapGooglePatent"></div>
</div>
</div>
</div>


<div id="scrapLucidData"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:1020px;margin-top:80px;'>
	<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLucid();"><i class="glyph-icon icon-close"></i></a>
	<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this));" style='float:right'><i class="glyph-icon icon-arrows-h "></i></a>
	<div class="scrollable-content scrollable-slim-sidebar">
		<div class="pad15A">
			<a href="#" title="" data-toggle="collapse" data-target="#sidebar-toggle-1" class="popover-title" id="scrapLucidDataTitle">
				<span class="caret"></span>
			</a>
			<div class="loading-spinner" id="loading_spinner_heading_lucid_scrap" style='display:none;'>
				<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
			</div>
			<div id="scrapLucid"></div>
		</div>
	</div>
</div>


<div id="excelData"  class="sb-slidebar bg-white sb-left sb-style-overlay" style='width:1020px;margin-top:80px;'>
<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftGoogle();" style='float:right'><i class="glyph-icon icon-close"></i></a>
<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this));" style='float:right'><i class="glyph-icon icon-arrows-h "></i></a>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div class="loading-spinner" id="loading_spinner_heading_excel_scrap" style='display:none;'>
		<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
	</div>
<div id="excelSheet"></div>
</div>
</div>
</div>
<div id="open_files_gd"  class="sb-slidebar bg-white sb-left sb-style-overlay" style='width:1020px;margin-top:80px;'>
<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftDrive();" style='float:right'><i class="glyph-icon icon-close"></i></a>
<a href='javascript:void(0)' class="btn nm" onclick="changeDriveMode(jQuery(this));" style='float:right'><i class="glyph-icon icon-arrows-h "></i></a>
<div class="scrollable-content scrollable-slim-sidebar">
<div class="pad15A">
<div class="loading-spinner" id="loading_spinner_heading_excel_scrap" style='display:none;'>
		<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
	</div>
<div id="open_drive_files"></div>
</div>
</div>
</div>
<div id="open_contact_gd"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:46%;margin-top:80px;'>
	<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftContact();" style='float:right'><i class="glyph-icon icon-close"></i></a>
	<div class="scrollable-content scrollable-slim-sidebar">
		<div class="pad15A">
			<div id="open_contact_list"></div>
		</div>
	</div>
</div>
<div id="open_sales_gd"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:46%;margin-top:80px;'>
	<a href='javascript:void(0)' class="btn" onclick="closeSlideBarLeftSales();" style='float:right'><i class="glyph-icon icon-close"></i></a>
	<div class="scrollable-content scrollable-slim-sidebar">
		<div class="pad15A">
			<div id="open_sales_list"></div>
		</div>
	</div>
</div>
<div id="open_all_list"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:40%;margin-top:80px;'>
	<a href='javascript:void(0)' class="btn" onclick="closeSlideBarRightList();" style='float:right'><i class="glyph-icon icon-close"></i></a>
	
		<div class="pad15A">
			<div id="open_list"></div>
		</div>
	
</div>

<div id="open_invitation"  class="sb-slidebar bg-white sb-right sb-style-overlay" style='width:46%;margin-top:80px;'>
	<a href='javascript:void(0)' class="btn" onclick="close_all_invitation();" style='float:right'><i class="glyph-icon icon-close"></i></a>
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
				        <input type="text" name='event[end_time]' id="eventEndTime" required class="form-control time-calendar  input-string" placeholder="To time">
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
						<textarea cols="29" rows="4" name="event[description]" class="form-control" style="height:218px !important;" tabindex="4" autocomplete=""></textarea>
					</div>
				</div>
				<div class="clear"></div>
				<div class="mrg10T" id="attendeesPlaceholder">
					<label>Add guests</label>
                  	<div class="col-xs-12">
                  		<input type="text" name="email[]" class="form-control input-string" placeholder="Enter guest email adresses">
                  	</div>
					<div class="clear"></div>
					<div class="clearfix mrg10T">
						<button type="button" onclick="addAttendees();" class="btn btn-primary btn-mwidth pull-right">Add</button>
					</div>
				</div>
				<div class="clear"></div>
				<div class="mrg10T">
					<button type="button" onclick="insertEvent()" id="btnEvent" class="btn btn-primary btn-mwidth pull-right">Save</button>
					<a href="javascript://" class="mrg10R pull-right" onclick="close_all_invitation();" style="color: #222222;font-size: 20px;margin-top: 2px;"><i class="glyph-icon"><img src="<?php echo $Layout->baseUrl;?>public/images/discard.png" style="opacity:0.55"></i></a>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function() { "use strict";
	$('.date_calendar').datepicker({
		format: 'yyyy-mm-dd'
	});
	$('.time-calendar').timepicker();
});
</script>
<div class="modal modal-opened-header fade" id="timmerPopup"  role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:40%;z-index:999999">
	<div class="modal-dialog" style=''> 
		<div class="modal-content">					
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -9px; margin-right: -5px;"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p>The timer stopped counting after 20 minutes of idle screen. Click any button if you wish to continue the timer's operation. If you like to adjust the timeline, please <a onclick='jQuery("#timmerPopup").modal("hide");getTimeLIne();' href="javascript://" style='color:56b2fe !important;'>click here</a>.</p>
			</div>
            <div class="modal-footer"></div>
		</div>
	</div>
</div>

<div id="loading">
    <div class="loading-spinner is-window">
    	<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
	</div>
</div>

<div id="page-wrapper">
	<!-- Top Bar Start -->
	<?php echo $Layout->element('topbar'); ?>
	<!-- Top Bar End -->
	<!-- Left Sidebar Start -->
	<?php echo $Layout->element('left_sidebar'); ?>
	<!-- Left Sidebar End -->
	<div id="page-content-wrapper">
        <div id="page-content">
            <div id="default_email" class="" style="display: none;">
                <div class="panel">
                    <div class="panel-body">
                        <form class="form-horizontal form-flat" style="margin: 0 auto; width: 549px;">
                            <div class="clearfix">
                                <label class="control-label" style="float:left; padding-top:0;">To:</label>
                                <input class="form-control input-string" style="float: left; width: 530px; margin-top: -3px;" />
                            </div>
                            <div class="clearfix">
                                <label class="control-label" style="float:left;">Subject:</label>
                                <input class="form-control input-string" style="float: left; width: 500px; margin-top: 4px;" />
                            </div>
                            <div class="clearfix">
                                <label class="control-label" style="float:left;">Attachment:</label>
                                <input type="file" style="float: left; margin-left: 6px; margin-top: 6px;" />
                            </div>
                            <div class="clearfix mrg5T">
                                <label>Message:</label>
                                <textarea class="form-control input-string" rows="5" style="width:549px;"></textarea>
                            </div>
                            <div class="clearfix mrg5T" style="width:549px;">
                                <button class="btn btn-primary pull-right" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>				  
			<?php echo $contents_for_layout; ?>
		</div>
	</div>
</div>
<!-- WIDGETS -->

</div>
</body>
</html>
