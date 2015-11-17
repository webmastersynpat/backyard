<?php 
	$getUserList = getAllUsersIncAdmin();
?>
<style>
.modal{
	/*overflow: hidden;
	resize: auto;
	bottom: auto;   
	right: auto;*/
	right:0px !important;
	left:none; 
}
.modal.ui-resizable {
	margin: 0; 
}
.modal-dialog{
	margin-right: 0;
	margin-left: 0;
}
.modal-content.ui-resizable {  
	overflow: auto;
}
.ui-draggable-handle {
	cursor: move;
}
#replyTaskModalLabel a{
}
	color:#79b9c7;text-decoration:underline;
.ui-resizable-handle.ui-icon {
	background: url(<?php echo $Layout->baseUrl;?>public/images/resize.png) no-repeat;
	z-index: 9999;
}
.ui-resizable-handle.ui-resizable-e {
	right: 0;
}
.ui-resizable-handle.ui-resizable-s {
	bottom: 0;
}

/** Add contact form */
#addContactForm {
	z-index: 10000 !important;
}

/** Gmail Modal */
.gmail-modal {
  /*	bottom: 10px;*/
  	/*display: none;*/
  	
    /*height: 530px;*/
    position: fixed;
    left: 0px;
    /*width: 50%;*/
    width: 100%;
    z-index: 1050;
}
.gmail-modal .modal-body {
	/*height: 315px;*/
  	overflow: auto;
}

.gmail-modal .attachment-list-item {
	float: none;
  	padding: 4px 27px;
  	position: relative;
}
.gmail-modal .attachment-list-item img {
	left: 5px;
  	margin: 0;
  	position: absolute;
  	top: 5px;
}
.gmail-modal .attachment-list-item > a {
    display: block;
	float: none;
    padding: 0;
}
.gmail-modal .attachment-list-item span {
	position: absolute;
    right: 1px;
    top: 0px;
}
.ui-autocomplete{
	z-index:9999 !important
}
.tl-content, .todo-content, .todo-box{
	font-weight:normal !important;
}


/** Task detail */
.comment_content {
	padding: 6px 25px 7px 10px !important;
}
#ticket__body {
	display: block !important;
	max-height: 56px;
	min-height: 20px;
	overflow: visible;
}
#ticket__body.is-open {
	max-height: 10000px;
}
#ticket__body p {
	margin: 0;
}
#ticket__body_button {
	cursor: pointer !important;
	position: absolute;
  	right: 0;
  	width: 19px;
  	height: 100%;
  	border-left: 1px solid #e6e6e6;
  	background: #f2f2f2;
    background: -moz-linear-gradient(top, #f2f2f2 0%, #e6e6e6 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f2f2f2), color-stop(100%,#e6e6e6));
    background: -webkit-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    background: -o-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    background: -ms-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f2f2', endColorstr='#e6e6e6',GradientType=0 );
    background: linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    font-size: 20px;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
}
#ticket__body_button span {
	background: url(/public/images/plus_11x11_bw.gif) no-repeat left top;
    height: 11px;
    margin: -5px 0 0 -5px;
    left: 50%;
	position: absolute;
    top: 50%;
    width: 11px;
}
#ticket__body_button.is-open span {
	background: url(/public/images/minus_11x3_bw.gif) no-repeat left top;
    height: 3px;
    margin: -2px 0 0 -5px;
}


/** Message inbox detail */
.message-inbox-detail {
	max-height: 16px;
	min-height: 16px;
	overflow: visible;
}
.message-inbox-detail.is-open {
	max-height: 10000px;
}
.message-inbox-toggler {
	cursor: pointer !important;
	position: absolute;
  	right: 0;
  	width: 19px;
  	height: 100%;
  	border-left: 1px solid #e6e6e6;
  	background: #f2f2f2;
    background: -moz-linear-gradient(top, #f2f2f2 0%, #e6e6e6 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f2f2f2), color-stop(100%,#e6e6e6));
    background: -webkit-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    background: -o-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    background: -ms-linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f2f2', endColorstr='#e6e6e6',GradientType=0 );
    background: linear-gradient(top, #f2f2f2 0%,#e6e6e6 100%);
    font-size: 20px;
    font-weight: 700; 
    text-decoration: none;
    text-align: center;
}
.message-inbox-toggler span {
	background: url(/public/images/plus_11x11_bw.gif) no-repeat left top;
    height: 11px;
    margin: -5px 0 0 -5px;
    left: 50%;
	position: absolute;
    top: 50%;
    width: 11px;
}
.message-inbox-toggler.is-open span {
	background: url(/public/images/minus_11x3_bw.gif) no-repeat left top;
    height: 3px;
    margin: -2px 0 0 -5px;
}


/** Timeline datatable */
#timelineDataTable_info {display: none !important;}
</style>
<script type="text/javascript">
    /* WYSIWYG editor */
	jQuery(document).ready(function(){
		$(function() { "use strict";
			$('.wysiwyg-editor').summernote({
				height: 350,
				toolbar: [
					['style', ['bold', 'italic', 'underline', 'clear']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['height', ['height']],
				]
			});
		});
	});
</script>
<script>
snapGlobalFileID ="";
jQuery(document).ready(function(){
	$('#ticket__body_button').off('click').on('click', function(e) {
		$(this).toggleClass('is-open');
		$('#ticket__body').toggleClass('is-open');
		return false;
	});
	$(".modal").draggable({
		handle: ".modal-header"
	});
	$(".modal").resizable();
	$('#open_licensees').on('shown.bs.modal', function (e) {
		open_licenseesResize();
	});
	$(window).on('resize', function() {
		var $open_licensees = $('#open_licensees');

		if($open_licensees.length && $open_licensees.is(':visible')) {
			open_licenseesResize();
		}
	});
	function open_licenseesResize() {
		var $iframe = $('#open_licensees').find('.modal-body iframe'),
			height = $(window).height() - 166;
		if($iframe.length) {
			$iframe.height(height);
		}
	}
	$('#open_lucidchart').on('shown.bs.modal', function (e) {
		open_lucidchartResize();
	});
	$(window).on('resize', function() {
		var $open_lucidchart = $('#open_lucidchart');
		if($open_lucidchart.length && $open_lucidchart.is(':visible')) {
			open_lucidchartResize();
		}
	});
	function open_lucidchartResize() {
		var $iframe = $('#open_lucidchart').find('.modal-body iframe'),
			height = $(window).height() - 231;

		if($iframe.length) {
			$iframe.height(height);
		}
	}
	/** Open opportunity */
	$('#open_opportunity').on('shown.bs.modal', function (e) {
		open_opportunityResize();
	});
	$(window).on('resize', function() {
		var $open_opportunity = $('#open_opportunity');
		if($open_opportunity.length && $open_opportunity.is(':visible')) {
			open_opportunityResize();
		}
	});
	function open_opportunityResize() {
		var $iframe = $('#open_opportunity').find('.modal-body iframe'),
			height = $(window).height() - 231;
		if($iframe.length) {
			$iframe.height(height);
		}
	}
	$('#btnComments').on('shown.bs.modal', function (e) {
		btnCommentsResize();
	});
	$(window).on('resize', function() {
		var $btnComments = $('#btnComments');
		if($btnComments.length && $btnComments.is(':visible')) {
			btnCommentsResize();
		}
	});
	function btnCommentsResize() {
		var $modalBody = $('#btnComments').find('.modal-body'),
			$patentees_data = $('#patentees_data'),
			height = $(window).height() - 226;
		if($modalBody.length) {
			$modalBody.height(height);
			$modalBody.css('overflow-y', 'auto');
		}
		if($patentees_data.length) {
			$patentees_data.height(height);
		}
	}
	$('#dashboard_charts').on('shown.bs.modal', function (e) {
		setTimeout(function() {
			dashboard_chartsResize();
		}, 500);
	});
	$(window).on('resize', function() {
		var $dashboard_charts = $('#dashboard_charts');
		if($dashboard_charts.length && $dashboard_charts.is(':visible')) {
			dashboard_chartsResize();
		}
	});
	function dashboard_chartsResize() {
		var $timelineBox = $('#dashboard_charts').find('.timeline-wrapper-inner'),
			$modalContent = $('#dashboard_charts').find('.modal-content'),
			modalContentHeight = $(window).height() - 77,
			timelineBoxHeight = (modalContentHeight - 120) / 2;
		if($timelineBox.length) {
			$timelineBox.height(timelineBoxHeight);
		}
		if($modalContent.length) {
			$modalContent.height(modalContentHeight);
		}
		timelineDataTable.columns.adjust();
		$('#timelineDataTable_wrapper .dataTables_scrollBody').height(timelineBoxHeight - 40);
	}
	$('#open_calendar').on('shown.bs.modal', function (e) {
		open_calendarResize();
	});
	$(window).on('resize', function() {
		var $open_calendar = $('#open_calendar');
		if($open_calendar.length && $open_calendar.is(':visible')) {
			open_calendarResize();
		}
	});
	function open_calendarResize() {
		var $iframe = $('#open_calendar').find('.modal-body iframe'),
			height = $(window).height() - 113;
		if($iframe.length) {
			$iframe.height(height);
		}
	}
	$('#replyTaskModal').on('shown.bs.modal', function (e) {
		replyTaskModalResize();
	});
	$(window).on('resize', function() {
		var $replyTaskModal = $('#replyTaskModal');
		if($replyTaskModal.length && $replyTaskModal.is(':visible')) {
			replyTaskModalResize();
		}
	});
	function replyTaskModalResize() {
		var $body = $('#replyTaskModal').find('.modal-body'),
			height = $(window).height() - 161;
		if($body.length) {
			$body.css('overflow', 'auto').height(height);
		}
	}
});
function nl2br (str, is_xhtml) {
  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
  return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
var availableTags = [];
  $(function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
	$("#emailSubject,#emailMessage").focus(function(){
		if(findDataCCRemove.length==0){
			$( "#emailCC" ).val('');
		}
		if(findDataRemove.length==0){
			$( "#emailTo" ).val('');
		}
		if(findDataBCCRemove.length==0){
			$( "#emailBCC" ).val('');
		}
	});
	findDataRemove = [];
	findDataCCRemove = [];
	findDataBCCRemove = [];
    $( "#emailTo" ).blur(function(){
		if(findDataRemove.length==0){
			$( "#emailTo" ).val('');
		}
	})
     .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
			findDataRemove = $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) );
				
			response( $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) ) );     
        },
        search: function() {
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	  $( "#emailCC" ).blur(function(){
		if(findDataCCRemove.length==0){
			$( "#emailCC" ).val('');
		}
	})
     .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
			findDataCCRemove = $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) );
			response( $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) ) );     
        },
        search: function() {
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	  $( "#emailBCC" ).blur(function(){
		if(findDataBCCRemove.length==0){
			$( "#emailBCC" ).val('');
		}
	})
     .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
			findDataBCCRemove = $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) );
			response( $.ui.autocomplete.filter(
				availableTags, extractLast( request.term ) ) );     
        },
        search: function() {
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  });
  </script>
