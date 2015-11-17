<?php echo doctype('html5'); ?>
<html lang="en">
<head>
<?php echo meta($meta); ?>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php echo $title_for_layout; ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>.spinner{margin:0;width:70px;height:18px;margin:-35px 0 0 -9px;position:absolute;top:50%;left:50%;text-align:center}.spinner>div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}</style>
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $Layout->baseUrl; ?>public/images/icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style_new_without_compress.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style.css">-->
<script>var __baseUrl="<?php echo $Layout->baseUrl; ?>",snp=0,systemLoginSession=0,chDa=1;</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/assets.js"></script>
<!--<script type="text/javascript" src="https://s3.amazonaws.com/backyard.synpat.com/assets.js"></script>-->
<script>function inputStringFieldsWidth(){$(".input-string-group").each(function(g,d){var j=$(this),i=j.find("label"),h=j.find(".form-control");h.is(":focus")||(i.length?h.width(j.width()-i.width()-20):h.width(j.width()-20),15>=h.width()?h.addClass("nopadding").css("text-align","right"):h.removeClass("nopadding").css("text-align","left"))})}function rowWidth(){$(".row.row-width").each(function(g,d){var j=$(d),i=j.find(">div:not(.col-width)").length,h=0;j.find(">div.col-width").each(function(f,e){var k=$(e);h+=k.outerWidth()});i&&j.find(">div:not(.col-width)").each(function(f,c){var k=$(c),l=100,l=k.attr("class").split(" "),l=l.filter(function(b){return -1!==b.indexOf("col-")});if(l.length){l=l[0].split("-")[2];switch(l){case"1":l=8.3333;break;case"2":l=16.6667;break;case"3":l=25;break;case"4":l=33.3333;break;case"5":l=41.6667;break;case"6":l=50;break;case"7":l=58.3333;break;case"8":l=66.6667;break;case"9":l=75;break;case"10":l=83.3333;break;case"11":l=91.6667;break;case"12":l=100;break;case"1x5":l=20;break;default:l=100}k.outerWidth((j.width()-h)*l/100-1)}})})}var _panelMarginBottom=4,_inputStringFieldsWidthInterval=null,_inputStringFieldsWidthIntervalPeriod=700,_rowWidthInterval=null,_rowWidthIntervalPeriod=300;function windowResize(){$("body").css("height",window.InnerHeight+"px");$(".dashboard-box, .dashboard-box-1").each(function(){$(this).css("height",window.innerHeight-$(this).offset().top-_panelMarginBottom+"px")});jQuery("#my_c_task_list").find(".dashboard-box").each(function(){$(this).css("height",window.innerHeight-$(this).offset().top+"px")});$(".dashboard-box-2").each(function(){$(this).css("height",window.innerHeight-$(this).offset().top+"px")});$("#notifications-btn .slimScrollDiv, #notifications-btn .scrollable-content").height($(window).height()-200);$("#dashboard-page #message-detail").length&&($("#dashboard-page #message-detail").outerHeight($("#contentPart").outerHeight()-22),$("#dashboard-page #messages-list > div").outerHeight($("#contentPart").outerHeight()-24));rowWidth();inputStringFieldsWidth()}window.onresize=function(){windowResize()};window.onload=function(){$("body").css("height",window.InnerHeight+"px");$(".dashboard-box, .dashboard-box-1").each(function(){$(this).css("overflow-x","hidden");$(this).css("overflow-y","auto");$(this).css("height",window.innerHeight-$(this).offset().top-_panelMarginBottom+"px")});$(".dashboard-box-2").each(function(){$(this).css("overflow-x","hidden");$(this).css("overflow-y","auto");$(this).css("height",window.innerHeight-$(this).offset().top+"px")});windowResize()};</script>
</head>
<body class='closed-sidebar'>
<div id="page-wrapper">
<div id="page-content-wrapper">
<div id="page-content">
<div class="row">
<?php echo $contents_for_layout; ?>
</div>
</div>
</div>
</div>
</body>
</html>