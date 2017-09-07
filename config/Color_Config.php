<?php

class Color_Config {
	const BOLD = 'font-weight: bold;';
	const PROPERTY_FG = 'color:';
	const PROPERTY_BG = 'background-color:';
	
	//const GAP = "border: 0px; background-color: transparent;";
	const GAP = "background-color: transparent; color: transparent; cursor: default;";
	
	const BOOKING_BORDER = "border: 3px solid red;";
	const BOOKING_BG = self::BOLD.self::PROPERTY_BG.'#f44336;';
		
	const RECORD_FG_DEFAULT = self::BOLD.self::PROPERTY_FG.'#fff;';
	const RECORD_BG_DEFAULT =  self::PROPERTY_BG.'#2e2e2e;';
	
	const GROUP_FG_DEFAULT = self::BOLD.self::PROPERTY_FG.'rgba(255, 152, 0, 0.8);';
	const GROUP_BG_DEFAULT = self::PROPERTY_BG.'rgba(255, 152, 0, 0.8);';
	
	const SHIFT_BGs = array(
		self::PROPERTY_BG.'#f48fb1;' //pink
		, self::PROPERTY_BG.'rgba(3, 169, 244, 0.8);' //blue
		, self::PROPERTY_BG.'rgba(255, 235, 59, 0.8);' //yellow
		, self::PROPERTY_BG.'rgba(156, 39, 176, 0.8);' //purple
		, self::PROPERTY_BG.'rgba(76, 175, 80, 0.8);' //green
		, self::PROPERTY_BG.'rgba(121, 85, 72, 0.8);' //brown
		, self::PROPERTY_BG.'rgba(255, 152, 0, 0.8);' //orange
		, self::PROPERTY_BG.'rgba(63, 81, 181, 0.8);' //indigo
		, self::PROPERTY_BG.'rgba(205, 220, 57, 0.8);' //lime
		, self::PROPERTY_BG.'rgba(0, 188, 212, 0.8);' //cyan
		, self::PROPERTY_BG.'rgba(158, 158, 158, 0.8);' //grey
		, self::PROPERTY_BG.'rgba(0, 150, 136, 0.8);' //teal
	);
	
	const SHIFT_FG_DEFAULT = self::BOLD.self::PROPERTY_FG.'#000;';
	const SHIFT_FGs = array(
		self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
		, self::SHIFT_FG_DEFAULT
	);
}