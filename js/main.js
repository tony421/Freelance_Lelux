function main_request_ajax(url, mode, data, onSuccess)
{
	//main_loading_show();
	
	$.ajax({
		'url' : url,
		'type' : 'post',
		'data' : { 'mode' : mode, 'data' : data},
		'dataType' : 'json',
		'success' : onSuccess,
		'error' : function(xhr, desc, err){
			//main_loading_hide();
			main_alert_message('Details: ' + desc + ' | Error:' + err);
		}
	});
}

function main_alert_message(msg, fnOnOK)
{
	$.messagebox.alert({ message : msg, onOK : fnOnOK });
}

function main_confirm_message(msg, fnOnYes, fnOnNo)
{
	$.messagebox.confirm({ message : msg, icon : 'question', onYes : fnOnYes, onNo : fnOnNo });
}