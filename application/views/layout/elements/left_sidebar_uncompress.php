		<div id="page-sidebar">
    <div class="scroll-sidebar"> 
        <ul id="sidebar-menu">
	<?php 

	if($this->session->userdata['type']!='9'){
	/*$menuArray = array();
	$menus = get_menu_arr();
	$i=0;
	foreach($menus as $menu)
	{

		$page_name = explode(">",$menu->page_name);
		if(count($page_name) == 3){
			if(!isset($menuArray[trim($page_name[0])])){
				$menuArray[trim($page_name[0])] = array();
			}						
			if(is_array($menuArray) && count($menuArray)>0){
				if(count($menuArray[trim($page_name[0])])>0){
					if(!isset($menuArray[trim($page_name[0])][trim($page_name[1])])){
						$menuArray[trim($page_name[0])][trim($page_name[1])]=array();
					} 					
				} else {
					$menuArray[trim($page_name[0])][trim($page_name[1])]=array();
				}				
				if(!isset($menuArray[trim($page_name[0])][trim($page_name[1])][trim($page_name[2])])){
					$menuArray[trim($page_name[0])][trim($page_name[1])][trim($page_name[2])]=$menu->page_url;
				}					
			} else {
				$menuArray[trim($page_name[0])][trim($page_name[1])][trim($page_name[2])]=array();
			}	
		} else if(count($page_name) == 1){	
			$menuArray [trim($page_name[0])]= $menu->page_url;
		} else if(count($page_name) == 2){
			if(!isset($menuArray[trim($page_name[0])])){
				$menuArray[trim($page_name[0])] = array();
			} 
			if(is_array($menuArray) && count($menuArray)>0){
				if(count($menuArray[trim($page_name[0])])>0){
					if(!isset($menuArray[trim($page_name[0])][trim($page_name[1])])){
						$menuArray[trim($page_name[0])][trim($page_name[1])]=$menu->page_url;
					} 					
				} else {
					$menuArray[trim($page_name[0])][trim($page_name[1])]=$menu->page_url;
				}
			} else {
				$menuArray[trim($page_name[0])][trim($page_name[1])]=$menu->page_url;
			}		
		}
		$i++;
	}
	foreach($menuArray as $key=>$value){
		if(count($value)>0 && is_array($value)){
?>	
			<li>
			<a href="#" title="Forms UI">
				<i class="glyph-icon <?php  if($key=='Leads'):?>icon-linecons-eye<?php elseif($key=='General Setting'):?> icon-linecons-diamond<?php endif;?>"></i>
				<span><?php echo $key;?></span>
			</a>
			<div class="sidebar-submenu">
					<ul>
					<?php
						foreach($value as $key1=> $val){
							?>
							<li>
							<?php	if(count($val) >= 1){ ?>
								<?php echo anchor('#','<span>'.$key1.'</span>',array('title'=>$key1))?>

								<div class="sidebar-submenu">

								<ul>

								<?php

								foreach($val as $key2=>$val1)

								{

									echo '<li>'.anchor($val1,'<span>'.$key2.'</span>',array('title'=>$key2)).'</li>';

								}

								?>

								</ul>

								</div>

							

							<?php

							}

							else{  echo anchor($val,'<span>'.$key1.'</span>',array('title'=>$key1));

							}?>

							</li>

							<?php

						}

						?>

					</ul>

					

				

			</div>

		</li>

<?php

		}else {

?>

		<li class="no-menu">
			<?php echo anchor($value,'<i class="glyph-icon icon-linecons-beaker"></i><span>'.$key.'</span>', array('title' => 'Opportunities'));?>		
		</li>

<?php

		}

		

	}
*/
	} else {

	?>
	<li>

		<a href="#" title="General Settings">

			<i class="glyph-icon icon-linecons-diamond"></i>

            <span>General Settings</span>

		</a>
   
		<div class="sidebar-submenu">

			<ul>

				<li><?php echo anchor('general/forms_data','<span>Forms Data</span>', array('title' => 'Forms Data'));?></li>
				
				<li><?php echo anchor('general/manage_opportunity_type','<span>Document Selections</span>', array('title' => 'Manage Opportunites Type'));?></li>

				<li><?php echo anchor('general/manage_sectors','<span>Manage Sectors</span>', array('title' => 'Manage Sectors'));?></li>
				
				<li><?php echo anchor('general/manage_technologies','<span>Manage Technologies</span>', array('title' => 'Manage Technologies'));?></li>

				<li><?php echo anchor('general/user_permissions','<span>User Permissions</span>', array('title' => 'User Permissions'));?></li>

				<li><?php echo anchor('general/create_an_opportunity','<span>Manage Opportunity</span>', array('title' => 'Manage Opportunity'));?></li>

				<li><?php echo anchor('general/add_user','<span>Add User</span>', array('title' => 'Add User'));?></li>

				<li><?php echo anchor('general/user_activities','<span>Users Activities</span>', array('title' => 'All Activity List'));?></li>
				
				<li><?php echo anchor('general/user_timeline','<span>Users Timelines</span>', array('title' => 'All Activity List'));?></li>

				<li><?php echo anchor('general/manage_leads','<span>Manage Leads</span>', array('title' => 'Manage Leads'));?></li>
				
				<li><?php echo anchor('general/manage_stages','<span>Manage Stages</span>', array('title' => 'Manage Button Stages'));?></li>
                
                <li><?php echo anchor('general/manage_task','<span>Manage Task</span>', array('title' => 'Manage Task'));?></li>
				
                <li><?php echo anchor('general/button_boxes','<span>Manage Button List</span>', array('title' => 'Manage Button List'));?></li>
				
                <li><?php echo anchor('general/email_templates','<span>Manage Email Templates</span>', array('title' => 'Manage Email Templates'));?></li>

			</ul>

		</div>

	</li>

	<?php } ?>
	</ul><!-- #sidebar-menu -->
		
    </div>

</div>

     