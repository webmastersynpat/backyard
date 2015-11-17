<?php echo doctype('html5'); ?>
<html  lang="en">
<head>
<?php echo meta($meta); ?>
    


   
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php echo $title_for_layout; ?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
        /* Loading Spinner */
        .spinner{margin:0;width:70px;height:18px;margin:-35px 0 0 -9px;position:absolute;top:50%;left:50%;text-align:center}.spinner > div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}
    </style>
<!-- Favicons -->

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $Layout->baseUrl; ?>public/images/icons/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $Layout->baseUrl; ?>public/images/icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo $Layout->baseUrl; ?>public/style.css">
<script>
	var __baseUrl = '<?php echo $Layout->baseUrl; ?>',
	snapGlobal="",
    leadGlobal =  0, 
	snp = 0,
    leadNameGlobal =  "",
	mainIndex = -1,
	totalCC = 0,
	systemLoginSession = 0,
	chDa = 1;
</script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/javascript_lib_level1.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/script.js"></script>
<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/views/layout/opportunity.js"></script>

</head>

<body class='closed-sidebar'>
	<div id="sb-site">
	</div>
	<div id="loading">
        <div class="loading-spinner is-window">
            <img src="<?php echo $Layout->baseUrl?>public/images/ajax-loader.gif" alt="">
        </div>
    </div>
	<div id="page-wrapper">
		<div id="page-content-wrapper">
            <div id="page-content">
				<div class="row">
					<?php echo $contents_for_layout; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="createTaskModal1" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
                    
					<?php echo form_open("opportunity/task",array("class"=>"form-horizontal form-flat","id"=>"formTask"));?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="createTaskModalLabel"></h4>
					</div>
					<div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">For:</label>
                                    <select name="task[user_id]" id="taskUserId" required="required" class="form-control" style="float: left; width: 225px; margin-top: 2px; margin-left: 3px;">
                                        <option value="">-- Select User --</option>
                                        <?php 
                                            $getUsers = getAllUsersIncAdmin();
                                            foreach($getUsers as $user){
                                                if($user->id!=$this->session->userdata['id']):
                                        ?>
                                            <option value="<?php echo $user->id?>"><?php echo $user->name;?></option>
                                        <?php
                                                endif;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 mrg5T">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">Subject:</label>
                                    <input type="text" maxlength="40" class="form-control input-string" name="task[subject]" id="taskSubject" style="float: left; width: 225px; margin-top: 4px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix mrg10T">
                            <div class="form-group">
                                <label>Message:</label>
                                <textarea  name="task[message]" class=" form-control input-string" id="taskMessage" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="clearfix">
                                    <label class="control-label" style="float:left;">Link Url:</label>
                                    <input input name="task[doc_url]" class="form-control input-string" id="taskDocUrl" style="float: left; width: 518px; margin-top: 4px;" />
                                </div>
                            </div>
                            <div class="col-xs-12 mrg5T">
                                <div class="clearfix">
                            <label class="control-label" style="float:left;">Execution Date:</label>
                            <input input name="task[execution_date]" class="bootstrap-datepicker form-control input-string" id="taskExecutionDate" placeholder="yyyy-mm-dd" style="float: left; width: 82px; margin-top: 4px;" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix">
                        </div>
					</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-mwidth" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-mwidth" onclick="submitTask();">Create</button>
                        <input type="hidden" name="task[parent_id]" value="0" id="taskParentId" />
                        <input type="hidden" name="task[from_user_id]" value="<?php echo $this->session->userdata['id']?>" id="taskFromUserId" />
                        <input type="hidden" name="task[lead_id]" value="0" id="taskLeadId" />
                        <input type="hidden" name="task[type]" id="taskType" />
                        <input type="hidden" name="other[return]" id="opportunity" />
                        <input type="hidden" name="task[id]" id="taskId" value="0" />
                    </div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
</body>
</html>