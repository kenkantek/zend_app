var currentPage = 1;
var interval;
var objectUpload;

$(document).ready(function(){

    //handle form seach
    $('#frmSearch').submit(function(){
		currentPage = 1;
        list();
        return false;
    })

    //List
    $('a.list').click(function(){
        list();
        return false;
    });

    //Add new
    $('a.add').click(function(){
        changeNavTitle('Add');
        $('#divSearch').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-banner',
			'',
			'getFormAddCallBack',
			'listError');

        return false;
    });

	list();
    
})

function listOk()
{
    $('a.lightbox').lightBox();

	$('a.up').click(function(){
        changeOrder($(this).attr('rel'),'up');
        return false;
    })
    $('a.down').click(function(){
        changeOrder($(this).attr('rel'),'down');
        return false;
    })

	$('a.delete').click(function(){
        if(confirm('Delete this item. Are you sure ?'))
         {
             callAjax('GET',
			'divContent',
			 controllerPath + 'delete-banner',
			'id=' + $(this).attr('rel'),
			'deleteCallBack',
			'listError',
            false);
         }

        return false;
    })

    //modify
    $('a.edit').click(function(){
        changeNavTitle('Edit');
        $('#divSearch').hide();

        getFormEdit($(this).attr('rel'));

        return false;
    });

	$('a.paging').click(function(){
		currentPage = $(this).attr('rel');
		list();
		return false;
	});

}
function listError()
{
    $('#divContent').html('Cannot process. Please refesh and try again');
	$(":button").removeAttr('disabled');
}
function list()
{
	$('#divSearch').show();

    //change title
    changeNavTitle('List');

    //assign current page form search
    var form = $('#frmSearch').serializeArray();
    form[form.length] = {name : 'page' , value : currentPage};

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-banner',
			 form,
			'listOk',
			'listError');
    return true;
}

function getFormAddCallBack()
{
    
	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload',
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
                        addNew();
					}
				});
				
	$('#btnSubmit').click(function(){
          checkAddNew();
	});
}
function checkAddNew()
{
	$(":button").attr('disabled',true);

	if($('#fileSelected').html()=='')
	{
		displayMessageBox('Please select image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	objectUpload.submit();
}

function addNew()
{
	callAjax('POST',
		'savingContent',
		 controllerPath + 'add-banner',
		 $('#frmAdd').serializeArray(),
		'addCallBack',
		'listError',
		false);
}

function addCallBack()
{
    if(!ajaxReturnData.result)
	{
		$('#savingContent').html('');
		return;
	}
    ajaxReturnData = null;
    list();
}

function deleteCallBack()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
	list();
}
function getFormEdit(id)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-edit-banner',
			'id=' + id,
			'getFormEditCallBack',
			'listError');
}
function getFormEditCallBack()
{
	$('a.lightbox').lightBox();
	
	objectUpload = new AjaxUpload('photo', {
					autoSubmit: false,
					action: controllerPath + 'do-upload',
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
                        edit();
					}
				});

	$('#btnSubmit').click(function(){
          checkEdit();
	});

	$('#resetFormEdit').click(function(){
        getFormEdit($('#current_page_id').val());
    });


}

function checkEdit()
{
	$(":button").attr('disabled',true);

	if ($('#check_image').attr('checked') && $('#fileSelected').html()=='')
	{
		displayMessageBox('You must select new image to replace current image!');
		$(":button").removeAttr('disabled');
		return false;
	}

	//we don't need upload file if don' replace
	if (!$('#check_image').attr('checked'))
	{
		edit();
		return false;
	}

	//else we will upload to replace
	objectUpload.submit();
}
function edit()
{
	var replaceFile = 0;
	if ($('#check_image').attr('checked')) replaceFile = 1;

	var formEdit = $('#frmAdd').serializeArray();
	formEdit[formEdit.length] = {name:'replaceFile', value:replaceFile};

	callAjax('POST',
		'savingContent',
		 controllerPath + 'update-banner',
		 formEdit,
		'addCallBack',
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

function changeOrder(id, type)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'reorder-banner',
			'id=' + id + '&type=' + type,
			'list',
			'listError',
            false);
}