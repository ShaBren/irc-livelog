<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en" dir="ltr">
<head>
	<link rel="stylesheet" href="base.css" media="all">
<?php 
	if ( $_GET['style'] == 'light' )
		echo '<link rel="stylesheet" href="light.css" media="all">';
	else
		echo '<link rel="stylesheet" href="dark.css" media="all">';
?>
	<link href='http://fonts.googleapis.com/css?family=Antic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Anonymous+Pro' rel='stylesheet' type='text/css'>
	<title>IRC Livelog</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="log.js"></script>
</head>
<body>
<div id="container">
	<div id="header" class="shadow">
		<span id="title">Please select a channel</span>
		<br />
		<span id="time"></span>
	</div>

	<div id="content" class="shadow">
	</div>

	<div id="footer" class="shadow">
		<p id="channels"></p>
		<p>Problems? Bug Sha`Bren on Freenode</p>
	</div>
</div>
</body>
</html>
