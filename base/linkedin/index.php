<?php 
	session_start();
?>
<style>
	body {
		margin: 0;
		padding: 0;
	}
	.emaillinked-form {
		font-family: Arial;
		margin: 8px;
		/*width: 290px;*/
	}
	.emaillinked-form input[type=text],
	.emaillinked-form input[type=email],
	.emaillinked-form input[type=password],
	.emaillinked-form textarea {
		background: #fff;
	    border: 1px solid #ddd;
	    color: #888;
	    box-sizing: border-box;
	    border-radius: 0px;
	    box-shadow: none;
	    display: block;
		font-family: Arial;
	    font-size: 14px;
	    height: 36px;
	    margin-bottom: 15px;
	    outline: none !important;
	    padding: 8px 8px 8px 8px;
	    text-overflow: ellipsis;
	    transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -moz-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -webkit-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -o-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    width: 100%;
	}
	.emaillinked-form textarea {
		height: 60px;
	}
	.emaillinked-form input[type=text]:hover,
	.emaillinked-form input[type=email]:hover,
	.emaillinked-form input[type=password]:hover,
	.emaillinked-form textarea:hover {
	    color:#333 !important;
	    border-color:#aaa !important;
	}
	.emaillinked-form input[type=text]:hover::-webkit-input-placeholder,
	.emaillinked-form input[type=email]:hover::-webkit-input-placeholder,
	.emaillinked-form input[type=password]:hover::-webkit-input-placeholder,
	.emaillinked-form textarea::-webkit-input-placeholder {
	    color: #333;
	    transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -moz-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -webkit-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	    -o-transition: color 300ms ease-in-out, background-color 300ms ease-in-out, border-color 300ms ease-in-out;
	}
	.emaillinked-form label {
	    display: block;
	    font-size: 13px;
	    font-weight: bold;
	    margin-bottom: 3px;
	}
	.emaillinked-form .button {
        background-color: #BD202F;
        border: none;
        border-radius: 0.21429em;
	    color: #ffffff;
   		cursor: pointer;
	    font-size: 14px;
	    font-weight: bold;
	    line-height: 1.4;
	    margin-bottom: 0;
	    margin-top: 0;
	    min-width: 200px;
	    padding: 8px 15px;
        text-transform: uppercase;
	    /*width: 100%;*/
	}
	.emaillinked-form .button:hover {
		background-color: #911924;
	}
	.table {
	margin:0px;padding:0px;
	width:100%;
	border:1px solid #e5e5e5;
	
	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;
	
	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;
	
	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;
	
	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}.table table{
    border-collapse: collapse;
        border-spacing: 0;
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.table tr:last-child td:last-child {
	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;
}
.table table tr:first-child td:first-child {
	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}
.table table tr:first-child td:last-child {
	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;
}.table tr:last-child td:first-child{
	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;
}.table tr:hover td{
	
}
.table tr:nth-child(odd){ background-color:#ededed; }
.table tr:nth-child(even)    { background-color:#ffffff; }.table td{
	vertical-align:middle;
	
	
	border:1px solid #e5e5e5;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:10px;
	font-size:13px;
	font-family:Arial;
	font-weight:normal;
	color:#000000;
}.table tr:last-child td{
	border-width:0px 1px 0px 0px;
}.table tr td:last-child{
	border-width:0px 0px 1px 0px;
}.table tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}
.table tr:first-child td{
		background:-o-linear-gradient(bottom, #9b9b9b 5%, #c4c4c4 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #9b9b9b), color-stop(1, #c4c4c4) );
	background:-moz-linear-gradient( center top, #9b9b9b 5%, #c4c4c4 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#9b9b9b", endColorstr="#c4c4c4");	background: -o-linear-gradient(top,#9b9b9b,c4c4c4);

	background-color:#9b9b9b;
	border:0px solid #e5e5e5;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:14px;
	font-family:Arial;
	font-weight:bold;
	color:#ffffff;
}
.table tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #9b9b9b 5%, #c4c4c4 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #9b9b9b), color-stop(1, #c4c4c4) );
	background:-moz-linear-gradient( center top, #9b9b9b 5%, #c4c4c4 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#9b9b9b", endColorstr="#c4c4c4");	background: -o-linear-gradient(top,#9b9b9b,c4c4c4);

	background-color:#9b9b9b;
}
.table tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}
.table tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}
</style>

<?php
	if(isset($_POST)){	

		switch($_POST['setup']){
			case 'implementation':
				
				if(isset($_POST['lead_id'])){
					$_SESSION['lead_id_linkedin'] = $_POST['lead_id'];
					$leadID = $_SESSION['lead_id_linkedin'];
					$emailList = array();
					$personList = array();
					$companyList = array();
					if(isset($_POST['email_list'])){
						$emailList = $_POST['email_list'];
					} 
					if(isset($_POST['person_list'])){

						$personList = $_POST['person_list'];

					} 
					if(isset($_POST['company_list'])){

						$companyList = $_POST['company_list'];

					} 
					if(isset($_POST['subject'])){	
						$_SESSION[$leadID.'subject_linkedin'] = $_POST['subject'];
					}
					$template = "";
					if(isset($_POST['email_message'])){
						$template = $_POST['email_message'];
					}
					$_SESSION[$leadID.'body_linkedin'] = $template;
					
					if(isset($_POST['template_file'])){
						$_SESSION[$leadID.'template_file_linkedin'] = $_POST['template_file'];
					}
					$_SESSION[$leadID.'mail_list_linkedin'] = $emailList;
					$_SESSION[$leadID.'person_list'] = $personList;
					$_SESSION[$leadID.'company_list'] = $companyList;
					$_SESSION[$leadID.'user_id_linkedin'] = $_POST['user_id'];
					$_SESSION[$leadID.'mail_start_linkedin'] = 0;
					$_SESSION[$leadID.'mail_log_linkedin'] = array();
					showSubjectBody();
				}
			break;
			case 'final_step':
				$leadID = $_SESSION['lead_id_linkedin'];
				if(isset($_POST['subject'])){
					$_SESSION[$leadID.'subject_linkedin']= $_POST['subject'];
				}
				if(isset($_POST['body'])){
					$_SESSION[$leadID.'body_linkedin'] = $_POST['body'];
				}
				showFinalStep();
			break;
			case 'send':				
				$leadID = $_SESSION['lead_id_linkedin'];									
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://backyard.synpat.com/dev2/customers/sales_activity_email_save',
					CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'lead_id' => $leadID,
						'to' => $_POST['to'],
						'type'=>'5',
						'subject'=>$_POST['subject'],
						'body'=>$_POST['email_message'],
						'user_id'=>$_SESSION[$leadID.'user_id_linkedin']
					)				
				));
				$resp = curl_exec($curl);
				if($resp){
					if($resp>0){						
					}
				}				
				$start = (int)$_SESSION[$leadID.'mail_start_linkedin'] + 1;
				$explode = explode(',',$_SESSION[$leadID.'mail_list_linkedin']);
				if(isset($explode[$start])){
					$emailNext = trim($explode[$start]);
					if(!empty($emailNext)){
						$_SESSION[$leadID.'mail_start_linkedin'] = $start ;
						showSubjectBody();
					} else {
						session_destroy();
						showCompleteMessage();
					}
				}					
			break;	
		}
	}
