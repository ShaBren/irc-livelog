<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
	Copyright (c) 2012 Stephen Bryant
	For conditions of distribution and use, see copyright notice in LICENSE
-->
<html lang="en" dir="ltr">
<head>
	<link rel="stylesheet" href="base.css" media="all">
<?php 
	switch ( $_GET['style'] )
	{
	case 'light':
		echo '<link rel="stylesheet" href="light.css" media="all">';
		break;
	
	case 'minimalist':
		break;

	default:
	case 'dark':
		echo '<link rel="stylesheet" href="dark.css" media="all">';
		break;
	}
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
		<p>
			<span id="channels"></span>
			<span id="styles">
				<select id="styleselect">
					<option value="dark">Dark</option>
					<option value="light">Light</option>
					<option value="minimalist">Minimalist</option>
				</select>
			</span>
		</p>
		<p>Maintained by <a href="mailto:shabren@shabren.com">Sha`Bren</a> | <a target="_blank" href="https://github.com/ShaBren/irc-livelog">Source</a></p>
	</div>
</div>
</body>
</html>
