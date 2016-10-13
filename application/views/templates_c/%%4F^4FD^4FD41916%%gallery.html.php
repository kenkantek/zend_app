<?php /* Smarty version 2.6.22, created on 2015-08-16 11:13:05
         compiled from gallery.html */ ?>
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
images/gallery_banner.png" />
		</div>
        <div class="clr"></div>
        <div class="clr"></div>
        <div id="home-content">

			<?php if (count ( $this->_tpl_vars['listAlbum'] ) > 0): ?>
			<div class="boxGallery">

				<ul>
				<?php $_from = $this->_tpl_vars['listAlbum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>

				<li>
					<a class="popupImage" rel="<?php echo $this->_tpl_vars['row']['id']; ?>
" href="#">
						<img src="<?php echo $this->_tpl_vars['galleryHelper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" width="<?php echo @ALBUM_IMAGE_WIDTH; ?>
" height="<?php echo @ALBUM_IMAGE_HEIGHT; ?>
"/>
						<br/>
						<h1><?php echo $this->_tpl_vars['row']['name']; ?>
</h1>
					</a>
				</li>

				<?php endforeach; endif; unset($_from); ?>
				</ul>

				<div class="clr"></div>

			</div>
			<?php else: ?>
			No album to show.
			<?php endif; ?>


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
	<div id="galleryContainerTemp" style="display: none">

	</div>
</body>
<script src="<?php echo @WEBSITE_URL; ?>
js/gallery.js?v=<?php echo @APPLICATION_VERSION; ?>
" type="text/javascript"></script>
</html>