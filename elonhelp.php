<?php
$data = array(
  "response_type" => "ephemeral",
  "channel" => $_POST['channel_id'],
  "text" => "*Jak używać `/elon`?*\n`/elon [:happyelon:|:neutralelon:|:sadelon:] [@name]` – tworzy nową ankietę.\n`/elon vote [@name] [:+1:|:thumbsup:|:-1:|:thumbsdown:]` – głosowanie. `:+1:|:thumbsup:` = za, `:-1:|:thumbsdown:` – przeciw.\n\n`/elon show [@name|@everyone]` – pokazuje reputację.",
  "mrkdwn" => true,
);

$json_string = json_encode($data);

header('Content-Type: application/json');
echo $json_string;
?>
