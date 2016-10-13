var currentPageAlbum = 1;
var currentPageImage = 1;
var interval;
var objectUpload;

$(document).ready(function(){

    //handle form seach
    $('#frmSearchAlbum').submit(function(){
		currentPageAlbum = 1;
        listAlbum();
        return false;
    })

    //List
    $('a.listAlbum').click(function(){
        listAlbum();
        return false;
    });

    //Add new
    $('a.addAlbum').click(function(){
        changeNavTitle('Add Album');
        $('#divSearchAlbum').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-album',
			'',
			'getFormAddAlbumCallBack',
			'listError');

        return false;
    });

	listAlbum();

	//get album list for search image
	getComboAlbum();


	//List
    $('a.listImage').click(function(){
        listImage();
        return false;
    });

	//handle form seach
    $('#frmSearchImage').submit(function(){
		currentPageImage = 1;
        listImage();
        return false;
    })

	//Add new
    $('a.addImage').click(function(){
        changeNavTitle('Add Image for Album');
        $('#divSearchImage').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-image',
			'',
			'getFormAddImageCallBack',
			'listError');

        return false;
    });
})

function getComboAlbum()
{
    callAjax('POST',
			'divAlbumListSearch',
			 controllerPath + 'get-combo-album',
			 null);
}


function listAlbum()
{
	$('#divSearchAlbum').show();
	$('#divSearchImage').hide();

    //change title
    changeNavTitle('List Albums');

    //assign current page form search
    var form = $('#frmSearchAlbum').serializeArray();
    form[form.length] = {name : 'page' , value : currentPageAlbum};

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-album',
			 form,
			'listAlbumOk',
			'listError');
    return true;
}

function listAlbumOk()
{
    $('a.lightbox').lightBox();

	$('a.upAlbum').click(function(){
        changeOrderAlbum($(this).attr('rel'),'up');
        return false;
    })
    $('a.downAlbum').click(function(){
        changeOrderAlbum($(this).attr('rel'),'down');
        return false;
    })

	$('a.deleteAlbum').click(function(){
        if(confirm('All images in this album will be deleted too.  Are you sure?'))
         {
             callAjax('GET',
			'divContent',
			 controllerPath + 'delete-album',
			'id=' + $(this).attr('rel'),
			'deleteAlbumCallBack',
			'listError',
            false);
         }

        return false;
    })

    //modify
    $('a.editAlbum').click(function(){
        changeNavTitle('Edit Album');
        $('#divSearchAlbum').hide();

        getFormEditAlbum($(this).attr('rel'));

        return false;
    });

	$('a.paging').click(function(){
		currentPageAlbum = $(this).attr('rel');
		listAlbum();
		return false;
	});

}

function listError()
{
    $('#divContent').html('Cannot process. Please refesh and try again');
	$(":button").removeAttr('disabled');
}

function getFormAddAlbumCallBack()
{

	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload-album-cover',
					allowedExtensions: ['jpg', 'jpeg', 'png'],
					name: 'image_file',
                    onChange: function(file, ext){
                        $('#fileSelected').html(file);
                    },
					onSubmit : function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg)$/i.test(ext))){
                            displayMessageBox('Error: invalid file extension');
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }
                        this.setData({'file_extension': ext});

						// change button text, when user selects file
						$('#photo').html('Uploading');

						// If you want to allow uploading only 1 file at time,
						// you can disable upload button
						this.disable();

						// Uploding -> Uploading. -> Uploading...
						interval = window.setInterval(function(){
							var text = $('#photo').html();
							if (text.length < 13){
								$('#photo').html(text + '.');
							} else {
								$('#photo').html('Uploading');
							}
						}, 200);
					},
					onComplete: function(file, response){

                        window.clearInterval(interval);
                        this.enable();
						response = JSON.parse(response);
                        if(!response.result)
                        {
                            $('#photo').text('Upload Fail');
                            displayMessageBox(response.data);
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }

						$('#photo').html('Upload Complete');
						$('#file_name').val(response.data);

                        //call add new
                        addNewAlbum();
					}
				});

	$('#btnSubmit').click(function(){
          checkAddNewAlbum();
	});
}
function checkAddNewAlbum()
{
	$(":button").attr('disabled',true);

	if($('#name').val() == '')
	{
		displayMessageBox('Please enter name!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#fileSelected').html()=='')
	{
		displayMessageBox('Please select image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	objectUpload.submit();
}

function addNewAlbum()
{
	callAjax('POST',
		'savingContent',
		 controllerPath + 'add-album',
		 $('#frmAdd').serializeArray(),
		'addAlbumCallBack',
		'listError',
		false);
}

function addAlbumCallBack()
{
    if(!ajaxReturnData.result)
	{
		$('#savingContent').html('');
		$(":button").removeAttr('disabled');
		return;
	}
    ajaxReturnData = null;
    listAlbum();

	//call list combo
	getComboAlbum();
}

function getFormEditAlbum(id)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-edit-album',
			'id=' + id,
			'getFormEditAlbumCallBack',
			'listError');
}
function getFormEditAlbumCallBack()
{
	$('a.lightbox').lightBox();

	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload-album-cover',
					name: 'image_file',
                    onChange: function(file, ext){
                        $('#fileSelected').html(file);
                    },
					onSubmit : function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg)$/i.test(ext))){
                            displayMessageBox('Error: invalid file extension');
                            $(":button").removeAttr('disabled');
                            return false;
                        }
                        this.setData({'file_extension': ext});

						// change button text, when user selects file
						$('#photo').html('Uploading');

						// If you want to allow uploading only 1 file at time,
						// you can disable upload button
						this.disable();

						// Uploding -> Uploading. -> Uploading...
						interval = window.setInterval(function(){
							var text = $('#photo').html();
							if (text.length < 13){
								$('#photo').html(text + '.');
							} else {
								$('#photo').html('Uploading');
							}
						}, 200);
					},
					onComplete: function(file, response){

                        window.clearInterval(interval);
                        this.enable();
                        response = JSON.parse(response);
                        if(!response.result)
                        {
                            $('#photo').text('Upload Fail');
                            displayMessageBox(response.data);
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }

						$('#photo').html('Upload Complete');
						$('#file_name').val(response.data);

                        //call edit
                        editAlbum();
					}
				});

	$('#btnSubmit').click(function(){
          checkEditAlbum();
	});

	$('#resetFormEdit').click(function(){
        getFormEditAlbum($('#idRecord').val());
    });


}

