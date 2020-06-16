var $dateInput, $txtDate;
var $btnCommissionReport, $btnIncomeReport;

// Booking Details
var _bookingInfo;
var $modalBookingDetails, $btnAddBooking;
var $lblBookingTime, $lblBookingClientAmt, $lblBookingRoom, $lblBookingTherapist;
var $txtBookingClientName, $txtBookingClientTel, $txtBookingRemark;

// Booking Queue
var _bookings, _bookingItem, _bookingQueueTherapists, _bookingQueueRooms;
var $modalBookingQueue, $btnBookingQueueRecord, $btnBookingQueueUpdate, $btnBookingQueueDelete;
var $lblBookingQueueClient, $lblBookingQueueAmount, $lblBookingQueueTime, $lblBookingQueueTherapist;
var $txtBookingQueueMinutes, $txtBookingQueueTimeIn, $txtBookingQueueTimeOut;
var $txtBookingQueueClientName, $txtBookingQueueClientTel, $txtBookingQueueRemark;
var $tableBookingQueueTherapist, _dtTableBookingQueueTherapist;
var $tableBookingQueueRoom, _dtTableBookingQueueRoom;

// Massage Record
var RECORD_ADD_MODE_QUEUE = 'ADD_MODE_QUEUE';
var RECORD_ADD_MODE_BOOKING = 'ADD_MODE_BOOKING';

var _massageTypes, _recordInfo, _bookingInfo, _recordAddMode;
var $modalMassageRecord, $btnAddRecord;
var $lblRecordTime, $lblRecordTherapist, $lblRecordRoom;
var $ddlRecordMassageType, $cbRecordRequested, $cbRecordPromo;
var $txtRecordStamp, $txtRecordCash, $txtRecordCredit, $txtRecordHICAPS, $txtRecordVoucher, $txtRecordTotal;
var $txtRecordMinutes, $txtRecordTimeIn, $txtRecordTimeOut;

