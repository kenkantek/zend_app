<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:31
         compiled from list_content_page.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No child page found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><strong>Title</strong></td>
	<td><strong>Title French</strong></td>
    <td><strong>Date Created</strong></td>
    <td><strong>Date Modified</strong></td>
    <td><strong>Child(s)</strong></td>
    <td width="80"><strong>Page Order</strong></td>
    <td width="70"><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
	  <td colspan="7" height="20"><hr size="1" color="#cccccc" width="100%" /></td>
  </tr>
  <tr>
    <td><?php echo $this->_tpl_vars['row']['title']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['title_fr']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_modified']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['childs']; ?>
</td>
    <td>
		<?php if ($this->_tpl_vars['row']['parent_id'] != 0): ?>
		<a href="#" class="up" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
up.png" width="30" height="30" /></a>
        <a href="#" class="down" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
down.png" width="30" height="30" /></a>
		<?php endif; ?>
    </td>
    <td>
        <a href="#" class="edit" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="29" /></a>

        <?php if ($this->_tpl_vars['row']['parent_id'] != 0): ?>
            <a href="#" class="delete" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" /></a>
        <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>