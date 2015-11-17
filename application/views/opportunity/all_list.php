<style>

body {
	overflow: auto !important;
	min-width: 0;
	width: 100% !important;
}

#TextBoxesGroup * {
	font-size: 13px !important;
}
.cHalf input[type="email"], 
input[type="password"], 
input[type="text"], input[type="number"] {
  -moz-box-sizing: border-box;
  
  border: 1px solid rgb(92, 92, 92);
  border-radius: 0.21429em;
  color: rgb(71, 71, 71);
  margin-left: 0;
  margin-right: 0;
  width: 100%;
}
.cHalf input,
.cHalf textarea {
  border: 1px solid #dfe8f1;
  padding: 2px 5px !important;
}
.cHalf input {
  height: 31px !important;
}
.cHalf input[type=radio] {
  height: 13px !important;
}
.cHalf textarea {
  height: 90px !important;
}
.cHalf input:focus,
.cHalf textarea:focus {
	border-color: #3da6ff;
}
.headingPOP1 {
	margin-left: 0 !important;
	margin-right: 0 !important;
}
.patenteespara1 {
  width: 265px !important;
  width: 240px !important;
}
.patenteespara {
	padding-left: 0 !important;
}
.patenteespara + input {
	position: relative;
	top: 4px;
}

.mainPaddingTop {
  padding-top: 10px;
}
.patenteespara {
  float: left;
  margin: 0px 10px 0px 0px;
  width: 333px;
  padding-left: 10px;
}
.mainTextCenter {
  text-align: center !important;
}
.cHalf {
  width: 370px;
  width: 100%;
}
.noPadding {
  padding: 0px !important;
}
.mainFont {
  font-size: 14px !important;
}

.p_tet {
  width: 111px !important;
  margin-left: 0px;
	color: #222222 !important;
  display: block !important;
  font-size: 13px !important;
  height: 40px !important;
  margin-bottom: 10px !important;
  padding-left: 10px !important;
}
.tertext {
 
  color: #222222 !important;
  display: block !important;
  font-size: 13px !important;
  height: 40px !important;
  margin-bottom: 10px !important;
  padding-left: 10px !important;
  width: 100% !important;
}
.headingPOP1 {
  color: #222222 !important;
  margin: 10px;
  font-family: "Trade Gothic Condensed","Helvetica Neue",Arial,sans-serif;
  font-size: 13px;
  font-weight: bold;
}/*
.mainColor {
  color: #e2e1e1 !important;
}*/
.p_100 {
  width: 95px !important;
  width: 60px !important;
    width: 125px !important;
  /*float: right;*/
}
.cbP p {
  display: inline-block;
  margin-left: 1px;
}
.cbP p label {
  display: inline-block;
  color: #222222 !important;
  font-size: 13px;
  font-weight: normal;
  width: 90px;
  text-align: center;
}
.cbP p:first-child label {
  /* padding-left: 10px; */
}
.mainRed {
  color: rgba(194, 0, 0, 0.8);
}
.mainWidth {
  width: 100%;
}
.mainMarginBottom {
  margin-bottom: 10px;
}
.textCC{
	width:710px !important;
}
#patent_data {
	overflow-x: auto;
}
#page-content{
	background:#FFFFFF !important;;
}
.p_image {
  background: url('http://synpat.com/wp-content/themes/synpat/images/a_add.png') 0px 2px no-repeat;
  width: 16px;
  height: 16px;
  display: inline-block;
  text-align: left;
  margin-right: 5px;
}
.p_image-1 {
  background: url('http://synpat.com/wp-content/themes/synpat/images/a_min.png') 0px 2px no-repeat;
  width: 16px;
  height: 16px;
  margin-right: 5px;
  display: inline-block;
  text-align: left;
}
</style>
<script>
jQuery(document).ready(function(){
	jQuery("#mainHolder").css("height",jQuery(window).height()+"px");
});


window.format=function(b,a){if(!b||isNaN(+a))return a;var a=b.charAt(0)=="-"?-a:+a,j=a<0?a=-a:0,e=b.match(/[^\d\-\+#]/g),h=e&&e[e.length-1]||".",e=e&&e[1]&&e[0]||",",b=b.split(h),a=a.toFixed(b[1]&&b[1].length),a=+a+"",d=b[1]&&b[1].lastIndexOf("0"),c=a.split(".");if(!c[1]||c[1]&&c[1].length<=d)a=(+a).toFixed(d+1);d=b[0].split(e);b[0]=d.join("");var f=b[0]&&b[0].indexOf("0");if(f>-1)for(;c[0].length<b[0].length-f;)c[0]="0"+c[0];else+c[0]==0&&(c[0]="");a=a.split(".");a[0]=c[0];if(c=d[1]&&d[d.length-
1].length){for(var d=a[0],f="",k=d.length%c,g=0,i=d.length;g<i;g++)f+=d.charAt(g),!((g-k+1)%c)&&g<i-c&&(f+=e);a[0]=f}a[1]=b[1]&&a[1]?h+a[1]:"";return(j?"-":"")+a[0]+a[1]};



