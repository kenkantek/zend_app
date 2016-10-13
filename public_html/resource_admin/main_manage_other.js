$(document).ready(function(){
	//Page List
    $('a.list').click(function(){
        list();
        return false;
    });

	list();

	//Add new
    $('a.add').click(function(){
        changeNavTitle('Add');
        $('#divSearch').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-new-other-page',
			'',
			'getFormAddCallBack',
			'listError');

        return false;
    });
})
function listOk()
{
	$('a.delete').click(function(){
        if(confirm('Are you sure ?'))
         {
             callAjax('POST',
			'divContent',
			 controllerPath + 'delete-other-page',
			'id=' + $(this).attr('rel'),
			'deleteCallBack',
			'listError',
            false);
         }

        return false;
    })

    //modify
    $('a.edit').click(function(){
        changeNavTitle('Edit Page');
        //$('#divSearch').hide();

        getFormEdit($(this).attr('rel'));

        return false;
    });

}

function listError()
{
    $('#divContent').html('Cannot process. Please refesh and try again');
}

function list()
{
    //change title
    changeNavTitle('List');

    callAjax('GET',
			'divContent',
			 controllerPath + 'get-list-other-page',
			'',
			'listOk',
			'listError');

}

function getFormAddCallBack()
{
    var oFCKeditorContentEn = new FCKeditor('content');
    oFCKeditorContentEn.BasePath = websiteUrl + "fckeditor/" ;
    oFCKeditorContentEn.Height = 320;
    oFCKeditorContentEn.Config["CustomConfigurationsPath"] = fckConfigFile  ;
    oFCKeditorContentEn.ReplaceTextarea();

	var oFCKeditorContentFr = new FCKeditor('content_fr');
    oFCKeditorContentFr.BasePath = websiteUrl + "fckeditor/" ;
    oFCKeditorContentFr.Height = 320;
    oFCKeditorContentFr.Config["CustomConfigurationsPath"] = fckConfigFile  ;
    oFCKeditorContentFr.ReplaceTextarea();

    $('#frmAdd').submit(function(){

        if($('#title').val() == '')
        {
            displayMessageBox('Please enter Title !');
            $('#title').focus();
            return false;
        }

        //must get all content from FCK first, else LOST DATA
        var oEditor = FCKeditorAPI.GetInstance('content') ;
        $('#content').val(oEditor.GetHTML());

		//must get all content from FCK first, else LOST DATA
        var oEditorFr = FCKeditorAPI.GetInstance('content_fr') ;
        $('#content_fr').val(oEditorFr.GetHTML());

        callAjax('POST',
			'savingContent',
			 controllerPath + 'add-new-other-page',
			 $('#frmAdd').serializeArray(),
			'addCallBack',
			'listError',
            false);

        return false;
    });

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
			 controllerPath + 'get-form-edit-other-page',
			'id=' + id,
			'getFormEditCallBack',
			'listError');
}

function getFormEditCallBack()
{
    var oFCKeditorContentEn = new FCKeditor('content');
    oFCKeditorContentEn.BasePath = websiteUrl + "fckeditor/" ;
    oFCKeditorContentEn.Height = 320;
    oFCKeditorContentEn.Config["CustomConfigurationsPath"] = fckConfigFile  ;
    oFCKeditorContentEn.ReplaceTextarea();

	var oFCKeditorContentFr = new FCKeditor('content_fr');
    oFCKeditorContentFr.BasePath = websiteUrl + "fckeditor/" ;
    oFCKeditorContentFr.Height = 320;
    oFCKeditorContentFr.Config["CustomConfigurationsPath"] = fckConfigFile  ;
    oFCKeditorContentFr.ReplaceTextarea();

    $('#frmAdd').submit(function(){

        if($('#title').val() == '')
        {
            displayMessageBox('Please enter Title!');
            $('#title_en').focus();
            return false;
        }

        //must get all content from FCK first, else LOST DATA
        var oEditor = FCKeditorAPI.GetInstance('content') ;
        $('#content').val(oEditor.GetHTML());

		//must get all content from FCK first, else LOST DATA
        var oEditorFr = FCKeditorAPI.GetInstance('content_fr') ;
        $('#content_fr').val(oEditorFr.GetHTML());

        callAjax('POST',
			'savingContent',
			 controllerPath + 'update-other-page',
			 $('#frmAdd').serializeArray(),
			'addCallBack',
			'listError',
            false);

        return false;
    });

    $('#resetFormEdit').click(function(){
        getFormEdit($('#current_page_id').val());
    });

}