<div id="page-header" class="bg-gradient-9">

    <div id="mobile-navigation">

        <button id="nav-toggle" class="collapsed" data-toggle="collapse" data-target="#page-sidebar"><span></span></button>

        <a href="#" class="logo-content-small" title="Backyard">SynPat</a>

    </div>

    <div id="header-logo" class="logo-bg">


        <a id="close-sidebar" href="#" title="Close sidebar">
            <span class="logo-content-big" title="Dashboard">SynPat</span>
            <i class="glyph-icon icon-angle-right"></i>
        </a>
       

    </div>
<script>

	function checkModalFrontOrHide($modal, callback) {
		var bodyDataZindex = jQuery('body').data('modalzindex'),
			modalZindex = +$modal.css('z-index');

		var lastZindex = 0;
		$('.modal:visible, .sb-slidebar:visible').each(function(index, obj) {
			if(+$(obj).css('z-index') > lastZindex) {
				lastZindex = +$(obj).css('z-index');
			}
		});
		if(lastZindex === modalZindex) {
			$modal.modal("hide");
			if(typeof callback === 'function') {
				callback();
			}
		} else {
			bodyDataZindex += 1;
    		jQuery('body').data('modalzindex', bodyDataZindex);
			$modal.css('z-index', bodyDataZindex);
		}
	}

	function openTaskModal(){
		if(!jQuery("#createTaskModal1").is(':visible')) {
			jQuery("#createTaskModal1").modal("show");
	        jQuery("#createTaskModalLabel").html("Create Task for - "+leadNameGlobal);
       	} else {
			checkModalFrontOrHide(jQuery("#createTaskModal1"));
       	}
	}	
	function openCalendar(){
		if(!jQuery("#open_calendar").is(':visible')) {
			_height = jQuery(window).height()-80;
			jQuery("#open_calendar").find('iframe').attr('src','https://www.google.com/calendar/embed?title=SynPat%40SynPat%20-%20Team%20Calendar&amp;mode=WEEK&amp;height=800&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=admin%40synpat.com&amp;color=%236B3304&amp;src=kerry%40synpat.com&amp;color=%238D6F47&amp;src=ron%40synpat.com&amp;color=%235A6986&amp;src=sanjay%40synpat.com&amp;color=%23865A5A&amp;src=synpat%40synpat.com&amp;color=%23060D5E&amp;src=uzi%40synpat.com&amp;color=%2328754E&amp;src=webmaster%40synpat.com&amp;color=%238D6F47&amp;ctz=America%2FLos_Angeles').css("height",_height);
			jQuery("#open_calendar").modal("show");
		} else {
			checkModalFrontOrHide(jQuery("#open_calendar"));
		}
	}    
    function openConmmentModal(){
		if(leadGlobal==0){
			alert("First you have to select a lead.");
		} else {
			if(!$('#btnComments').is(':visible')) {
				jQuery("#msg_div").html("");
				jQuery("#btnComments").modal('show');
				jQuery('body').removeAttr('onselectstart');
			}
			else {
				checkModalFrontOrHide(jQuery("#btnComments"));
			}
		}        
    }
	
	if(!String.linkify) {
		String.prototype.linkify = function() {
			var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;
			var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
			var emailAddressPattern = /[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/gim;
			return this
				.replace(urlPattern, '<a href="$&" target="_BLANK">$&</a>')
				.replace(pseudoUrlPattern, '$1<a href="http://$2" target="_BLANK">$2</a>')
				.replace(emailAddressPattern, '<a href="mailto:$&" target="_BLANK">$&</a>');
		};
	}	
	jQuery(document).ready(function(){
		jQuery('#btnComments').on('hidden.bs.modal', function () {
			jQuery('body').attr('onselectstart','return false');
		});		
	});
	function openLicenseesModal(){
		if(!jQuery("#open_licensees").is(':visible')) {
			jQuery('body').removeAttr('onselectstart');
			document.oncontextmenu=new Function("return true");
			jQuery("#open_licensees").modal('show');
			if(jQuery("#open_licensees").find('.showLucidData').length==0){
				_height = jQuery(window).height() - 80;
				jQuery('.licenseesBody').html("<iframe height='"+_height+"px' width='100%' style='overflow-x: hidden;' src='<?php echo $Layout->baseUrl;?>dashboard/licensees/"+leadGlobal+"'></iframe>");					
			}
		}
		else {
			checkModalFrontOrHide(jQuery("#open_licensees"));
		}
	}
	function openLucidChartModal(){
		if(leadGlobal==0){
			alert("First you have to select a lead.");
		} else {
			if(!jQuery("#open_lucidchart").is(':visible')) {
				jQuery("#open_lucidchart").modal('show');
				if(jQuery("#open_lucidchart").find('.showLucidData').length==0){
					jQuery('.lucidChartBody').html("<iframe height='600px' width='100%' style='overflow-x: hidden; overflow-y: auto' src='<?php echo $Layout->baseUrl;?>dashboard/charts/"+leadGlobal+"'></iframe>");					
				}
			}
			else {
				checkModalFrontOrHide(jQuery("#open_lucidchart"));
			}
		}
	}
	
	function openDocketModal(){
		if(leadGlobal==0){
			alert("First select a Lead");
		} else {
			if(!jQuery("#open_opportunity").is(':visible')) {
				jQuery("#docketLabel").html("Docket - "+leadNameGlobal);
			    _stage = jQuery("#all_type_list").find('tbody').find('tr.active').find('td').eq(2).html();
				_h = jQuery(window).height() -200;
	            jQuery("#open_opportunity:hidden").find('#docket_frame').html('<iframe width="100%" height="'+_h+'px" scrolling="yes" src="<?php echo $Layout->baseUrl;?>opportunity/dummy_opportunity/'+leadGlobal+'"></iframe>');
		        jQuery("#open_opportunity").modal('show');
            }
            else {
				checkModalFrontOrHide(jQuery("#open_opportunity"));
            }
		}
	}
	function openEOUModal(){
		if(leadGlobal==0){
			alert("First select a Lead");
		} else {
			if(!jQuery("#open_eou_folder").is(':visible')) {
				jQuery("#open_eou_folder:hidden").find('#eou_frame').html('<iframe width="100%" height="300px" scrolling="yes" src="<?php echo $Layout->baseUrl;?>opportunity/eou_in_folder/'+leadGlobal+'"></iframe>');
				jQuery("#open_eou_folder").modal('show');
			}
			else {
				checkModalFrontOrHide(jQuery("#open_eou_folder"));
			}
		}
	}   
    function saveComment(){
	  jQuery("#loading_lead_comment").show();
	  jQuery.ajax({
            url:'<?php echo site_url(); ?>dashboard/saveComment',
            type:'POST',
            data:{'lead_id':leadGlobal,'type':jQuery("#leadType").val(),'comment1':jQuery("#commentComment1").val(),'comment2':jQuery("#commentComment2").val(),'comment3':jQuery("#commentComment3").val(),'attractiveness':jQuery("#attractiveness").val(),id:jQuery("#commentId").val()},
            success:function(res){
				jQuery("#loading_lead_comment").hide();
				var obj = jQuery.parseJSON(res);
				if(obj.error==0){
					if(jQuery("#commentId").val()>0){
					    jQuery("#btnComments").find("#msg_div").html("<p style='color:#ff0000;font-weight:bold;margin-left:14px;margin-top:10px;'>Successfully Comments Updated</p>");
					} else{
					   jQuery("#btnComments").find("#msg_div").html("<p style='color:#ff0000;font-weight:bold;margin-left:14px;margin-top:10px;'>Successfully Comments Added</p>");
					}
				} else {
					alert("Try after sometime");
				}
            }
        });
	}
    function openContactForm(){
    	if(!jQuery("#contactForm").is(':visible')) {
			jQuery.ajax({
				type:'POST',
				url:"<?php echo $this->config->base_url()?>dashboard/search_contact",
				cache:false,
				success:function(data){
					availableTags = jQuery.parseJSON(data);										
				}
			});
			jQuery("#contactForm").find('.modal-body').html('<iframe id="contactFormIframe" src="<?php echo $Layout->baseUrl;?>opportunity/contact" width="100%" height="700px" scrolling="no"></iframe>');
       		jQuery("#contactForm").modal("show");
			$("#contactForm").draggable({
				handle: ".modal-header"
			});
			$("#contactForm .modal-content").resizable();
    	}
    	else {
       		jQuery("#contactForm").modal("hide");
    	}		
        jQuery("#contactForm").on('hidden.bs.modal', function () {
        	jQuery("#contactFormIframe").remove();
	  	});
    }	
	function openContactForFrom(type,parentElemenr){
		if(leadGlobal>0){
				jQuery("#contactSellerFormIframe").attr("src","<?php echo $Layout->baseUrl;?>opportunity/contact_form/"+leadGlobal+"/"+type+"/"+parentElemenr).css('display','block');
				jQuery("#sellerContactForm").modal("show");
		} else {
			alert('Please select lead first.');
		}
	}	
	function openMarketButton(t){
		if(leadGlobal>0){
			if(!jQuery("#marketContactForm").is(':visible')) {
				jQuery("#marketContactFormIframe").attr("src","<?php echo $Layout->baseUrl;?>opportunity/market_form/"+leadGlobal+"/"+t).css('display','block');
				jQuery("#marketContactForm").modal("show");
			}
		} else {
			alert('Please select lead first.');
		}
	}    
    function submitContactForm(){
        _data = "";
        _data = jQuery("#contactFormSubmit").serialize();
        jQuery.ajax({
            type:'POST',
            url:'<?php echo $Layout->baseUrl; ?>/opportunity/add_topbar_contact',
            data:_data,
            success:function(res){
                alert(res);
                jQuery("#contactForm").modal("hide");
            }
        });
    }	
	function openMessageBox(objectID){
		jQuery("#replyParentId").val(objectID);
		approvedFile(objectID);
	}
</script>

    <div id="header-nav-left">

        <div class="user-account-btn dropdown">

            <a href="#" title="My Account" class="user-profile clearfix" data-toggle="dropdown">

                <i class="glyph-icon icon-angle-down"></i>
                
                <span><?php echo $this->session->userdata['name'];?></span>

				<?php 

					if(empty($this->session->userdata['profile_pic'])):

				?>

				<img width="48" height="48" src="<?php echo $Layout->baseUrl; ?>public/image-resources/gravatar.jpg" alt="Profile image"/>

				<?php else:?>

					<img width="48" height="48" src="<?php echo $this->session->userdata['profile_pic']; ?>" alt="Profile image"/>

				<?php endif;?>

            </a>

            <div class="dropdown-menu float-left">

                <div class="box-sm">

                    <div class="login-box clearfix">

                        <div class="user-img">

                            <a href="#" title="" class="change-img">Change photo</a>

							<?php 

								if(empty($this->session->userdata['profile_pic'])):

							?>

                            <img src="<?php echo $Layout->baseUrl; ?>public/image-resources/gravatar.jpg" alt="">

							<?php else:?>

							<img src="<?php echo $this->session->userdata['profile_pic']; ?>" alt="">

							<?php endif;?>

							

                        </div>

                        <div class="user-info">

                            <span>

                                <?php echo $this->session->userdata['name'];?>

                                <i>

									<?php 

										switch($this->session->userdata['type']){

											case 1:

												echo "Program Director";

											break;

											case 5:

												echo "CIPO";

											break;

											case 9:

												echo "Admin";

											break;

										}

									?>

								</i>

                            </span>

							<?php echo anchor('users/profile', 'Edit profile', 'title="Edit profille"');?>

                            

                        </div>

                    </div>

                    <div class="divider"></div>


                    <div class="pad5A button-pane button-pane-alt text-center">

                        <a href="javascript://" onclick="logout();" class="btn display-block font-normal btn-danger" style="color:#ffffff;">

                            <i class="glyph-icon icon-power-off"></i>

                            Logout

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div><!-- #header-nav-left -->



    <div id="header-nav-right">
		<a class="hdr-btn" href="#" id="docket_invitation" onclick="open_all_invitation();" style='position: relative;'>
            <i class="glyph-icon icon-users tooltip-button" title="Calendar Event" data-placement="bottom"></i>
        </a>		
		<a class="hdr-btn" href="#" id="web_lead" onclick="open_all_list();" style='position: relative;'>
            <i class="glyph-icon icon-globe tooltip-button" title="Leads from SynPat.com" data-placement="bottom"></i>
        </a>		
		<a class="hdr-btn" href="#" onclick="openLicenseesModal();">
            <i class="glyph-icon icon-newspaper-o tooltip-button" title="Licensees" data-placement="bottom"></i>
        </a>
		<?php 
			$orderLucid = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(1,$this->session->userdata['modules_assign'])){
					$orderLucid = false;
				}
			}		
			if($orderLucid===true):
		?>		
		<?php endif;?> 
		<?php 
			$openDocket = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(2,$this->session->userdata['modules_assign'])){
					$openDocket = false;
				}
			}
			if($openDocket===true):
		?>
		<a class="hdr-btn" href="#" onclick="openDocketModal();">
            <i class="glyph-icon icon-stack-exchange tooltip-button" title="Docket" data-placement="bottom"></i>
        </a>
		<?php endif;?> 
		<?php 
			$openEOU = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(3,$this->session->userdata['modules_assign'])){
					$openEOU = false;
				}
			}
			if($openEOU===true):
		?>
		<a class="hdr-btn" href="#" onclick="openEOUModal();">
            <i class="glyph-icon icon-folder tooltip-button" title="Due Dilligence Project" data-placement="bottom"></i>
        </a>
		<?php endif;?> 
		<?php 
			$openComment = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(4,$this->session->userdata['modules_assign'])){
					$openComment = false;
				}
			}
			if($openComment===true):
		?>
        <a class="hdr-btn" href="#" onclick="openConmmentModal();">
            <i class="glyph-icon icon-comments-o tooltip-button" title="Lead Team Notes" data-placement="bottom"></i>
        </a>
		<?php endif;?>		
		<?php 
			$openTask = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(5,$this->session->userdata['modules_assign'])){
					$openTask = false;
				}
			}
			if($openTask===true):
		?>
		<a href="javascript://" onclick="openTaskModal()" class="hdr-btn">
			 <i class="glyph-icon icon-plus tooltip-button" title="Create a Task" data-placement="bottom"></i>
		</a>
		<?php endif;?> 
		<?php 
			$openMyTask = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(6,$this->session->userdata['modules_assign'])){
					$openMyTask = false;
				}
			}
			if($openMyTask===true):
		?>
		<a href="#" class="hdr-btn" id="task-btn">
            <i class="glyph-icon icon-tasks tooltip-button" title="Task Created For Me" data-placement="bottom"></i>
        </a>
		<?php 
			$completed = 0;
			$mycreatedTaskList = getUserMyCreatedTaskList();
			if(count($mycreatedTaskList)>0){
				for($i=0;$i<count($mycreatedTaskList);$i++){
					if($mycreatedTaskList[$i]->notifyStatus==1){
						$completed++;
					}
				}
			}
		?>
		<a href="#" class="hdr-btn" id="task-i-btn" style='position: relative;'>
			<span class='bs-badge badge-absolute bg-red' style='top:-13px;left:4px;padding:0 7px 0 5px;'><?php echo $completed;?></span>
            <i class="glyph-icon icon-tasks tooltip-button" title="Task I created" data-placement="bottom"></i>
        </a>
		<?php endif;?> 
		<?php 
			$openLeadActivities = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(7,$this->session->userdata['modules_assign'])){
					$openLeadActivities = false;
				}
			}
			if($openLeadActivities===true):
		?>
		<a href="#" class="hdr-btn" id="activity-btn">

            <i class="glyph-icon icon-exclamation-circle tooltip-button" title="Lead Activities" data-placement="bottom"></i>

        </a>
		<?php endif;?> 
		<div class="dropdown " id="notifications-btn">
			<?php 				
				$getAllConversation = getMessageTaskList($this->session->userdata['id']);
				$listConversation = $getAllConversation['receive'];				
			?>
            <a data-toggle="dropdown" href="#" aria-expanded="false" id="btn-notify-user">
			<?php
				if($getAllConversation['countReceieve']>0):
			?>
				<span class="bs-badge badge-absolute bg-red tooltip-button" title="Received Messages" data-placement="bottom" style='padding:0 7px 0 5px;left:10px;'><?php echo $getAllConversation['countReceieve'];?></span>
			<?php endif;?>
                <i class="glyph-icon icon-weixin tooltip-button" title="Received Messages" data-placement="bottom"></i>
            </a>
            <div class="dropdown-menu box-md float-right">
                <div class="popover-title display-block clearfix pad10A">
                    Messages 
                </div>
                <div class="scrollable-content scrollable-slim-box">
                    <ul class="todo-box"> 
                        <?php 							
							if(count($listConversation)>0){
								foreach($listConversation as $message){
						?>								
								<li>
									<a href="javascript://" onclick="openMessageBox(<?php echo $message->taskID?>)" style='text-decoration:none;'>
										<?php 
											$colors_array= "";
											switch($message->leadType){
												case 'Litigation':
													$colors_array = "bg-yellow";
												break;
												
												case 'Market':
													$colors_array = "bg-green";
												break;
												
												case 'General':
													$colors_array = "label-info";
												break;
												
												case 'SEP':
													$colors_array = "bg-warning";
												break;
											}
										?>
										<span class="tl-label bs-label bg-green"><?php echo $message->lead_name?></span>
										<span class="todo-container">
											<span class="todo-content" for="todo-1" title="<?php echo $message->subject?>"><?php echo $message->subject?></span>
											<span class="todo-footer clearfix">
												<span class="todo-footer-dateuser"> <?php echo date('M d,Y',strtotime($message->create_c));?>	&nbsp;&nbsp;&nbsp;<?php echo $message->fromUserName;?></span>
												<?php 
													if(empty($message->profile_pic)):
												?>
												<img title="<?php echo $message->userName;?>" src="<?php echo $this->config->base_url();?>public/upload/user.png" width="28"/>
												<?php else:?>
												<img title="<?php echo $message->userName;?>" src="<?php echo $message->profile_pic;?>" width="28"/>
												<?php endif;?>
												<!--_dataTask-->
											</span>
										</span>
									</a>
								</li>								
						<?php
								}
							} else {
						?>
						<li><p class="alert">No message for you!</p></li>
						<?php
							}
						?>
                    </ul>
                </div>     
            </div>
        </div>
		<div class="dropdown " id="my-notifications-btn">
			<?php 
				$listConversation = $getAllConversation['sent'];	
			?>
			<a data-toggle="dropdown" href="#" id="btn-my-notify-user">
			<?php
				if($getAllConversation['countSend']>0):
			?>
				<span class="bs-badge badge-absolute bg-red tooltip-button" title="Sent Messages" data-placement="bottom" style='padding:0 7px 0 5px;left:10px;'><?php echo $getAllConversation['countSend'];?></span>
			<?php endif;?>
                <i class="glyph-icon icon-weixin tooltip-button" title="Sent Messages" data-placement="bottom"></i>
            </a>
			<div class="dropdown-menu box-md float-right">
                <div class="popover-title display-block clearfix pad10A">
                    Messages 
                </div>
                <div class="scrollable-content scrollable-slim-box">
                    <ul class="todo-box"> 
                        <?php 
							if(count($listConversation)>0){
								foreach($listConversation as $message){
						?>	
								<li>
									<a href="javascript://" onclick="openMessageBox(<?php echo $message->taskID?>)" style='text-decoration:none;'>
										<?php 
											$colors_array= "";
											switch($message->leadType){
												case 'Litigation':
													$colors_array = "bg-yellow";
												break;
												
												case 'Market':
													$colors_array = "bg-green";
												break;
												
												case 'General':
													$colors_array = "label-info";
												break;
												
												case 'SEP':
													$colors_array = "bg-warning";
												break;
											}
										?>
										<span class="tl-label bs-label bg-green"><?php echo $message->lead_name?></span>
										<span class="todo-container">
											<span class="todo-content" for="todo-1" title="<?php echo $message->subject?>"><?php echo $message->subject?></span>
											<span class="todo-footer clearfix">
												<span class="todo-footer-dateuser"> <?php echo date('M d,Y',strtotime($message->create_c));?>	&nbsp;&nbsp;&nbsp;<?php echo $message->fromUserName;?></span>
												<?php 
													if(empty($message->profile_pic)):
												?>
												<img title="<?php echo $message->userName;?>" src="<?php echo $this->config->base_url();?>public/upload/user.png" width="28"/>
												<?php else:?>
												<img title="<?php echo $message->userName;?>" src="<?php echo $message->profile_pic;?>" width="28"/>
												<?php endif;?>
												<!--_dataTask-->
											</span>
										</span>
									</a>
								</li>								
						<?php
								}
							} else {
						?>
						<li><p class="alert">No message by you!</p></li>
						<?php
							}
						?>
                    </ul>
                </div>     
            </div>
		</div>
		<?php 
			$openContactForm = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(8,$this->session->userdata['modules_assign'])){
					$openContactForm = false;
				}
			}
			if($openContactForm===true):
		?>
		<a href="javascript://" class="header-btn" onclick="open_contact_list();">
            <i class="glyph-icon icon-user tooltip-button" title="Add Contact" data-placement="bottom"></i>
        </a>
		<?php endif;?> 
		<?php 
			$openCalendar = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(9,$this->session->userdata['modules_assign'])){
					$openCalendar = false;
				}
			}
			if($openCalendar===true):
		?>
        <a id="btnTopCalendar" class="header-btn" href="javascript://" onclick="openCalendar();">
            <i class="glyph-icon icon-calendar tooltip-button" title="Team Calendar" data-placement="bottom"></i>
        </a>
		<?php endif;?> 
		<?php 
			$openTimeline = true;
			if((int)$this->session->userdata['type']!=9){
				if(!in_array(10,$this->session->userdata['modules_assign'])){
					$openTimeline = false;
				}
			}
			if($openTimeline===true):
		?>
        <a id="btnChartss" class="header-btn" href="javascript://" onclick="getTimeLIne();">

            <i class="glyph-icon icon-pie-chart tooltip-button" title="Timeline" data-placement="bottom"></i>

        </a>
		<?php endif;?>		
        <a class="header-btn" href="<?php echo $Layout->baseUrl?>dashboard">
            <i class="glyph-icon icon-dashboard tooltip-button" title="Dashboard" data-placement="bottom"></i>
        </a>
        <a id="btnEmail" class="header-btn" href="#">
            <i class="glyph-icon icon-envelope tooltip-button" title="Emails&nbsp;/&nbsp;Leads" data-placement="bottom"></i>
        </a>
    </div><!-- #header-nav-right -->
