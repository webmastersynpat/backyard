<!-- Start right content -->
        <div class="content-page">
			<!-- ============================================================== -->
			<!-- Start Content here -->
			<!-- ============================================================== -->
            <div class="content">
								<!-- Page Heading Start -->
				<div class="page-heading">
            		<h1><i class='fa fa-check'></i> New Project</h1>
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
								<div id="horizontal-form">
									<?php echo form_open('project/new_project',array('class'=>'form-horizontal','role'=>'form'));?>
									  <div class="form-group">
										<label for="projectName" class="col-sm-2 control-label">Set a name of New Project</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'project[name]','id'=>'projectName','placeholder'=>'Name','class'=>'form-control'));?>										  
										  <!--<p class="help-block">Example block-level help text here.</p>-->
										</div>
									  </div>
									  <div class="form-group">
										<label for="projectContact" class="col-sm-2 control-label">Select multiple contacts from list</label>
										<div class="col-sm-10">
											<select name="project[contact]" id="projectContact" multiple class="form-control">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
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