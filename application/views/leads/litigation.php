<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">

<style>
.form-horizontal .control-label{text-align:left}

.overflow-link {
    overflow: hidden;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 106px;
}
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

   
function findWorksheet(o,p){
	/*v = o.find('option:selected').val();
	t = o.find('option:selected').text();*/
	v = o.val();	
	if(v!="" && v!=undefined){
		jQuery.ajax({
			url:'<?php echo $Layout->baseUrl?>leads/findWorksheetList',
			type:'POST',
			data:{v:v},
			cache:false,
			success:function(data){
				_d = jQuery.parseJSON(data);
				if(_d!=undefined  && _d.length>0){
					jQuery("#litigationWorksheetId").empty().append("<option value=''>-- Select Worksheet --</option>");
					for(i=0;i<_d.length;i++){
						_selected="";
						if(typeof p == 'string' || typeof p =='number'){
							if(p==_d[i].id){
								_selected = "SELECTED='SELECTED'";
							}
						}
						jQuery("#litigationWorksheetId").append("<option "+_selected+" value='"+_d[i].id+"' data-href='"+_d[i].full+"'>"+_d[i].text+"</option>");
					}
					if(typeof p == 'string' || typeof p =='number'){
						jQuery("#litigationWorksheetId").attr("disabled","disabled");
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
function openPatentList(){
        var patent_url = jQuery("#litigationFileUrl").val();
		snapGlobal = patent_url;
        if(patent_url != '')
        {
            window.open(patent_url,'_blank');   
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
    _nws = "";
	function record(level){
		jQuery("#formLitigation").get(0).reset();
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>index.php/leads/litigation_record_incomplete',
			data:{token:jQuery("#token").val(),level:level,type:'Litigation'},
			cache:false,
			success:function(response){
				response = jQuery.parseJSON(response);
                _nws = response;
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
                    jQuery("#litigationLinkToRPX").val(_data.link_to_rpx);
					jQuery("#litigationleadName").val(_data.lead_name);
					jQuery("#litigationFileUrl").val(_data.file_url);
                    jQuery("#litigationId").val(_data.id);
                    jQuery("#taskType").val('Litigation');
                    leadGlobal = _data.id;
                    
                    
                    if(response.results[0].comment.length>0){
                        //alert(response.results[0].comment.length);
                        jQuery("#commentID").val(response.results[0].comment[0].id);
                        jQuery("#commentComment1").val(response.results[0].comment[0].comment1);
				    	jQuery("#commentComment2").val(response.results[0].comment[0].comment2);
					    jQuery("#commentComment3").val(response.results[0].comment[0].comment3);
                        jQuery("select[name='litigation[attractive]']").find("options").each(function(){
                           if(jQuery(this).attr('value')==response.results[0].comment[0].attractive){
                                jQuery(this).attr('SELECTED','SELECTED');
                           } 
                        });
                    }
                    
                    
					snapGlobal = _data.file_url;
					jQuery("#litigationScrapperData").val(_data.scrapper_data);
                    jQuery("#litgationPatentData").val(_data.patent_data);
					
                    
                    jQuery("#scrap_patent_data").find('tbody').empty(); 
					if(_data.patent_data!=""){
						_parsePatentData = jQuery.parseJSON(_data.patent_data);
						if(_parsePatentData.length>0){
							for(i=0;i<_parsePatentData.length;i++){
								_tr = jQuery("<tr/>");
								_columns = _parsePatentData[i];
								for(j=0;j<_columns.length;j++){
									_class="";
									if(j==1 || j==2){
										_class="clickakble";
									}
									if(_columns[j]!=null){
										jQuery(_tr).append("<td class='"+_class+"'>"+_columns[j]+"</td>");
									} else {
										jQuery(_tr).append("<td class='"+_class+"'></td>");
									}
									if(j==0){
										if(_columns[j]!=null && _columns[j]!=""){
											var escaped = _columns[j];
											td = "<a href='javascript://' class='btn' onclick='getGooglePatent(\""+jQuery.trim(escaped)+"\")'>"+escaped+"</a>";
											jQuery(_tr).find('td').eq(0).html(td);
										}	
									}
								}
								jQuery("#scrap_patent_data").find('tbody').append(_tr);
							}
							backSwitch();
						} else {
							jQuery("#scrap_patent_data").find('tbody').append("<tr><<td colspan='9'>No able to import data</td>/tr>");
						}
					}
					/*TIMELINE*/
					
					_createdDate = new Date(_data.create_date);
					/*_createdDate.setFullYear(_createdDate.getFullYear()+3);
					_endDate = new Date(_createdDate);*/
					$( "div.timeline-html-wrap:hidden" ).empty();
					_div = '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.create_date)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">'+_data.lead_name+'</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">Create task</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
					/*
					$( "div.timeline-html-wrap:hidden" ).append(_div);
					clearInterval(makeTimelineXMLInterval);
					makeTimelineXML();

					makeTimelineXMLInterval = setInterval(function() {
						// if($('#mainPanelBox').width() !== mainPanelBoxWidth) {
						if($(window).width() !== mainPanelBoxWidth) {
							makeTimelineXML();
							// mainPanelBoxWidth = $('#mainPanelBox').width();
							mainPanelBoxWidth = $(window).width();
						}
					}, 700);
					*/
					/*END TIME LINE*/
                     /* Show btn blinking dpend upon condition */
					 jQuery("#litigationSellerInfo").val(_data.seller_info);
					 jQuery("#litigationProposal_letter").val(_data.send_proposal_letter);
					 jQuery("#litigationCreate_patent_list").val(_data.create_patent_list);
                        if(_data.seller_info==1){							
							jQuery("#assign_task").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" href="javascript:void(0);" onclick="assign_task(2);"> Collect Seller\'s info</a><div id="loader_seller" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
						} else if(_data.seller_info==2){
							jQuery("#assign_task").html('<span class="date-style">' +moment( new Date(_data.seller_info_text)).format('YYYY-MM-D')+"</span> Seller Info Done");
							_div += '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.seller_info_text)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">Seller info done</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">Seller info done</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
							//jQuery("#assign_task").addClass("btn btn-mwidth");							
						} else if(_data.seller_info==0){
							jQuery("#assign_task").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="assign_task(1);">Collect Seller\'s info</a>');
						}
                        
                        if(_data.send_proposal_letter==1){
							jQuery("#request_for_proposal").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" href="javascript:void(0);" onclick="sendRequestForProposalLetter(2)"> Send Proposal Letter</a><div id="loader_prospect" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');							
						} else if(_data.send_proposal_letter==2){
							jQuery("#request_for_proposal").html('<span class="date-style">' +moment( new Date(_data.send_proposal_letter_text)).format('YYYY-MM-D')+"</span> Proposal letter created");
							_div += '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.send_proposal_letter_text)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">Proposal Letter Created</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">Proposal Letter Created</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
							
						} else if(_data.send_proposal_letter==0){
							jQuery("#request_for_proposal").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="sendRequestForProposalLetter(1)"> Send Proposal Letter</a><div id="loader_prospect" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
						}
						
                        if(_data.create_patent_list==1){
							jQuery("#create_patent_list").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth btn-blink" href="javascript:void(0);" onclick="spreadsheet_box();"> Create Patent List</a><div id="loader_patent" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
						} else if(_data.create_patent_list==2){
							jQuery("#create_patent_list").removeClass('btn').removeClass('btn-mwidth').html('<span class="date-style">' +moment( new Date(_data.create_patent_list_text)).format('YYYY-MM-D')+"</span> Patent Spreadsheet created");
							_div += '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.create_patent_list_text)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">Patent Spreadsheet created</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">Patent Spreadsheet created</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
							// jQuery("#create_patent_list").addClass("btn btn-mwidth");
						} else if(_data.create_patent_list==0){
							 jQuery("#create_patent_list").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="spreadsheet_box();"> Create Patent List</a><div id="loader_patent" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
						}
						if(_data.spreadsheet_id!=""){
							jQuery("#litigationSpreadsheetId").val(_data.spreadsheet_id);
						}
						if(_data.spreadsheet_id!="" && _data.worksheet_id==""){
							findWorksheet(jQuery("#litigationSpreadsheetId"));
						}
						
						if(_data.spreadsheet_id!="" && _data.worksheet_id!=""){
							findWorksheet(jQuery("#litigationSpreadsheetId"), _data.worksheet_id);						
						}
						
						if(_data.complete==2){
							jQuery("#forward_to_review").html('<span class="date-style">' +moment( new Date(_data.forward_to_review_text)).format('YYYY-MM-D')+"</span>  Review");
							jQuery("#forward_to_review").addClass("btn btn-mwidth");
							
							_div += '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.forward_to_review_text)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">Review</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">Review</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
						} else {
							jQuery("#forward_to_review").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" onclick="forward_to_review();" href="javascript:void(0);"> Forward to Review</a>');
						}
						if(_data.embed_code!=""){
							jQuery("#schedule1stCall").html(_data.embed_code);							
						} else if(_data.embed_code==""){
							jQuery("#schedule1stCall").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="scheduleCall()"> Schedule 1st Call</a>');
						}
						if(_data.nda_term_sheet==1){
							jQuery("#ndaTermSheet").html('<span class="date-style">' +moment( new Date(_data.nda_term_sheet_text)).format('YYYY-MM-D')+"</span> NDA and TermSheet created");
							//jQuery("#ndaTermSheet").addClass("btn btn-mwidth btn-blink");
						} else if(_data.nda_term_sheet==2){
							jQuery("#ndaTermSheet").html('<span class="date-style">' +moment( new Date(_data.nda_term_sheet_text)).format('YYYY-MM-D')+"</span> NDA and TermSheet created");
							_div += '<div class="timeline-event">'+
								'<div class="timeline-date">'+moment(new Date(_data.nda_term_sheet_text)).format("D.MM.YYYY")+'</div>'+
								'<div class="timeline-title">NDA and TermSheet created</div>'+
								'<div class="timeline-thumb"></div>'+
								'<div class="timeline-content">NDA and TermSheet created</div>'+
								'<div class="timeline-link"><a href="http://design.synpat.com">Read More</a></div>'+
							'</div>';
						} else if(_data.nda_term_sheet==0){
							jQuery("#ndaTermSheet").removeClass('btn').removeClass('btn-mwidth').html('<a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="createPartNDATermsheet()"> NDA + TermSheet</a><div id="loader_NDA" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>');
						}
                 /*   $( "div.timeline-html-wrap:hidden" ).append(_div);
					clearInterval(makeTimelineXMLInterval);
					makeTimelineXML();
/*
					makeTimelineXMLInterval = setInterval(function() {
						// if($('#mainPanelBox').width() !== mainPanelBoxWidth) {
						if($(window).width() !== mainPanelBoxWidth) {
							makeTimelineXML();
							// mainPanelBoxWidth = $('#mainPanelBox').width();
							mainPanelBoxWidth = $(window).width();
						}
					}, 700);*/
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
                        
                       
                        /* End Show btn blinking dpend upon condition */					
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
						if(_data.scrapper_data!=""){
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
						}else {
							jQuery("#datatable-hide-columns"+_data.id).find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							jQuery("#datatable-hide-columns"+_data.id+"1").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							jQuery("#datatable-hide-columns"+_data.id+"2").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							jQuery("#datatable-hide-columns"+_data.id+"3").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
							jQuery("#datatable-hide-columns"+_data.id+"4").find('tbody').empty().append('<tr> <td colspan="4">No record found!</td></tr>');
						}
					
					} else {
						
						jQuery("#show_data").html('<div class="col-sm-12 noPadding" style=\'overflow-y:scroll;overflow:x:none;height:400px;\'><div class="col-sm-6 noPadding" id="defendant"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div><div class="col-sm-6" id="court_docket"><img src="'+_data.court_docket_entries+'" style="width:490px;"/></div></div>');
					}
					
					_drive = response.drive;
					jQuery("#litigation_doc_list").empty();
					if(_drive.length>0){
						jQuery("#litigation_doc_list").append("<ul class='todo-box-1'></ul>");
						for(d=0;d<_drive.length;d++){
							jQuery("#litigation_doc_list>ul").append("<li><img src='"+_drive[d].iconLink+"'/> <a target='_BLANK' href='"+_drive[d].alternateLink+"'>"+_drive[d].title+"</a></li>");
						}
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

                             var parsedDate = $.datepicker.parseDate('yy-mm-dd', _dataTimeLine[i].create_date.split(' ')[0]);
                             timeLineTable += 	'<div class="tl-container">' +
	                             					'<p class="tl-content">'+_dataTimeLine[i].message+'</p>'+
	                             					'<div class="tl-footer clearfix">'+
														'<div class="tl-timeuser">'+
															$.datepicker.formatDate('M dd, yy', parsedDate) + '&nbsp;&nbsp;&nbsp;' + _dataTimeLine[i].name+
														'</div>'+
														'<img width="28" src="'+_userImage+'"/>'+
													'</div>' +
												'</div>' +
											'</div>'+
										'</div>'+
									'</div>'+
							'</div>';
                            //alert(timeLineTable);
                        }
                       
                        
                        jQuery(".timeline-box").html(timeLineTable);

                        // Timeline events
                        timelineItemBindEvents();
		
						// Bottom Tabs
						$('#tablesOtherData .nav-tabs li a').off('click').on('click', function() {
							$(this).tab('show');
							return false;
						});
						
						
						
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
				<div class="row row-width">
					<?php if((int)$this->session->userdata['type']==9):?>
					<div class="col-width text-center" style="width:25px;">
						<!-- <button type="button" class="btn btn-danger btn-block" onclick="cancelImport();">Clear</button> -->
						<a href="#" onclick="cancelImport()" class="link-blue" style="display: inline-block; margin-top:5px; text-decoration:none;">
		            		<i style="font-size:16px;" class="glyph-icon icon-trash-o"></i>
		            	</a>
					</div>
					<div class="col-sm-12">
						<input name="litigation1[import_url]" id="lititgationImportURL" class="form-control" placeholder="Paste URL"/>
					</div>
					<div class="col-width" style="width: 230px;">
						<div class="row">
							<div class="col-xs-12">
								<button type="button" id="btnImport" class="btn btn-default float-left btn-mwidth" onclick="importDataFromExternalUrl();">
									<div id="loader" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
									Import
								</button>
							</div>
							<div class="col-xs-4" style="display: none;">
								<a href="javascript://" id="cancelImport" class="btn btn-default float-left btn-block" style='display:none' onclick="cancelImport();">Cancel</a>
							</div>
						</div>
					</div> <?php endif;?>
					<div class="col-width pull-right" style="width: 85px;">
						<ul class="pager create-lead-pager clearfix">
							<li class="previous"><a href="javascript:void(0)" onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i></a></li>
							<li class="pager-text-wrapper">
								<div class="pager-text">
									0/<?php echo $incomplete_leads->leadCount;?>
								</div>
							</li>
							<li class="next"><a href="javascript:void(0)" onclick="record('next');" ><i class="glyph-icon icon-angle-right"></i></a></li>
						</ul>
					</div>
					<!-- <div class="col-xs-1">
						<div class="pager-text">
							0/<?php echo $incomplete_leads->leadCount;?>
						</div>
					</div> -->
				</div>
				<div class="row row-width mrg5T">
					<div class="col-xs-12">
						<div class="row">
							<div class="row row-width mrg10T">
								<div class="col-xs-4">
									<div class="form-group input-string-group">
										<label class="control-label" for="litigationLinkToPacer"><strong>Lead Name:</strong></label>
										<?php echo form_input(array('name'=>'litigation[lead_name]','required'=>'required','id'=>'litigationleadName','placeholder'=>'','class'=>'form-control input-string is-big'));?>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="row row-width">
										<div class="col-width" style="width:280px;">
											<div class="row row-width">
												<div class="col-width" style="width: 100px;">
													<div class="form-group input-string-group">
														<label for="marketProspects" class="control-label">Prospects:</label>
														<?php echo form_input(array('name'=>'litigation[no_of_prospects]','id'=>'litigationProspects','placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
													</div>
												</div>
												<div class="col-width" style="width: 172px;">
													<div class="form-group input-string-group">
														<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
														<?php echo form_input(array('name'=>'litigation[expected_price]','id'=>'litigationExpectedPrice','placeholder'=>'','class'=>'form-control', 'maxlength'=>'4'));?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="col-xs-2">
									<div class="form-group input-string-group">
										<label class="control-label" for="litigationNoOfPatent">Number of Patents:</label>
										<?php echo form_input(array('name'=>'litigation[no_of_patent]','id'=>'litigationNoOfPatent','placeholder'=>'','class'=>'form-control is-small'));?>
									</div>
								</div>
								<!--div class="col-width" style="width:154px;">&nbsp;</div-->
							</div>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<!-- col-xs-4 col-xs-8 -->
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseName">Case Name:</label>
									<?php echo form_input(array('name'=>'litigation[case_name]','id'=>'litigationCaseName','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseName">Litigation Stage:</label>
									<?php echo form_input(array('name'=>'litigation[litigation_stage]','id'=>'litigationLitigationStage','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseName">Market/Industry:</label>
									<?php echo form_input(array('name'=>'litigation[market_industry]','id'=>'litigationMarketIndustry','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseName">Case Type:</label>
									<?php echo form_input(array('name'=>'litigation[case_type]','id'=>'litigationCaseType','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseNumber">Case Number:</label>
									<?php echo form_input(array('name'=>'litigation[case_number]','id'=>'litigationCaseNumber','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationCaseName">Cause:</label>
									<?php echo form_input(array('name'=>'litigation[cause]','id'=>'litigationCause','placeholder'=>'','class'=>'form-control is-big'));?>
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<!-- col-xs-6 col-xs-6 -->								
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
								<div class="form-group input-string-group">
									<label class="control-label" for="litigationLinkToPacer">Link to RPX:</label>
									<?php echo form_input(array('name'=>'litigation[link_to_rpx]','id'=>'litigationLinkToRPX','placeholder'=>'','class'=>'form-control'));?>
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-xs-4" style="padding-right: 7px;">
								<div class="row">
									<div class="col-xs-6">
									<?php echo form_textarea(array('name'=>'litigation[lead_attorney]','id'=>'litigationLeadAttorney','placeholder'=>'Plaintiff\'s Lead Attorney','class'=>'form-control','rows'=>3,'cols'=>29,'style'=>'height:189px !important;'));?>
									</div>
									<div class="col-xs-6">
										<div id="litigation_doc_list" class="panel google-box-list" style='height:189px;overflow-y:scroll;overflow-x:hidden;'>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-width mrg5T">
							<!-- <div class="col-xs-10" style="width:90%;">
								<div class="form-group" style='width:100%;'>
									<label class="sr-only" for="litigationLinkToPacer">Comment</label>							
									<?php //echo form_textarea(array('name'=>'litigation[comment]','id'=>'litigationComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>29));?>	
								</div>
							</div> -->
							<div class="col-sm-4">
								<label class="control-label" for="marketProspectsName">Are there >10 potential licensees? Who?</label>
								<?php echo form_textarea(array('name'=>'comment[comment1]','id'=>'commentComment1','class'=>'form-control','rows'=>4,'cols'=>15));?>	
							</div>
							<div class="col-sm-4">
								<label class="control-label" for="marketProspectsName">Will licensees want to pay the expected fee? Why?</label>
								<?php echo form_textarea(array('name'=>'comment[comment2]','id'=>'commentComment2','class'=>'form-control','rows'=>4,'cols'=>15));?>	
							</div>
							<div class="col-sm-4">
								<label class="control-label" for="marketProspectsName">Seller's concerns + Your general observations</label>
								<?php echo form_textarea(array('name'=>'comment[comment3]','id'=>'commentComment3','class'=>'form-control','rows'=>4,'cols'=>15));?>	
							</div>
						</div>
					</div>
					<div class="col-width" style="width:154px;">
						<div style="margin-right:-2px;">
							<div class="clearfix">
								<input type='hidden' name="litigation[scrapper_data]" id="litigationScrapperData" class='form-control'/>
								<input type="hidden" name="litigation[patent_data]" value="" id="litgationPatentData"/>
								<input type='hidden' name="litigation[id]" id="litigationId" value="0" class='form-control'/>
								<input type='hidden' name="other[id]" id="commentID" value="0" class='form-control'/>		                        
		                        <input type="hidden" name="litigation[patent_data]" value="" id="litigationPatentData"/>
		                        <input type="hidden" name="litigation[seller_info]" value="1" id="litigationSellerInfo"/>
		                        <input type="hidden" name="litigation[send_proposal_letter]" value="" id="litigationProposal_letter"/>
		                        <input type="hidden" name="litigation[create_patent_list]" value="" id="litigationCreate_patent_list"/>
						  		<button type="submit" class="btn btn-primary btn-mwidth float-right">Save</button>
						  	</div>
							<div class="clearfix mrg5T">
								<div class="todo-list-custom fright">
				    				<div class="row">
				    					<div class="col-sm-12" id="assign_task"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="assign_task(1);">Collect Seller's info</a>
                                        <div id="loader_seller" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
                                        </div>
				    				</div>
				    				<div class="row mrg5T">
				    					<div class="col-sm-12" id="request_for_proposal"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="sendRequestForProposalLetter(1)">Send Proposal Letter</a>
                                        <div id="loader_prospect" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
                                        </div>
				    				</div>
				    				<div class="row mrg5T">
				    					<div class="col-sm-12" id="create_patent_list"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="spreadsheet_box();">Create Patent List</a><div id="loader_patent" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
                                        </div>
				    				</div>
				    				<div class="row mrg5T">
				    					<div class="col-sm-12" id="forward_to_review">
                                            <a class="btn btn-default btn-mwidth" onclick="forward_to_review();" href="javascript:void(0);">Forward to Review</a>
                                            <div id="loader_review" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
                                        </div>
				    				</div>
				    				<div class="row mrg5T">
				    					<div class="col-sm-12" id="schedule1stCall"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="scheduleCall()">Schedule 1st Call</a></div>
				    				</div>
				    				<div class="row mrg5T">
										<div class="loading-spinner" id="spinner-loader-nda-timesheet" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div>
				    					<div class="col-sm-12" id="ndaTermSheet"><a class="btn btn-default btn-mwidth" href="javascript:void(0);" onclick="createPartNDATermsheet()">NDA + TermSheet</a><div id="loader_NDA" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div></div>
				    				</div>
				    			</div>
				    		</div>
							<div class="mrg5T" style="padding-left:2px;">
								<select  class="form-control" name='litigation[attractive]'>
									<option value="">Attractiveness</option>
									<option value="High" >High</option>
									<option value="Medium">Medium</option>
									<option value="Low">Low</option>
									<option value="Disapproved">Disapproved</option>
								</select>
							</div>
						</div>
					</div>
				</div>				
				<div class="row row-width mrg10T">
					<!--div class="col-xs-5">
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">
								<strong>Patent File Url:</strong>
							</label>
							<input type="textbox" class="form-control input-string is-big" name="litigation[file_url]" id="litigationFileUrl" value=""/>
						</div>						
					</div-->
                    <!-- <div class="col-xs-2">
                        <div class="form-group">
                            <label for="generalTechnologies" class="control-label pull-left">
                    			<strong></strong>
                    		</label>
                        </div>
                        <a  class="btn btn-default btn-mwidth" onclick="spreadsheet_box();">Create Spreadsheet</a>					
					</div> -->
                    <div class="col-xs-12">
                    	<div class="row mrg10T">
	                    	<!--<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
	                    			<label for="generalTechnologies" class="control-label" style="margin-top:2px;">
	                    				Select Spreadsheet:
	                    			</label>
	                    			<select name="litigation[spreadsheet_id]" id="litigationSpreadsheetId" class="form-control" onchange="findWorksheet(jQuery(this));">
	                    				<option value="">-- Select Spreadsheet --</option>
	                    				<?php 
	                    					foreach($listOfFiles as $files){
	                    				?>
	                    					<option value="<?php echo $files->id?>"><?php echo $files->title?></option>
	                    				<?php
	                    					}
	                    				?>
	                    			</select>
	                    			
	                    		</div>
	                    	</div>-->
	                    	<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
	                    			<label for="generalTechnologies" class="control-label" style="margin-top:2px;">
	                    				Select Worksheet:
	                    			</label>
	                    			<select name="litigation[worksheet_id]" id="litigationWorksheetId" class="form-control" onchange="findWorksheetUrl(jQuery(this))"></select>
									<input type="hidden" class="form-control input-string"  name="litigation[file_url]" id="litigationFileUrl" value=""/>
									<input type="hidden" class="form-control input-string"  name="litigation[spreadsheet_id]" id="litigationSpreadsheetId" value=""/>
	                    		</div>
	                    	</div>
                    	</div>
                    </div>
					<div class="col-width" style="width:382px;">
						<div style="clear:both; margin-top:17px;" class="clearfix">
			            	<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-left:15px;' onclick="refreshHST()">
			            		<i class="glyph-icon icon-trash" style="font-size:16px;"></i>
			            		Clear Table
			            	</a>
			            	<a href="#" id="loadingLink" class='link-blue pull-right' style='text-decoration:none;' onclick="findPatentFromSheet()">
			            		<i class="glyph-icon icon-recycle" style="font-size:16px;"></i>
			            		Import / Update Data
			            	</a>
			            	&nbsp;
			            	<div class="pull-right" id="loadingLabel" style="position: relative; width: 34px;"></div>
			            	<a href="#" class='link-blue pull-right' style='text-decoration:none; margin-right:15px;' onclick="openPatentList()">
			            		<i class="glyph-icon icon-folder-open" style="font-size:16px;"></i>
			            		Open Patent List
			            	</a>
			            </div>
					</div>
				</div>	
				<div class="mrg5T" style='margin-top:5px;width:100%;padding:0;' id="patent_data">
					<div class="example-box-wrapper">
						<table class="table table-bordered" id="scrap_patent_data">
							<thead>
								<tr>
									<th>Patent</th>
									<th>Notes</th>
									<th>Current Assignee</th>
									<th>Application</th>
									<th>Title</th>
									<th>Original Assignee</th>
									<th>Priority</th>
									<th>File</th>  
									<th>Family</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				<script>
					function backSwitch(){
						jQuery("#scrap_patent_data").find('.clickakble').dblclick(function(){
							switchToEdit(jQuery(this));
						});
					}
					_editable =false;
					
					function switchToEdit(object)
					{
						_editable=false;
						switchBack();	
						/*alert(object.attr('id'));*/
						if(object.attr('id')!='Container_Edittable' && jQuery("*:focus").attr('id')!="Container_Edittable"){
							_editable=true;
							_html = object.html();
							object.html("<input type='text' class='form-control' id='Container_Edittable' style='width:400px;'/>");
							object.find('input').val(_html).focus().click(function(){
								_editable=true;
							});
							backClick();
						}						
					}
					function backClick(){
						jQuery("#scrap_patent_data").click(function(event){
							if(jQuery(this).attr('id')!="Container_Edittable"){
								_editable=false;
								switchBack();
							} else {
								_editable=true;
							}
						});
					}
					
					function switchBack()
					{
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
					_mainData = "";
					function findPatentFromSheet(){
						if(jQuery("#litigationFileUrl").val()!=""){
							snapGlobal= jQuery("#litigationFileUrl").val();
							jQuery('#loadingLink').addClass('overflow-link');
							jQuery("#loadingLabel").html('<i style="color: rgb(34, 34, 34); position: static;" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A"></i>');
							jQuery.ajax({
								url:'<?php echo $Layout->baseUrl?>leads/googleSpreadSheet',
								type:'POST',
								data:{file_url:jQuery("#litigationFileUrl").val()},
								cache:false,
								success:function(data){
									if(data!=""){
										jQuery('#loadingLink').removeClass('overflow-link');
										jQuery("#loadingLabel").html('');
										jQuery("#scrap_patent_data").find('tbody').empty();
										_data = jQuery.parseJSON(data);
										
										if(_data.length>0){
											for(i=0;i<_data.length;i++){
												_tr = jQuery("<tr/>");
												_columns = _data[i];
												for(j=0;j<_columns.length;j++){
													_class="";
													if(j==1 || j==2){
														_class="clickakble";
													}
													if(_columns[j]!=null){
														jQuery(_tr).append("<td class='"+_class+"'>"+_columns[j]+"</td>");
													} else {
														jQuery(_tr).append("<td class='"+_class+"'></td>");
													}
													if(j==0){
														if(_columns[j]!=null && _columns[j]!=""){
															var escaped = _columns[j];
															td = "<a href='javascript://' class='btn' onclick='getGooglePatent(\""+jQuery.trim(escaped)+"\")'>"+escaped+"</a>";
															jQuery(_tr).find('td').eq(0).html(td);
														}	
													}
												}
												jQuery("#scrap_patent_data").find('tbody').append(_tr);
											}
											backSwitch();
										} else {
											jQuery("#scrap_patent_data").find('tbody').append("<tr><<td colspan='9'>No able to import data</td>/tr>");
										}										
									} else {
										jQuery('#loadingLink').removeClass('overflow-link');
										// jQuery("#loadingLabel").html('Error while importing');
										alert('Error while importing');
									}
								}
							});
						}
					}
                    jQuery(document).ready(function(){						
					});	
					
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
						_editable=false;
						switchBack();
						mainArray = [];
						if($("#scrap_patent_data").find("tbody").find("tr").length>0){
							$("#scrap_patent_data").find("tbody").find("tr").each(function(){
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
						jQuery("#litgationPatentData").val(JSON.stringify(mainArray));
						return true;
					}
                    function refreshHST(){
						var r= confirm("Are you sure?");
						if(r==true){
							jQuery("#scrap_patent_data").find('tbody').empty();							
						}
					}
                    
				</script>
                
               			
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

<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="create_spreadsheet">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">Create Spreadsheet</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group input-string-group nomr">
							<label for="spreadsheet" class="control-label">Spreadsheet:</label>
							<input type="text" class="form-control is-big" placeholder="" id="spreadsheet" value="" name="spreadsheet"required="required">
						</div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>
				<div class="loading-spinner" id="loading_spinner_spreadsheet" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div>
                <button  class="btn btn-primary btn-mwidth" type="button" onclick="create_spreadsheet();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>

<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="create_scheduleCall">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">Embed Schedule code</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group nomr">
							<label for="spreadsheet" class="control-label">Embed Code:</label>
							<textarea type="text" class="form-control" placeholder="" id="embed_code" value="" name="embed_code" required="required"></textarea>
						</div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>
				<div class="loading-spinner" id="loading_spinner_schedulecall" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div>
                <button  class="btn btn-primary btn-mwidth" type="button" onclick="save_embedCode();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>
<div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade in" id="nda_termsheet">   
    <!--div class="modal-backdrop fade in" style="height: 521px;"></div-->
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">NDA +  Termsheet</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group input-string-group nomr" id="nda_termsheet_html">
							
						</div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>				
            </div>
			</form>				
        </div>
	</div>
</div>
<script>
	 function spreadsheet_box(){
	    $("#loader_patent").show();
		jQuery('#spreadsheet').val(jQuery('#litigationleadName').val());
	    jQuery('#create_spreadsheet').modal('show');
	    
	    $("#loader_patent").hide();
	}
	function scheduleCall(){
		window.open("https://www.google.com/calendar/render?tab=mc#h","_blank");
		jQuery('#create_scheduleCall').modal('show');
	}
	function save_embedCode(){
		var embed_code = jQuery('#embed_code').val();
	    if(embed_code == ''){
	        jQuery('#embed_code').css('border-color','#ff0000');
	    }
		if(jQuery('#litigationId').val()!=""){
			jQuery("#loading_spinner_schedulecall").css('display','block');
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>index.php/leads/embedScheduleCall',
				data:{'embed_code':embed_code,lead_id:jQuery('#litigationId').val()},
				success:function(response){
					jQuery("#loading_spinner_spreadsheet").css('display','none');
					if(response==1){    
						jQuery("#schedule1stCall").html(jQuery('#embed_code').val());
						jQuery('#create_scheduleCall').modal('hide');
					}  else {
						alert("Please try after sometime.");   
					}              
				}
			});
		} else {
			alert("Please try after sometime.");   
		}
		
	}
	function create_spreadsheet(){
	    var spreadsheet = jQuery('#spreadsheet').val();
	    if(spreadsheet == ''){
	        jQuery('#spreadsheet').css('border-color','#ff0000');
	    }
		jQuery("#loading_spinner_spreadsheet").css('display','block');
	    if(spreadsheet != ''){
	         $("#loader_patent").show();
	        jQuery.ajax({
	            type:'POST',
	            url:'<?php echo $Layout->baseUrl?>index.php/leads/createLeadPatentSpreadSheet',
	            data:{'n':spreadsheet,lead_id:jQuery('#litigationId').val(),ds:1},
	            success:function(response){
	                $("#loader_patent").hide();
	                jQuery("#create_patent_list").find('a').addClass('btn-blink');
	                obj = JSON.parse(response);
					jQuery("#loading_spinner_spreadsheet").css('display','none');
	                if(obj.url != '' && obj.error==0){         
						jQuery("#litigationCreate_patent_list").val(2);
	                    jQuery("#create_patent_list a").hide();
	                    jQuery("#create_patent_list").html('<span class="date-style">'+moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> Patent SpreadSheet created").removeClass('btn').removeClass('btn-mwidth');
	                    // jQuery("#create_patent_list").addClass("btn btn-mwidth");
						jQuery("#litigationSpreadsheetId").val(obj.spread_sheet_id);
						findWorksheet(jQuery("#litigationSpreadsheetId"));
						$('#create_spreadsheet').modal('hide') ;                  
	                    window.open(obj.url,'_blank');
	                }  else {
	                    alert(obj.message);   
	                }              
	            }
	        });
	    }
	}

	function assign_task(type){
	        var lead_id = jQuery('#litigationId').val();
	        var seller_info = jQuery("#litigationSellerInfo").val(type);
	        response = "";
	        if(type == 1)
	        {
	            jQuery("#assign_task").addClass("btn-blink");
	        } 
	        else if(type==2)
	        if(lead_id > 0)
	        { 
	            $("#loader_seller").show();
	            jQuery.ajax({
	            type:'POST',
	            url: '<?php echo $Layout->baseUrl; ?>leads/assign_lead',
	            data:{'lead_id':lead_id,'base_url':'<?php echo $Layout->baseUrl; ?>leads/market',ds:1},
	            success:function(response){
	                $("#loader_seller").hide();
	                  obj = jQuery.parseJSON(response);
	                    jQuery("#assign_task a").hide();
	                    jQuery("#assign_task").html('<span class="date-style">' +moment(new Date(obj.date_created)).format('MM-D-YY')+"</span> Seller Info Done");
	                    jQuery("#assign_task").removeClass("btn-blink");
	                    jQuery("#assign_task").addClass("btn btn-mwidth");
	                }
	            });
	        }
	    }
	    
	    function sendRequestForProposalLetter(type){
	         /*   
	        if(jQuery("#litigationProposal_letter").val() == 0){
	            type = 1;
	        } else {
	            type = 2;
	        }*/
	        var lead_id = jQuery('#litigationId').val();        
	    	jQuery("#spinner-loader").css('display','inline-block');    	
	    	_names = jQuery("#litigationleadName").val();
	        jQuery("#litigationProposal_letter").val(type);
	        if(lead_id > 0) {
	            if(type == 1){
	                $("#loader_prospect").show();
					jQuery.ajax({        
	        		type:'POST',        
	        		url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',        
	        		data:{name:_names,type:'Litigation',"lead_id":lead_id,ds:1,send_proposal_letter:1},        
	        		cache:false,        
	        		success:function(res){  
	                        $("#loader_prospect").hide();
	                        jQuery("#request_for_proposal").addClass("btn-blink");
	            			jQuery("#spinner-loader").css('display','none');            
	            			_res = jQuery.parseJSON(res);
	                       // alert(_res);
	            			if(_res.link!=""){                            
	                            jQuery("#request_for_proposal").addClass("btn-blink").attr('onclick','sendRequestForProposalLetter(2)');
	            				window.open(_res.link,"_blank","toolbar=yes, scrollbars=yes, resizable=yes,width=600, height=500")
	            			} else {
	            				alert('Please try after some time!');
	            			}
	            		}
	        	   });
	            } else if(type == 2) {
	                $("#loader_prospect").show();
					jQuery.ajax({        
	        		type:'POST',        
	        		url:'<?php echo $Layout->baseUrl?>index.php/leads/updateLeadData',        
	        		data:{"lead_id":lead_id,send_proposal_letter:2},        
	        		cache:false,        
	        		success:function(res){  
	        		        $("#loader_prospect").hide();
	            			jQuery("#spinner-loader").css('display','none');            
							_res = jQuery.parseJSON(res);
							if(_res.error==0){
								jQuery("#request_for_proposal").removeClass("btn-blink");
								jQuery("#request_for_proposal").html('<span class="date-style">'+_res.date_created+"</span> Proposal letter created");
								 jQuery("#request_for_proposal").addClass("btn btn-mwidth");
							} else {
								alert("Please try after sometime.");
							}
	            		}
	        	   });
	            }
	        } else {
				alert("Please try after sometime.");
			}
	        
	    }
	    
	    function forward_to_review(){
	        var lead_id = jQuery('#litigationId').val();
	        if(lead_id > 0)
	        {
	            $("#loader_review").show();
	            jQuery.ajax({
	                type:'POST',
	                url: '<?php echo $Layout->baseUrl; ?>leads/forward_to_review',
	                data:{'lead_id':lead_id},
	                success:function(response){
	                    $("#loader_review").hide();
	                    res = jQuery.parseJSON(response);
	                  //  
	                    jQuery("#forward_to_review").html('<span class="date-style">'+moment( new Date(res.date_created)).format('MM-D-YY')+"</span> Review");
	                    jQuery("#forward_to_review a").hide();
	                    jQuery("#forward_to_review").addClass("btn btn-mwidth");
	                }
	            });
	        }
	    }
	    
	    function openPatentList(){
	        var patent_url = jQuery("#litigationFileUrl").val();
	        if(patent_url != '')
	        {
	            window.open(patent_url,'_blank');   
	        }
	    }
		
		function createPartNDATermsheet(){
			var lead_id = jQuery('#litigationId').val();
	        if(lead_id > 0)
	        {
				jQuery("#spinner-loader-nda-timesheet").css('display','block');  
	            $("#loader_NDA").show();     
	            jQuery.ajax({
	                type:'POST',
	                url: '<?php echo $Layout->baseUrl; ?>index.php/leads/createNDATermsheet',
	                data:{'v':lead_id},
	                success:function(response){
						jQuery("#spinner-loader-nda-timesheet").css('display','none');     
						_response = jQuery.parseJSON(response);
	                    $("#loader_NDA").hide();
						if(_response.error=="0"){
							jQuery("#nda_termsheet_html").html("<div class='col-lg-12'><label>NDA: </label><a href='"+_response.nda+"' target='_BLANK'>"+_response.nda+"</a></div><div class='col-lg-12'><label>TermSheet: </label><a href='"+_response.term_sheet+"' target='_BLANK'>"+_response.term_sheet+"</a></div>");
							jQuery("#nda_termsheet").modal("show");
	                        jQuery("#ndaTermSheet").html('<span class="date-style">'+moment( new Date(_response.date_created)).format('MM-D-YY')+"</span> NDA and TermSheet created");
						} else {
							alert("Please try after sometime.");
						}
	                }
	            });
	        }
		}

	$(function() {
		$('#tablesOtherData .nav-tabs li a').off('click').on('click', function() {
			$(this).tab('show');
			return false;
		});
	});

</script>