<script>
var chDa = 0;
jQuery(document).ready(function(){
	 $(function() { "use strict";
        $('.input-switch').bootstrapSwitch();
		$('.input-switch').on('switchChange.bootstrapSwitch', function (e, data) {			
			if(jQuery("input[name='checkbox-example-2221']").is(':checked')){
				chDa = 1;
			} else {
				chDa = 0;
			}
			runLogin();
		});
    });
	
});

function runLogin(){
	jQuery.ajax({
		url:'<?php echo $Layout->baseUrl?>dashboard/run_login',
		type:'POST',
		data:{d:chDa,l:leadGlobal},
		cache:false,
		success:function(data){
			
		}
	});
}

function logout(){
	if(jQuery("input[name='checkbox-example-2221']").is(':checked')){
		window.location='<?php echo $Layout->baseUrl?>login/logout/<?php echo $this->session->userdata['id']?>/1';
	} else {
		window.location='<?php echo $Layout->baseUrl?>login/logout/<?php echo $this->session->userdata['id']?>/0';
	}
}
</script>
    <div class="create-lead-pager-wrapper">
		<ul class="pager create-lead clearfix pull-left mrg10T mrg10R" style='margin-bottom:0px;'>
			<li><input type="checkbox" data-on-color="danger" data-size="mini" name="checkbox-example-2221" class="input-switch" checked data-size="mini" data-on-text="On" data-off-text="Off" onclick="checkLogin(jQuery(this))" value="yes"></li>
		</ul>
        <ul class="pager create-lead-pager clearfix pull-left">
            <li class="previous"><a onclick="recordLocal('prev');" href="javascript:void(0)"><i class="glyph-icon icon-angle-left"></i></a></li>
            <li class="pager-text-wrapper">
                <div class="pager-text">0/0</div>
            </li>
            <li class="next"><a onclick="recordLocal('next');" href="javascript:void(0)"><i class="glyph-icon icon-angle-right"></i></a></li>
        </ul>
    </div>

    <!-- Breadcrumbs -->
    <div class="breadcrumb-wrapper clearfix">
	    <ol class="breadcrumb clearfix"></ol>
    	<div class="topbar-lead-name" style=''></div>
    </div>
    <!-- /Breadcrumbs -->