</script>
<?php if((int)$lead_number>0 && (int)$serial_number>0):	?>
<script>
_aCall = '';
jQuery(document).ready(function(){
	jQuery('.auto_save_input').change(function(){
		saveCall();
	});
});
function saveCall(){
	if (typeof _aCall!='string') {
		_aCall.abort();
	}
	_aCall = jQuery.ajax({
				type:'POST',
				url:__baseUrl+'opportunity/pre_lead_on_fly',
				data:jQuery("#formPreLead").serializeArray(),
				cache:false,
				success:function(res){
					
				}
			 });
}
</script>
<?php endif;?>
 <div class="row mrg20B" style='' id="mainHolder">	
 <!--<a href='javascript:void(0);' class='btn btn-default btn-mwidth pull-right mrg10B' id="emableBTNForm" onclick="enablePreLeadForm();">Create Pre Lead</a>
  <a href='javascript:void(0);' class='btn btn-default btn-mwidth pull-right mrg10B' id="emableBTNForm" onclick="enableSwitch(1);">Switch</a>-->
 <div style='padding-left:5px;'>
	
	<div class="row mrg25B" style='width:670px;display:<?php if((int)$lead_number>0 && (int)$serial_number>0):	?>block <?php else: echo'none';?> <?php endif;?>;' id="pre_lead">
		<div class='col-xs-12'>
			
			<?php echo form_open('opportunity/save_pre_lead',array('class'=>'','role'=>'form','id'=>'formPreLead', 'style'=>'margin-bottom: 0;'));?>
			<?php 
				$d = 1;
				$anotherLicense = array();
				$n_patents="";
				$essnt="";
				$n_lic="";
				$technologies="";
				$standards="";
				$markets="";
				$products="";
				$u_upfront="";
				$otherData = array();
				$patent = array();
				$typeName = "";
				$broker = "";
				$first_name = "";
				$last_name = "";
				$email_address = "";
				$note = "";

				if(count($single_data)>0){				
					$other = $single_data->other_field;					
					if($other!=""){
						$anotherLicense = json_decode($other);
					}
					if(count($anotherLicense)>0){
						$n_patents = $anotherLicense->n_patents;
						$essnt = $anotherLicense->essnt;
						$n_lic = $anotherLicense->n_lic;
						$technologies = $anotherLicense->technologies;
						$standards = $anotherLicense->standards;
						$markets = $anotherLicense->markets;
						$products = $anotherLicense->products;
						$u_upfront = $anotherLicense->u_upfront;
						$otherData = $anotherLicense->another_license;
						if(isset($anotherLicense->patent_list)){
							$patent = $anotherLicense->patent_list;
						}
					}
					$typeName = (string)$single_data->type_name;
					$broker = (string)$single_data->broker;
					$first_name = (string)$single_data->first_name;
					$last_name = (string)$single_data->last_name;
					$email_address = (string)$single_data->email_address;
					$note = (string)$single_data->note;
				}
			?>
			<div id="TextBoxesGroup">						
						<input id="pre_serial_number" class="p_tet noPadding p_100  mainFont  mainTextCenter" maxlength="12" name="pre_serial_number" type="hidden" value="<?php echo $serial_number?>" placeholder="S/N" onkeypress="return isNumber(event)"  style="padding: 0 2px !important;height: 22px !important;">
						<h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Assets:</h2>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" style='width:100% !important;'><span class="mainColor mainFont">1. Number of assets  you wish to sell:</span></p>
						</div>
						<div id="patentNumbers" style="margin-bottom: 5px !important;">
							<div id="patentNumbers" style="margin-bottom: 5px !important;">
							<?php 
								$pt=1;
								if(count($patent)>0):
								for($p=0;$p<count($patent);$p++){
									$country = $patent[$p]->country;
									$application = $patent[$p]->application;
									$patentN = $patent[$p]->patent;
							?>
							<div id="patentNumbers<?php echo $pt;?>">
								<div class="cHalf">
								<input id="textbox1"  class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" style="display: inline-block !important;width: 154px !important;" name="country_n[]"  type="text" value="<?php echo $country;?>" title="Country" placeholder="Country" />
								<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" style="display: inline-block !important;width: 154px !important;" name="patent_n[]"  type="text" value="<?php echo $patentN;?>" placeholder="Patents" title="Patents"/>
								<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" style="display: inline-block !important;width: 154px !important;" name="application_n[]"  type="text" value="<?php echo $application;?>" placeholder="Applications" title="Applications" />
								</div>
								<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">
									<div class="cHalf rem" style=''>
										<a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href='javascript://'></a>
										<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div>
									</div>
									<?php if(count($patent)-1 == $p):?>
									<div class="cHalf adem">
										<a class="aplus p_image" onclick="addPatent(jQuery(this))" href='javascript://'></a> 
										<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div>
									</div>
									<?php endif;?>
								</div>
							</div>
							<?php
									$pt++;
								}
								else:
							?>
							
							
							<div id="patentNumbers1">
								<div class="cHalf">
								<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" name="country_n[]"  type="text" value="" title="Country" placeholder="Country" style="display: inline-block !important;width: 154px !important;"/>
								<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" name="patent_n[]"  type="text" value="" title="Patents" placeholder="Patents" style="display: inline-block !important;width: 154px !important;"/>
								<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" name="application_n[]"  type="text" value="" title="Applications" placeholder="Applications" style="display: inline-block !important;width: 154px !important;"/>
								</div>
								<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">
									<div class="cHalf rem" style='display:none;'>
										<a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href='javascript://'></a>
										<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div>
									</div>
									<div class="cHalf adem">
										<a class="aplus p_image" onclick="addPatent(jQuery(this))" href='javascript://'></a> 
										<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div>
									</div>
								</div>
							</div>
							<?php endif;?>
						</div>

						</div>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop"><span class="mainColor mainFont">2. How many are Standard Essential?</span></p>
						<input id="essnt" onkeypress="return isNumber(event)" class="p_tet noPadding  mainFont  p_100 mainTextCenter auto_save_input"  maxlength="3" name="essnt"  type="text" value="<?php echo $essnt;?>" placeholder=""  /></div>
						<h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Price:</h2>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">3. Your total asking price ($): </span></p>
						<input id="u_upfront" onkeypress="return isNumber(event)" onkeyup="inputKeyUp(event,jQuery(this))" class="p_tet  noPadding  mainFont p_100 mainTextCenter auto_save_input" maxlength="9" name="u_upfront"  type="text" value="<?php echo $u_upfront;?>" placeholder=" " /></div>						
						<div class="cHalf" id="pop_text" style="display:none;">
							<p class="patenteespara mainColor mainPaddingTop mainPaddingBottom20 mainWidth mainFontNormal mainFont15">Upon signing a purchase agreement, SynPat will begin a due diligence to validate the above information. Based on that information, your expected rewards from the Program are:</p>
						</div>
						<div class="cHalf cbP" style="display:none;">
						<p style="margin-bottom: 5px !important;">
						<label>Upfront<br>Price</label><br>
						<input id="u_upfront_100" onkeypress="return isNumber(event)" disabled="disabled" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" maxlength="10" name="u_upfront_100"  type="text" value="" placeholder=" ">
						</p>
						<p class="mainRed mainFont mainTextCenter">+</p>
						<p style="margin-bottom: 5px !important;">
						<label>Regular<br>Licensing</label><br>
						<input id="regular_license" onkeypress="return isNumber(event)" disabled="disabled" class="p_tet noPadding noMargin p_reg mainFont mainTextCenter auto_save_input" maxlength="10" name="regular_license"  type="text" value="" placeholder=" ">
						</p>
						<p class="mainRed mainFont mainTextCenter">+</p>
						<p style="margin-bottom: 5px !important;">
						<label>Risk Averse<br>Licensing</label><br>
						<input id="risk_reverse" onkeypress="return isNumber(event)" disabled="disabled" class="p_tet noPadding noMargin p_ris mainFont mainTextCenter auto_save_input" maxlength="10" name="risk_reverse"  type="text" value="" placeholder=" ">
						</p>
						<p class="mainRed  mainTextCenter" style="font-size:18px;">=</p>
						<p style="margin-bottom: 5px !important;">
						<label>Total<br>Rewards</label><br>
						<input id="total_rewards" onkeypress="return isNumber(event)" disabled="disabled" class="p_tet noPadding noMargin p_tot mainFont mainTextCenter auto_save_input" maxlength="10" name="total_rewards"  type="text" value="" placeholder=" ">
						</p>
						</div>
						<div class="cHalf cbP" style="display:none;">
						<p style="margin-left:10px;"><label>100 days max</label></p>
						<p class="mainRed mainFont mainTextCenter" style="width:20px;">&nbsp;</p>
						<p><label>4 months</label></p>
						<p class="mainRed mainFont mainTextCenter" style="width:20px;">&nbsp;</p>
						<p><label>Thereafter</label></p>
						</div>
						<div class="cHalf" id="alert_message" style="display:none">
						</div>
						<h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Market:</h2>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">4. Number of expected licensees (total):</span></p>
						<input id="n_lic" onkeypress="return isNumber(event)" onchange="inputKeyUp(event,jQuery(this))"  class="p_tet noPadding  mainFont p_100 mainTextCenter auto_save_input" maxlength="3" name="n_lic" type="text" value="<?php echo $n_lic;?>" placeholder=" " /></div>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">5.Relating Technologies: </span></p>
						<input id="textbox1" class="tertext mainFont auto_save_input" name="Technologies" type="text" value="<?php echo $technologies;?>" /></div>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">6.Relating Standards: </span></p>
						<input id="Standards" class="tertext mainFont auto_save_input" name="Standards"  type="text" value="<?php echo $standards;?>" /></div>
						<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">7.Relating Markets: </span></p>
						<input id="Markets" class="tertext mainFont auto_save_input" name="Markets"  type="text" value="<?php echo $markets;?>" /></div>
						<!--<div class="cHalf">
						<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">8.Relating Products: </span></p>
						</div>-->	<input id="Products" class="tertext mainFont auto_save_input" name="Products"  type="hidden" value="<?php echo $products;?>" />					
						<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 100% !important;"><span class="mainColor mainFont">9. Potential interested licensees under your patents:</span></p></div>
						<div id="TaxtBoxesGroup" style="margin-bottom: 5px !important;">
						<?php 
							if(is_array($otherData) && count($otherData)>0){
								$ss = 1;
								for($a=0;$a<count($otherData);$a++){
									$link = "";
									if(isset($otherData[$a]->link)){
										$link = stripslashes($otherData[$a]->link);
									}
								
						?>
						<div id="TaxtBoxesGroup1">
						<div class="cHalf"><input id="" class="tertext mainFont auto_save_input" name="n_name[]"   type="text" value="<?php echo $otherData[$a]->name;?>" placeholder="Name of Potential Licensee" /></div>
						<div class="cHalf"><input id="" class="tertext mainFont auto_save_input" name="r_lice[]"  type="text" value="<?php echo $otherData[$a]->lice;?>" placeholder="Name/Type of its Suspected Infringing Product/System" /></div>
						<div class="patenteespara mainColor mainWidth mainMarginBottom mainFont ">Is there existing Evidence of Use?<input id="evidence_e<?php echo $ss;?>" style="padding-top: 0px; margin-bottom: 3px;margin-left:8px;margin-right:3px;" name="evidence_e<?php echo $ss;?>" type="radio" value="No" class='auto_save_input' <?php if($otherData[$a]->evidence=="No"):?> CHECKED <?php endif;?>/>NO<input id="evidence_e<?php echo $ss;?>" class='auto_save_input' style="padding-top: 0px; margin-bottom: 3px;margin-left:10px;margin-right:3px;" name="evidence_e<?php echo $ss;?>" type="radio" value="Yes" <?php if($otherData[$a]->evidence=="Yes"):?> CHECKED <?php endif;?> />YES, here is a link</div>
						<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 200px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" class="tertext mainFont auto_save_input" style="width: 260px !important;" name="r_link[]" type="text" value="<?php echo $link;?>" placeholder=""></div>
						<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">
							<div class="cHalf rem mainMarginBottom" style="display:none;">
								<a class="aminus p_image-1" onclick="removeText(jQuery(this))" href="javascript://"></a>
								<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the potential licensee above</div>
							</div>
							<?php if(count($otherData)-1==$a):?>
							<div class="cHalf adem"><a class="aplus p_image" onclick="add(jQuery(this))" href="javascript://"></a> 
								<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another potential licensee</div>
							</div>
							<?php endif;?>
						</div>
						</div>
						<?php
								$ss++;
							}
								
							} else {
						?>
						
						<div id="TaxtBoxesGroup1">
						<div class="cHalf"><input id="textbox1" class="tertext mainFont auto_save_input" name="n_name[]"  type="text" value="" placeholder="Name of Potential Licensee" /></div>
						<div class="cHalf"><input id="textbox1" class="tertext mainFont auto_save_input" name="r_lice[]"  type="text" value="" placeholder="Name/Type of its Suspected Infringing Product/System" /></div>
						<div class="patenteespara mainColor mainWidth mainMarginBottom mainFont">Is there existing Evidence of Use?<input id="" style="padding-top: 0px; margin-bottom: 3px;margin-left:8px;margin-right:3px;" name="evidence_e1" type="radio" value="No" class='auto_save_input'/>NO <input id="" style="padding-top: 0px; margin-bottom: 3px;margin-left:10px;margin-right:3px;" name="evidence_e1" type="radio" value="Yes" class='auto_save_input' />YES, here is a link</div>
						<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 200px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" class="tertext mainFont" style="width: 260px !important;" name="r_link[]" type="text" value="" placeholder=""></div>
						<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">
							<div class="cHalf rem mainMarginBottom" style="display:none;">
								<a class="aminus p_image-1" onclick="removeText(jQuery(this))" href="javascript://"></a>
								<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the potential licensee above</div>
							</div>
							<div class="cHalf adem"><a class="aplus p_image" onclick="add(jQuery(this))" href="javascript://"></a> 
								<div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another potential licensee</div>
							</div>
						</div>
						</div>
							<?php } ?>
						
						</div>
						<h2 class="headingPOP1 mainColor">Seller’s Info:</h2>
						<div class="cHalf"><input id="companyname" class="tertext mainFont auto_save_input" name="companyname"  type="text" value="<?php echo $typeName;?>" placeholder="Seller’s Name " /></div>
						<div class="cHalf"><input id="Broker" class="tertext mainFont auto_save_input" name="Broker"  type="text" value="<?php echo $broker;?>" placeholder="Broker " /></div>
						<div class="cHalf"><input id="fname" class="tertext mainFont auto_save_input" name="fname"  type="text" value="<?php echo (string)$first_name;?>" placeholder="First Name " /></div>
						<div class="cHalf"><input id="lname" class="tertext mainFont auto_save_input" name="lname"  type="text" value="<?php echo (string)$last_name;?>" placeholder="Last Name " /></div>
						<div class="cHalf"><input type="text" name="email" value="<?php echo (string)$email_address;?>" class="tertext mainFont auto_save_input" id="email"   placeholder="Email Address"></div>
						<div class="cHalf"><textarea class="tertext auto_save_input" id="address" style="width: 100%; height: 100px !important; padding: 10px;" cols="4" name="address" rows="4" placeholder="Note"><?php echo (string)$note;?></textarea></div>
						</div>
						<div class="cHalf">
							<input type="hidden" name="contact_id" id="contact_id" value="0"/>
							<input type="hidden" name="serial_number" id="serial_number" value="<?php echo $serial_number?>"/>
							<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_number?>"/>
							<input type="hidden" name="type" id="type" value="<?php if(count($single_data)>0):?><?php echo $single_data->type;?><?php else:?>1<?php endif;?>"/>
							<button type="submit" class='btn btn-primary btn-mwidth pull-right'>Save</button>
							<!--<a href='javascript:void(0)' onclick="enablePreLeadForm();" class='btn btn-default btn-mwidth pull-right mrg10R'>Cancel</a>-->
						</div>
			</form>
			<div style="clear: both;padding-top: 25px;">
				<hr>
			</div>
		</div>
	</div>
