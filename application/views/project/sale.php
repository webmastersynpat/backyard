<!-- Start right content -->
        <div class="content-page">
			<!-- ============================================================== -->
			<!-- Start Content here -->
			<!-- ============================================================== -->
            <div class="content">
								<!-- Page Heading Start -->
				<div class="page-heading">
            		<h1><i class='fa fa-check'></i> Sale</h1>
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
									<?php echo form_open('project/sale',array('class'=>'form-horizontal','role'=>'form'));?>
									  <div class="form-group">
										<label for="salePortfolioId" class="col-sm-2 control-label">Portfolio</label>
										<div class="col-sm-10">
											<select name="sale[portfolio_id]" id="salePortfolioId"  class="form-control selectpicker">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="saleContactId" class="col-sm-2 control-label">Contact</label>
										<div class="col-sm-10">
											<select name="sale[contact_id]" id="saleContactId"  class="form-control selectpicker">
											  <option>1</option>
											  <option>2</option>
											  <option>3</option>
											  <option>4</option>
											  <option>5</option>
											</select>
										</div>
									  </div>
									  <div class="form-group">
										<label for="saleCategory" class="col-sm-2 control-label">Category</label>
										<div class="col-sm-10">
											<select name="sale[category]" id="saleCategory"  class="form-control selectpicker">
											  <option value="Participant">Participant</option>
											  <option value="Regular License">Regular License</option>
											  <option value="Risk Averse License">Risk Averse License</option>
											  <option value="Buyer">Buyer</option>
											</select>
										</div>
									  </div>
                                      <div class="form-group">
										<label for="saleRequestDate" class="col-sm-2 control-label">Agreement Date</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'sale[request_date]','id'=>'saleRequestDate','placeholder'=>'yyyy-mm-dd','class'=>'form-control datepicker-input'));?>
										</div>
									  </div>
									  <div class="form-group">
										<label for="salePrice" class="col-sm-2 control-label">Price</label>
										<div class="col-sm-10">
											<?php echo form_input(array('name'=>'sale[price]','id'=>'salePrice','placeholder'=>'Max Amount','class'=>'form-control','readonly'=>'readonly'));?>
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