function showSubjectBody(){
	$leadID = $_SESSION['lead_id_linkedin'];
	$subject = "";
	$body = "";
	if(isset($_SESSION[$leadID.'subject_linkedin'])){
		$subject = $_SESSION[$leadID.'subject_linkedin'];
	}
	if(isset($_SESSION[$leadID.'body_linkedin'])){
		$body = $_SESSION[$leadID.'body_linkedin'];
	
	}
?>
	<?php 
			$peronList = explode(',',$_SESSION[$leadID.'person_list']);		$companyList = explode(',',$_SESSION[$leadID.'company_list']);
		?>
	  <div style='float:left;width:25%'>
		<h3>To Send</h3>
		<table class='table'>
		
			<tr>
				<td>Person</td>
				<td>Company</td>
			</tr>
		
		<?php 
			if(count($peronList)>0){
				for($i=$_SESSION[$leadID.'mail_start_linkedin'];$i<count($peronList);$i++){
		?>
					<tr>
						<td><?php echo $peronList[$i]?></td>
						<td><?php echo $companyList[$i]?></td>
					</tr>
			<?php
				}
			}
		?>
		</table>
	  </div>
	  <div style='float:left;width:25%'>
		<h3>Send To</h3>
		<table class='table'>
		
			<tr>
				<td>Person</td>
				<td>Company</td>
			</tr>
		
		<?php 
			if($_SESSION[$leadID.'mail_start_linkedin']>0){
				for($i=0;$i<$_SESSION[$leadID.'mail_start_linkedin'];$i++){
			?>
					<tr>
						<td><?php echo $peronList[$i]?></td>
						<td><?php echo $companyList[$i]?></td>
					</tr>
			<?php
				}
			}
		?>
		</table> 
	  </div>
	  <div style='float:left;width:50%'>
	<form action="http://backyard.synpat.com/base/linkedin/index.php" method="post" class="emaillinked-form">
		<button type="submit" class="button" style='float:right;margin-bottom:10px;'>Start Sending this message to invitee</button>
		<label>Subject:</label><input type="text" name="subject" value="<?php echo $subject;?>"/>
		<label>Body:</label><textarea name="body" style='height:200px;'><?php echo $body;?></textarea>		
		<input type="hidden" name='setup' value="final_step"/>
	</form>
	</div>
<?php
}
function showFinalStep(){
	$leadID = $_SESSION['lead_id_linkedin'];
		$to = "";
		$person = "";
		$company ="";
		$subject = "";
		$body = "";
		if(isset($_SESSION[$leadID.'subject_linkedin'])){
			$subject = $_SESSION[$leadID.'subject_linkedin'];
		}
		if(isset($_SESSION[$leadID.'body_linkedin'])){
			$body = $_SESSION[$leadID.'body_linkedin'];
		}
		/*$body = $body.'&nbsp;&nbsp;&nbsp;'.$_SESSION[$leadID.'template_file_linkedin'];*/
		
		$mailList = explode(',',$_SESSION[$leadID.'mail_list_linkedin']);
		$peronList = explode(',',$_SESSION[$leadID.'person_list']);
		$companyList = explode(',',$_SESSION[$leadID.'company_list']);
		if(count($mailList)>0){
			$emailTo = trim($mailList[$_SESSION[$leadID.'mail_start_linkedin']]);
			$person = trim($peronList[$_SESSION[$leadID.'mail_start_linkedin']]);
			$company = trim($companyList[$_SESSION[$leadID.'mail_start_linkedin']]);
			if(isset($mailList[$_SESSION[$leadID.'mail_start_linkedin']]) && !empty($emailTo)){
				$to = $emailTo;
			}
		}		
	?>
	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="jquery.clipboard.js"></script>
	<div style='float:right;width:50%'>
	<form action="http://backyard.synpat.com/base/linkedin/index.php" method="post" class="emaillinked-form">		
		<input type="hidden" name="to" id="to" value="<?php echo $to;?>"/>
		<label>
			Message to be sent to <font color='#ff000'><?php echo $person?>, <?php echo $company?></label></font>
		</label>
		<label>Subject:</label>
		<input type="text" name="subject" id="subject" value="<?php echo $subject;?>"/>
		<label>Body:</label>
		<textarea name="email_message" id="email_message" style='height:110px;'><?php echo $body;?></textarea>
		<input type="hidden" name='setup' value="send"/>
		<div style='float:left;width:100%;'><button type="button" id="btnSubmit" onclick="checkFunctionImplementation(1)" class="button" style='float:right;margin-bottom:10px;'>Open Receiver's LinkedIn Profile</button></div>
		<div style='float:left;width:100%;'><a class='asubject button' href='javascript://' title="Copy to Clipboard" style='float:right;margin-bottom:10px;'>Copy the Subject Line to the clipboard to be pasted in the LinkedIn Profile.</a></div>
		<div style='float:left;width:100%;'><a class='abody button' href='javascript://' title="Copy to Clipboard"  style='float:right;margin-bottom:10px;'>Copy the message body to the clipboard to be pasted in the LinkedIn profile.</a></div>
		<div style='float:left;width:100%;'><button type="button" id="btnSubmit" onclick="checkFunctionImplementation(2)" class="button" style='float:right;margin-bottom:10px;'>Confirmed Sending on Linkedin</button>
		</div>
	</form>
	</div>
	<script>
	$(document).ready(function() {  
		var copy_sel = $('.asubject');

		// Disables other default handlers on click (avoid issues)
		copy_sel.on('click', function(e) {
			e.preventDefault();
		});

		// Apply clipboard click event
		copy_sel.clipboard({
			path: 'jquery.clipboard.swf',

			copy: function() {
				var this_sel = $("#subject");
				// Return text in closest element (useful when you have multiple boxes that can be copied)
				return this_sel.val();
			}
		});
		var copy_sel1 = $('.abody');

		// Disables other default handlers on click (avoid issues)
		copy_sel1.on('click', function(e) {
			e.preventDefault();
		});

		// Apply clipboard click event
		copy_sel1.clipboard({
			path: 'jquery.clipboard.swf',

			copy: function() {
				var this_sel = $("#email_message");
				// Return text in closest element (useful when you have multiple boxes that can be copied)
				return this_sel.val();
			}
		});
	});
		function checkFunctionImplementation(a){
			if(a==1){
				window.open(jQuery("#to").val(),"Linkedin Profile","width=650,height=600");
				/*jQuery("#btnSubmit").attr("onclick","checkFunctionImplementation(2)").text('Confirmed Sending on Linkedin');*/
			} else {
				document.forms[0].submit(); 
			}
		}
	</script>
	<?php
}
function showCompleteMessage(){
?>
<script>

function runClose(){
    opener.runThreadDetail(); 
}
</script>
<form action="http://backyard.synpat.com/base/linkedin/index.php" method="post" class="emaillinked-form">
	<h4>Finished.</h4>
	<a href='javascript:runClose();window.close();' class="button">Close</a>
</form>
<?php
}
?>