function checkEditAlbum()
{
	$(":button").attr('disabled',true);

	if($('#name').val() == '')
	{
		displayMessageBox('Please enter name!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if ($('#check_image').attr('checked') && $('#fileSelected').html()=='')
	{
		displayMessageBox('You must select new image to replace current image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	//we don't need upload file if don' replace
	if (!$('#check_image').attr('checked'))
	{
		editAlbum();
		return false;
	}

	//else we will upload to replace
	objectUpload.submit();
}
function editAlbum()
{
	var replaceFile = 0;
	if ($('#check_image').attr('checked')) replaceFile = 1;

	var formEdit = $('#frmAdd').serializeArray();
	formEdit[formEdit.length] = {name:'replaceFile', value:replaceFile};

	callAjax('POST',
		'savingContent',
		 controllerPath + 'update-album',
		 formEdit,
		'addAlbumCallBack',
		'listError',
		false);
}

function deleteAlbumCallBack()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
	listAlbum();

	//get combo album
	getComboAlbum();
}

function changeOrderAlbum(id, type)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'reorder-album',
			'id=' + id + '&type=' + type,
			'listAlbum',
			'listError',
            false);
}

function changeStatus(object,id)
{
	if (object.checked)
	   $('#' + id).css('display','block');
	else
	   $('#' + id).css('display','none');
}

//--------
function listImage()
{
	$('#divSearchAlbum').hide();
	$('#divSearchImage').show();

    //change title
    changeNavTitle('List Images');

    //assign current page form search
    var form = $('#frmSearchImage').serializeArray();
    form[form.length] = {name : 'page' , value : currentPageImage};

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-image',
			 form,
			'listImageOk',
			'listError');
    return true;
}

function listImageOk()
{
    $('a.lightbox').lightBox();

	$('a.upImage').click(function(){
        changeOrderImage($(this).attr('rel'),'up');
        return false;
    })
    $('a.downImage').click(function(){
        changeOrderImage($(this).attr('rel'),'down');
        return false;
    })

	$('a.deleteImage').click(function(){
        if(confirm('Delete this image. Are you sure ?'))
         {
             callAjax('GET',
			'divContent',
			 controllerPath + 'delete-image',
			'id=' + $(this).attr('rel'),
			'deleteImageCallBack',
			'listError',
            false);
         }

        return false;
    })

    //modify
    $('a.editImage').click(function(){
        changeNavTitle('Edit Image');
        $('#divSearchImage').hide();

        getFormEditImage($(this).attr('rel'));

        return false;
    });

	$('a.paging').click(function(){
		currentPageImage = $(this).attr('rel');
		listImage();
		return false;
	});

}

