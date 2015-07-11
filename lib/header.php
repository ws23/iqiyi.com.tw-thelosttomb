<?php require_once(dirname(__FILE__) . "/../config.php");  
	require_once(dirname(__FILE__) . "/std.php"); ?>

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo "//" . $HOST; ?>"><img class="logo" src="<?php echo $URLPv . "img/" . $logoName; ?>"/></a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
<?php
	$arr = array(
		array("index.php", "現正熱映"), 
		array("next.php", "預告片"), 
		array("other.php", "精彩花絮"), 
		array("photo.php", "劇照"), 
		array("index.php#fb", "網友互動")
	); 

	foreach( $arr as $i){
?>
		
	<li <?php if($URI==$folderRoot.$i[0]) echo 'class="active"';?>><a href="<?php if($URI==$folderRoot.$i[0]) echo '#'; else echo $URLPv . $i[0]; ?>"><?php echo $i[1]; ?></a></li>
<?php
	}
?>
					<li><a style="color: #FF0000; " href="<?php echo $URLPv; ?>howtowatch.html" target="_blank">無法觀看？</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="https://www.facebook.com/pps.iqiyi" target="_blank">
							粉絲團
							<img class="header-like" src="<?php echo $URLPv; ?>img/fblike.png" />
						</a>
					</li>
					<li>
						<div class="fblinkamount"><img class="amount" src="<?php echo $URLPv; ?>img/amount.png" />
						<?php echo getFacebookLikeAmount("http://www.facebook.com/pps.iqiyi"); ?></a>
						</div>
					</li>
					<li><a href="mailto:service@iqiyi.com.tw" target="_blank">聯絡我們</a></li>
				</ul>
			</div>
		</div>
	</nav>