function initPage()
{	
	main_ajax_success_hide_loading();
	
	$dateInput = $("#dateInput");
	$txtDate = $("#txtDate");
	
	$btnCommissionReport = $('#btnCommissionReport');
	$btnIncomeReport = $('#btnIncomeReport');
	
	$btnCommissionReport.click(function(){
		date = getSelectedDailyRecordDate();
		main_open_new_tab('../report/report.php?report_type=COMMISSION_DAILY_REPORT&date=' + date);
	});
	
	$btnIncomeReport.click(function(){
		date = getSelectedDailyRecordDate();
		main_open_new_tab('../report/report.php?report_type=INCOME_DAILY_REPORT&date=' + date);
	});
	
	$txtDate.change(function(){		
		frameName = $('.panel-heading .nav li.active a').prop("name");
		main_get_frame_content(frameName).updateFrameContent();
	});
	
	initDatepicker(new Date());
	
	$('.panel-heading .nav a').click(function(){
		currentFrame = frameName = $('.panel-heading .nav li.active a').prop("name");
		frameName = $(this).prop("name");
		
		if (frameName != currentFrame) {
			main_get_frame_content(currentFrame).clearFrameEditMode();
			main_get_frame_content(frameName).updateFrameContent();
		}
		
		//alert(main_get_frame_content(frameName).document.body.scrollHeight);
		//alert($(main_get_frame_content(frameName).document).find('html').height());
	});
	
	$('#tabDailyRecords').responsiveTabs();
	
	// START - Booking Details
	//
	$modalBookingDetails = $('#modalBookingDetails');
	$lblBookingTime = $('#lblBookingTime');
	$lblBookingClientAmt = $('#lblBookingClientAmt');
	$lblBookingRoom = $('#lblBookingRoom');
	$lblBookingTherapist = $('#lblBookingTherapist');
	$txtBookingClientName = $('#txtBookingClientName');
	$txtBookingClientTel = $('#txtBookingClientTel');
	$txtBookingRemark = $('#txtBookingRemark');
	$btnAddBooking = $('#btnAddBooking');
	
	$txtBookingClientTel.inputmask('9999-999-999');
	
	$txtBookingClientName.keypress(function(e){
		if (e.which == 13) {
			$txtBookingClientTel.focus();
			return false;
		}
	});
	$txtBookingClientTel.keypress(function(e){
		if (e.which == 13) {
			$txtBookingRemark.focus();
			return false;
		}
	});
	$txtBookingRemark.keypress(function(e){
		if (e.which == 13) {
			$btnAddBooking.click();
			return false;
		}
	});
	
	$btnAddBooking.click(addBooking);
	
	$modalBookingDetails.on('shown.bs.modal', function (e) {
		clearBookingInputs();
	})
	//
	// END - Booking Details
	
	// START - Booking Queue
	//
	$modalBookingQueue = $('#modalBookingQueue');
	$lblBookingQueueClient = $('#lblBookingQueueClient');
	$lblBookingQueueAmount = $('#lblBookingQueueAmount');
	$lblBookingQueueTime = $('#lblBookingQueueTime');
	$lblBookingQueueTherapist = $('#lblBookingQueueTherapist');
	$txtBookingQueueMinutes = $('#txtBookingQueueMinutes');
	$txtBookingQueueTimeIn  = $('#txtBookingQueueTimeIn');
	$txtBookingQueueTimeOut = $('#txtBookingQueueTimeOut');
	$txtBookingQueueClientName = $('#txtBookingQueueClientName');
	$txtBookingQueueClientTel = $('#txtBookingQueueClientTel');
	$txtBookingQueueRemark = $('#txtBookingQueueRemark');
	$btnBookingQueueRecord = $('#btnBookingQueueRecord');
	$btnBookingQueueUpdate = $('#btnBookingQueueUpdate');
	$btnBookingQueueDelete = $('#btnBookingQueueDelete');
	
	$txtBookingQueueClientTel.inputmask('9999-999-999');
	
	initTouchSpinInput($txtBookingQueueMinutes, 10, 1000, 60, 5);
	$txtBookingQueueMinutes.change(calBookingQueueTimeOut);
	
	initTimeInput($txtBookingQueueTimeIn);
	$txtBookingQueueTimeIn.change(calBookingQueueTimeOut);
	setBookingQueueTimeIn();
	
	initTableBookingQueue();
	
	$btnBookingQueueRecord.click(checkBookingQueueAvailabilityForRecord);
	$btnBookingQueueUpdate.click(checkBookingQueueAvailabilityForUpdate);
	$btnBookingQueueDelete.click(deleteBookingQueueBooking);
	//
	// END - Booking Queue
	
	// START - Massage Record
	//
	$modalMassageRecord = $('#modalMassageRecord');
	$lblRecordTime = $('#lblRecordTime');
	$lblRecordTherapist = $('#lblRecordTherapist');
	$lblRecordRoom = $('#lblRecordRoom');
	$ddlRecordMassageType = $('#ddlRecordMassageType');
	$cbRecordRequested = $('#cbRecordRequested');
	$cbRecordPromo = $('#cbRecordPromo');
	$txtRecordStamp = $('#txtRecordStamp');
	$txtRecordCash = $('#txtRecordCash');
	$txtRecordCredit = $('#txtRecordCredit');
	$txtRecordHICAPS = $('#txtRecordHICAPS');
	$txtRecordVoucher = $('#txtRecordVoucher');
	$txtRecordTotal = $('#txtRecordTotal');
	$txtRecordMinutes = $('#txtRecordMinutes');
	$txtRecordTimeIn  = $('#txtRecordTimeIn');
	$txtRecordTimeOut = $('#txtRecordTimeOut');
	$btnAddRecord = $('#btnAddRecord'); 
	
	initTouchSpinInput($txtRecordStamp, 0, 600, 0, 15);
	initMoneyInput($txtRecordCash, 0, 1000.99);
	initMoneyInput($txtRecordCredit, 0, 1000.99);
	initMoneyInput($txtRecordHICAPS, 0, 1000.99);
	initMoneyInput($txtRecordVoucher, 0, 1000.99);
	initMoneyInput($txtRecordTotal, 0, 1000.99);
	
	$txtRecordCash.change(calRecordPaidTotal);
	$txtRecordCredit.change(calRecordPaidTotal);
	$txtRecordHICAPS.change(calRecordPaidTotal);
	$txtRecordVoucher.change(calRecordPaidTotal);
	
	initTouchSpinInput($txtRecordMinutes, 10, 1000, 60, 5);
	$txtRecordMinutes.change(calRecordTimeOut);
	
	initTimeInput($txtRecordTimeIn);
	$txtRecordTimeIn.change(calRecordTimeOut);
	setRecordTimeIn();
	
	initMassageTypes();
	
	$btnAddRecord.click(addMassageRecord);
	//
	// END - Massage Record
}

function initDatepicker(date)
{
	initDatepickerInput($dateInput);
	
	// if the date var is set
	if (typeof(date) !== 'undefined')
		setDatepickerInputValue($dateInput, date);
}

function destroyDatepicker() {
	destroyDatepickerInput($dateInput);
}

function getSelectedDailyRecordDate()
{
	var selectedDate = getDatepickerValue($dateInput);
	
	//console.log('user: ' + $('#hiddenUserName').val().toLowerCase());
	//console.log('selected: ' + moment(selectedDate));
	//console.log('now: ' + moment(moment().format(MOMENT_DATE_FORMAT)));
	
	if ($('#hiddenUserName').val().toLowerCase() == 'paris') {
		if (moment(selectedDate) < moment())
			selectedDate =  moment().format(MOMENT_DATE_FORMAT);
	}
	
	//console.log('returning: ' + selectedDate);
	return selectedDate;
}

