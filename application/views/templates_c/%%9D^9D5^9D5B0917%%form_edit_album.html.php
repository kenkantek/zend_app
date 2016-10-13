<?php /* Smarty version 2.6.22, created on 2015-08-16 10:17:53
         compiled from form_edit_album.html */ ?>
<form id="frmAdd">
<table width="660" cellspacing="0" cellpadding="2" border="0">
		<tbody>
		<tr>
			<td><strong>Name *</strong></td>
			<td>
				<input type="text" name="name" id="name" maxlength="100" size="50" value="<?php echo $this->_tpl_vars['row']['name']; ?>
" />
			</td>
		</tr>
		<tr>
			<td><strong>Name French</strong></td>
			<td>
				<input type="text" name="name_fr" id="name_fr" maxlength="100" size="50" value="<?php echo $this->_tpl_vars['row']['name_fr']; ?>
" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td width="70"><strong>Current Cover Image</strong></td>
			<td>
				<a href="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
" class="lightbox">
					<img width="<?php echo @ALBUM_IMAGE_WIDTH; ?>
" height="<?php echo @ALBUM_IMAGE_HEIGHT; ?>
" src="<?php echo $this->_tpl_vars['helper']->getImageUrl($this->_tpl_vars['row']['image']); ?>
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
						Max Size		 : <?php echo @ALBUM_IMAGE_MAX_SIZE; ?>
Mb<br/>
						Recommended width x height : <?php echo @ALBUM_IMAGE_WIDTH; ?>
 x <?php echo @ALBUM_IMAGE_HEIGHT; ?>

						</td>
					</tr>
				</table>
				</div>
			</td>
		</tr>
		<tr>
		   <td><strong>Description</strong></td>
		   <td align="left">
			   <textarea name="description" id="description" style="width: 100%; height: 150px;"><?php echo $this->_tpl_vars['row']['description']; ?>
</textarea>
		   </td>
		</tr>
		<tr>
		   <td><strong>Description French</strong></td>
		   <td align="left">
			   <textarea name="description_fr" id="description_fr" style="width: 100%; height: 150px;"><?php echo $this->_tpl_vars['row']['description_fr']; ?>
</textarea>
		   </td>
		</tr>
		<tr>
		   <td><strong>Status</strong></td>
		   <td align="left">
			   <?php echo $this->_tpl_vars['helper']->getComboStatus($this->_tpl_vars['row']['status']); ?>

		   </td>
		</tr>
		<tr>
		   <td><strong>Order Number</strong></td>
		   <td align="left">
			   <input type="text" value="<?php echo $this->_tpl_vars['row']['order_num']; ?>
" name="order_num" value="order_num" />
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
" name="idRecord" id="idRecord" />
</form>
<div id="savingContent"></div>