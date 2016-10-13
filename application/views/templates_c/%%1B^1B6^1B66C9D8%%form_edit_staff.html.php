<?php /* Smarty version 2.6.22, created on 2015-03-05 22:02:45
         compiled from form_edit_staff.html */ ?>
<form id="frmAdd" method="post">
<table cellspacing="2" cellpadding="2" border="0" width="100%">
            <tbody>
              <td>First Name</td>
              <td><input id="firstname" name="firstname" value="<?php echo $this->_tpl_vars['row']['firstname']; ?>
" size="50" /></td>
            </tr>
            <tr>
              <td>Last Name</td>
              <td><input id="lastname" name="lastname" value="<?php echo $this->_tpl_vars['row']['lastname']; ?>
" size="50" /></td>
            </tr>
			<tr>
              <td>Phone</td>
              <td><input id="phone" name="phone" value="<?php echo $this->_tpl_vars['row']['phone']; ?>
"  size="50" /></td>
            </tr>
            <tr>
              <td>Email</td>
              <td><input id="email" name="email" value="<?php echo $this->_tpl_vars['row']['email']; ?>
" size="50" /></td>
            </tr>
			<tr>
              <td>Username</td>
              <td><input id="username" name="username" value="<?php echo $this->_tpl_vars['row']['username']; ?>
" size="50" /></td>
            </tr>
			<tr>
              <td>Password</td>
              <td><input id="password" name="password" size="50" /> (leave blank to keep current)</td>
            </tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Update" id="submit" name="submit">
					<input type="button" value="Reset" id="resetFormEdit" name="resetFormEdit">
				</td>
			</tr>
</table>
	<input type="hidden" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" name="current_page_id" id="current_page_id" />
</form>
<div id="savingContent"></div>