var $txtMinutes, $txtClient, $txtTimeIn, $txtTimeOut;
var $btnSearch, $btnRecord;

var $alertAvailability, $alertUnavailability;
var _searchResult;

var $tableTherapist, _dtTableTherapist;
var $tableRoom, _dtTableRoom;

function initPage() {
	main_ajax_success_hide_loading();
	
	$txtMinutes = $('#txtMinutes');
	$txtClient = $('#txtClient');
	$txtTimeIn = $('#txtTimeIn');
	$txtTimeOut = $('#txtTimeOut');
	$btnSearch = $('#btnSearch');
	$btnRecord = $('#btnRecord');
	$alertAvailability = $('#alertAvailability');
	$alertUnavailability = $('#alertUnavailability');
	
	initTouchSpinInput($txtMinutes, 10, 1000, 60, 5);
	$txtMinutes.change(function(){
		calTimeOut();
	});
	
	initTouchSpinInput($txtClient, 1, 99, 1, 1);
	
	initTimeInput($txtTimeIn);
	$txtTimeIn.change(function(){
		calTimeOut();
	});
	setTimeIn();
	
	$txtMinutes.keypress(function(e){
		if (e.which == 13) {
			$txtTimeIn.focus();
			return false;
		}
	});
	$txtTimeIn.keypress(function(e){
		if (e.which == 13) {
			$txtClient.focus();
			return false;
		}
	});
	$txtClient.keypress(function(e){
		if (e.which == 13) {
			$btnSearch.click();
			return false;
		}
	});
	
	$btnSearch.click(function(){
		searchQueue();
	});
	
	$btnRecord.click(function(){
		showMassageRecord();
	});
	
	initTableTherapist();
	initTableRoom();
	
	//initRoom();
	//initTherapist();
	searchQueue();
}

function setTimeIn(time) {
	time = typeof(time) === "undefined" ? currentTime() : time;
	
	setTimeInput($txtTimeIn, time);
	calTimeOut();
}
function calTimeOut() {
	if (isTimeInputComplete($txtTimeIn)) {
		timeIn = getTimeInput($txtTimeIn).split(":");
		minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_12_FORMAT);
		$txtTimeOut.val(timeOut);
	}
	else {
		$txtTimeOut.val('');
	}
}

function initTableTherapist() {
	$tableTherapist = $('#tableTherapist');
	
	_dtTableTherapist = $tableTherapist.DataTable({
		scrollY: 380,
		language: {
		      emptyTable: "No therapists on this shift"
		},
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: "therapist_id",
		columns: [
		          { data: "row_no", width: "30%", className: "text-center" }
		          , { data: "therapist_name", width: "40%" }
		          , { data: "therapist_available", className: "text-center"
		        	  , render: function(data, type, row){
		        		  if (data == 1)
		        			  return ICON_OK;
		        		  else
		        			  return ICON_REMOVE;
		        	  }
		          }
		]
	});
}
function clearTableTherapist() {
	_dtTableTherapist.rows().remove().draw();
	unbindTherapistRowsSelection();
}
function bindTableTherapist(therapists) {
	clearTableTherapist();
	addTherapistRows(therapists);
}
function addTherapistRows(therapists) {
	for (var i = 0; i < therapists.length; i++) {
		_dtTableTherapist.row.add({
			row_no: therapists[i]['row_no']
			, therapist_id: therapists[i]['therapist_id']
			, therapist_name: therapists[i]['therapist_name']
			, therapist_timeout: therapists[i]['therapist_timeout']
			, therapist_available: therapists[i]['therapist_available']
		}).draw();
	}
	
	setTherapistRowsSelection();
}
function setTherapistRowsSelection()
{
	$tableTherapist.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            _dtTableTherapist.$('tr.selected').removeClass('selected');

            // cannot be selected if therapist is not available
            if(_dtTableTherapist.rows(this).data()[0].therapist_available == 1)
            	$(this).addClass('selected');
        }
    });
}
function unbindTherapistRowsSelection()
{
	$tableTherapist.unbind(); // unbind events to prevent duplicate events
}

function initTableRoom() {
	$tableRoom = $('#tableRoom');
	
	_dtTableRoom = $tableRoom.DataTable({
		scrollY: 380,
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: "room_no",
		columns: [
		          { data: "room_desc", width: "50%", className: "text-center" }
		          , { data: "room_available", className: "text-center"
		        	  , render: function(data, type, row){
		        		  if (data == 1)
		        			  return ICON_OK;
		        		  else
		        			  return ICON_REMOVE;
		        	  } 
		          }
		]
	});
}
function clearTableRoom() {
	_dtTableRoom.rows().remove().draw();
	unbindRoomRowsSelection();
}
function bindTableRoom(rooms) {
	clearTableRoom();
	addRoomRows(rooms);
}
function addRoomRows(rooms) {
	for (var i = 0; i < rooms.length; i++) {
		_dtTableRoom.row.add({
			room_no: rooms[i]['room_no']
			, room_sub_no: rooms[i]['room_sub_no']
			, room_desc: rooms[i]['room_desc']
			, room_available: rooms[i]['room_available']
		}).draw();
		
		//alert(_dtTableRoom.rows(i).data()[0].room_no);
	}
	
	setRoomRowsSelection();
}
function setRoomRowsSelection()
{
	$tableRoom.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            _dtTableRoom.$('tr.selected').removeClass('selected');
            
            // cannot be selected if room is not available
            if(_dtTableRoom.rows(this).data()[0].room_available == 1)
            	$(this).addClass('selected');
        }
    });
}
function unbindRoomRowsSelection()
{
	$tableRoom.unbind(); // unbind events to prevent duplicate events
}

