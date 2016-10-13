<?php /* Smarty version 2.6.22, created on 2015-08-16 11:30:00
         compiled from latest_news.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'latest_news.html', 6, false),)), $this); ?>
<div id="newsbox"><div id="newstitle"><?php echo $this->_tpl_vars['translate']->_('LATEST_NEWS'); ?>
</div>

	<?php $_from = $this->_tpl_vars['uiVar']['latestNews']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['newsRow']):
?>
		<div id="newsitem"><?php echo $this->_tpl_vars['newsRow']['date']; ?>

			<br />
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
news-detail/<?php echo $this->_tpl_vars['newsRow']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['newsRow']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 35) : smarty_modifier_truncate($_tmp, 35)); ?>
</a>
		</div>
	<?php endforeach; endif; unset($_from); ?>


</div>