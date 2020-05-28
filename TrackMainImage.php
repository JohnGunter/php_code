<?php

function trackMainImage($page,$school,$missingImage) {
	session_start();
	error_reporting(E_ALL);
	
	//Create new directory if needed for storage by month
	$year = $today = date("Y");
	$month = $today = date("m");
	if( !is_dir('../files/missing_images/'.$year.'/'.$month) ) {
		mkdir('../files/missing_images/'.$year.'/'.$month, 0777, true);
	}

	//Set year month and day for log file name.
	$logYear = date("Y");
	$logMonth = date("m");
	$logFile = date("d");

	//Assign year month and day to file name variable.
	$fileName = $logYear.'_'.$logMonth.'_'.$logFile.'.txt';

	if( file_exists($_SERVER['DOCUMENT_ROOT']."/files/missing_images/".$year."/".$month."/".$fileName) ) {
		$myfile = fopen($_SERVER['DOCUMENT_ROOT']."/files/missing_images/".$year."/".$month."/".$fileName, 'a');
	} else {
		$myfile = fopen($_SERVER['DOCUMENT_ROOT']."/files/missing_images/".$year."/".$month."/".$fileName, 'w');
	}

	$txt = date('H:i:s').PHP_EOL;
	$txt .= 'File: '.$page.PHP_EOL;
	$txt .= 'School ID: '.$school.PHP_EOL;
	$txt .= 'Main Image: '.$missingImage.PHP_EOL.PHP_EOL;
	fwrite($myfile, $txt);
	fclose($myfile);
}

?>