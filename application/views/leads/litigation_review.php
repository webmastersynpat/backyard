<div class="row"><?php echo $Layout->element('task');?><div class="col-md-8 col-sm-8 col-xs-8" id="contentPart"><script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datepicker/datepicker.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs-ui/tabs.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

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
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Review</a></li><li class='active'>From Litigation</li>");
});
</script>
<script type="text/javascript">

    /* Datatables hide columns */
	var ___table,___table1,___table2,___table3,___table4;
    $(document).ready(function() {
		
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

   

</script>

<style>
	.dashboard-box {
		overflow-y: scroll !important;
	}
</style>

<!-- <div id="page-title">
    <h2>Litigation Review</h2>
    <p>Review of list of leads from litigation.</p>
</div> -->
<div class="panel dashboard-box">
    <div class="panel-body">
		<div class="example-box-wrapper"  id="review">
			<div class="table-responsive">
				<!-- <div class="btn-group pull-right" style='margin-bottom:5px;'> -->
				<div class="row row-width">
					<?php
						$previous = "disabled='disabled'";
						if(isset($current_page)>1){
							$previous ="";
						}
						$next = "disabled='disabled'";
						if($total_rows>1 && ($current_page+1)<=$no_of_pages){
							$next ="";
						}
					?>
					<!--<button type="button" id="prev" onclick="record('prev');" <?php echo $previous;?> class="glyph-icon tooltip-button demo-icon icon-angle-left"><i class="fa fa-chevron-left"></i></button>
					<button type="button" id="next" onclick="record('next');" <?php echo $next;?> class="glyph-icon tooltip-button demo-icon icon-angle-right"><i class="fa fa-chevron-right"></i></button>-->

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
				<?php echo form_open('leads/comment',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>'leadComment' ,"onsubmit"=>"return dataValidate()"));?>

					<div id="topPart">
						<div class="row mrg10T">
							<div class="col-xs-9">
								<?php foreach($results as $data):?>
								<div class="row">
                                    <div class="row row-width mrg10T">
        								<div class="col-xs-4">
        									<div class="form-group input-string-group">
        										<label class="control-label" for="litigationLinkToPacer"><strong>Lead Name:</strong></label>
        										<?php echo form_input(array('name'=>'litigation[lead_name]','value'=>$data['litigation']->lead_name, 'required'=>'required','id'=>'litigationleadName','placeholder'=>'','class'=>'form-control input-string is-big'));?>
        									</div>
        								</div>
        								<div class="col-xs-4">
        									<div class="row row-width">
        										<div class="col-width" style="width:280px;">
        											<div class="row row-width">
        												<div class="col-width" style="width: 100px;">
        													<div class="form-group input-string-group">
        														<label for="marketProspects" class="control-label">Prospects:</label>
        														<?php echo form_input(array('name'=>'litigation[no_of_prospects]','id'=>'litigationProspects','value' =>$data['litigation']->no_of_prospects , 'placeholder'=>'','class'=>'form-control', 'maxlength'=>'2'));?>
        													</div>
        												</div>
        												<div class="col-width" style="width: 172px;">
        													<div class="form-group input-string-group">
        														<label for="marketExpectedPrice" class="control-label">Expected Price($M):</label>
        														<?php echo form_input(array('name'=>'litigation[expected_price]','id'=>'litigationExpectedPrice','value' =>$data['litigation']->expected_price ,'placeholder'=>'','class'=>'form-control', 'maxlength'=>'4'));?>
        													</div>
        												</div>
        											</div>
        										</div>
        									</div>
        								</div>
                                        <div class="col-xs-4">
        									<div class="form-group input-string-group">
        										<label class="control-label" for="litigationNoOfPatent">Number of Patents:</label>
        										<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_patent;?>" readonly="readonly">
        									</div>
                                            
        								</div>
        								<!--div class="col-width" style="width:154px;">&nbsp;</div-->
        							</div>
									<div class="col-sm-4 col-md-4 col-xs-4">
										<div class="form-group input-string-group bigmr">
                                            <input type="hidden" name="lead_id" value="<?php echo $data['litigation']->id; ?>" id="lead_id"/>
											<label class="control-label">Case Name:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->case_name;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label">Litigation Stage:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->litigation_stage;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label">Market/Industry:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->market_industry;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label">Case Type:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->case_type;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label">Case Number:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->case_number;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label">Cause:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->cause;?>" readonly="readonly">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-xs-4">
										
										<div class="form-group input-string-group">
											<label class="control-label">Filling Date:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->filling_date;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group">
											<label class="control-label">Number of active defandant:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->active_defendants;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group">
											<label class="control-label">Number of original defandant:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->original_defendants;?>" readonly="readonly">
										</div>
										<div class="form-group input-string-group">
											<label class="control-label">Court:</label>
											<input type="text" class="form-control" value="<?php echo $data['litigation']->court;?>" readonly="readonly">
										</div>
										<div class="form-group text-overflow">
											<label class="control-label">Link to Pacer:</label>
											<a href="<?php echo $data['litigation']->link_to_pacer;?>" target="_blank"><?php echo $data['litigation']->link_to_pacer;?></a>
										</div>
                                        <div class="form-group input-string-group">
        									<label class="control-label" for="litigationLinkToPacer">Link to RPX:</label>
        									<?php echo form_input(array('name'=>'litigation[link_to_rpx]','id'=>'litigationLinkToRPX','placeholder'=>'','class'=>'form-control','value' => $data['litigation']->link_to_rpx));?>
        								</div>
									</div>
                                    <div class="col-sm-4 col-md-4 col-xs-4" style="padding-right: 7px;">
    								<div class="row">
    									<div class="col-xs-6">
    									<?php echo form_textarea(array('name'=>'litigation[lead_attorney]','id'=>'litigationLeadAttorney','placeholder'=>'Plaintiff\'s Lead Attorney','value' =>$data['litigation']->lead_attorney ,'class'=>'form-control','rows'=>3,'cols'=>29,'style'=>'height:189px !important;'));?>
    									</div>
    									<div class="col-xs-6">
    										<div id="litigation_doc_list" class="panel google-box-list" style='height:189px;overflow-y:scroll;overflow-x:hidden;'>
    											
    										</div>
    									</div>
    								</div>
							     </div>
								</div>
                                <?php endforeach;?>
                                <div class="row row-width mrg5T">
        							<!-- <div class="col-xs-10" style="width:90%;">
        								<div class="form-group" style='width:100%;'>
        									<label class="sr-only" for="litigationLinkToPacer">Comment</label>							
        									<?php //echo form_textarea(array('name'=>'litigation[comment]','id'=>'litigationComment','placeholder'=>'Notes','class'=>'form-control','rows'=>4,'cols'=>29));?>	
        								</div>
        							</div> -->
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
										  //echo 'A';
											foreach($results[0]['comment'] as $comment):
                                            //echo 'B';
												if($comment->user_id==$this->session->userdata['id']){
											//	    echo 'C';
													$commentID = $comment->id;
													$comment1 = $comment->comment1;
                                                    $comment2 = $comment->comment2;
                                                    $comment3 = $comment->comment3;
													$commentAttractive = $comment->attractive;
													$commentUser = $comment->user_id;
												}
												if($results[0]['litigation']->user_id==$comment->user_id){
												//    echo 'D';
													$comment1 = $comment->comment1;
                                                    $comment2 = $comment->comment2;
                                                    $comment3 = $comment->comment3;
													$creattorAttractive = $comment->attractive;
													$creattorUser = $comment->name;
													$creattorDate = $comment->created;
												}
											endforeach;
										}
									}
								    ?>
        							<div class="col-sm-4">
        								<label class="control-label" for="marketProspectsName">Are there >10 potential licensees? Who?</label>
                                        <textarea class="form-control" name="comment[comment1]" id="commentComment1" rows="4" cols="15"><?php echo $comment1; ?></textarea>
        							</div>
        							<div class="col-sm-4">
        								<label class="control-label" for="marketProspectsName">Will licensees want to pay the expected fee? Why?</label>
        								<textarea class="form-control" name="comment[comment2]" id="commentComment2" rows="4" cols="15"><?php echo $comment2; ?></textarea>
        							</div>
        							<div class="col-sm-4">
        								<label class="control-label" for="marketProspectsName">Seller's concerns + Your general observations</label>
                                        <textarea class="form-control" name="comment[comment3]" id="commentComment3" rows="4" cols="15"><?php echo $comment3; ?></textarea>
        							</div>
        						</div>
							</div>
						</div>
						<!--div class="row">
							<div class="col-sm-10" style="width:90%;">
								<?php 
									if(count($results)>0) {
										$litigationID = $results[0]['litigation']->id;
								?>
								<div class="widget-content padding">
									<div id="horizontal-form">
										<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $litigationID;?>"/>
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

										<div class="row mrg5T">
											<div class="col-sm-12 " style=''>
												<div class="form-group">
												<label class="sr-only" for="litigationCaseName">Comment</label>
												<div><strong>Notes:</strong></div>
												<div class="mrg5T"></div>
													<?php echo form_textarea(array('name'=>'other[comment]','id'=>'litigationComment','placeholder'=>'','class'=>'form-control','rows'=>'5','value'=>$commentText));?>				
												</div>
											</div>
										</div>
										<div class="col-sm-8">
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="col-sm-2" style="width:10%;">
								<div class="form-group" style="margin-top:29px;">
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
                                    <a href="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank">Open Spreadsheet in Google Drive</a>
									<!--label for="generalTechnologies" class="control-label">
										<strong>Patent File Url:</strong>
									</label>
									<!--input type="textbox" class="form-control input-string" name="litigation[file_url]" id="litigationFileUrl" value="<?php echo $results[0]['litigation']->file_url;?>"/-->
                                    <input type="hidden" name="litigation[file_url]" id="litigationFileUrl" value="https://docs.google.com/a/synpat.com/spreadsheets/d/1CYZa-CJTxb0njAWYNX5LVuhaa5Skytk8pTvfwvfnuCc/edit#gid=370401912" target="_blank" class="btn"/>
								</div>						
							</div>
							<div class="col-xs-3 text-right">
								<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet()" tabindex="13">Import / Update Data</button>
								<span id="loadingLabel"></span>
							</div>
						</div>
						<div class="row mrg10T">
							<div class="col-sm-5">
								<!--div class="form-group">
									<label style="float:left;" class="control-label"><strong>Name of Lead:</strong></label>
									<input type="text" style="float: left; margin-left:5px; width: 66.6666%;" value="<?php echo $results[0]['litigation']->lead_name;?>" readonly="readonly" class="form-control input-string" placeholder="" id="marketlead_name" required="required" value="">
								</div-->
							</div>
							<div class="col-sm-7">
								<div class="form-group text-right">
									<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">
										<input type="checkbox" value="1" name="litigation[complete]"> Lead is complete and ready to be forwarded for Execute
										<input type="hidden" class="form-control" id="litigationScrapperData">
									</label>
									<input type="hidden" name="other[type]" value="Litigation"/>
									<input type="hidden" name="other[id]" id="commentID" value="<?php echo $commentID;?>"/>
                                    <input type="hidden" name="litigation[patent_data]" value="<?php echo $results[0]['litigation']->patent_data?>" id="litgationPatentData"/>
									<button type="button" onclick="userComment();" class="btn btn-primary float-right">Save</button>							
								</div>
							</div>
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
								url:'<?php echo $Layout->baseUrl?>index.php/leads/googleSpreadSheet',
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
	            	<a href="#" class='link-blue pull-right' style='text-decoration:none;' onclick="refreshHST()">
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
                
					<div class="row  mrg5B mrg10T">
						<div class="col-xs-12">
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
											<td><?php echo $creattorCommentText?></td>
											<td><?php echo date('M d',strtotime($creattorDate));?></td>
											<td><span class="label alert <?php if($creattorAttractive=='High'):?>label-success<?php elseif($creattorAttractive=='Medium'):?>label-primary<?php elseif($creattorAttractive=='Low'):?>label-warning<?php elseif($creattorAttractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $creattorAttractive?></span></td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php endif;?>
						</div>
					</div>

				<?php echo form_close();?>
				<?php } else {?>
					<p class="alert">No record found!</p>
				<?php }?>
			</div>
			
			<?php 

					if(count($results)>0){
			?>
			<div class="widget-content padding" id="show_data">
				<div class="row">
				<?php 
					if(!empty($results[0]['litigation']->defendants) || !empty($results[0]['litigation']->court_docket_entries)):
				?>
				<div class="col-sm-12 noPadding" style='overflow-y:scroll;overflow:x:none;height:400px;'>
					<div class="row">
						<div class="col-sm-6 noPadding" id="defendant">
							<img src="<?php echo $results[0]['litigation']->defendants?>" style='width:100%;'>
						</div>
						<div class="col-sm-6" id="court_docket">
							<img src="<?php echo $results[0]['litigation']->court_docket_entries?>"  style="width:100%; ">
						</div>
					</div>
				</div>
				<?php else:
					$outPut = $results[0]['litigation']->scrapper_data;
					if(!empty($outPut)){
						$outPut = json_decode($results[0]['litigation']->scrapper_data);
						
					}
				?>
				<div class="col-sm-12 float-left" style='margin-top:5px;width:100%;'>
					<div class="row">
						<div class='col-sm-12' id="tablesOtherData">
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
												<?php 
													if(is_object($outPut)){
														
														if(isset($outPut->output->Tables)){
															$dataOutPut = $outPut->output->Tables;
															if(isset($dataOutPut->{'1'}) && count($dataOutPut->{'1'})>0){

																for($i=0;$i<count($dataOutPut->{'1'});$i++){
												?>
												<tr>
													<td><?php echo $dataOutPut->{'1'}[$i][0];?></td>
													<td><?php echo $dataOutPut->{'1'}[$i][1];?></td>
													<td><?php echo $dataOutPut->{'1'}[$i][2];?></td>
													<td><?php echo $dataOutPut->{'1'}[$i][3];?></td>
												</tr>
												<?php
																}
															}
														}																
													}
												?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane " id="tab2">
										<table id="datatable-hide-columns1" class="table table-striped table-bordered " cellspacing="0" width="100%">
											<thead>
											<tr>
												<th>Date Filed</th>
												<th>Defandants</th>
												<th>Litigation</th>
												<th>Termination Date</th>
											</tr>
											</thead>
											<tbody> 
												<?php 
													if(is_object($outPut)){
														if(isset($outPut->output->Tables)){
															$dataOutPut = $outPut->output->Tables;
															if(isset($dataOutPut->{'2'}) && count($dataOutPut->{'2'})>0){
																for($i=0;$i<count($dataOutPut->{'2'});$i++){
												?>
												<tr>
													<td><?php echo $dataOutPut->{'2'}[$i][0];?></td>
													<td><?php echo $dataOutPut->{'2'}[$i][1];?></td>
													<td><?php echo $dataOutPut->{'2'}[$i][2];?></td>
													<td><?php echo $dataOutPut->{'2'}[$i][3];?></td>
												</tr>
												<?php
																}
															}
														}																
													}
												?>
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
												<?php 
													if(is_object($outPut)){
														if(isset($outPut->output->Tables)){
															$dataOutPut = $outPut->output->Tables;
															if(isset($dataOutPut->{'3'}) && count($dataOutPut->{'3'})>0){
																for($i=0;$i<count($dataOutPut->{'3'});$i++){
												?>
												<tr>
													<td><?php echo $dataOutPut->{'3'}[$i][0];?></td>
													<td><?php echo $dataOutPut->{'3'}[$i][1];?></td>
													<td><?php echo $dataOutPut->{'3'}[$i][2];?></td>
												</tr>
												<?php
																}
															}
														}																
													}
												?>
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
												<?php 
													if(is_object($outPut)){
														if(isset($outPut->output->Tables)){
															$dataOutPut = $outPut->output->Tables;
															if(isset($dataOutPut->{'4'}) && count($dataOutPut->{'4'})>0){
																for($i=0;$i<count($dataOutPut->{'4'});$i++){
												?>
												<tr>
													<td><?php echo $dataOutPut->{'4'}[$i][0];?></td>
													<td><?php echo $dataOutPut->{'4'}[$i][1];?></td>
													<td><?php echo $dataOutPut->{'4'}[$i][2];?></td>
												</tr>
												<?php
																}
															}
														}																
													}
												?>
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
												<?php 
													if(is_object($outPut)){
														if(isset($outPut->output->docket_entries_table)){
															$dataOutPut = $outPut->output->docket_entries_table;
															if(isset($dataOutPut) && count($dataOutPut)>0){
																for($i=0;$i<count($dataOutPut);$i++){
												?>
												<tr>
													<td><?php echo $dataOutPut[$i][1];?></td>
													<td><?php echo $dataOutPut[$i][2];?></td>
													<td><?php echo $dataOutPut[$i][3];?></td>
													<td><?php echo $dataOutPut[$i][4];?></td>
												</tr>
												<?php
																}
															}
														}																
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<?php endif;?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
_res = "";
_senddt = '<?php echo $this->session->userdata['id'];?>';
	function userComment(){
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
			data:{token:jQuery("#token").val(),level:level,type:'Litigation',complete:1},
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
					snapGlobal = _data.file_url;
					_tr = '<div class="row mrg10T">' +
							'<div class="col-xs-9">' +
								'<div class="row">' +
									'<div class="col-xs-7">' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Case Name:</label>' +
											'<input type="text" class="form-control" value="'+_data.case_name+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Litigation Stage:</label>' +
											'<input type="text" class="form-control" value="'+$.trim(_data.litigation_stage.toString())+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Market/Industry:</label>' +
											'<input type="text" class="form-control" value="'+_data.market_industry+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Case Type:</label>' +
											'<input type="text" class="form-control" value="'+_data.case_type+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Case Number:</label>' +
											'<input type="text" class="form-control" value="'+_data.case_number+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group bigmr">' +
											'<label class="control-label">Cause:</label>' +
											'<input type="text" class="form-control" value="'+_data.cause+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
									'</div>' +
									'<div class="col-xs-5">' +
										'<div class="form-group input-string-group">' +
											'<label class="control-label">Number of patents</label>' +
											'<input type="text" class="form-control" value="'+_data.no_of_patent+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group">' +
											'<label class="control-label">Filling Date:</label>' +
											'<input type="text" class="form-control" value="'+_data.filling_date+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group">' +
											'<label class="control-label">Number of active defandant:</label>' +
											'<input type="text" class="form-control" value="'+_data.active_defendants+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group">' +
											'<label class="control-label">Number of original defandant:</label>' +
											'<input type="text" class="form-control" value="'+_data.original_defendants+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group input-string-group">' +
											'<label class="control-label">Court:</label>' +
											'<input type="text" class="form-control" value="'+_data.court+'" readonly="readonly" style="width: 30px;">' +
										'</div>' +
										'<div class="form-group text-overflow">' +
											'<label class="control-label">Link to Pacer:</label> ' +
											'<a href="'+_data.link_to_pacer+'" target="_blank">'+_data.link_to_pacer+'</a>' +
										'</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="col-xs-3">' +
								'<div class="form-flat mrg5T">' +
									_data.lead_attorney +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="row">' +
							'<div class="col-sm-10" style="width:90%;">' +
								'<div class="widget-content padding">' +
									'<div id="horizontal-form">' +
										'<input type="hidden" name="other[parent_id]" id="otherParentId" value="'+_data.id+'"/>' +
										'<div class="row mrg5T">' +
											'<div class="col-sm-12 " style="">' +
												'<div class="form-group">' +
												'<label class="sr-only" for="litigationCaseName">Comment</label>' +
												'<div><strong>Notes:</strong></div>' +
												'<div class="mrg5T"></div>' +
													'<textarea class="form-control" placeholder="" id="litigationComment" rows="5" cols="40" name="other[comment]"></textarea>' +
												'</div>' +
											'</div>' +
										'</div>' +
										'<div class="col-sm-8">' +
										'</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="col-sm-2" style="width:10%;">' +
								'<div class="form-group" style="margin-top:29px;">' +
									'<label class="sr-only" for="litigationCaseName">Attractive</label>' +
									'<select name="other[attractive]" class="form-control" required="required">' +
										'<option value="">Attractiveness</option>'+
										'<option value="High">High</option>'+
										'<option value="Medium">Medium</option>'+
										'<option value="Low">Low</option>'+
										'<option value="Disapproved">Disapproved</option>'+
									'</select>' +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="row mrg10T">'+
							'<div class="col-xs-9">'+
								'<div class="form-group">'+
									'<label for="generalTechnologies" class="control-label">'+
										'<strong>Patent File Url:</strong>'+
									'</label>'+
									'<input type="textbox" class="form-control input-string" name="litigation[file_url]" id="litigationFileUrl" value="'+_data.file_url+'"/>'+
								'</div>'+						
							'</div>'+
							'<div class="col-xs-3 text-right">'+
								'<button type="button" class="btn btn-primary pull-right" onclick="findPatentFromSheet();" tabindex="13">Import Data</button>'+
								'<span id="loadingLabel"></span>'+
							'</div>'+
						'</div>'+
						'<div class="row mrg10T">' +
							'<div class="col-sm-5">' +
								'<div class="form-group">' +
									'<label style="float:left;" class="control-label"><strong>Name of Lead:</strong></label>' +
									'<input type="text" style="float: left; margin-left:5px; width: 66.6666%;"  readonly="readonly" class="form-control input-string" placeholder="" id="marketlead_name" required="required" value="'+_data.lead_name+'">' +
								'</div>' +
							'</div>' +
							'<div class="col-sm-7">' +
								'<div class="form-group text-right">' +
									'<label style="display: inline-block; margin-top: 7px; margin-right: 30px;">' +
										'<input type="checkbox" value="1" name="litigation[complete]"> Lead is complete and ready to be forwarded for Execute' +
										'<input type="hidden" class="form-control" id="litigationScrapperData">' +
									'</label>' +
									'<input type="hidden" name="other[type]" value="Litigation"/>' +
									'<input type="hidden" name="other[id]" id="commentID" value="0"/>' +
									'<button type="button" onclick="userComment();" class="btn btn-primary float-right">Save</button>' +
								'</div>' +
							'</div>' +
						'</div>';					

					jQuery("#topPart").html(_tr);
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
					if(_data.defendants=="" && _data.court_docket_entries==""){
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
                        var timeLineTable = "";
                        _dataTimeLine = response.timeLine;	
                       
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
                        
                       
                      //  jQuery("#activity").html();
                        	
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
								jQuery("select[name='other[attractive]']").find('option').each(function(){ 
									if(jQuery(this).attr('value')==_commentAttractive){
										jQuery(this).attr('SELECTED','SELECTED');
									} else {
										jQuery(this).removeAttr('SELECTED');
									}
								});
							}
							if(response[i].user_id==_litigation.user_id){							
								var parsedDate = $.datepicker.parseDate('yy-mm-dd', response[i].created.split(' ')[0]);
								_tr = '<tr>'+
									'<td >'+response[i].name+'</td>'+
									'<td>'+response[i].comment+'</td>'+
									'<td>'+$.datepicker.formatDate('M, dd, yy', parsedDate)+'</td>'+
									'<td><span class="label alert label-success">'+response[i].attractive+'</span></td>'+					
								'</tr>';
								jQuery("#comment-list>tbody").html(_tr);
							}							
						}
					}					
				}
			}
		});
	}
</script></div><?php echo $Layout->element('timeline');?></div>