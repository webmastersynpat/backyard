<div class="row"><?php echo $Layout->element('task');?><div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<div class="panel dashboard-box">
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Review</a></li><li class='active'>From Market</li>");
	<?php 
		if(!empty($auth_url)){
	?>
		$('#myModalEmailLogin').modal('show');
	<?php
		}
	?>
    
});

   function dataValidate(){
       
    	var $container = jQuery("#scrap_patent_data");
    	hst = $container.data('handsontable');
    	jQuery("#marketPatentData").val(JSON.stringify(hst.getData()));
    	return true;
    }              
                    
</script>

<?php 
	if(!empty($auth_url)){
?>
	<script>
		function sendRequestURL(){
			jQuery.ajax({
				url:'<?php echo $Layout->baseUrl;?>leads/sendRequestURL',
				type:'POST',
				data:{t:'<?php echo base64_encode('synPatMarket')?>'},
				cache:false,
				success:function(data){
					_data = jQuery.parseJSON(data);
					if(_data.length>0){
						window.location = '<?php echo $auth_url;?>';
					}				
				}
			});
		}
	</script>
	<!--div class="modal fade" data-keyboard="false" data-backdrop="static" id="myModalEmailLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Gmail Login</h4>
				</div>
				<div class="modal-body">
					<p><a href="javascript:void(0);" onclick="sendRequestURL()"><img width="200" src="https://developers.google.com/accounts/images/sign-in-with-google.png"/></a></p>
				</div>
				<div class="modal-footer">					
				</div>
			</div>
		</div>
	</div-->
<?php }?>
<style>
	.dashboard-box {
		overflow-y: scroll !important;
	}
