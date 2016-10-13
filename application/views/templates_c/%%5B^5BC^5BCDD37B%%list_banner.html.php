<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:34
         compiled from list_banner.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No image found
<?php else: ?>
<table width="660" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><strong>Image</strong></td>
	<td><strong>Url</strong></td>
	<td width="80"><strong>Page Order</strong></td>
    <td width="70"><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
    <td colspan="3"><hr size="1" color="#c5c6c8" /></td>
  </tr>
  <tr>
    <td>
		<a href="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" class="lightbox">
			<img src="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" width="80" /><br/>
			View Large
		</a>
	</td>
	<td>
		<a href="<?php echo $this->_tpl_vars['row']['url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['row']['url']; ?>
</a>
	</td>
	<td>
        <a href="#" class="up" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
up.png" width="30" height="30" /></a>
        <a href="#" class="down" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
"><img src="<?php echo $this->_tpl_vars['resourceDir']; ?>
down.png" width="30" height="30" /></a>
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
<p align="center"><?php echo $this->_tpl_vars['paging']; ?>
</p>