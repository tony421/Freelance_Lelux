var $btnSearchClient;
var $radSearchMem;
var $radSearchName;
var $radSearchTel;
var $txtText;
var $tableClient;
var $tableClientBody;
var dtTableClient;


function initPage()
{
	main_ajax_success_hide_loading();
	
	$btnSearchClient = $('#btnSearchClient');
	$radSearchMem = $('#radSearchMem');
	$radSearchName = $('#radSearchName');
	$radSearchTel = $('#radSearchTel');
	$txtText = $('#txtText');
	$txtText.focus();
	
	$tableClient = $('#tableClient');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	dtTableClient = $tableClient.DataTable({
		language: {
		    info: "Showing _START_ to _END_ of _TOTAL_ clients",
			//info: "Showing _TOTAL_ clients",
			infoEmpty: "",
		    lengthMenu: "Show _MENU_ clients / page"
		},
		dom: '<"top"lifp<"clear">>rt<"bottom"ip<"clear">>',
		lengthMenu: [ [10, 25, 50], [10, 25, 50] ], // [[pageLength, -1], [lengthMenu, "All"]]
		paging: true,
		pagingType: "numbers",
		//pageLength: 50,
		info: true,
		searching: false,
		ordering: false,
		rowId: 'client_id',
		columns: [
		    { data: "client_id", title: "Client ID", visible: false },
		    { data: "client_name", title: "Client Name (Tel)" 
		    	, render: function ( data, type, row ) { return data + " (" + row['client_contact_no'] + ")"; } },
            { data: "health_fund_name", title: "Health Fund" },
            { data: "client_membership_no", title: "Membership No. (Patient ID)", width: '25%'
            	, render: function ( data, type, row ) { return data + " (" + row['client_patient_id'] + ")"; } }
        ]
	});
	$tableClientBody = $('#tableClient tbody');
	
	$txtText.keypress(function(e){
		if (e.which == 13) {
			$btnSearchClient.click();
			return false;
		}
	});
	
	$txtText.focus(function(){
		$(this).select();
	});
	
	$btnSearchClient.click(function(){
		searchClient();
	});
	
	$('input[type=radio][name=searchby]').change(function() {
        if ($(this).val() == '2') { // search by "Phone No."
        	$txtText.inputmask('9999-999-999');
        } else {
        	$txtText.inputmask('remove');
        }
        
        $txtText.focus();
	});
}

function searchClient()
{
	var searchCon = getSearchCondition();
	main_request_ajax('client-boundary.php', 'SEARCH_CLIENT', searchCon, onRequestDone);
	
//	var find = getSearchCondition();
//	var y = find.search_membership + ' | ' + find.search_name + ' | ' + find.search_text + '\n';
//	alert(y);
}

function onRequestDone(response)
{
	//hideTableClient();
	clearTableClient();
	
	if (response.success) {
		addClientRows(response.result);
		setClientRowSelection();
		//showTableClient();
	}
	else {
		main_alert_message(response.msg, function(){$txtText.focus();});
	}
}

function getSearchCondition()
{
	var search = {
		search_membership: $radSearchMem.is(':checked'),
		search_name: $radSearchName.is(':checked'),
		search_tel: $radSearchTel.is(':checked'),
		search_text: $txtText.val()
	};
	
	return search;
}

function clearTableClient()
{
	dtTableClient.rows().remove().draw();
	$tableClientBody.unbind(); // unbind events to prevent duplicate events
}

function hideTableClient()
{
	$tableClient.css('display', 'none');
	$tableClientBody.empty();
}

function showTableClient()
{
	$tableClient.css('display', '');
	$(tableClient).DataTable();
}

function addClientRows(result)
{
	for (var i = 0; i < result.length; i++) {
		dtTableClient.row.add({
			client_id: result[i]['client_id'],
		    health_fund_name: result[i]['health_fund_name'],
		    client_membership_no: result[i]['client_membership_no'],
		    client_patient_id: result[i]['client_patient_id'],
		    client_name: result[i]['client_name'],
		    client_contact_no: result[i]['client_contact_no']}).draw();
	}
}

function setClientRowSelection()
{
	$tableClientBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            dtTableClient.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableClientBody.on('dblclick', 'tr', function () {
		main_open_new_tab('../client/client-report.php?id=' + dtTableClient.row('.selected').id());
		//main_alert_message(dtTableClient.row('.selected').id());
	});
}










