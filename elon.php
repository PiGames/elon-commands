<?php
include "elonutils.php";
validateCommandToken();

$command = explode(" ", $_POST["text"])[0];

if( $command === ":happyelon:" || $command === ":neutralelon:" || $command === ":sadelon:" ) {
  include "elonstartpoll.php";
}

if( $command === "vote" ) {
  include "elonvote.php";
}

if( $command === "help" ) {
  include "elonhelp.php";
}

if( $command === "show" ) {
  include "elonshow.php";
}

?>
