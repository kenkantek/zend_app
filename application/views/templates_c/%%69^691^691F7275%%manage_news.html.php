<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:33
         compiled from manage_news.html */ ?>
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
          <td><a href="#" class="list">News List</a></td>
        </tr>
        <tr>
          <td><a href="#" class="add">Add News</a></td>
        </tr>
        <tr>
          <td height="30"><hr width="100%" size="1" color="#CCCCCC" /></td>
        </tr>
        <tr>
			<td>
				<div id="divSearch">
                <form id="frmSearch">
                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                 <tr>
					 <td align="center"><strong>Search</strong></td>
                 </tr>
                 <tr>
                     <td><span class="textField">Date format : dd-mm-yyyy</span></td>
                 </tr>
                 <tr>
                   <td><span class="textField">From Date</span></td>
                 </tr>
                 <tr>
                   <td>
                       <input name="from_date" type="text" id="from_date" class="field" maxlength="10" />
                   </td>
                 </tr>
				 <tr>
                   <td><span class="textField">To Date</span></td>
                 </tr>
                 <tr>
                   <td>
                       <input name="to_date" type="text" id="to_date"  class="field" maxlength="10" />
                   </td>
                 </tr>
                 <tr>
                   <td><span class="textField">Title</span></td>
                 </tr>
                 <tr>
                   <td><input name="textSearch" type="text" id="textSearch" class="field" maxlength="30" /></td>
                 </tr>
                 <tr>
                   <td align="center">
					   <input type="submit" name="button" id="button" value="Search" class="textField"/>
                       <input type="reset" name="button" id="button" value="Clear" class="textField"/>
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
 -> <span id="navTitle">List</span></h2>

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
_manage_news.js?ver=<?php echo @APPLICATION_VERSION; ?>
"></script>