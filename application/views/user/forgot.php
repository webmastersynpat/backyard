<h3 class="login-title" style='margin-top:100px;'>
	Welcome to Synpat's Backyard
	<span style='margin-top:0px;'>Version 1.0</span>
</h3>
<p class="login-text" style='text-align:justify;width:675px;margin-top:20px;margin-bottom:0px;'>
	I agree not copy, print or disclose, in writing or verbally, any of the information (including structure and data) presented in the SynPat Backyard Application. This undertaking may not be modified except by a specific instrument in writing signed by SynPat.
</p>
<?php echo form_open('login/confirm_password/'.$c,array('class'=>'col-md-4 col-sm-5 col-xs-11 col-lg-3 center-margin','id'=>'login-validation', 'style'=>'margin-top:20px;'));?>

<div id="login-form">

	<div class="pad20A">
	   <?php 
			if($this->session->flashdata('error')) {
		?>
			<p class='alert alert-danger' style='margin-bottom:5px;'><?php echo $this->session->flashdata('error');?></p>
		<?php
			}
		?>
        
         <?php 
			if($this->session->flashdata('success')) {
		?>
			<p class='alert alert-danger' style='margin-bottom:5px;'><?php echo $this->session->flashdata('success');?></p>
		<?php
			}
		?>                

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon addon-inside bg-gray">
					<i class="glyph-icon icon-unlock-alt"></i>
				</span>
				<input type="password" name="confirm[password]" id="userPassword" required class="form-control" placeholder="********">
			</div>
		</div>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon addon-inside bg-gray">
					<i class="glyph-icon icon-unlock-alt"></i>
				</span>
				<input type="password" name="confirm[password]" id="userConfirmPassword" required class="form-control" placeholder="********">
			</div>
		</div>
		<div class="row">
			<div class="checkbox-primary col-md-6 mrg5T" style="height: 20px; padding-left: 30px;">
				<!--<label>
					<input type="checkbox" id="loginCheckbox1" class="custom-checkbox">
					Remember me
				</label>-->
			</div>
			<div class="text-right col-md-6">
				<div class="form-group">
					<button type="submit" class="btn btn-block btn-primary">Change Password</button>
				</div>
				<a href="#" class="switch-button" switch-target="#login-forgot" switch-parent="#login-form" title="Recover password">Reset your password?</a>
			</div>
		</div>
	</div>
</div>

<?php echo form_close()?>

