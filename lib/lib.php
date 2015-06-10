<?php	/* standard function definitions */

/* To get the IP address of client */
function getIP() {
	if(!empty($_SERVER['REMOTE_ADDR']))
		$ip = $_SERVER['REMOTE_ADDR'];
	if(!empty($_SERVER['HTTP_X_FORWADED_FOR'])) {
		$ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']); 
		if($ip) {
			array_unshift($ips, $ip); 
			$ip = false; 
		}
		for($i=0; $i<count($ips); $i++) {
			if(!eregi("^(10|172.16|192.168).", $ips[$i])) {
				$ip = $ips[$i]; 
				break;	
			}
		}
	}
	return $ip; 
}

/* Log, Need Database (MYSQL) */
function setLog($DBlink, $type="info", $content, $user=""){
	$ip = getIP(); 
	$url = $_SERVER['REQUEST_URI']; 
	$DBlink->query("INSERT INTO `log`(`type`, `msg`, `user`, `site`, `IP`) VALUES ('{$type}', '{$content}', '{$user}', '{$url}', '{$ip}'); ");
}

/* alert */
function alert($msg) {
	echo "<script>alert('{$msg}')</script>"; 	
}

/* location */
function locate($url) {
	echo "<script> window.location.href='{$url}'; </script>"; 
}

//20150317
function updateUser($DBlink,$uID,$userName,$password,$nickName,$email,$authority){
    $table="user";
    $query="update ".$table." set userName='".$userName."',password='".$password.
            "',nickName='".$nickName."',email='".$email."',authority='".$authority.
            "' where ".getPriKeyFieldName($table)."=".$uID.";";
    $DBlink->query($query);
}

//20150317 neeed to change
function updateMust($DBlink,$mID,$startTime,$endTime,$titleText,$contentText,$URL,$state){
    $table="must";
    $query="update ".$table." set startTime='".$startTime."',endTime='".$endTime.
            "',titleText='".$titleText."',contentText='".$contentText.
            "',URL='".$URL."',state='".$state.
            "' where ".getPriKeyFieldName($table)."=".$mID.";";
    $DBlink->query($query);
}

//20150317 neeed to change
function updateRecommend($DBlink,$rID,$startTime,$endTime,$text,$URL,$state){
    $table="recommend";
    $query="update ".$table." set startTime='".$startTime."',endTime='".$endTime.
            "',text='".$text.
            "',URL='".$URL."',state='".$state.
            "' where ".getPriKeyFieldName($table)."=".$rID.";";
    $DBlink->query($query);
}

//20150317 neeed to change
function updateEditor($DBlink,$eID,$startTime,$endTime,$titleText,$contentText,$URL,$state){
    $table="editor";
    $query="update ".$table." set startTime='".$startTime."',endTime='".$endTime.
            "',titleText='".$titleText."',contentText='".$contentText.
            "',URL='".$URL."',state='".$state.
            "' where ".getPriKeyFieldName($table)."=".$eID.";";
    $DBlink->query($query);
}

//20150320
function updateTitle($DBlink,$tID,$titleText,$URL,$state){
    $table="title";
    $query="update ".$table." set titleText='".$titleText.
            "',URL='".$URL."',state='".$state.
            "' where ".getPriKeyFieldName($table)."=".$tID.";";
    $DBlink->query($query);
}

//20150323
function uploadImage($DBLink,$table,$id,$imageURL){
    $query="update `".$table."` set imageURL='".$imageURL."' where ".  getPriKeyFieldName($table)."=".$id.';';
    $DBLink->query($query);
}

//20150323
function isAdvertisementImageSizeLegal($width,$height){
    return $width==728&&$height==90;
}

//20150323
function isFocusImageSizeLegal($width,$height){
    return $width==380&&$height==270;
}

//20150323
function isImageSizeLegal($width,$height){
    return $width==180&&$height==101;
}

//20150323
function isAdSizeLegal($width,$height){
    return $width==728&&$height==90;
}

//20150323
function isCoBrandingSizeLegal($width,$height){
    return $width==90&&$height==90;
}

//20150324
function getImageWidth($table,$state){
    $width="";
    switch ($table) {
        case 'must':
            $width=180;
            if($state%2==1)
                $width=380;
            break;
        case 'recommend':
            if($state)
                $width=180;
            break;
        case 'editor':
            $width=180;
            break;
        case 'ad':
            $width=728;
            break;
        case 'co-branding':
            $width=90;
            break;
        default:
            break;
    }
    return $width;
}

//20150324
function getImageHeight($table,$state){
    $height="";
    switch ($table) {
        case 'must':
            $height=101;
            if($state%2==1)
                $height=270;
            break;
        case 'recommend':
            if($state%2==1)
                $height=101;
            break;
        case 'editor':
            $height=101;
            break;
        case 'ad':
            $height=90;
            break;
        case 'co-branding':
            $height=90;
            break;
        default:
            break;
    }
    return $height;
}

