<?php /* Smarty version 2.6.22, created on 2015-08-16 11:28:15
         compiled from news_detail.html */ ?>
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
images/<?php echo $this->_tpl_vars['uiVar']['headerImage']; ?>
" />
		</div>
        <div class="clr"></div>
        <div id="home-content">
			<h2><?php echo $this->_tpl_vars['uiVar']['row']['title']; ?>
</h2>
            <h4><?php echo $this->_tpl_vars['uiVar']['row']['date']; ?>
</h4>
			<?php echo $this->_tpl_vars['uiVar']['row']['content']; ?>

			<br/>
			<hr color="#CCCCCC" size="1" />
			<br/>
			&raquo; <a href="<?php echo $this->_tpl_vars['modulePath']; ?>
news/">View All News</a>
        </div>
        <div id="content-right">

			<div id="home-right-col">
				<div id="btn-project-areas"><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/project-areas/"><img src="<?php echo @WEBSITE_URL; ?>
images/btn-project-areas.jpg" title="Discover our Project Areas" border="0" /></a></div>
			</div>

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
</body>
</html>