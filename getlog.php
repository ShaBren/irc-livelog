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

$nickColors = array();
$nickIndex = 1;

echo "<table>";

while ( $row = $results->fetchArray() ) 
{
	$nick = $row[1];

	if ( array_key_exists( $nick, $nickColors ) )
	{
		$nickColor = $nickColors[$nick];
	}
	else
	{
		$nickColor = "nick".$nickIndex;

		$nickColors[$nick] = $nickColor;
		
		$nickIndex++;
		
		if ( $nickIndex > 8 )
		{
			$nickIndex = 1;
		}
	}

	$msg = htmlspecialchars( $row[2] );
	$time = strftime( "%T", $row[3] );
	$type = $row[4];

	if ( $type == 'PRIVMSG' )
	{
		echo "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message'>$msg</td></tr>";
	}
	else if ( $type == 'ACTION' )
	{
		echo "<tr><td class='time'>$time</td><td class='nick $nickColor action'>*$nick</td> <td class='message'>$msg</td></tr>";
	}
}
echo "</table>";
?>
