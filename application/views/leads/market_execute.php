<style>
	.dashboard-box {
		overflow-y: scroll !important;
	}
</style>

<div class="row">
    <?php echo $Layout->element('task');?>
        <div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
            <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
            <div class="panel dashboard-box">
                <script>
                    jQuery(document).ready(function(){
                    	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>From Market</li>");
                    });
                </script>
            
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
						snapGlobal = '<?php echo $results[0]['litigation']->file_url; ?>';
					</script>
    				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />
    				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>
                    <input type="hidden" name="litigation[patent_data]" value="" id="marketPatentData"/>
    				<!-- <table class="table" id='record-list'>
    
    					<tbody>
    
    						<?php foreach($results as $data):?>
    
    						<tr>
    							<td><b>Seller:</b> <?php echo $data['litigation']->plantiffs_name;?></td>
    							<td><b>No of Prospects:</b> <?php echo $data['litigation']->no_of_prospects;?></td>
    							<td><b>Expected Price:</b> <?php echo $data['litigation']->expected_price;?></td>
    						</tr>
    						<tr>
    							<td><b>Technologies/Markets:</b> <?php echo $data['litigation']->technologies;?></td>
    							<td><b>Prospect Name:</b> <?php echo $data['litigation']->prospects_name;?></td>
    							<td></td>
    						</tr>
    
    						<?php endforeach;?>
    
    					</tbody>
    
    				</table> -->
    
    				
    				<div id="topPart" class="form-horizontal form-flat mrg10T">
    					<div class="row">
    						<div class="col-xs-5">
    							<div class="row">
    								<div class="col-sm-6">
    									<div class="form-group input-string-group">
    										<label class="control-label" for="marketOwner">Seller:</label>
    										<input type="text" class="form-control" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
    									</div>
    								</div>
    								<div class="col-sm-6">
    									<div class="form-group input-string-group">
    										<label class="control-label" for="marketTechnologies">Tech. / Markets:</label>
    										<input type="text" class="form-control" value="<?php echo $data['litigation']->technologies;?>" readonly="readonly">
    									</div>
    								</div>
    							</div>
    						</div>
    						<div class="col-xs-7">
    							<div class="row">
    								<div class="col-xs-6">
    									<div class="row">
    										<div class="col-xs-5">
    											<div class="form-group input-string-group">
    												<label class="control-label" for="marketProspects">N. of Prospects:</label>
    												<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_prospects;?>" maxlength="2" readonly="readonly">
    											</div>
    										</div>
    										<div class="col-xs-7">
    											<div class="form-group input-string-group">
    												<label class="control-label" for="marketExpectedPrice">Expected Price($M):</label>
    												<input type="text" class="form-control" value="<?php echo $data['litigation']->expected_price;?>" maxlength="2" readonly="readonly">
    											</div>
    										</div>
    									</div>
    								</div>
    								<div class="col-xs-6">
                                        	<div class="col-xs-6">
                								<div class="form-group input-string-group nomr">
                									<label for="marketNo_of_us_patents" class="control-label">N. of US Patents:</label>
                							         <input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_us_patents;?>" readonly="readonly">
                                                     
                								</div>
                							</div>
                                            <div class="col-xs-6">
                								<div class="form-group input-string-group nomr">
                									<label for="marketNo_of_non_us_patents" class="control-label">N. of Non-US Patents:</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_non_us_patents;?>" readonly="readonly">
                                                    
                								</div>
                							</div>
    								</div>
    							</div>
    						</div>
    					</div>
                        <div class="row">
    				  		<div class="col-xs-5">
    							<div class="form-group input-string-group nomr">
									<label class="control-label" for="marketOwner">Prospect's Names:</label>
									<input type="text" class="form-control" value="<?php echo $data['litigation']->prospects_name;?>" readonly="readonly">
								</div>
    				  		</div>
    				  	</div>
    					<div class="row">
    				  		<div class="col-xs-5">
    							<div class="form-group input-string-group">
    								<label class="control-label">
    									Name of Lead:
    								</label>
    								<input type="text" tabindex="11" id="marketLeadName" value="<?php echo $data['litigation']->lead_name;?>" class="form-control" readonly="readonly">
    							</div>
    				  		</div>
    				  	</div>
						<div class="row mrg10T">
							<div class="col-xs-9">
								<div class="form-group">
									<!--label for="generalTechnologies" class="control-label">
										<strong>Patent File Url:</strong>
									</label>
									<input type="textbox" class="form-control input-string" name="litigation[file_url]" id="litigationFileUrl" value="<?php echo $data['litigation']->file_url;?>"/-->
                                    <a href="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank">Open Spreadsheet in Google Drive</a>

