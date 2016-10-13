<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:32
         compiled from list_other_page.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No page found
<?php else: ?>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td><strong>Title</strong></td>
	<td><strong>Title French</strong></td>
	<td><strong>Link to page</strong></td>
	<td><strong>Type</strong></td>
    <td><strong>Date Created</strong></td>
    <td><strong>Date Modified</strong></td>
    <td width="20"><strong>Action</strong></td>
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
	<td>
		<?php if ($this->_tpl_vars['row']['id'] > 6): ?>
		<a href="<?php echo @WEBSITE_URL; ?>
show-page/<?php echo $this->_tpl_vars['row']['page_unique_title']; ?>
/" target="_blank">
			View
		</a>
		<?php endif; ?>
	</td>
	<td><?php echo $this->_tpl_vars['row']['type']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_modified']; ?>
</td>
    <td>
        <a href="#" class="edit" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="29" /></a>

		<?php if ($this->_tpl_vars['row']['id'] > 6): ?>
            <a href="#" class="delete" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" /></a>
        <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>