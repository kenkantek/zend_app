<?php /* Smarty version 2.6.22, created on 2015-08-16 10:20:16
         compiled from manage_gallery.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<body>
<div id="boxlinks">


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'info.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


</div>

<div class="clr"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'main_menu.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="wrap">
<table border="0" cellpadding="0" cellspacing="0">
  <tr><td valign="top">
<div id="adminsubnav-top"></div>
    <div id="adminsubnav">
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><a href="#" class="listAlbum">List Albums</a></td>
        </tr>
        <tr>
          <td><a href="#" class="addAlbum">Add Album</a></td>
        </tr>
		<tr>
          <td><a href="#" class="listImage">List Images</a></td>
        </tr>
        <tr>
          <td><a href="#" class="addImage">Add Image</a></td>
        </tr>
        <tr>
          <td height="30"><hr width="100%" size="1" color="#CCCCCC" /></td>
        </tr>
        <tr>
			<td>
				<div id="divSearchAlbum">
				<form id="frmSearchAlbum" method="post">
                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                 <tr>
                   <td colspan="2"><strong>Search</strong></td>
                 </tr>
                 <tr>
                   <td>Name</td>
                   <td><input name="albumNameSearch" type="text" id="albumNameSearch" size="10"  /></td>
                 </tr>
				 <tr>
                   <td>Status</td>
                   <td>
					   <select id="status" name="status">
                           <option value="">--</option>
                           <option value="1">Active</option>
                           <option value="0">Disabled</option>
                       </select>
				   </td>
                 </tr>
                 <tr>
                   <td colspan="2" align="right">
					   <input type="reset" name="reset" id="reset" value="Clear" />
                       <input type="submit" name="button" id="button" value="Search" />
                   </td>
                 </tr>
                </table>
                </form>
				</div>

				<div id="divSearchImage" style="display: none">
				<form id="frmSearchImage" method="post">
                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                 <tr>
                   <td colspan="2"><strong>Search</strong></td>
                 </tr>
                 <tr>
                   <td>Title</td>
                   <td><input name="titleSearch" type="text" id="titleSearch" size="10"  /></td>
                 </tr>
				 <tr>
                   <td>Album</td>
                   <td>
					   <div id="divAlbumListSearch"></div>
				   </td>
                 </tr>
                 <tr>
                   <td colspan="2" align="right">
					   <input type="reset" name="reset" id="reset" value="Clear" />
                       <input type="submit" name="button" id="button" value="Search" />
                   </td>
                 </tr>
                </table>
                </form>
				</div>
			</td>
		</tr>
      </table>

    </div>
    <div id="adminsubnav-bottom"></div>
</td><td valign="top">
<div id="pagelist-top"></div>
	<div id="pagelist">

		<div class="homeboxcon">

			<h2><?php echo $this->_tpl_vars['navTitle']; ?>
 -> <span id="navTitle">List Albums</span></h2>

			<div id="message">
				<div id="messageDetail"></div>
			</div>

			<div id="divContent"></div>
	  </div>

	  <div class="pagelistbottom">

		</div>

	</div>

   </td></tr></table>

  <div class="clr"></div>

	<div style="height:15px"></div>

	<div class="doubledot"></div>

	<div class="clr"></div>

</div>

<div style="height:28px"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</body>
</html>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['resourceDir']; ?>
<?php echo $this->_tpl_vars['regionPrefix']; ?>
_manage_gallery.js?ver=<?php echo @APPLICATION_VERSION; ?>
"></script>