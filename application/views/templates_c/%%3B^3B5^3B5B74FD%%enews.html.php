<?php /* Smarty version 2.6.22, created on 2015-08-15 11:33:33
         compiled from enews.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<body>
<div class="site">
<div id="wrap">

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <div id="content-top-strip"></div>
  <div id="content-wrapper">
    <div id="content">
	  <div id="content-inner">
      <div class="clr"></div>
	    <div style="float: left; height: 186px; margin-bottom: 20px;width: 778px;">
			<img src="<?php echo @WEBSITE_URL; ?>
images/enews_banner.png" />
		</div>
        <div class="clr"></div>
        <div class="clr"></div>
        <div id="home-content">

			<script language="JavaScript" type="text/javascript" charset="utf-8" src="http://broadkast.ballyhoo.com.au/em/forms/subscribe.php?db=369700&amp;s=87503&amp;a=41414&amp;k=44889ae&amp;emb=1"></script>

        </div>
        <div id="content-right">

			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "latest_news.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscription_box.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
        </div>
	  </div>
     </div>
   </div>
   <div class="clr"></div>
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
	<div id="galleryContainerTemp" style="display: none">

	</div>
</body>
<script src="<?php echo @WEBSITE_URL; ?>
js/gallery.js?v=<?php echo @APPLICATION_VERSION; ?>
" type="text/javascript"></script>
</html>