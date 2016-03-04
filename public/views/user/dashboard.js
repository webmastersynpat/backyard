/* Responsive tabs */
_mainData = "";
	_allMainPatents = [];
	mainAllPatentData = [];
	/*
function initEditor(d){
	if(d=='' || d==undefined){
		$(function() { "use strict";
			$('.wysiwyg-editor').summernote({
				height: 350,
				disableDragAndDrop: true,
				toolbar: [
					['style', ['bold', 'italic', 'underline', 'clear']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['height', ['height']],
				]
			});
		});
	} else {
		$(d).summernote({
			height: 350,
			disableDragAndDrop: true,
			toolbar: [
				['style', ['bold', 'italic', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
			]
		});
	}
	
}*/
_relaseSplit = false;
function splitWindow(d){
	_wWidth = jQuery(window).width();
	switch(d){
		case 1:
			/*Disable Lead*/	
			_relaseSplit = true;
			listBoxWidth = 73;
			_rWidth = _wWidth - listBoxWidth-10;
			_emailLists = (_rWidth*35)/100;
			_lWidth = _rWidth - _emailLists;
			jQuery("#listLabels").css('display','');
			console.log(_emailLists);
			jQuery("#listMainContainerModif").css({display:'',width:_emailLists+'px'});
			_cWidth = listBoxWidth + _emailLists;
			jQuery("#listLabels").parent().parent().css('width',_cWidth+'px');
			jQuery("#displayEmail").css({display:'',width:_lWidth+'px',marginLeft:'0px'});
			jQuery("#old_lead").parent().css('display','none');
		break;
		case 2:
			/*Disable Emails*/
			_relaseSplit = true;
			gWidth = _wWidth-10;
			jQuery("#old_lead").parent().css({display:'',width:gWidth+'px'});
			jQuery("#listMainContainerModif").css('display','none');
			jQuery("#displayEmail").css('display','none');
			jQuery("#listLabels").css('display','none');
		break;
		default:
			/*Enable All and default width to every box*/
			_relaseSplit = false;
			divideScreen();
		break;
	}
}
function divideScreen(){
	_dWidth = jQuery(window).width();
	_cWidth = _dWidth - 83;
	_mWidth  = (_cWidth*25)/100;
	_lWidth = (_cWidth*40)/100;
	_sWidth = (_cWidth*35)/100;
	jQuery("#listMainContainerModif").css({width:_mWidth+'px'});
	jQuery("#old_lead").parent().css({width:_lWidth+'px'});
	jQuery("#displayEmail").css({width:_sWidth+'px'});
}

function resetMenus(){
	jQuery("#header-nav-right").find('li a.menu-active').each(function(){
		if(jQuery.trim(jQuery(this).text())!="Emails"){
			jQuery(this).removeClass('menu-active');
		}
	});
	countActiveMenus();
}
window.voiceMailList= function(data,textStatus,xhr){
	jQuery('.embedVoiceMail').html('');
	if(data!=""){
		_rows = data;
		if(_rows.length>0){
			_list='';
			for(i=0;i<_rows.length;i++){
				_callFrom='';
				_callDate='';
				_messageID = _rows[i].id;
				_callAttachments= _rows[i].attachments;
				_attachments = '';
				_from = ' Number: '+_rows[i].from.phoneNumber;
				_name = (typeof _rows[i].from.name!='undefined')?'Name:'+_rows[i].from.name:'';
				_to = _rows[i].from.phoneNumber;				
				if(_callAttachments.length>0){					
					for(a=0;a<_callAttachments.length;a++){
						fileAttach = '';
						file='';
						if(typeof _callAttachments[a].filename!="undefined" && _callAttachments[a].filename!=""){
							file = _callAttachments[a].filename;
							file = file.replace('/var/www/html/backyard/',__baseUrl);
							fileAttach = '<span><audio controls style="margin-top:7px;float: left;"><source src="'+file+'" type="audio/mpeg">Your browser does not support the audio element.</audio></span>';
						}
						_attachments += '<div class="comment">'+
								'<div class="comment_content">'+
									'<div class="meta">'+
										'<ul class="userinfo" style="width: 60%;float: left;">'+
											'<li class="pull-left" style="border:0px;width: auto !important;"><input type="radio" id="voicemail_list" value="'+_messageID+'" data-phone-number="'+encodeURIComponent(_rows[i].from.phoneNumber)+'" data-file-name="'+file+'" onclick="findAndOpenList(jQuery(this));"/><h6 style="width: auto !important;float: left;">'+_name+_from+'</h6></li>' +
											'<li class="fright" style="border:0px;">Created on '+moment(new Date(_rows[i].creationTime)).format('MMM D, YY h:mm')+'</li>'+
										'</ul>'+fileAttach+
									'</div>'+
								'</div>'+
							'</div>';
					}
				}
				_list +='<li style="border:0px;" class="ms-hover"><div class="viewticket"><div class="databox blue"><div class="comments" id="message-inbox-task-list" style="max-height:260px;overflow:hidden;overflow-y:scroll;overflow-x:none">'+_attachments+'</div></div></div></li>';
			}
			jQuery('.embedVoiceMail').html(_list);
		} else {
			jQuery('.embedVoiceMail').html('<li><p class="alert alert-info">You don\'t have any voicemail.</p></li>');
		}
	} else {
		jQuery('.embedVoiceMail').html('<li><p class="alert alert-info">You don\'t have any voicemail.</p></li>');
	}
	/*jQuery('#task-btn').addClass('menu-active');*/
	openVoiceMail();
	/*a.preventDefault();					
	rowWidth();					
	inputStringFieldsWidth();*/
	checkMyEmailsHeight();
}
function findAndOpenList(o){
	if(typeof o=="object"){
		
	}
}

function voiceMailCalls(){
	_myExt = jQuery("#user_ext").val();
	if(_myExt=="" || _myExt==undefined || _myExt=='undefined'){
		_myExt = '101';
	}
	/*_myExt = '101';*/
	data = {e:_myExt,'case':'voicemail'};
	call(__baseUrl+'vendor/ringcentral/ringcentral-php/demo/ringout.php','GET',data,voiceMailList,'json');	
}
window.voiceMailOpen=function(data,textStatus,xhr){
	if(data!=""){
		data = data.replace('/var/www/html/backyard/',__baseUrl);
		open_drive_files(data,1);
	} else {
		jQuery('#sb-site').prepend('<div class="col-lg-12 alert alert-info noticeInfoAlert mrg5T" style="position:relative;z-index:9999">No file found!.</div>');setTimeout(function(){jQuery('.noticeInfoAlert').remove()},3000);
	}
}
function openVoiceMailMessage(messageID,attachmentID){
	_myExt = jQuery("#user_ext").val();
	if(_myExt=="" || _myExt==undefined || _myExt=='undefined'){
		_myExt = '101';
	}
	_myExt = '101';
	data = {e:_myExt,'case':'voicemail_open',message_id:messageID,attachment_id:attachmentID};
	call(__baseUrl+'vendor/ringcentral/ringcentral-php/demo/ringout.php','GET',data,voiceMailOpen,'text');	
}
function saveTemplateInB(o){
	_d = o.code();
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'users/save_new_template',
		data:{temp:_d,subject:jQuery("#emailSubject").val(),name:new Date().getTime()},
		cache:false,
		success:function(data){
			if(data>0){
				jQuery('#sb-site').prepend('<div class="col-lg-12 alert alert-info noticeInfoAlert mrg5T" style="position:relative;z-index:9999">Templated saved.</div>');setTimeout(function(){jQuery('.noticeInfoAlert').remove()},3000);
			} else {
				jQuery('#sb-site').prepend('<div class="col-lg-12 alert alert-warning noticeInfoAlert mrg5T" style="position:relative;z-index:9999">Error, Please try after sometime.</div>');setTimeout(function(){jQuery('.noticeInfoAlert').remove()},3000);
			}
		}
	});
}
function enableActionRightAgain(d){
	jQuery('body').on('contextmenu','div.staRenewalAction',function(){
		if(leadGlobal>0){
			con = confirm("Are you sure you want to this action again?");
			if(con===true){
				if(jQuery(this).parent().attr('data-item-idd')!=undefined && jQuery(this).parent().attr('data-item-idd')>0){
					jQuery.ajax({
						type:'POST',
						url: __baseUrl + 'leads/findLeadButtonData',
						data:{l:leadGlobal,b:jQuery(this).parent().attr('data-item-idd')},
						cache:false,
						success:function(res){
							if(res!=""){
								respon = jQuery.parseJSON(res);
								if(respon.button_id!=undefined && respon.button_id!=""){
									_stringMode = "";
									if(jQuery("#from_litigation").is(":visible")){
										_stringMode="from_litigation";
									} else if(jQuery("#from_regular").is(":visible")){
										_stringMode="from_regular";
									} else if(jQuery("#from_nonacquistion").is(":visible")){
										_stringMode="from_nonacquistion";
									}
									if(_stringMode!=""){
										switch(respon.button_id){
											case 'DRIVE':
												driveMode(respon.id,_stringMode,respon.reference_id);
											break;
											case 'EMAIL':
												emailMode(respon.id,_stringMode,respon.reference_id);
											break;
											case 'TASK':
												taskMode(respon.id,_stringMode,respon.reference_id);
											break;
											case 'CLAIM_ILLUS':
												claimIllus(_stringMode);
											break;
											case 'TECHNICAL_DD': 
												technicalDD(_stringMode);
											break;
											case 'LEGAL_DD':
												legalDD(_stringMode);
											break;
											case 'PATENT_LIST':
												spreadsheet_box_mode(_stringMode);
											break;
											case 'NDA_TERMSHEET':
												createPartNDATermsheetMode(_stringMode);
											break;
											case 'DRAFT_PPA':
												draft_a_ppa(_stringMode);
											break;
										}
									}
								}
							}
						}
					});
				}
			}
		}
	});
}

jQuery(document).ready(function() { 
	jQuery("#from_litigation").find('.nav-responsive').tabdrop();
	$('body').on('contextmenu', 'a.drive_file_click', function() {
		/*jQuery("#from_litigation").find("#clipboard").removeClass('hide').css('display','inline-block').val(jQuery(this).attr('data-href')).select();
		jQuery("#from_regular").find("#clipboard").removeClass('hide').css('display','inline-block').val(jQuery(this).attr('data-href')).select();
		jQuery("#from_nonacquistion").find("#clipboard").removeClass('hide').css('display','inline-block').val(jQuery(this).attr('data-href')).select();*/
		document.oncontextmenu = new Function("return false");
		window.open(jQuery(this).attr('data-href'),"_BLANK");
		document.oncontextmenu = new Function("return true");
	});
	$('body').on('contextmenu', 'a.renewable', function() {
		if(jQuery(this).parent().parent().attr('data-item-idd')!=undefined && jQuery(this).parent().parent().attr('data-item-idd')>0){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'dashboard/button_renewable',
				data:{b:jQuery(this).parent().parent().attr('data-item-idd'),l:leadGlobal},
				cache:false,
				success:function(data){
					if(data!=""){
						alert(data);
					}
				}
			});
		}
	});
	$('body').on('contextmenu','table.DTFC_Cloned>tbody>tr>td>label>a',function(){
		if(leadGlobal>0){
			con = confirm("Are you sure you want to delete lead?");
			if(con===true){
				document.oncontextmenu = new Function("return false");
				if(leadGlobal>0){
					jQuery.ajax({
						type:'POST',
						url: __baseUrl + 'dashboard/delete_lead',
						data:{b:leadGlobal},
						cache:false,
						success:function(data){
							document.oncontextmenu = new Function("return true");
							if(data>0){
								window.location = __baseUrl;
							} else {
								alert("Server busy please try after sometime.")
							}
						}
					});
				}
			}
		} else {
			alert("Please select lead first.")
		}

	});
	jQuery("#topbar_commentForm").find('textarea,select').change(function(){
		saveComment();
	});
	/*enableActionRightAgain(1);*/
});



window.format=function(b,a){if(!b||isNaN(+a))return a;var a=b.charAt(0)=="-"?-a:+a,j=a<0?a=-a:0,e=b.match(/[^\d\-\+#]/g),h=e&&e[e.length-1]||".",e=e&&e[1]&&e[0]||",",b=b.split(h),a=a.toFixed(b[1]&&b[1].length),a=+a+"",d=b[1]&&b[1].lastIndexOf("0"),c=a.split(".");if(!c[1]||c[1]&&c[1].length<=d)a=(+a).toFixed(d+1);d=b[0].split(e);b[0]=d.join("");var f=b[0]&&b[0].indexOf("0");if(f>-1)for(;c[0].length<b[0].length-f;)c[0]="0"+c[0];else+c[0]==0&&(c[0]="");a=a.split(".");a[0]=c[0];if(c=d[1]&&d[d.length-
1].length){for(var d=a[0],f="",k=d.length%c,g=0,i=d.length;g<i;g++)f+=d.charAt(g),!((g-k+1)%c)&&g<i-c&&(f+=e);a[0]=f}a[1]=b[1]&&a[1]?h+a[1]:"";return(j?"-":"")+a[0]+a[1]};
function RemoveRougeChar(convertString){
    if(convertString.substring(0,1) == ","){
        return convertString.substring(1, convertString.length)
    }
    return convertString;
}
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

mainLogBox = 0;
jQuery('.breadcrumb').html("<li class='active'>Dashboard</li>");
_regString = /^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
jQuery(document).ready(function(){
	jQuery("#from_litigation").find("#litigationLinkToRPX").focus(function(){
		if(jQuery(this).val()!=""){
			if(_regString.test(jQuery(this).val())) {
				window.open(jQuery(this).val(),"_BLANK");
			}
		}
	});
	jQuery("#from_litigation").find("#litigationLinkToPacer").focus(function(){
		if(jQuery(this).val()!=""){
			if(_regString.test(jQuery(this).val())) {
				window.open(jQuery(this).val(),"_BLANK");
			}
		}
	});
	jQuery("#from_litigation").find('.button-list').sortable({
		items: "div.row",
		zIndex: 9999,
		start: function(event, ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
		},
		update: function (event, ui) {
			var newArray = [];
			jQuery("#from_litigation").find('.button-list').find('div.row').each(function(index){
				
				newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
			});
			jQuery("#from_litigation").find('.button-list').find("div.row").each(function(index){
				if(index==0){
					jQuery("#from_litigation").find('.button-list').find("div.row").removeClass("mrg5T");
				} else {
					jQuery("#from_litigation").find('.button-list').find("div.row").addClass("mrg5T");
				}
			});
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/change_order',
				data:{spliting:newArray},
				cache:false,
				success:function(){
					
				}
			});
		},
		stop: function (event,ui){
			var end_pos = ui.item.index();
			
		}
	});
	jQuery("#from_regular").find('.button-list').sortable({
		items: "div.row",
		zIndex: 9999,
		start: function(event, ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
		},
		update: function (event, ui) {			
			var newArray = [];
			jQuery("#from_regular").find('.button-list').find('div.row').each(function(index){				
				newArray.push(jQuery(this).attr('data-item-idd')+"_"+index);
			});
			jQuery("#from_regular").find('.button-list').find("div.row").each(function(index){
				if(index==0){
					jQuery("#from_regular").find('.button-list').find("div.row").removeClass("mrg5T");
				} else {
					jQuery("#from_regular").find('.button-list').find("div.row").addClass("mrg5T");
				}
			});
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/change_order',
				data:{spliting:newArray},
				cache:false,
				success:function(){
					
				}
			});
		},
		stop: function (event,ui){
			var end_pos = ui.item.index();
		}
	});
});

___FLAG = 0;
jQuery(document).ready(function(){
	jQuery('#from_litigation,#from_regular,#from_nonacquistion').find('input').keypress(function(e){
		___FLAG = 1;
	}).change(function(){___FLAG = 1;});
});

