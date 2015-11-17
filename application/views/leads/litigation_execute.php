
<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
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
<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>Leads</a></li><li><a>Execution</a></li><li class='active'>From Litigation</li>");
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
    <h2>Litigation Execute</h2>
    <p>Execute list of leads from litigation.</p>
</div> -->
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
				<div id="topPart" class="form-horizontal form-flat">
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
                                    <div class="col-xs-2">
    									<div class="form-group input-string-group">
    										<label class="control-label" for="litigationNoOfPatent">Number of Patents:</label>
    										<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_patent;?>" readonly="readonly">
    									</div>
                                        
    								</div>
    								<!--div class="col-width" style="width:154px;">&nbsp;</div-->
        							</div>
								<div class="col-sm-4 col-md-4 col-xs-4">
									<div class="form-group input-string-group bigmr">
										<label class="control-label">Case Name:</label>
										<input type="text" class="form-control" id="caseName" value="<?php echo $data['litigation']->case_name;?>" readonly="readonly">
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
										<label class="control-label">Number of patents</label>
										<input type="text" class="form-control" value="<?php echo $data['litigation']->no_of_patent;?>" readonly="readonly">
									</div>
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
							<?php } else {?>
								<p class="alert">No record found!</p>
							<?php }?>
						</div>
						<!--div class="col-xs-3">
							<div class="form-flat mrg5T">
								<?php if(count($results)>0){ echo $data['litigation']->lead_attorney;} ?>
							</div>
						</div-->
					</div>
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
					<?php if(count($results)>0){?>
					<!--div class="row">
						<div class="col-xs-9">
							<div class="row">
						  		<div class="col-xs-7">
									<div class="form-group input-string-group bigmr">
									<?php if(count($results)>0){?>
										<label class="control-label">
											Name of Lead:
										</label>
										<input type="text" tabindex="11" value="<?php echo $results[0]['litigation']->lead_name;?>" id="leadName" class="form-control"  readonly="readonly">
									<?php } ?>
									</div>
						  		</div>
							</div>
						</div>
				  	</div-->
					<div class="row mrg10T">
						<div class="col-xs-9">
							<div class="form-group input-string-group">
								<label for="generalTechnologies" class="control-label">
									<strong>Patent File Url:</strong>
								</label>
								<input type="textbox" class="form-control input-string" name="litigation[file_url]" id="litigationFileUrl" value="<?php echo $results[0]['litigation']->file_url;?>"/>
							</div>						
						</div>
						<div class="col-xs-3 text-right">
							<button type="button" class="btn btn-default btn-mwidth pull-right" onclick="findPatentFromSheet()" tabindex="13">Import / Update Data</button>
							<span id="loadingLabel"></span>
						</div>
					</div>
				</div>


			<!--div class="example-box-wrapper" style="margin-top:10px;">
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
							<?php /*if(count($results)>0){?>
							<tr>
								<td><?php echo $results[0]['litigation']->userName;?></td>y								<td>
									<?php echo $results[0]['litigation']->comment;?>
								</td>
								<td></td>
							</tr>
							<?php } */?>
							<?php /*if(count($results)>0){ if(count($results[0]['comment'])>0){?>
							<?php foreach($results[0]['comment'] as $comment):?>
							<!--tr>
								<td><?php echo $comment->name?></td>
								<td><?php echo $comment->comment?></td>
								<td><?php echo date("M d",strtotime($comment->created));?></td>
								<td><span class="label alert <?php if($comment->attractive=='High'):?>label-success<?php elseif($comment->attractive=='Medium'):?>label-primary<?php elseif($comment->attractive=='Low'):?>label-warning<?php elseif($comment->attractive=='Disapproved'):?>label-danger<?php endif;?>"><?php echo $comment->attractive?></span></td>					
							</tr-->
							<?php endforeach;?>
							<?php } }*/
							?>							
						</tbody>
					</table>
				</div>
			</div-->	
					<?php } ?>
				<?php if(count($results)>0){?>
			<input type="hidden" name="token" id="token" value="<?php echo base64_encode($current_page);?>" />
				<input type="hidden" name="other[parent_id]" id="otherParentId" value="<?php echo $results[0]['litigation']->id;?>"/>
                <input type="hidden" name="litigation[patent_data]" value="" id="litgationPatentData"/>
				<?php } ?>
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
                    //alert(_ddd);
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
                <?php 
    				if(count($results)>0){		
    			?>
                 <!--  Clear Button Patent Data -->
                <div style="clear:both;" class="mrg10T clearfix">
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
                
			<div class="example-box-wrapper" id="show_data">
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
				<div class="col-sm-12 float-left" style='margin-top:5px;width:100%;padding:0;'>
					<div style='overflow-y:scroll;width:100%;height:400px;'>
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
			<div class="example-box-wrapper text-center">
				<p>
					<!--<button  type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-info btn-sm">Create a Email Proposal</button>-->
					<button  type="button" onclick="emailProposal();" class="btn btn-default btn-mwidth btn-sm">+ Email Proposal</button>
					<button class="btn btn-default btn-mwidth btn-sm" onclick="sendRequestForProposalLetter()" type="button">+ Letter Proposal</button>
					<button class="btn btn-default btn-mwidth <?php if((int)$results[0]['litigation']->status==0):?> <?php else:?> <?php endif;?> btn-sm" id="btnApproved" <?php if((int)$results[0]['litigation']->status!=0):?> disabled='disabled'  <?php endif;?>  type="button"><?php if((int)$results[0]['litigation']->status==0):?>Approved Lead<?php else:?>Approved<?php endif;?></button>
					<span style=' display:none;float:none;' id="spinner-loader" class="glyph-icon remove-border tooltip-button icon-spin-6 icon-spin mrg0A" title="Please wait...." data-original-title="icon-spin-6"></span>
					<!-- <button  type="button" onclick="" class="btn btn-primary btn-sm">Save</button> -->
				</p>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
_res = "";
	
	jQuery("#btnApproved").click(function(){
		jQuery("#spinner-loader").css('display','inline-block');		
		if(jQuery(this).attr('disabled')==undefined){
			if(parseInt(jQuery("#otherParentId").val())>0){
				jQuery.ajax({
					type:'POST',
					url:'<?php echo $Layout->baseUrl?>leads/change_status_lead',
					data:{token:jQuery("#otherParentId").val()},
					cache:false,
					success:function(response){
						jQuery("#spinner-loader").css('display','none');
						_data = jQuery.parseJSON(response);
						if(_data.rows>0){
							alert('Lead approved successfully.');
							window.location= window.location.href;
						} else {
							alert('Please try after sometime!');
						}
					}
				});
			}
		}
		
	});
	function record(level){
		jQuery.ajax({
			type:'POST', 
			url:'<?php echo $Layout->baseUrl?>leads/litigation_record',
			data:{token:jQuery("#token").val(),level:level,type:'Litigation',complete:2},
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
						jQuery("#btnApproved").html('Approved Lead').removeClass('btn-primary').addClass('btn-default');
					} else {
						jQuery("#btnApproved").attr('disabled','disabled').html('Approved').removeClass('btn-default').addClass('btn-primary');
					}
					snapGlobal = _data.file_url;
					_tr = '<div class="row mrg10T">' +
								'<div class="col-xs-9">' +
									'<div class="row">' +
										'<div class="col-xs-7">' +
											'<div class="form-group input-string-group bigmr">' +
												'<label class="control-label">Case Name:</label>' +
												'<input type="text" class="form-control" id="caseName" value="'+_data.case_name+'" readonly="readonly" style="width: 30px;">' +
											'</div>' +
											'<div class="form-group input-string-group bigmr">' +
												'<label class="control-label">Litigation Stage:</label>' +
												'<input type="text" class="form-control" value="'+_data.litigation_stage+'" readonly="readonly" style="width: 30px;">' +
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
							'</div>'+
							'<div class="row">' +
								'<div class="col-xs-9">' +
									'<div class="row">' +
								  		'<div class="col-xs-7">' +
											'<div class="form-group input-string-group bigmr">' +
												'<label class="control-label" for="generalTechnologies">' +
													'Name of Lead:' +
												'</label>' +
												'<input type="text" tabindex="11" value="'+_data.lead_name+'" id="leadName" class="form-control" readonly="readonly" style="width: 30px;">' +
											'</div>' +
								  		'</div>' +
									'</div>' +
								'</div>' +
						  	'</div>'
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
									'<button type="button" class="btn btn-default btn-mwidth pull-right" onclick="findPatentFromSheet();" tabindex="13">Import / Update Data</button>'+
									'<span id="loadingLabel"></span>'+
								'</div>'+
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
					//jQuery("#defendant").html('<img src="'+_data.defendants+'" style="width:490px;"/>');
					//jQuery("#court_docket").html('<img src="'+_data.court_docket_entries+'" style="width:490px;"/>');
					/*Comments*/
					_trData = "";
					response = response.results[0].comment;
					if(response.length>0){					
						for(i=0;i<response.length;i++){
							_label = '';
							if(response[i].attractive=="High"){
								_label = 'label-success';
							} else if(response[i].attractive=="Medium"){
								_label = 'label-warning';
							} else if(response[i].attractive=="Low"){
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
		/*_number = jQuery("#record-list>tbody>tr").eq(jQuery("#record-list>tbody>tr").length-1).find('td').eq(0).html();
		_names = _number.split('<div class="lbl">Lead Name </div>');
		if(_names.length>1){
			_names = jQuery.trim(_names[1]);
		}*/
		_names = jQuery("#leadName").val();
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>leads/letter_proposal',
			data:{name:_names,type:'Litigation',lead_id:jQuery("#otherParentId").val()},
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
	function emailProposal(){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>leads/email_proposal_history',
			data:{lead_id:jQuery("#otherParentId").val()},
			cache:false,
			success:function(res){
				
			}
		});
		window.open('https://mail.google.com/mail/?view=cm&fs=1&tf=1','_BLANK');
		window.location = window.location.href;
	}
</script>
</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>