function getFormAddImageCallBack()
{

	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload-image',
					allowedExtensions: ['jpg', 'jpeg', 'png'],
					name: 'image_file',
                    onChange: function(file, ext){
                        $('#fileSelected').html(file);
                    },
					onSubmit : function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg)$/i.test(ext))){
                            displayMessageBox('Error: invalid file extension');
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }
                        this.setData({'file_extension': ext});

						// change button text, when user selects file
						$('#photo').html('Uploading');

						// If you want to allow uploading only 1 file at time,
						// you can disable upload button
						this.disable();

						// Uploding -> Uploading. -> Uploading...
						interval = window.setInterval(function(){
							var text = $('#photo').html();
							if (text.length < 13){
								$('#photo').html(text + '.');
							} else {
								$('#photo').html('Uploading');
							}
						}, 200);
					},
					onComplete: function(file, response){

                        window.clearInterval(interval);
                        this.enable();
						response = JSON.parse(response);
                        if(!response.result)
                        {
                            $('#photo').text('Upload Fail');
                            displayMessageBox(response.data);
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }

						$('#photo').html('Upload Complete');
						$('#file_name').val(response.data);

                        //call add new
                        addNewImage();
					}
				});

	$('#btnSubmit').click(function(){
          checkAddNewImage();
	});
}
function checkAddNewImage()
{
	$(":button").attr('disabled',true);

	if($('#title').val() == '')
	{
		displayMessageBox('Please enter title!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#id_album').val() == '')
	{
		displayMessageBox('Please select album for this image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#description').val() == '')
	{
		displayMessageBox('Please enter description!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#fileSelected').html()=='')
	{
		displayMessageBox('Please select image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	objectUpload.submit();
}

function addNewImage()
{
	callAjax('POST',
		'savingContent',
		 controllerPath + 'add-image',
		 $('#frmAdd').serializeArray(),
		'addImageCallBack',
		'listError',
		false);
}

function addImageCallBack()
{
    if(!ajaxReturnData.result)
	{
		$('#savingContent').html('');
		$(":button").removeAttr('disabled');
		return;
	}
    ajaxReturnData = null;
    listImage();

}

function getFormEditImage(id)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-edit-image',
			'id=' + id,
			'getFormEditImageCallBack',
			'listError');
}
function getFormEditImageCallBack()
{
	$('a.lightbox').lightBox();

	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload-image',
					name: 'image_file',
                    onChange: function(file, ext){
                        $('#fileSelected').html(file);
                    },
					onSubmit : function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg)$/i.test(ext))){
                            displayMessageBox('Error: invalid file extension');
                            $(":button").removeAttr('disabled');
                            return false;
                        }
                        this.setData({'file_extension': ext});

						// change button text, when user selects file
						$('#photo').html('Uploading');

						// If you want to allow uploading only 1 file at time,
						// you can disable upload button
						this.disable();

						// Uploding -> Uploading. -> Uploading...
						interval = window.setInterval(function(){
							var text = $('#photo').html();
							if (text.length < 13){
								$('#photo').html(text + '.');
							} else {
								$('#photo').html('Uploading');
							}
						}, 200);
					},
					onComplete: function(file, response){

                        window.clearInterval(interval);
                        this.enable();
                        response = JSON.parse(response);
                        if(!response.result)
                        {
                            $('#photo').text('Upload Fail');
                            displayMessageBox(response.data);
                            $(":button").removeAttr('disabled');
							$('#fileSelected').html('');
                            return false;
                        }

						$('#photo').html('Upload Complete');
						$('#file_name').val(response.data);

                        //call edit
                        editImage();
					}
				});

	$('#btnSubmit').click(function(){
          checkEditImage();
	});

	$('#resetFormEdit').click(function(){
        getFormEditImage($('#idRecord').val());
    });


}

function checkEditImage()
{
	$(":button").attr('disabled',true);

	if($('#title').val() == '')
	{
		displayMessageBox('Please enter title!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#id_album').val() == '')
	{
		displayMessageBox('Please select album for this image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if ($('#check_image').attr('checked') && $('#fileSelected').html()=='')
	{
		displayMessageBox('You must select new image to replace current image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	if($('#title').val() == '')
	{
		displayMessageBox('Please enter description!');
		$(":button").removeAttr('disabled');
		return false;
	}

	//we don't need upload file if don' replace
	if (!$('#check_image').attr('checked'))
	{
		editImage();
		return false;
	}

	//else we will upload to replace
	objectUpload.submit();
}
function editImage()
{
	var replaceFile = 0;
	if ($('#check_image').attr('checked')) replaceFile = 1;

	var formEdit = $('#frmAdd').serializeArray();
	formEdit[formEdit.length] = {name:'replaceFile', value:replaceFile};

	callAjax('POST',
		'savingContent',
		 controllerPath + 'update-image',
		 formEdit,
		'addImageCallBack',
		'listError',
		false);
}

function deleteImageCallBack()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
	listImage();
}

function changeOrderImage(id, type)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'reorder-image',
			'id=' + id + '&type=' + type + '&idAlbum=' + $('#albumSearch').val(),
			'listImage',
			'listError',
            false);
}