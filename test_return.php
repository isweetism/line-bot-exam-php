<?php

require "vendor/autoload.php";
$access_token = 'IdHnPm+0WSG3rYUP1jMSDRH38Cvvy/6n/fdNxUXWBiH3reUxf4P8ZWN1L2X4K728NdErN9zod2WXAl/mxImllQq+bewql8uSvdLso14g5Q3bFOfzVW/GWcEofZAMDUDEZdqQVTLrCJfek9/V81KYWwdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			//echo $result . "\r\n";
		}
	}
}
//echo "OK";

$channelSecret = '8f21efbe98b46edd18942d78c42bf8f1';
$idPush = $result;
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($idPush, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

?>
