<style>body{overflow:auto!important}#page-content{background:#ffffff!important} .btn-opp-block {width: 100%;}</style>
<script type="text/javascript" src="<?php echo $Layout->baseUrl?>public/js-init/moment-with-locales.min.js"></script>
<script type="text/javascript">/* WYSIWYG editor */jQuery(document).ready(function(){$(function(){$(".wysiwyg-editor").summernote({fontsize:'15',height:350,toolbar:[["style",["bold","italic","underline","clear"]],["fontsize",["fontsize"]],["color",["color"]],["para",["ul","ol","paragraph"]],["height",["height"]],]});$(".wysiwyg-editor1").summernote({fontsize:'15',height:150,toolbar:[["style",["bold","italic","underline","clear"]],["fontsize",["fontsize"]],["color",["color"]],["para",["ul","ol","paragraph"]],["height",["height"]],]})})});</script>
<div class="row"> 
<?php if((int)$lead_data->status>=0):?>
<div style='overflow-x: hidden; overflow-y: auto; height: auto;'>
<div class="pad10A">
<div class="col-md-12 col-sm-12 col-xs-12" id="contentPart">

<style id="tempStyles">.dashboard-box{overflow-y:scroll!important}.dataTables_filter>label{border-bottom:1px solid #ddd;float:left!important;margin-bottom:10px;margin-top:-5px}.dataTables_filter>label>label{background:none repeat scroll 0 0 #fff;float:left;padding-bottom:5px;padding-top:0!important;position:relative;top:8px}.dataTables_filter>label>input{border-bottom:none!important;height:19px;margin-top:5px}.dataTables_wrapper{margin-bottom:0!important}.dataTables_scrollBody{height:554px!important}.wtHolder.ht_master{height:auto!important}input[type=checkbox]{margin:0}#embedDataShare_wrapper .dataTable{border:0}.todo-list-custom .btn{width:100%!important}#datatable-contacts_wrapper .dataTables_scrollBody{overflow-x:hidden!important}#uploadFileImage{margin-top:0!important}.tab-pane{display:block}</style>
<script>
leadGlobal = "<?php echo $lead_id; ?>";
_token = "<?php echo $lead_id; ?>";
leadNameGlobal =  "";
<?php  
	if(count($lead_data)>0){
?>
	leadNameGlobal = '<?php echo $lead_data->lead_name?>';
<?php 
	}
?>
var refreshDataTableSizeInterval=null,oppMainPanelBoxWidth=0;function refreshDataTableSize(){$(".sorting_desc, .sorting_asc").trigger("click")}function mainTabsTabSelect(a){jQuery("#mainTabs > li").removeClass("high");if(a.hasClass("is-first")){jQuery("#mainTabs > li.is-first").addClass("high")}else{if(a.hasClass("is-second")){jQuery("#mainTabs > li.is-first").addClass("high");jQuery("#mainTabs > li.is-second").addClass("high")}else{if(a.hasClass("is-third")){jQuery("#mainTabs > li.is-first").addClass("high");jQuery("#mainTabs > li.is-second").addClass("high");jQuery("#mainTabs > li.is-third").addClass("high")}}}}jQuery(document).ready(function(){jQuery(".breadcrumb").html("<li><a>Opportunity</a></li><li class='active'>Working on Opportunity</li>");var a=40;jQuery("#store_small_desc").on("change keyup paste",function(d){var g=$.trim($("#store_small_desc").val()),f=g.replace(/\s+/gi," ").split(" ").length,c=g.length;if(f<a){return}if(f==a){d.preventDefault()}else{if(f>a){alert("Only "+a+" charcters allowed");this.value=this.value.substring(0,c)}}});jQuery("#tabDocumentsPatents").on("click",function(){jQuery("#mainTabs > li").removeClass("active");$(this).tab("show");setTimeout(function(){refreshDataTableSize()},300);return false});jQuery("#mainTabs > li > a").on("mousedown",function(){var c=jQuery(this).parent();mainTabsTabSelect(c)});jQuery("#mainTabs > li > a").on("click",function(){$(this).tab("show");setTimeout(function(){refreshDataTableSize()},300);return false});setTimeout(function(){refreshDataTableSize();refreshDataTableSizeInterval=setInterval(function(){if($("#page-content > .row").width()!==oppMainPanelBoxWidth){refreshDataTableSize();oppMainPanelBoxWidth=$("#page-content > .row").width()}},700)},1000);$("#activity-btn, #task-btn").on("click",function(){clearInterval(refreshDataTableSizeInterval);refreshDataTableSize();refreshDataTableSizeInterval=setInterval(function(){if($("#page-content > .row").width()!==oppMainPanelBoxWidth){refreshDataTableSize();oppMainPanelBoxWidth=$("#page-content > .row").width()}},700)});mainTabsTabSelect(jQuery("#mainTabs > li.active"));$("#uploadFileImage").on("shown.bs.modal",function(c){setTimeout(function(){b()},500)});$(window).on("resize",function(){var c=$("#uploadFileImage");if(c.length&&c.is(":visible")){b()}});function b(){var d=$("#uploadFileImage").find(".modal-body"),c=$(window).height()-153;if(d.length){d.height(c)}}});
</script>
<?php
foreach($timeline as $key => $val)
{
    if(trim($val->message) == "PPA drafted"){
        $draft_ppa_date = $val->create_date;
    } elseif(trim($val->message) == "PPA executed"){
        $executed_ppa = $val->create_date;
    } elseif(trim($val->message) == "Send request to CIPO for uploading damages report."){
        $upload_damage_report = $val->create_date;
    } elseif(trim($val->message) == "Send request to CIPO to start work on DD"){
        $cipo_start_work_dd = $val->create_date;
    } elseif(trim($val->message) == "NDA created"){
        $nda_created = $val->create_date;
    } elseif(trim($val->message) == "NDA approved"){
        $nda_approved = $val->create_date;
    } elseif(trim($val->message) == "CIPO approved NDA"){
        $nda_approved = $val->create_date;
    } elseif(trim($val->message) == "Send request to CIPO for NDA approval"){
        $send_req_nda_approval = $val->create_date;
    } elseif(trim($val->message) == "EOU confirmed"){
        $eou_confirmed = $val->create_date;
    } elseif(trim($val->message) == "NDA shared with Sellers"){
        $nda_shared = $val->create_date;
    } elseif(trim($val->message) == "Insert list of Assets"){
		$list_of_assets_send = $val->create_date;
	}  elseif(trim($val->message) == "CIPO Approved assets."){
		$list_of_assets_approve = $val->create_date;
	}elseif(trim($val->message) == "Drafted PLA"){
		$draft_pla = $val->create_date;
	}elseif(trim($val->message) == "Drafted Participant"){
		$draft_participant = $val->create_date;
	}    
}
	$active = 0;
	if(count($lead_stage)>0){
		$active = (int) $lead_stage->stage;
	}
	$opportunityType ="";
	$opportunityName = "";
	$sellerName = "";
	if(count($lead_data)>0){
		$opportunityType = $lead_data->type;
		$sellerName = $lead_data->plantiffs_name;
		$opportunityName = "";
		switch($lead_data->type){
			case 'Litigation':
				$opportunityName = $lead_data->case_name." - ".$lead_data->case_number;
			break;
			case 'Market':
			case 'General':
			case 'SEP':
				$opportunityName = $lead_data->plantiffs_name." - ".$lead_data->relates_to." - ".$lead_data->portfolio_number;
			break;
			default:
				$opportunityName = $lead_data->plantiffs_name;
			break;
		}
	}
	$optionExpirationDate = "";
	$imageLeft ="";
	$imageMiddle ="";
	$imageRight ="";
	$imageFour ="";
	$imageFive ="";
	$imageSix ="";
	$imageSeven ="";
	$imageEight ="";
	$imageNine ="";
	$imageTen ="";
	$imageEleven ="";
	$imageTwelve ="";
	$sellerAskingPrice="";
	
	if(!empty($acquisition['acquisition']->option_expiration_data)){
		$optionExpirationDate = $acquisition['acquisition']->option_expiration_data;
	}
	if(!empty($acordion_text->License->file_left)){
		$image = pathinfo($acordion_text->License->file_left);
		$imageLeft = $image['basename'];
	}
	if(!empty($acordion_text->License->file_middle)){
		$image = pathinfo($acordion_text->License->file_middle);
		$imageMiddle = $image['basename'];
	}
	if(!empty($acordion_text->License->file_right)){
		$image = pathinfo($acordion_text->License->file_right);
		$imageRight = $image['basename'];
	}
	if(!empty($acordion_text->License->file_four)){
		$image = pathinfo($acordion_text->License->file_four);
		$imageFour = $image['basename'];
	}
	if(!empty($acordion_text->License->file_five)){
		$image = pathinfo($acordion_text->License->file_five);
		$imageFive = $image['basename'];
	}
	if(!empty($acordion_text->License->file_six)){
		$image = pathinfo($acordion_text->License->file_six);
		$imageSix = $image['basename'];
	}
	if(!empty($acordion_text->License->file_seven)){
		$image = pathinfo($acordion_text->License->file_seven);
		$imageSeven = $image['basename'];
	}
	if(!empty($acordion_text->License->file_eight)){
		$image = pathinfo($acordion_text->License->file_eight);
		$imageEight = $image['basename'];
	}
	if(!empty($acordion_text->License->file_nine)){
		$image = pathinfo($acordion_text->License->file_nine);
		$imageNine = $image['basename'];
	}
	if(!empty($acordion_text->License->file_ten)){
		$image = pathinfo($acordion_text->License->file_ten);
		$imageTen = $image['basename'];
	}
	if(!empty($acordion_text->License->file_eleven)){
		$image = pathinfo($acordion_text->License->file_eleven);
		$imageEleven = $image['basename'];
	}
	if(!empty($acordion_text->License->file_twelve)){
		$image = pathinfo($acordion_text->License->file_twelve);
		$imageTwelve = $image['basename'];
	}
	if(!empty($acquisition['acquisition']->seller_asking_price)){
		$sellerAskingPrice = $acquisition['acquisition']->seller_asking_price;
	}
	$pla = "";
	$ppa = "";
	$rtp = "";
	$ppp = "";
	$sla = "";
	$damage = "";
	$syndication = "";
	if(!empty($acquisition['acquisition']->pla)){
		$pla = $acquisition['acquisition']->pla;
	} else if(count($acordion_text)>0){
		$pla = $acordion_text->License->purchase_license;
	}
	if(!empty($acquisition['acquisition']->ppa)){
		$ppa = $acquisition['acquisition']->ppa;
	} else if(count($acordion_text)>0){
		$ppa = $acordion_text->License->purchase_file;
	}
	if(!empty($acquisition['acquisition']->rtp)){
		$rtp = $acquisition['acquisition']->rtp;
	} else if(count($acordion_text)>0){
		$rtp = $acordion_text->License->purchase_buy;
	}
	if(!empty($acquisition['acquisition']->ppp)){
		$ppp = $acquisition['acquisition']->ppp;
	} else if(count($acordion_text)>0){
		$ppp = $acordion_text->License->program_policies_file;
	}
	if(!empty($acquisition['acquisition']->sla)){
		$sla = $acquisition['acquisition']->sla;
	} else if(count($acordion_text)>0){
		$sla = $acordion_text->License->strategic_license_agreement;
	}
	if(!empty($acquisition['acquisition']->damage)){
		$damage = $acquisition['acquisition']->damage;
	}
	if(!empty($acquisition['acquisition']->syndication)){
		$syndication = $acquisition['acquisition']->syndication;
	}
	
?>
<script type="text/javascript">/*<![CDATA[*/$(function(){$(".bootstrap-datepicker, .input-group.date").datepicker({format:"yyyy-mm-dd"})});function uploadDocketFileFolder(){jQuery('#spinner-loader-docket-file-folder').css('display','inline-block');jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl;?>opportunity/getStoreDIRList",cache:false,success:function(data){jQuery('#spinner-loader-docket-file-folder').css('display','none');}});};function startUpdatePatent(){if(_token==0||_token==""){alert("Server busy, Please refresh our page.")}else{jQuery("#spinner-loader-patent").css("display","inline-block");jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl;?>opportunity/startUpdatePatent",data:{token:_token},cache:false,success:function(a){jQuery("#spinner-loader-patent").css("display","none");_data=jQuery.parseJSON(a);if(_data>0){jQuery("#data_patent_update").html('<p class="label-after-btn"><span>Patents updated</span></p>')}else{alert("Server busy. Please refresh your page.")}}})}}function startInventionDataList(){if(_token==0||_token==""){alert("Server busy, Please refresh our page.")}else{jQuery("#spinner-loader-invention").css("display","inline-block");jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl;?>opportunity/startInventionData",data:{token:_token},cache:false,success:function(a){jQuery("#spinner-loader-invention").css("display","none");startUpdateIllustration();_data=jQuery.parseJSON(a);if(_data>0){jQuery("#data_invention").html('<p class="label-after-btn"><span>Invention data updated</span></p>')}else{alert("Server busy. Please refresh your page.")}}})}}function startUpdateIllustration(){if(_token==0||_token==""){alert("Server busy, Please refresh our page.")}else{jQuery("#spinner-loader-illustration").css("display","inline-block");jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl;?>opportunity/updateIllustration",data:{token:_token},cache:false,success:function(a){jQuery("#spinner-loader-illustration").css("display","none");_data=jQuery.parseJSON(a);if(_data>0){jQuery("#data_illustration").html('<p class="label-after-btn"><span>Illustration data updated</span></p>')}else{alert("Server busy. Please refresh your page.")}}})}}function approvalList(){if(_token==0||_token==""){alert("Server busy, Please refresh our page.")}else{jQuery("#spinner-loader-cipo").css("display","inline-block");jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl?>opportunity/approvalList",data:{token:_token},cache:false,success:function(a){jQuery("#spinner-loader-cipo").css("display","none");_data=jQuery.parseJSON(a);if(_data.url!=""){jQuery("#assets_approval").html('<p class="label-after-btn is-blink"><i class="glyph-icon icon-caret-right"></i><span>Wait for CIPO approval</span></p>')}else{alert("Server busy. Please refresh your page.")}}})}}function editInviteesData(a){if(__invitees!=undefined){_invitees=__invitees;for(i=0;i<_invitees.length;i++){if(_invitees[i]["contact"].id==a){jQuery("#inviteesCompanyName").val(_invitees[i]["contact"].company_name);jQuery("#inviteesPersonInCharge").val(_invitees[i]["contact"].person_in_charge);jQuery("#inviteesTelephone").val(_invitees[i]["contact"].telephone);jQuery("#inviteesEmail").val(_invitees[i]["contact"].email);jQuery("#inviteesId").val(_invitees[i]["contact"].id);jQuery("#marketSector>option").each(function(){__id=jQuery(this).attr("value");if(_invitees[i]["sector"].length>0){for(j=0;j<_invitees[i]["sector"].length;j++){if(_invitees[i]["sector"][j].id==__id){jQuery(this).attr("selected","selected")}}}});jQuery(".multi-select").multiSelect("refresh");jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>')}}}}function insertDataDocket(){jQuery("#insert_data_docket").removeClass("hide").addClass("display-block")}function checkListContacts(b){var a=[];jQuery("#list_inv_sec").find(".checkbox").find('input[type="checkbox"]').each(function(){if(jQuery(this).prop("checked")){a.push(jQuery(this).val())}});if(a.length>0){__val=b.val();if(__val!=""){jQuery.ajax({type:"POST",url:"<?php echo $Layout->baseUrl?>opportunity/getCheckListContact",data:{token:JSON.stringify(a)},cache:false,success:function(c){_data=jQuery.parseJSON(c);if(_data.length>0){if(typeof(___table)=="object"){___table.destroy()}jQuery("#datatable-contacts").find("tbody").empty();for(i=0;i<_data.length;i++){__name=(_data[i].name!="")?_data[i].name:_data[i].orgTitle;_checked="";if(__selectC.length>0){for(j=0;j<__selectC.length;j++){if(__selectC[j].contact_id==_data[i].id){_checked='CHECKED="CHECKED"'}}}if(jQuery("#selected_contact").find("table").length>0){jQuery("#selected_contact").find("table").find("tbody").find("tr").each(function(){if(jQuery(this).attr("id")==_data[i].id){_checked='CHECKED="CHECKED"'}})}sectors=_data[i].sectors;if(sectors!=""){sectors=sectors.join(",<br/>")}phoneNumber=_data[i].phoneNumber;if(phoneNumber!=""&&phoneNumber!=undefined&&phoneNumber.length>0){phoneNumber=phoneNumber.join(",<br/>")}jQuery("#datatable-contacts>tbody").append('<tr id="'+_data[i].id+'"><td style="width:30px;"><input type="checkbox" '+_checked+' name="invite[contact_id][]" onclick="getDataContact(jQuery(this))" value="'+_data[i].id+'"/></td><td>'+_data[i].name+"</td><td>"+_data[i].orgTitle+"</td><td>"+jQuery.trim(_data[i].orgName)+"</td><td>"+phoneNumber+"</td><td>"+sectors+"</td></tr>")}___table=jQuery("#datatable-contacts").DataTable({searching:true,scrollY:"554px",scrollX:false,scrollCollapse:true,paging:false});console.log("123321");___table.columns.adjust().draw()}else{jQuery("#datatable-contacts").find("tbody").empty()}}})}}else{if(typeof(___table)=="object"){___table.destroy()}jQuery("#datatable-contacts").find("tbody").empty();_data=__invitees;for(i=0;i<_data.length;i++){__name=(_data[i].name!="")?_data[i].name:_data[i].orgTitle;_checked="";if(__selectC.length>0){for(j=0;j<__selectC.length;j++){if(__selectC[j].contact_id==_data[i].id){_checked='CHECKED="CHECKED"'}}}if(jQuery("#selected_contact").find("table").length>0){jQuery("#selected_contact").find("table").find("tbody").find("tr").each(function(){if(jQuery(this).attr("id")==_data[i].id){_checked='CHECKED="CHECKED"'}})}sectors=_data[i].sectors;if(sectors!=""){sectors=sectors.join(",<br/>")}phoneNumber=_data[i].phoneNumber;if(phoneNumber!=""&&phoneNumber!=undefined&&phoneNumber.length>0){phoneNumber=phoneNumber.join(",<br/>")}jQuery("#datatable-contacts>tbody").append('<tr id="'+_data[i].id+'"><td style="width:30px;"><input type="checkbox" '+_checked+' name="invite[contact_id][]" onclick="getDataContact(jQuery(this))" value="'+_data[i].id+'"/></td><td style="width:200px;">'+_data[i].name+'</td><td style="width:200px;">'+_data[i].orgTitle+'</td><td style="width:300px;">'+jQuery.trim(_data[i].orgName)+'</td><td style="width:150px;">'+phoneNumber+'</td><td style="width:200px;">'+sectors+"</td></tr>")}___table=jQuery("#datatable-contacts").DataTable({searching:true,scrollY:"554px",scrollX:false,scrollCollapse:true,paging:false})}}function insertEmbedCode(){jQuery("#embedCode").css("display","block")};function btnModeStatus(bID,container){         
		jQuery('#loader_'+bID).removeClass('hide').addClass('show');
		jQuery('#drive_button'+bID).find('a').removeAttr("onclick");
		jQuery.ajax({
			type:'POST',
			url: __baseUrl + 'index.php/leads/btn_status_change',
			data:{lead_id:leadGlobal,b:bID},
			success:function(response){
				obj = JSON.parse(response);
				if(obj.error==0){         
					_html = jQuery('#drive_button'+bID).find('a').attr('data-status');
					jQuery('#drive_button'+bID).html(moment(new Date(obj.date_created)).format('MM-D-YY')+' <span>'+_html+'</span>');
				}  else {
					alert(obj.message);  
					jQuery('#loader_'+bID).addClass('hide').removeClass('show');
					jQuery('#drive_button'+bID).find('a').attr('onclick','btnModeStatus('+bID+',"'+container+'")');
				}              
			}
		});
	}/*]]>*/</script>
<?php 
	if($this->session->flashdata('message')){
	?>
		<?php echo $this->session->flashdata('message');?>
	<?php					
		}
	?>
	<?php echo form_open('opportunity/docket_save',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>"docketLead","enctype"=>'multipart/form-data'));?>
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-12"><h2><?php echo $lead_data->lead_name;?></h2></div>
			<div class="col-lg-10">
				<div class="" id="from_regular">
					<div class="col-xs-12">
						<div class="form-group bigmr">
							<label for="marketProspectsName" class="pull-left"></label>
							<?php 
								if(!empty($acquisition['acquisition']->store_name)):
									$urlName ="";
									if($acquisition['acquisition']->category>0){
										if(count($category_list)>0){
											for($cc=0;$cc<count($category_list);$cc++){
												if($category_list[$cc]->id==$acquisition['acquisition']->category){
													$urlName = $category_list[$cc]->name;
													$urlName = str_replace('','_',$urlName);
													$urlName = str_replace('-','_',$urlName);
													$urlName = str_replace('&',' ',$urlName);
													$urlName = str_replace('&amp;',' ',$urlName);
													$urlName = preg_replace("/[^a-zA-Z0-9_\s-]/", "_", $urlName);
													$urlName = preg_replace('/-/','_',$urlName);
													$urlName = preg_replace('/[\s,\-!]/',' ',$urlName);
													$urlName = preg_replace('/\s+/','_',$urlName);
												}
											}
										}
										if(!empty($urlName)){
											$urlName ='/departments/'.$urlName.'-'.$acquisition['acquisition']->category.'/'.$lead_data->serial_number.'/';
										}
									}
							?>
							<a class='mrg15R pull-left' href="http://www.synpat.com/store/<?php echo $acquisition['acquisition']->store_name;?>" target="_BLANK">Link to Docket</a>
							<a class='mrg15R pull-left' href="http://www.synpat.com/dd/<?php echo $lead_data->serial_number;?>" target="_BLANK">Link to DD</a>
							<?php if(!empty($urlName)):?><a class='mrg15R pull-left' href="http://www.synpat.com<?php echo $urlName;?>" target="_BLANK">Link to store</a><?php endif;?>
							<?php endif;?>
							<?php if(!empty($lead_data->technical_file)):?>
								<a class='mrg15R pull-left' onclick="opener.open_drive_files('https://docs.google.com/spreadsheets/d/<?php echo $lead_data->technical_file;?>')" href="javascript://">Technical DD</a>
							<?php endif; ?>
						</div>
					</div>



					<div>
  						<ul class="nav nav-tabs mrg5T" role="tablist" style="padding-left: 0;">
    						<li role="presentation" class="active"><a href="#docketTab1" aria-controls="docketTab1" role="tab" data-toggle="tab">Portfolio</a></li>
    						<li role="presentation"><a href="#docketTab2" aria-controls="docketTab2" role="tab" data-toggle="tab">Documents</a></li>
    						<li role="presentation"><a href="#docketTab3" aria-controls="docketTab3" role="tab" data-toggle="tab">Quality</a></li>
    						<li role="presentation"><a href="#docketTab4" aria-controls="docketTab4" role="tab" data-toggle="tab">Impact</a></li>
    						<li role="presentation"><a href="#docketTab5" aria-controls="docketTab5" role="tab" data-toggle="tab">Synpat Shop</a></li>
    						<li role="presentation"><a href="#docketTab6" aria-controls="docketTab6" role="tab" data-toggle="tab">Docket Accordion</a></li>
  						</ul>
					  	<div class="tab-content mrg10T">
					    	<div role="tabpanel" class="tab-pane active" id="docketTab1">
								<?php 
									$personName = "";
									$personEmail = "";
									$personPhone = "";
									$personPicture = "";
									if($acordion_text->License->person_name!=""){
										$personName = $acordion_text->License->person_name;
									}
									if($acordion_text->License->person_email!=""){
										$personEmail = $acordion_text->License->person_email;
									}
									if($acordion_text->License->person_phone!=""){
										$personPhone = $acordion_text->License->person_phone;
									}
									if($acordion_text->License->person_picture!=""){
										$personPicture = $acordion_text->License->person_picture;
										
									}
								?>
								<div class="row mrg10T">
									<div class="row">
										<div class="col-sm-8">
											<div class="row">
												<div class="col-sm-3">
													<div class="form-group input-string-group bigmr">
														<label for="marketNo_of_us_patents" class="control-label">Option Expiration Date:</label>
														<input type="text" id="dockeOptionExpirationDate"  name="docket[option_expiration_data]" value="<?php echo $optionExpirationDate;?>" class="form-control input-string bootstrap-datepicker" >
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group input-string-group bigmr">
														<label for="marketNo_of_us_patents" class="control-label">Person Name:</label>
														<input type="text" id="docketPersonName"  name="docket[person_name]" value="<?php echo $personName;?>" class="form-control input-string " >
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group input-string-group bigmr">
														<label for="marketNo_of_us_patents" class="control-label">Person Email:</label>
														<input type="text" id="docketPersonEmail"  name="docket[person_email]" value="<?php echo $personEmail;?>" class="form-control input-string " >
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group input-string-group bigmr">
														<label for="marketNo_of_us_patents" class="control-label">Person Phone:</label>
														<input type="text" id="docketPersonPhone"  name="docket[person_phone]" value="<?php echo $personPhone;?>" class="form-control input-string " >
													</div>
												</div> 
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group input-string-group bigmr" style="border: none !important;">
												<label for="marketNo_of_us_patents" class="control-label">Person Image:</label>
												<select name="docket[person_picture]" id="docketPicture" class="form-control">
													<option value="">-- Select File --</option>
													<?php 
														$personPicture = str_replace($this->config->base_url().'public/upload/',"",$personPicture);
														foreach($docs_list['images'] as $doc){
															$checked="";
															if(!empty($personPicture) && $doc->title==$personPicture){
																$checked="selected='selected'";
															}
													?>
													<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
													<?php
														}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketNo_of_non_us_patents" class="control-label">Image Left (280x260):</label>
											<select name="other[image_left]" id="docketImageLeft" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageLeft) && $doc->title==$imageLeft){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketExpectedPrice" class="control-label">Image Middle (280x260):</label>
											<select name="other[image_middle]" id="docketImageMiddle" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageMiddle) && $doc->title==$imageMiddle){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Right (280x260):</label>
											<select name="other[image_right]" id="docketImageRight" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageRight) && $doc->title==$imageRight){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Four (280x260):</label>
											<select name="other[image_four]" id="docketImageFour" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageFour) && $doc->title==$imageFour){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>	
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Five (280x260):</label>
											<select name="other[image_five]" id="docketImageFive" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageFive) && $doc->title==$imageFive){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Six (280x260):</label>
											<select name="other[image_six]" id="docketImageSix" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageSix) && $doc->title==$imageSix){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Seven (280x260):</label>
											<select name="other[image_seven]" id="docketImageSeven" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageSeven) && $doc->title==$imageSeven){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Eight (280x260):</label>
											<select name="other[image_eight]" id="docketImageEight" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageEight) && $doc->title==$imageEight){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Nine (280x260):</label>
											<select name="other[image_nine]" id="docketImageNine" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageNine) && $doc->title==$imageNine){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Ten (280x260):</label>
											<select name="other[image_ten]" id="docketImageTen" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageTen) && $doc->title==$imageTen){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Eleven (280x260):</label>
											<select name="other[image_eleven]" id="docketImageEleven" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageEleven) && $doc->title==$imageEleven){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Image Twelve (280x260):</label>
											<select name="other[image_twelve]" id="docketImageTwelve" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['images'] as $doc){
														$checked="";
														if(!empty($imageTwelve) && $doc->title==$imageTwelve){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->title?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row mrg10T" style="padding-right:25px;">
									<div class="col-sm-3">
										<div class="row">
											<label class="col-sm-12">Left Chart</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_chart_left">
												
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="row">
											<label class="col-sm-12">Middle Chart</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_chart_middle">
												
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="row">
											<label class="col-sm-12">Right Chart</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_chart_right">
												
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="row">
											<label class="col-sm-12">Potential Syndicate</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_potential_syndicate"></div>
										</div>
									</div>
								</div>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="docketTab2">
				    			<div class="row">
									<div class="col-xs-2">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketExpectedPrice" class="control-label" style="width: 31px;">PPA:</label>
											<select name="other[ppa]" id="otherPPA" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['list'] as $doc){
														$checked="";
														if(!empty($ppa) && $doc->alternateLink==$ppa){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->alternateLink?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-2">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketOwner" class="control-label" style="width: 31px;">PLA:</label>
											<select name="other[pla]" id="otherPLA" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['list'] as $doc){
														$checked="";
														if(!empty($pla) && $doc->alternateLink==$pla){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->alternateLink?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-2">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">SLA:</label>
											<select name="other[sla]" id="otherSla" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['list'] as $doc){
														$checked="";
														if(!empty($sla) && $doc->alternateLink==$sla){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->alternateLink?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-2">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketOwner" class="control-label" style="width: 31px;">RTP:</label>
											<select name="other[rtp]" id="otherRTP" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['list'] as $doc){
														$checked="";
														if(!empty($rtp) && $doc->alternateLink==$rtp){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->alternateLink?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-2">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label" style="width: 31px;">PPP:</label>
											<select name="other[ppp]" id="otherPPP" class="form-control">
												<option value="">-- Select File --</option>
												<?php 
													foreach($docs_list['list'] as $doc){
														$checked="";
														if(!empty($ppp) && $doc->alternateLink==$ppp){
															$checked="selected='selected'";
														}
												?>
												<option <?php echo $checked?> value="<?php echo $doc->alternateLink?>"><?php echo $doc->title?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
								</div>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="docketTab3">
								<div class="form-group input-string-group bigmr" >
									<label class="control-label" for="emebedCCCode">Enter the OrderName at ClaimCharts.info: </label>
									<input type="text" name="embed[order_name]" value="<?php echo $acquisition['acquisition']->order_name?>" class="form-control input-string" id="emebedOrderName" />
								</div>
								<div class="form-group input-string-group bigmr" >
									<label class="control-label" for="emebedCCCode">ClaimChart Embedding Code: </label>
									<input type="text" name="embed[cc_embed_code]" value='<?php echo $acquisition['acquisition']->cc_embed_code?>' class="form-control input-string" id="emebedCCCode" />
								</div>
								<div class="form-group input-string-group bigmr" >
									<label class="control-label" for="emebedCCCode">PriorArt Embedding Code: </label>
									<input type="text" name="embed[par_embed_code]" value='<?php echo $acquisition['acquisition']->par_embed_code?>' class="form-control input-string" id="emebedPARCode" />
								</div>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="docketTab4">
					    		<div class="row" style="padding-right: 25px;">
									<div class="col-xs-6">
										<div class="row" style="margin-top: 7px;">
											<label class="col-sm-12">Comparables</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_chart_comparables"></div>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="row" style="margin-top: 7px;">
											<label class="col-sm-12">Damages</label>
											<div class="col-sm-12" style='margin-top:5px;' id="table_chart_damages"></div>
										</div>
									</div>
								</div>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="docketTab5">
					    		<div class="row">
									<div class="col-xs-6">
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label for="marketProspects" class="control-label">Category:</label>
											<select name="acquisition[category]" id="otherCategory" class="form-control" style="width: 426px; text-align: left;">
												<option value='0'>-- Select Category --</option>
												<?php 
													if(count($category_list)>0){
														for($cl=0;$cl<count($category_list);$cl++){
												?>
												<option value="<?php echo $category_list[$cl]->id?>" <?php if($acquisition['acquisition']->category==$category_list[$cl]->id):?> selected='selected' <?php endif;?>><?php echo $category_list[$cl]->name?></option>
												<?php
														}
													}
												?>
											</select> 
										</div> 
										<div class="form-group input-string-group bigmr" style="border: none !important;">
											<label class="control-label" for="">Active Buttons: </label>
											<div class="controls" id="label_radio">
												<input type="radio" name="acquisition[active_button]" style='float:left;margin-top: 8px;margin-right: 10px;' <?php if($acquisition['acquisition']->active_button=='1'):?>checked="checked" <?php endif;?> id="licenseActiveButton1" value="1">
												<label for="licenseActiveButton1">Participate </label>
												<input type="radio" name="acquisition[active_button]" style='float:left;margin-top: 8px;margin-right: 10px;' <?php if($acquisition['acquisition']->active_button=='2'):?>checked="checked" <?php endif;?> id="licenseActiveButton2" value="2" >
												<label for="licenseActiveButton2">Regular License </label>
												<input type="radio" name="acquisition[active_button]" style='float:left;margin-top: 8px;margin-right: 10px;' <?php if($acquisition['acquisition']->active_button=='3'):?>checked="checked" <?php endif;?> id="licenseActiveButton3" value="3">
												<label for="licenseActiveButton3">Risk Averse License </label>
											</div>
										 </div>
										 <div class="form-group input-string-group bigmr">
											<label class="control-label" for="emebedCCCode">Cost Price: </label>
											<input type="text" name="acquisition[cost_price]"  class="form-control input-string" id="otherCostPrice" value='<?php echo $acquisition['acquisition']->cost_price?>'/>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="form-group input-string-group bigmr">
											<label class="control-label" for="emebedCCCode">Potential Participants: </label>
											<input type="text" name="acquisition[potential_participants]"  class="form-control input-string" id="otherPotentialParticipants" value='<?php echo $acquisition['acquisition']->potential_participants?>'/>
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label" for="emebedCCCode">Final Participants: </label>
											<input type="text" name="acquisition[final_participants]"  class="form-control input-string" id="otherFinalParticipants" value='<?php echo $acquisition['acquisition']->final_participants?>'/>
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label" for="emebedCCCode">Regular Licensing Starts: </label>
											<input type="text" name="acquisition[regular_license_starts]"  class="form-control input-string bootstrap-datepicker" id="otherRegularLicenseStarts" value='<?php echo ($acquisition['acquisition']->regular_license_starts!='0000-00-00 00:00:00')?date('Y-m-d',strtotime($acquisition['acquisition']->regular_license_starts)):""?>'/>
										</div>
										<div class="form-group input-string-group bigmr">
											<label class="control-label" for="emebedCCCode">Late Licensing Starts: </label>
											<input type="text" name="acquisition[late_license_starts]"  class="form-control input-string bootstrap-datepicker" id="otherLateLicenseStarts" value='<?php echo ($acquisition['acquisition']->late_license_starts!='0000-00-00 00:00:00')?date('Y-m-d',strtotime($acquisition['acquisition']->late_license_starts)):""?>'/>
										</div>
									</div>
									<div class='mrg20T' style="padding-right: 25px;">
										<label for="marketProspects" class="control-label">Store:</label>
										<textarea class='form-control input-string' name='acquisition[store]' id='store_small_desc'><?php echo $acquisition['acquisition']->store?></textarea>
									</div>
								</div>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="docketTab6">
								<div class="form-group input-string-group bigmr " style="border: none !important;">
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(1)" style="font-size:13px;padding:0px;">Portfolio</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(2)" style="font-size:13px;padding:0;">Syndication</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(3)" style="font-size:13px;padding:0px;">Simulator</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(4)" style="font-size:13px;padding:0;">Documents</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(5)" style="font-size:13px;padding:0px;">Scope</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(6)" style="font-size:13px;padding:0;">Quality</a>
									<a href="javascript://" class="btn mrg15R" onclick="openModalContent(7)" style="font-size:13px;padding:0;">Impact</a>
								</div>
					    	</div>
					  	</div>
					</div>
				</div>
			</div>
			<div class="col-lg-2">
				<div class="col-width" style="width:230px;" id="from_docket">
					<div style=" margin-top:-2px;">
						<div class="clearfix">
							<button type="submit"  class="btn btn-primary btn-block">Save</button>
							<div id="spinner-loader-save-btn" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>
						</div>
						<div class="clearfix mrg5T">										
							<div class="todo-list-custom button-list" style="width: 230px; padding-left: 0;">
								<div class="row mrg5T">
									<div class="col-sm-12" id="data_patent_update">
										<a href='javascript://' onclick="startUpdatePatent();" class='btn btn-opp-block btn-default mrg5T'>
											<span>Update Patent List</span>
										</a>
										<div id="spinner-loader-patent" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>
									</div>
								</div>
								<div class="row mrg5T">
									<div class="col-sm-12" id="data_invention">										
										<div id="spinner-loader-invention" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>
									</div>
								</div>
								<div class="row mrg5T">
									<div class="col-sm-12" id="data_illustration">										
										<div id="spinner-loader-illustration" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Drafting PPA" style="color:#222222;"></div>
									</div>
								</div>
								<div class="row mrg5T">
									<div class="col-xs-12">
										<button class="btn btn-opp-block btn-default mrg5T" type="button" onclick="uploadDocketFileFolder();">Update Docket File</button>
										<div id="spinner-loader-docket-file-folder" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Update files of Docket folders " style="color:#222222;"></div>
									</div>
								</div>
								<div class="row mrg5T">
									<div class="col-xs-12">
										<button class="btn btn-opp-block btn-default mrg5T" type="button" onclick="uploadFileImage();">Image To Drive</button>
									</div>
								</div>   
								<?php 
									if(count($buttons)>0){
										foreach($buttons as $button){
											switch( $button->button_id){
												case 'EMAIL':
												if($button->btnStatus=="0" || $button->renewable=="1"):
								?>
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="email_button<?php echo $button->id?>">
														<a class="btn btn-default btn-mwidth renewable" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="emailMode(<?php echo $button->id?>,'from_docket','<?php echo $button->reference_id?>');"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
													</div>
												</div>
								<?php
												else:
								?>
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="email_button<?php echo $button->id?>">
													<?php if($button->blink==1):?>
													<a class="btn btn-default btn-mwidth renewable btn-blink" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="btnModeStatus(<?php echo $button->id?>,'from_docket');"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
													<?php else:?>
														<span class="date-style"><?php echo date('m-d-y',strtotime($button->update_date))?></span> <?php echo $button->status_message;?>
													<?php endif;?>
													</div>
												</div>
								<?php
												endif;
												break;
												case 'DRIVE':
												if($button->btnStatus=="0" || $button->renewable=="1"):
								?>
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="drive_button<?php echo $button->id?>">
														<a class="btn btn-default btn-mwidth renewable" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="driveMode(<?php echo $button->id?>,'from_docket','<?php echo $button->reference_id?>',<?php echo $button->send_task?>);"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
													</div>
												</div>
								<?php
												else:
								?>				
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="drive_button<?php echo $button->id?>">
														<?php if($button->blink==1):?>
														<a class="btn btn-default btn-mwidth renewable btn-blink" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="btnModeStatus(<?php echo $button->id?>,'from_docket');"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
														<?php else:?>
														<span class="date-style"><?php echo date('m-d-y',strtotime($button->update_date))?></span> <?php echo $button->status_message;?>
														<?php endif;?>
													</div>
												</div>	
								<?php
												endif;
												break;
												case 'TASK':
												if($button->btnStatus=="0" || $button->renewable=="1"):
								?>
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="task_button<?php echo $button->id?>">
														<a class="btn btn-default btn-mwidth renewable" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="taskMode(<?php echo $button->id?>,'from_docket','<?php echo $button->reference_id?>');"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
													</div>
												</div>
								<?php
												else:
								?>				
												<div class="row mrg5T" data-item-idd="<?php echo $button->id?>">
													<div class="col-sm-12" id="task_button<?php echo $button->id?>">
														<?php if($button->blink==1):?>
														<a class="btn btn-default btn-mwidth renewable btn-blink" data-status="<?php echo $button->status_message?>" title="<?php echo $button->description?>" href="javascript://" onclick="btnModeStatus(<?php echo $button->id?>,'from_docket');"><?php echo $button->name?></a>
														<div id="loader_<?php echo $button->id?>" class="glyph-icon remove-border demo-icon tooltip-button icon-spin-1 icon-spin float-left mrg0A hide" title="" data-original-title="Processing Importing Data" style="color:#222222;"></div>
														<?php else:?>
														<span class="date-style"><?php echo date('m-d-y',strtotime($button->update_date))?></span> <?php echo $button->status_message;?>
														<?php endif;?>
													</div>
												</div>
								<?php
												endif;
												break;
											}
										}
									}
								?>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<input type="hidden" name="other[lead_id]" id="leadID" value="<?php echo $lead_id?>" />
	<input type="hidden" name="other[potential_data]" id="otherPotentialData" />
	<input type="hidden" name="other[commitment_data]" id="otherCommitmentData" />	
	<input type="hidden" name="other[chart_left]" id="otherChartLeft" />
	<input type="hidden" name="other[chart_middle]" id="otherChartMiddle" />
	<input type="hidden" name="other[chart_right]" id="otherChartRight" />
	<input type="hidden" name="other[comparable]" id="otherComparable" />
	<input type="hidden" name="other[damages]" id="otherDamages" />
	<input type="hidden" name="other[redirect]" id="otherRedirect" value="opportunity" />
	<?php echo form_close()?>
</div>
</div>
</div>
<?php else:?>
<p class='alert alert-warning'>This is not converted to opportunity</p>
<?php endif;?>
</div>
<div class="modal modal-opened-header fade" id="portfolioModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Portfolio Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$portfolio_text = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->portfolio_text)){
									$portfolio_text = $acordion_text->License->portfolio_text;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='portfolio_text' id='portfolio_text'><?php echo $portfolio_text;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(1)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-opened-header fade" id="syndicationModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Syndication Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$syndication_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->syndication_tab)){
									$syndication_tab = $acordion_text->License->syndication_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='syndication_tab' id='syndication_tab'><?php echo $syndication_tab;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(2)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-opened-header fade" id="simulatorModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Simulator Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$simulator_text = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->simulator_text)){
									$simulator_text = $acordion_text->License->simulator_text;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='simulator_text' id='simulator_text'><?php echo $simulator_text;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(3)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-opened-header fade" id="documentsModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Document Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$document_text = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->document_text)){
									$document_text = $acordion_text->License->document_text;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='document_text' id='document_text'><?php echo $document_text;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(4)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-opened-header fade" id="scopeModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Due Dilligence Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$due_diligence_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->due_diligence_tab)){
									$due_diligence_tab = $acordion_text->License->due_diligence_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='due_diligence_tab' id='due_diligence_tab'><?php echo $due_diligence_tab;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(5)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-opened-header fade" id="qualityModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Report Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<label class="col-lg-12 control-label mrg10T mrg10B">ClaimChart Accordion Text</label>
					<div class="col-lg-12">
						
						<?php 
							$claim_chart_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->claim_chart_tab)){
									$claim_chart_tab = $acordion_text->License->claim_chart_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='claim_chart_tab' id='claim_chart_tab'><?php echo $claim_chart_tab;?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
					<label class="col-lg-12 control-label mrg10T mrg10B">Prior Art Accordion Text</label>
						
						<?php 
							$prior_art_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->prior_art_tab)){
									$prior_art_tab = $acordion_text->License->prior_art_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='prior_art_tab' id='prior_art_tab'><?php echo $prior_art_tab;?></textarea>
					</div>
				</div> 
				<div class="row">
				<label class="col-lg-12 control-label mrg10T mrg10B">Damages Accordion Text</label>
					<div class="col-lg-12">
					
						<?php 
							$damage_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->damage_tab)){
									$damage_tab = $acordion_text->License->damage_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='damage_tab' id='damage_tab'><?php echo $damage_tab;?></textarea>
					</div>
				</div> 
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(6)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-opened-header fade" id="impactModal" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 0 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Impact Accordion Text</h4>				
			</div>
			<div class="modal-body"> 			   
				<div class="row">
					<div class="col-lg-12">
						<?php 
							$impact_tab = "";
							if(count($acordion_text)>0){
								if(isset($acordion_text->License->impact_tab)){
									$impact_tab = $acordion_text->License->impact_tab;
								}
							}
						?>
						<textarea class='wysiwyg-editor' name='impact_tab' id='impact_tab'><?php echo $impact_tab;?></textarea>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="saveModalText(7)" class='btn btn-mwidth btn-default' style='margin-right:1px;'>Save</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-opened-header fade" id="uploadFileImage" tabindex="-1" role="dialog" aria-labelledby="calendarLabel" aria-hidden="true" style='width:100%; margin-top: 50 !important;'>
	<div class="modal-dialog" style='width:100%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="">Image URL</h4>				
			</div>
			<div class="modal-body" style="max-height:200px;"> 			   
				<div class="row" style='min-height:150px;'>
					<div class="col-lg-12" >
						<input type="text" placeholder="Image URL" class="form-control input-string" name="image_url" id="image_url"/>
					</div>
					<div class="col-lg-12 mrg10T" >
						<input type="text" placeholder="Image Name" class="form-control input-string" name="image_name" id="image_name"/>
					</div>
				</div>               
			   <div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			
				<div class="loading-spinner" id="spinner-loader-image-drive" style="display:none;">
										<img src="<?php echo $this->config->base_url();?>public/images/ajax-loader.gif" alt="">
									</div>
				<button id="btn-drive" type="button" onclick="saveFullImageURL()" class='btn btn-opp-block btn-primary ' style='margin-right:1px;'>Save To Drive</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="createTaskModal1" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true" style="width:50%;left:0px"> <div class="modal-dialog" style='width:100%'> <div class="modal-content"> <?php echo form_open("opportunity/task",array("class"=>"form-horizontal form-flat","id"=>"formTask"));?> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title" id="createTaskModalLabel"></h4> </div> <div class="modal-body"> <div class="row"> <div class="col-xs-12"> <div class="clearfix"> <label class="control-label" style="float:left">For:</label> <select name="task[user_id]" id="taskUserId" required="required" class="form-control" style="float:left;width:225px;margin-top:2px;margin-left:3px"> <option value="">-- Select User --</option> <?php 
                                            $getUsers = getAllUsersIncAdmin();
                                            foreach($getUsers as $user){
                                        ?> <option value="<?php echo $user->id?>"><?php echo $user->name;?></option> <?php                                               
                                            }
                                        ?> </select> </div> </div> <div class="col-xs-12 mrg5T"> <div class="clearfix"> <label class="control-label" style="float:left">Subject:</label> <input type="text" maxlength="40" class="form-control input-string" name="task[subject]" id="taskSubject" style="float:left;width:225px;margin-top:4px"/> </div> </div> </div> <div class="clearfix mrg10T"> <div class="form-group"> <label>Message:</label> <textarea name="task[message]" class="form-control input-string" id="taskMessage" rows="5"></textarea> </div> </div> <div class="row"> <div class="col-xs-12"> <div class="clearfix"> <label class="control-label" style="float:left">Link Url:</label> <input input name="task[doc_url]" class="form-control input-string" id="taskDocUrl" style="float:left;width:518px;margin-top:4px" /> </div> </div> <div class="col-xs-12 mrg5T"> <div class="clearfix"> <label class="control-label" style="float:left">Execution Date:</label> <input input name="task[execution_date]" class="bootstrap-datepicker form-control input-string" id="taskExecutionDate" placeholder="yyyy-mm-dd" style="float:left;width:82px;margin-top:4px" /> </div> </div> <div class="col-xs-12 mrg5T"> <div class="clearfix"> <label class="control-label" style="float:left">Send Email:</label> <input type='checkbox' name="task[send_email]" class=" " id="taskSendEmail" style="float:left;margin-top:9px" value='1'/> </div> </div> </div> <div class="clearfix"> </div> </div> <div class="modal-footer"> <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button> <button type="button" class="btn btn-primary btn-mwidth" onclick="submitTask()">Create</button> <input type="hidden" name="task[parent_id]" value="0" id="taskParentId" /> <input type="hidden" name="task[from_user_id]" value="<?php echo $this->session->userdata['id']?>" id="taskFromUserId" /> <input type="hidden" name="task[lead_id]" value="0" id="taskLeadId" /> <input type="hidden" name="task[type]" id="taskType" /> <input type="hidden" name="task[id]" id="taskId" value="0" /><input type="hidden" name="task[record]" id="taskRecord" value="0" /><input type="hidden" name="other[return]" id="otherReturn" value="docket" /><input type="hidden" name="other[signature]" id="otherSignature" value="" /> </div> <?php echo form_close();?> </div> </div> </div>
<script>/*<![CDATA[*/function openTaskModal(){if(!jQuery("#createTaskModal1").is(":visible")){jQuery("#createTaskModal1").modal("show");jQuery("#createTaskModalLabel").html("Create Task for - "+leadNameGlobal)}else{checkModalFrontOrHide(jQuery("#createTaskModal1"))}}function saveFullImageURL(){jQuery("#spinner-loader-image-drive").css('display','block');jQuery("#btn-drive").css('display','none');if(jQuery("#image_url").val()!=""){jQuery.ajax({type:'POST',url:'<?php echo $Layout->baseUrl?>opportunity/upload_image_file',data:{i:jQuery("#image_url").val(),l:leadGlobal,n:jQuery("#image_name").val()},cache:false,success:function(data){jQuery("#spinner-loader-image-drive").css('display','none');jQuery("#btn-drive").css('display','block');if(data>0){window.location='<?php echo $Layout->baseUrl?>opportunity/docket/'+leadGlobal;}}});}else{jQuery("#spinner-loader-image-drive").css('display','none');jQuery("#btn-drive").css('display','block');}}
function openModalContent(type){switch(type){case 1:jQuery("#portfolioModal").modal("show");break;case 2:jQuery("#syndicationModal").modal("show");break;case 3:jQuery("#simulatorModal").modal("show");break;case 4:jQuery("#documentsModal").modal("show");break;case 5:jQuery("#scopeModal").modal("show");break;case 6:jQuery("#qualityModal").modal("show");break;case 7:jQuery("#impactModal").modal("show");break;}}
function saveModalText(type){$text="";switch(type){case 1:$text=jQuery("#portfolio_text").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,portfolio_text:$text},cache:false,success:function(){jQuery("#portfolioModal").modal("hide");}})}
break;case 2:$text=jQuery("#syndication_tab").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,syndication_tab:$text},cache:false,success:function(){jQuery("#syndicationModal").modal("hide");}})}
break;case 3:$text=jQuery("#simulator_text").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,simulator_text:$text},cache:false,success:function(){jQuery("#simulatorModal").modal("hide");}})}
break;case 4:$text=jQuery("#document_text").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,document_text:$text},cache:false,success:function(){jQuery("#documentsModal").modal("hide");}})}
break;case 5:$text=jQuery("#due_diligence_tab").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,due_diligence_tab:$text},cache:false,success:function(){jQuery("#scopeModal").modal("hide");}})}
break;case 6:$text=jQuery("#claim_chart_tab").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,claim_chart_tab:$text,prior_art_tab:jQuery("#prior_art_tab").code(),damage_tab:jQuery("#damage_tab").code()},cache:false,success:function(){jQuery("#qualityModal").modal("hide");}})}
break;case 7:$text=jQuery("#impact_tab").code();if($text!=""){jQuery.ajax({type:'POST',url:"<?php echo $Layout->baseUrl?>opportunity/save_accordion_text",data:{lead_id:leadGlobal,impact_tab:$text},cache:false,success:function(){jQuery("#impactModal").modal("hide");}})}
break;}}
jQuery(function(){jQuery('.multi-select').multiSelect('refresh');$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');setTimeout(function(){$('#tempStyles').remove();},300);});setInterval(function(){var $filterSearchCell=$('.dataTables_filter').parent();if(!$filterSearchCell.hasClass('is-hacked')){$filterSearchCell.parent().addClass('mrg10T');$filterSearchCell.prev().remove();$filterSearchCell.removeClass('col-sm-6').addClass('col-sm-12');$filterSearchCell.find('label:not(".control-label")').css({float:'none',display:'block'});$('.dataTables_filter input[type="search"]').addClass('form-control').addClass('input-string').css({marginLeft:'0',width:'40%'}).before('<label class="control-label" style="float:left;">Search:</label>');$filterSearchCell.addClass('is-hacked');$('#datatable-contacts-sharing').next().remove();}},500);
$('#portfolioModal').on('shown.bs.modal',function(e){portfolioModalResize();});$(window).on('resize',function(){portfolioModalResize();});
function portfolioModalResize(){var $modalBody=$('#portfolioModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#syndicationModal').on('shown.bs.modal',function(e){syndicationModalResize();});$(window).on('resize',function(){syndicationModalResize();});
function syndicationModalResize(){var $modalBody=$('#syndicationModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#simulatorModal').on('shown.bs.modal',function(e){simulatorModalResize();});$(window).on('resize',function(){simulatorModalResize();});
function simulatorModalResize(){var $modalBody=$('#simulatorModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#documentsModal').on('shown.bs.modal',function(e){documentsModalResize();});$(window).on('resize',function(){documentsModalResize();});
function documentsModalResize(){var $modalBody=$('#documentsModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#duedilligenceModal').on('shown.bs.modal',function(e){duedilligenceModalResize();});$(window).on('resize',function(){duedilligenceModalResize();});
function duedilligenceModalResize(){var $modalBody=$('#duedilligenceModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#reportsModal').on('shown.bs.modal',function(e){reportsModalResize();});$(window).on('resize',function(){reportsModalResize();});
function reportsModalResize(){var $modalBody=$('#reportsModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#impactModal').on('shown.bs.modal',function(e){impactModalResize();});$(window).on('resize',function(){impactModalResize();});
function impactModalResize(){var $modalBody=$('#impactModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#scopeModal').on('shown.bs.modal',function(e){scopeModalResize();});$(window).on('resize',function(){scopeModalResize();});
function scopeModalResize(){var $modalBody=$('#scopeModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}
if($noteEditable){$noteEditable.height(height-80);}}
$('#qualityModal').on('shown.bs.modal',function(e){qualityModalResize();});$(window).on('resize',function(){qualityModalResize();});
function qualityModalResize(){var $modalBody=$('#qualityModal .modal-body'),$noteEditable=$modalBody.find('.note-editable'),height=$(window).height()-197;if($modalBody.length){$modalBody.height(height);$modalBody.css('overflow-y','auto');}}
function submitTask(){if(leadGlobal==0||leadGlobal==""){alert("No entity found!")}else{if(jQuery("#taskLeadId").val()==""||jQuery("#taskLeadId").val()==0){jQuery("#taskLeadId").val(leadGlobal)}if(jQuery("#taskType").val()==""){jQuery("#taskType").val('Document')}jQuery("#formTask").eq(0).submit()}}
function driveMode(bID,container,ref,sendTask){if(leadGlobal!=0&&bID!=undefined&&container!=""&&ref!=""){jQuery("#loader_"+bID).removeClass('hide').addClass('show');jQuery.ajax({type:'POST',url:'<?php echo $Layout->baseUrl?>opportunity/drive_mode',data:{b:bID,c:container,r:ref,l:leadGlobal},cache:false,success:function(res){jQuery("#loader_"+bID).addClass('hide').removeClass('show');if(res!=""){if(sendTask==0){
	window.location='<?php echo $Layout->baseUrl?>opportunity/docket/'+leadGlobal;
} else {_d=jQuery.parseJSON(res);type=jQuery("#drive_button"+bID).find('a').html();jQuery("#drive_button"+bID).html(moment(new Date(_d.status)).format('MM-D-YY'));type = type.replace('Create','');jQuery("#formTask").get(0).reset();jQuery("#taskDocUrl").val(_d.url);jQuery("#taskRecord").val(1);if(type==''){type='Document';}jQuery('#otherSignature').val(opener.jQuery('#original_signature').val());jQuery("#taskType").val(type);jQuery("#taskLeadId").val(leadGlobal);openTaskModal();}}}});}}
function taskMode(bID,container,ref){if(leadGlobal!=0&&bID!=undefined&&container!=""&&ref!=""){jQuery("#loader_"+bID).removeClass('hide').addClass('show');jQuery.ajax({type:'POST',url:'<?php echo $Layout->baseUrl?>/opportunity/task_mode',data:{b:bID,c:container,r:ref,l:leadGlobal},cache:false,success:function(res){jQuery("#loader_"+bID).addClass('hide').removeClass('show');if(res!=""){_d=jQuery.parseJSON(res);_ss=jQuery("#"+container).find("#task_button"+bID).find("a").attr('data-status');if(_d.status!=""){opener.jQuery("#taskMessage").val(ref);opener.jQuery("#taskLeadId").val(leadGlobal);opener.openTaskModal();jQuery("#"+container).find("#task_button"+bID).html('<span class="date-style">'+moment(new Date(_d.status)).format('MM-D-YY')+"</span> "+_ss);}}}});}}
function emailMode(bID,container,ref){if(leadGlobal!=0&&bID!=undefined&&container!=""&&ref!=""){jQuery("#loader_"+bID).removeClass('hide').addClass('show');jQuery.ajax({type:'POST',url:'<?php echo $Layout->baseUrl?>/opportunity/email_mode',data:{b:bID,c:container,r:ref,l:leadGlobal},cache:false,success:function(res){jQuery("#loader_"+bID).addClass('hide').removeClass('show');if(res!=""){_d=jQuery.parseJSON(res);_ss=jQuery("#"+container).find("#email_button"+bID).find("a").attr('data-status');if(_d.status!=""){_str=jQuery("#emailMessage").val();opener.jQuery("#emailMessage").destroy();opener.jQuery("#emailMessage").val(_d.detail.template_html+_str);opener.jQuery("#emailSubject").val(_d.detail.subject);opener.jQuery("#messageLeadId").val(leadGlobal);opener.jQuery("#emailThreadId").val("");opener.jQuery("#emailMessageId").val("");opener.jQuery("#emailDocUrl").val("");$(function(){"use strict";$('.wysiwyg-editor').summernote({height:350,toolbar:[['style',['bold','italic','underline','clear']],['fontsize',['fontsize']],['color',['color']],['para',['ul','ol','paragraph']],['height',['height']],]});});opener.composeEmail();jQuery("#"+container).find("#email_button"+bID).html('<span class="date-style">'+moment(new Date(_d.status)).format('MM-D-YY')+"</span> "+_ss);}}}});}}/*]]>*/</script>
<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.css">
<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.css">
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/bootstrap-typeahead.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jquery.autoresize.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
<script>jQuery(document).ready(function(){jQuery("#docketLead").submit(function(event){event.preventDefault();validateAllData();});__data=[<?php
																for ($i = 0; $i < count($potential); $i++) {
																	echo "['" . $potential[$i]->participate . "','" . $potential[$i]->fees . "']";
																	if ($i < count($potential) - 1) {
																		echo ",";
																	}
																}
															?>];__data2=[<?php
															for ($i = 0; $i < count($chart_left); $i++) {
																echo "['" . $chart_left[$i]->country . "','" . $chart_left[$i]->applications . "','" . $chart_left[$i]->patents . "']";
																if ($i < count($chart_left) - 1) {
																	echo ",";
																}
															}
														?>];__data3=[<?php
															for ($i = 0; $i < count($chart_middle); $i++) {
																echo "['" . addslashes($chart_middle[$i]->technologies) . "','" . $chart_middle[$i]->data . "']";
																if ($i < count($chart_middle) - 1) {
																	echo ",";
																}
															}
														?>];__data4=[<?php 
															for ($i = 0; $i < count($chart_right); $i++) {
																echo "['" . $chart_right[$i]->year . "','" . $chart_right[$i]->data . "']";
																if ($i < count($chart_right) - 1) {
																	echo ",";
																}
															}
														?>];__data9=[<?php
															for ($i = 0; $i < count($comparables); $i++) {
																echo "['" . addslashes($comparables[$i]->file_name) . "','" . $comparables[$i]->file_link . "']";
																if ($i < count($comparables) - 1) {
																	echo ",";
																}
															}
														?>];__data10=[<?php
															for ($i = 0; $i < count($damages); $i++) {
																echo "['" . $damages[$i]->file_name . "','" . $damages[$i]->file_link . "']";
																if ($i < count($damages) - 1) {
																	echo ",";
																}
															}
														?>];var $container1=jQuery("#table_potential_syndicate");$container1.handsontable({startRows:1,startCols:2,colHeaders:['Participate','Fee'],minSpareCols:0,minSpareRows:1,colWidths:[100,100],manualColumnResize:true,contextMenu:false,columns:[{},{}]});if(__data.length>0){jQuery("#table_potential_syndicate").data('handsontable').loadData(__data);}
var $container2=jQuery("#table_chart_left");$container2.handsontable({startRows:1,startCols:3,colHeaders:['Country','Application','Patents'],minSpareCols:0,minSpareRows:1,colWidths:[100,150,100],manualColumnResize:true,contextMenu:false,});if(__data2.length>0){jQuery("#table_chart_left").data('handsontable').loadData(__data2);}
var $container3=jQuery("#table_chart_middle");$container3.handsontable({startRows:1,startCols:2,colHeaders:['Technologies','Data'],minSpareCols:0,minSpareRows:1,colWidths:[200,100],manualColumnResize:true,contextMenu:false,});if(__data3.length>0){jQuery("#table_chart_middle").data('handsontable').loadData(__data3);}
var $container4=jQuery("#table_chart_right");$container4.handsontable({startRows:1,startCols:2,colHeaders:['Year','Data'],minSpareCols:0,minSpareRows:1,colWidths:[200,100],manualColumnResize:true,contextMenu:false,});if(__data4.length>0){jQuery("#table_chart_right").data('handsontable').loadData(__data4);}
var $container9=jQuery("#table_chart_comparables");$container9.handsontable({startRows:1,startCols:2,colHeaders:['Name','Url'],minSpareCols:0,minSpareRows:1,colWidths:[200,100],manualColumnResize:true,contextMenu:false,});if(__data9.length>0){jQuery("#table_chart_comparables").data('handsontable').loadData(__data9);}
var $container10=jQuery("#table_chart_damages");$container10.handsontable({startRows:1,startCols:2,colHeaders:['Name','Url'],minSpareCols:0,minSpareRows:1,colWidths:[200,100],manualColumnResize:true,contextMenu:false,});if(__data10.length>0){jQuery("#table_chart_damages").data('handsontable').loadData(__data10);}});function validateAllData(){var handsontable=jQuery("#table_potential_syndicate").handsontable("getData");otherPotentialData=JSON.stringify(handsontable);jQuery("#otherPotentialData").val(otherPotentialData);var handsontable=jQuery("#table_chart_left").handsontable("getData");otherChartLeft=JSON.stringify(handsontable);jQuery("#otherChartLeft").val(otherChartLeft);var handsontable=jQuery("#table_chart_middle").handsontable("getData");otherChartMiddle=JSON.stringify(handsontable);jQuery("#otherChartMiddle").val(otherChartMiddle);var handsontable=jQuery("#table_chart_right").handsontable("getData");otherChartRight=JSON.stringify(handsontable);jQuery("#otherChartRight").val(otherChartRight);var handsontable=jQuery("#table_chart_comparables").handsontable("getData");otherComparable=JSON.stringify(handsontable);jQuery("#otherComparable").val(otherComparable);var handsontable=jQuery("#table_chart_damages").handsontable("getData");otherDamages=JSON.stringify(handsontable);jQuery("#otherDamages").val(otherDamages);var form = jQuery("#docketLead");jQuery("#spinner-loader-save-btn").removeClass("hide").addClass("show"); jQuery.ajax({type: form.attr('method'),url: form.attr('action'),data: form.serialize()}).done(function(){jQuery("#spinner-loader-save-btn").removeClass("show").addClass("hide");startInventionDataList();}).fail(function() {jQuery("#spinner-loader-save-btn").removeClass("show").addClass("hide");});}function uploadFileImage(){jQuery("#uploadFileImage").modal('show');}</script>