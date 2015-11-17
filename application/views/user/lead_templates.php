<style>
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

	.dataTables_info,
	.dataTables_filter {
		display: none;
	}

	#db_from_litigation_iframe {
		margin-top: 20px;
	}

</style>
<div class='col-lg-12'>
	
	<a id="btnActivityAll" class="btn btn-primary pull-right mrg5L" onclick="startCampaign(<?php echo $s?>)" style='width:100px;'>Start</a>
	<a id="btnNewTemplate" class="btn btn-primary pull-right mrg5L" onclick="window.parent.closeSlideBarLeftMessagePredfined();window.parent.getPredefinedMessages(3)" style='float:right;width:175px;'>Add a new teamplate</a>
	<h2>Email Campaign / LinkedIn Campaign</h2>
	<p class="mrg10B mrg20T">
		1) Click on the name of the messages to see their contents.<br/>
		2) Select the HTML message and the Text message to be included in the campaign.<br/>
		3) Click START to start the campaign.<br/>
	</p>
	<div class='col-lg-6'>
	<h3>Text Message</h3>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation1" >
		<thead>
			<tr>
				<th>#</th>                           
				<th>Subject</th>                           
				<th>Template name</th>  
			</tr>
		</thead>
		<tbody>
			<?php 
				if(count($lead_templates_linkedin)>0){
					foreach($lead_templates_linkedin as $lit){
		?>
					<tr data-item-idd="<?php echo $lit->id;?>">
						<td><input name='text_message' data-subject="<?php echo $lit->subject?>" type="radio" onclick="appendText(jQuery(this),'<?php echo $lit->file_name;?>',<?php echo $s?>,1)"/></td>
						<td><?php echo $lit->subject?></td>
						<td><!--<a href="<?php echo $lit->file_name?>" target="_blank">--><?php echo $lit->name?><!--</a>--></td>					
					</tr>
		<?php
					}
				}
			?>
		</tbody>
	</table>
	</div>
	<div class='col-lg-6'>
	<h3>HTML Message</h3>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" >
		<thead>
			<tr>
				<th>#</th>                           
				<!--<th>Subject</th>        -->                   
				<th>Template name</th>  
			</tr>
		</thead>
		<tbody>
			<?php 
				if(count($lead_templates_email)>0){
					foreach($lead_templates_email as $lit){
		?>
					<tr data-item-idd="<?php echo $lit->id;?>">
						<td><input name='predefine_message' data-subject="<?php echo $lit->subject?>" type="radio" onclick="appendText(jQuery(this),'<?php echo $lit->file_name;?>',<?php echo $s?>,0)"/></td>
						<!--<td><?php echo $lit->subject?></td>-->
						<td><!--<a href="<?php echo $lit->file_name?>" target="_blank">--><?php echo $lit->name?><!--</a>--></td>					
					</tr>
		<?php
					}
				}
			?>
		</tbody>
	</table>
	</div>	
	<div id="db_from_litigation_iframe1" class='col-lg-12'></div>
	<div id="db_from_litigation_iframe" class='col-lg-12 mrg5T'></div>
</div>
<script> 
	_profileText = '';
	_htmlTemplate = '';
	_textTemplate = '';
	_subject = '';
	function appendText(o,n,s,d){
		if(d==0){
			height = $('#db_from_litigation_iframe').height();
			// console.log(url);

			$('#db_from_litigation_iframe').html('<iframe src="' + n + '" width="100%" height="' + height + 'px" scrolling="yes"></iframe>');
			if(s==2){
				_profileText = n;
				_htmlTemplate = n;
			} else if(s==1){
				jQuery.ajax({
					type:'POST',
					url:__baseUrl+'users/template_file_content',
					data:{name:n,s:s},
					cache:false,
					statusCode: {
						502: function () {
							appendText(o,n,s,d)
						}
					},
					success:function(data){
						_profileText = data;
						if(data!=""){
							_htmlTemplate = n;
						}
					}
				});
			}
		} else if(d==1){
			height = $('#db_from_litigation_iframe1').height();
			// console.log(url);

			$('#db_from_litigation_iframe1').html('<iframe src="' + n + '" width="100%" height="' + height + 'px" scrolling="yes"></iframe>');
			_subject = o.attr('data-subject');
			jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/template_file_content',
			data:{name:n,s:2},
			cache:false,
			statusCode: {
				502: function () {
					appendText(o,n,s,d)
				}
			},
			success:function(data){
				_textTemplate = data;				
			}});
		}
		/*jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/template_file_content',
			data:{name:n,s:s},
			cache:false,
			success:function(data){
				if(data==""){
					dd = confirm("File not found. Do you want to proceed further?");
					if(dd){
						if(s==1){
							window.parent.sendEmailImap(data,'');
						} else if(s==2){
							window.parent.sendLinkedMessage(data);
						}
					}
				} else {
					if(s==1){
						window.parent.sendEmailImap(data,n);
					} else if(s==2){
						window.parent.sendLinkedMessage(data);
					}
				}				
			}
		});*/
	}

	function startCampaign(s){
		_profileText = _textTemplate +' '+_profileText;
		if(s==1){
			window.parent.sendEmailImap(_profileText,_htmlTemplate,_subject);
		} else if(s==2){
			window.parent.sendLinkedMessage(_profileText,_htmlTemplate,_subject);
		}
	}

	var ___table ;
	jQuery(document).ready(function(){
		_h = window.parent.$(window).height() - 120;
		/*___table = $('#db_from_litigation,#db_from_litigation1')
			.DataTable({
				"searching":true,
				"autoWidth": true,
				"paging": false,
				// "sScrollY": _h+"px",
				"sScrollY": '100px',
				"sScrollX": "100%",
				"sScrollXInner": "100%"
			});*/
	});

	window.resizeDataTable = function(height) {
		$('#db_from_litigation_wrapper .dataTables_scrollBody').height(height / 2 - 85);
		$('#db_from_litigation_iframe').height(height / 2 - 85);
		$('#db_from_litigation_iframe1').height(height / 2 - 85);

		if($('#db_from_litigation_iframe iframe').length) {
			$('#db_from_litigation_iframe iframe').height($('#db_from_litigation_iframe').height());
		}
		if($('#db_from_litigation_iframe1 iframe').length) {
			$('#db_from_litigation_iframe1 iframe').height($('#db_from_litigation_iframe1').height());
		}
	}

	$(function() {
		parent.leadTemplatesResize();
	})
	/*
	$(function() {
		$('#db_from_litigation tbody a').on('click', function() {
			var url = $(this).attr('href'),
				height = $('#db_from_litigation_iframe').height();
			$('#db_from_litigation_iframe').html('<iframe src="' + url + '" width="100%" height="' + height + 'px" scrolling="yes"></iframe>');

			return false;
		});
	});
	*/
</script>