<?php
$config = json_decode(file_get_contents(__DIR__ . "/config.json"), true);
$messages = file(__DIR__ . "/messages.txt");
// Trim each line
$messages = array_map("trim", $messages);
// Remove empty lines and lines starting with a comment
$messages = array_values(array_filter($messages, fn(string $s):bool => strlen($s) !== 0 && $s[0] !== ";"));
$index=random_int(0,count($messages)-1);
$message=$messages[$index];
$curl = curl_init($config["webhook"]);
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:application/json"]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
	"text" => $message
]));
curl_exec($curl);