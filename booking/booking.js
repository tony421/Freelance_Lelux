var PREFIX_DDL_THERAPIST = '#ddlTherapist';
var PREFIX_DDL_MASSAGE_TYPE = '#ddlMassageType';
// {0} = id, {1} = options
var DDL_THERAPIST_ELEMENT = "<div class=\"col-sm-2\" style=\"padding-bottom: 5px;\"> <select id=\"ddlTherapist{0}\" class=\"form-control\">{1}</select></div>";

var DDL_MASSAGE_TYPE_ELEMENT = "<div class=\"col-sm-2\" style=\"padding-bottom: 5px;\"> <select id=\"ddlMassageType{0}\" class=\"form-control\">{1}</select></div>";

var _therapistAmt, _therapists, _therapistOptions
var _massageTypes, _massageTypeOptions;
var _bookings, _bookingTimelineGroups;
var _bookingTimeline, _bookingTimelineMoveTo;

var $txtMinutes, $txtTimeIn, $txtTimeOut;
var $txtClient, $txtSingleRoom, $txtDoubleRoom;
var $btnSearch;
var $contextMenuShowRecord;
var $ddlTherapistContainer, $ddlMassageTypeContainer;
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
	$contextMenuShowRecord = $('#contextMenuShowRecord');
	$ddlTherapistContainer = $('#ddlTherapistContainer');
	$ddlMassageTypeContainer = $('#ddlMassageTypeContainer');
	$bookingTimeline = $('#bookingTimeline');
	
	initTouchSpinInput($txtMinutes, 10, 1000, 60, 5);
	$txtMinutes.change(calTimeOut);
	
	//$txtTimeIn.inputmask("hh:mm t"); // "hh:mm t" => 11:30 pm
	//$txtTimeIn.focus(function(){ $(this).select(); });
	initTimeInput($txtTimeIn);
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
		setDDLMassageType(clientAmt);
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
	
	$contextMenuShowRecord.click(function(){
		//console.log($('#popupContextMenu').data('recordId'));
		recordID = $('#popupContextMenu').data('recordId')
		parent.showMassageRecordDetails(recordID);
		$('#popupContextMenu').css('display', 'none');
	});
	
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
		
		_massageTypes = result['massage_types'];
		_massageTypeOptions = "";
		$.each(_massageTypes, function (i, type){
			_massageTypeOptions += "<option value='" + type['massage_type_id'] + "'>" + type['massage_type_name'] + "</option>";
		});
		
		$txtClient.change();
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
	
	// make the timeline smaller
	setTimeout(function(){_bookingTimeline.zoomOut(1, { animation: false})}, 10);
	
	//_bookingTimeline.off('select');
	_bookingTimeline.on('select', function (properties) {
		$('#popupContextMenu').css('display', 'none');
		
		if (properties.items != '') {
			//console.log('selected items: ' + properties.items); // items = id
			var bookingItemID = properties.items;
			
			var selectedTimelineItem = getSelectedTimelineItem(bookingItemID);
			if (typeof(selectedTimelineItem) !== 'undefined') {
				var type = selectedTimelineItem['item_type'];
				if (type == 'booking') {
					var selectedItem = getSelectedBookingItem(bookingItemID);
					
					// show the queue when its status is "coming"
					if (selectedItem['booking_item_status'] == 1) {
						showBookingQueue(bookingItemID);
					}
				} else if (type == 'record') {
					// the item is a record, then show menu
					var popoverOffset = $('.vis-item.vis-selected').offset();
					popoverOffset.top = popoverOffset.top + $('.vis-item.vis-selected').height();
					$('#popupContextMenu').css('display', 'block');
					$('#popupContextMenu').offset(popoverOffset);
					$('#popupContextMenu').data('recordId', bookingItemID);
				} else if (type == 'gap') {
					
				}
			}
		} else {
			
		}
		
		/*console.log($('.vis-item.vis-selected').offset());
		var popoverOffset = $('.vis-item.vis-selected').offset();
		popoverOffset.top = popoverOffset.top + $('.vis-item.vis-selected').height() + 12;
		$('.popupContextMenu').offset(popoverOffset);*/
	});
}