<div id="allOtherPreLead" style='display: <?php if((int)$lead_number>0 && (int)$serial_number>0):	?> none; <?php else:?> block; <?php endif;?>'>
<?php 
if(count($lists)>0){
	$i=0;
	foreach($lists as $contact){
?>
	<script>
		window._cn<?php echo $contact->id;?> = [<?php echo json_encode($contact)?>];     
	</script>
<div class="row mrg25T" style='width:670px;' id="showData<?php echo $contact->id;?>">	
	<div class="col-xs-12" style='width:100%; <?php if($i>0):?>border-top:5px solid #d9534f;padding-top:20px; <?php endif;?>'>
		<div class="col-xs-12" style=''><a style='' href='javascript://' onclick="moveToLead(<?php echo $contact->id;?>)" class='btn btn-primary mrg5R'>Move to Selected Lead</a>&nbsp;&nbsp;&nbsp;<a style='' href='javascript://' onclick="deleteLead(<?php echo $contact->id;?>)" class=' btn btn-default mrg5R '>Delete</a>&nbsp;&nbsp;&nbsp;<a style='' href='javascript://' onclick="editLead('<?php echo $contact->id;?>')" class=' btn btn-default mrg5R '>Edit</a> &nbsp; &nbsp; &nbsp; <?php if($contact->pre_serial_number==0):?><span> Received from web on <?php echo $contact->create_date?></span><?php else:?><span> Created on <?php echo $contact->create_date?></span><?php endif;?><span class="pull-right" style="margin-top:7px;"><?php if($contact->pre_serial_number!=0):?>(P/N) <?php echo $contact->pre_serial_number?><?php endif;?></span></div>
		<div id="" class="col-xs-12">
			<?php 
				$other = $contact->other_field;
				$anotherLicense = array();
				if($other!=""){
					$anotherLicense = json_decode($other);
				}
				
				$n_patents="";
				$essnt="";
				$n_lic="";
				$technologies="";
				$standards="";
				$markets="";
				$products="";
				$u_upfront="";
				$otherData = array();
				if(count($anotherLicense)>0){
					$n_patents = $anotherLicense->n_patents;
					$essnt = $anotherLicense->essnt;
					$n_lic = $anotherLicense->n_lic;
					$technologies = $anotherLicense->technologies;
					$standards = $anotherLicense->standards;
					$markets = $anotherLicense->markets;
					$products = $anotherLicense->products;
					$u_upfront = $anotherLicense->u_upfront;
					$otherData = $anotherLicense->another_license;
					$patent = array();
					if(isset($anotherLicense->patent_list)){
						$patent = $anotherLicense->patent_list;
					}
				}
			
			?>
			
			<h2 class="headingPOP1 mainColor" style="margin-top:15px;">Assets:</h2>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" style="width:100% !important;"><span class="mainColor mainFont">1. Number of patents you wish to sell:</span></p>			
			</div>
			<div id="patentNumbers" style="margin-bottom: 5px !important;">
				<?php 
					$pt=1;
					for($p=0;$p<count($patent);$p++){
						$country = $patent[$p]->country;
						$application = $patent[$p]->application;
						$patentN = $patent[$p]->patent;
				?>
				<div id="patentNumbers<?php echo $pt;?>">
					<div class="cHalf">
					<input id="textbox1"  class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter" style="display: inline-block !important;width: 154px !important;" name="country_n[]"  type="text" value="<?php echo $country;?>" placeholder="Country" />
					<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter" style="display: inline-block !important;width: 154px !important;" name="patent_n[]"  type="text" value="<?php echo $patentN;?>" placeholder="Patents" title="Patents" />
					<input id="textbox1" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter" style="display: inline-block !important;width: 154px !important;" name="application_n[]"  type="text" value="<?php echo $application;?>" placeholder="Applications" title="Applications"/>
					</div>
				</div>
				<?php
						$pt++;
					}
				?>
			</div>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop"><span class="mainColor mainFont">2. How many are Standard Essential?</span></p>
			<input id="" onkeypress="return isNumber(event)" class="p_tet noPadding  mainFont  p_100 mainTextCenter"  maxlength="3" name=""  type="text" value="<?php echo $essnt;?>" placeholder="" readonly /></div>
			
			<h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Price:</h2>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont">3. Your total asking price ($): </span></p>
			<input id="u_upfront" class="p_tet  noPadding  mainFont p_100 mainTextCenter" maxlength="9" name=""  type="text" readonly value="<?php /*$price = filter_var($u_upfront, FILTER_SANITIZE_NUMBER_INT); echo number_format($price)*/ echo $u_upfront;?>" placeholder=" " /></div>										
			<div class="cHalf" id="alert_message" style="display:none"></div>
			
			<h2 class="headingPOP1 mainColor" style="margin-top:0px;margin-bottom:0px;">Market:</h2>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" style='width:260px !important'><span class="mainColor mainFont">4. Number of expected licensees (total):</span></p>
			<input id="" onkeypress="return isNumber(event)" onchange="inputKeyUp(event,jQuery(this))"  class="p_tet noPadding  mainFont p_100 mainTextCenter" maxlength="3" name="" type="text" value="<?php echo $n_lic;?>" placeholder=" " readonly /></div>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">5.Relating Technologies: </span></p>
			<input id="" class="tertext mainFont" name="" readonly type="text" value="<?php echo $technologies;?>" /></div>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">6.Relating Standards: </span></p>
			<input id="" class="tertext mainFont" name="" readonly type="text" value="<?php echo $standards;?>" /></div>
			<div class="cHalf">
			<p class="patenteespara patenteespara1 mainPaddingTop" ><span class="mainColor mainFont" style="width:200px!important;">7.Relating Markets: </span></p>
			<input id="" class="tertext mainFont" name="" readonly type="text" value="<?php echo $markets;?>" /><input id="" class="tertext mainFont" name="" readonly type="hidden" value="<?php echo $products;?>" /></div>
			
			<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 100% !important;"><span class="mainColor mainFont">9. Potential interested licensees under your patents:</span></p></div>
			
			<div id="TaxtBoxesGroup" style="margin-bottom: 5px !important;">
			<?php 
				if(count($otherData)>0){
					$ss = 1;
					for($a=0;$a<count($otherData);$a++){
						$link = "";
						if(isset($otherData[$a]->link)){
							$link = $otherData[$a]->link;
						}
		
			?>
			<div id="TaxtBoxesGroup1">
			<div class="cHalf"><input id="" class="tertext mainFont" name="" readonly type="text" value="<?php echo $otherData[$a]->name;?>" placeholder="Name of Potential Licensee" /></div>
			<div class="cHalf"><input id="" class="tertext mainFont" name="" readonly type="text" value="<?php echo $otherData[$a]->lice;?>" placeholder="Name/Type of its Suspected Infringing Product/System" /></div>
			<div class="patenteespara mainColor mainWidth mainMarginBottom mainFont">Is there existing Evidence of Use? <?php if($otherData[$a]->evidence=="Yes"):?> YES, here is a link <?php endif;?> <?php if($otherData[$a]->evidence=="No"):?> NO <?php endif;?></div>
			<div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 200px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" readonly class="tertext mainFont" style="width: 260px !important;" name="r_link[]" type="text" value="<?php echo $link;?>" placeholder=""></div>
			<div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px;">
			</div>
			</div>
			<?php 
				}
					$ss++;
				}
			?>
			</div>  
			<h2 class="headingPOP1 mainColor">Seller’s Info:</h2>
			<div class="cHalf"><input id="" class="tertext mainFont" name=""  type="text" value="<?php echo (string)$contact->type_name; ?>" placeholder="Seller’s Name " /></div>
			<div class="cHalf"><input id="" class="tertext mainFont" name=""  type="text" value="<?php echo (string)$contact->broker; ?>" placeholder="Broker " /></div>
			<div class="cHalf"><input id="" class="tertext mainFont" name=""  type="text" value="<?php echo (string)$contact->first_name; ?>" placeholder="First Name " /></div>
			<div class="cHalf"><input id="" class="tertext mainFont" name=""  type="text" value="<?php echo (string)$contact->last_name; ?>" placeholder="Last Name " /></div>
			<div class="cHalf"><input type="text" name="" value="<?php echo (string)$contact->email_address; ?>" class="tertext mainFont" id="email"    placeholder="Email Address"></div>
			<div class="cHalf"><textarea class="tertext" id="" style="width: 100%; height: 100px !important; padding: 10px;" cols="4" name="" rows="4" placeholder="Note"><?php echo (string)$contact->note; ?></textarea></div>
			
		</div>
	</div>
	
</div>
<?php
					$i++;		
						}
					} else {
?>
	<div class="row"><div class="col-xs-12" style='width:100%;'><p class='alert alert-warning'>No record found!</p></div></div>
<?php						
					}
				?>
