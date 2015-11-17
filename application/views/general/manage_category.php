<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Manage Sectors</li>");
});
</script> 
<div class="panel dashboard-box">
    <div class="panel-body">
		<?php 
			if($this->session->flashdata('message')){
				echo $this->session->flashdata('message');	
			}
		?>
		<div class="example-box-wrapper">
		    <h3 class="title-hero">Manage Category</h3>
			<?php echo form_open('general/manage_category',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'))?>
				<?php 
					$id =0;
					$name = "";
					$subcategories = array();
					if(isset($category_data) && count($category_data)>0){
						$name = $category_data->name;
						$id = $category_data->id;
						$subcategories = $sector_category;						
					}					
				?>
				<div class="row row-width">
					<div class="col-width" style="width:400px;">
						<div class="form-group input-string-group" style="margin-left: 0;">
		                    <label class="control-label">Category:</label>
							<select name="category[id]" id="categoryId" class="form-control" onchange="getCategoryData(jQuery(this).val())">
								<option>-- Select Category --</option>
								<?php 
									if(count($categories)>0):
									$i=0;
									foreach($categories as $sector):
										$selected="";
										if($id == $sector->id){
											$selected="selected='selected'";
										}
								?>
									<option value='<?php echo $sector->id;?>' <?php echo $selected;?>><?php echo $sector->name?></option>	
								<?php  endforeach;?>
								<?php endif;?>
							</select>
		                </div>
					</div>
				</div>
				<div class="row mrg10T">
					<label class="col-sm-12 control-label">Sub Categories:</label>
					<div class="col-sm-12 mrg5T" style="padding-right:11px;">
						<select class="multi-select" multiple id="marCategoryId" name="mar[category_id][]">							
							<?php 
								$market_sectors = getAllSubCategories();
								if(count($market_sectors)>0){									
									foreach($market_sectors as $sector){
										$checked="";
										if(count($subcategories)){
											foreach($subcategories as $category){
												if($sector->id ==$category->id){
													$checked="selected='selected'";
												}
											}
										}							
							?>
										<option <?php echo $checked;?> value="<?php echo $sector->id;?>"><?php echo $sector->name;?></option>
							<?php
									}
								}
							?>
							
						</select>                                
					</div>
				</div>
				<div class="mrg5T">	
					<input type="hidden" name="trig_case" value="5"/>
					<button type="submit" class="btn btn-primary btn-mwidth">Save</button>
				</div>
			<?php echo form_close();?>
		</div>		
	</div>
</div>
<script>
	function getCategoryData(ID){
		window.location = window.location.href+"/"+ID;
	}
</script>