<?php /* Smarty version 2.6.22, created on 2015-08-16 11:30:00
         compiled from menu.html */ ?>
<div id="header">
    <div id="logo"><a href="<?php echo @WEBSITE_URL; ?>
"><img src="<?php echo @WEBSITE_URL; ?>
images/logo.png" title="Iron Ridge" border="0" /></a></div>
	   <div id="search">
		   <?php if ($this->_tpl_vars['staffLogged']): ?>
		   <a href="<?php echo $this->_tpl_vars['modulePath']; ?>
staff/">Welcome <?php echo $this->_tpl_vars['staffInfo']['firstname']; ?>
</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
logout/">Logout</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <?php endif; ?>
		   <input name="searchTextTop" type="text" class="searchbox" id="searchTextTop" value="<?php echo $this->_tpl_vars['translate']->_('SEARCH_BOX'); ?>
" />
	   </div>
       <div id="navigation">
           <div class="chromestyle" id="chromemenu">
			<ul>
				<!-- we use hard code ,since css use id, bad -->
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
"><div id="home" title="Home"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/about-us/"  rel="dropmenu1"><div id="about" title="About Us"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/project-areas/" rel="dropmenu2"><div id="project" title="Project Areas"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/aim-rule-26-info/" rel="dropmenu3"><div id="aim" title="AIM Rule 26 Info"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/media-centre/" rel="dropmenu4"><div id="csr" title="CSR"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/investor-centre/" rel="dropmenu5"><div id="investor" title="Investor Centre"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/contact-us/" rel="dropmenu6"><div id="contact" title="Contact Us"></div></a></li>
			<li><a href="<?php echo $this->_tpl_vars['modulePath']; ?>
gallery/"><div id="gallery" title="Gallery"></div></a></li>

			</ul>
           </div>
           <div class="clr"></div>
   	   </div>
   		<div class="clr"></div>


		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfAboutUs'] )): ?>
        <div class="dropmenudiv" id="dropmenu1">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfAboutUs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/about-us/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfProjectAreas'] )): ?>
        <div class="dropmenudiv" id="dropmenu2">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfProjectAreas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/project-areas/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfAIMRule26Info'] )): ?>
        <div class="dropmenudiv" id="dropmenu3">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfAIMRule26Info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/aim-rule-26-info/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfMediaCentre'] )): ?>
        <div class="dropmenudiv" id="dropmenu4">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfMediaCentre']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/media-centre/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfInvestorCentre'] )): ?>
        <div class="dropmenudiv" id="dropmenu5">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfInvestorCentre']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/investor-centre/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

		<?php if (count ( $this->_tpl_vars['uiVar']['subMenuOfContactUs'] )): ?>
        <div class="dropmenudiv" id="dropmenu6">
			<?php $_from = $this->_tpl_vars['uiVar']['subMenuOfContactUs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subMenu']):
?>
			<a href="<?php echo $this->_tpl_vars['modulePath']; ?>
page/contact-us/<?php echo $this->_tpl_vars['subMenu']['page_unique_title']; ?>
/"><?php echo $this->_tpl_vars['subMenu']['title']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php endif; ?>

        <script type="text/javascript">

            cssdropdown.startchrome("chromemenu")

        </script>

    <div class="clr"></div>
  </div>