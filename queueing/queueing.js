var $txtMinutes, $txtClient, $txtTimeIn, $txtTimeOut;
var $btnSearch, $btnRecord;

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
	
	initTouchSpinInput($txtMinutes, 10, 1000, 60, 5);
	$txtMinutes.change(function(){
		calTimeOut();
	});
	
	initTouchSpinInput($txtClient, 1, 99, 1, 1);
	
	$txtTimeIn.inputmask("hh:mm"); // "hh:mm t" => 11:30 pm
	$txtTimeIn.focus(function(){ $(this).select(); });
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
		
	});
	
	initTableTherapist();
	initTableRoom();
	
	//initRoom();
	//initTherapist();
	searchQueue();
}

function setTimeIn(time) {
	time = typeof(time) === "undefined" ? moment().format(MOMENT_TIME_FORMAT) : time;
	
	$txtTimeIn.val(time);
	calTimeOut();
}
function calTimeOut() {
	if ($txtTimeIn.inputmask("isComplete")) {
		timeIn = $txtTimeIn.val().split(":");
		minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_FORMAT);
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
	timeIn = $txtTimeIn.val().split(":");
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	
	return moment(date, MOMENT_DATE_FORMAT).add(timeIn[0], 'hours').add(timeIn[1], 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}
function getTimeOut()
{
	minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
	return moment(getTimeIn(), MOMENT_DATE_TIME_FORMAT).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}

function getSearchInfo() {
	var searchInfo = {
		date: parent.getSelectedDailyRecordDate()
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
		result = response.result;
		
		bindTableTherapist(result['therapists']);
		bindTableRoom(result['rooms']);
	}
	else
		main_alert_message(response.msg);
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
	searchQueue();
}