_mainData = '';
__caseName  = "";
__caseNumber  = "";
__filed = "";
__closed = "";
__lastDocket = "";
__pacer = "";
__leadAttroney = "";
__caseType="";
__marketSector="";
__court="";
__judge="";
__cause="";
__previously="";
__stage="";
_table = {};
var o = {};
_scrap = "";
_liti ="";
	function resetLitigationForm(){
		jQuery("#formLitigation").get(0).reset();
		jQuery("#litigationleadName").val(leadNameGlobal);
		jQuery("#litigationFileUrl").val(snapGlobal);
	}
	function emptyForm(){
		_skeltonTable = '<div class="col-sm-12 float-left" style="margin-top:5px;width:100%;">'+
											'<div style="width:100%;">'+
											'	<div class="col-sm-12" id="tablesOtherData">'+
									'				<h3 class="title-hero">  '+
									'					<span style="width:345px;display:inline-block;">This Case:</span>Litigation Campaign'+
									'				</h3>'+
									'				<div class="example-box-wrapper">'+
									'					<ul class="nav-responsive nav nav-tabs">'+
									'   					<li class="active"><a href="#tab6" data-toggle="tab">Patents In Suit</a></li>'+
									'						<li><a href="#tab7" data-toggle="tab">Complaint</a></li>'+
									'						<li><a href="#tab8" data-toggle="tab">Pacer</a></li>'+
									'						<li><a href="#tab5" data-toggle="tab">Docket Entries</a></li>'+
									'						<li><a href="#tab1" data-toggle="tab">Cases</a></li>'+
									'						<li><a href="#tab2" data-toggle="tab">Defendants</a></li>'+
									'						<li><a href="#tab3" data-toggle="tab">Patents</a></li>'+
									'						<li><a href="#tab4" data-toggle="tab">Accused Products</a></li>'+
									'					</ul>'+
									'					<div class="tab-content">'+
									'<div class="tab-pane active" id="tab6">'+
									'					<table id="datatable-hide-columns'+leadGlobal+'5"  class="table table-striped table-bordered " cellspacing="0" width="100%">'+
									'						<thead>'+
									'						<tr>'+
									'							<th>Patent #</th>'+
									'							<th>Title</th>'+
									'							<th>Est. Priority Date</th>'+
									'						</tr>'+
									'						</thead>'+
									'						<tbody> 								'+								
									'						</tbody>'+
									'					</table>'+
									'				</div>'+
									'				<div class="tab-pane" id="tab7"></div>'+
									'				<div class="tab-pane" id="tab8"></div>'+
									'						<div class="tab-pane" id="tab1">'+
									'							<table id="datatable-hide-columns'+leadGlobal+'" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
									'								<thead>'+
									'								<tr>'+
									'									<th>Date Filed</th>'+
									'									<th>Case Name</th>'+
									'									<th>Docket Number</th>'+
									'									<th>Termination Date</th>'+
									'								</tr>'+
									'								</thead>'+
									'								<tbody> </tbody>'+
									'							</table>'+
									'						</div>'+
									'						<div class="tab-pane " id="tab2">'+
									'							<table id="datatable-hide-columns'+leadGlobal+'1" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
									'								<thead>'+
									'								<tr>'+
									'									<th>Date Filed</th>'+
									'									<th>Defandants</th>'+
									'									<th>Litigation</th>'+
									'									<th>Termination Date</th>'+
									'								</tr>'+
									'								</thead>'+
									'								<tbody></tbody>'+
									'							</table>'+
									'						</div>'+
									'						<div class="tab-pane" id="tab3">'+
									'							<table id="datatable-hide-columns'+leadGlobal+'2" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
									'								<thead>'+
									'								<tr>'+
									'									<th>Patent #</th>'+
									'									<th>Title</th>'+
									'									<th>Est. Priority Date</th>'+
									'								</tr>'+
									'								</thead>'+
									'								<tbody> </tbody>'+
									'							</table>'+
									'						</div>'+
									'						<div class="tab-pane" id="tab4">'+
									'							<table id="datatable-hide-columns'+leadGlobal+'3" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
									'								<thead>'+
									'								<tr>'+
									'									<th>Date Filed</th>'+
									'									<th>Defandants</th>'+
									'									<th>Accused Products</th>'+
									'								</tr>'+
									'								</thead>'+
									'								<tbody></tbody>'+
									'							</table>'+
									'						</div>	'+
									'						<div class="tab-pane" id="tab5">'+
									'							<table id="datatable-hide-columns'+leadGlobal+'4" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
									'								<thead>'+
									'									<tr>'+
									'										<th>Entry #</th>'+
									'										<th>Date Filed</th>'+
									'										<th>Date Entered</th>'+
									'										<th>Entry Description</th>'+
									'									</tr>'+
									'								</thead>'+
									'								<tbody></tbody>'+
									'							</table>'+
									'						</div>'+
									'					</div>'+
									'				</div>'+
											'	</div>'+
											'</div>'+
										'</div>';							
						jQuery("#show_data").html(_skeltonTable);
		jQuery("#datatable-hide-columns"+leadGlobal).find('tbody').empty();
		_tableLiti = jQuery("#datatable-hide-columns"+leadGlobal).DataTable( {destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});		
		jQuery("#datatable-hide-columns"+leadGlobal+"1").find('tbody').empty();
		_tableLiti1 = jQuery("#datatable-hide-columns"+leadGlobal+"1").DataTable( {destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		jQuery("#datatable-hide-columns"+leadGlobal+"2").find('tbody').empty();
		_tableLiti2 = jQuery("#datatable-hide-columns"+leadGlobal+"2").DataTable( {destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		jQuery("#datatable-hide-columns"+leadGlobal+"3").find('tbody').empty();
		_tableLiti3 = jQuery("#datatable-hide-columns"+leadGlobal+"3").DataTable( {destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		jQuery("#datatable-hide-columns"+leadGlobal+"4").find('tbody').empty();
     	_tableLiti4 = jQuery("#datatable-hide-columns"+leadGlobal+"4").DataTable({destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		jQuery("#datatable-hide-columns"+leadGlobal+"5").find('tbody').empty();
		_tableLiti5 = jQuery("#datatable-hide-columns"+leadGlobal+"5").DataTable({destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		jQuery(function(){jQuery(".tabs-hover").tabs({event:"mouseover"})});tabDropInit();
	}
	function cancelImport(){
		emptyForm();
		$('#loader').hide();
		$('#btnImport').removeAttr('disabled').attr('onclick','importDataFromExternalUrl()');
		$('#btnImport').parent().removeClass('col-xs-8').addClass('col-xs-12');
		jQuery("#cancelImport").hide();
		_text = jQuery('.pager-text').html().toString();
		_text = _text.split('/');
		jQuery('.pager-text').html("0/"+_text[1])
		jQuery("#cancelImport").parent().hide();
	}

	function importDataFromExternalUrl(){
		if($('#lititgationImportURL').val()!=''){
			_scrapURL = $('#lititgationImportURL').val();
			emptyForm();
			$("#cancelImport").show();
			$("#cancelImport").parent().show();
			$("#btnImport").attr('disabled','disabled').removeAttr('onclick');
			$('#btnImport').parent().removeClass('col-xs-12').addClass('col-xs-8');
			$('#lititgationImportURL').val(_scrapURL);
			$("#loader").show();

			var parser = document.createElement('a');
			parser.href = _scrapURL;
			$.ajax({
				crossOrigin: true,
				url: _scrapURL,
				context: {},
				success: function(data) {
					_scrap = data;
					__caseName = jQuery(data).find("#mixpanel_object_name_holder").html();
					__caseNumber = jQuery(data).find('ul.header-info').find('li').eq(0).html();
					__filed = jQuery(data).find('ul.header-info').find('li').eq(1).html();
					if(__filed!=undefined && __filed!=""){
						ff = __filed.split('Filed:');
						__filed = ff[1];
					} else {
						__filed = "";
					}
					
					__closed = jQuery(data).find('ul.header-info').find('li').eq(2).html();
					__lastDocket = jQuery(data).find('ul.header-info').find('li').eq(3).html();
					__pacer = jQuery(data).find('ul.header-info').find('li').eq(4).find('a').attr('href');
					__stage = jQuery(data).find('ul.status').find('li').eq(0).html();
					jQuery(data).find('ul.case-details').find('li').each(function(){
						if(jQuery(this).find('div').html()=="Case Type"){
							__caseType= jQuery(this).find('.red').html();
						} 
						if(jQuery(this).find('div').html()=="Cause"){
							__cause = jQuery(this).html();
							__cause = jQuery.trim(__cause.substr(__cause.indexOf('</div')+6));
						}
						if(jQuery(this).find('div').html()=="Market Sector"){
							__marketSector = jQuery(this).html();
							__marketSector = jQuery.trim(__marketSector.substr(__marketSector.indexOf('</div')+6));
						}
						if(jQuery(this).find('div').html()=="Court"){
							__court = jQuery(this).html();
							__court = jQuery.trim(__court.substr(__court.indexOf('</div')+6));
						}
						if(jQuery(this).find('div').html()=="Judge"){
							__judge = jQuery(this).html();
							__judge = jQuery.trim(__judge.substr(__judge.indexOf('</div')+6));
						}
						if(jQuery(this).find('div').html()=="PREVIOUSLY PRESIDING"){
							__previously = jQuery(this).html();
							__previously = jQuery.trim(__previously.substr(__previously.indexOf('</div')+6));
						}
					});
					
					jQuery(data).find("div#plaintiff_container").find('ul').find('div.counsel-content').find('div.counsel-party').each(function(){
						_mainParent = jQuery(this).html();
						if(jQuery(this).html()!=""){
							_splitElement = jQuery(this).html().split('<br>');
							if(_splitElement.length>0){
								for(i=0;i<_splitElement.length;i++){
									if(_splitElement[i].indexOf('Lead Attorney')>=0){
										__leadAttroney = _mainParent.replace(/<\/?[^>]+(>|$)/g, "")/*.replace(/&nbsp;/g, ' ')*/.replace(/<br.*?>/g, '\n');
									}
								}
							}
						}
						
					});
					 
				}
			}).done(function(){
				_url = parser.protocol+parser.hostname+parser.pathname+"/related_cases";
				$.ajax({
					crossOrigin: true,
					url: _url,
					context: {},
					success: function(data) {
						_i=1;
						_liti = data;
						jQuery(data).find('div.tabs-content').find('div.content').each(function(){
							_table[_i] = [];
							jQuery(this).find('div.table-expand').find('table').find('tbody').find('tr').each(function(){
								data = [];
								jQuery(this).find('td').each(function(){
									data.push(jQuery(this).html());
								});
								_table[_i].push(data);
							});
							_i++;
						});
						o.output={
							"LeadAttorney":__leadAttroney,
							"Tables":_table,
							"casetype":__caseType,
							"data1":__caseNumber,
							"pacer":__pacer,
							"data2":__filed,
							"title":__caseName,
							"market":__marketSector,
							"cause":__cause,
							"court":__court,
							"judge":__judge,
							"previously":__previously,
							"stage":__stage,
							"docket_entries_table":[]
						};
						_mainData = o;						
					}
				});
			});
		}
	}
	
	_initialiseIframe = 0;
	
	function implementLitigationScrap(o){
		_initialiseIframe = 0;
		_outPut = o.output;
		if(jQuery("#litigationScrapperData").length>0){
			jQuery("#litigationScrapperData").val(JSON.stringify(o));
		}		
		_leadAttorney = _outPut.LeadAttorney;
		_leadAttorney = _leadAttorney.replace(/(\r\n|\n|\r)/gm,"");
		_pacer = _outPut.pacer;
		_complaint = _outPut.complaint;
		_caseType = _outPut.casetype;
		_stage = _outPut.stage;
		_caseNumber = _outPut.data1;
		_court = _outPut.court;
		_cause = _outPut.cause;
		_judge = _outPut.judge;
		_market = _outPut.market;
		_stringFiled =  _outPut.data2;
		_title = _outPut.title;
		if(_title!=undefined && _title!=""){
			_pantiffString = _title.split('v.');
			if(_pantiffString.length>0){
				_pantiffString = _pantiffString[0];
			} else {
				_pantiffString = "";
			}
		} else {
			_pantiffString = "";
		}
		jQuery("#litigationLeadAttorney").val(_leadAttorney);
		jQuery("#litigationLinkToPacer").val(_pacer);
		jQuery("#litigationLitigationStage").val(_stage);
		jQuery("#litigationCaseNumber").val(_caseNumber);
		jQuery("#litigationMarketIndustry").val(_market);
		jQuery("#litigationCaseName").val(_title);	
		jQuery("#litigationCaseType").val(_caseType);	
		jQuery("#litigationCause").val(_cause);	
		jQuery("#litigationCourt").val(_court);	
		jQuery("#litigationJudge").val(_judge);	
		jQuery("#litigationPresiding").val(_title);	
		/*jQuery("#litigationleadName").val(_title+' - '+_caseNumber);*/
		jQuery("#litigationFillingDate").val(jQuery.trim(_stringFiled));
		jQuery("#litigationPlantiffsName").val(jQuery.trim(_pantiffString));		
		_tableLiti.destroy();
		_tableLiti1.destroy();
		_tableLiti2.destroy();
		_tableLiti3.destroy();
		_tableLiti4.destroy();
		_tableLiti5.destroy();
		jQuery("#datatable-hide-columns"+leadGlobal+"5").find('tbody').empty();
		jQuery("#datatable-hide-columns"+leadGlobal).find('tbody').empty();
		jQuery("#datatable-hide-columns"+leadGlobal+"1").find('tbody').empty();
		jQuery("#datatable-hide-columns"+leadGlobal+"2").find('tbody').empty();
		jQuery("#datatable-hide-columns"+leadGlobal+"3").find('tbody').empty();
		jQuery("#datatable-hide-columns"+leadGlobal+"4").find('tbody').empty();
		_tables = _outPut.Tables;
		if(_tables[2]!=undefined){
			if(_tables[1].length>0){				
				for(i=0;i<_tables[1].length;i++){
					_patentUs = _tables[1][i][0];
					_title = _tables[1][i][1];
					_terminationDate = _tables[1][i][2];
					jQuery("#datatable-hide-columns"+leadGlobal+"5>tbody").append('<tr><td>'+_patentUs+'</td><td>'+_title+'</td><td>'+_terminationDate+'</td></tr>');
				}
				
			}
			if(_pacer!=""){
				jQuery("#tab8").html('<a href="'+_pacer+'" target="_blank">'+_pacer+'</a>');
			}
			if(_complaint!=""){
				if(_complaint==undefined){
					_complaint = 'about:blank';
				}
				jQuery("#tab7").html('<iframe src="'+_complaint+'" style="width:100%;height:600px;"></iframe>');
			}
			if(_tables[2].length>0){
				jQuery("#datatable-hide-columns"+leadGlobal).find('tbody').empty();
				for(i=0;i<_tables[2].length;i++){
					_dateFiled = _tables[2][i][0];
					_caseName = _tables[2][i][1];
					_docketNumber = _tables[2][i][2];
					_terminationDate = _tables[2][i][3];
					jQuery("#datatable-hide-columns"+leadGlobal+">tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_caseName+'</td><td>'+_docketNumber+'</td><td>'+_terminationDate+'</td></tr>');
				}
			}			
			if(_tables[3].length>0){
				jQuery("#litigationOriginalDefendants").val(_tables[3].length);
				jQuery("#datatable-hide-columns"+leadGlobal+"1").find('tbody').empty();
				_activeDefandants = 0;
				for(i=0;i<_tables[3].length;i++){
					_dateFiled = _tables[3][i][0];
					_defandants = _tables[3][i][1];
					_litigation = _tables[3][i][2];
					_terminationDate = _tables[3][i][3];
					if(_terminationDate==""){
						_activeDefandants++;
					}
					jQuery("#datatable-hide-columns"+leadGlobal+"1>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_litigation+'</td><td>'+_terminationDate+'</td></tr>');
				}
				jQuery("#litigationActiveDefendants").val(_activeDefandants);				
			} else {
				jQuery("#litigationOriginalDefendants").val(0);
				jQuery("#litigationActiveDefendants").val(0);
			}
			
			if(_tables[4].length>0){
				jQuery("#litigationNoOfPatent").val(_tables[4].length);
				jQuery("#datatable-hide-columns"+leadGlobal+"2").find('tbody').empty();
				for(i=0;i<_tables[4].length;i++){
					_patent = _tables[4][i][0];
					_title = _tables[4][i][1];
					_priority_date = _tables[4][i][2];
					jQuery("#datatable-hide-columns"+leadGlobal+"2>tbody").append('<tr><td>'+_patent+'</td><td>'+_title+'</td><td>'+_priority_date+'</td></tr>');
				}
			} else {
				jQuery("#litigationNoOfPatent").val(0);
			}
			
			if(_tables[5].length>0){
				jQuery("#datatable-hide-columns"+leadGlobal+"3").find('tbody').empty();
				for(i=0;i<_tables[5].length;i++){ 
					_dateFiled = _tables[5][i][0];
					_defandants = _tables[5][i][1];
					_accusedProduct = _tables[5][i][2];
					jQuery("#datatable-hide-columns"+leadGlobal+"3>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_accusedProduct+'</td></tr>');
				}				
			}
			if(_outPut.docket_entries_table[1]!=undefined && _outPut.docket_entries_table[1].length>0){
				jQuery("#datatable-hide-columns"+leadGlobal+"4").find('tbody').empty();
				for(i=0;i<_outPut.docket_entries_table[1].length;i++){
					__data = _outPut.docket_entries_table[1][i];
					_entry = __data[0];
					_dateFiled = __data[1];
					_dateEntered =__data[2];
					_entryDescription =__data[3];
					jQuery("#datatable-hide-columns"+leadGlobal+"4>tbody").append('<tr><td>'+_entry+'</td><td>'+_dateFiled+'</td><td>'+_dateEntered+'</td><td>'+_entryDescription+'</td></tr>');
				}
			}
		}
		_tableLiti5=jQuery("#datatable-hide-columns"+leadGlobal+"5").DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti=jQuery("#datatable-hide-columns"+leadGlobal).DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti1=jQuery("#datatable-hide-columns"+leadGlobal+"1").DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti2=jQuery("#datatable-hide-columns"+leadGlobal+"2").DataTable({"scrollY":"200px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti3=jQuery("#datatable-hide-columns"+leadGlobal+"3").DataTable({"scrollY":"200px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti4=jQuery("#datatable-hide-columns"+leadGlobal+"4").DataTable({"scrollY":"300px","scrollCollapse": true,destroy:true,paging:false,searching:true,language:{emptyTable:"No record found!"}});		
		/*
		$('#btnImport').removeAttr('disabled').attr('onclick','importDataFromExternalUrl()');
		$('#btnImport').parent().removeClass('col-xs-8').addClass('col-xs-12');*/
		jQuery("#cancelImport").hide();
		jQuery("#cancelImport").parent().hide();
		showDataClickPrevent();
	}
	
	function reinitTabData(){
		_tableLiti.destroy();
		_tableLiti1.destroy();
		_tableLiti2.destroy();
		_tableLiti3.destroy();
		_tableLiti4.destroy();
		_tableLiti5.destroy();
		_tableLiti5=jQuery("#datatable-hide-columns"+leadGlobal+"5").DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti=jQuery("#datatable-hide-columns"+leadGlobal).DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti1=jQuery("#datatable-hide-columns"+leadGlobal+"1").DataTable({"scrollY":"100px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti2=jQuery("#datatable-hide-columns"+leadGlobal+"2").DataTable({"scrollY":"200px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti3=jQuery("#datatable-hide-columns"+leadGlobal+"3").DataTable({"scrollY":"200px","scrollCollapse": true,destroy:true,paging:false,searching:false,language:{emptyTable:"No record found!"}});
		_tableLiti4=jQuery("#datatable-hide-columns"+leadGlobal+"4").DataTable({"scrollY":"300px","scrollCollapse": true,destroy:true,paging:false,searching:true,language:{emptyTable:"No record found!"}});
		if(_initialiseIframe==0){
			$('#show_data').find('iframe').css('width','100%');
			$('#show_data').find('iframe').attr( 'src', function ( i, val ) { return val; });
			_initialiseIframe = 1;
		}
	}
	function getPreLeadDetails(){
		if(leadGlobal!=0){
			_serialNumber= 0;
			if(jQuery("#from_regular").is(':visible')){
				_serialNumber= jQuery("#from_regular").find("#serialNumber").text();
			}
			if(jQuery("#from_nonacquistion").is(':visible')){
				_serialNumber= jQuery("#from_nonacquistion").find("#serialNumber").text();
			}
			if(jQuery("#from_litigation").is(':visible')){
				_serialNumber= jQuery("#from_litigation").find("#serialNumber").text();
			}
			if(_serialNumber!=""){
				if(!jQuery("#open_all_list").hasClass('is-open')) {
					$('body').append('<div class="modal-backdrop modal-backdrop-drive"></div>');
					openSlidebar(jQuery("#open_all_list"));slidebarOpenCallback(jQuery("#open_all_list"));
					jQuery("#open_list").html('<iframe id="allListIframe" src="'+__baseUrl+'opportunity/all_list?alx='+_serialNumber+'&plx='+leadGlobal+'" width="100%" height="100px" scrolling="yes"></iframe>');
					jQuery("#open_all_list").addClass('is-open');
					open_all_listResize();
				} else {
					jQuery("#open_list").html('<iframe id="allListIframe" src="'+__baseUrl+'opportunity/all_list?alx='+_serialNumber+'&plx='+leadGlobal+'" width="100%" height="100px" scrolling="yes"></iframe>');
				}
			}
		}
	}
	
	function enableTask(o){
		if(o.parent().parent().parent().parent().parent().attr('data-task') =="0"){
			o.parent().parent().parent().parent().parent().attr('data-task','1')
			o.find('i').removeClass('icon-plus').addClass('icon-check');
		} else {
			o.parent().parent().parent().parent().parent().attr('data-task','0')
			o.find('i').removeClass('icon-plus').addClass('icon-plus');
		}
		
	}

	function getListPrePatent(object){
		if(leadGlobal!=0){
			_top = object.offset().top;
			_top = 100;
			_left = object.offset().left;
			jQuery("#openPrePatentFIframe").attr("src",__baseUrl  + 'dashboard/findLeadPrePatent/'+leadGlobal);
			jQuery("#openPrePatentF").css('top',_top+'px').css('left',_left+'px').modal("show");
		}
	}

	function getCustomerListSalesActivity(){
		if(leadGlobal>0){
			open_sales_list(jQuery("#activityMainType").val());
		} else{
			alert("Please a lead first.");
		}
	}

	function sendEmailAddTask(){
		window.parent.sendTask = 1;
		jQuery("#myDashboardComposeEmails").get(0).submit();
	}
	
	function displayPatentTable(container){		
		jQuery("#salesActivityButton").removeClass('menu-active');
		jQuery("#acquisitionActivityButton").removeClass('menu-active');
		jQuery("#preSaleActivityButton").removeClass('menu-active');
		if(jQuery("#patent_data").hasClass("hide")){
			jQuery("#btnPatentsAll").addClass('menu-active');
			jQuery("#patent_data").removeClass("hide").addClass("show");
		} else {
			jQuery("#btnPatentsAll").removeClass('menu-active');			
			jQuery("#patent_data").removeClass("show").addClass("hide");
		}
		checkMyEmailsHeight();
	}
	function displayLitigationCampaign(container){
		if(jQuery("#show_data").hasClass("hide")){
			jQuery('#displayLitigationCampaign').addClass('menu-active');
			jQuery("#show_data").removeClass("hide").addClass("show");
			reinitTabData();
		} else {
			jQuery('#displayLitigationCampaign').removeClass('menu-active');
			jQuery("#show_data").removeClass("show").addClass("hide");
		} 
		checkMyEmailsHeight();
	}

	function displayAquisitionActivityTable(container,o){	
		jQuery('.actBtn').removeClass('active');
		jQuery("#salesActivityButton").removeClass('menu-active');
		jQuery("#btnPatentsAll").removeClass('menu-active');
		jQuery("#patent_data").removeClass("show").addClass("hide");
		if(jQuery("#preSaleActivityButton1").length>0){
			jQuery("#preSaleActivityButton1").removeClass('menu-active');
			jQuery("#preSaleActivityButton").removeClass('menu-active');
		}
		o.addClass('active');
		if(jQuery("#acquisitionActivityButton").hasClass('menu-active')){
			jQuery("#acquisitionActivityButton").removeClass('menu-active');
		} else {
			jQuery("#acquisitionActivityButton").addClass('menu-active');				
		}
		if(jQuery("#sales_acititity").hasClass("hide")){
			jQuery("#btnActivityAll").text("Manage Sellers");
			jQuery("#activityMainType").val(2);
			jQuery("#sales_acititity").removeClass("hide").addClass("show");
			jQuery("#aquisitionTable").removeClass("hide").addClass("show");
			jQuery("#activityTable").removeClass("show").addClass("hide");
			jQuery("#preSaleActivityTable").removeClass("show").addClass("hide");
		} else {
			if(jQuery("#activityMainType").val()==2){
				jQuery("#sales_acititity").removeClass("show").addClass("hide");
				jQuery("#activityMainType").val(0);
			} else {
				jQuery("#activityMainType").val(2);
				jQuery("#btnActivityAll").text("Manage Sellers");
				jQuery("#sales_acititity").removeClass("hide").addClass("show");
				jQuery("#aquisitionTable").removeClass("hide").addClass("show");
				jQuery("#activityTable").removeClass("show").addClass("hide");
				jQuery("#preSaleActivityTable").removeClass("show").addClass("hide");
			}			
		}
		checkMyEmailsHeight();
		toggleCompanySales();
	}
	
	function displaySaleActivityTable(container,o){			
		jQuery('.actBtn').removeClass('active');
		o.addClass('active');
		if(jQuery("#acquisitionActivityButton").length>0){
			jQuery("#acquisitionActivityButton").removeClass('menu-active');
		}
		if(jQuery("#preSaleActivityButton1").length>0){
			jQuery("#preSaleActivityButton1").removeClass('menu-active');
			jQuery("#preSaleActivityButton").removeClass('menu-active');
		}
		if(jQuery("#btnPatentsAll").length>0){
			jQuery("#btnPatentsAll").removeClass('menu-active');
		}
		jQuery("#patent_data").removeClass("show").addClass("hide");
		if(jQuery("#salesActivityButton").hasClass('menu-active')){					
			jQuery("#salesActivityButton").removeClass('menu-active');
		} else {
			jQuery("#salesActivityButton").addClass('menu-active');			
		}		
		jQuery("#preSaleActivityButton").html('PreSale');
		if(jQuery("#sales_acititity").hasClass("hide")){
			jQuery("#btnActivityAll").text("Manage Customers");
			jQuery("#activityMainType").val(1);
			jQuery("#sales_acititity").removeClass("hide").addClass("show");
			jQuery("#activityTable").removeClass("hide").addClass("show");
			jQuery("#aquisitionTable").removeClass("show").addClass("hide");
			jQuery("#preSaleActivityTable").removeClass("show").addClass("hide");
		} else {
			if(jQuery("#activityMainType").val()==1){
				jQuery("#activityMainType").val(0);
				jQuery("#sales_acititity").removeClass("show").addClass("hide");
			} else {
				jQuery("#btnActivityAll").text("Manage Customers");
				jQuery("#activityMainType").val(1);
				jQuery("#sales_acititity").removeClass("hide").addClass("show");
				jQuery("#activityTable").removeClass("hide").addClass("show");
				jQuery("#aquisitionTable").removeClass("show").addClass("hide");
				jQuery("#preSaleActivityTable").removeClass("show").addClass("hide");
			}
		}		
		checkMyEmailsHeight();
		toggleCompanySales();
		runFixedTableLayoutProccess(1);
	}
	
	function displayPreSaleActivityTable(container,o){
		jQuery('.actBtn').removeClass('active');
		o.addClass('active');
		if(jQuery("#acquisitionActivityButton").length>0){
			jQuery("#acquisitionActivityButton").removeClass('menu-active');
		}
		if(jQuery("#btnPatentsAll").length>0){
			jQuery("#btnPatentsAll").removeClass('menu-active');
		}
		if(jQuery("#salesActivityButton").length>0){
			jQuery("#salesActivityButton").removeClass('menu-active');
		}
		jQuery("#patent_data").removeClass("show").addClass("hide");
		if(jQuery("#preSaleActivityButton").hasClass('menu-active')){					
			jQuery("#preSaleActivityButton").removeClass('menu-active');	
			jQuery("#preSaleActivityButton1").removeClass('menu-active');
			jQuery("#salesActivityButton").addClass('menu-active');
			jQuery("#preSaleActivityButton").html('PreSale');
		} else {
			jQuery("#preSaleActivityButton").addClass('menu-active');	
			jQuery("#preSaleActivityButton1").addClass('menu-active');	
			jQuery("#preSaleActivityButton").html('Back');
		}		
		if(jQuery("#sales_acititity").hasClass("hide")){
			jQuery("#btnActivityAll").text("Manage Customers");
			jQuery("#activityMainType").val(3);
			jQuery("#sales_acititity").removeClass("hide").addClass("show");
			jQuery("#preSaleActivityTable").removeClass("hide").addClass("show");
			jQuery("#activityTable").removeClass("show").addClass("hide");
			jQuery("#aquisitionTable").removeClass("show").addClass("hide");
		} else {
			if(jQuery("#activityMainType").val()=="3"){
				jQuery("#preSaleActivityTable").removeClass("show").addClass("hide");
				jQuery("#activityTable").removeClass("hide").addClass("show");
				jQuery("#activityMainType").val(1);
			} else {
				jQuery("#btnActivityAll").text("Manage Customers");
				
				jQuery("#activityMainType").val(3);
				jQuery("#sales_acititity").removeClass("hide").addClass("show");
				jQuery("#preSaleActivityTable").removeClass("hide").addClass("show");
				jQuery("#activityTable").removeClass("show").addClass("hide");
				jQuery("#aquisitionTable").removeClass("show").addClass("hide");
			}
		}		
		checkMyEmailsHeight();
		toggleCompanySales();
	}

	function driveMode(bID,container,ref,sendTask){ 
		if(leadGlobal!=0 && bID!=undefined && container!="" && ref!=""){
			jQuery("#"+container).find("#loader_"+bID).removeClass("hide").addClass("show");
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'dashboard/drive_mode',
				data:{b:bID,c:container,r:ref,l:leadGlobal},
				cache:false,
				success:function(res){
					jQuery("#"+container).find("#loader_"+bID).removeClass("show").addClass("hide");
					if(res!=""){
						_d = jQuery.parseJSON(res);
						_ss = jQuery("#"+container).find("#drive_button"+bID).find("a").attr('data-status');
						if(_d.status!=""){
							if(sendTask==0){
								jQuery("#"+container).find("#drive_button"+bID).html('<span class="date-style">' + moment( new Date(_d.status)).format('MM-D-YY')+"</span> "+_ss);
								threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
							} else {
								type=jQuery("#"+container).find("#drive_button"+bID).find('a').html();type = type.replace('Create','');jQuery("#formTask").get(0).reset();jQuery("#taskDocUrl").val(_d.url);jQuery("#taskRecord").val(1);if(type==''){type='Document';}jQuery('#otherSignature').val(jQuery('#original_signature').val());jQuery("#taskType").val(type);jQuery("#taskLeadId").val(leadGlobal);openTaskModal();threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
							}
						} else{
							alert("Please try after sometime.");
						}
					}
				}
			});
		}
	}

	function fillPatentSheetListMode(fID){
		if(fID!=""){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/searchAllPatentFiles',
				data:{f:fID},
				cache:false,
				success:function(data){
					if(data!=""){
						_data = jQuery.parseJSON(data);
						if(_data.length>0){
							parentElement = "";
							_mainButtonParentElement = "";
							/*if(jQuery("#from_litigation").is(":visible")){
								parentElement = "litigationSpreadsheetId";
								_mainButtonParentElement = "from_litigation";
							} else if(jQuery("#from_regular").is(":visible")){
								parentElement = "marketSpreadsheetId";
								_mainButtonParentElement = "from_regular";
							} else if(jQuery("#from_nonacquistion").is(":visible")){
								parentElement = "acquisitionSpreadsheetId";
								_mainButtonParentElement = "from_nonacquistion";
							}*/
							parentElement = "patentSpreadsheetId";
							if(parentElement!=""){
								jQuery("#"+parentElement).find("option").remove();
								jQuery("#"+parentElement).append("<option value=''>-- Select SpreadSheet --</option>");
								for(i=0;i<_data.length;i++){
									_selected = "";
									if(snapGlobalFileID!='' && _data[i].id==snapGlobalFileID){
										_selected='SELECTED="SELECTED"';
									}
									jQuery("#"+parentElement).append("<option value='"+_data[i].id+"' "+_selected+">"+_data[i].title+"</option>");
								}
								
								/*findWorksheetMode(jQuery("#"+_mainButtonParentElement).find("#"+parentElement),snapGlobalFileWorkID,_mainButtonParentElement);*/
								findWorksheetMode(jQuery("#"+parentElement),snapGlobalFileWorkID,'');
							}
						}
					}
				}
			});
		}
	}

	_editable =false;
	function initContainer(container){
		jQuery("#scrap_patent_data").find("#Container_Edittable").keydown(function(e){
			var keycode = e.charCode || e.keyCode;
			if (keycode  == 9) { 
				return false;
			}
		});
	}

	function backSwitchPatentFrom(parentElement){
		jQuery("#scrap_patent_data").find('.clickakble').dblclick(function(){
			switchToEditMode(jQuery(this),parentElement);
			initContainer("#"+parentElement);
		});
	}
	_editable =false;

	function switchToEditMode(object,parentElement){
		_editable=false;
		switchBackMode(parentElement);
		if(object.attr('id')!='Container_Edittable'  && jQuery("*:focus").attr('id')!="Container_Edittable"){
			_editable=true;
			_html = object.html();
			object.html("<input type='text' class='form-control' tabindex='-1' id='Container_Edittable' style='width:400px;'/>");
			object.find('input').val(_html).focus().click(function(){
				_editable=true;
			});
			backClickMode(parentElement);
		}
	}

	function backClickMode(parentElement){
		jQuery("#scrap_patent_data").click(function(event){
			if(jQuery(this).attr('id')!="Container_Edittable"){
				_editable=false;
				switchBackMode(parentElement);
			} else {
				_editable=true;
			}
		});
	}

	function switchBackMode(parentElement){
		if(_editable==false && jQuery("*:focus").attr('id')!="Container_Edittable"){
			jQuery("#scrap_patent_data").find('.clickakble').each(function(){
				if(jQuery(this).find('#Container_Edittable').length>0){
					_val = jQuery(this).find('#Container_Edittable').val();
					jQuery(this).html(_val);
					jQuery(this).find('#Container_Edittable').remove();
				}
				jQuery("#scrap_patent_data").unbind("click");
			});
		}
	}

	function refreshHSTTable(parentElement){
		jQuery("#scrap_patent_data").find('tbody').empty();
	}

	function findPatentFromSheetForm(parentElement,d){
		mainAllPatentData=[];
		if(d!=undefined && d==1 && parentElement!='undefined'){
			switch(parentElement){
				case 'from_regular':
					snapGlobal = jQuery("#marketFileUrl").val();
					if(jQuery("#marketWorksheetId").val()!="" && jQuery("#marketWorksheetId>option:selected").attr('data-href')!=undefined && jQuery("#marketWorksheetId>option:selected").attr('data-href')!=""){
						snapGlobal = jQuery("#marketWorksheetId>option:selected").attr('data-href');
					}
				break;
				case 'from_litigation':
					snapGlobal = jQuery("#litigationFileUrl").val();
					if(jQuery("#litigationWorksheetId").val()!=""&& jQuery("#litigationWorksheetId>option:selected").attr('data-href')!=undefined && jQuery("#litigationWorksheetId>option:selected").attr('data-href')!=""){
						snapGlobal = jQuery("#litigationWorksheetId>option:selected").attr('data-href');
					}
				break;
				case 'from_nonacquistion':
					snapGlobal = jQuery("#acquisitionFileUrl").val();
					if(jQuery("#acquisitionWorksheetId").val()!=""&& jQuery("#acquisitionWorksheetId>option:selected").attr('data-href')!=undefined && jQuery("#acquisitionWorksheetId>option:selected").attr('data-href')!=""){
						snapGlobal = jQuery("#acquisitionWorksheetId>option:selected").attr('data-href');
					}
				break;
			}
		}	
		if(snapGlobal!="" || snapGlobalFileID!=""){
			if(parentElement!='undefined'){
				jQuery("#"+parentElement).find('#loadingLink').addClass('overflow-link');
			}			
			jQuery("#loadingLabel").html('<i style="color: rgb(34, 34, 34); position: static;" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A"></i>');
			jQuery.ajax({
				url: __baseUrl + 'leads/findPatentsAll',
				type:'POST',
				data:{file_url:snapGlobal,file_id:snapGlobalFileID},
				cache:false,
				success:function(data){
					_data= jQuery.parseJSON(data);
					if(_data.length>0){
						_allMainPatents = _data;
						runPatentScraping(0,parentElement);
					}
				}
			});
		}
	}

	function deleteDrive(d){
		if(d!=""){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/delete_drive',
				data:{d:d},
				cache:false,
				success:function(res){
					if(res>0){
						threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
					} else {
						alert("Server busy, Try after sometime.");
					}
				}
			});
		}
	}
	function taskMode(bID,container,ref){
		if(leadGlobal!=0 && bID!=undefined && container!="" && ref!=""){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'dashboard/task_mode',
				data:{b:bID,c:container,r:ref,l:leadGlobal},
				cache:false,
				success:function(res){
					if(res!=""){
						_d = jQuery.parseJSON(res);
						_ss = jQuery("#"+container).find("#task_button"+bID).find("a").attr('data-status');
						if(_d.status!=""){
							jQuery("#taskMessage").val(ref);
							jQuery("#taskLeadId").val(leadGlobal);
							openTaskModal();
							jQuery("#"+container).find("#task_button"+bID).html('<span class="date-style">' + moment( new Date(_d.status)).format('MM-D-YY')+"</span> "+_ss);
						}
					}
				}
			});
		}
	}

	function emailMode(bID,container,ref){
		if(leadGlobal!=0 && bID!=undefined && container!="" && ref!=""){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'dashboard/email_mode',
				data:{b:bID,c:container,r:ref,l:leadGlobal},
				cache:false,
				success:function(res){
					if(res!=""){
						_d = jQuery.parseJSON(res);
						_ss = jQuery("#"+container).find("#email_button"+bID).find("a").attr('data-status');
						if(_d.status!=""){
							_str = jQuery("#emailMessage").val();
							jQuery("#emailMessage").destroy();
							jQuery("#emailMessage").val(_d.detail.template_html+_str);
							jQuery("#emailSubject").val(_d.detail.subject);
							jQuery("#messageLeadId").val(leadGlobal);
							jQuery("#emailThreadId").val("");
							jQuery("#emailMessageId").val("");
							jQuery("#emailDocUrl").val("");
							initEditor();
							composeEmail();
							jQuery("#"+container).find("#email_button"+bID).html('<span class="date-style">' + moment( new Date(_d.status)).format('MM-D-YY')+"</span> "+_ss);
						}
					}
				}
			});
		}
	}

	function submitData(){
		jQuery('#from_litigation').find("#loading_spinner_form_litigation").show();
		if(dataValidate()){
			jQuery.ajax({
				url: __baseUrl + 'dashboard/litigation',
				type:'POST',
				data:jQuery("#formLitigation").serialize(),
				cache:false,
				success:function(data){
					jQuery('#from_litigation').find("#loading_spinner_form_litigation").hide();
					if(data==""){
						jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').html(jQuery("#marketlead_name").val());
					} else {
						alert(data);
					}
				}
			});
		} else {
			jQuery('#from_litigation').find("#loading_spinner_form_litigation").hide();
		}
	}

	function submitDataMarket(){
		jQuery('#from_regular').find("#loading_spinner_form_market").show();
		if(dataValidateMarket()){
			jQuery.ajax({
				url: __baseUrl + 'dashboard/market',
				type:'POST',
				data:jQuery("#marketLead").serialize(),
				cache:false,
				success:function(data){
					jQuery('#from_regular').find("#loading_spinner_form_market").hide();
					if(data==""){
						if(jQuery("#marketlead_name").val()!=jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').text()){
							jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').html(jQuery("#marketlead_name").val());
							jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').attr('title',jQuery("#marketlead_name").val());
						}
					} else {
						alert(data);
					}
				}
			});
		} else {
			jQuery('#from_regular').find("#loading_spinner_form_market").hide();
		}
	}

	function getNewDataFromPopup(){	
		jQuery("#leadBtnSave").removeAttr('onclick');
		if(jQuery("#popupLeadname").val()!="" && jQuery("#popupType").val()!=""){
			jQuery("#loader_new_lead").removeClass("demo-icon").css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'dashboard/create_lead',
				data:{n:jQuery("#popupLeadname").val(),t:jQuery("#popupType").val()},
				cache:false,
				success:function(data){
					jQuery("#loader_new_lead").css('display','none');
					jQuery("#leadBtnSave").attr('onclick','getNewDataFromPopup()');
					if(parseInt(data)>0){
						/*Refresh Table of Leads and Selecting Leads*/
						window.location = __baseUrl+'dashboard/index/'+data;
					} else if(data=='-1'){
						alert('Lead name already exist');
					} else {
						alert("Server busy, Please refresh page and try again.");
					}
				}
			});
		} else if(jQuery("#popupLeadname").val()==""){
			jQuery("#popupLeadname").css('border','1px solid #ff0000');
		}  else if(jQuery("#popupType").val()==""){
			jQuery("#popupLeadname").css('border','0px');
			jQuery("#popupType").css('border','1px solid #ff0000');
		}
	}
	function taskMessageBox(_data){
		var refreshParent = $("#sb-site");
		var loaderTheme = "bg-default";
		var loaderOpacity = "60";
		var loaderStyle = "dark";
		var loader = '<div id="refresh-overlay" class="ui-front loader ui-widget-overlay ' + loaderTheme + ' opacity-' + loaderOpacity + '"><img src="public/images/spinner/loader-' + loaderStyle + '.gif" alt="" /></div>';
		if ( $('#refresh-overlay').length ) {
			$('#refresh-overlay').remove();
		}
		__mainEntityFollow = _data.lead_id;
		$(refreshParent).append(loader);
		$('#refresh-overlay').fadeIn('fast');
		jQuery("#taskUserId").val(_data.user_id);
		jQuery("#taskSubject").val(_data.subject);
		jQuery("#taskMessage").val(_data.message);
		jQuery("#taskExecutionDate").val(_data.execution_date);
		jQuery("#taskDocUrl").val(_data.doc_url);
		jQuery("#taskParentId").val(_data.parent_id);
		jQuery("#taskFromUserId").val(_data.from_user_id);
		jQuery("#taskLeadId").val(_data.lead_id);
		jQuery("#taskType").val(_data.type);
		jQuery("#taskId").val(_data.task_id);
		jQuery('#createTaskModal1').modal('show');
		jQuery('#createTaskModal1').on('hidden.bs.modal',function(){
			$("#refresh-overlay").css('display','none');
			$('#refresh-overlay').fadeOut("fast");
		});
		jQuery('#createTaskModal1').on('hidden',function(){
			$("#refresh-overlay").css('display','none');
			$('#refresh-overlay').fadeOut("fast");
		});
	}
	function executeNDA(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Refresh your page.");
		} else {
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/executeNDA',
				data:{token:leadGlobal},
				cache:false,
				success:function(res){
					_data = jQuery.parseJSON(res);
					if(parseInt(_data.send)>0){
						_statusMessage = "Waiting for Admin to execute NDA";
						if(_data.button_data.status_message!=undefined && _data.button_data.status_message!=""){
							_statusMessage = _data.button_data.status_message;
						}
						jQuery("#"+type).find("#execute_nda").html('<span class="date-style">' + moment( new Date(_data.date_update)).format('MM-D-YY')+"</span> "+_statusMessage);
					} else {
						alert(_data.send);
					}
				}
			});
		}
	}
	function approvedLead(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Refresh your page.");
		} else {
			jQuery("#loader_approved_lead").css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/change_status_lead',
				data:{token:leadGlobal},
				cache:false,
				success:function(response){
					jQuery("#loader_approved_lead").css('display','none');
					_data = jQuery.parseJSON(response);
					if(_data.rows>0){
						_statusMessage = "Synpat like the deal";
						if(_data.button_data.status_message!=undefined && _data.button_data.status_message!=""){
							_statusMessage = _data.button_data.status_message;
						}
						jQuery("#"+type).find('#approved_lead').html('<span class="date-style">' + moment( new Date(_data.date_update)).format('MM-D-YY')+"</span> "+_statusMessage);
						jQuery("#all_type_list").find('tr.active').find('td').eq(4).html(_data.date_update);
					} else {
						alert("Server busy. Refresh your page.");
					}
				}
			});
		}
	}

	function sellerInterested(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Refresh your page.");
		} else {
			jQuery("#"+type).find("#loader_seller_market").css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/sellerInterested',
				data:{token:leadGlobal},
				cache:false,
				success:function(response){
					jQuery("#"+type).find("#loader_seller_market").css('display','none');
					_data = jQuery.parseJSON(response);
					if(_data.rows>0){
						_statusMessage = "Seller like the deal";
						if(_data.button_data.status_message!=undefined && _data.button_data.status_message!=""){
							_statusMessage = _data.button_data.status_message;
						}
						jQuery("#"+type).find('#seller_deal_for_market').html('<span class="date-style">' + moment( new Date(_data.date_update)).format('MM-D-YY')+"</span> "+_statusMessage);
						jQuery("#all_type_list").find('tr.active').find('td').eq(3).html(_data.date_update);
					} else {
						alert("Server busy. Refresh your page.");
					}
				}
			});
		}
	}

	function fundingTransfer(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Refresh your page.");
		} else {
			jQuery("#"+type).find("#loader_funding_market").css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/fundsTransfer',
				data:{token:leadGlobal},
				cache:false,
				success:function(response){
					jQuery("#"+type).find("#loader_funding_market").css('display','none');
					_data = jQuery.parseJSON(response);
					if(_data.rows>0){
						_statusMessage = "Funds Transfer Successful";
						if(_data.button_data.status_message!=undefined && _data.button_data.status_message!=""){
							_statusMessage = _data.button_data.status_message;
						}
						jQuery("#"+type).find('#funding_successful').html('<span class="date-style">' + moment( new Date(_data.date_update)).format('MM-D-YY')+"</span> "+_statusMessage);
						jQuery("#all_type_list").find('tr.active').find('td').eq(6).html(_data.date_update);
					} else {
						alert("Server busy. Refresh your page.");
					}
				}
			});
		}
	}
	function ndaExecuted(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Refresh your page.");
		} else {
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/ndaExecuted',
				data:{token:leadGlobal},
				cache:false,
				success:function(res){
					_data = jQuery.parseJSON(res);
					if(parseInt(_data.send)>0){
						taskMessageBox(_data);
					} else {
						alert("Server busy. Refresh your page.");
					}
				}
			});
		}
	}
	function eouConfirmation(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Please refresh your page.");
		} else {
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/eouConfirmation',
				data:{token:leadGlobal},
				cache:false,
				success:function(res){
					_data = jQuery.parseJSON(res);
					if(parseInt(_data.send)>0){
						jQuery("#"+type).find("#seller_rou").html("<span class='date-style'>"+_data.created+"</span>Seller EOU is in the Lead folder");
					} else {
						alert("Server busy. Refresh your page.");
					}
				}
			});
		}
	}
	function draft_a_ppa(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Please refresh your page.");
		} else {
			jQuery("#"+type).find("#loader_ppa").css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/draft_a_ppa',
				data:{token:leadGlobal},
				cache:false,
				statusCode: {
					404: function() {
					  threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
					},
					504: function() {
					  threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
					}
				  },
				success:function(res){
					jQuery("#"+type).find("#loader_ppa").css('display','none');
					jQuery("#"+type).find("#draft_a_ppa").html('<p class="label-after-btn"><i class="glyph-icon icon-caret-right"></i>PPA has been successfully drafted</p>');
					_data  = jQuery.parseJSON(res);
					if(_data.url!=""){
						/*window.open(_data.url,"_BLANK");*/
						threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
						open_drive_files(_data.url);
					} else {
						alert("Server busy. Please refresh your page.");
					}
				}
			});
		}
	}
	function execute_ppa(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Please refresh your page.");
		} else {
			jQuery("#"+type).find('#spinner-loader-execute_ppa').css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/execute_ppa',
				data:{token:leadGlobal},
				cache:false,
				success:function(res){
					jQuery("#"+type).find('#spinner-loader-execute_ppa').css('display','none');
					_data  = jQuery.parseJSON(res);
					if(_data.send>0){
						threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
						/*taskMessageBox(_data);*/
					} else {
						alert("Server busy. Please refresh your page.");
					}
				}
			});
		}
	}
	function ppaExecuted(type){
		if(leadGlobal==0 || leadGlobal==""){
			alert("Server busy. Please refresh your page.");
		} else {
			jQuery("#"+type).find('#spinner-loader-ppa_executed').css('display','inline-block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'opportunity/ppaExecuted',
				data:{token:leadGlobal},
				cache:false,
				success:function(res){
					jQuery("#"+type).find('#spinner-loader-ppa_executed').css('display','none');
					_data  = jQuery.parseJSON(res);
					if(_data.send>0){
						_statusMessage = "PPA has executed successfully";
						if(_data.button_data.status_message!=undefined && _data.button_data.status_message!=""){
							_statusMessage = _data.button_data.status_message;
						}
						jQuery("#"+type).find("#ppa_execute").html('<span class="date-style">' + moment(new Date(_data.date_update)).format('MM-D-YY')+"</span> "+_statusMessage);
						jQuery("#all_type_list").find('tr.active').find('td').eq(5).html(_data.date_update);
						/*taskMessageBox(_data);*/
					} else {
						alert("Server busy. Please refresh your page.");
					}
				}
			});
		}
	}
	function openAggregateReferencedApplicant(type){
		if(jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').length>0){
			jQuery('.aggregate_data').empty().append("<tbody><tr>"+jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').html()+"</tr></tbody>");
			jQuery("#newAggregateRefrencedApplicant").modal("show");
		}
		/*switch(type){
			case 'from_regular':
				if(jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').length>0){
					jQuery('.aggregate_data').empty().append("<tbody><tr>"+jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').html()+"</tr></tbody>");
					jQuery("#newAggregateRefrencedApplicant").modal("show");
					jQuery('body').removeAttr('onselectstart');
				}
			break;
			case 'from_litigation':
				if(jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').length>0){
					jQuery('.aggregate_data').empty().append("<tbody><tr>"+jQuery("#scrap_patent_data").find('tbody').find('tr.aggregate').html()+"</tr></tbody>");
					jQuery("#newAggregateRefrencedApplicant").modal("show");
					jQuery('body').removeAttr('onselectstart');
				}
			break;
		}*/
	}
	jQuery(document).ready(function(){
		jQuery('#newAggregateRefrencedApplicant').on('hidden.bs.modal', function () {
		});
	});
	function referencedCheckApply(){
		jQuery(".aggregate_data").find("tr").each(function(){
			if(jQuery(this).find('input[name="referenced_check[]"]').is(':checked')==false){
				jQuery(this).hide();
			}
		});
	}
	function referencedCheckCancel(){
		jQuery(".aggregate_data").find("tr").find('input[name="referenced_check[]"]').prop( "checked", false );
		jQuery(".aggregate_data").find("tr").show();
	}
	function referencedSelectAll(){
		jQuery(".aggregate_data").find("tr").show().find('input[name="referenced_check[]"]').prop( "checked", true );
	}
	function searchForm(){
		_form = jQuery("#search_form").serialize();	
		jQuery("#s_result").empty('<div class="loading-spinner" id="loading_spinner_heading_drive" style="display:block;"><img src="public/images/ajax-loader.gif" alt=""></div>');
		jQuery("#emailListSearch").empty();
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'dashboard/search_lead',
			data:_form,
			cache:false,
			success:function(data){
				_data = jQuery.parseJSON(data);
				if(_data.length>0){
					_tr="";
					jQuery("#s_result").html("<table class='table' id='search_results'><thead><tr><th>Lead</th><th>Type</th><th>Info</th><th>Seller</th><th>Synpat</th><th>PPA</th><th>Close</th></tr></thead><tbody></tbody></table>");
					for(i=0;i<_data.length;i++){
						$type="";
						$stage ="";
						$main = "mainLead";
						switch(_data[i].type){
							case 'Litigation':
								$type ="Lit.";
							break;
							case 'Market':
								$type ="Mkt.";
							break;
							case 'General':
								$type ="Pro.";
							break;
							case 'SEP':
								$type ="SEP";
							break;
							default:
								$type = _data[i].type;
								$main = "outterLead";
							break;
						}
						if(_data[i].complete<2){
							$stage="Lead";
						} else {
							$stage ="Oppt.";
						}
						$sellerInfo = "";
						if(_data[i].seller_info_text!="" && _data[i].seller_info_text!=null){
							$sellerInfo = moment(new Date(_data[i].seller_info_text)).format('MM D,YY'); 
						}
						$sellerLike = "";
						if(_data[i].seller_like!="" && _data[i].seller_like!=null){
							$sellerLike = moment(new Date(_data[i].seller_like)).format('MM D,YY');
						}
						$synpatLike = "";
						if(_data[i].synpat_like!="" && _data[i].synpat_like!=null){
							$synpatLike = moment(new Date(_data[i].synpat_like)).format('MM D,YY');
						}
						$ppa = "";
						if(_data[i].ppa_date!="" && _data[i].ppa_date!=null){
							$ppa = moment(new Date(_data[i].ppa_date)).format('MM D,YY');
						}
						$fundingTrnsfr = "";
						if(_data[i].funding_trnsfr!="" && _data[i].funding_trnsfr!=null){
							$fundingTrnsfr = moment(new Date(_data[i].funding_trnsfr)).format('MM D,YY');
						}
						$sellerClass = "";
						if(_data[i].seller_info==1){
							$sellerClass = "btn-blink";
						}
						_tr='<tr class="border-blue-alt droppable old_lead '+$main+' " data-id="'+_data[i].id+'" data-type="'+_data[i].type+'" onclick="threadDetail(jQuery(this));" >'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:200px;" data-id="'+_data[i].id+'" data-type="'+_data[i].type+'" class=""><label><a style="text-align:left;" title="'+_data[i].lead_name+'" class="btn" href="javascript:void(0);">'+_data[i].lead_name.substring(0,30)+'</a></label></td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:45px;">'+$type+'</td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;" class="'+$sellerClass+'">'+$sellerInfo+'</td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">'+
															'<div style="white-space:nowrap;">'+$sellerLike+'</div>'+
														'</td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">'+
															'<div style="white-space:nowrap;">'+$synpatLike+'</div>'+
														'</td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">'+
															'<div style="white-space:nowrap;">'+$ppa+'</div>'+
														'</td>'+
														'<td style="padding:3px 2px; border-right:none; border-left:none;width:71px;">'+
															'<div style="white-space:nowrap;">'+$fundingTrnsfr+'</div>'+
														'</td>'+
													'</tr>';
						jQuery("#search_results").find('tbody').append(_tr);
					}
				} else {
					jQuery("#s_result").html("<p class='alert alert-warning'>No record found!</p>")
				}
			}
		});
	}

	function runPatentScraping(pos,mode){
		pi = 0;
		jQuery.each(_allMainPatents[pos], function(key, value) {
			if(pi==0){
				_patent = value;
			}
			pi++;
		});
		/*if(_patent==undefined){
			_patent = _allMainPatents[pos].patent;
		}*/
		jQuery.ajax({
			url: __baseUrl + 'leads/googleSpreadSheet',
			type:'POST',
			data:{p:_patent,boxes:leadGlobal},
			cache:false,
			success:function(data){
				if(data!=""){
					_data = jQuery.parseJSON(data);
					mainAllPatentData.push(_data);
					incPos = pos + 1;
					if(_allMainPatents[incPos]!=undefined){
						runPatentScraping(incPos,mode);
					} else{
						fillTablePatent(mode);
					}
				}
			}
		}).fail(function(){
			incPos = pos + 1;
			if(_allMainPatents[incPos]!=undefined){
				runPatentScraping(incPos);
			} else{
				fillTablePatent(mode);
			}
		});
	}

	function fillTablePatent(parentElement){
		if(mainAllPatentData.length>0){
			if(parentElement!='undefined'){
				jQuery("#"+parentElement).find('#loadingLink').removeClass('overflow-link');
			}
			jQuery("#loadingLabel").html('');
			mainArray = [];
			if(jQuery("#scrap_patent_data").find("tbody").find("tr").length>0){
				jQuery("#scrap_patent_data").find("tbody").find("tr").each(function(){
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
						jQuery("#scrap_patent_data").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				jQuery("#scrap_patent_data").find("th").each(function(){
					_innerArray.push(null);
				});
				mainArray.push(_innerArray);
			}
			_patentDataValue = "";
			if(parentElement!='undefined'){
				switch(parentElement){
					case 'from_regular':
						_patentDataValue = "marketPatentData";
					break;
					case 'from_litigation':
						_patentDataValue = "litigationPatentData";
					break;
					case 'from_nonacquistion':
						_patentDataValue = "acquisitionPatentData";
					break;
				}
				jQuery("#"+_patentDataValue).val(JSON.stringify(mainArray));
			}
			jQuery("#scrap_patent_data").find('tbody').empty();
			_data=mainAllPatentData;
			if(_data.length>0){
				for(i=0;i<_data.length;i++){
					_tr = jQuery("<tr/>").addClass('mainDataP');
					_columns = _data[i];
					for(j=0;j<_columns.length;j++){						
						_class="";
						columData = _columns[j];
						
						if(j>=1 && j<=4){
							
							_class="clickakble";
							try {
								_getOldData = jQuery("#"+_patentDataValue).val();
								if(_getOldData!=""){
									
									_getOldData = jQuery.parseJSON(_getOldData);
									
									if(_getOldData.length>0){
										for(ol=0;ol<_getOldData.length;ol++){
											
											if(_columns[0]==_getOldData[ol][0]){
												if(j==1){
													if(_getOldData[ol][1]!='undefined'){
														columData = _getOldData[ol][1];
													}
												} else if(j==2){
													if(_getOldData[ol][2]!='undefined'){
														columData = _getOldData[ol][2];
													}
												} else if(j==3){
													if(_getOldData[ol][3]!='undefined'){
														columData = _getOldData[ol][3];
													}
												} else if(j==4){
													if(_getOldData[ol][4]!='undefined'){
														columData = _getOldData[ol][4];
													}
												}
											}
										}
									}
								}
							} catch (e) {
								
							}
						}
						
						if(columData!=null && j==11){
							
							newReferenced = jQuery.map(columData, function(value, index) {
								return [value,index];
							});
							columData = "<div style='width:200px;max-height:200px;overflow:scroll;overflow-y:scroll;overflow-x:none;'><ul style='list-style;padding-left:0px;'>";
							___tt = "<table><tbody>";
							for(r=0;r<newReferenced.length;r++){
								columData += "<li>"+newReferenced[r+1]+": "+newReferenced[r]+"</li>"; r++;
								___tt +="<tr><td>"+newReferenced[r+1]+"</td><td>"+newReferenced[r]+"</td></tr>";
							}
							___tt +="</tbody></table>";
							
							columData +="</ul></div>";
						}
						if(j<12){
							
							if(columData!=null){																		
								jQuery(_tr).append("<td class='"+_class+"'>"+columData+"</td>");
							} else {
								jQuery(_tr).append("<td class='"+_class+"'></td>");
							}
						}
						if(j==0){
							if(columData!=null && columData!=""){											
								td = "<a href='javascript://' class='btn' onclick='getGooglePatent(\""+jQuery.trim(columData)+"\")'>"+columData+"</a>";
								jQuery(_tr).find('td').eq(0).html(td);
							}	
						}
					}
					jQuery("#scrap_patent_data").find('tbody').append(_tr);
					if(i==_data.length-1){
						_tr = jQuery("<tr/>").addClass('aggregate');	
						newDataColumn = _columns = _data[i][12]
						overAllReferenced = jQuery.map(newDataColumn, function(value, index) {
							return [value,index];
						});
						newStrTab = "<div style='width:500px;max-height:400px;overflow:scroll;overflow-y:scroll;overflow-x:none;'><table class='table'>";
						for(r=0;r<overAllReferenced.length;r++){ newStrTab +="<tr><td>"+overAllReferenced[r+1]+"</td><td>"+overAllReferenced[r]+"</td></tr>";r++;}																								
						newStrTab +="</table></div>";
						/*_columnData = _columnData.substr(0,_columnData.length-2);*/														
						jQuery(_tr).append("<td colspan='12'>"+newStrTab+"</td>");
						jQuery("#scrap_patent_data").find('tbody').append(_tr);
					}
				}
				backSwitchPatentFrom(parentElement);
			} else {
				jQuery("#scrap_patent_data").find('tbody').append("<tr><td colspan='9'>No able to import data</td></tr>");
			}									
		} else {
			if(parentElement!='undefined'){
				jQuery("#"+parentElement).find('#loadingLink').removeClass('overflow-link');
			}			
			jQuery("#loadingLabel").html('Error while importing');
			/*alert('Error while importing');*/
		}
	}
	function getGooglePatent(patent){
		if(patent!=""){			
			jQuery("#patent_data").find('table>tbody>tr.mainDataP').each(function(){
				if(jQuery(this).find('td').eq(0).find('a').text()==patent){
					jQuery(this).find('td').eq(0).find('a').css('font-weight','bold');
				} else {
					jQuery(this).find('td').eq(0).find('a').css('font-weight','');
				}
			});
			jQuery("#scrapGoogleData").find('.pad15A').html('<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style="display:none;"><img src="public/images/ajax-loader.gif" alt=""></div><div id="scrapGooglePatent"></div>');
			jQuery("#scrapGoogleData").addClass("sb-active");
			openSlidebar(jQuery("#scrapGoogleData"));
			_height = jQuery(window).height();
			jQuery("#scrapGooglePatent").html('<iframe height="'+_height+'" width="100%" src="leads/scrapData/'+patent+'"></iframe>');
		}						
	}

	function dataValidate(){
		_editable=false;
		switchBackMode('from_litigation');
		mainArray = [];								
		var pattern=/^[0-9a-zA-Z]+$/;
		if(jQuery.trim($("#litigationleadName").val())==""){
			$("#litigationleadName").val(leadNameGlobal);
		}
		if(jQuery.trim($("#litigationleadName").val())=="" ){
			alert("Please enter name of lead.");
			return false;
		} else {
			if($("#scrap_patent_data").find("tbody").find("tr.mainDataP").length>0){
				$("#scrap_patent_data").find("tbody").find("tr.mainDataP").each(function(){
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
						$("#scrap_patent_data").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				$("#scrap_patent_data").find("th").each(function(){
					_innerArray.push(null);
				});
				mainArray.push(_innerArray);
			}
			jQuery("input[name='litigation[patent_data]']").val(JSON.stringify(mainArray));
			return true;
		}									
	}

	function scheduleCall(){
		window.open("https://www.google.com/calendar/render?tab=mc#h","_blank");
		jQuery('#create_scheduleCall').modal('show');
	}

	function save_embedCode(){
		var embed_code = jQuery("#create_scheduleCall").find('#embed_code').val();
		if(embed_code == ''){
			jQuery("#create_scheduleCall").find('#embed_code').css('border-color','#ff0000');
		}
		if(jQuery('#litigationId').val()!=""){
			jQuery("#loading_spinner_schedulecall").css('display','block');
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'index.php/leads/embedScheduleCall',
				data:{'embed_code':embed_code,lead_id:jQuery('#litigationId').val()},
				success:function(response){
					jQuery("#loading_spinner_schedulecall").css('display','none');
					if(parseInt(response)==1){    
						jQuery("#from_litigation").find("#schedule1stCall").before(jQuery('#embed_code').val());
						jQuery('#create_scheduleCall').modal('hide');
					}  else {
						alert("Server busy. Refresh your page.");   
					}              
				}
			});
		} else {
			alert("Server busy. Refresh your page.");   
		}
		
	}
	function claimIllus(fromType){
		jQuery('#'+fromType).find("#loader_claim_illus_dd_market").show();
		jQuery('#'+fromType).find("#claim_illus").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/createClaimillustration',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_claim_illus_dd_market").hide();
				jQuery('#'+fromType).find("#claim_illus").find('a').addClass('btn-blink');
				obj = JSON.parse(response);
				if(obj.url != '' && obj.error==0){
					_html = jQuery('#'+fromType).find("#claim_illus a").html();
					jQuery('#'+fromType).find("#claim_illus a").attr('onclick','claimIllusStatusChange('+fromType+')').html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html).addClass('btn-blink');
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
					_fileURL = obj.url;
					_link = "https://docs.google.com/file/d/"+obj.spread_sheet_id+"/preview";
					_mimeType = obj.mimeType;
					_icon = obj.iconLink;
					_title = obj.title;
					window.parent.jQuery("#"+fromType).find("#litigation_doc_list").find('ul.todo-box-1').append('<li class="driveDragable ui-draggable ui-draggable-handle ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files(\''+_link+'\')">'+_title+'</a></li>');				
					docFileDraggable();
					driveFileDraggable();							
					_html = '<ul class="attachment-list"><li class="attachment-list-item ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files('+_link+')">'+_title+'</a><span class="remove-attachment pull-right hide"><a class="" onclick="deleteMe(jQuery(this))"><i class="glyph-icon icon-close"></i></a></span></li></ul>';
					jQuery("#attach_droppable:hidden").html(_html);
					jQuery("#attach_droppable").html(_html);
					jQuery("#emailDocUrl").val(_html);
					$('.wysiwyg-editor').destroy();
					initEditor('.wysiwyg-editor');					
					mainLogBox=1;
					jQuery("#legal_patents").val(obj.pp);
					jQuery("#f_t").val(obj.f_t);
					composeEmail(0);
					jQuery("#attach_droppable").html(_html);
					initAttachRemove();
				}  else {
					alert(obj.message);   
					jQuery('#'+fromType).find("#claim_illus").find('a').attr('onclick','claimIllus('+fromType+')');
				}              
			}
		});
	}

	function claimIllusStatusChange(fromType){
		jQuery('#'+fromType).find("#loader_claim_illus_dd_market").show();
		jQuery('#'+fromType).find("#claim_illus").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/claimIllusStatusChange',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_claim_illus_dd_market").hide();
				jQuery('#'+fromType).find("#claim_illus").find('a').addClass('btn-blink');
				obj = JSON.parse(response);
				if(obj.error==0){
					_html = jQuery('#'+fromType).find("#claim_illus a").html();
					jQuery('#'+fromType).find("#claim_illus").html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html).removeClass('btn-blink');
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
				}  else {
					alert(obj.message);
					jQuery('#'+fromType).find("#claim_illus").find('a').attr('onclick','claimIllusStatusChange('+fromType+')');
				}
			}
		});
	}
	
	function btnModeStatus(bID,container){         
		jQuery('#loader_'+bID).removeClass('hide').addClass('show');
		jQuery('#drive_button'+bID).find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/btn_status_change',
			data:{lead_id:leadGlobal,b:bID},
			success:function(response){
				obj = JSON.parse(response);
				if(obj.error==0){         
					_html = jQuery('#drive_button'+bID).find('a').attr('data-status');
					jQuery('#drive_button'+bID).html(moment(new Date(obj.date_created)).format('MM-D-YY')+' <span>'+_html+'</span>');
				}  else {
					alert(obj.message);  
					jQuery('#loader_'+bID).addClass('hide').removeClass('show');
					jQuery('#drive_button'+bID).find('a').attr('onclick','btnModeStatus('+bID+',"'+container+'")');
				}              
			}
		});
	}
	
	function technicalDD(fromType){
		jQuery('#'+fromType).find("#loader_technical_dd_market").show();
		jQuery('#'+fromType).find("#technical_dd").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/createTechnicalDD',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_technical_dd_market").hide();
				jQuery('#'+fromType).find("#technical_dd").find('a').addClass('btn-blink');
				obj = JSON.parse(response);											
				if(obj.url != '' && obj.error==0){         
					_html = jQuery('#'+fromType).find("#technical_dd a").html();
					jQuery('#'+fromType).find("#technical_dd a").attr('onclick','technicalStatusChange('+fromType+')').html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html).addClass('btn-blink');
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
					_fileURL = obj.url;
					_link = "https://docs.google.com/file/d/"+obj.spread_sheet_id+"/preview";
					_mimeType = obj.mimeType;
					_icon = obj.iconLink;
					_title = obj.title;
					window.parent.jQuery("#"+fromType).find("#litigation_doc_list").find('ul.todo-box-1').append('<li class="driveDragable ui-draggable ui-draggable-handle ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files(\''+_link+'\')">'+_title+'</a></li>');					
					docFileDraggable();
					driveFileDraggable();							
					_html = '<ul class="attachment-list"><li class="attachment-list-item ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files('+_link+')">'+_title+'</a><span class="remove-attachment pull-right hide"><a class="" onclick="deleteMe(jQuery(this))"><i class="glyph-icon icon-close"></i></a></span></li></ul>';
					jQuery("#attach_droppable:hidden").html(_html);
					jQuery("#attach_droppable").html(_html);
					jQuery("#emailDocUrl").val(_html);
					$('.wysiwyg-editor').destroy();
					initEditor('.wysiwyg-editor');
					mainLogBox=1;
					jQuery("#legal_patents").val(obj.pp);
					jQuery("#f_t").val(obj.f_t);
					composeEmail(0);
					jQuery("#attach_droppable").html(_html);
					initAttachRemove();
				}  else {
					alert(obj.message);   
					jQuery('#'+fromType).find("#technical_dd").find('a').attr('onclick','technicalDD('+fromType+')');
				}              
			}
		});
	}

	function technicalStatusChange(fromType){                            	    
		jQuery('#'+fromType).find("#loader_technical_dd_market").show();
		jQuery('#'+fromType).find("#technical_dd").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/technicalStatusChange',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_technical_dd_market").hide();
				jQuery('#'+fromType).find("#technical_dd").find('a').addClass('btn-blink');
				obj = JSON.parse(response);
				if(obj.error==0){         
					_html = jQuery('#'+fromType).find("#technical_dd a").html();
					jQuery('#'+fromType).find("#technical_dd").html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html).removeClass('btn-blink');
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
				}  else {
					alert(obj.message);   
					jQuery('#'+fromType).find("#technical_dd").find('a').attr('onclick','technicalStatusChange('+fromType+')');
				}              
			}
		});
	}

	function legalDD(fromType){
		jQuery('#'+fromType).find("#loader_legal_dd_market").show();
		jQuery('#'+fromType).find("#legal_dd").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/createLegalDD',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_legal_dd_market").hide();											
				obj = JSON.parse(response);											
				if(obj.url != '' && obj.error==0){         
					_html = jQuery('#'+fromType).find("#legal_dd a").html();
					jQuery('#'+fromType).find("#legal_dd a").attr('onclick','legalStatusChange('+fromType+')').html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html).addClass('btn-blink');
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
					_fileURL = obj.url;
					_link = "https://docs.google.com/file/d/"+obj.spread_sheet_id+"/preview";
					_mimeType = obj.mimeType;
					_icon = obj.iconLink;
					_title = obj.title;
					window.parent.jQuery("#"+fromType).find("#litigation_doc_list").find('ul.todo-box-1').append('<li class="driveDragable ui-draggable ui-draggable-handle ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files(\''+_link+'\')">'+_title+'</a></li>');
					docFileDraggable();
					driveFileDraggable();
					_html = '<ul class="attachment-list"><li class="attachment-list-item ms-hover"><img src="'+_icon+'"> <a data-href="'+_fileURL+'" target="_BLANK" href="javascript://" onclick="open_drive_files('+_link+')">'+_title+'</a><span class="remove-attachment pull-right hide"><a class="" onclick="deleteMe(jQuery(this))"><i class="glyph-icon icon-close"></i></a></span></li></ul>';
					jQuery("#attach_droppable:hidden").html(_html);
					jQuery("#attach_droppable").html(_html);
					jQuery("#emailDocUrl").val(_html);												
					$('.wysiwyg-editor').destroy();
					initEditor('.wysiwyg-editor');
					mainLogBox = 1;
					jQuery("#legal_patents").val(obj.pp);
					jQuery("#f_t").val(obj.f_t);
					composeEmail(0);
					jQuery("#attach_droppable").html(_html);
					initAttachRemove();
				}  else {
					alert(obj.message);   
					jQuery('#'+fromType).find("#legal_dd").find('a').attr('onclick','legalDD('+fromType+')');
				}              
			}
		});
	}

	function legalStatusChange(fromType){
		jQuery('#'+fromType).find("#loader_legal_dd_market").show();
		jQuery('#'+fromType).find("#legal_dd").find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/legalStatusChange',
			data:{lead_id:leadGlobal,ds:1},
			success:function(response){
				jQuery('#'+fromType).find("#loader_legal_dd_market").hide();											
				obj = JSON.parse(response);											
				if(obj.error==0){         
					_html = jQuery('#'+fromType).find("#legal_dd a").html();
					jQuery('#'+fromType).find("#legal_dd").html(moment(new Date(obj.date_created)).format('MM-D-YY')+' '+_html);
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
				}  else {
					alert(obj.message);   
					jQuery('#'+fromType).find("#legal_dd").find('a').attr('onclick','legalStatusChange('+fromType+')');
				}              
			}
		});
	}

	function openPatentList(){
		var patent_url = jQuery("#litigationFileUrl").val();
		if(patent_url != ''){
			open_drive_files(patent_url);		
		}
	}

	function scheduleCallMarket(){
		jQuery("#scheduleCallMarket").modal("show");
		window.open("https://www.google.com/calendar/render?tab=mc&pli=1#h","_BLANK");
	}
	function saveScheduleCallMarket(){
		var lead_id = jQuery('#marketLeadId').val();
		var embedCode = jQuery('#marketEmbedCode').val();
		if(embedCode==""){
			jQuery('#marketEmbedCode').css('border-color','#ff0000');
		}
		if(embedCode!=''){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/embedScheduleCall',
				data:{'embed_code':embedCode,'lead_id':lead_id,ds:1},
				success:function(response){
					if(response=="1"){                    
						jQuery("#schedule1stCallMarket").before(embedCode);
						jQuery("#scheduleCallMarket").modal("hide");
					}  else {
						alert("Server busy. Refresh your page.");   
					}
				}
			});
		}
	}
	function openPatentListMarket(){
		var patent_url = jQuery("#marketFileUrl").val();
		if(patent_url != ''){
			open_drive_files(patent_url);
		}
	}
	_mainData = "";

	function dataValidateMarket(){
		_editable=false;
		switchBackMode('from_regular');
		mainArray = [];
		var pattern=/^[0-9a-zA-Z]+$/;
		if($.trim($("#marketlead_name").val())==""){
			$("#marketlead_name").val(leadNameGlobal);
		}
		if($.trim($("#marketlead_name").val())==""){
			alert("Please enter name of lead.");
			return false;
		} else {
			if($("#scrap_patent_data").find("tbody").find("tr.mainDataP").length>0){
				$("#scrap_patent_data").find("tbody").find("tr.mainDataP").each(function(){
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
						$("#scrap_patent_data").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				$("#scrap_patent_data").find("th").each(function(){
					_innerArray.push(null);
				});
				mainArray.push(_innerArray);
			}
			jQuery("#marketPatentData").val(JSON.stringify(mainArray));
			return true;
		}
	}
	function dataValidateLeadForm(){
		_editable=false;
		switchBackMode('from_nonacquistion');
		mainArray = [];
		var pattern=/^[0-9a-zA-Z]+$/;
		if($.trim($("#acquisitionlead_name").val())==""){
			$("#acquisitionlead_name").val(leadNameGlobal);
		}
		if($.trim($("#acquisitionlead_name").val())==""){
			alert("Please enter name of lead.");
			return false;
		} else {
			if(jQuery("#scrap_patent_data").find("tbody").find("tr.mainDataP").length>0){
				jQuery("#scrap_patent_data").find("tbody").find("tr.mainDataP").each(function(){
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
						jQuery("#scrap_patent_data").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				jQuery("#scrap_patent_data").find("th").each(function(){
					_innerArray.push(null);
				});
				mainArray.push(_innerArray);
			}
			jQuery("#from_nonacquistion").find("#acquisitionPatentData").val(JSON.stringify(mainArray));
			return true;
		}
	}
	
	function submitFromData(parentElement){
		jQuery('#'+parentElement).find("#loading_spinner_form_market").show();
		if(dataValidateLeadForm()){
			jQuery.ajax({
				url: __baseUrl + 'dashboard/lead_form',
				type:'POST',
				data:jQuery("#leadForm").serialize(),
				cache:false,
				success:function(data){		
					jQuery('#'+parentElement).find("#loading_spinner_form_market").hide();
					if(data==""){
						jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').attr("title",jQuery("#acquisitionlead_name").val());
						jQuery("table.DTFC_Cloned>tbody>tr.active").find('td').eq(0).find('label>a').html(jQuery("#acquisitionlead_name").val()).attr("title",jQuery("#acquisitionlead_name").val());
					} else {
						alert(data);
					}									
				}
			});
		} else {
			jQuery('#'+parentElement).find("#loading_spinner_form_market").hide();
		}
	}
	
	function runTableLeadBal(){
		jQuery("#all_type_list>tbody>tr").each(function(index){
			_title = jQuery("table.DTFC_Cloned>tbody>tr").eq(index).find('td').eq(0).find('label>a').attr("title");
			if(_title==""){
				_title = jQuery("table.DTFC_Cloned>tbody>tr").eq(index).find('td').eq(0).find('label>a').html();
			}
			if(_title==""){
				_title = jQuery("#all_type_list>tbody>tr").eq(index).find('td').eq(0).find('label>a').attr("title");
			}
			if(_title==""){
				_title = jQuery("#all_type_list>tbody>tr").eq(index).find('td').eq(0).find('label>a').html();
			}
			if(_title!=''){
				jQuery("table.DTFC_Cloned>tbody>tr").eq(index).find('td').eq(0).find('label>a').html(_title);
				jQuery("table.DTFC_Cloned>tbody>tr").eq(index).find('td').eq(0).find('label>a').attr("title",_title);
				jQuery("#all_type_list>tbody>tr").eq(index).find('td').eq(0).find('label>a').html(_title);
				jQuery("#all_type_list>tbody>tr").eq(index).find('td').eq(0).find('label>a').attr("title",_title);
			}
		});			
	}
	
	function assign_task_mode(type,parentElement){
        if(leadGlobal>0){
			switch(parentElement){
				case 'from_nonacquistion':
					jQuery('#'+parentElement).find("#acquisitionSellerInfo").val(type);
				break;
				case 'from_regular':
					jQuery("#marketSellerInfo").val(type)
				break;
				case 'from_litigation':
					jQuery('#'+parentElement).find("#litigationSellerInfo").val(type);
				break;
			}		
			if(type== 1)
			{
				jQuery('#'+parentElement).find("#loader_seller_market").show();
				jQuery.ajax({
					type:'POST',
					url:  __baseUrl + 'leads/post_seller_info',
					data:{'lead_id':leadGlobal,ds:1},
					success:function(response){
						jQuery('#'+parentElement).find("#loader_seller_market").hide();
						obj = jQuery.parseJSON(response);
						if(typeof(obj.date_created)!="undefined"){
							jQuery('#'+parentElement).find("#assign_task_market").addClass("btn-blink");
							jQuery('#'+parentElement).find("#assign_task_market").find('a').attr('onclick','assign_task_mode(2)');
							jQuery("#all_type_list").find('tr.active').find('td').eq(2).addClass('btn-blink').html(obj.date_created);
						}														
					}
				});
			} else {
				_validateFrom = true;
				switch(parentElement){
					case 'from_regular':
						if(jQuery("#marketlead_name").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketOwner").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketPersonName1").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketPersonName2").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketBroker").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketBrokerPerson").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketNo_of_us_patents").val()==""){
							_validateFrom = false;
						}
						if(jQuery("#marketAddress").val()==""){
							_validateFrom = false;
						}
					break;
					case 'from_nonacquistion':
						if(jQuery('#'+parentElement).find("#acquisitionlead_name").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionOwner").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionPersonName1").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionPersonName2").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionBroker").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionBrokerPerson").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionNo_of_us_patents").val()==""){
							_validateFrom = false;
						}
						if(jQuery('#'+parentElement).find("#acquisitionAddress").val()==""){
							_validateFrom = false;
						}
					break;
					case 'from_litigation':
						_validateFrom = true;
					break;
				}				
				if(_validateFrom===true){
					jQuery('#'+parentElement).find("#loader_seller_market").show();
					jQuery.ajax({
						type:'POST',
						url: __baseUrl + 'leads/assign_lead',
						data:{'lead_id':leadGlobal,'base_url': __baseUrl + 'leads/market',ds:1},
						success:function(response){
							jQuery('#'+parentElement).find("#loader_seller_market").hide();
							obj = jQuery.parseJSON(response);
							jQuery('#'+parentElement).find("#assign_task_market a").hide();
							_statusMessage = "Seller Info Done";
							if(obj.button_data.status_message!=undefined && obj.button_data.status_message!=""){
								_statusMessage = obj.button_data.status_message;
							}
							jQuery('#'+parentElement).find("#assign_task_market").html('<span class="date-style">' + moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> "+_statusMessage);
							jQuery("#all_type_list").find('tr.active').find('td').eq(2).removeClass("btn-blink").html(obj.date_created);
							jQuery('#'+parentElement).find("#assign_task_market").removeClass("btn-blink");
						}
					});
				} else {
					alert("Please fill-in all the fields in the form. Put N/A where not applicable.")
				}
			}
		} else {
			alert("Please select lead first");
		}        
    }
	
	function spreadsheet_box_mode(parentElement){
		switch(parentElement){
			case 'from_regular':
				jQuery("#create_spreadsheet_market").find('#spreadsheet').val(jQuery('#'+parentElement).find('#marketlead_name').val());
			break;
			case 'from_litigation':
				jQuery("#create_spreadsheet_market").find('#spreadsheet').val(jQuery('#'+parentElement).find('#litigationlead_name').val());
			break;
			case 'from_nonacquistion':
				jQuery("#create_spreadsheet_market").find('#spreadsheet').val(jQuery('#'+parentElement).find('#acquisitionlead_name').val());
			break;
		}
		create_spreadsheet_mode(parentElement);
	}
	
	function create_spreadsheet_mode(parentElement){      
       if(leadNameGlobal != ''){
		   jQuery('#'+parentElement).find("#create_patent_list_market").find('a').removeAttr('onclick');
			jQuery('#'+parentElement).find("#loader_patent_market").show();
            jQuery.ajax({
                type:'POST',
                url: __baseUrl + 'leads/createLeadPatentSpreadSheet',
                data:{'n':leadNameGlobal,'lead_id':leadGlobal,ds:1},
                success:function(response){
                   jQuery('#'+parentElement).find("#loader_patent_market").hide();
                    jQuery('#'+parentElement).find("#create_patent_list_market").find('a').addClass('btn-blink');
                    obj = JSON.parse(response);
                    if(obj.url != '' && obj.error==0){ 
						threadDetail(jQuery("#all_type_list").find('tr.active'),"",1);
						open_drive_files(obj.url);
                    } else {
						jQuery('#'+parentElement).find("#create_patent_list_market").find('a').attr('onclick','spreadsheet_box_mode()');
                    }
                }
            });
         } else {
			 alert("Please enter lead name before creating patent sheet");
		 }
     }
	 
	 function scheduleCallMode(parentElement){
		jQuery("#scheduleCallAcquisition").modal("show");
		window.open("https://www.google.com/calendar/render?tab=mc&pli=1#h","_BLANK");
	 }
	 
	 function saveScheduleCallMode(parentElement){
		var lead_id = jQuery('#'+parentElement).find('#acquisitionLeadId').val();
		var embedCode = jQuery('#'+parentElement).find('#acquisitionEmbedCode').val();
		if(embedCode==""){
			jQuery('#'+parentElement).find('#acquisitionEmbedCode').css('border-color','#ff0000');
		}
		if(embedCode!=''){
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/embedScheduleCall',
				data:{'embed_code':embedCode,'lead_id':lead_id,ds:1},
				success:function(response){
					if(response=="1"){                    
						jQuery('#'+parentElement).find("#schedule1stCallMarket").before(embedCode);
						jQuery("#scheduleCallAcquisition").modal("hide");
					}  else {
						alert("Server busy. Refresh your page.");   
					}
				}
			});
		}
	 }

	function forward_to_review_mode(parentElement){
		if(leadGlobal > 0){
			$("#loader_review_market").show();
			jQuery.ajax({
				type:'POST',
				url: __baseUrl + 'leads/forward_to_review',
				data:{'lead_id':leadGlobal},
				success:function(response){
					jQuery("#"+parentElement).find("#loader_review_market").hide();
					threadDetail(jQuery("#all_type_list").find('tr.active'),"",1); 
				}
			});
		}
	}
	
	function createPartNDATermsheetMode(parentElement){
		var lead_id = leadGlobal;
		if(lead_id!=0 && lead_id!=""){
		   jQuery("#"+parentElement).find("#spinner-loader-nda-timesheet").css('display','block');  
		   jQuery("#"+parentElement).find("#loader_NDA_market").show();     
			jQuery.ajax({
				url: __baseUrl + 'leads/createNDATermsheet',
				type:'POST',
				data:{v:lead_id},
				cache:false,
				statusCode: {
					404: function() {
					  threadDetail(jQuery("#all_type_list").find('tr.active'));
					},
					504: function() {
					  threadDetail(jQuery("#all_type_list").find('tr.active'));
					}
				  },
				success:function(response){
					jQuery("#"+parentElement).find("#spinner-loader-nda-timesheet").css('display','none');     
					_response = jQuery.parseJSON(response);
					jQuery("#"+parentElement).find("#loader_NDA_market").hide();
					if(_response.error=="0"){
						threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));
					} else {
						alert("Got error from Google Drive, Please try again.");
					}
				}
			});
		}
	}
	
	function findWorksheetMode(o,p,parentElement){
		v = jQuery.trim(o.val());	
		if(v!=""){
			jQuery.ajax({
				url: __baseUrl + 'leads/findWorksheetList',
				type:'POST',
				data:{v:v},
				cache:false,
				success:function(data){
					_d = jQuery.parseJSON(data);
					if(_d!=undefined  && _d.length>0){
						_option = "";
						for(i=0;i<_d.length;i++){
							_selected="";
							if(typeof p == 'string' || typeof p =='number'){
								if(p==_d[i].id){
									_selected = "SELECTED='SELECTED'";
								}
							}							
							if(i==0 && ( typeof p!='string' && typeof p!='number') ){
								_selected = "SELECTED='SELECTED'";								
							}
							_option +="<option  "+_selected+" value='"+_d[i].id+"' data-href='"+_d[i].full+"'>"+_d[i].text+"</option>";
						}
						jQuery("#patentWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>"+_option);
						if(jQuery("#patentWorksheetId").val()==""){
							jQuery("#patentWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
							snapGlobal = jQuery("#patentWorksheetId>option").eq(1).attr("data-href");
							jQuery("#patentFileUrl").val(snapGlobal);
						} else {
							snapGlobal = jQuery("#patentWorksheetId>option:selected").eq(1).attr("data-href");
							if(snapGlobal==undefined){
								snapGlobal = jQuery("#patentWorksheetId>option:SELECTED").eq(1).attr("data-href");
							}
						}
							/*
						switch(parentElement){
							case 'from_regular':
							jQuery("#marketWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>"+_option);
							if(jQuery("#marketWorksheetId").val()==""){
								jQuery("#"+parentElement).find("#marketWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
								snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option").eq(1).attr("data-href");
								jQuery("#"+parentElement).find("#marketFileUrl").val(snapGlobal);
							} else {
								snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option:selected").eq(1).attr("data-href");
								if(snapGlobal==undefined){
									snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option:SELECTED").eq(1).attr("data-href");
								}
							}
							break;
							case 'from_nonacquistion':
								jQuery("#"+parentElement).find("#acquisitionWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>"+_option);
								if(jQuery("#"+parentElement).find("#acquisitionWorksheetId").val()==""){
									jQuery("#"+parentElement).find("#acquisitionWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
									snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option").eq(1).attr("data-href");
									jQuery("#"+parentElement).find("#acquisitionFileUrl").val(snapGlobal);
								} else {
									snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option:selected").attr("data-href");
									if(snapGlobal==undefined){
										snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option:SELECTED").attr("data-href");
									}
								}
							break;
							case 'from_litigation':
								jQuery("#litigationWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>");
								if(jQuery("#litigationWorksheetId").val()==""){
									jQuery("#"+parentElement).find("#litigationWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
									snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option").eq(1).attr("data-href");
									jQuery("#"+parentElement).find("#marketFileUrl").val(snapGlobal);
								} else {
									snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option:selected").eq(1).attr("data-href");
									if(snapGlobal==undefined){
										snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option:SELECTED").eq(1).attr("data-href");
									}
								}
							break;
						}*/						
					}
				}
			});
		}
	}
	/*
	function findWorksheetListFromUrl(o,p,parentElement){
		v = o;	
		if(v!=""){
			jQuery.ajax({
				url: __baseUrl + 'leads/findWorksheetListFromUrl',
				type:'POST',
				data:{v:v},
				cache:false,
				success:function(data){
					_d = jQuery.parseJSON(data);
					if(_d!=undefined  && _d.length>0){
						_option = "";
						for(i=0;i<_d.length;i++){
							_selected="";
							if(typeof p == 'string' || typeof p =='number'){
								if(p==_d[i].id){
									_selected = "SELECTED='SELECTED'";
								}
							}							
							if(i==0 && ( typeof p!='string' && typeof p!='number') ){
								_selected = "SELECTED='SELECTED'";								
							}
							_option +="<option  "+_selected+" value='"+_d[i].id+"' data-href='"+_d[i].full+"'>"+_d[i].text+"</option>";
						}
						switch(parentElement){
							case 'from_regular':
							jQuery("#marketWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>"+_option);
							if(jQuery("#marketWorksheetId").val()==""){
								jQuery("#"+parentElement).find("#marketWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
								snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option").eq(1).attr("data-href");
								jQuery("#"+parentElement).find("#marketFileUrl").val(snapGlobal);
							} else {
								snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option:selected").eq(1).attr("data-href");
								if(snapGlobal==undefined){
									snapGlobal = jQuery("#"+parentElement).find("#marketWorksheetId>option:SELECTED").eq(1).attr("data-href");
								}
							}
							break;
							case 'from_nonacquistion':
								jQuery("#"+parentElement).find("#acquisitionWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>"+_option);
								if(jQuery("#"+parentElement).find("#acquisitionWorksheetId").val()==""){
									jQuery("#"+parentElement).find("#acquisitionWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
									snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option").eq(1).attr("data-href");
									jQuery("#"+parentElement).find("#acquisitionFileUrl").val(snapGlobal);
								} else {
									snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option:selected").attr("data-href");
									if(snapGlobal==undefined){
										snapGlobal = jQuery("#"+parentElement).find("#acquisitionWorksheetId>option:SELECTED").attr("data-href");
									}
								}
							break;
							case 'from_litigation':
								jQuery("#litigationWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>");
								if(jQuery("#litigationWorksheetId").val()==""){
									jQuery("#"+parentElement).find("#litigationWorksheetId>option").eq(1).attr("SELECTED","SELECTED");
									snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option").eq(1).attr("data-href");
									jQuery("#"+parentElement).find("#marketFileUrl").val(snapGlobal);
								} else {
									snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option:selected").eq(1).attr("data-href");
									if(snapGlobal==undefined){
										snapGlobal = jQuery("#"+parentElement).find("#litigationWorksheetId>option:SELECTED").eq(1).attr("data-href");
									}
								}
							break;
						}						
					}
				}
			});
		}
	}
	*/
	
	function findWorksheetUrlMarket(o,p){
		u = o.find('option:selected').attr('data-href');
		if(u!=""){
			jQuery('#patentFileUrl').val(u);
			snapGlobal = u;
		}
    }

	function toggleCompanySales(){
		if($("#activityTable:visible tr.master a.showActivity").length>0){
			$("#activityTable:visible tr.master a.showActivity").unbind("click");
			$("#activityTable:visible tr.master a.showActivity").click(function(){
				$(this).parent().parent().next("tr").toggle();
			});
		}
		if($("#aquisitionTable:visible tr.master a.showActivity").length>0){
			$("#aquisitionTable:visible tr.master a.showActivity").unbind("click");
			$("#aquisitionTable:visible tr.master a.showActivity").click(function(){
				$(this).parent().parent().next("tr").toggle();
			});
		}
		if($("#preSaleActivityTable:visible tr.master a.showActivity").length>0){
			$("#preSaleActivityTable:visible tr.master a.showActivity").unbind("click");
			$("#preSaleActivityTable:visible tr.master a.showActivity").click(function(){
				$(this).parent().parent().next("tr").toggle();
			});
		}		
	}

function findMyContactList(cID){
	jQuery("#activityPerson").find("option").remove();
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+"dashboard/c_my_contact_list",
		data:{c:cID},
		cache:false,
		success:function(res){
			if(res!=""){
				_cList = jQuery.parseJSON(res);
				jQuery("#activityPerson").append("<option value=''>-- Select Person --</option>")
				if(_cList.length>0){
					for(c=0;c<_cList.length;c++){
						jQuery("#activityPerson").append("<option value='"+_cList[c].contactID+"'>"+_cList[c].firstName+" "+_cList[c].lastName+"</option>");
					}
				}
			} else{
				jQuery("#activityPerson").append("<option value=''>-- Select Person --</option>")
			}
		}
	});
}
function getCalendarColors(){
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'users/getCalendarColors',
		cache:false,
		success:function(d){
			if(d!=""){
				_d = jQuery.parseJSON(d);
				if(_d.length>0){
					_stringColors = '';
					for(i=0;i<_d.length;i++){
						_stringColors +='<a href="javascript://" onclick="eventColorActivate('+(i+1)+');"><span style="width:15px;height:15px;background:'+_d[i]+';float:left;margin-right:3px;"></span></a>';
					}
					jQuery('#eventColorImplement').css({paddingTop:'10px;',marginLeft:'5px;'}).html(_stringColors);
				}
			}
		}
	});
}

