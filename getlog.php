<?php
/*
	Copyright (c) 2012 Stephen Bryant
	For conditions of distribution and use, see copyright notice in LICENSE
*/

$db = new SQLite3( 'log.sqlite' );

date_default_timezone_set('America/New_York');

if ( array_key_exists( 'time', $_GET ) and $_GET['time'] > 0 )
{
	$timeOffset = $_GET['time'];
}
else
{
	$timeOffset = 3600; // Default to an hour
}

if ( array_key_exists( 'channel', $_GET ) )
{
	$channel = $db->escapeString( $_GET['channel'] );
}
else
{
	$channel = "#ncsulug";
}

if ( array_key_exists( 'timestamp', $_GET ) )
{
	$timestamp = $db->escapeString( $_GET['timestamp'] );
}
else
{
	$timestamp = "0";
}

$time = time() - $timeOffset;

$results = $db->query( "SELECT _ROWID_, * FROM log WHERE channel='$channel' AND time > $time AND _ROWID_ > $timestamp" );

$i = 0;

while ( $row = $results->fetchArray() ) 
{
	$i++;
}

$results->reset();

$log = "";

$pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

while ( $row = $results->fetchArray() ) 
{
	$nick = $row[2];

	$nickColor = "nick" . ( ( ( crc32( $nick ) ) % 8 ) + 1 );

	$msg = htmlspecialchars( $row[3] );

	$msg = preg_replace_callback("#$pattern#i", function($matches) 
		{
	    	$input = $matches[0];
		    $url = preg_match('!^https?://!i', $input) ? $input : "http://$input";
			return '<a href="' . $url . '" rel="nofollow" target="_blank">' . "$input</a>";
		}, $msg );

	$time = strftime( "%T", $row[4] );
	$type = $row[5];
	$timestamp = $row[0];

	if ( $type == 'PRIVMSG' )
	{
		if ( $nick == "LOUDBOT" )
		{
			$log .= "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message loud'>$msg</td></tr>\n";
		}
		else
		{
			$log .= "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message'>$msg</td></tr>\n";
		}
	}
	else if ( $type == 'ACTION' )
	{
		$log .= "<tr><td class='time'>$time</td><td class='nick $nickColor action'>*$nick</td> <td class='message'>$msg</td></tr>\n";
	}
}

$log = json_encode( $log );

$timestamp = json_encode( $timestamp );

echo "{\"log\":$log, \"timestamp\":$timestamp}"

?>
