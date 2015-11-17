<div class="assets" style="background:#fff">
	<div class="row">
		<div class="col-md-12">
			<div class="topBlock" style="min-height:100px;border-bottom:1px solid #aaa;background:#fff">
				<!-- 				blocktop -->
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="list" style="height:100%;background:#fff">
				<?php 
					echo '<ul class="nav nav-pills nav-stacked">';
					for($i=0;$i<count($list);$i++){
						if($i==0){
							$selected="active";
						}else{
							$selected="";
						}
						echo "<li class='$selected''><a href='#' class='asset' data-val='".$list[$i]->sharecode."'>".$list[$i]->name."</a></li>";
					}
					echo "</ul>";
				?>				
			</div>
		</div>
		<div class="col-md-9">
			<div class="viewer" style="border-left:1px solid #aaa;">
				<div style="width: 100%; height: 500px; position: relative;">
					<iframe id="viewer" allowfullscreen frameborder="0" style="border:none; width:100%; height:500px" src="https://www.lucidchart.com/documents/embeddedchart/<?php if(count($list)>0) echo $list[0]->sharecode; ?>" id="jT20-I2lO~wy"></iframe>
					<a href="https://www.lucidchart.com/pages/examples/mind_mapping_software" style="margin: 0; padding: 0; border: none; display: inline-block; position: absolute; bottom: 5px; left: 5px;">
						<img alt="mind mapping software" title="Lucidchart online diagrams" style="width: 100px; height: 30px; margin: 0; padding: 0; border-image: none; border: none; display: block" src="https://www.lucidchart.com/img/diagrams-lucidchart.png" />
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script >
	$('.asset').click(function(){
		$('ul li').removeClass('active');
		$(this).parent('li').addClass('active');
		if($(this).attr('data-val'))
		$('#viewer').attr('src','https://www.lucidchart.com/documents/embeddedchart/'+$(this).attr('data-val'));
	})
</script>