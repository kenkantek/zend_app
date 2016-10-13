<?php /* Smarty version 2.6.22, created on 2015-08-16 11:28:20
         compiled from news_list.html */ ?>
<?php if (count ( $this->_tpl_vars['list'] ) > 0): ?>
<ul>
<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
<li class="newsItem" style="width: 100%">&raquo; <a href="<?php echo $this->_tpl_vars['modulePath']; ?>
news-detail/<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['date']; ?>
 - <?php echo $this->_tpl_vars['row']['title']; ?>
</a></li>
<br />
<?php endforeach; endif; unset($_from); ?>
</ul>
<br/>
<p align="center"><?php echo $this->_tpl_vars['paging']; ?>
</p>
<?php else: ?>
<p>No news</p>
<?php endif; ?>