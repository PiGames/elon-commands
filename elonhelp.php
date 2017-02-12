<?php
$data = array(
  "response_type" => "ephemeral",
  "channel" => $_POST['channel_id'],
  "text" => "Jak używać `/elon`?\n`/elon :sadelon: [@name]` – odejmuje 2 punkty reputacji.\n`/elon :neutralelon: [@name]` – dodaje 1 punkt reputacji.\n`/elon :happyelon: [@name]` – dodaje 2 punkty reputacji.\n`/elon show [@name|@everyone]` – pokazuje reputację.",
  "mrkdwn" => true,
);

$json_string = json_encode($data);

header('Content-Type: application/json');
echo $json_string;
?>
