<?php /* Smarty version 2.6.22, created on 2015-08-16 11:30:00
         compiled from footer.html */ ?>
<div id="footer">
   <div id="footerleft">
   <div id="stafflogin">
	   <?php if ($this->_tpl_vars['staffLogged']): ?>
	   <a href="<?php echo $this->_tpl_vars['modulePath']; ?>
staff"><img src="<?php echo @WEBSITE_URL; ?>
images/stafflogin.jpg" title="Staff Login" width="97" height="28" border="0" /></a>
	   <?php else: ?>
	   <a href="#" class="staffLogin"><img src="<?php echo @WEBSITE_URL; ?>
images/stafflogin.jpg" title="Staff Login" width="97" height="28" border="0" /></a>
	   <?php endif; ?>
   </div>
<!--   <div id="disclaimer">The information contained in this website (http://www.ironridgeresources.com.au/) has<br />been disclosed pursuant to Rule 26 of the AIM Rules for Companies.</div>-->
   </div>
   <div id="footerright">
   <div id="cancer" title="">
	   <a href="#" target="_blank"><img src="<?php echo @WEBSITE_URL; ?>
images/twitter.png" border="0" /></a>
	   <a href="#" target="_blank"><img src="<?php echo @WEBSITE_URL; ?>
images/youtube.png" border="0" /></a>
   </div>
   <div id="copyright"><em>Copyright 2013</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
faq/">FAQ</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
privacy/">Privacy</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/contact-us/">Contact Us</a></div>
   </div>
</div>
<?php if (! $this->_tpl_vars['staffLogged']): ?>
<div style="display: none">
	<div id="staffLoginBox" style="width: 400px;height: 200px;">
		<h2>Staff Login</h2>
		<table>
			<tr>
				<td>Username</td>
				<td><input name="staffUsername" id="staffUsername" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input name="staffPassword" id="staffPassword" type="password" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="button" value="Login" id="doLoginStaff" />
					<input type="button" value="Close" id="doCloseBox" />
				</td>
			</tr>
		</table>
	</div>
</div>
<?php endif; ?>