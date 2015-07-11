<!DOCTYPE html>
<?php require_once(dirname(__FILE__) . '/config.php'); ?>
<html>
<head>
	<meta charset="utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="<?php echo $URLPv . "img/" . $iconName; ?>" type="image/x-icon">
	<link rel="icon" href="<?php echo $URLPv . "img/" . $iconName; ?>" type="image/x-icon">
	<meta name="viewpoint" content="width=device-width, initial-scale=1">
	<meta name="title" content="<?php echo $titleName; ?>">
	<meta name="description" content="">
	<meta name="author" content="臺灣愛奇藝股份有限公司">

	<meta property="fb:app_id" content="<?php echo $FBCommentID; ?>">
<!--	<meta property="og:site_name" content="<?php echo $titleName; ?>">
	<meta property="og:url" content="http<?php echo $URLPv; ?>">
	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php echo $titleName; ?>">
-->	

	<title><?php echo $titleName; ?></title>

	<link href="<?php echo $URLPv; ?>lib/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="index.css" rel="stylesheet">
	<script src="<?php echo $URLPv; ?>lib/jquery/jquery-1.11.2.js"></script>
	<script src="<?php echo $URLPv; ?>lib/bootstrap/js/bootstrap.js"></script>
	<?php include_once("analyticstracking.php"); ?>
	<?php require_once(dirname(__FILE__) . '/lib/std.php'); ?>
	<script>
		<?php 
			$result = $DBmain->query("SELECT * FROM `ad` WHERE `state` = 0; "); 
			$max = 0; 
			while($result->fetch_array(MYSQLI_BOTH))
				$max++; 
		?>
		var list_num = 0; 
		var t;
		var rot = document.getElementsByClassName("ad-content"); 
		function rotate(){
			var list_num_max = <?php echo $max; ?>;
			if(window.list_num >= list_num_max) 
				window.list_num = 0; 
			for(var i=0;i<list_num_max;i++)
				window.rot[i].setAttribute("class", "ad-content ad-hidden"); 
			window.rot[list_num].setAttribute("class", "ad-content ad-show"); 
			window.list_num++; 
			window.t = setTimeout("rotate()", 5000); 
		}
	</script>
	 
</head>
<body class="outliner" onload="rotate(); ">
<!-- preprocess start -->
<?php setLog($DBmain, 'info', 'into index', ''); ?>
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) 
				return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.2&appId=<?php echo $FBCommentID; ?>";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

<?php
	$result = $DBmain->query("SELECT * FROM `main` WHERE `engName` = '{$actName}'; "); 
	$row = $result->fetch_array(MYSQLI_BOTH);
	$AID = $row['id'];  
?>
<!-- preprocess end -->

<!-- header start -->
	<?php require_once(dirname(__FILE__) . "/lib/header.php"); ?>
	<div class="cut-background"><img class="background" src="img/background.jpg"></div>
	<script>
		var maxWidth = document.documentElement.clientWidth-15;  
		var src = document.getElementsByClassName("cut-background")[0]; 
		src.style.setProperty("width", maxWidth + "px"); 
	</script>
	<img class="act-logo">
<!-- header end -->

<!-- body start -->
<div class="container">
	<!-- focus -->
	<script>
		function changeVideo(str) {
			src = document.getElementById('focus'); 
			src.innerHTML = ""; 
			src.innerHTML = '<embed id="embed" class="embed" src="' + str + '-autoplay=1" quality="high" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>'; 
		}

	</script>
	<div id ="focus" class="focus"><?php
		$result = $DBmain->query("SELECT `videoURL` FROM `video` WHERE `state` = 0 AND `mainID` = {$AID} ORDER BY `id` DESC LIMIT 1; "); 
		if($result != NULL){ 
		$row = $result->fetch_array(MYSQLI_BOTH); 
	?>
		<embed id="embed" class="embed" src="<?php echo $row['videoURL'] . "-autoplay=1"; ?>" quality="high" align="middle" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash"></embed>
	<?php } ?>
	</div>
	<!-- 劇集列表 -->
	<a name="list"></a>
	<div class="panel panel-theme">
		<div class="panel-heading">
			<h3 class="panel-title">劇集列表</h3>
		</div>
		<div class="panel-body">
		<?php 
			$result = $DBmain->query("SELECT * FROM `video` WHERE `state` = 0 AND `mainID` = {$AID} ORDER BY `id` DESC; "); 
			while($row = $result->fetch_array(MYSQLI_BOTH)){ 
		?>
			<div class="video">
				<p onclick="changeVideo('<?php echo $row['videoURL']; ?>')"><img src="<?php echo $URLPv . $row['imageURL']; ?>"/></p>
				<a href="<?php echo $row['linkURL']; ?>" target="_blank"><strong><?php echo $row['title']; ?></strong><br />
				<?php echo $row['text']; ?></a>
			</div>
		<?php } ?>
		</div>
	</div>
	<!-- 廣告版位 -->
	<div class="ad">
	<?php 
		$result = $DBmain->query("SELECT * FROM `ad` WHERE `state` = 0 ORDER BY `id` DESC; "); 
		while($row = $result->fetch_array(MYSQLI_BOTH)) {
	?>
			<a href="<?php echo $row['linkURL']; ?>" target="_blank"><img class="ad-content ad-hidden" src="<?php echo $AdPv . $row['imageURL']; ?>"/></a>
	<?php } ?>
	</div>

<!-- 網友互動 -->
	<a name="fb"></a>
	<div class="panel panel-theme">
		<div class="panel-heading">
			<h3 class="panel-title">網友互動</h3>
		</div>
		<div class="panel-body">
			<div class="fb-comments" data-href="http:<?php echo $URLPv; ?>" data-width="100%" data-numposts="10" data-colorscheme="dark" data-order-by="reverse_time" fb-xfbml-state="tendered">
			</div>	
		</div>
	</div>
</div>

<!-- body end -->
<?php require_once(dirname(__FILE__) . "/lib/stdEnd.php"); ?> 
</body>
</html>
