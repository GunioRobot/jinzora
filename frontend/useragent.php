<?php
	// constants:
	// JZ_FRONTEND_OVERRIDE
	// JZ_STYLE_OVERRIDE
	// JZ_LANGUAGE_OVERRIDE

	// todo: jukebox
global $jzSERVICES,$USER_SETTINGS_OVERRIDE;
	$useragent = $_SERVER['HTTP_USER_AGENT'];

	// This is for the Nintendo Wii
	if (false !== stristr($useragent, 'Nintendo Wii')) {
		define('JZ_FRONTEND_OVERRIDE','wiijay');
	}

	// This is for Windows Mobile Devices
	if (false !== stristr($useragent, 'Windows CE')) {
		define('JZ_FRONTEND_OVERRIDE','mobile');
		define('JZ_STYLE_OVERRIDE','sandstone');
		global $jzSERVICES;
		//$jzSERVICES->loadService('players','ptunes');
		//$USER_SETTINGS_OVERRIDE['playlist_type'] = 'asx';
		// using TCPMP + m3us
	}
?>