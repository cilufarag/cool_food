<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  $user = 'cgg8944';
  $password = 'degreethrow';
  $db = 'cgg8944';
  $host = 'localhost';
  $port = 8889;

  function retrieveConnectionToDB() {
    global $user, $host, $password, $db, $port;

    $mysqli = new mysqli($host, $user, $password, $db, $port);

    if(!$mysqli) {
      exit("ERROR {$mysqli->connect_error}");
    }

    return $mysqli;
  }

?>