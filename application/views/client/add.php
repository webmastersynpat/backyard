<!-- Start right content -->
        <div class="content-page">
			<!-- ============================================================== -->
			<!-- Start Content here -->
			<!-- ============================================================== -->
            <div class="content">
								<!-- Page Heading Start -->
				<div class="page-heading">
            		<h1><i class='fa fa-check'></i> New Client</h1>
            	</div>
            	<!-- Page Heading End-->	
								
				<!-- Your awesome content goes here -->
				<div class="row">
					<div class="col-sm-12 portlets">
						
						<div class="widget">
							<div class="widget-header transparent">
								<!--<h2><strong>Horizontal</strong> Form</h2>-->
								<div class="additional-btn">
									<a href="#" class="hidden reload"><i class="icon-ccw-1"></i></a>
									<a href="#" class="widget-toggle"><i class="icon-down-open-2"></i></a>
									<!--<a href="#" class="widget-close"><i class="icon-cancel-3"></i></a>-->
								</div>
							</div>
							<div class="widget-content padding">	
								<?php 
									if($this->session->flashdata('message')){
								?>
									<p class='alert alert-success'><?php echo $this->session->flashdata('message');?></p>
								<?php					
									}
								?>
								<?php 
									if($this->session->flashdata('error')){
								?>
									<p class='alert alert-danger'><?php echo $this->session->flashdata('error');?></p>
								<?php					
									}
								?>
								<div id="horizontal-form">
									<?php echo form_open('client/add',array('class'=>'form-horizontal','role'=>'form'));?>
									  <div class="form-group">
										<label for="clientName" class="col-sm-2 control-label">Name</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'client[name]','id'=>'clientName','placeholder'=>'Name','class'=>'form-control'));?>										  
										  <!--<p class="help-block">Example block-level help text here.</p>-->
										</div>
									  </div>
									  <div class="form-group">
										<label for="clientAddress" class="col-sm-2 control-label">Address</label>
										<div class="col-sm-10">
										<?php echo form_textarea(array('name'=>'client[address]','id'=>'clientAddress','placeholder'=>'Address','class'=>'form-control','rows'=>2));?>										  
										</div>
									  </div>
									  <div class="form-group">
										<label for="clientSector" class="col-sm-2 control-label">Sector</label>
										<div class="col-sm-10">
											<select name='client[sector]' id='clientSector' class="form-control selectpicker">
											  <option>Select Sector</option>
											  <option>Sector1</option>
											  <option>Sector2</option>
											  <option>Sector3</option>
											  <option>Sector4</option>
											  <option>Sector5</option>
											</select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="clientPerson1" class="col-sm-2 control-label">Person1 Name:</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'client[person1]','id'=>'clientPerson1','placeholder'=>'Person1 Name','class'=>'form-control'));?>										  
										  <!--<p class="help-block">Example block-level help text here.</p>-->
										</div>
									  </div>
									  <div class="form-group">
										<label for="clientPerson2" class="col-sm-2 control-label">Person2 Name:</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'client[person2]','id'=>'clientPerson2','placeholder'=>'Person2 Name','class'=>'form-control'));?>										  
										  <!--<p class="help-block">Example block-level help text here.</p>-->
										</div>
									  </div>
									  <div class="form-group">
										<label for="clientPerson3" class="col-sm-2 control-label">Person3 Name:</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'client[person3]','id'=>'clientPerson3','placeholder'=>'Person3 Name','class'=>'form-control'));?>										  
										  <!--<p class="help-block">Example block-level help text here.</p>-->
										</div>
									  </div>
									  <div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
										  <button type="submit" class="btn btn-default">Save</button>
										</div>
									  </div>
									<?php echo form_close()?>
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
				<!-- End of your awesome content -->
				<!-- Footer Start -->
            <?php echo $Layout->element('footer'); ?>
            <!-- Footer End -->			
            </div>
			<!-- ============================================================== -->
			<!-- End content here -->
			<!-- ============================================================== -->

        </div>
		<!-- End right content -->