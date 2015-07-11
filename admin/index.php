<?php session_start();
require_once(dirname(__FILE__) . "/../lib/std.php") ; 
?>
<!DOCTYPE html>
<?php require_once(dirname(__FILE__) . "/../config.php"); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<link rel="shortcut icon" href="<?php echo $URLPv . "img/" . $iconName; ?>" type="image/x-icon">
	<link rel="icon" href="<?php echo $URLPv . "img/" . $iconName; ?>" type="image/x-icon">
	<meta name="viewpoint" content="width=device-width, initial-scale=1">
	<meta name="title" content="<?php echo $titleName; ?>">
	<meta name="description" content="">
	<meta name="author" content="臺灣愛奇藝股份有限公司">
	<title><?php echo $titleName; ?></title>

	<link href="<?php echo $URLPv; ?>lib/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo $URLPv; ?>admin/index.css" rel="stylesheet">
	<script src="<?php echo $URLPv; ?>lib/jquery/jquery-1.11.2.js"></script>
	<script src="<?php echo $URLPv; ?>lib/bootstrap/js/bootstrap.js"></script>
	<?php require_once(dirname(__FILE__) . "/../analyticstracking.php"); ?>
</head>
<body>
<?php
if(isset($_SESSION['UID'])) { // 已登入
	setlog($DBmain, 'info', 'enter admin interface', $_SESSION['UID']);
?>
<!-- header start -->
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><img class="logo" src="<?php echo $URLPv . "img/" . $logoName; ?>"/></a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=list"; ?>">劇集列表</a></li>
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=next"; ?>">預告片</a></li>
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=other"; ?>">精彩花絮</a></li>
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=photo"; ?>">劇照</a></li>
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=activity"; ?>">抽獎活動</a></li>
				<li><a href="//developers.facebook.com/tools/comments/100922380244479/approved/descending/" target="_blank">網友互動</a></li>
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=ad"; ?>">廣告版塊</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo $URLPv . "admin/index.php?admin=logout"; ?>">登出</a></li>
			</ul>
		</div>
	</div>
</nav>
<!-- header end -->

<!-- preprocess start -->
<script>
function Edit(type, id) {
	if(id==0) {
		document.getElementById(type + '_0_title').removeAttribute("style"); 
		if(type != "a") {
			document.getElementById(type + '_0_text').removeAttribute("style"); 
			document.getElementById(type + '_0_video').removeAttribute("style"); 
		}
		document.getElementById(type + '_0_link').removeAttribute("style");
		document.getElementsByName(type + '_0_state')[0].removeAttribute("style");  
		document.getElementById(type + '_0_img').removeAttribute("style"); 
	}
	else {
		document.getElementById(type + '_' + id + '_title').removeAttribute("readonly"); 
		if(type != "a") {
			document.getElementById(type + '_' + id + '_text').removeAttribute("readonly"); 
			document.getElementById(type + '_' + id + '_video').removeAttribute("style"); 
			document.getElementById(type + '_' + id + '_video_a').setAttribute("style", "display: none; ");
		}
		document.getElementById(type + '_' + id + '_link_a').setAttribute("style", "display: none; "); 
		document.getElementById(type + '_' + id + '_link').removeAttribute("style"); 
	}
}

function NoEdit(type, id) {
	if(id==0) {
		document.getElementById(type + '_0_title').setAttribute("style", "display: none; "); 
		if(type != "a"){
			document.getElementById(type + '_0_text').setAttribute("style", "display: none; "); 
			document.getElementById(type + '_0_video').setAttribute("style", "display: none; "); 
		}
		document.getElementById(type + '_0_link').setAttribute("style", "display: none; "); 
		document.getElementsByName(type + '_0_state')[0].setAttribute("style", "display: none; "); 
		document.getElementById(type + '_0_img').setAttribute("style", "display: none; "); 
	}
	else {
		document.getElementById(type + '_' + id + '_title').setAttribute("readonly", ""); 
		if(type != "a"){
			document.getElementById(type + '_' + id + '_text').setAttribute("readonly", ""); 
			document.getElementById(type + '_' + id + '_video').setAttribute("style", "display: none; "); 
			document.getElementById(type + '_' +  id + '_video_a').removeAttribute("style"); 
		}
		document.getElementById(type + '_' + id + '_link_a').removeAttribute("style"); 
		document.getElementById(type + '_' + id + '_link').setAttribute("style", "display: none; "); 
	}
}

function Action(type, id) {
	var state = document.getElementsByName(type + '_' + id + '_act')[0].value;
	
	if(state == "edit")
		Edit(type, id); 
	else if(state == "delete")
			NoEdit(type, id); 
	else if(state == "read")
		NoEdit(type, id); 
}
</script>

<?php 
	$result = $DBmain->query("SELECT * FROM `main` WHERE `engName` = '{$actName}'; ");  
	$row = $result->fetch_array(MYSQLI_BOTH); 
	$AID = $row['id'];
?>
<!-- preporcess end -->

<div class="container">
<?php
if($_GET['admin']=="list"){
?>
<form action="edit.php" method="post" enctype="multipart/form-data">
<div class="panel panel-theme">
	<div class="panel-heading"><h2>劇集列表</h2></div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-2">標題(7)</th>
				<th class="col-md-2">子標題(8)</th>
				<th class="col-md-1">縮圖連結</th>
				<th class="col-md-1">影片連結</th>
				<th class="col-md-1">內嵌網址</th>
				<th class="col-md-1">狀態</th>
				<th class="col-md-1">動作</th>
			</tr>
		</thead>
		<tbody>
<?php
		$result = $DBmain->query("SELECT * FROM `video` WHERE `state` < 2 AND `mainID` = {$AID} ORDER BY `id` ASC; "); 			
		while($row = $result->fetch_array(MYSQLI_BOTH)){
?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="v_<?php echo $row['id']; ?>_title" id="v_<?php echo $row['id']; ?>_title" placeholder="標題（限7字）" maxlength="7" value="<?php echo $row['title']; ?>" />
					</div>
				</td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="v_<?php echo $row['id']; ?>_text" id="v_<?php echo $row['id']; ?>_text" placeholder="子標題（限8字）" maxlength="8" value="<?php echo $row['text']; ?>" />
					</div>
				</td>
				<td>
					<a href="<?php echo $URLPv . $row['imageURL']; ?>" target="_blank">縮圖</a>
				</td>
				<td>
					<div id="v_<?php echo $row['id']; ?>_link_a">
						<a href="<?php echo $row['linkURL']; ?>" target="_blank">原始</a>
					</div>
					<div id="v_<?php echo $row['id']; ?>_link" style="display: none; ">
						<input class="form-control" type="text" name="v_<?php echo $row['id']; ?>_link" placeholder="影片原始連結" value="<?php echo $row['linkURL']; ?>" />
					</div>
				</td>
				<td>
					<div id="v_<?php echo $row['id']; ?>_video_a">
						<a href="<?php echo $row['videoURL']; ?>" target="_blank">內嵌</a>
					</div>
					<div id="v_<?php echo $row['id']; ?>_video" style="display: none; ">
						<input class="form-control" type="text" name="v_<?php echo $row['id']; ?>_video" placeholder="內嵌影片網址" value="<?php echo $row['videoURL']; ?>" />
					</div>
				</td>
				<td>
					<select name="v_<?php echo $row['id']; ?>_state" class="form-control">
						<option value="able" <?php echo $row['state']=='0'? "selected" : "";  ?>>顯示</option>
						<option value="disable" <?php echo $row['state']=='1'? "selected" : "";  ?>>隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('v', <?php echo $row['id']; ?>)" name="v_<?php echo $row['id']; ?>_act" class="form-control">
						<option value="read" selected>檢視</option>
						<option value="edit">編輯</option>
						<option value="delete">刪除</option>
					</select>
				</td>
			</tr>
<?php
		}
?>			
			<tr>
				<td></td>
				<td>
					<input id="v_0_title" class="form-control" type="text" name="v_0_title" placeholder="標題（限7字）" maxlength="7" style="display: none; "/>
				</td>
				<td>
					<input id="v_0_text" class="form-control" type="text" name="v_0_text" placeholder="子標題（限8字）" maxlength="8" style="display: none; "/>
				</td>
				<td>
				</td>
				<td>
					<input id="v_0_link" class="form-control" type="text" name="v_0_link" placeholder="影片原始連結" style="display: none; "/>
				</td>
				<td>
					<input id="v_0_video" class="form-control" type="text" name="v_0_video" placeholder="內嵌影片網址" style="display: none; "/>
				</td>
				<td>
					<select name="v_0_state" class="form-control" style="display: none; ">
						<option value="able" selected>顯示</option>
						<option value="disable">隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('v', 0)" name="v_0_act" class="form-control">
						<option value="read" selected></option>
						<option value="edit">新增</option>
					</select>
				</td>		
			</tr>
			<tr>
				<input class="btn btn-info form-control" type="submit" value="儲存所有變更" />
			</tr>
				<tr id="v_0_img" style="display: none; ">
					<td colspan="3"><p align="right"><span class="label label-info">image Size: 116x65</p></td>
					<td colspan="3"><input class="form-control" type="file" name="v_0_img" /></td>
					<td colspan="2"></td>
				</tr>
		</tbody>
	</table>
</div>
</form>
<?php	
}
else if($_GET['admin']=="next"){
?>
<form action="edit.php" method="post" enctype="multipart/form-data">
<div class="panel panel-theme">
	<div class="panel-heading"><h2>預告片</h2></div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-2">標題(10)</th>
				<th class="col-md-2">子標題(22)</th>
				<th class="col-md-1">縮圖連結</th>
				<th class="col-md-1">影片連結</th>
				<th class="col-md-1">內嵌網址</th>
				<th class="col-md-1">狀態</th>
				<th class="col-md-1">動作</th>
			</tr>
		</thead>
		<tbody>
<?php
		$result = $DBmain->query("SELECT * FROM `next` WHERE `state` < 2 AND `mainID` = {$AID} ORDER BY `id` ASC; "); 			
		while($row = $result->fetch_array(MYSQLI_BOTH)){
?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="n_<?php echo $row['id']; ?>_title" id="n_<?php echo $row['id']; ?>_title" placeholder="標題（限10字）" maxlength="10" value="<?php echo $row['title']; ?>" />
					</div>
				</td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="n_<?php echo $row['id']; ?>_text" id="n_<?php echo $row['id']; ?>_text" placeholder="子標題（限22字）" maxlength="22" value="<?php echo $row['text']; ?>" />
					</div>
				</td>
				<td>
					<a href="<?php echo $URLPv . $row['imageURL']; ?>" target="_blank">縮圖</a>
				</td>
				<td>
					<div id="n_<?php echo $row['id']; ?>_link_a">
						<a href="<?php echo $row['linkURL']; ?>" target="_blank">原始</a>
					</div>
					<div id="n_<?php echo $row['id']; ?>_link" style="display: none; ">
						<input class="form-control" type="text" name="n_<?php echo $row['id']; ?>_link" placeholder="影片原始連結" value="<?php echo $row['linkURL']; ?>" />
					</div>
				</td>
				<td>
					<div id="n_<?php echo $row['id']; ?>_video_a">
						<a href="<?php echo $row['videoURL']; ?>" target="_blank">內嵌</a>
					</div>
					<div id="n_<?php echo $row['id']; ?>_video" style="display: none; ">
						<input class="form-control" type="text" name="n_<?php echo $row['id']; ?>_video" placeholder="內嵌影片網址" value="<?php echo $row['videoURL']; ?>" />
					</div>
				</td>
				<td>
					<select name="n_<?php echo $row['id']; ?>_state" class="form-control">
						<option value="able" <?php echo $row['state']=='0'? "selected" : "";  ?>>顯示</option>
						<option value="disable" <?php echo $row['state']=='1'? "selected" : "";  ?>>隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('n', <?php echo $row['id']; ?>)" name="n_<?php echo $row['id']; ?>_act" class="form-control">
						<option value="read" selected>檢視</option>
						<option value="edit">編輯</option>
						<option value="delete">刪除</option>
					</select>
				</td>
			</tr>
<?php
		}
?>			
			<tr>
				<td></td>
				<td>
					<input id="n_0_title" class="form-control" type="text" name="n_0_title" placeholder="標題（限10字）" maxlength="10" style="display: none; "/>
				</td>
				<td>
					<input id="n_0_text" class="form-control" type="text" name="n_0_text" placeholder="子標題（限22字）" maxlength="22" style="display: none; "/>
				</td>
				<td>
				</td>
				<td>
					<input id="n_0_link" class="form-control" type="text" name="n_0_link" placeholder="影片原始連結" style="display: none; "/>
				</td>
				<td>
					<input id="n_0_video" class="form-control" type="text" name="n_0_video" placeholder="內嵌影片網址" style="display: none; "/>
				</td>
				<td>
					<select name="n_0_state" class="form-control" style="display: none; ">
						<option value="able" selected>顯示</option>
						<option value="disable">隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('n', 0)" name="n_0_act" class="form-control">
						<option value="read" selected></option>
						<option value="edit">新增</option>
					</select>
				</td>		
			</tr>
			<tr>
				<input class="btn btn-info form-control" type="submit" value="儲存所有變更" />
			</tr>
				<tr id="n_0_img" style="display: none; ">
					<td colspan="3"><p align="right"><span class="label label-info">image Size: 160x90</p></td>
					<td colspan="3"><input class="form-control" type="file" name="n_0_img" /></td>
					<td colspan="2"></td>
				</tr>
		</tbody>
	</table>
</div>
</form>
<?php 
}
else if($_GET['admin']=="other"){
?>
<form action="edit.php" method="post" enctype="multipart/form-data">
<div class="panel panel-theme">
	<div class="panel-heading"><h2>精彩花絮</h2></div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-2">標題(10)</th>
				<th class="col-md-2">子標題(22)</th>
				<th class="col-md-1">縮圖連結</th>
				<th class="col-md-1">影片連結</th>
				<th class="col-md-1">內嵌影片</th>
				<th class="col-md-1">狀態</th>
				<th class="col-md-1">動作</th>
			</tr>
		</thead>
		<tbody>
<?php
		$result = $DBmain->query("SELECT * FROM `other` WHERE `state` < 2 AND `mainID` = {$AID} ORDER BY `id` ASC; "); 			
		while($row = $result->fetch_array(MYSQLI_BOTH)){
?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="o_<?php echo $row['id']; ?>_title" id="o_<?php echo $row['id']; ?>_title" placeholder="標題（限10字）" maxlength="10" value="<?php echo $row['title']; ?>" />
					</div>
				</td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="o_<?php echo $row['id']; ?>_text" id="o_<?php echo $row['id']; ?>_text" placeholder="子標題（限22字）" maxlength="22" value="<?php echo $row['text']; ?>" />
					</div>
				</td>
				<td>
					<a href="<?php echo $URLPv . $row['imageURL']; ?>" target="_blank">縮圖</a>
				</td>
				<td>
					<div id="o_<?php echo $row['id']; ?>_link_a">
						<a href="<?php echo $row['linkURL']; ?>" target="_blank">原始</a>
					</div>
					<div id="o_<?php echo $row['id']; ?>_link" style="display: none; ">
						<input class="form-control" type="text" name="o_<?php echo $row['id']; ?>_link" placeholder="影片原始連結" value="<?php echo $row['linkURL']; ?>" />
					</div>
				</td>
				<td>
					<div id="o_<?php echo $row['id']; ?>_video_a">
						<a href="<?php echo $row['videoURL']; ?>" target="_blank">內嵌</a>
					</div>
					<div id="o_<?php echo $row['id']; ?>_video" style="display: none; ">
						<input class="form-control" type="text" name="o_<?php echo $row['id']; ?>_video" placeholder="內嵌影片網址" value="<?php echo $row['videoURL']; ?>" />
					</div>
				</td>
				<td>
					<select name="o_<?php echo $row['id']; ?>_state" class="form-control">
						<option value="able" <?php echo $row['state']=='0'? "selected" : "";  ?>>顯示</option>
						<option value="disable" <?php echo $row['state']=='1'? "selected" : "";  ?>>隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('o', <?php echo $row['id']; ?>)" name="o_<?php echo $row['id']; ?>_act" class="form-control">
						<option value="read" selected>檢視</option>
						<option value="edit">編輯</option>
						<option value="delete">刪除</option>
					</select>
				</td>
			</tr>
<?php
		}
?>			
			<tr>
				<td></td>
				<td>
					<input id="o_0_title" class="form-control" type="text" name="o_0_title" placeholder="標題（限10字）" maxlength="10" style="display: none; "/>
				</td>
				<td>
					<input id="o_0_text" class="form-control" type="text" name="o_0_text" placeholder="子標題（限22字）" maxlength="22" style="display: none; "/>
				</td>
				<td>
				</td>
				<td>
					<input id="o_0_link" class="form-control" type="text" name="o_0_link" placeholder="影片原始連結" style="display: none; "/>
				</td>
				<td>
					<input id="o_0_video" class="form-control" type="text" name="o_0_video" placeholder="內嵌影片網址" style="display: none; "/>
				</td>
				<td>
					<select name="o_0_state" class="form-control" style="display: none; ">
						<option value="able" selected>顯示</option>
						<option value="disable">隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('o', 0)" name="o_0_act" class="form-control">
						<option value="read" selected></option>
						<option value="edit">新增</option>
					</select>
				</td>		
			</tr>
			<tr>
				<input class="btn btn-info form-control" type="submit" value="儲存所有變更" />
			</tr>
				<tr id="o_0_img" style="display: none; ">
					<td colspan="3"><p align="right"><span class="label label-info">image Size: 160x90</p></td>
					<td colspan="3"><input class="form-control" type="file" name="o_0_img" /></td>
					<td colspan="2"></td>
				</tr>
		</tbody>
	</table>
</div>
</form>
<?php }
else if($_GET['admin']=="photo"){
?>
	<div class="jumbotron">
		<div class="page-header">
			<h2>暫時不提供管理服務TAT</h2>
		</div>
	</div>
<?php }
else if($_GET['admin']=="activity"){ ?>
    <div class="jumbotron">
        <div class="page-header">
            <h2>暫時不提供管理服務TAT</h2>
        </div>
    </div>
<?php }
else if($_GET['admin']=="logout"){
	session_destroy(); 
	locate('index.php'); 
}
else if($_GET['admin']=="ad"){ ?>
<form action="edit.php" method="post" enctype="multipart/form-data">
<div class="panel panel-theme">
	<div class="panel-heading"><h2>廣告版位</h2></div>
	<table class="table">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-4">識別文字</th>
				<th class="col-md-1">圖片連結</th>
				<th class="col-md-1">超連結</th>
				<th class="col-md-1">狀態</th>
				<th class="col-md-1">動作</th>
			</tr>
		</thead>
		<tbody>
<?php
		$result = $DBmain->query("SELECT * FROM `ad` WHERE `state` < 2 ORDER BY `id` ASC; "); 			
		while($row = $result->fetch_array(MYSQLI_BOTH)){
?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td>
					<div class="form-group">
						<input readonly class="form-control" type="text" name="a_<?php echo $row['id']; ?>_title" id="a_<?php echo $row['id']; ?>_title" placeholder="識別文字" maxlength="255" value="<?php echo $row['title']; ?>" />
					</div>
				</td>
				<td>
					<a href="<?php echo $URLPv . $row['imageURL']; ?>" target="_blank">圖片</a>
				</td>
				<td>
					<div id="a_<?php echo $row['id']; ?>_link_a">
						<a href="<?php echo $row['linkURL']; ?>" target="_blank">超連結</a>
					</div>
					<div id="a_<?php echo $row['id']; ?>_link" style="display: none; ">
						<input class="form-control" type="text" name="a_<?php echo $row['id']; ?>_link" placeholder="超連結" value="<?php echo $row['linkURL']; ?>" />
					</div>
				</td>
				<td>
					<select name="a_<?php echo $row['id']; ?>_state" class="form-control">
						<option value="able" <?php echo $row['state']=='0'? "selected" : "";  ?>>顯示</option>
						<option value="disable" <?php echo $row['state']=='1'? "selected" : "";  ?>>隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('a', <?php echo $row['id']; ?>)" name="a_<?php echo $row['id']; ?>_act" class="form-control">
						<option value="read" selected>檢視</option>
						<option value="edit">編輯</option>
						<option value="delete">刪除</option>
					</select>
				</td>
			</tr>
<?php
		}
?>			
			<tr>
				<td></td>
				<td>
					<input id="a_0_title" class="form-control" type="text" name="a_0_title" placeholder="識別文字" maxlength="255" style="display: none; "/>
				</td>
				<td>
				</td>
				<td>
					<input id="a_0_link" class="form-control" type="text" name="a_0_link" placeholder="超連結" style="display: none; "/>
				</td>
				<td>
					<select name="a_0_state" class="form-control" style="display: none; ">
						<option value="able" selected>顯示</option>
						<option value="disable">隱藏</option>
					</select>
				</td>
				<td>
					<select onchange="Action('a', 0)" name="a_0_act" class="form-control">
						<option value="read" selected></option>
						<option value="edit">新增</option>
					</select>
				</td>		
			</tr>
			<tr>
				<input class="btn btn-info form-control" type="submit" value="儲存所有變更" />
			</tr>
				<tr id="a_0_img" style="display: none; ">
					<td colspan="2"><p align="right"><span class="label label-info">image Size: 980x100</p></td>
					<td colspan="3"><input class="form-control" type="file" name="a_0_img" /></td>
					<td></td>
				</tr>
		</tbody>
	</table>
</div>
</form>
<?php }
else { ?>
	<div class="jumbotron">
		<div class="page-header">
			<h2>請點選上方選單進入對應管理介面</h1>
		</div>
	<div>
<?php }
?>
</div>
<?php
} 
else if(isset($_POST['UID']) && isset($_POST['UPW'])) { // 登入驗證
	$userName = $DBmain->real_escape_string($_POST['UID']); 
	$userPW = $DBmain->real_escape_string($_POST['UPW']); 

	if( $userName!=$_POST['UID'] || $userPW!=$_POST['UPW'] ) {
		alert('請不要輸入奇怪的東西，請重新輸入。');
		setLog($DBmain, "warning", "escape_string: {$_POST['userID']}, {$_POST['password']}". "");
		locate('index.php');
	}

	$result = $DBmain->query("SELECT * FROM `admin` WHERE `UID` = '{$userName}'; ");
	$row = $result->fetch_array(MYSQLI_BOTH);
	if( $row['UID']!=$userName ) {
		alert('使用者名稱不存在，請重新輸入。');
		setLog($DBmain, "warning", "userName do not exist: {$userName}", ""); 
		locate('index.php'); 
	}
	else if( $row['UPW']==md5($userPW) ) {
		setLog($DBmain, "info", "Login Success!!", $userName);
		$_SESSION['UID'] = $userName; 
		$DBmain->query("UPDATE `admin` SET `loginTime` = CURRENT_TIMESTAMP WHERE `UID` = '{$userName}'; ");
		locate('index.php'); 
	}
	else {
		alert('使用者密碼錯誤，請重新輸入。'); 
		setLog($DBmain, "warning", "password error: {$userPW}", $userName); 
		locate('index.php'); 
	}

	$result->free(); 
	require_once(dirname(__FILE__) . '/../lib/stdEnd.php'); 
}
else { // 登入介面	
	setLog($DBmain, 'info', 'enter admin login interface'); 
?>
<div class="container">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3>Administrator Login</h3>
		</div>
		<div class="panel-body">
			<form action="index.php" method="post">
				<div class="form-group">
					<label>UserName: </label>
					<input type="text" class="form-control" name="UID" placeholder="User Name" />
				</div>
				<div class="form-group">
					<label>Password: </label>
					<input type="password" class="form-control" name="UPW" placeholder="Password"/><br />
				</div>
				<button class="btn btn-lg btn-info" type="submit">Login</button>
			</form>
		</div>
	</div>
</div>

<?php } 

require_once(dirname(__FILE__) . "/../lib/stdEnd.php"); 
?>
</body>
</html>
