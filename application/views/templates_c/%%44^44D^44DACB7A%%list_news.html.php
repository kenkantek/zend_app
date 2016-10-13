<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:33
         compiled from list_news.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) == 0): ?>
    No record(s) found
<?php else: ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><strong>Title</strong></td>
	<td><strong>Title French</strong></td>
    <td><strong>Date</strong></td>
    <td><strong>Date Created</strong></td>
    <td><strong>Date Modified</strong></td>
    <td width="70"><strong>Action</strong></td>
  </tr>
  <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
  <tr>
	  <td colspan="6" height="20"><hr size="1" color="#cccccc" width="100%" /></td>
  </tr>
  <tr>
    <td>
		<a href="<?php echo @WEBSITE_URL; ?>
news-detail/<?php echo $this->_tpl_vars['row']['id']; ?>
" target="_blank">
		<?php echo $this->_tpl_vars['row']['title']; ?>

		</a>
	</td>
	<td>
		<a href="<?php echo @WEBSITE_URL; ?>
news-detail/<?php echo $this->_tpl_vars['row']['id']; ?>
" target="_blank">
		<?php echo $this->_tpl_vars['row']['title_fr']; ?>

		</a>
	</td>
    <td><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_created']; ?>
</td>
    <td><?php echo $this->_tpl_vars['row']['date_modified']; ?>
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