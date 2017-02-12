<?php
  include 'passwords.php';

  $text = explode(" ", $_POST["text"]);
  array_shift($text);
  $text = implode(" ", $text);
  define("TEXT", $text);

  function validateCommandToken() {
    if( COMMAND_TOKEN !== $_POST["token"] ) {
      returnToSlackChannel("Błąd autoryzacji!");
      exit();
    }
  }

  function execQuery($query, $queryArgs = null) {
    try {
      $db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8mb4', LOGIN, PASSWORD);
      $queryPDO = $db->prepare($query);
      $queryPDO->execute($queryArgs);
      $queryOut = $queryPDO->fetchAll(PDO::FETCH_ASSOC);

      return $queryOut;
    } catch (PDOException $e) {
      $error = array();
      $error["error"] = utf8_encode($e->getMessage());
      $error["error_code"] = "0";
      echo json_encode($error);
      die();
    }
  }

  function returnToSlackChannel($text, $inChannel = false) {
    $data = array(
      "response_type" => $inChannel ? "in_channel" : "ephemeral",
      "channel" => $_POST['channel_id'],
      "text" => $text,
      "mrkdwn" => true,
    );
    $json_string = json_encode($data);

    header('Content-Type: application/json');
    echo $json_string;
  }

  function execCurl($url, $data = array()) {
    $data["token"] = TOKEN;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);

    return $server_output;
  }
?>
