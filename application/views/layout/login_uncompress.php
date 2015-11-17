<?php echo doctype('html5'); ?>
<html lang ="en">
<head>

    <?php echo meta($meta); ?>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php echo $title_for_layout; ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<!-- Favicons -->

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $Layout->baseUrl; ?>public/images/icons/favicon.ico">


<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/views/layout/login.css">
</head>
<body>
<!--
<div id="loading">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
-->
<div class="page-wrapper">
    <div id="page-wrapper">

        <div id="page-header" class="bg-gradient-9">
            <div id="mobile-navigation">
                <button id="nav-toggle" class="collapsed" data-toggle="collapse" data-target="#page-sidebar"><span></span></button>
                <a href="#" class="logo-content-small" title="Backyard">SynPat</a>
            </div>
            <div id="header-logo" class="logo-bg">
                <a id="close-sidebar" href="#" title="Close sidebar">
                    <span class="logo-content-big" title="Dashboard">SynPat</span>
                </a>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-lg-12">
                <p class="login-text"></p>
            </div>
        </div> -->

        <div class="center-vertical">
        	<div class="center-content">
        		<?php echo $contents_for_layout; ?>
        	</div>
        </div>

    </div>

    <div class="login-copyright">
        &copy; SynPat <?php echo date('Y')?>
    </div>
</div>

</body>
</html>