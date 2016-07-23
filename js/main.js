// First, checks if it isn't implemented yet.
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}

function main_request_ajax(url, mode, data, onSuccess)
{
	//main_loading_show();

	// ***Note
	//		- a single quate copied from any document can cause an error [Unexpected end of input]
	//			, so check carefully or type by yourself.
	
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

function main_open_new_tab(url)
{
	window.open(url, '_blank');
}

function main_log_out()
{
	main_request_ajax('../authentication/authentication-boundary.php', 'LOG_OUT', {}, main_on_log_out_success);
}

function main_on_log_out_success(response)
{
	if (response.success) {
		main_redirect('../login/login.php');
	}
	else {
		main_alert_message(response.msg);
	}
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

function main_convert_date_format(date)
{
	// Bug with Datebox's formatter
	// set it like this : data-options="formatter: main_date_box_format"
	var day = date.getDate();
	var month = date.getMonth()+1;
	var year = date.getFullYear();
	
	day = day < 10 ? ('0' + day) : day;
	month = month < 10 ? ('0' + month) : month;
	
	return day + '/' + month + '/' + year;
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

function main_is_int(n){
    return n % 1 === 0;
}

function main_is_float(n){
    return n % 1 !== 0;
}