function eventColorActivate(id){
	jQuery("#eventColor").val(id);
}
function getCompaniesListSalesBroker() {
    jQuery("#open_sales_gd").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_sales_gd"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 
    openSlidebar(jQuery("#open_sales_gd")),

    slidebarOpenCallback(jQuery("#open_sales_gd")), jQuery("#open_sales_list").html('<iframe id="salesFormIframe" src="'+__baseUrl+'opportunity/sales_contact/'+leadGlobal+'/5" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_sales_gd").addClass("is-open"), open_sales_listResize())
}
function getCompaniesListPreSalesBroker(){
	 jQuery("#open_sales_gd").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_sales_gd"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 
    openSlidebar(jQuery("#open_sales_gd")),

    slidebarOpenCallback(jQuery("#open_sales_gd")), jQuery("#open_sales_list").html('<iframe id="salesFormIframe" src="'+__baseUrl+'opportunity/sales_pre_contact/'+leadGlobal+'/3" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_sales_gd").addClass("is-open"), open_sales_listResize())
}

function openComposeEmail(o){
	o.parent().find('input[name="sales_person[]"]').prop("checked",true);
	_mainActivity = jQuery("#activityMainType").val();
	_containerSelect = "";
	if(_mainActivity==1){
		_containerSelect = "activityTable";
	} else if(_mainActivity==2){
		_containerSelect = "aquisitionTable";
	} else if(_mainActivity==3){
		_containerSelect = "preSaleActivityTable";
	}
	if(_containerSelect==""){
		if(jQuery("#activityTable").is(":visible")){
			_containerSelect = "activityTable";
			_mainActivity=1;
			jQuery("#activityMainType").val(_mainActivity);
		} else if(jQuery("#aquisitionTable").is(":visible")){
			_containerSelect = "aquisitionTable";
			_mainActivity=2;
			jQuery("#activityMainType").val(_mainActivity);
		}  else if(jQuery("#preSaleActivityTable").is(":visible")){
			_containerSelect = "preSaleActivityTable";
			_mainActivity=3;
			jQuery("#activityMainType").val(_mainActivity);
		}		
	}
	_sales_emails = "";
	jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").each(function(){
		_sales_emails +=jQuery(this).attr('data-attr-em')+", ";
	});
	if(_emailActivate>0){
		composeEmail();
		jQuery("#eventT").val(jQuery("#activityMainType").val());			
		jQuery("#emailAccountType").val(_emailActivate);
		if(_sales_emails!=""){
			jQuery("#emailTo").val(_sales_emails);
			findDataRemove.push(_sales_emails);
		}
	}
	
}

