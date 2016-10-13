<?php /* Smarty version 2.6.22, created on 2015-03-09 20:02:10
         compiled from form_add_image.html */ ?>
<form id="frmAdd">
<table width="660" cellspacing="0" cellpadding="2" border="0">
		<tbody>
		<tr>
			<td><strong>Title *</strong></td>
			<td>
			<input type="text" name="title" id="title" maxlength="100" size="50" />
			</td>
		</tr>
		<tr>
			<td><strong>Album *</strong></td>
			<td>
			<?php echo $this->_tpl_vars['comboAlbum']; ?>

			</td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td><strong>Image *</strong></td>
			<td align="left">
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
			 </td>
		 </tr>
		<tr>
		   <td><strong>Description</strong></td>
		   <td align="left">
			   <textarea name="description" id="description" style="width: 100%; height: 150px;"></textarea>
		   </td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				<input type="button" value="Save" id="btnSubmit" name="btnSubmit">
			</td>
		</tr>
       </tbody>
</table>
</form>
<div id="savingContent"></div>