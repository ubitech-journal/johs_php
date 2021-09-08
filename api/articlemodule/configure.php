<?php
	define("CMSPHPPATH", str_replace('\\','/',realpath(dirname(dirname(dirname(__FILE__))))) . "/");
	
	if (empty($_SERVER['HTTP_HOST']))
	{
		define("CMSHTTPHOST", "http://");
	}
	else
	{
		define("CMSHTTPHOST", "http://" . $_SERVER["HTTP_HOST"]);
	}
	
	global $UBI_CONFIG;
	
	// $UBI_CONFIG 	= array();
	// $UBI_CONFIG['conn_host'] 				= 	"localhost";
	// $UBI_CONFIG['conn_instance'] 			= 	"ubijourn_jabonline";				
	// $UBI_CONFIG['user_name'] 				= 	"ubijourn_ubi";				
	// $UBI_CONFIG['password'] 				= 	"Tango@2019";	
	$UBI_CONFIG 	= array();
	$UBI_CONFIG['conn_host'] 				= 	"localhost";
	$UBI_CONFIG['conn_instance'] 			= 	"ubijourn_JOHS";				
	$UBI_CONFIG['user_name'] 				= 	"ubijourn_johs";				
	$UBI_CONFIG['password'] 				= 	"johs@123";	

/*	$UBI_CONFIG['conn_host'] 				= 	"98.130.0.98";
	$UBI_CONFIG['conn_instance'] 			= 	"sanjeev_ijlpr";				
	$UBI_CONFIG['user_name'] 				= 	"sanjeev_ihv96";				
	$UBI_CONFIG['password'] 				= 	"sumit96t";				
*/?>