function checkActivityLog(){
	_mainActivity = jQuery("#activityMainType").val();
	_containerSelect = "";
	if(_mainActivity==1){
		_containerSelect = "activityTable";
	} else if(_mainActivity==2){
		_containerSelect = "aquisitionTable";
	} else if(_mainActivity==3){
		_containerSelect = "preSaleActivityTable";
	}
	if(_containerSelect==""){
		if(jQuery("#activityTable").is(":visible")){
			_containerSelect = "activityTable";
			_mainActivity=1;
			jQuery("#activityMainType").val(_mainActivity);
		} else if(jQuery("#aquisitionTable").is(":visible")){
			_containerSelect = "aquisitionTable";
			_mainActivity=2;
			jQuery("#activityMainType").val(_mainActivity);
		}  else if(jQuery("#preSaleActivityTable").is(":visible")){
			_containerSelect = "preSaleActivityTable";
			_mainActivity=3;
			jQuery("#activityMainType").val(_mainActivity);
		}		
	}
	if(_containerSelect!=""){
	_c = jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").length;
	_p = "";
	/*_p = jQuery("#activityPerson").val();*/
	_error = 0;
	_message = "";
	_sales_emails = "";
	_nameUser="";
	_companyID="";
	_modifyNaeAndC = "";
	_companyName = "";
	jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").each(function(){
		_p +=jQuery(this).val()+",";
		_companyID +=jQuery(this).parent().parent().attr('data-c')+",";
		_sales_emails +=jQuery(this).attr('data-attr-em')+", ";
		_nameUser +=jQuery(this).attr('data-attr-name')+", ";
		_modifyNaeAndC +=jQuery(this).attr('data-attr-name')+' - '+jQuery(this).attr('data-attr-c-name')+",  ";
		_companyName +=jQuery(this).attr('data-attr-c-name')+", ";
	});
	if(_p!=""){
		jQuery("#activityPerson").val(_p.substr(0,_p.length-1));
	}
	_companyName = _companyName.substr(0,_companyName.length-1);
	_modifyNaeAndC = _modifyNaeAndC.substr(0,_modifyNaeAndC.length-1);
	switch(parseInt(jQuery("#activityType").val())){
		case 201:
			/*Email Campaign or Linkedin Campaign*/
			window.open("http://backyard.synpat.com/base/email_campaign/campaign.php","_BLANK");
		break;
		case 203:
			/*Assign Broker*/
			if(jQuery("#activityMainType").val()==1){
				if(jQuery('input[name="assign_delete[]"]:checked').length>0){				
					getCompaniesListSalesBroker();
				} else{
					jQuery("#activityType").val('');
					alert('Please select checkbox from list');
				}
			} else if(jQuery("#activityMainType").val()==3){
				if(jQuery('input[name="assign_delete[]"]:checked').length>0){				
					getCompaniesListPreSalesBroker();
				} else{
					jQuery("#activityType").val('');
					alert('Please select checkbox from list');
				}
			}
			
		break;
		case 204:
			/*Delete multiple Companies*/
			if(jQuery('input[name="assign_delete[]"]:checked').length>0){	
				deleteSalesInvitedC(jQuery('input[name="assign_delete[]"]:checked').parent().parent().attr('data-c'));			
			} else{
					jQuery("#activityType").val('');
					alert('Please select checkbox from list');
				}
		break;
		case 1:
			/*Call in or Conference Call*/
			if(_p!=""){
				getCallEvent(_modifyNaeAndC,_p,_companyID);
				jQuery("#callType>option").each(function(){
					switch(parseInt(jQuery(this).val())){
						case 1:
							jQuery(this).removeAttr('disabled');
						break;
						case 2:
							jQuery(this).prop('disabled',true);
						break;
						case 37:
							jQuery(this).removeAttr('disabled');
						break;
						case 207:
							jQuery(this).prop('disabled',true);
						break;
					}
				});
			} else {
				jQuery("#activityType").val('');
				alert("Please select a person for Call");
			}			
		break;
		case 207:
			/*Meeting memo*/
			if(_p!=""){
				getCallEvent(_modifyNaeAndC,_p,_companyID);
				jQuery("#callType>option").each(function(){
					switch(parseInt(jQuery(this).val())){
						case 1:
							jQuery(this).prop('disabled',true);
						break;
						case 2:
							jQuery(this).prop('disabled',true);
						break;
						case 37:
							jQuery(this).prop('disabled',true);
						break;
						case 207:
							jQuery(this).removeAttr('disabled');
							jQuery(this).prop('selected',true);
						break;
					}
				});
			} else {
				jQuery("#activityType").val('');
				alert("Please select a person for Call");
			}			
		break;
		case 208:
			getPredefinedMessages(4);
		break;
		case 7:
			getPredefinedMessages(3);
		break;
		case 36:
			moveEmails();
		break;
		case 10:
			/*Task*/     
			openTaskModal();
		break;
		case 11:
			/*Calendar Event*/
			if(_sales_emails!=""){
				open_all_invitation();
				$(function(){$(".date_calendar").datepicker({format:"yyyy-mm-dd"});$(".time-calendar").timepicker()});
				resetEventForm();
				findDataRemoveEve.push(_sales_emails);
				jQuery("#attendeeEmail").val(_sales_emails);
				jQuery("#lead_id").val(leadGlobal);
				jQuery("#acitivity_event_type").val(_mainActivity);
				jQuery("#eventSummary").val(leadNameGlobal+' '+_nameUser.substr(0,_nameUser.length-2)+' / '+jQuery('.user-account-btn').find('span').eq(0).text());
				/*getCalendarColors();*/
			} else {
				jQuery("#activityType").val('');
				alert("Please select person to whom you want to create a Calendar event.");
			}
		break;
		case 3:
		if(_c==0){
			_message = "Please select person first.";
			jQuery("#activityType").val('');
			_error = 1;
		}
		
		if(_error==0){
			if (_p=="" || _p==undefined){
				_message = "Please select person.";
				_error = 1;
			}
		}		
		if(_error==0){			
			getLeadTemplates(1);
		} else {
			if(_message!=""){
				jQuery("#activityType").val('');
				jQuery("#activityPerson").val('');
				alert(_message);
			} else {				
				alert("There is something wrong. Please refresh you page.");
			}			
		}
		break;
		case 5:
			_p = "";
			_sales_emails="";
			jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").each(function(){
				_p +=jQuery(this).val()+",";
				_sales_emails +=jQuery(this).attr('data-attr-linkedin')+", ";
			});
			if(_p!=""){
				jQuery("#activityPerson").val(_p.substr(0,_p.length-1));
			}
			if(_sales_emails==""){
				jQuery("#activityType").val('');
				jQuery("#activityPerson").val('');
				alert("No contact member has linkedin profile url.");
			} else {
				getLeadTemplates(2);
			}
		break;
		case 9:
			composeEmail();
			jQuery("#eventT").val(jQuery("#activityMainType").val());			
			jQuery("#emailAccountType").val(1);
			if(_sales_emails!=""){
				jQuery("#emailTo").val(_sales_emails);
				findDataRemove.push(_sales_emails);
			}			
		break;
		case 205:
			composeEmail();
			jQuery("#eventT").val(jQuery("#activityMainType").val());			
			/*jQuery("#emailAccount").val(1);*/
			if(_sales_emails!=""){
				jQuery("#emailTo").val(_sales_emails);
				findDataRemove.push(_sales_emails);
			}	
			jQuery("#emailAccountType").val(2);
		break;
	} 
	} else {
		alert("There is something wrong, Please refresh your page.");
		jQuery("#activityType").val('');
	}
}
window.showMessageOnFly = function(mesg,clas){
	jQuery('#sb-site').prepend('<div class="col-lg-12 alert '+clas+' noticeInfoAlert mrg5T" style="position:absolute;z-index:9999">'+mesg+'</div>');setTimeout(function(){jQuery('.noticeInfoAlert').remove()},3000);
}

