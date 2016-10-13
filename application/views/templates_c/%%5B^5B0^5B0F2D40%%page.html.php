<?php /* Smarty version 2.6.22, created on 2015-08-16 11:18:31
         compiled from page.html */ ?>
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
        <div class="clr"></div>
        <div id="home-content">
			<?php if (count ( $this->_tpl_vars['uiVar']['initPage']['content'] )): ?>
				<?php echo $this->_tpl_vars['uiVar']['initPage']['content']; ?>

			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			<?php if ($this->_tpl_vars['uiVar']['sharePriceChart'] && 1 == 0): ?>
				<table border="0" width="100%">

					<tbody>

					  <tr>

						<td colspan="8"><div align="center">Solomon Gold Current Share Price - 20 Minute Delay</div></td>

					  </tr>

					  <tr>

						<td><div align="center"><strong>Last Price </strong></div></td>

						<td><div align="center"><strong>Change</strong></div></td>

						<td><div align="center"><strong>Open</strong></div></td>

						<td><div align="center"><strong>High</strong></div></td>

						<td><div align="center"><strong>Low</strong></div></td>

						<td><div align="center"><strong>Volume</strong></div></td>

					  </tr>

					  <tr>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['lastPrice']; ?>
</div></td>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['change']; ?>
</div></td>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['open']; ?>
</div></td>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['high']; ?>
</div></td>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['low']; ?>
</div></td>

						<td><div align="center"><?php echo $this->_tpl_vars['uiVar']['sharePriceChart']['volume']; ?>
</div></td>

					  </tr>

					</tbody>

				  </table>

				  <p align="center">
					<!-- <img src="http://chart.finance.yahoo.com/c/6m/d/solg.l" width="512" height="288" border="1" /> -->
					</p>
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
</body>
</html>