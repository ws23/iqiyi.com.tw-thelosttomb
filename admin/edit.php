<?php 
	session_start(); 
	require_once(dirname(__FILE__) . "/../lib/std.php");
	require_once(dirname(__FILE__) . "/../config.php");  

    $result = $DBmain->query("SELECT * FROM `main` WHERE `engName` = '{$actName}'; ");
	$row = $result->fetch_array(MYSQLI_BOTH);
	$AID = $row['id'];  

	if(!isset($_SESSION['UID'])){
		alert('Login Error. ')	; 
		setLog($DBmain, 'error', 'don\'t have session. '); 
		locate('index.php'); 
	}
	else {
		$type = ["v", "n", "o", "a"];
		
		foreach($type as $arr) {
			if($arr == 'v')
				$DBTable = "video"; 
			else if($arr == 'n')
			    $DBTable = "next"; 
			else if($arr == 'o')
			    $DBTable = "other"; 
			else if($arr == 'a')
				$DBTable = "ad"; 
			else {
			    setLog($DBmain, 'error', 'have error type', $_SESSION['UID']); 
			    continue; 
			}
			if($DBTable == "ad")
				$result = $DBmain->query("SELECT * FROM `{$DBTable}` WHERE `state` < 2; "); 
			else
				$result = $DBmain->query("SELECT * FROM `{$DBTable}` WHERE `state` < 2 AND `mainID` = {$AID}; "); 
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
				$tmp = $arr . '_' . $row['id'] . '_'; 
				
				if(!isset($_POST[$tmp . 'state']))
					continue; 
				
				$str = ""; 
                if($_POST[$tmp . 'state'] == "able")  
                    $str .= "`state` = 0";
                else if($_POST[$tmp . 'state'] == "disable")
                    $str .= "`state` = 1";
                else 
				    setLog($DBmain, 'error', 'have error post `state`', $_SESSION['UID']); 
				if($str!="")
					$DBmain->query("UPDATE `{$DBTable}` SET {$str} WHERE `id` = {$row['id']}; "); 


				if($_POST[$tmp . 'act'] == "read")
					continue; 

				else if($_POST[$tmp . 'act'] == "edit") {
					$str = "UPDATE `{$DBTable}` SET `title` = '{$_POST[$tmp . 'title']}', `linkURL` = '{$_POST[$tmp . 'link']}'";
					if($arr != 'a'){
						$str .=", `text` = '{$_POST[$tmp . 'text']}'"; 
						$str .= ", `videoURL` = '{$_POST[$tmp . 'video']}'"; 
					}
					$str .= " WHERE `id` = {$row['id']}; "; 
					$DBmain->query($str); 
					setLog($DBmain, 'info', "edit `$DBTable` #{$row['id']}", $_SESSION['UID']); 
				}
				else if($_POST[$tmp . 'act'] == "delete") {
					$DBmain->query("UPDATE `{$DBTable}` SET `state` = 2 WHERE `id` = {$row['id']}; "); 
					setLog($DBmain, 'info', "delete `{$DBTable}` #{$row['id']}", $_SESSION['UID']); 
				}
				else
					setLog($DBmain, 'error', "have error post `act` ({$tmp}act = {$_POST[$tmp . 'act']})", $_SESSION['UID']); 
			}

			if($_POST[$arr . '_0_act'] == "edit") {
				$now = date('Y-m-d', time());
				$imgURL = "img/uploads/{$now}-{$_FILES[$arr . '_0_img']['name']}"; 

				move_uploaded_file($_FILES[$arr . '_0_img']['tmp_name'], dirname(__FILE__) . "/../" . $imgURL); 
				setLog($DBmain, 'info', 'upload image', $_SESSION['UID']); 

				$state = $_POST[$arr . '_0_state'] =="able"? 0:1;
				if($arr != 'a')
					$text = $_POST[$arr . '_0_text'] ; 
				$title = $_POST[$arr . '_0_title']; 
				$link = $_POST[$arr . '_0_link']; 
				
				if($arr != 'a') {
					$video = $_POST[$arr . '_0_video']; 
					$DBmain->query("INSERT INTO `{$DBTable}` (`mainID`, `title`, `text`, `state`, `imageURL`, `linkURL`, `videoURL`) VALUES ({$AID}, '{$title}', '{$text}', {$state}, '{$imgURL}', '{$link}', '$video'); "); 
				}
				else if($arr =='a')
					$DBmain->query("INSERT INTO `{$DBTable}` (`title`, `state`, `imageURL`, `linkURL`) VALUE ('{$title}', {$state}, '{$imgURL}', '{$link}'); "); 	
				else
					$DBmain->query("INSERT INTO `{$DBTable}` (`mainID`, `title`, `text`, `state`, `imageURL`, `linkURL`) VALUES ({$AID}, '{$title}', '{$text}', {$state}, '{$imgURL}', '{$link}'); "); 

				setLog($DBmain, 'info', "new {$DBTable}"); 
			}
		}	 
	}
	require_once(dirname(__FILE__) . "/../lib/stdEnd.php"); 
	locate('index.php'); 
?>