window.successMoveTemplate=function(data,textStatus,xhr){
	if(data>0){
		showMessageOnFly("Templates copy to lead","alert-info");
	}
}
window.embedTemplateMessage = function(data,textStatus,xhr){
	if(data!=""){
		if(typeof data.subject!="undefined"){
			jQuery("#CallPurpose").val(data.subject);
		}
		if(typeof data.template_html!="undefined"){
			jQuery("#predefined_template").code(data.template_html);
		}		
	}
}
function getCallEvent(_nameUser,_p,_companyID){
	jQuery("#formCallInOut").get(0).reset();
	jQuery("#callInOutModalLabel").html(leadNameGlobal);
	jQuery("#callLeadId").val(leadGlobal);
	jQuery("#callMainActivity").val(jQuery("#activityMainType").val());
	jQuery("#callPerson").val(_p.substr(0,_p.length-1));	
	jQuery("#callCompanyID").val(_companyID.substr(0,_companyID.length-1));	
	jQuery("#callParticipant").val(_nameUser.substr(0,_nameUser.length-2));	
	/*Check which activity open*/
	stageName= "";
	if(_mainActivity=="1"){
		obj= jQuery("#activityTable").find('input[name="sales_person[]"]:checked').eq(0).parent().parent().parent().parent().parent().parent().prev();
		if(obj.hasClass('master')){
			stageName = obj.find('select[name="stage_progress"]').find('option:selected').attr('class');
		}
	}
	_w= jQuery(window).height();
	jQuery("#predefined_template").val('');
	jQuery("#predefined_template").destroy();
	initEditor('#predefined_template',_w-250);
	if(stageName!=""){
		data = {st:stageName,lead:leadGlobal};
		call(__baseUrl+'users/find_message_template_stage','POST',data,embedTemplateMessage,'json');
	}
	jQuery('#callInOutModal').modal('show');
	/*jQuery('#CallExecutionDate').datepicker("destroy");*/
	_da = moment(new Date()).tz('America/Los_Angeles').format('YYYY-MM-D h:mm:ss a');
	_date = new Date(_da);
	jQuery('#CallExecutionDate').datepicker({format:"yyyy-mm-dd"}).datepicker('setDate', _date);
	jQuery("#nextCallDate").datepicker({format:"yyyy-mm-dd"});
	_hr = _date.getHours();
	_mn = _date.getMinutes();
	jQuery("#callTimeStart").val(_hr);
	jQuery("#callTimeEnd").val(_hr);
	/*jQuery("#company_calendar").html('<iframe src="'+__baseUrl+'users/company_calendar" style="width:100%;height:350px"></iframe>');*/
	/*users/company_calendar*/
	/*jQuery("#callTimeStart>option").each(function(){
		if(jQuery(this).attr('value')==_hr){
			jQuery(this).prop("selected",true);
		}
	});
	jQuery("#callTimeEnd>option").each(function(){
		if(jQuery(this).attr('value')==_mn){
			jQuery(this).prop("selected",true);
		}
	});*/
}
window.successMessageAfterTemplateSave = function(data,textStatus,xhr){
	if(data>0){
		showMessageOnFly("Template saved.","alert-info");
	}
}
function saveAsInLeadScript(t){
	/*Save in lead bank*/
	_mainActivity = jQuery("#activityMainType").val();
	stageName= "";
	if(_mainActivity=="1"){
		obj= jQuery("#activityTable").find('input[name="sales_person[]"]:checked').eq(0).parent().parent().parent().parent().parent().parent().prev();
		if(obj.hasClass('master')){
			stageName = obj.find('select[name="stage_progress"]').find('option:selected').attr('class');
		}
	}
	if(stageName!=""){
		data={subject:jQuery("#CallPurpose").val(),template:jQuery("#predefined_template").code(),lead:leadGlobal,type:t,stage:stageName};
		call(__baseUrl+'users/saveTemplateScript','POST',data,successMessageAfterTemplateSave,'text');
	} else {
		showMessageOnFly("Error.","alert-warning");
	}	
}
function editActivitiesData(lead,activityID){
	if(lead!=0 && activityID>0){
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'leads/getCallData',
			data:{activity:activityID,lead:lead,t:jQuery("#activityMainType").val()},
			cache:false,
			success:function(d){
				if(d!=''){
					_activity= jQuery.parseJSON(d);
					if(typeof _activity.id!='undefined'){
						console.log('1');
						jQuery("#formCallInOut").get(0).reset();
						jQuery("#callInOutModalLabel").html(leadNameGlobal);
						jQuery("#callLeadId").val(lead);
						jQuery("#callMainActivity").val(jQuery("#activityMainType").val());
						jQuery("#callPerson").val(_activity.contact_id);	
						jQuery("#callCompanyID").val(_activity.company_id);	
						jQuery("#callParticipant").val(_activity.personName);
						_date = _activity.activity_date;
						date = _date.split(' ');
						jQuery("#").val(date[0]);
						_time = date[1].split(':');
						jQuery("#CallExecutionDate").val(date[0]);
						jQuery("#callTimeStart").val(_time[0]);
						jQuery("#callId").val(_activity.id);
						note = _activity.note;
						message = note.split('<br/>');
						jQuery("#callTimeEnd").val(_time[1]);						
						jQuery("#CallPurpose").val(message[0]);
						jQuery("#callNote").val(message[1]);
						jQuery("#callType").val(_activity.type);
						jQuery("#callFromUserId").val(_activity.type);
						jQuery('#callInOutModal').modal('show');
					}
				}
			}
		})
	}
}
function findContainer(){
	_mainActivity = jQuery("#activityMainType").val();
	_containerSelect = "";
	if(_mainActivity==1){
		_containerSelect = "activityTable";
	} else if(_mainActivity==2){
		_containerSelect = "aquisitionTable";
	} else if(_mainActivity==3){
		_containerSelect = "preSaleActivityTable";
	}
	if(_containerSelect==""){
		if(jQuery("#activityTable").is(":visible")){
			_containerSelect = "activityTable";
			_mainActivity=1;
			jQuery("#activityMainType").val(_mainActivity);
		} else if(jQuery("#aquisitionTable").is(":visible")){
			_containerSelect = "aquisitionTable";
			_mainActivity=2;
			jQuery("#activityMainType").val(_mainActivity);
		}  else if(jQuery("#preSaleActivityTable").is(":visible")){
			_containerSelect = "preSaleActivityTable";
			_mainActivity=3;
			jQuery("#activityMainType").val(_mainActivity);
		}		
	}
	return _containerSelect;
}

function saveCall(s){
	if(jQuery("#callMainActivity").val()!="" && jQuery("#callPerson").val()!=""){
		jQuery("#saveBtnCall").addClass("hide");
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'leads/callinout',
			data:jQuery("#formCallInOut").serializeArray(),
			cache:false,
			success:function(res){
				jQuery("#saveBtnCall").removeClass("hide");
				if(res>0){
					jQuery('#callInOutModal').modal('hide');
					/*Open Calendar event popup*/
					_containerSelect = findContainer();
					_findActivity = jQuery("#activityMainType").val();
					_p = '';
					_sales_emails ='';
					_nameUser = '';
					_companyID = '';
					jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").each(function(){
						_p +=jQuery(this).val()+",";
						_sales_emails +=jQuery(this).attr('data-attr-em')+", ";
						_nameUser +=jQuery(this).attr('data-attr-name')+", ";
						_o = jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").parent().parent().parent().parent().parent().parent().prev();
						_companyID +=_o.attr('data-c')+",";
					});
					if(_p!=""){
						jQuery("#activityPerson").val(_p.substr(0,_p.length-1));
					}
					if(typeof s!="undefined"){
						if(s==1){
							open_all_invitation();
							$(function(){$(".date_calendar").datepicker({format:"yyyy-mm-dd"});$(".time-calendar").timepicker()});
							resetEventForm();
							findDataRemoveEve.push(_sales_emails);
							jQuery("#attendeeEmail").val(_sales_emails);
							jQuery("#lead_id").val(leadGlobal);
							jQuery("#acitivity_event_type").val(_mainActivity);
							jQuery("#eventSummary").val(leadNameGlobal+' '+_nameUser.substr(0,_nameUser.length-2)+' / '+jQuery('.user-account-btn').find('span').eq(0).text());
						} else if(s==2){
							openTaskModal();
							jQuery("#activityPersonID").val(_p.substr(0,_p.length-1));
							jQuery("#activityCompanyID").val(_companyID.substr(0,_companyID.length-1));
							jQuery("#activityActivityType").val(_findActivity);
						}
					}					
					/*End*/
					jQuery("#activityType").val('');
					jQuery("#formCallInOut").get(0).reset();
					refreshAcquisitionAndSalesActivity();
				} else {
					showMessageOnFly("There is some problem in this page. Please refresh.","alert-warning");
				}
			}
		});
	} else {
		showMessageOnFly("There is some problem in this page. Please refresh.","alert-warning");
	}
}
_spreadsheet=[];
function findWholeInActivity(){
	_mainActivity = jQuery("#activityMainType").val();
	if(_mainActivity==1){
		_table="<table class='table table-bordered' id='sortingActivityTable'><thead><tr><th>Name</th><th>Company</th><th>Title</th><th>Telephone</th><th>Email</th><th>LinkedIn</th></tr></thead><tbody>";
		_spreadsheet=[];
		jQuery("#activityTable").find('tbody').find('tr.master').each(function(){
			_parent = jQuery(this);
			_companyName = _parent.find('a').eq(1).find('b').text();
			_parent.next().find('tbody>tr.salesFDroppable').each(function(index,element){
				_spreadRows={};
				_checkObject = jQuery(this).find('td>input[type="checkbox"]');
				_name= _checkObject.attr('data-attr-name');
				_title= jQuery(this).find('td').eq(2).text();
				_telephone="";
				_id= _checkObject.attr('value');
				if(jQuery(this).find('td').eq(jQuery(this).find('td').length-1).find('a').length>0){
					_telephone= jQuery(this).find('td').eq(jQuery(this).find('td').length-1).find('a').eq(0).text();
				}				
				_email= _checkObject.attr('data-attr-em');
				_linkedin= _checkObject.attr('data-attr-linkedin');
				if(_email=="undefined" || _email==undefined){
					_email='';
				}
				if(_linkedin=="undefined" || _linkedin==undefined){
					_linkedin='';
				}
				if(_name=="undefined" || _name==undefined){
					_name='';
				}
				if(_title=="undefined" || _title==undefined){
					_title='';
				}
				if(_telephone=="undefined" || _telephone==undefined){
					_telephone='';
				}
				if(_name!=""){
					_table +='<tr><td>'+_name+'</td><td>'+_companyName+'</td><td>'+_title+'</td><td>'+_telephone+'</td><td>'+_email+'</td><td>'+_linkedin+'</td></tr>';
					_spreadRows.name = _name;
					_spreadRows.title = _title;
					_spreadRows.company = _companyName;
					_spreadRows.telephone = _telephone;
					_spreadRows.email = _email;
					_spreadRows.linkedin = _linkedin;
					_spreadRows.contact_id = _id;					
					_spreadsheet.push(_spreadRows);
				}	
			})
					
		});		
		_table +="</tbody></table>";
		jQuery("#sortingPopup").find('.modal-body').html('');
		jQuery("#sortingPopup").css({width:'100%',left:'0%',marginLeft:'0px'});
		jQuery("#sortingPopup").find('.modal-dialog').css('width','100%');
		_button="<div class='row'><div class='col-lg-12'><a class='btn btn-primary' href='javascript://' onclick='createSpreadSheetForWhole(jQuery(this))'>Create SpreadSheet</a></div></div>"
		jQuery("#sortingPopup").find('.modal-body').append(_button+'<div class="row"><div class="col-lg-12">'+_table+'</div></div>');
		jQuery("#sortingPopup").off('shown.bs.modal').on('shown.bs.modal', function() {
			jQuery("#sortingActivityTable").DataTable({"paging": false,"destroy":true,"scrollY":"400px","language": {"emptyTable": "No record found!"}});
		});
		jQuery("#sortingPopup").modal('show');
	}
}

function createSpreadSheetForWhole(){
	if(leadGlobal>0 && _spreadsheet.length>0 && jQuery("#activityMainType").val()>0){
		data = {lead:leadGlobal,activity:jQuery("#activityMainType").val(),rec:JSON.stringify(_spreadsheet)};
		call(__baseUrl+'leads/create_spreadsheet_for_hole','POST',data,openSpreadSheetAfterCreate,'json');
	}
}
window.openSpreadSheetAfterCreate = function(data,textStatus,xhr){
	console.log(data);
	if(typeof data.url!="undefined" && data.url!=""){
		/*open_drive_files(data.url);*/
		showMessageOnFly("Project created.","alert-info");
	} else {
		showMessageOnFly("Error! while creating google spreadsheet.","alert-warning");
	}
}

function findW5Width(container){
	widthI = 0;
	openAllCompanies();
	jQuery(container).find('tr.master').each(function(){
		jQuery(this).find('td').each(function(){
			jQuery(this).css({border:'0px solid #d1c8c8',borderTop:'1px solid #67B7F5',borderRight:'1px solid #d1c8c8',borderLeft:'1px solid #d1c8c8'});
		})
		_nextHidden = jQuery(this).next();
		_nextHidden.find('table').eq(1).find('tr').each(function(index){
			if(widthI<_nextHidden.find('table').eq(1).find('tr').eq(index).find('td').eq(2).outerWidth()){				
				widthI = _nextHidden.find('table').eq(1).find('tr').eq(index).find('td').eq(2).outerWidth();
			}
		});
		_height = _nextHidden.children('td').eq(0).find('table').find('tbody>tr').length;
		_height1 = _nextHidden.children('td').eq(1).find('table').find('tr').length;
		if(_height1>_height){
			_nextHidden.children('td').eq(0).find('table').find('tr:last').find('td').each(function(){
				jQuery(this).attr('style','border-bottom-width:1px;border-left-width:0px');
			});
		}
		if(_height1>0){
			_nextHidden.children('td').eq(1).find('table').find('tr').eq(0).find('td').each(function(){
				_style=jQuery(this).attr('style');
				_style +=';border-top-width:0px;';
				jQuery(this).attr('style',_style);
			});
		}
		/*_nextHidden.children('td').eq(0).attr('style','border-top-width:1px;');
		_nextHidden.children('td').eq(1).attr('style','border-top-width:1px;');*/
	});
	openAllCompanies();
	return widthI;
}

function runFixedTableLayoutProccess(cl){
	if(cl==1){
		_w5 = findW5Width("#activityTable");
		if(_w5>0){			
			_main = jQuery("#activityTable").parent().width();		
			jQuery("#activityTable").find('tr.master').each(function(){
				_w = jQuery("#activityTable").find('tr.master').find('td').eq(0).outerWidth();
				_w2 = jQuery("#activityTable").find('tr.master').find('td').eq(1).outerWidth();
				_firstTdWidth = _w + _w2;
				_w3 = jQuery("#activityTable").find('tr.master').find('td').eq(2).outerWidth();
				_w4 = jQuery("#activityTable").find('tr.master').find('td').eq(3).outerWidth();
				jQuery("#activityTable").find('tr.master').find('td').eq(4).css({width:_w5+'px'});
				jQuery("#activityTable").find('thead').find('th').eq(4).css({width:_w5+'px'});
				_secondTdWidth = _w3 + _w4 + _w5;
				_nextHidden = jQuery(this).next();
				_child1 = _nextHidden.children('td').eq(0).css({width:_firstTdWidth+'px'});
				_child2 = _nextHidden.children('td').eq(1).css({width:_secondTdWidth+'px'});				
				_nextHidden.find('table').eq(0).css({width:_firstTdWidth+'px'});
				_tdW1 = 65;
				_tdW2 = _w - _tdW1-1;
				_tdW4 = 110;
				_tdW3 = _w2 - _tdW4;
				_nextHidden.find('table').eq(0).find('tr').find('th').eq(0).css({width:_tdW1+'px',borderTopWidth:'0px'});
				_nextHidden.find('table').eq(0).find('tr').find('th').eq(1).css({width:_tdW2+'px',borderTopWidth:'0px'});
				_nextHidden.find('table').eq(0).find('tr').find('th').eq(2).css({width:_tdW3+'px',borderTopWidth:'0px'});
				_nextHidden.find('table').eq(0).find('tr').find('th').eq(3).css({width:_tdW4+'px',borderTopWidth:'0px'});
				_nextHidden.find('table').eq(0).find('tr').each(function(){
					jQuery(this).find('td').eq(0).css({width:_tdW1+'px'});
					jQuery(this).find('td').eq(1).css({width:_tdW2+'px'});
					jQuery(this).find('td').eq(2).css({width:_tdW3+'px'});
					jQuery(this).find('td').eq(3).css({width:_tdW4+'px'});
				});
				_nextHidden.find('table').eq(1).css({width:_secondTdWidth+'px'});
				_nextHidden.find('table').eq(1).find('td').eq(0).css({width:_w3+'px'});
				_nextHidden.find('table').eq(1).find('td').eq(1).css({width:_w4+'px'});
				_nextHidden.find('table').eq(1).find('td').eq(2).css({width:_w5+'px'});
			});
		}		
	}
}
function openTemplateEditor(){	$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),jQuery("#open_template_editor").addClass("sb-active"),openSlidebar(jQuery("#open_template_editor")),slidebarOpenCallback(jQuery("#open_template_editor"));$(".dropdown-toggle").dropdown();}
function closeTemplateEditor(){jQuery("#templateEditor").code('');jQuery(".modal-backdrop-drive").remove();jQuery("#open_template_editor").removeClass("sb-active").removeClass("is-open");closeSlidebar(jQuery("#open_template_editor"));jQuery('#template_id').val(0);}
function saveActivity(){
	if(leadGlobal>0){
		
		if(jQuery("input[name='sales_person[]']").is(':checked')){
			jQuery("input[name='sales_person[]']:checked").parent().parent().parent().parent().parent().parent()
			jQuery("#activityCid").val(jQuery("input[name='sales_person[]']:checked").parent().parent().parent().parent().parent().parent().prev().attr('data-c'));
			jQuery("#activityPerson").val(jQuery("input[name='sales_person[]']:checked").val());
			jQuery("#activityLeadId").val(leadGlobal);
			if(jQuery("#activityType").val()!=""){
				if(jQuery("#activityPerson").val()!=""){
					if(jQuery("#activityNote").val()!=""){						
						jQuery("#btnSaveActivity").addClass("hide");
						jQuery.ajax({
							type:'POST',
							url:__baseUrl+'leads/sales_acititity_data',
							data:jQuery("#frm_sales_activity").serializeArray(),
							cache:false,
							success:function(res){
								jQuery("#btnSaveActivity").removeClass("hide");
								if(res>0){
									jQuery("#frm_sales_activity").get(0).reset();
									refreshAcquisitionAndSalesActivity();
								} else {
									alert("Server busy, try after sometime.");
								}
							}
						});
					} else {
						alert("Please enter note for the activity.");
					}					
				} else {
					alert("Please select person.");
				}				
			} else {
				alert("Please select person.");
			}
		} else{
			alert("Please select company first.");
		}
	}
}
function saveTemplate(){
	if(jQuery("#template_file_name").val()==""){
		alert("Please enter template name");
	} else {
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/save_new_template',
			data:{temp:jQuery("#templateEditor").code(),subject:jQuery("#template_subject").val(),name:jQuery("#template_file_name").val(),activity_type:jQuery("#activityType").val(),lead_id:window.leadGlobal},
			cache:false,
			success:function(data){
				if(data>0){
					alert("Template Saved");
				} else {
					alert("Try after sometime");
				}
			}
		});
	}
}
function updateTemplate(){
	if(jQuery("#template_file_name").val()==""){
		alert("Please enter template name");
	} else {
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/update_template',
			data:{temp:jQuery("#templateEditor").code(),id:jQuery('#template_id').val(),subject:jQuery("#template_subject").val(),name:jQuery("#template_file_name").val(),activity_type:jQuery("#activityType").val(),lead_id:window.leadGlobal},
			cache:false,
			success:function(data){
				if(data>0){
					alert("Template updated");
				} else {
					alert("Try after time");
				}
			}
		});
	}	
}
function saveToFileFolder(){
	if(jQuery("#template_file_name").val()==""){
		alert("Please enter template file name");
	} else {
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/save_template_file',
			data:{temp:jQuery("#templateEditor").code(),lead_id:leadGlobal,name:jQuery("#template_file_name").val(),subject:jQuery("#template_subject").val(),type:jQuery("#template_type").val()},
			cache:false,
			success:function(data){
				if(data>0){
					alert("File created successfully.");
				} else {
					alert("Please try after time");
				}
			}
		});
	}
}

