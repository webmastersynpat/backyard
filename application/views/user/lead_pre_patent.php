<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.css">
<link rel="stylesheet" media="screen" href="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.css">
<script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/handsontable.full.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/bootstrap-typeahead.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jquery.autoresize.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
<script src="<?php echo $Layout->baseUrl?>public/assets/lib/jQuery-contextMenu/jquery.ui.position.js"></script>	
<?php 
$prePatents = preg_replace('/\\\\\"/',"\"", $prePatents);
$prePatents = json_decode(substr($prePatents,1,-1));
$patentCount = 0;
$applicationCount = 0;
?>		
<script>
jQuery(document).ready(function(){
__data2=[
		<?php
			
			if(count($prePatents)>0):
			for ($i = 0; $i < count($prePatents); $i++) {
				$patentCount = $patentCount + (int) $prePatents[$i]->patent;
				$applicationCount = $applicationCount + (int) $prePatents[$i]->application;
				echo "['" . $prePatents[$i]->country . "','".$prePatents[$i]->patent."','".$prePatents[$i]->application."']";
				if ($i < count($prePatents) - 1) {
					echo ",";
				}
			}
			endif;
		?>
	];

var $container2 = jQuery("#table_chart_left");
$container2.handsontable({
	startRows: 1,
	startCols: 3,
	colHeaders: ['Country', 'Patents','Application'],
	minSpareCols: 0,
	minSpareRows: 1,
	colWidths: [100, 150, 100],
	manualColumnResize: true,
	contextMenu: false,
	columns: [
				{},
				{},															
				{}															
			]
});
jQuery("#table_chart_left").data('handsontable').loadData(__data2);
});

function validateDataChart(){
	var handsontable = jQuery("#table_chart_left").handsontable("getData");	
	otherChartLeft = JSON.stringify(handsontable);		
	jQuery("#left_chart_patent").val(otherChartLeft);
}
</script>
<style>
	body {
		min-width: 0;
	}
	#page-content {
	    background: #ffffff !important;
	}
</style>
<div class="col-sm-4">
	<?php echo form_open('opportunity/patents_charts_from_lead',array('class'=>'form-horizontal form-flat','role'=>'form','id'=>'formPatentCharts', 'style'=>'margin-bottom: 0;','onsubmit'=>"validateDataChart();"));?>
	<label class="col-sm-12">Patents</label>
	<div class="col-sm-12" style='margin-top:5px;width:400px;' id="table_chart_left">
		
	</div>
	<?php 
		if($lead>0):
	?>
	
	<div class="col-sm-4">
		<table class="table" style="margin-bottom: 5px; margin-top: -4px; width: 396px;">
			<tr>
				<td class="text-right" style="width: 100px;"><b>Total:</b></td>
				<td style="width: 100px;"><?php echo $patentCount;;?></td>
				<td style="width: 100px;"><?php echo $applicationCount;?></td>
			</tr>
		</table>
	</div>

	<div class="col-sm-4">
	<input type="hidden" name="lead" value="<?php echo $lead;?>"/>
	<input type="hidden" name="left_chart_patent" id="left_chart_patent" value=""/>
	<button type="submit" class="btn btn-primary">Save</button>
	</div>
	<?php
		endif;
	?>
	<?php echo form_close();?>
</div>
