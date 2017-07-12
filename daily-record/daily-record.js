var _bookingInfo;

var $dateInput, $txtDate;
var $btnCommissionReport, $btnIncomeReport;
var $modalBookingDetails, $lblBookingTime, $lblBookingClientAmt, $lblBookingRoom, $lblBookingTherapist;
var $txtBookingClientName, $txtBookingClientTel, $btnAddBooking;

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
			main_get_frame_content(frameName).clearFrameEditMode();
			main_get_frame_content(frameName).updateFrameContent();
		}
		
		//alert(main_get_frame_content(frameName).document.body.scrollHeight);
		//alert($(main_get_frame_content(frameName).document).find('html').height());
	});
	
	// START - Booking
	//
	$modalBookingDetails = $('#modalBookingDetails');
	$lblBookingTime = $('#lblBookingTime');
	$lblBookingClientAmt = $('#lblBookingClientAmt');
	$lblBookingRoom = $('#lblBookingRoom');
	$lblBookingTherapist = $('#lblBookingTherapist');
	$txtBookingClientName = $('#txtBookingClientName');
	$txtBookingClientTel = $('#txtBookingClientTel');
	$btnAddBooking = $('#btnAddBooking');
	
	$txtBookingClientTel.inputmask('9999-999-999');
	
	$btnAddBooking.click(addBooking);
	
	$modalBookingDetails.on('shown.bs.modal', function (e) {
		clearBookingInputs();
	})
	//
	// END - Booking	
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
	return getDatepickerValue($dateInput);
}

function clearFramesEditMode()
{
	main_get_frame_content('frameMassage').clearEditMode();
	main_get_frame_content('frameSale').clearEditMode();
	main_get_frame_content('frameReception').clearEditMode();
}

//START - Booking
//
function showBookingDetails(minutes, date, start, end, clientAmt, singleRoomAmt, doubleRoomAmt, therapists) {
	setBookingTime(minutes, start, end);
	setBookingClient(clientAmt);
	setBookingRoom(singleRoomAmt, doubleRoomAmt);
	setBookingTherapist(therapists);
	
	_bookingInfo = {
			date: date 
			, time_in: start
			, time_out: end
			, client_amount: clientAmt
			, single_room_amount: singleRoomAmt
			, double_room_amount: doubleRoomAmt
			, therapists: therapists
	};
	
	$modalBookingDetails.modal('show');
}
function setBookingTime(minutes, start, end) {
	var text = '<span class="text-mark">{0}</span> minutes from <span class="text-mark">{1}</span> to <span class="text-mark">{2}</span>';
	var startTime = moment(start).format(MOMENT_TIME_FORMAT);
	var endTime = moment(end).format(MOMENT_TIME_FORMAT);
	
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
function setBookingTherapist(therapists) {
	var text = '';
	
	for (var i = 0; i < therapists.length; i++) {
		if (text.length)
			text += ', ';
		
		text += '<span class="text-mark">' + therapists[i]['therapist_name'] + '</span>';
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
		
		main_alert_message(response.msg
			, function(){ main_get_frame_content('frameBooking').onAddBookingDone(moveTo); 
		});
	} else {
		main_alert_message(response.msg);
	}
}
function getBookingInfo() {
	_bookingInfo['client_name'] = $txtBookingClientName.val();
	_bookingInfo['client_tel'] = $txtBookingClientTel.val();
	
	return _bookingInfo;
}
//
// END - Boking

function testTabClick()
{
	alert(typeof($dateInput.datepicker()));
}








