

<style>
	body {
		overflow: auto !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

	#datatable-contacts-sharing_wrapper .dataTables_filter label,
	#datatable-contacts-sharing1_wrapper .dataTables_filter label {
		padding-top: 2px;
	}
	#datatable-contacts-sharing_wrapper .dataTables_filter input,
	#datatable-contacts-sharing1_wrapper .dataTables_filter input {
	    box-shadow: none;
	    float: right;
	    height: 24px;
	    margin: -4px 0 2px 2px;
	    padding-left: 5px;
	    padding-right: 5px;
	}

	#datatable-contacts-sharing_wrapper .dataTables_scroll,
	#datatable-contacts-sharing1_wrapper .dataTables_scroll {
		background: none;
		clear: both;
	}
	#datatable-contacts-sharing_wrapper .dataTables_info,
	#datatable-contacts-sharing1_wrapper .dataTables_info {
		display: none;
	}
	.dataTables_scroll{
		background:none;
	}
</style>

<script>
	var ___table ;
	jQuery(document).ready(function(){
		/*___table = $('#datatable-contacts-sharing')
			.DataTable({								
				"searching":true,
				"autoWidth": true,
				"paging": false,
				"sScrollY": "300px",
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
			___table = $('#datatable-contacts-sharing1')
			.DataTable({								
				"searching":true,
				"autoWidth": true,
				"paging": false,
				"sScrollY": "300px",
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});
			___table = $('#datatable-contacts-sharing2')
			.DataTable({								
				"searching":true,
				"autoWidth": true,
				"paging": false,
				"sScrollY": "300px",
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});*/
			jQuery('body').removeAttr('onselectstart');
			document.oncontextmenu=new Function("return true");
	});
