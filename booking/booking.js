var PREFIX_DDL_THERAPIST = '#ddlTherapist';
// {0} = id, {1} = options
var DDL_THERAPIST_ELEMENT = "<div class=\"col-sm-2\" style=\"padding-bottom: 5px;\"> <select id=\"ddlTherapist{0}\" class=\"form-control\">{1}</select></div>";
var DDL_THERAPIST_ELEMENT_EMPTY = "<div class=\"col-sm-2\" style=\"padding-bottom: 5px;\"> <select id=\"ddlTherapist\" class=\"form-control\"></select></div>";

var _therapistAmt, _therapists, _therapistOptions;
var _bookings, _bookingTimelineGroups;
var _bookingTimeline, _bookingTimelineMoveTo;

var $txtMinutes, $txtTimeIn, $txtTimeOut;
var $txtClient, $txtSingleRoom, $txtDoubleRoom;
var $btnSearch;
var $ddlTherapistContainer;
var $bookingTimeline;

function initPage() {
	main_ajax_success_hide_loading();
	
	$txtMinutes = $('#txtMinutes');
	$txtTimeIn = $('#txtTimeIn');
	$txtTimeOut = $('#txtTimeOut');
	$txtClient = $('#txtClient');
	$txtSingleRoom = $('#txtSingleRoom');
	$txtDoubleRoom = $('#txtDoubleRoom');
	$btnSearch = $('#btnSearch');
	$ddlTherapistContainer = $('#ddlTherapistContainer');
	$bookingTimeline = $('#bookingTimeline');
	
	initTouchSpinInput($txtMinutes, 10, 1000, 60, 5);
	$txtMinutes.change(calTimeOut);
	
	$txtTimeIn.inputmask("hh:mm"); // "hh:mm t" => 11:30 pm
	$txtTimeIn.focus(function(){ $(this).select(); });
	$txtTimeIn.change(calTimeOut);
	setTimeIn();
	
	initTouchSpinInput($txtClient, 0, 99, 0, 1);
	$txtClient.change(function(){
		clientAmt = $(this).val();
		
		if (clientAmt.trim().length) {
			if (clientAmt > _therapistAmt) {
				clientAmt = _therapistAmt;
			}
		} else {
			clientAmt = 0;
		}
		
		$(this).val(clientAmt);
		setDefaultValueForRooms(clientAmt);
		setDDLTherapist(clientAmt);
	});
	
	initTouchSpinInput($txtSingleRoom, 0, 99, 0, 1);
	$txtSingleRoom.change(validateSingleRoomAmount);
	initTouchSpinInput($txtDoubleRoom, 0, 99, 0, 1);
	$txtDoubleRoom.change(validateDoubleRoomAmount);
	
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
			$txtSingleRoom.focus();
			return false;
		}
	});
	$txtSingleRoom.keypress(function(e){
		if (e.which == 13) {
			$txtDoubleRoom.focus();
			return false;
		}
	});
	$txtDoubleRoom.keypress(function(e){
		if (e.which == 13) {
			$(PREFIX_DDL_THERAPIST + '0').focus();
			return false;
		}
	});
	
	$btnSearch.click(searchAvailability);
	
	initConfig();
}

function initConfig() {
	// therapist amount
	// therapist on shift
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('../booking/booking-boundary.php', 'GET_CONFIG', selectedDate, onInitConfigDone);
	
}
function onInitConfigDone(response) {
	if (response.success) {
		result = response.result;
		_therapistAmt = result['therapist_amount'];
		_therapists = result['therapists'];
		
		_therapistOptions = "<option value='0'>[Any]</option>";
		$.each(_therapists, function (i, therapist){
			_therapistOptions += "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
		});
		
		initBookingTimeline();
	} else {
		main_alert_message(response.msg);
	}
}

function initBookingTimeline() {
	selectedDate = parent.getSelectedDailyRecordDate();	
	main_request_ajax('../booking/booking-boundary.php', 'GET_BOOKING_TIMELINE', selectedDate, onInitBookingTimelineDone);
}
function onInitBookingTimelineDone(response) {
	if (response.success) {
		result = response.result;
		_bookings = result['bookings'];
		_bookingTimelineGroups = result['timeline_groups'];
		
		renderBookingTimeline(_bookingTimelineGroups);
	} else {
		main_alert_message(response.msg);
	}
}
function renderBookingTimeline(timelineGroups, bgItem) {
	// DOM element where the Timeline will be attached
	var container = document.getElementById('bookingTimeline');

	var groups = new vis.DataSet(timelineGroups);
	var items = new vis.DataSet();
	
	if (typeof(bgItem) !== 'undefined')
		items.add(bgItem);
		
	for (var i = 0; i < timelineGroups.length; i++) {
		items.add(timelineGroups[i]['items']);
	}
	
	//Create a Timeline
	if (typeof(_bookingTimeline) !== 'undefined')
		_bookingTimeline.destroy();
	
	_bookingTimeline = new vis.Timeline(container, items, groups, getTimelineOptions(selectedDate));	
}

