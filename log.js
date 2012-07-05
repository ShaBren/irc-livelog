$( document ).ready( function()
				{
				doStart();
				} );

function doStart()
{
		setupStyles();
		getChannels();
		getData();
		updateClock();
		setInterval('updateClock()', 1000 );

}

var lastData = "";
var lastTimestamp = 0;

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
		var urlVars = getUrlVars();
		var channel = urlVars["channel"];
		var style = urlVars["style"];

		if ( !style )
		{
			style = "dark";
		}

		$.get( logUrl, function( data ) 
			{
				$( '#channels' ).html( data );

				var urlVars = getUrlVars();
				var channel = urlVars["channel"];

				if ( channel )
				{
					channel = channel.replace(/%23/g, "#");

					$( '#channelselect' ).val( channel );
				}

				$( '#channelselect' ).change( function()
					{
						var channel = $( "#channelselect" ).val();

						channel = channel.replace(/#/g, "%23");

						window.location = "http://lug.fltt.us/?style=" + style + "&channel=" + channel;
					} 
				);

			} 
		);

}

function setupStyles()
{
		var urlVars = getUrlVars();
		var channel = urlVars["channel"];
		var style = urlVars["style"];

		if ( !channel )
		{
			channel = "";
		}

		if ( !style )
		{
			style = "dark";
		}

		$( '#styleselect' ).val( style );

		$( '#styleselect' ).change( function()
			{
				var style = $( "#styleselect" ).val();

				window.location = "http://lug.fltt.us/?style=" + style + "&channel=" + channel;
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

		var logUrl = "getlog.php?channel=" + channel + "&time=" + time + "&timestamp=" + lastTimestamp;

		$.getJSON( logUrl, function( data ) 
			{
				if ( data.log != "" )
				{
					lastTimestamp = data.timestamp;
					lastData += data.log;
					$( '#content' ).html( "<table>" + lastData + "</table>" );
					$( '#content' ).prop( { scrollTop: $( '#content' ).prop( 'scrollHeight' ) } );
					
					var glow = $('.loud');

					setInterval(
						function()
						{
		    				glow.hasClass('glow') ? glow.removeClass('glow') : glow.addClass('glow');
						}, 1000
					);
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
		var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;

		// Update the time display
		$( '#time' ).html( currentTimeString );
}
