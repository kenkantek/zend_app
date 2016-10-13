<?php /* Smarty version 2.6.22, created on 2015-03-05 22:01:53
         compiled from form_edit_banner.html */ ?>
<form id="frmAdd">
<table width="660" cellspacing="0" cellpadding="2" border="0">
		<tbody>
		<tr>
			<td><strong>URL</strong></td>
			<td>
				<input type="text" name="url" id="url" maxlength="100" size="50" value="<?php echo $this->_tpl_vars['row']['url']; ?>
" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td width="70"><strong>Current Image</strong></td>
			<td>
				<a href="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" class="lightbox">
						<img width="500" src="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" alt="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" />
				</a>
				<br/>
				<input type="checkbox" onclick="changeStatus(this,'selectImage')" id="check_image" name="check_image"> Replace image ?
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="selectImage" style="display:none;">
				<table>
					<tr>
						<td width="70">Image</td>
						<td>
								<div class="wrapper">
									<div id="photo" class="button">select file</div>
								</div>
								<div style="float:left; padding-top:15px;padding-left:10px;">
									File selected : <span id="fileSelected"></span>
								</div>
								<input type="hidden" name="file_name" id="file_name" value="" />
						</td>
					</tr>
					<tr>
						<td>Note</td>
						<td align="left" valign="middle">
						Supported Format : JPEG,JPG,PNG<br/>
						Max Size		 : <?php echo @SLIDE_IMAGE_MAX_SIZE; ?>
Mb<br/>
						Recommend width x height : <?php echo @SLIDE_IMAGE_WIDTH; ?>
 x <?php echo @SLIDE_IMAGE_HEIGHT; ?>

						</td>
					</tr>
				</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				<input type="button" value="Update" id="btnSubmit" name="btnSubmit">
				<input type="button" value="Reset" id="resetFormEdit" name="resetFormEdit">
			</td>
		</tr>
       </tbody>
</table>
	<input type="hidden" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" name="current_page_id" id="current_page_id" />
</form>
<div id="savingContent"></div>