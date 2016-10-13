<?php /* Smarty version 2.6.22, created on 2015-08-16 10:20:16
         compiled from list_album.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No record(s) found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
	<td><strong>Name</strong></td>
	<td><strong>Name French</strong></td>
    <td><strong>Album Cover</strong></td>
	<td><strong>Status</strong></td>
	<td><strong>Date Created</strong></td>
	<td><strong>Order Num</strong></td>
	<td><strong>Album Order</strong></td>
    <td width=""><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
    <td colspan="9"><hr size="1" color="#c5c6c8" /></td>
  </tr>
  <tr>
	<td><?php echo $this->_tpl_vars['row']['name']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['name_fr']; ?>
</td>
	<td>
		<a href="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" class="lightbox">
			<img class="lightbox" src="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" width="<?php echo @ALBUM_IMAGE_WIDTH; ?>
" height="<?php echo @ALBUM_IMAGE_HEIGHT; ?>
"/>
		</a>
	</td>
	<td><?php echo $this->_tpl_vars['helper']->getStatusText($this->_tpl_vars['row']['status']); ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
	<td><?php echo $this->_tpl_vars['row']['order_num']; ?>
</td>
	<td>
		<a href="#" class="upAlbum" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
up.png" title="Move Up" width="30" height="30" /></a>
        <a href="#" class="downAlbum" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
down.png" title="Move Down" width="30" height="30" /></a>
    </td>
    <td>
        <a href="#" class="editAlbum" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
edit.png" width="30" height="30" title="Edit" /></a>
        <a href="#" class="deleteAlbum" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
delete.png" width="30" height="29" title="Delete"  /></a>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<p align="center"><?php echo $this->_tpl_vars['paging']; ?>
</p>
<?php endif; ?>