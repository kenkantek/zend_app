<?php /* Smarty version 2.6.22, created on 2015-08-16 10:20:06
         compiled from list_image.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No record(s) found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
	<td><strong>Title</strong></td>
    <td><strong>Image</strong></td>
	<td><strong>Description</strong></td>
	<td><strong>Album</strong></td>
	<td><strong>Date Created</strong></td>
	<?php if ($this->_tpl_vars['albumSelected']): ?>
	<td><strong>Order num</strong></td>
	<td><strong>Image Order</strong></td>
	<?php endif; ?>
    <td width=""><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
    <td colspan="8"><hr size="1" color="#c5c6c8" /></td>
  </tr>
  <tr>
	<td><?php echo $this->_tpl_vars['row']['title']; ?>
<br/><?php echo $this->_tpl_vars['row']['title_fr']; ?>
</td>
	<td>
		<a href="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" class="lightbox">
			<img class="lightbox" src="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" width="100" />
		</a>
	</td>
	<td><?php echo $this->_tpl_vars['row']['description']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['albumName']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
	<?php if ($this->_tpl_vars['albumSelected']): ?>
	<td><?php echo $this->_tpl_vars['row']['order_num']; ?>
</td>
	<td>
		<a href="#" class="upImage" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
up.png" title="Move Up" width="30" height="30" /></a>
        <a href="#" class="downImage" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
down.png" title="Move Down" width="30" height="30" /></a>
    </td>
	<?php endif; ?>
    <td>
        <a href="#" class="editImage" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="30" title="Edit" /></a>
        <a href="#" class="deleteImage" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" title="Delete"  /></a>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<p align="center"><?php echo $this->_tpl_vars['paging']; ?>
</p>
<?php endif; ?>