function clearFramesEditMode()
{
	main_get_frame_content('frameMassage').clearFramesEditMode();
	main_get_frame_content('frameSale').clearFramesEditMode();
	main_get_frame_content('frameReception').clearFramesEditMode();
}

function switchTab(tabName) {
	$('#tabDailyRecords a[name="' + tabName + '"]').tab('show');
}

function showMassageRecordDetails(recordID) {
	switchTab('frameMassage');
	main_get_frame_content('frameMassage').showMassageRecordDetails(recordID);
}

//START - Booking Details
//
function showBookingDetails(minutes, date, start, end, clientAmt, singleRoomAmt, doubleRoomAmt, therapists, massageTypes) {
	setBookingTime(minutes, start, end);
	setBookingClient(clientAmt);
	setBookingRoom(singleRoomAmt, doubleRoomAmt);
	setBookingTherapist(therapists, massageTypes);
	
	_bookingInfo = {
			date: date
			, minutes: minutes
			, time_in: start
			, time_out: end
			, client_amount: clientAmt
			, single_room_amount: singleRoomAmt
			, double_room_amount: doubleRoomAmt
			, therapists: therapists
			, massage_types: massageTypes
	};
	
	$modalBookingDetails.modal('show');
}
function setBookingTime(minutes, start, end) {
	var text = '<span class="text-mark">{0}</span> min from <span class="text-mark">{1}</span> to <span class="text-mark">{2}</span>';
	
	var startTime = formatTime(start);
	var endTime = formatTime(end);
	
	$lblBookingTime.html(text.format(minutes, startTime, endTime));
}
function setBookingClient(clientAmt) {
	if (clientAmt > 1) {
		$lblBookingClientAmt.html('<span class="text-mark">' + clientAmt + '</span> people');
	} else {
		$lblBookingClientAmt.html('<span class="text-mark">' + clientAmt + '</span> person');
	}
}
function setBookingRoom(singleRoomAmt, doubleRoomAmt) {
	var text = '';
	
	if (singleRoomAmt > 0)
		if (singleRoomAmt > 1)
			text += '<span class="text-mark">' + singleRoomAmt + '</span> single rooms';
		else 
			text += '<span class="text-mark">' + singleRoomAmt + '</span> single room';
	
	if (singleRoomAmt > 0 && doubleRoomAmt > 0)
		text += ', ';
	
	if (doubleRoomAmt > 0)
		if (doubleRoomAmt > 1)
			text += '<span class="text-mark">' + doubleRoomAmt + '</span> double rooms';
		else 
			text += '<span class="text-mark">' + doubleRoomAmt + '</span> double room';
	
	$lblBookingRoom.html(text);
}
function setBookingTherapist(therapists, massageTypes) {
	var text = '';
	
	for (var i = 0; i < therapists.length; i++) {
		if (text.length)
			text += '<br>';
		
		text += (i + 1) + ') <span class="text-mark">' + massageTypes[i]['massage_type_name'] + '</span> with <span class="text-mark">' + therapists[i]['therapist_name'] + '</span>';
	}
	
	$lblBookingTherapist.html(text);
}

function validateBookingInputs() {
	if ($txtBookingClientName.val().trim().length) {
		if ($txtBookingClientTel.inputmask("isComplete")) {
			return true;
		} else {
			main_alert_message('Please enter "Client Tel"!', function(){ $txtBookingClientTel.focus();});
		}
	} else {
		main_alert_message('Please enter "Client Name"!', function(){ $txtBookingClientName.focus();});
	}
}
function clearBookingInputs() {
	$txtBookingClientName.val('');
	$txtBookingClientTel.val('');
	$txtBookingRemark.val('');
	
	$txtBookingClientName.focus();
}

function addBooking() {
	if (validateBookingInputs()) {
		main_request_ajax('../booking/booking-boundary.php', 'ADD_BOOKING', getBookingInfo(), onAddBookingDone);
	}
}
function onAddBookingDone(response) {
	if (response.success) {
		var moveTo = response.result['booking_move_to'];		
		$modalBookingDetails.modal('hide');		
		
		main_info_message(response.msg
			, function(){ main_get_frame_content('frameBooking').onAddBookingDone(moveTo); 
		});
	} else {
		main_alert_message(response.msg);
	}
}
function getBookingInfo() {
	_bookingInfo['client_name'] = $txtBookingClientName.val();
	_bookingInfo['client_tel'] = $txtBookingClientTel.val();
	_bookingInfo['remark'] = $txtBookingRemark.val().trim();
	
	return _bookingInfo;
}
//
// END - Boking Details

