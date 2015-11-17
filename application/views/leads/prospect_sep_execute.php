<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>Proactive SEP</li>");
});
</script>

<style>
	.dashboard-box {
		overflow-y: scroll !important;
	}
</style>

<div class="panel dashboard-box">

    <div class="panel-body">

		<div class="example-box-wrapper">

			<div class="table-responsive">

                <div class="row row-width">

					<?php 

						$previous = "disabled='disabled'";

						if($current_page>1){

							$previous ="";

						}

						$next = "disabled='disabled'";

						if($total_rows>1 && ($current_page+1)<=$no_of_pages){

							$next ="";

						}

					?>
					
					<div class="col-xs-12"></div>
                    <div class="col-width" style="width: 85px;">
						<ul class="pager create-lead-pager clearfix">
	                        <li class="previous"><a href="javascript:void(0)" <?php echo $previous;?> onclick="record('prev');" ><i class="glyph-icon icon-angle-left"></i></a></li>
                            <li class="pager-text-wrapper">
                                <div class="pager-text">
                                    0/<?php echo $incomplete_leads->leadCount;?>
                                </div>
                            </li>
	                        <li class="next"><a href="javascript:void(0)" <?php echo $next;?> onclick="record('next');" ><i class="glyph-icon icon-angle-right"></i></a></li>
	                    </ul>
					</div>
					<!-- <div class="col-xs-1">
						<div class="pager-text">
							<?php if(count($results)>0):?>
								1/<?php echo $incomplete_leads->leadCount;?>
								<?php else:?>
								0/0
								<?php endif;?>
						</div>
					</div> -->

				</div>

				<?php 

					if(count($results)>0){												

				?>
				<script>
					snapGlobal = '<?php echo $results[0]['litigation']->file_url;?>';
				</script>
				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />

				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>

				<!-- <table class="table" id='record-list'>

					<tbody>

						<?php foreach($results as $data):?>

						<tr>

							<td><div class="lbl">Owner: </div> <?php echo $data['litigation']->plantiffs_name;?></td>

							<td><div class="lbl">No of Patents: </div> <?php echo $data['litigation']->no_of_prospects;?></td>

							<td><div class="lbl">Relates To: </div> <?php echo $data['litigation']->relates_to;?></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name1: </div> <?php echo $data['litigation']->person_name_1;?></td>

							<td><div class="lbl">Person Title1: </div> <?php echo $data['litigation']->person_title_1;?></td>

							<td></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name2: </div> <?php echo $data['litigation']->person_name_2;?></td>

							<td><div class="lbl">Person Title2: </div> <?php echo $data['litigation']->person_title_2;?></td>

							<td></td>

						</tr>

						<tr>

							<td><div class="lbl">Person Name3: </div> <?php echo $data['litigation']->person_name_3;?></td>

							<td><div class="lbl">Person Title3: </div> <?php echo $data['litigation']->person_title_3;?></td>

							<td></td>

						</tr>

						<tr>

							<td colspan="2"><div class="lbl">Address: </div><?php echo $data['litigation']->address;?></td>

							<td><div class="lbl">Portfolio Number: </div><?php echo $data['litigation']->portfolio_number;?></td>

						</tr>

						<?php endforeach;?>

					</tbody>

				</table> -->
				
				<div id="topPart" class="form-horizontal form-flat">
					<?php foreach($results as $data):?>
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group input-string-group bigmr">
								<label class="control-label">Patent Owner:</label>
								<input type="text" class="form-control" id="patentOwner" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
							</div>
							<div class="form-group">
								<label class="control-label">Address:</label>
								<div><?php echo $data['litigation']->address;?></div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group input-string-group bigmr">
								<label class="control-label">Relates To:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->relates_to;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group bigmr">
								<label class="control-label">Person Name1:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_name_1;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group bigmr">
								<label class="control-label">Person Title1:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_title_1;?>" readonly="readonly">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group input-string-group nomr">
								<label class="control-label">Number of Patents:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_prospects;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group nomr">
								<label class="control-label">Person Name2:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_name_2;?>" readonly="readonly">
							</div>
							<div class="form-group input-string-group nomr">
								<label class="control-label">Person Title2:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->person_title_2;?>" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="row mrg10T">
						<div class="col-xs-9">
							<div class="form-group">
								<!--label for="generalTechnologies" class="control-label">
									<strong>Patent File Url:</strong>
								</label>
								<input type="textbox" class="form-control input-string" name="general[file_url]" id="litigationFileUrl" value="<?php echo $results[0]['litigation']->file_url;?>"/-->
                                <a href="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank">Open Spreadsheet in Google Drive</a>

<input type="hidden" name="litigation[file_url]" id="litigationFileUrl" value="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank" class="btn"/>
							</div>						
						</div>
						<div class="col-xs-3 text-right">
							<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import Data</button>
							<span id="loadingLabel"></span>
						</div>
					</div>
					<div class="row">
				  		<div class="col-xs-4">
							<div class="form-group input-string-group bigmr">
								<label class="control-label">
									Name of Lead:
								</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->lead_name?>" tabindex="11" readonly="readonly">
							</div>
				  		</div>
				  	</div>
					<?php endforeach;?>
				</div>

				<?php } else {?>

					<p class="alert">No record found!</p>

				<?php }?>

			</div>			