</script>
<div class="row">
	<h3>Request To Participate/License</h3>
	<div class="col-xs-12" style="min-height:100px;max-height:300px;overflow-y:Scroll">
		<table class="table" class="table" id="datatable-contacts-sharing">
			<thead>
				<tr>
					<th>#</th>
					<th>Company Name</th>
					<th>Name</th>
					<th>Phone No</th>
					<th>Email</th>
					<th>Message</th>
					<th>File</th>
					<th>Type</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(isset($licensees->participant) && count($licensees->participant)>0){
						foreach($licensees->participant as $contact){
				?>
				<tr>
					<td><input type="radio" value="<?php echo $contact->Request_to_participates->id?>"/></td>
					

					<td><?php echo (!empty($contact->Request_to_participates->company_name))?$contact->Request_to_participates->company_name:'';?></td>
					<td><?php echo (!empty($contact->Request_to_participates->person_name))?$contact->Request_to_participates->person_name:'';?></td>
					<td><?php echo $contact->Request_to_participates->person_phone; ?></td>
					<td><?php echo $contact->Request_to_participates->person_email; ?></td>
					<td><?php echo $contact->Request_to_participates->message;?></td>
					<td>
						<?php if(!empty($contact->Request_to_participates->file)):
								$file = pathinfo($contact->Request_to_participates->file);
								$fileUrl = str_replace("/home/synpatne/public_html/synpat_com/","http://www.synpat.com/",$file['dirname']);
						?>
							<a href="<?php echo $fileUrl.'/'.$file['basename'];?>" target="_BLANK"><?php echo $file['basename']?></a>
						<?php endif;?>
					</td>
					<td><?php echo ((int)$contact->Request_to_participates->type==0)?'Participant':'License'?></td>
					<td><?php echo (!empty($contact->Request_to_participates->create_date))?date('Y-m-d',strtotime($contact->Request_to_participates->create_date)):'';?></td>
					<td><a href="javascript://" onclick="sendConfirmation(jQuery(this),0);" class='font-blue'>Confirm</a><img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="" style='display:none;'></td>
				</tr>
				<?php		
						}
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="col-xs-12 ">
	<h3 class='mrg20T'>Confirmed Request</h3>
	<div class="col-xs-12 " style="min-height:100px;max-height:300px;overflow-y:Scroll;">
	<table class="table" class="table" id="datatable-contacts-sharing1" >
		<thead>
			<tr>
				<th>Company Name</th>
				<th>Name</th>
				<th>Phone No</th>
				<th>Email</th>
				<th>Message</th>
				<th>File</th>
				<th>Type</th>
				<th>Received</th>
				<th>Confirmed</th>
				<th>Price</th>
				<th>Include in Chart</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				if(isset($licensees->potential) && count($licensees->potential)>0){
					foreach($licensees->potential as $contact){
			?>
			<tr>
				<td><?php echo (!empty($contact->Potential_participates->company_name))?$contact->Potential_participates->company_name:'';?></td>
				<td><?php echo (!empty($contact->Potential_participates->person_name))?$contact->Potential_participates->person_name:'';?></td>
				<td><?php echo $contact->Potential_participates->person_phone; ?></td>
				<td><?php echo $contact->Potential_participates->person_email; ?></td>
				<td><?php echo $contact->Potential_participates->message;?></td>
				<td>
				<?php if(!empty($contact->Potential_participates->file)):
						$file = explode('?',$contact->Potential_participates->file);
						$previewFile = "";
						if(count($file)==2){
							$previewFile = substr(trim($file[0]),0,-4);
							$previewFile = $previewFile."/preview";
						}
				?>
					<a data-href="<?php echo $contact->Potential_participates->file?>" target="_BLANK" href="javascript://" onclick="window.parent.open_drive_files('<?php echo $previewFile?>')" class='font-blue'>View</a>
				<?php endif;?>
				</td>
				<td><?php echo ((int)$contact->Potential_participates->type==0)?'Participant':'License'?></td>
				<td><?php echo (!empty($contact->Potential_participates->create_date))?date('Y-m-d H:i:s',strtotime($contact->Potential_participates->create_date)):'';?></td>
				<td><?php echo (!empty($contact->Potential_participates->send_date))?date('Y-m-d H:i:s',strtotime($contact->Potential_participates->send_date)):'';?></td>
				<td style='width:150px;'>
					<div id="showPrice<?php echo $contact->Potential_participates->id?>">
				<?php 
					if($contact->Potential_participates->price!='0.00'):
				?>
					<span><?php echo $contact->Potential_participates->price;?></span>&nbsp;&nbsp;<a href='javascript://' class='font-blue' onclick="insertPrice(<?php echo $contact->Potential_participates->id?>)">Update</a>
				<?php
					else:
				?>
					<a href='javascript://' onclick="insertPrice(<?php echo $contact->Potential_participates->id?>)" class='font-blue'>Insert Price</a>
				<?php
					endif;
				?>
					</div>
					<div id="updatePrice<?php echo $contact->Potential_participates->id?>" style='display:none;' class='update'>
						<input style='width:60px;' onkeypress="return validateFloatKeyPress(this,event);" maxlength="4" type="text" name="price<?php echo $contact->Potential_participates->id?>" id="price<?php echo $contact->Potential_participates->id?>" value="<?php echo ($contact->Potential_participates->price!='0.00')?$contact->Potential_participates->price:'';?>" class='price'/>
						<a href="javascript://" onclick="updatePrice(<?php echo $contact->Potential_participates->id?>)" class='font-blue'>Save</a> <a href='javascript://' onclick="cancel(<?php echo $contact->Potential_participates->id?>)" class='font-blue'>Cancel</a>
					</div>
				</td>
				<td><input type="checkbox" name="potential_syndicates[]" <?php if($contact->Potential_participates->display==1):?>checked='checked' <?php endif;?> value="<?php echo $contact->Potential_participates->id?>" onclick="showBtnUpdate()"/></td>
			</tr>
			<?php		
					}
				}
			?>
		</tbody>
	</table>
	<a class='btn btn-default btn-mwidth pull-right mrg10T' id="updateSyndicate" style='display:none;' href='javascript://' onclick="potentialSyndicate()">Update Potential Syndicate</a>
	</div>
	</div>
	<div class="col-xs-12 ">
	<h3 class='mrg20T'>Revised Document</h3>
	<div class="col-xs-12" style="min-height:100px;max-height:300px;overflow-y:Scroll">
		<table class="table" class="table" id="datatable-contacts-sharing2">
			<thead>
				<tr>
					<td>#</td>
					<th>Company Name</th>
					<th>Name</th>
					<th>Email</th>
					<th>Message</th>
					<th>Non-Exclusive License</th>
					<th>Strategic License</th>
					<th>Request to Participate</th>
					<th>Program</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(isset($licensees->revised) && count($licensees->revised)>0){
						foreach($licensees->revised as $contact){
				?>
				<tr>
					<td><input type="radio" value="<?php echo $contact->Revised_documents->id?>"/></td>
					<td><?php echo (!empty($contact->Revised_documents->company_name))?$contact->Revised_documents->company_name:'';?></td>
					<td><?php echo (!empty($contact->Revised_documents->your_name))?$contact->Revised_documents->your_name:'';?></td>
					<td><?php echo $contact->Revised_documents->email_address; ?></td>
					<td><?php echo $contact->Revised_documents->message; ?></td>
					<td><?php if(!empty($contact->Revised_documents->license_aggreement)):
								$file = pathinfo($contact->Revised_documents->license_aggreement);
								$fileUrl = str_replace("/home/synpatne/public_html/synpat_com/","http://www.synpat.com/",$file['dirname']);
						?>
							<a class='font-blue' href="<?php echo $fileUrl.'/'.$file['basename'];?>" target="_BLANK"><?php echo $file['basename']?></a>
						<?php endif;?></td>
					<td><?php if(!empty($contact->Revised_documents->strategic_aggreement)):
								$file = pathinfo($contact->Revised_documents->strategic_aggreement);
								$fileUrl = str_replace("/home/synpatne/public_html/synpat_com/","http://www.synpat.com/",$file['dirname']);
						?>
							<a class='font-blue' href="<?php echo $fileUrl.'/'.$file['basename'];?>" target="_BLANK"><?php echo $file['basename']?></a>
						<?php endif;?></td>
					<td><?php if(!empty($contact->Revised_documents->request_aggreement)):
								$file = pathinfo($contact->Revised_documents->request_aggreement);
								$fileUrl = str_replace("/home/synpatne/public_html/synpat_com/","http://www.synpat.com/",$file['dirname']);
						?>
							<a class='font-blue' href="<?php echo $fileUrl.'/'.$file['basename'];?>" target="_BLANK"><?php echo $file['basename']?></a>
						<?php endif;?></td>
					<td>
						<?php if(!empty($contact->Revised_documents->program_aggreement)):
								$file = pathinfo($contact->Revised_documents->program_aggreement);
								$fileUrl = str_replace("/home/synpatne/public_html/synpat_com/","http://www.synpat.com/",$file['dirname']);
						?>
							<a class='font-blue' href="<?php echo $fileUrl.'/'.$file['basename'];?>" target="_BLANK"><?php echo $file['basename']?></a>
						<?php endif;?>
					</td>
					<td><?php echo (!empty($contact->Revised_documents->create_date))?date('Y-m-d',strtotime($contact->Revised_documents->create_date)):'';?></td>
					<td><a href="javascript://" onclick="sendConfirmation(jQuery(this),1);" class='font-blue'>Confirm</a><img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="" style='display:none;'></td>
				</tr>
				<?php		
						}
					}
				?>
			</tbody>
		</table>
	</div>
	</div>
</div> 
<script>

function showBtnUpdate(){
	if(jQuery("#updateSyndicate").css('display')=='none'){
		if(jQuery('input[name="potential_syndicates[]"]').is(':checked')==true){
			jQuery("#updateSyndicate").css('display','block');
		}	else {
			jQuery("#updateSyndicate").css('display','none');
		}		
	} else {
		if(jQuery('input[name="potential_syndicates[]"]').is(':checked')==false){
			jQuery("#updateSyndicate").css('display','none');
		}
	}
}

function potentialSyndicate(){
	var syn = [];
	jQuery('input[name="potential_syndicates[]"]').each(function(){
		if(jQuery(this).is(':checked') && jQuery(this).parent().parent().find('input.price').val()!=""){
			syn.push(jQuery(this).val());
		}
	});
	if(syn.length>0){
		if(window.parent.leadGlobal>0){
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>/opportunity/potential_syndicate',
				data:{p:JSON.stringify(syn),l:window.parent.leadGlobal},
				cache:false,
				success:function(data){
					
				}
			});
		} else {
			alert("Please select lead first.")
		}
	} else {
		alert("Please select checkbox and insert price first.");
	}
}




