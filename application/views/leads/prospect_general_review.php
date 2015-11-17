<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Review</a></li><li class='active'>Proactive General</li>");
});
</script>

<style>
	.dashboard-box {
		overflow-y: scroll !important;
	}
</style>

<div class="panel dashboard-box">

    <div class="panel-body">

		<div class="example-box-wrapper"  id="review">


			<?php 
				
			
				$commentID = 0;
				$commentText ="";
				$commentAttractive = "";
				$commentUser = 0;
				$creattorCommentText = "";
				$creattorAttractive = "";
				$creattorUser = "";
				$creattorDate = "";
				if(count($results)>0){
					if(count($results[0]['comment'])>0){
						foreach($results[0]['comment'] as $comment):
							if($comment->user_id==$this->session->userdata['id']){
								$commentID = $comment->id;
								$commentText = $comment->comment;
								$commentAttractive = $comment->attractive;
								$commentUser = $comment->user_id;
							}
							if($results[0]['litigation']->user_id==$comment->user_id){
								$creattorCommentText = $comment->comment;
								$creattorAttractive = $comment->attractive;
								$creattorUser = $comment->name;
								$creattorDate = $comment->created;
							}
						endforeach;
					}
				}
			?>

			<div class="table-responsive">

				<div class="row row-width">

					<?php 

						$previous = "disabled='disabled'";

						if((int)$current_page>1){						

							$previous ="";

						}

						$next = "disabled='disabled'";

						

						if($total_rows>1 && ($current_page+1)<=$no_of_pages){

							$next ="";

						}

					?>

					<!--<button type="button" id="prev" onclick="record('prev');" <?php echo $previous;?> class="glyph-icon tooltip-button demo-icon icon-angle-left"><i class="fa fa-chevron-left"></i></button>

					<button type="button" id="next" onclick="record('next');" <?php echo $next;?> class="glyph-icon tooltip-button demo-icon icon-angle-right"><i class="fa fa-chevron-right"></i></button>-->

					<div class="col-xs-12">
						<div id="nameOfLeadContainer" class="form-horizontal form-flat">
							<div class="row">
								<div class="col-sm-4">
										<div class="form-group input-string-group">
										<label style="float:left;" class="control-label"><strong>Name of Lead:</strong></label>
										<input type="text" style="float: left; margin-left:5px; margin-top:4px; width: 66.6666%;" value="<?php echo $results[0]['litigation']->lead_name;?>" readonly="readonly" class="form-control" placeholder="" id="marketlead_name" required="required" >
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group text-right">
										<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">
											<input type="checkbox" value="1" name="litigation[complete]"> Lead is complete and ready to be forwarded for Execute
										</label>
										<input type="hidden" name="other[type]" value="General"/>
		                                <input type="hidden" name="litigation[patent_data]" value="" id="generalPatentData"/>
										<input type="hidden" name="other[id]" id="commentID" value="<?php echo $commentID;?>"/>
										<button type="button" onclick="userComment();" class="btn btn-primary btn-mwidth float-right">Save</button>							
									</div>
								</div>
							</div>
						</div>
					</div>
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
				<div id="topPart" class="form-horizontal form-flat">

					

					

					<?php foreach($results as $data):?>
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group input-string-group bigmr">
								<label class="control-label">Patent Owner:</label>
								<input type="text" class="form-control" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
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
					<?php endforeach;?>

					


					<?php echo form_open('leads/comment',array('class'=>'form-horizontal bordered-row form-flat','role'=>'form','id'=>'leadComment'));?>

					<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $data['litigation']->id;?>"/>
					<div class="row mrg5T" style="padding:0px">
						<div class="col-sm-10" style="width:90%;">
							<div class="form-group">
							<label class="sr-only" for="litigationCaseName">Comment</label>
							<div><strong>Notes:</strong></div>
							<div class="mrg5T"></div>
								<?php echo form_textarea(array('name'=>'other[comment]','id'=>'litigationComment','placeholder'=>'','class'=>'form-control','rows'=>'5','value'=>$commentText));?>				
							</div>
						</div>
						<div class="col-sm-2" style="width:10%;">				
							<div class="form-group" style="margin-top:24px;">
								<label class="sr-only" for="litigationCaseName">Attractive</label>						
								<select name="other[attractive]" class="form-control">
									<option value="">Attractiveness</option>
									<option <?php if($commentAttractive=='High'):?>SELECTED="SELECTED"<?php endif;?> value="High">High</option>
									<option <?php if($commentAttractive=='Medium'):?>SELECTED="SELECTED"<?php endif;?> value="Medium">Medium</option>
									<option  <?php if($commentAttractive=='Low'):?>SELECTED="SELECTED"<?php endif;?>value="Low">Low</option>
									<option  <?php if($commentAttractive=='Disapproved'):?>SELECTED="SELECTED"<?php endif;?>value="Disapproved">Disapproved</option>
								</select>			
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
							<button type="button" class="btn btn-default btn-mwidth pull-right" onclick="findPatentFromSheet()" tabindex="13">Import / Update Data</button>
							<span id="loadingLabel"></span>
						</div>
					</div>
					<?php echo form_close();?>	
					<?php } else {?>

						<p class="alert">No record found!</p>

					<?php }?>
				</div>	

			</div>

			

			<?php 

				if(count($results)>0){								

					$litigationID = $results[0]['litigation']->id;

					

			?>
            <!--  Clear Button Patent Data -->
            <div style="clear:both;" class="mrg10T clearfix">
                <!-- <button class='btn btn-danger' type='button' onclick="refreshHST()">Clear Table</button> -->
                <a href="#" class="link-blue pull-right" style="text-decoration:none;" onclick="refreshHST()">
            		<i class="glyph-icon icon-trash-o" style="font-size:16px;"></i>
            		Clear Table
            	</a>
            </div>
            
			<div class="widget-content padding">

				<div id="horizontal-form">

				

					<?php if($results[0]['litigation']->user_id!=$this->session->userdata['id']):?>
						<div class="table-responsive">
						<h3 class="title-hero">Team Notes</h3>		
						<table class="table table-hover" id='comment-list'>
							<thead>
								<tr>
									<th>By</th>
									<th>Note</th>
									<th>Date</th>
									<th>Attractiveness</th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $creattorUser?></td>
									<td ><?php echo $creattorCommentText?></td>
									<td><?php echo date('M d,Y',strtotime($creattorDate));?></td>
									<td><span class="label alert <?php if($creattorAttractive=='High'):?>label-success<?php elseif($creattorAttractive=='Medium'):?>label-primary<?php elseif($creattorAttractive=='Low'):?>label-warning<?php elseif($creattorAttractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $creattorAttractive?></span></td>					
								</tr>
							</tbody>
						</table>
						</div>					
					<?php endif;?>

				
				

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
			<?php /*if(!empty($results[0]['litigation']->scrapper_data)):*/?>
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
                    jQuery("#generalPatentData").val(_ddd);
					jQuery(document).ready(function(){
						var $container = jQuery("#scrap_patent_data");
							$container.handsontable({
							 data:jQuery.parseJSON(_ddd),
								startRows: 1,
								data:jQuery.parseJSON(_ddd),
								startCols: 9,
								/*colWidths: [45, 55, 90, 80, 80, 40, 40, 70, 80],								*/
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
				</script>
			<?php /*endif;*/?>
			<div class="mrg5T handsontable" id="scrap_patent_data" >
					
			</div>
           
			<?php } ?>			

		</div>

	</div>

</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>

<script>
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
                    
_res = "";

_senddt = '<?php echo $this->session->userdata['id'];?>';

	function userComment(){
	   
       var $container = jQuery("#scrap_patent_data");
    	hst = $container.data('handsontable');
    	jQuery("#generalPatentData").val(JSON.stringify(hst.getData()));
        
		_form = jQuery("#leadComment").serialize();

		jQuery.ajax({

			url:'<?php echo $Layout->baseUrl?>leads/comment',

			type:'POST',

			data:_form,

			cache:false,			

			success:function(response){

				response = jQuery.parseJSON(response);
				jQuery(".panel-body").find('.alert').remove();
				if(response.message!=undefined){

					jQuery("#review").before('<p class="alert alert-success">'+response.message+'</p>');

					jQuery("#commentID").val(response.id);

				} else if(response.error!=undefined){

					jQuery("#review").before('<p class="alert alert-danger">'+response.message+'</p>');

				}

				//jQuery("#leadComment")[0].reset();				

			}

		});

	}

	___data = '';

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'General',complete:1},

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
										'<input type="text" value="'+_data.plantiffs_name+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
									'<div class="form-group">' +
										'<label class="control-label">Address:</label>' +
										'<div>'+_data.address+'</div>' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Relates To:</label>' +
										'<input type="text" value="'+_data.relates_to+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Person Name1:</label>' +
										'<input type="text" value="'+_data.person_name_1+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
									'<div class="form-group input-string-group bigmr">' +
										'<label class="control-label">Person Title1:</label>' +
										'<input type="text" value="'+_data.person_title_1+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-4">' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Number of Patents:</label>' +
										'<input type="text" value="'+_data.no_of_prospects+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Person Name2:</label>' +
										'<input type="text" value="'+_data.person_name_2+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
									'<div class="form-group input-string-group nomr">' +
										'<label class="control-label">Person Title2:</label>' +
										'<input type="text" value="'+_data.person_title_2+'" class="form-control" style="width: 30px;" readonly="readonly">' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<form id="leadComment"><div style="padding:0px" class="row mrg5T">' +
								'<div class="col-sm-10" style="width:90%;">' +
									'<div class="form-group">' +
									'<label for="litigationCaseName" class="sr-only">Comment</label>' +
									'<div><strong>Notes:</strong></div>' +
									'<div class="mrg5T"></div>' +
										'<textarea class="form-control" placeholder="" id="litigationComment" rows="5" cols="40" name="other[comment]"></textarea>' +
									'</div>' +
								'</div>' +
								'<div class="col-sm-2" style="width:10%;">' +
									'<div style="margin-top:24px;" class="form-group">' +
										'<label for="litigationCaseName" class="sr-only">Attractive</label>' +
										'<select class="form-control" name="other[attractive]">' +
											'<option value="">Attractiveness</option>' +
											'<option value="High">High</option>' +
											'<option value="Medium">Medium</option>' +
											'<option value="Low">Low</option>' +
											'<option value="Disapproved">Disapproved</option>' +
										'</select>' +
									'</div>' +
								'</div>' +
							'</div>	' +
							'<div class="row mrg10T">'+
								'<div class="col-xs-9">'+
									'<div class="form-group">'+
										/*'<label for="generalTechnologies" class="control-label">'+
											'<strong>Patent File Url:</strong>'+
										'</label>'+
										'<input type="textbox" class="form-control input-string" name="general[file_url]" value="'+_data.file_url+'" id="litigationFileUrl" value=""/>'+*/
										'<a href="'+_data.file_url+'" target="_blank">Open Spreadsheet in Google Drive</a>'+
										'<input type="hidden" name="litigation[file_url]" id="litigationFileUrl" value="'+_data.file_url+'" target="_blank" class="btn"/>'+
									'</div>'+
								'</div>'+
								'<div class="col-xs-3 text-right">'+
									'<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import Data</button>'+
									'<span id="loadingLabel"></span>'+
								'</div>'+
							'</div>'+
							'<input type="hidden" name="other[parent_id]" id="otherParentId" value="'+_data.id+'"/></form>';

					$('#nameOfLeadContainer').html(
							'<div class="row">' +
								'<div class="col-sm-4">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label" style="float:left;"><strong>Name of Lead:</strong></label>' +
										'<input type="text" required="required" value="'+_data.lead_name+'" id="marketlead_name" class="form-control" readonly="readonly" style="width:30px">' +
									'</div>' +
								'</div>' +
								'<div class="col-sm-8">' +
									'<div class="form-group text-right">' +
										'<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">' +
											'<input type="checkbox" value="1" name="litigation[complete]"> Lead is complete and ready to be forwarded for Execute' +
											'<input type="hidden" id="litigationScrapperData" class="form-control">' +
										'</label>' +
										'<input type="hidden" value="SEP" name="other[type]">' +
										'<input type="hidden" value="0" id="commentID" name="other[id]">' +
                                        '<input type="hidden" name="litigation[patent_data]" value="" id="sepPatentData"/>'+
										'<button class="btn btn-primary btn-mwidth float-right" onclick="userComment();" type="button">Save</button>' +
									'</div>' +
								'</div>' +
							'</div>'
					);

					// jQuery("#record-list>tbody").html(_tr);
					jQuery("#topPart").html(_tr);
                   
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

							jQuery("#litigationComment").val(_commentText);

							jQuery("#commentID").val(_commentID);

							jQuery("select[name='other[attractive]']").find('option').each(function(){ 

								if(jQuery(this).attr('value')==_commentAttractive){

									jQuery(this).attr('SELECTED','SELECTED');

								} else {

									jQuery(this).removeAttr('SELECTED');

								}

							});

						}

						if(_comment.user_id==_data.user_id){

							/*Creator Comment*/
								var parsedDate = $.datepicker.parseDate('yy-mm-dd', _comment.created.split(' ')[0]);
								jQuery("#comment-list>tbody").html('<tr>'+
									'<td>'+_comment.name+'</td>'+
									'<td>'+_comment.comment+' </td>'+
									'<td>'+$.datepicker.formatDate('M dd, yy', parsedDate)+'</td>'+
									'<td><span class="label alert label-success">'+_comment.attractive+'</span></td>	'+				
								'</tr>');

						}						

					}

					if(_flag==0){

						jQuery("#litigationComment").val('');

						jQuery("#commentID").val(0);

						jQuery("select[name='other[attractive]']").find('option').each(function(){ 

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