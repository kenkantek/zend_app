<?php /* Smarty version 2.6.22, created on 2013-09-04 05:08:35
         compiled from form_add_banner.html */ ?>
<form id="frmAdd">
<table width="660" cellspacing="0" cellpadding="2" border="0">
		<tbody>
		<tr>
			<td><strong>URL</strong></td>
			<td>
			<input type="text" name="url" id="url" maxlength="100" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr size="1" color="#c5c6c8"></td>
		</tr>
		<tr>
			<td><strong>Image</strong></td>
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
				Max Size		 : <?php echo @SLIDE_IMAGE_MAX_SIZE; ?>
Mb<br/>
				Recommend width x height : <?php echo @SLIDE_IMAGE_WIDTH; ?>
 x <?php echo @SLIDE_IMAGE_HEIGHT; ?>

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