//20150323
function updateAd($DBLink,$aID,$URL,$state){
    $table='ad';
    $query="update {$table} set URL='{$URL}',state={$state} where ".getPriKeyFieldName($table)."={$aID};";
    $DBLink->query($query);
}

//20150323
function updateCoBranding($DBLink,$cID,$URL,$state){
    $table='co-branding';
    $query="update `{$table}` set URL='{$URL}',state={$state} where ".getPriKeyFieldName($table)."={$cID};";
    $DBLink->query($query);
}

//20150318
function ajaxDataList($table){
    switch ($table){
        case 'must':
            echo 'type:op_type,table:table,mID:id,state:post_state,startTime:start_date_change,endTime:end_date_change,'.
                    'imageURL:imageURL,URL:URL,titleText:titleText,contentText:contentText';
            break;
        case 'recommend':
            echo 'type:op_type,table:table,rID:id,state:post_state,startTime:start_date_change,endTime:end_date_change,'.
                    'imageURL:imageURL,URL:URL,text:text';
            break;
        case 'editor':
            echo 'type:op_type,table:table,eID:id,state:post_state,startTime:start_date_change,endTime:end_date_change,'.
                    'imageURL:imageURL,URL:URL,titleText:titleText,contentText:contentText';
            break;
        case 'title':
            echo 'type:op_type,table:table,tID:id,state:post_state,URL:URL,titleText:titleText';
            break;
        case 'ad':case 'co-branding':
            echo 'type:op_type,table:table,'.  getPriKeyFieldName($table).':id,state:post_state,URL:URL';
            break;
        default :
            echo 'type:op_type,table:table,id:id,state:post_state';
            break;
    }
}

//20150319
function getPreState($DBlink,$table,$priKey){
    $query="select * from `".$table."` where ".getPriKeyFieldName($table)."=".$priKey.";";
    $result=$DBlink->query($query);
    $row = $result->fetch_array(MYSQLI_BOTH);
    $pre=$row['state'];
    mysqli_free_result($result);
    return $pre;
}

//20150316
function removeArticle($DBlink,$table,$priKey){
    $pre_state=getPreState($DBlink, $table, $priKey);
    if($pre_state<2)
        $new_state=$pre_state+2;
    else
        $new_state=$pre_state;
    $new_state+=4;
    $query="update `".$table."` set state='".$new_state."' where ".getPriKeyFieldName($table)."=".$priKey.";";
    $DBlink->query($query);
}
//20150316
function getPriKeyFieldName($table){
    $field="";
    switch($table){
        case 'editor':
            $field='eID';
            break;
        case 'log':
            $field='lID';
            break;
        case 'main':
            break;
        case 'must':
            $field='mID';
            break;
        case 'recommend':
            $field='rID';
            break;
        case 'user':
            $field="uID";
            break;
        case 'title':
            $field='tID';
            break;
        case 'ad':
            $field='aID';
            break;
        case 'co-branding':
            $field='cID';
            break;
    }
    return $field;
}

//20150316
//
//$layout=> "standard","box_count","button_count","button"
//
function getFacebookLikeFormatLink($href,$layout){
    $data_layout="standard";
    if(isset($layout)&&isFacebookLikeDataLayout($layout))$data_layout=$layout;
    return '<div class="fb-like" data-href="'.$href.'" data-width="40" data-layout="'.$data_layout.'" data-action="like" data-colorscheme="dark" data-show-faces="true" data-share="true" ></div>';
}

//20150316
function isFacebookLikeDataLayout($layout){
    switch ($layout){
        case 'standard':
        case 'box_count':
        case 'button_count':
        case 'button':
            return true;
        default :
            return false;
    }
}

//20150316
//need to enhance
function checkUser($DBLink,$user_id){
    if(isset($user_id)){
        $DBLink->query("select * from user where uID='".$user_id."';");
        if($DBLink->num_rows>1){
            setLog($DBLink, $type="warning", "multiple user id : ".$user_id, $user_id);
            return false;
        }
        return true;
    }
    else
        return false;
}

/* get like amount */

function getFacebookLikeAmount($url){
	$URL = "https://api.facebook.com/method/fql.query?query=select%20%20like_count%20from%20link_stat%20where%20url=%22{$url}%22"; 
	$xml = simplexml_load_file($URL); 
	$amount = -1; 
	$str = ""; 

	foreach($xml->children() as $child){
		foreach($child->children() as $content)	
			if($content->getName() == "like_count")
				$amount = intval($content, 10); 
	}
	
	$str = (string)$amount;
	$len = strlen($str); 
	
	$out = ""; 
	for($i=0;$i<11-$len;$i++)
		$out .= "&nbsp;"; 

	return $out.$str; 
}

?>
