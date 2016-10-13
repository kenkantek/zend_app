<?php /* Smarty version 2.6.22, created on 2015-08-16 04:32:54
         compiled from form_add_content_page.html */ ?>
<form id="frmAdd">
<table width="100%" cellspacing="2" cellpadding="0" border="0">
          <tbody><tr>
            <td><strong>Parent Page</strong></td>
            <td><?php echo $this->_tpl_vars['parentCombo']; ?>
</td>
          </tr>
           <tr>
            <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
          <tr>
            <td><strong>Title</strong></td>
            <td><input type="text" size="50" id="title" name="title"></td>
          </tr>
		  <tr>
            <td><strong>Title French </strong></td>
            <td><input type="text" size="50" id="title_fr" name="title_fr"></td>
          </tr>
		  <tr>
			  <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
          <tr>
            <td><strong>Content</strong></td>
            <td>
                <textarea id="content" name="content"></textarea>
            </td>
          </tr>
		  <tr>
            <td><strong>Content French</strong></td>
            <td>
                <textarea id="content_fr" name="content_fr"></textarea>
            </td>
          </tr>
          <tr>
			<td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
        <tr>
            <td align="right" colspan="2">
                <input type="submit" value="Create Page" id="submit" name="submit"></td>
        </tr>
       </tbody>
</table>
</form>
<div id="savingContent"></div>