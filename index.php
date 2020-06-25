<?php
require_once "config.php";
require_once __DIR__.'/vendor/autoload.php';
use RestCord\DiscordClient;
$items=array_merge(...array_map(function(string $playlist):array{
	$litems=[];
	$pagetoken="";
	do
	{
		$conts=json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=$playlist&key=".YOUTUBE_TOKEN.$pagetoken));
		$litems[]=$conts->items;
		if($conts->nextPageToken) {
			$pagetoken="&pageToken=".$conts->nextPageToken;
		} else {
			$pagetoken=null;
		}
	}
	while($pagetoken);
	return array_merge(...$litems);
},YOUTUBE_PLAYLISTS));
$count=count($items);
$index=random_int(0,$count);
$videoid=$items[$index]->contentDetails->videoId;
if(NO_DISCORD_PING)
{
	echo "Log in to discord with token ".DISCORD_TOKEN.", message ".DISCORD_CHANNEL." with 'VGM of the Day - ".date("m/d/y")." - https://www.youtube.com/watch?v=$videoid'".PHP_EOL;
}
else
{
	$discord = new DiscordClient(['token' => DISCORD_TOKEN]); // Token is required
	$discord->channel->createMessage(['channel.id' => DISCORD_CHANNEL, 'content' => 'VGM of the Day - '.date("m/d/y").' https://www.youtube.com/watch?v='.$videoid]);
}
