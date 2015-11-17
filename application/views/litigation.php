<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">

<style>
.form-horizontal .control-label{text-align:left}
</style>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datepicker/datepicker.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs-ui/tabs.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>


<script type="text/javascript">
    /* jQuery UI Tabs */

    $(function() { "use strict";
        $(".tabs").tabs();
    });

    $(function() { "use strict";
        $(".tabs-hover").tabs({
            event: "mouseover"
        });
    });
</script>

<!-- Boostrap Tabs -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs.js"></script>

<!-- Tabdrop Responsive -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs-responsive.js"></script>
<script type="text/javascript">
    /* Responsive tabs */
    $(function() { "use strict";
        $('.nav-responsive').tabdrop();
    });
</script>

<script type="text/javascript">

    /* Datatables hide columns */
	__mainTa = '<?php echo base64_encode('0')?>';
	var ___table,___table1,___table2,___table3,___table4;
    $(document).ready(function() {
		jQuery('.breadcrumb').html("<li><a>Leads</a></li><li class='active'>From Litigation</li>");
         ___table = $('#datatable-hide-columns').DataTable( {
            
            "paging": false
        } );
		 ___table1 = $('#datatable-hide-columns1').DataTable( {
            
            "paging": false
        } );
		 ___table2 = $('#datatable-hide-columns2').DataTable( {
            
            "paging": false 
        } );
		 ___table3 = $('#datatable-hide-columns3').DataTable( {
            
            "paging": false 
        } );
		___table4 = $('#datatable-hide-columns4').DataTable( {
            
            "paging": false 
        } );
        $('#datatable-hide-columns_filter').hide();
        $('#datatable-hide-columns2_filter').hide();
        $('#datatable-hide-columns1_filter').hide();
        $('#datatable-hide-columns3_filter').hide();

      
    } );

   
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
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/jquery.ajax-cross-origin.min.js"></script>
<script type="text/javascript">
    /* Datepicker bootstrap */

    $(function() { "use strict";
        $('.bootstrap-datepicker').bsdatepicker({
            format: 'yyyy-mm-dd'
        });
    });
	function emptyForm(){
		jQuery("#token").val(__mainTa);
		jQuery(".prev>a").attr('onclick',"record('prev')");
		jQuery(".next>a").attr('onclick',"record('next')");
		jQuery("#formLitigation")[0].reset();
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
									'							<table id="datatable-hide-columns"  class="table table-striped table-bordered " cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns1" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns2" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns3" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns4" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
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
		jQuery("#datatable-hide-columns").find('tbody').empty();
		___table =jQuery("#datatable-hide-columns").DataTable( {
			"paging": false,
			"searching":false
		});		
		jQuery("#datatable-hide-columns1").find('tbody').empty();
		___table1 =jQuery("#datatable-hide-columns1").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns2").find('tbody').empty();
		___table2 =jQuery("#datatable-hide-columns2").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns3").find('tbody').empty();
		___table3 =jQuery("#datatable-hide-columns3").DataTable( {
			"paging": false,
			"searching":false
		});
		jQuery("#datatable-hide-columns4").find('tbody').empty();
		___table4 =jQuery("#datatable-hide-columns4").DataTable( {
			"paging": false							
		});
	}
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
	
	_senddt = '<?php echo $this->session->userdata['id'];?>';
	function record(level){
		jQuery("#formLitigation").get(0).reset();
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>leads/litigation_record_incomplete',
			data:{token:jQuery("#token").val(),level:level,type:'Litigation'},
			cache:false,
			success:function(response){
				response = jQuery.parseJSON(response);
				if(response.results.length>0){
					_data = response.results[0].litigation;	
					if(jQuery("#otherParentId").length>0){
						jQuery("#otherParentId").val(_data.id);
					}
					if(jQuery("#sendLitigation").length>0){
						jQuery("#sendLitigation").val(_data.id);
					}
					jQuery("#litigationCaseName").val(_data.case_name);
					jQuery("#litigationNoOfPatent").val(_data.no_of_patent);
					jQuery("#litigationLeadAttorney").val(_data.lead_attorney);
					jQuery("#litigationLitigationStage").val(_data.litigation_stage);
					jQuery("#litigationFillingDate").val(_data.filling_date);
					jQuery("#litigationMarketIndustry").val(_data.market_industry);
					jQuery("#litigationActiveDefendants").val(_data.active_defendants);
					jQuery("#litigationCaseType").val(_data.case_type);
					jQuery("#litigationOriginalDefendants").val(_data.original_defendants);
					jQuery("#litigationCaseNumber").val(_data.case_number);
					jQuery("#litigationCourt").val(_data.court);
					jQuery("#litigationCause").val(_data.cause);
					jQuery("#litigationLinkToPacer").val(_data.link_to_pacer);
					jQuery("#litigationleadName").val(_data.lead_name);
					jQuery("#litigationFileUrl").val(_data.file_url);
					snapGlobal = _data.file_url;
					jQuery("#litigationScrapperData").val(_data.scrapper_data);
                    jQuery("#litgationPatentData").val(_data.patent_data);
					jQuery("#litigationId").val(_data.id);
                    
                    jQuery("#scrap_patent_data").handsontable("destroy"); 
					if(_data.patent_data!=""){
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({						
							startRows: 1,
							data:jQuery.parseJSON(_data.patent_data),
							startCols: 9,		
							colHeaders: ['Patent', 'Application','Title','Original Assignee','Current Assignee','Priority','File','Family','*  *  *  *  *  Notes  *  *  *  *  '],
							manualColumnResize: false,
							manualRowResize: false,
							minSpareRows: 1,
							persistentState: false,
							columnSorting: true,
							contextMenu: false,
							fixedRowsTop: 0,
							columns: [
										{renderer: coverRenderer},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{}
									]
						});
					}
                    
					if(_data.defendants=="" && _data.court_docket_entries==""){
						_skeltonTable = '<div class="col-sm-12 float-left" style="margin-top:5px;width:100%;padding:0;">'+
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
									'							<table id="datatable-hide-columns'+_data.id+'"  class="table table-striped table-bordered " cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns'+_data.id+'1" class="table table-striped table-bordered " cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns'+_data.id+'2" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns'+_data.id+'3" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
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
									'							<table id="datatable-hide-columns'+_data.id+'4" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
									'								<thead>'+
									'									<tr>'+
									'										<th>Entry #</th>'+
									'										<th>Date Filed</th>'+
									'										<th>Date Entered</th>'+
									'										<th>Entry description</th>'+
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
						jQuery(function() { "use strict";
							jQuery(".tabs").tabs();
						});

						jQuery(function() { "use strict";
							jQuery(".tabs-hover").tabs({
								event: "mouseover"
							});
						});
						jQuery(function() { "use strict";
							jQuery('.nav-responsive').tabdrop();
						});
						_mainData = jQuery.parseJSON(_data.scrapper_data);				
						_outPut = _mainData.output;
						_leadAttorney = _outPut.LeadAttorney;
						_leadAttorney = _leadAttorney.replace(/(\r\n|\n|\r)/gm,"");
						_pacer = _outPut.pacer;
						_caseType = _outPut.casetype;
						_caseNumber = _outPut.data1;
						_market = _outPut.market;
						_stringFiled =  _outPut.data2;
						_title = _outPut.title;
						_pantiffString = _title.split('v.');				
						_tables = _outPut.Tables;
						if(_tables[1]!=undefined){
							if(_tables[1].length>0){
								jQuery("#datatable-hide-columns"+_data.id).find('tbody').empty();
								for(i=0;i<_tables[1].length;i++){
									_dateFiled = _tables[1][i][0];
									_caseName = _tables[1][i][1];
									_docketNumber = _tables[1][i][2];
									_terminationDate = _tables[1][i][3];
									jQuery("#datatable-hide-columns"+_data.id+">tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_caseName+'</td><td>'+_docketNumber+'</td><td>'+_terminationDate+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id).DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id).find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[2].length>0){
								jQuery("#datatable-hide-columns"+_data.id+"1").find('tbody').empty();
								_activeDefandants = 0;
								for(i=0;i<_tables[2].length;i++){
									_dateFiled = _tables[2][i][0];
									_defandants = _tables[2][i][1];
									_litigation = _tables[2][i][2];
									_terminationDate = _tables[2][i][3];
									if(_terminationDate==""){
										_activeDefandants++;
									}
									jQuery("#datatable-hide-columns"+_data.id+"1>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_litigation+'</td><td>'+_terminationDate+'</td></tr>');
								}
								jQuery("#litigationActiveDefendants").val(_activeDefandants);
								jQuery("#datatable-hide-columns"+_data.id+"1").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"1").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[3].length>0){
								jQuery("#litigationNoOfPatent").val(_tables[3].length);
								jQuery("#datatable-hide-columns"+_data.id+"2").find('tbody').empty();
								for(i=0;i<_tables[3].length;i++){
									_patent = _tables[3][i][0];
									_title = _tables[3][i][1];
									_priority_date = _tables[3][i][2];
									jQuery("#datatable-hide-columns"+_data.id+"2>tbody").append('<tr><td>'+_patent+'</td><td>'+_title+'</td><td>'+_priority_date+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"2").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"2").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_tables[4].length>0){
								jQuery("#datatable-hide-columns"+_data.id+"3").find('tbody').empty();
								for(i=0;i<_tables[4].length;i++){ 
									_dateFiled = _tables[4][i][0];
									_defandants = _tables[4][i][1];
									_accusedProduct = _tables[4][i][2];
									jQuery("#datatable-hide-columns"+_data.id+"3>tbody").append('<tr><td>'+_dateFiled+'</td><td>'+_defandants+'</td><td>'+_accusedProduct+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"3").DataTable( {
									"paging": false,
									"searching":false
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"3").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
							
							if(_outPut.docket_entries_table.length>0){
								jQuery("#datatable-hide-columns"+_data.id+"4").find('tbody').empty();
								for(i=0;i<_outPut.docket_entries_table.length;i++){
									__data = _outPut.docket_entries_table[i]
									_entry = __data[1];
									_dateFiled = __data[2];
									_dateEntered =__data[3];
									_entryDescription =__data[4];
									jQuery("#datatable-hide-columns"+_data.id+"4>tbody").append('<tr><td>'+_entry+'</td><td>'+_dateFiled+'</td><td>'+_dateEntered+'</td><td>'+_entryDescription+'</td></tr>');
								}
								jQuery("#datatable-hide-columns"+_data.id+"4").DataTable( {
									"paging": false							
								});
							} else {
								jQuery("#datatable-hide-columns"+_data.id+"4").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							}
						}
					
					} else {
						jQuery("#show_data").html('<div class="col-sm-12 noPadding" style=\'overflow-y:scroll;overflow:x:none;height:400px;\'><div class="col-sm-6 noPadding" id="defendant"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div><div class="col-sm-6" id="court_docket"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div></div>');
					}
                    var timeLineTable = "";
                        _dataTimeLine = response.timeLine;	
                        //alert(_dataTimeLine.length);
                       
                        for(i=0;i<_dataTimeLine.length;i++){
                             
                              timeLineTable += '<div class="tl-row">'+
									               '<div class="tl-item float-right"><div class="tl-bullet bg-red"></div>'+
									                   	'<div class="popover right">'+
										                      '<div class="arrow"></div>'+
										                      	'<div style="cursor: pointer;" class="popover-content">';
                          
                             _colorClass = "";
                             _label = "";
                             if(_dataTimeLine[i].hasOwnProperty('leadType')){
                               switch(_dataTimeLine[i].leadType){
									case 'Litigation':
										_colorClass = "bg-yellow";
									break;
									
									case 'Market': 
										_colorClass = "bg-green";
									break;
									
									case 'General':
										_colorClass = "label-info";
									break;
									
									case 'SEP':
										_colorClass = "bg-warning";
									break;
								}
								_label = (_dataTimeLine[i].lead_name!="")?_dataTimeLine[i].lead_name:_dataTimeLine[i].plantiffs_name;
                             }
							timeLineTable += '<div class="tl-label bs-label '+_colorClass+'">'+_label+'</div>';
                            _userImage = "http://design.synpat.com/public/upload/user.png";
                             if(_dataTimeLine[i].profile_pic!=""){
                                _userImage = _dataTimeLine[i].profile_pic;
                             }
                             timeLineTable += 	'<p class="tl-content">'+_dataTimeLine[i].message+'</p>'+
                             					'<div class="tl-footer clearfix">'+
													'<div class="tl-time">'+
														_dataTimeLine[i].create_date+
													'</div>'+
													'<div class="tl-user">'+
														_dataTimeLine[i].name+
													'</div>'+
													'<img width="28" src="'+_userImage+'"/>'+
												'</div>' +
											'</div>'+
										'</div>'+
									'</div>'+
							'</div>';
                            //alert(timeLineTable);
                        }
                       
                        
                        jQuery(".timeline-box").html(timeLineTable);
						
						
						
					jQuery("#token").val(response.token);
					jQuery('.pager-text').html(response.current_page+'/'+response.no_of_pages);
					
					if((parseInt(response.current_page)+1)<=parseInt(response.no_of_pages)){
						jQuery(".next>a").attr('onclick',"record('next')");
					} else {
						jQuery(".next>a").removeAttr('onclick');
					}
					if((parseInt(response.current_page)-1)>0){
						jQuery(".prev>a").attr('onclick',"record('prev')");
					} else {
						jQuery(".prev>a").removeAttr('onclick');
					}					
					/*Comments*/
					_trData = "";
					_litigation = response.results[0].litigation;
					response = response.results[0].comment;
					if(response.length>0){					
						for(i=0;i<response.length;i++){
							if(response[i].user_id==_senddt){
								_commentID = response[i].id;
								_commentText = response[i].comment;
								_commentAttractive = response[i].attractive;
								jQuery("#litigationComment").val(_commentText);
								jQuery("#commentID").val(_commentID);
								jQuery("select[name='litigation[attractive]']").find('option').each(function(){ 
									if(jQuery(this).attr('value')==_commentAttractive){
										jQuery(this).attr('SELECTED','SELECTED');
									} else {
										jQuery(this).removeAttr('SELECTED');
									}
								});
							}
							if(response[i].user_id==_litigation.user_id){							
								_tr = '<tr>'+
									'<td>'+response[i].name+'</td>'+
									'<td>'+response[i].comment+'</td>'+
									'<td>'+$.datepicker.formatDate('M dd', new Date(response[i].created))+'</td>'+
									'<td class="text-right" width="10%"><span class="label alert label-success">'+response[i].attractive+'</span></td>'+					
								'</tr>';
								jQuery("#comment-list>tbody").html(_tr);
							}							
						}
					}					
				}
			}
		});
	}
</script>
<div class="panel dashboard-box">
    <div class="panel-body">
		 <div class="example-box-wrapper">
			<?php 
				if($this->session->flashdata('message')){
			?>
				<p class='alert alert-success'><?php echo $this->session->flashdata('message');?></p>
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
            <?php echo form_open('leads/litigation',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>'formLitigation',"onsubmit"=>"return dataValidate();", 'style'=>'margin-bottom: 0;'));?>
				<div class="row">
					<div class="col-xs-1">
						<button type="button" class="btn btn-danger btn-block" onclick="cancelImport();">Clear</button>
					</div>
					<div class="col-xs-8">
						<div class="row">
							<div class="col-sm-9">
								<input name="litigation1[import_url]" id="lititgationImportURL" class="form-control" placeholder="Paste URL"/>
							</div>
							<div class="col-sm-3">
								<div class="row">
									<div class="col-xs-12">
										<button type="button" id="btnImport" class="btn btn-black float-left btn-block" onclick="importDataFromExternalUrl();">
											<div id="loader" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data"></div>
											Import
										</button>
									</div>
									<div class="col-xs-4" style="display: none;">
										<a href="javascript://" id="cancelImport" class="btn btn-default float-left btn-block" style='display:none' onclick="cancelImport();">Cancel</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-2">
						<ul class="pager create-lead-pager clearfix">
							<li class="next"><a href="javascript:void(0)" onclick="record('next');" >Next <i class="glyph-icon icon-angle-right"></i></a></li>
							<li class="previous"><a href="javascript:void(0)" onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i> Previous</a></li>
						</ul>
					</div>
					<div class="col-xs-1">
						<div class="pager-text">
							0/<?php echo $incomplete_leads->leadCount;?>
						</div>
					</div>
				</div>
				<div class="row mrg5T">
					<div class="col-sm-5 col-md-5 col-xs-5">
						<!-- col-xs-4 col-xs-8 -->
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseName">Case Name:</label>
							<?php echo form_input(array('name'=>'litigation[case_name]','id'=>'litigationCaseName','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseName">Litigation Stage:</label>
							<?php echo form_input(array('name'=>'litigation[litigation_stage]','id'=>'litigationLitigationStage','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseName">Market/Industry:</label>
							<?php echo form_input(array('name'=>'litigation[market_industry]','id'=>'litigationMarketIndustry','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseName">Case Type:</label>
							<?php echo form_input(array('name'=>'litigation[case_type]','id'=>'litigationCaseType','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseNumber">Case Number:</label>
							<?php echo form_input(array('name'=>'litigation[case_number]','id'=>'litigationCaseNumber','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label class="control-label" for="litigationCaseName">Cause:</label>
							<?php echo form_input(array('name'=>'litigation[cause]','id'=>'litigationCause','placeholder'=>'','class'=>'form-control is-big'));?>
						</div>
					</div>
					<div class="col-sm-4 col-md-4 col-xs-4">
						<!-- col-xs-6 col-xs-6 -->
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationNoOfPatent">Number of Patents:</label>
							<?php echo form_input(array('name'=>'litigation[no_of_patent]','id'=>'litigationNoOfPatent','placeholder'=>'','class'=>'form-control is-small'));?>
						</div>
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationCaseName">Filling Date:</label>
							<?php echo form_input(array('name'=>'litigation[filling_date]','id'=>'litigationFillingDate','placeholder'=>'yyyy-mm-dd','class'=>'form-control bootstrap-datepicker'));?>
						</div>
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationCaseName">Number of Active Defendants:</label>
							<?php echo form_input(array('name'=>'litigation[active_defendants]','id'=>'litigationActiveDefendants','placeholder'=>'','class'=>'form-control is-small'));?>
						</div>
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationCaseName">Number of Original Defendants:</label>
							<?php echo form_input(array('name'=>'litigation[original_defendants]','id'=>'litigationOriginalDefendants','placeholder'=>'','class'=>'form-control is-small'));?>
						</div>
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationCaseName">Court:</label>
							<?php echo form_input(array('name'=>'litigation[court]','id'=>'litigationCourt','placeholder'=>'','class'=>'form-control'));?>
						</div>
						<div class="form-group input-string-group">
							<label class="control-label" for="litigationLinkToPacer">Link to Pacer:</label>
							<?php echo form_input(array('name'=>'litigation[link_to_pacer]','id'=>'litigationLinkToPacer','placeholder'=>'','class'=>'form-control'));?>
						</div>
					</div>
					<div class="col-sm-3 col-md-3 col-xs-3">
						<?php echo form_textarea(array('name'=>'litigation[lead_attorney]','id'=>'litigationLeadAttorney','placeholder'=>'Plaintiff\'s Lead Attorney','class'=>'form-control','rows'=>3,'cols'=>29,'style'=>'height:75px;margin-top:5px;'));?>
					</div>
				</div>
				
				<div class="row mrg10T">
					<div class="col-xs-10" style="width:90%;">
						<div class="form-group" style='width:100%;'>
							<label class="sr-only" for="litigationLinkToPacer">Comment</label>							
							<?php echo form_textarea(array('name'=>'litigation[comment]','id'=>'litigationComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>29));?>	
						</div>
					</div>
					<div class="col-xs-2" style="width:10%;">
						<select  class="form-control" name='litigation[attractive]'>
							<option value="">Attractiveness</option>
							<option value="High" >High</option>
							<option value="Medium">Medium</option>
							<option value="Low">Low</option>
							<option value="Disapproved">Disapproved</option>
						</select>
					</div>
				</div>	
				<div class="row mrg10T">
					<!--div class="col-xs-5">
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">
								<strong>Patent File Url:</strong>
							</label>
							<input type="textbox" class="form-control input-string is-big" name="litigation[file_url]" id="litigationFileUrl" value=""/>
						</div>						
					</div-->
                    <div class="col-xs-9">
                    	<div class="col-xs-6">
                    		<div class="form-group">
                    			<label for="generalTechnologies" class="control-label">
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
                    		<div class="form-group">
                    			<label for="generalTechnologies" class="control-label">
                    				<strong>Select Worksheet:</strong>
                    			</label>
                    			<select name="general[worksheet_id]" id="generalWorksheetId" class="form-control" onchange="findWorksheetUrl(jQuery(this))"></select>
                    		</div>
                    	</div>
                    </div>
					
					<div class="col-xs-3 ">
					<div class="form-group">
                    			<label for="generalTechnologies" class="control-label" style='width:111px;height:17px'>
                    				
                    			</label>
						<button type="button" class="btn btn-primary" onclick="findPatentFromSheet()" tabindex="13">Import / Refresh Data</button>
						<span id="loadingLabel"></span>
						</div>
					</div>
				</div>
				<div class="row mrg10T">
					<div class="col-xs-5">
						<div class="form-group">
							<label class="control-label" for="litigationLinkToPacer"><strong>Name of Lead:</strong></label>
							<?php echo form_input(array('name'=>'litigation[lead_name]','required'=>'required','id'=>'litigationleadName','placeholder'=>'','class'=>'form-control input-string is-big'));?>
						</div>
					</div>
					<div class="col-xs-7 text-right">
						<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">
							<input type="checkbox" value="1" name="litigation[complete]" /> Lead is complete and ready to be forwarded for review
							<input type='hidden' name="litigation[scrapper_data]" id="litigationScrapperData" class='form-control'/>
							<input type="hidden" name="litigation[patent_data]" value="" id="litgationPatentData"/>
							<input type='hidden' name="litigation[id]" id="litigationId" value="0" class='form-control'/>
							<input type='hidden' name="other[id]" id="commentID" value="0" class='form-control'/>
						</label>
				  		<button type="submit" class="btn btn-primary float-right">Save</button>  
					</div>
				</div>
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
							jQuery("#loadingLabel").html('Please wait......');
							jQuery.ajax({
								url:'<?php echo $Layout->baseUrl?>leads/googleSpreadSheet',
								type:'POST',
								data:{file_url:jQuery("#litigationFileUrl").val()},
								cache:false,
								success:function(data){
									if(data!=""){
										jQuery("#loadingLabel").html('');
										jQuery("#scrap_patent_data").handsontable("destroy");
										var $container = jQuery("#scrap_patent_data");
										$container.handsontable({						
											startRows: 1,
											data:jQuery.parseJSON(data),
											startCols: 9,		
											colHeaders: ['Patent', 'Application','Title','Original Assignee','Current Assignee','Priority','Fee Status','Family','*  *  *  *  *  Notes  *  *  *  *  '],
											manualColumnResize: false,
											manualRowResize: false,
											minSpareRows: 1,
											persistentState: false,
											columnSorting: true,
											contextMenu: false,
											fixedRowsTop: 0,
											columns: [
														{renderer: coverRenderer},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{renderer: renderReadOnly},
														{}
													]
										});
									} else {
										jQuery("#loadingLabel").html('Error while importing');
									}
								}
							});
						}
					}
                    jQuery(document).ready(function(){
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({	
   							startRows: 1,
							startCols: 9,								
							colHeaders: ['Patent', 'Application','Title','Original Assignee','Current Assignee','Priority','Fee Status','Family','*  *  *  *  *  Notes  *  *  *  *  '],
							manualColumnResize: false,
							manualRowResize: false,
							minSpareRows: 1,
							persistentState: false,
							contextMenu: false,
							columnSorting: true,
							fixedRowsTop: 0,
							columns: [
										{renderer: coverRenderer},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{}
									]
						});
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
						jQuery("#litgationPatentData").val(JSON.stringify(hst.getData()));
						return true;
					}
                    function refreshHST(){
						var r= confirm("Are you sure?");
					if(r==true){
						jQuery("#scrap_patent_data").handsontable("destroy"); 
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({						
							startRows: 1,
							startCols: 9,		
							colHeaders: ['Patent', 'Application','Title','Original Assignee','Current Assignee','Priority','Fee Status','Family','*  *  *  *  *  Notes  *  *  *  *  '],
							manualColumnResize: false,
							manualRowResize: false,
							minSpareRows: 1,
							persistentState: false,
							columnSorting: true,
							contextMenu: false,
							fixedRowsTop: 0,
							columns: [
										{renderer: coverRenderer},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{renderer: renderReadOnly},
										{}
									]
						});
							}
					}
                    
				</script>
                 <div style="clear:both;" class="clearfix mrg10T">
					<button class='btn btn-danger' type='button' onclick="refreshHST()">Clear Table</button>
				</div>
				<div class="mrg5T" style='margin-top:5px;width:100%;padding:0;' id="patent_data">
					<!-- <div class="panel panel-no-margin">
						<div class="panel-body"> -->
							<div class="example-box-wrapper">
								<div class="handsontable" id="scrap_patent_data" >
									
								</div>
							</div>
						<!-- </div>
					</div> -->
				</div>	
               			
				<div class="mrg15T" style='margin-top:5px;width:100%;' id="show_data">
					<div style='width:100%;'>
						<div class='' id="tablesOtherData">
							<h3 class="title-hero">  
								Litigation Campaign
							</h3>
							<div class="example-box-wrapper">
								<ul class="nav-responsive nav nav-tabs">
									<li class="active"><a href="#tab1" data-toggle="tab">Cases</a></li>
									<li class=""><a href="#tab2" data-toggle="tab">Defendants</a></li>
									<li><a href="#tab3" data-toggle="tab">Patents</a></li>
									<li><a href="#tab4" data-toggle="tab">Accused Products</a></li>
									<li><a href="#tab5" data-toggle="tab">Docket Entries</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab1">
										<table id="datatable-hide-columns"  class="table table-striped table-bordered " cellspacing="0" width="100%">
											<thead>
											<tr>
												<th>Date Filed</th>
												<th>Case Name</th>
												<th>Docket Number</th>
												<th>Termination Date</th>
											</tr>
											</thead>
											<tbody> 
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane " id="tab2">
										<table id="datatable-hide-columns1" class="table table-striped table-bordered " cellspacing="0" width="100%">
											<thead>
											<tr>
												<th>Date Filed</th>
												<th>Case Name</th>
												<th>Litigation</th>
												<th>Termination Date</th>
											</tr>
											</thead>
											<tbody> 
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="tab3">
										<table id="datatable-hide-columns2" class="table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
											<tr>
												<th>Patent #</th>
												<th>Title</th>
												<th>Est. Priority Date</th>
											</tr>
											</thead>
											<tbody> 
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="tab4">
										<table id="datatable-hide-columns3" class="table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
											<tr>
												<th>Date Filed</th>
												<th>Defandants</th>
												<th>Accused Products</th>
											</tr>
											</thead>
											<tbody> 
												
											</tbody>
										</table>
									</div>	
									<div class="tab-pane" id="tab5">
										<table id="datatable-hide-columns4" class="table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>Entry #</th>
													<th>Date Filed</th>
													<th>Date Entered</th>
													<th>Entry Description</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>					
			</form>
			<input type="hidden" name="token" id="token" value="<?php echo base64_encode('0')?>" />
		</div>
	</div>
</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>