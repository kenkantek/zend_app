$(document).ready(function(){
	$('a.popupImage').click(function(){
		loadGallery($(this).attr('rel'));
		return false;
	})
})

function loadGallery(id)
{
	$.ajax({
			url: processPath + 'gallery-slide-show/' + id,
			type: 'GET',
			data: '',
			dataType: 'json',
			timeout: ajaxTimeOut,
			beforeSend: function(){
				var sTag = '<span style="color:#FFFFFF;font-size:16px;font-weight:bold;">Loading images. <br/><br/><br/>Please wait...</font>';

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
					message: '<br><br><img src="' + websiteUrl + 'images/waiting.gif" /><br><br>' + sTag
				});

				return true;
			},
			error: function(data){
				alert('Can not process data');
				window.location.reload();
			},
			success: function(returnData) {
				$.unblockUI();

				if(returnData.result == false)
				{
					alert(returnData.data);
					return;
				}

				$('#galleryContainerTemp').html(returnData.data);

				//call back
				$('a.galleryImage').prettyPhoto();

				//first click
				$('a.galleryImage:first').click();
			}
	});

	return false;
}