function sendLinkedMessage(message,name,subject){
	if(jQuery("#linked_frm").length>0){
		jQuery("#linked_frm").remove();
	}
	var form = document.createElement("form");
	form.setAttribute("method", "POST");
	form.setAttribute("action", "http://backyard.synpat.com/base/linkedin/index.php");
	form.setAttribute("target", "_blank");
	form.setAttribute("id", "linked_frm");	
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "lead_id");
	hiddenField.setAttribute("id", "lead_id");
	hiddenField.setAttribute("value", leadGlobal);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "lead_name");
	hiddenField.setAttribute("id", "lead_name");
	hiddenField.setAttribute("value", leadNameGlobal);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "setup");
	hiddenField.setAttribute("id", "setup");
	hiddenField.setAttribute("value", 'implementation');
	form.appendChild(hiddenField);
	_sales_emails="",_personNames="",_companyNames="";
	_mainActivity = jQuery("#activityMainType").val();
	_container = "";
	if(_mainActivity==1){
		_container = "activityTable";
	} else if(_mainActivity==2){
		_container = "aquisitionTable";
	}
	jQuery('#'+_container).find("input[name='sales_person[]']:checked").each(function(){		
		_sales_emails +=jQuery(this).attr('data-attr-linkedin')+", ";
		_personNames +=jQuery(this).attr('data-attr-name')+", ";
		_companyNames +=jQuery(this).attr('data-attr-c-name')+", ";
	});
	var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "activity_type");
		hiddenField.setAttribute("id", "activity_type");
		hiddenField.setAttribute("value", _mainActivity);
		form.appendChild(hiddenField);
	if(_sales_emails!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "email_list");
		hiddenField.setAttribute("id", "email_list");
		hiddenField.setAttribute("value", _sales_emails);
		form.appendChild(hiddenField);
	}
	if(_personNames!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "person_list");
		hiddenField.setAttribute("id", "person_list");
		hiddenField.setAttribute("value", _personNames);
		form.appendChild(hiddenField);
	}
	if(_companyNames!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "company_list");
		hiddenField.setAttribute("id", "company_list");
		hiddenField.setAttribute("value", _companyNames);
		form.appendChild(hiddenField);
	}
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "user_id");
	hiddenField.setAttribute("id", "user_id");
	hiddenField.setAttribute("value", _CS);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "email_message");
	hiddenField.setAttribute("id", "email_message");
	hiddenField.setAttribute("value", message);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "template_file");
	hiddenField.setAttribute("id", "template_file");
	hiddenField.setAttribute("value", name);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "subject");
	hiddenField.setAttribute("id", "subject");
	hiddenField.setAttribute("value", subject);
	form.appendChild(hiddenField);
	document.body.appendChild(form);	
	document.forms['linked_frm'].submit();		
}

function sendEmailImap(html,name,subject){
	if(jQuery("#imap_frm").length>0){
		jQuery("#imap_frm").remove();
	}
	var form = document.createElement("form");
	form.setAttribute("method", "POST");
	form.setAttribute("action", "http://backyard.synpat.com/base/email_campaign/index.php");
	form.setAttribute("target", "_blank");
	form.setAttribute("id", "imap_frm");	
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "lead_id");
	hiddenField.setAttribute("id", "lead_id");
	hiddenField.setAttribute("value", leadGlobal);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "lead_name");
	hiddenField.setAttribute("id", "lead_name");
	hiddenField.setAttribute("value", leadNameGlobal);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "setup");
	hiddenField.setAttribute("id", "setup");
	hiddenField.setAttribute("value", 'implementation');
	form.appendChild(hiddenField);
	_sales_emails="",_personNames="",_companyNames="";	
	_mainActivity = jQuery("#activityMainType").val();
	_container = "";
	if(_mainActivity==1){
		_container = "activityTable";
	} else if(_mainActivity==2){
		_container = "aquisitionTable";
	}
	jQuery('#'+_container).find("input[name='sales_person[]']:checked").each(function(){		
		_sales_emails +=jQuery(this).attr('data-attr-em')+", ";
		_personNames +=jQuery(this).attr('data-attr-name')+", ";
		_companyNames +=jQuery(this).attr('data-attr-c-name')+", ";
	});
	var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "activity_type");
		hiddenField.setAttribute("id", "activity_type");
		hiddenField.setAttribute("value", _mainActivity);
		form.appendChild(hiddenField);
	if(_sales_emails!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "email_list");
		hiddenField.setAttribute("id", "email_list");
		hiddenField.setAttribute("value", _sales_emails);
		form.appendChild(hiddenField);
	}
	if(_personNames!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "person_list");
		hiddenField.setAttribute("id", "person_list");
		hiddenField.setAttribute("value", _personNames);
		form.appendChild(hiddenField);
	}
	if(_companyNames!=""){
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "company_list");
		hiddenField.setAttribute("id", "company_list");
		hiddenField.setAttribute("value", _companyNames);
		form.appendChild(hiddenField);
	}
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "user_id");
	hiddenField.setAttribute("id", "user_id");
	hiddenField.setAttribute("value", _CS);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "email_message");
	hiddenField.setAttribute("id", "email_message");
	hiddenField.setAttribute("value", html);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "template_file");
	hiddenField.setAttribute("id", "template_file");
	hiddenField.setAttribute("value", name);
	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "subject");
	hiddenField.setAttribute("id", "subject");
	hiddenField.setAttribute("value", subject);
	form.appendChild(hiddenField);
	document.body.appendChild(form);	
	document.forms['imap_frm'].submit();		
}
function flagSaleActivity(t,o){
	o.parent().find("input[name='sales_person[]']").prop('checked',true);
	if(t==1){
		jQuery("#eventCid").val(o.parent().parent().attr('data-c'));
		jQuery("#eventPid").val(o.parent().parent().attr('data-p'));
		jQuery("#eventT").val(jQuery("#activityMainType").val());
		jQuery("#emailDocUrl").val("");jQuery("#attach_droppable").empty();
		jQuery("#anotherSys").val(0);
		jQuery("#emailCC").css("width","725px");
		$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>');
		jQuery("#gmail_message_modal").css("display","block").addClass("sb-active").animate({textIndent:0},{step:function(e,j){$(this).css("transform","translate(350px)")},duration:"slow"},"linear");
		jQuery("body").removeAttr("onselectstart");document.oncontextmenu=new Function("return true");
		$(".dropdown-toggle").dropdown();
		jQuery("#emailThreadId").val("");
		jQuery("#emailMessageId").val("");
		jQuery("#attach_droppable").empty();
		jQuery("#emailSubject").val('').removeAttr("readonly");
		jQuery("#gmail_message").css("display","block");
		jQuery(".gmail-modal").css("display","block");
		if(mainLogBox==1){jQuery("#legal_log").val(1)}else{jQuery("#legal_log").val(0)}
		jQuery("#gmail_message_modal").find("h4").html("Compose Message: "+leadNameGlobal);jQuery("#messageLeadId").val(leadGlobal);
		jQuery("body").data("modalzindex",jQuery("#gmail_message_modal").css("z-index"));
		if(o.attr('data-attr-em')!=undefined){
			findDataRemove.push(o.attr('data-attr-em'));
		jQuery("#emailTo").val(o.attr('data-attr-em')+', ');
		}
		
	} else if(t==2){
		sendLinkedMessage('');
	}
}
function refreshAcquisitionAndSalesActivity(){
	/*jQuery("#activityType").val('');*/
	if(leadGlobal>0){
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'leads/findAcquisitionAndSalesData',
			data:{boxes:leadGlobal},
			cache:false,
			success:function(data){
				if(data!=""){
					_data = jQuery.parseJSON(data);
					/*jQuery("#activityType").val('');*/
					acquisitionImport(_data);
					_leadCompaniesAssignBroker = _data.broker_as_companies;
					salesActivityList(_data.sales_activity);
					preSalesActivityList(_data.presales_activity);
					docFileDraggable();
					initHoverEmailClose();
				}
			}
		});
	}
}
function processContact(b){
	_activity = jQuery("#activityMainType").val();
	_container = "";
	switch(parseInt(_activity)){
		case 1:
			_container = "#activityTable";
		break;
		case 2:
			_container = "#aquisitionTable";
		break;
		case 3:
			_container = "#preSaleActivityTable";
		break;
	}
	if(_container!=""){
		if(jQuery(_container).find('input[name="sales_person[]"]:checked').length>0){
			/*Append new Contact or update contact data*/
			data={edit_link:b};
			call(__baseUrl+'opportunity/findContact','POST',data,embedRecord,'json');
		} else {
			refreshAcquisitionAndSalesActivity();
		}
	}
}
window.embedRecord = function(data,textStatus,xhr){
	console.log(data);
	if(typeof data.id!="undefined"){
		_activity = jQuery("#activityMainType").val();
		_container = "";
		switch(parseInt(_activity)){
			case 1:
				_container = "#activityTable";
			break;
			case 2:
				_container = "#aquisitionTable";
			break;
			case 3:
				_container = "#preSaleActivityTable";
			break;
		}
		console.log(_container);
		if(_container!=""){
			jQuery(_container).find('tbody>tr.master').each(function(){
				_companyName = jQuery(this).find('a').eq(1).find('b').text();
				console.log(jQuery(this).attr('data-c')+":FF:"+data.company_id);
				if(jQuery(this).attr('data-c')==data.company_id){
					_personTables = jQuery(this).next();
					social_links = '';
					if(data.email!=''){
						social_links += '<a onclick="openComposeEmail(jQuery(this));" href="javascript://"><i class="glyph-icon icon-envelope-square"></i></a>';
					}
					if(data.linkedin_url!=''){
						social_links += '<a href="'+data.linkedin_url+'" target="_blank"><i class="glyph-icon icon-linkedin"></i></a>';
					}
					gateway='';
					no_contact='';
					if(data.gateway=='1'){
						gateway = '&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/gateway.png" style="width:16px;"/>';
					}
					if(data.no_contact=='1'){
						no_contact = '&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/no_contact.jpg"/>';
					}
					console.log(_personTables.find('tr.salesFDroppable').length);
					if(_personTables.find('tr.salesFDroppable').length>0){
						/*Update*/
						append = false;
						_personTables.find('tr.salesFDroppable').each(function(){
							if(jQuery(this).attr('data-p')==data.id){
								append = true;
								jQuery(this).html('<td style="border-left:0px; width:65px;"><input name="sales_person[]" class="sales-activity-checkbox" data-attr-em="'+data.email+'" data-attr-linkedin="'+data.linkedin_url+'" data-attr-name="'+data.name+'" data-attr-c-name="'+_companyName+'" type="checkbox" value="'+data.id+'">'+social_links+'</td><td><a href="javascript://" onclick="editContact('+data.id+')">'+data.name+gateway+no_contact+'</a></td><td>'+data.job_title+'</td><td style=""><a href="javascript://" onclick="callFromLandline('+encodeURIComponent(data.phone)+',jQuery(this))">'+data.phone+'</a></td>');
							}
						});
						if(append===false){
							_personTables.find('tbody').html('<tr class="salesFDroppable" data-c="'+data.company_id+'" data-p="'+data.id+'"><td style="border-left:0px; width:65px;"><input name="sales_person[]" class="sales-activity-checkbox" data-attr-em="'+data.email+'" data-attr-linkedin="'+data.linkedin_url+'" data-attr-name="'+data.name+'" data-attr-c-name="'+_companyName+'" type="checkbox" value="'+data.id+'">'+social_links+'</td><td><a href="javascript://" onclick="editContact('+data.id+')">'+data.name+gateway+no_contact+'</a></td><td>'+data.job_title+'</td><td style=""><a href="javascript://" onclick="callFromLandline('+encodeURIComponent(data.phone)+',jQuery(this))">'+data.phone+'</a></td></tr>');
						}
					} else {
						/*Insert*/						
						_personTables.find('tbody').html('<tr class="salesFDroppable" data-c="'+data.company_id+'" data-p="'+data.id+'"><td style="border-left:0px; width:65px;"><input name="sales_person[]" class="sales-activity-checkbox" data-attr-em="'+data.email+'" data-attr-linkedin="'+data.linkedin_url+'" data-attr-name="'+data.name+'" data-attr-c-name="'+_companyName+'" type="checkbox" value="'+data.id+'">'+social_links+'</td><td><a href="javascript://" onclick="editContact('+data.id+')">'+data.name+gateway+no_contact+'</a></td><td>'+data.job_title+'</td><td style=""><a href="javascript://" onclick="callFromLandline('+encodeURIComponent(data.phone)+',jQuery(this))">'+data.phone+'</a></td></tr>');
					}
				}
			});
		}
	}
}
function editContact(contactID){
	jQuery.ajax({
			type:'POST',
			url:__baseUrl+'opportunity/findContact',
			data:{edit_link:contactID},
			cache:false,
			success:function(data){
				_data = jQuery.parseJSON(data);
				__backSpace = _data;
				if(typeof(_data.first_name)!="undefined"){ 
					jQuery("#inviteeFirstName").val(_data.first_name);
					jQuery("#inviteeLastName").val(_data.last_name);
					jQuery("#inviteeJobTitle").val(_data.job_title);
					jQuery("#inviteeTelephone").val(_data.telephone);
					jQuery("#invitePhone").val(_data.phone);
					jQuery("#inviteeEmail").val(_data.email);
					jQuery("#inviteeSecondaryEmailAddress").val(_data.secondary_email);
					_address=_data.street;
					if(_data.city!=''){
						_address += ', '+_data.city;
					}
					if(_data.state!=''){
						_address += ', '+_data.state;
					}
					if(_data.zip!=''){
						_address += ', '+_data.zip;
					}
					if(_data.country!=''){
						_address += ', '+_data.country;
					}
					jQuery("#inviteeAddress").val(_address);
					jQuery("#inviteeStreet").val(_data.street);
					jQuery("#inviteeCity").val(_data.city);
					jQuery("#inviteeState").val(_data.state);
					jQuery("#inviteeZip").val(_data.zip);
					jQuery("#inviteeCountry").val(_data.country);
					jQuery("#inviteeNote").val(_data.note);
					jQuery("#inviteeId").val(_data.id);					
					jQuery("#inviteeLinkedinUrl").val(_data.linkedin_url);		
					if(_data.linkedin_url!=""){
						jQuery("#inviteeLinkedinUrl").parent().find("label").html('<a href="'+_data.linkedin_url+'" target="_blank">LinkedIN Profile Url:</a>');
					} else {
						jQuery("#inviteeLinkedinUrl").parent().find("label").html('LinkedIN Profile Url:');
					}
					jQuery("#inviteeWebAddress").val(_data.web_address);
					if(_data.gateway==1){
						jQuery("#inviteeGateway").prop('checked',true);
					} else {
						jQuery("#inviteeGateway").prop('checked',false);
					}
					if(_data.no_contact==1){
						jQuery("#inviteeNoContact").prop('checked',true);
					} else {
						jQuery("#inviteeNoContact").prop('checked',false);
					}
					jQuery("#inviteeCompanyId").destroy();	
					jQuery("#inviteeCompanyId>option").removeAttr('selected');
					jQuery("#inviteeCompanyId>option").removeAttr('SELECTED');
					jQuery(".multi-select").multiSelect('refresh');	
					jQuery("#inviteeCompanyId>option").each(function(){
						if(jQuery(this).attr('value')==_data.company_id){
							jQuery(this).attr('selected','selected');
							/*checkCompanyChange(jQuery("#inviteeCompanyId"));*/
						}
					});
					/*jQuery(".multi-select").multiSelect('refresh');
					jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');*/
					jQuery("#addContactForm").css('z-index','9999');
					jQuery("#addContactForm").modal("show");
				}
			}
			});
}
function openPreContacts(){
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'leads/pre_contacts/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize(),myProfileResize())/*,retrieveFullContacts()*/
}

function openPreCompanies(){
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'leads/pre_companies/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize(),myProfileResize())/*,retrieveFullContacts()*/
}

function retrieveFullContacts(){
	jQuery("#predefineFormIframe").contents().find('#btnFullContact').css('display','none');
	jQuery.ajax({
		url:__baseUrl+'leads/fetch_list_full_contacts',
		success:function(d){
			jQuery("#predefineFormIframe").contents().find("#btnFullContact").css('display','inline-display');
			document.getElementById('predefineFormIframe').contentWindow.location.reload();
		}
	});
}

function open_scanned_documents(obj){
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):(jQuery('#scanned_documents').addClass('menu-active'),$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'leads/scanning_document_files/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize())
}
function openGoogleContacts(){
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'opportunity/google_contact/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize())
}
function openLitigationScrap(){
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'leads/litigation_scrap/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize())
}

