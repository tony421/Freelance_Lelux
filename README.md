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
	
Instruction for Next Update:
	- change [therapist_id] of "--- Unknown ---" therapist to 0
	- change [therapist_permission] of "--- Unknown ---" therapist to 0
	- update table structure of "health_fund" and its data
	