<?php /* Smarty version 2.6.22, created on 2012-06-25 05:14:31
         compiled from list_email.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No record(s) found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><strong>Email</strong></td>
    <td><strong>Name</strong></td>
    <td><strong>Type</strong></td>
    <td width="70"><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
	  <td colspan="4" height="20"><hr size="1" color="#cccccc" width="100%" /></td>
  </tr>
  <tr>
    <td><?php echo $this->_tpl_vars['row']['email']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['helper']->getType($this->_tpl_vars['row']['type']); ?>
</td>
    <td>
        <a href="#" class="edit" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="29" /></a>
        <a href="#" class="delete" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" /></a>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>