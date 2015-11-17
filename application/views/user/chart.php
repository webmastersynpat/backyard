<style>
	body {
		min-width: 0;
		overflow: auto !important;
		width: 100%;
	}
	#page-content {
	    background: #ffffff !important;
	}

	.dataTables_info {
		display: none;
	}
</style>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12" id="contentPart">
		<div class="row">
			<div class="col-md-12">
				<table id="contentPartTable" class="table"> 
					<thead>
						<tr>
							<th>Patent</th>
							<th>Action</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(count($lucidPatents)>0){
							for($i=0;$i<count($lucidPatents);$i++){
					?>
							<tr>
								<td><a href='javascript://' onclick='window.parent.getGooglePatent("<?php echo $lucidPatents[$i]->patent;?>")'><?php echo $lucidPatents[$i]->patent;?></a></td>								
								<td>
									<?php 
										if(empty($lucidPatents[$i]->file_url)):
									?>
									<a href='
										https://www.lucidchart.com/documents/external?callback=<?php echo urlencode($Layout->baseUrl."dashboard/createDocument/".$lucidPatents[$i]->patent."/".$lucidPatents[$i]->lead_id);?>'>Create File</a>
									<?php else:?>
									<a href="https://www.lucidchart.com/documents/view/<?php echo $lucidPatents[$i]->file_url?>"><i class='glyph-icon icon-eye'></i></a>
									<a href="https://www.lucidchart.com/documents/edit/<?php echo $lucidPatents[$i]->file_url?>"><i class="glyph-icon icon-edit"></i></a>
									<?php 
										endif;
									?>								
								</td>
								<td>
									<select name="choose_<?php echo $lucidPatents[$i]->id;?>">
										<option value="Empty" <?php if($lucidPatents[$i]->status=="Empty"):?> <?php echo "SELECTED='SELECTED'"; endif;?>>Empty</option>
										<option value="In Progress" <?php if($lucidPatents[$i]->status=="In Progress"):?> <?php echo "SELECTED='SELECTED'"; endif;?>>In Progress</option>
										<option value="Complete" <?php if($lucidPatents[$i]->status=="Complete"):?> <?php echo "SELECTED='SELECTED'"; endif;?>>Complete</option>
									</select>
								</td>
							</tr>
					<?php
							}
						} else {
					?>
							<tr><td colspan="3">Please import the Patent List and click Save.</td></tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript" src="<?php echo $Layout->baseUrl;?>public/widgets/datatable/datatable.js"></script>	
<script>

	___table = $('#contentPartTable')
			.DataTable({
				"searching":false,
				"autoWidth": true,
				"paging": false,
				"sScrollY": "300px",
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});

function createChartFile(object,patent){
	if(patent!=""){
		jQuery.ajax({
			url:'<?php echo $Layout->baseUrl;?>leads/filePatentsChart',
			type:'POST',
			data:{p:patent,l:leadGlobal},
			cache:false,
			success:function(data){
				_data  = jQuery.parseJSON(data);
				if(_data.error=="0" && _data.id!=""){
					object.html("<a target='_blank' href='https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/drive.file+https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&client_id=7081045131.apps.googleusercontent.com&response_type=code&access_type=offline&redirect_uri=https://www.lucidchart.com/documents/openDrive&user_id="+_data.user_id+"&state=%7B%22ids%22:%5B%22"+_data.id+"%22%5D,%22action%22:%22open%22,%22userId%22:%22"+_data.user_id+"0%22%7D'>"+_data.name+"</a>");
					window.location = 'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/drive.file+https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&client_id=7081045131.apps.googleusercontent.com&response_type=code&access_type=offline&redirect_uri=https://www.lucidchart.com/documents/openDrive&user_id='+_data.user_id+'&state=%7B%22ids%22:%5B%22'+_data.id+'%22%5D,%22action%22:%22open%22,%22userId%22:%22'+_data.user_id+'0%22%7D';
				} else {
					alert("Please try after sometime.");
				}
			}
		});
	} else {
		
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
/*
function getLucidChart(fileUrl,userID){
	if(fileUrl!="" && userID!=""){
		jQuery("#loading_spinner_heading_lucid_scrap").css('display','block');
		jQuery("#scrapGoogleData").addClass("sb-active").animate({ textIndent:0}, {
														step: function(now,fx) {
														  $(this).css('transform','translate(-350px)');
														},
														duration:'slow'
													},'linear');
		jQuery("#loading_spinner_heading_lucid_scrap").css('display','none');									
		jQuery("#scrapGooglePatent").html("<iframe src='https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/drive.file+https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&client_id=7081045131.apps.googleusercontent.com&response_type=code&access_type=offline&redirect_uri=https://www.lucidchart.com/documents/openDrive&user_id="+userID+"&state=%7B%22ids%22:%5B%22"+fileUrl+"%22%5D,%22action%22:%22open%22,%22userId%22:%22"+userID+"0%22%7D' width='100%' height='600px;'></iframe>");	
	}						
}*/
</script>