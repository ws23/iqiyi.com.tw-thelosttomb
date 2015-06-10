<?php 
	require_once(dirname(__FILE__) . "/config.php"); 
	require_once(dirname(__FILE__) . "/lib/std.php"); 

	$result = $DBmain->query("SELECT * FROM `rule`, `main` WHERE `main`.`id` = `rule`.`mainID` AND `main`.`engName` = '{$actName}' ORDER BY `rule`.`id` ASC LIMIT 1; "); 
	$row = $result->fetch_array(MYSQLI_BOTH); 
	echo $row['text']; 
	require_once(dirname(__FILE__) . "/lib/stdEnd.php"); 
?>
