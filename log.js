$( document ).ready( function()
				{
				doStart();
				} );

function doStart()
{
		getChannels();
		getData();
		updateClock();
		setInterval('updateClock()', 1000 );
}

var lastData = "";

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

		for(var i = 0; i < hashes.length; i++)
		{
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
		}

		return vars;
}

function getChannels()
{
		var logUrl = "getchans.php";

		$.get( logUrl, function( data ) 
			{
				$( '#channels' ).html( data );

				var urlVars = getUrlVars();
				var channel = urlVars["channel"];

				if ( !channel )
				{
					return;
				}

				channel = channel.replace(/%23/g, "#");
				$( '#channelselect' ).val( channel );
			} 
		);

		$( '#channels' ).change( function()
			{
				var channel = $( "#channelselect" ).val();

				channel = channel.replace(/#/g, "%23");

				window.location = "http://lug.fltt.us/?channel=" + channel
			} 
		);
}

function setupStyles()
{
		$( '#channels' ).change( function()
			{
				var style = $( "#styleselect" ).val();

				window.location = "http://lug.fltt.us/?channel=" + style
			} 
		);
}

function getData()
{
		var urlVars = getUrlVars();
		var time = urlVars['time'];
		var channel = urlVars["channel"];

		if ( !channel )
		{
			return;
		}


		$( '#title' ).html( channel.replace( /%23/g, "#" ) );

		var logUrl = "getlog.php?channel=" + channel + "&time=" + time;

		$.get( logUrl, function( data ) 
			{
				if ( data != lastData )
				{
					$( '#content' ).html( data );
					$( '#content' ).prop( { scrollTop: $( '#content' ).prop( 'scrollHeight' ) } );
					lastData = data;
				}
			} 
		);

		setTimeout( "getData()", 1000 );
}

function updateClock()
{
		var currentTime = new Date();

		var currentHours = currentTime.getHours();
		var currentMinutes = currentTime.getMinutes();
		var currentSeconds = currentTime.getSeconds();

		// Pad the minutes and seconds with leading zeros, if required
		currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
		currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
		currentHours = ( currentHours < 10 ? "0" : "" ) + currentHours;

		// Compose the string for display
		var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

		// Update the time display
		$( '#time' ).html( currentTimeString );
}