function initRoom() {
	main_request_ajax('../queueing/queueing-boundary.php', 'GET_ROOM', {}, onInitRoomDone);
}
function onInitRoomDone(response) {
	if (response.success) {		
		bindTableRoom(response.result);
	} else {
		main_alert_message(response.msg);
	}
}

function initTherapist() {
	$selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('../queueing/queueing-boundary.php', 'GET_THERAPIST_ON_QUEUE', $selectedDate, onInitTherapistDone);
}
function onInitTherapistDone(response) {
	if (response.success) {
		bindTableTherapist(response.result);
	} else {
		main_alert_message(response.msg);
	}
}

function getTimeIn()
{
	timeIn = getTimeInput($txtTimeIn);
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	return moment(date + ' ' + timeIn, MOMENT_DATE_TIME_FORMAT).format(MOMENT_DATE_TIME_FORMAT);
}
function getTimeOut()
{
	minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
	return moment(getTimeIn(), MOMENT_DATE_TIME_FORMAT).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}

function getSearchInfo() {
	var searchInfo = {
		date: parent.getSelectedDailyRecordDate()
		, minutes: $txtMinutes.val()
		, time_in: getTimeIn()
		, time_out: getTimeOut()
		, client_amount: $txtClient.val()
	};
	
	return searchInfo;
}

function validateInputs()
{
	if ($txtMinutes.val().trim().length) {
		if ($txtTimeIn.val().trim().length) {
			if ($txtClient.val().trim().length) {
				return true;
			} else {
				main_alert_message('Please enter "Client Amount"', function(){ $txtClient.focus();});
			}
		}
		else {
			main_alert_message('Please enter "Time-In"', function(){ $txtTimeIn.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Minutes"', function(){ $txtMinutes.focus();});
	}
	
	return false;
} // validateInputs

function getBookingSearchInfo() {
	// use the ID of booking from "Booking" page to search
}

function searchQueue() {
	if (validateInputs()) {
		main_request_ajax('../queueing/queueing-boundary.php', 'SEARCH_QUEUE_FOR_WALK_IN', getSearchInfo(), onSearchQueueDone);
	}
}
function onSearchQueueDone(response) {
	if (response.success) {
		var result = response.result;
		_searchResult = result;
		
		showAlert(result['available'], result['client_amount'], result['minutes'], result['time_in'], result['time_out']);
		
		bindTableTherapist(result['therapists']);
		bindTableRoom(result['rooms']);
	}
	else
		main_alert_message(response.msg);
}

function showAlert(isSucceeded, clientAmt, minutes, timeIn, timeOut) {
	var text = '';
	var msg = '';
	var clientUnit = '';
	
	hideAlert();
	
	if (clientAmt > 1)
		clientUnit = 'people';
	else
		clientUnit = 'person';
	
	var startTime = formatTime(timeIn);
	var endTime = formatTime(timeOut);
	
	if (isSucceeded) {
		text = 'We are <span class="text-mark">available</span> for <span class="text-mark">{0}</span> {1} for <span class="text-mark">{2}</span> minutes from <span class="text-mark">{3}</span> to <span class="text-mark">{4}</span>';
		msg = text.format(clientAmt, clientUnit, minutes, startTime, endTime);
		
		$alertAvailability.html(msg);
		$alertAvailability.css('display', 'block');
	} else {
		text = '<strong>Sorry!</strong> We are <span class="text-mark">not available</span> for <span class="text-mark">{0}</span> {1} for <span class="text-mark">{2}</span> minutes from <span class="text-mark">{3}</span> to <span class="text-mark">{4}</span>';
		msg = text.format(clientAmt, clientUnit, minutes, startTime, endTime);
		
		$alertUnavailability.html(msg);
		$alertUnavailability.css('display', 'block');
	}
}
function hideAlert() {
	$alertAvailability.css('display', 'none');
	$alertUnavailability.css('display', 'none');
}

function showMassageRecord() {	
	var selectedTherapistIndex = _dtTableTherapist.row('.selected').index(); // can also use .id()
	var selectedRoomIndex = _dtTableRoom.row('.selected').index();
	
	if (typeof(selectedTherapistIndex) === 'undefined'
		|| typeof(selectedRoomIndex) === 'undefined') {
		main_alert_message('Please select <strong>therapist</strong> and <strong>room</strong>!');
	} else {
		parent.showMassageRecord(getRecordInfo(selectedTherapistIndex, selectedRoomIndex));
	}
}
//will be called by PARENT
function onAddMassageRecordDone() {
	// ***after recording, search again by minus 1 client amount
	var clientAmt = _searchResult['client_amount'] - 1;
	if (clientAmt > 0) {
		$txtClient.val(clientAmt);
	}
	
	searchQueue();
}

function getRecordInfo(selectedTherapistIndex, selectedRoomIndex) {
	var recordInfo = {
		date: _searchResult['date']
		, minutes: _searchResult['minutes']
		, time_in: _searchResult['time_in']
		, time_out: _searchResult['time_out']
		, therapist_id: _searchResult['therapists'][selectedTherapistIndex]['therapist_id']
		, therapist_name: _searchResult['therapists'][selectedTherapistIndex]['therapist_name']
		, room_no: _searchResult['rooms'][selectedRoomIndex]['room_no']
		, massage_type_id: 0
	};
	
	return recordInfo;
}

//will be called by PARENT
function clearFrameEditMode()
{
	//alert('CLEAR - Queueing');
}

//will be called by PARENT
function updateFrameContent()
{
	//alert("UPDATE - Queueing");
	setTimeIn();
	searchQueue();
}















