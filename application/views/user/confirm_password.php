<script>

function validate(form){
	if($("#userPassword").val()!=$("#userConfirmPassword").val())
	{
		alert("password should be same");
		return false;
	}
	return true;
}
jQuery(document).ready(function(){
	$('#userConfirmPassword').on('keyup', function () {
		if ($(this).val() == $('#userPassword').val()) {
			$('#message').html('matching').css('color', 'green');
		} else {
			$('#message').html('not matching').css('color', 'red');
		}
	});
});
</script>
<h3 class="login-title" style='margin-top:100px;'>
	Change Password
	<span style='margin-top:0px;'></span>
</h3>
<p class="login-text" style='text-align:justify;width:675px;margin-top:20px;margin-bottom:0px;'>
	
</p>
<?php echo form_open('login/confirm_password/'.$c,array('class'=>'col-md-4 col-sm-5 col-xs-11 col-lg-3 center-margin','id'=>'forgot_form', 'style'=>'margin-top:20px;', 'onsubmit'=>'return validate(this);'));?>

<div id="login-form">
	<div class="pad20A">
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon addon-inside bg-gray">
					<i class="glyph-icon icon-unlock-alt"></i>
				</span>
				<input type="password" name="confirm[password]" id="userPassword" required class="form-control" placeholder="New Password">
			</div>
		</div>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon addon-inside bg-gray">
					<i class="glyph-icon icon-unlock-alt"></i>
				</span>
				<input type="password" name="confirm[password]" id="userConfirmPassword" required class="form-control" placeholder="Confirm Password"><span id='message'></span>
			</div>
		</div>
		<div class="row">
			<div class="checkbox-primary col-md-6 mrg5T" style="height: 20px; padding-left: 30px;">
			</div>
			<div class="text-right col-md-6">
				<div class="form-group">
					<button type="submit" class="btn btn-block btn-primary">Confirm</button>
				</div>				
			</div>
		</div>
	</div>
</div>

<?php echo form_close()?>