function loadDriveFiles(){
	jQuery.ajax({type:"POST",url:__baseUrl+"leads/findDriveFiles",data:{boxes:leadGlobal},cache:false,success:function(e){_data=jQuery.parseJSON(e);_drive=_data.drive;_container='';if(_drive.length>0){_container="";if(jQuery("#from_regular").is(":visible")){_container="#from_regular"}else{if(jQuery("#from_litigation").is(":visible")){_container="#from_litigation"}else{if(jQuery("#from_nonacquistion").is(":visible")){_container="#from_nonacquistion"}}}if(_container==''){ _container= '#'+_mainBtnParentElement;}console.log(_container);jQuery(_container).find("#litigation_doc_list>ul").empty();jQuery(_container).find('#clipboard').html('<option value="">Go to main</option>');for(d=0;d<_drive.length;d++){if(_drive[d].mimeType=="application/vnd.google-apps.folder"){jQuery(_container).find('#clipboard').append('<option value="'+_drive[d].id+'">'+_drive[d].title+'</option>');}if(_drive[d].mimeType=="application/pdf"||_drive[d].mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"||_drive[d].mimeType=="application/msword"||_drive[d].mimeType=="image/jpeg"||_drive[d].mimeType=="image/png"){url="https://docs.google.com/file/d/"+_drive[d].id+"/preview";jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable "><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" target="_BLANK" href="javascript://" class="drive_file_click" data-mime="'+_drive[d].mimeType+'" onclick="open_drive_files(\''+url+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}else{jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable"><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" data-mime="'+_drive[d].mimeType+'" target="_BLANK" href="javascript://" class="drive_file_click"   onclick="open_drive_files(\''+_drive[d].alternateLink+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}}jQuery(_container).find("#litigation_doc_list>ul").find("li.driveDragable").find("a").click(function(){_message='Open '+jQuery(this).text()+' file.'; runHistoryUserLog(_message);jQuery(this).parent().parent().find('li').removeClass('active');jQuery(this).parent().addClass('active')});}docFileDraggable();driveFileDraggable();initHoverEmailClose();}});
}

function runHistoryUserLog(_message){
	jQuery.ajax({
		url:__baseUrl+'dashboard/log_user_history',
		type:'POST',
		data:{lead_id:leadGlobal,message:_message},
		cache:false,
		success:function(data){
		}
	});
}
 window.AcquisitionUser=[];
 window.SalesUser=[];
 function isHTML(str){
	 return /<(basefont|hr|input|source|frame|param|area|meta|!--|col|link|option|base|img|wbr|!DOCTYPE).*?>|<(a|abbr|acronym|address|applet|article|aside|audio|b|bdi|bdo|big|blockquote|body|button|canvas|caption|center|cite|code|colgroup|command|datalist|dd|del|details|dfn|dialog|dir|div|dl|dt|em|embed|fieldset|figcaption|figure|font|footer|form|frameset|head|header|hgroup|h1|h2|h3|h4|h5|h6|html|i|iframe|ins|kbd|keygen|label|legend|li|map|mark|menu|meter|nav|noframes|noscript|object|ol|optgroup|output|p|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|span|strike|strong|style|sub|summary|sup|table|tbody|td|textarea|tfoot|th|thead|time|title|tr|track|tt|u|ul|var|video).*?<\/\2>/i.test(str);
 }
 String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
}

function tapLeadPosition(g){
	_scrollTop=jQuery("#all_type_list").find("tbody").find('tr[data-id="'+g+'"]').offset();
	if(jQuery("#dashboard_charts").is(":visible")){
		jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-263.5)
	} else {
		jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106)
	}
}

function getLeadList(o){
	jQuery.ajax({
		type:'post',
		data:{contact_id:o.val()},
		url:__baseUrl+'dashboard/find_lead_by_contact_id',
		success:function(d){
			if(d!=""){
				_d = jQuery.parseJSON(d);				
				if(_d.list.length>0){
					_table = '<div style="overflow-x:none;overflow-y:scroll;height:300px;"> <table id="lb'+o.val()+'" class="table table-striped table-hover"><thead><tr><th>Lead</th><th>Activity</th></tr></thead><tbody>';
					for(l=0;l<_d.list.length;l++){
						_row = _d.list[l];
						_activityName="";
						if(_d.list[l].activity==1){
							_activityName = "Sales";
						} else if(_d.list[l].activity==2){
							_activityName = "Acquisition";
						}
						_table +='<tr onclick="threadDetail(jQuery(\'#all_type_list\').find(\'tr[data-id='+_d.list[l].id+']\'));tapLeadPosition('+_d.list[l].id+');" style="cursor:pointer;"><td>'+_d.list[l].lead_name+'</td><td>'+_activityName+'</td></tr>';
					}
					_table +='</tbody></table>';
					window.parent.jQuery("#sortingPopup").find(".modal-body").html(_table);
					window.parent.jQuery("#sortingPopup").find("#lb"+o.val()).DataTable().destroy();
					window.parent.jQuery("#sortingPopup").modal("show");
					window.parent.jQuery("#sortingPopup").find("#lb"+o.val()).DataTable({destroy:true,paging:false,searching:true,language:{emptyTable:"No record found!"}});
				} else {
					alert("No Lead associate with this contact.");
				}				
			}
		}
	});
}


function openCompanyContact(contactID){
		if(contactID>0){
			jQuery.ajax({
			type:'POST',
			url:__baseUrl+'opportunity/findCompany',
			data:{edit_link:contactID},
			cache:false,
			success:function(data){
				_data = jQuery.parseJSON(data);
				if(typeof(_data.company_name)!="undefined"){
					window.parent.jQuery("#ccompanyFormSubmit").get(0).reset();
					window.parent.jQuery("#companyBroker").val(_data.broker);
					if(_data.broker_details.first_name!=undefined){
						window.parent.jQuery("#companyBrokerName").val(_data.broker_details.first_name+' '+_data.broker_details.last_name);
						window.parent.jQuery("#companyBrokerFirm").val(_data.broker_details.company_name);
					}					
					window.parent.jQuery("#company_users_show_table").empty();
					window.parent.jQuery("#companyJobTitle").val(_data.company_name);
					window.parent.jQuery("#companyTelephone").val(_data.telephone);
					window.parent.jQuery("#companyEmail").val(_data.email);
					window.parent.jQuery("#companyWebAddress").val(_data.web_address);	
					window.parent.jQuery("#companyStreet").val(_data.street);
					window.parent.jQuery("#companyStreet").val(_data.city);
					window.parent.jQuery("#companyState").val(_data.state);
					window.parent.jQuery("#companyZip").val(_data.zip);
					window.parent.jQuery("#companyCompanyNameAlias").val(_data.company_name_alias);
					window.parent.jQuery("#companyCountry").val(_data.country);
					window.parent.jQuery("#companyId").val(_data.id);	
					window.parent.jQuery("#companySector").val(_data.sectorID);	
					window.parent.checkSector(_data.sectorID,1);
					if(_data.companyUsers.length>0){
						_table = jQuery("<table/>").addClass('table');
						_thead = jQuery("<thead/>");
						_tr = jQuery("<tr/>");
						jQuery(_tr).append("<th>Name</th>");
						jQuery(_tr).append("<th>Work Phone</th>");
						jQuery(_tr).append("<th>Mobile Phone</th>");
						jQuery(_thead).append(_tr);
						jQuery(_table).append(_thead);
						_tbody = jQuery("<tbody/>");
						for(u=0;u<_data.companyUsers.length;u++){
							_tr = jQuery("<tr/>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].name+"</td>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].phone+"</td>");
							jQuery(_tr).append("<td>"+_data.companyUsers[u].telephone+"</td>");
							jQuery(_tbody).append(_tr);
						}
						jQuery(_table).append(_tbody);
						window.parent.jQuery("#company_users_show_table").css({height:'250px',overflowY:'scroll'}).append(_table);
					}
					window.parent.jQuery(".multi-select").multiSelect('refresh');
					window.parent.jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
					window.parent.jQuery("#addCCompanyForm").css('z-index','9999');
					window.parent.jQuery("#addCCompanyForm").modal("show");
				}
			} 
			});
		} else {
			alert("Please select contact first");
		}		
	}
	
function openCompanyThroughContact(){
	if(jQuery("#inviteeCompanyId").val()!=""){
		openCompanyContact(jQuery("#inviteeCompanyId").val());
	}
}
function linkedInSearch(){
	_companyName = jQuery("#companyJobTitle").val();
	urlString = "";
	if(_companyName!=""){
		urlString = "https://www.linkedin.com/vsearch/p?company="+_companyName+"&openAdvancedForm=true&companyScope=C&locationType=Y&orig=ADVS&sa=D&sntz=1";
		window.open(urlString,'_blank');
	} else {
		alert("Company name is blank");
	}	
}

function addNewContact(o){
	openAddForm();	
	jQuery("#inviteeCompanyId>option").each(function(){
		obj = o.parent().parent().parent().parent().parent().parent().prev();
		if(jQuery(this).attr('value')==obj.attr('data-c')){
			jQuery(this).prop('selected',true);
			_company = jQuery(this).text();
			jQuery("#addContactForm #createContactModalLabel").html('Add a new person under '+_company);
			jQuery("#addContactForm #createContactModalLabel").css('width','80%');
			jQuery("#addContactForm #btnManageCategories").css('display','none').removeAttr('onclick');
			jQuery("#inviteeCompanyId").parent().append('<input type="hidden" name="invitee[company_id]" id="inviteeCompanyId" value="'+jQuery(this).attr('value')+'"/>');
			jQuery("#inviteeCompanyId").parent().find("label").css('display','none');
			jQuery("#inviteeCompanyId").css('display','none');			
		}
	});
}

function acquisitionImport(_data){ 
	window.AcquisitionUser=[];
	if(_data.acquisition.length>0){a=_data.acquisition;jQuery("#aquisitionTable").find("tbody.main_active").empty();if(a.length>0){for(i=0;i<a.length;i++){_cID=a[i].company.id;_cName=a[i].company.company_name;broker_name='';broker_company='';if(typeof a[i].company.broker_details.first_name!='undefined'){broker_name = a[i].company.broker_details.first_name+' '+a[i].company.broker_details.last_name;broker_company = a[i].company.broker_details.company_name; }_person="";_activity="";editConf='';_date="";_note="";if(a[i].activities.length>0){_person=a[i].activities[0].firstName+" "+a[i].activities[0].lastName;_activity=salesActivities[a[i].activities[0].type];_date=a[i].activities[0].activity_date;_note=a[i].activities[0].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[0].type==11){_note = _note.nl2br();}if(a[i].activities[0].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[0].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[0].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[0].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[0].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}if(typeof a[i].activities[0].type!='undefined'){switch(parseInt(a[i].activities[0].type)){case 1: case 2: case 37: editConf ="<div class='pull-left' style='width:100%;'><span class='pull-right email-close hide' ><a href='javascript:void(0);' class='' onclick='editActivitiesData("+leadGlobal+',"'+a[i].activities[0].id+"\")'><i class='glyph-icon icon-edit'></i></a></span></div>";break; }}}_tr="<tr class='master '  data-c='"+_cID+"'><td style='width:65px;'><a href='javascript://' onclick='deleteAcquisitionInvitedC("+_cID+")'><i class='glyph-icon'><img src='"+__baseUrl+"public/images/discard.png' style='opacity:0.55'></i></a></td><td style='width:234px;'><a href='javascript://' class='showActivity'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a>&nbsp;<a href='javascript://' onclick='openCompanyContact("+_cID+")'><b>"+_cName+"</b><span class='broker_detail' data-company='"+broker_company+"' style='float:right;'>"+broker_name+"</span></a></td><td style='width:110px;'>"+_date+"</td><td style='width:120px;'>"+_person+"</td>";if(_activity!="" && a[i].activities.length>0 &&a[i].activities[0].email_id!=0){if(a[i].activities[0].email.length>0){_d=a[i].activities[0].email[0];window.AcquisitionUser[_d.id] = _d;_receivedDate="";subject="";__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+_d.id+"\")'><i class='glyph-icon icon-close'></i></a>";if(_d.content!=""){_contents=jQuery.parseJSON(_d.content);if(_contents.to!=undefined){_receivedDate = _contents.date;subject = _contents.subject;} else {if(_contents.length>0){for(c=0;c<_contents.length;c++){_content=_contents[c];header=_content.header;if(header.length>0){for(h=0;h<header.length;h++){if(header[h].name=="Subject"){subject = header[h].value}if(header[h].name=="Date"){_receivedDate=header[h].value}}}}}}}_color='#2196f3';if(_d.sent_from==1){_color='#d1c8c8';}_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i> <a class='pull-left pad5L' data-tr='"+a[i].activities[0].email_id+"' style='width:93%;' href='javascript:void(0)' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+subject+"</a>";if(_contents.to!=undefined){_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;<a style='' class='pull-left pad5L' style='width:93%;' href='javascript://' data-tr='"+_d.id+"' onclick='imapShowDataAcc("+_d.id+",jQuery(this));'>"+subject+"</a>" ; }   _innerTR='';if(_d.file_attach!=""&&_d.file_attach!="0"){_files=_d.file_attach.split(",");if(_files.length>0){for(f=0;f<_files.length;f++){if(_files[f]!=""){filename=_files[f].indexOf("upload");if(filename>0){filename=_files[f].substr(filename+7);translated=escapedString(_files[f]);_innerShowData="<a data-href='"+translated+"' data-mime='' onclick='open_drive_files(\""+translated+"\");' href='javascript://'  target='_BLANK' style='width:93%'><i class='glyph-icon icon-file-o' style='color:#2196f3'></i> "+filename+"</a>";_innerTR+="<tr class='"+_content.message_id+" attach docDragable'><td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_innerShowData+"</td></tr>";}}}}}_tr+="<td style='width:400px;'>"+_showData+"<div class='pull-left' style='width:100%;'><span class='message-item-date'>"+moment(new Date(_receivedDate)).format("MMM DD, YYYY")+"</span><span class='pull-right email-close hide' >"+__a+"</span></div><table>"+_innerTR+"</table></td></tr>";} else {if(a[i].activities[0].subject==""){_tr+="<td style='width:400px;'><a style='color:#56b2fe;text-decoration:underline;' href='javascript://' data-tr='"+a[i].activities[0].email_id+"' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>View Email</a></td></tr>"}else{_tr+="<td><a style='color:#56b2fe;text-decoration:underline;' data-tr='"+a[i].activities[0].email_id+"' href='javascript://' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+a[i].activities[0].subject+"</a></td></tr>"}}}else{_note = _note+editConf;_tr+="<td><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}jQuery("#aquisitionTable").find("tbody.main_active").append(_tr);_cList="<table class='table' style='border:0px;'><thead><tr><th>#<a href='javascript://' onclick='addNewContact(jQuery(this))' class='mrg10L' style='display:inline-block'><i class='glyph-icon icon-plus-circle'></i></a>&nbsp;</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody></tbody></table>";_cActivites="<table class='table' style='border:0px;'></table>";if(a[i].people.length>0){_cList="";_tr="";for(p=0;p<a[i].people.length;p++){_name=a[i].people[p].first_name+" "+a[i].people[p].last_name;_phone=a[i].people[p].phone;_gateway='';if(a[i].people[p].gateway>0){_gateway='&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/gateway.png" style="width:16px;"/>';}no_contact='';if(a[i].people[p].no_contact=='1'){no_contact = '&nbsp;&nbsp;<img src="'+__baseUrl+'public/images/no_contact.jpg"/>';}if(_phone==""){_phone=a[i].people[p].telephone;if(_phone!=''){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}else{if(a[i].people[p].telephone!=""){_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';_phone+='<br/><a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+a[i].people[p].telephone+'"),jQuery(this))\'>'+a[i].people[p].telephone+'</a>';} else {_phone = '<a href="javascript://" onclick=\'callFromLandline(encodeURIComponent("'+_phone+'"),jQuery(this))\'>'+_phone+'</a>';}}_sLinks='';if(a[i].people[p].email!=''){_sLinks='<a href="javascript://" onclick="openComposeEmail(jQuery(this))"><i class="glyph-icon icon-envelope-square"></i></a>';}if(a[i].people[p].linkedin_url!=''){_sLinks +='&nbsp;&nbsp;<a href="'+a[i].people[p].linkedin_url+'" target="_BLANK"><i class="glyph-icon icon-linkedin"></i></a>';}_tr+="<tr class='salesFDroppable' data-c='"+_cID+"' data-p='"+a[i].people[p].id+"'><td style='border-left:0px; width:65px;'><input name='sales_person[]' class='sales-activity-checkbox' data-attr-em='"+a[i].people[p].email+"' data-attr-linkedin='"+a[i].people[p].linkedin_url+"' data-attr-name='"+_name+"' data-attr-c-name='"+_cName+"'  type='checkbox' value='"+a[i].people[p].id+"'/>"+_sLinks+"</td><td><a href='javascript://' onclick='editContact("+a[i].people[p].id+")'>"+_name+_gateway+no_contact+"</a></td><td>"+a[i].people[p].job_title+"</td><td style=''>"+_phone+"</td></tr>"}_cList="<table class='table' style='border:0px;'><thead><tr><th>#<a href='javascript://' onclick='addNewContact(jQuery(this))' class='mrg10L' style='display:inline-block'><i class='glyph-icon icon-plus-circle'></i></a>&nbsp;</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>"+_tr+"</tbody></table>"}if(a[i].activities.length>0){_cActivites="";editConf='';_tr="";for(al=1;al<a[i].activities.length;al++){_person=a[i].activities[al].firstName+" "+a[i].activities[al].lastName;_activity=salesActivities[a[i].activities[al].type];_date=a[i].activities[al].activity_date;_note=a[i].activities[al].note;if(!isHTML(_note)){_note = _note.nl2br();}if(a[i].activities[al].type==11){_note = _note.nl2br();}if(a[i].activities[al].type==206){_note = "<img src='"+__baseUrl+"public/images/small-vm-calldrip.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==37){_note = "<img src='"+__baseUrl+"public/images/Conference_Call-512.png' style='width:16px;'/> "+_note;}if(a[i].activities[al].type==1){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#2196f3' ></i> "+_note;}if(a[i].activities[al].type==2){_note = "<i class='glyph-icon icon-phone' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==5){_note = "<i class='glyph-icon icon-linkedin' title='Contacts' style='color:#d1c8c8' ></i> "+_note;}if(a[i].activities[al].type==10){_note = "<a href='javascript://' onclick='approvedFile("+a[i].activities[al].task_id+")'><i class='glyph-icon icon-tasks' title='Contacts' style='color:#2196f3' ></i></a> &nbsp;"+_note;}editConf='';if(typeof a[i].activities[al].type!='undefined'){switch(parseInt(a[i].activities[al].type)){case 1: case 2: case 37: editConf ="<div class='pull-left' style='width:100%;'><span class='pull-right email-close hide' ><a href='javascript:void(0);' class='' onclick='editActivitiesData("+leadGlobal+',"'+a[i].activities[al].id+"\")'><i class='glyph-icon icon-edit'></i></a></span></div>";console.log('Activities Under'); break;}}if(_activity!=""&&a[i].activities[al].email_id!=0){if(a[i].activities[al].email.length>0){_d=a[i].activities[al].email[0];window.AcquisitionUser[_d.id] = _d;_receivedDate="";subject="";__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+_d.id+"\")'><i class='glyph-icon icon-close'></i></a>";if(_d.content!=""){_contents=jQuery.parseJSON(_d.content);if(_contents.to!=undefined){_receivedDate = _contents.date;subject = _contents.subject;} else{ if(_contents.length>0){for(c=0;c<_contents.length;c++){_content=_contents[c];header=_content.header;if(header.length>0){for(h=0;h<header.length;h++){if(header[h].name=="Subject"){subject = header[h].value}if(header[h].name=="Date"){_receivedDate=header[h].value}}}}}}}_color='#2196f3';if(_d.sent_from==1){_color='#d1c8c8';}_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i> <a class='pull-left pad5L' style='width:93%;' href='javascript:void(0)' data-tr='"+a[i].activities[al].email_id+"' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>"+subject+"</a>";if(_contents.to!=undefined){_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i>&nbsp;<a style='' class='pull-left pad5L' style='width:93%;' href='javascript://' data-tr='"+_d.id+"' onclick='imapShowDataAcc("+_d.id+",jQuery(this));'>"+subject+"</a>" ; } _innerTR='';if(_d.file_attach!=""&&_d.file_attach!="0"){_files=_d.file_attach.split(",");if(_files.length>0){for(f=0;f<_files.length;f++){if(_files[f]!=""){filename=_files[f].indexOf("upload");if(filename>0){filename=_files[f].substr(filename+7);translated=escapedString(_files[f]);_innerShowData="<a data-href='"+translated+"' data-mime='' onclick='open_drive_files(\""+translated+"\");' href='javascript://'  target='_BLANK' style='width:93%'><i class='glyph-icon icon-file-o' style='color:#2196f3'></i> "+filename+"</a>";_innerTR+="<tr class='"+_content.message_id+" attach docDragable'><td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_innerShowData+"</td></tr>";}}}}}_note=_showData+"<div class='pull-left' style='width:100%;'><span class='message-item-date'>"+moment(new Date(_receivedDate)).format("MMM DD, YYYY")+"</span><span class='pull-right email-close hide' >"+__a+"</span></div><table>"+_innerTR+"</table>";} else {if(a[i].activities[al].subject==""){_note="<a class='btn' href='javascript://' data-tr='"+a[i].activities[al].email_id+"' onclick='findOwnThread("+a[i].activities[al].email_id+",jQuery(this),2);'>View Email</a>"}else{_note="<a class='btn' href='javascript://' data-tr='"+a[i].activities[al].email_id+"' onclick='findOwnThread("+a[i].activities[al].email_id+",jQuery(this),2);'>"+a[i].activities[al].subject+"</a>"}}}else{_note = _note+editConf;console.log('Activities GOT');}_tr+="<tr><td style='width: 110px;'>"+_date+"</td><td style='width: 120px;'>"+_person+"</td><td style='border-right:0px; width: 400px;'><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}_cActivites="<table class='table' style='border:0px;'><tbody>"+_tr+"</tbody></table>"}_newTr="<tr style='display:none;'><td colspan='2' style='padding:0px;border:0px;width:300px;'>"+_cList+"</td><td colspan='4' style='padding:0px;border:0px;'>"+_cActivites+"</td></tr>";jQuery("#aquisitionTable").find("tbody.main_active").append(_newTr);}salesEmailDroppable()}toggleCompanySales();initHoverEmailClose() }else{jQuery('#aquisitionTable').find('tbody.main_active').empty()}
}	
function findThisDriveFile(o){
	_container="";if(jQuery("#from_regular").is(":visible")){_container="#from_regular"}else{if(jQuery("#from_litigation").is(":visible")){_container="#from_litigation"}else{if(jQuery("#from_nonacquistion").is(":visible")){_container="#from_nonacquistion"}}}
	if(jQuery(_container).find("#litigation_doc_list>ul").find('li').hasClass('active')){
		_anchorObject = jQuery(_container).find("#litigation_doc_list>ul").find('li.active').find('a');_id=_anchorObject.attr("data-file-id");_fF='';if(_container!=''){_fF = jQuery(_container).find('select#clipboard').val();}if(_id!=""&&_fF!=""&&_id!=undefined&&_fF!=undefined){jQuery("#mainDocWaitBox").modal("show");jQuery.ajax({url:__baseUrl+"dashboard/move_drive_file_in_lead_folder",type:"POST",data:{d:_id,f:_fF},cache:false,success:function(k){jQuery("#mainDocWaitBox").modal("hide");_anchorObject.parent().remove();closeSlideBarLeftDrive();}});}
	} else {
			if(o.val()==""){
			loadDriveFiles();
		} else {
			jQuery.ajax({type:"POST",url:__baseUrl+"leads/findDriveFilesSubFolder",data:{boxes:leadGlobal,f:o.val()},cache:false,success:function(e){_data=jQuery.parseJSON(e);_drive=_data.drive;jQuery(_container).find("#litigation_doc_list>ul").empty();if(_drive.length>0){for(d=0;d<_drive.length;d++){if(_drive[d].mimeType=="application/pdf"||_drive[d].mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"||_drive[d].mimeType=="application/msword"||_drive[d].mimeType=="image/jpeg"||_drive[d].mimeType=="image/png"){url="https://docs.google.com/file/d/"+_drive[d].id+"/preview";jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable "><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" target="_BLANK" href="javascript://" class="drive_file_click" data-mime="'+_drive[d].mimeType+'" onclick="open_drive_files(\''+url+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}else{jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable"><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" data-mime="'+_drive[d].mimeType+'" target="_BLANK" href="javascript://" class="drive_file_click"   onclick="open_drive_files(\''+_drive[d].alternateLink+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}}}docFileDraggable();driveFileDraggable();initHoverEmailClose()}});
		}
	}
}						
window.runThreadDetail= function (){
	/*runActivityProcess();*/
	threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));	
};
window.runActivityProcess = function(){
	data = {};
	call(__baseUrl+'dashboard/run_process','POST',data,startCampignProcess,'json');
}
window.runSignleActivityProcess = function(email,campaign_id){
	data = {email:email,campaign_id:campaign_id};
	call(__baseUrl+'dashboard/run_process_single','POST',data,startSingleProccess,'json');
}
window.doNothing = function(){
	
}
window.startSingleProccess = function(data,textStatus,xhr){
	if(data.length>0){
		call(__baseUrl+'dashboard/sales_activity_email_save','POST',data[0],doNothing,'json');
	}
	
}
cpList = [];
window.startCampignProcess = function(data,textStatus,xhr){
	if(data!='' && typeof data=='object'){
		if(data.length>0){
			cpList = data;
			jQuery("#alert_message_show").removeClass('alert-success').addClass('alert-info').removeClass('hide').addClass('show').html("Campaign process start.");
			setTimeout(function(){jQuery("#alert_message_show").removeClass('show').addClass('hide')},2000);
			initCampaignProcess(0);
		}
	}
}
_startNo = 0;
window.checkAndRunProcess = function(data,textStatus,xhr){
	if(data>0){
		_startNo = _startNo + 1;
		if(_startNo<cpList.length){
			initCampaignProcess(_startNo);
		} else {
			_startNo = 0;
			processID = cpList[0].campaignProcessID;
			data= {process_id:processID};
			call(__baseUrl+'dashboard/end_campaign_process','POST',data,endProcess,'json');
		}
	}
}
window.endProcess = function(data,textStatus,xhr){
	if(data>0){
		jQuery("#alert_message_show").removeClass('alert-info').addClass('alert-success').removeClass('hide').addClass('show').html("Campaign process finished.");
		setTimeout(function(){jQuery("#alert_message_show").removeClass('show').addClass('hide')},2000);
	}
	cpList = [];
	_startNo = 0;
}
function initCampaignProcess(startNo){
	if(cpList.length>0 && typeof cpList[startNo]!='undefined'){
		_startNo = startNo;
		call(__baseUrl+'dashboard/sales_activity_email_save','POST',cpList[startNo],checkAndRunProcess,'json');
	}
}
jQuery(document).ready(function(){
	toggleCompanySales();
	jQuery('.form-control.is-date').datepicker();
});
function openSendTask(url,type){
jQuery("#formTask").get(0).reset();
jQuery("#taskDocUrl").val(url);
jQuery("#replyParentId").val(0);
jQuery("#replyType").val(type);	
jQuery("#replyLeadId").val(leadGlobal);
openTaskModal();
}
function tableSortActivity(){
	_activity = jQuery("#activityMainType").val();
	_container = "";
	if(_activity==1){
		_container = "activityTable";
	} else if(_activity==2){
		_container = "aquisitionTable";
	}else if(_activity==3){
		_container = "preSaleActivityTable";
	}
	if(_container!=""){
		if(_container=="activityTable"){
			_table1 = "<table class='table table-bordered' id='sortingActivity'><thead><tr><th>Name</th><th>Stage</th><th>Broker</th><th>Broker Firm</th></tr></thead><tbody>";
			_table2 = "<table class='table table-bordered' id='sortingActivity1'><thead><tr><th>Name</th><th>Stage</th><th>Broker</th><th>Broker Firm</th></tr></thead><tbody>";
			_table3 = "<table class='table table-bordered' id='sortingActivity2'><thead><tr><th>Name</th><th>Stage</th><th>Broker</th><th>Broker Firm</th></tr></thead><tbody>";
			jQuery("#"+_container).find('tr.master').each(function(){
			_data_c = jQuery(this).attr('data-c');
			obj = jQuery(this).find('td').eq(1).find('a.showActivity').eq(1);
			if(obj.length>0){
			_name = obj.text();	
			} else {
				obj = jQuery(this).find('td').eq(1).find('a').eq(1);
				_name = obj.text();	
			}
			broker = '';
			brokerFirm = '';
			if(obj.find('span.broker_detail').length>0){
				broker = obj.find('span.broker_detail').text();
				brokerFirm = obj.find('span.broker_detail').attr('data-company');
			}
			_className="torquoise";
			if(jQuery(this).find('td').eq(0).find('select').length>0){
				_select = jQuery(this).find('td').eq(0).find('select').find('option:selected').text();
				_className  = jQuery(this).find('td').eq(0).find('select').find('option:selected').attr('class');
			}else {
				_select = jQuery(this).find('td').eq(0).text();
			}
			if(_className=="" || _className=="undefined" || _className==undefined){
				_className="torquoise";
			}
			switch(_className){
				case 'torquoise':
					_table1 +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td><td>"+_select+"</td><td>"+broker+"</td><td>"+brokerFirm+"</td></tr>";
				default:
				break;
				case 'seablue':
					_table2 +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td><td>"+_select+"</td><td>"+broker+"</td><td>"+brokerFirm+"</td></tr>";
				break;
				case 'darksea':
					_table3 +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td><td>"+_select+"</td><td>"+broker+"</td><td>"+brokerFirm+"</td></tr>";
				break;
			}			
			});
			_table1 +="</tbody></table>";
			_table2 +="</tbody></table>";
			_table3 +="</tbody></table>";
			jQuery("#sortingPopup").find('.modal-body').html('');
			jQuery("#sortingPopup").css({width:'100%',left:'0%',marginLeft:'0px'});
			jQuery("#sortingPopup").find('.modal-dialog').css('width','100%');
			jQuery("#sortingPopup").find('.modal-body').append('<div class="row"><div class="col-lg-4">'+_table1+'</div><div class="col-lg-4">'+_table2+'</div><div class="col-lg-4">'+_table3+'</div></div>');
			jQuery("#sortingPopup").off('shown.bs.modal').on('shown.bs.modal', function() {
				jQuery("#sortingActivity,#sortingActivity1,#sortingActivity2").DataTable({"paging": false,"destroy":true,"scrollY":"400px","language": {"emptyTable": "No record found!"}});
			});
		} else {
			if(_container=="preSaleActivityTable"){
				_table = "<table class='table table-bordered' id='sortingActivity'><thead><tr><th>Name</th><th>Broker</th><th>Broker Firm</th></tr></thead><tbody>";
			} else {
				_table = "<table class='table table-bordered' id='sortingActivity'><thead><tr><th>Name</th><th>Broker</th><th>Broker Firm</th></tr></thead><tbody>";
			}
			jQuery("#"+_container).find('tr.master').each(function(){
			_data_c = jQuery(this).attr('data-c');
			obj = jQuery(this).find('td').eq(1).find('a.showActivity').eq(1);
			if(obj.length>0){
			_name = obj.text();	
			} else {
				obj = jQuery(this).find('td').eq(1).find('a').eq(1);
				_name = obj.text();	
			}
			broker = '';
			brokerFirm = '';
			if(obj.find('span.broker_detail').length>0){
				broker = obj.find('span.broker_detail').text();
				brokerFirm = obj.find('span.broker_detail').attr('data-company');
			}
			if(_container=="preSaleActivityTable"){
				_table +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td><td>"+broker+"</td><td>"+brokerFirm+"</td></tr>";
			} else {
				_table +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td><td>"+broker+"</td><td>"+brokerFirm+"</td></tr>";
			}
			});
			_table +="</tbody></table>";
			jQuery("#sortingPopup").find('.modal-body').html(_table);
			jQuery("#sortingPopup").css({width:'600px',left:'-50%',marginLeft:'-300px'});
			jQuery("#sortingPopup").find('.modal-dialog').css('width','100%');
			
			jQuery("#sortingPopup").off('shown.bs.modal').on('shown.bs.modal', function() {
				jQuery("#sortingActivity").DataTable({"paging": false,"destroy":true,"scrollY":"400px","language": {"emptyTable": "No record found!"}});
			});
		}
		jQuery("#sortingPopup").modal('show');
	} else {
		alert("No table found");
	}
}
function findSelectedCompany(o){
	o.parent().find('tr').removeClass('active');
	o.addClass('active');
	_activity = jQuery("#activityMainType").val();
	_container = "";
	if(_activity==1){
		_container = "activityTable";
	} else if(_activity==2){
		_container = "aquisitionTable";
	}
	if(_container!=""){
		jQuery("#"+_container).find('tr.master').removeClass('active');
		jQuery("#"+_container).find('tr.master').each(function(){
			if(jQuery(this).find('td').eq(1).find('a').eq(1).text()==o.find('td').eq(0).text()){
				jQuery(this).addClass('active');

				jQuery('#dashboard-page').scrollTop(0);
				jQuery('#dashboard-page').scrollTop(jQuery(this).offset().top-450);
			}
		});
	}
}

function getNextInboxEmails(container,o,records){
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'users/getMoreRecordsInEmail',
		data:{r:records,t:'INBOX'},
		cache:false,
		success:function(data){
			if(data!=''){
				if(jQuery('.emails-group-container').find('a.active').text()=="Inbox"){
					/*jQuery('#messages-list').find('.messages_container').append(data);
					initDragDrop();*/
					sendCurrentOldLeadLL('INBOX');
				}				
			}
		}
	})
}

function call(url,type,data,fn,datatype){
	jQuery.ajax({
		type:type,
		crossDomain: true,
		url:url,
		data:data,
		cache:false,
		dataType:datatype,
		success:fn
	});
}
_replyEmailFlag=0;
sendTask  = 0;
function getImapEmails(container,o){
	jQuery('.message-item').remove();
	jQuery('#displayEmail').empty();
	jQuery("#loading_spinner_heading_messages").css("display","block");
	o.addClass('active');
	
	jQuery('.emails-group-container').find('a').each(function(index){
		if(jQuery(this).attr('data-title')=='INBOX'){
			jQuery(this).removeClass('active');
		} 
	});
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'users/imap_emails',
		cache:false,
		success:function(data){
			if(data!=""){
				_list = jQuery.parseJSON(data);
				if(_list.length>0){ 
					for(i=0;i<_list.length;i++){
						_emailDetail= _list[i];		
						_attachment="";
						if(typeof(_emailDetail.attachments)!='undefined'){
							_attachment='<strong><i class="glyph-icon icon-paperclip"></i>'+_emailDetail.attachments.length+"</strong>";
						}						
						/*_inner ='<div class="message-item-right"><div class="media"><div class="media-body"><h5 class="c-dark"><a class="c-dark" style="font-weight:normal" href="javascript:void(0)">'+_emailDetail.from+'</a></h5><h4 class="c-dark">'+_emailDetail.subject+'</h4><div><span class="message-item-date">'+moment(new Date(_emailDetail.overview.date)).format("MMM DD, YYYY")+'</span>&nbsp;'+_attachment+' <a href="javascript://" onclick="enableTask(jQuery(this));" style="float:right;width:15px;" class="c-dark"><i class="glyph-icon icon-plus"></i></a></div></div></div></div>';*/
						_inner ='<div class="message-item-right"><div class="media"><div class="media-body"><h5 class="c-dark"><a class="c-dark" style="font-weight:normal" href="javascript:void(0)">'+_emailDetail.from+'</a></h5><h4 class="c-dark">'+_emailDetail.subject+'</h4><div><span class="message-item-date">'+moment(new Date(_emailDetail.overview.date)).format("MMM DD, YYYY")+'</span>&nbsp;'+_attachment+' </div></div></div></div>';
						_div = jQuery("<div/>").data("detail",_emailDetail).click(function(){embedImapEmailDetail(jQuery(this));}).addClass("message-item media draggable ui-draggable ui-draggable-handle").attr("data-id",_emailDetail.uid).attr("data-date",_emailDetail.overview.date).attr("data-message-id",_emailDetail.overview.message_id).attr('data-task','0').css('position','relative').append(_inner);
						jQuery('#messages-list').find('.messages_container').append(_div);
					}
				}  
			}
			jQuery("#loading_spinner_heading_messages").css("display","none");
		}
	});
}
function embedImapEmailDetail(o){
	jQuery("#messages-list").find('.messages_container').find('div.message-item').removeClass('message-active');
	o.addClass('message-active');
	message = o.data("detail");
	window.parent.leadGlobal=0;
	window.parent.leadNameGlobal='';
	jQuery('.topbar-lead-name').html('');
	jQuery("#gmail_message").hide();jQuery("#from_regular").hide();jQuery("#from_litigation").hide();jQuery('#sales_acititity').removeClass('show').addClass('hide');jQuery("#from_nonacquistion").hide();
	jQuery("#all_type_list tbody tr").removeClass("active");jQuery("#all_type_list tbody td").removeClass("active");
	jQuery('.DTFC_Cloned tbody tr, .DTFC_Cloned tbody td').removeClass('active');jQuery("#all_type_list tbody td").removeClass("active");
	if(jQuery("#myDashboardComposeEmails").length>0){jQuery("#myDashboardComposeEmails").get(0).reset()}
	jQuery("#displayEmail").html();
	_container = "";
	if(jQuery("#activityMainType").val()==1){
		_container = "activityTable";
	} else{
		_container = "aquisitionTable";
	}
	if(jQuery("#"+_container).find("input[name='sales_person[]']").is(':checked')){
		if(jQuery("#"+_container).find("input[name='sales_person[]']:checked").length==1){
			jQuery.ajax({
				type:'POST',
				url:__baseUrl+'dashboard/linkImapMessage',
				data:{msg_no:message.overview.msgno,uid:message.overview.uid,lead_id:leadGlobal,p_id:jQuery("#"+_container).find("input[name='sales_person[]']:checked").val(),c_id:jQuery("#"+_container).find("input[name='sales_person[]']:checked").parent().parent().attr('data-c'),activity_type:jQuery("#activityMainType").val()},
				cache:false,
				success:function(data){
					if(data>0){
						refreshAcquisitionAndSalesActivity();
					} else {
						alert('Error!');
					}
				}
			});
		} else {
			alert("Please select one person to whom you want to associate email.");
		}
	} else {
		imapMessageShow(message);
	}
}
_whichNum=0;
function imapShowData(obj,o){
	jQuery("#aquisitionTable").find('tbody>tr>td').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').removeClass('active');
	jQuery("#aquisitionTable").find('tbody>tr>td').find('div').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').find('div').removeClass('active');
	o.parent().addClass('active');
	imapMessageShow(JSON.parse(obj.content),obj.user_id);
	
}
function imapShowDataSales(ID,o){
	obj = window.SalesUser[ID];
	_whichNum = ID;
	jQuery("#aquisitionTable").find('tbody>tr>td').removeClass('active');
	jQuery("#aquisitionTable").find('tbody>tr>td').find('div').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').find('div').removeClass('active');
	if(typeof o=='object'){o.parent().addClass('active');}
	if(typeof obj.content=="string"){imapMessageShow(JSON.parse(obj.content),obj.user_id,obj.sent_from);} else {
		console.log(obj.sent_from);
		imapMessageShow(obj.content,obj.user_id,obj.sent_from);
	}
}
function imapShowDataAcc(ID,o){
	obj = window.AcquisitionUser[ID];
	jQuery("#aquisitionTable").find('tbody>tr>td').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').removeClass('active');
	jQuery("#aquisitionTable").find('tbody>tr>td').find('div').removeClass('active');
	jQuery("#activityTable").find('tbody>tr>td').find('div').removeClass('active');
	o.parent().addClass('active');
	if(obj.content!=undefined){
		imapMessageShow(JSON.parse(obj.content),obj.user_id);
	}	
}
function releaseAll(){
	window.parent.leadGlobal=0;
	window.parent.leadNameGlobal='';
	window.parent.snapGlobal='';
	window.parent.snp='';
	window.parent.snapGlobalFileID='';
	window.parent.snapGlobalFileWorkName='';
	window.parent.jQuery("#show_data").empty();
	window.parent.jQuery("#scrap_patent_data").find('tbody').empty();
	window.parent.jQuery("#preSaleActivityTable").find('tbody').empty();
	window.parent.jQuery("#aquisitionTable").find('tbody').empty();
	window.parent.jQuery("#activityTable").find('tbody').empty();
	window.parent.jQuery("#scrap_patent_data").find('tbody').empty();
	window.parent.jQuery("#salesActivityButton").removeAttr('onclick');
	window.parent.jQuery("#acquisitionActivityButton").removeAttr('onclick');
}

function imapMessageShow(message,userID,sentFrom){	
	user = 0;
	if(typeof userID!='undefined'){
		user = userID;
	}
	_attachments = "";
	if(typeof sentFrom=='undefined'){resetMenus(); releaseAll();}
	if(typeof(message.attachments)!="undefined" && message.attachments.length>0){
		for(i=0;i<message.attachments.length;i++){
			name = "";
			if(typeof(message.attachments[i].name)!="undefined"){
				name = message.attachments[i].name;
			} else if(typeof(message.attachments[i].filename)!="undefined"){
				name = message.attachments[i].filename;
			}
			_attachments +='<li><i class="glyph-icon icon-file" style="color:#2196f3"></i> <a target="_BLANK" href="javascript:// class="strong text-regular"><strong>'+name+'</strong></a></li>';
		}
		_attachments = "<ul class='todo-box-1'>"+_attachments+"</ul>";
	}
	_to="";
	if(typeof(message.to)!='undefined'){
		_to = message.to.join(",");
	}
	_cc="";
	if(typeof(message.cc)!='undefined'){
		_cc = message.cc.join(",");
	}
	_messageBody= message.body;
	if(isHTML(_messageBody)===false){
		_messageBody= message.body;
		_messageBody = _messageBody.nl2br();
	}
	/*jQuery(_messageBody)
	(function($) {
    $.strRemove = function(theTarget, theString) {
        return $("<div/>").append(
            $(theTarget, theString).remove().end()
        ).html();
    };
})(jQuery);
	*/
	_messagesString ='<div class="row">'+
										'<div class="col-md-12 col-sm-12 col-xs-12">'+
										'    <div class="p-20">'+				
										'        <div class="message-item media">'+
										'            <div class="message-item-right">'+
										'                <div class="media">'+
										'                    <div class="media-body">'+
										'                        <p class="c-gray"></p>'+
										'                    </div>'+
										'                </div>'+
										'            </div>'+
										'        </div>'+
										'    </div>'+
										'   <div class="message-body" id="message-body">'+_messageBody+
										'    </div>'+
										'</div>'+
									'</div>';
	_emailBody ='<div data-padding="40" data-height="window" class="panel panel-default panel-no-margin withScroll mCustomScrollbar _mCS_117" id="message-detail" style="height:300px;max-height:300px;border:0;overflow-y:auto">'+
	'<div id="mCSB_117" class="mCustomScrollBox mCS-dark-2" data-message-id="'+message.overview.message_id+'" data-message-reference="'+message.overview.references+'">'+
	'<div class="mCSB_container">'+
	'<div class="panel-heading messages message-result">'+
	'<h2 class="message-detail-title is-subject p-t-20 w-500 show">'+
    '<span class="message-detail-subject">'+message.subject+'</span>'+
    '</h2>'+
	'<div class="row">'+
	'<div class="col-xs-5">'+
	'<h2 id="messageDetailTitleSubject" class="message-detail-title p-t-20 w-500 show">'+
	'<strong><span class="message-detail-from show">'+message.from+'</span></strong>'+
	'</h2>'+
	'<h2 class="message-detail-title p-t-20 w-500 show">'+
	'to: <span class="message-detail-to">'+_to+'</span>'+
	'<div class="message-detail-buttons-left btn-group" role="group">'+
	'<div class="btn-group" role="group">'+
	'<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+
	'<span class="caret"></span>'+
	'</button>'+
	'<div class="dropdown-menu" role="menu" style="width:300px">'+
	'<div class="row">'+
	'<div class="col-xs-3">'+
	'<label>from:</label>'+
	'</div>'+
	'<div class="col-xs-9">'+
	'<strong class="message-detail-from"><xmp>'+message.from+'</xmp></strong>'+
	'</div>'+
	'</div>'+
	'<div class="row">'+
	'<div class="col-xs-3">'+
	'<label>to:</label>'+
	'</div>'+
	'<div class="col-xs-9">'+
	'<span class="message-detail-to"><xmp>'+_to+'</xmp></span>'+
	'</div>'+
	'</div>'+
	'<div class="row">'+
	'<div class="col-xs-3">'+
	'<label>cc:</label>'+
	'</div>'+
	'<div class="col-xs-9">'+
	'<span class="message-detail-cc"><xmp>'+_cc+'</xmp></span>'+
	'</div>'+
	'</div>'+
	'<div class="row">'+
	'<div class="col-xs-3">'+
	'<label>date:</label>'+
	'</div>'+
	'<div class="col-xs-9">'+
	'<span class="message-detail-date">'+moment(new Date(message.date)).format("MMM DD, YYYY")+'</span>'+
	'</div>'+
	'</div>'+
	'<div class="row">'+
	'<div class="col-xs-3">'+
	'<label>subject:</label>'+
	'</div>'+
	'<div class="col-xs-9">'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</h2>'+
	'</div>'+
	'<div class="col-xs-7 text-right">'+
	'<h2 class="message-detail-title p-t-20 w-500 is-date show">'+
	'<span class="message-detail-date">'+moment(new Date(message.date)).format("MMM DD, YYYY")+'</span>'+
	'</h2>'+
	'<div class="message-detail-right show">'+
	'<a href="javascript://" onclick="javascript://" data-original-title="" class="message-detail-star tooltip-button" title="To favorite" data-placement="bottom"></a>'+
	'<div class="message-detail-buttons-right btn-group" role="group">';
	if(typeof sentFrom=="undefined"){
		_emailBody +=
		'<button type="button" class="btn btn-default tooltip-button mrg5R" onclick="fileTaskImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" title="File &#43; Task data-placement="bottom"> File &#43; Task'+
		'</button>'+
		'<button type="button" class="btn btn-default tooltip-button mrg5R" onclick="fileImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" title="File" data-placement="bottom"> File'+
		'</button>'+
		'<button type="button" class="btn btn-default tooltip-button" onclick="replyImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" title="Reply All" data-placement="bottom"> Reply All'+
		'</button>';
	} else if(leadGlobal>0 && typeof sentFrom!="undefined") {
		_emailBody +=	
		'<button type="button" id="btnResend" class="btn btn-default tooltip-button mrg5R" onclick="resendImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+','+_whichNum+',jQuery(this))" title="Resend this message" data-placement="bottom"> Resend'+
		'</button>'+
		'<button type="button" class="btn btn-default tooltip-button mrg5R" onclick="taskImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" title="Task Only" data-placement="bottom"> Task'+
		'</button>'+
		'<button type="button" class="btn btn-default tooltip-button" onclick="replyImapEmailAll('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" title="Reply All" data-placement="bottom"> Reply All'+
		'</button>';
	}	
	_emailBody +='<div class="btn-group" role="group">'+
	'<button type="button" class="btn btn-default dropdown-toggle eReply" data-toggle="dropdown" aria-expanded="false">'+
	'<span class="caret"></span>'+
	'</button>'+
	'<ul class="dropdown-menu" role="menu">'+
	'<li><a href="javascript://" onclick="openEmailDetails()" class="eReply"> Email Open</a></li>'+
	'<li><a href="javascript://" onclick="replyImapEmail('+message.overview.msgno+','+message.overview.uid+','+sentFrom+')" class="eReply"><i class="glyph-icon icon-reply"></i> Reply</a></li>'+
	'<li><a href="javascript://" onclick="printEmail(jQuery(this))">Print</a></li>'+
	'<li><a href="javascript://" onclick="threadLabelChanged("\Trash\",jQuery(this))">Delete</a></li>'+
	'<li><a href="javascript://" onclick="threadLabelChanged("\Spam\",jQuery(this))">Report spam</a></li>'+
	'<li><a href="javascript://" onclick="threadLabelChanged("\Unread\",jQuery(this))">Mark as unread</a></li>'+
	'</ul>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>'+
	'<div class="panel-body messages message-result message_detail">'+
	'<div class="loading-spinner" id="loading_spinner" style="display:none">'+
	'<img src="'+__baseUrl+'public/images/ajax-loader.gif" alt="">'+
	'</div>'+_messagesString+_attachments+
	'</div>'+
	'</div>'+
	'</div>'+
	'</div>';
	jQuery("#displayEmail").html(_emailBody);
	jQuery("#emailThreadId").val("");
	jQuery("#emailMessageId").val("");  
	$(".dropdown-toggle").dropdown();
	jQuery("#emailSubject").val("");
	jQuery("#emailThreadId").val("");
	jQuery("#emailMessageId").val("");
	jQuery("#eventT").val(jQuery("#activityMainType").val());
	jQuery("#eventCid").val(0);
	jQuery("#eventPid").val(0);	
	checkMyEmailsHeight();
}
setInterval(callUsersCalendar,300000); 
function callUsersCalendar(){
	data = {}
	call(__baseUrl+'users/getServiceAccountCalendar',"GET",data,userCalendarMessage,"json");
}
window.userCalendarMessage = function(){}
function resendImapEmailAll(mesgNo,UID,sentFrom,whichN,o){
	o.css('display','none');
	data={mesg_no:mesgNo,uid:UID,sent_from:sentFrom,which_n:whichN,lead:leadGlobal};
	call(__baseUrl+'dashboard/resendImapEmail','POST',data,resendMessage,'json');
}
function openLeadPredefinedTemplate(){
	jQuery("#activityType").val(7);
	checkActivityLog();
}
function resendMessage(data,textStatus,xhr){
	if(data>0){
		jQuery("#btnResend").css('display','');
		showMessageOnFly("Email send!","alert-info");
	}
}

function taskImapEmailAll(mesgNo,UID,sentFrom){
	openTaskModal();
}

function fileImapEmailAll(mesgNo,UID,sentFrom){
	_replyEmailFlag = 3;
	_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
	_email = htmlDecode(_email);
	if (_email.indexOf("<") >= 0) {
		_nn = _email.substr(0, _email.indexOf("<"));
		_ss = _email.substr(_email.indexOf("<"));
		email = _ss.substr(1, _ss.indexOf(">") - 1)
	} else {
		email = _email;
	}
	findActivitesLeadsFromEmail(email,mesgNo,UID,sentFrom);	
}
function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes[0].nodeValue;
}
function fileTaskImapEmailAll(mesgNo,UID,sentFrom){
	_replyEmailFlag = 4;
	_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
	_email = htmlDecode(_email);
	if (_email.indexOf("<") >= 0) {
		_nn = _email.substr(0, _email.indexOf("<"));
		_ss = _email.substr(_email.indexOf("<"));
		email = _ss.substr(1, _ss.indexOf(">") - 1)
	} else {
		email = _email;
	}
	findActivitesLeadsFromEmail(email,mesgNo,UID,sentFrom);	
}

/*Imap Email Reply*/
function replyImapEmail(msgNo,UID,sentFrom){	
	if(leadGlobal>0){
		replyImapEmailActivities();
	} else {
		_replyEmailFlag = 1;
		_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
		_email = htmlDecode(_email);
		if (_email.indexOf("<") >= 0) {
			_nn = _email.substr(0, _email.indexOf("<"));
			_ss = _email.substr(_email.indexOf("<"));
			email = _ss.substr(1, _ss.indexOf(">") - 1)
		} else {
			email = _email;
		}
		findActivitesLeadsFromEmail(email,msgNo,UID,sentFrom);	
	}	
}
function replyImapEmailAll(msgNo,UID,sentFrom){
	if(leadGlobal>0){
		replyImapEmailAllActivities();
	} else {
		_replyEmailFlag = 2;
		_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
		_email = htmlDecode(_email);
		if (_email.indexOf("<") >= 0) {
			_nn = _email.substr(0, _email.indexOf("<"));
			_ss = _email.substr(_email.indexOf("<"));
			email = _ss.substr(1, _ss.indexOf(">") - 1)
		} else {
			email = _email;
		}
		findActivitesLeadsFromEmail(email,msgNo,UID,sentFrom);	
	}
	
}
/*Imap Email Reply End*/
function findActivitesLeadsFromEmail(email,msgNo,UID,sentFrom){
	jQuery.ajax({
		type:'post',
		data:{email:email},
		url:__baseUrl+'dashboard/find_activites_leads_from_email',
		success:function(d){
			if(d!=""){
				_d = jQuery.parseJSON(d);
				if(_d.person_id>0){
					if(_d.count==0){
						if(_d.lead!=0){			
							associateEmailWithLead(msgNo,UID,_d.activity,_d.company_id, _d.person_id,_d.lead,_d.lead_name);
						}
					}else{
						if(_d.list.length>0){
							_table = '<div style="overflow-x:none;overflow-y:scroll;height:300px;"> <table id="lb'+UID+'" class="table table-striped table-hover"><thead><tr><th>Lead</th><th>Activity</th></tr></thead><tbody>';
							for(l=0;l<_d.list.length;l++){
								_row = _d.list[l];
								_activityName="";
								if(_d.list[l].activity==1){
									_activityName = "Sales";
								} else if(_d.list[l].activity==2){
									_activityName = "Acquisition";
								}
								_table +='<tr onclick="associateEmailWithLead('+msgNo+','+UID+','+_d.list[l].activity+','+_d.company_id+','+_d.person_id+','+_d.list[l].id+',\''+_d.list[l].lead_name+'\');" style="cursor:pointer;"><td>'+_d.list[l].lead_name+'</td><td>'+_activityName+'</td></tr>';
							}
							_table +='</tbody></table>';
							window.parent.jQuery("#sortingPopup").find(".modal-body").html(_table);
							window.parent.jQuery("#sortingPopup").find("#lb"+UID).DataTable().destroy();						
							window.parent.jQuery("#sortingPopup").modal("show");
							window.parent.jQuery("#sortingPopup").find("#lb"+UID).DataTable({destroy:true,paging:false,searching:true,language:{emptyTable:"No record found!"}});
						}
					}
				} else {
					data = {email:email,msgNo:msgNo,UID:UID,sentFrom:sentFrom};
					call(__baseUrl+'dashboard/runCheckingEmailFailureActivity','POST',data,runCheckingEmailFailureActivity,'json');
				}
			}
		}
	});
}

window.runCheckingEmailFailureActivity = function(data,textStatus,xhr){
	if(data.failure==0){
		openAddForm();
		window.parent.jQuery('#addContactForm').find('#inviteeEmail').val(data.email);
	} else {
		window.parent.jQuery("#messages-list").find('div[data-id='+data.UID+']').remove();
		if(leadGlobal>0 && leadGlobal==data.lead_id){
			refreshAcquisitionAndSalesActivity();
		} else {
			activity = data.activity;
			window.parent.jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");
			_index=window.parent.jQuery("#all_type_list").find("tbody").find('tr[data-id="'+data.lead_id+'"]').index();
			window.parent.jQuery("#all_type_list").find("tbody").find("tr").eq(_index).addClass("active");
			window.parent.jQuery(".DTFC_Cloned").find("tbody").find("tr").eq(_index).addClass("active");
			_scrollTop=window.parent.jQuery("#all_type_list").find("tbody").find("tr.active").offset();
			console.log(_scrollTop);
			if(window.parent.jQuery("#dashboard_charts").is(":visible")){
				window.parent.jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-263.5);
				console.log('KS');
			} else {
				window.parent.jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106);
				console.log('BKS');
			}
			if(activity==1){
				window.parent.jQuery('#sales_acititity').removeClass('hide').addClass('show');
				window.parent.jQuery('#activityTable').removeClass('hide').addClass('show');
				window.parent.jQuery('#aquisitionTable').removeClass('show').addClass('hide');
			} else if(activity==2){
				window.parent.jQuery('#sales_acititity').removeClass('hide').addClass('show');
				window.parent.jQuery('#aquisitionTable').removeClass('hide').addClass('show');
				window.parent.jQuery('#activityTable').removeClass('show').addClass('hide');
			}
			/*if(window.parent.jQuery("#search_lead_box").is(':visible'))*/
			window.parent.threadDetail(window.parent.jQuery("#all_type_list").find("tbody").find("tr.active"));
		}
		if(_replyEmailFlag==4){
			/*Task*/
			window.leadGlobal = data.lead_id;
			window.parent.openTaskModal();
			window.parent.jQuery("#taskEmailId").val(_d.send_email);
			/*End Task*/
		}		
	}
}

function associateEmailWithLead(msgNo,uid,activity,companyID,personID,leadID,leadName){
	window.parent.jQuery("#activityMainType").val(activity);
	_company_id = companyID;
	_person_id = personID;
	window.parent.leadGlobal = leadID;
	window.parent.leadNameGlobal = leadName;
	console.log("LeadID:"+window.parent.leadGlobal);
	console.log("Name:"+window.parent.leadNameGlobal);
	/*_MessageID*/
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'dashboard/linkImapMessage',
		data:{msg_no:msgNo,uid:uid,lead_id:leadID,p_id:personID,c_id:companyID,activity_type:activity},
		success:function(d){ if(d>0){window.parent.jQuery("#sortingPopup").modal("hide");
		if(_replyEmailFlag==1 || _replyEmailFlag==2){
			window.parent.sendTask = window.parent.jQuery("#messages-list").find('div[data-id='+uid+']').attr('data-task');
		} else if(_replyEmailFlag==4){
			openTaskModal();
			jQuery("#taskEmailId").val(d);
		}		
		window.parent.jQuery("#messages-list").find('div[data-id='+uid+']').remove();		
		window.parent.jQuery("#all_type_list").find("tbody").find("tr").removeClass("active");
		_index=window.parent.jQuery("#all_type_list").find("tbody").find('tr[data-id="'+leadID+'"]').index();
		window.parent.jQuery("#all_type_list").find("tbody").find("tr").eq(_index).addClass("active");
		window.parent.jQuery(".DTFC_Cloned").find("tbody").find("tr").eq(_index).addClass("active");
		_scrollTop=window.parent.jQuery("#all_type_list").find("tbody").find("tr.active").offset();
		console.log(_scrollTop);
	if(window.parent.jQuery("#dashboard_charts").is(":visible")){
		window.parent.jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-263.5);
		console.log('KS');
	} else {
		window.parent.jQuery("#all_type_list").parent().scrollTop(_scrollTop.top-106);
		console.log('BKS');
	}
	if(activity==1){
		window.parent.jQuery('#sales_acititity').removeClass('hide').addClass('show');
		window.parent.jQuery('#activityTable').removeClass('hide').addClass('show');
		window.parent.jQuery('#aquisitionTable').removeClass('show').addClass('hide');
	} else if(activity==2){
		window.parent.jQuery('#sales_acititity').removeClass('hide').addClass('show');
		window.parent.jQuery('#aquisitionTable').removeClass('hide').addClass('show');
		window.parent.jQuery('#activityTable').removeClass('show').addClass('hide');
	}
	/*if(window.parent.jQuery("#search_lead_box").is(':visible'))*/
	window.parent.threadDetail(window.parent.jQuery("#all_type_list").find("tbody").find("tr.active"));}}
	});
	if(_replyEmailFlag==1){
		replyImapEmailActivities();
	}else if(_replyEmailFlag==2){
		replyImapEmailAllActivities();
	}
}
re = /\S+@\S+\.\S+/;
_cmu = 'licenses@synpat.com';
function replyImapEmailActivities(){
	embedReference = false;
	if(leadGlobal>0){
		_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
		_nn = "";
		_newem = "";
		if (_email.indexOf("<") >= 0) {
			_nn = _email.substr(0, _email.indexOf("<"));
			_ss = _email.substr(_email.indexOf("<"));
			_newem = _ss.substr(1, _ss.indexOf(">") - 1)
		} else {
			_nn = _email;
			_newem = _email
		}
		openSlidebar(jQuery("#gmail_message_modal"));
		jQuery("#gmail_message").css("display", "block");
		jQuery(".gmail-modal").css("display", "block");
		jQuery("body").removeAttr("onselectstart");
		document.oncontextmenu = new Function("return true");
		jQuery(".dropdown-toggle").dropdown();
		jQuery("#emailName").val(jQuery.trim(_nn));
		if(re.test(_newem) && _newem!=_cmu){
			jQuery("#emailTo").val(_newem + ", ");
			findDataRemove.push(_newem);
		} else {
			if(_newem==_cmu){
				embedReference=true;
			}
			_email=jQuery("#displayEmail").find(".message-detail-to").eq(0).html();
			_nn = "";
			_newem = "";
			if (_email.indexOf("<") >= 0) {
				_nn = _email.substr(0, _email.indexOf("<"));
				_ss = _email.substr(_email.indexOf("<"));
				_newem = _ss.substr(1, _ss.indexOf(">") - 1)
			} else {
				_nn = _email;
				_newem = _email
			}			
			if(re.test(_newem) && _newem!=_cmu){
				jQuery("#emailName").val(jQuery.trim(_nn));
				jQuery("#emailTo").val(_newem + ", ");
				findDataRemove.push(_newem);
			} else {
				if(_newem==_cmu){
					embedReference=true;
				}
				jQuery("#emailName").val('');
				_newem = '';
				findDataRemove = [];
			}
		}	
		if (leadGlobal>0) {
			_subject = jQuery("#displayEmail").find('.message-detail-subject').text();
			if (_subject.indexOf("RE:") == -1 && _subject.indexOf("Re:") == -1 && _subject.indexOf("re:") == -1) {
				_subject = "RE: " + _subject
			}
			jQuery("#emailSubject").val(_subject);			
			if(embedReference === true){
					if(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id')!='undefined' && jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id')!=''){
					jQuery("#emailMessageId").val(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id'));
				}
				if(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference')!='undefined' && jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference')!=''){
					jQuery("#emailReference").val(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference'));
				}
			} else {
				jQuery("#emailMessageId").val('');
				jQuery("#emailReference").val('');
			}			
		}
		jQuery("#emailCC").css("width", "725px").val("");		
		jQuery("#attach_droppable").empty();
		jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + leadNameGlobal);
		jQuery("#messageLeadId").val(leadGlobal);
		jQuery("#emailMessage").focus();
		jQuery("#emailAccountType").val(2);
		checkBodyScrollable();
	} else {
		alert("Please associate email to lead first.");
	}
}
function replyImapEmailAllActivities(){
	embedReference = false;
	if(leadGlobal>0){
		_email = jQuery("#displayEmail").find('.message-detail-from').eq(0).html();
		_nn = "";
		_allToEmails=jQuery("#displayEmail").find(".message-detail-to").eq(0).html();
		_emailss=_allToEmails.split(",");
		_newem="";
		stringMainEmails=[];
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
						if(re.test(_newString)){							
							if(jQuery.inArray(_newString,stringMainEmails)!=-1) {
							}
							else {
								if(_newString!=window.parent._cmu) {
									stringMainEmails.push(_newString);
									_newem+=_newString+",";
								} else {									
									embedReference = true;
								}
							}
						}
					}
				}
			}
		}
		_cc=jQuery("#displayEmail").find(".message-detail-cc").eq(0).html();
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
							if(re.test(_newString)){
								if(jQuery.inArray(_newString,stringMainCCEmails)!=-1) {
								}
								else {
									if(_newString==_cmu){
										embedReference = true;
									}
									stringMainCCEmails.push(_newString);
									_ccEmailput+=_newString+",";
								}
							}							
						}
					}
				}
			}
		}
		console.log(_newem);
		_nn=_email.substr(0,_email.indexOf("<"));
		_ss=_email.substr(_email.indexOf("<"));
		_fromem=_ss.substr(1,_ss.indexOf(">")-1);
		if(re.test(_fromem)){
			if(_fromem!=_cmu) {
				_newem+=_fromem+",";
			} else {
				embedReference = true;
			}
		}
		
		if(_newem!="") {
			jQuery("#emailTo").val(_newem);
			findDataRemove.push(_newem)
		}

		if(_ccEmailput!="") {
			findDataCCRemove.push(_ccEmailput);
			jQuery("#emailCC").val(_ccEmailput)
		}
		if (leadGlobal>0) {
			_subject = jQuery("#displayEmail").find('.message-detail-subject').text();
			if (_subject.indexOf("RE:") == -1 && _subject.indexOf("Re:") == -1 && _subject.indexOf("re:") == -1) {
				_subject = "RE: " + _subject
			}
			jQuery("#emailSubject").val(_subject);			
			if(embedReference === true){
					if(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id')!='undefined' && jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id')!=''){
					jQuery("#emailMessageId").val(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-id'));
				}
				if(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference')!='undefined' && jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference')!=''){
					jQuery("#emailReference").val(jQuery("#displayEmail").find('#mCSB_117').attr('data-message-reference'));
				}
			} else {
				jQuery("#emailMessageId").val('');
				jQuery("#emailReference").val('');
			}			
		}
		openSlidebar(jQuery("#gmail_message_modal"));
		jQuery("#gmail_message").css("display", "block");
		jQuery(".gmail-modal").css("display", "block");
		jQuery("body").removeAttr("onselectstart");
		document.oncontextmenu = new Function("return true");
		jQuery(".dropdown-toggle").dropdown();
		jQuery("#emailCC").css("width", "725px");
		jQuery("#attach_droppable").empty();
		jQuery("#gmail_message_modal").find("h4").html("Reply Message: " + leadNameGlobal);
		jQuery("#messageLeadId").val(leadGlobal);
		jQuery("#emailMessage").focus();
		jQuery("#emailAccountType").val(2);
		checkBodyScrollable();
	} else {
		alert("Please associate email to lead first.");
	}
}
function openAddForm(){
	window.parent.jQuery("#contactFormSubmit").get(0).reset();
	window.parent.jQuery("#contactFormSubmit").find("#inviteeId").val("");
	window.parent.jQuery("#contactFormSubmit").find("#marSector").find("option").removeAttr("selected");
	// window.parent.jQuery("#addContactForm").css('z-index','9999');
	window.parent.jQuery(".multi-select").multiSelect('refresh');
	window.parent.jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
	window.parent.jQuery("#addContactForm").modal("show");
	window.parent.jQuery("#addContactForm").find("#createContactModalLabel").html('');
	window.parent.jQuery("#addContactForm #createContactModalLabel").css('width','30%');
	window.parent.jQuery("#addContactForm").find('input[name="invitee[company_id]"]').remove();
	window.parent.jQuery("#addContactForm").find('select#inviteeCompanyId').css('display','');
	window.parent.jQuery("#addContactForm").find('select#inviteeCompanyId').parent().find("label").css('display','');
	window.parent.jQuery("#addContactForm").find('#btnManageCategories').css('display','').attr('onclick','openCompanyThroughContact()');
}
function deleteGoogleContactModal(){
	if(jQuery("#inviteeId").val()>0){
		contactID = jQuery("#inviteeId").val();
		res = confirm("Are you sure?");
		if(res){
			jQuery.ajax({
				type:'POST',
				url:__baseUrl+'opportunity/deleteContact',
				data:{delete_link:jQuery("#inviteeId").val()},
				cache:false,
				success:function(data){
					jQuery("#addContactForm").modal("hide");	
					if(jQuery("#contactFormIframe").length>0){
						document.getElementById("contactFormIframe").contentWindow.location = document.getElementById("contactFormIframe").contentWindow.location.href;
					}
					if(jQuery("#activityMainType").val()>0){
						refreshAcquisitionAndSalesActivity();
					}
				}
			});
		}
	}
}

