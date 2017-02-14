# Freelance_Lelux

Manual Changes:
	1. changing "log path"

Deployment: 30 March 2016
	*Note:	changing "log path"
	Issue:	mysql - casting "report_hour * 60" to integer causes an error, so casting to decimal(0) instead
	
Deployment Instruction: 21 April 2016
	- MySQL: health_fund: Add new health fund "HCF"
	- MySQL: health_fund: edit single quote of "The Doctors’ Health Fund"
	- MySQL: update - therapist table structure
	- MySQL: change data type of create_user, update_user, void_user to tinyint in client and report table
		*** beware of existed data: change it before update the struture
	- Task Schedule: create backup task with .bat

Deployment Instruction: 4 May 2016
	- change [therapist_id] of "--- Unknown ---" therapist to 0
	- change [therapist_permission] of "--- Unknown ---" therapist to 0
	- update table structure of "health_fund" and its data

Deployment Instruction: May 2016
	- Add more "Health Fund" which are "Cessnock District...", "Teachers Union Health" and "Teacher Federation Health"
	- Correct "Provider No" of "CBHS Health..."
	
Deployment Instruction: 27 July 2016
	- therapist/therapist-manage.js : no scrolling
	- report/report.php : new reports
	- master-page/menu.php : new menu
	- massage/	: new features
	- locales/ : scripts for moment.js
	- js/main.js : check integer value
	- js/	: datepicker.js & moment.js
	- css/	: css for datepicker
	- controller/ : new => Config function & MassageRecord function
					edited => ReportFunction & Session & Utilities
	- config/Const_Config.php
	- client/client-report.js : fixing bug
	- Database =>
		- new tables => config & massage_record & request_condition
	- [New] adding paid by Voucher
	- [New] change stamp amount to stamp minute
	- [on-site updating] adding [Voucher] into therapist list so that can record voucher sales
	
Deployment Instruction:
	- allow users to edit HealthFund, Membership No and PatientID on Client-Report page
	- adding "massage_record_time_in" & "massage_record_time_out" columns to "massage_record" table with "datetime" data type and default "1900-1-1 00:00:00" 
	- ***please!!, checking data after adding new columns in table "massage_record"
	- adding "Loading Panel" 
	- Patient ID "0" is allowed
	
Deployment Instruction on 10 Feb 2017:
	- Alter the type of tracable column info (eg. create_user, create_datetime) to "SMALL INT"
	- Check all tables that possibly have "Active" flags & set them correctly
	- Delete [Voucher] from "Therapist" Table
	- "dummy content after confliction between workspace and master files"

Next Deployment:
	** Need to fix:
		- (Added) Lelux icon
		- (Fixed) Income Report => display "Used Free Stamp" in minute
		- (Fixed) Report.Client Contacts => provide options: print all clients and clients in specific month
		- (Fixed) Editable Dropdown with no items bug (See example in "Sale.js" but not proper solution)
		- (Fixed) No items in dropdown case (MassageRecord, ClientReport, Sale)
		- Loading panel during HTML interpretation
	
	 
	 