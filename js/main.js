function main_request_ajax(url, mode, data, onSuccess)
{
	//main_loading_show();
	
	$.ajax({
		url: url,
		type: 'post',
		data: { 'mode' : mode, 'data' : data},
		// [dataType] can be removed so that receiving any types of return data from PHP
		// eg. check the actual error on PHP if we received ParserError
		// eg. result from print_r()
		dataType: 'json', 
		success: onSuccess,
		error: function(xhr, desc, err){
			//main_loading_hide();
			main_alert_message('Details: ' + desc + ' | Error:' + err);
			
			// N.B.
			// undefined object (non-exist jquery object) from Client-Side cause ParserError
			// unmatch keys of the object between client and server (php) also cause ParserError
		}
	});
}

function main_redirect(url)
{
	window.location.replace(url);
}

function main_get_parameter(name, url) {
    if (!url) url = window.location.href;
    url = url.toLowerCase(); // This is just to avoid case sensitiveness  
    name = name.replace(/[\[\]]/g, "\\$&").toLowerCase();// This is just to avoid case sensitiveness for query parameter name
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function main_input_date_picker(jquerySelector)
{
	$(jquerySelector).datepicker({
        showOn: 'button',
        buttonImage: '../image/calendar.png',
        buttonImageOnly: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'dd/mm/yy',
        showAnim: '',
        buttonText: ''
	});
}

function main_alert_message(msg, fnOnOK)
{
	$.messagebox.alert({ message : msg, icon : 'error', onOK : fnOnOK });
}

function main_info_message(msg, fnOnOK)
{
	$.messagebox.alert({ message : msg, icon : 'info', onOK : fnOnOK });
}

function main_confirm_message(msg, fnOnYes, fnOnNo)
{
	$.messagebox.confirm({ message : msg, icon : 'question', onYes : fnOnYes, onNo : fnOnNo });
}