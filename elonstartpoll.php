<?php
$commandText = explode(" ", $_POST["text"]);

if(!preg_match('/<@(\w+)\|(\w+)>/', $commandText[1], $user_matches) ) {
  if( preg_match('/@(\w+)/', $commandText[1]) ) {
    returnToSlackChannel("Nie znam takiego użytkownika!");
  } else {
    returnToSlackChannel("Nie podałeś użytkownika!");
  }
  exit();
}

if ($commandText[0] !== ":happyelon:" && $commandText[0] !== ":neutralelon:" && $commandText[0] !== ":sadelon:") {
  returnToSlackChannel("Na jakiego Elona mamy głosować?");
  exit();
}

$user_name = $user_matches[2];
$user_id = trim($user_matches[1], "@");

if( empty(execQuery("SELECT * FROM `polls` WHERE `user` = :id AND `active` = '1'", array(":id" => $user_id))) ) {
  execQuery("INSERT INTO `polls`(`user`, `voted`, `votingfor`) VALUES (:id, '[]', :votingfor)", array(":id" => $user_id, ":votingfor" => $commandText[0]));
  returnToSlackChannel("Utworzono głosowanie czy <@$user_id|$user_name> ma dostać ".$commandText[0]. ". Aby zagłosować wpisz `/elon vote [@name] [:+1:|:thumbsup:|:-1:|:thumbsdown:]`", true);
} else {
  returnToSlackChannel("Taka ankieta już istnieje! Jeśli chcesz zagłosować użyj `/elon vote [@name] [:+1:|:thumbsup:|:-1:|:thumbsdown:]`");
}


// /elon startpoll @bibixx :happyelon:
?>
