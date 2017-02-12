<?php
$commandText = TEXT;

if(!preg_match('/<@(\w+)\|(\w+)>/', $commandText, $user_matches) ) {
  if( preg_match('/@(\w+)/', $commandText) ) {
    returnToSlackChannel("Nie znam takiego użytkownika!");
  } else {
    returnToSlackChannel("Nie podałeś użytkownika!");
  }
  exit();
}

$user_name = $user_matches[2];
$user_id = trim($user_matches[1], "@");

if( empty(execQuery("SELECT * FROM `users` WHERE `id` = :id", array(":id" => $user_id))) ) {
  execQuery("INSERT INTO `users`(`id`, `name`) VALUES (:id, :name)", array(":id" => $user_id, ":name" => $user_name));
}

execQuery("UPDATE `users` SET `reputation` = `reputation` + :rep_change WHERE `id` = :id", array(":id" => $user_id, ":rep_change" => $rep_change));

$newReputation = execQuery("SELECT `reputation` FROM `users` WHERE `id` = :id", array(":id" => $user_id))[0]["reputation"];

returnToSlackChannel("Nowa reputacja użytkownika <@$user_id|$user_name> to *$newReputation*.", true);

// /elon :sadelon: @bibixx
?>
