<div class="row">
<?php echo $Layout->element('task');?>
<div class="col-md-8 col-sm-8 col-xs-8">

<style>
.form-horizontal .control-label{text-align:left}
</style>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datepicker/datepicker.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs-ui/tabs.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>


<script type="text/javascript">
    /* jQuery UI Tabs */

    $(function() { "use strict";
        $(".tabs").tabs();
    });

    $(function() { "use strict";
        $(".tabs-hover").tabs({
            event: "mouseover"
        });
    });
</script>

<!-- Boostrap Tabs -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs.js"></script>

<!-- Tabdrop Responsive -->

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/tabs/tabs-responsive.js"></script>
<script type="text/javascript">
    /* Responsive tabs */
    $(function() { "use strict";
        $('.nav-responsive').tabdrop();
    });
</script>



<!-- <div id="page-title">
    <h2>Forward Lead</h2>
</div> -->
<div class="panel dashboard-box">
    <div class="panel-body">
		 <div class="example-box-wrapper">
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
            <?php echo form_open('leads/sent_lead',array('class'=>'form-horizontal','role'=>'form','id'=>'formLitigation'));?>
				
				<div class="col-sm-12">
					<div class="col-xs-4">
						<div class="clearfix">
							<label class="control-label" for="litigationCaseName">Forward to: </label>
							<div class="mrg5T">
								<select name="lead_forward" class="form-control" required>
									<option value="">-- Select User --</option>
	                                <?php
	                                foreach($userPageAssigned as $val)
	                                {
	                                    ?>
	                                    <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
	                                   
	                                    <?php
	                                }
	                                ?>
	                            </select>
							</div>
						</div>	
                        <div class="clearfix mrg10T">	
							<input type="hidden" name="lead_id" value="<?php echo $LeadForward->id; ?>"/>
							<button type="submit" class="btn btn-primary">Save</button>  
						</div>				
					</div>
                </div>				
			</form>
		</div>
	</div>
</div>
</div>
<?php echo $Layout->element('timeline');?>
</div>
