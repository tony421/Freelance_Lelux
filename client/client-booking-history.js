var $btnSearch;
var $radSearchName;
var $radSearchTel;
var $txtText;
var $tableBookingHistory;
var $tableBookingHistoryBody;
var dtTableBookingHistory;
var $dateStart, $dateEnd;

function initPage()
{
	main_ajax_success_hide_loading();
	
	$dateStart = $('#dateStartInput');
	$dateEnd = $('#dateEndInput');
	$btnSearch = $('#btnSearch');
	$radSearchName = $('#radSearchName');
	$radSearchTel = $('#radSearchTel');
	$txtText = $('#txtText');
	$txtText.focus();
	
	$tableBookingHistory = $('#tableBookingHistory');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	dtTableBookingHistory = $tableBookingHistory.DataTable({
		language: {
		    info: "Showing _START_ to _END_ of _TOTAL_ clients",
			//info: "Showing _TOTAL_ clients",
			infoEmpty: "",
		    lengthMenu: "Show _MENU_ bookings / page"
		},
		dom: '<"top"lifp<"clear">>rt<"bottom"ip<"clear">>',
		lengthMenu: [ [10, 25, 50], [10, 25, 50] ], // [[pageLength, -1], [lengthMenu, "All"]]
		paging: true,
		pagingType: "numbers",
		info: true,
		searching: false,
		ordering: true,
		order: [[1, 'asc']],
		rowId: 'booking_id',
		// Columns: booking_id, booking_name, booking_tel, booking_date, booking_time_in, booking_time_out, booking_remark, therapist_name, massage_type_name, booking_date_formated, booking_time_formated
		columns: [
		    { data: "booking_id", title: "Booking ID", visible: false, orderable: false },
		    { data: "booking_name", title: "For order", visible: false, orderable: false },
		    { data: "booking_time_in", title: "For order", visible: false, orderable: false },
		    { data: "booking_name", title: "Booking Name (Tel)", orderable: true, orderData: [1]
		    	, render: function ( data, type, row ) { return data + "<br>(" + row['booking_tel'] + ")"; } },
            { data: "booking_date", title: "Date", orderable: true, orderData: [2]
		    	, render: function ( data, type, row ) { 
		    		return row['booking_date_formated']; 
	    		} 
            },
            { data: "booking_time_formated", title: "Time (Minutes)", orderable: false
		    	, render: function ( data, type, row ) {
		    		var minutes = moment(row['booking_time_out']).diff(moment(row['booking_time_in']), 'minutes');
		    		return data + " (" + minutes + ")"; 
	    		} 
            },
            { data: "booking_remark", title: "Remark", orderable: false },
            { data: "therapist_name", title: "Therapist", orderable: false },
            { data: "massage_type_name", title: "Massage Type", orderable: false },
        ]
	});
	$tableBookingHistoryBody = $('#tableBookingHistory tbody');
	
	$txtText.keypress(function(e){
		if (e.which == 13) {
			$btnSearch.click();
			return false;
		}
	});
	$txtText.focus(function(){
		$(this).select();
	});
	
	$btnSearch.click(function(){
		searchBooking();
	});
	
	$('input[type=radio][name=searchby]').change(function() {
        if ($(this).val() == '2') { // search by "Phone No."
        	$txtText.inputmask('9999-999-999');
        } else {
        	$txtText.inputmask('remove');
        }
        
        $txtText.focus();
	});
	
	initDatePickers();
}

function initDatePickers() {
    initDatepickerInput($dateStart, DATE_PICKER_SHORT_FORMAT);
    initDatepickerInput($dateEnd, DATE_PICKER_SHORT_FORMAT);

    var lastMonth = new Date();
    lastMonth.setDate(lastMonth.getDate() - 7);
    
    setDatepickerInputValue($dateStart, lastMonth);
    setDatepickerInputValue($dateEnd, new Date());

    $($dateStart).datepicker().on('changeDate', function(e){
	    resetDateEnd(getDatepickerValue(this), getDatepickerValue($dateEnd));
	});

    $($dateEnd).datepicker().on('changeDate', function(e){
	    resetDateStart(getDatepickerValue($dateStart), getDatepickerValue(this));
	});
}

function resetDateEnd(dateStart, dateEnd) {
    dateStart = new Date(dateStart);
    dateEnd = new Date(dateEnd);

    if (dateStart > dateEnd) {
	    setDatepickerInputValue($dateEnd, dateStart);
    }
}

function resetDateStart(dateStart, dateEnd) {
    dateStart = new Date(dateStart);
    dateEnd = new Date(dateEnd);

    if (dateEnd < dateStart) {
	    setDatepickerInputValue($dateStart, dateEnd);
    }
}

function searchBooking()
{
	var searchCon = getSearchCondition();
	console.info('searchBooking()', searchCon);
	main_request_ajax('client-boundary.php', 'GET_BOOKING_HISTORY', searchCon, onRequestDone);
}

function onRequestDone(response)
{
	clearTableBooking();
	
	if (response.success) {
		addBookingRows(response.result);
		setBookingRowSelection();
		//showTableBooking();
	}
	else {
		main_alert_message(response.msg, function(){$txtText.focus();});
	}
}

function getSearchCondition()
{
	var search = {
		date_start: getDatepickerValue($dateStart),
		date_end: getDatepickerValue($dateEnd),
		search_name: $radSearchName.is(':checked'),
		search_tel: $radSearchTel.is(':checked'),
		search_text: $txtText.val()
	};
	
	return search;
}

function clearTableBooking()
{
	dtTableBookingHistory.rows().remove().draw();
	$tableBookingHistoryBody.unbind(); // unbind events to prevent duplicate events
}

function hideTableBooking()
{
	$tableBookingHistory.css('display', 'none');
	$tableBookingHistoryBody.empty();
}

function showTableBooking()
{
	$tableBookingHistory.css('display', '');
	$(tableBookingHistory).DataTable();
}

function addBookingRows(result)
{
	// Columns: booking_id, booking_name, booking_tel, booking_date, booking_time_in, booking_time_out, booking_remark, therapist_name, massage_type_name, booking_date_formated, booking_time_formated	
	for (var i = 0; i < result.length; i++) {
		dtTableBookingHistory.row.add({
			booking_id: result[i]['booking_id'],
			booking_name: result[i]['booking_name'],
			booking_tel: result[i]['booking_tel'],
			booking_date: result[i]['booking_date'],
			booking_time_in: result[i]['booking_time_in'],
			booking_time_out: result[i]['booking_time_out'],
			booking_remark: result[i]['booking_remark'],
			therapist_name: result[i]['therapist_name'],
			massage_type_name: result[i]['massage_type_name'],
			booking_date_formated: result[i]['booking_date_formated'],
			booking_time_formated: result[i]['booking_time_formated']
		}).draw();
	}
}

function setBookingRowSelection()
{
	$tableBookingHistoryBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	dtTableBookingHistory.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
}
