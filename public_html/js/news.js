$(document).ready(function(){

	var currentPage = 1;

	//for new
	if($('#newsContainer').length)
	{
		list(currentPage);
	}
})

function list(page)
{
	currentPage = page;
	callAjax('GET',
			'newsContainer',
			 news_list_process_page + currentPage,
			'',
			'listOk',
			'listError');
}

function listOk()
{
	$('a.paging').click(function(){
		list($(this).attr('rel'));
		return false;
	});
}

function listError()
{
}