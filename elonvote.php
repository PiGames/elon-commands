<?php
$commandText = explode(" ", TEXT);

if(!preg_match('/<@(\w+)\|(\w+)>/', $commandText[0], $user_matches) ) {
  if( preg_match('/@(\w+)/', $commandText[0]) ) {
    returnToSlackChannel("Nie znam takiego użytkownika!");
  } else {
    returnToSlackChannel("Nie podałeś użytkownika!");
  }
  exit();
}

$user_name = $user_matches[2];
$user_id = trim($user_matches[1], "@");
$poll = execQuery("SELECT * FROM `polls` WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id));

if( !empty($poll) ) {
  if ($commandText[1] !== ":+1:" && $commandText[1] !== ":thumbsup:" && $commandText[1] !== ":-1:" && $commandText[1] !== ":thumbsdown:") {
    returnToSlackChannel("Jak chcesz zagłosować? `/elon vote [@name] [:+1:|:thumbsup:|:-1:|:thumbsdown:]`");
    exit();
  }

  $users_voted = json_decode($poll[0]["voted"]);

  if( in_array($_POST["user_id"], $users_voted) ) {
    returnToSlackChannel("Nie możesz zagłosować drugi raz!");
    exit();
  }

  array_push($users_voted, $_POST["user_id"]);

  if($commandText[1] === ":+1:" || $commandText[1] === ":thumbsup:") {
    execQuery("UPDATE `polls` SET `voted` = :voted, `infavour` = `infavour` + 1 WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id, ":voted" => json_encode($users_voted)));
  } else if($commandText[1] === ":-1:" || $commandText[1] === ":thumbsdown:") {
    execQuery("UPDATE `polls` SET `voted` = :voted, `against` = `against` + 1 WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id, ":voted" => json_encode($users_voted)));
  }

  $poll = execQuery("SELECT * FROM `polls` WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id))[0];

  if( $poll["infavour"] + $poll["against"] < 5 ) {
    returnToSlackChannel("<@$user_id|$user_name>\nZa: ".$poll["infavour"]."\nPrzeciw: ".$poll["against"], true);
  } else {
    execQuery("UPDATE `polls` SET `active` = 0 WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id));
    $rep_change = 0;

    switch ($poll["votingfor"]) {
      case ':happyelon:':
        $rep_change = 2;
        break;
      case ':neutralelon:':
        $rep_change = 1;
        break;
      case ':sadelon:':
        $rep_change = -2;
        break;
    }

    if ($poll["infavour"] > $poll["against"]) {
      if( empty(execQuery("SELECT * FROM `users` WHERE `id` = :id", array(":id" => $user_id))) ) {
        execQuery("INSERT INTO `users`(`id`, `name`) VALUES (:id, :name)", array(":id" => $user_id, ":name" => $user_name));
      }

      execQuery("UPDATE `users` SET `reputation` = `reputation` + :rep_change WHERE `id` = :id", array(":id" => $user_id, ":rep_change" => $rep_change));
      $newReputation = execQuery("SELECT `reputation` FROM `users` WHERE `id` = :id", array(":id" => $user_id))[0]["reputation"];

      returnToSlackChannel("<@$user_id|$user_name> dostaje ".$poll["votingfor"].".\nZa: ".$poll["infavour"]."\nPrzeciw: ".$poll["against"]."\n"."Nowa reputacja użytkownika to *$newReputation*.", true);
    } else {
      returnToSlackChannel("<@$user_id|$user_name> *nie* dostaje ".$poll["votingfor"].".\nZa: ".$poll["infavour"]."\nPrzeciw: ".$poll["against"], true);
    }
  }
} else {
  returnToSlackChannel("Taka ankieta nie istnieje! Jeśli stworzyć ankietę użyj `/elon startpoll [@name] [:happyelon:|:neutralelon:|:sadelon:]`");
}



// /elon vote @bibixx :+1:
?>