// START - Boking Queue
//
function showBookingQueue(bookings, bookingItem) {
	_bookings = bookings;
	_bookingItem = bookingItem;
	
	setBookingQueueClient(bookingItem['booking_name'], bookingItem['booking_tel'], bookingItem['booking_group_total'], bookingItem['booking_group_item_no']);
	setBookingQueueAmount(bookingItem['booking_client'], bookingItem['single_room_amount'], bookingItem['double_room_amount'], bookingItem['booking_group_total'], bookingItem['booking_group_item_no']);
	setBookingQueueTime(bookingItem['booking_time_in'], bookingItem['booking_time_out']);
	setBookingQueueTherapist(bookingItem['massage_type_name'], bookingItem['therapist_name']);
	setBookingQueueRemark(bookingItem['booking_remark']);
	
	var searchInfo = {
		booking_id: bookingItem['booking_id']
		, booking_item_id: bookingItem['booking_item_id']
	};
	
	searchBookingQueue(searchInfo);
}
function searchBookingQueue(searchInfo) {
	main_request_ajax('../queueing/queueing-boundary.php', 'SEARCH_QUEUE_FOR_BOOKING', searchInfo, onSearchBookingQueue);
}
function onSearchBookingQueue(response) {
	if (response.success) {
		var result = response.result;
		_bookingQueueTherapists = result['therapists'];
		_bookingQueueRooms = result['rooms'];
		
		clearTableBookingQueue();
		bindTableBookingQueueTherapist(result['therapists'], _bookingItem['therapist_id']);
		bindTableBookingQueueRoom(result['rooms']);
		setTableBookingQueueRowsSelection();
		
		$modalBookingQueue.modal('show');
	} else {
		main_alert_message(response.msg);
	}
}

function setBookingQueueClient(clientName, clientTel, groupTotal, groupItemNo) {
	var text;
	var amountText;
	
	/*
	 * change: the group amount to be set in setBookingQueueAmount()
	if (groupTotal > 0) 
		text = '<span class="text-mark">{0}</span>, {1} (<span class="text-mark">{2}</span> of <span class="text-mark">{3}</span>)';
	else
		text = '<span class="text-mark">{0}</span>, {1}';
	*/
	
	$txtBookingQueueClientName.val(clientName);
	$txtBookingQueueClientTel.val(clientTel);
}
function setBookingQueueAmount(clientAmt, singleRoomAmt, doubleRoomAmt, groupTotal, groupItemNo) {
	var format = '<span class="text-mark">{0}</span> {1} ';
	var textGroupAmt = '';
	var textClientAmt = '';
	var textRoomAmt = '';
	
	if (groupTotal > 0)
		textGroupAmt = '(<span class="text-mark">{0}</span> of <span class="text-mark">{1}</span>) '.format(groupItemNo, groupTotal);
	
	if (clientAmt > 1)
		textClientAmt = format.format(clientAmt, 'people');
	else
		textClientAmt = format.format(clientAmt, 'person');
	
	if (singleRoomAmt > 1)
		textRoomAmt += format.format(singleRoomAmt, 'single rooms');
	else if (singleRoomAmt > 0)
		textRoomAmt += format.format(singleRoomAmt, 'single room');
	
	if (doubleRoomAmt > 1)
		textRoomAmt += format.format(doubleRoomAmt, 'double rooms');
	else if (doubleRoomAmt > 0)
		textRoomAmt += format.format(doubleRoomAmt, 'double room');
		
	$lblBookingQueueAmount.html(textGroupAmt + textClientAmt + textRoomAmt);
}
function setBookingQueueTime(start, end) {
	var text = '<span class="text-mark">{0}</span> minutes from <span class="text-mark">{1}</span> to <span class="text-mark">{2}</span>';
	
	var minutes = moment(end).diff(moment(start), 'minutes');
	var startTime = formatTime(start);
	var endTime = formatTime(end);
	
	//$lblBookingQueueTime.html(text.format(minutes, startTime, endTime));
	
	$txtBookingQueueMinutes.val(minutes);
	setBookingQueueTimeIn(startTime);
}
function setBookingQueueTherapist(massageType, therapistName) {
	var text = '<span class="text-mark">{0}</span> with <span class="text-mark">{1}</span>';
	
	$lblBookingQueueTherapist.html(text.format(massageType, therapistName));
}
function setBookingQueueRemark(remark) {
	$txtBookingQueueRemark.val(remark);
}


function initTableBookingQueue() {
	$tableBookingQueueTherapist = $('#tableBookingQueueTherapist');
	$tableBookingQueueRoom = $('#tableBookingQueueRoom');
	
	_dtTableBookingQueueTherapist = $tableBookingQueueTherapist.DataTable({
		scrollY: 150,
		language: {
		      emptyTable: "No therapists available"
		},
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: "therapist_id",
		columns: [
		          { data: "therapist_name", width: "100%", className: "text-center" }
		]
	});
	
	_dtTableBookingQueueRoom = $tableBookingQueueRoom.DataTable({
		scrollY: 150,
		language: {
		      emptyTable: "No rooms available"
		},
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: "room_no",
		columns: [
		          { data: "room_desc" , width: "100%", className: "text-center" }
		]
	});
}
function clearTableBookingQueue() {
	_dtTableBookingQueueTherapist.rows().remove().draw();
	_dtTableBookingQueueRoom.rows().remove().draw();
	unbindTableBookingQueueRowsSelection();
}
function unbindTableBookingQueueRowsSelection()
{
	$tableBookingQueueTherapist.unbind(); // unbind events to prevent duplicate events
	$tableBookingQueueRoom.unbind();
}
function setTableBookingQueueRowsSelection()
{
	$tableBookingQueueTherapist.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableBookingQueueTherapist.$('tr.selected').removeClass('selected');
        	$(this).addClass('selected');
        }
    });
	
	$tableBookingQueueRoom.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableBookingQueueRoom.$('tr.selected').removeClass('selected');
        	$(this).addClass('selected');
        }
    });
}