</div>
<!-- Time Line Insert -->
<div class="modal modal-opened-header fade" id="dashboard_charts" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%'>
            <div class="modal-dialog" style='width:100%;'>
				<div class="modal-content">
					<div class='modal-header'><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">&nbsp;</h4></div>
					<div class="modal-body">    
                       <div class="timeline-wrapper" style='width:99.9%'>
							<div class="row">
								<div class="col-lg-12">
								<div>
									<b><big>Time Flow</big></b>
								</div>
								<div class="timeline-wrapper-inner" style='height:500px;width:73%;float:left'>
									<?php 
										$getMyLogTime = getMyLogTime($this->session->userdata['id']);
										if(count($getMyLogTime['all_work'])>0){
									?>
										<table id="timelineDataTable" class='table dataTable no-footer' style='width:100% !important;'>
										<thead>
											<tr>
												<th>Date</th> 
												<th>Start</th>
												<th>End</th>
												<th>Duration</th>
												<th>Leads</th>
												<th>Adjustments</th>
												<th>Total Time</th>
												<th>Comment</th>
											</tr>
										</thead>
										<tbody>
									<?php
										for($i=0;$i<count($getMyLogTime['all_work']);$i++){
									?>
										<tr id="<?php echo $getMyLogTime['all_work'][$i]->id;?>">
											<td><?php echo date('Y-m-d',strtotime($getMyLogTime['all_work'][$i]->login_date))?></td>
											<td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->login_date));?></td>
											<td><?php echo date('H:i',strtotime($getMyLogTime['all_work'][$i]->logout_date));?></td>
											<td><?php echo $getMyLogTime['all_work'][$i]->hrsWorked?></td>
											<td>
												<?php 
													if((int)$getMyLogTime['all_work'][$i]->lead_id>0){
														$getLeadDetail = getLeadDetail($getMyLogTime['all_work'][$i]->lead_id);
														if(count($getLeadDetail)>0){
															echo $getLeadDetail->lead_name;
														}
													}
												?>
											</td>
											<td><select name="actualHrs<?php echo $getMyLogTime['all_work'][$i]->id;?>" id="actualHrs<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:73px' onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)">
													<option value="">-- Adjust. --</option>
													<option value="-00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:15")?'SELECTED="SELECTED"':'';?>>-00:15</option>
													<option value="-00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:30")?'SELECTED="SELECTED"':'';?>>-00:30</option>
													<option value="-00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-00:45")?'SELECTED="SELECTED"':'';?>>-00:45</option>
													<option value="-01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:00")?'SELECTED="SELECTED"':'';?>>-01:00</option>
													<option value="-01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:15")?'SELECTED="SELECTED"':'';?>>-01:15</option>
													<option value="-01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:30")?'SELECTED="SELECTED"':'';?>>-01:30</option>
													<option value="-01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-01:45")?'SELECTED="SELECTED"':'';?>>-01:45</option>
													<option value="-02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:00")?'SELECTED="SELECTED"':'';?>>-02:00</option>
													<option value="-02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:15")?'SELECTED="SELECTED"':'';?>>-02:15</option>
													<option value="-02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:30")?'SELECTED="SELECTED"':'';?>>-02:30</option>
													<option value="-02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-02:45")?'SELECTED="SELECTED"':'';?>>-02:45</option>
													<option value="-03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:00")?'SELECTED="SELECTED"':'';?>>-03:00</option>
													<option value="-03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:15")?'SELECTED="SELECTED"':'';?>>-03:15</option>
													<option value="-03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:30")?'SELECTED="SELECTED"':'';?>>-03:30</option>
													<option value="-03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-03:45")?'SELECTED="SELECTED"':'';?>>-03:45</option>
													<option value="-04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:00")?'SELECTED="SELECTED"':'';?>>-04:00</option>
													<option value="-04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:15")?'SELECTED="SELECTED"':'';?>>-04:15</option>
													<option value="-04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:30")?'SELECTED="SELECTED"':'';?>>-04:30</option>
													<option value="-04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-04:45")?'SELECTED="SELECTED"':'';?>>-04:45</option>
													<option value="-05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="-05:00")?'SELECTED="SELECTED"':'';?>>-05:00</option>
													<option value="00:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="00:15")?'SELECTED="SELECTED"':'';?>>00:15</option>
													<option value="00:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="0:30")?'SELECTED="SELECTED"':'';?>>00:30</option>
													<option value="00:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="0:45")?'SELECTED="SELECTED"':'';?>>00:45</option>
													<option value="01:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:00")?'SELECTED="SELECTED"':'';?>>01:00</option>
													<option value="01:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:15")?'SELECTED="SELECTED"':'';?>>01:15</option>
													<option value="01:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:30")?'SELECTED="SELECTED"':'';?>>01:30</option>
													<option value="01:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="01:45")?'SELECTED="SELECTED"':'';?>>01:45</option>
													<option value="02:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="2:00")?'SELECTED="SELECTED"':'';?>>02:00</option>
													<option value="02:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:15")?'SELECTED="SELECTED"':'';?>>02:15</option>
													<option value="02:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:30")?'SELECTED="SELECTED"':'';?>>02:30</option>
													<option value="02:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="02:45")?'SELECTED="SELECTED"':'';?>>02:45</option>
													<option value="03:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:00")?'SELECTED="SELECTED"':'';?>>03:00</option>
													<option value="03:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:15")?'SELECTED="SELECTED"':'';?>>03:15</option>
													<option value="03:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:30")?'SELECTED="SELECTED"':'';?>>03:30</option>
													<option value="03:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="03:45")?'SELECTED="SELECTED"':'';?>>03:45</option>
													<option value="04:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:00")?'SELECTED="SELECTED"':'';?>>04:00</option>
													<option value="04:15" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:15")?'SELECTED="SELECTED"':'';?>>04:15</option>
													<option value="04:30" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:30")?'SELECTED="SELECTED"':'';?>>04:30</option>
													<option value="04:45" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="04:45")?'SELECTED="SELECTED"':'';?>>04:45</option>
													<option value="05:00" <?php echo ($getMyLogTime['all_work'][$i]->actual_hrs=="05:00")?'SELECTED="SELECTED"':'';?>>05:00</option>
													
												</select>
											</td>
											<td>
												<?php
													$duration = $getMyLogTime['all_work'][$i]->hrsWorked;
													$adjustedlHrs = $getMyLogTime['all_work'][$i]->actual_hrs;
													if(!empty($adjustedlHrs)){
														$adjustedlHrs = $adjustedlHrs.":00";
													}
													$totalHrs = $duration;
													if($duration!='' && $duration!='00:00:00'){
														$d = strtotime($duration);
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("-".$h." hour  -".$m." minutes",$d));
														} else if($lengthOfAdjustedHrs==8){
															list($h,$m,$s) = explode(':',$adjustedlHrs);
															$totalHrs = date('H:i:s',strtotime("+".$h." hour  +".$m." minutes",$d));
														}
													} else{
														$lengthOfAdjustedHrs = strlen($adjustedlHrs);
														if($lengthOfAdjustedHrs==9 || $lengthOfAdjustedHrs==8){
															$totalHrs = $adjustedlHrs;
														}
													}
													echo $totalHrs;
												?>
											</td>
											<td style='width:430px;'><textarea class='form-control'  onchange="saveFlagUpdate(<?php echo $getMyLogTime['all_work'][$i]->id;?>)" placeholder="Comment" name="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" id="comment<?php echo $getMyLogTime['all_work'][$i]->id;?>" style='width:400px;height:25px;border:0px;'><?php echo $getMyLogTime['all_work'][$i]->comment?></textarea></td>
										</tr>
									<?php
										}
									?>
										</tbody>
										</table>

											
										<script>
											var timelineDataTable = $('#timelineDataTable').DataTable({
												"autoWidth": true,
    											"paging": false,
												"searching":false,
    											"scrollY": 400,
												"order": [[ 0, "desc" ]]
											});
										</script>
									<?php
										}
									?>
									<div class="col-lg-12" style='border-top:1px solid #d1c8c8;width:99%'></div>
									</div>
									
									<div class='pull-right' style="width:25%; margin-top: 8px;">
										<div class="row">
											<div class="col-lg-12" style="width: 285px;">
												<select class='form-control' onchange="findLeadHrs(jQuery(this));">
													<option>--Select Lead--</option>
													<?php 
														if(count($getMyLogTime['allLeadsWorked'])>0){
															foreach($getMyLogTime['allLeadsWorked'] as $lead){
													?>
																<option value="<?php echo $lead->lead_id?>"><?php echo $lead->lead_name?></option>
													<?php
															}
														}
													?>
												</select>
												<label class='hide mrg10T'><strong>Total Time Spent in this Lead:</strong></label>
											</div>
											<div class="col-lg-12 mrg10T">
												<label><strong>Total time spent in current month: <?php echo ($getMyLogTime['totalHoursCurrent']->totalHrsWorked!=null)?$getMyLogTime['totalHoursCurrent']->totalHrsWorked:'';?></strong></label>
											</div>
											<div class="col-lg-12 mrg10T">
												<label><strong>Total time spend in previous month: <?php echo ($getMyLogTime['totalHours']->totalHrsWorked!=null)?$getMyLogTime['totalHours']->totalHrsWorked:'';?></strong></label>
											</div>
										</div>
									</div>									
								</div>
							</div>

							<div class="mrg25T">
								<b><big>Activity Flow</big></b>
							</div>
							<div id="mytimeline" class="mrg10T"></div>
						</div>                    
                        <div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>	