</style>

    <div class="panel-body">

		<div class="example-box-wrapper" id="review">

			<div class="table-responsive form-horizontal form-flat">


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
							<div class="row row-width">
								<div class="col-sm-4">
									<div class="form-group input-string-group">
										<label style="float:left;" class="control-label"><strong>Name of Lead:</strong></label>
										<input type="text" value="<?php echo $results[0]['litigation']->lead_name;?>" readonly="readonly" class="form-control" placeholder="" id="marketlead_name" required="required" value="">
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group text-right">
										<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">
											<input type="checkbox" value="1" name="litigation[complete]"> Lead is complete and ready to be forwarded for Execute
										</label>
										<input type="hidden" name="other[type]" value="Market"/>
										<input type="hidden" name="other[id]" id="commentID" value="<?php echo $commentID;?>"/>
			                            <input type="hidden" name="litigation[patent_data]" value="" id="marketPatentData"/>
										<button type="button" onclick="userComment();" class="btn btn-primary float-right btn-mwidth">Save</button>
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

				<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />

				<!-- <table class="table" id='record-list'>

					<tbody>

						<?php foreach($results as $data):?>

						<tr>
							<td><b>Seller:</b> </td>
							<td><b>No of Prospects:</b> </td>
							<td><b>Expected Price:</b> </td>
						</tr>
						<tr>
							<td><b>Technologies/Markets:</b> </td>
							<td><b>Prospect Name:</b> </td>
							<td></td>
						</tr>

						<?php endforeach;?>

					</tbody>

				</table> -->


				<div class="row mrg10T">
					<div class="col-xs-5" style="width:41%;">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group input-string-group">
									<label class="control-label" for="marketOwner">Seller:</label>
									<input type="text" class="form-control" value="<?php echo $data['litigation']->plantiffs_name;?>" readonly="readonly">
								</div>
							</div>
							<div class="col-sm-1"></div>
							<div class="col-sm-5">
								<div class="form-group input-string-group">
									<label class="control-label" for="marketTechnologies">Tech. / Markets:</label>
									<input type="text" class="form-control" value="<?php echo $data['litigation']->technologies;?>" readonly="readonly">
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-7" style="width:59%;">
						<div class="row">
							<div class="col-xs-6">
								<div class="col-xs-5">
									<div class="form-group input-string-group noborder">
										<label class="control-label" for="marketProspects">N. of Prospects:</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_prospects;?>" maxlength="2" readonly="readonly" style="max-width:30px; min-width:30px;">
									</div>
								</div>
								<div class="col-xs-7">
									<div class="form-group input-string-group noborder">
										<label class="control-label" for="marketExpectedPrice">Expected Price($M):</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->expected_price;?>" maxlength="4" readonly="readonly" style="max-width:38px; min-width:30px;">
									</div>
								</div>
							</div>
                        	<div class="col-xs-6">
                            	<div class="col-xs-6">
    								<div class="form-group input-string-group noborder">
    									<label for="marketNo_of_us_patents" class="control-label">N. of US Patents:</label>
    							         <input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_us_patents;?>" maxlength="2" readonly="readonly" style="max-width: 30px; min-width: 30px;">
                                         
    								</div>
    							</div>
                                <div class="col-xs-6">
    								<div class="form-group input-string-group noborder">
    									<label for="marketNo_of_non_us_patents" class="control-label">N. of Non-US Patents:</label>
                                        <input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_non_us_patents;?>" maxlength="2" readonly="readonly" style="max-width: 30px; min-width: 30px;">
                                        
    								</div>
    							</div>
							</div>
						</div>
					</div>
				</div>
                <div class="row mrg5">
			  		<div class="col-xs-12">
						<div class="form-group input-string-group nomr">
							<label class="control-label" for="marketOwner">Prospect's Names:</label>
							<input type="text" class="form-control" value="<?php echo $data['litigation']->prospects_name;?>" readonly="readonly">
						</div>
			  		</div>
			  	</div>
				<?php } else {?>

					<p class="alert">No record found!</p>

				<?php }?>
				<?php 

					if(count($results)>0){												

				?>
				<script>
					snapGlobal = '<?php echo $data['litigation']->file_url; ?>';
				</script>
                <?php echo form_open('leads/comment',array('class'=>'form-horizontal bordered-row form-flat','role'=>'form',"onsubmit"=>"return dataValidate();",'id'=>'leadComment'));?>

				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $data['litigation']->id;?>"/>
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
                
                
                <!--div class="row mrg5T">
					<div class="col-sm-10 " style="width:90%;">
						<div class="form-group">
						<label class="sr-only" for="litigationCaseName">Comment</label>
						<div class=""><strong>Notes:</strong></div>
						<div class="mrg5T"></div>
							<?php echo form_textarea(array('name'=>'other[comment]','id'=>'litigationComment','placeholder'=>'','class'=>'form-control','rows'=>'5','value'=>$commentText));?>				
						</div>
					</div>
					<div class="col-xs-2" style="width:10%;">				
						<div class="form-group" style="margin-top:23px;">
							<label class="sr-only" for="litigationCaseName">Attractive</label>						
							<select name="other[attractive]" class="form-control" required="required">
								<option value="">Attractiveness</option>
								<option <?php if($commentAttractive=='High'):?>SELECTED="SELECTED"<?php endif;?> value="High">High</option>
								<option <?php if($commentAttractive=='Medium'):?>SELECTED="SELECTED"<?php endif;?> value="Medium">Medium</option>
								<option  <?php if($commentAttractive=='Low'):?>SELECTED="SELECTED"<?php endif;?>value="Low">Low</option>
								<option  <?php if($commentAttractive=='Disapproved'):?>SELECTED="SELECTED"<?php endif;?>value="Disapproved">Disapproved</option>
							</select>			
						</div>	
					</div>
				</div-->
				<div class="row mrg10T">
					<div class="col-xs-9">
						<div class="form-group">
							<!--label for="generalTechnologies" class="control-label">
								<strong>Patent File Url:</strong>
							</label>
							<input type="textbox" class="form-control input-string" name="litigation[file_url]" id="litigationFileUrl" value="<?php echo $results[0]['litigation']->file_url;?>"/-->
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
                    jQuery("#marketPatentData").val(_ddd);
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

                    
                    
                    function bytesToSize(bytes) {
					   if(bytes == 0) return '0 Byte';
					   var k = 1000;
					   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
					   var i = Math.floor(Math.log(bytes) / Math.log(k));
					   return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
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
                                                    '<input type="hidden" name="litigation[patent_data]" value="" id="marketPatentData"/>'+
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
                    <a onclick="refreshHST()" style="text-decoration:none;" class="link-blue pull-right" href="#">
	            		<i style="font-size:16px;" class="glyph-icon icon-trash-o"></i>
	            		Clear Table
	            	</a>
                </div>
				<div class="mrg5T" style='margin-top:5px;width:100%;padding:0;' id="scrap_patent_data">
					<!-- <div class="panel panel-no-margin">
						<div class="panel-body"> -->
							<div class="example-box-wrapper">
								<div class="mrg10T mrg10B handsontable">
									
								</div>
							</div>
						<!-- </div>
					</div> -->
				</div>
                
			    

			<?php 
					}
				if(count($results)>0){								

					$litigationID = $results[0]['litigation']->id;

					

			?>

			<div class="widget-content padding">

				<div id="horizontal-form">

				

					<?php if($results[0]['litigation']->user_id!=$this->session->userdata['id']):?>
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
								<tr>
									<td><?php echo $creattorUser?></td>
									<td><?php echo $creattorCommentText?></td>
									<td><?php echo date('M d',strtotime($creattorDate));?></td>
									<td><span class="label alert <?php if($creattorAttractive=='High'):?>label-success<?php elseif($creattorAttractive=='Medium'):?>label-primary<?php elseif($creattorAttractive=='Low'):?>label-warning<?php elseif($creattorAttractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $creattorAttractive?></span></td>					
								</tr>
							</tbody>
						</table>
						</div>					
					<?php endif;?>
								

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
                                                <i class='glyph-icon icon-envelope'></i> <a href='javascript:void(0)' onclick='findThread("<?php echo $box['parent_id'] ?>",jQuery(this),1);'><?php echo $subject;?></a>
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

			<?php } ?>			

		</div>

	</div>

</div></div><?php echo $Layout->element('timeline');?></div>

<script>

_res = "";

_senddt = '<?php echo $this->session->userdata['id'];?>';

	function userComment(){
	   
        var $container = jQuery("#scrap_patent_data");
    	hst = $container.data('handsontable');
    	jQuery("#marketPatentData").val(JSON.stringify(hst.getData()));
        
        
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

	

	function record(level){

		jQuery.ajax({

			type:'POST',

			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',

			data:{token:jQuery("#token").val(),level:level,type:'Market',complete:1},

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

					_tr ='<tr>'+

							'<td><b>Seller:</b> '+_data.plantiffs_name+'</td>'+

							'<td><b>No of Prospects:</b> '+_data.no_of_prospects+'</td>'+

							'<td><b>Expected Price:</b> '+_data.expected_price+'</td>'+

						'</tr>'+	

						'<tr>'+

							'<td><b>Technologies/Markets:</b> '+_data.technologies+'</td>'+

							'<td><b>Prospect Name:</b> '+_data.prospects_name+'</td>'+

						'</tr>';
					jQuery("#marketlead_name").val(_data.lead_name);
					jQuery("#litigationFileUrl").val(_data.file_url);
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
					
					
					jQuery("#record-list>tbody").html(_tr);

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
									'<td>'+$.datepicker.formatDate('M, dd, yy', parsedDate)+'</td>'+
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
										  _showData="<i class='glyph-icon icon-envelope'></i> <a href='javascript:void(0)' onclick='findThread(\""+_d.parent_id+"\",jQuery(this),1);'>"+_d.header[h].value+"</a>";
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
								_showData="<i class='glyph-icon icon-file'> <a href='https://mail.google.com/mail/u/0/?ui=2&view=att&th="+_d.message_id+"&disp=safe&realattid="+_d.realAttachID+"' target='_BLANK'>"+_d.filename+"</a>";
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
							'</div>';
                            //alert(timeLineTable);
                        }
                       
                        
                        jQuery(".timeline-box").html(timeLineTable);
				}

			}

		});

	}

</script>