function bindTableBookingQueueTherapist(therapists, requestedTherapistID) {
	for (var i = 0; i < therapists.length; i++) {
		if (therapists[i]['therapist_available'] == 1) {
			_dtTableBookingQueueTherapist.row.add({
				row_no: therapists[i]['row_no']
				, therapist_id: therapists[i]['therapist_id']
				, therapist_name: therapists[i]['therapist_name']
				, therapist_timeout: therapists[i]['therapist_timeout']
			}).draw();
			
			// auto select therapist if it is requested
			if (therapists[i]['therapist_id'] == requestedTherapistID) {
				//console.log(_dtTableBookingQueueTherapist.row(':eq(' + i + ')').data());
				
				// ** .select() cannot be used because it needs the extension!!
				//_dtTableBookingQueueTherapist.row(i).select();
				
				// select the row which is just added
				_dtTableBookingQueueTherapist.$('tr:last').addClass('selected');
			}
		}
	}
}
function bindTableBookingQueueRoom(rooms) {
	for (var i = 0; i < rooms.length; i++) {
		if (rooms[i]['room_available'] == 1 || rooms[i]['room_reserved'] == 1) {
			_dtTableBookingQueueRoom.row.add({
				room_no: rooms[i]['room_no']
				, room_desc: rooms[i]['room_desc'] + rooms[i]['room_remark']
			}).draw();
		}
	}
}

function checkBookingQueueAvailabilityForRecord() {
	if (validateBookingQueueInputs()) {
		searchInfo = getBookingQueueSearchInfo();
		
		main_request_ajax('../queueing/queueing-boundary.php', 'SEARCH_AVAILABILITY_FOR_BOOKING', searchInfo, onCheckBookingQueueAvailabilityForRecordDone);
	}
}
function onCheckBookingQueueAvailabilityForRecordDone(response) {
	if (response.success) {
		result = response.result;
		isBookingAvailable = result['available'];
		
		// able to add a record regardless availability
		addBookingQueueRecord();
		/*
		if (isBookingAvailable) {
			addBookingQueueRecord();
		} else {						
			main_confirm_message('The new record will overlap other records! Do you still want to add it?'
				, addBookingQueueRecord
				, function(){}, 1);
		}
		*/
	} 
	else
		main_alert_message(response.msg);
}
function addBookingQueueRecord() {	
	var selectedTherapistID = _dtTableBookingQueueTherapist.row('.selected').id(); // can also use .index()
	var selectedRoomNo = _dtTableBookingQueueRoom.row('.selected').id();
	
	if (typeof(selectedTherapistID) === 'undefined'
		|| typeof(selectedRoomNo) === 'undefined') {
		main_alert_message('Please select <strong>therapist</strong> and <strong>room</strong>!');
	} else {
		var selectedTherapist = getBookingQueueSelectedTherapist(selectedTherapistID);
		var selectedRoom = getBookingQueueSelectedRoom(selectedRoomNo);
		var recordInfo = getBookingQueueRecordInfo(selectedTherapist, selectedRoom);
		
		$modalBookingQueue.modal('hide');
		setTimeout(function(){ showMassageRecord(recordInfo, _bookingItem); }, 400);
	}
}
function getBookingQueueRecordInfo(selectedTherapist, selectedRoom) {
	var recordInfo = {
		date: _bookingItem['booking_date']
		, minutes: $txtBookingQueueMinutes.val()
		, time_in: getBookingQueueTimeIn()
		, time_out: getBookingQueueTimeOut()
		, massage_type_id: _bookingItem['massage_type_id']
		, therapist_id: selectedTherapist['therapist_id']
		, therapist_name: selectedTherapist['therapist_name']
		, room_no: selectedRoom['room_no']
	};
	
	return recordInfo;
}
function getBookingQueueSelectedTherapist(therapistID) {
	for (var i = 0; i < _bookingQueueTherapists.length; i++) {
		if (_bookingQueueTherapists[i]['therapist_id'] == therapistID)
			return _bookingQueueTherapists[i];
	}
}
function getBookingQueueSelectedRoom(roomNo) {
	for (var i = 0; i < _bookingQueueRooms.length; i++) {
		if (_bookingQueueRooms[i]['room_no'] == roomNo)
			return _bookingQueueRooms[i];
	}
}

