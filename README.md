# Freelance_Lelux

Deployment: 30 March 2016
	Note:	changing "log path"
	Issue:	mysql - casting "report_hour * 60" to integer causes an error, so casting to decimal(0) instead
	
Instruction for Next Update:
	- MySQL: health_fund: Add new health fund "HCF"
	- MySQL: health_fund: edit single quate of "The Doctors’ Health Fund"
	- MySQL: update - therapist table structure
	- MySQL: change data type of create_user, update_user, void_user to tinyint in client and report table
		*** beware of existed data: change it before update the struture