function openProgressPop(o){
	updateStage(o);
}
function updateStage(o){
	_c = o.parent().parent().attr("data-c");
	checkProcessColor();
	_s = o.val();
	if(_s==""){
		_s = 0;
	}
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'dashboard/update_sales_company_stage',
		data:{lead:leadGlobal,c:_c,s:_s},
		cache:false,
		success:function(data){
			jQuery("#statingPopup").modal("hide");
			refreshAcquisitionAndSalesActivity();
		}
	});
}

function checkAllContacts(type,obj){
	switch(type){
		case 1:
			/*Email*/
			jQuery('#activityTable').find('tbody').find('input[name="sales_person[]"]').each(function(){				
				if(jQuery(this).attr('data-attr-em')!='' && obj.is(':checked')){
					jQuery(this).prop('checked',true);
				} else {
					jQuery(this).prop('checked',false);
				}
			});
		break;
		case 2:
			/*LinkedIn*/
			jQuery('#activityTable').find('tbody').find('input[name="sales_person[]"]').each(function(){
				if(jQuery(this).attr('data-attr-linkedin')!='' && obj.is(':checked')){
					jQuery(this).prop('checked',true);
				} else {
					jQuery(this).prop('checked',false);
				}
			});
		break;
	}
}

function importFileToDrive(){
	_fileUrl = jQuery("#lititgationImportURL").val();
	_folder = jQuery("#clipboard").val();
	jQuery.ajax({
		url:__baseUrl+"leads/fileInsert",
		type:"POST",
		data:{file_name:'Compaint',doc_url:_fileUrl,d:leadGlobal,f:_folder,change:1},
		cache:false,
		success:function(k){
			
		}
	});
}
jQuery(document).ready(function(){
	/*jQuery('#emailMessage').summernote({
	  onChange: function(contents, $editable) {
		rangeSelection = document.getSelection();
	  }
	});*/
});