var parent_id = null;
var textSearch = null;

$(document).ready(function(){

    //first load combo parent
    loadParentCombo();

    //handle form seach
    $('#frmSearch').submit(function(){
        list();
        return false;
    })

    //Page List
    $('a.list').click(function(){
        list();
        return false;
    });

    //Add new
    $('a.add').click(function(){
        changeNavTitle('Add New Page');
        $('#divSearch').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-new-page',
			'',
			'getFormAddCallBack',
			'listError');

        return false;
    });

})
function loadParentCombo()
{
    if($('#parent_id').length > 0)
    {
        callAjax('GET',
                'divParent',
                 controllerPath + 'get-combo-parent',
                'parent_id=' + $('#parent_id').val() ,
                'list',
                'listError');
    }
    else
    {
        callAjax('GET',
                'divParent',
                 controllerPath + 'get-combo-parent',
                '',
                'list',
                'listError');
    }
}

function listOk()
{
    $('a.up').click(function(){
        changeOrder($(this).attr('rel'),'up');
        return false;
    })
    $('a.down').click(function(){
        changeOrder($(this).attr('rel'),'down');
        return false;
    })
    $('a.delete').click(function(){
        if(confirm('Delete this page also delete its child page. Are you sure ?'))
         {
             callAjax('POST',
			'divContent',
			 controllerPath + 'delete-content-page',
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
        $('#divSearch').hide();

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
    $('#divSearch').show();

    parent_id   = $('#parent_id').val();
    textSearch  = $('#textSearch').val();

    if(parent_id == '')
    {
        displayMessageBox('Please select parent');
        return;
    }

    //change title
    changeNavTitle('List');

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-content-page',
			'parent_id=' + parent_id + '&text=' + fixedEncodeURIComponent(textSearch),
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
			 controllerPath + 'add-new-page',
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
    loadParentCombo();
}
function deleteCallBack()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
    loadParentCombo();
}
function getFormEdit(id)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-edit-page',
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
			 controllerPath + 'update-page',
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

function changeOrder(id, type)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'reorder-page',
			'id=' + id + '&type=' + type,
			'list',
			'listError',
            false);
}