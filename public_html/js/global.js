$(document).ready(function(){

	// Uses Looped Slider plugin
	if($('#slide_home').length >0)
	{
		$('#slide_home').loopedSlider({
			autoStart: 12000, //this is time switch between images ,4second
			containerClick: false,
			restart: 6000,
			fadespeed: 500 //duration of fade effect 0.5 second,
		});
	}


    $('#enews_email').focus(function(){
		$(this).val(($(this).val() == 'Email') ? '' : $(this).val());
	});

	$('#enews_email').blur(function(){
		$(this).val(($(this).val() == '') ? 'Email' : $(this).val());
	});

	$('#enews_name').focus(function(){
		$(this).val(($(this).val() == 'Name') ? '' : $(this).val());
	});

	$('#enews_name').blur(function(){
		$(this).val(($(this).val() == '') ? 'Name' : $(this).val());
	});

	$('#form_subscribe').submit(function(){

			if($('#enews_name').val() == '')
			{
				alert('Please enter Name');
				$('#enews_name').focus();
				return false;
			}

			if(!checkEmail($('#enews_email').val()) || $('#enews_email').val() == 'Email')
			{
				alert('Invalid email');
				$('#enews_email').focus();
				return false;
			}

			var param = new Array({name: 'email' , value : $('#enews_email').val()},
								{name: 'name' , value : $('#enews_name').val()});

			$.ajax({
								url: processPath + '/do/send/subcription/',
								type: 'POST',
								data: param,
								dataType: 'json',
								timeout: ajaxTimeOut,
								beforeSend: function(){
									beforeSubmit();
								},
								error: function(data){
									alert('Can not process data');
									window.location.reload();
								},
								success: function(returnData) {
									alert(returnData.data);
									$.unblockUI();
									$('#enews_name').val('');
									$('#enews_email').val('');
								}
			});

			return false;
		});

		//search box
		$('#searchTextTop').focus(function(){
			$(this).val(($(this).val() == 'Search') ? '' : $(this).val());
		});

		$('#searchTextTop').blur(function(){
			$(this).val(($(this).val() == '') ? 'Search' : $(this).val());
		});
		$('#btSubmitSearch').click(function(){
			doSearchAction();
		});

		$('#searchTextTop').keyup(function(e){
			if(e.keyCode == 13)
			{
				doSearchAction();
			}
		});

		//popup staff login box
		$('a.staffLogin').click(function(){
			$('#staffUsername').val('');
			$('#staffPassword').val('');

			//popup
			$.fancybox({
				'titlePosition'     : 'inside',
				'transitionIn'      : 'none',
				'transitionOut'     : 'none',
				'href'              : '#staffLoginBox',
				'modal'             : true,
				'showCloseButton'   : false
			});
			return false;
		})

		$('#doCloseBox').click(function(){
			$.fancybox.close();
			return false;
		});

		$('#doLoginStaff').click(function(){

			if($('#staffUsername').val() == '')
			{
				alert(staffEmptyUsername);
				$('#staffUsername').focus();
				return false;
			}

			if($('#staffPassword').val() == '')
			{
				alert(staffEmptyPassword);
				$('#staffPassword').focus();
				return false;
			}

			var param = new Array({name: 'staffUsername' , value : $('#staffUsername').val()},
								{name: 'staffPassword' , value : $('#staffPassword').val()});

			$.ajax({
								url: processPath + 'do-login/',
								type: 'POST',
								data: param,
								dataType: 'json',
								timeout: ajaxTimeOut,
								beforeSend: function(){
									beforeSubmit();
								},
								error: function(data){
									alert('Can not process data');
									window.location.reload();
								},
								success: function(returnData) {
									$.unblockUI();
									if(returnData.result == true)
									{
										window.location = processPath + 'staff';
									}
									else
									{
										alert(returnData.data);
									}
								}
			});

			return false;
		});

//    $('#formSigup').submit(function(){
//        if($('#sigupName').val() == '' || $('#sigupName').val() == 'Name')
//        {
//            displayMessageBox('Please enter your name!');
//            return false;
//        }
//
//        if(!checkEmail($('#sigupEmail').val()) || $('#sigupEmail').val() == 'Email Address')
//        {
//            displayMessageBox('Please enter a valid email address!');
//            return false;
//        }
//
//        callAjax('POST',
//			'sendingContent',
//            websiteUrl + 'do/send/subcription/',
//			 $(this).serializeArray(),
//			'sendCallBack',
//			'ajaxError');
//
//        return false;
//    });
})

//function sendCallBack()
//{
//    $('#sendingContent').html('');
//    if(ajaxReturnData.result != true)
//    {
//        displayMessageBox(ajaxReturnData.data);
//        return;
//    }
//    $('#sigupName').val('Name');
//    $('#sigupEmail').val('Email Address');
//    displayMessageBox(ajaxReturnData.data);
//}
//
//function ajaxError()
//{
//
//}

function doSearchAction()
{
	if($('#searchTextTop').val() == ''
			|| $('#searchTextTop').val() == 'Search'
			|| $('#searchTextTop').val().length < 3)
		{
			alert(invalidTextSearch);
			return false;
		}
	window.location = processPath + 'search/' + $('#searchTextTop').val();
}

function beforeSubmit()
{
	var sTag = '<span style="color:#FFFFFF;font-size:16px;font-weight:bold;">Processing. <br/><br/><br/>Please wait...</font>';

	$.blockUI({
		overlayCSS: {backgroundColor: ''},
		css: {
			border: 'none',
			backgroundColor: '#8F133A',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: 0.5,
			color: '#fff',
			width: '100%',
			left:0,
			height:'200'
		},
		message: '<br><br><img src="images/waiting.gif" /><br><br>' + sTag
	});

	return true;
}