<input type="hidden" name="litigation[file_url]" id="litigationFileUrl" value="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank" class="btn"/>
								</div>						
							</div>
							<div class="col-xs-3 text-right">
								<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import Data</button>
								<span id="loadingLabel"></span>
							</div>
						</div>
    				</div>
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
    								$comment1 = $comment->comment1;
                                    $comment2 = $comment->comment2;
                                    $comment3 = $comment->comment3;
    								$commentAttractive = $comment->attractive;
    								$commentUser = $comment->user_id;
    							}
    							if($results[0]['litigation']->user_id==$comment->user_id){
    								$creattorCommentText = $comment->comment;
    								$creattorAttractive = $comment->attractive;
    								$comment1 = $comment->comment1;
                                    $comment2 = $comment->comment2;
                                    $comment3 = $comment->comment3;
                                    $creattorUser = $comment->name;
    								$creattorDate = $comment->created;
    							}
    						endforeach;
    					}
    				}
    				?>	
                <div class="row" style="padding-right:8px; padding-left:2px;">
					<!-- <label class="col-sm-1 control-label" for="litigationLinkToPacer">Comment: </label> -->
					<div class="col-sm-4">
						<label class="control-label" for="marketProspectsName">Are there >10 potential licensees? Who?</label>
						<?php echo form_textarea(array('name'=>'comment[comment1]','id'=>'commentComment1','value' => $comment1,'class'=>'form-control','rows'=>4,'cols'=>15));?>	
					</div>
					<div class="col-sm-4">
						<label class="control-label" for="marketProspectsName">Will licensees want to pay the expected fee? Why?</label>
						<?php echo form_textarea(array('name'=>'comment[comment2]','id'=>'commentComment2','value' => $comment2,'class'=>'form-control','rows'=>4,'cols'=>15));?>	
					</div>
					<div class="col-sm-4">
						<label class="control-label" for="marketProspectsName">Seller's concerns + Your general observations</label>
						<?php echo form_textarea(array('name'=>'comment[comment3]','id'=>'commentComment3','value' => $comment3,'class'=>'form-control','rows'=>4,'cols'=>15));?>	
					</div>
				</div>
    				<?php } else {?>
    
    					<p class="alert">No record found!</p>
    
    				<?php }?>
    
    			</div>	
                
    			<div class="example-box-wrapper" style="margin-top: 10px;">
    
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
    
    							</tr>
    
    							<?php endforeach;?>
    
    							<?php 
                                } 
                                }
    							?>							
    
    						</tbody>
    
    					</table>
    				</div>
    			</div>			
    			<div class="col-sm-12 pull-left" style="padding:0px" >
    					<?php 
    						if(count($results)>0){
    							if(count($results[0]['market'])>0){
    					       ?>
    							<h3>Content</h3>
    							<table class='table table-bordered' id="boxesList">
    								<tbody>
    					           <?php
    								foreach($results[0]['market'] as $box){
    					           ?>
    									<tr>
    										<?php 
    											if($box['type']=="Message"){
    												$subject = "";
    												$date = "";
    												foreach($box['header'] as $header){
    													if($header->name=="Subject"){
    														$subject = $header->value;
    													}
    													if($header->name=="Date"){
    														$date = $header->value;
    													}
    												}
    										?>
    											<td>
                                                <i class='glyph-icon icon-file'></i> <a href='javascript:void(0)' onclick='findThread("<?php echo $box['parent_id'] ?>",jQuery(this),1);'><?php echo $subject;?></a>
                                                    
                                                </td>
    											<td><?php echo date('M d',strtotime($date));?></td>
    										  <?php
    											} else if($box['type']="Attachment"){
    												
    												$date = "";
    												foreach($box['header'] as $header){
    													if($header->name=="Date"){
    														$date = $header->value;
    													}
    												}
    										      ?>
    												<td><i class='glyph-icon icon-file'></i> <a href='https://mail.google.com/mail/u/0/?ui=2&view=att&th=<?php echo $box['message_id'];?>&disp=safe&realattid=<?php echo $box['realAttachID'];?>' target="_blank"><?php echo $box['filename'];?></a></td>
    												<td><?php echo date('M d',strtotime($date));?></td>
    										<?php
    											}
    										?>
    									</tr>
    					           <?php
    								}
    					           ?>
    								</tbody>
    							</table>
    					       <?php
    							}
    						}
    					?>
    					
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
                    jQuery("#marketPatentData").val(_ddd);
                  //  alert(_ddd);
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
						jQuery("#generalScrapperData").val(JSON.stringify(hst.getData()));
						return true;
					}
                    
                    function bytesToSize(bytes) {
					   if(bytes == 0) return '0 Byte';
					   var k = 1000;
					   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
					   var i = Math.floor(Math.log(bytes) / Math.log(k));
					   return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
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
                    
                    function findThread(thread,object, flag){
						if(typeof(flag)==="undefined"){
							flag=0;
						}
                        
							jQuery("#scrapGoogleData").find('.pad15A').html('<div class="loading-spinner" id="loading_spinner_heading_google_scrap" style="display:none;"><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i><i class="bg-primary"></i></div><div id="scrapGooglePatent"></div>');
							jQuery("#loading_spinner_heading_google_scrap").css('display','block');
							jQuery("#scrapGoogleData").addClass("sb-active").animate({ textIndent:0}, {
																			step: function(now,fx) {
																			  $(this).css('transform','translate(-350px)');
																			},
																			duration:'slow'
																		},'linear');
							jQuery.ajax({
							type:'POST',
							url:'<?php echo $Layout->baseUrl?>opportunity/findThread',
							data:{thread:jQuery.trim(thread)},
							cache:false,
							success:function(res){
								_data = jQuery.parseJSON(res);
								jQuery("#loading_spinner_heading_google_scrap").css('display','none');			
								for(i=0;i<_data.length;i++){
									headers = _data[i].header;
									subject = "";
									from = "";
									to = "";
									date = "";
									email = "";
									for(j=0;j<headers.length;j++){
										if(headers[j].name=='Subject'){
											subject = headers[j].value;
										}
										if(headers[j].name=='From'){
											from = headers[j].value;
										}
										if(headers[j].name=='To'){
											to = headers[j].value;
										}
										if(headers[j].name=='Authentication-Results'){
											raw = headers[j].value;
											raw = raw.split('smtp.mail=');
											if(raw==2){
												email = raw[1];
											}
										}
										if(headers[j].name=='Received'){
											date = headers[j].value;
											_da = date.split(';')
											if(_da.length==2){
												date = _da[1];
											}
										}
									}
									body = _data[i].body;
									_attachmentString ="";
									if(_data[i].attachments.length>0){
										_attachmentString='<div class="message-attache">';
										for(a=0;a<_data[i].attachments.length;a++){
											attachmentData  = _data[i].attachments[a];
											_attachmentString +='<div class="media">'+
                                                '<i class="fa fa-paperclip pull-left fa-2x"></i>'+
                                                '<div class="media-body">'+
                                                    '<div><a target="_BLANK" href="https://mail.google.com/mail/u/0/?ui=2&view=att&th='+_data[i].message_id+'&disp=safe&realattid='+attachmentData.realAttachID+'" dataId="'+_data[i].message_id+'" dataMime="'+attachmentData.mimeType+'" dataAttached="'+attachmentData.attachmentId+'" dataFileName="'+attachmentData.filename+'" db="getGmailAttachment(jQuery(this))"; class="strong text-regular">'+attachmentData.filename+'</a>'+
                                                    '</div>'+
                                                    '<span>'+bytesToSize(attachmentData.size)+'</span>'+
                                                    '<div class="clearfix"></div>'+
                                                '</div>'+
                                            '</div>';
										}
										_attachmentString +="</div>";										
									}
									_messagesString = "";
                                    if(i==0){
                                        jQuery("#scrapGooglePatent").empty();
                                        	_messagesString +='<div class="col-lg-12 col-md-12 email-hidden-sm detail-message"><div data-padding="40" data-height="window" class="panel panel-default panel-no-margin withScroll mCustomScrollbar _mCS_117" id="message-detail" style="height:302px;"><div style="height:100%; overflow-x:hidden !important;overflow-y;scroll !important; max-width:100%;" id="mCSB_117" class="mCustomScrollBox mCS-dark-2"><div class="mCSB_container"><div class="panel-heading messages message-result">	<h2 class="message-detail-title is-subject p-t-20 w-500"><span class="message-detail-subject" style="display:inline-block;">'+subject+'</span></h2><div class="row">'+
											'<div class="col-xs-6">'+
												'<h2 class="message-detail-title p-t-20 w-500" style="display:inline-block;">'+
											'		<strong><span class="message-detail-from">'+from+'</span></strong>'+
											'	</h2>'+
											'	<h2 class="message-detail-title p-t-20 w-500" style="display:inline-block;">'+
												'	to: <span class="message-detail-to">'+to+'</span>'+
												'	<div role="group" class="message-detail-buttons-left btn-group">'+
	  										'			<div role="group" class="btn-group">'+
	    										'			<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">'+
	      										'				<span class="caret"></span>'+
	    											'		</button>'+
	    											'		<div role="menu" class="dropdown-menu" style="width:350px;">'+
	    											'			<div class="row">'+
	    											'				<div class="col-xs-3"><label>from:</label></div>'+
	    											'				<div class="col-xs-9"><strong class="message-detail-from">'+from+'</strong></div>'+
	    											'			</div>'+
	    											'			<div class="row">'+
	    											'				<div class="col-xs-3"><label>to:</label></div>'+
	    											'				<div class="col-xs-9"><span class="message-detail-to">'+to+'</span></div>'+
	    											'			</div>'+
	    											'			<div class="row">'+
	    											'				<div class="col-xs-3"><label>date:</label></div>'+
	    											'				<div class="col-xs-9"><span class="message-detail-date">'+moment(new Date(date)).format('lll')+'</span></div>'+
	    											'			</div>'+
	    											'			<div class="row">'+
	    											'				<div class="col-xs-3"><label>subject:</label></div>'+
	    											'				<div class="col-xs-9"><span class="message-detail-subject">'+subject+'</span></div>'+
	    											'			</div>'+
	    											'		</div>'+
	  											'		</div>'+
												'	</div>'+
											'	</h2>'+
										'	</div>'+
											'<div class="col-xs-6 text-right">'+
											'	<h2 class="message-detail-title p-t-20 w-500 is-date">'+
											'		<span class="message-detail-date">'+moment(new Date(date)).format('lll')+'</span>'+
											'	</h2>'+
											'	<div class="message-detail-right">'+
											'		<a data-placement="bottom" title="" class="message-detail-star tooltip-button" href="javascript://" data-original-title="Starred"><i class="glyph-icon icon-star"></i></a>'+
											'	</div>'+
                                            '<input type="hidden" name="litigation[patent_data]" value="" id="marketPatentData"/>'+
										'	</div>'+
									'	</div>';'	</div>';
                                    }
                                    /*
									if(i==0){
										jQuery("#message-detail .panel-heading").find('.message-detail-subject').html(subject);
										jQuery('.message-detail-title').show();
										jQuery('.message-detail-title.is-date').css({ display: 'inline-block' });
										$('.message-detail-from').html(from);
										$('.message-detail-to').html(to);
										$('.message-detail-date').html(moment(new Date(date)).format('lll'));
										jQuery('#message-detail .message-detail-right').css({display: 'inline-block'});
									}*/
									_messagesString +='<div class="row">'+
															'<div class="col-md-12 col-sm-12 col-xs-12">'+
															'    <div class="p-20">';
									if(i>0){
										_messagesString +='<h3 class="message-title">'+subject+'</h3>';
									}
									_messagesString +=		'        <div class="message-item media">'+
															'            <div class="message-item-right">'+
															'                <div class="media">'+
															'                    <div class="media-body">'+
															'                        <small class="pull-right">'+moment(new Date(date)).format('lll')+'</small>'+
															'                        <h5 class="c-dark"><strong>'+from+'</strong></h5>'+
															'                        <p class="c-gray">'+email+'</p>'+
															'                    </div>'+
															'                </div>'+
															'            </div>'+
															'        </div>'+
															'    </div>'+
															'   <div class="message-body">'+body+_attachmentString+
															'    </div>'+
															'</div>'+
														'</div>';
									jQuery("#scrapGooglePatent").append(_messagesString);
									if(i>0 && i<_data.length-1){
										jQuery("#scrapGooglePatent").append('<div class="message-between"></div>');
									}
                                    if(i==_data.length-1){
                                   	    jQuery("#scrapGooglePatent").append('</div></div></div></div>');
                                    }									
								}
                                jQuery('[data-toggle=dropdown]').dropdown();
							}
						});
							
                        /*	
						jQuery("#list_boxes").find('table').find('tr').removeClass("activetr");
						if(flag==0){
							if(object.parent().parent().attr('id')!="new_lead_drop"){
								jQuery('.messages-list-leads').find('.message-item').each(function(){
									jQuery(this).removeClass('message-active');
									jQuery(this).find('h5').addClass('c-dark');
									jQuery(this).find('h4').addClass('c-dark');
									jQuery(this).find('p').addClass('c-gray');
									jQuery(this).find('a').addClass('c-dark');
								});
								// object.parent().parent().parent().parent().parent().parent().find('h5').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('h4').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('p').removeClass('c-gray');
								// object.parent().parent().parent().parent().parent().parent().find('a').removeClass('c-dark');
								// object.parent().parent().parent().parent().parent().parent().find('a').css('color','#FFF');
								// object.parent().parent().parent().parent().parent().parent().addClass('message-active');
								object.parent().parent().parent().find('h5').removeClass('c-dark');
								object.parent().parent().parent().find('h4').removeClass('c-dark');
								object.parent().parent().parent().find('p').removeClass('c-gray');
								object.parent().parent().parent().find('a').removeClass('c-dark');
								object.parent().parent().parent().find('a').css('color','#FFF');
								object.parent().parent().parent().addClass('message-active');
							}
						}
						jQuery(".message-detail-subject").empty();
						jQuery('.message-detail-right').hide();
						jQuery('.message-detail-title').hide();
						jQuery('.message_detail').empty().html('<div class="loading-spinner" id="loading_spinner" style="display:none;">'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
											'<i class="bg-primary"></i>'+
										'</div>');
						jQuery("#loading_spinner").show();
						jQuery("#messages-boxlist").find('ul.todo-box').find("li").each(function(){
							jQuery(this).removeClass('active');
							jQuery("#marketLead").get(0).reset();
						});*/
						
					} 
                    
				</script>
                 <!--  Clear Button Patent Data -->
                <div style="clear:both;" class="mrg10T clearfix">
                    <!-- <button class='btn btn-danger' type='button' onclick="refreshHST()">Clear Table</button> -->
                    <a href="#" class="link-blue pull-right" style="text-decoration:none;" onclick="refreshHST()">
                        <i class="glyph-icon icon-trash-o" style="font-size:16px;"></i>
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
               
                
                
    			<?php 
    			if(count($results)>0){		
        			?>
        			<div class="example-box-wrapper text-center">
        				<p>
        					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->
        
        					<button  type="button" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK')" class="btn btn-primary btn-default btn-sm">+ Email Proposal</button>
        
        					<button class="btn btn-default btn-mwidth btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>
        
        					<button class="btn btn-default btn-mwidth <?php if((int)$results[0]['litigation']->status==0):?><?php else:?><?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>
        					<span style='display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>					
        
        					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->
        				</p>
        
        			</div>
    			<?php } ?>
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
      //  patent_data = jQuery.JSON.stringify(jQuery('#marketPatentData').val());
        
		if(jQuery(this).attr('disabled')==undefined){
		  parent_id = '<?php echo $results[0]['litigation']->id;?>';
          //alert(patent_data['other']);
        
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

						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-sm');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').addClass('btn-sm');

					}

				}

			});

		}

	});

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'Market',complete:2},

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

						jQuery("#btnApproved").html('Approved Lead').addClass('btn-sm');

					} else {

						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').addClass('btn-sm');

					}

					// _tr ='<tr>'+

					// 		'<td><b>Seller:</b> '+_data.plantiffs_name+'</td>'+

					// 		'<td><b>No of Prospects:</b> '+_data.no_of_prospects+'</td>'+

					// 		'<td><b>Expected Price:</b> '+_data.expected_price+'</td>'+

					// 	'</tr>'+	

					// 	'<tr>'+

					// 		'<td><b>Technologies/Markets:</b> '+_data.technologies+'</td>'+

					// 		'<td><b>Prospect Name:</b> '+_data.prospects_name+'</td>'+

					// 	'</tr>';
					snapGlobal= _data.file_url;
					_tr = '<div class="row">' +
								'<div class="col-xs-5">' +
									'<div class="row">' +
										'<div class="col-sm-6">' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label" for="marketOwner">Seller:</label>' +
												'<input type="text" class="form-control" value="'+_data.plantiffs_name+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
										'</div>' +
										'<div class="col-sm-6">' +
											'<div class="form-group input-string-group">' +
												'<label class="control-label" for="marketTechnologies">Tech. / Markets:</label>' +
												'<input type="text" class="form-control" value="'+_data.technologies+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="col-xs-7">' +
									'<div class="row">' +
										'<div class="col-xs-5">' +
											'<div class="row">' +
												'<div class="col-xs-6">' +
													'<div class="form-group input-string-group">' +
														'<label class="control-label" for="marketProspects">N. of Prospects:</label>' +
														'<input type="text" class="form-control" value="'+_data.no_of_prospects+'" maxlength="2" readonly="readonly" style="width: 15px;">' +
													'</div>' +
												'</div>' +
												'<div class="col-xs-6">' +
													'<div class="form-group input-string-group">' +
														'<label class="control-label" for="marketExpectedPrice">Expected Price:</label>' +
														'<input type="text" class="form-control" value="'+_data.expected_price+'" maxlength="2" readonly="readonly" style="width: 15px;">' +
													'</div>' +
												'</div>' +
											'</div>' +
										'</div>' +
										'<div class="col-xs-7">' +
											'<div class="form-group input-string-group nomr">' +
												'<label class="control-label" for="marketOwner">Prospect\'s Names:</label>' +
												'<input type="text" class="form-control" value="'+_data.prospects_name+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="row mrg10T">'+
								'<div class="col-xs-9">'+
									'<div class="form-group">'+
										'<label for="generalTechnologies" class="control-label">'+
											'<strong>Patent File Url:</strong>'+
										'</label>'+
										'<input type="textbox" class="form-control input-string" name="market[file_url]" value="'+_data.file_url+'" id="litigationFileUrl" value=""/>'+
									'</div>'+
								'</div>'+
								'<div class="col-xs-3 text-right">'+
									'<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import Data</button>'+
									'<span id="loadingLabel"></span>'+
								'</div>'+
							'</div>'+
							'<div class="row">' +
						  		'<div class="col-xs-5">' +
									'<div class="form-group input-string-group">' +
										'<label class="control-label">' +
											'Name of Lead:' +
										'</label>' +
										'<input type="text" tabindex="11" id="marketLeadName" value="'+_data.lead_name+'" class="form-control" style="width: 30px;"  readonly="readonly">' +
									'</div>' +
						  		'</div>' +
						  	'</div>';

					// jQuery("#record-list>tbody").html(_tr);
					jQuery('#topPart').html(_tr);
					
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

					jQuery("#boxesList>tbody").empty();
					if(response.results[0].market.length>0){
						for(i=0;i<response.results[0].market.length;i++){
							_d = response.results[0].market[i];
							_header = _d.header;
							_showData = "";
							_receivedDate = "";
							if(_d.type=="Message"){
								if(_d.header.length>0){
									for(h=0;h<_d.header.length;h++){
										if(_d.header[h].name=="Subject"){
											_showData="<i class='glyph-icon icon-envelope'></i> "+_d.header[h].value;
										}
										if(_d.header[h].name=="Date"){
											_receivedDate = _d.header[h].value;																
										}
									}
								}
							} else if(_d.type=="Attachment"){
								if(_d.header.length>0){
									for(h=0;h<_d.header.length;h++){
										if(_d.header[h].name=="Date"){
											_receivedDate = _d.header[h].value;																
										}
									}
								}
								_showData="<i class='glyph-icon icon-file'>"+_d.filename;
							}
							if(_showData!=""){
								_tr = '<tr><td>'+_showData+'</td><td>'+$.datepicker.formatDate('M dd', new Date(_receivedDate))+'</td></tr>';
								jQuery("#boxesList>tbody").append(_tr);
							}							
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
                             timeLineTable +=   '<p class="tl-content">'+_dataTimeLine[i].message+'</p>'+
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

										'<td>'+$.datepicker.formatDate('M dd, yy', parsedDate)+'</td>'+

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
		/*
		_number = jQuery("#record-list>tbody>tr").eq(0).find('td').eq(0).html();

		_names = _number.split("<b>Seller:</b>");

		if(_names.length>1){

			_names = jQuery.trim(_names[1]);

		}
		*/
		_names = jQuery("#marketLeadName").val();
		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',

			data:{name:_names,type:'Market'},

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



