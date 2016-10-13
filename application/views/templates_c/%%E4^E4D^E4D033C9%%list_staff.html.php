<?php /* Smarty version 2.6.22, created on 2015-08-16 10:20:17
         compiled from list_staff.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No record(s) found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
	<td><strong>Username</strong></td>
    <td><strong>Email</strong></td>
    <td><strong>Name</strong></td>
	<td><strong>Status</strong></td>
	<td><strong>Date Created</strong></td>
    <td width="140"><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
    <td colspan="8"><hr size="1" color="#c5c6c8" /></td>
  </tr>
  <tr>
	<td><?php echo $this->_tpl_vars['row']['username']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['email']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['firstname']; ?>
 <?php echo $this->_tpl_vars['row']['lastname']; ?>
</td>
	<td><?php echo $this->_tpl_vars['helper']->getStateStatus($this->_tpl_vars['row']['status'],$this->_tpl_vars['row']['id']); ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
    <td>
        <a href="#" class="edit" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="30" title="Edit" /></a>
<!--        <a href="#" class="delete" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" title="Delete"  /></a>-->
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<p align="center"><?php echo $this->_tpl_vars['paging']; ?>
</p>
<?php endif; ?>