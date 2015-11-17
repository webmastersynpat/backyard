<script>
jQuery(document).ready(function(){
	jQuery('.breadcrumb').html("<li><a>General</a></li><li class='active'>Manage Sectors</li>");
	$(function() {
		parent.open_sector_searchResize();
	})
});
</script>
<style>
	body {
		overflow: auto !important;
		min-width: 0;
		width: 100% !important;
	}
	#page-content {
	    background: #ffffff !important;
	}
	
.active_s, .active_s td{
	background:#2196F3 !important;
}
.active_s td a{
	color:#fff;
}
</style>
<div class="panel dashboard-box">
    <div class="panel-body">
		<?php 
			if($this->session->flashdata('message')){
				echo $this->session->flashdata('message');	
			}
		?>
		<?php 
			$id =0;
			$name = "";
			$depcategories = array();
			if(isset($sector_data) && count($sector_data)>0){
				$name = $sector_data->name;
				$id = $sector_data->id;
				$depcategories = $sector_category;
			}					
		?>
		<div class="example-box-wrapper" id="sector">
		    <h3 class="title-hero">Sector</h3>
			<?php echo form_open('general/add_sectors',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'));?>
			
			<div class="row row-width">
				<div class="col-width" style="width:400px;">
					<div class="form-group input-string-group" style="margin-left: 0;">
						<label class="control-label">Create a new Sector:</label>
						<input type="text" name="sector[name]" value="<?php echo $name; ?>" id="sectorName" required class='form-control'/>
					</div>
				</div>
			</div>
			<div class="mrg5T">
				<input type="hidden" name="sector[id]" id="sectorId" value="<?php echo $id;?>"/>
				<input type="hidden" name="trig_case" value="1"/>
				<button type="submit" class="btn btn-primary btn-mwidth">Save new sector</button>
			</div>
			<?php echo form_close();?>
		</div>
		<div class="example-box-wrapper" >
			<div class="col-sm-3">
				<div style='overflow-y:scroll;height:300px;'>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
						<thead>
							<tr>
								<th>Name</th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							if(count($sectors)>0):
							$i=0;
							foreach($sectors as $sector):
						?>				
						<tr class="<?php if($i%2==0):?>even<?php else:?>odd<?php endif;?> gradeX  <?php if($id>0 && $id==$sector->id):?> active_s<?php endif;?>  ">
							<td><a href='javascript://'  onclick="getCategoryData(<?php echo $sector->id;?>,1,jQuery(this))"><?php echo $sector->name;?></td>
							<td class="text-center"><?php echo anchor('general/delete_sector/'.$sector->id,'<i class="glyph-icon icon-close"></i>', array("class"=>'','onclick' => "return confirm('Do you want delete this record')"))?></td>
						</tr>
						<?php $i++; endforeach;?>
						<?php else:?>
						<tr colspan="2"><p class='alert'>No record found!</p></tr>
						<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5">
				<div class="example-box-wrapper">
					<h3 class="title-hero">Add/Remove categories to/from selected sector</h3>
					<?php echo form_open('general/manage_sectors',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'))?>
						
						
						<input type="hidden" name="sector[id]" id="updatesectorId" value="<?php echo $id;?>"/>						
						<div class="row ">
							<label class="col-sm-12 control-label">Catgories:</label>
							<div class="col-sm-12 mrg5T" style="padding-right:11px;">
								<select class="multi-select" multiple id="marCategoryId" name="mar[category_id][]">							 
									<?php 
										$market_sectors = getAllCategories();
										if(count($market_sectors)>0){									
											foreach($market_sectors as $sector){
												$checked="";
												if(count($depcategories)){
													foreach($depcategories as $cate){
														if($sector->id ==$cate->category_id){
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
							<input type="hidden" name="trig_case" value="4"/>
							<button type="submit" class="btn btn-primary btn-mwidth">Update Sector</button>
						</div>
					<?php echo form_close();?>
				</div>	
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>

    <div class="panel-body">		
			
	</div>


    <div class="panel-body">
		<?php 
			$id =0;
			$name = "";
			$subcategories = array();
			if(isset($category_data) && count($category_data)>0){
				$name = $category_data->name;
				$id = $category_data->id;
				$subcategories = $cat_category;						
			}					
		?>
		<div class="example-box-wrapper" id="category" style='border-top:1px solid #d1c8c8;'>
		    <h3 class="title-hero mrg10T">Categories</h3>
			<?php echo form_open('general/add_category',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'));?>
			
			<div class="row row-width">
				<div class="col-width" style="width:400px;">
					<div class="form-group input-string-group" style="margin-left: 0;">
						<label class="control-label">Create a new Category:</label>
						<input type="text" name="category[name]" value="<?php echo $name; ?>" id="categoryName" required class='form-control'/>
					</div>
				</div>
			</div>
			<div class="mrg5T">
				<input type="hidden" name="category[id]" id="categoryId" value="<?php echo $id;?>"/>
				<input type="hidden" name="category[type]" id="categoryType" value="0"/>
				<input type="hidden" name="trig_case" value="2"/>
				<button type="submit" class="btn btn-primary btn-mwidth">Save new category</button>
			</div>
			<?php echo form_close();?>
		</div>
		<div class="example-box-wrapper" >
			<div class="col-sm-3">
				<div style='overflow-y:scroll;height:300px'>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
						<thead>
							<tr>
								<th>Name</th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							if(count($categories)>0):
							$i=0;
							foreach($categories as $cat):
						?>				
						<tr class="<?php if($i%2==0):?>even<?php else:?>odd<?php endif;?> gradeX <?php if($id>0 && $id==$cat->id):?> active_s<?php endif;?>">
							<td><a href='javascript:void(0)' onclick="getCategoryData(<?php echo $cat->id?>,0,jQuery(this))"><?php echo $cat->name?></a></td>
							<td class="text-center"><?php echo anchor('general/delete_category/'.$cat->id,'<i class="glyph-icon icon-close"></i>', array("class"=>'','onclick' => "return confirm('Do you want delete this record')"))?></td>
						</tr>
						<?php $i++; endforeach;?>
						<?php else:?>
						<tr colspan="2"><p class='alert'>No record found!</p></tr>
						<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5">
				<h3 class="title-hero">Add/Remove sub categories to/from selected category</h3>
				<?php echo form_open('general/manage_category',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'))?>
				<input type="hidden" name="category[id]" id="updatecategoryId" value="<?php echo $id;?>"/>
				<div class="row">
					<label class="col-sm-12 control-label">Sub Categories:</label>
					<div class="col-sm-12 mrg5T" style="padding-right:11px;">
						<select class="multi-select1" multiple id="marSubCategoryId" name="mar[category_id][]">							
							<?php 
								$market_sectors = $subcategory;
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
					<button type="submit" class="btn btn-primary btn-mwidth">Update Category</button>
				</div>
			<?php echo form_close();?>
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>

<script>
	function getCategoryData(ID,type,o){
		o.parent().parent().parent().find('tr').removeClass('active_s');
		o.parent().parent().addClass('active_s');
		jQuery("#updatesectorId").val(ID);
		jQuery("#updatecategoryId").val(ID);
		jQuery.ajax({
			type:'POST',
			url:__baseUrl+'general/getDataFromAjax/'+ID+'/'+type,
			cache:false,
			success:function(data){
				_data = jQuery.parseJSON(data);
				if(type==1){					
					jQuery("#marCategoryId").find("option").removeAttr("selected");
					if(_data.list.length>0){
						for(i=0;i<_data.list.length;i++){
							jQuery("#marCategoryId").find("option").each(function(){
								if(jQuery(this).attr("value")==_data.list[i].category_id){
									jQuery(this).attr("selected","selected");
								}
							})
						}						
					}
					jQuery("#marCategoryId").multiSelect("refresh");
					jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
				} else if(type==0){
					jQuery("#marSubCategoryId").find("option").removeAttr("selected");
					if(_data.list.length>0){
						for(i=0;i<_data.list.length;i++){
							jQuery("#marSubCategoryId").find("option").each(function(){
								if(jQuery(this).attr("value")==_data.list[i].id){
									jQuery(this).attr("selected","selected");
								}
							})
						}						
					}
					jQuery("#marSubCategoryId").multiSelect("refresh");
					jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
				}
			}
		});
		/*window.location = __baseUrl+'general/manage_sectors/'+ID+'/1';*/
	}
	function getManageCategoryData(ID){
		window.location = __baseUrl+'general/manage_sectors/'+ID+'/0';
	}
</script>

    <div class="panel-body">
		
		<div class="example-box-wrapper" id="subcategory" style='border-top:1px solid #d1c8c8;'>
		    <h3 class="title-hero mrg10T">Sub a Categories</h3>
			<?php echo form_open('general/add_subcategory',array('class'=>"form-horizontal form-flat bordered-row", 'style'=>'margin-bottom: 0'));?>
			<?php 
					$id =0;
					$name = "";
					if(isset($subcategory_data) && count($subcategory_data)>0){
						$name = $subcategory_data->name;
						$id = $subcategory_data->id;
					}					
				?>
			<div class="row row-width">
				<div class="col-width" style="width:400px;">
					<div class="form-group input-string-group" style="margin-left: 0;">
						<label class="control-label">Create new Sub Category:</label>
						<input type="text" name="subcategory[name]" value="<?php echo $name; ?>" id="subcategoryName" required class='form-control'/>
					</div>
				</div>
			</div>
			<div class="mrg5T">
				<input type="hidden" name="subcategory[id]" id="subcategoryId" value="<?php echo $id;?>" />
				<input type="hidden" name="subcategory[type]" id="subcategoryType" value="1"/>
				<input type="hidden" name="trig_case" value="3"/>
				<button type="submit" class="btn btn-primary btn-mwidth">Save new sub category</button>
			</div>
			<?php echo form_close();?>
		</div>
		<div class="col-sm-3">
			<div class="example-box-wrapper" style="overflow-y:scroll;height:300px;">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
					<thead>
						<tr>
							<th>Name</th>
							<th width="50px">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(count($subcategory)>0):
						$i=0;
						foreach($subcategory as $sector):
					?>				
					<tr class="<?php if($i%2==0):?>even<?php else:?>odd<?php endif;?> gradeX">
						<td><?php echo $sector->name?></td>
						<td class="text-center"><?php echo anchor('general/manage_sectors/'.$sector->id.'/2','<i class="glyph-icon icon-edit"></i>', array("class"=>''))?> / <?php echo anchor('general/delete_subcategory/'.$sector->id,'<i class="glyph-icon icon-close"></i>', array("class"=>'','onclick' => "return confirm('Do you want delete this record')"))?></td>
					</tr>
					<?php $i++; endforeach;?>
					<?php else:?>
					<tr colspan="2"><p class='alert'>No record found!</p></tr>
					<?php endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>jQuery(document).ready(function(){jQuery(".multi-select1,.multi-select").multiSelect();jQuery(".ms-container").append('<i class="glyph-icon icon-exchange"></i>')});</script>