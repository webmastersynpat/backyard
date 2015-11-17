<div id="page-sidebar">
<div class="scroll-sidebar">
<ul id="sidebar-menu">
<?php 

	if($this->session->userdata['type']!='9'){

	} else {

	?>
<li>
<a href="#" title="General Settings">
<i class="glyph-icon icon-linecons-diamond"></i>
<span>General Settings</span>
</a>
<div class="sidebar-submenu" style="height:300px;overflow-y:scroll;">
<ul>
<li><?php echo anchor('general/forms_data','<span>Forms Data</span>', array('title' => 'Forms Data'));?></li>
<li><?php echo anchor('general/transaction','<span>Transaction</span>', array('title' => 'Transaction'));?></li>
<li><?php echo anchor('general/manage_opportunity_type','<span>Document Selections</span>', array('title' => 'Manage Opportunites Type'));?></li>
<li><a href='javascript://' onclick="getSectorsPage()" title="Manage Sectors / Categories"><span>Manage Sectors / Categories</span></a><?php /*echo anchor('#','', array('title' => 'Manage Sectors / Categories','onclick'=>'getSectorsPage()'));*/?></li>
<!--<li><?php echo anchor('general/manage_technologies','<span>Manage Technologies</span>', array('title' => 'Manage Technologies'));?></li>-->
<li><?php echo anchor('general/user_permissions','<span>User Permissions</span>', array('title' => 'User Permissions'));?></li>
<li><?php echo anchor('general/create_an_opportunity','<span>Manage Opportunity</span>', array('title' => 'Manage Opportunity'));?></li>
<li><?php echo anchor('general/add_user','<span>Add User</span>', array('title' => 'Add User'));?></li>
<li><?php echo anchor('general/user_activities','<span>Users Activities</span>', array('title' => 'All Activity List'));?></li>
<li><?php echo anchor('general/user_timeline','<span>Users Timelines</span>', array('title' => 'All Activity List'));?></li>
<li><?php echo anchor('general/user_timeline_table','<span>Users Timelines(Table View)</span>', array('title' => 'All Activity List'));?></li>
<li><?php echo anchor('general/manage_leads','<span>Manage Leads</span>', array('title' => 'Manage Leads'));?></li>
<li><?php echo anchor('general/manage_stages','<span>Manage Stages</span>', array('title' => 'Manage Button Stages'));?></li>
<li><?php echo anchor('general/manage_task','<span>Manage Task</span>', array('title' => 'Manage Task'));?></li>
<li><?php echo anchor('general/button_boxes','<span>Manage Button List</span>', array('title' => 'Manage Button List'));?></li>
<li><?php echo anchor('general/email_templates','<span>Manage Text Templates</span>', array('title' => 'Manage Text Templates'));?></li>
<li><?php echo anchor('general/customer_request','<span>Store Customer Request</span>', array('title' => 'Store Customer Request'));?></li>
<li><?php echo anchor('customers/companies_list','<span>Store Customer List</span>', array('title' => 'Store Customer List'));?></li>
</ul>
</div>
</li>
<?php } ?>
</ul>
</div>
</div>