function addTimelineBackgound(timeIn, timeOut) {
	setTimelineMoveTo(timeIn);
	
	bgItem = {id: 'BG', content: '', start: timeIn, end: timeOut, type: 'background'}
	renderBookingTimeline(_bookingTimelineGroups, bgItem);
	
	//moveToTime = moment(timeIn, MOMENT_DATE_TIME_FORMAT).add(35, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
	//setTimeout(function(){ _bookingTimeline.moveTo(moveToTime); }, 200);
}

function getTimelineOptions(date) {
	var start, end;
	
	if (typeof(_bookingTimelineMoveTo) === 'undefined') {
		start = getTimelineStart(date);
		end = getTimelineEnd(date);
	} else {
		start = _bookingTimelineMoveTo;
		end = moment(_bookingTimelineMoveTo, MOMENT_DATE_TIME_FORMAT).add(195, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
		_bookingTimelineMoveTo = undefined;
	}
	
	return {
			orientation: 'both'
			, zoomable: false
			, showMajorLabels: false
			, timeAxis: { scale: 'minute', step: 15 }
			//, minHeight: '300px'
			, start: start
		    , end: end
		    , min: getTimelineMin(date)
		    , max: getTimelineMax(date)
	};
}
function getTimelineStart(date) {
	if (date == currentDate()) {
		return date + ' ' + currentTime();
	} else {
		return date + ' ' + OPEN_TIME;
	}
}
function getTimelineEnd(date) {
	return moment(getTimelineStart(date), MOMENT_DATE_TIME_FORMAT).add(195, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}
function getTimelineMin(date) {
	return date + ' ' + OPEN_TIME;
}
function getTimelineMax(date) {
	return moment(date, MOMENT_DATE_FORMAT).add(1, 'days');
}
function getTimelineGroups() {
	var groups = [];
	for (var i = 0; i < _therapists.length; i++) {
		groups[i] = { id: i + 1, content: 'Therapist #' + (i + 1), style: 'font-weight: bold;' };
	}
	
	return new vis.DataSet(groups);
}
function setTimelineMoveTo(timeIn) {
	_bookingTimelineMoveTo = moment(timeIn, MOMENT_DATE_TIME_FORMAT).add(-60, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
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

function setDefaultValueForRooms(clientAmt) {
	singleRoom = clientAmt % 2;
	doubleRoom = Math.floor(clientAmt / 2);
	
	$txtSingleRoom.val(singleRoom);
	$txtDoubleRoom.val(doubleRoom);
}

function setDDLTherapist(clientAmt) {
	$ddlTherapistContainer.children().unbind();
	$ddlTherapistContainer.empty();
	
	if (clientAmt > 0) {
		for (var i = 0; i < clientAmt; i++) {
			ddlTherapist = DDL_THERAPIST_ELEMENT.format(i, _therapistOptions);
			$ddlTherapistContainer.append(ddlTherapist);
			
			listenDDLTherapistChange(i);
		}
	} else {
		$ddlTherapistContainer.append(DDL_THERAPIST_ELEMENT_EMPTY);
	}
}
function listenDDLTherapistChange(index) {
	$(PREFIX_DDL_THERAPIST + index).change(function(){
		checkDuplicateSelectedTherapist(clientAmt, index, $(this).val());
	});
}
function checkDuplicateSelectedTherapist(clientAmt, currentIndex, currentVal) {
	console.log(clientAmt + " | " + currentIndex + " | " + currentVal);
	for (var i = 0; i < clientAmt; i++) {
		if (i != currentIndex) {
			if ($(PREFIX_DDL_THERAPIST + i).val() == currentVal) {
				$(PREFIX_DDL_THERAPIST + currentIndex).val(0);
			}
		}
	}
}

function getSearchInfo() {
	var searchInfo = {
		date: parent.getSelectedDailyRecordDate()
		, minutes: $txtMinutes.val()
		, time_in: getTimeIn()
		, time_out: getTimeOut()
		, client_amount: $txtClient.val()
		, single_room_amount: $txtSingleRoom.val()
		, double_room_amount: $txtDoubleRoom.val()
		, therapists: getSelectedTherapists()
	};
	
	return searchInfo;
}
function getSelectedTherapists() {
	therapists = [];
	clientAmt = $txtClient.val();
	
	for (var i = 0; i < clientAmt; i++) {
		therapist = {
			therapist_id: $(PREFIX_DDL_THERAPIST + i).val()
			, therapist_name: getDDLSelectedText(PREFIX_DDL_THERAPIST + i)
		};
		//therapist['therapist_id'] = $(PREFIX_DDL_THERAPIST + i).val();
		//therapist['therapist_name'] = getDDLSelectedText(PREFIX_DDL_THERAPIST + i);
		
		therapists.push(therapist);
	}
	
	return therapists;
}

function validateInputs()
{
	if ($txtMinutes.val().trim().length) {
		if ($txtTimeIn.val().trim().length) {
			if ($txtClient.val().trim().length) {
				if (parseInt($txtClient.val()) > 0) {
					if ($txtSingleRoom.val().trim().length || $txtDoubleRoom.val().trim().length) {
						if (parseInt($txtSingleRoom.val()) > 0 || parseInt($txtDoubleRoom.val()) > 0) {
							if(validateRoomAmount()) {
								return true;
							} else {
								main_alert_message('Please check "Room amount" is not right for "client amount"', function(){ $txtSingleRoom.focus();});
							}
						} else {
							main_alert_message('Please enter "Room Amount"', function(){ $txtSingleRoom.focus();});
						}
					} else {
						main_alert_message('Please enter "Room Amount"', function(){ $txtSingleRoom.focus();});
					}
				} else {
					main_alert_message('Please enter "Client Amount"', function(){ $txtClient.focus();});
				}
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

function validateRoomAmount() {
	clientAmt = parseInt($txtClient.val());
	singleAmt = parseInt($txtSingleRoom.val());
	doubleAmt = parseInt($txtDoubleRoom.val());
	
	totalRoomCapacity = singleAmt + (doubleAmt * 2);
	
	if (totalRoomCapacity == clientAmt)
		return true;
	else
		return false;
} 
function validateSingleRoomAmount() {
	if ($txtSingleRoom.val().length) {
		clientAmt = parseInt($txtClient.val());
		singleAmt = parseInt($txtSingleRoom.val());
		doubleAmt = parseInt($txtDoubleRoom.val());
		
		totalRoomCapacity = singleAmt + (doubleAmt * 2);
		
		if (totalRoomCapacity > clientAmt) {
			singleAmt = clientAmt - (doubleAmt * 2);
			$txtSingleRoom.val(singleAmt);
		}
	} else {
		$txtSingleRoom.val(0);
	}
}
function validateDoubleRoomAmount() {
	if($txtDoubleRoom.val().length) {
		clientAmt = parseInt($txtClient.val());
		singleAmt = parseInt($txtSingleRoom.val());
		doubleAmt = parseInt($txtDoubleRoom.val());
		
		totalRoomCapacity = singleAmt + (doubleAmt * 2);
		
		if (totalRoomCapacity > clientAmt) {
			doubleAmt = parseInt((clientAmt - singleAmt) / 2);
			$txtDoubleRoom.val(doubleAmt);
		}
	} else {
		$txtDoubleRoom.val(0);
	}
}

function searchAvailability() {
	if (validateInputs()) {
		searchInfo = getSearchInfo();
		addTimelineBackgound(searchInfo['time_in'], searchInfo['time_out']);
		
		main_request_ajax('../queueing/queueing-boundary.php', 'SEARCH_AVAILABILITY_FOR_BOOKING', searchInfo, onSearchAvailabilityDone);
	}
}
function onSearchAvailabilityDone(response) {
	if (response.success) {
		result = response.result;
		
		console.log(result['available'] + ' | ' + result['remark']);
		bookingAvailable = result['available'];
		
		if (bookingAvailable) {
			parent.showBookingDetails(
					result['minutes']
					, result['date']
					, result['time_in']
					, result['time_out']
					, result['client_amount']
					, result['single_room_amount']
					, result['double_room_amount']
					, result['therapists']);
		} else {
			var msg = result['remark'];
			msg += ' from <span class="text-mark">{0}</span> to <span class="text-mark">{1}</span>';
			
			main_alert_message(msg.format(moment(result['time_in']).format(MOMENT_TIME_FORMAT), moment(result['time_out']).format(MOMENT_TIME_FORMAT)));
		}
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
	initConfig();
}

//will be called by PARENT
function onAddBookingDone(moveTo)
{
	setTimelineMoveTo(moveTo);
	initBookingTimeline();
}






