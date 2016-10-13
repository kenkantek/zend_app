var parent_id = null;
var textSearch = null;
var currentPage = 1;

$(document).ready(function(){

    $('#from_date').datepicker({autoSize: true,
                            changeMonth: true,
                            changeYear: true,
                            closeText: 'Close',
                            currentText: 'Now',
                            dateFormat: 'dd-mm-yy',
                            gotoCurrent: true,
                            showButtonPanel: true,
                            showWeek: true,
                            weekHeader: 'Week'
    });

    $('#to_date').datepicker({autoSize: true,
                            changeMonth: true,
                            changeYear: true,
                            closeText: 'Close',
                            currentText: 'Now',
                            dateFormat: 'dd-mm-yy',
                            gotoCurrent: true,
                            showButtonPanel: true,
                            showWeek: true,
                            weekHeader: 'Week'
    });

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
        changeNavTitle('Add News');
        $('#divSearch').hide();

        callAjax('GET',
			'divContent',
			 controllerPath + 'get-form-add-news',
			'',
			'getFormAddCallBack',
			'listError');

        return false;
    });

	list();

})

function listOk()
{
    $('a.delete').click(function(){
        if(confirm('Delete this item. Are you sure ?'))
         {
             callAjax('GET',
			'divContent',
			 controllerPath + 'delete-news',
			'id=' + $(this).attr('rel'),
			'deleteCallBack',
			'listError',
            false);
         }

        return false;
    })

    //modify
    $('a.edit').click(function(){
        changeNavTitle('Edit News');
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
}
function list()
{
	$('#divSearch').show();

    if($('#from_date').val() != '' && !checkDate('from_date', 'From Date'))
    {
        $('#from_date').focus();
        return false;
    }

    if($('#to_date').val() != '' && !checkDate('to_date', 'To Date'))
    {
        $('#to_date').focus();
        return false;
    }

    if($('#from_date').val() != '' && $('#to_date').val() != '')
    {
        if (!dateRangeValid($('#from_date').val(), $('#to_date').val()))
        {
            displayMessageBox('Date range is invalid!');
            return false;
        }
    }


    //change title
    changeNavTitle('List');

    //assign current page form search
    var form = $('#frmSearch').serializeArray();
    form[form.length] = {name : 'page' , value : currentPage};

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-news',
			 form,
			'listOk',
			'listError');
    return true;
}

function getFormAddCallBack()
{
    $('#date').datepicker({autoSize: true,
                            changeMonth: true,
                            changeYear: true,
                            closeText: 'Close',
                            currentText: 'Now',
                            dateFormat: 'dd-mm-yy',
                            gotoCurrent: true,
                            showButtonPanel: true,
                            showWeek: true,
                            weekHeader: 'Week'
    });

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

        if(!checkDate('date', 'Date'))
        {
            $('#date').focus();
            return false;
        }

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
			 controllerPath + 'add-news',
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
			 controllerPath + 'get-form-edit-news',
			'id=' + id,
			'getFormEditCallBack',
			'listError');
}
function getFormEditCallBack()
{
    $('#date').datepicker({autoSize: true,
                            changeMonth: true,
                            changeYear: true,
                            closeText: 'Close',
                            currentText: 'Now',
                            dateFormat: 'dd-mm-yy',
                            gotoCurrent: true,
                            showButtonPanel: true,
                            showWeek: true,
                            weekHeader: 'Week'
    });

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

        if(!checkDate('date', 'Date'))
        {
            $('#date').focus();
            return false;
        }

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
			 controllerPath + 'update-news',
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