</div>			
<script>





function enablePreLeadForm(){
	if(jQuery("#pre_lead").css('display')=="none"){
		jQuery('a.serial_number').remove();
		jQuery("#formPreLead")[0].reset();
		jQuery("#pop_text").css("display","none");
		jQuery(".cbP").css("display","none");
		jQuery("#patentNumbers").empty();
		jQuery("#TaxtBoxesGroup").empty();
		counter=0;
		counterPatent=0;
		add('object');
		addPatent('object');
		jQuery("#pre_lead").css('display',"block");
		jQuery("#emableBTNForm").css('display',"none");
		jQuery("#contact_id").val(0);
	} else {
		jQuery("#pre_lead").css('display',"none");
		jQuery("#emableBTNForm").css('display',"block");
		jQuery("#contact_id").val(0);
	}
}   

function enableSwitch(d){
	if(d==1){
		jQuery("#pre_lead").css('display',"none");
		jQuery("#allOtherPreLead").css('display','');		
		jQuery("#emableBTNForm").attr('onclick','enableSwitch(0)');
	} else {
		jQuery("#pre_lead").css('display',"block");
		jQuery("#allOtherPreLead").css('display','none');
		jQuery("#emableBTNForm").attr('onclick','enableSwitch(1)');
	}
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
var counter = 2;
function add(object) {
   jQuery("#TaxtBoxesGroup").find('.adem').css('display','none');			  
	var newTextBoxDiv = $('<div/>').attr("id", 'TaxtBoxesGroup' + counter);			  
	newTextBoxDiv.html(' <div class="cHalf" ><input type="text" name="n_name[]" value="" class="tertext mainFont auto_save_input" id="textbox' + counter + '"    placeholder="Name of Potential Licensee"  ></div><div class="cHalf"> <input type="text" name="r_lice[]" value="" class="tertext mainFont auto_save_input" id="textbox' + counter + '"    placeholder="Name/Type of its Suspected Infringing Product/System"  ></div><div class="cHalf patenteespara mainColor mainWidth mainMarginBottom mainFont" >Is there existing Evidence of Use?<input type="radio" name="evidence_e' + counter + '" value="No" class="auto_save_input" id="" style="margin-bottom:3px;margin-left:8px;margin-right:3px;" > NO<input type="radio" name="evidence_e' + counter +
		  '" value="Yes" class="auto_save_input" id="" style="margin-bottom:3px;margin-left:10px;margin-right:3px;">YES</div><div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 200px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" class="tertext mainFont auto_save_input" style="width: 260px !important;" name="r_link[]" type="text" value="" placeholder=""></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem mainMarginBottom"><a class="aminus p_image-1"  onclick="removeText(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the potential licensee above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="add(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another potential licensee</div></div></div>'); 		  
	newTextBoxDiv.appendTo("#TaxtBoxesGroup");
	if(jQuery("#TaxtBoxesGroup").find('.rem').length>1){
		jQuery("#TaxtBoxesGroup").find('.rem').css('display','inline-block');
	}
	jQuery('.auto_save_input').change(function(){
		saveCall();
	});	
	counter++;
}
function removeText(object){
	if(counter==1){
	  alert("No more textbox to remove");
	  return false;
	} 			  
	counter--;
	object.parent().parent().parent().remove();
	//$("#TaxtBoxesGroup" + counter).remove();
	if(jQuery("#TaxtBoxesGroup").find('.rem').length==1){
		jQuery("#TaxtBoxesGroup").find('.rem').css('display','none');
	}
	jQuery("#TaxtBoxesGroup").find('.adem').eq(jQuery("#TaxtBoxesGroup").find('.adem').length-1).css('display','inline-block');
}
			
function inputKeyUp(event,object){

	
	if(event.which >= 37 && event.which <= 40){
		event.preventDefault();
	}
	var $this = $(this);
	var upFront = jQuery('input[name="u_upfront"]').val();

	var license = jQuery('input[name="n_patents"]').val();
	_tempNum2 = upFront.toString().replace(',',"");
	_tempNum2 = _tempNum2.toString().replace(',',"");
	if(_tempNum2!=""){
		_tempNum2 = parseInt(_tempNum2);
	} else {
		_tempNum2 = 0;
	}
	var num = _tempNum2.toString().replace(/,/gi, "").split("").reverse().join("");
	var num2 = RemoveRougeChar(num.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
		if(parseInt(num2)>0){
		jQuery('input[name="u_upfront"]').val(num2);	} else {		jQuery('input[name="u_upfront"]').val('');	}
	num2=num2.toString().replace(',',"");
	numVal=_tempNum2;
	_upfront = numVal*2/5
	_licSta = parseInt(numVal)/3;
	_riskSta = (parseInt(numVal)*4)/15;
	_mainTotal = _licSta + _riskSta + parseInt(_upfront);
	_licSta=_licSta.toFixed(0);
	_riskSta=_riskSta.toFixed(0);
	_mainTotal=_mainTotal.toFixed(0);
	_ask_price = jQuery('input[name="u_upfront"]').val();
	if(_ask_price!=""){
		jQuery("#u_upfront_100").val('$'+format("#,###.",_upfront));
		jQuery("#regular_license").val('$'+format("#,###.",_licSta));
		jQuery("#risk_reverse").val('$'+format("#,###.",_riskSta));
		jQuery("#total_rewards").val('$'+format("#,###.",_mainTotal));		
	}
	_total_reward = jQuery("#total_rewards").val();
	_noOfLicense = jQuery("#n_lic").val();
	if(_noOfLicense>3){
		_cal_per = parseInt((_noOfLicense*30)/100);
	} else {
		_cal_per = parseInt(_noOfLicense);
	}
	_ask_price  = _ask_price.replace(",","");
	_ask_price  = _ask_price.replace(",","");
	//_perParticipant = _cal_per * parseInt(_ask_price);
	_perParticipant =  parseInt(_ask_price)/ _cal_per;
	_perParticipantPatent =  parseInt(_ask_price)/ parseInt(jQuery("#n_patents").val());
	_textUpdate = "";
	_alertClass='none';
	_sendClass='inline-block';
	if(object.attr('id')=='u_upfront' && _ask_price!=0){
		jQuery("#pop_text").css('display','inline-block');
		jQuery(".cbP").css('display','inline-block');
	}
	if(_noOfLicense!="" && _noOfLicense<10){
		_textUpdate +="<p style='padding: 10px;display: inline-block;color: rgba(194, 0, 0, 0.8); font-size: 16px;font-weight:normal;background:rgb(35, 31, 32) !important;margin-bottom:0px;'>The Open Licensing Program was designed to provide license opportunities to a larger number of operating companies. Perhaps additonal research would reveal a larger number of potential licensees.</p>";
		_alertClass='inline-block';
		_sendClass='none';
		jQuery("#pop_text").css('display','none');
		jQuery(".cbP").css('display','none');
	} else {
		if(parseFloat(_perParticipant)>2000000){
			if(_perParticipantPatent>100000){
				_alertClass='inline-block';
				_sendClass='none';			
				_textUpdate ='<p style="padding: 10px;display: inline-block;color: rgba(194, 0, 0, 0.8); font-size: 16px;font-weight:normal;background:rgb(35, 31, 32) !important;">30% of the licensees ('+_cal_per+') are expected to participate in the syndicate and fund your asking upfront price, each paying $'+format("#,###.",_perParticipant)+' (your asking price / number of Participants). This sum is quite high, considering the the number of patents that you wish to sell. Thus, your asking upfront price seems un-transactable. In light of your expected total return of '+_total_reward+', perhaps you would like to adjust your asking upfront price.</p>';
			}
		}
	}
	jQuery("#alert_message").css('display',_alertClass);
	jQuery("#send_button").css('display',_sendClass);
	jQuery("#alert_message").html(_textUpdate);
	// _licSta = RemoveRougeChar(_licSta.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
	// _riskSta = RemoveRougeChar(_riskSta.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
	// _mainTotal = RemoveRougeChar(_mainTotal.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
	
	//jQuery("#risksta").val(_riskSta);
	//jQuery("#t_reward").val(_mainTotal);  
	saveCall();
	
}

function RemoveRougeChar(convertString){
    
    
    if(convertString.substring(0,1) == ","){
        
        return convertString.substring(1, convertString.length)            
        
    }
    return convertString;
    
}




    


	function enableLeadForm(){
		
	}
	
counterPatent=2;

function removePatent(object){
	if(counterPatent==1){
	  alert("No more textbox to remove");
	  return false;
	} 			  
	counterPatent--;
	object.parent().parent().parent().remove();
	//$("#patentNumbers" + counterPatent).remove();
	if(jQuery("#patentNumbers").find('.rem').length==1){
		jQuery("#patentNumbers").find('.rem').css('display','none');
	}
	jQuery("#patentNumbers").find('.adem').eq(jQuery("#patentNumbers").find('.adem').length-1).css('display','inline-block');
}
function addPatent(object) {
   jQuery("#patentNumbers").find('.adem').css('display','none');			  
	var newTextBoxDiv = $('<div/>').attr("id", 'patentNumbers' + counterPatent);			  
	newTextBoxDiv.html('<div class="cHalf" ><input type="text" name="country_n[]" value=""  class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input"  style="display: inline-block !important;width: 154px !important;" id="textbox' + counterPatent + '"    placeholder="Country"  > <input type="text" name="patent_n[]" value="" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" id="textbox' + counterPatent + '" style="display: inline-block !important;width: 154px !important;"   placeholder="Patents" title="Patents" > <input type="text" name="application_n[]" value="" class="p_tet noPadding noMargin  p_100 mainFont mainTextCenter auto_save_input" id="textbox' + counterPatent + '"   style="display: inline-block !important;width: 154px !important;" placeholder="Applications" title="Applications" > </div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem"><a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="addPatent(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div></div></div>'); 		  
	newTextBoxDiv.appendTo("#patentNumbers");
	if(jQuery("#patentNumbers").find('.rem').length>1){
		jQuery("#patentNumbers").find('.rem').css('display','inline-block');
	}			  
	counterPatent++;
	jQuery('.auto_save_input').change(function(){
		saveCall();
	});
}

	function editLead(contactID){
		if(contactID){
			jQuery("#pre_lead").css("display","block");			
			jQuery(window).scrollTop(0);
			if(window["_cn"+contactID]!=undefined){
				_s = window["_cn"+contactID];
				_data = _s[0];
				jQuery('a.serial_number').remove();
				if(typeof _data.popup_type!="undefined" && parseInt(_data.popup_type)==4){
					if(_data.pre_serial_number>0){
						jQuery("#pre_serial_number").val(_data.pre_serial_number);
						jQuery("#TextBoxesGroup").before("<a class='serial_number' style='float:right;color:#56b2fe' target='_BLANK' href='http://www.synpat.com/sellerform/?sr="+_data.pre_serial_number+"' class='btn btn-primary btn-block'>Synpat.com</a>")
					} else {
						jQuery("#pre_serial_number").val(_data.serial_number);
					}
					jQuery("#type").val(_data.type);
					_anotherLicense = jQuery.parseJSON(_data.other_field);
					if(_anotherLicense.patent_list!=undefined){
						jQuery("#patentNumbers").empty();
						if(_anotherLicense.patent_list.length>0){
							for(i=0;i<_anotherLicense.patent_list.length;i++){
								var newTextBoxDiv = $('<div/>').attr("id", 'patentNumbers' + counterPatent);	
								_country = _anotherLicense.patent_list[i].country;
								_application = _anotherLicense.patent_list[i].application;
								_patent = _anotherLicense.patent_list[i].patent;
								newTextBoxDiv.html(' <div class="cHalf" ><input type="text" name="country_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" id="textbox' + counterPatent + '" value="'+_country+'"    placeholder="Country"  > <input type="text" name="patent_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value="'+_patent+'"   id="textbox' + counterPatent + '"  title="Patents"   placeholder="Patents"  > <input type="text" name="application_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value="'+_application+'"   id="textbox' + counterPatent + '" title="Applications"    placeholder="Applications"  ></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem"><a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="addPatent(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div></div></div>'); 
								jQuery("#patentNumbers").append(newTextBoxDiv);
								if(jQuery("#patentNumbers").find('.rem').length>1){
									jQuery("#patentNumbers").find('.rem').css('display','inline-block');
								}			  
								counterPatent++;
							}
							if(jQuery("#patentNumbers").find('.adem').length>1){							
								jQuery("#patentNumbers").find('.adem').not(':last').remove();
							}
						} else {
							jQuery("#patentNumbers").find('.adem').css('display','none');			  
							var newTextBoxDiv = $('<div/>').attr("id", 'patentNumbers' + counterPatent);			  
							newTextBoxDiv.html('<div class="cHalf" ><input type="text" name="country_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" id="textbox' + counterPatent + '"    placeholder="Country"  > <input type="text" name="application_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value=""  id="textbox' + counterPatent + '"  title="Application"  placeholder="Application"  > <input type="text" name="patent_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value=""  id="textbox' + counterPatent + '" title="Patents"   placeholder="Patent"  ></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem"><a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="addPatent(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div></div></div>'); 		  
							newTextBoxDiv.appendTo("#patentNumbers");
							if(jQuery("#patentNumbers").find('.rem').length>1){
								jQuery("#patentNumbers").find('.rem').css('display','inline-block');
							}			  
							counterPatent++;
						}
					} else {
						jQuery("#patentNumbers").find('.adem').css('display','none');			  
						var newTextBoxDiv = $('<div/>').attr("id", 'patentNumbers' + counterPatent);			  
						newTextBoxDiv.html('<div class="cHalf" ><input type="text" name="country_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" id="textbox' + counterPatent + '"    placeholder="Country"  > <input type="text" name="application_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value=""  id="textbox' + counterPatent + '"  title="Application"  placeholder="Application"  > <input type="text" name="patent_n[]" class="p_tet noPadding noMargin  mainFont mainTextCenter auto_save_input" style="display:inline-block !important;width:154px !important;" value=""  id="textbox' + counterPatent + '"    placeholder="Patent" title="Patent" ></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem"><a class="aminus p_image-1"  onclick="removePatent(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the line above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="addPatent(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another jurisdiction</div></div></div>'); 		  
						newTextBoxDiv.appendTo("#patentNumbers");
						if(jQuery("#patentNumbers").find('.rem').length>1){
							jQuery("#patentNumbers").find('.rem').css('display','inline-block');
						}			  
						counterPatent++;
					}
					
					
					
					/*
					jQuery("#n_patents").val(_anotherLicense.n_patents);
					*/
					jQuery("#essnt").val(_anotherLicense.essnt);
					jQuery("#n_lic").val(_anotherLicense.n_lic);
					jQuery("#u_upfront").val(_anotherLicense.u_upfront);
					var upFront = jQuery('input[name="u_upfront"]').val();
					var license = jQuery('input[name="n_patents"]').val();
					_tempNum2 = upFront.toString().replace(',',"");
					_tempNum2 = _tempNum2.toString().replace(',',"");
					if(_tempNum2!=""){
						_tempNum2 = parseInt(_tempNum2);
					} else {
						_tempNum2 = 0;
					}
					var num = _tempNum2.toString().replace(/,/gi, "").split("").reverse().join("");
					var num2 = RemoveRougeChar(num.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
						if(parseInt(num2)>0){
						jQuery('input[name="u_upfront"]').val(num2);	} else {		jQuery('input[name="u_upfront"]').val('');	}
					num2=num2.toString().replace(',',"");							
					numVal=_tempNum2;
					_upfront = numVal*2/5
					_licSta = parseInt(numVal)/3;
					_riskSta = (parseInt(numVal)*4)/15;
					_mainTotal = _licSta + _riskSta + parseInt(_upfront);
					_licSta=_licSta.toFixed(0);
					_riskSta=_riskSta.toFixed(0);
					_mainTotal=_mainTotal.toFixed(0);
					_ask_price = jQuery('input[name="u_upfront"]').val();
					if(_ask_price!=""){
						jQuery("#u_upfront_100").val('$'+format("#,###.",_upfront));
						jQuery("#regular_license").val('$'+format("#,###.",_licSta));
						jQuery("#risk_reverse").val('$'+format("#,###.",_riskSta));
						jQuery("#total_rewards").val('$'+format("#,###.",_mainTotal));		
					}							
					_total_reward = jQuery("#total_rewards").val();
					_noOfLicense = jQuery("#n_lic").val();
					if(_noOfLicense>3){
						_cal_per = parseInt((_noOfLicense*30)/100);
					} else {
						_cal_per = parseInt(_noOfLicense);
					}
					_ask_price  = _ask_price.replace(",","");
					_ask_price  = _ask_price.replace(",","");
					//_perParticipant = _cal_per * parseInt(_ask_price);
					_perParticipant =  parseInt(_ask_price)/ _cal_per;
					_perParticipantPatent =  parseInt(_ask_price)/ parseInt(jQuery("#n_patents").val());
					_textUpdate = "";
					_alertClass='none';
					_sendClass='inline-block';
					if(_ask_price!=0){
						jQuery("#pop_text").css('display','inline-block');
						jQuery(".cbP").css('display','inline-block');
					}
					if(_noOfLicense!="" && _noOfLicense<10){
						_textUpdate +="<p style='padding: 10px;display: inline-block;color: rgba(194, 0, 0, 0.8); font-size: 16px;font-weight:normal;background:rgb(35, 31, 32) !important;margin-bottom:0px;'>The Open Licensing Program was designed to provide license opportunities to a larger number of operating companies. Perhaps additonal research would reveal a larger number of potential licensees.</p>";
						_alertClass='inline-block';
						_sendClass='none';
						jQuery("#pop_text").css('display','none');
						jQuery(".cbP").css('display','none');
					} else {
						if(parseFloat(_perParticipant)>2000000){
							if(_perParticipantPatent>100000){
								_alertClass='inline-block';
								_sendClass='none';			
								_textUpdate ='<p style="padding: 10px;display: inline-block;color: rgba(194, 0, 0, 0.8); font-size: 16px;font-weight:normal;background:rgb(35, 31, 32) !important;">30% of the licensees ('+_cal_per+') are expected to participate in the syndicate and fund your asking upfront price, each paying $'+format("#,###.",_perParticipant)+' (your asking price / number of Participants). This sum is quite high, considering the the number of patents that you wish to sell. Thus, your asking upfront price seems un-transactable. In light of your expected total return of '+_total_reward+', perhaps you would like to adjust your asking upfront price.</p>';
							}
						}
					}
					jQuery("#alert_message").css('display',_alertClass);
					jQuery("#send_button").css('display',_sendClass);
					jQuery("#alert_message").html(_textUpdate);
					jQuery("#textbox1").val(_anotherLicense.technologies);
					jQuery("#Standards").val(_anotherLicense.standards);
					jQuery("#Markets").val(_anotherLicense.markets);
					jQuery("#Products").val(_anotherLicense.products);							
					if(_anotherLicense.another_license.length>0){								
						counter =1; 
						jQuery("#TaxtBoxesGroup").empty()
						for(i=0;i<_anotherLicense.another_license.length;i++){									
							jQuery("#TaxtBoxesGroup").find('.adem').css('display','none');			  
							var newTextBoxDiv = $('<div/>').attr("id", 'TaxtBoxesGroup' + counter);	
							_name = _anotherLicense.another_license[i].name;
							_r_lice = _anotherLicense.another_license[i].lice;
							ychecked="";
							nchecked="";
							if(_anotherLicense.another_license[i].evidence=="No"){
								nchecked = "CHECKED='CHECKED'";
							}
							if(_anotherLicense.another_license[i].evidence=="Yes"){
								ychecked = "CHECKED='CHECKED'";
							}
							_link = "";
							if(_anotherLicense.another_license[i].link!=undefined){
								_link = _anotherLicense.another_license[i].link;
							}
							newTextBoxDiv.html(' <div class="cHalf" ><input type="text" name="n_name[]" value="'+_name+'" class="tertext mainFont auto_save_input" id="textbox' + counter + '"    placeholder="Name of Potential Licensee"  ></div><div class="cHalf"> <input type="text" name="r_lice[]" value="'+_r_lice+'" class="tertext mainFont auto_save_input" id="textbox' + counter + '"    placeholder="Name/Type of its Suspected Infringing Product/System"  ></div><div class="cHalf patenteespara mainColor mainWidth mainMarginBottom mainFont" >Is there existing Evidence of Use?<input type="radio" name="evidence_e' + counter +  '" value="No" '+nchecked+' class="auto_save_input" id="" style="margin-bottom:3px;margin-left:8px;margin-right:3px;" >NO<input type="radio" name="evidence_e' + counter + '" '+ychecked+' value="Yes" class="auto_save_input" id="" style="margin-bottom:3px;margin-left: 10px;margin-right:3px;">YES,here ia a link</div><div class="cHalf"><p class="patenteespara  mainPaddingTop" style="width: 200px !important;"><span class="mainColor mainFont">to the Evidence of Use: </span></p><input id="" class="tertext mainFont auto_save_input" style="width: 260px !important;" name="r_link[]" type="text" value="'+_link+'" placeholder=""></div><div class="cHalf mainMarginTop mainMarginBottom" style="padding-left: 13px; "><div class="cHalf rem"><a class="aminus p_image-1"  onclick="removeText(jQuery(this))" href="javascript://"></a><div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Remove the potential licensee above</div></div><div class="cHalf adem"><a class="aplus p_image" onclick="add(jQuery(this))" href="javascript://"></a> <div class="additionlic" style="display: inline-block; margin-left: 0px; font-size: 17px;">Add another potential licensee</div></div></div>'); 
							jQuery("#TaxtBoxesGroup").append(newTextBoxDiv);
							if(jQuery("#TaxtBoxesGroup").find('.rem').length>1){
								jQuery("#TaxtBoxesGroup").find('.rem').css('display','inline-block');
							}			  
							counter++;
						}
					}
					jQuery("#companyname").val(_data.type_name);
					jQuery("#Broker").val(_data.broker);
					jQuery("#fname").val(_data.first_name);
					jQuery("#lname").val(_data.last_name); 
					jQuery("#email").val(_data.email_address);
					jQuery("#address").val(_data.note);							
				}
			}
		}
	}
	function deleteLead(contactID){
		if(contactID>0){
			r= confirm("Are you sure want to delete?");
			if(r){
				jQuery.ajax({
					url:'<?php echo $Layout->baseUrl?>/opportunity/delete_web_lead',
					type:'POST',
					data:{i:contactID},
					cache:false,
					success:function(data){
						if(data>0){
							jQuery("#showData"+contactID).remove();
							_l = window.parent.jQuery("#web_lead").find('span').text();
							if(parseInt(_l)>0){
								_l = parseInt(_l)-1;
								window.parent.jQuery("#web_lead").find('span').html(_l);
							}
						} else {
							alert("Server busy, Please try after sometime.");
						}
					}
				});
			}			
		}
	}
	
	function moveToLead(contactID){
		if(window.parent.snp!=0 && window.parent.leadGlobal!=0){
			jQuery.ajax({
				type:'POST',	
				url:'<?php echo $Layout->baseUrl?>opportunity/sr_attached',
				data:{lead:window.parent.leadGlobal,sr:window.parent.snp,contact:contactID},
				cache:false,
				success:function(data){
					if(data!=""){
						jQuery("#showData"+contactID).remove();
						_data = jQuery.parseJSON(data);
						window.parent.updatePatenteesData(_data);
					}
				}
			});
		} else {
			alert("Please save lead first and then then try to move contact to lead.");
		}		
	}
</script>
</div>
</div>