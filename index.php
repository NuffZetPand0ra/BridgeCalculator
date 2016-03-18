<?php
namespace Nuffy\Bridgify;

require_once 'pbnFormatter.php';
require_once 'bcalc.php';
$input = "";
$format = "calc";
if(isset($_POST['data']) && strlen($_POST['data'])>0){
	$input = $_POST['data'];
	try{
		$formatter = new pbnFormatter($input);
	}catch(Exception $e){
		echo "<pre>";var_dump($e);echo "</pre>";
	}
	if($_POST['output'] == "pbn"){
		$format = "pbn";
		header('Content-Type: application/pbn');
		header('Content-Disposition: attachment; filename="'.time().'.pbn"');
		echo $formatter->getPBN();
		exit;
	}else{
		$format = "calc";
		$bcalc = new bcalc($formatter);
		try{
			$bcalc->calculateTricks("a","-q");
		}catch(Exception $e){
			echo "<pre>";var_dump($e);echo "</pre>";
		}
		
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PBN formatter</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<style>
		.bcalc-tricks{
			margin:0;
			padding:0;
			border-collapse:collapse;
		}
		.bcalc-tricks tr{
			margin:0;
			padding:0;
		}
		.bcalc-tricks tbody tr:nth-child(odd) td{
			background-color:#F2F2F2;
		}
		.bcalc-tricks td{
			margin:0;
			padding:2px 4px;
			text-align:center;
			border-width:1px;
			border-style:solid;
			border-color:#9F9F9F;
		}
		.bcalc-tricks thead:first-child td:first-child{
			border-width:0 1px 1px 0;
		}
		.bcalc-tricks-header .nt{
			font-weight:bold;
		}
		.bcalc-tricks-header .d, .bcalc-tricks-header .h{
			color:red;
		}
		.bcalc-tricks .hand-letter{
			font-weight:bold;
		}
		ol.board{
			display:block;
			position:relative;
			margin:0;
			padding:0;
			width:100%;
			max-width:800px;
			list-style:none outside none;
		}
		ol.board > li{
			display:inline-block;
			width:33%;
			background-color:#F2F2F2;
		}
		ol.board > li:nth-child(1), ol.board > li:nth-child(4){
			margin:0 33% 0 33%;
		}
		ol.board > li:nth-child(2), ol.board > li:nth-child(3){
			/*margin:5% 0;*/
		}
		ol.board > li:nth-child(2){
			margin-right:33%;
		}
		ol.board > li > ol{			
			list-style:none outside none;
			margin:0;
			padding:4px 8px;
		}
		ol.board .H-suit .suit-symbol, ol.board .D-suit .suit-symbol{
			color:red;
		}
		ol.board .suit-symbol{
			padding-right:2px;
		}
	</style>
</head>
<body>
	<form method="post" accept-charset="UTF-8">
		<textarea name="data" rows="13" cols="30"><?php echo $input; ?></textarea><hr>
		<input type="radio" name="output" value="pbn"<?php if($format == "pbn") echo " checked "; ?>> PBN file<br>
		<input type="radio" name="output" value="calc"<?php if($format == "calc") echo " checked "; ?>> Calculate tricks<hr>
		<input type="submit" value="Go!">
	</form>
	<?php
	if(isset($bcalc)){
		echo $formatter;
		echo "<hr>";
		echo $bcalc;
	}
	// echo "<pre>";var_dump($formatter);echo "</pre>";
	?>
</body>