function findThreadData(t){
	if(window.parent.leadGlobal!=0){
		if(t!=""){
			window.parent.jQuery("#other_list_boxes").find('table').find('tr').each(function(){
				if(jQuery(this).attr('data-id')==t){
					window.parent.findOwnThread(t,jQuery(this).find('td').find('a'),3);
				}
			});
		}
	} else {
		alert("Please select lead first.");
	}
}

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
}


function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
	function insertPrice(d){
		jQuery("#update").css('display','none');
		if(jQuery("#updatePrice"+d).length>0){
			jQuery("#updatePrice"+d).css('display','');
			jQuery("#showPrice"+d).css('display','none');			
		}
	}
	function cancel(d){				
		if(jQuery("#price"+d).val()!=""){
			if(jQuery("#showPrice"+d).find('span').length>0){
				jQuery("#showPrice"+d).find('span').html(jQuery("#price"+d).val());
			} else {
				jQuery("#showPrice"+d).find('a').before('<span>'+jQuery("#price"+d).val()+'</span>');
			}
		} else {
			jQuery("#showPrice"+d).find('span').remove();
		}
		jQuery("#updatePrice"+d).css('display','none');
		jQuery("#showPrice"+d).css('display','');
	}
	function updatePrice(d){
		if(jQuery("#price"+d).val()!=""){
			jQuery.ajax({
				type:'POST',
				url:'<?php echo $Layout->baseUrl?>dashboard/updatePricePotential',
				data:{i:d,p:jQuery("#price"+d).val()},
				cache:false,
				success:function(data){
					if(parseInt(data)>0){
						window.location = window.location.href;
					} else {
						alert("Server busy. Try after sometime!.");
					}
				}
			}).fail(function(){
				alert("Server busy. Try after sometime!.");
			});
		}
	}
	function sendConfirmation(o,t){		 
		if(o.parent().parent().find('input[type="radio"]:checked').length>0 && window.parent.jQuery('input[name="sales_person[]"]:checked').length>0){
			if(window.parent.jQuery('input[name="sales_person[]"]:checked').length==1){
				if(t==0){
					l=o.parent().parent().find('input[type="radio"]:checked').val();
					p=window.parent.jQuery('input[name="sales_person[]"]:checked').val();
					o.css('display','none');
					o.parent().find('img').css('display','inline-block');
					le = window.parent.leadGlobal;
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $Layout->baseUrl?>dashboard/send_c',
						data:{i:l,l:le},
						cache:false,
						success:function(data){
							if(data!=""){
								_data = jQuery.parseJSON(data);
								if(_data.error==0){
									_fileURL = _data.file.alternateLink;
									_link = "https://docs.google.com/file/d/"+_data.file.id+"/preview";
									_mimeType = _data.file.mimeType;
									_icon = _data.file.iconLink;
									_title = _data.file.title;	
									_message= o.parent().parent().find('td').eq(5).text();
									o.parent().parent().remove();
									if(le==0){
										le = _data.lead_id;
										window.parent.leadGlobal = le;
									}
									updateSalesActivity(p,_message,_link,le,_data.file.id,_mimeType,_title);
									updatePotentialParticipant(l,le,_fileURL);
								}
							}					
						}
					});
				} else if(t==1){
					l=o.parent().parent().find('input[type="radio"]:checked').val();
					p=window.parent.jQuery('input[name="sales_person[]"]:checked').val();
					o.css('display','none');
					o.parent().find('img').css('display','inline-block');
					le = window.parent.leadGlobal;
					jQuery.ajax({
						type:'POST',
						url:'<?php echo $Layout->baseUrl?>dashboard/send_revised',
						data:{i:l,l:le,p:p},
						cache:false,
						success:function(data){
							if(data>0){
								window.location = __baseUrl+'dashboard/licensees';
							} else {
								alert("Server busy, Please try after sometime.");
							}					
						}
					});
				}
			} else {
				alert("Please select only one person");
			}
		} else {
			alert("Please select person and request to participate radio button.");
		}
	}
	
	function updateSalesActivity(p,message,fileUrl,le,icon,mime,title){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>customers/docketDocumentSalesActivity',
			data:{p:p,le:le,file:fileUrl,m:message,i:icon,mine:mime,title:title},
			cache:false,
			success:function(data){
				if(data>0){
					/*window.parent.jQuery("#anotherSys").val(data);*/
				}
			}
		});
	}
	function updatePotentialParticipant(l,le,fileUrl){
		jQuery.ajax({
			type:'POST',
			url:'<?php echo $Layout->baseUrl?>dashboard/potentialParticipant',
			data:{l:l,le:le,file:fileUrl},
			cache:false,
			success:function(data){
				if(data>0){
					/*window.parent.jQuery("#anotherSys").val(data);*/
					window.location = __baseUrl+'dashboard/licensees';
				}
			}
		});
	}
</script>