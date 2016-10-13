<?php /* Smarty version 2.6.22, created on 2015-08-16 11:30:00
         compiled from index.html */ ?>
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

			</div>
		</div>
		<?php else: ?>
		<div id="home-main-img">
			<img src="<?php echo @WEBSITE_URL; ?>
images/home.jpg" width="780" height="350" />
		</div>
		<?php endif; ?>



        <div id="home-content">
			<?php if (count ( $this->_tpl_vars['uiVar']['homeContent'] )): ?>
				<?php echo $this->_tpl_vars['uiVar']['homeContent']; ?>

			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			
			<?php echo $this->_tpl_vars['uiVar']['rssWidget']; ?>

        </div>
        <div id="content-right">

        	<div id="home-right-col">
        <div id="btn-project-areas"><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/project-areas/"><img src="<?php echo @WEBSITE_URL; ?>
images/btn-project-areas.jpg" title="Discover our Project Areas" border="0" /></a></div>
        <div id="btn-investor-centre"><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
subscription/"><img src="<?php echo @WEBSITE_URL; ?>
images/btn-investor-centre.jpg" title="Visit our Investor Centre" border="0" /></a></div>

			<!--<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/investor-centre/share-price-chart/">
            <div id="share-price">
			   <?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['lastPrice']; ?>
p
               </div>
			</a>-->

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

        <div class="clr"></div>

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