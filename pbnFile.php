<?php
require_once 'pbnFormatter.php';
if(isset($_GET['data']) && strlen($_GET['data'])>0){
	$formatter = new pbnFormatter($_GET['data']);
	header('Content-Type: application/pbn');
	header('Content-Disposition: attachment; filename="'.time().'.pbn"');
	echo $formatter->getPBN();
}else{
	header("HTTP/1.0 400 Bad Request");
	exit;
}