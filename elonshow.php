<?php
$commandText = TEXT;
$ids = array();

if(preg_match('/<@(\w+)\|(\w+)>/', $commandText, $user_matches) ) {
  $user_name = $user_matches[2];
  $user_id = trim($user_matches[1], "@");

  array_push($ids, array(
    "id" => $user_id,
    "name" => $user_id,
  ));
} else if (preg_match('/<!(\w+)>/', $commandText, $user_matches)) {
  $group = $user_matches[1];
  if( $group === "everyone" ) {
    $allUsers = json_decode(execCurl("https://slack.com/api/users.list", array("presence" => true)), true);
    foreach ($allUsers["members"] as $key => $user) {
      if( !$user["deleted"] && !$user["is_bot"] && $user["id"] !== "USLACKBOT" ) {
        array_push($ids, array(
          "id" => $user["id"],
          "name" => $user["name"],
        ));
      }
    }
  } else {
    returnToSlackChannel("Nie podałeś użytkownika!");
    exit();
  }
} else {
  returnToSlackChannel("Nie podałeś użytkownika!");
  exit();
}

$query = "SELECT * FROM `users`";
for($x=0; $x<count($ids); $x++){
  if( $x === 0 ) {
    $query .= " WHERE ";
  } else {
    $query .= " OR ";
  }

  $query .= "`id` = ";
  $query .= "'". $ids[$x]["id"] ."'";
}

$all_reps = execQuery($query);
$outputText = "";

foreach ($all_reps as $key => $value) {
  $reps[$value["id"]] = $value["reputation"];
}

for($x=0; $x<count($ids); $x++){
  $user_id = $ids[$x]["id"];
  $user_name = $ids[$x]["name"];
  $rep = isset($reps[$user_id]) ? $reps[$user_id] : 0;
  $outputText .= "Reputacja użytkownika <@$user_id|$user_name> to *$rep*.\n";
}

returnToSlackChannel($outputText);

// /elon show @channel
?>