function validateBookingQueueInputs() {
	if ($txtBookingQueueClientName.val().trim().length) {
		if ($txtBookingQueueClientTel.inputmask("isComplete")) {
			if ($txtBookingQueueMinutes.val().trim().length) {
				if (isTimeInputComplete($txtBookingQueueTimeIn)) {
					return true;
				} else {
					main_alert_message('Please enter "Time-In"', function(){ $txtBookingQueueTimeIn.focus();});
				}
			} else {
				main_alert_message('Please enter "Minutes"', function(){ $txtBookingQueueMinutes.focus();});
			}
		} else {
			main_alert_message('Please enter "Client Tel"', function(){ $txtBookingQueueClientTel.focus();});
		}
	} else {
		main_alert_message('Please enter "Client Name"', function(){ $txtBookingQueueClientName.focus();});
	}
}
function getBookingQueueSearchInfo() {
	var searchInfo = {
		booking_id: _bookingItem['booking_id']
		, date: _bookingItem['booking_date']
		, minutes: $txtBookingQueueMinutes.val()
		, time_in: getBookingQueueTimeIn()
		, time_out: getBookingQueueTimeOut()
		, client_amount: _bookingItem['booking_client']
		, single_room_amount: _bookingItem['single_room_amount']
		, double_room_amount: _bookingItem['double_room_amount']
		, therapists: getBookingQueueBookedTherapists(_bookingItem['booking_id'])
	};
	
	return searchInfo;
}
function getBookingQueueBookedTherapists(bookingID) {
	therapists = [];
	
	for (var i = 0; i < _bookings.length; i++) {
		if (_bookings[i]['booking_id'] == bookingID) {
			therapist = {
				therapist_id: _bookings[i]['therapist_id']
				, therapist_name: _bookings[i]['therapist_name']
			};
			
			therapists.push(therapist);
		}
	}
	
	return therapists;
}
function checkBookingQueueAvailabilityForUpdate() {
	if (validateBookingQueueInputs()) {
		searchInfo = getBookingQueueSearchInfo();
		
		main_request_ajax('../queueing/queueing-boundary.php', 'SEARCH_AVAILABILITY_FOR_BOOKING', searchInfo, onCheckBookingQueueAvailabilityForUpdateDone);
	}
}
function onCheckBookingQueueAvailabilityForUpdateDone(response) {
	if (response.success) {
		result = response.result;
		isBookingAvailable = result['available'];
		
		if (isBookingAvailable) {
			updateBookingQueueRecord();
		} else {
			var msg = result['remark'];
			msg += ' for <span class="text-mark">{0}</span> client from <span class="text-mark">{1}</span> to <span class="text-mark">{2}</span> (<span class="text-mark">{3}</span> minutes)';
			
			main_alert_message(msg.format(result['client_amount'], formatTime(result['time_in']), formatTime(result['time_out']), result['minutes']));
		}
	} 
	else
		main_alert_message(response.msg);
}

function getBookingQueueBookingInfoForUpdate() {
	var bookingInfo = {
			booking_id: _bookingItem['booking_id']
			, minutes: $txtBookingQueueMinutes.val()
			, time_in: getBookingQueueTimeIn()
			, time_out: getBookingQueueTimeOut()
			, client_name: $txtBookingQueueClientName.val()
			, client_tel: $txtBookingQueueClientTel.val()
			, remark : $txtBookingQueueRemark.val().trim()
		};
	
	return bookingInfo;
}
function updateBookingQueueRecord() {
	main_confirm_message('Do you want to UPDATE the booking?'
		, function(){
			main_request_ajax('../booking/booking-boundary.php', 'UPDATE_BOOKING'
				, getBookingQueueBookingInfoForUpdate()
				, onUpdateBookingQueueBookingDone);
		}
		, function(){}, 0);
}
function onUpdateBookingQueueBookingDone(response) {
	if (response.success) {
		var moveTo = response.result['booking_move_to'];
		main_get_frame_content('frameBooking').onUpdateBookingDone(moveTo);
		
		$modalBookingQueue.modal('hide');
	} else {
		main_alert_message(response.msg);
	}
}

function deleteBookingQueueBooking() {
	main_confirm_message('Do you want to DELETE the booking?'
		, function(){
			main_request_ajax('../booking/booking-boundary.php', 'DELETE_BOOKING', _bookingItem['booking_id'], onDeleteBookingQueueBookingDone);
		}
		, function(){}, 1);
}
function onDeleteBookingQueueBookingDone(response) {
	if (response.success) {
		main_info_message(response.msg, function(){
			var moveTo = _bookingItem['booking_time_in'];
			main_get_frame_content('frameBooking').onDeleteBookingDone(moveTo);
		});
		
		$modalBookingQueue.modal('hide');
	} else {
		main_alert_message(response.msg);
	}
}