<!-- End Time Line-->

		<script type="text/javascript">
			function findLeadHrs(o){
				if(o.val()!=""){
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $Layout->baseUrl?>dashboard/leadLogTime',
						data:{l:o.val()},
						success:function(data){
							if(data!=""){
								o.parent().find('label').removeClass('hide').html('<strong>Total Time Spent in this Lead: '+data+'</strong>');
							} else {
								o.parent().find('label').addClass('hide');
							}
						}
					})
				}
			}
			/* Datepicker bootstrap */
			jQuery(document).ready(function(){
				jQuery("#dashboard_charts").on('hidden.bs.modal', function () {
					if(updateLogs.length>0){
						for(d=0;d<updateLogs.length;d++){
							updateLogData(updateLogs[d]);
						}
					}
				});
			});
			var updateLogs = [];
			function saveFlagUpdate(d){				
				if(jQuery.inArray(d,updateLogs)=='-1'){
					updateLogs.push(d);		
					updateLogData(d);
				}else{
					updateLogData(d);
				}
			}
			function updateLogData(d){
				_aH = jQuery("#actualHrs"+d).val();
				_c = jQuery("#comment"+d).val();
				jQuery.ajax({
					url:'<?php echo $Layout->baseUrl;?>dashboard/updateLogHr',
					type:'POST',
					data:{i:d,ah:_aH,c:_c},
					cache:false,
					success:function(data){
						if(data!='0'){
							jQuery("#"+d).find('td').eq(6).html(data);
						}
					}
				})
			}
			$(function() { "use strict";
				$('.bootstrap-datepicker').bsdatepicker({
					format: 'yyyy-mm-dd'
				});
			});
			
			function getTimeLIne(){
				if(!jQuery("#dashboard_charts").is(':visible')) {
					_height=jQuery(window).height()-80;
					jQuery("#dashboard_charts .modal-content").css("height",_height+"px");
					jQuery("#dashboard_charts").modal("show");
				}
				else {
					checkModalFrontOrHide(jQuery("#dashboard_charts"));
				}
			}
			function submitTask(){
				if(leadGlobal==0 ||  leadGlobal == ""){
					alert("No entity found!");
				} else {
					if(jQuery("#taskLeadId").val()=="" || jQuery("#taskLeadId").val()== 0){
						jQuery("#taskLeadId").val(leadGlobal);
					}
					
					if(jQuery("#taskType").val()==""){
						jQuery("#taskType").val(__mainEntityType);
					}					
					jQuery("#formTask").eq(0).submit();
				}
			}
			function replyTask(){
				if(parseInt(jQuery("#replyLeadId").val())>0){
					jQuery("#formReplyTask").eq(0).submit();
				} else {
					alert("No entity found!");
				}
			}
			 
			function addContact(){
				_error = 0;
				_message = "";
				var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
				if(jQuery.trim(jQuery("#inviteeFirstName").val())==""){
					_error = 1;
					_message  ="Please enter first name.\n";
				}
				if(jQuery.trim(jQuery("#inviteeLastName").val())==""){
					_error = 1;
					_message  +="Please enter last name.\n";
				}
				if(jQuery.trim(jQuery("#inviteeTelephone").val())==""){
					_error = 1;
					_message  +="Please enter work phone.\n";
				}
				if(jQuery.trim(jQuery("#inviteeEmail").val())==""){
					_error = 1;
					_message  +="Please enter email address.\n";
				} else if(!filter.test(jQuery.trim(jQuery("#inviteeEmail").val()))){
					_error = 1;
					_message  +="Please enter correct email address.\n";
				}
				if(_error==0){
					jQuery("#buttonADDContact").css('display','none');
					jQuery("#addContactTask").css("display","inline-block");
					_dataSearlise = jQuery("#contactFormSubmit").serialize();
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $Layout->baseUrl?>opportunity/add_contact',
						data:_dataSearlise,
						cache:false,
						success:function(data){
							jQuery("#buttonADDContact").css('display','');
							jQuery("#addContactTask").css("display","none");
							if(data>0){							
								document.getElementById('contactFormIframe').contentWindow.location.reload();
								jQuery("#addContactForm").modal("hide");
							}
						}
					});
				} else {
					alert(_message);
				}				
			}
			
			function refreshContacts(){
				jQuery.ajax({
					type:'POST',
					url:"<?php echo $this->config->base_url()?>dashboard/search_contact",
					cache:false,
					success:function(data){
						availableTags = jQuery.parseJSON(data);	
						alert("Contact list refresh.");
					}
				});
			}
			
		</script>
		
		<div class="modal modal-opened-header fade" id="open_calendar" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%'>
            <div class="modal-dialog" style='width:100%'>
				<div class="modal-content">
					<div class="modal-body">    
                        <div class="row">
                            <div class="col-lg-12">                               	
								<iframe id="calendarFramePg" src="" style="border-width:0;width:100%; "  height="800" frameborder="0" scrolling="no"></iframe>
								
                            </div>
                        </div>                       
                        <div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
        <div class="modal modal-opened-header fade" id="open_opportunity"  role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%'>
            <div class="modal-dialog" style='width:100%'>
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="docketLabel">Docket</h4>
					</div>
					<div class="modal-body">					
                        <div class="row">
                            <div class="col-lg-12" id="docket_frame">                                
                            </div>                            
                        </div>                       
                        <div class="clearfix">
                        </div>
					</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>                       
                    </div>					
				</div>
			</div>
		</div>
		<div class="modal modal-opened-header fade " id="open_eou_folder"  role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style="width:100%">
            <div class="modal-dialog " style='margin-top: 3px; width:100%'>
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="calendarLabel">Task Log Data</h4>
					</div>
					<div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12" id="eou_frame">                                
                            </div>                            
                        </div>                       
                        <div class="clearfix">
                        </div>
					</div>
                    <div class="modal-footer sb-bottom">
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>                       
                    </div>					
				</div>
			</div>
		</div>
		<div class="modal modal-opened-header fade" id="open_licensees"   role="dialog" aria-labelledby="lucidchartLabel" aria-hidden="true" style='width:100%'>
            <div class="modal-dialog" style='width:100%'>
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="calendarLabel">Licensees</h4>
					</div>
					<div class="modal-body licenseesBody">                           
                        <div class="clearfix">
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal modal-opened-header fade" id="open_lucidchart" tabindex="-1"  role="dialog" aria-labelledby="lucidchartLabel" aria-hidden="true" style='width:100%'>
            <div class="modal-dialog" style='width:100%'>
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="calendarLabel">Docket</h4>
					</div>
					<div class="modal-body lucidChartBody">                           
                        <div class="clearfix">
                        </div>
					</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>                       
                    </div>
					
				</div>
			</div>
		</div>
        <div class="modal modal-opened-header fade" id="btnComments" tabindex="-1" role="dialog" aria-labelledby="btnComments" aria-hidden="true" style='width:100%'>
			<div class="modal-dialog" style='width:100%'>
				<div class="modal-content">					
					<div class="modal-header"> 
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="calendarLabel">Team Notes</h4>
					</div>
                    <div id="msg_div"></div>
					<div class="modal-body">
                        <div class="row">
							<div class="col-lg-12">
								<div class="col-lg-3" id="patentees_data" style="height: 495px; overflow:auto;">
								</div>
								<div class="col-lg-9">
									<div class="col-lg-12" id="comment_upper_list" style="max-height: 300px; overflow-x: hidden; overflow-y: auto;"></div>
									<div id="form_comment_upper" class="mrg10T col-lg-12">
										<form name="topbar_commentForm" action="" id="topbar_commentForm" class="form-flat form-horizontal">
											<div class="row row-width">
												<div class="col-xs-4">
													<label class="control-label" for="marketProspectsName">Are there >10 potential licensees? Who?</label>
													<?php echo form_textarea(array('name'=>'comment[comment1]','id'=>'commentComment1','value' => '','class'=>'form-control','rows'=>4,'cols'=>15));?>	
												</div>
												<div class="col-xs-4">
													<label class="control-label" for="marketProspectsName">Will licensees want to pay the expected fee? Why?</label>
													<?php echo form_textarea(array('name'=>'comment[comment2]','id'=>'commentComment2','value' => '','class'=>'form-control','rows'=>4,'cols'=>15));?>	
												</div>
												<div class="col-xs-4">
													<label class="control-label" for="marketProspectsName">Seller's concerns + Your general observations</label>
													<?php echo form_textarea(array('name'=>'comment[comment3]','id'=>'commentComment3','value' => '','class'=>'form-control','rows'=>4,'cols'=>15));?>	
												</div>
												<div class="col-width" style="width:154px;">
													<div style="margin-right:-4px;">
														<label class="control-label" for="marketProspectsName">Attractiveness</label>
														<select name="attractiveness" id="attractiveness" class="form-control" style='/*width:90px;*/'>
															<option value="High">High</option>
															<option value="Medium">Medium</option>
															<option value="Low">Low</option>
														</select>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>							
						</div>                       
				   </div>
                    <div class="modal-footer">
                        <input type="hidden" name="comment_id" id="commentId"/>
                        <input type="hidden" name="type" value="" id="leadType"/>
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>
                        <a href="javascript:void(0);" class="btn btn-primary btn-mwidth" onclick="saveComment();">Save</a>
						<div id="loading_lead_comment" class="loading-spinner hide">
							<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt="">
						</div>
                    </div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="createTaskModal1" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true" style="width:50%;left:0px">
			<div class="modal-dialog" style='width:100%'>
				<div class="modal-content">                    
					<?php echo form_open("opportunity/task",array("class"=>"form-horizontal form-flat","id"=>"formTask"));?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createTaskModalLabel"></h4>
					</div>
					<div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">For:</label>
                                    <select name="task[user_id]" id="taskUserId" required="required" class="form-control" style="float: left; width: 225px; margin-top: 2px; margin-left: 3px;">
                                        <option value="">-- Select User --</option>
                                        <?php 
                                            $getUsers = getAllUsersIncAdmin();
                                            foreach($getUsers as $user){
                                        ?>
                                            <option value="<?php echo $user->id?>"><?php echo $user->name;?></option>
                                        <?php                                               
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 mrg5T">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">Subject:</label>
                                    <input type="text" maxlength="40" class="form-control input-string" name="task[subject]" id="taskSubject" style="float: left; width: 225px; margin-top: 4px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix mrg10T">
                            <div class="form-group">
                                <label>Message:</label>
                                <textarea  name="task[message]" class=" form-control input-string" id="taskMessage" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">Link Url:</label>
                                    <input input name="task[doc_url]" class="form-control input-string" id="taskDocUrl" style="float: left; width: 518px; margin-top: 4px;" />
                                </div>
                            </div>
                            <div class="col-xs-12 mrg5T">
                                <div class="clearfix">
                            <label class="control-label" style="float:left;">Execution Date:</label>
                            <input input name="task[execution_date]" class="bootstrap-datepicker form-control input-string" id="taskExecutionDate" placeholder="yyyy-mm-dd" style="float: left; width: 82px; margin-top: 4px;" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix">
                        </div>
					</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-mwidth" onclick="submitTask();">Create</button>
                        <input type="hidden" name="task[parent_id]" value="0" id="taskParentId" />
                        <input type="hidden" name="task[from_user_id]" value="<?php echo $this->session->userdata['id']?>" id="taskFromUserId" />
                        <input type="hidden" name="task[lead_id]" value="0" id="taskLeadId" />
                        <input type="hidden" name="task[type]" id="taskType" />
                        <input type="hidden" name="task[id]" id="taskId" value="0" />
                    </div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
		<div class="modal fade" id="replyTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true" style="width:50%;left:0px">
			<div class="modal-dialog" style='width:100%'>
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="replyTaskModalLabel">Task Details</h4>
					</div>
					<div class="modal-body">
						<?php echo form_open("opportunity/reply_task",array("class"=>"form-horizontal form-flat","id"=>"formReplyTask"));?>
                        <div class="row">
                            <div class="col-xs-2" style='width:12%;overflow:hidden;'>
                                <div class="form-group">
                                    <label><strong>From:</strong></label>
                                    <span id="replyFromUser"></span>
                                </div>
                            </div>
							<div class="col-xs-2" style='width:12%;overflow:hidden;'>
                                <div class="form-group">
                                    <label><strong>To:</strong></label>
                                    <span id="replyToUser"></span>
                                </div>
                            </div>
							<div class="col-xs-3">
                                <div class="form-group">
                                    <label><strong>Subject:</strong></label>
                                    <span id="replySubject" style='overflow:hidden'></span>
                                </div>
                            </div>
                            <div class="col-xs-2" style='margin-left:3px;'>
                                <div class="form-group">
                                    <label><strong>Created On:</strong></label>
                                    <span id="replyReceived"></span>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label><strong>Execut. Dt:</strong></label>
                                    <span id="replyExecutionDate"></span>
                                </div>
                            </div>
							
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label><strong>Link:</strong></label>
                                    <span id="replyLink"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="viewticket">
									<div class="box_content p9">
										<div class="databox blue">
											<div class="comments">
												<div class="comment">
													<div class="comment_content">
														<div id="ticket__body">
															<p></p>
														</div>
													</div>
													<a id="ticket__body_button"><span></span></a>
												</div>
											</div>
										</div>
									</div>									
                                </div>
                            </div>
                        </div>
                        <div class="row mrg10T">
                            <div class="col-xs-4" id="replyForwardTask">
                                <div class="form-group">
                                    <label>
                                        Forward Task
                                        <input type="checkbox" name="reply[forward]" id="replyForward" value="1" onclick="forwardEnabled(jQuery(this))"/>
                                    </label>
                                </div>
                            </div>
							<div class="col-xs-4" id="deleteTask"> 
								<div class="form-group">
									<label>
										Remove  Task
										<input type="checkbox" name="reply[reply]" value="2" onclick="taskMyComplete(jQuery(this))"  id="replyRemoveTask" />
									</label>
								</div>
							</div>
							<div class="col-xs-4" id="replyCompleteTask">
								<div class="form-group">
									<label>
										Complete
										<input type="checkbox" name="reply[complete]" value="1" onclick="taskComplete(jQuery(this))"  id="replyComplete" />
									</label>
								</div>
							</div>
							
                        </div>
						<div class="" id="forwardUserTo" style="display:none;">
							<div class="form-group">
                                <label style="float:left;" class="control-label">User:</label>
								<select name="reply[user_id]" id="taskUserId" required="required" class="form-control" style="float: left; width: 225px; margin-top: 2px; margin-left: 3px;">
									<option value="">-- Select User --</option>
									<?php 										
										foreach($getUserList as $user){
											if($user->id!=$this->session->userdata['id']):
									?>
										<option value="<?php echo $user->id?>"><?php echo $user->name;?></option>
									<?php
											endif;
										}
									?>
								</select>
							</div>
						</div>
                        <div class="row ">
                            <div class="col-xs-12 mrg10T" id="forwardMessageTo" style="display:none;">
                                <div class="form-group">
                                    <label>Message:</label>
                                    <textarea  name="reply[message]" class=" form-control input-string" id="replyMessage" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 mrg5T" id="forwardExecutionDate" style="display:none;">
                                <div class="form-group">
                                    <label class="control-label" style="float:left;">Execution Date:</label>
                                    <input name="reply[execution_date]" class="bootstrap-datepicker form-control input-string" id="replyExecutionDate" style="float: left; width: 82px; margin-top: 4px;" placeholder="yyyy-mm-dd" />
                                </div>
                            </div>
                        </div>
						<div class="row ">							
							<button type="button" class="btn btn-primary btn-mwidth" onclick="replyTask();" id="replyButton" style='display:none'>Submit</button>
							<button type="button" class="btn btn-primary btn-mwidth" id="formOPen" style='display:none'>Open Form</button>
							<input type="hidden" name="reply[parent_id]" value="0" id="replyParentId" />
							<input type="hidden" name="reply[from_user_id]" value="<?php echo $this->session->userdata['id']?>" id="replyFromUserId" />
							<input type="hidden" name="reply[lead_id]" value="0" id="replyLeadId" />
							<input type="hidden" name="reply[type]" id="replyType" />
							<input type="hidden" name="reply[subject]" id="replyInputSubject" />
							<input type="hidden" name="reply[flag]" value="0" id="replyFlag" />
						</div>
						<?php echo form_close();?>
						<h3 class="font-blue mrg10T">Messages</h3>
						<?php echo form_open("opportunity/task_conversation",array("class"=>"form-horizontal form-flat","id"=>"formTaskConversation"));?>
						<script>
							jQuery(document).ready(function(){
								jQuery(".multi-select").multiSelect('refresh');
								jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
							});
						</script>
						<div class="row">
							<img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="" id="messageListTask" style="display:none">
							<div>
								<div class="viewticket">
									<div class="box m0">
										<div class="box_content p9">
											<div class="databox blue">
												<h5>
													<a id="expand_collapse_button" class="fright" href="">+ Expand All</a>
												</h5>
												<div class="comments" id="message-inbox-task-list" style="max-height:260px;overflow:hidden;overflow-y:scroll;overflow-x:none;">
												</div>
											</div>
										</div>
									</div> 
								</div>
							</div>
							<div class="col-lg-12 mrg10T">
								<textarea name="message[message]" id="messageConversationTask" class="form-control" rows="3" col="29" style='height:65px !important;border-color:#3daeff !important;'></textarea>
							</div>
							<div class="col-lg-12 mrg10T">								
								<select name="message[user][]" id="messageUser" multiple required="required" class="multi-select">
									<?php 
										foreach($getUserList as $user){
									?>
										<option value="<?php echo $user->id;?>"><?php echo $user->name;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<input type="hidden" name="message[task_id]" value="0" id="messageTaskID" />
							<input type="hidden" name="message[from_u]" value="<?php echo $this->session->userdata['id']?>" id="messageSend" />
							<button type="button" class="btn btn-primary btn-mwidth mrg10T pull-right" onclick="sendMessageTaskFor();" id="senTaskConversation">Send</button>
							<div id="loading_task_conversation" class="loading-spinner hide">
								<img src="<?php echo $Layout->baseUrl;?>public/images/ajax-loader.gif" alt=""/>
							</div>
						</div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div> 
		<div id="gmail_message_modal"  class="sb-slidebar bg-white sb-left sb-style-overlay" style='width:53%;margin-top:79px;'>
			<div class="scrollable-content scrollable-slim-sidebar">
				<div class="">
					<script>
						jQuery(document).ready(function(){
							$("#myDashboardComposeEmails").on("submit", function () {
								$(this).find(":submit").prop("disabled", true);
							});
							
						});
					</script>
					<?php echo form_open_multipart('dashboard/reply_email',array('class'=>'form-horizontal form-flat','role'=>'form','style'=>'padding-left:2px','id'=>"myDashboardComposeEmails",'autocomplete'=>'off'));?>						
						<?php echo form_input(array('name'=>'email[to_name]','type'=>'hidden','id'=>'emailName','required'=>'required','placeholder'=>'Name','class'=>'form-control'));?>						
						<div class="gmail-modal" id="">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close"  onclick="closeSlideBarLeftMessage();"><span aria-hidden="true">&times;</span></button>
									<button type="button" onclick='refreshContacts();' class="btn btn-primary btn-mwidth pull-right mrg20R">Refresh Contacts</button>
									<h4 class="modal-title">Reply Message</h4>
								</div>
								<div class="modal-body clearfix">
									<div id="gmail_message" style="margin-top:-13px;">
										<div class="col-md-12 pull-left mrg5T">
											<div class="form-group input-string-group" style="border: none !important;margin-bottom:0px;">
												<label class="control-label">To:</label>
												<?php echo form_input(array('name'=>'email[to]','id'=>'emailTo','required'=>'required','placeholder'=>'','class'=>'form-control textCC','style'=>'width:80%;','autocomplete'=>'off','value'=>''));?>
											</div>
											<div class="" style="border-top: 1px solid #dfe8f1;margin-top:1px ;margin-left:-17px; margin-right:-17px;"></div>
											<div class="form-group input-string-group mrg5T" style="border: none !important;margin-bottom:0px;">
												<label class="control-label">CC:</label>
												<?php echo form_input(array('name'=>'email[cc]','id'=>'emailCC','placeholder'=>'','class'=>'form-control textCC','style'=>'width:80%;'));?>
											</div>
											<div class="" style="border-top: 1px solid #dfe8f1;margin-top:1px; margin-left:-17px; margin-right:-17px;"></div>
											<div class="form-group input-string-group mrg5T" style="border: none !important;margin-bottom:0px;">
												<label class="control-label">BCC:</label>
												<?php echo form_input(array('name'=>'email[bcc]','id'=>'emailBCC','placeholder'=>'','class'=>'form-control textCC','style'=>'width:80%;'));?>
											</div>
											<div class="" style="border-top: 1px solid #dfe8f1;margin-top:1px; margin-left:-17px; margin-right:-17px;"></div>
											<div class="form-group input-string-group mrg5T" style="border: none !important;margin-bottom:0px;">
												<label class="control-label">Subject:</label>
												<?php echo form_input(array('name'=>'email[subject]','id'=>'emailSubject','required'=>'required','placeholder'=>'','class'=>'form-control','style'=>'width:80%;'));?>
											</div>
											<div class="" style="border-top: 1px solid #dfe8f1;margin-top:1px; margin-left:-17px; margin-right:-17px;"></div>
											<div class="clearfix mrg5T">
												<label class="control-label" style="float:left;">Attachment:</label>
												<div id="attach_droppable" style='min-height:25px; padding:0;margin-left: 75px;width: auto;'></div>
											</div>
											<div class="mrg10T" style="border-top: 1px solid #dfe8f1; margin-left:-17px; margin-right:-17px;"></div>
											<div class="form-group mrg5T">
												<div class="row">													
													<?php 
														$signature = "";
														if(!empty($this->session->userdata['signature'])){
															$signature = $this->session->userdata['signature'];
														}
													?>
													<div class="col-sm-12">
														<?php echo form_textarea(array('name'=>'email[message]','id'=>'emailMessage','required'=>'required','placeholder'=>'Message','class'=>'form-control ckeditor wysiwyg-editor','cols'=>29, 'rows'=>50, 'style'=>'border: none !important; padding:0;height:300px !important;','value'=>"<br/><br/><br/><br/><br/>".$signature));?>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-offset-2 col-sm-10">
												<input type="hidden" name="email[thread_id]" id="emailThreadId"/>
												<input type="hidden" name="email[message_id]" id="emailMessageId"/>
												<input type="hidden" name="email[lead_id]" id="messageLeadId"/>
												<input type="hidden" name="email[from_name]" value="<?php echo $this->session->userdata['name']?>" id="emailFromName"/>
												<input type="hidden" name="email[from_email]" value="<?php echo $this->session->userdata['email']?>" id="emailFromEmail"/>										
												<input type="hidden" name="email[reference]" value="" id="emailReference"/>
												<input type="hidden" name="email[doc_url]" value="" id="emailDocUrl"/>
												<input type="hidden" name="another[sys]" value="0" id="anotherSys"/>
												<input type="hidden" name="another[legal_log]" value="0" id="legal_log"/>					
												<input type="hidden" name="another[legal_patents]" value="0" id="legal_patents"/>
												<input type="hidden" name="another[f_t]" value="" id="f_t"/>
												<input type="hidden" name="event[c_id]" id="eventCid" value="0"/>
												<input type="hidden" name="event[p_id]" id="eventPid" value="0"/>
												<button type="submit" class="btn btn-primary btn-mwidth pull-right">Send</button>
												<a href='javascript://' class="mrg10R pull-right" onclick="discardEmail()" style="color: #222222;font-size: 20px;margin-top: 2px;"><i class="glyph-icon"><img src="<?php echo $Layout->baseUrl?>public/images/discard.png" style="opacity:0.55"/></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php echo form_close();?>					
				</div>
			</div>
		</div>
		
		<div class="modal modal-opened-header fade" id="emailOpenModal" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:50%;left:0px">
			<div class="modal-dialog" style="width:100%;">
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createContactModalLabel">Email Content</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12" id="emailOpenContent" style='max-height:600px;overflow-y:scroll;overflow-x:none;'>
								
							</div>
						</div>
					</div>
                    <div class="modal-footer"></div>					
				</div>
			</div>
		</div>
        <div class="modal modal-opened-header fade" id="contactForm" tabindex="" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:48%;">
			<div class="modal-dialog" style='width:100% !important'> 
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createContactModalLabel">SynPat's Contact</h4>
					</div>
					<div class="modal-body">
						
					</div>                    					
				</div>
			</div>
		</div>
		<script>
			function checkCompanyChange(o){
				jQuery("#companySector").find("option").removeAttr("selected");
				jQuery("#companySector").find("option").removeAttr("SELECTED");
				if(o.val()>0){
					jQuery("#companyCompanyName").val('');
					if(jQuery("#inviteeCompanyId>option:selected").attr('data-b')!=undefined && jQuery("#inviteeCompanyId>option:selected").attr('data-b')>0){
						jQuery("#companySector").val(jQuery("#inviteeCompanyId>option:selected").attr('data-b'));
						checkSector(jQuery("#companySector"));
					}
				}
			}
			function checkCompanyName(o){
				if(o.val()!=""){
					jQuery("#inviteeCompanyId").find("option").removeAttr("selected");
					jQuery("#inviteeCompanyId").find("option").removeAttr("SELECTED");
					jQuery("#companySector").find("option").removeAttr("selected");
					jQuery("#companySector").find("option").removeAttr("SELECTED");
				}
			}
			function checkSector(o){
				jQuery("#preferenceDepartments").multiSelect("destroy"); 
				jQuery("#preferenceDepartments").find("option").remove();
				if(o.val()>0){					
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $Layout->baseUrl?>customers/find_departments',
						data:{s:o.val()},
						cache:false,
						success:function(res){
							if(res!=""){
								_data = jQuery.parseJSON(res);
								if(_data.length>0){
									for(i=0;i<_data.length;i++){
										jQuery("#preferenceDepartments").append("<option value='"+_data[i].id+"'>"+_data[i].name+"</option>");
									}									
									jQuery("#preferenceDepartments").multiSelect();
									jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
								}
							}							
						}
					});
				}
				open_contact_listResize();
			}
		</script>
		<div class="modal modal-opened-header fade sb-right1" id="addContactForm" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:40%;">
			<div class="modal-dialog" style='width:100% !important;height:100% !important;'> 
				<div class="modal-content" style='height:100% !important;'>					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createContactModalLabel" style="width:30%;height:30px;"></h4>
					</div>
					<div class="modal-body">
						<div class="row">
						<div class="col-xs-12">
							<?php echo form_open('opportunity/contact',array('class'=>"form-flat",'id'=>'contactFormSubmit', 'style'=>'margin-left:2px; margin-right:-10px;'));?>
							
							<div class="row">
								
								<div class="col-xs-12">
									<div class="form-group input-string-group">
										<label class="control-label">Job Title:</label>
										<input type="text" name="invitee[job_title]" id="inviteeJobTitle"  class="form-control" placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">First Name:</label>
										<input type="text" name="invitee[first_name]" id="inviteeFirstName" class="form-control" required placeholder=""/>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">Last Name:</label>
										<input type="text" name="invitee[last_name]" id="inviteeLastName" class="form-control" required placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">Work Phone:</label>
										<input type="text" name="invitee[telephone]" id="inviteeTelephone"  class="form-control" placeholder=""/>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">Mobile Phone:</label>
										<input type="text" name="invitee[phone]" id="invitePhone" class="form-control"    placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">E-mail:</label>
										<input type="email" name="invitee[email]" id="inviteeEmail" class="form-control"  placeholder=""/>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">Web address:</label>
										<input type="email" name="invitee[web_address]" id="inviteeWebAddress" class="form-control"  placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group input-string-group">
										<label class="control-label">LinkedIN Profile Url:</label>
										<input type="email" name="invitee[linkedin_url]" id="inviteeLinkedinUrl" class="form-control"  placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">Street:</label>
										<input type="text" name="invitee[street]" id="inviteeStreet" class="form-control"  placeholder=""/>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">City:</label>
										<input type="text" name="invitee[city]" id="inviteeCity" class="form-control"  placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">State/Province:</label>
										<input type="text" name="invitee[state]" id="inviteeState" class="form-control"  placeholder=""/>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group input-string-group">
										<label class="control-label">ZIP/Postal Code:</label>
										<input type="text" name="invitee[zip]" id="inviteeZip" class="form-control"  placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group input-string-group">
										<label class="control-label">Country/Region:</label>
										<input type="text" name="invitee[country]" id="inviteeCountry" class="form-control"  placeholder=""/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group input-string-group">
										<label class="control-label">Note:</label>
										<textarea  name="invitee[note]" id="inviteeNote" class="form-control"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<?php 
										$companies = getAllCompanies();
									?>
									<div class="form-group input-string-group" style="border:0px;">
										<label class="col-sm-12 control-label">Company:</label>
										<select class=""  id="inviteeCompanyId" name="invitee[company_id]" onchange="checkCompanyChange(jQuery(this));" size="50" style="height: 150px !important; width:100% !important overflow-y:Scroll !important;text-align:left;border:solid 1px #dfe8f1;padding:0 5px;">
											<?php 												
												if(count($companies)>0){
													foreach($companies as $company){
											?>
														<option data-b='<?php echo $company->sectorID?>' data-bn='<?php echo $company->sectorName?>' value="<?php echo $company->id;?>"><?php echo $company->company_name;?></option>
											<?php
													}
												}
											?>
										</select> 
									</div>
								</div>
								<div class="col-xs-6">
									<div class="col-xs-12">
										<div class="form-group input-string-group" style=''>
											<label class="control-label">Company Name:</label>
											<input type="text" onkeydown="checkCompanyName(jQuery(this))" name="company[company_name]" id="companyCompanyName" class="form-control"  placeholder=""/>
										</div>
									</div>
									<div class="col-xs-12">
										<div class="form-group input-string-group" style="border:0px;">
											<label class="control-label">Business:</label>								
											<select class="form-control" id="companySector" name="company[sector]" onchange="checkSector(jQuery(this))" size="50" style="height: 142px !important; overflow-y:Scroll !important" >
												<?php 
													$market_sectors = getAllMarketSectors();
													if(count($market_sectors)>0){
														foreach($market_sectors as $sector){
												?>
															<option value="<?php echo $sector->id;?>"><?php echo $sector->name;?></option>
												<?php
														}
													}
												?>											
											</select>  
										</div>
									</div>
								</div>
							</div>
							<div class="row mrg10T">
								<label class="col-sm-12 control-label">Departments:</label>
								<div class="col-sm-12 mrg5T" style="padding-right:11px;">
									<select multiple  class="form-control" id="preferenceDepartments" name="preferences[departments][]">
									</select>                                
								</div>
							</div>
							<div class="mrg5T">   
								<input type="hidden" name="invitee[id]" id="inviteeId" class="form-control"  value=""/>
								<button type="button" onclick="addContact()" id="buttonADDContact" class='btn btn-primary btn-mwidth'>Save</button>
								<img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="" id="addContactTask" style='display:none;'>
							</div>
							<?php echo form_close();?>
						</div>
						</div>
					</div>
                    <div class="modal-footer"></div>					
				</div>
			</div>
		</div>
		<div class="modal modal-opened-header fade" id="sellerContactForm" tabindex="-1" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:48%;">
			<div class="modal-dialog" style='width:100% !important'> 
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createContactModalLabel" style="width:30%;height:30px;"></h4>
					</div>
					<div class="modal-body">
						<iframe id="contactSellerFormIframe" scrolling="no" src="" width="100%" height="650px" style='display:none'></iframe>
					</div>
                    <div class="modal-footer"></div>					
				</div>
			</div>
		</div>	
		<div class="modal modal-opened-header fade" id="marketContactForm" tabindex="-1" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:40%;">
			<div class="modal-dialog" style='width:100% !important'> 
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createContactModalLabel">   <button type="button" class="btn btn-primary mrg10L" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">SELECT</span></button></h4>
					</div>
					<div class="modal-body">
						<iframe id="marketContactFormIframe" src="" width="100%" height="650px" style='display:none'></iframe>
					</div>
                    <div class="modal-footer"></div>					
				</div>
			</div>
		</div>
		<div class="modal modal-opened-header fade" id="openPrePatentF" tabindex="-1" role="dialog" aria-labelledby="openPrePatentFLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="width:27%; top: 0;">
			<div class="modal-dialog" style='width:100% !important'> 
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px;"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id=""></h4>
					</div>
					<div class="modal-body">
						<iframe id="openPrePatentFIframe" src="" width="100%" height="150px"></iframe>
					</div>
				</div>
			</div>
		</div>
		<div id="myEmailsRetrieve" style='display:none; min-height:60px; max-height:350px;overflow:hidden;overflow-y:scroll;' class="pad0A">
		</div>