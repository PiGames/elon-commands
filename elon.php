<?php
include "elonutils.php";
validateCommandToken();

$command = explode(" ", $_POST["text"])[0];

if( $command === ":sadelon:" ) {
  $rep_change = -2;
  include "elonchange.php";
}

if( $command === "help" ) {
  include "elonhelp.php";
}

if( $command === ":neutralelon:" ) {
  $rep_change = 1;
  include "elonchange.php";
}

if( $command === ":happyelon:" ) {
  $rep_change = 2;
  include "elonchange.php";
}

if( $command === "show" ) {
  include "elonshow.php";
}

?>