function setBookingQueueTimeIn(time) {
	time = typeof(time) === "undefined" ? currentTime() : time;
	
	setTimeInput($txtBookingQueueTimeIn, time);
	calBookingQueueTimeOut();
}
function calBookingQueueTimeOut() {
	if (isTimeInputComplete($txtBookingQueueTimeIn)) {
		timeIn = getTimeInput($txtBookingQueueTimeIn).split(":");
		minutes = $txtBookingQueueMinutes.val().trim().length ? $txtBookingQueueMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_12_FORMAT);
		$txtBookingQueueTimeOut.val(timeOut);
	}
	else {
		$txtBookingQueueTimeOut.val('');
	}
}
function getBookingQueueTimeIn()
{
	timeIn = getTimeInput($txtBookingQueueTimeIn);
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	return moment(date + ' ' + timeIn, MOMENT_DATE_TIME_FORMAT).format(MOMENT_DATE_TIME_FORMAT);
}
function getBookingQueueTimeOut()
{
	minutes = $txtBookingQueueMinutes.val().trim().length ? $txtBookingQueueMinutes.val() : 0;
	return moment(getBookingQueueTimeIn(), MOMENT_DATE_TIME_FORMAT).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}
//
// END - Boking Queue

// START - Massage Record
//
function initMassageTypes()
{
	main_request_ajax('../massagetype/massagetype-boundary.php', 'GET_MASSAGE_TYPE', {}, onInitMassageTypesDone);
}
function onInitMassageTypesDone(response)
{
	if (response.success) {
		_massageTypes = response.result;
		bindMassageTypeDDL(_massageTypes);
	}
}
function bindMassageTypeDDL(massageTypes)
{
	$ddlRecordMassageType.empty();
	
	$.each(massageTypes, function (i, massageType){
		option = "<option value='" + massageType['massage_type_id'] + "'>" + massageType['massage_type_name'] + "</option>";
		
		$ddlRecordMassageType.append(option);
	});
}

function showMassageRecord(recordInfo, bookingInfo) {
	_recordInfo = recordInfo;
	_bookingInfo = bookingInfo;
	
	if (typeof(bookingInfo) === 'undefined')
		_recordAddMode = RECORD_ADD_MODE_QUEUE;
	else
		_recordAddMode = RECORD_ADD_MODE_BOOKING;
	
	clearRecordInputs();
	setRecordTime(recordInfo['minutes'], recordInfo['time_in'], recordInfo['time_out']);
	setRecordTherapist(recordInfo['therapist_name'], bookingInfo['therapist_name']);
	setRecordRow(recordInfo['room_no']);
	setRecordMassageType(recordInfo['massage_type_id']);
	
	$modalMassageRecord.modal('show');
}

function clearRecordInputs() {
	$cbRecordRequested.prop('checked', false);
	$cbRecordPromo.prop('checked', false);
	$txtRecordStamp.val(0);
	setMoneyInputValue($txtRecordCash, 0);
	setMoneyInputValue($txtRecordCredit, 0);
	setMoneyInputValue($txtRecordHICAPS, 0);
	setMoneyInputValue($txtRecordVoucher, 0);
	setMoneyInputValue($txtRecordTotal, 0);
}
function setRecordTime(minutes, start, end) {
	var text = '<span class="text-mark">{0}</span> minutes (<span class="text-mark">{1}</span> to <span class="text-mark">{2}</span>)';
	
	var startTime = formatTime(start);
	var endTime = formatTime(end);
	
	//$lblRecordTime.html(text.format(minutes, startTime, endTime));
	
	$txtRecordMinutes.val(minutes);
	setRecordTimeIn(startTime);
	
	// Minutes and Time-In can only be edited in Adding Booking Mode
	if (_recordAddMode == RECORD_ADD_MODE_BOOKING) {
		$txtRecordMinutes.prop('disabled', '');
		$txtRecordTimeIn.prop('disabled', '');
	} else {
		$txtRecordMinutes.prop('disabled', 'disabled');
		$txtRecordTimeIn.prop('disabled', 'disabled');
	}
}
function setRecordTherapist(name, reqName) {
	$lblRecordTherapist.html('<span class="text-mark">' + name + '</span>');
	
	if (name == reqName)
		$cbRecordRequested.prop('checked', true);
}
function setRecordRow(roomNo) {
	$lblRecordRoom.html('<span class="text-mark">' + roomNo + '</span>');
}
function setRecordMassageType(massageTypeID) {
	if (massageTypeID != 0)
		$ddlRecordMassageType.val(massageTypeID);
}

function calRecordPaidTotal() {
	var cash = getMoneyInputValue($txtRecordCash);
	var credit = getMoneyInputValue($txtRecordCredit);
	var hicaps = getMoneyInputValue($txtRecordHICAPS);
	var voucher = getMoneyInputValue($txtRecordVoucher);
	
	setMoneyInputValue($txtRecordTotal, cash + credit + hicaps + voucher);
}

