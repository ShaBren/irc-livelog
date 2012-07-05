<?php

$db = new SQLite3( 'log.sqlite' );

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

$time = time() - $timeOffset;

$results = $db->query( "SELECT * FROM log WHERE channel='$channel' AND time > $time" );

$i = 0;

while ( $row = $results->fetchArray() ) 
{
	$i++;
}

$results->reset();

echo "<table>\n";

if ( $i % 2 )
{
	echo "<tr></tr>\n";
}

$pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

while ( $row = $results->fetchArray() ) 
{
	$nick = $row[1];

	$nickColor = "nick" . ( ( ( crc32( $nick ) ) % 8 ) + 1 );

	$msg = htmlspecialchars( $row[2] );

	$msg = preg_replace_callback("#$pattern#i", function($matches) 
		{
	    	$input = $matches[0];
		    $url = preg_match('!^https?://!i', $input) ? $input : "http://$input";
			return '<a href="' . $url . '" rel="nofollow" target="_blank">' . "$input</a>";
		}, $msg );

	$time = strftime( "%T", $row[3] );
	$type = $row[4];

	if ( $type == 'PRIVMSG' )
	{
		if ( $nick == "LOUDBOT" )
		{
			echo "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message loud'>$msg</td></tr>\n";
		}
		else
		{
			echo "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message'>$msg</td></tr>\n";
		}
	}
	else if ( $type == 'ACTION' )
	{
		echo "<tr><td class='time'>$time</td><td class='nick $nickColor action'>*$nick</td> <td class='message'>$msg</td></tr>\n";
	}
}
echo "</table>";
?>
