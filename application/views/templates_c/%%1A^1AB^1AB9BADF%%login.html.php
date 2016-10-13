<?php /* Smarty version 2.6.22, created on 2015-08-16 10:20:23
         compiled from login.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ironridge | Admin</title>
<script language="javascript">
	var websiteUrl			= '<?php echo @WEBSITE_URL; ?>
';
	var ajax_loader_image	= '<?php echo @WEBSITE_URL; ?>
images/ajax-loader.gif';
	var ajaxTimeOut = <?php echo @AJAX_TIMEOUT; ?>
 ;
	var message_icon = '<?php echo $this->_tpl_vars['resourceDir']; ?>
message.png';
</script>
<link href="<?php echo $this->_tpl_vars['resourceDir']; ?>
admin.css?v=<?php echo @APPLICATION_VERSION; ?>
" rel="stylesheet" type="text/css" media="screen, print" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['resourceDir']; ?>
admin.js?v=<?php echo @APPLICATION_VERSION; ?>
" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['resourceDir']; ?>
admin_common.js?v=<?php echo @APPLICATION_VERSION; ?>
" type="text/javascript"></script>
</head>

<body>
<div id="boxlinks">

<div id="admin-banner">
  <table width="940" border="0" cellpadding="0" cellspacing="0">
    <tr>

      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td>
		  <a href="<?php echo @WEBSITE_URL; ?>
" target="_blank">
		  <h1>Ironridge Admin</h1>
		  </a>
	  </td>
      <td width="750" align="right">&nbsp;</td>
    </tr>
  </table>

</div>


</div>

<div class="clr"></div>

<div id="navmenu"></div>

<div class="wrap">

	<div id="homebox1">

		<div class="homeboxcon">

			<h3>&nbsp;</h3>

		  <form id="frmLogin" name="frmLogin" method="post" action="<?php echo @ADMIN_MODULE_PATH; ?>
checklogin">
		    <table border="0" cellspacing="2" cellpadding="2">
				<tr>
				  <td colspan="2">
					  <div id="message">
							<div id="messageDetail">
							  <p><strong>Log In:</strong></p>
							  <p>&nbsp;</p>
							</div>
					</div>
				  </td>
				</tr>
				<tr>
                  <td width="100">Username</td>
                  <td><input name="username" type="text" id="username" size="30" /></td>
                </tr>
                <tr>

                  <td width="100">Password</td>
                  <td><input name="password" type="password" id="password" size="30" /></td>
                </tr>
                <tr>
                  <td width="100">&nbsp;</td>
                  <td align="right"><input type="submit" name="button" id="button" value="Log In" /></td>
                </tr>
            </table>

          </form>
  </div></div>

	<div class="clr"></div>

	<div style="height:15px"></div>
<div class="doubledot"></div>

	<div class="clr"></div>

</div>

<div style="height:28px"></div>

<div id="footer"></div>

</body>
</html>
<script language="javascript">
	var errorMessage = '<?php echo $this->_tpl_vars['errorMessage']; ?>
';
	$(document).ready(function(){
		showMess();

		$('#frmLogin').submit(function(){
			if($('#username').val() == '')
			{
				displayMessageBox('Please enter username!');
				return false;
			}

			if($('#password').val() == '')
			{
				displayMessageBox('Please enter your password!');
				return false;
			}

			return true;
		});
	});
	function showMess()
	{
		if(errorMessage != '')
		{
			displayMessageBox(errorMessage);
		};
	}
</script>