<?php 

					if(count($results)>0){												

				?>

			<div class="example-box-wrapper mrg10T">

				<div class="table-responsive">

					<h3 class="title-hero">Team Notes</h3>

					<table class="table table-hover valign-top" id='comment-list'>

						<thead>

							<tr>
								<th>By</th>
								<th>Note</th>
								<th>Date</th>
								<th>Attractiveness</th>
							</tr>

						</thead>

						<tbody>

							<?php /* if(count($results)>0){?>

							<tr>

								<td><?php echo $results[0]['litigation']->userName;?></td>

								<td>

									<?php echo $results[0]['litigation']->comment;?>

								</td>

								<td></td>

							</tr>

							<?php } */ ?>

							<?php if(count($results)>0){ if(count($results[0]['comment'])>0){?>

							<?php foreach($results[0]['comment'] as $comment):?>

							<tr>

								<td><?php echo $comment->name?></td>

								<td><?php echo $comment->comment?></td>
								<td><?php echo date("M d,Y",strtotime($comment->created));?></td>

								<td><span class="label alert <?php if($comment->attractive=='High'):?>label-success<?php elseif($comment->attractive=='Medium'):?>label-primary<?php elseif($comment->attractive=='Low'):?>label-warning<?php else:?>label-danger<?php endif;?>"><?php echo $comment->attractive?></span></td>					
                                <input type="hidden" name="litigation[patent_data]" value="" id="sepPatentData"/>
							</tr>

							<?php endforeach;?>

							<?php } }

							?>							

						</tbody>

					</table>

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
                    _ddd = '<?php echo $results[0]['litigation']->patent_data;?>';
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
                    jQuery("#sepPatentData").val(_ddd);
					jQuery(document).ready(function(){
						var $container = jQuery("#scrap_patent_data");
						$container.handsontable({	
						  data:jQuery.parseJSON(_ddd),
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
					function dataValidate(){
						var $container = $("#scrap_patent_data");
						hst = $container.data('handsontable');
						jQuery("#generalScrapperData").val(JSON.stringify(hst.getData()));
						return true;
					}
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
                    
				</script>
                
                <!--  Clear Button Patent Data -->
                <div style="clear:both;" class="mrg10T clearfix">
                    <!-- <button class='btn btn-danger' type='button' onclick="refreshHST()">Clear Table</button> -->
                    <a onclick="refreshHST()" style="text-decoration:none;" class="link-blue pull-right" href="#">
	            		<i style="font-size:16px;" class="glyph-icon icon-trash-o"></i>
	            		Clear Table
	            	</a>
                </div>
                
				<div class="example-box-wrapper">
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
				</div>
                
              
					<?php }?>

			<?php 



					if(count($results)>0){		

			?>

			

			<div class="example-box-wrapper text-center">

				<p>

					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->

					<button  type="button" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK')" class="btn btn-default btn-mwidth btn-sm">+ Email Proposal</button>

					<button class="btn btn-default btn-mwidth btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>

					<button class="btn btn-default btn-mwidth <?php if((int)$results[0]['litigation']->status==0):?><?php else:?><?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>

					<!--<button class="btn btn-primary btn-sm" onclick="" type="button">Save</button>-->
					
					<span style='display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
					
					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->

				</p>

			</div>

			<?php } ?>

		</div>

	</div>

</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>

<script>

_res = "";

	jQuery("#btnApproved").click(function(){
	   
        var $container = jQuery("#scrap_patent_data");
        hst = $container.data('handsontable');
        patent_data = JSON.stringify(hst.getData());
        
		if(jQuery(this).attr('disabled')==undefined){
            
             parent_id = '<?php echo $results[0]['litigation']->id;?>';
         // alert(patent_data['other']);
        
        jQuery.ajax({
            type:'POST',
            url:'<?php echo $Layout->baseUrl?>leads/insert_patent_data',
            data:{'patent_data':patent_data,'parent_id':parent_id},
            cache:false,
            success:function(response){
               // alert(response);
            }
        })
            
            
			jQuery.ajax({

				type:'POST',

				url:'<?php echo $Layout->baseUrl?>leads/change_status_lead',

				data:{token:jQuery("#otherParentId").val()},

				cache:false,

				success:function(res){

					_data = jQuery.parseJSON(res);

					if(parseInt(_data.rows)==0){

						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-primary').addClass('btn-default');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').removeClass('btn-default').addClass('btn-primary');

					}

				}

			});

		}

	});

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'SEP',complete:2},

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

					if(parseInt(_data.status)==0){

						jQuery("#btnApproved").html('Approved Lead');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled');

					}

					// _tr ='<tr>'+

					// 	'<td><b>Owner: </b> '+_data.plantiffs_name+'</td>'+

					// 	'<td><b>No of Patents: </b> '+_data.no_of_prospects+'</td>'+

					// 	'<td><b>Relates To: </b> '+_data.relates_to+'</td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name1: </b> '+_data.person_name_1+'</td>'+

					// 	'<td><b>Person Title1: </b> '+_data.person_title_1+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name2: </b>'+_data.person_name_2+'</td>'+

					// 	'<td><b>Person Title2: </b> '+_data.person_title_2+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td><b>Person Name3: </b> '+_data.person_name_3+'</td>'+

					// 	'<td><b>Person Title3: </b> '+_data.person_title_3+'</td>'+

					// 	'<td></td>'+

					// '</tr>'+

					// '<tr>'+

					// 	'<td colspan="2"><b>Address: </b>'+_data.address+'</td>'+

					// 	'<td><b>Portfolio Number: </b>'+_data.portfolio_number+'</td>'+

					// '</tr>';							
					snapGlobal= _data.file_url;
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
					_tr = '<div class="row">' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Patent Owner:</label>' +
										'<input type="text" class="form-control" value="'+_data.plantiffs_name+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group">' +
										'<label class="control-label">Address:</label>' +
										'<div>'+_data.address+'</div>' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Relates To:</label>' +
										'<input type="text" class="form-control" value="'+_data.relates_to+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Person Name1:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_name_1+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Person Title1:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_title_1+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Number of Patents:</label>' +
										'<input type="text" class="form-control" value="'+_data.no_of_prospects+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Person Name2:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_name_2+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Person Title2:</label>' +
										'<input type="text" class="form-control" value="'+_data.person_title_2+'" readonly="readonly" style="width: 30px;">' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="row mrg10T">'+
								'<div class="col-xs-9">'+
									'<div class="form-group">'+
										'<label for="generalTechnologies" class="control-label">'+
											'<strong>Patent File Url:</strong>'+
										'</label>'+
										'<input type="textbox" class="form-control input-string" name="general[file_url]" value="'+_data.file_url+'" id="litigationFileUrl" value=""/>'+
									'</div>'+
								'</div>'+
								'<div class="col-xs-3 text-right">'+
									'<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import Data</button>'+
									'<span id="loadingLabel"></span>'+
								'</div>'+
							'</div>'+
							'<div class="row">' +
						  		'<div class="col-xs-4">' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">' +
											'Name of Lead:' +
										'</label>' +
										'<input type="text" class="form-control" value="'+_data.lead_name+'" tabindex="11" readonly="readonly" style="width: 30px;">' +
                                        '<input type="hidden" name="litigation[patent_data]" value="" id="sepPatentData"/>'+
									'</div>' +
						  		'</div>' +
						  	'</div>';

					// jQuery("#record-list>tbody").html(_tr);
					jQuery('#topPart').html(_tr);

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


					/*Comments*/

					_trData = "";

					response = response.results[0].comment;

					if(response.length>0){	

						/*_trData ='<tr>'+

										'<td>'+response.results[0].litigation.userName+'</td>'+

										'<td>'+response.results[0].litigation.comment+'</td>'+

										'<td></td>'+

									'</tr>';*/

						for(i=0;i<response.length;i++){

							_label = '';

							if(response[i].attractive=="High"){

								_label = 'label-success';

							} else if(response[i].attractive=="Medium"){

								_label = 'label-primary';

							} else if(response[i].attractive=="Low"){

								_label = 'label-warning';

							} else if(response[i].attractive=="Disapproved"){

								_label = 'label-danger';

							}

							var parsedDate = $.datepicker.parseDate('yy-mm-dd', response[i].created.split(' ')[0]);

							_trData+='<tr>'+

										'<td>'+response[i].name+'</td>'+

										'<td>'+response[i].comment+'</td>'+

										'<td>'+$.datepicker.formatDate('M, dd, yy', parsedDate)+'</td>'+

										'<td><span class="label alert '+_label+'">'+response[i].attractive+'</span></td>'+

									'</tr>';

						}

					} else {

						_trData="<td colspan='3'><p class='alert'>No record found!</p></td>";

					}

					jQuery("#comment-list>tbody").html(_trData);

					
				}

			}

		});

	}

	

	function sendRequestForProposalLetter(){

		jQuery("#spinner-loader").css('display','inline-block');

	//	_number = jQuery("#record-list>tbody>tr").eq(0).find('td').eq(0).html();

	//	_names = _number.split("<b>Owner: </b>");

        _names = jQuery("#patentOwner").val();
        
		if(_names.length>1){

			_names = jQuery.trim(_names[1]);

		}

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',

			data:{name:_names,type:'General'},

			cache:false,

			success:function(res){

				jQuery("#spinner-loader").css('display','none');

				_res = jQuery.parseJSON(res);

				if(_res.link!=""){

					window.open(_res.link,"_blank","toolbar=yes, scrollbars=yes, resizable=yes,width=600, height=500")

				} else {

					alert('Please try after some time!');

				}

			}

		});

	}

</script>



