<?php /* Smarty version 2.6.22, created on 2015-08-16 05:37:09
         compiled from form_edit_other_page.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'form_edit_other_page.html', 12, false),)), $this); ?>
<form id="frmAdd">
<table width="100%" cellspacing="2" cellpadding="0" border="0">
          <tbody><tr>
            <td><strong>Type</strong></td>
            <td><?php echo $this->_tpl_vars['row']['type']; ?>
</td>
          </tr>
           <tr>
            <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
          <tr>
            <td><strong>Title</strong></td>
            <td><input type="text" size="50" id="title" name="title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></td>
          </tr>
		  <tr>
            <td><strong>Title French</strong></td>
            <td><input type="text" size="50" id="title" name="title_fr" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['title_fr'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></td>
          </tr>
		  <tr>
			  <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
          <tr>
            <td><strong>Content</strong></td>
            <td>
                <textarea id="content" name="content"><?php echo $this->_tpl_vars['row']['content']; ?>
</textarea>
            </td>
          </tr>
		   <tr>
            <td><strong>Content French</strong></td>
            <td>
                <textarea id="content_fr" name="content_fr"><?php echo $this->_tpl_vars['row']['content_fr']; ?>
</textarea>
            </td>
          </tr>
          <tr>
			  <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
		  <?php if ($this->_tpl_vars['row']['type'] == 'home'): ?>
		  <tr>
            <td><strong>Link</strong></td>
            <td><input type="text" size="40" id="link" name="link" value="<?php echo $this->_tpl_vars['row']['link']; ?>
"> (leaving this field empty will automatically this page link internally)</td>
          </tr>
		  <?php endif; ?>
		  <tr>
			  <td colspan="2" height="20"><hr size="1" color="#ccccc"></td>
          </tr>
        <tr>
            <td align="right" colspan="2">
                <input type="submit" value="Update" id="submit" name="submit">
                <input type="button" value="Reset" id="resetFormEdit" name="resetFormEdit">
            </td>
        </tr>
       </tbody>
</table>
    <input type="hidden" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" name="current_page_id" id="current_page_id" />
	<input type="hidden" value="<?php echo $this->_tpl_vars['row']['page_unique_title']; ?>
" name="current_unique_title" id="current_unique_title" />
</form>
<div id="savingContent"></div>