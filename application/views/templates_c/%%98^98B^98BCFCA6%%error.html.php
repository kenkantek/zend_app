<?php /* Smarty version 2.6.22, created on 2013-11-22 23:33:28
         compiled from error.html */ ?>
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

	    <?php if (count ( $this->_tpl_vars['slideBanner'] ) > 0): ?>
		<div id="home-main-img">
			<div id="slide_home">
				<div class="container">
					<div class="slides">
						<?php echo $this->_tpl_vars['slideBanner']['slides']; ?>

					</div>
				</div>
				 <div class="controls">
					<a class="previous" href="#">Previous</a>
					<ul class="pagination">
						<?php echo $this->_tpl_vars['slideBanner']['pages']; ?>

					</ul>
					<a class="next" href="#">Next</a>
				</div>
			</div>
		</div>
		<?php else: ?>
		<div id="home-main-img">
			<img src="<?php echo @WEBSITE_URL; ?>
images/home.jpg" width="510" height="344" />
		</div>
		<?php endif; ?>

        <div id="home-right-col">
        <div id="btn-project-areas"><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/project-areas/"><img src="<?php echo @WEBSITE_URL; ?>
images/btn-project-areas.jpg" title="Discover our Project Areas" width="245" height="110" border="0" /></a></div>
        <div id="btn-investor-centre"><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/investor-centre/"><img src="<?php echo @WEBSITE_URL; ?>
images/btn-investor-centre.jpg" title="Visit our Investor Centre" width="245" height="110" border="0" /></a></div>
        <div id="share-price"></div>
        </div>
        <div class="clr"></div>
        <div id="home-content">
			<h1>Page not found</h1>
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
</body>
</html>