function validateRecordInputs() {
	if ($txtRecordMinutes.val().trim().length) {
		if (isTimeInputComplete($txtRecordTimeIn)) {
			return true;
		} else {
			main_alert_message('Please enter "Time-In"', function(){ $txtRecordTimeIn.focus();});
		}
	} else {
		main_alert_message('Please enter "Minutes"', function(){ $txtRecordMinutes.focus();});
	}
}
function getRecordInfo() {
	var recordInfo = {
		massage_record_id: ''
		, massage_record_date: _recordInfo['date']
		, massage_record_minutes: _recordInfo['minutes']
		, massage_record_time_in: _recordInfo['time_in']
		, massage_record_time_out: _recordInfo['time_out']
		, room_no: _recordInfo['room_no']
		, therapist_id: _recordInfo['therapist_id']
		, massage_type_id: $ddlRecordMassageType.val()
		, massage_record_requested: $cbRecordRequested.is(':checked')
		, massage_record_promotion: $cbRecordPromo.is(':checked')
		, massage_record_stamp: $txtRecordStamp.val()
		, massage_record_cash: getMoneyInputValue($txtRecordCash)
		, massage_record_credit: getMoneyInputValue($txtRecordCredit)
		, massage_record_hicaps: getMoneyInputValue($txtRecordHICAPS)
		, massage_record_voucher: getMoneyInputValue($txtRecordVoucher)
		, massage_record_commission: 0
		, massage_record_request_reward: 0
		, massage_type_commission: getSelectedMassageTypeCommission()
	};
	
	if (_recordAddMode == RECORD_ADD_MODE_BOOKING) {
		recordInfo['booking_id'] = _bookingInfo['booking_id'];
		recordInfo['booking_time_in'] = _bookingInfo['booking_time_in'];
		recordInfo['booking_item_id'] = _bookingInfo['booking_item_id'];
		recordInfo['booking_item_status'] = _bookingInfo['booking_item_status'];
		
		recordInfo['massage_record_minutes'] = $txtRecordMinutes.val();
		recordInfo['massage_record_time_in'] = getRecordTimeIn();
		recordInfo['massage_record_time_out'] = getRecordTimeOut();
	}
	
	return recordInfo;
}
function getSelectedMassageTypeCommission()
{
	var selectedIndex = $ddlRecordMassageType.prop('selectedIndex');
	
	if (typeof(selectedIndex) === 'undefined')
		return 0;
	else
		return _massageTypes[selectedIndex]['massage_type_commission'];
}

function addMassageRecord() {
	if (_recordAddMode == RECORD_ADD_MODE_QUEUE) {
		main_request_ajax('../massage/massage-boundary.php', 'ADD_RECORD_QUEUE', getRecordInfo(), onAddMassageRecordDone);
	} else if (_recordAddMode == RECORD_ADD_MODE_BOOKING) {
		if (validateRecordInputs()) {
			main_request_ajax('../massage/massage-boundary.php', 'ADD_RECORD_BOOKING', getRecordInfo(), onAddMassageRecordDone);
		}
	}
}
function onAddMassageRecordDone(response) {
	if (response.success) {
		if (_recordAddMode == RECORD_ADD_MODE_QUEUE) {
			main_info_message(response.msg, function(){
				main_get_frame_content('frameQueueing').onAddMassageRecordDone();
			});
		} else if (_recordAddMode == RECORD_ADD_MODE_BOOKING) {
			var moveTo = response.result['booking_move_to'];
			
			main_info_message(response.msg, function(){
				main_get_frame_content('frameBooking').onAddMassageRecordDone(moveTo);
			});
		}
		
		$modalMassageRecord.modal('hide');
	} else {
		main_alert_message(response.msg);
	}
}

function setRecordTimeIn(time) {
	time = typeof(time) === "undefined" ? currentTime() : time;
	
	setTimeInput($txtRecordTimeIn, time);
	calRecordTimeOut();
}
function calRecordTimeOut() {
	if (isTimeInputComplete($txtRecordTimeIn)) {
		timeIn = getTimeInput($txtRecordTimeIn).split(":");
		minutes = $txtRecordMinutes.val().trim().length ? $txtRecordMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_12_FORMAT);
		$txtRecordTimeOut.val(timeOut);
	}
	else {
		$txtRecordTimeOut.val('');
	}
}
function getRecordTimeIn()
{
	timeIn = getTimeInput($txtRecordTimeIn);
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	return moment(date + ' ' + timeIn, MOMENT_DATE_TIME_FORMAT).format(MOMENT_DATE_TIME_FORMAT);
}
function getRecordTimeOut()
{
	minutes = $txtRecordMinutes.val().trim().length ? $txtRecordMinutes.val() : 0;
	return moment(getRecordTimeIn(), MOMENT_DATE_TIME_FORMAT).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}
//
// END - Massage Record

function testTabClick()
{
	alert(typeof($dateInput.datepicker()));
}








