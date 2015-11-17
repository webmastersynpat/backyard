<?php 

$templates = getAllTemplates();

?>

<style> 
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}

</style>
<div class='col-lg-12'>
<p class='mrg10B'>Select one of the following templates in order to edit it or create a new template based on the selected one:</p>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="db_from_litigation" >
	<thead>
		<tr>
			<th>#</th>                           
			<th>Subject</th>                           
			<th>Template Name</th>                           
			<th>Description</th>  
		</tr>
	</thead>
	<tbody>  
		<?php 
			if(count($templates)>0){
				foreach($templates as $lit){
	?>
				<tr data-item-idd="<?php echo $lit->id;?>">
					<td><input name='predefine_message' data-name="<?php echo $lit->template_name;?>" data-subject="<?php echo $lit->subject;?>" type="radio" onclick="appendText(jQuery(this),<?php echo $t;?>)"/><a href='javascript://' onclick='deletePreTemplate(<?php echo $lit->id;?>)'><i class="glyph-icon"><img src="<?php echo $Layout->baseUrl?>public/images/discard.png" style="opacity:0.55"></i></a></td>					
					<td><?php echo $lit->subject;?></td>					
					<td><?php echo $lit->template_name;?></td>					
					<td><?php echo $lit->template_html?></td>					
				</tr>
	<?php
				}
			}
		?>
	</tbody>
</table>
</div>
<script>
	function appendText(o,t){
		_text = o.parent().next().next().next().text();
		_html = o.parent().next().next().next().html();
		switch(t){
			case 1:
			default:
				window.parent.$("#emailMessage").code(_html+"<br/><br/><br/><br/><br/>"+window.parent.$("#original_signature").val());
				window.parent.$("#emailSubject").val(o.attr("data-subject"));
			break;
			case 2:
				window.parent.jQuery('textarea[name="event[description]"]').val(_text);
			break;
			case 3:
				window.parent.openTemplateEditor();
				window.parent.jQuery("#templateEditor").code(_html);
				window.parent.jQuery('#template_id').val(o.parent().parent().attr('data-item-idd'));
				window.parent.jQuery('#template_subject').val(o.attr('data-subject'));
				window.parent.jQuery('#template_file_name').val(o.attr('data-name'));
			break;
		}
	}
	
	function deletePreTemplate(d){
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'users/delete_predefined_template',
			data:{id:d},
			cache:false,
			success:function(b){
				if(b>0){
					window.parent.document.getElementById("predefineFormIframe").contentWindow.location.reload();
				} else {
					alert("Please try after sometime");
				}
			}
		})
	}
</script>