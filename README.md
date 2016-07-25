# Freelance_Lelux

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
	
Next Deployment Instruction:
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
		