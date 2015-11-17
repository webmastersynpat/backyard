/* Responsive tabs */
_mainData = "";
	_allMainPatents = [];
	mainAllPatentData = [];
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
		window.open(jQuery(this).attr('data-href'),"_BLANK");
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
				if(leadGlobal>0){
					jQuery.ajax({
						type:'POST',
						url: __baseUrl + 'dashboard/delete_lead',
						data:{b:leadGlobal},
						cache:false,
						success:function(data){
							if(data>0){
								window.location = window.location.href;
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
	});
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
	function emptyForm(){		
		jQuery("#formLitigation").get(0).reset();
		jQuery("#litigationleadName").val(leadName);
		jQuery("#litigationFileUrl").val(snapGlobal);
		___table.destroy();
		___table1.destroy();
		___table2.destroy();
		___table3.destroy();
		___table4.destroy();
		_skeltonTable = '<div class="col-sm-12 float-left" style="margin-top:5px;width:100%;">'+
											'<div style="width:100%;">'+
											'	<div class="col-sm-12" id="tablesOtherData">'+
									'				<h3 class="title-hero">  '+
									'					Litigation Campaign'+
									'				</h3>'+
									'				<div class="example-box-wrapper">'+
									'					<ul class="nav-responsive nav nav-tabs">'+
									'						<li class="active"><a href="#tab1" data-toggle="tab">Cases</a></li>'+
									'						<li class=""><a href="#tab2" data-toggle="tab">Defendants</a></li>'+
									'						<li><a href="#tab3" data-toggle="tab">Patents</a></li>'+
									'						<li><a href="#tab4" data-toggle="tab">Accused Products</a></li>'+
									'						<li><a href="#tab5" data-toggle="tab">Docket Entries</a></li>'+
									'					</ul>'+
									'					<div class="tab-content">'+
									'						<div class="tab-pane active" id="tab1">'+
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
		___table =jQuery("#datatable-hide-columns"+leadGlobal).DataTable( {
			"paging": false,
			"searching":false
		});		
		jQuery("#datatable-hide-columns"+leadGlobal+"1").find('tbody').empty();
		___table1 =jQuery("#datatable-hide-columns"+leadGlobal+"1").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns"+leadGlobal+"2").find('tbody').empty();
		___table2 =jQuery("#datatable-hide-columns"+leadGlobal+"2").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns"+leadGlobal+"3").find('tbody').empty();
		___table3 =jQuery("#datatable-hide-columns"+leadGlobal+"3").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns"+leadGlobal+"4").find('tbody').empty();
		___table4 =jQuery("#datatable-hide-columns"+leadGlobal+"4").DataTable( {
			"paging": false							
		});
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
						_outPut = o.output;
						jQuery("#litigationScrapperData").val(JSON.stringify(_mainData));
						_leadAttorney = _outPut.LeadAttorney;
						_leadAttorney = _leadAttorney.replace(/(\r\n|\n|\r)/gm,"");
						_pacer = _outPut.pacer;
						_caseType = _outPut.casetype;
						_stage = _outPut.stage;
						_caseNumber = _outPut.data1;
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
						jQuery("#litigationCaseType").val(_title);	
						jQuery("#litigationCause").val(_title);	
						jQuery("#litigationCourt").val(_title);	
						jQuery("#litigationJudge").val(_title);	
						jQuery("#litigationPresiding").val(_title);	
						jQuery("#litigationleadName").val(_title+' - '+_caseNumber);
						jQuery("#litigationFillingDate").val(jQuery.trim(_stringFiled));
						jQuery("#litigationPlantiffsName").val(jQuery.trim(_pantiffString));
						_tables = _outPut.Tables;
						if(_tables[1]!=undefined){
							if(_tables[1].length>0){
								//table.fnDestroy();
								___table.destroy();
								jQuery("#datatable-hide-columns").find('tbody').empty();
								for(i=0;i<_tables[1].length;i++){
									_dateFiled = _tables[1][i][0];
									_caseName = _tables[1][i][1];
									_docketNumber = _tables[1][i][2];
									_terminationDate = _tables[1][i][3];
									jQuery("#datatable-hide-columns>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_caseName+'</td><td>'+_docketNumber+'</td><td>'+_terminationDate+'</td></tr>');
								}
								___table =jQuery("#datatable-hide-columns").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
								___table.destroy();
								___table =jQuery("#datatable-hide-columns").DataTable( {
									"paging": false,
									"searching":false
								});
							}
							
							if(_tables[2].length>0){
								jQuery("#litigationOriginalDefendants").val(_tables[2].length);
								___table1.destroy();
								jQuery("#datatable-hide-columns1").find('tbody').empty();
								_activeDefandants = 0;
								for(i=0;i<_tables[2].length;i++){
									_dateFiled = _tables[2][i][0];
									_defandants = _tables[2][i][1];
									_litigation = _tables[2][i][2];
									_terminationDate = _tables[2][i][3];
									if(_terminationDate==""){
										_activeDefandants++;
									}
									jQuery("#datatable-hide-columns1>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_litigation+'</td><td>'+_terminationDate+'</td></tr>');
								}
								jQuery("#litigationActiveDefendants").val(_activeDefandants);
								___table1 =jQuery("#datatable-hide-columns1").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#litigationOriginalDefendants").val(0);
								jQuery("#litigationActiveDefendants").val(0);
								jQuery("#datatable-hide-columns1").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
								___table1.destroy();
								___table1 =jQuery("#datatable-hide-columns1").DataTable( {
									"paging": false,
									"searching":false
								});
							}
							
							if(_tables[3].length>0){
								jQuery("#litigationNoOfPatent").val(_tables[3].length);
								___table2.destroy();
								jQuery("#datatable-hide-columns2").find('tbody').empty();
								for(i=0;i<_tables[3].length;i++){
									_patent = _tables[3][i][0];
									_title = _tables[3][i][1];
									_priority_date = _tables[3][i][2];
									jQuery("#datatable-hide-columns2>tbody").append('<tr><td>'+_patent+'</td><td>'+_title+'</td><td>'+_priority_date+'</td></tr>');
								}
								___table2 =jQuery("#datatable-hide-columns2").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#litigationNoOfPatent").val(0);
								jQuery("#datatable-hide-columns2").find('tbody').empty().append('<tr> <td colspan="3">No record found!</td></tr>');
								___table2.destroy();
								___table2 =jQuery("#datatable-hide-columns2").DataTable( {
									"paging": false,
									"searching":false
								});
							}
							
							if(_tables[4].length>0){
								___table3.destroy();
								jQuery("#datatable-hide-columns3").find('tbody').empty();
								for(i=0;i<_tables[4].length;i++){ 
									_dateFiled = _tables[4][i][0];
									_defandants = _tables[4][i][1];
									_accusedProduct = _tables[4][i][2];
									jQuery("#datatable-hide-columns3>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_accusedProduct+'</td></tr>');
								}
								___table3 =jQuery("#datatable-hide-columns3").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns3").find('tbody').empty().append('<tr> <td colspan="3">No record found!</td></tr>');
								___table3.destroy();
								___table3 =jQuery("#datatable-hide-columns3").DataTable( {
									"paging": false,
									"searching":false
								});
							}
							
							if(_outPut.docket_entries_table.length>0){
								___table4.destroy();
								jQuery("#datatable-hide-columns4").find('tbody').empty();
								for(i=0;i<_outPut.docket_entries_table.length;i++){
									__data = _outPut.docket_entries_table[i]
									_entry = __data[1];
									_dateFiled = __data[2];
									_dateEntered =__data[3];
									_entryDescription =__data[4];
									jQuery("#datatable-hide-columns4>tbody").append('<tr><td>'+_entry+'</td><td>'+_dateFiled+'</td><td>'+_dateEntered+'</td><td>'+_entryDescription+'</td></tr>');
								}
								___table4 =jQuery("#datatable-hide-columns4").DataTable( {
									"paging": false
								});
							} else {
								jQuery("#datatable-hide-columns4").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
								___table4.destroy();
								___table4 =jQuery("#datatable-hide-columns4").DataTable( {
									"paging": false
								});
							}
						}
						$('#loader').hide();
						$('#btnImport').removeAttr('disabled').attr('onclick','importDataFromExternalUrl()');
						$('#btnImport').parent().removeClass('col-xs-8').addClass('col-xs-12');
						jQuery("#cancelImport").hide();
						jQuery("#cancelImport").parent().hide();
					}
				});
			});
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
			if(_serialNumber!=""){
				if(!jQuery("#open_all_list").hasClass('is-open')) {
					$('body').append('<div class="modal-backdrop modal-backdrop-drive"></div>');
					jQuery("#open_all_list")
						.addClass("sb-active")
						.animate({ textIndent:0}, {
							step: function(now,fx) { 
								$(this).css('transform','translate(-350px)');
							},
							duration:'slow'
						}, 'linear');
					jQuery("#open_list").html('<iframe id="allListIframe" src="'+__baseUrl+'opportunity/all_list?alx='+_serialNumber+'&plx='+leadGlobal+'" width="100%" height="100px" scrolling="yes"></iframe>');
					jQuery("#open_all_list").addClass('is-open');
					open_all_listResize();
				} else {
					jQuery("#open_list").html('<iframe id="allListIframe" src="'+__baseUrl+'opportunity/all_list?alx='+_serialNumber+'&plx='+leadGlobal+'" width="100%" height="100px" scrolling="yes"></iframe>');
				}
			}
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
			alert("Sorry no lead selected.");
		}
	}

	function displayPatentTable(container){
		jQuery("#sales_acititity").removeClass("show").addClass("hide");
		if(jQuery("#"+container).find(".openPatentDetail").hasClass("hide")){
			jQuery("#"+container).find(".openPatentDetail").removeClass("hide").addClass("show");
			jQuery("#"+container).find("#patent_data").removeClass("hide").addClass("show");
		} else {
			jQuery("#"+container).find(".openPatentDetail").removeClass("show").addClass("hide");
			jQuery("#"+container).find("#patent_data").removeClass("show").addClass("hide");
		}
	}
	function displayLitigationCampaign(container){
		if(jQuery("#"+container).find("#show_data").hasClass("hide")){
			jQuery("#"+container).find("#show_data").removeClass("hide").addClass("show");
		} else {
			jQuery("#"+container).find("#show_data").removeClass("show").addClass("hide");
		} 
	}

	function displayAquisitionActivityTable(container,o){	
		jQuery('.actBtn').removeClass('active');
		o.addClass('active');
		jQuery("#"+container).find(".openPatentDetail").removeClass("show").addClass("hide");
		jQuery("#"+container).find("#patent_data").removeClass("show").addClass("hide");		
		if(jQuery("#sales_acititity").hasClass("hide")){
			jQuery("#btnActivityAll").text("Manage Sellers");
			jQuery("#activityMainType").val(2);
			jQuery("#sales_acititity").removeClass("hide").addClass("show");
			jQuery("#aquisitionTable").removeClass("hide").addClass("show");
			jQuery("#activityTable").removeClass("show").addClass("hide");
		} else {
			if(jQuery("#activityMainType").val()==2){
				jQuery("#sales_acititity").removeClass("show").addClass("hide");
			} else {
				jQuery("#activityMainType").val(2);
				jQuery("#btnActivityAll").text("Manage Sellers");
				jQuery("#sales_acititity").removeClass("hide").addClass("show");
				jQuery("#aquisitionTable").removeClass("hide").addClass("show");
				jQuery("#activityTable").removeClass("show").addClass("hide");
			}			
		}
		toggleCompanySales();
	}
	
	function displaySaleActivityTable(container,o){		
		jQuery('.actBtn').removeClass('active');
		o.addClass('active');
		jQuery("#"+container).find(".openPatentDetail").removeClass("show").addClass("hide");
		jQuery("#"+container).find("#patent_data").removeClass("show").addClass("hide");
		if(jQuery("#sales_acititity").hasClass("hide")){
			jQuery("#btnActivityAll").text("Manage Customers");
			jQuery("#activityMainType").val(1);
			jQuery("#sales_acititity").removeClass("hide").addClass("show");
			jQuery("#activityTable").removeClass("hide").addClass("show");
			jQuery("#aquisitionTable").removeClass("show").addClass("hide");
		} else {
			if(jQuery("#activityMainType").val()==1){
				jQuery("#sales_acititity").removeClass("show").addClass("hide");
			} else {
				jQuery("#btnActivityAll").text("Manage Customers");
				jQuery("#activityMainType").val(1);
				jQuery("#sales_acititity").removeClass("hide").addClass("show");
				jQuery("#activityTable").removeClass("hide").addClass("show");
				jQuery("#aquisitionTable").removeClass("show").addClass("hide");
			}
		}		
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
							if(jQuery("#from_litigation").is(":visible")){
								parentElement = "litigationSpreadsheetId";
							} else if(jQuery("#from_regular").is(":visible")){
								parentElement = "marketSpreadsheetId";
							} else if(jQuery("#from_nonacquistion").is(":visible")){
								parentElement = "acquisitionSpreadsheetId";
							}
							if(parentElement!=""){
								jQuery("#"+parentElement).find("option").remove();
								jQuery("#"+parentElement).append("<option value=''>-- Select SpreadSheet --</option>");
								for(i=0;i<_data.length;i++){
									jQuery("#"+parentElement).append("<option value='"+_data[i].id+"'>"+_data[i].title+"</option>");
								}
							}
						}
					}
				}
			});
		}
	}

	_editable =false;
	function initContainer(container){
		jQuery(container).find("#Container_Edittable").keydown(function(e){
			var keycode = e.charCode || e.keyCode;
			if (keycode  == 9) { 
				return false;
			}
		});
	}

	function backSwitchPatentFrom(parentElement){
		jQuery("#"+parentElement).find("#scrap_patent_data_market").find('.clickakble').dblclick(function(){
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
		jQuery("#"+parentElement).find("#scrap_patent_data_market").click(function(event){
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
			jQuery("#"+parentElement).find("#scrap_patent_data_market").find('.clickakble').each(function(){
				if(jQuery(this).find('#Container_Edittable').length>0){
					_val = jQuery(this).find('#Container_Edittable').val();
					jQuery(this).html(_val);
					jQuery(this).find('#Container_Edittable').remove();
				}
				jQuery("#"+parentElement).find("#scrap_patent_data_market").unbind("click");
			});
		}
	}

	function refreshHSTTable(parentElement){
		jQuery("#"+parentElement).find("#scrap_patent_data_market").find('tbody').empty();
	}

	function findPatentFromSheetForm(parentElement,d){
		mainAllPatentData=[];
		if(d!=undefined && d==1){
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
			jQuery("#"+parentElement).find('#loadingLink').addClass('overflow-link');
			jQuery("#"+parentElement).find("#loadingLabel").html('<i style="color: rgb(34, 34, 34); position: static;" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A"></i>');
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
		switch(type){
			case 'from_regular':
				if(jQuery("#"+type).find("#scrap_patent_data_market").find('tbody').find('tr.aggregate').length>0){
					jQuery('.aggregate_data').empty().append("<tbody><tr>"+jQuery("#"+type).find("#scrap_patent_data_market").find('tbody').find('tr.aggregate').html()+"</tr></tbody>");
					jQuery("#newAggregateRefrencedApplicant").modal("show");
					jQuery('body').removeAttr('onselectstart');
				}
			break;
			case 'from_litigation':
				if(jQuery("#"+type).find("#scrap_patent_data").find('tbody').find('tr.aggregate').length>0){
					jQuery('.aggregate_data').empty().append("<tbody><tr>"+jQuery("#"+type).find("#scrap_patent_data").find('tbody').find('tr.aggregate').html()+"</tr></tbody>");
					jQuery("#newAggregateRefrencedApplicant").modal("show");
					jQuery('body').removeAttr('onselectstart');
				}
			break;
		}
	}
	jQuery(document).ready(function(){
		jQuery('#newAggregateRefrencedApplicant').on('hidden.bs.modal', function () {
			jQuery('body').attr('onselectstart','return false');
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
		jQuery("#s_result").empty();
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
			jQuery("#"+parentElement).find('#loadingLink').removeClass('overflow-link');
			jQuery("#"+parentElement).find("#loadingLabel").html('');
			mainArray = [];
			if(jQuery("#"+parentElement).find("#scrap_patent_data_market").find("tbody").find("tr").length>0){
				jQuery("#"+parentElement).find("#scrap_patent_data_market").find("tbody").find("tr").each(function(){
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
						jQuery("#"+parentElement).find("#scrap_patent_data_market").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				jQuery("#"+parentElement).find("#scrap_patent_data_market").find("th").each(function(){
					_innerArray.push(null);
				});
				mainArray.push(_innerArray);
			}
			_patentDataValue = "";
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
			jQuery("#"+parentElement).find("#scrap_patent_data_market").find('tbody').empty();
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
					jQuery("#"+parentElement).find("#scrap_patent_data_market").find('tbody').append(_tr);
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
						jQuery("#"+parentElement).find("#scrap_patent_data_market").find('tbody').append(_tr);
					}
				}
				backSwitchPatentFrom(parentElement);
			} else {
				jQuery("#"+parentElement).find("#scrap_patent_data_market").find('tbody').append("<tr><<td colspan='9'>No able to import data</td>/tr>");
			}									
		} else {
			jQuery("#"+parentElement).find('#loadingLink').removeClass('overflow-link');
			// jQuery("#loadingLabel").html('Error while importing');
			alert('Error while importing');
		}
	}
	function getGooglePatent(patent){
		if(patent!=""){
			jQuery("#scrapGoogleData").find('.pad15A').html('<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style="display:none;"><img src="public/images/ajax-loader.gif" alt=""></div><div id="scrapGooglePatent"></div>');
			/*jQuery("#loading_spinner_heading_google_scrap").css('display','block');*/
			/*jQuery("#scrapGoogleData").addClass("sb-active").animate({ textIndent:0}, {
															step: function(now,fx) {
															  $(this).css('transform','translate(-350px)');
															},
															duration:'slow'
														},'linear');*/
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
			if($("#scrap_patent_data_market").find("tbody").find("tr.mainDataP").length>0){
				$("#scrap_patent_data_market").find("tbody").find("tr.mainDataP").each(function(){
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
			if(jQuery("#from_nonacquistion").find("#scrap_patent_data_market").find("tbody").find("tr.mainDataP").length>0){
				jQuery("#from_nonacquistion").find("#scrap_patent_data_market").find("tbody").find("tr.mainDataP").each(function(){
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
						jQuery("#from_nonacquistion").find("#scrap_patent_data_market").find("th").each(function(){
							_innerArray.push(null);
						});
						mainArray.push(_innerArray);
					}
				});
			} else {
				_innerArray = [];
				jQuery("#from_nonacquistion").find("#scrap_patent_data_market").find("th").each(function(){
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
						jQuery("#all_type_list>tbody>tr.active").find('td').eq(0).find('label>a').html(jQuery("#acquisitionlead_name").val());
					} else {
						alert(data);
					}									
				}
			});
		} else {
			jQuery('#'+parentElement).find("#loading_spinner_form_market").hide();
		}
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
						alert("Server busy. Refresh your page.");
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
	
	
	function findWorksheetUrlMarket(o,p){
		u = o.find('option:selected').attr('data-href');
		if(u!=""){
			p.val(u);
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

function checkActivityLog(){
	_mainActivity = jQuery("#activityMainType").val();
	_containerSelect = "";
	if(_mainActivity==1){
		_containerSelect = "activityTable";
	} else if(_mainActivity==2){
		_containerSelect = "aquisitionTable";
	}
	if(_containerSelect!=""){
	_c = jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").length;
	_p = "";
	_p = jQuery("#activityPerson").val();
	_error = 0;
	_message = "";
	_sales_emails = "";
	jQuery('#'+_containerSelect).find("input[name='sales_person[]']:checked").each(function(){
		_p +=jQuery(this).val()+",";
		_sales_emails +=jQuery(this).attr('data-attr-em')+", "
	});
	if(_p!=""){
		jQuery("#activityPerson").val(_p.substr(0,_p.length-1));
	}
	switch(parseInt(jQuery("#activityType").val())){
		case 7:
			getPredefinedMessages(3);
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
				alert("No contact memeber has linked profile url.");
			} else {
				getLeadTemplates(2);
			}
		break;
		case 9:
			composeEmail();
			jQuery("#eventT").val(jQuery("#activityMainType").val());
		break;
	} 
	} else {
		alert("There is something wrong, Please refresh your page.");
	}
}
function openTemplateEditor(){	$("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),jQuery("#open_template_editor").addClass("sb-active"),openSlidebar(jQuery("#open_template_editor")),slidebarOpenCallback(jQuery("#open_template_editor"));jQuery("body").removeAttr("onselectstart");document.oncontextmenu=new Function("return true");$(".dropdown-toggle").dropdown();}
function closeTemplateEditor(){jQuery("#templateEditor").code('');jQuery(".modal-backdrop-drive").remove();jQuery("#open_template_editor").removeClass("sb-active").removeClass("is-open");closeSlidebar(jQuery("#open_template_editor"));jQuery('#template_id').val(0);jQuery("body").attr("onselectstart","return false");document.oncontextmenu=new Function("return false");}
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
			data:{temp:jQuery("#templateEditor").code(),subject:jQuery("#template_subject").val(),name:jQuery("#template_file_name").val()},
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
			data:{temp:jQuery("#templateEditor").code(),id:jQuery('#template_id').val(),subject:jQuery("#template_subject").val(),name:jQuery("#template_file_name").val()},
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
					alert("Html file was created");
				} else {
					alert("Try after time");
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
	jQuery.ajax({
		type:'POST',
		url:__baseUrl+'leads/findAcquisitionAndSalesData',
		data:{boxes:leadGlobal},
		cache:false,
		success:function(data){
			if(data!=""){
				_data = jQuery.parseJSON(data);
				acquisitionImport(_data);
				salesActivityList(_data.sales_activity);
				docFileDraggable();
				initHoverEmailClose();
			}
		}
	});
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
	jQuery("#open_prefined_message").hasClass("is-open")?checkModalFrontOrHide(jQuery("#open_prefined_message"),function(){closeSlideBarLeftSales()}):($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")),slidebarOpenCallback(jQuery("#open_prefined_message")),jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="'+__baseUrl+'leads/pre_contacts/" width="100%" height="100px" scrolling="yes"></iframe>'),jQuery("#open_prefined_message").addClass("is-open"),open_prefined_listResize())
}
function loadDriveFiles(){
	jQuery.ajax({type:"POST",url:__baseUrl+"leads/findDriveFiles",data:{boxes:leadGlobal},cache:false,success:function(e){_data=jQuery.parseJSON(e);_drive=_data.drive;if(_drive.length>0){_container="";if(jQuery("#from_regular").is(":visible")){_container="#from_regular"}else{if(jQuery("#from_litigation").is(":visible")){_container="#from_litigation"}else{if(jQuery("#from_nonacquistion").is(":visible")){_container="#from_nonacquistion"}}}jQuery(_container).find("#litigation_doc_list>ul").empty();jQuery(_container).find('#clipboard').html('<option value="">Go to main</option>');for(d=0;d<_drive.length;d++){if(_drive[d].mimeType=="application/vnd.google-apps.folder"){jQuery(_container).find('#clipboard').append('<option value="'+_drive[d].id+'">'+_drive[d].title+'</option>');}if(_drive[d].mimeType=="application/pdf"||_drive[d].mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"||_drive[d].mimeType=="application/msword"||_drive[d].mimeType=="image/jpeg"||_drive[d].mimeType=="image/png"){url="https://docs.google.com/file/d/"+_drive[d].id+"/preview";jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable "><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" target="_BLANK" href="javascript://" class="drive_file_click" data-mime="'+_drive[d].mimeType+'" onclick="open_drive_files(\''+url+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}else{jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable"><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" data-mime="'+_drive[d].mimeType+'" target="_BLANK" href="javascript://" class="drive_file_click"   onclick="open_drive_files(\''+_drive[d].alternateLink+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}}}docFileDraggable();driveFileDraggable();initHoverEmailClose()}})
} 
function acquisitionImport(_data){ 
	if(_data.acquisition.length>0){a=_data.acquisition;jQuery("#aquisitionTable").find("tbody.main_active").empty();if(a.length>0){for(i=0;i<a.length;i++){_cID=a[i].company.id;_cName=a[i].company.company_name;_person="";_activity="";_date="";_note="";if(a[i].activities.length>0){_person=a[i].activities[0].firstName+" "+a[i].activities[0].lastName;_activity=salesActivities[a[i].activities[0].type];_date=a[i].activities[0].activity_date;_note=a[i].activities[0].note}_tr="<tr class='master '  data-c='"+_cID+"'><td style='width:65px;'><a href='javascript://' onclick='deleteAcquisitionInvitedC("+_cID+")'><i class='glyph-icon'><img src='"+__baseUrl+"public/images/discard.png' style='opacity:0.55'></i></a></td><td style='width:234px;'><a href='javascript://' class='showActivity'><i class='glyph-icon icon-play' title='Contacts' style='' ></i></a>&nbsp;<a href='javascript://' class='showActivity'>"+_cName+"</a></td><td style='width:100px;'>"+_activity+"</td><td style='width:110px;'>"+_date+"</td><td style='width:120px;'>"+_person+"</td>";if(_activity!="" && a[i].activities.length>0 &&a[i].activities[0].email_id!=0){if(a[i].activities[0].email.length>0){_d=a[i].activities[0].email[0];_receivedDate="";subject="";__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+_d.id+"\")'><i class='glyph-icon icon-close'></i></a>";if(_d.content!=""){_contents=jQuery.parseJSON(_d.content);if(_contents.length>0){for(c=0;c<_contents.length;c++){_content=_contents[c];header=_content.header;if(header.length>0){for(h=0;h<header.length;h++){if(header[h].name=="Subject"){subject = header[h].value}if(header[h].name=="Date"){_receivedDate=header[h].value}}}}}}_color='#2196f3';if(_d.sent_from==1){_color='#d1c8c8';}_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i> <a class='pull-left pad5L' style='width:93%;' href='javascript:void(0)' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+subject+"</a>";_innerTR='';if(_d.file_attach!=""&&_d.file_attach!="0"){_files=_d.file_attach.split(",");if(_files.length>0){for(f=0;f<_files.length;f++){if(_files[f]!=""){filename=_files[f].indexOf("upload");if(filename>0){filename=_files[f].substr(filename+7);translated=escapedString(_files[f]);_innerShowData="<a data-href='"+translated+"' data-mime='' onclick='open_drive_files(\""+translated+"\");' href='javascript://'  target='_BLANK' style='width:93%'><i class='glyph-icon icon-file-o' style='color:#2196f3'></i> "+filename+"</a>";_innerTR+="<tr class='"+_content.message_id+" attach docDragable'><td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_innerShowData+"</td></tr>";}}}}}_tr+="<td style='width:400px;'>"+_showData+"<div class='pull-left' style='width:100%;'><span class='message-item-date'>"+moment(new Date(_receivedDate)).format("MMM DD, YYYY")+"</span><span class='pull-right email-close hide' >"+__a+"</span></div><table>"+_innerTR+"</table></td></tr>";} else {if(a[i].activities[0].subject==""){_tr+="<td style='width:400px;'><a style='color:#56b2fe;text-decoration:underline;' href='javascript://' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>View Email</a></td></tr>"}else{_tr+="<td><a style='color:#56b2fe;text-decoration:underline;' href='javascript://' onclick='findOwnThread(\""+a[i].activities[0].email_id+"\",jQuery(this),2);'>"+a[i].activities[0].subject+"</a></td></tr>"}}}else{_tr+="<td><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}jQuery("#aquisitionTable").find("tbody.main_active").append(_tr);_cList="<table class='table' style='border:0px;'><thead><tr><th>#</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody></tbody></table>";_cActivites="<table class='table' style='border:0px;'></table>";if(a[i].people.length>0){_cList="";_tr="";for(p=0;p<a[i].people.length;p++){_name=a[i].people[p].first_name+" "+a[i].people[p].last_name;_phone=a[i].people[p].phone;if(_phone==""){_phone=a[i].people[p].telephone;if(_phone!=''){_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';}}else{if(a[i].people[p].telephone!=""){_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';_phone+='<br/><a href="javascript://" onclick=\'callFromLandline("'+a[i].people[p].telephone+'")\'>'+a[i].people[p].telephone+'</a>';} else {_phone = '<a href="javascript://" onclick=\'callFromLandline("'+_phone+'")\'>'+_phone+'</a>';}}_sLinks='';if(a[i].people[p].email!=''){_sLinks='<a href="javascript://" onclick="flagSaleActivity(1,jQuery(this))" data-attr-em="'+a[i].people[p].email+'" class="sales-activity-icon"><i class="glyph-icon icon-envelope-square"></i></a>';}if(a[i].people[p].linkedin_url!=''){_sLinks +='&nbsp;&nbsp;<a href="javascript://" onclick="flagSaleActivity(2,jQuery(this))" data-attr-linkedin="'+a[i].people[p].linkedin_url+'" class="sales-activity-icon"><i class="glyph-icon icon-linkedin"></i></a>';}_tr+="<tr class='salesFDroppable' data-c='"+_cID+"' data-p='"+a[i].people[p].id+"'><td style='border-left:0px; width:65px;'><input name='sales_person[]' class='sales-activity-checkbox' data-attr-em='"+a[i].people[p].email+"' data-attr-linkedin='"+a[i].people[p].linkedin_url+"' data-attr-name='"+_name+"' data-attr-c-name='"+_cName+"'  type='checkbox' value='"+a[i].people[p].id+"'/>"+_sLinks+"</td><td><a href='javascript://' onclick='editContact("+a[i].people[p].id+")'>"+_name+"</a></td><td>"+a[i].people[p].job_title+"</td><td style=''>"+_phone+"</td></tr>"}_cList="<table class='table' style='border:0px;'><thead><tr><th>#</th><th>Name</th><th>Title</th><th>Phone</th></tr></thead><tbody>"+_tr+"</tbody></table>"}if(a[i].activities.length>0){_cActivites="";_tr="";for(al=1;al<a[i].activities.length;al++){_person=a[i].activities[al].firstName+" "+a[i].activities[al].lastName;_activity=salesActivities[a[i].activities[al].type];_date=a[i].activities[al].activity_date;_note=a[i].activities[al].note;if(_activity!=""&&a[i].activities[al].email_id!=0){if(a[i].activities[al].email.length>0){_d=a[i].activities[al].email[0];_receivedDate="";subject="";__a="<a href='javascript:void(0);' class='' onclick='removeFromBox("+leadGlobal+',"'+_d.id+"\")'><i class='glyph-icon icon-close'></i></a>";if(_d.content!=""){_contents=jQuery.parseJSON(_d.content);if(_contents.length>0){for(c=0;c<_contents.length;c++){_content=_contents[c];header=_content.header;if(header.length>0){for(h=0;h<header.length;h++){if(header[h].name=="Subject"){subject = header[h].value}if(header[h].name=="Date"){_receivedDate=header[h].value}}}}}}_color='#2196f3';if(_d.sent_from==1){_color='#d1c8c8';}_showData="<i class='glyph-icon icon-envelope pull-left' style='color:"+_color+"'></i> <a class='pull-left pad5L' style='width:93%;' href='javascript:void(0)' onclick='findOwnThread(\""+a[i].activities[al].email_id+"\",jQuery(this),2);'>"+subject+"</a>";_innerTR='';if(_d.file_attach!=""&&_d.file_attach!="0"){_files=_d.file_attach.split(",");if(_files.length>0){for(f=0;f<_files.length;f++){if(_files[f]!=""){filename=_files[f].indexOf("upload");if(filename>0){filename=_files[f].substr(filename+7);translated=escapedString(_files[f]);_innerShowData="<a data-href='"+translated+"' data-mime='' onclick='open_drive_files(\""+translated+"\");' href='javascript://'  target='_BLANK' style='width:93%'><i class='glyph-icon icon-file-o' style='color:#2196f3'></i> "+filename+"</a>";_innerTR+="<tr class='"+_content.message_id+" attach docDragable'><td style='border-left: none; border-bottom: none; border-right:none; padding:5px 8px;'>"+_innerShowData+"</td></tr>";}}}}}_note=_showData+"<div class='pull-left' style='width:100%;'><span class='message-item-date'>"+moment(new Date(_receivedDate)).format("MMM DD, YYYY")+"</span><span class='pull-right email-close hide' >"+__a+"</span></div><table>"+_innerTR+"</table>";} else {if(a[i].activities[al].subject==""){_note="<a class='btn' href='javascript://' onclick='findOwnThread("+a[i].activities[al].email_id+",jQuery(this),2);'>View Email</a>"}else{_note="<a class='btn' href='javascript://' onclick='findOwnThread("+a[i].activities[al].email_id+",jQuery(this),2);'>"+a[i].activities[al].subject+"</a>"}}}_tr+="<tr><td style='width: 100px;'>"+_activity+"</td><td style='width: 110px;'>"+_date+"</td><td style='width: 120px;'>"+_person+"</td><td style='border-right:0px; width: 400px;'><div class='sales-activity-notes'><div class='sales-activity-notes-content'>"+_note+"</div></div><a href='' class='sales-activity-notes-icon' onclick='return salesActivityNotesIconClick(jQuery(this))'><i class='glyph-icon icon-angle-down'></i><i class='glyph-icon icon-angle-up'></i></a></td></tr>"}_cActivites="<table class='table' style='border:0px;'><tbody>"+_tr+"</tbody></table>"}_newTr="<tr style='display:none;'><td colspan='2' style='padding:0px;border:0px;width:300px;'>"+_cList+"</td><td colspan='4' style='padding:0px;border:0px;'>"+_cActivites+"</td></tr>";jQuery("#aquisitionTable").find("tbody.main_active").append(_newTr);}salesEmailDroppable()}toggleCompanySales();initHoverEmailClose() }else{jQuery('#aquisitionTable').find('tbody.main_active').empty()}
}					
function findThisDriveFile(o){
	if(o.val()==""){
		loadDriveFiles();
	} else {
		jQuery.ajax({type:"POST",url:__baseUrl+"leads/findDriveFilesSubFolder",data:{boxes:leadGlobal,f:o.val()},cache:false,success:function(e){_data=jQuery.parseJSON(e);_drive=_data.drive;if(_drive.length>0){_container="";if(jQuery("#from_regular").is(":visible")){_container="#from_regular"}else{if(jQuery("#from_litigation").is(":visible")){_container="#from_litigation"}else{if(jQuery("#from_nonacquistion").is(":visible")){_container="#from_nonacquistion"}}}jQuery(_container).find("#litigation_doc_list>ul").empty();for(d=0;d<_drive.length;d++){if(_drive[d].mimeType=="application/pdf"||_drive[d].mimeType=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"||_drive[d].mimeType=="application/msword"||_drive[d].mimeType=="image/jpeg"||_drive[d].mimeType=="image/png"){url="https://docs.google.com/file/d/"+_drive[d].id+"/preview";jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable "><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" target="_BLANK" href="javascript://" class="drive_file_click" data-mime="'+_drive[d].mimeType+'" onclick="open_drive_files(\''+url+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}else{jQuery(_container).find("#litigation_doc_list>ul").append('<li class="driveDragable"><img src="'+_drive[d].iconLink+'"/> <a data-file-id="'+_drive[d].id+'" data-href="'+_drive[d].alternateLink+'" data-mime="'+_drive[d].mimeType+'" target="_BLANK" href="javascript://" class="drive_file_click"   onclick="open_drive_files(\''+_drive[d].alternateLink+"')\">"+_drive[d].title+'</a><span class="pull-right drive-close hide"><a href="javascript:void(0);" class="" onclick="deleteDrive(\''+_drive[d].id+'\')"><i class="glyph-icon icon-close"></i></a></span></li>')}}}docFileDraggable();driveFileDraggable();initHoverEmailClose()}});
	}
}						
window.runThreadDetail= function (){
	threadDetail(jQuery("#all_type_list").find("tbody").find('tr.active'));	
};
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
	}
	if(_container!=""){
		_table = "<table class='table table-bordered' id='sortingActivity'><thead><tr><th>Name</th></tr></thead><tbody>";
		jQuery("#"+_container).find('tr.master').each(function(){
			_data_c = jQuery(this).attr('data-c');
			_name = jQuery(this).find('td').eq(1).find('a.showActivity').eq(1).text();
			_table +="<tr data-c='"+_data_c+"' class='sorting_tr_activity' onclick='findSelectedCompany(jQuery(this))'><td>"+_name+"</td></tr>";
		});
		_table +="</tbody></table>";
		jQuery("#sortingPopup").find('.modal-body').html(_table);
		jQuery("#sortingPopup").off('shown.bs.modal').on('shown.bs.modal', function() {
			jQuery("#sortingActivity").DataTable({"paging": false,"destroy":true,"scrollY":"400px","language": {"emptyTable": "No record found!"}});
		});
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
			if(jQuery(this).find('td').eq(1).find('a.showActivity').eq(1).text()==o.find('td').eq(0).text()){
				jQuery(this).addClass('active');

				jQuery('#dashboard-page').scrollTop(0);
				jQuery('#dashboard-page').scrollTop(jQuery(this).offset().top-450);
			}
		});
	}
}