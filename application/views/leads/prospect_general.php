<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
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
			<script>
				function dataValidate(){
					var $container = $("#scrap_patent_data");
					hst = $container.data('handsontable');
					jQuery("#generalPatentData").val(JSON.stringify(hst.getData()));
					return true;
				}
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
			<?php echo form_open('leads/prospect_general',array('id' => 'formProspectGeneral', 'class'=>'form-horizontal form-flat','role'=>'form','onsubmit'=>'return dataValidate()', 'style'=>'margin-bottom: 0;'));?>
			<div class="row row-width">
				<div class="col-xs-12">
					<div class="form-horizontal form-flat">
						<div class="row">
					  		<div class="col-xs-4">
								<div class="form-group input-string-group">
									<label for="generalTechnologies" class="control-label"><strong>Name of Lead:</strong></label>
									<?php echo form_input(array('name'=>'general[lead_name]','required'=>"required",'id'=>'generallead_name','placeholder'=>'','class'=>'form-control',"tabindex"=>"11"));?>
								</div>
					  		</div>
							<div class="col-xs-8 text-right">
								<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">
									<input type="checkbox" value="1" name="general[complete]" tabindex="12" /> Lead is complete and ready to be forwarded for review
								</label>
		                        <input type="hidden" name="general[patent_data]" value="" id="generalPatentData"/>
								<input type="hidden" name="general[id]" value="0" id="generalId"/>
								<input type="hidden" name="other[id]" value="0" id="commentID"/>
							  	<button type="submit" class="btn btn-primary pull-right btn-mwidth" tabindex="13">Save</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-width" style="width: 85px;">
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
			
				<div class="row">
					<div class="col-xs-4">
						<div class="form-group input-string-group bigmr">
							<label for="generalOwner" class="control-label">Patent Owner:</label>
							<?php echo form_input(array('name'=>'general[plantiffs_name]','id'=>'generalOwner','placeholder'=>'','class'=>'form-control',"tabindex"=>"1"));?>
						</div>
						<div class="row mrg10T">
							<!-- <label for="generalProspectsName" class="col-sm-2 control-label">Address:</label> -->
							<div class="col-sm-12" style="padding-right: 27px;">
								<?php echo form_textarea(array('name'=>'general[address]','id'=>'generalAddress','placeholder'=>'Address','class'=>'form-control','rows'=>4,'cols'=>29,'style'=>'height:53px !important;',"tabindex"=>"4"));?>
							</div>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="form-group input-string-group bigmr">
							<label for="generalProspects" class="control-label">Relates To:</label>
							<?php echo form_input(array('name'=>'general[relates_to]','id'=>'generalRelatesTo','placeholder'=>'','class'=>'form-control',"tabindex"=>"2"));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label for="generalExpectedPrice" class="control-label">Person Name1:</label>
							<?php echo form_input(array('name'=>'general[person_name_1]','id'=>'generalPersonName1','placeholder'=>'','class'=>'form-control',"tabindex"=>"5"));?>
						</div>
						<div class="form-group input-string-group bigmr">
							<label for="generalTechnologies" class="control-label">Person Title1:</label>
							<?php echo form_input(array('name'=>'general[person_title_1]','id'=>'generalPersonTitle1','placeholder'=>'','class'=>'form-control',"tabindex"=>"7"));?>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="form-group input-string-group nomr">
							<label for="generalProspects" class="control-label">Number of Patents:</label>
							<?php echo form_input(array('name'=>'general[no_of_prospects]','id'=>'generalProspects','placeholder'=>'','class'=>'form-control',"tabindex"=>"3"));?>
						</div>
						<div class="form-group input-string-group nomr">
							<label for="generalExpectedPrice" class="control-label">Person Name2:</label>
							<?php echo form_input(array('name'=>'general[person_name_2]','id'=>'generalPersonName2','placeholder'=>'','class'=>'form-control',"tabindex"=>"6"));?>
						</div>
						<div class="form-group input-string-group nomr">
							<label for="generalTechnologies" class="control-label">Person Title2:</label>
							<?php echo form_input(array('name'=>'general[person_title_2]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control',"tabindex"=>"8"));?>
						</div>
						<!-- <div class="form-group">
							<label for="generalExpectedPrice" class="control-label">Person Name3:</label>
							<?php echo form_input(array('name'=>'general[person_name_3]','id'=>'generalPersonName3','placeholder'=>'','class'=>'form-control input-string'));?>
						</div>
						<div class="form-group">
							<label for="generalTechnologies" class="control-label">Person Title3:</label>
							<?php echo form_input(array('name'=>'general[person_title_3]','id'=>'generalPersonTitle2','placeholder'=>'','class'=>'form-control input-string'));?>
						</div> -->
					</div>
				</div>
				
				<div class="row mrg10T">
					<div class="col-xs-10" style="width:90%;">
						<div class="row">
							<!-- <label class="col-sm-2 control-label" for="litigationLinkToPacer">Comment</label> -->
							<div class="col-sm-12">
								<?php echo form_textarea(array('name'=>'general[comment]','id'=>'generalComment','placeholder'=>'Notes',"required"=>"required",'class'=>'form-control','rows'=>4,'cols'=>29,"tabindex"=>"9"));?>	
							</div>
						</div>
					</div>
					<div class="col-xs-2" style="width:10%;">
						<select  name="general[attractive]" class="form-control" required="required" tabindex="10">
							<option value="">Attractiveness</option>
							<option value="High" >High</option>
							<option value="Medium">Medium</option>
							<option value="Low">Low</option>
							<option value="Disapproved">Disapproved</option>
						</select>
					</div>
				</div>
				<div class="row mrg10T">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="generalTechnologies" class="control-label">
                    			<strong></strong>
                    		</label>
                        </div>
                        <a  class="btn-default btn btn-mwidth" onclick="spreadsheet_box();">Create Spreadsheet</a>					
                    </div>
					<div class="col-xs-6">
						<div class="row mrg10T">
							<div class="col-xs-6">
								<div class="form-group input-string-group select-string-group">
									<label for="generalTechnologies" class="control-label" style="margin-top:2px;">
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
								<div class="form-group input-string-group select-string-group">
									<label for="generalTechnologies" class="control-label" style="margin-top:2px;">
										<strong>Select Worksheet:</strong>
									</label>
									<select name="general[worksheet_id]" id="generalWorksheetId" class="form-control" onchange="findWorksheetUrl(jQuery(this))"></select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-4 text-right mrg10T" style="margin-top:7px;">
						<button type="button" class="btn btn-default pull-right btn-mwidth" onclick="findPatentFromSheet()" tabindex="13">Import / Update Data</button>
						<span id="loadingLabel"></span>
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
										var $container = jQuery("#scrap_patent_data");
										$container.handsontable({						
											startRows: 1,
											data:jQuery.parseJSON(data),
											startCols: 9,		
											colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
											manualColumnResize: false,
											manualRowResize: false,
											minSpareRows: 1,
											columnSorting: true,
											persistentState: false,
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
						jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Create</a></li><li class='active'>Proactive General</li>");
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({						
							startRows: 1,
							startCols: 9,								
							colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
							manualColumnResize: false,
							manualRowResize: false,
							minSpareRows: 1,
							columnSorting: true,
							persistentState: false,
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
							//cellProperties.readOnly = true;
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
                    </script>
                    <!--  Clear Button Patent Data -->
                    <div style="clear:both;" class="mrg15T clearfix">
    					<!-- <button class='btn btn-danger' type='button' onclick="refreshHST()">Clear Table</button> -->
    					<a onclick="refreshHST()" style="text-decoration:none;" class="link-blue pull-right" href="#">
		            		<i style="font-size:16px;" class="glyph-icon icon-trash-o"></i>
		            		Clear Table
		            	</a>
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
				
                    <script>
					function refreshHST(){
						jQuery("#scrap_patent_data").handsontable("destroy"); 
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({						
							startRows: 1,
							startCols: 9,		
							colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
							manualColumnResize: false,
							manualRowResize: false,
							minSpareRows: 1,
							columnSorting: true,
							persistentState: false,
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
					_senddt = '<?php echo $this->session->userdata['id'];?>';
					___data = '';

					function record(level){
						jQuery("#formProspectGeneral").get(0).reset();
						jQuery.ajax({

							type:'POST',

							url:'<?php echo $Layout->baseUrl?>leads/litigation_record_incomplete',

							data:{token:jQuery("#token").val(),level:level,type:'General',complete:0},

							cache:false,

							success:function(response){				

								response = jQuery.parseJSON(response);

								___data = response;

								if(response.results.length>0){

									_data = response.results[0].litigation;	

									if(jQuery("#otherParentId").length>0){

										jQuery("#otherParentId").val(_data.id);

									}

									if(jQuery("#sendLitigation").length>0){

										jQuery("#sendLitigation").val(_data.id);

									}

									jQuery("#generalOwner").val(_data.plantiffs_name);
									jQuery("#generalRelatesTo").val(_data.relates_to);
									jQuery("#generalProspects").val(_data.no_of_prospects);
									jQuery("#generalAddress").val(_data.address);
									jQuery("#generalPersonName1").val(_data.person_name_1);
									jQuery("#generalPersonName2").val(_data.person_name_2);
									jQuery("#generalPersonTitle1").val(_data.person_title_1);
									jQuery("#generalPersonTitle2").val(_data.person_title_2);
									jQuery("#generalScrapperData").val(_data.scrapper_data);
									jQuery("#generallead_name").val(_data.lead_name);
                                    jQuery("#generalPatentData").val(_data.patent_data);
                                    jQuery("#litigationFileUrl").val(_data.file_url);
									snapGlobal= _data.file_url;
									jQuery("#generalId").val(_data.id);

									//jQuery("#token").val(response.token);					
									jQuery("#scrap_patent_data").handsontable("destroy"); 
									if(_data.patent_data!=""){
										var $container = jQuery("#scrap_patent_data");
										$container.handsontable({						
											startRows: 1,
											data:jQuery.parseJSON(_data.patent_data),
											startCols: 9,		
											colHeaders: ['Patent','*  *  *  *  *  Notes  *  *  *  *  ','Current Assignee', 'Application','Title','Original Assignee','Priority','File','Family'],
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

									_flag= 0;

									for(i=0;i<response.results[0].comment.length;i++){

										_comment = response.results[0].comment[i];

										if(_comment.user_id==_senddt){

											_flag = 1;

											_commentID = _comment.id;

											_commentText = _comment.comment;

											_commentAttractive = _comment.attractive;

											jQuery("#generalComment").val(_commentText);

											jQuery("#commentID").val(_commentID);

											jQuery("select[name='general[attractive]']").find('option').each(function(){ 

												if(jQuery(this).attr('value')==_commentAttractive){

													jQuery(this).attr('SELECTED','SELECTED');

												} else {

													jQuery(this).removeAttr('SELECTED');

												}

											});

										}
                                        
                                        
                                      /*  jQuery("#scrap_patent_data").handsontable("destroy"); 
                						var $container = jQuery("#scrap_patent_data");
                						$container.handsontable({						
                							startRows: 1,
                							startCols: 9,		
                							colHeaders: ['Patent', 'Application','Title','Original Assignee','Current Assignee','Priority','File','Family','*  *  *  *  *  Notes  *  *  *  *  '],
                							manualColumnResize: false,
                							manualRowResize: false,
                							minSpareRows: 1,
                							persistentState: false,
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
                						});*/
                                        
                                        
										if(_comment.user_id==_data.user_id){

											/*Creator Comment*/
												jQuery("#comment-list>tbody").html('<tr>'+
													'<td>'+_comment.name+'</td>'+
													'<td>'+_comment.comment+' </td>'+
													'<td>'+$.datepicker.formatDate('M, dd, yy', new Date(_comment.created))+'</td>'+
													'<td><span class="label alert label-success">'+_comment.attractive+'</span></td>	'+				
												'</tr>');

										}						

									}

									if(_flag==0){

										jQuery("#generalComment").val('');

										jQuery("#commentID").val(0);

										jQuery("select[name='general[attractive]']").find('option').each(function(){ 

											if(jQuery(this).attr('value')==''){

												jQuery(this).attr('SELECTED','SELECTED');

											} else {

												jQuery(this).removeAttr('SELECTED');

											}

										});

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

								}

							}

						});

					}
				</script>
				<style>
					.handsontable col {
					    width: auto !important;
					}
					.handsontable table.htCore{
						/*table-layout: auto !important;*/
					}
					.handsontable thead th .relative {
						padding-left: 0;
						padding-right: 0;
					}
					.ht_clone_top.handsontable {
						width: 100% !important;
					}
				</style>
				
				
				<!--<div class="mrg15T">
					<div class="example-box-wrapper">
						<iframe src="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=0" width="160px" height="600px"></iframe>
					</div>
				</div>-->
			<?php echo form_close()?>
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
			<form class="form-horizontal form-flat" accept-charset="utf-8" method="post" action="" id="form1">				
            	<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
				<h4 id="" class="modal-title">Create Spreadsheet</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group input-string-group nomr">
							<label class="control-label" for="spreadsheet">Spreadsheet:</label>
							<input type="text" required="required" name="spreadsheet" value="" id="spreadsheet" placeholder="" class="form-control is-big" style="width: 481px; text-align: left;">
						</div>
                    </div>
                </div>
			</div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-mwidth" type="button">Cancel</button>
                <button  class="btn btn-primary btn-mwidth" type="button" onclick="create_spreadsheet();">Save</button>
            </div>
			</form>				
        </div>
	</div>
</div>
<script>
/*
jQuery(function(){
    jQuery('input').on('blur', function(){
    jQuery("#form1").validate();
});
})*/
 function spreadsheet_box(){
	jQuery('#spreadsheet').val(jQuery("#generalOwner").val());
    jQuery('#create_spreadsheet').modal('show');
}
function create_spreadsheet(){
   var spreadsheet = jQuery('#spreadsheet').val();
   if(spreadsheet == "")
   {
     jQuery('#spreadsheet').css('border-color','#ff0000');
   }
   
   if(spreadsheet != '')
   {
        jQuery.ajax({
            type:'POST',
            url:'<?php echo $Layout->baseUrl?>leads/createLeadPatentSpreadSheet',
            data:{'n':spreadsheet},
            success:function(response){
                obj = JSON.parse(response);
                if(obj.error==0)
                {
                    window.open(obj.url,'_blank');//URL Link open in new tab 
                }
                else
                {
                    alert(obj.message);   
                }
                //jQuery('#create_spreadsheet').modal('hide');
            }
        });
   }
}
</script>