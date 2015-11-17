<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/multi-select/multiselect.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datepicker/datepicker.js"></script>

<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8" id="contentPart">
	<div class="panel dashboard-box">
    	<div class="panel-body">
    		<div class="example-box-wrapper">
    		
    			<div class="row" style="margin-bottom: 15px;">
    				<div class="col-xs-9">
						<form class="form-inline form-flat">
							<div class="form-group">
								<label></label>
								<input type="text" disabled="" class="form-control input-string" placeholder="Opportunity Type">
							</div>
							<div class="form-group">
								<label></label>
								<input type="text" disabled="" class="form-control input-string" placeholder="Opportunity Name">
							</div>
							<div class="form-group">
								<label></label>
								<input type="text" disabled="" class="form-control input-string" placeholder="Seller's Type">
							</div>
						</form>
    				</div>
    				<div class="col-xs-3 text-right">
    					<a href="#" class="btn btn-primary btn-small">PD's Page</a>
    				</div>
    			</div>

				<ul id="mainTabs" class="list-group list-group-separator row list-group-icons">
					<li class="col-md-3 active">
						<a class="list-group-item" data-toggle="tab" href="#createOpportunityTab">
							<i class="glyph-icon font-red icon-bullhorn"></i>
							Create an Opportunity
						</a>
					</li>
					<li class="col-md-3">
						<a class="list-group-item" data-toggle="tab" href="#signPPATab">
							<i class="glyph-icon icon-dashboard"></i>
							Sign a PPA
						</a>
					</li>
					<li class="col-md-3">
						<a class="list-group-item" data-toggle="tab" href="#syndicateTab">
							<i class="glyph-icon font-primary icon-camera"></i>
							Syndicate
						</a>
					</li>
					<li class="col-md-3">
						<form class="form-flat">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<div><label>NDA:</label></div>
										<select multiple style="height: 52px;">
											<option value="1">Option 1</option>
											<option value="2">Option 2</option>
											<option value="3">Option 3</option>
											<option value="4">Option 4</option>
											<option value="5">Option 5</option>
											<option value="6">Option 6</option>
											<option value="7">Option 7</option>
										</select>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group">
										<div><label>PPA:</label></div>
										<select multiple style="height: 52px;">
											<option value="1">Option 1</option>
											<option value="2">Option 2</option>
											<option value="3">Option 3</option>
											<option value="4">Option 4</option>
											<option value="5">Option 5</option>
											<option value="6">Option 6</option>
											<option value="7">Option 7</option>
										</select>
									</div>
								</div>
							</div>
						</form>
					</li>
				</ul>

				<div class="tab-content">
					<div id="createOpportunityTab" class="tab-pane active">
						<div class="form-wizard">
							<ul>
								<li class="active">
									<a data-toggle="tab">
										<label class="wizard-step">1</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">2</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">3</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">4</label>
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-xs-3">
											<div class="step-container">
												<form class="form-horizontal form-flat">
													<div class="row">
														<label class="col-sm-12 control-label" for="acquisitionMarketSector">Market Sector</label>
														<div class="col-sm-12 mrg5T">
															<select class="form-control custom-select" required="">
																<option value="">-- Select Market Sector --</option>
																<option value="2">Software</option>
																<option value="1">Telecommunication</option>
															</select>
														</div>
													</div>
													<div class="clearfix mrg10T">
  														<label class="control-label" style="float: left;">Seller Upfront Price ($M)</label>
  														<input type="text" class="form-control input-string" style="float: left; margin-top: 4px; width: 45px;">
													</div>
													<div class="clearfix mrg10T">
  														<label class="control-label" style="float: left;">Number of Patent</label>
  														<input type="text" class="form-control input-string" style="float: left; margin-top: 4px; width: 45px;">
													</div>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Technologies</label>
														<div class="col-sm-12 mrg5T">
															<select multiple class="multi-select">
																<option value="1">Tech 1</option>
																<option value="2">Tech 2</option>
																<option value="3">Tech 3</option>
																<option value="4">Tech 4</option>
																<option value="5">Tech 5</option>
															</select>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<form class="form-flat">
													<div>
														<a href="#" class="btn btn-black">Draft an NDA</a>
													</div>
													<div class="mrg10T">
														<a href="#" class="btn btn-primary">CIPO approval</a>
													</div>
													<div class="mrg10T"><label>Share NDA with Seller:</label></div>
													<table class="table table-bordered mrg10T">
														<thead>
															<tr>
																<th>#</th>
																<th>Name</th>
																<th>Company Name</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>1</td>
																<td>2</td>
																<td>3</td>
															</tr>
														</tbody>
													</table>
													<div class="mrg10T">
														<a href="#" class="btn btn-primary">Execute NDA</a>
													</div>
													<div class="mrg10T">
														<a href="#" class="btn btn-primary">NDA Executed</a>
													</div>
												</form>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<div>
													<a href="#" class="btn btn-success">Seller's EOU in Folder</a>
												</div>
												<table class="table table-bordered mrg10T">
													<thead>
														<tr>
															<th>Company</th>
															<th>Product</th>
															<th>Quality</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																Microsoft
															</td>
															<td>
																Xbox
															</td>
															<td>
																H/M/L
															</td>
														</tr>
													</tbody>
												</table>
												<div class="text-right mrg10T">
													<button class="btn btn-primary">Save</button>
												</div>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<form class="form-horizontal form-flat">
													<div class="clearfix">
														<label style="float:left;" class="control-label">How many SEP:</label>
														<input type="text" style="float: left; width: 100px; margin-top: 4px;" class="form-control input-string">
													</div>
													<table class="table table-bordered mrg10T">
														<thead>
															<tr>
																<th>Standard</th>
																<th>Product</th>
																<th>EOU</th>
																<th>In folder</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	802.11g
																</td>
																<td>
																	modem
																</td>
																<td>
																	yes/no
																</td>
																<td>
																	yes/no
																</td>
															</tr>
														</tbody>
													</table>
													<div class="text-right mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>
													<div class="clearfix mrg10T">
														<label style="float:left;" class="control-label"># Potential Licenses:</label>
														<input type="text" style="float: left; width: 100px; margin-top: 4px;" class="form-control input-string">
													</div>
													<table class="table table-bordered mrg10T">
														<thead>
															<tr>
																<th>Company</th>
																<th>Product</th>
																<th>EOU</th>
																<th>In folder</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Microsoft
																</td>
																<td>
																	modem
																</td>
																<td>
																	yes/no
																</td>
																<td>
																	yes/no
																</td>
															</tr>
														</tbody>
													</table>
													<div class="text-right mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane">
									1
								</div>
								<div class="tab-pane">
									3
								</div>
								<div class="tab-pane">
									4
								</div>
							</div>
						</div>
					</div>
					<div id="signPPATab" class="tab-pane">
						<div class="form-wizard">
							<ul>
								<li class="active">
									<a data-toggle="tab">
										<label class="wizard-step">1</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">2</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">3</label>
									</a>
								</li>
								<li>
									<a data-toggle="tab">
										<label class="wizard-step">4</label>
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="row">
										<div class="col-xs-3">
											<div class="step-container">
												<div>
													<a href="#" class="btn btn-success">Draft a PPA</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">DEMO DDMM</a>
												</div>
												<table class="table table-bordered mrg10T">
													<thead>
														<tr>
															<th>List of Assets</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Asset 1</td>
														</tr>
													</tbody>
												</table>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">List Approved by CIPO</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-black">Order CC + PAR</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-black">Insert embedding CC + PAR Code</a>
												</div>
												<div class="mrg10T">
													<label class="control-label mrg10T"><b>Share the PPA</b></label>
													<div class="checkbox">
							                            <label><input type="checkbox">Consumer</label>
							                        </div>
													<div class="checkbox">
							                            <label><input type="checkbox">Products</label>
							                        </div>
													<div class="checkbox">
							                            <label><input type="checkbox">Computers</label>
							                        </div>
													<div class="checkbox">
							                            <label><input type="checkbox">Automotive</label>
							                        </div>
													<div class="checkbox">
							                            <label><input type="checkbox">Semiconductors</label>
							                        </div>
												</div>
												<table class="table table-bordered mrg10T">
													<thead>
														<tr>
															<th>Title 1</th>
															<th>Title 2</th>
															<th>Title 3</th>
															<th>Title 4</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>1</td>
															<td>2</td>
															<td>3</td>
															<td>4</td>
														</tr>
														<tr>
															<td>5</td>
															<td>6</td>
															<td>7</td>
															<td>8</td>
														</tr>
														<tr>
															<td>9</td>
															<td>10</td>
															<td>11</td>
															<td>12</td>
														</tr>
													</tbody>
												</table>
												<form class="form-flat">
													<div class="mrg10T">
														<label class="control-label mrg10T"><b>Create a new Contact</b></label>
													</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">Company Name</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 110px;" class="form-control input-string">
													</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">Person in Charge</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 110px;" class="form-control input-string">
													</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">Phone</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 110px;" class="form-control input-string">
													</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">E-mail</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 110px;" class="form-control input-string">
													</div>
													<div class="row mrg10T">
														<label class="col-sm-12 control-label">Markets</label>
														<div class="col-sm-12 mrg5T">
															<select multiple class="multi-select">
																<option value="1">Mark 1</option>
																<option value="2">Mark 2</option>
																<option value="3">Mark 3</option>
																<option value="4">Mark 4</option>
																<option value="5">Mark 5</option>
															</select>
														</div>
													</div>
													<div class="mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>
												</form>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<div>
													<a href="#" class="btn btn-success">Execute a PPA</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">Start DD</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">Due Diligence 2 (FileMaker)</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">Start Market Research</a>
												</div>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
												<div>
													<a href="#" class="btn btn-success">PPA Executed</a>
												</div>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary">Order Damages Report by CIPO</a>
												</div>
												<form class="form-flat">
													<div class="mrg10T">
														<label class="control-label mrg10T"><b>Option Expiration</b></label>
													</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">Option Expiration Date</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 82px;" class="form-control input-string bootstrap-datepicker" placeholder="mm/dd/yyyy">
													</div>
													<div class="mrg10T">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">First file</span>
								                                <span class="fileinput-exists">Change</span>
								                                <input type="file" name="">
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">Second file</span>
								                                <span class="fileinput-exists">Change</span>
								                                <input type="file" name="">
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="">
														<div class="fileinput fileinput-new" data-provides="fileinput">
								                            <span class="btn btn-primary btn-file">
								                                <span class="fileinput-new">Third file</span>
								                                <span class="fileinput-exists">Change</span>
								                                <input type="file" name="">
								                            </span>
								                            <span class="fileinput-filename"></span>
								                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
								                        </div>
							                       	</div>
													<div class="clearfix mrg10T">
  														<label style="float: left;" class="control-label">Sellers Asking Price</label>
  														<input type="text" style="float: left; margin-top: 4px; width: 82px;" class="form-control input-string">
													</div>
													<div class="mrg10T">
														<button class="btn btn-primary">Save</button>
													</div>
												</form>
												<div class="mrg10T">
													<a href="#" class="btn btn-primary mrg10T">Upload Document by CIPO</a>
												</div>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="step-container">
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane">
									1
								</div>
								<div class="tab-pane">
									3
								</div>
								<div class="tab-pane">
									4
								</div>
							</div>
						</div>
					</div>
					<div id="syndicateTab" class="tab-pane">
						Syndicate
					</div>
				</div>

			</div>
    	</div>
    </div>
</div>
<?php echo $Layout->element('timeline');?>
</div>


<script>
	jQuery(function() {
		jQuery('.multi-select').multiSelect('refresh');
		$(".ms-container").append('<i class="glyph-icon icon-exchange"></i>');
	});

	jQuery('.bootstrap-datepicker').bsdatepicker()
</script>