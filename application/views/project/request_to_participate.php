<!-- Start right content -->
        <div class="content-page">
			<!-- ============================================================== -->
			<!-- Start Content here -->
			<!-- ============================================================== -->
            <div class="content">
								<!-- Page Heading Start -->
				<div class="page-heading">
            		<h1><i class='fa fa-check'></i> Syndication</h1>
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
									<?php echo form_open('project/rrequest_to_participate',array('class'=>'form-horizontal','role'=>'form'));?>
									  <div class="form-group">
										<label for="participatePortfolioId" class="col-sm-2 control-label">Portfolio</label>
										<div class="col-sm-10">
											<select name="participate[portfolio_id]" id="participatePortfolioId"  class="form-control selectpicker">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="participateContactId" class="col-sm-2 control-label">Contact</label>
										<div class="col-sm-10">
											<select name="participate[contact_id]" id="participateContactId"  class="form-control selectpicker">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
										</div>
									  </div>	                                      
                                      <div class="form-group">
										<label for="participateRequestDate" class="col-sm-2 control-label">Request Date</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'participate[request_date]','id'=>'participateRequestDate','placeholder'=>'yyyy-mm-dd','class'=>'form-control datepicker-input'));?>
										</div>
									  </div>
									  <div class="form-group">
										<label for="participatePrice" class="col-sm-2 control-label">Max Amount</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'participate[price]','id'=>'participatePrice','placeholder'=>'Max Amount','class'=>'form-control'));?>
										</div>
									  </div>
                                       <div class="form-group">
										<label for="participateThirdContactId" class="col-sm-2 control-label">Third Party</label>
										<div class="col-sm-10">
											<select name="participate[third_contact_id]" id="participateThirdContactId"  class="form-control selectpicker">
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