function showBookingQueue(bookingItemID) {
	var bookingItem = getSelectedBookingItem(bookingItemID);
	parent.showBookingQueue(_bookings, bookingItem);
}
function getSelectedBookingItem(bookingItemID) {
	for (var i = 0; i < _bookings.length; i++) {
		if (_bookings[i]['booking_item_id'] == bookingItemID) {
			return _bookings[i];
		}
	}
}
function getSelectedTimelineItem(itemID) {
	for (var i = 0; i < _bookingTimelineGroups.length; i++) {
		for (var j = 0; j < _bookingTimelineGroups[i]['items'].length; j++) {
			if (_bookingTimelineGroups[i]['items'][j]['id'] == itemID) {
				return _bookingTimelineGroups[i]['items'][j];
			}
		}
	}
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
			, format: {
				minorLabels: {
					hour: 'h:mm A'
					, minute: 'h:mm A'
				}
			}
			, timeAxis: { scale: 'minute', step: 60 }
			//, minHeight: '430px'
			, maxHeight: '480px'
			, stack: false
			, start: start
		    , end: end
		    , min: getTimelineMin(date)
		    , max: getTimelineMax(date)
		    , groupOrder: '' // this is added to fix bug when more 10 groups have to be shown
	};
}
function getTimelineStart(date) {
	if (date == currentDate()) {
		return currentDateTime();
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
function getTimeIn()
{
	timeIn = getTimeInput($txtTimeIn).split(":");
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
		$ddlTherapistContainer.append(DDL_THERAPIST_ELEMENT.format('', ''));
	}
}
function listenDDLTherapistChange(index) {
	$(PREFIX_DDL_THERAPIST + index).change(function(){
		checkDuplicateSelectedTherapist(clientAmt, index, $(this).val());
	});
}
function checkDuplicateSelectedTherapist(clientAmt, currentIndex, currentVal) {
	//console.log(clientAmt + " | " + currentIndex + " | " + currentVal);
	for (var i = 0; i < clientAmt; i++) {
		if (i != currentIndex) {
			if ($(PREFIX_DDL_THERAPIST + i).val() == currentVal) {
				$(PREFIX_DDL_THERAPIST + currentIndex).val(0);
			}
		}
	}
}

function setDDLMassageType(clientAmt) {
	$ddlMassageTypeContainer.empty();
	
	if (clientAmt > 0) {
		for (var i = 0; i < clientAmt; i++) {
			ddlMassageType = DDL_MASSAGE_TYPE_ELEMENT.format(i, _massageTypeOptions);
			$ddlMassageTypeContainer.append(ddlMassageType);
		}
	} else {
		$ddlMassageTypeContainer.append(DDL_MASSAGE_TYPE_ELEMENT.format('', ''));
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
		, massage_types: getSelectedMassageTypes()
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
function getSelectedMassageTypes() {
	massageTypes = [];
	clientAmt = $txtClient.val();
	
	for (var i = 0; i < clientAmt; i++) {
		type = {
			massage_type_id: $(PREFIX_DDL_MASSAGE_TYPE + i).val()
			, massage_type_name: getDDLSelectedText(PREFIX_DDL_MASSAGE_TYPE+ i)
		};
			
		massageTypes.push(type);
	}
	
	return massageTypes;
}

function validateInputs()
{
	if ($txtMinutes.val().trim().length) {
		if (isTimeInputComplete($txtTimeIn)) {
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

function clearBookingInputs() {
	$txtClient.val(0);
	$txtClient.change();
}

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
		
		//console.log(result['available'] + ' | ' + result['remark']);
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
					, result['therapists']
					, result['massage_types']);
		} else {
			var msg = result['remark'];
			msg += ' for <span class="text-mark">{0}</span> client from <span class="text-mark">{1}</span> to <span class="text-mark">{2}</span> (<span class="text-mark">{3}</span> minutes)';
			
			main_alert_message(msg.format(result['client_amount'], formatTime(result['time_in']), formatTime(result['time_out']), result['minutes']));
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
	setTimeIn();
}

//will be called by PARENT
function onAddBookingDone(moveTo)
{
	clearBookingInputs();
	setTimelineMoveTo(moveTo);
	initBookingTimeline();
}

//will be called by PARENT
function onAddMassageRecordDone(moveTo) {
	setTimelineMoveTo(moveTo);
	initBookingTimeline();
}

//will be called by PARENT
function onUpdateBookingDone(moveTo) {
	setTimelineMoveTo(moveTo);
	initBookingTimeline();
}

//will be called by PARENT
function onDeleteBookingDone(moveTo) {
	setTimelineMoveTo(moveTo);
	initBookingTimeline();
}






