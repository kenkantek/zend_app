var parent_id = null;
var textSearch = null;
var currentPage = 1;

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

	list();

})

function listOk()
{
    $('a.delete').click(function(){
        if(confirm('Delete this item. Are you sure ?'))
         {
             callAjax('GET',
			'divContent',
			 controllerPath + 'delete-staff',
			'id=' + $(this).attr('rel'),
			'deleteCallBack',
			'listError',
            false);
         }

        return false;
    })

	//modify
    $('a.edit').click(function(){
        changeNavTitle('Detail');
        $('#divSearch').hide();

        getFormEdit($(this).attr('rel'));

        return false;
    });

	$('a.paging').click(function(){
		currentPage = $(this).attr('rel');
		list();
		return false;
	});

    $('a.activeState').click(function(){
        changeStatus($(this).attr('rel'),1);
        return false;
    })

    $('a.deactiveState').click(function(){
        changeStatus($(this).attr('rel'),0);
        return false;
    })

}
function listError()
{
    $('#divContent').html('Can not process. Please refesh and try again');
}
function list()
{
    //change title
    changeNavTitle('List');

	$('#divSearch').show();

    //assign current page form search
    var form = $('#frmSearch').serializeArray();
    form[form.length] = {name : 'page' , value : currentPage};

    callAjax('POST',
			'divContent',
			 controllerPath + 'get-list-staff',
			 form,
			'listOk',
			'listError');
    return true;
}

function deleteCallBack()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
	list();
}

function changeStatus(id, status)
{
    callAjax('GET',
			'divContent',
			controllerPath + 'change-staff-status' + '/id/' + id + '/status/' + status,
            '',
			'changeStatusCallback',
			'listError',
            false);
}

function changeStatusCallback()
{
    if(!ajaxReturnData.result) return;
    ajaxReturnData = null;
	list();
}

function getFormEdit(id)
{
    callAjax('GET',
			'divContent',
			 controllerPath + 'get-staff-edit',
			'id=' + id,
			'getFormEditCallBack',
			'listError');
}
function getFormEditCallBack()
{

    $('#frmAdd').submit(function(){

		if($('#username').val() == '')
		{
			displayMessageBox('Username can not be empty');
			return false;
		}

        callAjax('POST',
			'savingContent',
			 controllerPath + 'update-staff',
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