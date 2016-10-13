//SonIT
//this common function using for call ajax
var ajaxReturnData = null;
function callAjax(type, id_div_content,link_action,param,callback_success,callback_error,displayData)
{
    if (param == null) param = '';
    $.ajax({
						url: link_action,
						type: type,
						data: param,
						dataType: 'json',
						timeout: ajaxTimeOut,
						beforeSend: function(){
								$('#'+ id_div_content).html('<p align="center"><img src="' + ajax_loader_image +'" /></p>');
						},
						error: function(data){
                                if (callback_error != null){
                                    eval( callback_error + '();');
                                }
                        },
					    success: function(returnData) {
                                ajaxReturnData = returnData;
                                if(!returnData.result)
                                {
                                    displayMessageBox(returnData.data);
                                }
                                
								if(returnData.data == 'TIME_OUT')
								{
									window.location = websiteUrl + 'admin/index/index/e/2';
								}
                                if (displayData || displayData == null)
                                    $('#'+ id_div_content).html(returnData.data);
                                else
                                    displayMessageBox(returnData.data);

                                if (callback_success != null) {
                                    eval( callback_success + '();');
                                }
						}
	});
}

var t;
var seconds = 0;

function displayMessageBox(message)
{
	clearTimeout(t);
	$('#messageDetail').html('<img src="' + message_icon + '" />' + ' ' + message);
	$('#message').show('fast');
	window.scrollTo(0,0);
	t = setTimeout("hideMessageBox(seconds)",3000);
}

function hideMessageBox(seconds)
{
	if (seconds ==0 ) seconds+=3000;
	$('#message').hide(200);
	if (seconds == 3000 ) clearTimeout(t);
}

function checkEmail(emailValue) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailValue)){
		return true;
	}
	return false;
}

function displayImageWaiting()
{
	$('#message').removeClass('message');
	$('#message').show();
	$('#messageDetail').html('<p align="center"><img src="' + ajax_loader_image +'" /></p>');
}
function displayError()
{
	$('#message').addClass('message');
	$('#message').show('fast');
	$('#messageDetail').html('<p align="center">Data request error. Please refesh and try again.</p>');
}
function displayDeleteOk()
{
	$('#message').addClass('message');
	$('#message').show('fast');
	$('#messageDetail').html('<p align="center">Data request error. Please refesh and try again.</p>');
}

function changeNavTitle(value)
{
    $('#navTitle').html(value);
}
function fixedEncodeURIComponent (str) {
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28'). replace(/\)/g, '%29').replace(/\*/g, '%2A');
}

function checkDate(ele, text)
{
	var marker="-";
	var submitDate = $('#' + ele).val();
	var dateCompTemp = submitDate.split(marker);
	var dateComp = new Array(3);
	dateComp[0] = dateCompTemp[1];
	dateComp[1] = dateCompTemp[0];
	dateComp[2] = dateCompTemp[2];
	var now = new Date();
	var yearNow=now.getFullYear();
	dayInmonth = new Array(12);
	dayInmonth[0]=31;
	dayInmonth[1]=29;
	dayInmonth[2]=31;
	dayInmonth[3]=30;
	dayInmonth[4]=31;
	dayInmonth[5]=30;
	dayInmonth[6]=31;
	dayInmonth[7]=30;
	dayInmonth[8]=31;
	dayInmonth[9]=31;
	dayInmonth[10]=30;
	dayInmonth[11]=31;
	if (dateComp.length != 3 )
	{
		displayMessageBox("Please enter correct date format for "+text+" (dd-mm-yyyy)!");
		return false;
	}
	for (var i=0; i < 3; i++)
	{
		if(isNaN(dateComp[i]))
		{
			displayMessageBox("Please enter numeric for month, date, and year ( " + text + " )!");
			return false;
		}
	}
	if (dateComp[0] > 12 || dateComp[0] < 1)
	{
		displayMessageBox("Please enter a valid month for "+text+" (1 to 12) !");
		return false;
	}
	if(dateComp[2] < 1900)
	{
		displayMessageBox("Please enter a valid year for "+text+" (from 1900) !");
		return false;
	}

//	if (dateComp[2] > yearNow+1)
//	{
//		displayMessageBox("Please enter a valid year for "+text+"! (future)");
//		return false;
//	}
//	if (dateComp[2] < yearNow-1)
//	{
//		displayMessageBox("Please enter a valid year for "+text+"! (past)");
//		return false;
//	}
	if (dateComp[2] % 4 == 0)
	{
		dayInmonth[1]=29;
	}
	else
	{
		dayInmonth[1]=28;
	}
	if (dateComp[1] > dayInmonth[dateComp[0]-1] || dateComp[1] < 1)
	{
		displayMessageBox("Please enter a valid date!");
		return false;
	}

	return true;
}

function dateRangeValid(startDate, endDate)
{
	start	= startDate.split('-');
	end		= endDate.split('-');
	dateStart	= new Date(start[2], start[1], start[0]);
	dateEnd		= new Date(end[2], end[1], end[0]);
	if(dateStart > dateEnd) return false;
	return true;
}