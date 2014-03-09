<?php

	$host = "localhost";
	$user = "root";
	$pass = "";
	$database = "catalog";
	
	/*$host = "192.168.0.127";
	$user = "matrixdev";
	$pass = "matrixdev";
	$database = "time_report";*/
	if($db = mysql_connect($host, $user, $pass) or die(mysql_error())){
		mysql_select_db($database, $db) or die(mysql_error());
	}

?>